<?php

namespace App\Services;

use App\Services\FacebookMessageResponseSender;
use Log;

class FacebookMessageHandler
{

  private $sender = null;

  public function __construct(FacebookMessageResponseSender $sender)
  {
    $this->sender = $sender;
  }

  /**
   * Handle incoming messages
   * @param  Array $incomingMessages
   */
  public function handle($incomingMessages)
  {
    if (!$incomingMessages) {
      Log::info("no incoming message");
      return;
    }

    Log::info(json_encode($incomingMessages));
    if (!is_array($incomingMessages)) {
      Log::notice('incoming message not an array');
      return;
    }
    foreach ($incomingMessages as $key => $value) {
      $this->handleMessages($value);
    }
  }

  /**
   * Handle message
   * @param  Array $value
   */
  private function handleMessages($value)
  {
    if (!isset($value["messaging"]) || !is_array($value["messaging"])) {
      Log::notice('missing messaging object');
      return;
    }
    foreach ($value["messaging"] as $message) {
      $this->handleMessage($message);
    }
  }

  /**
   * Handle message
   * @param  Array  $message
   */
  private function handleMessage($message)
  {
    if (
      !isset($message["message"]) ||
      (
        isset($message["message"]["is_echo"]) &&
        $message["message"]["is_echo"]
      )
    ) {
      Log::notice('echo, not send to sender');
      return;
    }
    if (
      !isset($message["sender"]) ||
      !isset($message["sender"]["id"]) ||
      !isset($message["message"]["text"])
    ) {
      Log::notice('missing sender id or message text');
      return;
    }
    $this->checkMessage($message["sender"]["id"], $message["message"]["text"]);
  }

  /**
   * Check message text
   * @param  Int    $from
   * @param  String $text
   */
  private function checkMessage($from, $text)
  {

    // checking the messages goes in here
    // for now we just reply with the same message

    $this->sendMessage($from, $text);
  }


  /**
   * Send message
   * @param  Client     $sender
   * @param  Int        $to
   * @param  String     $text
   * @return Bool
   */
  private function sendMessage($to, $text)
  {
    $quote = "My quote: ".$text;
    return $this->sender->sendQuote(
      $to,
      $quote
    );
  }
}
