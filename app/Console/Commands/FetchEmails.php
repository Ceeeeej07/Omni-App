<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Webklex\IMAP\Facades\Client;
use App\Models\Email;
use Illuminate\Support\Facades\Log;

class FetchEmails extends Command
{
    protected $signature = 'email:fetch';
    protected $description = 'Fetch new emails from IMAP inbox';

    public function handle()
    {
        $client = Client::make([
            'host'          => env('IMAP_HOST'),
            'port'          => env('IMAP_PORT'),
            'encryption'    => env('IMAP_ENCRYPTION'),
            'validate_cert' => env('IMAP_VALIDATE_CERT', true),
            'username'      => env('IMAP_USERNAME'),
            'password'      => env('IMAP_PASSWORD'),
            'protocol'      => 'imap'
        ]);

        try {
            $client->connect();
            $inbox = $client->getFolder('INBOX');
            $messages = $inbox->messages()->unseen()->limit(10)->get();

            foreach ($messages as $message) {
                Email::create([
                    'user_id' => null,  
                    'sender_email' => $message->getFrom()[0]->mail,
                    'recipient_email' => env('IMAP_USERNAME'),
                    'subject' => $message->getSubject(),
                    'body' => $message->getTextBody(),
                    'attachment' => null,
                    'type' => 'received',
                ]);

                // Mark email as read
                $message->setFlag('Seen');
            }

            Log::info(count($messages) . " new emails fetched successfully.");
            $this->info(count($messages) . " new emails fetched successfully.");
        } catch (\Exception $e) {
            Log::error('IMAP Fetch Error: ' . $e->getMessage());
            $this->error('IMAP Fetch Error: ' . $e->getMessage());
        }
    }
}
