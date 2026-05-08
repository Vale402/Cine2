@extends('layouts.admin')

@section('titulo', 'Películas')

@section('contenido')

<div class="admin-topbar">
    <h1><i class="bi bi-film"></i> Películas</h1>
    <div class="admin-topbar-actions">
        <a href="{{ route('admin.peliculas.create') }}" class="btn-cine">
            <i class="bi bi-plus-circle"></i> Nueva Película
        </a>
    </div>
</div>

<div class="admin-panel animate-fade-in-up">
    <div class="admin-panel-body p-0">
        @if($peliculas->isEmpty())
            <div class="admin-empty">
                <i class="bi bi-film"></i>
                <h5>No hay películas registradas</h5>
                <p>Agrega la primera película para comenzar.</p>
                <a href="{{ route('admin.peliculas.create') }}" class="btn-cine mt-2">
                    <i class="bi bi-plus-circle"></i> Crear Película
                </a>
            </div>
        @else
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Género</th>
                        <th>Duración</th>
                        <th>Clasificación</th>
                        <th>Idioma</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($peliculas as $pelicula)
                    <tr>
                        <td class="text-cine-muted">{{ $pelicula->id }}</td>
                        <td class="fw-semibold text-cine-text">{{ $pelicula->titulo }}</td>
                        <td><span class="badge-cine badge-cine-genre">{{ $pelicula->genero }}</span></td>
                        <td>{{ $pelicula->duracion }} min</td>
                        <td><span class="badge-cine badge-cine-rating">{{ $pelicula->clasificacion }}</span></td>
                        <td>{{ $pelicula->idioma }}</td>
                        <td class="text-center">
                            <div class="d-flex gap-1 justify-content-center">
                                <a href="{{ route('admin.peliculas.edit', $pelicula->id) }}"
                                   class="action-btn action-btn-edit" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.peliculas.destroy', $pelicula->id) }}" method="POST"
                                      onsubmit="return confirm('¿Estás seguro de eliminar esta película?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn action-btn-delete" title="Eliminar">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
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
