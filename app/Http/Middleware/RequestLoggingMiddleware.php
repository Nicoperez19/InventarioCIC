<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
class RequestLoggingMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);
        // Log de entrada
        Log::info('Request iniciado', [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'path' => $request->path(),
            'route_name' => $request->route()?->getName(),
            'user_id' => auth()->id(),
            'user_run' => auth()->user()->run ?? 'N/A',
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'headers' => $request->headers->all(),
            'query_params' => $request->query->all(),
            'request_data' => $request->except(['password', 'password_confirmation', '_token']),
            'timestamp' => now()->toISOString()
        ]);
        try {
            $response = $next($request);
            $endTime = microtime(true);
            $duration = round(($endTime - $startTime) * 1000, 2); // en milisegundos
            // Log de salida exitosa
            Log::info('Request completado exitosamente', [
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'status_code' => $response->getStatusCode(),
                'duration_ms' => $duration,
                'user_id' => auth()->id(),
                'response_size' => strlen($response->getContent()),
                'timestamp' => now()->toISOString()
            ]);
            return $response;
        } catch (\Exception $e) {
            $endTime = microtime(true);
            $duration = round(($endTime - $startTime) * 1000, 2);
            // Log de error
            Log::error('Request falló con excepción', [
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'path' => $request->path(),
                'route_name' => $request->route()?->getName(),
                'user_id' => auth()->id(),
                'user_run' => auth()->user()->run ?? 'N/A',
                'ip' => $request->ip(),
                'duration_ms' => $duration,
                'exception' => [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ],
                'timestamp' => now()->toISOString()
            ]);
            throw $e;
        }
    }
}
