<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;

class FacebookWebhookTest extends Command
{

    protected $signature = 'facebook:webhooktest {--host=} {--from=} {--text=} {--json=}';
    protected $description = 'Test a webhook message with text of facebook';

    private $client = null;

    public function __construct(Client $client)
    {
        $this->client = $client;
        parent::__construct();
    }

    public function handle()
    {
      $host = $this->option('host');
      if (!$host) {
        $host = 'http://localhost:8000/webhook';
      }
      $fromId = $this->option('from');
      $text = $this->option('text');
      $json = $this->option('json');

      if ($text && $fromId) {
        $json = $this->sendTestMessage($fromId, $text);
      }
      if ($json) {
        $json = json_decode($json,true);
        $this->sendJson($host, $json);
      }
    }

    /**
     * Send test messsage to webhook
     * @param  String   $host
     * @param  Int      $fromId
     * @param  String   $text
     */
    private function jsonFromMessage($fromId, $text)
    {
      $id = uniqid();
      // create fake facebook message
      $message = [
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
      ];
      return $message;
    }

    private function sendJson($host, $message)
    {
      // send fake message
      $this->client->request(
        'POST',
        $host,
        ["query" => ["entry" => $message]]
      );
    }
}