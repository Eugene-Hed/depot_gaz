<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\TrialAndLicenseManager;
use Symfony\Component\HttpFoundation\Response;

class TrialAndLicenseMiddleware
{
    protected $manager;

    public function __construct(TrialAndLicenseManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Ne pas bloquer la page d'activation elle-même ou les fichiers de style
        if ($request->is('activation*') || $request->is('_debugbar*') || $request->is('livewire*')) {
            return $next($request);
        }

        // Si l'application est activée, on continue normalement
        if ($this->manager->isActivated()) {
            return $next($request);
        }

        // Si la période d'essai est expirée, on redirige vers l'activation
        if ($this->manager->isTrialExpired()) {
            return redirect()->route('activation.index');
        }

        return $next($request);
    }
}
