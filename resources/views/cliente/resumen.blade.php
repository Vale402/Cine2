@extends('layouts.cine')

@section('titulo', 'Resumen de Compra')

@section('contenido')

{{-- Breadcrumb --}}
<ol class="breadcrumb-cine">
    <li><a href="{{ route('cartelera') }}"><i class="bi bi-film"></i> Cartelera</a></li>
    <li class="active">Resumen de Compra</li>
</ol>

{{-- Step Indicator --}}
<div class="step-indicator">
    <div class="step-item completed"><span class="step-circle"><i class="bi bi-check"></i></span><span class="step-label">Cartelera</span></div>
    <div class="step-line completed"></div>
    <div class="step-item completed"><span class="step-circle"><i class="bi bi-check"></i></span><span class="step-label">Función</span></div>
    <div class="step-line completed"></div>
    <div class="step-item completed"><span class="step-circle"><i class="bi bi-check"></i></span><span class="step-label">Cantidad</span></div>
    <div class="step-line completed"></div>
    <div class="step-item completed"><span class="step-circle"><i class="bi bi-check"></i></span><span class="step-label">Asientos</span></div>
    <div class="step-line completed"></div>
    <div class="step-item active"><span class="step-circle">5</span><span class="step-label">Pago</span></div>
</div>

<div class="row justify-content-center">
    <div class="col-md-7 col-lg-6">
        <div class="card-cine-static p-4 animate-fade-in-up">

            <h4 class="text-cine-text fw-bold text-center mb-4" style="font-family:'Outfit',sans-serif;">
                <i class="bi bi-receipt text-cine-primary"></i> Resumen de tu Compra
            </h4>

            {{-- Function Info --}}
            <div class="info-panel info-panel-highlight mb-4">
                <h6 class="text-cine-primary fw-bold mb-2">
                    <i class="bi bi-film"></i> Información de la Función
                </h6>
                <div class="row text-cine-text" style="font-size:0.9rem;">
                    <div class="col-sm-6">
                        <p class="mb-1"><strong>Película:</strong> {{ $funcion->pelicula }}</p>
                        <p class="mb-1"><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($funcion->fecha)->format('d/m/Y') }}</p>
                    </div>
                    <div class="col-sm-6">
                        <p class="mb-1"><strong>Hora:</strong> {{ \Carbon\Carbon::parse($funcion->hora)->format('H:i') }}</p>
                        <p class="mb-1">
                            <strong>Sala:</strong> {{ $funcion->sala }}
                            @if($funcion->tipo_sala == 'IMAX')
                                <span class="badge-cine badge-cine-imax ms-1">IMAX</span>
                            @elseif($funcion->tipo_sala == '3D')
                                <span class="badge-cine badge-cine-3d ms-1">3D</span>
                            @else
                                <span class="badge-cine badge-cine-2d ms-1">2D</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            {{-- Seats Table --}}
            <h6 class="text-cine-primary fw-bold mb-3">
                <i class="bi bi-grid-3x3-gap"></i> Asientos Seleccionados
            </h6>
            <div class="table-responsive mb-4">
                <table class="table table-cine text-center mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Asiento</th>
                            <th>Precio</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($asientos as $index => $asiento)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><span class="badge-cine badge-cine-seat">{{ $asiento->asiento }}</span></td>
                            <td class="text-cine-success">${{ number_format($funcion->precio, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Total --}}
            <div class="info-panel text-center mb-4" style="border-color: var(--cine-primary);">
                <p class="text-cine-text mb-1 fs-5">Total a pagar</p>
                <p class="text-cine-success fw-bold mb-1" style="font-size:2rem; font-family:'Outfit',sans-serif;">
                    ${{ number_format($total, 2) }} MXN
                </p>
                <small class="text-cine-muted">
                    <i class="bi bi-cash-stack"></i> Pago en efectivo en taquilla
                </small>
            </div>

            {{-- Actions --}}
            <form action="{{ route('boleto.confirmar') }}" method="POST">
                @csrf
                <input type="hidden" name="funcion_id" value="{{ $funcion->funcion_id }}">
                @foreach($asientos as $asiento)
                    <input type="hidden" name="asientos[]" value="{{ $asiento->asiento_id }}">
                @endforeach

                <div class="d-flex gap-3">
                    <a href="{{ route('cartelera') }}" class="btn-cine-outline w-50 py-2 text-center">
                        <i class="bi bi-x-circle"></i> Cancelar
                    </a>
                    <button type="submit" class="btn-cine w-50 py-2 fs-5">
                        <i class="bi bi-check-circle"></i> Confirmar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
