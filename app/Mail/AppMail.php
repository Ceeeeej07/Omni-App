<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Contracts\Queue\ShouldQueue;

class AppMail extends Mailable
{
    use Queueable, SerializesModels;

    public $email; 

    public function __construct($email)
    {
        $this->email = $email;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            to: [$this->email->recipient_email],
            subject: $this->email->subject
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail',
            with: [
                'body' => $this->email->body,
                'attachment' => $this->email->attachment ? asset('storage/' . $this->email->attachment) : null
            ]
        );
    }

    public function attachments(): array
    {
        return !empty($this->email->attachment) 
            ? [Attachment::fromPath(public_path('storage/' . $this->email->attachment))]
            : [];
    }
}
