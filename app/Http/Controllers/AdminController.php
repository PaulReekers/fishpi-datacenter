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
            (object)["id" => "testrunlights", "name" => "Run a test on the lights"],
            (object)["id" => "settemp", "name" => "Set the temp"],
            (object)["id" => "askip", "name" => "Retrieve the IP"],
            (object)["id" => "compose", "name" => "Compose you're leds"],
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
            (object)["id" => "white", "name" => "White"],
            (object)["id" => "blue", "name" => "Blue"],
            (object)["id" => "lamp1", "name" => "Light 1"],
            (object)["id" => "lamp2", "name" => "Light 2"],
            (object)["id" => "lamp3", "name" => "Light 3"],
        ];

        $alarmtemp = Setting::where("name","=","alarmtemp")->first();
        $criticaltemp = Setting::where("name","=","criticaltemp")->first();
        $lastIP = Setting::where("name","=","ip")->first();

        $vw = view('admin.index');
        $vw->commands = $this->commandList;
        $vw->alarmtemp = $alarmtemp ? $alarmtemp->value : 23000;
        $vw->criticaltemp = $criticaltemp ? $criticaltemp->value : 25000;
        $vw->lastIP = $lastIP ? $lastIP->value : "0.0.0.0";
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
            case "testrunlights":
            case "testrun":
                $json = [];
            break;
            case "compose":
                $json = json_decode($request->json);
            break;
            case "settemp":
                $json = [
                    "alarmtemp" => (int)$request->alarmtemp,
                    "criticaltemp" => (int)$request->criticaltemp
                ];
            break;
            case "askip":
                $json = [];
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
