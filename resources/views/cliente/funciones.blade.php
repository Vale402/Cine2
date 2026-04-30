@extends('layouts.cine')

@section('titulo', 'Funciones - ' . $pelicula->titulo)

@section('contenido')

{{-- Breadcrumb --}}
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('cartelera') }}" class="text-danger">Cartelera</a>
        </li>
        <li class="breadcrumb-item active text-white">{{ $pelicula->titulo }}</li>
    </ol>
</nav>

{{-- Info de la pelicula --}}
<div class="card card-pelicula mb-4 p-3">
    <div class="row align-items-center">
        <div class="col-auto">
            <i class="bi bi-camera-reels text-danger" style="font-size: 4rem;"></i>
        </div>
        <div class="col">
            <h3 class="fw-bold text-white mb-1">{{ $pelicula->titulo }}</h3>
            <span class="badge bg-danger me-1">{{ $pelicula->clasificacion }}</span>
            <span class="text-white">{{ $pelicula->genero }}</span>
            <span class="text-white ms-2">
                <i class="bi bi-clock"></i> {{ $pelicula->duracion }} min
            </span>
            <span class="text-white ms-2">
                <i class="bi bi-translate"></i> {{ $pelicula->idioma }}
            </span>
        </div>
    </div>
</div>

{{-- Funciones --}}
@if($funciones->isEmpty())
    <div class="alert alert-warning text-center">
        <i class="bi bi-exclamation-circle fs-3"></i>
        <p class="mt-2">No hay funciones disponibles para hoy ni mañana.</p>
        <a href="{{ route('cartelera') }}" class="btn btn-cine mt-2">
            Volver a la Cartelera
        </a>
    </div>
@else
    {{-- Agrupar por fecha --}}
    @php
        $funcionesPorFecha = $funciones->groupBy('fecha');
    @endphp

    @foreach($funcionesPorFecha as $fecha => $funcionesDelDia)
    <div class="mb-4">
        <h5 class="text-danger fw-bold mb-3">
            <i class="bi bi-calendar-event"></i>
            {{ \Carbon\Carbon::parse($fecha)->isToday() ? 'Hoy' : 'Mañana' }}
            - {{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }}
        </h5>

        <div class="row g-3">
            @foreach($funcionesDelDia as $funcion)
            <div class="col-md-4">
                <div class="card card-pelicula p-3 text-center">
                    <div class="mb-2">
                        <span class="badge fs-6 px-3 py-2
                            @if($funcion->tipo_sala == 'IMAX') bg-warning text-dark
                            @elseif($funcion->tipo_sala == '3D') bg-info text-dark
                            @else bg-secondary
                            @endif">
                            {{ $funcion->tipo_sala }}
                        </span>
                    </div>
                    <h4 class="text-white fw-bold">
                        <i class="bi bi-clock"></i>
                        {{ \Carbon\Carbon::parse($funcion->hora)->format('H:i') }}
                    </h4>
                    <p class="text-white mb-1">
                        <i class="bi bi-building"></i> {{ $funcion->sala }}
                    </p>
                    <a href="{{ route('funcion.cantidad', $funcion->funcion_id) }}"
                       class="btn btn-cine w-100">
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