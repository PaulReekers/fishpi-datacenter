<?php

namespace App\Http\Controllers;

use Response;
use App\Command;
use App\Setting;
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
      $commandData = json_decode($data->data);

      switch($data->command) {
        case "settemp":
          $setting = Setting::firstOrNew(['name' => 'alarmtemp']);
          $setting->value = $commandData->alarmtemp;
          $setting->save();

          $setting = Setting::firstOrNew(['name' => 'criticaltemp']);
          $setting->value = $commandData->criticaltemp;
          $setting->save();
        break;
      }

      $response = [
        "command" => $data->command,
        "data" => $commandData
      ];

      $data->executed = 1;
      $data->save();
    } else {
      $response = [];
    }
        
    return Response::json($response, 200);
  }

  public function getip(Request $request)
  {
    $setting = Setting::firstOrNew(['name' => 'ip']);
    $setting->value = $request->ip;
    $setting->save();
  }
    
}
