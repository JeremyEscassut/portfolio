<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendTestMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:test {--to=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a simple test email to the configured contact address (or --to)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $to = $this->option('to') ?: config('mail.contact_address') ?: env('MAIL_CONTACT_ADDRESS');

        if (! $to) {
            $this->error('No recipient configured. Set MAIL_CONTACT_ADDRESS in your .env or pass --to.');
            return 1;
        }

        $this->info("Sending test mail to: {$to}");

        try {
            Mail::raw('Test email from Portfolio (local test).', function ($message) use ($to) {
                $message->to($to)->subject('Portfolio — Test email');
            });

            $this->info('Test email envoyé avec succès. Vérifiez MailHog (http://127.0.0.1:8025) ou les logs dans storage/logs/laravel.log.');
            return 0;
        } catch (\Exception $e) {
            $this->error('Exception while sending mail: ' . $e->getMessage());
            return 1;
        }
    }
}
