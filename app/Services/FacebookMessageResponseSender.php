<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;
use Log;

class FacebookMessageResponseSender
{
    /**
     * Set a quote via FB to a user
     * @param  Int      $recipientUserId
     * @param  String   $message
     * @return Bool
     */
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
                            'text' => $message,
                        ]
                    ],
                ]
            );
        } catch (\Exception $e) {
            Log::notice((string)$e);
            Log::notice('Sending went wrong with message: '.$e->getMessage());
            return false;
        }
        return true;
    }

    /**
     * Send image
     * @param  Int      $recipientUserId
     * @param  String   $file
     * @return Bool
     */
    public function sendImage($recipientUserId, $file)
    {
        $fileContent = fopen(storage_path('app/'.$file), 'r');
        $fileMimeType = Storage::mimeType($file);
        $fileName = basename($file);

        $client = new Client(['base_uri' => 'https://graph.facebook.com/v2.6/']);
        try {
            $client->request(
                'POST',
                'me/messages',
                [
                    'query' => ['access_token' => env('FACEBOOK_PAGE_ACCESS_TOKEN')],
                    'multipart' => [
                        [
                            "name" => "recipient",
                            "contents" => json_encode(["id" => $recipientUserId])
                        ],[
                            "name" => "message",
                            "contents" => json_encode(["attachment" => [ "type" => "image", "payload" => []]])
                        ],[
                            "name" => "filedata",
                            "filename" => $fileName,
                            "Mime-Type" => $fileMimeType,
                            "contents" => $fileContent
                        ]
                    ]
                ]
            );
        } catch (\Exception $e) {
            Log::notice((string)$e);
            Log::notice('Sending went wrong with message: '.$e->getMessage());
            return false;
        }
        return true;
    }
}
