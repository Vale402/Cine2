@extends('layouts.cine')

@section('titulo', 'Cantidad de Boletos')

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
        <li class="breadcrumb-item active text-white">Cantidad de Boletos</li>
    </ol>
</nav>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card card-pelicula p-4 text-center">

            <i class="bi bi-ticket-perforated text-danger mb-3" style="font-size: 4rem;"></i>

            <h4 class="text-white fw-bold mb-1">{{ $funcion->pelicula }}</h4>
            <p class="text-white mb-3">
                <i class="bi bi-calendar"></i>
                {{ \Carbon\Carbon::parse($funcion->fecha)->format('d/m/Y') }}
                &nbsp;|&nbsp;
                <i class="bi bi-clock"></i>
                {{ \Carbon\Carbon::parse($funcion->hora)->format('H:i') }}
                &nbsp;|&nbsp;
                <span class="badge bg-danger">{{ $funcion->tipo_sala }}</span>
            </p>

            <div class="alert alert-dark border border-secondary mb-4">
                <p class="mb-0 text-white">
                    <i class="bi bi-chair text-success"></i>
                    <strong class="text-success">{{ $disponibles }}</strong>
                    asientos disponibles
                </p>
                <p class="mb-0 text-warning mt-1">
                    <i class="bi bi-currency-dollar"></i>
                    Precio por boleto:
                    <strong>${{ number_format($funcion->precio, 2) }} MXN</strong>
                </p>
            </div>

            <form id="formCantidad" action="" method="GET">
                <label class="form-label text-white fw-bold fs-5 mb-3">
                    ¿Cuántos boletos deseas?
                </label>
                <div class="d-flex align-items-center justify-content-center gap-3 mb-4">
                    <button type="button" class="btn btn-outline-danger btn-lg px-4"
                        onclick="cambiarCantidad(-1)">
                        <i class="bi bi-dash-lg"></i>
                    </button>
                    <input type="number" id="cantidad" name="cantidad"
                        value="1" min="1" max="{{ min($disponibles, 10) }}"
                        class="form-control text-center fw-bold fs-4 text-white bg-dark border-secondary"
                        style="width: 80px;" readonly>
                    <button type="button" class="btn btn-outline-danger btn-lg px-4"
                        onclick="cambiarCantidad(1)">
                        <i class="bi bi-plus-lg"></i>
                    </button>
                </div>

                <div id="totalPreview" class="alert alert-dark border-danger mb-4">
                    <p class="mb-0 text-white fs-5">
                        Total estimado:
                        <strong class="text-success" id="totalMonto">
                            ${{ number_format($funcion->precio, 2) }} MXN
                        </strong>
                    </p>
                </div>

                <button type="button" class="btn btn-cine w-100 py-2 fs-5"
                     onclick="irAAsientos()">
                        <i class="bi bi-arrow-right-circle"></i> Elegir Asientos
                </button>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    const precio = {{ $funcion->precio }};
    const max = {{ min($disponibles, 10) }};

    function cambiarCantidad(valor) {
        const input = document.getElementById('cantidad');
        let actual = parseInt(input.value);
        actual += valor;
        if (actual < 1) actual = 1;
        if (actual > max) actual = max;
        input.value = actual;
        document.getElementById('totalMonto').textContent =
            '$' + (precio * actual).toFixed(2) + ' MXN';
    }
    function irAAsientos() {
    const cantidad = document.getElementById('cantidad').value;
    const id = {{ $funcion->funcion_id }};
    window.location.href = `/funcion/${id}/asientos/${cantidad}`;
    }
</script>
@endsection