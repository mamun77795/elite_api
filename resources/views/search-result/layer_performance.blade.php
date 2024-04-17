<?php
  use App\Http\Controllers\LayerPerformanceFunctionController;
  $Function 	= new LayerPerformanceFunctionController();
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
                        <h3 class="p-3 mb-2 bg-primary text-white" style="padding:2px;">LAYER PERFORMANCES</h3>
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
                                <th>ENDUSER NAME</th>
                                <th>DEALER NAME</th>
                                <th>DEALER NUMBER</th>
                                <th>DEALER ADDRESS</th>
                                <th>FARM NAME</th>
                                <th>FARM NUMBER</th>
                                <th>FARM ADDRESS</th>
                                <th>TOTAL BIRDS</th>
                                <th>AGE</th>
                                <th>BIRD WEIGHT ACHIEVED(gm)</th>
                                <th>BIRD WEIGHT TARGET(gm)</th>
                                <th>FEED INTAKE(gm) FEED/BIRD</th>
                                <th>FEED INTAKE(gm) TARGET</th>
                                <th>EGG PRODUCTION ACHIEVED</th>
                                <th>EGG PRODUCTION TARGET</th>
                                <th>EGG WEIGHT ACHIEVED(gm)</th>
                                <th>EGG WEIGHT STANDARD(gm)</th>
                                <th>FEED TYPE</th>
                                <th>PRODUCTION DATE</th>
                                <th>BATCH NO</th>
                                <th>FEED MILL</th>
                                <th>HATCHERY</th>
                                <th>BREED</th>
                                <th>FEED</th>
                                <th>COLOR</th>
                                <th>DISEASE</th>
                                <th>REMARKS</th>
                                <th>DATE TIME</th>
                            </tr>
                            </thead>
                            <tbody>
                              @foreach($endUsers as $eu)
                                <tr>
                                  <td>{{ $loop->index+1 }}</td>
                                  <td>{{ $Function->getClientNamebyID($eu->client_id) }}</td>
                                  <td>{{ $Function->getEndUserName($eu->enduser_id) }}</td>
                                  <td>{{ $Function->getagent_name($eu->dealer_id,$eu->sub_agent_id) }}</td>
                                  <td>{{ $Function->getagentphone($eu->dealer_id,$eu->sub_agent_id) }}</td>
                                  <td>{{ $Function->getagent_address($eu->dealer_id,$eu->sub_agent_id) }}</td>
                                  <td>{{ $Function->getfarm_name($eu->farm_id) }}</td>
                                  <td>{{ $Function->getfarm_phone($eu->farm_id) }}</td>
                                  <td>{{ $Function->getfarm_address($eu->farm_id) }}</td>
                                  <td>{{ $Function->getTotalBird($eu->id) }}</td>
                                  <td>{{ $Function->getAge($eu->id) }}</td>
                                  <td>{{ $Function->getBirdWeightAchieved($eu->id) }}</td>
                                  <td>{{ $Function->getBirdWeightTarget($eu->id) }}</td>
                                  <td>{{ $Function->getFeedIntakeBird($eu->id) }}</td>
                                  <td>{{ $Function->getFeedIntakeTarget($eu->id) }}</td>
                                  <td>{{ $Function->getEggProductionAchieved($eu->id) }}</td>
                                  <td>{{ $Function->getEggProductionTarget($eu->id) }}</td>
                                  <td>{{ $Function->getEggWeightAchieved($eu->id) }}</td>
                                  <td>{{ $Function->getEggWeightTarget($eu->id) }}</td>
                                  <td>{{ $Function->getfeed_type($eu->id) }}</td>
                                  <td>{{ $Function->getproduction_date($eu->id) }}</td>
                                  <td>{{ $Function->getbatch_no($eu->id) }}</td>
                                  <td>{{ $Function->getfeedmill($eu->feed_mill_id) }}</td>
                                  <td>{{ $Function->gethatchery($eu->id) }}</td>
                                  <td>{{ $Function->getbreed($eu->breed_id) }}</td>
                                  <td>{{ $Function->getfeed($eu->feed_id) }}</td>
                                  <td>{{ $Function->getcolor($eu->id) }}</td>
                                  <td>{{ $Function->getDisease($eu->id) }}</td>
                                  <td>{{ $Function->getRemarks($eu->id) }}</td>
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
                        <h3>LAYER PERFORMANCES</h3>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="scrollmenu">
                      <table class="table table-bordered table-hover" id="searchResult">
                          <thead>
                          <tr>
                              <th>#</th>
                              <th>CLIENT</th>
                              <th>ENDUSER NAME</th>
                              <th>DEALER NAME</th>
                              <th>DEALER NUMBER</th>
                              <th>DEALER ADDRESS</th>
                              <th>FARM NAME</th>
                              <th>FARM NUMBER</th>
                              <th>FARM ADDRESS</th>
                              <th>TOTAL BIRDS</th>
                              <th>AGE</th>
                              <th>BIRD WEIGHT ACHIEVED(gm)</th>
                              <th>BIRD WEIGHT TARGET(gm)</th>
                              <th>FEED INTAKE(gm) FEED/BIRD</th>
                              <th>FEED INTAKE(gm) TARGET</th>
                              <th>EGG PRODUCTION ACHIEVED</th>
                              <th>EGG PRODUCTION TARGET</th>
                              <th>EGG WEIGHT ACHIEVED(gm)</th>
                              <th>EGG WEIGHT STANDARD(gm)</th>
                              <th>FEED TYPE</th>
                              <th>PRODUCTION DATE</th>
                              <th>BATCH NO</th>
                              <th>FEED MILL</th>
                              <th>HATCHERY</th>
                              <th>BREED</th>
                              <th>FEED</th>
                              <th>COLOR</th>
                              <th>DISEASE</th>
                              <th>REMARKS</th>
                              <th>DATE TIME</th>
                          </tr>
                          </thead>
                          <tbody>
                            @foreach($endUsers as $eu)
                              <tr>
                                <td>{{ $loop->index+1 }}</td>
                                <td>{{ $Function->getClientNamebyID($eu->client_id) }}</td>
                                <td>{{ $Function->getEndUserName($eu->enduser_id) }}</td>
                                <td>{{ $Function->getagent_name($eu->dealer_id,$eu->sub_agent_id) }}</td>
                                <td>{{ $Function->getagentphone($eu->dealer_id,$eu->sub_agent_id) }}</td>
                                <td>{{ $Function->getagent_address($eu->dealer_id,$eu->sub_agent_id) }}</td>
                                <td>{{ $Function->getfarm_name($eu->farm_id) }}</td>
                                <td>{{ $Function->getfarm_phone($eu->farm_id) }}</td>
                                <td>{{ $Function->getfarm_address($eu->farm_id) }}</td>
                                <td>{{ $Function->getTotalBird($eu->id) }}</td>
                                <td>{{ $Function->getAge($eu->id) }}</td>
                                <td>{{ $Function->getBirdWeightAchieved($eu->id) }}</td>
                                <td>{{ $Function->getBirdWeightTarget($eu->id) }}</td>
                                <td>{{ $Function->getFeedIntakeBird($eu->id) }}</td>
                                <td>{{ $Function->getFeedIntakeTarget($eu->id) }}</td>
                                <td>{{ $Function->getEggProductionAchieved($eu->id) }}</td>
                                <td>{{ $Function->getEggProductionTarget($eu->id) }}</td>
                                <td>{{ $Function->getEggWeightAchieved($eu->id) }}</td>
                                <td>{{ $Function->getEggWeightTarget($eu->id) }}</td>
                                <td>{{ $Function->getfeed_type($eu->id) }}</td>
                                <td>{{ $Function->getproduction_date($eu->id) }}</td>
                                <td>{{ $Function->getbatch_no($eu->id) }}</td>
                                <td>{{ $Function->getfeedmill($eu->feed_mill_id) }}</td>
                                <td>{{ $Function->gethatchery($eu->id) }}</td>
                                <td>{{ $Function->getbreed($eu->breed_id) }}</td>
                                <td>{{ $Function->getfeed($eu->feed_id) }}</td>
                                <td>{{ $Function->getcolor($eu->id) }}</td>
                                <td>{{ $Function->getDisease($eu->id) }}</td>
                                <td>{{ $Function->getRemarks($eu->id) }}</td>
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
