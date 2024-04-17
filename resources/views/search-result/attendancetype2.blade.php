<?php
  use App\Http\Controllers\AttendanceFunctionController;
  $Function 	= new AttendanceFunctionController();
?>
@extends('layout2')

@section('content')


    {{-------------------------------------------}}
    @role(['admin','developer'])
    <div class="row">
        <div class="col-md-12">


                <div class="portlet-title">
                    <div class="caption">
                        <i class="livicon" data-name="lab" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                        <h3 class="p-3 mb-2 bg-primary text-white" style="padding:2px;">End User Attendance</h3>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="scrollmenu">
                      <button style="margin-right:5px" class="btn btn-info" id="exportButton" onclick="createFormattingWorkbook()">Export to csv</button>

                      <table class="table table-bordered table-hover" id="exportTable">
                          <thead>
                          <tr>
                              <th>ENDUSER NAME</th>
                              <th>STATUS</th>
                              <th>DATE TIME</th>
                              <th>ADDRESS</th>

                          </tr>
                          </thead>
                          <tbody>
                            @foreach($endUsers as $eu)
                              <tr>
                                <td>{{ $Function->getEndUserName($eu->enduser_id) }}</td>
                                <td>{{ $Function->getEndUserStatus($eu->id) }}</td>
                                <td>{{ $Function->getEndUserDateTime($eu->id) }}</td>
                                <td>{{ $Function->getEndUserAddress($eu->id) }}</td>
                              </tr>
                            @endforeach
                          </tbody>
                      </table>
                    </div>
                </div>


        </div>
    </div>
    @endrole

    @role(['bdthai','daimond','amrit','getco'])
    <div class="row">
        <div class="col-md-12">


                <div class="portlet-title">
                    <div class="caption">
                        <i class="livicon" data-name="lab" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                        <h3>End User Attendance</h3>
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
                                <th>STATUS</th>
                                <th>ADDRESS</th>
                                <th>DATE TIME</th>
                            </tr>
                            </thead>
                            <tbody>
                              @foreach($endUsers as $eu)
                                <tr>
                                  <td>{{ $loop->index+1 }}</td>
                                  <td>{{ $Function->getClientNamebyID($eu->client_id) }}</td>
                                  <td>{{ $Function->getEndUserName($eu->enduser_id) }}</td>
                                  <td>{{ $Function->getEndUserStatus($eu->id) }}</td>
                                  <td>{{ $Function->getEndUserAddress($eu->id) }}</td>
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

    @role(['client'])
    <div class="row">
        <div class="col-md-12">


                <div class="portlet-title">
                    <div class="caption">
                        <i class="livicon" data-name="lab" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                        <h3 class="p-3 mb-2 bg-primary text-white" style="padding:2px;">End User Attendance</h3>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="scrollmenu">
                      <button style="margin-right:5px" class="btn btn-info" id="exportButton" onclick="createFormattingWorkbook()">Export to csv</button>
                      <table class="table table-bordered table-hover" id="exportTable">
                          <thead>
                          <tr>
                              <th>ENDUSER NAME</th>
                              <th>STATUS</th>
                              <th>DATE TIME</th>
                              <th>ADDRESS</th>

                          </tr>
                          </thead>
                          <tbody>
                            @foreach($endUsers as $eu)
                              <tr>
                                <td>{{ $Function->getEndUserName($eu->enduser_id) }}</td>
                                <td>{{ $Function->getEndUserStatus($eu->id) }}</td>
                                <td>{{ $Function->getEndUserDateTime($eu->id) }}</td>
                                <td>{{ $Function->getEndUserAddress($eu->id) }}</td>
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
