<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSolicitudRequest;
use App\Models\Detalle_Solicitud;
use App\Models\Producto;
use App\Models\Solicitud;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SolicitudController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(): View
    {
        $this->authorizeAction('view-requests');

        $solicitudes = Solicitud::with(['usuario.departamento', 'detalleSolicitudes.producto'])
            ->orderBy('fecha_solicitud', 'desc')
            ->paginate(20);

        return view('layouts.solicitud.solicitud_index', compact('solicitudes'));
    }

    public function create(): View
    {
        $this->authorizeAction('create-requests');

        $productos = Producto::with('unidad')
            ->where('stock_actual', '>', 0)
            ->orderBy('nombre_producto')
            ->get();

        return view('layouts.solicitud.solicitud_create', compact('productos'));
    }

    public function store(StoreSolicitudRequest $request): RedirectResponse
    {
        $this->authorizeAction('create-requests');

        try {
            $this->logAction('Creando solicitud', [
                'usuario_id' => auth()->id(),
            ]);

            $validated = $request->validated();

            return $this->executeInTransaction(function () use ($validated) {
                // Crear solicitud
                $solicitud = Solicitud::create([
                    'id_solicitud' => uniqid('SOL_'),
                    'fecha_solicitud' => now(),
                    'estado_solicitud' => Solicitud::ESTADO_PENDIENTE,
                    'observaciones' => $validated['observaciones'],
                    'id_usuario' => auth()->id(),
                ]);

                // Crear detalles de solicitud
                foreach ($validated['productos'] as $productoData) {
                    $producto = Producto::find($productoData['id_producto']);

                    if (! $producto->canReduceStock($productoData['cantidad'])) {
                        throw new \Exception("No hay suficiente stock para el producto: {$producto->nombre_producto}");
                    }

                    Detalle_Solicitud::create([
                        'id_detalle_solicitud' => uniqid('DET_'),
                        'id_solicitud' => $solicitud->id_solicitud,
                        'id_producto' => $productoData['id_producto'],
                        'cantidad_solicitud' => $productoData['cantidad'],
                    ]);
                }

                $this->logAction('Solicitud creada exitosamente', [
                    'solicitud_id' => $solicitud->id_solicitud,
                    'productos_count' => count($validated['productos']),
                ]);

                return redirect()->route('solicitudes')->with('status', 'Solicitud creada exitosamente.');
            });

        } catch (\Throwable $e) {
            return $this->handleException($e, 'SolicitudController@store', [
                'usuario_id' => auth()->id(),
            ]);
        }
    }

    public function show(Solicitud $solicitud): View
    {
        $this->authorize('view', $solicitud);

        $solicitud->load(['usuario.departamento', 'detalleSolicitudes.producto.unidad']);

        return view('layouts.solicitud.solicitud_show', compact('solicitud'));
    }

    public function approve(Solicitud $solicitud): RedirectResponse
    {
        $this->authorizeAction('approve-requests');

        try {
            $this->logAction('Aprobando solicitud', [
                'solicitud_id' => $solicitud->id_solicitud,
            ]);

            return $this->executeInTransaction(function () use ($solicitud) {
                if (! $solicitud->canBeApproved()) {
                    return redirect()->back()->withErrors([
                        'error' => 'La solicitud no puede ser aprobada en su estado actual.',
                    ]);
                }

                // Verificar stock disponible
                foreach ($solicitud->detalleSolicitudes as $detalle) {
                    if (! $detalle->canBeFulfilled()) {
                        return redirect()->back()->withErrors([
                            'error' => "No hay suficiente stock para el producto: {$detalle->producto->nombre_producto}",
                        ]);
                    }
                }

                // Aprobar solicitud
                $solicitud->approve();

                // Reducir stock
                foreach ($solicitud->detalleSolicitudes as $detalle) {
                    $detalle->fulfill();
                }

                $this->logAction('Solicitud aprobada exitosamente', [
                    'solicitud_id' => $solicitud->id_solicitud,
                ]);

                return redirect()->back()->with('status', 'Solicitud aprobada exitosamente.');
            });

        } catch (\Throwable $e) {
            return $this->handleException($e, 'SolicitudController@approve', [
                'solicitud_id' => $solicitud->id_solicitud,
            ]);
        }
    }

    public function reject(Solicitud $solicitud): RedirectResponse
    {
        $this->authorizeAction('reject-requests');

        try {
            $this->logAction('Rechazando solicitud', [
                'solicitud_id' => $solicitud->id_solicitud,
            ]);

            if (! $solicitud->canBeRejected()) {
                return redirect()->back()->withErrors([
                    'error' => 'La solicitud no puede ser rechazada en su estado actual.',
                ]);
            }

            $solicitud->reject();

            $this->logAction('Solicitud rechazada exitosamente', [
                'solicitud_id' => $solicitud->id_solicitud,
            ]);

            return redirect()->back()->with('status', 'Solicitud rechazada exitosamente.');

        } catch (\Throwable $e) {
            return $this->handleException($e, 'SolicitudController@reject', [
                'solicitud_id' => $solicitud->id_solicitud,
            ]);
        }
    }

    public function deliver(Solicitud $solicitud): RedirectResponse
    {
        $this->authorizeAction('deliver-requests');

        try {
            $this->logAction('Entregando solicitud', [
                'solicitud_id' => $solicitud->id_solicitud,
            ]);

            if (! $solicitud->canBeDelivered()) {
                return redirect()->back()->withErrors([
                    'error' => 'La solicitud no puede ser entregada en su estado actual.',
                ]);
            }

            $solicitud->deliver();

            $this->logAction('Solicitud entregada exitosamente', [
                'solicitud_id' => $solicitud->id_solicitud,
            ]);

            return redirect()->back()->with('status', 'Solicitud entregada exitosamente.');

        } catch (\Throwable $e) {
            return $this->handleException($e, 'SolicitudController@deliver', [
                'solicitud_id' => $solicitud->id_solicitud,
            ]);
        }
    }

    public function destroy(Solicitud $solicitud): RedirectResponse
    {
        $this->authorize('delete', $solicitud);

        try {
            $this->logAction('Eliminando solicitud', [
                'solicitud_id' => $solicitud->id_solicitud,
            ]);

            if (! $solicitud->isPendiente()) {
                return redirect()->back()->withErrors([
                    'error' => 'Solo se pueden eliminar solicitudes pendientes.',
                ]);
            }

            $solicitud->delete();

            $this->logAction('Solicitud eliminada exitosamente', [
                'solicitud_id' => $solicitud->id_solicitud,
            ]);

            return redirect()->back()->with('status', 'Solicitud eliminada exitosamente.');

        } catch (\Throwable $e) {
            return $this->handleException($e, 'SolicitudController@destroy', [
                'solicitud_id' => $solicitud->id_solicitud,
            ]);
        }
    }

    /**
     * Obtener solicitudes pendientes
     */
    public function pending(): View
    {
        $this->authorizeAction('view-pending-requests');

        $solicitudes = Solicitud::getPendientes();

        return view('layouts.solicitud.pending', compact('solicitudes'));
    }

    /**
     * Obtener mis solicitudes
     */
    public function myRequests(): View
    {
        $solicitudes = Solicitud::getByUsuario(auth()->id());

        return view('layouts.solicitud.my_requests', compact('solicitudes'));
    }
}
