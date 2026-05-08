@extends('layouts.cine')

@section('titulo', 'Método de Pago')

@section('contenido')

{{-- Breadcrumb --}}
<ol class="breadcrumb-cine">
    <li><a href="{{ route('cartelera') }}"><i class="bi bi-film"></i> Cartelera</a></li>
    <li class="active">Método de Pago</li>
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
    <div class="step-item completed"><span class="step-circle"><i class="bi bi-check"></i></span><span class="step-label">Resumen</span></div>
    <div class="step-line completed"></div>
    <div class="step-item active"><span class="step-circle">6</span><span class="step-label">Pago</span></div>
</div>

<div class="row justify-content-center" x-data="pagoApp">
    <div class="col-md-10 col-lg-8">

        {{-- Mini resumen arriba --}}
        <div class="info-panel info-panel-highlight mb-4 animate-fade-in" style="border-radius:14px;">
            <div class="row align-items-center text-cine-text" style="font-size:0.9rem;">
                <div class="col-sm-6">
                    <strong><i class="bi bi-film text-cine-primary"></i> {{ $funcion->pelicula }}</strong>
                    &nbsp;·&nbsp;
                    {{ \Carbon\Carbon::parse($funcion->fecha)->format('d/m/Y') }}
                    &nbsp;{{ \Carbon\Carbon::parse($funcion->hora)->format('H:i') }}
                </div>
                <div class="col-sm-3">
                    <i class="bi bi-grid-3x3-gap text-cine-primary"></i>
                    @foreach($asientos as $a)
                        <span class="badge-cine badge-cine-seat" style="font-size:0.75rem;">{{ $a->asiento }}</span>
                    @endforeach
                </div>
                <div class="col-sm-3 text-sm-end">
                    <span class="text-cine-success fw-bold" style="font-size:1.1rem; font-family:'Outfit',sans-serif;">
                        ${{ number_format($total, 2) }} MXN
                    </span>
                </div>
            </div>
        </div>

        <div class="row g-4">

            {{-- Selector de método --}}
            <div class="col-md-4">
                <div class="card-cine-static p-3 animate-fade-in-up h-100">
                    <h6 class="text-cine-primary fw-bold mb-3">
                        <i class="bi bi-wallet2"></i> Método de Pago
                    </h6>

                    {{-- Efectivo --}}
                    <div class="metodo-btn mb-2"
                         :class="metodo === 'efectivo' ? 'metodo-btn-active' : ''"
                         @click="metodo = 'efectivo'">
                        <i class="bi bi-cash-stack fs-4"></i>
                        <div>
                            <div class="fw-bold" style="font-size:0.95rem;">Efectivo</div>
                            <div style="font-size:0.75rem; opacity:0.7;">Pago en taquilla</div>
                        </div>
                        <i class="bi ms-auto" :class="metodo === 'efectivo' ? 'bi-check-circle-fill text-cine-success' : 'bi-circle'"></i>
                    </div>

                    {{-- Tarjeta --}}
                    <div class="metodo-btn"
                         :class="metodo === 'tarjeta' ? 'metodo-btn-active' : ''"
                         @click="metodo = 'tarjeta'">
                        <i class="bi bi-credit-card fs-4"></i>
                        <div>
                            <div class="fw-bold" style="font-size:0.95rem;">Tarjeta</div>
                            <div style="font-size:0.75rem; opacity:0.7;">Débito / Crédito</div>
                        </div>
                        <i class="bi ms-auto" :class="metodo === 'tarjeta' ? 'bi-check-circle-fill text-cine-success' : 'bi-circle'"></i>
                    </div>
                </div>
            </div>

            {{-- Panel derecho --}}
            <div class="col-md-8">
                <form action="{{ route('boleto.confirmar') }}" method="POST" id="form-pago">
                    @csrf
                    <input type="hidden" name="funcion_id" value="{{ $pago['funcion_id'] }}">
                    @foreach($pago['asientos'] as $asiento_id)
                        <input type="hidden" name="asientos[]" value="{{ $asiento_id }}">
                    @endforeach
                    <input type="hidden" name="metodo_pago" :value="metodo">

                    {{-- PANEL EFECTIVO --}}
                    <div x-show="metodo === 'efectivo'" x-transition class="card-cine-static p-4 animate-fade-in-up text-center">
                        <div style="font-size:4rem; line-height:1; margin-bottom:1rem;">💵</div>
                        <h5 class="text-cine-text fw-bold mb-2" style="font-family:'Outfit',sans-serif;">
                            Pago en Efectivo
                        </h5>
                        <p class="text-cine-muted mb-1" style="font-size:0.9rem;">
                            Presenta tu boleto en taquilla y realiza el pago al momento de ingresar.
                        </p>
                        <p class="text-cine-success fw-bold mb-4" style="font-size:1.4rem; font-family:'Outfit',sans-serif;">
                            Total: ${{ number_format($total, 2) }} MXN
                        </p>
                        <div class="d-flex gap-3 justify-content-center">
                            <a href="{{ route('boleto.resumen') }}" class="btn-cine-outline px-4 py-2">
                                <i class="bi bi-arrow-left"></i> Regresar
                            </a>
                            <button type="submit" class="btn-cine px-5 py-2 fs-5">
                                <i class="bi bi-check-circle"></i> Confirmar
                            </button>
                        </div>
                    </div>

                    {{-- PANEL TARJETA --}}
                    <div x-show="metodo === 'tarjeta'" x-transition class="card-cine-static p-4 animate-fade-in-up">

                        {{-- Preview tarjeta --}}
                        <div class="tarjeta-preview mb-4" :class="flipCard ? 'flipped' : ''">
                            <div class="tarjeta-front">
                                <div class="tarjeta-chip">
                                    <i class="bi bi-cpu"></i>
                                </div>
                                <div class="tarjeta-numero" x-text="formatNumero(numero)"></div>
                                <div class="tarjeta-bottom">
                                    <div>
                                        <div class="tarjeta-sublabel">TITULAR</div>
                                        <div class="tarjeta-nombre" x-text="nombre || 'NOMBRE APELLIDO'"></div>
                                    </div>
                                    <div class="text-end">
                                        <div class="tarjeta-sublabel">VENCE</div>
                                        <div class="tarjeta-expiry" x-text="expiry || 'MM/AA'"></div>
                                    </div>
                                </div>
                                <div class="tarjeta-brand">
                                    <i class="bi bi-credit-card-2-front-fill fs-4"></i>
                                </div>
                            </div>
                            <div class="tarjeta-back">
                                <div class="tarjeta-banda"></div>
                                <div class="tarjeta-cvv-row">
                                    <div class="tarjeta-cvv-firma"></div>
                                    <div class="tarjeta-cvv-box">
                                        <div class="tarjeta-sublabel" style="color:#888; font-size:0.6rem;">CVV</div>
                                        <div class="fw-bold" style="letter-spacing:3px;" x-text="cvv || '***'"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Formulario --}}
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label text-cine-text fw-bold" style="font-size:0.85rem;">Número de Tarjeta</label>
                                <input type="text" class="form-control-cine"
                                       placeholder="0000 0000 0000 0000"
                                       maxlength="19"
                                       x-model="numero"
                                       @input="numero = formatInput($event.target.value)"
                                       autocomplete="off">
                            </div>
                            <div class="col-12">
                                <label class="form-label text-cine-text fw-bold" style="font-size:0.85rem;">Nombre del Titular</label>
                                <input type="text" class="form-control-cine"
                                       placeholder="Como aparece en la tarjeta"
                                       x-model="nombre"
                                       @input="nombre = $event.target.value.toUpperCase()"
                                       autocomplete="off">
                            </div>
                            <div class="col-6">
                                <label class="form-label text-cine-text fw-bold" style="font-size:0.85rem;">Fecha de Vencimiento</label>
                                <input type="text" class="form-control-cine"
                                       placeholder="MM/AA"
                                       maxlength="5"
                                       x-model="expiry"
                                       @input="expiry = formatExpiry($event.target.value)"
                                       autocomplete="off">
                            </div>
                            <div class="col-6">
                                <label class="form-label text-cine-text fw-bold" style="font-size:0.85rem;">CVV</label>
                                <input type="text" class="form-control-cine"
                                       placeholder="***"
                                       maxlength="4"
                                       x-model="cvv"
                                       @focus="flipCard = true"
                                       @blur="flipCard = false"
                                       autocomplete="off">
                            </div>
                        </div>

                        <div class="d-flex gap-3 justify-content-center mt-4">
                            <a href="{{ route('boleto.resumen') }}" class="btn-cine-outline px-4 py-2">
                                <i class="bi bi-arrow-left"></i> Regresar
                            </a>
                            <button type="submit" class="btn-cine px-5 py-2 fs-5"
                                    :disabled="!numero || !nombre || !expiry || !cvv">
                                <i class="bi bi-lock-fill"></i> Pagar ${{ number_format($total, 2) }}
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>{{-- /row --}}

    </div>
