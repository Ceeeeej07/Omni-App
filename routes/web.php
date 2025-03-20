<?php

use App\Livewire\Email\Inbox;

use App\Livewire\Email\Compose;
use App\Http\Livewire\SmsComponent;
use App\Http\Livewire\CallComponent;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\TwilioController;


Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');




Route::get('chat-app', [ChatController::class, 'chatapp'])
    ->middleware(['auth', 'verified'])
    ->name('chat-app');

Route::get('chat/{id}', [ChatController::class, 'chat'])
    ->middleware(['auth', 'verified'])
    ->name('chat');


Route::get('mailbox', [MailController::class, 'mailbox'])
    ->middleware(['auth', 'verified'])
    ->name('mailbox');

Route::get('compose', [MailController::class, 'compose'])
    ->middleware(['auth', 'verified'])
    ->name('compose');



Route::get('sms', [TwilioController::class, 'sms'])
    ->middleware(['auth', 'verified'])
    ->name('sms');

Route::get('call', [TwilioController::class, 'call'])
    ->middleware(['auth', 'verified'])
    ->name('call');


// Twilio Controller Routes for API functionality

// Route::middleware('auth:sanctum')->group(function () {
//     Route::post('/send-sms', [TwilioController::class, 'sendSms'])->name('twilio.send-sms');
//     Route::post('/make-call', [TwilioController::class, 'makeCall'])->name('make-call');
// });

// // TwiML Routes for Twilio Webhooks
// Route::post('/twilio/incoming-sms', [TwilioController::class, 'incomingSms'])->name('twilio.incoming-sms');
// Route::post('/twilio/incoming-call', [TwilioController::class, 'incomingCall'])->name('twilio.incoming-call');
// Route::post('/twilio/voice-instructions', [TwilioController::class, 'voiceInstructions'])->name('twilio.voice-instructions');
// Route::post('/twilio/call-status', [TwilioController::class, 'callStatus'])->name('twilio.call-status');
    
require __DIR__.'/auth.php';
