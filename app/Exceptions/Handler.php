<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $exception)
    {
        // Manejar errores 403 (Forbidden) de manera personalizada
        if ($exception instanceof AuthorizationException) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permisos para realizar esta acción',
                    'error' => 'Forbidden'
                ], 403);
            }

            // Para requests web, mostrar una página de error 403
            return response()->view('errors.403', [
                'message' => 'No tienes permisos para acceder a esta página'
            ], 403);
        }

        // Manejar errores 404
        if ($exception instanceof NotFoundHttpException) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Recurso no encontrado',
                    'error' => 'Not Found'
                ], 404);
            }
        }

        // Manejar errores de validación
        if ($exception instanceof ValidationException) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Datos de validación incorrectos',
                    'errors' => $exception->errors()
                ], 422);
            }
        }

        // Manejar errores de throttling
        if ($exception instanceof ThrottleRequestsException) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Demasiadas solicitudes. Inténtalo más tarde.',
                    'error' => 'Too Many Requests'
                ], 429);
            }
        }

        return parent::render($request, $exception);
    }
}


