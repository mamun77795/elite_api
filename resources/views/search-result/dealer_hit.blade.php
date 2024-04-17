<?php
  use App\Http\Controllers\DealerHitFunctionController;
  $Function 	= new DealerHitFunctionController();
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
                        <h3 class="p-3 mb-2 bg-primary text-white" style="padding:2px;">End User Dealer Hits</h3>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="scrollmenu">
                      <button style="margin-right:5px" class="btn btn-info" id="exportButton" onclick="createFormattingWorkbook()">Export to csv</button>
                      <button class="btn btn-danger" id="exportButton" onclick="createFormattingWorkbook()">Export to pdf</button>
                        <table class="table table-bordered table-hover" id="exportTable">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>CLIENT</th>
                                <th>ENDUSER NAME</th>
                                <th>DEALER NAME</th>
                                <th>DEALER ADDRESS</th>
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
                                  <td>{{ $Function->getDealerName($eu->id) }}</td>
                                  <td>{{ $Function->getDealerAddress($eu->id) }}</td>
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

    @role(['client','daimond','nourish','alpha'])
    <div class="row">
        <div class="col-md-12">


                <div class="portlet-title">
                    <div class="caption">
                        <i class="livicon" data-name="lab" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                        <h3>End User Dealer Hits</h3>
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
                                <th>DEALER NAME</th>
                                <th>DEALER ADDRESS</th>
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
                                  <td>{{ $Function->getDealerName($eu->id) }}</td>
                                  <td>{{ $Function->getDealerAddress($eu->id) }}</td>
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
