@extends('layout')
@section('content')
    <div class="panel-body">
        <div class="row">
                <div class="container">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            MY Calender
                        </div>
                        <div class="panel-body col-lg-8">
                                {!! $calendar->calendar() !!}
                                {!! $calendar->script() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
