<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Sms;
use Illuminate\Support\Facades\Http;

class SmsComponent extends Component
{
    public $phoneNumber;
    public $messageBody;
    public $messages = [];
    public $success;
    public $error = null;

    public function render()
    {
        return view('livewire.sms-component');
    }

    protected $rules = [
        'phoneNumber' => 'required|string|min:10',
        'messageBody' => 'required|string|max:1600',
    ];

    public function mount()
    {
        $this->loadMessages();
        $this->success = null;
    }

    public function loadMessages()
    {
        $this->messages = Sms::orderBy('created_at', 'desc')
            ->take(50)
            ->get()
            ->toArray();
    }

    public function sendMessage()
    {
        $this->validate();

        try {
            $response = Http::post(route('twilio.send-sms'), [
                'to' => $this->phoneNumber,
                'body' => $this->messageBody,
            ]);

            if ($response->successful()) {
                $this->success = 'Message sent successfully!';
                $this->error = null;
                $this->messageBody = '';
                $this->loadMessages();
            } else {
                $this->error = 'Failed to send message: ' . $response->json('message');
                $this->success = null;
            }
        } catch (\Exception $e) {
            $this->error = 'An error occurred: ' . $e->getMessage();
            $this->success = null;
        }
    }


}