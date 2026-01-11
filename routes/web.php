<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\Transactions\TransactionController;
use App\Http\Controllers\Clients\ClientController;
use App\Http\Controllers\MarqueController;
use App\Http\Controllers\TypeBouteilleController;
use App\Http\Controllers\FournisseurController;
use App\Http\Controllers\RapportController;
use App\Http\Controllers\Paiements\PaiementController;
use App\Http\Controllers\Commandes\DetailCommandeController;

// Routes publiques
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Routes authentifiées
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    // Stocks
    Route::prefix('stocks')->name('stocks.')->group(function () {
        Route::get('/', [StockController::class, 'index'])->name('index');
        Route::get('/create', [StockController::class, 'create'])->name('create');
        Route::post('/', [StockController::class, 'store'])->name('store');
        Route::get('/{stock}', [StockController::class, 'show'])->name('show');
        Route::get('/{stock}/edit', [StockController::class, 'edit'])->name('edit');
        Route::put('/{stock}', [StockController::class, 'update'])->name('update');
    });

    // Transactions
    Route::prefix('transactions')->name('transactions.')->group(function () {
        Route::get('/', [TransactionController::class, 'index'])->name('index');
        Route::get('/create', [TransactionController::class, 'create'])->name('create');
        Route::post('/', [TransactionController::class, 'store'])->name('store');
        Route::get('/{transaction}', [TransactionController::class, 'show'])->name('show');
    });

    // Clients
    Route::prefix('clients')->name('clients.')->group(function () {
        Route::get('/', [ClientController::class, 'index'])->name('index');
        Route::get('/create', [ClientController::class, 'create'])->name('create');
        Route::post('/', [ClientController::class, 'store'])->name('store');
        Route::get('/{client}/edit', [ClientController::class, 'edit'])->name('edit');
        Route::put('/{client}', [ClientController::class, 'update'])->name('update');
        Route::delete('/{client}', [ClientController::class, 'destroy'])->name('destroy');
    });

    // Marques
    Route::prefix('marques')->name('marques.')->group(function () {
        Route::get('/', [MarqueController::class, 'index'])->name('index');
        Route::get('/create', [MarqueController::class, 'create'])->name('create');
        Route::post('/', [MarqueController::class, 'store'])->name('store');
        Route::get('/{marque}/edit', [MarqueController::class, 'edit'])->name('edit');
        Route::put('/{marque}', [MarqueController::class, 'update'])->name('update');
        Route::delete('/{marque}', [MarqueController::class, 'destroy'])->name('destroy');
    });

    // Types de bouteilles
    Route::prefix('types-bouteilles')->name('types-bouteilles.')->group(function () {
        Route::get('/', [TypeBouteilleController::class, 'index'])->name('index');
        Route::get('/create', [TypeBouteilleController::class, 'create'])->name('create');
        Route::post('/', [TypeBouteilleController::class, 'store'])->name('store');
        Route::get('/{typeBouteille}/edit', [TypeBouteilleController::class, 'edit'])->name('edit');
        Route::put('/{typeBouteille}', [TypeBouteilleController::class, 'update'])->name('update');
        Route::delete('/{typeBouteille}', [TypeBouteilleController::class, 'destroy'])->name('destroy');
    });

    // Fournisseurs
    Route::prefix('fournisseurs')->name('fournisseurs.')->group(function () {
        Route::get('/', [FournisseurController::class, 'index'])->name('index');
        Route::get('/create', [FournisseurController::class, 'create'])->name('create');
        Route::post('/', [FournisseurController::class, 'store'])->name('store');
        Route::get('/{fournisseur}/edit', [FournisseurController::class, 'edit'])->name('edit');
        Route::put('/{fournisseur}', [FournisseurController::class, 'update'])->name('update');
        Route::delete('/{fournisseur}', [FournisseurController::class, 'destroy'])->name('destroy');
    });

    // Paiements
    Route::prefix('paiements')->name('paiements.')->group(function () {
        Route::get('/', [PaiementController::class, 'index'])->name('index');
        Route::get('/create', [PaiementController::class, 'create'])->name('create');
        Route::post('/', [PaiementController::class, 'store'])->name('store');
        Route::get('/{paiement}', [PaiementController::class, 'show'])->name('show');
        Route::get('/{paiement}/edit', [PaiementController::class, 'edit'])->name('edit');
        Route::put('/{paiement}', [PaiementController::class, 'update'])->name('update');
        Route::delete('/{paiement}', [PaiementController::class, 'destroy'])->name('destroy');
        Route::get('/client/{client}', [PaiementController::class, 'byClient'])->name('by-client');
    });

    // Détails de Commandes
    Route::prefix('commandes/{commande}/details')->name('commandes.details.')->group(function () {
        Route::get('/', [DetailCommandeController::class, 'index'])->name('index');
        Route::get('/create', [DetailCommandeController::class, 'create'])->name('create');
        Route::post('/', [DetailCommandeController::class, 'store'])->name('store');
        Route::get('/{detail}/edit', [DetailCommandeController::class, 'edit'])->name('edit');
        Route::put('/{detail}', [DetailCommandeController::class, 'update'])->name('update');
        Route::delete('/{detail}', [DetailCommandeController::class, 'destroy'])->name('destroy');
        Route::post('/{detail}/livraison', [DetailCommandeController::class, 'updateLivraison'])->name('update-livraison');
    });

    // Rapports et Exportations
    Route::prefix('rapports')->name('rapports.')->group(function () {
        Route::get('/', [RapportController::class, 'index'])->name('index');
        Route::get('/export', [RapportController::class, 'export'])->name('export');
        Route::get('/export-pdf', [RapportController::class, 'exportPDF'])->name('export-pdf');
    });
});

