<?php

namespace App\Services\MessageParser;

use App\Services\MessageParser\MessageParser;
use App\Services\MessageParser\MessageParserInterface;
use Log;
use App\Services\APIAIClient;

class APIAIMessageParser extends MessageParser implements MessageParserInterface
{

  public function __construct(APIAIClient $client)
  {
    $this->client = $client;
  }

  /**
   * Handle message
   * @param  Int    $from
   * @param  String $text
   * @return Object
   */
  public function handle($from, $text, $quickReply)
  {
    $response = $this->client->handle($from, $text);
    $this->handleResponse($response);
  }

  /**
   * Handle response
   * @param  Object   $response
   * @return String
   */
  private function handleResponse($response)
  {
    // check if the response has a result
    if (!isset($response->result)) {
      return;
    }

    Log::info(json_encode($response));

    if (isset($response->result->fulfillment)) {
      // check if the response has text to reply
      if (isset($response->result->fulfillment->speech)) {
        $this->responseText = $response->result->fulfillment->speech;
      }
      if (isset($response->result->fulfillment->messages)) {
        foreach ($response->result->fulfillment->messages as $message) {
          if ($message->type == 2) {
            $this->responseText = $message->title;
            $this->responseQuickReplies = $message->replies;
            return;
          }
        }
      }
    }

    // check if the response has text to reply
    if (isset($response->result->speech)) {
      $this->responseText = $response->result->speech;
    }

    // check if the response has an action to call (otherwise return the text we received)
    if (!isset($response->result->action)) {
      return;
    }

    // check if the action we want to call is there (otherwise return the text we receieved)
    $this->responseAction = $response->result->action;

    // get the parameters to send to the function we received
    $this->responseActionParams = ["text" => $this->responseText];
    if (isset($response->result->parameters)) {
      $this->responseActionParams = array_merge($this->responseActionParams, (array) $response->result->parameters);
    }
  }
}