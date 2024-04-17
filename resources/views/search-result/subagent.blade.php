<?php
  use App\Http\Controllers\SubAgentFunctionController;
  $Function 	= new SubAgentFunctionController();
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
                        <h3>Sub Agents</h3>
                    </div>
                </div>
                <div class="portlet-body">

                    <div class="scrollmenu">
                      <button id="exportButton" onclick="createFormattingWorkbook()">Create File</button>
                         <br />
                        <table class="table table-bordered table-hover" id="exportTable">
                            <thead>
                            <tr>
                                <th>ENDUSER NAME</th>
                                <th>DEALER NAME</th>
                                <th>SUB AGENT NAME</th>
                                <th>SUB AGENT ADDRESS</th>
                                <th>SUB AGENT PHONE</th>
                                <th>SUB AGENT TYPE</th>
                                <th>DATE TIME</th>
                            </tr>
                            </thead>
                            <tbody>
                              @foreach($endUsers as $eu)
                                <tr>
                                  <td>{{ $Function->getEndUserName($eu->enduser_id) }}</td>
                                  <td>{{ $Function->getdealer_id($eu->dealer_id) }}</td>
                                  <td>{{ $Function->getsubagent_name($eu->id) }}</td>
                                  <td>{{ $Function->getsubagent_address($eu->id) }}</td>
                                  <td>{{ $Function->getsubagent_phone($eu->id) }}</td>
                                  <td>{{ $Function->getsubagent_type($eu->id) }}</td>
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
                        <h3>Sub Agents</h3>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="scrollmenu">
                      <table class="table table-bordered table-hover" id="searchResult">
                          <thead>
                          <tr>
                              <th>ENDUSER NAME</th>
                              <th>DEALER NAME</th>
                              <th>SUB AGENT NAME</th>
                              <th>SUB AGENT ADDRESS</th>
                              <th>SUB AGENT PHONE</th>
                              <th>SUB AGENT TYPE</th>
                              <th>DATE TIME</th>
                          </tr>
                          </thead>
                          <tbody>
                            @foreach($endUsers as $eu)
                              <tr>
                                <td>{{ $Function->getEndUserName($eu->enduser_id) }}</td>
                                <td>{{ $Function->getdealer_id($eu->dealer_id) }}</td>
                                <td>{{ $Function->getsubagent_name($eu->id) }}</td>
                                <td>{{ $Function->getsubagent_address($eu->id) }}</td>
                                <td>{{ $Function->getsubagent_phone($eu->id) }}</td>
                                <td>{{ $Function->getsubagent_type($eu->id) }}</td>
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
