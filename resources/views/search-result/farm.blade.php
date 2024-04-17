<?php
  use App\Http\Controllers\FarmFunctionController;
  $Function 	= new FarmFunctionController();
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
                        <h3 class="p-3 mb-2 bg-primary text-white" style="padding:2px;">End User Search Result of <em>{{ $supervisorName }}>Farms</h3>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="scrollmenu">
                      <button style="margin-right:5px" class="btn btn-info" id="exportButton" onclick="createFormattingWorkbook()">Export to csv</button>
                      <button class="btn btn-danger" id="exportButton" onclick="createFormattingWorkbook()">Export to pdf</button>
                        <table class="table table-bordered table-hover" id="exportTable">
                            <thead>
                            <tr>
                                <th>ENDUSER NAME</th>
                                <th>DEALER NAME</th>
                                <th>FARM NAME</th>
                                <th>FARM ADDRESS</th>
                                <th>FARM PHONE</th>
                                <th>FARM CAPACITY</th>
                                <th>FARM TYPE</th>
                                <th>DATE TIME</th>
                            </tr>
                            </thead>
                            <tbody>
                              @foreach($endUsers as $eu)
                                <tr>
                                  <td>{{ $Function->getEndUserName($eu->enduser_id) }}</td>
                                  <td>{{ $Function->getagent_name($eu->dealer_id,$eu->sub_agent_id) }}</td>
                                  <td>{{ $Function->getfarm_name($eu->id) }}</td>
                                  <td>{{ $Function->getfarm_address($eu->id) }}</td>
                                  <td>{{ $Function->getfarm_phone($eu->id) }}</td>
                                  <td>{{ $Function->getfarm_capacity($eu->id) }}</td>
                                  <td>{{ $Function->getfarm_type($eu->id) }}</td>
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

    @role(['client','bdthai','daimond','amrit','nourish','alpha'])
    <div class="row">
        <div class="col-md-12">


                <div class="portlet-title">
                    <div class="caption">
                        <i class="livicon" data-name="lab" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                        <h3>Farms</h3>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="scrollmenu">
                      <table class="table table-bordered table-hover" id="searchResult">
                          <thead>
                          <tr>
                              <th>ENDUSER NAME</th>
                              <th>DEALER NAME</th>
                              <th>FARM NAME</th>
                              <th>FARM ADDRESS</th>
                              <th>FARM PHONE</th>
                              <th>FARM CAPACITY</th>
                              <th>FARM TYPE</th>
                              <th>DATE TIME</th>
                          </tr>
                          </thead>
                          <tbody>
                            @foreach($endUsers as $eu)
                              <tr>
                                <td>{{ $Function->getEndUserName($eu->enduser_id) }}</td>
                                <td>{{ $Function->getagent_name($eu->dealer_id,$eu->sub_agent_id) }}</td>
                                <td>{{ $Function->getfarm_name($eu->id) }}</td>
                                <td>{{ $Function->getfarm_address($eu->id) }}</td>
                                <td>{{ $Function->getfarm_phone($eu->id) }}</td>
                                <td>{{ $Function->getfarm_capacity($eu->id) }}</td>
                                <td>{{ $Function->getfarm_type($eu->id) }}</td>
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
