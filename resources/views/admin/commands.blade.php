@extends('layouts.app')

@section('content')
<div class="container admin">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Commands</div>
                <div class="panel-body">
                    <table width="80%">
                    @foreach ($commands as $command)
                        <tr class="executed-{{$command->executed}}">
                            <td>{{$command->command}}</td>
                            <td>{{$command->data}}</td>
                            <td>{{$command->created}}</td>
                        <tr>
                    @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
