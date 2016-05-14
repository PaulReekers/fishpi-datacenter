@extends('layouts.app')
@section('title', 'Admin panel')

@section('content')
<div class="container admin col-md-8 col-md-offset-2">
    <div class="panel panel-default">

        <h2 class="panel-heading">Admin panel</h2>

        <p class="panel-body">
            Add a command and send it to FishPi
        </p>

        <form class="form-horizontal" role="form" method="POST">
            <input type="hidden" name="_token" value="{!! csrf_token() !!}">

                <div class="form-group">
                    <label for="command" class="col-lg-2 control-label">Command:</label>
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
            <div class="panel-body command-extra" id="askip">
                <h3>Retrieve the current IP</h3>
                <div>
                    Last known IP was: {{ $lastIP }}
                </div>
            </div>
            <div class="panel-body command-extra" id="compose">
                <h3>Compose you're leds</h3>
                <div>Use the key's "1" "2" and "3" for the leds, and "N" for none</div>
                <div id="compose-list">
                    <textarea name="json" id="json"></textarea>
                </div>
            </div>
            <div class="panel-body command-extra" id="settemp">
                <h3>Set alarming temprature</h3>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="alarmtemp">Alarm temp (orange):</label>
                    <div class="col-sm-10">
                      <input type="number" class="form-control" id="alarmtemp" name="alarmtemp" value="{{ $alarmtemp }}" placeholder="Enter alarm temprature" min="-18000" max="50000">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="criticaltemp">Critical temp (red):</label>
                    <div class="col-sm-10">
                      <input type="number" class="form-control" id="criticaltemp" name="criticaltemp" value="{{ $criticaltemp }}" placeholder="Enter critical temprature" min="-18000" max="50000">
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
@endsection
