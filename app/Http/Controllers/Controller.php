<?php
namespace App\Http\Controllers;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
abstract class Controller extends BaseController
{
    use AuthorizesRequests;
    protected function handleException(\Throwable $e, string $action, array $context = []): RedirectResponse
    {
        Log::error("Error en {$action}", array_merge($context, [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]));
        return back()->withErrors([
            'error' => 'Ocurrió un error inesperado. Por favor, inténtalo de nuevo.',
        ])->withInput();
    }
    protected function executeInTransaction(callable $callback)
    {
        return DB::transaction($callback);
    }
    protected function authorizeAction(string $permission): void
    {
        $this->authorize($permission);
    }
    protected function logAction(string $action, array $data = []): void
    {
        Log::info($action, array_merge($data, [
            'user_id' => auth()->id(),
            'timestamp' => now(),
        ]));
    }
}
