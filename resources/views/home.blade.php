@extends('layouts.app')
@section('title', 'Welcome to the FishPi Datacenter')

@section('content')
<div class="container">
    <div class="row banner">

        <div class="col-md-12">

            <h1 class="text-center margin-top-100">
                Welcome to the FishPi Datacenter!
            </h1>

            <h3 class="text-center margin-top-100">Keep da fishies happy.</h3>

        </div>

    </div>

    <div class="row">
    <div class="col-lg-6 col-lg-offset-3">
        <div class="draw-gauge-water col-sm-6"></div>
        <div class="draw-gauge-air col-sm-6"></div>
    </div>
    </div>

    <div class="container col-md-18">
        <div class="panel panel-default">
            <div class="draw-linechart"></div>
        </div>

        <div class="text-center">
            <a href="https://youtu.be/U4RwWMN276U" target="_blank" class="btn btn-primary">Watch The FishPi Datacenter Live Stream</a>
            <div class="small"><strong>Note:</strong> Fishies need sleep too, at night the stream is offline</div>
        </div>

    </div>
</div>
@endsection
