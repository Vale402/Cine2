@extends('layouts.cine')

@section('titulo', 'Cantidad de Boletos')

@section('estilos')
<link href="{{ asset('css/cine-asientos.css') }}" rel="stylesheet">
@endsection

@section('contenido')

{{-- Breadcrumb --}}
<ol class="breadcrumb-cine">
    <li><a href="{{ route('cartelera') }}"><i class="bi bi-film"></i> Cartelera</a></li>
    <li><a href="{{ route('pelicula.funciones', $funcion->pelicula_id) }}">{{ $funcion->pelicula }}</a></li>
    <li class="active">Cantidad</li>
</ol>

{{-- Step Indicator --}}
<div class="step-indicator">
    <div class="step-item completed"><span class="step-circle"><i class="bi bi-check"></i></span><span class="step-label">Cartelera</span></div>
    <div class="step-line completed"></div>
    <div class="step-item completed"><span class="step-circle"><i class="bi bi-check"></i></span><span class="step-label">Función</span></div>
    <div class="step-line completed"></div>
    <div class="step-item active"><span class="step-circle">3</span><span class="step-label">Cantidad</span></div>
    <div class="step-line pending"></div>
    <div class="step-item pending"><span class="step-circle">4</span><span class="step-label">Asientos</span></div>
    <div class="step-line pending"></div>
    <div class="step-item pending"><span class="step-circle">5</span><span class="step-label">Pago</span></div>
</div>

<div class="row justify-content-center">
    <div class="col-md-5 col-lg-4">
        <div class="card-cine-static p-4 text-center animate-fade-in-up">

            {{-- Icon --}}
            <div class="mb-3">
                <i class="bi bi-ticket-perforated text-cine-primary" style="font-size: 3.5rem;"></i>
            </div>

            {{-- Movie + function info --}}
            <h4 class="text-cine-text fw-bold mb-1" style="font-family:'Outfit',sans-serif;">{{ $funcion->pelicula }}</h4>
            <div class="d-flex justify-content-center flex-wrap gap-2 mb-3 text-cine-muted" style="font-size:0.85rem;">
                <span><i class="bi bi-calendar"></i> {{ \Carbon\Carbon::parse($funcion->fecha)->format('d/m/Y') }}</span>
                <span><i class="bi bi-clock"></i> {{ \Carbon\Carbon::parse($funcion->hora)->format('H:i') }}</span>
                @if($funcion->tipo_sala == 'IMAX')
                    <span class="badge-cine badge-cine-imax">IMAX</span>
                @elseif($funcion->tipo_sala == '3D')
                    <span class="badge-cine badge-cine-3d">3D</span>
                @else
                    <span class="badge-cine badge-cine-2d">2D</span>
                @endif
            </div>

            {{-- Available seats --}}
            <div class="info-panel mb-4" style="padding:0.75rem 1rem;">
                <p class="mb-1 text-cine-text" style="font-size:0.9rem;">
                    <i class="bi bi-grid-3x3-gap text-cine-success"></i>
                    <strong class="text-cine-success">{{ $disponibles }}</strong> asientos disponibles
                </p>
                <p class="mb-0 text-cine-gold" style="font-size:0.9rem;">
                    <i class="bi bi-currency-dollar"></i>
                    {{ number_format($funcion->precio, 2) }} MXN por boleto
                </p>
            </div>

            {{-- Quantity Selector --}}
            <label class="form-label-cine fs-5 mb-3 d-block">¿Cuántos boletos?</label>

            <div class="qty-selector mb-4">
                <button type="button" class="qty-btn" onclick="cambiarCantidad(-1)">
                    <i class="bi bi-dash-lg"></i>
                </button>
                <div class="qty-display" id="cantidadDisplay">1</div>
                <button type="button" class="qty-btn" onclick="cambiarCantidad(1)">
                    <i class="bi bi-plus-lg"></i>
                </button>
            </div>

            {{-- Total Preview --}}
            <div class="info-panel mb-4" style="border-color: var(--cine-primary); padding:0.75rem;">
                <p class="mb-0 text-cine-text" style="font-size:1.1rem;">
                    Total estimado:
                    <strong class="text-cine-success" id="totalMonto" style="font-size:1.25rem;">
                        ${{ number_format($funcion->precio, 2) }} MXN
                    </strong>
                </p>
            </div>

            {{-- CTA --}}
            <button type="button" class="btn-cine w-100 py-2 fs-5" onclick="irAAsientos()">
                <i class="bi bi-arrow-right-circle"></i> Elegir Asientos
            </button>

        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    const precio = {{ $funcion->precio }};
    const max = {{ min($disponibles, 10) }};
    let cantidad = 1;

    function cambiarCantidad(valor) {
        cantidad += valor;
        if (cantidad < 1) cantidad = 1;
        if (cantidad > max) cantidad = max;
        document.getElementById('cantidadDisplay').textContent = cantidad;
        document.getElementById('totalMonto').textContent =
            '$' + (precio * cantidad).toFixed(2) + ' MXN';
    }

    function irAAsientos() {
        const id = {{ $funcion->funcion_id }};
        window.location.href = `/funcion/${id}/asientos/${cantidad}`;
    }
</script>
@endsection
