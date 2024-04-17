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
                        <h3>SALES ORDER</h3>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="scrollmenu">
                      <table class="table table-bordered table-hover" id="exportTable">
                          <thead>
                          <tr>
                              <th>ENDUSER NAME</th>
                              <th>OUTLET NAME</th>
                              <th>INVOICE NUMBER</th>
                              <th>NET SALE</th>
                              <th>DATE TIME</th>

                          </tr>
                          </thead>
                          <tbody>
                            @php
                            $total = 0;
                            @endphp
                            @foreach($endUsers as $eu)
                              <tr>
                                <td>{{ $Function->getEndUserName($eu->enduser_id) }}</td>
                                <td>{{ $Function->getoutletname($eu->outlet_id) }}</td>
                                <td>{{ $Function->getinvoicenumber($eu->id) }}</td>
                                <td>{{ $Function->getnetsale($eu->id) }}</td>
                                <td>{{ $Function->getEndUserDateTime($eu->id) }}</td>
                                @php $total += $Function->getnetsale($eu->id)   @endphp
                              </tr>
                            @endforeach
                            <tr>
                              <td>-</td>
                              <td>-</td>
                              <td>-</td>
                              <td>@php
                              echo 'TOTAL : '.$total;
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

    @role(['client','bdthai','daimond','getco'])
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
                              <th>ENDUSER NAME</th>
                              <th>OUTLET NAME</th>
                              <th>INVOICE NUMBER</th>
                              <th>NET SALE</th>
                              <th>DATE TIME</th>

                          </tr>
                          </thead>
                          <tbody>
                            @php
                            $total = 0;
                            @endphp
                            @foreach($endUsers as $eu)
                              <tr>
                                <td>{{ $Function->getEndUserName($eu->enduser_id) }}</td>
                                <td>{{ $Function->getoutletname($eu->outlet_id) }}</td>
                                <td>{{ $Function->getinvoicenumber($eu->id) }}</td>
                                <td>{{ $Function->getnetsale($eu->id) }}</td>
                                <td>{{ $Function->getEndUserDateTime($eu->id) }}</td>
                                @php $total += $Function->getnetsale($eu->id)   @endphp
                              </tr>
                            @endforeach
                            <tr>
                              <td>-</td>
                              <td>-</td>
                              <td>-</td>
                              <td>@php
                              echo 'TOTAL : '.$total;
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
