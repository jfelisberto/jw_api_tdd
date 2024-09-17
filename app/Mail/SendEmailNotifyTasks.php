<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendEmailNotifyTasks extends Mailable
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
    // public function __construct(public $user, public $task_id, public $task, public $subject)
    public function __construct(public $object)
    {
        //
    }

    /**
     * Obtem o envelope da mensagem.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Gerenciamento de tarefas',
        );
    }

    /**
     * Obtem a definicao de conteúdo da mensagem.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.send-email-html-notify-tasks',
            text: 'emails.send-email-text-notify-tasks',
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
