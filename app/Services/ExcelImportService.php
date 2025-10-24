<?php

namespace App\Services;

use App\Models\Insumo;
use App\Models\TipoInsumo;
use App\Models\UnidadMedida;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class ExcelImportService
{
    private $errors = [];
    private $successCount = 0;
    private $tipoInsumoMap = [];

    public function importFromFile($filePath, $fileExtension)
    {
        try {
            $spreadsheet = $this->loadFile($filePath, $fileExtension);
            
            if (strtolower($fileExtension) === 'csv') {
                // Para CSV, usar el nombre del archivo como tipo de insumo
                $fileName = pathinfo($filePath, PATHINFO_FILENAME);
                $this->createTipoInsumosFromSheets([$fileName]);
                // Para CSV, usar la primera hoja (índice 0)
                $worksheet = $spreadsheet->getSheet(0);
                $this->processSheet($worksheet, $fileName);
            } else {
                // Para Excel, usar los nombres de las hojas
                $sheetNames = $spreadsheet->getSheetNames();
                
                // Paso 1: Crear tipos de insumo desde los nombres de las hojas
                $this->createTipoInsumosFromSheets($sheetNames);
                
                // Paso 2: Procesar cada hoja para crear insumos
                foreach ($sheetNames as $sheetName) {
                    $this->processSheet($spreadsheet, $sheetName);
                }
            }
            
            return [
                'success' => true,
                'message' => "Carga masiva completada. {$this->successCount} insumos procesados.",
                'errors' => $this->errors
            ];
            
        } catch (\Exception $e) {
            Log::error('Error en carga masiva: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error al procesar el archivo: ' . $e->getMessage(),
                'errors' => $this->errors
            ];
        }
    }

    private function loadFile($filePath, $fileExtension)
    {
        $reader = match (strtolower($fileExtension)) {
            'xlsx' => new Xlsx(),
            'xls' => new Xls(),
            'csv' => new Csv(),
            default => throw new \Exception('Formato de archivo no soportado')
        };

        return $reader->load($filePath);
    }

    private function createTipoInsumosFromSheets($sheetNames)
    {
        foreach ($sheetNames as $sheetName) {
            // Verificar si el tipo de insumo ya existe
            $tipoInsumo = TipoInsumo::where('nombre_tipo', $sheetName)->first();
            
            if (!$tipoInsumo) {
                $tipoInsumo = TipoInsumo::create([
                    'nombre_tipo' => $sheetName
                ]);
            }
            
            $this->tipoInsumoMap[$sheetName] = $tipoInsumo->id;
        }
    }

    private function processSheet($spreadsheetOrWorksheet, $sheetName)
    {
        // Si es un Spreadsheet, obtener la hoja por nombre
        if (method_exists($spreadsheetOrWorksheet, 'getSheetByName')) {
            $worksheet = $spreadsheetOrWorksheet->getSheetByName($sheetName);
            if (!$worksheet) {
                $this->errors[] = "No se pudo acceder a la hoja: {$sheetName}";
                return;
            }
        } else {
            // Si ya es un Worksheet, usarlo directamente
            $worksheet = $spreadsheetOrWorksheet;
        }

        $tipoInsumoId = $this->tipoInsumoMap[$sheetName] ?? null;
        if (!$tipoInsumoId) {
            $this->errors[] = "No se encontró tipo de insumo para la hoja: {$sheetName}";
            return;
        }

        // Leer desde la fila 4 (índice 3)
        $highestRow = $worksheet->getHighestRow();
        
        for ($row = 4; $row <= $highestRow; $row++) {
            $this->processRow($worksheet, $row, $tipoInsumoId);
        }
    }

    private function processRow($worksheet, $row, $tipoInsumoId)
    {
        try {
            // Columna B: Código del insumo
            $codigoInsumo = $worksheet->getCell('B' . $row)->getValue();
            
            // Columna C: Nombre del insumo
            $nombreInsumo = $worksheet->getCell('C' . $row)->getValue();
            
            // Columna D: Unidad de medida
            $unidadMedida = $worksheet->getCell('D' . $row)->getValue();

            // Validar que al menos el nombre del insumo esté presente
            if (empty($nombreInsumo)) {
                $this->errors[] = "Fila {$row}: Nombre del insumo es requerido";
                return;
            }

            // Generar código si no existe
            if (empty($codigoInsumo)) {
                $codigoInsumo = $this->generateInsumoCode();
            }

            // Buscar o crear unidad de medida
            $unidadMedidaId = $this->findOrCreateUnidadMedida($unidadMedida);

            // Verificar si el insumo ya existe
            $insumoExistente = Insumo::where('id_insumo', $codigoInsumo)->first();
            if ($insumoExistente) {
                $this->errors[] = "Fila {$row}: El insumo con código {$codigoInsumo} ya existe";
                return;
            }

            // Crear el insumo
            DB::beginTransaction();
            
            Insumo::create([
                'id_insumo' => $codigoInsumo,
                'nombre_insumo' => $nombreInsumo,
                'id_unidad' => $unidadMedidaId,
                'tipo_insumo_id' => $tipoInsumoId,
                'stock_minimo' => 0,
                'stock_actual' => 0,
                'codigo_barra' => null
            ]);

            DB::commit();
            $this->successCount++;

        } catch (\Exception $e) {
            DB::rollBack();
            $this->errors[] = "Fila {$row}: Error al procesar - " . $e->getMessage();
        }
    }

    private function generateInsumoCode()
    {
        do {
            $codigo = 'INS' . strtoupper(Str::random(6));
        } while (Insumo::where('id_insumo', $codigo)->exists());
        
        return $codigo;
    }

    private function findOrCreateUnidadMedida($unidadMedida)
    {
        if (empty($unidadMedida)) {
            // Unidad por defecto si no se especifica
            $unidadMedida = 'UNIDAD';
        }

        $unidad = UnidadMedida::where('nombre_unidad_medida', $unidadMedida)->first();
        
        if (!$unidad) {
            $unidad = UnidadMedida::create([
                'id_unidad' => 'UM' . strtoupper(Str::random(4)),
                'nombre_unidad_medida' => $unidadMedida
            ]);
        }

        return $unidad->id_unidad;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getSuccessCount()
    {
        return $this->successCount;
    }
}

