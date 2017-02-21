<?php

namespace App\Services;

use GuzzleHttp\Client;

class FacebookMessageResponseSender
{
    public function sendQuote($recipientUserId, $message)
    {
        $client = new Client(['base_uri' => 'https://graph.facebook.com/v2.6/']);

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
    }
}
