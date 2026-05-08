@extends('layouts.admin')

@section('titulo', 'Salas')

@section('contenido')

<div class="admin-topbar">
    <h1><i class="bi bi-building"></i> Salas</h1>
    <div class="admin-topbar-actions">
        <a href="{{ route('admin.salas.create') }}" class="btn-cine">
            <i class="bi bi-plus-circle"></i> Nueva Sala
        </a>
    </div>
</div>

<div class="admin-panel animate-fade-in-up">
    <div class="admin-panel-body p-0">
        @if($salas->isEmpty())
            <div class="admin-empty">
                <i class="bi bi-building"></i>
                <h5>No hay salas registradas</h5>
                <p>Agrega la primera sala para comenzar.</p>
            </div>
        @else
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th class="text-center">Capacidad</th>
                        <th class="text-end">Precio</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($salas as $sala)
                    <tr>
                        <td class="text-cine-muted">{{ $sala->id }}</td>
                        <td class="fw-semibold text-cine-text">{{ $sala->nombre }}</td>
                        <td>
                            @if($sala->tipo == 'IMAX')
                                <span class="badge-cine badge-cine-imax">IMAX</span>
                            @elseif($sala->tipo == '3D')
                                <span class="badge-cine badge-cine-3d">3D</span>
                            @else
                                <span class="badge-cine badge-cine-2d">{{ $sala->tipo }}</span>
                            @endif
                        </td>
                        <td class="text-center">{{ $sala->capacidad }} asientos</td>
                        <td class="text-end text-cine-success fw-bold">${{ number_format($sala->precio, 2) }}</td>
                        <td class="text-center">
                            <div class="d-flex gap-1 justify-content-center">
                                <a href="{{ route('admin.salas.edit', $sala->id) }}"
                                   class="action-btn action-btn-edit" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.salas.destroy', $sala->id) }}" method="POST"
                                      onsubmit="return confirm('¿Estás seguro de eliminar esta sala?')">
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
