<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'L\'identifiant est requis',
            'password.required' => 'Le mot de passe est requis',
        ]);

        // Chercher l'utilisateur par username
        $user = User::where('username', $credentials['username'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors([
                'username' => 'Les identifiants sont invalides ou cet utilisateur est inactif.',
            ])->onlyInput('username');
        }

        if ($user->statut !== 'actif') {
            return back()->withErrors([
                'username' => 'Ce compte a été désactivé.',
            ])->onlyInput('username');
        }

        // Mise à jour du dernier login
        $user->update(['dernier_login' => now()]);

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->route('dashboard')->with('success', 'Bienvenue ' . $user->nom_complet);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Vous avez été déconnecté.');
    }
}
