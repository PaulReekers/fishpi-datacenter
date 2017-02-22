<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FacebookMessageResponseSender;
use Log;


class BotController extends Controller
{

  public function check(Request $request)
  {
    if($request->get('hub_verify_token') == env('VERIFY_TOKEN')) {
      return $request->get('hub_challenge');
    }
    return 'Wrong validation token';
  }

  public function receive(Request $request, FacebookMessageResponseSender $sender)
  {
    $incomingMessages = $request->get('entry');
    if (!$incomingMessages) {
      return;
    }

    Log::info(json_encode($incomingMessages));
    if (!is_array($incomingMessages)) {
      Log::notice('incoming message not an array');
      return;
    }
    foreach ($incomingMessages as $key => $value) {
      if (!isset($value["messaging"]) || !is_array($value["messaging"])) {
        Log::notice('missing messaging object');
        continue;
      }
      foreach ($value["messaging"] as $message) {
        if (
          !isset($message["sender"]) ||
          !isset($message["sender"]["id"]) ||
          !isset($message["message"]) ||
          !isset($message["message"]["text"])
        ) {
          Log::notice('missing sender id or message text');
          continue;
        }
        $this->sendMessage($message["sender"]["id"], $message["message"]["text"]);
      }
    }
  }

  private function sendMessage($to, $text)
  {
    $quote = "My quote: ".$text;
    $sender->sendQuote(
      $to,
      $quote
    );
  }

}