</div>

@section('estilos')
<style>
    /* Botones de método de pago */
    .metodo-btn {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 16px;
        border: 2px solid var(--cine-border);
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.2s;
        color: var(--cine-text);
        background: var(--cine-surface);
    }
    .metodo-btn:hover { border-color: var(--cine-primary); background: var(--cine-hover); }
    .metodo-btn-active { border-color: var(--cine-primary) !important; background: var(--cine-hover) !important; }

    /* Input de tarjeta */
    .form-control-cine {
        width: 100%;
        background: var(--cine-surface);
        border: 2px solid var(--cine-border);
        border-radius: 10px;
        padding: 10px 14px;
        color: var(--cine-text);
        font-size: 0.95rem;
        transition: border-color 0.2s;
        outline: none;
        font-family: 'Outfit', sans-serif;
        letter-spacing: 1px;
    }
    .form-control-cine::placeholder { color: var(--cine-muted); letter-spacing: 0; }
    .form-control-cine:focus { border-color: var(--cine-primary); }

    /* Tarjeta preview */
    .tarjeta-preview {
        width: 100%;
        max-width: 360px;
        height: 200px;
        margin: 0 auto;
        perspective: 1000px;
        position: relative;
    }
    .tarjeta-front, .tarjeta-back {
        position: absolute;
        inset: 0;
        border-radius: 16px;
        backface-visibility: hidden;
        transition: transform 0.6s ease;
        padding: 20px 24px;
        box-shadow: 0 8px 32px rgba(255,99,126,0.25);
    }
    .tarjeta-front {
        background: linear-gradient(135deg, #FF637E 0%, #c72d53 100%);
        color: #fff;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .tarjeta-back {
        background: linear-gradient(135deg, #c72d53 0%, #9b1a3d 100%);
        transform: rotateY(180deg);
        color: #fff;
    }
    .tarjeta-preview.flipped .tarjeta-front { transform: rotateY(-180deg); }
    .tarjeta-preview.flipped .tarjeta-back  { transform: rotateY(0deg); }

    .tarjeta-chip { font-size: 1.4rem; opacity: 0.85; }
    .tarjeta-numero { font-size: 1.25rem; letter-spacing: 3px; font-family: 'Courier New', monospace; text-align: center; }
    .tarjeta-bottom { display: flex; justify-content: space-between; align-items: flex-end; }
    .tarjeta-sublabel { font-size: 0.6rem; letter-spacing: 1px; opacity: 0.7; text-transform: uppercase; }
    .tarjeta-nombre { font-size: 0.85rem; letter-spacing: 1px; font-weight: 600; max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .tarjeta-expiry { font-size: 0.9rem; font-weight: 600; }
    .tarjeta-brand { position: absolute; top: 20px; right: 20px; opacity: 0.8; }

    .tarjeta-banda { height: 44px; background: rgba(0,0,0,0.5); margin: 0 -24px; margin-top: 10px; }
    .tarjeta-cvv-row { display: flex; align-items: center; gap: 12px; margin-top: 16px; padding: 0 4px; }
    .tarjeta-cvv-firma { flex: 1; height: 36px; background: rgba(255,255,255,0.85); border-radius: 4px; }
    .tarjeta-cvv-box { background: #fff; color: #333; border-radius: 6px; padding: 4px 12px; text-align: center; min-width: 60px; }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('pagoApp', () => ({
            metodo: 'efectivo',
            numero: '',
            nombre: '',
            expiry: '',
            cvv: '',
            flipCard: false,
            formatNumero(val) {
                return val || '0000 0000 0000 0000';
            },
            formatInput(val) {
                let digits = val.replace(/\D/g, '').slice(0, 16);
                return digits.replace(/(.{4})/g, '$1 ').trim();
            },
            formatExpiry(val) {
                let clean = val.replace(/\D/g, '').slice(0, 4);
                if (clean.length >= 3) return clean.slice(0, 2) + '/' + clean.slice(2);
                return clean;
            },
        }));
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js" defer></script>
@endsection

@endsection
