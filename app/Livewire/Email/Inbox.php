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
        // ✅ Fetch emails where the logged-in user is the recipient
        $this->emails = Email::where('recipient_email', Auth::user()->email)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function render()
    {
        return view('livewire.email.inbox');
    }
}
