@extends('layouts.cine')

@section('titulo', 'Cartelera')

@section('contenido')

{{-- Header --}}
<div class="section-header d-flex justify-content-between align-items-center">
    <div>
        <h2><i class="bi bi-film"></i> Cartelera</h2>
        <p>Selecciona una película para ver funciones disponibles</p>
    </div>
</div>

@if($peliculas->isEmpty())
    <div class="card-cine-static text-center py-5 animate-fade-in">
        <i class="bi bi-film text-cine-muted" style="font-size: 4rem;"></i>
        <h4 class="text-cine-text mt-3">No hay películas disponibles</h4>
        <p class="text-cine-muted">Vuelve pronto para ver la nueva cartelera</p>
    </div>
@else
    <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 row-cols-xl-4 g-4">
        @foreach($peliculas as $index => $pelicula)
        @php
            $generoLower = strtolower($pelicula->genero);
            $generoClass = 'genero-default';
            if (str_contains($generoLower, 'accion') || str_contains($generoLower, 'acci')) $generoClass = 'genero-accion';
            elseif (str_contains($generoLower, 'terror')) $generoClass = 'genero-terror';
            elseif (str_contains($generoLower, 'animacion') || str_contains($generoLower, 'animaci')) $generoClass = 'genero-animacion';
            elseif (str_contains($generoLower, 'romance')) $generoClass = 'genero-romance';
            elseif (str_contains($generoLower, 'ciencia')) $generoClass = 'genero-ciencia';

            $icons = [
                'genero-accion' => 'bi-lightning-charge-fill',
                'genero-terror' => 'bi-moon-stars-fill',
                'genero-animacion' => 'bi-stars',
                'genero-romance' => 'bi-heart-fill',
                'genero-ciencia' => 'bi-rocket-takeoff-fill',
                'genero-default' => 'bi-camera-reels-fill',
            ];
            $icon = $icons[$generoClass] ?? 'bi-camera-reels-fill';
        @endphp
        <div class="col animate-fade-in-up delay-{{ ($index % 4) + 1 }}">
            <div class="card-cine h-100 d-flex flex-column">
                {{-- Poster --}}
                @if($pelicula->imagen)
                    <div class="movie-poster-placeholder {{ $generoClass }}" style="padding:0; overflow:hidden;">
                        <img src="{{ asset('storage/' . $pelicula->imagen) }}" alt="{{ $pelicula->titulo }}"
                             style="width:100%; height:100%; object-fit:cover;">
                    </div>
                @else
                    <div class="movie-poster-placeholder {{ $generoClass }}">
                        <i class="bi {{ $icon }}" style="opacity:0.3;"></i>
                    </div>
                @endif

                {{-- Body --}}
                <div class="movie-card-body flex-grow-1 d-flex flex-column">
                    <h5 class="movie-card-title">{{ $pelicula->titulo }}</h5>

                    <div class="d-flex flex-wrap gap-1 mb-3">
                        <span class="badge-cine badge-cine-genre">{{ $pelicula->genero }}</span>
                        <span class="badge-cine badge-cine-rating">{{ $pelicula->clasificacion }}</span>
                    </div>

                    <ul class="movie-card-info mb-3">
                        <li>
                            <i class="bi bi-clock"></i>
                            {{ $pelicula->duracion }} min
                        </li>
                        <li>
                            <i class="bi bi-translate"></i>
                            {{ $pelicula->idioma }}
                        </li>
                        <li>
                            <i class="bi bi-calendar-event"></i>
                            {{ $pelicula->total_funciones }} funciones
                        </li>
                    </ul>

                    <div class="mt-auto">
                        <a href="{{ route('pelicula.funciones', $pelicula->pelicula_id) }}"
                           class="btn-cine w-100 d-block text-center py-2">
                            <i class="bi bi-play-circle-fill"></i> Ver Funciones
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
@endif
@endsection
