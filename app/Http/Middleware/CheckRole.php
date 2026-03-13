<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = auth('api')->user();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Non authentifié'
            ], 401);
        }

        // Vérifier si l'utilisateur a l'un des rôles requis
        if (!in_array($user->role, $roles)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Accès refusé. Rôle insuffisant.',
                'required_roles' => $roles,
                'your_role' => $user->role
            ], 403);
        }

        return $next($request);
    }
}
