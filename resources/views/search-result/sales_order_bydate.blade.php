<?php
  use App\Http\Controllers\SalesOrderControllerFunctionController;
  $Function 	= new SalesOrderControllerFunctionController();
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
                        <h3 class="p-3 mb-2 bg-primary text-white" style="padding:2px;">SALES ORDER</h3>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="scrollmenu">
                      <button style="margin-right:5px" class="btn btn-info" id="exportButton" onclick="createFormattingWorkbook()">Export to csv</button>
                      <button class="btn btn-danger" id="exportButton" onclick="createFormattingWorkbook()">Export to pdf</button>
                        <table class="table table-bordered table-hover" id="exportTable">
                          <thead>
                          <tr>
                            <th>TARGET</th>
                            <th>SALES</th>
                            <th>SALES ACHIEVEMENT</th>

                          </tr>
                          </thead>
                          <tbody>
                            @php
                            $totalsales = 0;
                            $totaltarget = 0;
                            @endphp

                            @foreach($dates as $date)

                                @php $totalsales += $Function->getsales($date)   @endphp
                                @php $totaltarget += $Function->gettarget($date)   @endphp

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
                              <td>@php
                              echo 'Total: '. $totaltarget;
                              @endphp</td>
                              <td>@php
                              echo 'Total: '. $totalsales;
                              @endphp</td>
                              <td>@php
                              echo 'Total: '. $achievement;
                              @endphp</td>

                            </tr>
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
                        <h3>SALES ORDER</h3>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="scrollmenu">
                      <table class="table table-bordered table-hover" id="exportTable">
                        <thead>
                        <tr>
                          <th>TARGET</th>
                          <th>SALES</th>
                          <th>SALES ACHIEVEMENT</th>

                        </tr>
                        </thead>
                        <tbody>
                          @php
                          $totalsales = 0;
                          $totaltarget = 0;
                          @endphp

                          @foreach($dates as $date)

                              @php $totalsales += $Function->getsales_btal($date)   @endphp
                              @php $totaltarget += $Function->gettarget_btal($date)   @endphp

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
                            <td>@php
                            echo 'Total: '. $totaltarget;
                            @endphp</td>
                            <td>@php
                            echo 'Total: '. $totalsales;
                            @endphp</td>
                            <td>@php
                            echo 'Total: '. $achievement;
                            @endphp</td>

                          </tr>
                        </tbody>
                    </table>
                    </div>
                </div>


        </div>
    </div>
    @endrole

    @role(['getco'])
    <div class="row">
        <div class="col-md-12">


                <div class="portlet-title">
                    <div class="caption">
                        <i class="livicon" data-name="lab" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                        <h3>SALES ORDER</h3>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="scrollmenu">
                      <table class="table table-bordered table-hover" id="exportTable">
                        <thead>
                        <tr>
                          <th>TARGET</th>
                          <th>SALES</th>
                          <th>SALES ACHIEVEMENT</th>

                        </tr>
                        </thead>
                        <tbody>
                          @php
                          $totalsales = 0;
                          $totaltarget = 0;
                          @endphp

                          @foreach($dates as $date)

                              @php $totalsales += $Function->getsales_getco($date)   @endphp
                              @php $totaltarget += $Function->gettarget_getco($date)   @endphp

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
                            <td>@php
                            echo 'Total: '. $totaltarget;
                            @endphp</td>
                            <td>@php
                            echo 'Total: '. $totalsales;
                            @endphp</td>
                            <td>@php
                            echo 'Total: '. $achievement;
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
