<?php

namespace App\Http\Controllers;

use App\Models\Sms;
use App\Models\Call;
use Twilio\Rest\Client;
use Illuminate\Http\Request;
use Twilio\TwiML\VoiceResponse;
use App\Events\IncomingCallEvent;
use Illuminate\Support\Facades\Log;
use Twilio\TwiML\MessagingResponse;

class TwilioController extends Controller
{
    protected $twilioClient;
    protected $twilioNumber;

    public function call()
    {
        return view('call');
    }

    public function sms()
    {
        return view('sms');
    }

    public function __construct()
    {
        $this->twilioClient = new Client(
            config('services.twilio.sid'),
            config('services.twilio.token')
        );
        $this->twilioNumber = config('services.twilio.number');
    }

    /**
     * Send SMS using Twilio
     */
    public function sendSms(Request $request)
    {
        $request->validate([
            'to' => 'required|string',
            'body' => 'required|string'
        ]);

        try {
            $message = $this->twilioClient->messages->create(
                $request->to,
                [
                    'from' => $this->twilioNumber,
                    'body' => $request->body,
                ]
            );

            // Store message in database
            Sms::create([
                'sid' => $message->sid,
                'to' => $request->to,
                'from' => $this->twilioNumber,
                'body' => $request->body,
                'status' => $message->status,
                'direction' => 'outbound',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'SMS sent successfully',
                'sid' => $message->sid,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Handle incoming SMS webhook from Twilio
     */
    public function incomingSms(Request $request)
    {
        Log::info('Incoming SMS Request:', $request->all());

        try {
            // Check for required MessageSid
            if (!$request->has('MessageSid')) {
                return response()->json([
                    'success' => false,
                    'message' => 'MessageSid is required',
                ], 400);
            }
        
            // Check for duplicate SMS
            if (Sms::where('sid', $request->MessageSid)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Duplicate SMS detected, ignoring request',
                ], 409);
            }
        
            // Save the incoming SMS
            Sms::create([
                'sid' => $request->MessageSid,
                'to' => $request->To,
                'from' => $request->From,
                'body' => $request->Body,
                'status' => 'received',
                'direction' => 'inbound',
            ]);
        
            // Prepare Twilio XML response
            $response = new MessagingResponse();
            $response->message("SMS received successfully!");
        
            return response($response->__toString(), 200)
                ->header('Content-Type', 'text/xml');
        } catch (\Exception $e) {
            Log::error('Error saving SMS: ' . $e->getMessage());
        
            return response()->json([
                'success' => false,
                'message' => 'Failed to save SMS: ' . $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Make an outbound call using Twilio
     */
    public function makeCall(Request $request)
    {
        $request->validate([
            'to' => 'required|string',
            'message' => 'nullable|string',
        ]);

        try {
            $call = $this->twilioClient->calls->create(
                $request->to,
                $this->twilioNumber,
                [
                    'url' => route('twilio.voice-instructions'),
                    'statusCallback' => route('twilio.call-status'),
                    'statusCallbackMethod' => 'POST',
                ]
            );

            // Store call in database
            Call::create([
                'sid' => $call->sid,
                'to' => $request->to,
                'from' => $this->twilioNumber,
                'status' => $call->status,
                'direction' => 'outbound',
                'message' => $request->message ?? null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Call initiated successfully',
                'sid' => $call->sid,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Provide voice instructions for outbound calls
     */
    public function voiceInstructions(Request $request)
    {
        $response = new VoiceResponse();
        
        // Get the message from the database if available
        $call = Call::where('sid', $request->CallSid)->first();
        $message = $call && $call->message 
            ? $call->message 
            : 'This is an automated call from our application. Thank you for your time.';
        
        $response->say($message);
        
        return response($response->__toString(), 200)
            ->header('Content-Type', 'text/xml');
    }

    /**
     * Handle incoming call webhook
     */
    public function incomingCall(Request $request)
{
    $call = Call::create([
        'sid' => $request->CallSid,
        'to' => $request->To,
        'from' => $request->From,
        'status' => 'ringing',
        'direction' => 'inbound',
    ]);

    // Broadcast event to Livewire
    broadcast(new IncomingCallEvent($call))->toOthers();

    $response = new VoiceResponse();
    $response->say('You have an incoming call.');

    return response($response->__toString(), 200)
        ->header('Content-Type', 'text/xml');
}

    /**
     * Handle call status updates
     */
    public function callStatus(Request $request)
    {
        // Update call status in database
        Call::where('sid', $request->CallSid)
            ->update(['status' => $request->CallStatus]);
        
        return response('', 204);
    }
}
