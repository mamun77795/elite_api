
@extends('layout')
@section('content')
    {{--<div class="row">--}}
        {{--<div class="col-lg-3 col-md-6 col-sm-6 margin_10 animated fadeInLeftBig">--}}
            {{--<!-- Trans label pie charts strats here-->--}}
            {{--<div class="lightbluebg no-radius">--}}
                {{--<div class="panel-body squarebox square_boxs">--}}
                    {{--<div class="col-xs-12 pull-left nopadmar">--}}
                        {{--<div class="row">--}}
                            {{--<div class="square_box col-xs-7 text-right">--}}
                                {{--<span>TOTAL COMPANY</span>--}}
                                {{--<div class="number" id="myTargetElement1">32</div>--}}
                            {{--</div>--}}
                            {{--<i class="fa fa-home fa-5x" aria-hidden="true"></i>--}}
                        {{--</div>--}}
                        {{--<div class="row">--}}
                            {{--<div class="col-xs-6">--}}
                                {{--<small class="stat-label">ACTIVE</small>--}}
                                {{--<h4 id="myTargetElement1.1">30</h4>--}}
                            {{--</div>--}}
                            {{--<div class="col-xs-6 text-right">--}}
                                {{--<small class="stat-label">SUSPEND</small>--}}
                                {{--<h4 id="myTargetElement1.2">2</h4>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
        {{--<div class="col-lg-3 col-md-6 col-sm-6 margin_10 animated fadeInUpBig">--}}
            {{--<!-- Trans label pie charts strats here-->--}}
            {{--<div class="redbg no-radius">--}}
                {{--<div class="panel-body squarebox square_boxs">--}}
                    {{--<div class="col-xs-12 pull-left nopadmar">--}}
                        {{--<div class="row">--}}
                            {{--<div class="square_box col-xs-7 pull-left">--}}
                                {{--<span>TOTAL EMPLOYEES</span>--}}
                                {{--<div class="number" id="myTargetElement2">50</div>--}}
                            {{--</div>--}}
                            {{--<i class="fa fa-user fa-5x" aria-hidden="true"></i>--}}
                        {{--</div>--}}
                        {{--<div class="row">--}}
                            {{--<div class="col-xs-6">--}}
                                {{--<small class="stat-label">Last Week</small>--}}
                                {{--<h4 id="myTargetElement2.1"></h4>--}}
                            {{--</div>--}}
                            {{--<div class="col-xs-6 text-right">--}}
                                {{--<small class="stat-label">Last Month</small>--}}
                                {{--<h4 id="myTargetElement2.2"></h4>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
        {{--<div class="col-lg-3 col-sm-6 col-md-6 margin_10 animated fadeInDownBig">--}}
            {{--<!-- Trans label pie charts strats here-->--}}
            {{--<div class="goldbg no-radius">--}}
                {{--<div class="panel-body squarebox square_boxs">--}}
                    {{--<div class="col-xs-12 pull-left nopadmar">--}}
                        {{--<div class="row">--}}
                            {{--<div class="square_box col-xs-7 pull-left">--}}
                                {{--<span>Subscribers</span>--}}
                                {{--<div class="number" id="myTargetElement3"></div>--}}
                            {{--</div>--}}
                            {{--<i class="livicon pull-right" data-name="archive-add" data-l="true" data-c="#fff" data-hc="#fff" data-s="70"></i>--}}
                        {{--</div>--}}
                        {{--<div class="row">--}}
                            {{--<div class="col-xs-6">--}}
                                {{--<small class="stat-label">Last Week</small>--}}
                                {{--<h4 id="myTargetElement3.1"></h4>--}}
                            {{--</div>--}}
                            {{--<div class="col-xs-6 text-right">--}}
                                {{--<small class="stat-label">Last Month</small>--}}
                                {{--<h4 id="myTargetElement3.2"></h4>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}


        {{--<div class="col-lg-3 col-md-6 col-sm-6 margin_10 animated fadeInRightBig">--}}
            {{--<!-- Trans label pie charts strats here-->--}}
            {{--<div class="palebluecolorbg no-radius">--}}
                {{--<div class="panel-body squarebox square_boxs">--}}
                    {{--<div class="col-xs-12 pull-left nopadmar">--}}
                        {{--<div class="row">--}}
                            {{--<div class="square_box col-xs-7 pull-left">--}}
                                {{--<span>Registered Users</span>--}}
                                {{--<div class="number" id="myTargetElement4"></div>--}}
                            {{--</div>--}}
                            {{--<i class="livicon pull-right" data-name="users" data-l="true" data-c="#fff" data-hc="#fff" data-s="70"></i>--}}
                        {{--</div>--}}
                        {{--<div class="row">--}}
                            {{--<div class="col-xs-6">--}}
                                {{--<small class="stat-label">Last Week</small>--}}
                                {{--<h4 id="myTargetElement4.1"></h4>--}}
                            {{--</div>--}}
                            {{--<div class="col-xs-6 text-right">--}}
                                {{--<small class="stat-label">Last Month</small>--}}
                                {{--<h4 id="myTargetElement4.2"></h4>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}

    {{--</div>--}}

    {{-------------------------------------------}}
    @role(['admin','developer'])
    <div class="row">
        <div class="col-lg-4 col-md-6 col-sm-6 margin_10 animated fadeInLeftBig">
            <!-- Trans label pie charts strats here-->
            <div class="lightbluebg no-radius">
                <div class="panel-body squarebox square_boxs">
                    <div class="col-xs-12 pull-left nopadmar">
                        <div class="row">
                            <div class="square_box col-xs-7 text-right">
                                <span>TOTAL CLIENTS</span>
                                <div class="number" id="myTargetElement1">{{$totalCompany}}</div>
                            </div>
                            <div class="square_box col-xs-3 pull-right">
                            <i class="fa fa-home fa-5x" aria-hidden="true"></i>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <small class="stat-label">ACTIVE</small>
                                <h4 id="myTargetElement1.1">{{$totalstatus}}</h4>
                            </div>
                            <div class="col-xs-6 text-right">
                                <small class="stat-label">INACTIVE</small>
                                <h4 id="myTargetElement1.2">{{$totinalactive_status}}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-6 margin_10 animated fadeInUpBig">
            <!-- Trans label pie charts strats here-->
            <div class="redbg no-radius">
                <div class="panel-body squarebox square_boxs">
                    <div class="col-xs-12 pull-left nopadmar">
                        <div class="row">
                            <div class="square_box col-xs-7 pull-left">
                                <span>TOTAL USERS</span>
                                <div class="number" id="myTargetElement2">{{$totalEmployee}}</div>
                            </div>
                            <div class="square_box col-xs-3 pull-right">
                            <i class="fa fa-user fa-5x" aria-hidden="true"></i>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <small class="stat-label">ACTIVE</small>
                                <h4 id="myTargetElement1.1">{{$totalactive_enduser}}</h4>
                            </div>
                            <div class="col-xs-6 text-right">
                                <small class="stat-label">INACTIVE</small>
                                <h4 id="myTargetElement1.2">{{$totinalactive_enduser}}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-sm-6 col-md-6 margin_10 animated fadeInDownBig">
            <!-- Trans label pie charts strats here-->
            <div class="palebluecolorbg no-radius">
                <div class="panel-body squarebox square_boxs">
                    <div class="col-xs-12 pull-left nopadmar">
                        <div class="row">
                            <div class="square_box col-xs-7 pull-left">
                                <span>TOTAL PAYMENTS</span>
                                <div class="number" id="myTargetElement3">{{$totalpayment}}</div>
                            </div>
                            <div class="square_box col-xs-3 pull-right">
                              <!-- <i class="livicon pull-right" data-name="archive-add" data-l="true" data-c="#fff" data-hc="#fff" data-s="70"></i> -->
                            <i class="fa fa-money fa-5x" aria-hidden="true"></i>
                            </div>


                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <small class="stat-label">PAID</small>
                                <h4 id="myTargetElement3.1">{{$paid}}</h4>
                            </div>
                            <div class="col-xs-6 text-right">
                                <small class="stat-label">DUE</small>
                                <h4 id="myTargetElement3.2">{{$due}}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-md-12">

            <div class="portlet box danger">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="livicon" data-name="lab" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                        <h3>CLIENT INFORMATION</h3>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="table-scrollable">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>CLIENT</th>
                                <th>LIMIT</th>
                                <th>PAYMENT TYPE</th>
                                <th>EXPIRATION DATE</th>
                                <th>TOTAL AMOUNT</th>
                                <th>DUE</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($location1 as $location)
                                <tr class="active">
                                    <td>{{$location->id}}</td>
                                    <td>
                                        @php
                                            $userId = $location->user_id;
                                            $user = \App\User::find($userId);
                                            $user_name=$user->name;
                                        @endphp
                                        {{$user_name}}
                                    </td>
                                    <td>
                                        @php
                                            $paymentId = $location->payment_id;
                                            $user = \App\Payment::find($paymentId);
                                            $user_limit=$user->user_limit;
                                        @endphp
                                        {{$user_limit." Users"}}
                                    </td>

                                    <td>
                                      @php
                                          $paymentId = $location->payment_id;
                                          $user = \App\Payment::find($paymentId);
                                          $paymenttype=$user->paymenttype;
                                          $status_payment=$user->status_payment;
                                      @endphp
                                      {{$paymenttype.' ('.$status_payment.')'}}
                                    </td>
                                    <td>
                                      @php
                                          $paymentId = $location->payment_id;
                                          $user = \App\Payment::find($paymentId);
                                          $expiration_date=$user->expiration_date;
                                      @endphp
                                      {{$expiration_date}}
                                    </td>
                                    <td>
                                        {{$location->	total_amount." TAKA"}}
                                    </td>
                                    <td>
                                        {{$location->due." TAKA"}}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
    @endrole
    {{-------------------------------------------}}
    @role('viewer')
    <div class="row">
        <div class="col-lg-4 col-md-6 col-sm-6 margin_10 animated fadeInLeftBig">
            <!-- Trans label pie charts strats here-->
            <div class="lightbluebg no-radius">
                <div class="panel-body squarebox square_boxs">
                    <div class="col-xs-12 pull-left nopadmar">
                        <div class="row">
                            <div class="square_box col-xs-7 text-right">
                                <span>TOTAL CLIENTS</span>
                                <div class="number" id="myTargetElement1">{{$totalCompany}}</div>
                            </div>
                            <div class="square_box col-xs-3 pull-right">
                            <i class="fa fa-home fa-5x" aria-hidden="true"></i>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <small class="stat-label">ACTIVE</small>
                                <h4 id="myTargetElement1.1">{{$totalstatus}}</h4>
                            </div>
                            <div class="col-xs-6 text-right">
                                <small class="stat-label">INACTIVE</small>
                                <h4 id="myTargetElement1.2">{{$totinalactive_status}}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-6 margin_10 animated fadeInUpBig">
            <!-- Trans label pie charts strats here-->
            <div class="redbg no-radius">
                <div class="panel-body squarebox square_boxs">
                    <div class="col-xs-12 pull-left nopadmar">
                        <div class="row">
                            <div class="square_box col-xs-7 pull-left">
                                <span>TOTAL USERS</span>
                                <div class="number" id="myTargetElement2">{{$totalEmployee}}</div>
                            </div>
                            <div class="square_box col-xs-3 pull-right">
                            <i class="fa fa-user fa-5x" aria-hidden="true"></i>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <small class="stat-label">ACTIVE</small>
                                <h4 id="myTargetElement1.1">{{$totalactive_enduser}}</h4>
                            </div>
                            <div class="col-xs-6 text-right">
                                <small class="stat-label">INACTIVE</small>
                                <h4 id="myTargetElement1.2">{{$totinalactive_enduser}}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-sm-6 col-md-6 margin_10 animated fadeInDownBig">
            <!-- Trans label pie charts strats here-->
            <div class="palebluecolorbg no-radius">
                <div class="panel-body squarebox square_boxs">
                    <div class="col-xs-12 pull-left nopadmar">
                        <div class="row">
                            <div class="square_box col-xs-7 pull-left">
                                <span>TOTAL PAYMENTS</span>
                                <div class="number" id="myTargetElement3">{{$totalpayment}}</div>
                            </div>
                            <div class="square_box col-xs-3 pull-right">
                              <!-- <i class="livicon pull-right" data-name="archive-add" data-l="true" data-c="#fff" data-hc="#fff" data-s="70"></i> -->
                            <i class="fa fa-money fa-5x" aria-hidden="true"></i>
                            </div>


                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <small class="stat-label">PAID</small>
                                <h4 id="myTargetElement3.1">{{$paid}}</h4>
                            </div>
                            <div class="col-xs-6 text-right">
                                <small class="stat-label">DUE</small>
                                <h4 id="myTargetElement3.2">{{$due}}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-md-12">

            <div class="portlet box danger">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="livicon" data-name="lab" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                        <h3>CLIENT INFORMATION</h3>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="table-scrollable">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>CLIENT</th>
                                <th>LIMIT</th>
                                <th>PAYMENT TYPE</th>
                                <th>EXPIRATION DATE</th>
                                <th>TOTAL AMOUNT</th>
                                <th>DUE</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($location1 as $location)
                                <tr class="active">
                                    <td>{{$location->id}}</td>
                                    <td>
                                        @php
                                            $userId = $location->user_id;
                                            $user = \App\User::find($userId);
                                            $user_name=$user->name;
                                        @endphp
                                        {{$user_name}}
                                    </td>
                                    <td>
                                        @php
                                            $paymentId = $location->payment_id;
                                            $user = \App\Payment::find($paymentId);
                                            $user_limit=$user->user_limit;
                                        @endphp
                                        {{$user_limit." Users"}}
                                    </td>

                                    <td>
                                      @php
                                          $paymentId = $location->payment_id;
                                          $user = \App\Payment::find($paymentId);
                                          $paymenttype=$user->paymenttype;
                                          $status_payment=$user->status_payment;
                                      @endphp
                                      {{$paymenttype.' ('.$status_payment.')'}}
                                    </td>
                                    <td>
                                      @php
                                          $paymentId = $location->payment_id;
                                          $user = \App\Payment::find($paymentId);
                                          $expiration_date=$user->expiration_date;
                                      @endphp
                                      {{$expiration_date}}
                                    </td>
                                    <td>
                                        {{$location->	total_amount." TAKA"}}
                                    </td>
                                    <td>
                                        {{$location->due." TAKA"}}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
    @endrole

    @role(['client','amrit','bdthai','daimond','nourish','alpha','getco']);
    <div class="row">
        <div class="col-lg-4 col-md-6 col-sm-6 margin_10 animated fadeInLeftBig">
            <!-- Trans label pie charts strats here-->
            <div class="lightbluebg no-radius">
                <div class="panel-body squarebox square_boxs">
                    <div class="col-xs-12 pull-left nopadmar">
                        <div class="row">
                            <div class="square_box col-xs-7 text-right">
                                <span>TOTAL SUPERVISOR</span>
                                <div class="number" id="myTargetElement1">{{$totalSupervisor}}</div>
                            </div>
                            <div class="square_box col-xs-3 pull-right">
                            <i class="fa fa-user fa-5x" aria-hidden="true"></i>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-6 margin_10 animated fadeInUpBig">
            <!-- Trans label pie charts strats here-->
            <div class="redbg no-radius">
                <div class="panel-body squarebox square_boxs">
                    <div class="col-xs-12 pull-left nopadmar">
                        <div class="row">
                            <div class="square_box col-xs-7 pull-left">
                                <span>TOTAL USERS</span>
                                <div class="number" id="myTargetElement2">{{$totalEmployee}}</div>
                            </div>
                            <div class="square_box col-xs-3 pull-right">
                            <i class="fa fa-user fa-5x" aria-hidden="true"></i>
                            </div>

                        </div>
                        <!-- <div class="row">
                            <div class="col-xs-6">
                                <small class="stat-label">ACTIVE</small>
                                <h4 id="myTargetElement1.1">{{$totalactive_enduser}}</h4>
                            </div>
                            <div class="col-xs-6 text-right">
                                <small class="stat-label">INACTIVE</small>
                                <h4 id="myTargetElement1.2">{{$totinalactive_enduser}}</h4>
                            </div>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-sm-6 col-md-6 margin_10 animated fadeInDownBig">
            <!-- Trans label pie charts strats here-->
            <div class="palebluecolorbg no-radius">
                <div class="panel-body squarebox square_boxs">
                    <div class="col-xs-12 pull-left nopadmar">
                        <div class="row">
                            <div class="square_box col-xs-7 pull-left">
                                <span>TOTAL EXPENSE</span>
                                <div class="number" id="myTargetElement3">{{$total_amount}}</div>
                            </div>
                            <div class="square_box col-xs-3 pull-right">
                              <!-- <i class="livicon pull-right" data-name="archive-add" data-l="true" data-c="#fff" data-hc="#fff" data-s="70"></i> -->
                            <i class="fa fa-money fa-5x" aria-hidden="true"></i>
                            </div>


                        </div>
                        <!-- <div class="row">
                            <div class="col-xs-6">
                                <small class="stat-label">PAID</small>
                                <h4 id="myTargetElement3.1">{{$paid}}</h4>
                            </div>
                            <div class="col-xs-6 text-right">
                                <small class="stat-label">DUE</small>
                                <h4 id="myTargetElement3.2">{{$due}}</h4>
                            </div>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-md-12">

            <div class="portlet box danger">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="livicon" data-name="lab" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                        <h3>EMPLOYEE INFORMATION</h3>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="table-scrollable">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>EMPLOYEE</th>
                                <th>PHONE NUMBER</th>
                                <!-- <th>PAYMENT TYPE</th>
                                <th>EXPIRATION DATE</th>
                                <th>TOTAL AMOUNT</th>
                                <th>DUE</th> -->
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($location1 as $location)
                                <tr class="active">
                                    <td>{{$location->id}}</td>
                                    <td>
                                        @php

                                            $user_name=$location->name;
                                        @endphp
                                        {{$user_name}}
                                    </td>
                                    <td>
                                        @php

                                            $user_phone_number=$location->phone_number;
                                        @endphp
                                        {{$user_phone_number}}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
    @endrole
    {{-------------------------------------------}}
    {{-------------------------------------------}}
    @role(['employees']);
    <div class="row">
        @foreach($empData as $employees)
        <div class="col-lg-4 col-md-6 col-sm-6 margin_10 animated fadeInLeftBig">
            <!-- Trans label pie charts strats here-->
            <div class="lightbluebg no-radius">
                <div class="panel-body squarebox square_boxs">
                    <div class="col-xs-12 pull-left nopadmar">
                        <div class="row">
                            @foreach($empData as $employees)
                                <div class="square_box col-xs-10 text-left">
                                    <span>YOUR NAME</span>
                                    <div class="number" id="myTargetElement1">
                                        {{Auth::user()->name}}
                                    </div>
                                    <div class="col-xs text-right">
                                        <i class="fa fa-home fa-5x" aria-hidden="true"></i>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-6 margin_10 animated fadeInUpBig">
            <!-- Trans label pie charts strats here-->
            <div class="redbg no-radius">
                <div class="panel-body squarebox square_boxs">
                    <div class="col-xs-12 pull-left nopadmar">
                        <div class="row">
                            <div class="square_box col-xs-7 pull-left">
                                <span>DESIGNATION: </span>
                                <div class="" id="myTargetElement2"><span>{{$employees->designation}}</span></div>
                            </div>
                            <i class="fa fa-user fa-5x" aria-hidden="true"></i>
                        </div>
                        <div class="col-xs-6">
                            <small class="stat-label">JOINING DATE:</small>
                            @php
                           $data = Carbon::createFromTimeStamp(strtotime($employees->created_at))->diffForHumans();
                            @endphp
                            <h4 id="myTargetElement3.1">{{$data}}</h4>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-sm-6 col-md-6 margin_10 animated fadeInDownBig">
            <!-- Trans label pie charts strats here-->
            <div class="goldbg no-radius">
                <div class="panel-body squarebox square_boxs">
                    <div class="col-xs-12 pull-left nopadmar">
                        <div class="row">
                            <div class="square_box col-xs-7 pull-left">
                                <span>Subscribers</span>
                                <div class="number" id="myTargetElement3"></div>
                            </div>
                            <i class="livicon pull-right" data-name="archive-add" data-l="true" data-c="#fff" data-hc="#fff" data-s="70"></i>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <small class="stat-label">Last Week</small>
                                <h4 id="myTargetElement3.1"></h4>
                            </div>
                            <div class="col-xs-6 text-right">
                                <small class="stat-label">Last Month</small>
                                <h4 id="myTargetElement3.2"></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
            @endforeach
    </div>

    <div class="row">
        <div class="col-md-6">
            <!-- BEGIN SAMPLE TABLE PORTLET-->
            <div class="portlet box danger">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="livicon" data-name="lab" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                        <div class="btn btn-success btn-xs">{{Auth::user()->name}}</div>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="table-scrollable">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>NAME</th>
                                <th>DATE</th>
                                <th>STATUS</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($empAttendance as $attendances)
                                <tr class="active">
                                    <td>{{$attendances->id}}</td>
                                    <td>
                                        @php
                                            $empId = $attendances->employee_id;
                                            $emp = \App\Employee::find($empId);
                                            $userId = $emp->user_id;
                                            $user = \App\User::find($userId);
                                        @endphp
                                        <a href="{{ url('employees/' . $attendances->employee_id)}}">{{$user->name}}</a>
                                    </td>
                                    <td>{{$attendances->date_time}}</td>
                                    <td>
                                        @if (($attendances->status) == 1)
                                            <div class="btn btn-info btn-xs">START TIME </div>
                                        @elseif (($attendances->status) == 2)
                                            <div class="btn btn-success btn-xs">END TIME</div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endrole
@stop
