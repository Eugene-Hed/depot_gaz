@extends('layouts.app')

@section('title', 'Exportation de Rapports')

@section('content')
<div class="container-fluid py-4 dashboard-corporate">
    <!-- Header -->
    <div class="row mb-5 align-items-center">
        <div class="col-md-8">
            <h1 class="h3 fw-bold text-navy mb-1 text-uppercase ls-wide">Centre de Rapports</h1>
            <p class="text-secondary small mb-0">Analysez vos performances et exportez les registres consolidés</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <div class="date-badge">
                <i class="bi bi-clock-history me-2"></i>
                <small>Données à jour</small>
            </div>
        </div>
    </div>

    <!-- Visual Summary (Quick Stats) -->
    <div class="row g-4 mb-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-navy text-white p-4">
                    <h6 class="mb-0 fw-bold text-uppercase ls-wide small">Répartition des Revenus par Type d'Opération</h6>
                </div>
                <div class="card-body p-4 bg-white">
                    <div class="row g-4 align-items-center">
                        <div class="col-lg-12">
                            <div class="d-flex flex-wrap gap-4">
                                @php
                                    $types = [
                                        'achat_simple' => ['label' => 'Achat Simple', 'color' => '#3b82f6'],
                                        'echange_simple' => ['label' => 'Échange Standard', 'color' => '#10b981'],
                                        'echange_type' => ['label' => 'Échange Spécifiant', 'color' => '#f59e0b'],
                                        'achat_gros' => ['label' => 'Vente en Gros', 'color' => '#6366f1'],
                                    ];
                                @endphp

                                @foreach($types as $key => $meta)
                                    @if(isset($statsRevenus[$key]))
                                        <div class="flex-grow-1 p-3 rounded-3 border bg-light-subtle shadow-hover" style="border-left: 4px solid {{ $meta['color'] }} !important;">
                                            <small class="text-muted fw-bold text-uppercase font-2xs d-block mb-1">{{ $meta['label'] }}</small>
                                            <h4 class="fw-bold text-navy mb-0">{{ number_format($statsRevenus[$key], 0, ',', ' ') }} <small class="text-muted small">F</small></h4>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Grid -->
    <div class="row g-4">
        <div class="col-lg-12">
            <h6 class="fw-bold text-navy mb-4 text-uppercase ls-wide small"><i class="bi bi-download me-2"></i> Documents Disponibles à l'Exportation</h6>
            <div class="row g-4">
                @foreach($rapports as $rapport)
                    <div class="col-xl-4 col-md-6">
                        <div class="card border-0 shadow-sm h-100 rounded-4 ranking-item">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="avatar-circle bg-light-navy text-navy me-3">
                                        <i class="bi {{ $rapport['icon'] }}"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold text-navy mb-0">{{ $rapport['nom'] }}</h6>
                                        <small class="text-muted font-2xs">{{ $rapport['count'] }} entrées au total</small>
                                    </div>
                                </div>
                                <p class="text-secondary small mb-4" style="height: 40px; overflow: hidden;">
                                    {{ $rapport['description'] }}
                                </p>
                                
                                <div class="d-flex gap-2">
                                    @foreach($rapport['formats'] as $format)
                                        @if($format === 'csv')
                                            <a href="{{ route('rapports.export', ['type' => $rapport['id'], 'format' => 'csv']) }}" 
                                               class="btn btn-sm btn-outline-navy flex-grow-1 rounded-pill fw-bold">
                                                <i class="bi bi-filetype-csv me-1"></i> CSV
                                            </a>
                                        @elseif($format === 'xlsx')
                                            <a href="{{ route('rapports.export', ['type' => $rapport['id'], 'format' => 'xlsx']) }}" 
                                               class="btn btn-sm btn-outline-navy flex-grow-1 rounded-pill fw-bold">
                                                <i class="bi bi-filetype-xlsx me-1"></i> EXCEL
                                            </a>
                                        @elseif($format === 'pdf')
                                            <a href="{{ route('rapports.export-pdf', ['type' => $rapport['id']]) }}" 
                                               class="btn btn-sm btn-outline-navy flex-grow-1 rounded-pill fw-bold">
                                                <i class="bi bi-filetype-pdf me-1"></i> PDF
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
</div>

<style>
    :root {
        --navy: #0f172a;
        --light-navy: rgba(15, 23, 42, 0.05);
    }
    .text-navy { color: var(--navy); }
    .bg-navy { background-color: var(--navy) !important; color: white; }
    .ls-wide { letter-spacing: 2px; }
    .font-2xs { font-size: 0.65rem; }
    .italic { font-style: italic; }
    
    .dashboard-corporate {
        background-color: #f8fafc;
        min-height: 100vh;
    }

    .date-badge {
        background: white;
        padding: 8px 15px;
        border-radius: 50px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        display: inline-flex;
        align-items: center;
        color: #64748b;
    }

    .avatar-circle {
        width: 48px; height: 48px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;
    }

    .shadow-hover {
        transition: all 0.2s ease;
    }
    .shadow-hover:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
        background-color: white !important;
    }

    .ranking-item {
        transition: transform 0.2s;
    }
    .ranking-item:hover { transform: translateY(-5px); }

    .btn-outline-navy {
        border: 1px solid var(--navy);
        color: var(--navy);
        font-size: 0.75rem;
    }
    .btn-outline-navy:hover {
        background-color: var(--navy);
        color: white;
    }
</style>
@endsection
