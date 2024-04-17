<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UserList;
use App\Supervisor;
use DB;
use App\User;
use App\AdvanceExpense;
use App\Client;
use App\Invitation;
use Jcf\Geocode\Geocode;
use Carbon\Carbon;
use App\Attendance;
use App\OutletHit;
use App\DealerHit;
use App\Outlet;
use App\Dealer;
use App\SalesOrder;
use App\MonthlySalesTarget;
use Auth;
use Spatie\Geocoder\Facades\Geocoder;
use App\DailyActivity;
use App\VisitedAgent;
use App\VisitedFarmer;


class UserListFunctionController extends Controller
{
    // Enduser Search Depending on Supervisor
    public function getSupervisor(){

      $supervisors = DB::table('supervisors')
            ->join('users', 'supervisors.user_id', '=', 'users.id')
            ->select('users.name', 'supervisors.id')
            ->get();

        return $supervisors;
    }

    public function gettotal_amount($id,$date)
    {

      $expense_list = AdvanceExpense::where('enduser_id',$id )
                                        ->Where('created_at', 'LIKE', '%' . $date . '%')
                                        ->get()->first();




                                        $conveyance      = $expense_list['conveyance'] == NULL ? 0 : $expense_list['conveyance'];
                                        $daily_allowance = $expense_list['daily_allowance'] == NULL ? 0 : $expense_list['daily_allowance'];
                                        $hotel_rent = $expense_list['hotel_rent'] == NULL ? 0 : $expense_list['hotel_rent'];
                                        $food = $expense_list['food'] == NULL ? 0 : $expense_list['food'];
                                        $photostat = $expense_list['photostat'] == NULL ? 0 : $expense_list['photostat'];
                                        $fax = $expense_list['fax'] == NULL ? 0 : $expense_list['fax'];
                                        $others = $expense_list['others'] == NULL ? 0 : $expense_list['others'];

      $total_amount = $conveyance +
                      $daily_allowance +
                      $hotel_rent +
                      $food+
                      $photostat +
                      $fax +
                      $others;

      return $total_amount;
    }


    public function getoutlethit_count($id,$date)
    {
      $totalactive_status = OutletHit::where('enduser_id',$id )
                                        ->Where('created_at', 'LIKE', '%' . $date . '%')->count();
      // $expense_list = OutletHit::where('enduser_id',$id )
      //                                   ->Where('created_at', 'LIKE', '%' . $date . '%')
      //                                   ->get()->toArray();


      return $totalactive_status;
    }

    public function getdealerhit_count($id,$date)
    {
      $totalactive_status = DealerHit::where('enduser_id',$id )
                                        ->Where('created_at', 'LIKE', '%' . $date . '%')->count();


      return $totalactive_status;
    }

    public function getsales($id,$date)
    {
      $totalactive_status = SalesOrder::where('enduser_id',$id )
                                        ->Where('created_at', 'LIKE', '%' . $date . '%')->get();

      $totalpayment = collect($totalactive_status)->sum('gross_sale');


      return $totalpayment;
    }

    public function gettarget($id,$date)
    {
      $totalactive_status = MonthlySalesTarget::where('enduser_id',$id )
                                        ->Where('created_at', 'LIKE', '%' . $date . '%')->select('sales_target')->get()->first();


      return $totalactive_status['sales_target'];
    }

    public function getoutlethit_location($id,$date)
    {
      $totalactives = OutletHit::where('enduser_id',$id )
                                        ->Where('created_at', 'LIKE', '%' . $date . '%')
                                        ->select('outlet_id')->get();
      // $expense_list = OutletHit::where('enduser_id',$id )
      //                                   ->Where('created_at', 'LIKE', '%' . $date . '%')
      //                                   ->get()->toArray();
      // $allData = [];
      // foreach ($totalactives as $totalactive) {
      //   $outlets = Outlet::where('id',$totalactive['outlet_id'] )->
      //                     orderBy('created_at','desc')->
      //                     select('person_address')->
      //                     get();
      //                     $allData[] = $outlets;
      //
      // }
      // return response()->json(['data' => $allData], 200);
      $totalactive_status = OutletHit::where('outlet_hits.enduser_id',$id )
                             ->Where('outlet_hits.created_at', 'LIKE', '%' . $date . '%')
                             ->leftJoin('outlets', 'outlets.id', '=','outlet_hits.outlet_id' )

    ->pluck('outlets.person_address');
    //$a = array_values($totalactive_status[0]['outlets.person_address']);
      //$a = explode(",", $totalactive_status);

      $singleAddress=null;
      $max = sizeof($totalactive_status);
      for($i=0; $i<$max; $i++ ){
         $singleAddress .= $totalactive_status[$i]."> ";
      }

      return substr($singleAddress,0,strlen($singleAddress)-2);

    }

