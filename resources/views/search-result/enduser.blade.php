<?php
  use App\Http\Controllers\FunctionController;
  $Function 	= new FunctionController();
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
                        <h3>End User Search Result of <em>{{ $supervisorName }}</em> from {{ $from }} to {{ $to }}</h3>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="table-scrollable">
                        <table class="table table-bordered table-hover" id="searchResult">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>CLIENT</th>
                                <th>NAME</th>
                                <th>PHONE NUMBER</th>
                                <th>STATUS</th>
                                <th>INVITATION</th>
                            </tr>
                            </thead>
                            <tbody>
                              @foreach($endUsers as $eu)
                                <tr>
                                  <td>{{ $loop->index+1 }}</td>
                                  <td>{{ $Function->getClientNamebyID($eu->client_id) }}</td>
                                  <td>{{ $Function->getEndUserName($eu->id) }}</td>
                                  <td>{{ $Function->getEndUserPhoneNumber($eu->id) }}</td>
                                  <td>{{ $Function->getEndUserStatus($eu->id) }}</td>
                                  <td>{{ $Function->getInvitationCodebyID($eu->invitation_id) }}</td>
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

            <div class="portlet box danger">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="livicon" data-name="lab" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                        <h3>End User Search Result of <em>{{ $supervisorName }}</em> from {{ $from }} to {{ $to }}</h3>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="table-scrollable">
                        <table class="table table-bordered table-hover" id="searchResult">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>CLIENT</th>
                                <th>INVITATION</th>
                            </tr>
                            </thead>
                            <tbody>
                              @foreach($endUsers as $eu)
                                <tr>
                                  <td>{{ $loop->index+1 }}</td>
                                  <td>{{ $Function->getClientNamebyID($eu->client_id) }}</td>
                                  <td>{{ $Function->getInvitationCodebyID($eu->invitation_id) }}</td>
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
