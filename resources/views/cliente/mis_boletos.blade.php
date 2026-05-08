@extends('layouts.cine')

@section('titulo', 'Mis Boletos')

@section('contenido')

{{-- Header --}}
<div class="section-header d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
        <h2><i class="bi bi-ticket-perforated"></i> Mis Boletos</h2>
        <p>Historial de todas tus compras</p>
    </div>
    <a href="{{ route('cartelera') }}" class="btn-cine">
        <i class="bi bi-film"></i> Ver Cartelera
    </a>
</div>

@if($compras->isEmpty())
    <div class="card-cine-static text-center py-5 animate-fade-in">
        <i class="bi bi-ticket-perforated text-cine-muted" style="font-size: 4rem;"></i>
        <h4 class="text-cine-text mt-3">No tienes boletos aún</h4>
        <p class="text-cine-muted">¡Compra tu primer boleto en la cartelera!</p>
        <a href="{{ route('cartelera') }}" class="btn-cine mt-2">
            <i class="bi bi-film"></i> Ir a Cartelera
        </a>
    </div>
@else
    <div class="row g-4">
        @foreach($compras as $index => $compra)
        <div class="col-md-6 animate-fade-in-up delay-{{ ($index % 4) + 1 }}">
            <div class="ticket" style="border-width:1px;">
                {{-- Header --}}
                <div class="d-flex justify-content-between align-items-center px-3 py-2"
                     style="background: var(--cine-gradient);">
                    <span class="text-cine-text fw-bold" style="font-size:0.85rem;">🎬 CINEAPP</span>
                    <span class="badge bg-dark bg-opacity-50" style="font-size:0.75rem;">
                        {{ count($compra['asientos']) }}
                        {{ count($compra['asientos']) == 1 ? 'boleto' : 'boletos' }}
                    </span>
                </div>

                {{-- Body --}}
                <div class="p-3">
                    <h6 class="text-cine-text fw-bold mb-2" style="font-family:'Outfit',sans-serif;">
                        {{ $compra['pelicula'] }}
                    </h6>

                    <div class="row text-cine-muted mb-2" style="font-size:0.85rem;">
                        <div class="col-6">
                            <p class="mb-1">
                                <i class="bi bi-calendar text-cine-primary"></i>
                                {{ \Carbon\Carbon::parse($compra['fecha_funcion'])->format('d/m/Y') }}
                            </p>
                            <p class="mb-1">
                                <i class="bi bi-clock text-cine-primary"></i>
                                {{ \Carbon\Carbon::parse($compra['hora_funcion'])->format('H:i') }}
                            </p>
                        </div>
                        <div class="col-6">
                            <p class="mb-1">
                                <i class="bi bi-building text-cine-primary"></i>
                                {{ $compra['sala'] }}
                                @if($compra['tipo_sala'] == 'IMAX')
                                    <span class="badge-cine badge-cine-imax" style="font-size:0.6rem;">IMAX</span>
                                @elseif($compra['tipo_sala'] == '3D')
                                    <span class="badge-cine badge-cine-3d" style="font-size:0.6rem;">3D</span>
                                @else
                                    <span class="badge-cine badge-cine-2d" style="font-size:0.6rem;">2D</span>
                                @endif
                            </p>
                            <p class="mb-1 text-cine-success fw-bold">
                                ${{ number_format($compra['total'], 2) }} MXN
                            </p>
                        </div>
                    </div>

                    {{-- Seats --}}
                    <div class="d-flex flex-wrap gap-1 mb-2">
                        @foreach($compra['asientos'] as $asiento)
                            <span class="badge-cine badge-cine-seat">{{ $asiento }}</span>
                        @endforeach
                    </div>

                    <hr style="border-color: var(--cine-border); margin: 0.5rem 0;">

                    <p class="text-cine-muted mb-0" style="font-size:0.78rem;">
                        <i class="bi bi-clock-history"></i>
                        {{ \Carbon\Carbon::parse($compra['fecha_compra'])->format('d/m/Y H:i') }}
                        &nbsp;·&nbsp; #{{ implode(', #', $compra['ids']) }}
                    </p>
                </div>
            </div>
        </div>
        @endforeach
    </div>
@endif

@endsection
