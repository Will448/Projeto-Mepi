<?php

// ============================================================
// ARQUIVO 2: app/Mail/ReservaStatusMail.php
// ============================================================
namespace App\Mail;

use App\Models\ReservaEquipamento;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReservaStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public ReservaEquipamento $reserva) {}

    public function envelope(): Envelope
    {
        $status = $this->reserva->status === 'aprovado' ? 'Aprovada ✅' : 'Negada ❌';
        return new Envelope(subject: "MEPI — Reserva de EPI {$status}");
    }

    public function content(): Content
    {
        return new Content(view: 'emails.reserva-status');
    }
}