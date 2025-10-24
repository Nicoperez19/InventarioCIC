<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class DebugPermissionsMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        
        if ($user) {
            Log::info('Debug Permissions', [
                'user_run' => $user->run,
                'user_roles' => $user->getRoleNames()->toArray(),
                'user_permissions' => $user->getAllPermissions()->pluck('name')->toArray(),
                'route' => $request->route()?->getName(),
                'url' => $request->fullUrl(),
                'can_view_providers' => $user->can('view-providers'),
                'can_view_invoices' => $user->can('view-invoices'),
            ]);
        } else {
            Log::info('Debug Permissions - No user authenticated', [
                'route' => $request->route()?->getName(),
                'url' => $request->fullUrl(),
            ]);
        }

        return $next($request);
    }
}


