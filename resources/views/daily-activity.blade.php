<?php
  // use App\Http\Controllers\FunctionController;
  // $Function 	= new FunctionController();
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
                        <h3 class="p-3 mb-2 bg-primary text-white" style="padding:2px;">Daily Activity</h3>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="scrollmenu">
                      <button style="margin-right:5px" class="btn btn-info" id="exportButton" onclick="createFormattingWorkbook()">Export to csv</button>
                      <button class="btn btn-danger" id="exportButton" onclick="createFormattingWorkbook()">Export to pdf</button>
                        <table class="table table-bordered table-hover" id="exportTable">
                          <thead>
                              <tr>
                                  <th>Date</th>
                                  <th>Region</th>
                                  <th>Area</th>
                                  <th>Duration</th>
                                  <th>Purpose</th>

                                  <th>Name of Farmer</th>
                                  <th>Farmer Address</th>
                                  <th>Farmer Phone</th>
                                  <th>Farm Capacity</th>
                                  <th>Farm Type</th>
                                  <th>Comments</th>

                                  <th>Name of Agent</th>
                                  <th>Agent Type</th>
                                  <th>Agent Address</th>
                                  <th>Agent Phone</th>
                                  <th>Comments</th>

                                  <th>Name of Agent</th>
                                  <th>Name of Sub Agent</th>
                                  <th>Sub Agent Address</th>
                                  <th>Sub Agent Phone</th>
                                  <th>Customer Feedback</th>
                              </tr>
                          </thead>

                          <tbody>
                              @foreach($dates as $date)
                                  @php
                                      $activityCount   = 0;
                                      $farmCount       = 0;
                                      $agentCount      = 0;
                                      $subagentCount      = 0;
                                      $numbers         = array();
                                      $maxRow          = 1;
                                      if(isset($activities[$date])){
                                          $activityCount      = sizeof($activities[$date]);
                                          array_push($numbers, $activityCount);
                                      }
                                      if(isset($farmersArr[$date])){
                                          $farmCount      = sizeof($farmersArr[$date]);
                                          array_push($numbers, $farmCount);
                                      }

                                      if(isset($agentsArr[$date])){
                                          $agentCount         = sizeof($agentsArr[$date]);
                                          array_push($numbers, $agentCount);
                                      }

                                      if(isset($subagentsArr[$date])){
                                          $subagentCount         = sizeof($subagentsArr[$date]);
                                          array_push($numbers, $subagentCount);
                                      }
                                      $maxRow =  max($numbers); // Number of rows in a date
                                      unset($numbers); // Empty this array for this Loop.
                                  @endphp
                                  @for($i = 0; $i < $maxRow; $i++)
                                      <tr>
                                          <td>{{ $i === 0 ? $date : "-" }}</td>
                                          @if(isset($activities[$date]))
                                              @php
                                                  $activityDataArr    = $activities[$date];
                                              @endphp
                                              @if(isset($activityDataArr[$i]))
                                                  @php
                                                      $activity = $activityDataArr[$i];
                                                  @endphp
                                                  <td>{{ $activity['region'] }}</td>
                                                  <td>{{ $activity['name_of_area'] }}</td>
                                                  <td>{{ $activity['working_duration'] }}</td>
                                                  <td>{{ $activity['purpose'] }}</td>
                                              @else
                                                  <td>-</td>
                                                  <td>-</td>
                                                  <td>-</td>
                                                  <td>-</td>
                                              @endif
                                          @endif

                                          <!-- Farmers -->
                                          @if(isset($farmersArr[$date]))
                                              @php
                                                  $farmerdataArr =  $farmersArr[$date];
                                              @endphp
                                              @if(isset($farmerdataArr[$i]))
                                                  @php
                                                      $farmerdata = $farmerdataArr[$i];
                                                  @endphp
                                                  <td>{{ $farmerdata['name'] }}</td>
                                                  <td>{{ $farmerdata['address'] }}</td>
                                                  <td>{{ $farmerdata['phone'] }}</td>
                                                  <td>{{ $farmerdata['farm_capacity'] }}</td>
                                                  <td>{{ $farmerdata['farm_type'] }}</td>
                                                  <td>{{ $farmerdata['comments'] }}</td>
                                              @else
                                                  <td>-</td>
                                                  <td>-</td>
                                                  <td>-</td>
                                                  <td>-</td>
                                                  <td>-</td>
                                                  <td>-</td>
                                              @endif
                                          @else
                                              <td>-</td>
                                              <td>-</td>
                                              <td>-</td>
                                              <td>-</td>
                                              <td>-</td>
                                              <td>-</td>
                                          @endif

                                          <!-- Agents -->
                                          @if(isset($agentsArr[$date]))
                                              @php
                                                  $agentDataArr =  $agentsArr[$date];
                                              @endphp
                                              @if(isset($agentDataArr[$i]))
                                                  @php
                                                      $agentdata = $agentDataArr[$i];
                                                  @endphp
                                                  <td>{{ $agentdata['name'] }}</td>
                                                  <td>{{ $agentdata['agent_type'] }}</td>
                                                  <td>{{ $agentdata['address'] }}</td>
                                                  <td>{{ $agentdata['phone'] }}</td>
                                                  <td>{{ $agentdata['comments'] }}</td>
                                              @else
                                                  <td>-</td>
                                                  <td>-</td>
                                                  <td>-</td>
                                                  <td>-</td>
                                                  <td>-</td>
                                              @endif
                                          @else
                                              <td>-</td>
                                              <td>-</td>
                                              <td>-</td>
                                              <td>-</td>
                                              <td>-</td>
                                          @endif


                                          <!--Sub Agents -->
                                          @if(isset($subagentsArr[$date]))
                                              @php
                                                  $subagentDataArr =  $subagentsArr[$date];
                                              @endphp
                                              @if(isset($subagentDataArr[$i]))
                                                  @php
                                                      $subagentdata = $subagentDataArr[$i];
                                                  @endphp
                                                  <td>{{ $subagentdata['name_agent'] }}</td>
                                                  <td>{{ $subagentdata['name_sub_agent'] }}</td>
                                                  <td>{{ $subagentdata['address'] }}</td>
                                                  <td>{{ $subagentdata['phone'] }}</td>
                                                  <td>{{ $subagentdata['customer_feedback'] }}</td>
                                              @else
                                                  <td>-</td>
                                                  <td>-</td>
                                                  <td>-</td>
                                                  <td>-</td>
                                                  <td>-</td>
                                              @endif
                                          @else
                                              <td>-</td>
                                              <td>-</td>
                                              <td>-</td>
                                              <td>-</td>
                                              <td>-</td>
                                          @endif


                                      </tr>

                                  @endfor

                              @endforeach
                          </tbody>
                        </table>
                    </div>
                </div>


        </div>
    </div>
    @endrole

    @role(['client','daimond','amrit','nourish','alpha'])
    <div class="row">
        <div class="col-md-12">


                <div class="portlet-title">
                    <div class="caption">
                        <i class="livicon" data-name="lab" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                        <h3>Daily Activity</h3>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="scrollmenu">
                      <table class="table table-bordered table-hover" id="exportTable">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Region</th>
                                <th>Area</th>
                                <th>Duration</th>
                                <th>Purpose</th>

                                <th>Name of Farmer</th>
                                <th>Farmer Address</th>
                                <th>Farmer Phone</th>
                                <th>Farm Capacity</th>
                                <th>Farm Type</th>
                                <th>Comments</th>

                                <th>Name of Agent</th>
                                <th>Agent Type</th>
                                <th>Agent Address</th>
                                <th>Agent Phone</th>
                                <th>Comments</th>

                                <th>Name of Agent</th>
                                <th>Name of Sub Agent</th>
                                <th>Sub Agent Address</th>
                                <th>Sub Agent Phone</th>
                                <th>Customer Feedback</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($dates as $date)
                                @php
                                    $activityCount   = 0;
                                    $farmCount       = 0;
                                    $agentCount      = 0;
                                    $subagentCount      = 0;
                                    $numbers         = array();
                                    $maxRow          = 1;
                                    if(isset($activities[$date])){
                                        $activityCount      = sizeof($activities[$date]);
                                        array_push($numbers, $activityCount);
                                    }
                                    if(isset($farmersArr[$date])){
                                        $farmCount      = sizeof($farmersArr[$date]);
                                        array_push($numbers, $farmCount);
                                    }

                                    if(isset($agentsArr[$date])){
                                        $agentCount         = sizeof($agentsArr[$date]);
                                        array_push($numbers, $agentCount);
                                    }

                                    if(isset($subagentsArr[$date])){
                                        $subagentCount         = sizeof($subagentsArr[$date]);
                                        array_push($numbers, $subagentCount);
                                    }
                                    $maxRow =  max($numbers); // Number of rows in a date
                                    unset($numbers); // Empty this array for this Loop.
                                @endphp
                                @for($i = 0; $i < $maxRow; $i++)
                                    <tr>
                                        <td>{{ $i === 0 ? $date : "-" }}</td>
                                        @if(isset($activities[$date]))
                                            @php
                                                $activityDataArr    = $activities[$date];
                                            @endphp
                                            @if(isset($activityDataArr[$i]))
                                                @php
                                                    $activity = $activityDataArr[$i];
                                                @endphp
                                                <td>{{ $activity['region'] }}</td>
                                                <td>{{ $activity['name_of_area'] }}</td>
                                                <td>{{ $activity['working_duration'] }}</td>
                                                <td>{{ $activity['purpose'] }}</td>
                                            @else
                                                <td>-</td>
                                                <td>-</td>
                                                <td>-</td>
                                                <td>-</td>
                                            @endif
                                        @endif

                                        <!-- Farmers -->
                                        @if(isset($farmersArr[$date]))
                                            @php
                                                $farmerdataArr =  $farmersArr[$date];
                                            @endphp
                                            @if(isset($farmerdataArr[$i]))
                                                @php
                                                    $farmerdata = $farmerdataArr[$i];
                                                @endphp
                                                <td>{{ $farmerdata['name'] }}</td>
                                                <td>{{ $farmerdata['address'] }}</td>
                                                <td>{{ $farmerdata['phone'] }}</td>
                                                <td>{{ $farmerdata['farm_capacity'] }}</td>
                                                <td>{{ $farmerdata['farm_type'] }}</td>
                                                <td>{{ $farmerdata['comments'] }}</td>
                                            @else
                                                <td>-</td>
                                                <td>-</td>
                                                <td>-</td>
                                                <td>-</td>
                                                <td>-</td>
                                                <td>-</td>
                                            @endif
                                        @else
                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                        @endif

                                        <!-- Agents -->
                                        @if(isset($agentsArr[$date]))
                                            @php
                                                $agentDataArr =  $agentsArr[$date];
                                            @endphp
                                            @if(isset($agentDataArr[$i]))
                                                @php
                                                    $agentdata = $agentDataArr[$i];
                                                @endphp
                                                <td>{{ $agentdata['name'] }}</td>
                                                <td>{{ $agentdata['agent_type'] }}</td>
                                                <td>{{ $agentdata['address'] }}</td>
                                                <td>{{ $agentdata['phone'] }}</td>
                                                <td>{{ $agentdata['comments'] }}</td>
                                            @else
                                                <td>-</td>
                                                <td>-</td>
                                                <td>-</td>
                                                <td>-</td>
                                                <td>-</td>
                                            @endif
                                        @else
                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                        @endif


                                        <!--Sub Agents -->
                                        @if(isset($subagentsArr[$date]))
                                            @php
                                                $subagentDataArr =  $subagentsArr[$date];
                                            @endphp
                                            @if(isset($subagentDataArr[$i]))
                                                @php
                                                    $subagentdata = $subagentDataArr[$i];
                                                @endphp
                                                <td>{{ $subagentdata['name_agent'] }}</td>
                                                <td>{{ $subagentdata['name_sub_agent'] }}</td>
                                                <td>{{ $subagentdata['address'] }}</td>
                                                <td>{{ $subagentdata['phone'] }}</td>
                                                <td>{{ $subagentdata['customer_feedback'] }}</td>
                                            @else
                                                <td>-</td>
                                                <td>-</td>
                                                <td>-</td>
                                                <td>-</td>
                                                <td>-</td>
                                            @endif
                                        @else
                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                        @endif


                                    </tr>

                                @endfor

                            @endforeach
                        </tbody>
                      </table>
                    </div>
                </div>


        </div>
    </div>
    @endrole

@stop
