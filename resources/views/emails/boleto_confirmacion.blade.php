<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Compra</title>
    <style>
        body { margin: 0; padding: 0; background: #f5e6ea; font-family: 'Segoe UI', Arial, sans-serif; color: #2d2d2d; }
        .wrapper { max-width: 560px; margin: 32px auto; background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(255,99,126,0.12); }
        .header { background: linear-gradient(135deg, #FF637E 0%, #ff4d6d 100%); padding: 28px 32px; text-align: center; }
        .header h1 { margin: 0; color: #fff; font-size: 1.7rem; letter-spacing: 1px; }
        .header p { margin: 6px 0 0; color: rgba(255,255,255,0.85); font-size: 0.9rem; }
        .hero { padding: 28px 32px 0; text-align: center; }
        .hero .check { font-size: 2.8rem; }
        .hero h2 { margin: 10px 0 4px; color: #FF637E; font-size: 1.4rem; }
        .hero p { color: #888; font-size: 0.9rem; margin: 0; }
        .ticket { margin: 24px 28px; border: 2px dashed #f5c2cc; border-radius: 14px; overflow: hidden; }
        .ticket-header { background: #fff0f3; padding: 14px 20px; border-bottom: 2px dashed #f5c2cc; text-align: center; }
        .ticket-header .movie { font-size: 1.2rem; font-weight: 700; color: #2d2d2d; margin: 0; }
        .ticket-body { padding: 20px; }
        .info-grid { display: table; width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        .info-cell { display: table-cell; width: 33.33%; text-align: center; padding: 8px 4px; }
        .label { font-size: 0.65rem; font-weight: 700; letter-spacing: 1px; color: #FF637E; text-transform: uppercase; margin: 0 0 4px; }
        .value { font-size: 0.95rem; font-weight: 600; color: #2d2d2d; margin: 0; }
        .divider { border: none; border-top: 1px dashed #f5c2cc; margin: 14px 0; }
        .seats-section { text-align: center; margin-bottom: 14px; }
        .seat-badge { display: inline-block; background: #fff0f3; border: 1px solid #f5c2cc; border-radius: 6px; padding: 4px 10px; margin: 3px; font-size: 0.85rem; font-weight: 600; color: #FF637E; }
        .client-row { display: table; width: 100%; }
        .client-cell { display: table-cell; width: 50%; text-align: center; }
        .total-value { font-size: 1.5rem; font-weight: 700; color: #28a745; }
        .qr-section { text-align: center; padding: 16px 0 8px; }
        .qr-section img { border: 3px solid #f5c2cc; border-radius: 10px; padding: 6px; background: #fff; }
        .qr-label { font-size: 0.7rem; color: #aaa; margin: 6px 0 0; }
        .ticket-ids { text-align: center; padding: 0 0 14px; }
        .ticket-ids p { font-size: 0.75rem; color: #aaa; margin: 2px 0; }
        .ticket-footer { background: #fff0f3; padding: 12px 20px; text-align: center; font-size: 0.8rem; color: #888; border-top: 2px dashed #f5c2cc; }
        .footer-main { padding: 20px 32px; text-align: center; }
        .footer-main p { font-size: 0.8rem; color: #aaa; margin: 4px 0; }
        .badge-tipo { display: inline-block; background: #FF637E; color: #fff; border-radius: 4px; padding: 1px 6px; font-size: 0.65rem; font-weight: 700; vertical-align: middle; margin-left: 4px; }
    </style>
</head>
<body>
    <div class="wrapper">
        {{-- Header --}}
        <div class="header">
            <h1>🎬 CINEAPP</h1>
            <p>Tu entrada de cine digital</p>
        </div>

        {{-- Hero --}}
        <div class="hero">
            <div class="check">✅</div>
            <h2>¡Compra Confirmada!</h2>
            <p>Presenta este código QR en taquilla para acceder a la sala</p>
        </div>

        {{-- Ticket --}}
        <div class="ticket">
            <div class="ticket-header">
                <p class="movie">{{ $compra['pelicula'] }}</p>
            </div>

            <div class="ticket-body">

                {{-- Fecha / Hora / Sala --}}
                <div class="info-grid">
                    <div class="info-cell">
                        <p class="label">FECHA</p>
                        <p class="value">{{ \Carbon\Carbon::parse($compra['fecha_funcion'])->format('d/m/Y') }}</p>
                    </div>
                    <div class="info-cell">
                        <p class="label">HORA</p>
                        <p class="value">{{ \Carbon\Carbon::parse($compra['hora_funcion'])->format('H:i') }}</p>
                    </div>
                    <div class="info-cell">
                        <p class="label">SALA</p>
                        <p class="value">
                            {{ $compra['sala'] }}
                            <span class="badge-tipo">{{ $compra['tipo_sala'] }}</span>
                        </p>
                    </div>
                </div>

                <hr class="divider">

                {{-- Asientos --}}
                <div class="seats-section">
                    <p class="label" style="margin-bottom:8px;">ASIENTO(S)</p>
                    @foreach($compra['asientos'] as $asiento)
                        <span class="seat-badge">{{ $asiento }}</span>
                    @endforeach
                </div>

                <hr class="divider">

                {{-- Cliente y Total --}}
                <div class="client-row">
                    <div class="client-cell">
                        <p class="label">CLIENTE</p>
                        <p class="value" style="font-size:0.9rem;">{{ $compra['cliente'] }}</p>
                    </div>
                    <div class="client-cell">
                        <p class="label">TOTAL PAGADO</p>
                        <p class="total-value">${{ number_format($compra['total'], 2) }}</p>
                    </div>
                </div>

                <hr class="divider">

                {{-- QR Code --}}
                <div class="qr-section">
                    <img src="data:image/svg+xml;base64,{{ $qrBase64 }}" alt="QR Boleto" width="160" height="160">
                    <p class="qr-label">Escanea este código en taquilla</p>
                </div>

                {{-- IDs --}}
                <div class="ticket-ids">
                    <p>Boleto(s) #{{ implode(', #', $compra['ids']) }}</p>
                    <p>Comprado: {{ \Carbon\Carbon::parse($compra['fecha_compra'])->format('d/m/Y H:i') }}</p>
                </div>
            </div>

            <div class="ticket-footer">
                ℹ️ Pago en efectivo en taquilla · Presenta este boleto al ingresar
            </div>
        </div>

        {{-- Footer --}}
        <div class="footer-main">
            <p>Este correo fue enviado automáticamente. No es necesario responderlo.</p>
            <p>© {{ date('Y') }} CineApp — Todos los derechos reservados</p>
        </div>
    </div>
</body>
</html>
