@extends('layouts.app')

@section('content')
<div class="container admin">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Admin panel</div>
                <div class="panel-body">
                    Add a command and send it to FishPi
                </div>
                <form class="form-horizontal" role="form" method="POST">
                <input name="_token" hidden value="{!! csrf_token() !!}" />
                <div class="panel-body">
                    Command: 
                    <select name="command" id="command">
                    @foreach ($commands as $command)
                        <option value="{{ $command->id }}">{{ $command->name }}</option>
                    @endforeach
                    </select>
                </div>
                <div class="panel-body command-extra" id="setled">
                    <h3>Testing the leds</h3>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="led">Led:</label>
                        <div class="col-sm-10">
                            <select name="led" class="form-control" id="led" placeholder="Choose the led">
                            @foreach ($leds as $led)
                                <option value="{{ $led->id }}">{{ $led->name }}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="clear">Clear all other leds:</label>
                        <div class="col-sm-1">
                            <input type="checkbox" name="clear" class="form-control" id="clear">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="ledtime">Time (seconds):</label>
                        <div class="col-sm-1">
                            <input type="number" name="ledtime" class="form-control" id="ledtime" min="1" max="100">
                        </div>
                    </div>                    
                </div>
                <div class="panel-body command-extra" id="settemp">
                    <h3>Set alerting temprature</h3>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="alerttemp">Alert temp (orange):</label>
                        <div class="col-sm-10">
                          <input type="number" class="form-control" id="alerttemp" value="{{ $alerttemp }}" placeholder="Enter alert temprature" min="-18000" max="50000">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="criticaltemp">Critical temp (red):</label>
                        <div class="col-sm-10">
                          <input type="number" class="form-control" id="criticaltemp" value="{{ $criticaltemp }}" placeholder="Enter critical temprature" min="-18000" max="50000">
                        </div>
                    </div>
                </div>
                <div class="form-group"> 
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-default">Submit</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
