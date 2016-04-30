<?php

namespace App\Http\Controllers;

use App\Command;
use App\Setting;
use App\Http\Requests;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');

        $this->commandList = [
            (object)["id" => "setled", "name" => "Set a led"],
            (object)["id" => "testrun", "name" => "Run a test"],
            (object)["id" => "settemp", "name" => "Set the temp"],
            (object)["id" => "askip", "name" => "Retrieve the IP"]
        ];
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ledList = [
            (object)["id" => "red", "name" => "Red"],
            (object)["id" => "orange", "name" => "Orange"],
            (object)["id" => "green", "name" => "Green"],
        ];

        $alarmtemp = Setting::where("name","=","alarmtemp")->first();
        $criticaltemp = Setting::where("name","=","criticaltemp")->first();

        $vw = view('admin.index');
        $vw->commands = $this->commandList;
        $vw->alarmtemp = $alarmtemp ? $alarmtemp->value : 23000;
        $vw->criticaltemp = $criticaltemp ? $criticaltemp->value : 25000;
        $vw->leds = $ledList;
        return $vw;
    }

    public function commands()
    {

        $data = Command::orderBy("created", "desc")->get();

        $vw = view('admin.commands');
        $vw->commands = $data;
        return $vw;
    }

    public function store(Request $request)
    {
        $availableCommands = array_map(function($item){
            return $item->id;
        },$this->commandList);
        if (!in_array($request->command, $availableCommands)) {
            return redirect('admin');
        }

        switch ($request->command) {
            case "setled":
                $json = [
                    "led" => $request->led,
                    "clear" => ($request->clear == "on"),
                    "time" => $request->ledtime,
                ];
            break;
            case "testrun":
                $json = [];
            break;
            case "settemp":
                $json = [
                    "alarmtemp" => (int)$request->alarmtemp,
                    "criticaltemp" => (int)$request->criticaltemp
                ];            
            break;
        }

        $command = new Command;
        $command->command = $request->command;
        $command->data = json_encode($json);
        $command->executed = false;
        $command->save();

        return redirect('admin/commands');
    }
}
