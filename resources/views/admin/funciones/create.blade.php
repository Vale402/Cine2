@extends('layouts.admin')

@section('titulo', 'Nueva Función')

@section('contenido')

<div class="admin-topbar">
    <div>
        <h1><i class="bi bi-plus-circle"></i> Nueva Función</h1>
        <ol class="breadcrumb-cine mt-3">
            <li><a href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
            <li><a href="{{ route('admin.funciones') }}">Funciones</a></li>
            <li class="active">Nueva</li>
        </ol>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="admin-panel animate-fade-in-up">
            <div class="admin-panel-header">
                <h3><i class="bi bi-calendar-event"></i> Datos de la Función</h3>
            </div>
            <div class="admin-panel-body">
                <form action="{{ route('admin.funciones.store') }}" method="POST" class="admin-form">
                    @csrf

                    <div class="form-group">
                        <label for="pelicula_id"><i class="bi bi-film"></i> Película</label>
                        <select name="pelicula_id" id="pelicula_id" class="form-control form-control-cine" required>
                            <option value="">Seleccionar película...</option>
                            @foreach($peliculas as $pelicula)
                                <option value="{{ $pelicula->id }}" {{ old('pelicula_id') == $pelicula->id ? 'selected' : '' }}>
                                    {{ $pelicula->titulo }} ({{ $pelicula->duracion }} min)
                                </option>
                            @endforeach
                        </select>
                        @error('pelicula_id') <p class="auth-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label for="sala_id"><i class="bi bi-building"></i> Sala</label>
                        <select name="sala_id" id="sala_id" class="form-control form-control-cine" required>
                            <option value="">Seleccionar sala...</option>
                            @foreach($salas as $sala)
                                <option value="{{ $sala->id }}" {{ old('sala_id') == $sala->id ? 'selected' : '' }}>
                                    {{ $sala->nombre }} ({{ $sala->tipo }}) — ${{ number_format($sala->precio, 2) }}
                                </option>
                            @endforeach
                        </select>
                        @error('sala_id') <p class="auth-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</p> @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="fecha"><i class="bi bi-calendar"></i> Fecha</label>
                                <input type="date" name="fecha" id="fecha" class="form-control form-control-cine"
                                       value="{{ old('fecha', now()->format('Y-m-d')) }}" min="{{ now()->format('Y-m-d') }}" required>
                                @error('fecha') <p class="auth-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</p> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="hora"><i class="bi bi-clock"></i> Hora</label>
                                <input type="time" name="hora" id="hora" class="form-control form-control-cine"
                                       value="{{ old('hora') }}" required>
                                @error('hora') <p class="auth-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-3 mt-3">
                        <a href="{{ route('admin.funciones') }}" class="btn-cine-outline flex-fill text-center py-2">
                            <i class="bi bi-x-circle"></i> Cancelar
                        </a>
                        <button type="submit" class="btn-cine flex-fill py-2">
                            <i class="bi bi-check-circle"></i> Programar Función
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
