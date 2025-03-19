<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Call;
use Illuminate\Support\Facades\Http;

class CallComponent extends Component
{
    public $phoneNumber;
    public $message;
    public $calls = [];
    public $success = null;
    public $error = null;

    protected $rules = [
        'phoneNumber' => 'required|string|min:10',
        'message' => 'nullable|string|max:500',
    ];

    public function mount()
    {
        $this->loadCalls();
    }

    public function loadCalls()
    {
        $this->calls = Call::orderBy('created_at', 'desc')
            ->take(50)
            ->get()
            ->toArray();
    }

    public function makeCall()
    {
        $this->validate();

        try {
            $response = Http::post(route('twilio.make-call'), [
                'to' => $this->phoneNumber,
                'message' => $this->message,
            ]);

            if ($response->successful()) {
                $this->success = 'Call initiated successfully!';
                $this->error = null;
                $this->message = '';
                $this->loadCalls();
            } else {
                $this->error = 'Failed to initiate call: ' . $response->json('message');
                $this->success = null;
            }
        } catch (\Exception $e) {
            $this->error = 'An error occurred: ' . $e->getMessage();
            $this->success = null;
        }
    }

    public function render()
    {
        return view('livewire.call-component');
    }
}