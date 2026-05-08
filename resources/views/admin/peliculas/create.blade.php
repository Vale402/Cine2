@extends('layouts.admin')

@section('titulo', 'Nueva Película')

@section('contenido')

<div class="admin-topbar">
    <div>
        <h1><i class="bi bi-plus-circle"></i> Nueva Película</h1>
        <ol class="breadcrumb-cine mt-3">
            <li><a href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
            <li><a href="{{ route('admin.peliculas') }}">Películas</a></li>
            <li class="active">Nueva</li>
        </ol>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="admin-panel animate-fade-in-up">
            <div class="admin-panel-header">
                <h3><i class="bi bi-film"></i> Datos de la Película</h3>
            </div>
            <div class="admin-panel-body">
                <form action="{{ route('admin.peliculas.store') }}" method="POST" class="admin-form" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label for="titulo"><i class="bi bi-type"></i> Título</label>
                        <input type="text" name="titulo" id="titulo" class="form-control form-control-cine"
                               value="{{ old('titulo') }}" placeholder="Nombre de la película" required>
                        @error('titulo') <p class="auth-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</p> @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="genero"><i class="bi bi-tag"></i> Género</label>
                                <select name="genero" id="genero" class="form-control form-control-cine" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="Accion" {{ old('genero') == 'Accion' ? 'selected' : '' }}>Acción</option>
                                    <option value="Animacion" {{ old('genero') == 'Animacion' ? 'selected' : '' }}>Animación</option>
                                    <option value="Ciencia Ficcion" {{ old('genero') == 'Ciencia Ficcion' ? 'selected' : '' }}>Ciencia Ficción</option>
                                    <option value="Comedia" {{ old('genero') == 'Comedia' ? 'selected' : '' }}>Comedia</option>
                                    <option value="Drama" {{ old('genero') == 'Drama' ? 'selected' : '' }}>Drama</option>
                                    <option value="Romance" {{ old('genero') == 'Romance' ? 'selected' : '' }}>Romance</option>
                                    <option value="Terror" {{ old('genero') == 'Terror' ? 'selected' : '' }}>Terror</option>
                                </select>
                                @error('genero') <p class="auth-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</p> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="clasificacion"><i class="bi bi-shield-check"></i> Clasificación</label>
                                <select name="clasificacion" id="clasificacion" class="form-control form-control-cine" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="A" {{ old('clasificacion') == 'A' ? 'selected' : '' }}>A (Todo público)</option>
                                    <option value="B" {{ old('clasificacion') == 'B' ? 'selected' : '' }}>B (12+)</option>
                                    <option value="B15" {{ old('clasificacion') == 'B15' ? 'selected' : '' }}>B15 (15+)</option>
                                    <option value="C" {{ old('clasificacion') == 'C' ? 'selected' : '' }}>C (18+)</option>
                                    <option value="D" {{ old('clasificacion') == 'D' ? 'selected' : '' }}>D (Adultos)</option>
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
                                       value="{{ old('duracion') }}" placeholder="120" min="1" required>
                                @error('duracion') <p class="auth-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</p> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="idioma"><i class="bi bi-translate"></i> Idioma</label>
                                <select name="idioma" id="idioma" class="form-control form-control-cine" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="Español" {{ old('idioma') == 'Español' ? 'selected' : '' }}>Español</option>
                                    <option value="Inglés" {{ old('idioma') == 'Inglés' ? 'selected' : '' }}>Inglés</option>
                                    <option value="Subtitulada" {{ old('idioma') == 'Subtitulada' ? 'selected' : '' }}>Subtitulada</option>
                                </select>
                                @error('idioma') <p class="auth-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="imagen"><i class="bi bi-image"></i> Imagen / Póster</label>
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
                            <i class="bi bi-check-circle"></i> Guardar Película
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
