<?php

namespace App\Livewire\Email;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Email;
use Illuminate\Support\Facades\Mail;
use App\Mail\AppMail;

class Compose extends Component
{
    use WithFileUploads;

    public $recipient_email, $subject, $body, $attachment;

    public function sendEmail()
    {
        $this->validate([
            'recipient_email' => 'required|email',
            'subject' => 'required|string|max:255',
            'body' => 'required|string|min:5',
            'attachment' => 'nullable|file|max:2048',
        ]);

        $attachmentPath = null;

        if ($this->attachment) {
            $attachmentPath = $this->attachment->store('attachments', 'public');
        }

        $email = Email::create([
            'user_id' => auth()->id(),
            'recipient_email' => $this->recipient_email,
            'subject' => $this->subject,
            'body' => nl2br($this->body),
            'attachment' => $attachmentPath ? 'attachments/' . basename($attachmentPath) : null,
            'type' => 'sent',
        ]);

        Mail::to($this->recipient_email)->send(new AppMail($email));

        // 🔥 Fix: Force Livewire to reset the file input
        $this->reset(['recipient_email', 'subject', 'body', 'attachment']);
        $this->dispatch('emailSent');

        // 🔥 Fix: Reload the Livewire component to prevent caching issues
        $this->dispatch('refreshComponent');
    }


    public function render()
    {
        return view('livewire.email.compose');
    }
}
