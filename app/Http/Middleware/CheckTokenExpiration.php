<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class CheckTokenExpiration
{
    /**
     * Middleware per bloccare token scaduti
     */
    public function handle(Request $request, Closure $next)
    {
        // Recupera il token Bearer dall'header Authorization
        $token = $request->bearerToken();

        if ($token) {
            $accessToken = PersonalAccessToken::findToken($token);

            // Se il token esiste ed è scaduto, restituisce errore 401
            if ($accessToken && $accessToken->expires_at && now()->greaterThan($accessToken->expires_at)) {
                return response()->json(['message' => 'Token expired'], 401);
            }
        }

        return $next($request);
    }
}
