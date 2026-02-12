<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();
        if (!$user) {
            abort(403);
        }

        $userRole = strtolower((string) $user->role);
        $allowed = array_map('strtolower', array_map('trim', explode(',', $role)));
        if (!in_array($userRole, $allowed, true)) {
            abort(403);
        }

        return $next($request);
    }
}
