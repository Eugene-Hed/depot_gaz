<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use App\Models\Transaction;
use App\Models\Client;
use App\Models\Paiement;
use App\Models\Commande;
use App\Models\TypeBouteille;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Stats stock
        $statsStock = [
            'total' => Stock::sum(DB::raw('quantite_pleine + quantite_vide')),
            'rupture' => Stock::whereHas('typeBouteille', function ($query) {
                $query->whereRaw('stocks.quantite_pleine < types_bouteilles.seuil_alerte');
            })->count(),
        ];

        // Stats transactions
        $today = today();
        $thisMonth = Carbon::now()->startOfMonth();
        $thisYear = Carbon::now()->startOfYear();

        $statsTransactions = [
            'today' => Transaction::whereDate('created_at', $today)->count(),
            'month' => Transaction::where('created_at', '>=', $thisMonth)->count(),
            'total_month' => Transaction::where('created_at', '>=', $thisMonth)
                ->where('type', 'vente')
                ->sum('montant_total'),
            'total_year' => Transaction::where('created_at', '>=', $thisYear)
                ->where('type', 'vente')
                ->sum('montant_total'),
        ];

        // Stats clients
        $statsClients = [
            'actifs' => Client::where('statut', 'actif')->count(),
            'total' => Client::count(),
            'avec_dette' => 0,  // Colonne solde_dette n'existe pas
            'total_dette' => 0, // Colonne solde_dette n'existe pas
        ];

        // Stats paiements (table paiements n'existe pas encore)
        $statsPaiements = [
            'today' => 0,
            'month' => 0,
            'pending' => 0,
        ];

        // Stats commandes (table commandes n'existe pas encore)
        $statsCommandes = [
            'total_month' => 0,
            'pending' => 0,
        ];

        // Transactions récentes
        $recentTransactions = Transaction::with('typeBouteille')
            ->latest()
            ->limit(10)
            ->get();

        // Paiements récents (table paiements n'existe pas)
        $recentPaiements = collect();

        // Top produits
        $topProducts = TypeBouteille::withCount('transactions')
            ->orderByDesc('transactions_count')
            ->limit(5)
            ->get();

        // Ventes par jour (derniers 7 jours)
        $salesByDay = Transaction::selectRaw('DATE(created_at) as date, COUNT(*) as count, SUM(montant_total) as total')
            ->where('type', 'vente')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Stock par type (pour graphique)
        $stockByType = TypeBouteille::with('stock')
            ->get()
            ->map(function ($type) {
                return [
                    'name' => $type->nom,
                    'pleine' => $type->stock->quantite_pleine ?? 0,
                    'vide' => $type->stock->quantite_vide ?? 0,
                ];
            });

        // Modes de paiement (table paiements n'existe pas)
        $paymentModes = collect();

        // Alertes
        $alerts = [];
        
        // Stocks faibles
        Stock::with('typeBouteille')
            ->get()
            ->filter(function ($stock) {
                return $stock->quantite_pleine < $stock->typeBouteille->seuil_alerte;
            })
            ->each(function ($stock) use (&$alerts) {
                $alerts[] = [
                    'type' => 'warning',
                    'message' => "Stock faible: {$stock->typeBouteille->nom} ({$stock->quantite_pleine} restants)",
                    'icon' => 'exclamation-circle'
                ];
            });

        // Commandes non livrées depuis plus de 7 jours (table commandes n'existe pas)
        // À implémenter après création de la table commandes

        // Paiements en attente (table paiements n'existe pas)
        // À implémenter après création de la table paiements

        // Clients avec dettes importantes
        // Note: Colonne solde_dette n'existe pas dans la table clients
        // À implémenter après ajout de la migration

        return view('dashboard', [
            'statsStock' => $statsStock,
            'statsTransactions' => $statsTransactions,
            'statsClients' => $statsClients,
            'statsPaiements' => $statsPaiements,
            'statsCommandes' => $statsCommandes,
            'recentTransactions' => $recentTransactions,
            'recentPaiements' => $recentPaiements,
            'topProducts' => $topProducts,
            'salesByDay' => $salesByDay,
            'stockByType' => $stockByType,
            'paymentModes' => $paymentModes,
            'alerts' => $alerts,
        ]);
    }
}
