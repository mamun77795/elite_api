<?php
  use App\Http\Controllers\OutletFunctionController;
  $Function 	= new OutletFunctionController();
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
                        <h3>End User Outlets</h3>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="scrollmenu">
                        <table class="table table-bordered table-hover" id="searchResult">
                            <thead>
                            <tr>
                                <th>ENDUSER NAME</th>
                                <th>ZONE</th>
                                <th>AREA</th>
                                <th>OUTLET NAME</th>
                                <th>OUTLET CATEGORY</th>
                                <th>ADDRESS</th>
                                <th>latitude</th>
                                <th>longitude</th>
                                <th>PROPRIETOR NAME</th>
                                <th>PROPRIETOR PHONE</th>
                                <th>DATE TIME</th>
                            </tr>
                            </thead>
                            <tbody>
                              @foreach($endUsers as $eu)
                                <tr>
                                  <td>{{ $Function->getEndUserName($eu->enduser_id) }}</td>
                                  <td>{{ $Function->getdivision_id($eu->division_id) }}</td>
                                  <td>{{ $Function->getdistrict_id($eu->district_id) }}</td>
                                  <td>{{ $Function->getoutlet_name($eu->id) }}</td>
                                  <td>{{ $Function->getoutlet_category($eu->id) }}</td>
                                  <td>{{ $Function->getperson_address($eu->id) }}</td>
                                  <td>{{ $Function->getperson_latitude($eu->id) }}</td>
                                  <td>{{ $Function->getperson_longitude($eu->id) }}</td>
                                  <td>{{ $Function->getperson_name($eu->id) }}</td>
                                  <td>{{ $Function->getperson_number($eu->id) }}</td>
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

    @role(['client','bdthai','getco'])
    <div class="row">
        <div class="col-md-12">


                <div class="portlet-title">
                    <div class="caption">
                        <i class="livicon" data-name="lab" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                        <h3>End User Outlets</h3>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="scrollmenu">
                      <table class="table table-bordered table-hover" id="searchResult">
                          <thead>
                          <tr>
                              <th>ENDUSER NAME</th>
                              <th>ZONE</th>
                              <th>AREA</th>
                              <th>OUTLET NAME</th>
                              <th>OUTLET CATEGORY</th>
                              <th>ADDRESS</th>
                              <th>PROPRIETOR NAME</th>
                              <th>PROPRIETOR PHONE</th>
                              <th>DATE TIME</th>
                          </tr>
                          </thead>
                          <tbody>
                            @foreach($endUsers as $eu)
                              <tr>
                                <td>{{ $Function->getEndUserName($eu->enduser_id) }}</td>
                                <td>{{ $Function->getdivision_id($eu->division_id) }}</td>
                                <td>{{ $Function->getdistrict_id($eu->district_id) }}</td>
                                <td>{{ $Function->getoutlet_name($eu->id) }}</td>
                                <td>{{ $Function->getoutlet_category($eu->id) }}</td>
                                <td>{{ $Function->getperson_address($eu->id) }}</td>
                                <td>{{ $Function->getperson_name($eu->id) }}</td>
                                <td>{{ $Function->getperson_number($eu->id) }}</td>
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
