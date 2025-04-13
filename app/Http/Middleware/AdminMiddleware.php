<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Gestisce l'accesso solo per utenti amministratori.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || ! $request->user()->is_admin) {
            return response()->json(['message' => 'Accesso non autorizzato.'], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