    public function getdealerhit_location($id,$date)
    {
      $totalactives = DealerHit::where('enduser_id',$id )
                                        ->Where('created_at', 'LIKE', '%' . $date . '%')
                                        ->select('dealer_id')->get();
      // $expense_list = OutletHit::where('enduser_id',$id )
      //                                   ->Where('created_at', 'LIKE', '%' . $date . '%')
      //                                   ->get()->toArray();
      // $allData = [];
      // foreach ($totalactives as $totalactive) {
      //   $outlets = Outlet::where('id',$totalactive['outlet_id'] )->
      //                     orderBy('created_at','desc')->
      //                     select('person_address')->
      //                     get();
      //                     $allData[] = $outlets;
      //
      // }
      // return response()->json(['data' => $allData], 200);
      $totalactive_status = DealerHit::where('dealer_hits.enduser_id',$id )
                             ->Where('dealer_hits.created_at', 'LIKE', '%' . $date . '%')
                             ->leftJoin('dealers', 'dealers.id', '=','dealer_hits.dealer_id' )

    ->pluck('dealers.dealer_address');
    //$a = array_values($totalactive_status[0]['outlets.person_address']);
      //$a = explode(",", $totalactive_status);

      $singleAddress=null;
      $max = sizeof($totalactive_status);
      for($i=0; $i<$max; $i++ ){
         $singleAddress .= $totalactive_status[$i]."> ";
      }

      return substr($singleAddress,0,strlen($singleAddress)-2);

    }

    public function getattendance($id,$date)
    {
      $totalactive_status = Attendance::where('enduser_id',$id )
                                        ->Where('created_at', 'LIKE', '%' . $date . '%')->get()->first();
      if($totalactive_status){
        return 'PRESENT';
      }else{
        return 'ABSENT';
      }

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
    public function getClientNamebyID($id)
    {
      $cliendObj = new Client();
      $client    = $cliendObj->find($id);

      $userObj   = new User();
      $user      = $userObj->find($client->user_id);

      return $user->name;
    }

    public function getAllAttendance()
    {
      $eUserObj   = new Attendance();
      $endUsers   = $eUserObj->get();

      return $endUsers;
    }
    public function getAllEndUsers()
    {

      if (\Entrust::hasRole('admin')) {
        $eUserObj   = new UserList();
        $endUsers   = $eUserObj->get();

        return $endUsers;
      } elseif (\Entrust::hasRole('client')) {
        $company_id = Client::whereUser_id(Auth::user()->id)->get()->first();
        $companyId = $company_id->id;

        $eUserObj   = new UserList();
        $endUsers   = $eUserObj->where('client_id', $companyId)->get();

        return $endUsers;
      }elseif (\Entrust::hasRole('bdthai')) {


        $eUserObj   = new UserList();
        $endUsers   = $eUserObj->where('client_id', '18')->get();

        return $endUsers;
      }elseif (\Entrust::hasRole('getco')) {


        $eUserObj   = new UserList();
        $endUsers   = $eUserObj->where('client_id', '26')->get();

        return $endUsers;
      }elseif (\Entrust::hasRole('amrit')) {


        $eUserObj   = new UserList();
        $endUsers   = $eUserObj->where('client_id', '18')->get();

        return $endUsers;
      }elseif (\Entrust::hasRole('daimond')) {


        $eUserObj   = new UserList();
        $endUsers   = $eUserObj->where('client_id', '21')->get();

        return $endUsers;
      }elseif (\Entrust::hasRole('nourish')) {


        $eUserObj   = new UserList();
        $endUsers   = $eUserObj->where('client_id', '5')->get();

        return $endUsers;
      }elseif (\Entrust::hasRole('alpha')) {


        $eUserObj   = new UserList();
        $endUsers   = $eUserObj->where('client_id', '24')->get();

        return $endUsers;
      }

    }
    public function getEndUserName($id)
    {
      $eUserObj   = new UserList();
      $endUsers   = $eUserObj->find($id);

      return $endUsers->name;
    }
    public function getEndUserStatus($id)
    {
      $eUserObj   = new Attendance();
      $endUsers   = $eUserObj->find($id);

      if($endUsers->status == 1){
        $r = 'ENTRY';
      }else{
        $r = 'EXIT';
      }

      return $r;
    }

    public function getEndUserDateTime($id)
    {
      $eUserObj   = new Attendance();
      $endUsers   = $eUserObj->find($id);

      $datetime = $endUsers->created_at;

      return $datetime;
    }
}
