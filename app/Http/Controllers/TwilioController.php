<?php

namespace App\Http\Controllers;

use App\Models\Sms;
use App\Models\Call;
use Twilio\Rest\Client;
use Illuminate\Http\Request;
use Twilio\TwiML\VoiceResponse;
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
        // Store the incoming message
        Sms::create([
            'sid' => $request->MessageSid,
            'to' => $request->To,
            'from' => $request->From,
            'body' => $request->Body,
            'status' => 'received',
            'direction' => 'inbound',
        ]);

        // Create a response
        $response = new MessagingResponse();
        // You can add an auto-reply if needed
        // $response->message("Thanks for your message! We'll get back to you soon.");
        
        return response($response->__toString(), 200)
            ->header('Content-Type', 'text/xml');
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
        // Store the incoming call
        Call::create([
            'sid' => $request->CallSid,
            'to' => $request->To,
            'from' => $request->From,
            'status' => 'ringing',
            'direction' => 'inbound',
        ]);
        
        $response = new VoiceResponse();
        
        // You can greet the caller
        $response->say('Thank you for calling. Your call is being processed.');
        
        // You could add a gather to collect user input
        // $gather = $response->gather(['numDigits' => 1, 'action' => route('twilio.process-input')]);
        // $gather->say('Press 1 to speak with an agent, Press 2 to leave a message.');
        
        // Or you could record a message
        // $response->record(['maxLength' => 30, 'action' => route('twilio.save-recording')]);
        
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
