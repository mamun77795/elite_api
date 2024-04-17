<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\EndUser;
use App\Supervisor;
use DB;
use App\User;
use App\Client;
use App\Invitation;
use Jcf\Geocode\Geocode;
use Carbon\Carbon;
use Spatie\Geocoder\Facades\Geocoder;
use App\DailyActivity;
use App\VisitedAgent;
use App\VisitedFarmer;
use App\VisitedSubAgent;
use Auth;


class FunctionController extends Controller
{
    // Enduser Search Depending on Supervisor
    public function getSupervisor(){

      $supervisors = DB::table('supervisors')
            ->join('users', 'supervisors.user_id', '=', 'users.id')
            ->select('users.name', 'supervisors.id')
            ->get();

        return $supervisors;
    }

    public function getEndUsers(Request $request)
    {
        $supID      = $request->id;
        $eUserObj   = new EndUser();
        if($supID == 'NULL'){
          $endUsers   = $eUserObj->WhereNull('supervisor_id')->get();
        }
        else{
          $endUsers   = $eUserObj->where('supervisor_id', $supID)->get();
        }

        return response()->json($endUsers);
    }

    public function generateEndUserForm($supervijors)
    {

    }

    public function getClientNamebyID($id)
    {
      $cliendObj = new Client();
      $client    = $cliendObj->find($id);

      $userObj   = new User();
      $user      = $userObj->find($client->user_id);

      return $user->name;
    }

    public function getInvitationCodebyID($id)
    {
      $invObj = new Invitation();
      $inv    = $invObj->find($id);

      return $inv->code;
    }

    // Daily Activity
    public function getAllEndUsers()
    {
      $eUserObj   = new EndUser();
      $endUsers   = $eUserObj->get();

      return $endUsers;
    }

    public function dailyactivitySearch(Request $request)
    {
      $endUser       = $request->enduser;
      $from          = $request->from;
      $to            = $request->to;

      $dates         = [];
      $datesag       = [];
      $datessubag    = [];
      $datesfm       = [];

      $activities    = [];
      $farmersArr    = [];
      $agentsArr     = [];
      $subagentsArr     = [];

      // if($from && $to){
      //       $fromDateTr = date('Y-m-d', strtotime($from));
      //       $toDateTr   = date('Y-m-d', strtotime($to));
      //       // $dates = [];

      //       $dates = $this->generateDateRange(Carbon::parse($fromDateTr), Carbon::parse($toDateTr));
      //   }

      $activityObj   = new DailyActivity();
      $farmerObj     = new VisitedFarmer();
      $agentObj      = new VisitedAgent();
      $subagentObj      = new VisitedSubAgent();

      $activity      = $activityObj->whereDate('created_at', '>=', $from.' 00:00:00')
                        ->whereDate('created_at', '<=', $to.' 11:59:59')
                        ->where('enduser_id', $endUser)
                        ->orderBY('created_at' , 'DESC')
                        ->get();

      $farmers       = $farmerObj->whereDate('created_at', '>=', $from.' 00:00:00')
                        ->whereDate('created_at', '<=', $to.' 11:59:59')
                        ->where('enduser_id', $endUser)
                        ->orderBY('created_at' , 'ASCE')
                        ->get();

      $agents        = $agentObj->whereDate('created_at', '>=', $from.' 00:00:00')
                        ->whereDate('created_at', '<=', $to.' 11:59:59')
                        ->where('enduser_id', $endUser)
                        ->orderBY('created_at' , 'ASCE')
                        ->get();
      $subagents        = $subagentObj->whereDate('created_at', '>=', $from.' 00:00:00')
                        ->whereDate('created_at', '<=', $to.' 11:59:59')
                        ->where('enduser_id', $endUser)
                        ->orderBY('created_at' , 'ASCE')
                        ->get();

      if($activity){
        foreach ($activity as $act) {
          if(!in_array(($act->created_at->format('Y-m-d')), $dates)){
            $dates[] = $act->created_at->format('Y-m-d');
            }
          }
          foreach ($dates as $date) {
            // $activities[]   = $date;
            foreach ($activity as $act) {
              if(($act->created_at->format('Y-m-d')) == $date){
                  $activities[$date][]  = [
                                  'id'                => $act->id,
                                  'region'            => $act->region,
                                  'name_of_area'      => $act->name_of_area,
                                  'working_duration'  => $act->working_duration,
                                  'purpose'           => $act->purpose,
                                  'created'           => $act->created_at->format('Y-m-d')
                                ];
              }
            }
          }
      }

      if($farmers){
        foreach ($farmers as $frm) {
          if(!in_array(($frm->created_at->format('Y-m-d')), $datesfm)){
            $datesfm[] = $frm->created_at->format('Y-m-d');
            }
          }
          foreach ($datesfm as $date) {
            // $activities[]   = $date;
            foreach ($farmers as $frm) {
              if(($frm->created_at->format('Y-m-d')) == $date){
                  $farmersArr[$date][]  = [
                                  'id'                => $frm->id,
                                  'address'           => $frm->address,
                                  'phone'             => $frm->phone,
                                  'name'              => $frm->name,
                                  'farm_capacity'     => $frm->farm_capacity,
                                  'farm_type'         => $frm->farm_type,
                                  'comments'          => $frm->comments
                                ];
              }
            }
          }
      }

      if($agents){
        foreach ($agents as $agn) {
          if(!in_array(($agn->created_at->format('Y-m-d')), $datesag)){
            $datesag[] = $agn->created_at->format('Y-m-d');
            }
          }
          foreach ($datesag as $date) {
            // $activities[]   = $date;
            foreach ($agents as $agn) {
              if(($agn->created_at->format('Y-m-d')) == $date){
                  $agentsArr[$date][]  = [
                                  'id'                  => $agn->id,
                                  'address'             => $agn->address,
                                  'name'                => $agn->name,
                                  'phone'               => $agn->phone,
                                  'comments'            => $agn->comments,
                                  'agent_type'          => $agn->agent_type,
                                ];
              }
            }
          }
      }

      if($subagents){
        foreach ($subagents as $subagn) {
          if(!in_array(($subagn->created_at->format('Y-m-d')), $datessubag)){
            $datessubag[] = $subagn->created_at->format('Y-m-d');
            }
          }
          foreach ($datessubag as $date) {
            // $activities[]   = $date;
            foreach ($subagents as $subagn) {
              if(($subagn->created_at->format('Y-m-d')) == $date){
                  $subagentsArr[$date][]  = [
                                  'id'                  => $subagn->id,
                                  'address'             => $subagn->address,
                                  'name_agent'          => $subagn->name_agent,
                                  'phone'               => $subagn->phone,
                                  'customer_feedback'   => $subagn->customer_feedback,
                                  'name_sub_agent'      => $subagn->name_sub_agent,
                                ];
              }
            }
          }
      }





      // return response()->json($agentsArr);
      return view('daily-activity', compact('dates', 'activities', 'farmersArr', 'datesfm', 'agentsArr', 'datesag', 'subagentsArr', 'datessubag'));
      // return view('daily-activity', compact('dates', 'activities'));

    }

