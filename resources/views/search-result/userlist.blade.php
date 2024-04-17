<?php
  use App\Http\Controllers\UserListFunctionController;
  $Function 	= new UserListFunctionController();
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
                        <h3 class="p-3 mb-2 bg-primary text-white" style="padding:2px;">End User Report</h3>
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
                                <th>DATE</th>
                                <th>ENDUSER NAME</th>
                                <th>Attendance</th>
                                <th>EXPENSE</th>
                                <th>TARGET</th>
                                <th>SALES</th>
                                <th>SALES ACHIEVEMENT</th>
                                <th>OUTLET HIT COUNT</th>
                                <th>OUTLET HIT LOCATION</th>
                            </tr>
                            </thead>
                            <tbody>

                              @php
                              $id = $endUsers[0]['id'];
                              @endphp
                              @php
                              $totalexpense = 0;
                              $totaloutlethit = 0;
                              $totalsales = 0;
                              $totaltarget = 0;
                              @endphp

                              @foreach($dates as $date)
                                <tr>
                                  <td>{{ $loop->index+1 }}</td>
                                  <td>{{ $date}}</td>
                                  <td>{{ $endUsers[0]['name'] }}</td>
                                  <td>{{ $Function->getattendance($id,$date) }}</td>
                                  <td>{{ $Function->gettotal_amount($id,$date) }}</td>
                                  <td>-</td>
                                  <td>{{ $Function->getsales($id,$date) }}</td>
                                  <td>-</td>
                                  <td>{{ $Function->getoutlethit_count($id,$date) }}</td>
                                  <td>{{ $Function->getoutlethit_location($id,$date) }}</td>
                                  @php $totalexpense += $Function->gettotal_amount($id,$date)   @endphp
                                  @php $totaloutlethit += $Function->getoutlethit_count($id,$date)   @endphp
                                  @php $totalsales += $Function->getsales($id,$date)   @endphp
                                  @php $totaltarget += $Function->gettarget($id,$date)   @endphp
                                </tr>
                              @endforeach

                              @if($totaltarget == 0)
                              @php
                              $achievement = 0
                              @endphp
                              @else
                              @php
                              $achievement = ($totalsales * 100) / $totaltarget
                              @endphp
                              @endif
                              <tr>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>@php
                                echo 'Total: '. $totalexpense;
                                @endphp</td>
                                <td>@php
                                echo 'Total: '. $totaltarget;
                                @endphp</td>
                                <td>@php
                                echo 'Total: '. $totalsales;
                                @endphp</td>
                                <td>@php
                                echo 'Total: '. $achievement;
                                @endphp</td>
                                <td>@php
                                echo 'Total: '. $totaloutlethit;
                                @endphp</td>
                                <td>-</td>

                              </tr>
                            </tbody>
                        </table>
                    </div>
                </div>


        </div>
    </div>
    @endrole

    @role(['client','bdthai','amrit','getco'])
    <div class="row">
        <div class="col-md-12">


                <div class="portlet-title">
                    <div class="caption">
                        <i class="livicon" data-name="lab" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                        <h3>End User Report</h3>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="scrollmenu">
                      <table class="table table-bordered table-hover" id="exportTable">
                          <thead>
                          <tr>
                              <th>#</th>
                              <th>DATE</th>
                              <th>ENDUSER NAME</th>
                              <th>Attendance</th>
                              <th>EXPENSE</th>
                              <th>TARGET</th>
                              <th>SALES</th>
                              <th>SALES ACHIEVEMENT</th>
                              <th>OUTLET HIT COUNT</th>
                              <th>OUTLET HIT LOCATION</th>
                          </tr>
                          </thead>
                          <tbody>

                            @php
                            $id = $endUsers[0]['id'];
                            @endphp
                            @php
                            $totalexpense = 0;
                            $totaloutlethit = 0;
                            $totalsales = 0;
                            $totaltarget = 0;
                            @endphp

                            @foreach($dates as $date)
                              <tr>
                                <td>{{ $loop->index+1 }}</td>
                                <td>{{ $date}}</td>
                                <td>{{ $endUsers[0]['name'] }}</td>
                                <td>{{ $Function->getattendance($id,$date) }}</td>
                                <td>{{ $Function->gettotal_amount($id,$date) }}</td>
                                <td>-</td>
                                <td>{{ $Function->getsales($id,$date) }}</td>
                                <td>-</td>
                                <td>{{ $Function->getoutlethit_count($id,$date) }}</td>
                                <td>{{ $Function->getoutlethit_location($id,$date) }}</td>
                                @php $totalexpense += $Function->gettotal_amount($id,$date)   @endphp
                                @php $totaloutlethit += $Function->getoutlethit_count($id,$date)   @endphp
                                @php $totalsales += $Function->getsales($id,$date)   @endphp
                                @php $totaltarget += $Function->gettarget($id,$date)   @endphp
                              </tr>
                            @endforeach

                            @if($totaltarget == 0)
                            @php
                            $achievement = 0
                            @endphp
                            @else
                            @php
                            $achievement = ($totalsales * 100) / $totaltarget
                            @endphp
                            @endif
                            <tr>
                              <td>-</td>
                              <td>-</td>
                              <td>-</td>
                              <td>-</td>
                              <td>@php
                              echo 'Total: '. $totalexpense;
                              @endphp</td>
                              <td>@php
                              echo 'Total: '. $totaltarget;
                              @endphp</td>
                              <td>@php
                              echo 'Total: '. $totalsales;
                              @endphp</td>
                              <td>@php
                              echo 'Total: '. $achievement;
                              @endphp</td>
                              <td>@php
                              echo 'Total: '. $totaloutlethit;
                              @endphp</td>
                              <td>-</td>

                            </tr>
                          </tbody>
                      </table>
                    </div>
                </div>


        </div>
    </div>
    @endrole

    @role(['daimond'])
    <div class="row">
        <div class="col-md-12">


                <div class="portlet-title">
                    <div class="caption">
                        <i class="livicon" data-name="lab" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                        <h3>End User Report</h3>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="scrollmenu">
                      <table class="table table-bordered table-hover" id="exportTable">
                          <thead>
                          <tr>
                              <th>#</th>
                              <th>DATE</th>
                              <th>ENDUSER NAME</th>
                              <th>Attendance</th>
                              <th>EXPENSE</th>
                              <th>DEALER HIT COUNT</th>
                              <th>DEALER HIT LOCATION</th>
                          </tr>
                          </thead>
                          <tbody>

                            @php
                            $id = $endUsers[0]['id'];
                            @endphp
                            @php
                            $totalexpense = 0;
                            $totaldealerhit = 0;
                            $totalpresent = 0;
                            @endphp

                            @foreach($dates as $date)
                              <tr>
                                <td>{{ $loop->index+1 }}</td>
                                <td>{{ $date}}</td>
                                <td>{{ $endUsers[0]['name'] }}</td>
                                <td>{{ $Function->getattendance($id,$date) }}</td>
                                <td>{{ $Function->gettotal_amount($id,$date) }}</td>
                                <td>{{ $Function->getdealerhit_count($id,$date) }}</td>
                                <td>{{ $Function->getdealerhit_location($id,$date) }}</td>
                                @php $totalexpense += $Function->gettotal_amount($id,$date)   @endphp
                                @php $totaldealerhit += $Function->getdealerhit_count($id,$date)   @endphp
                              </tr>
                            @endforeach
                            <tr>
                              <td>-</td>
                              <td>-</td>
                              <td>-</td>
                              <td>-</td>
                              <td>@php
                              echo 'Total: '. $totalexpense;
                              @endphp</td>
                              <td>@php
                              echo 'Total: '. $totaldealerhit;
                              @endphp</td>
                              <td>-</td>

                            </tr>
                          </tbody>
                      </table>
                    </div>
                </div>


        </div>
    </div>
    @endrole

    @role(['nourish'])
    <div class="row">
        <div class="col-md-12">


                <div class="portlet-title">
                    <div class="caption">
                        <i class="livicon" data-name="lab" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                        <h3>End User Report</h3>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="scrollmenu">
                      <table class="table table-bordered table-hover" id="exportTable">
                          <thead>
                          <tr>
                              <th>#</th>
                              <th>DATE</th>
                              <th>ENDUSER NAME</th>
                              <th>Attendance</th>
                              <th>EXPENSE</th>
                              <th>DEALER HIT COUNT</th>
                              <th>DEALER HIT LOCATION</th>
                          </tr>
                          </thead>
                          <tbody>

                            @php
                            $id = $endUsers[0]['id'];
                            @endphp
                            @php
                            $totalexpense = 0;
                            $totaldealerhit = 0;
                            $totalpresent = 0;
                            @endphp

                            @foreach($dates as $date)
                              <tr>
                                <td>{{ $loop->index+1 }}</td>
                                <td>{{ $date}}</td>
                                <td>{{ $endUsers[0]['name'] }}</td>
                                <td>{{ $Function->getattendance($id,$date) }}</td>
                                <td>{{ $Function->gettotal_amount($id,$date) }}</td>
                                <td>{{ $Function->getdealerhit_count($id,$date) }}</td>
                                <td>{{ $Function->getdealerhit_location($id,$date) }}</td>
                                @php $totalexpense += $Function->gettotal_amount($id,$date)   @endphp
                                @php $totaldealerhit += $Function->getdealerhit_count($id,$date)   @endphp
                              </tr>
                            @endforeach
                            <tr>
                              <td>-</td>
                              <td>-</td>
                              <td>-</td>
                              <td>-</td>
                              <td>@php
                              echo 'Total: '. $totalexpense;
                              @endphp</td>
                              <td>@php
                              echo 'Total: '. $totaldealerhit;
                              @endphp</td>
                              <td>-</td>

                            </tr>
                          </tbody>
                      </table>
                    </div>
                </div>


        </div>
    </div>
    @endrole

    @role(['alpha'])
    <div class="row">
        <div class="col-md-12">


                <div class="portlet-title">
                    <div class="caption">
                        <i class="livicon" data-name="lab" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                        <h3>End User Report</h3>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="scrollmenu">
                      <table class="table table-bordered table-hover" id="exportTable">
                          <thead>
                          <tr>
                              <th>#</th>
                              <th>DATE</th>
                              <th>ENDUSER NAME</th>
                              <th>Attendance</th>
                              <th>EXPENSE</th>
                              <th>DEALER HIT COUNT</th>
                              <th>DEALER HIT LOCATION</th>
                          </tr>
                          </thead>
                          <tbody>

                            @php
                            $id = $endUsers[0]['id'];
                            @endphp
                            @php
                            $totalexpense = 0;
                            $totaldealerhit = 0;
                            $totalpresent = 0;
                            @endphp

                            @foreach($dates as $date)
                              <tr>
                                <td>{{ $loop->index+1 }}</td>
                                <td>{{ $date}}</td>
                                <td>{{ $endUsers[0]['name'] }}</td>
                                <td>{{ $Function->getattendance($id,$date) }}</td>
                                <td>{{ $Function->gettotal_amount($id,$date) }}</td>
                                <td>{{ $Function->getdealerhit_count($id,$date) }}</td>
                                <td>{{ $Function->getdealerhit_location($id,$date) }}</td>
                                @php $totalexpense += $Function->gettotal_amount($id,$date)   @endphp
                                @php $totaldealerhit += $Function->getdealerhit_count($id,$date)   @endphp
                              </tr>
                            @endforeach
                            <tr>
                              <td>-</td>
                              <td>-</td>
                              <td>-</td>
                              <td>-</td>
                              <td>@php
                              echo 'Total: '. $totalexpense;
                              @endphp</td>
                              <td>@php
                              echo 'Total: '. $totaldealerhit;
                              @endphp</td>
                              <td>-</td>

                            </tr>
                          </tbody>
                      </table>
                    </div>
                </div>


        </div>
    </div>
    @endrole

@stop
