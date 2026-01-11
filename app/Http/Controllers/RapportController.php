<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Fournisseur;
use App\Models\TypeBouteille;
use App\Models\Marque;
use App\Models\Transaction;
use App\Models\Stock;
use App\Exports\ClientsExport;
use App\Exports\FournisseursExport;
use App\Exports\StocksExport;
use App\Exports\TransactionsExport;
use App\Exports\TypesBouteillesExport;
use App\Exports\MarquesExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class RapportController extends Controller
{
    /**
     * Afficher la page d'exportation de rapports
     */
    public function index()
    {
        $rapports = [
            [
                'id' => 'clients',
                'nom' => 'Rapport Clients',
                'description' => 'Exporter la liste complète des clients avec leurs soldes et transactions',
                'formats' => ['csv', 'xlsx', 'pdf'],
                'icon' => 'bi-people',
                'count' => Client::count(),
            ],
            [
                'id' => 'fournisseurs',
                'nom' => 'Rapport Fournisseurs',
                'description' => 'Exporter la liste des fournisseurs avec leurs informations de contact',
                'formats' => ['csv', 'xlsx', 'pdf'],
                'icon' => 'bi-truck',
                'count' => Fournisseur::count(),
            ],
            [
                'id' => 'stocks',
                'nom' => 'Rapport Stocks',
                'description' => 'Exporter l\'état des stocks par type de bouteille avec les seuils d\'alerte',
                'formats' => ['csv', 'xlsx', 'pdf'],
                'icon' => 'bi-boxes',
                'count' => Stock::count(),
            ],
            [
                'id' => 'transactions',
                'nom' => 'Rapport Transactions',
                'description' => 'Exporter l\'historique des transactions par période',
                'formats' => ['csv', 'xlsx', 'pdf'],
                'icon' => 'bi-arrow-left-right',
                'count' => Transaction::count(),
            ],
            [
                'id' => 'types_bouteilles',
                'nom' => 'Rapport Types de Bouteilles',
                'description' => 'Exporter le catalogue des types de bouteilles par marque',
                'formats' => ['csv', 'xlsx'],
                'icon' => 'bi-box',
                'count' => TypeBouteille::count(),
            ],
            [
                'id' => 'marques',
                'nom' => 'Rapport Marques',
                'description' => 'Exporter la liste des marques avec le nombre de types',
                'formats' => ['csv', 'xlsx'],
                'icon' => 'bi-tag',
                'count' => Marque::count(),
            ],
        ];

        return view('rapports.index', compact('rapports'));
    }

    /**
     * Exporter les données en Excel ou CSV
     */
    public function export(Request $request)
    {
        $type = $request->query('type');
        $format = $request->query('format', 'csv');
        
        $filename = 'rapport_' . $type . '_' . now()->format('d-m-Y_H-i-s');

        try {
            return match ($type) {
                'clients' => Excel::download(new ClientsExport(), $filename . '.' . $format),
                'fournisseurs' => Excel::download(new FournisseursExport(), $filename . '.' . $format),
                'stocks' => Excel::download(new StocksExport(), $filename . '.' . $format),
                'transactions' => Excel::download(new TransactionsExport(), $filename . '.' . $format),
                'types_bouteilles' => Excel::download(new TypesBouteillesExport(), $filename . '.' . $format),
                'marques' => Excel::download(new MarquesExport(), $filename . '.' . $format),
                default => abort(404, 'Rapport non trouvé'),
            };
        } catch (\Exception $e) {
            return redirect()->route('rapports.index')->with('error', 'Erreur lors de l\'export: ' . $e->getMessage());
        }
    }

    /**
     * Exporter en PDF
     */
    public function exportPDF(Request $request)
    {
        $type = $request->query('type');
        $filename = 'rapport_' . $type . '_' . now()->format('d-m-Y_H-i-s');

        try {
            $pdf = app('dompdf.wrapper');
            
            $data = match ($type) {
                'clients' => ['records' => Client::all()],
                'fournisseurs' => ['records' => Fournisseur::all()],
                'stocks' => ['records' => Stock::with(['typeBouteille', 'typeBouteille.marque'])->get()],
                'transactions' => ['records' => Transaction::with('client')->latest()->get()],
                'types_bouteilles' => ['records' => TypeBouteille::with('marque')->get()],
                'marques' => ['records' => Marque::withCount('typesBouteilles')->get()],
                default => abort(404, 'Rapport non trouvé'),
            };

            $pdf->loadView('rapports.pdf.' . $type, $data)
                ->setPaper('a4', 'landscape');
            
            return $pdf->download($filename . '.pdf');
        } catch (\Exception $e) {
            return redirect()->route('rapports.index')->with('error', 'Erreur lors de l\'export PDF: ' . $e->getMessage());
        }
    }
}

