<?php

namespace App\Http\Controllers;

use App\Client;
use App\User;
use App\ClientSetup;
use App\Payment;
use App\Invitation;
use App\EndUser;
use App\FcrBeforeSale;
use App\FcrAfterSale;
use App\LayerPerformance;
use App\LayerLifeCycle;
use App\BroilerLifeCycle;
use App\DailyActivity;
use App\Dealer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class ClientControllerApi extends Controller
{
    public function get_client_report(Request $request){

      $client_id = $request->client_id;
      $user_id = Client::where('id',$client_id)->select('user_id')->get()->last();

      if (!$user_id) {
          return response()->json(['data'=>['error'=>'Invalid Client.']],200);
      }

      // Client Name and Email
      $user = User::where('id',$user_id['user_id'])->select('name','email')->get()->last();

      $client_name = $user['name'];
      $client_email = $user['email'];

      // Total User Limits
      $payment = Payment::where('user_id',$user_id['user_id'])->select('user_limit')->get()->last();
      $user_limit = $payment['user_limit'];

      // Total Invitation Sent
      $invitation_list = Invitation::where('client_id',$client_id)->
                                      where('status','2')->
                                      select('status')->
                                      get()->
                                      toArray();
      $invitations_used = count($invitation_list);

      $invitation_list = Invitation::where('client_id',$client_id)->
                                      where('status','1')->
                                      select('status')->
                                      get()->
                                      toArray();
      $invitations_unused = count($invitation_list);

      $client = [
            'filename' => $client_name.'.xls',
            //'User ID' => $user_id['user_id'],
            //'Client ID' => $client_id,
            'Name' => $client_name,
            'Email' => $client_email,
            'Total User Limit' => $user_limit.' Users',
            'Total Invitations Sent' => $invitations_used + $invitations_unused.' Users',
            'Registered Users' => $invitations_used.' Users',
            'Unused Users' => $invitations_unused.' Users',
      ];

      $enduser_list = EndUser::where('client_id',$client_id )->
                               where('status',1 )->
                               select('id','phone_number','name')->
                               get()->
                               toArray();
      $enduser_data = [];
      $index = 1;
      foreach ($enduser_list as $enduser) {

        $fcr_before_sale_list = FcrBeforeSale::where('client_id',$client_id )->
                                 where('enduser_id',$enduser['id'])->count();

        $fcr_after_sale_list = FcrAfterSale::where('client_id',$client_id )->
                                where('enduser_id',$enduser['id'])->count();

        $layer_performance_list = LayerPerformance::where('client_id',$client_id )->
                                where('enduser_id',$enduser['id'])->count();

        $layer_life_cycle_list = LayerLifeCycle::where('client_id',$client_id )->
                                where('enduser_id',$enduser['id'])->count();

        $broiler_life_cycle_list = BroilerLifeCycle::where('client_id',$client_id )->
                                where('enduser_id',$enduser['id'])->count();

        $daily_activity_list = DailyActivity::where('client_id',$client_id )->
                                 where('enduser_id',$enduser['id'])->count();

        $dealer_point_list = Dealer::where('client_id',$client_id )->
                                 where('enduser_id',$enduser['id'])->count();

        $data = [
              '#' => $index,
              'Name' => $enduser['name'],
              'Phone' => $enduser['phone_number'],
              'FCR Before Sale' => $fcr_before_sale_list,
              'FCR After Sale' => $fcr_after_sale_list,
              'Layer Performance' => $layer_performance_list,
              'Layer Life Cycle' => $layer_life_cycle_list,
              'Broiler Life Cycle' => $broiler_life_cycle_list,
              'Daily Activity' => $daily_activity_list,
              'Dealer Points' => $dealer_point_list
        ];
        $index += 1;
        $enduser_data[] = $data;
      }

      return response()->json(['data' => ['client' => $client,'users' => $enduser_data]],200);


      // Total User Registered


    }

    public function get_client_all_info(Request $request){

      $name = $request->name;
      $user = User::where('name',$name)->select('id')->get()->last();

      if (!$user) {
          return response()->json(['data'=>['error'=>'Invalid Client Name.']],200);
      }

      $user_id = $user['id'];

      $client = Client::where('user_id',$user_id)->select('id','code','phone_number','address','type','created_at')->get()->last();
      if (!$client) {
          return response()->json(['data'=>['error'=>'Client Not Exist.']],200);
      }

      $client_payment = Payment::where('user_id',$user_id)->select('user_limit',
                                                                     'paymenttype',
                                                                     'expiration_date')->get()->last();

      $client_setup = ClientSetup::where('user_id',$user_id)->select('dealer_point_limit',
                                                                     'refresh_duration',
                                                                     'sound',
                                                                     'background_location_update',
                                                                     'distance_feet_update',
                                                                     'advance_expense',
                                                                     'daily_activity',
                                                                     'fcr_report',
                                                                     'layer_performance_report',
                                                                     'layer_life_cycle_report',
                                                                     'broiler_life_cycle_report')->get()->last();

      $invitations_users = Invitation::where('client_id',$client['id'])->select('id')->get()->toArray();
      $invitations_used = Invitation::where('client_id',$client['id'])->where('status','1')->select('id')->get()->toArray();
      $invitations_unused = Invitation::where('client_id',$client['id'])->where('status','2')->select('id')->get()->toArray();

      $enduser_active = EndUser::where('client_id',$client['id'])->where('status','1')->select('id')->get()->toArray();
      $enduser_inactive = EndUser::where('client_id',$client['id'])->where('status','2')->select('id')->get()->toArray();

      $client_id = $client['id'];
      $client_code = $client['code'];
      $client_phone_number = $client['phone_number'];
      $client_address = $client['address'];
      $client_type = $client['type'];
      $client_created = $client['created_at'];
      $client_expire = $client_payment['expiration_date'];
      $client_payment_type = $client_payment['paymenttype'];
      $client_user_limit = $client_payment['user_limit'];
      $client_inivition_send = count($invitations_users);
      $client_inivition_used = count($invitations_used);
      $client_inivition_unused = count($invitations_unused);

      $active_enduser = count($enduser_active);
      $inactive_enduser = count($enduser_inactive);

      $dealer_point_limit = $client_setup['dealer_point_limit'];
      $refresh_duration = $client_setup['refresh_duration'];
      $background_location_update = $client_setup['background_location_update'];
      $distance_feet_update = $client_setup['distance_feet_update'];
      $sound = $client_setup['sound'];
      $advance_expense = $client_setup['advance_expense'];
      $daily_activity = $client_setup['daily_activity'];
      $fcr_report = $client_setup['fcr_report'];
      $layer_performance_report = $client_setup['layer_performance_report'];
      $layer_life_cycle_report = $client_setup['layer_life_cycle_report'];
      $broiler_life_cycle_report = $client_setup['broiler_life_cycle_report'];

      $data = [
            'Client ID' => $client_id,
            'Client Name' => $name,
            'Admin Code' => $client_code,
            'Phone Number' => $client_phone_number,
            'Address' => $client_address,
            'TYPE' => $client_type,
            'Client Created' => $client_created->toDateTimeString(),
            'Client Expire' => $client_expire,
            'Client Payment Type' => $client_payment_type,
            'Total Invitation Send' => $client_inivition_send .' Users',
            'Total Invitation Used' => $client_inivition_used .' Users',
            'Total Invitation Unused' => $client_inivition_unused .' Users',
            'Client User Limit' => 'Maximum '.$client_user_limit.' Users',
            'Active Users' => $active_enduser.' Users',
            'Deactivated Users' => $inactive_enduser.' Users',
            'Dealer Point Limit' => $dealer_point_limit.' Dealers',
            'User List will Refresh' => 'Every '.$refresh_duration.' Seconds',
            'User Background Location Update Time' => 'Every '.$background_location_update.' Seconds',
            'User Background Update Distance' => 'When '.$distance_feet_update.' Feet Changes',
            'Click User Icon to Play Sound' => $sound == 1 ? 'YES' : 'NO',
            'Advance Expense Report Option Avaialble' => $advance_expense == 1 ? 'YES' : 'NO',
            'Daily Activity Option Avaialble' => $daily_activity == 1 ? 'YES' : 'NO',
            'FCR Report Option Avaialble' => $fcr_report == 1 ? 'YES' : 'NO',
            'Layer Performance Report Option Avaialble' => $layer_performance_report == 1 ? 'YES' : 'NO',
            'Layer Life Cycle Option Avaialble' => $layer_life_cycle_report == 1 ? 'YES' : 'NO',
            'Broiler Life Cycle Report Option Avaialble' => $broiler_life_cycle_report == 1 ? 'YES' : 'NO',
      ];

      return response()->json(['data'=>$data],200);

    }

    public function get_client_list(Request $request){

        $client_list = Client::select('id','user_id','code')->get()->toArray();
        $allData = [];

        foreach ($client_list as $client) {
            $user = User::where('id',$client['user_id'])->select('name')->get()->last();
            $data = [
                  'id' => $client['id'],
                  'name' => $user['name'],
                  'code' => $client['code']
            ];
            $allData[] = $data;
        }

        return response()->json(['data'=>$allData],200);

    }

    public function get_client_setup_info(Request $request){

      $client_id = $request->client_id;

      $user = Client::where('id',$client_id)->select('user_id')->get()->last();

      if (!$user) {
          return response()->json(['data'=>['error'=>'Invalid User.']],200);
      }

      $user_id = $user['user_id'];
      $client_setup = ClientSetup::where('user_id',$user_id)->
                                    select('advance_expense',
                                           'notification_update',
                                           'simple_report',
                                           'fcr_report',
                                           'layer_performance_report',
                                           'layer_life_cycle_report',
                                           'broiler_life_cycle_report',
                                           'advance_dealer',
                                           'advance_tour_plan',
                                           'actual_tour_plan',
                                           'sales_order',
                                           'fcr_only',
                                           'daily_report',
                                           'monthly_sales_target',
                                           'daily_activity',
                                           'outlet',
                                           'tp_type',
                                           'atp_type')->
                                    get()->
                                    last();
      return response()->json(['data'=>$client_setup],200);
    }

    public function index(Request $request){
        $data = Client::whereUsers_id(3)->get();
        return response()->json(['data'=>$data],200);
    }

    public function store(Request $request){
        $id = $request->get('id');
        $article = Client::findOrFail($id);
        $article->update($request->all());
        return response()->json(['data'=>$article],200);
    }
}
