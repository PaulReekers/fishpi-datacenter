<?php

namespace App\Http\Controllers;

use Response;
use App\FishData;
use Carbon\Carbon;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class FishDataController extends Controller
{

    public function index()
    {
        $data = FishData::paginate(200);
        $response = Response::json($data,200);
        return $response;
    }

    public function store(Request $request)
    {
        if((!$request->time) || (!$request->water) || (!$request->air)){

            $response = Response::json([
                'error' => [
                    'message' => 'Please enter all required fields'
                ]
            ], 422);

            return $response;
        }

         $data = new FishData(array(
            'time' => Carbon::createFromTimestamp($request->time),
            'water' => $request->water,
            'air' => $request->air,
         ));

        $data->save();

        $response = Response::json([
            'message' => 'The post has been created succesfully',
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
                  'message' => 'This data cannot be found.'
             ]
         ], 404);
         return $response;
        }

     $response = Response::json($data, 200);
     return $response;
    }

    public function drawGauge() {

        $data = FishData::orderBy('time', 'desc')->firstOrFail();;

        $response = Response::json($data, 200);
        return $response;
    }
}
