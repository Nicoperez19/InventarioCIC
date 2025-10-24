<?php

namespace App\Http\Controllers;

use App\Models\Insumo;
use App\Models\TipoInsumo;
use App\Models\UnidadMedida;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CargaMasivaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('layouts.carga_masiva.carga_masiva_index');
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'archivo' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Archivo inválido',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $archivo = $request->file('archivo');
            $extension = $archivo->getClientOriginalExtension();
            
            $resultados = $this->procesarArchivo($archivo, $extension);

            return response()->json([
                'success' => true,
                'data' => $resultados,
                'message' => 'Carga masiva completada exitosamente'
            ]);

        } catch (\Exception $e) {
            Log::error('Error en carga masiva: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'success' => false,
                'message' => 'Error en carga masiva: ' . $e->getMessage()
            ], 500);
        }
    }

    private function procesarArchivo($archivo, string $extension): array
    {
        $resultados = [
            'tipos_insumo_creados' => 0,
            'insumos_creados' => 0,
            'errores' => [],
            'tipos_insumo' => [],
            'insumos' => []
        ];

        return DB::transaction(function () use ($archivo, $extension, $resultados) {
            // Cargar el archivo
            $spreadsheet = $this->cargarArchivo($archivo, $extension);
            
            // Paso 1: Crear tipos de insumo desde los nombres de las hojas
            $tiposInsumo = $this->crearTiposInsumoDesdeHojas($spreadsheet);
            $resultados['tipos_insumo_creados'] = count($tiposInsumo);
            $resultados['tipos_insumo'] = $tiposInsumo;

            // Paso 2: Procesar cada hoja para crear insumos
            foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {
                $nombreHoja = $worksheet->getTitle();
                $tipoInsumo = TipoInsumo::where('nombre_tipo', $nombreHoja)->first();
                
                if ($tipoInsumo) {
                    $insumosHoja = $this->procesarHojaInsumos($worksheet, $tipoInsumo->id);
                    $resultados['insumos_creados'] += count($insumosHoja);
                    $resultados['insumos'] = array_merge($resultados['insumos'], $insumosHoja);
                }
            }

            return $resultados;
        });
    }

    private function cargarArchivo($archivo, string $extension)
    {
        $rutaTemporal = $archivo->getRealPath();
        
        if ($extension === 'csv') {
            return IOFactory::load($rutaTemporal);
        } else {
            return IOFactory::load($rutaTemporal);
        }
    }

    private function crearTiposInsumoDesdeHojas($spreadsheet): array
    {
        $tiposCreados = [];

        foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {
            $nombreHoja = $worksheet->getTitle();
            
            // Verificar si el tipo de insumo ya existe
            $tipoExistente = TipoInsumo::where('nombre_tipo', $nombreHoja)->first();
            
            if (!$tipoExistente) {
                $tipoInsumo = TipoInsumo::create([
                    'nombre_tipo' => $nombreHoja,
                    'descripcion' => "Tipo de insumo creado automáticamente desde hoja: {$nombreHoja}",
                    'color' => $this->generarColorAleatorio(),
                    'activo' => true
                ]);
                
                $tiposCreados[] = [
                    'id' => $tipoInsumo->id,
                    'nombre_tipo' => $tipoInsumo->nombre_tipo,
                    'color' => $tipoInsumo->color
                ];
            } else {
                $tiposCreados[] = [
                    'id' => $tipoExistente->id,
                    'nombre_tipo' => $tipoExistente->nombre_tipo,
                    'color' => $tipoExistente->color,
                    'existe' => true
                ];
            }
        }

        return $tiposCreados;
    }

    private function procesarHojaInsumos(Worksheet $worksheet, int $tipoInsumoId): array
    {
        $insumosCreados = [];
        $filaInicio = 4; // Empezar desde la fila 4
        $ultimaFila = $worksheet->getHighestRow();

        for ($fila = $filaInicio; $fila <= $ultimaFila; $fila++) {
            // Columna B: Código del insumo
            $codigoInsumo = $worksheet->getCell("B{$fila}")->getValue();
            // Columna C: Nombre del insumo
            $nombreInsumo = $worksheet->getCell("C{$fila}")->getValue();
            // Columna D: Unidad de medida
            $unidadMedida = $worksheet->getCell("D{$fila}")->getValue();

            // Verificar que al menos el nombre del insumo esté presente
            if (empty($nombreInsumo)) {
                continue;
            }

            // Generar código si no existe
            if (empty($codigoInsumo)) {
                $codigoInsumo = $this->generarCodigoInsumo();
            }

            // Buscar o crear unidad de medida
            $unidadMedidaId = $this->buscarOCrearUnidadMedida($unidadMedida);

            // Crear el insumo
            $insumo = Insumo::create([
                'id_insumo' => $this->generarIdInsumo(),
                'codigo_barra' => $codigoInsumo,
                'nombre_insumo' => $nombreInsumo,
                'stock_minimo' => 0,
                'stock_actual' => 0,
                'id_unidad' => $unidadMedidaId,
                'tipo_insumo_id' => $tipoInsumoId,
                'departamento_id' => null
            ]);

            $insumosCreados[] = [
                'id_insumo' => $insumo->id_insumo,
                'codigo_barra' => $insumo->codigo_barra,
                'nombre_insumo' => $insumo->nombre_insumo,
                'tipo_insumo' => $insumo->tipoInsumo->nombre_tipo ?? 'Sin tipo'
            ];
        }

        return $insumosCreados;
    }

    private function buscarOCrearUnidadMedida(string $nombreUnidad): int
    {
        if (empty($nombreUnidad)) {
            // Unidad por defecto si no se especifica
            $unidad = UnidadMedida::firstOrCreate(
                ['nombre_unidad' => 'Unidad'],
                ['descripcion' => 'Unidad por defecto']
            );
            return $unidad->id_unidad;
        }

        $unidad = UnidadMedida::firstOrCreate(
            ['nombre_unidad' => $nombreUnidad],
            ['descripcion' => "Unidad de medida: {$nombreUnidad}"]
        );

        return $unidad->id_unidad;
    }

    private function generarCodigoInsumo(): string
    {
        do {
            $codigo = 'INS-' . strtoupper(Str::random(8));
        } while (Insumo::where('codigo_barra', $codigo)->exists());

        return $codigo;
    }

    private function generarIdInsumo(): string
    {
        do {
            $id = 'INS' . str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT);
        } while (Insumo::where('id_insumo', $id)->exists());

        return $id;
    }

    private function generarColorAleatorio(): string
    {
        $colores = [
            '#3B82F6', '#EF4444', '#10B981', '#F59E0B', '#8B5CF6',
            '#06B6D4', '#84CC16', '#F97316', '#EC4899', '#6366F1'
        ];

        return $colores[array_rand($colores)];
    }
}