    private function generateDateRange(Carbon $start_date, Carbon $end_date)
    {
        $dates = [];

        for($date = $start_date; $date->lte($end_date); $date->addDay()) {
            $dates[] = $date->format('Y-m-d');
        }

        return $dates;
    }

    public function location()
    {
      // $client = new \ipfinder\ipfinder\IPfinder('free');
      // $ip_address = '103.245.97.4';
      // $details = $client->getAddressInfo($ip_address);
      //
      // var_dump($details);

      // $response = Geocode::make()->latLng(22.6556598,89.7818354);
      // if ($response) {
      // 	echo $response->latitude();
      // 	echo $response->longitude();
      // 	echo $response->formattedAddress();
      // 	echo $response->locationType();
      // }

      $lat    = '23.1604325';
      $long   = '89.206484';
      $apiKey = 'AIzaSyDRz6coTtBzuFMAwYOD_XDVYOcpqUIf-nQ';

      $client = new \GuzzleHttp\Client();

      // $api    = 'https://maps.googleapis.com/maps/api/geocode/json?latlng='.$lat.','.$long.'&key='.$apiKey;
      $api = 'https://roads.googleapis.com/v1/nearestRoads?points=23.1604325,89.206484&key=AIzaSyC8VlW3VoylGkddCvc0pgw2RU9fH1zh1BU';
      $response = $client->request('GET', $api);

      // exit($api);

      //echo $response->getStatusCode(); # 200
      //echo $response->getHeaderLine('content-type'); # 'application/json; charset=utf8'
      //echo $response->getBody(); # '{"id": 1420053, "name": "guzzle", ...}'

      return response()->json($response->getBody());
      // $result =  $response->getBody();

    }

    public function roleBasedEnduser()
    {
      $eUserObj   = new EndUser();

      $endUsers   = array();

      if (\Entrust::hasRole('admin')) {
            $endUsers   = $eUserObj->get();
        } elseif (\Entrust::hasRole('client')) {
            $company_id = Client::whereUser_id(Auth::user()->id)->get()->first();
            $companyId = $company_id->id;

            $endUsers   = $eUserObj->where('client_id', $companyId)->get();

        }elseif (\Entrust::hasRole('daimond')) {
          $endUsers   = $eUserObj->where('client_id', '21')->get();

        }elseif (\Entrust::hasRole('amrit')) {
          $endUsers   = $eUserObj->where('client_id', '7')->get();

        }elseif (\Entrust::hasRole('nourish')) {
          $endUsers   = $eUserObj->where('client_id', '5')->get();

        }elseif (\Entrust::hasRole('alpha')) {
          $endUsers   = $eUserObj->where('client_id', '24')->get();

        } elseif (\Entrust::hasRole('supervisor')) {
            $superVisor   = Supervisor::whereUser_id(Auth::user()->id)->get()->first();
            $superVisorId = $superVisor->id;

            $endUsers   = $eUserObj->where('supervisor_id', $superVisorId)->get();
        }

      return $endUsers;
    }



}
