<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Webklex\IMAP\Facades\Client;
use App\Models\Email;

class FetchEmails extends Command
{
    protected $signature = 'email:fetch';
    protected $description = 'Fetch new emails from Gmail and store them in the database';

    public function handle()
    {
        $client = Client::account('default'); // Connect to IMAP
        $client->connect();

        $folder = $client->getFolder('INBOX'); // Get Inbox
        $messages = $folder->messages()->unseen()->get(); // Only fetch unread emails

        foreach ($messages as $message) {
            Email::create([
                'user_id' => null, // No sender user_id available
                'recipient_email' => env('MAIL_FROM_ADDRESS'), // Your email
                'subject' => $message->getSubject(),
                'body' => $message->getHTMLBody() ?? $message->getTextBody(),
                'attachment' => null, // Handle attachments later
                'type' => 'received', // Mark as received email
            ]);

            $message->setFlag('Seen'); // Mark email as read
        }

        $this->info("Fetched " . count($messages) . " new emails.");
    }
}
