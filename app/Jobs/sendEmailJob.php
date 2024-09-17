<?php

namespace App\Jobs;

use App\Mail\SendEmail;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class sendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(private $object)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $sendmail = self::executeService(new SendEmail($this->object));
        } catch (Exception $e) {
            $logger = new Logger('SendEmalQueueJob->handle');
            $logger->pushHandler(new StreamHandler(storage_path("logs/jobs/send_email.log")), Logger::INFO);
            $logger->info('Falha ao enviar o e-mail', ['error' => $e]);
        }
    }

    /**
     * Sending the e-mail
     */
    protected function executeService($email)
    {

        try {
            return Mail::send($email);
        } catch (Exception $e) {
            $logger = new Logger('SendEmalQueueJob->executeService');
            $logger->pushHandler(new StreamHandler(storage_path("logs/jobs/send_email.log")), Logger::INFO);
            $logger->info('Falha ao enviar o e-mail', ['error' => $e]);
        }

    }
}
