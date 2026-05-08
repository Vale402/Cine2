@extends('layouts.cine')

@section('titulo', 'Seleccionar Asientos')

@section('estilos')
<link href="{{ asset('css/cine-asientos.css') }}" rel="stylesheet">
@endsection

@section('contenido')

{{-- Breadcrumb --}}
<ol class="breadcrumb-cine">
    <li><a href="{{ route('cartelera') }}"><i class="bi bi-film"></i> Cartelera</a></li>
    <li><a href="{{ route('pelicula.funciones', $funcion->pelicula_id) }}">{{ $funcion->pelicula }}</a></li>
    <li class="active">Asientos</li>
</ol>

{{-- Step Indicator --}}
<div class="step-indicator">
    <div class="step-item completed"><span class="step-circle"><i class="bi bi-check"></i></span><span class="step-label">Cartelera</span></div>
    <div class="step-line completed"></div>
    <div class="step-item completed"><span class="step-circle"><i class="bi bi-check"></i></span><span class="step-label">Función</span></div>
    <div class="step-line completed"></div>
    <div class="step-item completed"><span class="step-circle"><i class="bi bi-check"></i></span><span class="step-label">Cantidad</span></div>
    <div class="step-line completed"></div>
    <div class="step-item active"><span class="step-circle">4</span><span class="step-label">Asientos</span></div>
    <div class="step-line pending"></div>
    <div class="step-item pending"><span class="step-circle">5</span><span class="step-label">Pago</span></div>
</div>

{{-- Function Info --}}
<div class="info-panel info-panel-highlight mb-4 animate-fade-in">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h5 class="text-cine-text fw-bold mb-1" style="font-family:'Outfit',sans-serif;">{{ $funcion->pelicula }}</h5>
            <div class="d-flex flex-wrap gap-2 align-items-center text-cine-muted" style="font-size:0.85rem;">
                <span><i class="bi bi-calendar"></i> {{ \Carbon\Carbon::parse($funcion->fecha)->format('d/m/Y') }}</span>
                <span><i class="bi bi-clock"></i> {{ \Carbon\Carbon::parse($funcion->hora)->format('H:i') }}</span>
                <span><i class="bi bi-building"></i> {{ $funcion->sala }}</span>
                @if($funcion->tipo_sala == 'IMAX')
                    <span class="badge-cine badge-cine-imax">IMAX</span>
                @elseif($funcion->tipo_sala == '3D')
                    <span class="badge-cine badge-cine-3d">3D</span>
                @else
                    <span class="badge-cine badge-cine-2d">2D</span>
                @endif
            </div>
        </div>
        <div class="text-end">
            <p class="text-cine-text mb-0" style="font-size:0.9rem;">
                Selecciona <strong class="text-cine-primary">{{ $cantidad }}</strong> asiento(s)
            </p>
            <p class="text-cine-success fw-bold mb-0">
                ${{ number_format($funcion->precio, 2) }} MXN c/u
            </p>
        </div>
    </div>
</div>

{{-- Legend --}}
<div class="seat-legend animate-fade-in">
    <div class="seat-legend-item">
        <div class="seat-legend-dot available"></div> Disponible
    </div>
    <div class="seat-legend-item">
        <div class="seat-legend-dot occupied"></div> Ocupado
    </div>
    <div class="seat-legend-item">
        <div class="seat-legend-dot selected"></div> Seleccionado
    </div>
</div>

{{-- Screen --}}
<div class="screen-container animate-fade-in">
    <p class="screen-label">PANTALLA</p>
    <div class="screen"></div>
</div>

{{-- Seat Map --}}
<form action="{{ route('boleto.preparar') }}" method="POST" id="formAsientos">
    @csrf
    <input type="hidden" name="funcion_id" value="{{ $funcion->funcion_id }}">

    <div class="seat-grid mb-4 animate-fade-in-up">
        @foreach($asientosPorFila as $fila => $asientos)
        <div class="seat-row">
            <span class="seat-row-label">{{ $fila }}</span>
            @foreach($asientos as $asiento)
                @if($asiento->estado == 'ocupado')
                    <button type="button" class="seat occupied" disabled title="Ocupado">
                        {{ $asiento->numero }}
                    </button>
                @else
                    <button type="button" class="seat available"
                        data-id="{{ $asiento->asiento_id }}"
                        data-nombre="{{ $asiento->asiento }}"
                        onclick="seleccionarAsiento(this)"
                        title="{{ $asiento->asiento }}">
                        {{ $asiento->numero }}
                    </button>
                @endif
            @endforeach
            <span class="seat-row-label">{{ $fila }}</span>
        </div>
        @endforeach
    </div>

    {{-- Selection Summary --}}
    <div class="selection-summary animate-fade-in-up">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <p class="text-cine-text mb-1" style="font-size:0.9rem;">
                    <strong>Asientos:</strong>
                    <span id="asientosSeleccionados" class="text-cine-primary">Ninguno</span>
                </p>
                <p class="text-cine-text mb-0">
                    <strong>Total:</strong>
                    <span id="totalMonto" class="text-cine-success fw-bold fs-5">$0.00 MXN</span>
                </p>
            </div>
            <button type="submit" id="btnContinuar" class="btn-cine px-4 py-2 fs-5" disabled>
                <i class="bi bi-arrow-right-circle"></i> Continuar
            </button>
        </div>
    </div>

    <div id="inputsAsientos"></div>
</form>

@endsection

@section('scripts')
<script>
    const maxAsientos = {{ $cantidad }};
    const precio = {{ $funcion->precio }};
    let seleccionados = [];

    function seleccionarAsiento(btn) {
        const id = btn.dataset.id;
        const nombre = btn.dataset.nombre;
        const index = seleccionados.findIndex(a => a.id === id);

        if (index > -1) {
            seleccionados.splice(index, 1);
            btn.classList.remove('selected');
            btn.classList.add('available');
        } else {
            if (seleccionados.length >= maxAsientos) {
                // Visual feedback instead of alert
                btn.style.animation = 'none';
                btn.offsetHeight; // trigger reflow
                btn.style.animation = 'shake 0.3s ease';
                return;
            }
            seleccionados.push({ id, nombre });
            btn.classList.remove('available');
            btn.classList.add('selected');
        }
        actualizarResumen();
    }

    function actualizarResumen() {
        const spanNombres = document.getElementById('asientosSeleccionados');
        const spanTotal = document.getElementById('totalMonto');
        const btnContinuar = document.getElementById('btnContinuar');
        const inputsDiv = document.getElementById('inputsAsientos');

        if (seleccionados.length === 0) {
            spanNombres.textContent = 'Ninguno';
            spanTotal.textContent = '$0.00 MXN';
            btnContinuar.disabled = true;
            inputsDiv.innerHTML = '';
            return;
        }

        spanNombres.textContent = seleccionados.map(a => a.nombre).join(', ');
        const total = seleccionados.length * precio;
        spanTotal.textContent = '$' + total.toFixed(2) + ' MXN';
        btnContinuar.disabled = seleccionados.length !== maxAsientos;
        inputsDiv.innerHTML = seleccionados
            .map(a => `<input type="hidden" name="asientos[]" value="${a.id}">`)
            .join('');
    }
</script>
<style>
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-4px); }
        75% { transform: translateX(4px); }
    }
</style>
@endsection
