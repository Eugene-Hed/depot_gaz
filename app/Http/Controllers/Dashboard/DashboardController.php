<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use App\Models\Transaction;
use App\Models\Client;
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
            'total_month' => Transaction::where('created_at', '>=', $thisMonth)->sum('montant_net'),
            'total_year' => Transaction::where('created_at', '>=', $thisYear)->sum('montant_net'),
        ];

        // Stats clients
        $statsClients = [
            'actifs' => Client::where('statut', 'actif')->count(),
            'total' => Client::count(),
        ];

        // Transactions récentes
        $recentTransactions = Transaction::with('typeBouteille')
            ->latest()
            ->limit(10)
            ->get();

        // Top produits
        $topProducts = TypeBouteille::with('marque')
            ->withCount('transactions')
            ->orderByDesc('transactions_count')
            ->limit(5)
            ->get();

        // Ventes par jour (derniers 7 jours)
        $salesByDay = Transaction::selectRaw('DATE(created_at) as date, COUNT(*) as count, SUM(montant_net) as total')
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

        return view('dashboard', [
            'statsStock' => $statsStock,
            'statsTransactions' => $statsTransactions,
            'statsClients' => $statsClients,
            'recentTransactions' => $recentTransactions,
            'topProducts' => $topProducts,
            'salesByDay' => $salesByDay,
            'stockByType' => $stockByType,
            'alerts' => $alerts,
        ]);
    }
}

