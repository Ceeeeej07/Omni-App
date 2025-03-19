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
        try {
            $client = Client::account('default');
            $client->connect();

            $folder = $client->getFolder('INBOX'); // Get Inbox
            $messages = $folder->messages()->unseen()->get(); // Fetch only unread emails

            foreach ($messages as $message) {
                Email::create([
                    'user_id' => null, // We don't know the sender’s user ID
                    'recipient_email' => env('MAIL_FROM_ADDRESS'), // ✅ Your email (receiver)
                    'sender_email' => $message->getFrom()[0]->mail ?? 'Unknown Sender', // ✅ Store sender's email
                    'subject' => $message->getSubject() ?? 'No Subject',
                    'body' => $message->getHTMLBody() ?? $message->getTextBody() ?? 'No Content',
                    'attachment' => null, // Handle attachments later
                    'type' => 'received', // ✅ Mark as received email
                ]);

                $message->setFlag('Seen'); // Mark email as read
            }

            $this->info("Fetched " . count($messages) . " new emails.");
        } catch (\Exception $e) {
            $this->error("IMAP Fetch Error: " . $e->getMessage());
        }
    }


}
