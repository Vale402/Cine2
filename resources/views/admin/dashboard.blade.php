@extends('layouts.admin')

@section('titulo', 'Dashboard')

@section('contenido')

{{-- Topbar --}}
<div class="admin-topbar">
    <div>
        <h1><i class="bi bi-speedometer2"></i> Dashboard</h1>
        <p class="text-cine-muted mb-0" style="font-size:0.9rem;">
            Resumen general del sistema — {{ now()->format('d/m/Y') }}
        </p>
    </div>
</div>

{{-- Stat Cards --}}
<div class="stats-grid animate-fade-in-up">
    <div class="stat-card stat-card-primary">
        <div class="d-flex align-items-center gap-3">
            <div class="stat-icon stat-icon-primary">
                <i class="bi bi-film"></i>
            </div>
            <div>
                <div class="stat-value">{{ $stats->total_peliculas }}</div>
                <div class="stat-label">Películas</div>
            </div>
        </div>
    </div>

    <div class="stat-card stat-card-info">
        <div class="d-flex align-items-center gap-3">
            <div class="stat-icon stat-icon-info">
                <i class="bi bi-building"></i>
            </div>
            <div>
                <div class="stat-value">{{ $stats->total_salas }}</div>
                <div class="stat-label">Salas</div>
            </div>
        </div>
    </div>

    <div class="stat-card stat-card-gold">
        <div class="d-flex align-items-center gap-3">
            <div class="stat-icon stat-icon-gold">
                <i class="bi bi-ticket-perforated"></i>
            </div>
            <div>
                <div class="stat-value">{{ $stats->total_boletos_vendidos }}</div>
                <div class="stat-label">Boletos Vendidos</div>
            </div>
        </div>
    </div>

    <div class="stat-card stat-card-success">
        <div class="d-flex align-items-center gap-3">
            <div class="stat-icon stat-icon-success">
                <i class="bi bi-cash-stack"></i>
            </div>
            <div>
                <div class="stat-value">${{ number_format($stats->ingresos_totales, 0) }}</div>
                <div class="stat-label">Ingresos Totales</div>
            </div>
        </div>
    </div>
</div>

{{-- Quick Stats Row --}}
<div class="admin-panel animate-fade-in-up delay-1">
    <div class="admin-panel-header">
        <h3><i class="bi bi-lightning-charge"></i> Resumen Rápido</h3>
    </div>
    <div class="admin-panel-body">
        <div class="quick-stats">
            <div class="quick-stat-item">
                <div class="quick-stat-value text-cine-primary">{{ $stats->funciones_hoy }}</div>
                <div class="quick-stat-label">Funciones Hoy</div>
            </div>
            <div class="quick-stat-item">
                <div class="quick-stat-value text-cine-gold">{{ $stats->boletos_hoy }}</div>
                <div class="quick-stat-label">Boletos Hoy</div>
            </div>
            <div class="quick-stat-item">
                <div class="quick-stat-value text-cine-success">${{ number_format($stats->ingresos_hoy, 0) }}</div>
                <div class="quick-stat-label">Ingresos Hoy</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- Ventas por Película --}}
    <div class="col-lg-7 animate-fade-in-up delay-2">
        <div class="admin-panel h-100">
            <div class="admin-panel-header">
                <h3><i class="bi bi-bar-chart"></i> Ventas por Película</h3>
            </div>
            <div class="admin-panel-body p-0">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Película</th>
                            <th>Género</th>
                            <th class="text-center">Boletos</th>
                            <th class="text-end">Ingresos</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ventasPeliculas as $vp)
                        <tr>
                            <td>
                                <span class="fw-semibold text-cine-text">{{ $vp->titulo }}</span>
                            </td>
                            <td>
                                <span class="badge-cine badge-cine-genre">{{ $vp->genero }}</span>
                            </td>
                            <td class="text-center">
                                <span class="fw-bold text-cine-text">{{ $vp->boletos_vendidos }}</span>
                            </td>
                            <td class="text-end">
                                <span class="text-cine-success fw-bold">${{ number_format($vp->ingresos_totales, 2) }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Ventas Diarias --}}
    <div class="col-lg-5 animate-fade-in-up delay-3">
        <div class="admin-panel h-100">
            <div class="admin-panel-header">
                <h3><i class="bi bi-graph-up"></i> Ventas Recientes</h3>
            </div>
            <div class="admin-panel-body p-0">
                @if($ventasDiarias->isEmpty())
                    <div class="admin-empty">
                        <i class="bi bi-graph-down"></i>
                        <p class="text-cine-muted">No hay datos de ventas recientes</p>
                    </div>
                @else
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th class="text-center">Boletos</th>
                                <th class="text-end">Ingresos</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ventasDiarias as $vd)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($vd->fecha)->format('d/m/Y') }}</td>
                                <td class="text-center">
                                    <span class="fw-bold text-cine-text">{{ $vd->total_boletos }}</span>
                                </td>
                                <td class="text-end">
                                    <span class="text-cine-success">${{ number_format($vd->ingresos_dia, 2) }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Próximas Funciones --}}
<div class="admin-panel mt-4 animate-fade-in-up delay-4">
    <div class="admin-panel-header">
        <h3><i class="bi bi-calendar-check"></i> Próximas Funciones</h3>
        <a href="{{ route('admin.funciones') }}" class="btn-cine-outline" style="font-size:0.8rem; padding:0.35rem 0.85rem;">
            Ver todas <i class="bi bi-arrow-right"></i>
        </a>
    </div>
    <div class="admin-panel-body p-0">
        @if($ocupacion->isEmpty())
            <div class="admin-empty">
                <i class="bi bi-calendar-x"></i>
                <h5>Sin funciones próximas</h5>
                <p>No hay funciones programadas próximamente.</p>
            </div>
        @else
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Película</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Sala</th>
                        <th class="text-center">Vendidos</th>
                        <th class="text-center">Capacidad</th>
                        <th class="text-center">Ocupación</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ocupacion as $oc)
                    <tr>
                        <td class="fw-semibold text-cine-text">{{ $oc->pelicula }}</td>
                        <td>{{ \Carbon\Carbon::parse($oc->fecha)->format('d/m') }}</td>
                        <td>
                            <span class="fw-semibold text-cine-text">{{ \Carbon\Carbon::parse($oc->hora)->format('H:i') }}</span>
                        </td>
                        <td>{{ $oc->sala }}</td>
                        <td class="text-center">
                            <span class="fw-bold {{ $oc->boletos_vendidos > 0 ? 'text-cine-success' : 'text-cine-muted' }}">
                                {{ $oc->boletos_vendidos }}
                            </span>
                        </td>
                        <td class="text-center text-cine-muted">{{ $oc->capacidad }}</td>
                        <td class="text-center">
                            @php $pct = floatval($oc->porcentaje_ocupacion); @endphp
                            <div class="d-flex align-items-center gap-2 justify-content-center">
                                <div style="width:60px;height:6px;background:var(--cine-border);border-radius:3px;overflow:hidden;">
                                    <div style="width:{{ min($pct, 100) }}%;height:100%;border-radius:3px;
                                        background:{{ $pct >= 80 ? 'var(--cine-primary)' : ($pct >= 40 ? 'var(--cine-secondary)' : 'var(--cine-success)') }};"></div>
                                </div>
                                <span class="fw-semibold" style="font-size:0.8rem;min-width:38px;">{{ $pct }}%</span>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>

@endsection
