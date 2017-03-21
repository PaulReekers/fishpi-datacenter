<?php

namespace App\Services;

use GuzzleHttp\Client;

class APIAIClient
{
  private $key = null;

  /**
   * @param Client  $client
   * @param Token   $key
   */
  public function __construct(Client $client, $key)
  {
    $this->key = $key;
    $this->client = $client;
  }

  /**
   * Handle call to API.AI
   * @param  [type] $from [description]
   * @param  [type] $text [description]
   * @return [type]       [description]
   */
  public function handle($from, $text)
  {
    return json_decode($this->call($from, $text)->getBody());
  }

  /**
   * Call API.AI
   * @param  SessionID  $from
   * @param  Text       $text
   * @return Array
   */
  private function call($from, $text)
  {
    $params = [
      'headers' => [
        'Authorization' => 'Bearer '.$this->key,
        'Accept' => 'application/json'
      ],
      'query' => [
        'v' => '20150910',
        'sessionId' => $from,
        'query' => $text,
        'lang' => 'en',
      ]
    ];

    return $this->client->get('/api/query', $params);
  }
}