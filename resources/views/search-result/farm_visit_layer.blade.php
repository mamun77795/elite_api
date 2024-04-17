<?php
  use App\Http\Controllers\FarmVisitLayerFunctionController;
  $Function 	= new FarmVisitLayerFunctionController();
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
                      <h3>Farm Visit Layer</h3>
                  </div>
                </div>
                <div class="portlet-body">
                    <div class="scrollmenu">
                        <table class="table table-bordered table-hover" id="searchResult">
                          <thead>
                          <tr>
                              <th>USER NAME</th>
                              <th>NAME</th>
                              <th>PHONE</th>
                              <th>ADDRESS</th>
                              <th>FARM NAME</th>
                              <th>FARM NUMBER</th>
                              <th>FARM ADDRESS</th>
                              <th>VISITING DATE</th>
                              <th>NO OF BIRD</th>
                              <th>AGE OF BIRD</th>
                              <th>AGE OF FARM</th>
                              <th>FARM DENSITY</th>
                              <th>HATCHERY NAME</th>
                              <th>FEED COMPANY</th>
                              <th>HEALTH CONDITION</th>
                              <th>FEED INTAKE(ACTUAL)</th>
                              <th>FEED INTAKE(STANDARD)</th>
                              <th>TOTAL FEED INTAKE(ACTUAL)</th>
                              <th>TOTAL FEED INTAKE(STANDARD)</th>
                              <th>TOTAL WATER INTAKE(ACTUAL)</th>
                              <th>TOTAL WATER INTAKE(STANDARD)</th>
                              <th>% PRODUCTION(ACTUAL)</th>
                              <th>% PRODUCTION(STANDARD)</th>
                              <th>AVG BODY WT(ACTUAL)</th>
                              <th>AVG BODY WT(STANDARD)</th>
                              <th>LIGHTING HOUR(ACTUAL)</th>
                              <th>LIGHTING HOUR(STANDARD)</th>
                              <th>LAST DEWARMING DATE</th>
                              <th>PERFORMANCE</th>
                              <th>DATE</th>

                          </tr>
                          </thead>
                          <tbody>

                            @foreach($endUsers as $eu)
                              <tr>
                                <td>{{ $Function->getEndUserName($eu->enduser_id) }}</td>
                                <td>{{ $Function->getadvancedealer($eu->advance_dealer_id,$eu->sub_agent_id) }}</td>
                                <td>{{ $Function->getdealerphone($eu->advance_dealer_id,$eu->sub_agent_id) }}</td>
                                <td>{{ $Function->getdealeraddress($eu->advance_dealer_id,$eu->sub_agent_id) }}</td>
                                <td>{{ $Function->getfarm_name($eu->farm_id) }}</td>
                                <td>{{ $Function->getfarm_address($eu->farm_id) }}</td>
                                <td>{{ $Function->getfarm_phone($eu->farm_id) }}</td>
                                <td>{{ $Function->getvisitingdate($eu->id) }}</td>
                                <td>{{ $Function->getnoofbird($eu->id) }}</td>
                                <td>{{ $Function->getageofbird($eu->id) }}</td>
                                <td>{{ $Function->getageoffarm($eu->id) }}</td>
                                <td>{{ $Function->getfarmdensity($eu->id) }}</td>
                                <td>{{ $Function->gethatcheryname($eu->id) }}</td>
                                <td>{{ $Function->getfeedcompany($eu->id) }}</td>
                                <td>{{ $Function->gethealthcondition($eu->id) }}</td>
                                <td>{{ $Function->getfeed_intake_actual($eu->id) }}</td>
                                <td>{{ $Function->getfeed_intake_standard($eu->id) }}</td>
                                <td>{{ $Function->total_feed_intake_actual($eu->id) }}</td>
                                <td>{{ $Function->total_feed_intake_standard($eu->id) }}</td>
                                <td>{{ $Function->total_water_intake_actual($eu->id) }}</td>
                                <td>{{ $Function->total_water_intake_standard($eu->id) }}</td>
                                <td>{{ $Function->percent_production_actual($eu->id) }}</td>
                                <td>{{ $Function->percent_production_standard($eu->id) }}</td>
                                <td>{{ $Function->avg_body_wt_actual($eu->id) }}</td>
                                <td>{{ $Function->avg_body_wt_standard($eu->id) }}</td>
                                <td>{{ $Function->lighting_hour_actual($eu->id) }}</td>
                                <td>{{ $Function->lighting_hour_standard($eu->id) }}</td>
                                <td>{{ $Function->last_dewarming_date($eu->id) }}</td>
                                <td>{{ $Function->performance($eu->id) }}</td>
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

    @role(['client','bdthai','daimond','amrit'])
    <div class="row">
        <div class="col-md-12">


          <div class="portlet-title">
            <div class="caption">
                <i class="livicon" data-name="lab" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                <h3>Sales Order</h3>
            </div>
          </div>
          <div class="portlet-body">
              <div class="scrollmenu">
                <table class="table table-bordered table-hover" id="searchResult">
                  <thead>
                  <tr>
                      <th>USER NAME</th>
                      <th>NAME</th>
                      <th>PHONE</th>
                      <th>ADDRESS</th>
                      <th>FARM NAME</th>
                      <th>FARM NUMBER</th>
                      <th>FARM ADDRESS</th>
                      <th>VISITING DATE</th>
                      <th>NO OF BIRD</th>
                      <th>AGE OF BIRD</th>
                      <th>AGE OF FARM</th>
                      <th>FARM DENSITY</th>
                      <th>HATCHERY NAME</th>
                      <th>FEED COMPANY</th>
                      <th>HEALTH CONDITION</th>
                      <th>FEED INTAKE(ACTUAL)</th>
                      <th>FEED INTAKE(STANDARD)</th>
                      <th>TOTAL FEED INTAKE(ACTUAL)</th>
                      <th>TOTAL FEED INTAKE(STANDARD)</th>
                      <th>TOTAL WATER INTAKE(ACTUAL)</th>
                      <th>TOTAL WATER INTAKE(STANDARD)</th>
                      <th>% PRODUCTION(ACTUAL)</th>
                      <th>% PRODUCTION(STANDARD)</th>
                      <th>AVG BODY WT(ACTUAL)</th>
                      <th>AVG BODY WT(STANDARD)</th>
                      <th>LIGHTING HOUR(ACTUAL)</th>
                      <th>LIGHTING HOUR(STANDARD)</th>
                      <th>LAST DEWARMING DATE</th>
                      <th>PERFORMANCE</th>
                      <th>DATE</th>

                  </tr>
                  </thead>
                  <tbody>

                    @foreach($endUsers as $eu)
                      <tr>
                        <td>{{ $Function->getEndUserName($eu->enduser_id) }}</td>
                        <td>{{ $Function->getadvancedealer($eu->advance_dealer_id,$eu->sub_agent_id) }}</td>
                        <td>{{ $Function->getdealerphone($eu->advance_dealer_id,$eu->sub_agent_id) }}</td>
                        <td>{{ $Function->getdealeraddress($eu->advance_dealer_id,$eu->sub_agent_id) }}</td>
                        <td>{{ $Function->getfarm_name($eu->farm_id) }}</td>
                        <td>{{ $Function->getfarm_address($eu->farm_id) }}</td>
                        <td>{{ $Function->getfarm_phone($eu->farm_id) }}</td>
                        <td>{{ $Function->getvisitingdate($eu->id) }}</td>
                        <td>{{ $Function->getnoofbird($eu->id) }}</td>
                        <td>{{ $Function->getageofbird($eu->id) }}</td>
                        <td>{{ $Function->getageoffarm($eu->id) }}</td>
                        <td>{{ $Function->getfarmdensity($eu->id) }}</td>
                        <td>{{ $Function->gethatcheryname($eu->id) }}</td>
                        <td>{{ $Function->getfeedcompany($eu->id) }}</td>
                        <td>{{ $Function->gethealthcondition($eu->id) }}</td>
                        <td>{{ $Function->getfeed_intake_actual($eu->id) }}</td>
                        <td>{{ $Function->getfeed_intake_standard($eu->id) }}</td>
                        <td>{{ $Function->total_feed_intake_actual($eu->id) }}</td>
                        <td>{{ $Function->total_feed_intake_standard($eu->id) }}</td>
                        <td>{{ $Function->total_water_intake_actual($eu->id) }}</td>
                        <td>{{ $Function->total_water_intake_standard($eu->id) }}</td>
                        <td>{{ $Function->percent_production_actual($eu->id) }}</td>
                        <td>{{ $Function->percent_production_standard($eu->id) }}</td>
                        <td>{{ $Function->avg_body_wt_actual($eu->id) }}</td>
                        <td>{{ $Function->avg_body_wt_standard($eu->id) }}</td>
                        <td>{{ $Function->lighting_hour_actual($eu->id) }}</td>
                        <td>{{ $Function->lighting_hour_standard($eu->id) }}</td>
                        <td>{{ $Function->last_dewarming_date($eu->id) }}</td>
                        <td>{{ $Function->performance($eu->id) }}</td>
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
