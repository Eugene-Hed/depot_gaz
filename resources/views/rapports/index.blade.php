@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h1 class="d-flex align-items-center gap-3">
                <i class="bi bi-file-earmark-arrow-down" style="font-size: 2rem; color: #6366f1;"></i>
                <span>Exportation de Rapports</span>
            </h1>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm bg-info-subtle">
                <div class="card-body">
                    <h5 class="card-title fw-bold">
                        <i class="bi bi-info-circle"></i> À propos
                    </h5>
                    <p class="text-muted small mb-3">
                        Exportez les données de votre système en différents formats pour analyse et archivage.
                    </p>
                    <hr class="my-3">
                    <h6 class="fw-bold mb-2">Formats disponibles:</h6>
                    <ul class="list-unstyled small text-muted">
                        <li><i class="bi bi-file-earmark-spreadsheet"></i> <strong>CSV</strong> - Compatible Excel</li>
                        <li><i class="bi bi-file-earmark-spreadsheet"></i> <strong>XLSX</strong> - Format Microsoft</li>
                        <li><i class="bi bi-file-earmark-pdf"></i> <strong>PDF</strong> - Format universel</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            <div class="row">
                @foreach($rapports as $rapport)
                    <div class="col-md-6 mb-4">
                        <div class="card border-0 shadow-sm h-100 position-relative overflow-hidden" style="transition: all 0.3s ease;">
                            <div class="card-body">
                                <div class="d-flex gap-3 mb-3">
                                    <div class="display-6 text-primary">
                                        <i class="bi {{ $rapport['icon'] }}"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="card-title fw-bold mb-0">{{ $rapport['nom'] }}</h5>
                                        <small class="text-muted">{{ $rapport['count'] }} enregistrement(s)</small>
                                    </div>
                                </div>
                                <p class="card-text text-muted small mb-3">
                                    {{ $rapport['description'] }}
                                </p>
                                
                                <div class="btn-group w-100" role="group">
                                    @foreach($rapport['formats'] as $format)
                                        @if($format === 'csv')
                                            <a href="{{ route('rapports.export', ['type' => $rapport['id'], 'format' => 'csv']) }}" 
                                               class="btn btn-outline-primary btn-sm" 
                                               title="Télécharger en CSV">
                                                <i class="bi bi-download"></i> CSV
                                            </a>
                                        @elseif($format === 'xlsx')
                                            <a href="{{ route('rapports.export', ['type' => $rapport['id'], 'format' => 'xlsx']) }}" 
                                               class="btn btn-outline-success btn-sm"
                                               title="Télécharger en XLSX">
                                                <i class="bi bi-download"></i> XLSX
                                            </a>
                                        @elseif($format === 'pdf')
                                            <a href="{{ route('rapports.export-pdf', ['type' => $rapport['id']]) }}" 
                                               class="btn btn-outline-danger btn-sm"
                                               title="Télécharger en PDF">
                                                <i class="bi bi-download"></i> PDF
                                            </a>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Infos pratiques -->
    <div class="row mt-5">
        <div class="col">
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body">
                    <h5 class="card-title fw-bold mb-3">
                        <i class="bi bi-lightbulb"></i> Conseils d'utilisation
                    </h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong class="text-primary">Format CSV</strong>
                                <p class="text-muted small mb-0">Universel, compatible avec tous les tableurs. Idéal pour l'archivage.</p>
                            </div>
                            <div class="mb-3">
                                <strong class="text-success">Format XLSX</strong>
                                <p class="text-muted small mb-0">Microsoft Excel natif. Parfait pour les analyses détaillées.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong class="text-danger">Format PDF</strong>
                                <p class="text-muted small mb-0">Lisible sur tout appareil. Idéal pour imprimer ou partager.</p>
                            </div>
                            <div class="mb-3">
                                <strong class="text-info">Formatage</strong>
                                <p class="text-muted small mb-0">Les dates et montants sont automatiquement formatés selon la locale.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        border-radius: 0.5rem;
    }
    
    .card:hover {
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.15) !important;
        transform: translateY(-2px);
    }
    
    .display-6 {
        line-height: 1;
    }
</style>
@endsection
