@extends('layouts.cine')

@section('titulo', 'Seleccionar Asientos')

@section('estilos')
<style>
    .asiento {
        width: 40px;
        height: 40px;
        border-radius: 8px 8px 0 0;
        border: none;
        font-size: 11px;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.2s;
        margin: 3px;
    }
    .asiento.disponible {
        background-color: #28a745;
        color: white;
    }
    .asiento.disponible:hover {
        background-color: #1e7e34;
        transform: scale(1.1);
    }
    .asiento.ocupado {
        background-color: #dc3545;
        color: white;
        cursor: not-allowed;
    }
    .asiento.seleccionado {
        background-color: #0d6efd;
        color: white;
        transform: scale(1.1);
    }
    .pantalla {
        background: linear-gradient(180deg, #ffffff 0%, #cccccc 100%);
        height: 8px;
        border-radius: 4px;
        margin: 0 auto 30px auto;
        width: 70%;
    }
</style>
@endsection

@section('contenido')

<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('cartelera') }}" class="text-danger">Cartelera</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('pelicula.funciones', $funcion->pelicula_id) }}" class="text-danger">
                {{ $funcion->pelicula }}
            </a>
        </li>
        <li class="breadcrumb-item active text-white">Seleccionar Asientos</li>
    </ol>
</nav>

{{-- Info de la funcion --}}
<div class="card card-pelicula p-3 mb-4">
    <div class="row align-items-center">
        <div class="col">
            <h5 class="text-white fw-bold mb-1">{{ $funcion->pelicula }}</h5>
            <span class="text-white">
                <i class="bi bi-calendar"></i>
                {{ \Carbon\Carbon::parse($funcion->fecha)->format('d/m/Y') }}
                &nbsp;|&nbsp;
                <i class="bi bi-clock"></i>
                {{ \Carbon\Carbon::parse($funcion->hora)->format('H:i') }}
                &nbsp;|&nbsp;
                <span class="badge bg-danger">{{ $funcion->tipo_sala }}</span>
                &nbsp;|&nbsp;
                <i class="bi bi-building"></i> {{ $funcion->sala }}
            </span>
        </div>
        <div class="col-auto text-end">
            <p class="text-white mb-0">
                Selecciona <strong class="text-danger">{{ $cantidad }}</strong> asiento(s)
            </p>
            <p class="text-success fw-bold mb-0">
                ${{ number_format($funcion->precio, 2) }} MXN c/u
            </p>
        </div>
    </div>
</div>

{{-- Leyenda --}}
<div class="d-flex gap-4 justify-content-center mb-4">
    <span>
        <button class="asiento disponible" disabled></button>
        Disponible
    </span>
    <span>
        <button class="asiento ocupado" disabled></button>
        Ocupado
    </span>
    <span>
        <button class="asiento seleccionado" disabled></button>
        Seleccionado
    </span>
</div>

{{-- Pantalla --}}
<p class="text-center text-white small mb-1">PANTALLA</p>
<div class="pantalla"></div>
{{-- hola --}}
{{-- Mapa de asientos --}}
<form action="{{ route('boleto.resumen') }}" method="POST" id="formAsientos">
    @csrf
    <input type="hidden" name="funcion_id" value="{{ $funcion->funcion_id }}">

    <div class="text-center mb-4">
        @foreach($asientosPorFila as $fila => $asientos)
        <div class="d-flex justify-content-center align-items-center mb-1">
            <span class="text-white me-2 fw-bold" style="width: 20px;">{{ $fila }}</span>
            @foreach($asientos as $asiento)
                @if($asiento->estado == 'ocupado')
                    <button type="button"
                        class="asiento ocupado"
                        disabled
                        title="Ocupado">
                        {{ $asiento->numero }}
                    </button>
                @else
                    <button type="button"
                        class="asiento disponible"
                        data-id="{{ $asiento->asiento_id }}"
                        data-nombre="{{ $asiento->asiento }}"
                        onclick="seleccionarAsiento(this)"
                        title="{{ $asiento->asiento }}">
                        {{ $asiento->numero }}
                    </button>
                @endif
            @endforeach
        </div>
        @endforeach
    </div>

    {{-- Resumen de seleccion --}}
    <div class="card card-pelicula p-3 mb-4">
        <div class="row align-items-center">
            <div class="col">
                <p class="text-white mb-1">
                    <strong>Asientos seleccionados:</strong>
                    <span id="asientosSeleccionados" class="text-danger">Ninguno</span>
                </p>
                <p class="text-white mb-0">
                    <strong>Total:</strong>
                    <span id="totalMonto" class="text-success fw-bold fs-5">
                        $0.00 MXN
                    </span>
                </p>
            </div>
            <div class="col-auto">
                <button type="submit" id="btnContinuar"
                    class="btn btn-cine px-4 py-2 fs-5" disabled>
                    <i class="bi bi-arrow-right-circle"></i> Continuar
                </button>
            </div>
        </div>
    </div>

    {{-- Inputs hidden para los asientos seleccionados --}}
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
            // Deseleccionar
            seleccionados.splice(index, 1);
            btn.classList.remove('seleccionado');
            btn.classList.add('disponible');
        } else {
            // Seleccionar
            if (seleccionados.length >= maxAsientos) {
                alert(`Solo puedes seleccionar ${maxAsientos} asiento(s).`);
                return;
            }
            seleccionados.push({ id, nombre });
            btn.classList.remove('disponible');
            btn.classList.add('seleccionado');
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

        // Mostrar nombres
        spanNombres.textContent = seleccionados.map(a => a.nombre).join(', ');

        // Calcular total
        const total = seleccionados.length * precio;
        spanTotal.textContent = '$' + total.toFixed(2) + ' MXN';

        // Habilitar boton si selecciono la cantidad correcta
        btnContinuar.disabled = seleccionados.length !== maxAsientos;

        // Crear inputs hidden
        inputsDiv.innerHTML = seleccionados
            .map(a => `<input type="hidden" name="asientos[]" value="${a.id}">`)
            .join('');
    }
</script>
@endsection