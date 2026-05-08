@extends('layouts.admin')

@section('titulo', 'Funciones')

@section('contenido')

<div class="admin-topbar">
    <h1><i class="bi bi-calendar-event"></i> Funciones</h1>
    <div class="admin-topbar-actions">
        <a href="{{ route('admin.funciones.create') }}" class="btn-cine">
            <i class="bi bi-plus-circle"></i> Nueva Función
        </a>
    </div>
</div>

<div class="admin-panel animate-fade-in-up">
    <div class="admin-panel-body p-0">
        @if($funciones->isEmpty())
            <div class="admin-empty">
                <i class="bi bi-calendar-event"></i>
                <h5>No hay funciones registradas</h5>
                <p>Programa la primera función para comenzar.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Película</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Sala</th>
                            <th>Tipo</th>
                            <th class="text-end">Precio</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($funciones as $funcion)
                        @php
                            $fechaCarbon = \Carbon\Carbon::parse($funcion->fecha);
                            $esPasada = $fechaCarbon->isPast() && !$fechaCarbon->isToday();
                        @endphp
                        <tr style="{{ $esPasada ? 'opacity:0.5;' : '' }}">
                            <td class="text-cine-muted">{{ $funcion->funcion_id }}</td>
                            <td class="fw-semibold text-cine-text">{{ $funcion->pelicula }}</td>
                            <td>
                                @if($fechaCarbon->isToday())
                                    <span class="text-cine-success fw-semibold">Hoy</span>
                                @elseif($fechaCarbon->isTomorrow())
                                    <span class="text-cine-gold fw-semibold">Mañana</span>
                                @else
                                    {{ $fechaCarbon->format('d/m/Y') }}
                                @endif
                            </td>
                            <td class="fw-semibold text-cine-text">{{ \Carbon\Carbon::parse($funcion->hora)->format('H:i') }}</td>
                            <td>{{ $funcion->sala }}</td>
                            <td>
                                @if($funcion->tipo_sala == 'IMAX')
                                    <span class="badge-cine badge-cine-imax">IMAX</span>
                                @elseif($funcion->tipo_sala == '3D')
                                    <span class="badge-cine badge-cine-3d">3D</span>
                                @else
                                    <span class="badge-cine badge-cine-2d">2D</span>
                                @endif
                            </td>
                            <td class="text-end text-cine-success">${{ number_format($funcion->precio, 2) }}</td>
                            <td class="text-center">
                                <div class="d-flex gap-1 justify-content-center">
                                    <a href="{{ route('admin.funciones.edit', $funcion->funcion_id) }}"
                                       class="action-btn action-btn-edit" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.funciones.destroy', $funcion->funcion_id) }}" method="POST"
                                          onsubmit="return confirm('¿Estás seguro de eliminar esta función?')">
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
            </div>
        @endif
    </div>
</div>

@endsection
