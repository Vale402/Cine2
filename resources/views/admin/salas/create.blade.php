@extends('layouts.admin')

@section('titulo', 'Nueva Sala')

@section('contenido')

<div class="admin-topbar">
    <div>
        <h1><i class="bi bi-plus-circle"></i> Nueva Sala</h1>
        <ol class="breadcrumb-cine mt-3">
            <li><a href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
            <li><a href="{{ route('admin.salas') }}">Salas</a></li>
            <li class="active">Nueva</li>
        </ol>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-5">
        <div class="admin-panel animate-fade-in-up">
            <div class="admin-panel-header">
                <h3><i class="bi bi-building"></i> Datos de la Sala</h3>
            </div>
            <div class="admin-panel-body">
                <form action="{{ route('admin.salas.store') }}" method="POST" class="admin-form">
                    @csrf

                    <div class="form-group">
                        <label for="nombre"><i class="bi bi-building"></i> Nombre</label>
                        <input type="text" name="nombre" id="nombre" class="form-control form-control-cine"
                               value="{{ old('nombre') }}" placeholder="Sala 1" required>
                        @error('nombre') <p class="auth-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label for="tipo"><i class="bi bi-display"></i> Tipo</label>
                        <select name="tipo" id="tipo" class="form-control form-control-cine" required>
                            <option value="">Seleccionar...</option>
                            <option value="2D" {{ old('tipo') == '2D' ? 'selected' : '' }}>2D</option>
                            <option value="3D" {{ old('tipo') == '3D' ? 'selected' : '' }}>3D</option>
                            <option value="IMAX" {{ old('tipo') == 'IMAX' ? 'selected' : '' }}>IMAX</option>
                        </select>
                        @error('tipo') <p class="auth-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</p> @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="capacidad"><i class="bi bi-grid-3x3-gap"></i> Capacidad</label>
                                <input type="number" name="capacidad" id="capacidad" class="form-control form-control-cine"
                                       value="{{ old('capacidad') }}" placeholder="80" min="1" required>
                                @error('capacidad') <p class="auth-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</p> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="precio"><i class="bi bi-currency-dollar"></i> Precio</label>
                                <input type="number" name="precio" id="precio" class="form-control form-control-cine"
                                       value="{{ old('precio') }}" placeholder="75.00" step="0.01" min="0" required>
                                @error('precio') <p class="auth-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-3 mt-3">
                        <a href="{{ route('admin.salas') }}" class="btn-cine-outline flex-fill text-center py-2">
                            <i class="bi bi-x-circle"></i> Cancelar
                        </a>
                        <button type="submit" class="btn-cine flex-fill py-2">
                            <i class="bi bi-check-circle"></i> Guardar Sala
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
