<?php
namespace App\Http\Controllers;
use App\Services\ExcelImportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
    public function upload(Request $request)
    {
        $request->validate([
            'archivo' => 'required|file|mimes:xlsx,xls,csv|max:10240'
        ]);
        try {
            $file = $request->file('archivo');
            $fileExtension = $file->getClientOriginalExtension();
            $tempPath = $file->getRealPath();
            if (!$tempPath || !file_exists($tempPath)) {
                throw new \Exception('No se pudo acceder al archivo subido');
            }
            $excelService = new ExcelImportService();
            $result = $excelService->importFromFile($tempPath, $fileExtension);
            if ($result['success']) {
                $message = $result['message'];
                if (!empty($result['errors'])) {
                    $message .= ' Algunos registros tuvieron errores: ' . implode(', ', $result['errors']);
                }
                return redirect()->route('carga-masiva.index')
                    ->with('success', $message);
            } else {
                return redirect()->route('carga-masiva.index')
                    ->with('error', $result['message']);
            }
        } catch (\Exception $e) {
            Log::error('Error en carga masiva: ' . $e->getMessage(), [
                'temp_path' => $tempPath ?? 'N/A',
                'file_exists' => isset($tempPath) ? file_exists($tempPath) : false
            ]);
            return redirect()->route('carga-masiva.index')
                ->with('error', 'Error al procesar el archivo: ' . $e->getMessage());
        }
    }
    public function downloadTemplate()
    {
        $filePath = public_path('storage/plantillas/Plantilla_Inventario_Insumo_CIC.xlsx');
        
        if (!file_exists($filePath)) {
            return redirect()->route('carga-masiva.index')
                ->with('error', 'La plantilla no está disponible en este momento.');
        }
        
        return response()->download($filePath, 'Plantilla_Inventario_Insumo_CIC.xlsx');
    }
}
