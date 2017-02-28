<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FacebookMessageHandler;
use Log;


class BotController extends Controller
{

  private $handler = null;

  public function __construct(FacebookMessageHandler $handler)
  {
    $this->handler = $handler;
  }

  /**
   * Facebook check webhook
   * @param  Request $request [description]
   * @return String
   */
  public function check(Request $request)
  {
    if($request->get('hub_verify_token') == env('VERIFY_TOKEN')) {
      return $request->get('hub_challenge');
    }
    return 'Wrong validation token';
  }

  /**
   * Receive webhook data
   * @param  Request $request
   */
  public function receive(Request $request)
  {
    $incomingMessages = $request->get('entry');
    $this->handler->handle($incomingMessages);
  }
}