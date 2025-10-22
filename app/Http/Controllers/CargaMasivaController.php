<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use App\Models\Producto;
use App\Models\Unidad;
use App\Services\BarcodeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CargaMasivaController extends Controller
{
    public function index(): View
    {
        return view('layouts.inventario.carga_masiva');
    }

    public function upload(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xlsx,xls|max:10240'
        ]);

        $file = $request->file('file');
        $path = $file->store('temp');
        $extension = $file->getClientOriginalExtension();

        $created = 0;
        $updated = 0;
        $errors = [];

        DB::beginTransaction();
        try {
            $this->clearAllBarcodes();
            
            if (in_array($extension, ['csv', 'txt'])) {
                $this->processCsvFile($path, $created, $updated, $errors);
            } else {
                $this->processExcelFile($path, $created, $updated, $errors);
            }

            DB::commit();
            
            Log::info('CargaMasiva - Resumen', [
                'created' => $created,
                'updated' => $updated,
                'errors' => count($errors)
            ]);

            $message = "Carga masiva completada. Creados: {$created}, Actualizados: {$updated}";
            if (!empty($errors)) {
                $message .= ". Errores: " . implode(', ', $errors);
            }

            return back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('CargaMasiva - Error general', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => 'Error procesando archivo: ' . $e->getMessage()]);
        } finally {
            Storage::delete($path);
        }
    }

    private function processCsvFile(string $path, int &$created, int &$updated, array &$errors): void
    {
        $handle = fopen(storage_path("app/{$path}"), 'r');
        if ($handle === false) {
            throw new \Exception('No se pudo leer el archivo CSV');
        }

        $rowIndex = 0;
        while (($data = fgetcsv($handle, 0, ';')) !== false) {
            $rowIndex++;
            if (count($data) === 1) continue;
            
            $this->processRow($rowIndex, $data[0] ?? '', $data[1] ?? '', $data[2] ?? '', $data[3] ?? '', $data[4] ?? '0', $created, $updated, $errors);
        }
        fclose($handle);
    }

    private function processExcelFile(string $path, int &$created, int &$updated, array &$errors): void
    {
        try {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load(storage_path("app/{$path}"));
        } catch (\Throwable $e) {
            throw new \Exception('No se pudo leer el archivo Excel');
        }

        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray(null, true, true, true);
        
        foreach ($rows as $idx => $row) {
            $rowIndex = (int) $idx;
            if ($rowIndex <= 4) continue;
            
            $this->processRow($rowIndex, $row['A'] ?? '', $row['B'] ?? '', $row['C'] ?? '', $row['D'] ?? '', $row['E'] ?? '0', $created, $updated, $errors);
        }
    }

    private function processRow(int $rowIndex, string $deptCell, string $idProductoCell, string $nombreCell, string $unidadCell, string $stockCell, int &$created, int &$updated, array &$errors): void
    {
        if ($deptCell === '' || $nombreCell === '' || $unidadCell === '') {
            $errors[] = "Fila {$rowIndex}: Datos incompletos";
            return;
        }

        $departamento = Departamento::where('id_depto', $deptCell)->first();
        if (!$departamento) {
            $errors[] = "Fila {$rowIndex}: Departamento '{$deptCell}' no encontrado";
            return;
        }

        $unidad = Unidad::where('id_unidad', $unidadCell)->orWhere('nombre_unidad', $unidadCell)->first();
        if (!$unidad) {
            $errors[] = "Fila {$rowIndex}: Unidad '{$unidadCell}' no encontrada";
            return;
        }

        $idProducto = $idProductoCell ?: $this->generateProductId($departamento->id_depto);
        $codigoBarra = $this->generateSimpleBarcode($this->getUnitPrefix($unidad->id_unidad), $this->getNextSequenceForUnit($unidad->id_unidad));

        $payload = [
            'id_producto' => $idProducto,
            'codigo_barra' => $codigoBarra,
            'nombre_producto' => $nombreCell,
            'stock_minimo' => 0,
            'stock_actual' => is_numeric($stockCell) ? (int) $stockCell : 0,
            'observaciones' => null,
            'id_unidad' => $unidad->id_unidad,
        ];

        $producto = Producto::find($idProducto);
        if ($producto) {
            $producto->update($payload);
            $updated++;
        } else {
            $producto = Producto::create($payload);
            $created++;
        }

        $producto->departamentos()->syncWithoutDetaching([$departamento->id_depto]);
    }

    private function generateProductId(string $deptPrefix): string
    {
        $max = Producto::where('id_producto', 'like', $deptPrefix . '_%')
            ->get()
            ->map(fn($p) => (int) substr($p->id_producto, strlen($deptPrefix) + 1))
            ->max() ?? 0;

        return $deptPrefix . '_' . ($max + 1);
    }

    private function getUnitPrefix(string $unidadId): string
    {
        $unitPrefixes = [
            'U001' => '1',
            'U002' => '2',
            'U003' => '3',
            'U004' => '4',
            'U005' => '5',
            'U006' => '6',
            'U007' => '7',
        ];

        return $unitPrefixes[$unidadId] ?? '1';
    }

    private function getNextSequenceForUnit(string $unidadId): int
    {
        $lastBarcode = Producto::where('id_unidad', $unidadId)
            ->whereNotNull('codigo_barra')
            ->orderBy('codigo_barra', 'desc')
            ->first();

        if (!$lastBarcode || !$lastBarcode->codigo_barra) {
            return 1;
        }

        $lastCode = $lastBarcode->codigo_barra;
        $prefix = $this->getUnitPrefix($unidadId);
        
        if (str_starts_with($lastCode, $prefix)) {
            $sequence = (int) substr($lastCode, 1, 6);
            return $sequence + 1;
        }

        return 1;
    }

    private function generateSimpleBarcode(string $prefix, int $sequence): string
    {
        $code = $prefix . str_pad($sequence, 6, '0', STR_PAD_LEFT);
        $checksum = $this->calculateSimpleChecksum($code);
        
        return $code . $checksum;
    }

    private function calculateSimpleChecksum(string $code): int
    {
        $sum = 0;
        for ($i = 0; $i < 7; $i++) {
            $digit = (int) $code[$i];
            $sum += ($i % 2 === 0) ? $digit * 3 : $digit;
        }
        
        return (10 - ($sum % 10)) % 10;
    }

    private function clearAllBarcodes(): void
    {
        try {
            Producto::whereNotNull('codigo_barra')->update(['codigo_barra' => null]);
            $this->clearBarcodeImages();
            
            Log::info('CargaMasiva - Códigos de barras limpiados');
        } catch (\Exception $e) {
            Log::error('CargaMasiva - Error limpiando códigos de barras', ['error' => $e->getMessage()]);
        }
    }

    private function clearBarcodeImages(): void
    {
        try {
            $barcodePath = 'codigos_productos';
            
            if (Storage::disk('public')->exists($barcodePath)) {
                $files = Storage::disk('public')->files($barcodePath);
                
                foreach ($files as $file) {
                    Storage::disk('public')->delete($file);
                }
                
                Log::info('CargaMasiva - Imágenes eliminadas', ['files_deleted' => count($files)]);
            }
        } catch (\Exception $e) {
            Log::error('CargaMasiva - Error eliminando imágenes', ['error' => $e->getMessage()]);
        }
    }
}