<?php
  use App\Http\Controllers\AdvanceTourPlanFunctionController;
  $Function 	= new AdvanceTourPlanFunctionController();
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
                        <h3>ADVANCE TOUR PLAN</h3>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="scrollmenu">
                      <table class="table table-bordered table-hover" id="searchResult">
                          <thead>
                          <tr>
                              <th>ENDUSER NAME</th>
                              <th>DATE</th>
                              <th>NAME</th>
                              <th>AREA NAME</th>
                              <th>TYPE</th>
                              <th>PURPOSE</th>
                              <th>CREATEING DATE</th>

                          </tr>
                          </thead>
                          <tbody>
                            @foreach($endUsers as $eu)
                              <tr>
                                <td>{{ $Function->getEndUserName($eu->enduser_id) }}</td>
                                <td>{{ $Function->getEndUserDateTime($eu->id) }}</td>
                                <td>{{ $Function->getname($eu->id) }}</td>
                                <td>{{ $Function->getarea_name($eu->id) }}</td>
                                <td>{{ $Function->gettype($eu->id) }}</td>
                                <td>{{ $Function->getpurpose($eu->id) }}</td>
                                <td>{{ $Function->getCreatingDateTime($eu->id) }}</td>

                              </tr>
                            @endforeach
                          </tbody>
                      </table>
                    </div>
                </div>


        </div>
    </div>
    @endrole

    @role(['client','bdthai','daimond'])
    <div class="row">
        <div class="col-md-12">


                <div class="portlet-title">
                    <div class="caption">
                        <i class="livicon" data-name="lab" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                        <h3>ADVANCE TOUR PLAN</h3>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="scrollmenu">
                      <table class="table table-bordered table-hover" id="searchResult">
                          <thead>
                          <tr>
                              <th>ENDUSER NAME</th>
                              <th>DATE</th>
                              <th>NAME</th>
                              <th>AREA NAME</th>
                              <th>TYPE</th>
                              <th>PURPOSE</th>
                              <th>CREATEING DATE</th>

                          </tr>
                          </thead>
                          <tbody>
                            @foreach($endUsers as $eu)
                              <tr>
                                <td>{{ $Function->getEndUserName($eu->enduser_id) }}</td>
                                <td>{{ $Function->getEndUserDateTime($eu->id) }}</td>
                                <td>{{ $Function->getname($eu->id) }}</td>
                                <td>{{ $Function->getarea_name($eu->id) }}</td>
                                <td>{{ $Function->gettype($eu->id) }}</td>
                                <td>{{ $Function->getpurpose($eu->id) }}</td>
                                <td>{{ $Function->getCreatingDateTime($eu->id) }}</td>

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
