<?php

namespace App\Http\Controllers;

use Response;
use App\Command;
use Carbon\Carbon;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class CommandController extends Controller
{

  public function __construct()
  {
    $this->middleware('auth.basic');
  }

  public function index()
  {
    $items = Command::where("executed", "=", 0)->orderBy("created", "asc")->limit(1)->get();

    if (count($items) > 0) {
      $data = $items[0];
      $response = [
        "command" => $data->command,
        "data" => json_decode($data->data)
      ];

      $data->executed = 1;
      $data->save();
    } else {
      $response = [];
    }
        
    return Response::json($response, 200);
  }
    
}
