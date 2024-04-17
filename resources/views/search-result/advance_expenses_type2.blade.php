<?php
  use App\Http\Controllers\AdvanceExpenseFunctionController;
  $Function 	= new AdvanceExpenseFunctionController();
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
                        <h3>Expense</h3>
                    </div>
                </div>
                <div class="portlet-body">

                    <div class="scrollmenu">
                      <button style="margin-right:5px" class="btn btn-info" id="exportButton" onclick="createFormattingWorkbook()">Export to csv</button>

                        <table class="table table-bordered table-hover" id="exportTable">
                            <thead>
                            <tr>
                                <th>ENDUSER NAME</th>
                                <th>SCHEDULE VISIT</th>
                                <th>VISITING AREA</th>
                                <th>PURPOSE</th>
                                <th>CONVEYANCE</th>
                                <th>DAILY ALLOWANCE</th>
                                <th>HOTEL RENT</th>
                                <th>FOOD</th>
                                <th>PHOTOSTAT</th>
                                <th>FAX</th>
                                <th>OTHERS</th>
                                <th>DATE TIME</th>
                                <th>TOTAL</th>
                            </tr>
                            </thead>
                            <tbody>
                              @php
                              $total = 0;
                              @endphp
                              @foreach($endUsers as $eu)
                                <tr>
                                  <td>{{ $Function->getEndUserName($eu->enduser_id) }}</td>
                                  <td>{{ $Function->getScheduleVisit($eu->id) }}</td>
                                  <td>{{ $Function->getVisitingArea($eu->id) }}</td>
                                  <td>{{ $Function->getpurpose($eu->id) }}</td>
                                  <td>{{ $Function->getconveyance($eu->id) }}</td>
                                  <td>{{ $Function->getdaily_allowance($eu->id) }}</td>
                                  <td>{{ $Function->gethotel_rent($eu->id) }}</td>
                                  <td>{{ $Function->getfood($eu->id) }}</td>
                                  <td>{{ $Function->getphotostat($eu->id) }}</td>
                                  <td>{{ $Function->getfax($eu->id) }}</td>
                                  <td>{{ $Function->getothers($eu->id) }}</td>
                                  <td>{{ $Function->getEndUserDateTime($eu->id) }}</td>
                                  <td>{{ $Function->gettotal_amount($eu->id) }}</td>
                                  @php $total += $Function->gettotal_amount($eu->id)   @endphp
                                </tr>
                              @endforeach
                              <tr>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>@php
                                echo $total;
                                @endphp</td>
                              </tr>

                            </tbody>
                        </table>
                    </div>
                </div>


        </div>
    </div>
    @endrole

    @role(['bdthai','daimond','amrit'])
    <div class="row">
        <div class="col-md-12">


                <div class="portlet-title">
                    <div class="caption">
                        <i class="livicon" data-name="lab" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                        <h3>Expense</h3>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="scrollmenu">
                      <table class="table table-bordered table-hover" id="exportTable">
                          <thead>
                          <tr>
                              <th>ENDUSER NAME</th>
                              <th>SCHEDULE VISIT</th>
                              <th>VISITING AREA</th>
                              <th>PURPOSE</th>
                              <th>CONVEYANCE</th>
                              <th>DAILY ALLOWANCE</th>
                              <th>HOTEL RENT</th>
                              <th>FOOD</th>
                              <th>PHOTOSTAT</th>
                              <th>FAX</th>
                              <th>OTHERS</th>
                              <th>DATE TIME</th>
                              <th>TOTAL</th>
                          </tr>
                          </thead>
                          <tbody>
                            @php
                            $total = 0;
                            @endphp
                            @foreach($endUsers as $eu)
                              <tr>
                                <td>{{ $Function->getEndUserName($eu->enduser_id) }}</td>
                                <td>{{ $Function->getScheduleVisit($eu->id) }}</td>
                                <td>{{ $Function->getVisitingArea($eu->id) }}</td>
                                <td>{{ $Function->getpurpose($eu->id) }}</td>
                                <td>{{ $Function->getconveyance($eu->id) }}</td>
                                <td>{{ $Function->getdaily_allowance($eu->id) }}</td>
                                <td>{{ $Function->gethotel_rent($eu->id) }}</td>
                                <td>{{ $Function->getfood($eu->id) }}</td>
                                <td>{{ $Function->getphotostat($eu->id) }}</td>
                                <td>{{ $Function->getfax($eu->id) }}</td>
                                <td>{{ $Function->getothers($eu->id) }}</td>
                                <td>{{ $Function->getEndUserDateTime($eu->id) }}</td>
                                <td>{{ $Function->gettotal_amount($eu->id) }}</td>
                                @php $total += $Function->gettotal_amount($eu->id)   @endphp
                              </tr>
                            @endforeach
                            <tr>
                              <td>-</td>
                              <td>-</td>
                              <td>-</td>
                              <td>-</td>
                              <td>-</td>
                              <td>-</td>
                              <td>-</td>
                              <td>-</td>
                              <td>-</td>
                              <td>-</td>
                              <td>-</td>
                              <td>-</td>
                              <td>@php
                              echo $total;
                              @endphp</td>
                            </tr>

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
                        <h3>Expense</h3>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="scrollmenu">
                      <button style="margin-right:5px" class="btn btn-info" id="exportButton" onclick="createFormattingWorkbook()">Export to csv</button>

                        <table class="table table-bordered table-hover" id="exportTable">
                          <thead>
                          <tr>
                              <th>NAME</th>
                              <th>DATE TIME</th>
                              <th>CONVEYANCE</th>
                              <th>D/A</th>
                              <th>H/R</th>
                              <th>OTHERS</th>
                              <th>TOTAL</th>
                          </tr>
                          </thead>
                          <tbody>
                            @php
                            $total = 0;
                            @endphp
                            @foreach($endUsers as $eu)
                              <tr>
                                <td>{{ $Function->getEndUserName($eu->enduser_id) }}</td>
                                <td>{{ $Function->getEndUserDateTime($eu->id) }}</td>
                                <td>{{ $Function->getconveyance($eu->id) }}</td>
                                <td>{{ $Function->getdaily_allowance($eu->id) }}</td>
                                <td>{{ $Function->gethotel_rent($eu->id) }}</td>
                                <td>{{ $Function->getothers($eu->id) }}</td>
                                <td>{{ $Function->gettotal_amount($eu->id) }}</td>
                                @php $total += $Function->gettotal_amount($eu->id)   @endphp
                              </tr>
                            @endforeach
                            <tr>
                              <td>-</td>
                              <td>-</td>
                              <td>-</td>
                              <td>-</td>
                              <td>-</td>
                              <td>-</td>
                              <td>@php
                              echo $total;
                              @endphp</td>
                            </tr>

                          </tbody>
                      </table>
                    </div>
                </div>


        </div>
    </div>
    @endrole

@stop
