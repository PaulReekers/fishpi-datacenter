@extends('layouts.app')
@section('title', 'Welcome to the FishPi Datacenter')

@section('content')
<div class="container">
    <div class="row banner">

        <div class="col-md-12">

            <h1 class="text-center margin-top-100">
                Welcome at the FishPi Datacenter
            </h1>

            <h3 class="text-center margin-top-100">How to create your own fishtank datacenter!</h3>
            <h4 class="text-center margin-top-100">And keep da fishies happy.</h4>

        </div>

    </div>

    <div class="container col-md-18">

        <div class="draw-gauge" id="gauge-div"></div>
        <div class="panel panel-default">
            <div class="draw-linechart"></div>
        </div>

        <div class="ustream-container">
            <iframe src="https://www.ustream.tv/embed/22140808?html5ui&volume=0&showtitle=false" style="border: 0 none transparent;"  webkitallowfullscreen allowfullscreen frameborder="no" width="480" height="270"></iframe>
        </div>

    </div>
</div>
@endsection
