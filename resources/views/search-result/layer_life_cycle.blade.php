<?php
  use App\Http\Controllers\LayerLifeCycleFunctionController;
  $Function 	= new LayerLifeCycleFunctionController();
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
                        <h3 class="p-3 mb-2 bg-primary text-white" style="padding:2px;">LAYER LIFE CYCLE</h3>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="scrollmenu">
                      <button style="margin-right:5px" class="btn btn-info" id="exportButton" onclick="createFormattingWorkbook()">Export to csv</button>
                      <button class="btn btn-danger" id="exportButton" onclick="createFormattingWorkbook()">Export to pdf</button>
                        <table class="table table-bordered table-hover" id="exportTable">
                          <thead>
                          <tr>
                              <th>USER NAME</th>
                              <th>DEALER NAME</th>
                              <th>DEALER NUMBER</th>
                              <th>DEALER ADDRESS</th>
                              <th>FARM NAME</th>
                              <th>FARM NUMBER</th>
                              <th>FARM ADDRESS</th>
                              <th>HATCHERY DATE</th>
                              <th>VISITING DATE</th>
                              <th>AGE(WEEK)</th>
                              <th>TOTAL BIRDS</th>
                              <th>DEAD BIRD</th>
                              <th>PRESENT BIRDS</th>
                              <th>AVARAGE WEIGHT</th>
                              <th>TARGET WEIGHT</th>
                              <th>UNIFORMITY</th>
                              <th>FEED PER BIRD</th>
                              <th>TARGET FEED</th>
                              <th>TOTAL EGG</th>
                              <th>EGG PRODUCTION %</th>
                              <th>TARGET EGG PRODUCTION %</th>
                              <th>EGG WEIGHT(ACTUAL)</th>
                              <th>EGG WEIGHT(STANDARD)</th>
                              <th>PRODUCTION DATE</th>
                              <th>FEED TYPE</th>
                              <th>BATCH NO</th>
                              <th>HATCHERY</th>
                              <th>BREED</th>
                              <th>FEED</th>
                              <th>FEED MILL</th>
                              <th>MEDICINE</th>
                              <th>REMARKS</th>
                              <th>DATE TIME</th>
                          </tr>
                          </thead>
                          <tbody>
                            @foreach($endUsers as $eu)
                              <tr>
                                <td>{{ $Function->getEndUserName($eu->enduser_id) }}</td>
                                <td>{{ $Function->getagent_name($eu->dealer_id,$eu->sub_agent_id) }}</td>
                                <td>{{ $Function->getagentphone($eu->dealer_id,$eu->sub_agent_id) }}</td>
                                <td>{{ $Function->getagent_address($eu->dealer_id,$eu->sub_agent_id) }}</td>
                                <td>{{ $Function->getfarm_name($eu->farm_id) }}</td>
                                <td>{{ $Function->getfarm_phone($eu->farm_id) }}</td>
                                <td>{{ $Function->getfarm_address($eu->farm_id) }}</td>
                                <td>{{ $Function->gethatching_date($eu->id) }}</td>
                                <td>{{ $Function->getvisiting_date($eu->id) }}</td>
                                <td>{{ $Function->getbird_age_week($eu->id) }}</td>
                                <td>{{ $Function->gettotal_birds($eu->id) }}</td>
                                <td>{{ $Function->getbird_dead_bird($eu->id) }}</td>
                                <td>{{ $Function->getpresent_bird($eu->id,$eu->farm_id) }}</td>
                                <td>{{ $Function->getaverage_weight($eu->id) }}</td>
                                <td>{{ $Function->gettarget_weight($eu->id) }}</td>
                                <td>{{ $Function->getuniformity($eu->id) }}</td>
                                <td>{{ $Function->getfeedperbird($eu->id) }}</td>
                                <td>{{ $Function->gettarget_feed($eu->id) }}</td>
                                <td>{{ $Function->gettotal_egg($eu->id) }}</td>
                                <td>{{ $Function->getegg_production($eu->id,$eu->farm_id) }}</td>
                                <td>{{ $Function->gettarget_egg_production($eu->id) }}</td>
                                <td>{{ $Function->getegg_weight_actual($eu->id) }}</td>
                                <td>{{ $Function->getegg_weight_standard($eu->id) }}</td>
                                <td>{{ $Function->getproduction_date($eu->id) }}</td>
                                <td>{{ $Function->getfeed_type($eu->id) }}</td>
                                <td>{{ $Function->getbatch_no($eu->id) }}</td>
                                <td>{{ $Function->gethatchery($eu->id) }}</td>
                                <td>{{ $Function->getbreed($eu->breed_id) }}</td>
                                <td>{{ $Function->getfeed($eu->feed_id) }}</td>
                                <td>{{ $Function->getfeedmill($eu->feed_mill_id) }}</td>
                                <td>{{ $Function->getmedicine($eu->id) }}</td>
                                <td>{{ $Function->getremarks($eu->id) }}</td>
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

    @role(['client','nourish','alpha'])
    <div class="row">
        <div class="col-md-12">


                <div class="portlet-title">
                    <div class="caption">
                        <i class="livicon" data-name="lab" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                        <h3>LAYER LIFE CYCLE</h3>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="scrollmenu">
                      <table class="table table-bordered table-hover" id="searchResult">
                        <thead>
                        <tr>
                            <th>USER NAME</th>
                            <th>DEALER NAME</th>
                            <th>DEALER NUMBER</th>
                            <th>DEALER ADDRESS</th>
                            <th>FARM NAME</th>
                            <th>FARM NUMBER</th>
                            <th>FARM ADDRESS</th>
                            <th>HATCHERY DATE</th>
                            <th>VISITING DATE</th>
                            <th>AGE(WEEK)</th>
                            <th>TOTAL BIRDS</th>
                            <th>DEAD BIRD</th>
                            <th>PRESENT BIRDS</th>
                            <th>AVARAGE WEIGHT</th>
                            <th>TARGET WEIGHT</th>
                            <th>UNIFORMITY</th>
                            <th>FEED PER BIRD</th>
                            <th>TARGET FEED</th>
                            <th>TOTAL EGG</th>
                            <th>EGG PRODUCTION %</th>
                            <th>TARGET EGG PRODUCTION %</th>
                            <th>EGG WEIGHT(ACTUAL)</th>
                            <th>EGG WEIGHT(STANDARD)</th>
                            <th>PRODUCTION DATE</th>
                            <th>FEED TYPE</th>
                            <th>BATCH NO</th>
                            <th>HATCHERY</th>
                            <th>BREED</th>
                            <th>FEED</th>
                            <th>FEED MILL</th>
                            <th>MEDICINE</th>
                            <th>REMARKS</th>
                            <th>DATE TIME</th>
                        </tr>
                        </thead>
                        <tbody>
                          @foreach($endUsers as $eu)
                            <tr>
                              <td>{{ $Function->getEndUserName($eu->enduser_id) }}</td>
                              <td>{{ $Function->getagent_name($eu->dealer_id,$eu->sub_agent_id) }}</td>
                              <td>{{ $Function->getagentphone($eu->dealer_id,$eu->sub_agent_id) }}</td>
                              <td>{{ $Function->getagent_address($eu->dealer_id,$eu->sub_agent_id) }}</td>
                              <td>{{ $Function->getfarm_name($eu->farm_id) }}</td>
                              <td>{{ $Function->getfarm_phone($eu->farm_id) }}</td>
                              <td>{{ $Function->getfarm_address($eu->farm_id) }}</td>
                              <td>{{ $Function->gethatching_date($eu->id) }}</td>
                              <td>{{ $Function->getvisiting_date($eu->id) }}</td>
                              <td>{{ $Function->getbird_age_week($eu->id) }}</td>
                              <td>{{ $Function->gettotal_birds($eu->id) }}</td>
                              <td>{{ $Function->getbird_dead_bird($eu->id) }}</td>
                              <td>{{ $Function->getpresent_bird($eu->id,$eu->farm_id) }}</td>
                              <td>{{ $Function->getaverage_weight($eu->id) }}</td>
                              <td>{{ $Function->gettarget_weight($eu->id) }}</td>
                              <td>{{ $Function->getuniformity($eu->id) }}</td>
                              <td>{{ $Function->getfeedperbird($eu->id) }}</td>
                              <td>{{ $Function->gettarget_feed($eu->id) }}</td>
                              <td>{{ $Function->gettotal_egg($eu->id) }}</td>
                              <td>{{ $Function->getegg_production($eu->id,$eu->farm_id) }}</td>
                              <td>{{ $Function->gettarget_egg_production($eu->id) }}</td>
                              <td>{{ $Function->getegg_weight_actual($eu->id) }}</td>
                              <td>{{ $Function->getegg_weight_standard($eu->id) }}</td>
                              <td>{{ $Function->getproduction_date($eu->id) }}</td>
                              <td>{{ $Function->getfeed_type($eu->id) }}</td>
                              <td>{{ $Function->getbatch_no($eu->id) }}</td>
                              <td>{{ $Function->gethatchery($eu->id) }}</td>
                              <td>{{ $Function->getbreed($eu->breed_id) }}</td>
                              <td>{{ $Function->getfeed($eu->feed_id) }}</td>
                              <td>{{ $Function->getfeedmill($eu->feed_mill_id) }}</td>
                              <td>{{ $Function->getmedicine($eu->id) }}</td>
                              <td>{{ $Function->getremarks($eu->id) }}</td>
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
