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

        if (is_array($message)) {
            $quickreplies = $message["quickreplies"];
            $message = $message["text"];
        }

        $messageData = ['text' => $message];
        if (!empty($quickreplies)) {
            $messageData['quick_replies'] = [];
            foreach ($quickreplies as $key => $quickreply) {
                $messageData['quick_replies'][] = [
                    "content_type" => "text",
                    "title" => $quickreply,
                    "payload" => $key,
                ];
            }
        }

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
                        'message' => $messageData
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

        $host = parse_url($file, PHP_URL_HOST);
        if ($host) {

            $data = [
                'json' => [
                    'recipient' => [
                        'id' => $recipientUserId
                    ],
                    'message' => [
                        'attachment' => [
                            "type" => 'image',
                            'payload' => [
                                "url" => $file,
                                "is_reusable" => true
                            ]
                        ]
                    ]
                ]
            ];

        } else {
            $filePath = resource_path('assets/images/'.$file);
            $fileContent = fopen($filePath, 'r');
            $fileMimeType = mime_content_type($filePath);
            $fileName = basename($file);

            $data = [
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
            ];
        }

        Log::info($data);

        $client = new Client(['base_uri' => 'https://graph.facebook.com/v2.6/']);
        try {
            $client->request(
                'POST',
                'me/messages',
                array_merge(['query' => ['access_token' => env('FACEBOOK_PAGE_ACCESS_TOKEN')]], $data)
            );
        } catch (\Exception $e) {
            Log::notice((string)$e);
            Log::notice('Sending went wrong with message: '.$e->getMessage());
            return false;
        }

        return true;
    }
}
