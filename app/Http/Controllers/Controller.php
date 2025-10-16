<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller extends BaseController
{
    use AuthorizesRequests;
    /**
     * Manejo centralizado de errores
     */
    protected function handleException(\Throwable $e, string $action, array $context = []): RedirectResponse
    {
        Log::error("Error en {$action}", array_merge($context, [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]));

        return back()->withErrors([
            'error' => 'Ocurrió un error inesperado. Por favor, inténtalo de nuevo.'
        ])->withInput();
    }

    /**
     * Ejecutar operación en transacción
     */
    protected function executeInTransaction(callable $callback)
    {
        return DB::transaction($callback);
    }

    /**
     * Validar que el usuario tenga permisos
     */
    protected function authorizeAction(string $permission): void
    {
        $this->authorize($permission);
    }

    /**
     * Log de acciones importantes
     */
    protected function logAction(string $action, array $data = []): void
    {
        Log::info($action, array_merge($data, [
            'user_id' => auth()->id(),
            'timestamp' => now()
        ]));
    }
}
