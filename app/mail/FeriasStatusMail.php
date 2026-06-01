<?php
// ============================================================
// ARQUIVO 1: app/Mail/FeriasStatusMail.php
// ============================================================
namespace App\Mail;

use App\Models\Ferias;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FeriasStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Ferias $ferias) {}

    public function envelope(): Envelope
    {
        $status = $this->ferias->status === 'aprovado' ? 'Aprovadas ✅' : 'Negadas ❌';
        return new Envelope(subject: "MEPI — Férias {$status}");
    }

    public function content(): Content
    {
        return new Content(view: 'emails.ferias-status');
    }
}
