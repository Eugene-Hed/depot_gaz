<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Helpers\TrialManager;

class CheckTrialExpiration
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Autoriser l'accès aux routes d'activation
        if ($request->is('activate*') || $request->is('api*')) {
            return $next($request);
        }

        // Si l'application est activée, on continue
        if (TrialManager::isActivated()) {
            return $next($request);
        }

        // Si la période de test est expirée, on bloque
        if (TrialManager::isBlocked()) {
            return redirect()->route('activation.index');
        }

        return $next($request);
    }
}
