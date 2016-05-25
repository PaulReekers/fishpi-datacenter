<?php

namespace App\Http\Controllers;

use Response;
use App\Setting;
use App\FishData;
use Carbon\Carbon;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class FishDataController extends Controller
{

    public function store(Request $request)
    {
        $hash = md5($request->air.$request->water.$request->time.env('APP_HASH', ''));

        if(!$request->time || !$request->water || !$request->air || $hash != $request->hash){

            $response = Response::json([
                'error' => [
                    'message' => 'Please enter all required fields.'
                ]
            ], 422);

            return $response;
        }

        if ($request->water < 35000 || $request->air < 40000) {

            $data = new FishData(array(
                'time' => Carbon::createFromTimestamp($request->time),
                'water' => $request->water,
                'air' => $request->air,
            ));

        } else {

            $response = Response::json([
                'error' => [
                    'message' => 'Data out of bounds'
                ]
            ], 422);

            return $response;
        }

        $data->save();

        $response = Response::json([
            'message' => 'The data has been stored succesfully.',
            'data' => $data,
        ], 201);

        return $response;
    }

    /**
     * Give the data from current time to given timestamp
     */
    public function show($from)
    {
        $from = Carbon::createFromTimestamp($from);
        $until = Carbon::now();

        $data = FishData::whereBetween('time', array($from, $until))->get();

        if(!$data){
          $response = Response::json([
              'error' => [
                  'message' => 'There is no data to show.'
             ]
         ], 404);
         return $response;
        }

     $response = Response::json($data, 200);
     return $response;
    }

    public function drawGauge() {

        $data = FishData::orderBy('time', 'desc')->firstOrFail();;

        if(!$data){
          $response = Response::json([
              'error' => [
                  'message' => 'Gauge data could not be found.'
             ]
         ], 404);
         return $response;
        }

        $alarmtemp = Setting::where("name","=","alarmtemp")->first();
        $criticaltemp = Setting::where("name","=","criticaltemp")->first();

        $data['alarmtemp'] = $alarmtemp ? $alarmtemp->value : 23000;
        $data['criticaltemp'] = $criticaltemp ? $criticaltemp->value : 25000;

        $response = Response::json($data, 200);
        return $response;
    }

    public function drawLineChart()
    {
        $data = FishData::orderBy('time', 'desc')->simplePaginate(200);

        if(!$data){
          $response = Response::json([
              'error' => [
                  'message' => 'Linechart could not be found.'
             ]
         ], 404);
         return $response;
        }

        $response = Response::json($data, 200);
        return $response;
    }
}
