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
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Permettre l'accès à la page d'expiration
        if ($request->path() === 'trial-expired') {
            return $next($request);
        }

        // Vérifier si la période de test est expirée
        if (TrialManager::isTrialExpired()) {
            return response()->view('trial.expired', [
                'expired_on' => TrialManager::getTrialEndDate()
            ], 403);
        }

        return $next($request);
    }
}
