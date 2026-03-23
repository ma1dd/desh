<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UpdateLastSeenAt
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $user = Auth::user();
        if (! $user) {
            return $response;
        }

        // Обновляем не чаще 1 раза в минуту, чтобы не писать в БД на каждый запрос.
        $shouldUpdate = ! $user->last_seen_at || $user->last_seen_at->lt(now()->subMinute());

        if ($shouldUpdate) {
            $user->forceFill(['last_seen_at' => now()])->save();
        }

        return $response;
    }
}

