<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StoreSessionUserId
{
    /**
     * Handle an incoming request.
     * 
     * This middleware stores the authenticated user's RUN (user_id) in the sessions table.
     * Laravel's default session middleware doesn't populate the user_id column because
     * our User model uses 'run' as primary key instead of 'id'.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // After the request is processed, check if user is authenticated
        // and update the session record with the user_id
        if (Auth::check()) {
            $user = Auth::user();
            $sessionId = $request->session()->getId();
            
            // Update the sessions table to store the user's RUN in the user_id column
            DB::table('sessions')
                ->where('id', $sessionId)
                ->update([
                    'user_id' => $user->run,
                    'last_activity' => time(),
                ]);

            \Log::debug('Session user_id updated', [
                'session_id' => substr($sessionId, 0, 10) . '...',
                'user_id' => $user->run,
            ]);
        }

        return $response;
    }
}
