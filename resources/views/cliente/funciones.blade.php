@extends('layouts.cine')

@section('titulo', 'Funciones — ' . $pelicula->titulo)

@section('contenido')

{{-- Breadcrumb --}}
<ol class="breadcrumb-cine">
    <li><a href="{{ route('cartelera') }}"><i class="bi bi-film"></i> Cartelera</a></li>
    <li class="active">{{ $pelicula->titulo }}</li>
</ol>

{{-- Step Indicator --}}
<div class="step-indicator">
    <div class="step-item completed"><span class="step-circle"><i class="bi bi-check"></i></span><span class="step-label">Cartelera</span></div>
    <div class="step-line completed"></div>
    <div class="step-item active"><span class="step-circle">2</span><span class="step-label">Función</span></div>
    <div class="step-line pending"></div>
    <div class="step-item pending"><span class="step-circle">3</span><span class="step-label">Cantidad</span></div>
    <div class="step-line pending"></div>
    <div class="step-item pending"><span class="step-circle">4</span><span class="step-label">Asientos</span></div>
    <div class="step-line pending"></div>
    <div class="step-item pending"><span class="step-circle">5</span><span class="step-label">Pago</span></div>
</div>

{{-- Movie Info --}}
<div class="info-panel info-panel-highlight mb-4 animate-fade-in">
    <div class="d-flex align-items-center gap-3 flex-wrap">
        <div class="flex-grow-1">
            <h3 class="fw-bold text-white mb-2" style="font-family:'Outfit',sans-serif;">{{ $pelicula->titulo }}</h3>
            <div class="d-flex flex-wrap gap-2 align-items-center">
                <span class="badge-cine badge-cine-rating">{{ $pelicula->clasificacion }}</span>
                <span class="badge-cine badge-cine-genre">{{ $pelicula->genero }}</span>
                <span class="text-cine-muted"><i class="bi bi-clock"></i> {{ $pelicula->duracion }} min</span>
                <span class="text-cine-muted"><i class="bi bi-translate"></i> {{ $pelicula->idioma }}</span>
            </div>
        </div>
    </div>
</div>

{{-- Functions --}}
@if($funciones->isEmpty())
    <div class="card-cine-static text-center py-5 animate-fade-in">
        <i class="bi bi-calendar-x text-cine-muted" style="font-size: 3rem;"></i>
        <h5 class="text-white mt-3">Sin funciones disponibles</h5>
        <p class="text-cine-muted">No hay funciones programadas para hoy ni mañana.</p>
        <a href="{{ route('cartelera') }}" class="btn-cine mt-2">
            <i class="bi bi-arrow-left"></i> Volver a Cartelera
        </a>
    </div>
@else
    @php $funcionesPorFecha = $funciones->groupBy('fecha'); @endphp

    @foreach($funcionesPorFecha as $fecha => $funcionesDelDia)
    <div class="mb-4 animate-fade-in-up">
        <h5 class="fw-bold mb-3 d-flex align-items-center gap-2">
            <i class="bi bi-calendar-event text-cine-primary"></i>
            <span class="text-white">
                {{ \Carbon\Carbon::parse($fecha)->isToday() ? 'Hoy' : 'Mañana' }}
            </span>
            <span class="text-cine-muted fw-normal" style="font-size:0.9rem;">
                — {{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }}
            </span>
        </h5>

        <div class="row g-3">
            @foreach($funcionesDelDia as $funcion)
            <div class="col-md-4 col-sm-6">
                <div class="card-cine p-3 text-center h-100 d-flex flex-column justify-content-between">
                    {{-- Sala Badge --}}
                    <div class="mb-2">
                        @if($funcion->tipo_sala == 'IMAX')
                            <span class="badge-cine badge-cine-imax px-3 py-1">IMAX</span>
                        @elseif($funcion->tipo_sala == '3D')
                            <span class="badge-cine badge-cine-3d px-3 py-1">3D</span>
                        @else
                            <span class="badge-cine badge-cine-2d px-3 py-1">2D</span>
                        @endif
                    </div>

                    {{-- Time --}}
                    <h4 class="text-white fw-bold mb-1" style="font-family:'Outfit',sans-serif;">
                        {{ \Carbon\Carbon::parse($funcion->hora)->format('H:i') }}
                    </h4>

                    {{-- Sala & Price --}}
                    <p class="text-cine-muted mb-1" style="font-size:0.85rem;">
                        <i class="bi bi-building"></i> {{ $funcion->sala }}
                    </p>
                    <p class="text-cine-gold fw-bold mb-3">
                        ${{ number_format($funcion->precio, 2) }} MXN
                    </p>

                    {{-- CTA --}}
                    <a href="{{ route('funcion.cantidad', $funcion->funcion_id) }}"
                       class="btn-cine w-100 py-2">
                        <i class="bi bi-ticket-perforated"></i> Seleccionar
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endforeach
@endif
@endsection