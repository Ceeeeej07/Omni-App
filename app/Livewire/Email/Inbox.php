<?php

namespace App\Livewire\Email;

use Livewire\Component;
use App\Models\Email;
use Illuminate\Support\Facades\Auth;

class Inbox extends Component
{
    public $emails;

    public function mount()
    {
        $this->emails = Email::where('type', 'received')->orderBy('created_at', 'desc')->get();
        
        $this->emails = Email::where('recipient_email', Auth::user()->email)
            ->whereNotNull('sender_email')
            ->where('type', 'received')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function render()
    {
        return view('livewire.email.inbox', [
            'emails' => $this->emails,
        ]);
    }
}
