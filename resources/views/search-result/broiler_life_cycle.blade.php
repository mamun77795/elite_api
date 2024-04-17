<?php
  use App\Http\Controllers\BroilerLifeCycleFunctionController;
  $Function 	= new BroilerLifeCycleFunctionController();
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
                        <h3 class="p-3 mb-2 bg-primary text-white" style="padding:2px;">BROILER LIFE CYCLE</h3>
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
                              <th>AGENT NAME</th>
                              <th>AGENT NUMBER</th>
                              <th>AGENT ADDRESS</th>
                              <th>FARM NAME</th>
                              <th>FARM NUMBER</th>
                              <th>FARM ADDRESS</th>
                              <th>VISITING DATE</th>
                              <th>VISITING WEEK</th>
                              <th>TOTAL BIRDS</th>
                              <th>AGE(DAY)</th>
                              <th>MORTALITY(PES)</th>
                              <th>MORTALITY %</th>
                              <th>STANDARD WEIGHT</th>
                              <th>ACHIEVED WEIGHT</th>
                              <th>FEED CONSUMPTION TOTAL(kg)</th>
                              <th>FEED CONS. PER BIRD(gm)</th>
                              <th>FEED CONSUMPTION PER STANDARD</th>
                              <th>F.C.R WITH M</th>
                              <th>F.C.R WITHOUT M</th>
                              <th>HATCHERY</th>
                              <th>FEED</th>
                              <th>BREED</th>
                              <th>FEED TYPE</th>
                              <th>PRODUCTION DATE</th>
                              <th>BATCH NO</th>
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
                                <td>{{ $Function->getvisiting_date($eu->id) }}</td>
                                <td>{{ $Function->getvisiting_week($eu->id) }}</td>
                                <td>{{ $Function->gettotal_birds($eu->id) }}</td>
                                <td>{{ $Function->getage_day($eu->id) }}</td>
                                <td>{{ $Function->getmortality_pes($eu->id) }}</td>
                                <td>{{ $Function->getmortality_percent($eu->id,$eu->farm_id) }}</td>
                                <td>{{ $Function->getstandard_weight($eu->id) }}</td>
                                <td>{{ $Function->getachieved_weight($eu->id) }}</td>
                                <td>{{ $Function->getfeed_consumption_total($eu->id) }}</td>
                                <td>{{ $Function->getfeed_cons_per_bird($eu->id,$eu->farm_id) }}</td>
                                <td>{{ $Function->getfeed_consumption_per_standard($eu->id) }}</td>
                                <td>{{ $Function->getfcr_with_m($eu->id,$eu->farm_id) }}</td>
                                <td>{{ $Function->getfcr_without_m($eu->id,$eu->farm_id) }}</td>
                                <td>{{ $Function->gethatchery($eu->id) }}</td>
                                <td>{{ $Function->getfeed($eu->feed_id) }}</td>
                                <td>{{ $Function->getbreed($eu->breed_id) }}</td>
                                <td>{{ $Function->getfeed_type($eu->id) }}</td>
                                <td>{{ $Function->getproduction_date($eu->id) }}</td>
                                <td>{{ $Function->getbatch_no($eu->id) }}</td>
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
                        <h3>BROILER LIFE CYCLE</h3>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="scrollmenu">
                      <table class="table table-bordered table-hover" id="searchResult">
                        <thead>
                        <tr>
                            <th>USER NAME</th>
                            <th>AGENT NAME</th>
                            <th>AGENT NUMBER</th>
                            <th>AGENT ADDRESS</th>
                            <th>FARM NAME</th>
                            <th>FARM NUMBER</th>
                            <th>FARM ADDRESS</th>
                            <th>VISITING DATE</th>
                            <th>VISITING WEEK</th>
                            <th>TOTAL BIRDS</th>
                            <th>AGE(DAY)</th>
                            <th>MORTALITY(PES)</th>
                            <th>MORTALITY %</th>
                            <th>STANDARD WEIGHT</th>
                            <th>ACHIEVED WEIGHT</th>
                            <th>FEED CONSUMPTION TOTAL(kg)</th>
                            <th>FEED CONS. PER BIRD(gm)</th>
                            <th>FEED CONSUMPTION PER STANDARD</th>
                            <th>F.C.R WITH M</th>
                            <th>F.C.R WITHOUT M</th>
                            <th>HATCHERY</th>
                            <th>FEED</th>
                            <th>BREED</th>
                            <th>FEED TYPE</th>
                            <th>PRODUCTION DATE</th>
                            <th>BATCH NO</th>
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
                              <td>{{ $Function->getvisiting_date($eu->id) }}</td>
                              <td>{{ $Function->getvisiting_week($eu->id) }}</td>
                              <td>{{ $Function->gettotal_birds($eu->id) }}</td>
                              <td>{{ $Function->getage_day($eu->id) }}</td>
                              <td>{{ $Function->getmortality_pes($eu->id) }}</td>
                              <td>{{ $Function->getmortality_percent($eu->id,$eu->farm_id) }}</td>
                              <td>{{ $Function->getstandard_weight($eu->id) }}</td>
                              <td>{{ $Function->getachieved_weight($eu->id) }}</td>
                              <td>{{ $Function->getfeed_consumption_total($eu->id) }}</td>
                              <td>{{ $Function->getfeed_cons_per_bird($eu->id,$eu->farm_id) }}</td>
                              <td>{{ $Function->getfeed_consumption_per_standard($eu->id) }}</td>
                              <td>{{ $Function->getfcr_with_m($eu->id,$eu->farm_id) }}</td>
                              <td>{{ $Function->getfcr_without_m($eu->id,$eu->farm_id) }}</td>
                              <td>{{ $Function->gethatchery($eu->id) }}</td>
                              <td>{{ $Function->getfeed($eu->feed_id) }}</td>
                              <td>{{ $Function->getbreed($eu->breed_id) }}</td>
                              <td>{{ $Function->getfeed_type($eu->id) }}</td>
                              <td>{{ $Function->getproduction_date($eu->id) }}</td>
                              <td>{{ $Function->getbatch_no($eu->id) }}</td>
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
