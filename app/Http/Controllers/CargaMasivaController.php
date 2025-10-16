<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use App\Models\Producto;
use App\Models\Unidad;
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
        return view('layouts.carga_masiva.carga_masiva_index');
    }

    public function upload(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt,xlsx,xls'],
        ]);

        $file = $request->file('file');
        // Guardar archivo en storage/app/public/documentos_excels_subidos
        $original = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();
        $filename = $original.'_'.now()->format('Ymd_His').'.'.$extension;
        $storedPath = $file->storeAs('documentos_excels_subidos', $filename, 'public');
        Log::info('CargaMasiva - Archivo subido almacenado', [
            'stored_path' => $storedPath,
        ]);

        // Usar la ruta física del archivo almacenado para el procesamiento
        $path = Storage::disk('public')->path($storedPath);
        $extension = strtolower($extension);

        $handle = fopen($path, 'r');
        if ($handle === false) {
            return back()->withErrors(['file' => 'No se pudo leer el archivo subido.']);
        }

        $rowIndex = 0;
        $created = 0;
        $updated = 0;
        $errors = [];

        // Esperado CSV columnas:
        // 1) id_depto (ej: CIC_admin)
        // 2) (opcional) id_producto; si viene vacío, se genera como prefix_n
        // 3) nombre_producto
        // 4) unidad (id_unidad o nombre_unidad)
        // 5) stock_actual

        DB::beginTransaction();
        try {
            if (in_array($extension, ['csv', 'txt'])) {
                $handle = fopen($path, 'r');
                if ($handle === false) {
                    return back()->withErrors(['file' => 'No se pudo leer el archivo subido.']);
                }
                while (($data = fgetcsv($handle, 0, ';')) !== false) {
                    $rowIndex++;
                    if (count($data) === 1) {
                        $data = str_getcsv($data[0], ',');
                    }
                    if ($rowIndex <= 4) {
                        continue;
                    }
                    $deptCell = trim($data[0] ?? '');
                    $idProductoCell = trim($data[1] ?? '');
                    $nombreCell = trim($data[2] ?? '');
                    $unidadCell = trim($data[3] ?? '');
                    $stockCell = trim($data[4] ?? '0');
                    $this->processRow($rowIndex, $deptCell, $idProductoCell, $nombreCell, $unidadCell, $stockCell, $created, $updated, $errors);
                }
                fclose($handle);
            } else {
                // XLSX/XLS vía PhpSpreadsheet
                try {
                    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($path);
                } catch (\Throwable $e) {
                    Log::error('CargaMasiva - No se pudo abrir XLSX/XLS', ['message' => $e->getMessage()]);

                    return back()->withErrors(['file' => 'No se pudo leer el archivo Excel. Instala el lector o sube CSV.']);
                }
                $sheet = $spreadsheet->getActiveSheet();
                $rows = $sheet->toArray(null, true, true, true); // claves A,B,C...
                foreach ($rows as $idx => $row) {
                    $rowIndex = (int) $idx;
                    if ($rowIndex <= 4) {
                        continue;
                    }
                    $deptCell = trim((string) ($row['A'] ?? ''));
                    $idProductoCell = trim((string) ($row['B'] ?? ''));
                    $nombreCell = trim((string) ($row['C'] ?? ''));
                    $unidadCell = trim((string) ($row['D'] ?? ''));
                    $stockCell = trim((string) ($row['E'] ?? '0'));
                    $this->processRow($rowIndex, $deptCell, $idProductoCell, $nombreCell, $unidadCell, $stockCell, $created, $updated, $errors);
                }
            }

            DB::commit();
            Log::info('CargaMasiva - Resumen', [
                'creados' => $created,
                'actualizados' => $updated,
                'errores' => count($errors),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error en carga masiva de artículos de aseo', [
                'message' => $e->getMessage(),
            ]);

            return back()->withErrors(['file' => 'Ocurrió un error al procesar el archivo. Revisa el log para más detalles.']);
        } finally {
            // nada que cerrar si fue XLSX; CSV ya fue cerrado arriba
        }

        $status = "Carga completada: {$created} creados, {$updated} actualizados.";
        if (! empty($errors)) {
            $status .= ' Algunos registros tuvieron problemas.';

            return back()->with('status', $status)->withErrors(['detalle' => implode("\n", $errors)]);
        }

        return back()->with('status', $status);
    }

    private function extractPrefixFromDept(string $deptId): string
    {
        // Ejemplos: CIC_admin -> admin; DEPT_marketing -> marketing; fallback: todo en minúsculas
        if (str_contains($deptId, '_')) {
            $parts = explode('_', $deptId, 2);

            return strtolower($parts[1] ?? $deptId);
        }

        return strtolower($deptId);
    }

    private function generateNextProductId(string $prefix): ?string
    {
        $prefixWithUnderscore = $prefix.'_';
        $existingIds = Producto::where('id_producto', 'like', $prefixWithUnderscore.'%')
            ->pluck('id_producto')
            ->all();

        $max = 0;
        foreach ($existingIds as $id) {
            if (str_starts_with($id, $prefixWithUnderscore)) {
                $suffix = substr($id, strlen($prefixWithUnderscore));
                if (ctype_digit($suffix)) {
                    $num = (int) $suffix;
                    if ($num > $max) {
                        $max = $num;
                    }
                }
            }
        }

        $next = $max + 1;

        return $prefix.'_'.$next;
    }

    private function processRow(int $rowIndex, string $deptCell, string $idProductoCell, string $nombreCell, string $unidadCell, string $stockCell, int &$created, int &$updated, array &$errors): void
    {
        if ($deptCell === '' || $nombreCell === '' || $unidadCell === '') {
            $msg = "Fila {$rowIndex}: Datos incompletos (departamento/nombre/unidad).";
            $errors[] = $msg;
            Log::warning('CargaMasiva - Datos incompletos', [
                'row' => $rowIndex,
                'departamento' => $deptCell,
                'nombre' => $nombreCell,
                'unidad' => $unidadCell,
            ]);

            return;
        }

        $departamento = Departamento::where('nombre_depto', $deptCell)->first();
        if (! $departamento) {
            $msg = "Fila {$rowIndex}: Departamento '{$deptCell}' no existe (por nombre).";
            $errors[] = $msg;
            Log::warning('CargaMasiva - Departamento no encontrado', [
                'row' => $rowIndex,
                'departamento_nombre' => $deptCell,
            ]);

            return;
        }

        $unidad = Unidad::where('id_unidad', $unidadCell)
            ->orWhere('nombre_unidad', $unidadCell)
            ->first();
        if (! $unidad) {
            $msg = "Fila {$rowIndex}: Unidad '{$unidadCell}' no existe.";
            $errors[] = $msg;
            Log::warning('CargaMasiva - Unidad no encontrada', [
                'row' => $rowIndex,
                'unidad' => $unidadCell,
            ]);

            return;
        }

        $prefix = $this->extractPrefixFromDept($departamento->id_depto);
        // Generar SIEMPRE el id por departamento (ignorar celda de ID si viene)
        $idProducto = $this->generateNextProductId($prefix);
        $prefix = $this->extractPrefixFromDept($departamento->id_depto);
        // Generar SIEMPRE el id por departamento (ignorar celda de ID si viene)
        $idProducto = $this->generateNextProductId($prefix);

        if ($idProducto === null) {
            $msg = "Fila {$rowIndex}: No se pudo generar id_producto para prefijo '{$prefix}'.";
            $errors[] = $msg;
            Log::warning('CargaMasiva - No se pudo generar id_producto', [
                'row' => $rowIndex,
                'prefijo' => $prefix,
            ]);

            return;
        }

        $payload = [
            'id_producto' => $idProducto,
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

    // Se elimina sanitizeProductId: el id se genera siempre por departamento
}
