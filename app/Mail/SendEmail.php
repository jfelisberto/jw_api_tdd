<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendEmail extends Mailable
{
    use Queueable, SerializesModels;

    private $object;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($object)
    {
        $this->object = $object;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->to($this->object->email);

        /**
         * Fazendo uso do template adequado conforme o objeto template
         */
        if ($this->object->template == 'notfy-tasks') {
            return $this->subject('Gerenciamento de tarefas')
                ->view(
                    'emails.send-email-html-notify-tasks',
                    ['content' => $this->object]
                );
        }

        if ($this->object->template == 'forgot-password') {
            return $this->subject('Recuperar de senha de acesso')
                ->view(
                    'emails.send-email-html-forgot-password-code',
                    ['content' => $this->object]
                );
        }
    }

}
