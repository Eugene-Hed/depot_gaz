<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\TrialManager;

class ActivationController extends Controller
{
    /**
     * Affiche la page d'activation
     */
    public function index()
    {
        // Si déjà activé, on redirige vers l'accueil
        if (TrialManager::isActivated()) {
            return redirect('/');
        }

        $installID = TrialManager::getInstallID();
        $isTrialActive = TrialManager::isTrialActive();
        $daysRemaining = TrialManager::getRemainingDays();

        return view('activation.index', compact('installID', 'isTrialActive', 'daysRemaining'));
    }

    /**
     * Traite la saisie de la clé de licence
     */
    public function activate(Request $request)
    {
        $request->validate([
            'license_key' => 'required|string',
        ]);

        if (TrialManager::activate($request->license_key)) {
            return redirect('/')->with('success', 'Application activée avec succès ! Merci de votre confiance.');
        }

        return back()->with('error', 'Clé de licence invalide. Veuillez vérifier votre saisie ou contacter l\'administrateur.');
    }
}
