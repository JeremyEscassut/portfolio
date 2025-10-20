<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactMessage extends Mailable
{
    use Queueable, SerializesModels;

    public $payload;

    /**
     * Create a new message instance.
     *
     * @param array $payload  // ['name'=>..., 'email'=>..., 'message'=>...]
     */
    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }

    public function build()
    {
        $subjectName = $this->payload['name'] ?? null;
        $subject = 'Message via portfolio';
        if ($subjectName) {
            $subject .= ' — ' . trim($subjectName);
        }
        return $this->subject($subject)
            ->replyTo($this->payload['email'])
            ->view('emails.contact')
            ->with('payload', $this->payload);
    }
}
