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



    
require __DIR__.'/auth.php';
