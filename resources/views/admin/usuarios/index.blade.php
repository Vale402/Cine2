@extends('layouts.admin')

@section('titulo', 'Usuarios')

@section('contenido')

<div class="admin-topbar">
    <h1><i class="bi bi-people"></i> Usuarios</h1>
    <div class="admin-topbar-actions">
        <div class="info-panel" style="padding:0.5rem 1rem;">
            <span class="text-cine-muted" style="font-size:0.85rem;">Total:</span>
            <span class="fw-bold text-cine-text ms-1">{{ $usuarios->count() }} usuarios</span>
        </div>
    </div>
</div>

<div class="admin-panel animate-fade-in-up">
    <div class="admin-panel-body p-0">
        @if($usuarios->isEmpty())
            <div class="admin-empty">
                <i class="bi bi-people"></i>
                <h5>No hay usuarios registrados</h5>
                <p>Los usuarios aparecerán aquí cuando se registren.</p>
            </div>
        @else
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Rol</th>
                        <th>Registrado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($usuarios as $usuario)
                    <tr>
                        <td class="text-cine-muted">{{ $usuario->id }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="sidebar-user-avatar" style="width:30px;height:30px;font-size:0.7rem;border-radius:8px;">
                                    {{ strtoupper(substr($usuario->name, 0, 1)) }}
                                </div>
                                <span class="fw-semibold text-cine-text">{{ $usuario->name }}</span>
                            </div>
                        </td>
                        <td>{{ $usuario->email }}</td>
                        <td>{{ $usuario->telefono ?? '—' }}</td>
                        <td>
                            @if($usuario->rol == 'administrador')
                                <span class="badge-cine badge-cine-imax">Admin</span>
                            @else
                                <span class="badge-cine badge-cine-2d">Cliente</span>
                            @endif
                        </td>
                        <td class="text-cine-muted" style="font-size:0.82rem;">
                            {{ $usuario->created_at ? \Carbon\Carbon::parse($usuario->created_at)->format('d/m/Y') : '—' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>

@endsection
