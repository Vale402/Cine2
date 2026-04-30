@extends('layouts.cine')

@section('titulo', 'Resumen de Compra')

@section('contenido')

<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('cartelera') }}" class="text-danger">Cartelera</a>
        </li>
        <li class="breadcrumb-item active text-white">Resumen de Compra</li>
    </ol>
</nav>

<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card card-pelicula p-4">

            <h4 class="text-white fw-bold text-center mb-4">
                <i class="bi bi-receipt text-danger"></i> Resumen de tu Compra
            </h4>

            {{-- Info de la funcion --}}
            <div class="alert alert-dark border border-secondary mb-4">
                <h6 class="text-danger fw-bold mb-2">
                    <i class="bi bi-film"></i> Información de la Función
                </h6>
                <p class="text-white mb-1">
                    <strong>Película:</strong> {{ $funcion->pelicula }}
                </p>
                <p class="text-white mb-1">
                    <strong>Fecha:</strong>
                    {{ \Carbon\Carbon::parse($funcion->fecha)->format('d/m/Y') }}
                </p>
                <p class="text-white mb-1">
                    <strong>Hora:</strong>
                    {{ \Carbon\Carbon::parse($funcion->hora)->format('H:i') }}
                </p>
                <p class="text-white mb-1">
                    <strong>Sala:</strong> {{ $funcion->sala }}
                    <span class="badge bg-danger ms-1">{{ $funcion->tipo_sala }}</span>
                </p>
                <p class="text-white mb-0">
                    <strong>Precio por boleto:</strong>
                    <span class="text-success">${{ number_format($funcion->precio, 2) }} MXN</span>
                </p>
            </div>

            {{-- Asientos seleccionados --}}
            <h6 class="text-danger fw-bold mb-3">
                <i class="bi bi-chair"></i> Asientos Seleccionados
            </h6>
            <div class="table-responsive mb-4">
                <table class="table table-dark table-bordered text-center">
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
                            <td>
                                <span class="badge bg-primary fs-6">
                                    {{ $asiento->asiento }}
                                </span>
                            </td>
                            <td class="text-success">
                                ${{ number_format($funcion->precio, 2) }} MXN
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Total --}}
            <div class="alert alert-dark border border-danger text-center mb-4">
                <p class="text-white mb-0 fs-5">
                    Total a pagar:
                    <strong class="text-success fs-4">
                        ${{ number_format($total, 2) }} MXN
                    </strong>
                </p>
                <small class="text-muted">Pago en efectivo en taquilla</small>
            </div>

            {{-- Botones --}}
            <form action="{{ route('boleto.confirmar') }}" method="POST">
                @csrf
                <input type="hidden" name="funcion_id" value="{{ $funcion->funcion_id }}">
                @foreach($asientos as $asiento)
                    <input type="hidden" name="asientos[]" value="{{ $asiento->asiento_id }}">
                @endforeach

                <div class="d-flex gap-3">
                    <a href="{{ route('cartelera') }}"
                       class="btn btn-outline-danger w-50 py-2">
                        <i class="bi bi-x-circle"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-cine w-50 py-2 fs-5">
                        <i class="bi bi-check-circle"></i> Confirmar Compra
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection