<?php

namespace App\Services;

use GuzzleHttp\Client;
use Log;

class FacebookMessageResponseSender
{
    public function sendQuote($recipientUserId, $message)
    {
        $client = new Client(['base_uri' => 'https://graph.facebook.com/v2.6/']);

        try {
            $client->request(
                'POST',
                'me/messages',
                [
                    'query' => ['access_token' => env('FACEBOOK_PAGE_ACCESS_TOKEN')],
                    'json' => [
                        'recipient' => [
                            'id' => $recipientUserId
                        ],
                        'message' => [
                            'text' => $message
                        ]
                    ]
                ]
            );
        } catch (\Exception $e) {
            Log::notice('Sending went wrong with message: '.$e->getMessage());
            return false;
        }
        return true;
    }
}
