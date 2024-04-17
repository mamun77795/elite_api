<?php
  use App\Http\Controllers\AdvanceLocationFunctionController;
  $Function 	= new AdvanceLocationFunctionController();
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
                        <h3 class="p-3 mb-2 bg-primary text-white" style="padding:2px;">LOCATIONS</h3>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="scrollmenu">
                      <button style="margin-right:5px" class="btn btn-info" id="exportButton" onclick="createFormattingWorkbook()">Export to csv</button>
                      <button class="btn btn-danger" id="exportButton" onclick="createFormattingWorkbook()">Export to pdf</button>
                        <table class="table table-bordered table-hover" id="exportTable">
                            <thead>
                            <tr>
                                <th>NAME</th>
                                <th>FROM</th>
                                <th>START time</th>
                                <th>TO</th>
                                <th>END time</th>
                                <th>CONVEYANCE</th>
                            </tr>
                            </thead>
                            <tbody>
                              @foreach($endUsers as $eu)
                                <tr>
                                  <td>{{ $Function->getEndUserName($eu->enduser_id) }}</td>
                                  <td>{{ $Function->getEndUserStartAddress($eu->id) }}</td>
                                  <td>{{ $Function->getEndUserstartDateTime($eu->id) }}</td>
                                  <td>{{ $Function->getEndUserEndAddress($eu->id) }}</td>
                                  <td>{{ $Function->getEndUserDateTime($eu->id) }}</td>
                                  <td>{{ $Function->getCONVEYANCE($eu->conveyance_id) }}</td>
                                </tr>
                              @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>


        </div>
    </div>
    @endrole

    @role(['client','bdthai','daimond','getco'])
    <div class="row">
        <div class="col-md-12">


                <div class="portlet-title">
                    <div class="caption">
                        <i class="livicon" data-name="lab" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                        <h3>LOCATIONS</h3>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="scrollmenu">
                      <button style="margin-right:5px" class="btn btn-info" id="exportButton" onclick="createFormattingWorkbook()">Export to csv</button>
                      <table class="table table-bordered table-hover" id="exportTable">
                          <thead>
                          <tr>
                              <th>NAME</th>
                              <th>FROM</th>
                              <th>START time</th>
                              <th>TO</th>
                              <th>END time</th>
                              <th>CONVEYANCE</th>
                          </tr>
                          </thead>
                          <tbody>
                            @foreach($endUsers as $eu)
                              <tr>
                                <td>{{ $Function->getEndUserName($eu->enduser_id) }}</td>
                                <td>{{ $Function->getEndUserStartAddress($eu->id) }}</td>
                                <td>{{ $Function->getEndUserstartDateTime($eu->id) }}</td>
                                <td>{{ $Function->getEndUserEndAddress($eu->id) }}</td>
                                <td>{{ $Function->getEndUserDateTime($eu->id) }}</td>
                                <td>{{ $Function->getCONVEYANCE($eu->conveyance_id) }}</td>
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
