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
      $incomingMessageText = $incomingMessages[0]['messaging'][0]['message']['text'];
      $incomingMessageSenderId = $incomingMessages[0]['messaging'][0]['sender']['id'];
      //if($this->isAskingForQuote($incomingMessageText)) {
        $quote = "My quote: ".$incomingMessageText;
        $sender->sendQuote(
          $incomingMessageSenderId,
          $quote
        );
      //}
  }

}