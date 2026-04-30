@extends('layouts.cine')

@section('titulo', 'Compra Confirmada')

@section('contenido')

<div class="row justify-content-center">
    <div class="col-md-7">

        {{-- Mensaje de éxito --}}
        <div class="text-center mb-4">
            <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
            <h3 class="text-white fw-bold mt-3">¡Compra Confirmada!</h3>
            <p class="text-white">Presenta este boleto en taquilla para acceder a la sala</p>
        </div>

        {{-- Boleto único --}}
        <div class="card p-0 mb-4" style="background:#1a1a1a; border: 2px solid #e50914; border-radius: 16px; overflow:hidden;">

            {{-- Encabezado del boleto --}}
            <div style="background:#e50914;" class="p-3 text-center">
                <h4 class="text-white fw-bold mb-0">
                    🎬 CINEAPP
                </h4>
                <small class="text-white opacity-75">Boleto de Entrada</small>
            </div>

            {{-- Cuerpo del boleto --}}
            <div class="p-4">

                {{-- Pelicula --}}
                <h4 class="text-white fw-bold text-center mb-3">
                    {{ $compra['pelicula'] }}
                </h4>

                <hr style="border-color:#333;">

                {{-- Detalles --}}
                <div class="row text-center mb-3">
                    <div class="col-4">
                        <p class="text-white small mb-1">FECHA</p>
                        <p class="text-white fw-bold mb-0">
                            {{ \Carbon\Carbon::parse($compra['fecha_funcion'])->format('d/m/Y') }}
                        </p>
                    </div>
                    <div class="col-4">
                        <p class="text-white small mb-1">HORA</p>
                        <p class="text-white fw-bold mb-0">
                            {{ \Carbon\Carbon::parse($compra['hora_funcion'])->format('H:i') }}
                        </p>
                    </div>
                    <div class="col-4">
                        <p class="text-white small mb-1">SALA</p>
                        <p class="text-white fw-bold mb-0">
                            {{ $compra['sala'] }}
                            <span class="badge bg-danger">{{ $compra['tipo_sala'] }}</span>
                        </p>
                    </div>
                </div>

                <hr style="border-color:#333; border-style: dashed;">

                {{-- Asientos --}}
                <div class="text-center mb-3">
                    <p class="text-white small mb-2">ASIENTO(S)</p>
                    <div class="d-flex flex-wrap justify-content-center gap-2">
                        @foreach($compra['asientos'] as $asiento)
                            <span class="badge bg-primary fs-6 px-3 py-2">
                                {{ $asiento }}
                            </span>
                        @endforeach
                    </div>
                </div>

                <hr style="border-color:#333; border-style: dashed;">

                {{-- Cliente y total --}}
                <div class="row text-center">
                    <div class="col-6">
                        <p class="text-white small mb-1">CLIENTE</p>
                        <p class="text-white fw-bold mb-0">{{ $compra['cliente'] }}</p>
                    </div>
                    <div class="col-6">
                        <p class="text-white small mb-1">TOTAL PAGADO</p>
                        <p class="text-success fw-bold fs-5 mb-0">
                            ${{ number_format($compra['total'], 2) }} MXN
                        </p>
                    </div>
                </div>

                <hr style="border-color:#333;">

                {{-- Numeros de boleto --}}
                <p class="text-white small text-center mb-0">
                    Boleto(s) #{{ implode(', #', $compra['ids']) }}
                </p>
                <p class="text-white small text-center mb-0">
                    Comprado: {{ \Carbon\Carbon::parse($compra['fecha_compra'])->format('d/m/Y H:i') }}
                </p>

            </div>

            {{-- Pie del boleto --}}
            <div style="background:#111; border-top: 2px dashed #333;" class="p-3 text-center">
                <small class="text-white">
                    <i class="bi bi-info-circle"></i>
                    Pago en efectivo en taquilla · Presenta este boleto al ingresar
                </small>
            </div>
        </div>

        {{-- Botones --}}
        <div class="d-flex gap-3 justify-content-center">
            <a href="{{ route('mis.boletos') }}" class="btn btn-outline-light px-4">
                <i class="bi bi-ticket-perforated"></i> Ver Mis Boletos
            </a>
            <a href="{{ route('cartelera') }}" class="btn btn-cine px-4">
                <i class="bi bi-film"></i> Volver a Cartelera
            </a>
        </div>

    </div>
</div>

@endsection