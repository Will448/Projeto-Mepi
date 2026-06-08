<?php

// ============================================================
// ARQUIVO 3: app/Mail/FolhaGeradaMail.php
// ============================================================
namespace App\Mail;

use App\Models\FolhaPagamento;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FolhaGeradaMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public FolhaPagamento $folha) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "MEPI — Holerite {$this->folha->competencia_formatada} disponível"
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.folha-gerada');
    }
}