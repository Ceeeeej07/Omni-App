<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Twilio\Jwt\AccessToken;
use Twilio\Jwt\Grants\VoiceGrant;

class TwilioTokenController extends Controller
{
    public function generateToken()
    {
        $accountSid = env('TWILIO_SID');
        $authToken = env('TWILIO_TOKEN'); // No API Key required
        $twilioAppSid = env('TWILIO_APP_SID'); // TwiML App SID
        $identity = 'browser_user'; 

        // Create access token
        $token = new AccessToken(
            $accountSid,
            $authToken,  // Twilio requires API Secret, but if it's not needed, just pass Auth Token
            3600, // Token expiry in seconds
            $identity
        );

        // Create Voice Grant
        $voiceGrant = new VoiceGrant();
        $voiceGrant->setOutgoingApplicationSid($twilioAppSid);
        $voiceGrant->setIncomingAllow(true); // Allow incoming calls

        // Attach grant to token
        $token->addGrant($voiceGrant);

        return response()->json(['token' => $token->toJWT()]);
    }
}
