<?php

use App\Http\Controllers\TwilioController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes (if needed)

// Protected routes (require authentication)

    Route::post('/send-sms', [TwilioController::class, 'sendSms'])->name('twilio.send-sms');
    Route::post('/make-call', [TwilioController::class, 'makeCall'])->name('twilio.make-call');
    
    // TwiML Routes for Twilio Webhooks
    Route::post('/twilio/incoming-sms', [TwilioController::class, 'incomingSms'])->name('twilio.incoming-sms');
    Route::post('/twilio/incoming-call', [TwilioController::class, 'incomingCall'])->name('twilio.incoming-call');
    Route::post('/twilio/voice-instructions', [TwilioController::class, 'voiceInstructions'])->name('twilio.voice-instructions');
    Route::post('/twilio/call-status', [TwilioController::class, 'callStatus'])->name('twilio.call-status');

