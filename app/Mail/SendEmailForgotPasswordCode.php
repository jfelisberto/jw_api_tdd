<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendEmailForgotPasswordCode extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Cria uma nova instancia da mensagem.
     *
     * @param mixed $user O usuario para o qual o e-mail sera enviado.
     * @param string $code O código de recuperacao de senha.
     * @param string $formattedDate A data formatada para inclusao no e-mail.
     * @param string $formattedTime A hora formatada para inclusao no e-mail.
     */
    public function __construct(public $user, public $code, public $formattedDate, public $formattedTime)
    {
        //
    }

    /**
     * Obtem o envelope da mensagem.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Recuperar de senha de acesso',
        );
    }

    /**
     * Obtem a definicao de conteúdo da mensagem.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.sendEmailHtmlForgotPasswordCode',
            text: 'emails.sendEmailTextForgotPasswordCode',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
