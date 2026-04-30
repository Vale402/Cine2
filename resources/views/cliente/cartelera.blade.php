@extends('layouts.cine')

@section('titulo', 'Cartelera')

@section('contenido')
<div class="row mb-4">
    <div class="col">
        <h2 class="fw-bold">
            <i class="bi bi-film text-danger"></i> Cartelera
        </h2>
        <p class="text-white">Selecciona una película para ver sus funciones disponibles</p>
    </div>
</div>

@if($peliculas->isEmpty())
    <div class="alert alert-warning text-center">
        <i class="bi bi-exclamation-circle fs-3"></i>
        <p class="mt-2">No hay películas disponibles en este momento.</p>
    </div>
@else
    <div class="row row-cols-1 row-cols-md-3 g-4">
        @foreach($peliculas as $pelicula)
        <div class="col">
            <div class="card card-pelicula h-100">
                <div class="card-body">
                    {{-- Icono decorativo --}}
                    <div class="text-center mb-3">
                        <i class="bi bi-camera-reels text-danger" style="font-size: 3rem;"></i>
                    </div>

                    {{-- Titulo --}}
                    <h5 class="card-title text-white fw-bold text-center">
                        {{ $pelicula->titulo }}
                    </h5>

                    <hr style="border-color: #000000;">

                    {{-- Informacion --}}
                    <ul class="list-unstyled text-white small">
                        <li class="mb-1">
                            <i class="bi bi-tag-fill text-danger"></i>
                            <strong class="text-white">Género:</strong>
                            {{ $pelicula->genero }}
                        </li>
                        <li class="mb-1">
                            <i class="bi bi-clock-fill text-danger"></i>
                            <strong class="text-white">Duración:</strong>
                            {{ $pelicula->duracion }} min
                        </li>
                        <li class="mb-1">
                            <i class="bi bi-shield-fill text-danger"></i>
                            <strong class="text-white">Clasificación:</strong>
                            <span class="badge bg-danger">{{ $pelicula->clasificacion }}</span>
                        </li>
                        <li class="mb-1">
                            <i class="bi bi-translate text-danger"></i>
                            <strong class="text-white">Idioma:</strong>
                            {{ $pelicula->idioma }}
                        </li>
                        <li class="mb-1">
                            <i class="bi bi-calendar-event text-danger"></i>
                            <strong class="text-white">Funciones:</strong>
                            {{ $pelicula->total_funciones }} disponibles
                        </li>
                    </ul>
                </div>
                <div class="card-footer bg-transparent border-0 pb-3">
                    <a href="{{ route('pelicula.funciones', $pelicula->pelicula_id) }}"
                       class="btn btn-cine w-100">
                        <i class="bi bi-play-circle-fill"></i> Ver Funciones
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
@endif
@endsection