<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Usage: ->middleware('role:admin,analyst')
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(403);
        }

        $roleName = $user->role?->name;

        if (! $roleName || ! in_array($roleName, $roles, true)) {
            abort(403);
        }

        return $next($request);
    }
}

