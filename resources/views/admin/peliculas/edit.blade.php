@extends('layouts.admin')

@section('titulo', 'Editar Película')

@section('contenido')

<div class="admin-topbar">
    <div>
        <h1><i class="bi bi-pencil-square"></i> Editar Película</h1>
        <ol class="breadcrumb-cine mt-3">
            <li><a href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
            <li><a href="{{ route('admin.peliculas') }}">Películas</a></li>
            <li class="active">Editar</li>
        </ol>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="admin-panel animate-fade-in-up">
            <div class="admin-panel-header">
                <h3><i class="bi bi-film"></i> {{ $pelicula->titulo }}</h3>
            </div>
            <div class="admin-panel-body">
                <form action="{{ route('admin.peliculas.update', $pelicula->id) }}" method="POST" class="admin-form" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="titulo"><i class="bi bi-type"></i> Título</label>
                        <input type="text" name="titulo" id="titulo" class="form-control form-control-cine"
                               value="{{ old('titulo', $pelicula->titulo) }}" required>
                        @error('titulo') <p class="auth-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</p> @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="genero"><i class="bi bi-tag"></i> Género</label>
                                <select name="genero" id="genero" class="form-control form-control-cine" required>
                                    @foreach(['Accion', 'Animacion', 'Ciencia Ficcion', 'Comedia', 'Drama', 'Romance', 'Terror'] as $g)
                                        <option value="{{ $g }}" {{ old('genero', $pelicula->genero) == $g ? 'selected' : '' }}>{{ $g }}</option>
                                    @endforeach
                                </select>
                                @error('genero') <p class="auth-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</p> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="clasificacion"><i class="bi bi-shield-check"></i> Clasificación</label>
                                <select name="clasificacion" id="clasificacion" class="form-control form-control-cine" required>
                                    @foreach(['A', 'B', 'B15', 'C', 'D'] as $c)
                                        <option value="{{ $c }}" {{ old('clasificacion', $pelicula->clasificacion) == $c ? 'selected' : '' }}>{{ $c }}</option>
                                    @endforeach
                                </select>
                                @error('clasificacion') <p class="auth-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="duracion"><i class="bi bi-clock"></i> Duración (min)</label>
                                <input type="number" name="duracion" id="duracion" class="form-control form-control-cine"
                                       value="{{ old('duracion', $pelicula->duracion) }}" min="1" required>
                                @error('duracion') <p class="auth-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</p> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="idioma"><i class="bi bi-translate"></i> Idioma</label>
                                <select name="idioma" id="idioma" class="form-control form-control-cine" required>
                                    @foreach(['Español', 'Inglés', 'Subtitulada'] as $i)
                                        <option value="{{ $i }}" {{ old('idioma', $pelicula->idioma) == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endforeach
                                </select>
                                @error('idioma') <p class="auth-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="imagen"><i class="bi bi-image"></i> Imagen / Póster</label>
                        @if($pelicula->imagen)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $pelicula->imagen) }}" alt="Póster actual"
                                     style="max-height: 120px; border-radius: 8px; border: 2px solid var(--cine-border);">
                                <small class="d-block text-cine-muted mt-1">Póster actual. Sube otra imagen para reemplazarla.</small>
                            </div>
                        @endif
                        <input type="file" name="imagen" id="imagen" class="form-control form-control-cine"
                               accept="image/jpeg,image/png,image/jpg,image/webp">
                        <small class="text-cine-muted">Formatos: JPG, PNG, WEBP. Máximo 2MB.</small>
                        @error('imagen') <p class="auth-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</p> @enderror
                    </div>

                    <div class="d-flex gap-3 mt-3">
                        <a href="{{ route('admin.peliculas') }}" class="btn-cine-outline flex-fill text-center py-2">
                            <i class="bi bi-x-circle"></i> Cancelar
                        </a>
                        <button type="submit" class="btn-cine flex-fill py-2">
                            <i class="bi bi-check-circle"></i> Actualizar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
