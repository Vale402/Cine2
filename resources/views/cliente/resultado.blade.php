@extends('layouts.cine')

@section('titulo', 'Compra Confirmada')

@section('contenido')

<div class="row justify-content-center">
    <div class="col-md-7 col-lg-5">

        {{-- Success Animation --}}
        <div class="text-center mb-4 animate-fade-in">
            <div class="animate-check" style="display:inline-block;">
                <i class="bi bi-check-circle-fill text-cine-success" style="font-size: 5rem;"></i>
            </div>
            <h3 class="text-cine-text fw-bold mt-3" style="font-family:'Outfit',sans-serif;">¡Compra Confirmada!</h3>
            <p class="text-cine-muted">Presenta este boleto en taquilla para acceder a la sala</p>
        </div>

        {{-- Ticket --}}
        <div class="ticket animate-fade-in-up delay-2">

            {{-- Header --}}
            <div class="ticket-header">
                <h4>🎬 CINEAPP</h4>
                <small class="text-cine-text" style="opacity:0.8;">Boleto de Entrada</small>
            </div>

            {{-- Body --}}
            <div class="ticket-body">

                {{-- Movie Title --}}
                <h4 class="text-cine-text fw-bold text-center mb-3" style="font-family:'Outfit',sans-serif;">
                    {{ $compra['pelicula'] }}
                </h4>

                <hr class="ticket-divider">

                {{-- Details Grid --}}
                <div class="row text-center mb-3">
                    <div class="col-4">
                        <p class="ticket-detail-label">FECHA</p>
                        <p class="ticket-detail-value">
                            {{ \Carbon\Carbon::parse($compra['fecha_funcion'])->format('d/m/Y') }}
                        </p>
                    </div>
                    <div class="col-4">
                        <p class="ticket-detail-label">HORA</p>
                        <p class="ticket-detail-value">
                            {{ \Carbon\Carbon::parse($compra['hora_funcion'])->format('H:i') }}
                        </p>
                    </div>
                    <div class="col-4">
                        <p class="ticket-detail-label">SALA</p>
                        <p class="ticket-detail-value">
                            {{ $compra['sala'] }}
                            @if($compra['tipo_sala'] == 'IMAX')
                                <span class="badge-cine badge-cine-imax" style="font-size:0.65rem;">IMAX</span>
                            @elseif($compra['tipo_sala'] == '3D')
                                <span class="badge-cine badge-cine-3d" style="font-size:0.65rem;">3D</span>
                            @else
                                <span class="badge-cine badge-cine-2d" style="font-size:0.65rem;">2D</span>
                            @endif
                        </p>
                    </div>
                </div>

                <hr class="ticket-divider">

                {{-- Seats --}}
                <div class="text-center mb-3">
                    <p class="ticket-detail-label">ASIENTO(S)</p>
                    <div class="d-flex flex-wrap justify-content-center gap-2">
                        @foreach($compra['asientos'] as $asiento)
                            <span class="badge-cine badge-cine-seat px-3 py-2" style="font-size:0.9rem;">
                                {{ $asiento }}
                            </span>
                        @endforeach
                    </div>
                </div>

                <hr class="ticket-divider">

                {{-- Client & Total --}}
                <div class="row text-center">
                    <div class="col-6">
                        <p class="ticket-detail-label">CLIENTE</p>
                        <p class="ticket-detail-value" style="font-size:0.9rem;">{{ $compra['cliente'] }}</p>
                    </div>
                    <div class="col-6">
                        <p class="ticket-detail-label">TOTAL PAGADO</p>
                        <p class="text-cine-success fw-bold mb-0" style="font-size:1.5rem; font-family:'Outfit',sans-serif;">
                            ${{ number_format($compra['total'], 2) }}
                        </p>
                    </div>
                </div>

                <hr style="border-color: var(--cine-border); margin-top:1rem;">

                {{-- Ticket IDs --}}
                <p class="text-cine-muted text-center mb-0" style="font-size:0.8rem;">
                    Boleto(s) #{{ implode(', #', $compra['ids']) }}
                </p>
                <p class="text-cine-muted text-center mb-0" style="font-size:0.8rem;">
                    Comprado: {{ \Carbon\Carbon::parse($compra['fecha_compra'])->format('d/m/Y H:i') }}
                </p>
            </div>

            {{-- Footer --}}
            <div class="ticket-footer">
                <i class="bi bi-info-circle"></i>
                Pago en efectivo en taquilla · Presenta este boleto al ingresar
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="d-flex gap-3 justify-content-center mt-4 animate-fade-in-up delay-3">
            <a href="{{ route('mis.boletos') }}" class="btn-cine-outline px-4 py-2">
                <i class="bi bi-ticket-perforated"></i> Mis Boletos
            </a>
            <a href="{{ route('cartelera') }}" class="btn-cine px-4 py-2">
                <i class="bi bi-film"></i> Cartelera
            </a>
        </div>

    </div>
</div>

@endsection
