<?php

namespace App\Livewire\Email;

use App\Mail\AppMail;
use App\Models\Email;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

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

        $this->attachmentPath = null;
        $this->attachmentName = null;

        if ($this->attachment) {
         
            $this->attachmentName = time() . '_' . $this->attachment->getClientOriginalName();
            $this->attachmentPath = 'attachments/' . $this->attachmentName;
            $this->attachment->storeAs('attachments', $this->attachmentName, 'public');
        }

        $email = Email::create([
            'user_id' => auth()->id(),
            'recipient_email' => $this->recipient_email,
            'subject' => $this->subject,
            'body' => nl2br($this->body),
            'attachment' => $this->attachmentPath,
            'attachment_name' => $attachmentName ?? null,
            'type' => 'sent',
            'sender_email' => auth()->user()->email,
        ]);

        $mailInstance = new AppMail($email);

        // ✅ Attach file if it exists
        if ($this->attachmentPath) {
            $fullPath = Storage::disk('public')->path($this->attachmentPath);
            $mailInstance->attach($fullPath, [
                'as' => $this->attachmentName,
                'mime' => Storage::disk('public')->mimeType($this->attachmentPath),
            ]);
        }

        Mail::to($this->recipient_email)->send($mailInstance);

        $this->reset(['recipient_email', 'subject', 'body', 'attachment']);
        $this->dispatch('emailSent');
        session()->flash('message', 'Email sent successfully!');
    }




    public function render()
    {
        return view('livewire.email.compose');
    }
}
