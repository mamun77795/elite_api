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
                    <h3 class="p-3 mb-2 bg-primary text-white" style="padding:2px;">End User Search Result of</h3>
                </div>
            </div>
            <div class="portlet-body">
                <div class="table-scrollable">

                    <table class="table table-bordered table-hover" id="exportTable">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>NAME</th>
                            <th>EMAIL</th>
                            <th>CODE</th>
                            <th>PASSWORD</th>
                            <th>ADDRESS</th>
                            <th>PHONE NUMBER</th>
                            <th>TYPE</th>
                            <th>USER LIMIT</th>
                            <th>STATUS</th>
                            <th>DATE TIME</th>
                        </tr>
                        </thead>
                        <tbody>
                          @foreach($all_forms_info as $eu)
                            <tr>
                              <td>{{$eu->id}}</td>
                              <td>{{$eu->name}}</td>
                              <td>{{$eu->email}}</td>
                              <td>{{$eu->code}}</td>
                              <td>{{$eu->password}}</td>
                              <td>{{$eu->address}}</td>
                              <td>{{$eu->phone_number}}</td>
                              <td>{{$eu->type}}</td>
                              <td>{{$eu->user_limit}}</td>
                              <td>{{$eu->status}}</td>
                              <td>{{$eu->created_at}}</td>
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
