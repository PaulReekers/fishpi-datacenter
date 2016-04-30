@extends('layouts.app')
@section('title', 'Welcome to the FishPi Datacenter')

@section('content')
<div class="container">
    <div class="row banner">

        <div class="col-md-12">

            <h1 class="text-center margin-top-100">
                Welcome at the Fishpi Datacenter
            </h1>

            <h3 class="text-center margin-top-100">How to create your own datacenter fishtank!</h3>
            <h4 class="text-center margin-top-100">And keep the fishies happy.</h4>

        </div>

    </div>

    <div class="container col-md-18">

         <div class="draw_gauge" id="gauge_div"></div>

    </div>
</div>
@endsection
