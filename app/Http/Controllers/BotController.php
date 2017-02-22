<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FacebookMessageResponseSender;


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

    Log::info('incoming message: '.json_encode($incomingMessages));
    if (!is_array($incomingMessages)) {
      return;
    }
    foreach ($incomingMessages as $key => $value) {
      if (!isset($value["messaging"]) || !is_array($value["messaging"])) {
        continue;
      }
      foreach ($value["messaging"] as $message) {
        if (!isset($message["text"]) || !isset($message["id"])) {
          continue;
        }
        $this->sendMessage($message["id"], $message["text"]);
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