<?php

namespace App\Http\Controllers;

class CommandController extends Controller
{

  public function check(Request $request)
  {
    if($request->get('hub_verify_token') == env('VERIFY_TOKEN')) {
      return $request->get('hub_challenge');
    }
    return 'Wrong validation token';
  }

  public function receive(Request $request)
  {
  }

}