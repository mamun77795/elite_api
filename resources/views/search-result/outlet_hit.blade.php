<?php
  use App\Http\Controllers\OutletHitFunctionController;
  $Function 	= new OutletHitFunctionController();
?>
@extends('layout')

@section('content')


    {{-------------------------------------------}}
    @role(['admin','developer'])
    <div class="row">
        <div class="col-md-12">


                <div class="portlet-title">
                    <div class="caption">
                        <i class="livicon" data-name="lab" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                        <h3>End User Outlet Hits</h3>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="scrollmenu">
                        <table class="table table-bordered table-hover" id="searchResult">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>CLIENT</th>
                                <th>ENDUSER NAME</th>
                                <th>OUTLET NAME</th>
                                <th>ADDRESS</th>
                                <th>DISTANCE(meter)</th>
                                <th>DATE TIME</th>
                            </tr>
                            </thead>
                            <tbody>
                              @foreach($endUsers as $eu)
                                <tr>
                                  <td>{{ $loop->index+1 }}</td>
                                  <td>{{ $Function->getClientNamebyID($eu->client_id) }}</td>
                                  <td>{{ $Function->getEndUserName($eu->enduser_id) }}</td>
                                  <td>{{ $Function->getDealerName($eu->outlet_id) }}</td>
                                  <td>{{ $Function->getDealerAddress($eu->outlet_id) }}</td>
                                  <td>{{ $Function->getDistance($eu->id) }}</td>
                                  <td>{{ $Function->getEndUserDateTime($eu->id) }}</td>
                                </tr>
                              @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>


        </div>
    </div>
    @endrole

    @role(['client','bdthai','getco'])
    <div class="row">
        <div class="col-md-12">


                <div class="portlet-title">
                    <div class="caption">
                        <i class="livicon" data-name="lab" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                        <h3>End User Outlet Hits</h3>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="scrollmenu">
                        <table class="table table-bordered table-hover" id="searchResult">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>ENDUSER NAME</th>
                                <th>OUTLET NAME</th>
                                <th>ADDRESS</th>
                                <th>DISTANCE(meter)</th>
                                <th>DATE TIME</th>
                            </tr>
                            </thead>
                            <tbody>
                              @foreach($endUsers as $eu)
                                <tr>
                                  <td>{{ $loop->index+1 }}</td>
                                  <td>{{ $Function->getEndUserName($eu->enduser_id) }}</td>
                                  <td>{{ $Function->getDealerName($eu->outlet_id) }}</td>
                                  <td>{{ $Function->getDealerAddress($eu->outlet_id) }}</td>
                                  <td>{{ $Function->getDistance($eu->id) }}</td>
                                  <td>{{ $Function->getEndUserDateTime($eu->id) }}</td>
                                </tr>
                              @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>


        </div>
    </div>
    @endrole

@stop
