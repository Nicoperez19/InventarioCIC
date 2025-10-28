<?php
namespace App\Http\Controllers;
use App\Models\Solicitud;
use App\Models\SolicitudItem;
use App\Models\Insumo;
use App\Models\Departamento;
use App\Models\TipoInsumo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class SolicitudController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $solicitudes = Solicitud::with(['user', 'departamento', 'tipoInsumo', 'items.insumo'])
            ->orderBy('fecha_solicitud', 'desc')
            ->paginate(15);
        return view('layouts.solicitud.solicitud_index', compact('solicitudes'));
    }
    public function create()
    {
        $departamentos = Departamento::orderBy('nombre_depto')->get();
        $tiposInsumo = TipoInsumo::activos()->orderBy('nombre_tipo')->get();
        return view('layouts.solicitud.solicitud_create', compact('departamentos', 'tiposInsumo'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'tipo_solicitud' => 'required|in:individual,masiva',
            'departamento_id' => 'required|exists:departamentos,id_depto',
            'tipo_insumo_id' => 'nullable|exists:tipo_insumos,id',
            'observaciones' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.insumo_id' => 'required|exists:insumos,id_insumo',
            'items.*.cantidad_solicitada' => 'required|integer|min:1',
            'items.*.observaciones_item' => 'nullable|string|max:500',
        ]);
        try {
            DB::beginTransaction();
            $solicitud = Solicitud::create([
                'tipo_solicitud' => $request->tipo_solicitud,
                'observaciones' => $request->observaciones,
                'user_id' => Auth::id(),
                'departamento_id' => $request->departamento_id,
                'tipo_insumo_id' => $request->tipo_insumo_id,
                'fecha_solicitud' => now(),
            ]);
            foreach ($request->items as $item) {
                SolicitudItem::create([
                    'solicitud_id' => $solicitud->id,
                    'insumo_id' => $item['insumo_id'],
                    'cantidad_solicitada' => $item['cantidad_solicitada'],
                    'observaciones_item' => $item['observaciones_item'] ?? null,
                ]);
            }
            DB::commit();
            return redirect()->route('solicitudes.index')
                ->with('success', 'Solicitud creada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Error al crear la solicitud: ' . $e->getMessage());
        }
    }
    public function show(Solicitud $solicitud)
    {
        $solicitud->load(['user', 'departamento', 'tipoInsumo', 'items.insumo.unidadMedida', 'aprobadoPor', 'entregadoPor']);
        return view('layouts.solicitud.solicitud_show', compact('solicitud'));
    }
    public function edit(Solicitud $solicitud)
    {
        if ($solicitud->estado !== 'pendiente') {
            return redirect()->route('solicitudes.index')
                ->with('error', 'No se puede editar una solicitud que no está pendiente.');
        }
        $departamentos = Departamento::orderBy('nombre_depto')->get();
        $tiposInsumo = TipoInsumo::activos()->orderBy('nombre_tipo')->get();
        $solicitud->load(['items.insumo.unidadMedida']);
        return view('layouts.solicitud.solicitud_edit', compact('solicitud', 'departamentos', 'tiposInsumo'));
    }
    public function update(Request $request, Solicitud $solicitud)
    {
        if ($solicitud->estado !== 'pendiente') {
            return redirect()->route('solicitudes.index')
                ->with('error', 'No se puede editar una solicitud que no está pendiente.');
        }
        $request->validate([
            'tipo_solicitud' => 'required|in:individual,masiva',
            'departamento_id' => 'required|exists:departamentos,id_depto',
            'tipo_insumo_id' => 'nullable|exists:tipo_insumos,id',
            'observaciones' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.insumo_id' => 'required|exists:insumos,id_insumo',
            'items.*.cantidad_solicitada' => 'required|integer|min:1',
            'items.*.observaciones_item' => 'nullable|string|max:500',
        ]);
        try {
            DB::beginTransaction();
            $solicitud->update([
                'tipo_solicitud' => $request->tipo_solicitud,
                'observaciones' => $request->observaciones,
                'departamento_id' => $request->departamento_id,
                'tipo_insumo_id' => $request->tipo_insumo_id,
            ]);
            $solicitud->items()->delete();
            foreach ($request->items as $item) {
                SolicitudItem::create([
                    'solicitud_id' => $solicitud->id,
                    'insumo_id' => $item['insumo_id'],
                    'cantidad_solicitada' => $item['cantidad_solicitada'],
                    'observaciones_item' => $item['observaciones_item'] ?? null,
                ]);
            }
            DB::commit();
            return redirect()->route('solicitudes.index')
                ->with('success', 'Solicitud actualizada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Error al actualizar la solicitud: ' . $e->getMessage());
        }
    }
    public function destroy(Solicitud $solicitud)
    {
        if ($solicitud->estado !== 'pendiente') {
            return redirect()->route('solicitudes.index')
                ->with('error', 'No se puede eliminar una solicitud que no está pendiente.');
        }
        try {
            $solicitud->delete();
            return redirect()->route('solicitudes.index')
                ->with('success', 'Solicitud eliminada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('solicitudes.index')
                ->with('error', 'Error al eliminar la solicitud: ' . $e->getMessage());
        }
    }
    public function aprobar(Request $request, Solicitud $solicitud)
    {
        if ($solicitud->estado !== 'pendiente') {
            return redirect()->route('solicitudes.index')
                ->with('error', 'Solo se pueden aprobar solicitudes pendientes.');
        }
        try {
            $solicitud->aprobar(Auth::id());
            return redirect()->route('solicitudes.index')
                ->with('success', 'Solicitud aprobada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('solicitudes.index')
                ->with('error', 'Error al aprobar la solicitud: ' . $e->getMessage());
        }
    }
    public function rechazar(Request $request, Solicitud $solicitud)
    {
        if ($solicitud->estado !== 'pendiente') {
            return redirect()->route('solicitudes.index')
                ->with('error', 'Solo se pueden rechazar solicitudes pendientes.');
        }
        try {
            $solicitud->rechazar(Auth::id());
            return redirect()->route('solicitudes.index')
                ->with('success', 'Solicitud rechazada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('solicitudes.index')
                ->with('error', 'Error al rechazar la solicitud: ' . $e->getMessage());
        }
    }
    public function entregar(Request $request, Solicitud $solicitud)
    {
        if ($solicitud->estado !== 'aprobada') {
            return redirect()->route('solicitudes.index')
                ->with('error', 'Solo se pueden entregar solicitudes aprobadas.');
        }
        try {
            $solicitud->entregar(Auth::id());
            return redirect()->route('solicitudes.index')
                ->with('success', 'Solicitud entregada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('solicitudes.index')
                ->with('error', 'Error al entregar la solicitud: ' . $e->getMessage());
        }
    }
    public function getInsumos(Request $request)
    {
        $query = Insumo::with(['unidadMedida', 'tipoInsumo', 'departamento']);
        if ($request->tipo_insumo_id) {
            $query->where('tipo_insumo_id', $request->tipo_insumo_id);
        }
        if ($request->departamento_id) {
            $query->where('departamento_id', $request->departamento_id);
        }
        $insumos = $query->orderBy('nombre_insumo')->get();
        return response()->json($insumos);
    }
    public function getAllInsumos(Request $request)
    {
        $insumos = Insumo::with(['unidadMedida', 'tipoInsumo', 'departamento'])
            ->orderBy('nombre_insumo')
            ->get();
        return response()->json($insumos);
    }
}
