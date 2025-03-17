<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;


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


Route::view('mailbox', 'mailbox')
    ->middleware(['auth', 'verified'])
    ->name('mailbox');

    
require __DIR__.'/auth.php';
