@extends('layouts.admin')

@section('titulo', 'Boletos Vendidos')

@section('contenido')

<div class="admin-topbar">
    <h1><i class="bi bi-ticket-perforated"></i> Boletos Vendidos</h1>
    <div class="admin-topbar-actions">
        <div class="info-panel" style="padding:0.5rem 1rem;">
            <span class="text-cine-muted" style="font-size:0.85rem;">Total:</span>
            <span class="fw-bold text-cine-text ms-1">{{ $boletos->count() }} boletos</span>
        </div>
    </div>
</div>

<div class="admin-panel animate-fade-in-up">
    <div class="admin-panel-body p-0">
        @if($boletos->isEmpty())
            <div class="admin-empty">
                <i class="bi bi-ticket-perforated"></i>
                <h5>No hay boletos vendidos</h5>
                <p>Aún no se han registrado ventas en el sistema.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Cliente</th>
                            <th>Película</th>
                            <th>Fecha Función</th>
                            <th>Hora</th>
                            <th>Sala</th>
                            <th>Asiento</th>
                            <th class="text-end">Precio</th>
                            <th>Fecha Compra</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($boletos as $boleto)
                        <tr>
                            <td class="text-cine-muted">{{ $boleto->boleto_id }}</td>
                            <td class="fw-semibold text-cine-text">{{ $boleto->cliente }}</td>
                            <td>{{ $boleto->pelicula }}</td>
                            <td>{{ \Carbon\Carbon::parse($boleto->fecha_funcion)->format('d/m/Y') }}</td>
                            <td class="fw-semibold text-cine-text">{{ \Carbon\Carbon::parse($boleto->hora_funcion)->format('H:i') }}</td>
                            <td>
                                {{ $boleto->sala }}
                                @if($boleto->tipo_sala == 'IMAX')
                                    <span class="badge-cine badge-cine-imax" style="font-size:0.6rem;">IMAX</span>
                                @elseif($boleto->tipo_sala == '3D')
                                    <span class="badge-cine badge-cine-3d" style="font-size:0.6rem;">3D</span>
                                @endif
                            </td>
                            <td><span class="badge-cine badge-cine-seat">{{ $boleto->asiento }}</span></td>
                            <td class="text-end text-cine-success fw-bold">${{ number_format($boleto->precio, 2) }}</td>
                            <td class="text-cine-muted" style="font-size:0.82rem;">
                                {{ \Carbon\Carbon::parse($boleto->fecha_compra)->format('d/m/Y H:i') }}
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
