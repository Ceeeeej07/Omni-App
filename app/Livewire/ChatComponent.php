<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Message;
use Livewire\Component;
use App\Models\Attachment;
use Livewire\Attributes\On;
use Illuminate\Http\Request;
use Livewire\WithFileUploads;
use App\Events\MessageSendEvent;

class ChatComponent extends Component
{

    use WithFileUploads;
    public $attachment;
    public $user;
    public $sender_id;
    public $receiver_id;
    public $message = '';
    public $messages = [];
    public function render()
    {
        return view('livewire.chat-component');
    }

    public function mount($user_id)
    {

        //dd($user_id);
        $this->sender_id = auth()->user()->id;
        $this->receiver_id = $user_id;

        $messages = Message::where (function($query)
        {
            $query->where('sender_id', $this->sender_id)
                ->where('receiver_id', $this->receiver_id);
        })->orWhere(function($query)
        {
            $query->where('sender_id', $this->receiver_id)
            ->where('receiver_id', $this->sender_id);
        })->with('sender:id,name', 'receiver:id,name')->get();
    

        foreach($messages as $message)
        {
            $this->chatMessage($message);
        }

        // redirect ni sya sa user nga gi chat
        $this->user = User::find($user_id);
    }

    public function sendMessage()
    {
        $this->validate([
            'message' => 'nullable|string',
            'attachment' => 'nullable|file|max:2048', // Allow files up to 2MB
        ]);

        // Only create a message if text or attachment exists
        if (!empty($this->message) || $this->attachment) {
            $message = new Message();
            $message->sender_id = $this->sender_id;
            $message->receiver_id = $this->receiver_id;
            $message->message = $this->message ?? '';

            // Handle file upload
            if ($this->attachment) {
                $originalFileName = $this->attachment->getClientOriginalName(); // Get original name
                $filePath = $this->attachment->storeAs('attachments', $originalFileName, 'public'); // Store with original name
                $message->attachment = $filePath;
            }
            

            $message->save();
            $this->chatMessage($message);

            // Reset inputs
            $this->message = '';
            $this->attachment = null;

            broadcast(new MessageSendEvent($message))->toOthers();
        }
    }

    

    #[On('echo-private:chat-channel.{sender_id},MessageSendEvent')]
    public function listenForMessage($event)
    {
        $chatMessage = Message::whereId($event['message']['id'])->with('sender:id,name', 'receiver:id,name')->first();
        $this-> chatMessage($chatMessage); 
    }
    public function chatMessage($message)
    {
        $this->messages[] = [
            'id' => $message->id,
            'message' => $message->message,
            'sender' => $message->sender->name,
            'receiver' => $message->receiver->name,
            'attachment' => $message->attachment ? url('storage/' . $message->attachment) : null,
        ];
    }


}
