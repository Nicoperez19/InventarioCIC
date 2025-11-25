<?php
namespace App\Http\Controllers;
use App\Models\Factura;
use App\Models\Proveedor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Smalot\PdfParser\Parser as PdfParser;
use PhpOffice\PhpWord\IOFactory as PhpWordIOFactory;
class FacturaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Factura::where('run', Auth::user()->run)
                ->with('proveedor')
                ->orderBy('created_at', 'desc');
            if ($request->has('search')) {
                $search = $request->get('search');
                $query->where(function($q) use ($search) {
                    $q->where('numero_factura', 'like', "%{$search}%")
                      ->orWhere('observaciones', 'like', "%{$search}%")
                      ->orWhereHas('proveedor', function($q) use ($search) {
                          $q->where('nombre_proveedor', 'like', "%{$search}%");
                      });
                });
            }
            if ($request->has('proveedor_id')) {
                $query->where('proveedor_id', $request->get('proveedor_id'));
            }
            if ($request->has('fecha_desde')) {
                $query->where('fecha_factura', '>=', $request->get('fecha_desde'));
            }
            if ($request->has('fecha_hasta')) {
                $query->where('fecha_factura', '<=', $request->get('fecha_hasta'));
            }
            $facturas = $query->paginate($request->get('per_page', 15));
            return response()->json([
                'success' => true,
                'data' => $facturas,
                'message' => 'Facturas obtenidas exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener facturas: ' . $e->getMessage()
            ], 500);
        }
    }
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'numero_factura' => 'required|string|max:255|unique:facturas,numero_factura',
                'proveedor_id' => 'required|exists:proveedores,id',
                'monto_total' => 'required|numeric|min:0',
                'fecha_factura' => 'required|date',
                'archivo' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
                'observaciones' => 'nullable|string|max:1000'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Datos de validación incorrectos',
                    'errors' => $validator->errors()
                ], 422);
            }
            $facturaData = $request->only(['numero_factura', 'proveedor_id', 'monto_total', 'fecha_factura', 'observaciones']);
            $facturaData['run'] = Auth::user()->run;
            if ($request->hasFile('archivo')) {
                $archivo = $request->file('archivo');
                $nombreArchivo = Str::slug($request->numero_factura) . '_' . time() . '.' . $archivo->getClientOriginalExtension();
                $ruta = $archivo->storeAs('facturas', $nombreArchivo, 'private');
                $facturaData['archivo_path'] = $ruta;
                $facturaData['archivo_nombre'] = $archivo->getClientOriginalName();
            }
            $factura = Factura::create($facturaData);
            $factura->load('proveedor');
            return response()->json([
                'success' => true,
                'data' => $factura,
                'message' => 'Factura creada exitosamente'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear factura: ' . $e->getMessage()
            ], 500);
        }
    }
    public function update(Request $request, Factura $factura)
    {
            if ($factura->run !== Auth::user()->run) {
            abort(403, 'No tienes permisos para editar esta factura');
            }
        
            $validator = Validator::make($request->all(), [
            'numero_factura' => 'required|string|max:255|unique:facturas,numero_factura,' . $factura->id,
            'proveedor_id' => 'required|exists:proveedores,id',
            'monto_total' => 'required|numeric|min:0',
            'fecha_factura' => 'required|date',
            'archivo' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
                'observaciones' => 'nullable|string|max:1000'
            ]);

            if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $facturaData = $request->only(['numero_factura', 'proveedor_id', 'monto_total', 'fecha_factura', 'observaciones']);
        
        if ($request->hasFile('archivo')) {
            if ($factura->archivo_path && Storage::disk('public')->exists($factura->archivo_path)) {
                Storage::disk('public')->delete($factura->archivo_path);
            }
            
            $carpetaFacturas = 'facturas';
            if (!Storage::disk('public')->exists($carpetaFacturas)) {
                Storage::disk('public')->makeDirectory($carpetaFacturas);
            }
            
                $archivo = $request->file('archivo');
            $proveedor = Proveedor::findOrFail($request->proveedor_id);
            $nombreArchivo = Str::slug($proveedor->nombre_proveedor) . '_' . time() . '.' . $archivo->getClientOriginalExtension();
            $ruta = $archivo->storeAs($carpetaFacturas, $nombreArchivo, 'public');
                $facturaData['archivo_path'] = $ruta;
                $facturaData['archivo_nombre'] = $archivo->getClientOriginalName();
            }
        
            $factura->update($facturaData);
        
        return redirect()->route('facturas.index')
            ->with('success', 'Factura actualizada exitosamente.');
    }
    public function destroy(Factura $factura)
    {
        if ($factura->run !== Auth::user()->run) {
            abort(403, 'No tienes permisos para eliminar esta factura');
        }
        
        try {
            if ($factura->archivo_path && Storage::disk('public')->exists($factura->archivo_path)) {
                Storage::disk('public')->delete($factura->archivo_path);
            }
            
            $factura->delete();
            
            return redirect()->route('facturas.index')
                ->with('success', 'Factura eliminada exitosamente.');
                
        } catch (\Exception $e) {
            return redirect()->route('facturas.index')
                ->with('error', 'Error al eliminar factura: ' . $e->getMessage());
        }
    }
    public function download(Factura $factura)
    {
        if ($factura->run !== Auth::user()->run) {
            abort(403, 'No tienes permisos para descargar esta factura');
        }
        
        if (!$factura->archivo_path) {
            abort(404, 'El archivo no existe');
        }

        if (!Storage::disk('public')->exists($factura->archivo_path)) {
            abort(404, 'El archivo no existe');
        }

        $path = Storage::disk('public')->path($factura->archivo_path);
        
        if (!file_exists($path)) {
            abort(404, 'El archivo no existe en el servidor');
        }

        return response()->download($path, $factura->archivo_nombre);
    }

    public function view(Factura $factura)
    {
            if ($factura->run !== Auth::user()->run) {
            abort(403, 'No tienes permisos para ver esta factura');
        }
        
        if (!$factura->archivo_path) {
            abort(404, 'El archivo no existe');
        }

        if (!Storage::disk('public')->exists($factura->archivo_path)) {
            abort(404, 'El archivo no existe');
        }

        $path = Storage::disk('public')->path($factura->archivo_path);
        
        if (!file_exists($path)) {
            abort(404, 'El archivo no existe en el servidor');
        }

        $extension = strtolower(pathinfo($factura->archivo_path, PATHINFO_EXTENSION));
        
        $mimeTypes = [
            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ];
        
        $mimeType = $mimeTypes[$extension] ?? 'application/octet-stream';
        
        return response()->file($path, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $factura->archivo_nombre . '"',
        ]);
    }
    public function getProveedores(): JsonResponse
    {
        try {
            $proveedores = Proveedor::orderBy('nombre_proveedor')->get();
            return response()->json([
                'success' => true,
                'data' => $proveedores,
                'message' => 'Proveedores obtenidos exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener proveedores: ' . $e->getMessage()
            ], 500);
        }
    }

    public function upload(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'proveedor_id' => 'required|exists:proveedores,id',
                'archivo' => 'required|file|mimes:pdf,doc,docx|max:10240',
                'observaciones' => 'nullable|string|max:1000',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $carpetaFacturas = 'facturas';
            $rutaPublicStorage = storage_path('app/public/' . $carpetaFacturas);
            
            if (!file_exists($rutaPublicStorage)) {
                Storage::disk('public')->makeDirectory($carpetaFacturas);
            }

            $publicLink = public_path('storage');
            if (!file_exists($publicLink) || !is_link($publicLink)) {
                try {
                    if (PHP_OS_FAMILY === 'Windows') {
                        $target = storage_path('app/public');
                        if (!file_exists($target)) {
                            Storage::disk('public')->makeDirectory('');
                        }
                    } else {
                        symlink(storage_path('app/public'), $publicLink);
                    }
                } catch (\Exception $e) {
                }
            }

            $archivo = $request->file('archivo');
            $proveedor = Proveedor::findOrFail($request->proveedor_id);
            
            $nombreOriginal = $archivo->getClientOriginalName();
            $numeroFactura = $this->extraerNumeroFacturaDelNombre($nombreOriginal);
            
            if (empty($numeroFactura)) {
                return back()->withInput()
                    ->with('error', 'No se pudo encontrar el número de factura en el nombre del archivo. Por favor, asegúrate de que el nombre del archivo contenga números consecutivos (ej: factura_0000052095.pdf).');
            }
            
            $nombreArchivo = Str::slug($proveedor->nombre_proveedor) . '_' . time() . '.' . $archivo->getClientOriginalExtension();
            
            $ruta = $archivo->storeAs($carpetaFacturas, $nombreArchivo, 'public');
            $rutaCompleta = Storage::disk('public')->path($ruta);

            $fechaEmision = null;
            $montoTotal = 0;
            $textoExtraido = null;
            $infoDebug = null;
            
            try {
                $textoExtraido = $this->extraerTextoArchivo($rutaCompleta, $archivo->getClientOriginalExtension());
                if ($textoExtraido) {
                    $muestraTexto = mb_substr($textoExtraido, 0, 500);
                    $resultado = $this->extraerMontoTotal($textoExtraido);
                    $montoTotal = $resultado['monto'];
                    
                    // Extraer fecha de emisión
                    $fechaEmision = $this->extraerFechaEmision($textoExtraido);
                    
                    $infoDebug = "Muestra del texto extraído (primeros 500 caracteres): " . $muestraTexto . " | " . $resultado['debug'];
                } else {
                    $infoDebug = "No se pudo extraer texto del archivo.";
                }
            } catch (\Exception $e) {
                Storage::disk('public')->delete($ruta);
                return back()->withInput()
                    ->with('error', 'No se pudo leer el archivo. Por favor, verifica que el archivo esté en formato PDF o DOC válido.');
            }
            
            if (Factura::where('numero_factura', $numeroFactura)->exists()) {
                Storage::disk('public')->delete($ruta);
                return back()->withInput()
                    ->with('error', "El número de factura '{$numeroFactura}' ya existe en el sistema.");
            }

            $factura = Factura::create([
                'numero_factura' => $numeroFactura,
                'proveedor_id' => $request->proveedor_id,
                'monto_total' => $montoTotal,
                'fecha_factura' => $fechaEmision ?? now(),
                'archivo_path' => $ruta,
                'archivo_nombre' => $archivo->getClientOriginalName(),
                'observaciones' => $request->observaciones ?? null,
                'run' => Auth::user()->run,
            ]);

            $mensaje = 'Factura subida exitosamente.';
            $detalles = [];
            
            $detalles[] = "Número de factura: {$numeroFactura}";
            
            if ($montoTotal > 0) {
                $detalles[] = "Monto total extraído: $" . number_format($montoTotal, 0, ',', '.');
            } else {
                $detalles[] = "No se pudo extraer el monto total automáticamente.";
                if ($infoDebug) {
                    $detalles[] = "Información de depuración: {$infoDebug}";
                }
            }
            
            if (!empty($detalles)) {
                $mensaje .= ' ' . implode(' | ', $detalles);
            }

            return redirect()->route('facturas.index')
                ->with('success', $mensaje);

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error al subir la factura: ' . $e->getMessage());
        }
    }

    private function extraerTextoArchivo(string $rutaCompleta, string $extension): ?string
    {
        try {
            $extension = strtolower($extension);
            
            if ($extension === 'pdf') {
                $parser = new PdfParser();
                $pdf = $parser->parseFile($rutaCompleta);
                return $pdf->getText();
            } elseif (in_array($extension, ['doc', 'docx'])) {
                $phpWord = PhpWordIOFactory::load($rutaCompleta);
                $texto = '';
                foreach ($phpWord->getSections() as $section) {
                    foreach ($section->getElements() as $element) {
                        if (method_exists($element, 'getText')) {
                            $texto .= $element->getText() . "\n";
                        } elseif (method_exists($element, 'getRows')) {
                            foreach ($element->getRows() as $row) {
                                foreach ($row->getCells() as $cell) {
                                    $texto .= $cell->getText() . "\t";
                                }
                                $texto .= "\n";
                            }
                        }
                    }
                }
                return $texto;
            }
            
            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    private function extraerMontoTotal(string $texto): array
    {
        $lineas = explode("\n", $texto);
        $montosEncontrados = [];
        $lineasConTotal = [];
        $lineasRevisadas = [];
        
        $patronesEspecificos = [
            '/MONTO\s+TOTAL\s+(\d{1,3}(?:\.\d{3})+)/i',
            '/MONTO\s+TOTAL\s*:?\s*\$?\s*(\d{1,3}(?:\.\d{3})+)/i',
            '/MONTO\s+TOTAL\s*(\d{1,3}(?:\.\d{3})+)/i',
            '/TOTAL\s*:?\s*\$?\s*(\d{1,3}(?:\.\d{3})+)/i',
            '/TOTAL\s+\$?\s*(\d{1,3}(?:\.\d{3})+)/i',
        ];
        
        foreach ($lineas as $indice => $linea) {
            $lineaLimpia = trim($linea);
            $lineaMinuscula = mb_strtolower($lineaLimpia, 'UTF-8');
            
            if (preg_match('/monto\s+total|^total\s*\$|total\s*:?\s*\$?/i', $lineaLimpia)) {
                $lineasConTotal[] = $lineaLimpia;
                
                $tieneRut = preg_match('/\d{1,2}\.\d{3}\.\d{3}-\d/', $lineaLimpia);
                
                if (!$tieneRut) {
                    foreach ($patronesEspecificos as $patron) {
                        if (preg_match($patron, $lineaLimpia, $match)) {
                            $montoStr = trim($match[1]);
                            $lineasRevisadas[] = "Línea: '{$lineaLimpia}' - Monto extraído: '{$montoStr}'";
                            
                            if (!preg_match('/^\d{1,2}\.\d{3}\.\d{3}-\d$/', $montoStr) && !preg_match('/^\d{8,9}$/', str_replace('.', '', $montoStr))) {
                                $monto = $this->convertirMontoANumero($montoStr);
                                if ($monto >= 1000 && $monto < 999999999) {
                                    $montosEncontrados[] = $monto;
                                    $lineasRevisadas[] = "  -> Aceptado: {$monto}";
                                    break;
                                }
                            }
                        }
                    }
                } else {
                    $lineasRevisadas[] = "Línea contiene RUT, buscando en líneas siguientes...";
                }
                
                if (empty($montosEncontrados)) {
                    for ($i = $indice + 1; $i < min($indice + 5, count($lineas)); $i++) {
                        $siguienteLinea = trim($lineas[$i]);
                        if (!empty($siguienteLinea) && preg_match('/^(\d{1,3}(?:\.\d{3})+)$/', $siguienteLinea, $matchSiguiente)) {
                            $montoStr = trim($matchSiguiente[1]);
                            $numeroSinPuntos = str_replace('.', '', $montoStr);
                            
                            if (!preg_match('/^\d{8,9}$/', $numeroSinPuntos)) {
                                $monto = $this->convertirMontoANumero($montoStr);
                                if ($monto >= 1000 && $monto < 999999999) {
                                    $montosEncontrados[] = $monto;
                                    $lineasRevisadas[] = "  -> Aceptado de línea " . ($i + 1) . ": {$monto}";
                                    break;
                                }
                            }
                        }
                    }
                }
            }
        }
        
        if (empty($montosEncontrados)) {
            $ultimasLineas = array_slice($lineas, -30);
            $textoFinal = implode("\n", $ultimasLineas);
            
            foreach ($patronesEspecificos as $patron) {
                if (preg_match($patron, $textoFinal, $match)) {
                    $montoStr = trim($match[1]);
                    $numeroSinPuntos = str_replace('.', '', $montoStr);
                    
                    if (!preg_match('/^\d{1,2}\.\d{3}\.\d{3}-\d$/', $montoStr) && !preg_match('/^\d{8,9}$/', $numeroSinPuntos)) {
                        $monto = $this->convertirMontoANumero($montoStr);
                        if ($monto >= 1000 && $monto < 999999999) {
                            $montosEncontrados[] = $monto;
                            $lineasRevisadas[] = "  -> Aceptado de últimas líneas: {$monto}";
                            break;
                        }
                    }
                }
            }
            
            if (empty($montosEncontrados)) {
                foreach ($ultimasLineas as $indice => $linea) {
                    $lineaLimpia = trim($linea);
                    if (preg_match('/monto\s+total|^total\s*\$|total\s*:?\s*\$?/i', $lineaLimpia)) {
                        for ($i = $indice + 1; $i < count($ultimasLineas); $i++) {
                            $siguienteLinea = trim($ultimasLineas[$i]);
                            if (preg_match('/^(\d{1,3}(?:\.\d{3})+)$/', $siguienteLinea, $matchLinea)) {
                                $montoStr = trim($matchLinea[1]);
                                $numeroSinPuntos = str_replace('.', '', $montoStr);
                                
                                if (!preg_match('/^\d{8,9}$/', $numeroSinPuntos) && !preg_match('/^\d{1,2}\.\d{3}\.\d{3}-\d$/', $montoStr)) {
                                    $monto = $this->convertirMontoANumero($montoStr);
                                    if ($monto >= 1000 && $monto < 999999999) {
                                        $montosEncontrados[] = $monto;
                                        $lineasRevisadas[] = "  -> Aceptado después de MONTO TOTAL en últimas líneas: {$monto}";
                                        break 2;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        $debug = [];
        $debug[] = "Total de líneas: " . count($lineas);
        
        if (empty($lineasConTotal)) {
            $debug[] = "No se encontraron líneas con 'MONTO TOTAL' o 'TOTAL'";
            
            $ultimas40 = array_slice($lineas, -40);
            $textoFinal = implode(' ', $ultimas40);
            
            $patronesAlternativos = [
                '/TOTAL\s*:?\s*\$?\s*(\d{1,3}(?:\.\d{3})+)/i',
                '/TOTAL\s+\$?\s*(\d{1,3}(?:\.\d{3})+)/i',
                '/SON\s*:?\s*.*?(\d{1,3}(?:\.\d{3})+)/i',
                '/TOTAL\s+(\d{1,3}(?:\.\d{3})+)/i',
            ];
            
            foreach ($patronesAlternativos as $patron) {
                if (preg_match_all($patron, $textoFinal, $matches, PREG_SET_ORDER)) {
                    foreach ($matches as $match) {
                        $montoStr = trim($match[1]);
                        $numeroSinPuntos = str_replace('.', '', $montoStr);
                        $lineaContexto = '';
                        foreach ($ultimas40 as $l) {
                            if (strpos($l, $montoStr) !== false) {
                                $lineaContexto = mb_substr(trim($l), 0, 150);
                                break;
                            }
                        }
                        
                        if (!preg_match('/^\d{8,9}$/', $numeroSinPuntos) && !preg_match('/^\d{1,2}\.\d{3}\.\d{3}-\d$/', $montoStr)) {
                            $monto = $this->convertirMontoANumero($montoStr);
                            if ($monto >= 1000 && $monto < 999999999) {
                                $montosEncontrados[] = $monto;
                                $lineasRevisadas[] = "  -> Encontrado con patrón alternativo: {$monto} (contexto: {$lineaContexto})";
                                break 2;
                            }
                        }
                    }
                }
            }
            
            if (empty($montosEncontrados)) {
                foreach ($ultimas40 as $indice => $linea) {
                    $lineaLimpia = trim($linea);
                    
                    if (preg_match('/^(\d{1,3}(?:\.\d{3})+)$/', $lineaLimpia, $match)) {
                        $montoStr = trim($match[1]);
                        $numeroSinPuntos = str_replace('.', '', $montoStr);
                        
                        if (!preg_match('/^\d{8,9}$/', $numeroSinPuntos) && !preg_match('/^\d{1,2}\.\d{3}\.\d{3}-\d$/', $montoStr)) {
                            $monto = $this->convertirMontoANumero($montoStr);
                            if ($monto >= 1000 && $monto < 999999999) {
                                $contextoAnterior = isset($ultimas40[$indice - 1]) ? mb_substr(trim($ultimas40[$indice - 1]), 0, 100) : '';
                                $contextoSiguiente = isset($ultimas40[$indice + 1]) ? mb_substr(trim($ultimas40[$indice + 1]), 0, 100) : '';
                                if (stripos($contextoAnterior, 'total') !== false || stripos($contextoSiguiente, 'total') !== false) {
                                    $montosEncontrados[] = $monto;
                                    $lineasRevisadas[] = "  -> Encontrado número cerca de TOTAL: {$monto}";
                                    break;
                                }
                            }
                        }
                    }
                    
                    if (preg_match('/total/i', $lineaLimpia) && preg_match('/(\d{1,3}(?:\.\d{3})+)/', $lineaLimpia, $match)) {
                        $montoStr = trim($match[1]);
                        $numeroSinPuntos = str_replace('.', '', $montoStr);
                        
                        if (!preg_match('/^\d{8,9}$/', $numeroSinPuntos) && !preg_match('/^\d{1,2}\.\d{3}\.\d{3}-\d$/', $montoStr)) {
                            $monto = $this->convertirMontoANumero($montoStr);
                            if ($monto >= 1000 && $monto < 999999999) {
                                $montosEncontrados[] = $monto;
                                $lineasRevisadas[] = "  -> Encontrado en línea con TOTAL: {$monto} (línea: {$lineaLimpia})";
                                break;
                            }
                        }
                    }
                }
            }
            
            $ultimas15 = array_slice($lineas, -15);
            $debug[] = "Últimas 15 líneas:";
            foreach ($ultimas15 as $i => $linea) {
                $debug[] = "  " . ($i + 1) . ". " . mb_substr(trim($linea), 0, 150);
            }
        } else {
            $debug[] = "Líneas con MONTO TOTAL: " . count($lineasConTotal);
            foreach ($lineasConTotal as $i => $linea) {
                $debug[] = "  " . ($i + 1) . ". " . mb_substr($linea, 0, 150);
            }
        }
        
        if (!empty($lineasRevisadas)) {
            $debug[] = "Proceso:";
            foreach ($lineasRevisadas as $info) {
                $debug[] = "  " . $info;
            }
        }
        
        if (empty($montosEncontrados)) {
            $debug[] = "Resultado: No se encontraron montos válidos";
        } else {
            $debug[] = "Montos encontrados: " . implode(', ', $montosEncontrados);
            $debug[] = "Monto seleccionado: " . max($montosEncontrados);
        }

        return [
            'monto' => !empty($montosEncontrados) ? max($montosEncontrados) : 0,
            'debug' => implode('; ', $debug)
        ];
    }

    private function extraerNumeroFacturaDelNombre(string $nombreArchivo): ?string
    {
        if (preg_match_all('/\d+/', $nombreArchivo, $matches)) {
            $numeros = $matches[0];
            
            foreach ($numeros as $numero) {
                if (strlen($numero) >= 4 && strlen($numero) <= 15) {
                    return $numero;
                }
            }
            
            if (!empty($numeros)) {
                return end($numeros);
            }
        }
        
        return null;
    }

    private function convertirMontoANumero(string $montoString): float
    {
        $montoString = trim(preg_replace('/[^\d.,]/', '', $montoString));
        
        if (empty($montoString)) {
            return 0;
        }

        if (preg_match('/\d+\.\d{3}(?:\.\d{3})*(?:,\d{2})?$/', $montoString)) {
            $montoString = str_replace('.', '', $montoString);
            $montoString = str_replace(',', '.', $montoString);
        } elseif (preg_match('/\d+\.\d{3}$/', $montoString)) {
            $montoString = str_replace('.', '', $montoString);
        } elseif (preg_match('/\d+\.\d{3}(?:\.\d{3})*/', $montoString) && strpos($montoString, ',') !== false) {
            $montoString = str_replace('.', '', $montoString);
            $montoString = str_replace(',', '.', $montoString);
        } elseif (strpos($montoString, ',') !== false && strpos($montoString, '.') !== false) {
            $montoString = str_replace('.', '', $montoString);
            $montoString = str_replace(',', '.', $montoString);
        } elseif (strpos($montoString, ',') !== false && strpos($montoString, '.') === false) {
            if (preg_match('/,\d{1,2}$/', $montoString)) {
                $montoString = str_replace(',', '.', $montoString);
            } else {
                $montoString = str_replace(',', '', $montoString);
            }
        }

        $monto = floatval($montoString);
        return $monto > 0 ? $monto : 0;
    }

    private function extraerFechaEmision(string $texto): ?\Carbon\Carbon
    {
        if (empty($texto)) {
            return null;
        }

        // Nombres de meses en español
        $meses = [
            'enero' => 1, 'febrero' => 2, 'marzo' => 3, 'abril' => 4,
            'mayo' => 5, 'junio' => 6, 'julio' => 7, 'agosto' => 8,
            'septiembre' => 9, 'octubre' => 10, 'noviembre' => 11, 'diciembre' => 12
        ];

        // Patrones para buscar la fecha de emisión
        $patrones = [
            // Fecha Emisión: 22 Septiembre 2025
            '/fecha\s+emisi[oó]n\s*:?\s*(\d{1,2})\s+([a-záéíóúñ]+)\s+(\d{4})/i',
            // Fecha Emisión: 22/09/2025
            '/fecha\s+emisi[oó]n\s*:?\s*(\d{1,2})\/(\d{1,2})\/(\d{4})/i',
            // Fecha Emisión: 22-09-2025
            '/fecha\s+emisi[oó]n\s*:?\s*(\d{1,2})-(\d{1,2})-(\d{4})/i',
            // Fecha de Emisión: 22 Septiembre 2025
            '/fecha\s+de\s+emisi[oó]n\s*:?\s*(\d{1,2})\s+([a-záéíóúñ]+)\s+(\d{4})/i',
            // Emisión: 22 Septiembre 2025
            '/emisi[oó]n\s*:?\s*(\d{1,2})\s+([a-záéíóúñ]+)\s+(\d{4})/i',
            // Fecha: 22 Septiembre 2025
            '/fecha\s*:?\s*(\d{1,2})\s+([a-záéíóúñ]+)\s+(\d{4})/i',
        ];

        foreach ($patrones as $patron) {
            if (preg_match($patron, $texto, $matches)) {
                try {
                    // Si el patrón tiene formato con nombre de mes
                    if (isset($matches[2]) && !is_numeric($matches[2])) {
                        $dia = (int)$matches[1];
                        $mesNombre = mb_strtolower(trim($matches[2]), 'UTF-8');
                        $anio = (int)$matches[3];

                        // Buscar el mes en el array
                        $mes = null;
                        foreach ($meses as $nombreMes => $numeroMes) {
                            if (mb_stripos($mesNombre, $nombreMes) !== false || mb_stripos($nombreMes, $mesNombre) !== false) {
                                $mes = $numeroMes;
                                break;
                            }
                        }

                        if ($mes && $dia >= 1 && $dia <= 31 && $anio >= 1900 && $anio <= 2100) {
                            return \Carbon\Carbon::create($anio, $mes, $dia);
                        }
                    }
                    // Si el patrón tiene formato numérico (DD/MM/YYYY o DD-MM-YYYY)
                    elseif (isset($matches[2]) && is_numeric($matches[2])) {
                        $dia = (int)$matches[1];
                        $mes = (int)$matches[2];
                        $anio = (int)$matches[3];

                        if ($dia >= 1 && $dia <= 31 && $mes >= 1 && $mes <= 12 && $anio >= 1900 && $anio <= 2100) {
                            return \Carbon\Carbon::create($anio, $mes, $dia);
                        }
                    }
                } catch (\Exception $e) {
                    continue;
                }
            }
        }

        // Si no se encontró con los patrones específicos, buscar fechas en formato común cerca de "emisión"
        $lineas = explode("\n", $texto);
        foreach ($lineas as $linea) {
            if (preg_match('/emisi[oó]n/i', $linea)) {
                // Buscar fecha en formato DD/MM/YYYY o DD-MM-YYYY en la misma línea o siguiente
                if (preg_match('/(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})/', $linea, $matches)) {
                    try {
                        $dia = (int)$matches[1];
                        $mes = (int)$matches[2];
                        $anio = (int)$matches[3];
                        
                        if ($dia >= 1 && $dia <= 31 && $mes >= 1 && $mes <= 12 && $anio >= 1900 && $anio <= 2100) {
                            return \Carbon\Carbon::create($anio, $mes, $dia);
                        }
                    } catch (\Exception $e) {
                        continue;
                    }
                }
            }
        }

        return null;
    }

}



