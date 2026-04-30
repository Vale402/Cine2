@extends('layouts.cine')

@section('titulo', 'Mis Boletos')

@section('contenido')

<div class="row mb-4">
    <div class="col">
        <h2 class="fw-bold">
            <i class="bi bi-ticket-perforated text-danger"></i> Mis Boletos
        </h2>
        <p class="text-white">Historial de todas tus compras</p>
    </div>
    <div class="col-auto">
        <a href="{{ route('cartelera') }}" class="btn btn-cine">
            <i class="bi bi-film"></i> Ver Cartelera
        </a>
    </div>
</div>

@if($compras->isEmpty())
    <div class="text-center py-5">
        <i class="bi bi-ticket-perforated text-white" style="font-size: 5rem;"></i>
        <h4 class="text-white mt-3">No tienes boletos aún</h4>
        <p class="text-white">¡Compra tu primer boleto en la cartelera!</p>
        <a href="{{ route('cartelera') }}" class="btn btn-cine mt-2">
            <i class="bi bi-film"></i> Ver Cartelera
        </a>
    </div>
@else
    <div class="row g-3">
        @foreach($compras as $compra)
        <div class="col-md-6">
            <div class="card p-0" style="background:#1a1a1a; border: 1px solid #000000; border-radius: 12px; overflow:hidden;">

                {{-- Encabezado --}}
                <div style="background:#e50914;" class="px-3 py-2 d-flex justify-content-between align-items-center">
                    <span class="text-white fw-bold small">🎬 CINEAPP</span>
                    <span class="badge bg-dark">
                        {{ count($compra['asientos']) }}
                        {{ count($compra['asientos']) == 1 ? 'boleto' : 'boletos' }}
                    </span>
                </div>

                {{-- Cuerpo --}}
                <div class="p-3">
                    <h6 class="text-white fw-bold mb-2">{{ $compra['pelicula'] }}</h6>

                    <div class="row text-white small mb-2">
                        <div class="col-6">
                            <p class="mb-1">
                                <i class="bi bi-calendar text-danger"></i>
                                {{ \Carbon\Carbon::parse($compra['fecha_funcion'])->format('d/m/Y') }}
                            </p>
                            <p class="mb-1">
                                <i class="bi bi-clock text-danger"></i>
                                {{ \Carbon\Carbon::parse($compra['hora_funcion'])->format('H:i') }}
                            </p>
                        </div>
                        <div class="col-6">
                            <p class="mb-1">
                                <i class="bi bi-building text-danger"></i>
                                {{ $compra['sala'] }}
                                <span class="badge bg-danger">{{ $compra['tipo_sala'] }}</span>
                            </p>
                            <p class="mb-1 text-success fw-bold">
                                ${{ number_format($compra['total'], 2) }} MXN
                            </p>
                        </div>
                    </div>

                    {{-- Asientos --}}
                    <div class="d-flex flex-wrap gap-1 mb-2">
                        @foreach($compra['asientos'] as $asiento)
                            <span class="badge bg-primary">{{ $asiento }}</span>
                        @endforeach
                    </div>

                    <hr style="border-color:#ffffff; margin: 8px 0;">

                    <p class="text-white small mb-0">
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