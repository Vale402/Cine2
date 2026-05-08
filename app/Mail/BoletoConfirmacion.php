<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class BoletoConfirmacion extends Mailable
{
    use Queueable, SerializesModels;

    public array $compra;
    public string $qrBase64;

    public function __construct(array $compra)
    {
        $this->compra = $compra;

        $qrData = implode("\n", [
            'CINEAPP - Boleto de Entrada',
            'IDs: ' . implode(', ', $compra['ids']),
            'Pelicula: ' . $compra['pelicula'],
            'Fecha: ' . \Carbon\Carbon::parse($compra['fecha_funcion'])->format('d/m/Y'),
            'Hora: ' . \Carbon\Carbon::parse($compra['hora_funcion'])->format('H:i'),
            'Sala: ' . $compra['sala'] . ' (' . $compra['tipo_sala'] . ')',
            'Asientos: ' . implode(', ', $compra['asientos']),
        ]);

        $this->qrBase64 = base64_encode(
            QrCode::size(180)->margin(1)->generate($qrData)
        );
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🎬 Confirmación de Compra - ' . $this->compra['pelicula'],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.boleto_confirmacion',
        );
    }
}
