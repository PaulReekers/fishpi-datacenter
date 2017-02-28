<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;

class FacebookWebhookTest extends Command
{

    protected $signature = 'facebook:webhooktest {host} {from} {text}';
    protected $description = 'Test a webhook message with text of facebook';

    private $client = null;

    public function __construct(Client $client)
    {
        $this->client = $client;
        parent::__construct();
    }

    public function handle()
    {
      $host = $this->argument('host');
      $fromId = $this->argument('from');
      $text = $this->argument('text');

      $this->sendTestMessage($host, $fromId, $text);
    }

    /**
     * Send test messsage to webhook
     * @param  String   $host
     * @param  Int      $fromId
     * @param  String   $text
     */
    private function sendTestMessage($host, $fromId, $text)
    {
      $id = uniqid();
      // create fake facebook message
      $message = [[
        "id" => $id,
        "time" => time(),
        "messaging" => [
          [
            "sender" => [
              "id" => $fromId
            ],
            "recipient" => [
              "id" => $id
            ],
            "timestamp" => time(),
            "message" => [
              "mid" => md5($id),
              "seq" => 4164,
              "text" => $text
            ]
          ]
        ]
      ]];

      // send fake message
      $this->client->request(
        'POST',
        $host,
        ["query" => ["entry" => $message]]
      );
    }
}