<?php
namespace App\Services;
use App\Models\Insumo;
use App\Models\TipoInsumo;
use App\Models\UnidadMedida;
use App\Models\Proveedor;
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
                $fileName = pathinfo($filePath, PATHINFO_FILENAME);
                $this->createTipoInsumosFromSheets([$fileName]);
                $worksheet = $spreadsheet->getSheet(0);
                $this->processSheet($worksheet, $fileName);
            } else {
                $sheetNames = $spreadsheet->getSheetNames();
                $this->createTipoInsumosFromSheets($sheetNames);
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
        if (method_exists($spreadsheetOrWorksheet, 'getSheetByName')) {
            $worksheet = $spreadsheetOrWorksheet->getSheetByName($sheetName);
            if (!$worksheet) {
                $this->errors[] = "No se pudo acceder a la hoja: {$sheetName}";
                return;
            }
        } else {
            $worksheet = $spreadsheetOrWorksheet;
        }
        $tipoInsumoId = $this->tipoInsumoMap[$sheetName] ?? null;
        if (!$tipoInsumoId) {
            $this->errors[] = "No se encontró tipo de insumo para la hoja: {$sheetName}";
            return;
        }
        $highestRow = $worksheet->getHighestRow();
        for ($row = 4; $row <= $highestRow; $row++) {
            $this->processRow($worksheet, $row, $tipoInsumoId);
        }
    }
    private function processRow($worksheet, $row, $tipoInsumoId)
    {
        try {
            $codigoInsumo = $worksheet->getCell('B' . $row)->getValue();
            $nombreInsumo = $worksheet->getCell('C' . $row)->getValue();
            $unidadMedida = $worksheet->getCell('D' . $row)->getValue();
            $stockActual = $worksheet->getCell('E' . $row)->getValue();
            $rutProveedor = $worksheet->getCell('F' . $row)->getValue();
            $nombreProveedor = $worksheet->getCell('G' . $row)->getValue();
            
            if (empty($nombreInsumo)) {
                $this->errors[] = "Fila {$row}: Nombre del insumo es requerido";
                return;
            }
            if (empty($codigoInsumo)) {
                $codigoInsumo = $this->generateInsumoCode();
            }
            
            // Validar y procesar stock actual
            if (empty($stockActual) || !is_numeric($stockActual)) {
                $stockActual = 0;
            } else {
                $stockActual = (int) $stockActual;
            }
            
            // Procesar proveedor si se proporciona RUT o nombre
            $proveedorId = null;
            if (!empty($rutProveedor) || !empty($nombreProveedor)) {
                $proveedor = $this->findOrCreateProveedor($rutProveedor, $nombreProveedor, $row);
                if ($proveedor) {
                    $proveedorId = $proveedor->id;
                }
            }
            
            $unidadMedidaId = $this->findOrCreateUnidadMedida($unidadMedida);
            $insumoExistente = Insumo::where('id_insumo', $codigoInsumo)->first();
            if ($insumoExistente) {
                $this->errors[] = "Fila {$row}: El insumo con código {$codigoInsumo} ya existe";
                return;
            }
            DB::beginTransaction();
            Insumo::create([
                'id_insumo' => $codigoInsumo,
                'nombre_insumo' => $nombreInsumo,
                'id_unidad' => $unidadMedidaId,
                'tipo_insumo_id' => $tipoInsumoId,
                'proveedor_id' => $proveedorId,
                'stock_actual' => $stockActual,
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
    
    private function findOrCreateProveedor($rut, $nombreProveedor, $row)
    {
        try {
            // Si no hay RUT ni nombre, no hacer nada
            if (empty($rut) && empty($nombreProveedor)) {
                return null;
            }
            
            // Si hay RUT, formatearlo y buscar por RUT
            if (!empty($rut)) {
                $rutFormateado = \App\Helpers\RunFormatter::format($rut);
                $proveedor = Proveedor::where('rut', $rutFormateado)->first();
                
                if ($proveedor) {
                    // Si existe y tiene nombre diferente, actualizar el nombre si está vacío en BD
                    if (!empty($nombreProveedor) && empty($proveedor->nombre_proveedor)) {
                        $proveedor->update(['nombre_proveedor' => $nombreProveedor]);
                    }
                    return $proveedor;
                }
            }
            
            // Si no se encontró por RUT, buscar por nombre
            if (!empty($nombreProveedor)) {
                $proveedor = Proveedor::where('nombre_proveedor', $nombreProveedor)->first();
                if ($proveedor) {
                    // Si existe y tiene RUT diferente, actualizar el RUT si está vacío en BD
                    if (!empty($rut)) {
                        $rutFormateado = \App\Helpers\RunFormatter::format($rut);
                        if (empty($proveedor->rut)) {
                            $proveedor->update(['rut' => $rutFormateado]);
                        }
                    }
                    return $proveedor;
                }
            }
            
            // Si no existe, crear nuevo proveedor
            // Validar que al menos tenga RUT o nombre
            if (empty($rut) && empty($nombreProveedor)) {
                return null;
            }
            
            $data = [];
            if (!empty($rut)) {
                $data['rut'] = \App\Helpers\RunFormatter::format($rut);
            }
            if (!empty($nombreProveedor)) {
                $data['nombre_proveedor'] = $nombreProveedor;
            }
            
            // Si no hay RUT, generar uno temporal o usar el nombre como identificador
            if (empty($data['rut'])) {
                // Usar el nombre como base para un RUT temporal (solo para identificación)
                $data['rut'] = 'TEMP-' . strtoupper(Str::random(8));
            }
            
            $proveedor = Proveedor::create($data);
            return $proveedor;
            
        } catch (\Exception $e) {
            // No fallar la importación si hay error con el proveedor
            $this->errors[] = "Fila {$row}: Error al procesar proveedor - " . $e->getMessage();
            return null;
        }
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
