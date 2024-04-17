<?php
  use App\Http\Controllers\LocationFunctionController;
  $Function 	= new LocationFunctionController();
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
                                <th>#</th>
                                <th>CLIENT</th>
                                <th>SUPERVISOR NAME</th>
                                <th>ENDUSER NAME</th>
                                <th>SUGGESTION</th>
                                <th>ADDRESS</th>
                                <th>DISTANCE(KM)</th>
                                <th>DATE TIME</th>
                            </tr>
                            </thead>
                            <tbody>
                              @php
                              $index = 0;

                              $temp_enduser;
                              @endphp
                              @foreach($endUsers as $eu)

                              @if ($index == 0)
                              @php
                              $firstrow_enduser = $eu;
                              $temp_enduser = $eu;
                              $totalDistance = 0;
                              @endphp

                              <tr>
                                <td>{{ $loop->index+1 }}</td>
                                <td>{{ $Function->getClientNamebyID($eu->client_id) }}</td>
                                <td>{{ $Function->getSupervisorNamebyID($eu->supervisor_id) }}</td>
                                <td>{{ $Function->getEndUserName($eu->enduser_id) }}</td>
                                <td>{{ $Function->getsuggestion($eu->suggestion_id) }}</td>
                                <td>{{ $Function->getEndUserAddress($eu->id) }}</td>
                                <td>0</td>
                                <td>{{ $Function->getEndUserDateTime($eu->id) }}</td>
                              </tr>
                              @php
                              $index++;
                              continue;
                              @endphp

                              @endif


                                <tr>
                                  <td>{{ $loop->index+1 }}</td>
                                  <td>{{ $Function->getClientNamebyID($eu->client_id) }}</td>
                                  <td>{{ $Function->getSupervisorNamebyID($eu->supervisor_id) }}</td>
                                  <td>{{ $Function->getEndUserName($eu->enduser_id) }}</td>
                                  <td>{{ $Function->getsuggestion($eu->suggestion_id) }}</td>
                                  <td>{{ $Function->getEndUserAddress($eu->id) }}</td>

                                  @if($Function->getEndUserDateTime($eu->id) == $Function->getEndUserDateTime($temp_enduser->id))
                                    <td>{{$Function->getEndUserDistance($eu->id,$temp_enduser->id) }}</td>
                                    @php $totalDistance += $Function->getEndUserDistance($eu->id,$temp_enduser->id)   @endphp
                                  @else
                                    <td>0</td>
                                  @endif

                                  @php
                                    $temp_enduser = $eu;
                                  @endphp

                                  <td>{{ $Function->getEndUserDateTime($eu->id) }}</td>

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
                                echo 'Total: '. $totalDistance;
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

    @role(['client','bdthai'])
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
                        <table class="table table-bordered table-hover" id="searchResult">
                          <thead>
                          <tr>
                              <th>#</th>
                              <th>CLIENT</th>
                              <th>SUPERVISOR NAME</th>
                              <th>ENDUSER NAME</th>
                              <th>SUGGESTION</th>
                              <th>ADDRESS</th>
                              <th>DATE TIME</th>
                          </tr>
                          </thead>
                          <tbody>
                            @foreach($endUsers as $eu)
                              <tr>
                                <td>{{ $loop->index+1 }}</td>
                                <td>{{ $Function->getClientNamebyID($eu->client_id) }}</td>
                                <td>{{ $Function->getSupervisorNamebyID($eu->supervisor_id) }}</td>
                                <td>{{ $Function->getEndUserName($eu->enduser_id) }}</td>
                                <td>{{ $Function->getsuggestion($eu->suggestion_id) }}</td>
                                <td>{{ $Function->getEndUserAddress($eu->id) }}</td>
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

    @role(['daimond'])
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
                      <table class="table table-bordered table-hover" id="exportTable">
                          <thead>
                          <tr>
                              <th>#</th>
                              <th>CLIENT</th>
                              <th>SUPERVISOR NAME</th>
                              <th>ENDUSER NAME</th>
                              <th>SUGGESTION</th>
                              <th>ADDRESS</th>
                              <th>DISTANCE(KM)</th>
                              <th>DATE TIME</th>
                          </tr>
                          </thead>
                          <tbody>
                            @php
                            $index = 0;

                            $temp_enduser;
                            @endphp
                            @foreach($endUsers as $eu)

                            @if ($index == 0)
                            @php
                            $firstrow_enduser = $eu;
                            $temp_enduser = $eu;
                            $totalDistance = 0;
                            @endphp

                            <tr>
                              <td>{{ $loop->index+1 }}</td>
                              <td>{{ $Function->getClientNamebyID($eu->client_id) }}</td>
                              <td>{{ $Function->getSupervisorNamebyID($eu->supervisor_id) }}</td>
                              <td>{{ $Function->getEndUserName($eu->enduser_id) }}</td>
                              <td>{{ $Function->getsuggestion($eu->suggestion_id) }}</td>
                              <td>{{ $Function->getEndUserAddress($eu->id) }}</td>
                              <td>0</td>
                              <td>{{ $Function->getEndUserDateTime($eu->id) }}</td>
                            </tr>
                            @php
                            $index++;
                            continue;
                            @endphp

                            @endif


                              <tr>
                                <td>{{ $loop->index+1 }}</td>
                                <td>{{ $Function->getClientNamebyID($eu->client_id) }}</td>
                                <td>{{ $Function->getSupervisorNamebyID($eu->supervisor_id) }}</td>
                                <td>{{ $Function->getEndUserName($eu->enduser_id) }}</td>
                                <td>{{ $Function->getsuggestion($eu->suggestion_id) }}</td>
                                <td>{{ $Function->getEndUserAddress($eu->id) }}</td>

                                @if($Function->getEndUserDateTime($eu->id) == $Function->getEndUserDateTime($temp_enduser->id))
                                  <td>{{$Function->getEndUserDistance($eu->id,$temp_enduser->id) }}</td>
                                  @php $totalDistance += $Function->getEndUserDistance($eu->id,$temp_enduser->id)   @endphp
                                @else
                                  <td>0</td>
                                @endif

                                @php
                                  $temp_enduser = $eu;
                                @endphp

                                <td>{{ $Function->getEndUserDateTime($eu->id) }}</td>

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
                              echo 'Total: '. $totalDistance;
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

    @role(['nourish','alpha'])
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
                      <table class="table table-bordered table-hover" id="exportTable">
                          <thead>
                          <tr>
                              <th>#</th>
                              <th>CLIENT</th>
                              <th>SUPERVISOR NAME</th>
                              <th>ENDUSER NAME</th>
                              <th>SUGGESTION</th>
                              <th>ADDRESS</th>
                              <th>DISTANCE(KM)</th>
                              <th>DATE TIME</th>
                          </tr>
                          </thead>
                          <tbody>
                            @php
                            $index = 0;

                            $temp_enduser;
                            @endphp
                            @foreach($endUsers as $eu)

                            @if ($index == 0)
                            @php
                            $firstrow_enduser = $eu;
                            $temp_enduser = $eu;
                            $totalDistance = 0;
                            @endphp

                            <tr>
                              <td>{{ $loop->index+1 }}</td>
                              <td>{{ $Function->getClientNamebyID($eu->client_id) }}</td>
                              <td>{{ $Function->getSupervisorNamebyID($eu->supervisor_id) }}</td>
                              <td>{{ $Function->getEndUserName($eu->enduser_id) }}</td>
                              <td>{{ $Function->getsuggestion($eu->suggestion_id) }}</td>
                              <td>{{ $Function->getEndUserAddress($eu->id) }}</td>
                              <td>0</td>
                              <td>{{ $Function->getEndUserDateTime($eu->id) }}</td>
                            </tr>
                            @php
                            $index++;
                            continue;
                            @endphp

                            @endif


                              <tr>
                                <td>{{ $loop->index+1 }}</td>
                                <td>{{ $Function->getClientNamebyID($eu->client_id) }}</td>
                                <td>{{ $Function->getSupervisorNamebyID($eu->supervisor_id) }}</td>
                                <td>{{ $Function->getEndUserName($eu->enduser_id) }}</td>
                                <td>{{ $Function->getsuggestion($eu->suggestion_id) }}</td>
                                <td>{{ $Function->getEndUserAddress($eu->id) }}</td>

                                @if($Function->getEndUserDateTime($eu->id) == $Function->getEndUserDateTime($temp_enduser->id))
                                  <td>{{$Function->getEndUserDistance($eu->id,$temp_enduser->id) }}</td>
                                  @php $totalDistance += $Function->getEndUserDistance($eu->id,$temp_enduser->id)   @endphp
                                @else
                                  <td>0</td>
                                @endif

                                @php
                                  $temp_enduser = $eu;
                                @endphp

                                <td>{{ $Function->getEndUserDateTime($eu->id) }}</td>

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
                              echo 'Total: '. $totalDistance;
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
