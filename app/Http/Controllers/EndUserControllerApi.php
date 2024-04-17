<?php

namespace App\Http\Controllers;

use App\Client;
use App\User;
use Auth;
use App\EndUser;
use App\ClientSetup;
use App\Payment;
use Illuminate\Http\Request;
use Rashidul\RainDrops\Controllers\BaseController;
use DB;
use \Crypt;
use App\Constants;
use Carbon;
Use App\Photo;
Use App\Attendance;
Use App\Invitation;
use App\Device;
use App\Status;
use App\Message;
use App\TaskStatus;
use App\Task;
use App\PhoneChange;
use App\VersionNotification;
use App\FcrAfterSale;
use App\FcrBeforeSale;
use App\Conveyance;
use App\LayerPerformance;
use App\LayerLifeCycle;
use App\BroilerLifeCycle;
use App\Log;

class EndUserControllerApi extends BaseController
{
    protected $modelClass = EndUser::class;

    public function user_login(Request $request)
    {
        $invitation_code = $request->invitation_code;
        $name = $request->name;
        $udid = $request->udid;
        $imei = $request->imei;
        $platform	= $request->platform;
        $phone = $request->phone;
        $push_id = $request->push_id;
        $app_version = $request->app_version;
        $app_identifier = $request->app_identifier;
        $phone_migration = $request->phone_migration;
        $fixed = new Constants();

        $invitation = Invitation::where('code',$invitation_code )->
                      select('id','client_id','supervisor_id','phone_number','status')->
                      get()->first();

        // FIND SUPERVISOR
        $supervisor_id = null;
        if ($invitation['supervisor_id'] != null) {
            $supervisor_id = $invitation['supervisor_id'];
        }

        // Invitation Code Varification
        if(!$invitation){
          $data = ['error' => 'Invalid Invitation Code'];
          return response()->json(['data' => $data], 200);
        }

        // User Active Varification
        $active_status = EndUser::where('invitation_id',$invitation['id'] )->
                                  select('status')->get()->first();
        if($active_status['status'] == 2){
          $data = ['error' => 'You are deactivated by Admin.'];
          return response()->json(['data' => $data], 200);
        }

        // Get Device Information
        // TRY WITH UDID
        $device = Device::where('udid',$udid )->where('app_identifier','com.smartmux.etracker.user')->
                                                select('id','enduser_id','udid','push_id')->get()->first();
        if (!$device) {
          if($imei){
            $device = Device::where('imei',$imei )->where('app_identifier','com.smartmux.etracker.user')->select('id','enduser_id','udid','push_id')->get()->first();
          }
        }

        if ($device) {
          $enduser_id = $device['enduser_id'];
          $udid = $device['udid'];

          $enduser = EndUser::where('invitation_id',$invitation['id'] )->
                              where('id',$enduser_id)->
                              select('id','invitation_id','name','phone_number')->get()->last();
          if($enduser == null){
            $data = ['error' => 'You are already registered to another phone.'];
            return response()->json(['data' => $data], 200);
          }

          $version_error = null;
          $version_url = null;
          $required_app_version = VersionNotification::where('app_name','USER APP' )->
                                                       where('client_id', $invitation['client_id'])->
                                                       select('version','version_code','whats_new','apk_url')->get()->last();
          if($app_version != $required_app_version['version'] ){
              $version_error = "New Version available.Please update.";
              $version_url = $required_app_version['apk_url'];
            }

          $user_id = Client::where('id',$invitation['client_id'])->select('user_id')->get()->last();
          $user_info = User::where('id',$user_id['user_id'])->select('name')->get()->last();

          $data = ['client_id' => $invitation['client_id'],
                  'supervisor_id' => $supervisor_id == null ? -1 : $supervisor_id,
                  'enduser_id' => $enduser['id'],
                  'code' => $invitation_code,
                  'client_name' => $user_info['name']];
                  if ($version_error) {
                    $data['version_error'] = $version_error;
                    $data['version_url'] = $version_url;
                  }

          return response()->json(['data' => $data], 200);
        }

        $status = $invitation['status'];
        if($status == 2){

          // Check the User Device Capable version
          $phone_change = PhoneChange::where('invitation_id',$invitation['id'] )->select('id','status')->get()->last();
          if($phone_change['status'] == 1){
            $data = ['error' => 'You have already requested for the phone change.
                                 Please wait for the Admin response.'];
            return response()->json(['data' => $data], 200);
          }elseif($phone_change['status'] == 3){
            $data = ['error' => 'Admin denied your request.'];
            return response()->json(['data' => $data], 200);
          }
          if($phone_migration){
            $data = ['phone_migration_error' => 'One device has been registered previously. Do you want to reset the previous device and use new one?'];
            return response()->json(['data' => $data], 200);
          }else{
            $data = ['error' => 'Code Already in Use'];
            return response()->json(['data' => $data], 200);
          }
        }
        // Insert End User
        DB::table('endusers')->insert(['invitation_id' => $invitation['id'],
                                       'client_id' => $invitation['client_id'],
                                       'supervisor_id' => $supervisor_id,
                                       'phone_number' => $invitation['phone_number'],
                                       'name' => $name]);
        // Get End User
        $enduser = EndUser::where('invitation_id',$invitation['id'])->
                            select('id','name','phone_number')->
                            get()->first();

        // Update Invitations
        DB::table('invitations')->where('id', $invitation['id'])->update(['status' => 2]);
        $device = Device::where('enduser_id',$enduser['id'])->
                          select('id')->get()->first();
        if (!$device) {
          Device::insert([
              'client_id' => $invitation['client_id'],
              'supervisor_id' => $supervisor_id == NULL ? -1 : $supervisor_id,
              'enduser_id' => $enduser['id'],
              'platform' => $platform,
              'device' => $phone,
              'app_version' => $app_version,
              'app_identifier' => $app_identifier,
              'udid' => $udid,
              'imei' => $imei,
              'push_id' => $push_id,
          ]);
        }
        $user_id = Client::where('id',$invitation['client_id'])->
                           select('user_id')->get()->last();
        $user_info = User::where('id',$user_id['user_id'])->select('name')->get()->last();
        $data = ['client_id' => $invitation['client_id'],
                'supervisor_id' => $supervisor_id == null ? -1 : $supervisor_id,
                'enduser_id' => $enduser['id'],
                'code' => $invitation_code,
                'client_name' => $user_info['name']];
        return response()->json(['data' => $data], 200);
    }

    public function user_login_get(Request $request)
    {
        $enduser_id = $request->enduser_id;
        $device = Device::where('enduser_id',$enduser_id )->
                        select('platform','device','app_version','app_identifier','udid','imei','push_id')->
                        get()->first();
        
        if($device == NULL){
            $data = [
                'message' => 'No Device Exist.'];
            return response()->json(['data' => $data], 200);
        }

        $enduser = EndUser::where('id',$enduser_id )->
                        select('client_id','name','invitation_id')->
                        get()->first();
        $client = Client::where('id',$enduser['client_id'] )->
                        select('user_id')->
                        get()->first();
        $user = User::where('id',$client['user_id'] )->
                        select('name')->
                        get()->first();                        

        $invitation = Invitation::where('id',$enduser['invitation_id'])->
                        select('code')->
                        get()->first();
        $data = [
                'client_name' => $user['name'],
                'code' => $invitation['code'],
                'name' => $enduser['name'],
                'udid' => $device['udid'],
                'imei' => $device['imei'],
                'app_version' => $device['app_version'],
                'platform' => $device['platform'],
                'app_identifier' => $device['app_identifier'],
                'platform' => $device['platform'],
                'device' => $device['device'],
                'push_id' => $device['push_id']];
        return response()->json(['data' => $data], 200);
    }
    
    public function add_end_user(Request $request)
    {
        $invitation_code = $request->invitation_code;
        $name = $request->name;
        $udid = $request->udid;
        $imei = $request->imei;
        $app_version = $request->app_version;
        $phone_migration = $request->phone_migration;
        $fixed = new Constants();

        $invitation = Invitation::where('code',$invitation_code )->select('id','client_id','supervisor_id','phone_number','status','test_mood')->get()->first();

        $supervisor_id = null;
        if ($invitation['supervisor_id'] != null) {
            $supervisor_id = $invitation['supervisor_id'];
        }

        // Invitation Code Varification
        if(!$invitation){
          $data = ['error' => 'Invalid Invitation Code'];
          return response()->json(['data' => $data], 200);
        }

        // User Active Varification
        $active_status = EndUser::where('invitation_id',$invitation['id'] )->select('status')->get()->first();
        if($active_status['status'] == 2){
          $data = ['error' => 'You are deactivated by Admin.'];
          return response()->json(['data' => $data], 200);
        }

        // Get Device Information
        // TRY WITH UDID
        $device = Device::where('udid',$udid )->where('app_identifier','com.smartmux.etracker.user')->select('id','enduser_id','udid','push_id')->get()->first();
        if (!$device) {
          if($imei){
            $device = Device::where('imei',$imei )->where('app_identifier','com.smartmux.etracker.user')->select('id','enduser_id','udid','push_id')->get()->first();
            // Update UDID here
          }
        }

        if ($device) {
          $enduser_id = $device['enduser_id'];
          $udid = $device['udid'];

          $enduser = EndUser::where('invitation_id',$invitation['id'] )->where('id',$enduser_id)->select('id','invitation_id','name','phone_number')->get()->last();
          if($enduser == null){
            $data = ['error' => 'You are already registered to another phone.'];
            return response()->json(['data' => $data], 200);
          }

          $version_error = null;
          $version_url = null;
          $version_code = null;
          $whats_new = null;


          $required_app_version = VersionNotification::where('app_name','USER APP' )->where('client_id', $invitation['client_id'])->select('version','version_code','whats_new','apk_url')->get()->last();
          if($app_version != $required_app_version['version'] ){
              $version_error = "New Version available.Please update.";
              $version_url = $required_app_version['apk_url'];
              $version_code = $required_app_version['version_code'];
              $whats_new = $required_app_version['whats_new'];
            }

          $now = Carbon::now();
          $current_date = $now->todateString();

          $attendance_last = Attendance::where('enduser_id',$enduser['id'] )->where('client_id',$invitation['client_id'] )->Where('created_at', 'LIKE', '%' . $current_date . '%')->select('id','status')->get()->last();
          $attendance_message = 'NOT YET';
          $attendance_color = '#FFCA28';
          if ($attendance_last) {
            $attendance_message = $attendance_last['status'] == 1 ? "ENTERED" : "EXITED";
            $attendance_color = $attendance_last['status'] == 1 ? "#00a651" : "#ed1c24";
          }
          $status = Status::where('client_id',$invitation['client_id'])->where('enduser_id',$enduser['id'])->Where('created_at', 'LIKE', '%' . $current_date . '%')->select('mood')->get()->last();
          $mood = $status['mood'];
          $message_list = Message::where('client_id',$invitation['client_id'] )->where('enduser_id',$enduser['id'])->select('id')->get()->toArray();
          $image = Photo::where('client_id',$invitation['client_id'])->where('enduser_id',$enduser['id'])->select('picture')->get()->last();
          //dd($invitation['client_id']. ' '.$enduser['id']);
          $user_id = Client::where('id',$invitation['client_id'])->select('user_id')->get()->last();
          $client_setup = ClientSetup::where('user_id',$user_id['user_id'])->select('user_visit','distance_feet_update','test_mode','background_location_update','advance_expense','dealer_point_limit','daily_activity','simple_report','fcr_report','layer_performance_report','layer_life_cycle_report','broiler_life_cycle_report','advance_dealer','advance_tour_plan','actual_tour_plan',
          'sales_order','fcr_only','daily_report','monthly_sales_target','notification_update','tp_type','outlet','atp_type','conveyance_enable',
          'task','non_internet_mood','sales_order_type','advance_activity_suggestion')->get()->first();
          $user_visit = $client_setup['user_visit'];
          $test_mode = $client_setup['test_mode'];
          $advance_expense = $client_setup['advance_expense'];
          $dealer_point_limit = $client_setup['dealer_point_limit'];
          $daily_activity = $client_setup['daily_activity'];
          $simple_report = $client_setup['simple_report'];
          $fcr_report = $client_setup['fcr_report'];
          $layer_performance_report = $client_setup['layer_performance_report'];
          $layer_life_cycle_report = $client_setup['layer_life_cycle_report'];
          $broiler_life_cycle_report = $client_setup['broiler_life_cycle_report'];
          $advance_dealer = $client_setup['advance_dealer'];
          $advance_tour_plan = $client_setup['advance_tour_plan'];
          $actual_tour_plan = $client_setup['actual_tour_plan'];
          $sales_order = $client_setup['sales_order'];
          $fcr = $client_setup['fcr_only'];
          $daily_report = $client_setup['daily_report'];
          $monthly_sales_target = $client_setup['monthly_sales_target'];
          $notification_update = $client_setup['notification_update'];
          $outlet = $client_setup['outlet'];
          $conveyance_enable = $client_setup['conveyance_enable'];
          $tasks = $client_setup['task'];
          $non_internet_mood = $client_setup['non_internet_mood'];
          $sales_order_type = $client_setup['sales_order_type'];
          $advance_activity_suggestion = $client_setup['advance_activity_suggestion'];

          if($mood){
              $mood = $mood == 2 ? 0 : $mood;
          }else{
              $mood = 0;
          }

          if($user_visit){
              $user_visit = $user_visit == 2 ? 0 : $user_visit;
          }else{
              $user_visit = 0;
          }

          if($test_mode){
              $test_mode = $test_mode == 2 ? 0 : $test_mode;
          }else{
              $test_mode = 0;
          }

          if($advance_expense){
              $advance_expense = $advance_expense == 2 ? 0 : $advance_expense;
          }else{
              $advance_expense = 0;
          }

          if($daily_activity){
              $daily_activity = $daily_activity == 2 ? 0 : $daily_activity;
          }else{
              $daily_activity = 0;
          }

          if($simple_report){
              $simple_report = $simple_report == 2 ? 0 : $simple_report;
          }else{
              $simple_report = 0;
          }

          if($fcr_report){
              $fcr_report = $fcr_report == 2 ? 0 : $fcr_report;
          }else{
              $fcr_report = 0;
          }

          if($layer_performance_report){
              $layer_performance_report = $layer_performance_report == 2 ? 0 : $layer_performance_report;
          }else{
              $layer_performance_report = 0;
          }

          if($layer_life_cycle_report){
              $layer_life_cycle_report = $layer_life_cycle_report == 2 ? 0 : $layer_life_cycle_report;
          }else{
              $layer_life_cycle_report = 0;
          }

          if($broiler_life_cycle_report){
              $broiler_life_cycle_report = $broiler_life_cycle_report == 2 ? 0 : $broiler_life_cycle_report;
          }else{
              $broiler_life_cycle_report = 0;
          }

          if($advance_tour_plan){
              $advance_tour_plan = $advance_tour_plan == 2 ? 0 : $advance_tour_plan;
          }else{
              $advance_tour_plan = 0;
          }

          if($actual_tour_plan){
              $actual_tour_plan = $actual_tour_plan == 2 ? 0 : $actual_tour_plan;
          }else{
              $actual_tour_plan = 0;
          }

          if($advance_dealer){
              $advance_dealer = $advance_dealer == 2 ? 0 : $advance_dealer;
          }else{
              $advance_dealer = 0;
          }

          if($sales_order){
              $sales_order = $sales_order == 2 ? 0 : $sales_order;
          }else{
              $sales_order = 0;
          }

          if($fcr){
              $fcr = $fcr == 2 ? 0 : $fcr;
          }else{
              $fcr = 0;
          }

          if($daily_report){
              $daily_report = $daily_report == 2 ? 0 : $daily_report;
          }else{
              $daily_report = 0;
          }

          if($monthly_sales_target){
              $monthly_sales_target = $monthly_sales_target == 2 ? 0 : $monthly_sales_target;
          }else{
              $monthly_sales_target = 0;
          }

          if($notification_update){
              $notification_update = $notification_update == 2 ? 0 : $notification_update;
          }else{
              $notification_update = 0;
          }

          if($outlet){
              $outlet = $outlet == 2 ? 0 : $outlet;
          }else{
              $outlet = 0;
          }

          if($conveyance_enable){
              $conveyance_enable = $conveyance_enable == 2 ? 0 : $conveyance_enable;
          }else{
              $conveyance_enable = 0;
          }

          if($tasks){
              $tasks = $tasks == 2 ? 0 : $tasks;
          }else{
              $tasks = 0;
          }

          if($non_internet_mood){
              $non_internet_mood = $non_internet_mood == 2 ? 0 : $non_internet_mood;
          }else{
              $non_internet_mood = 0;
          }

          if($sales_order_type){
              $sales_order_type = $sales_order_type == 2 ? 0 : $sales_order_type;
          }else{
              $sales_order_type = 0;
          }

          if($advance_activity_suggestion){
              $advance_activity_suggestion = $advance_activity_suggestion == 2 ? 0 : $advance_activity_suggestion;
          }else{
              $advance_activity_suggestion = 0;
          }

          $task_id = Task::where('client_id',$invitation['client_id'])->where('enduser_id',$enduser['id'])->select('id','title')->get()->toArray();
          $status = 0;
          foreach ($task_id as $task)  {
            $status_id = TaskStatus::where('status', 1)->where('task_id', $task['id'])->get()->first();
            if($status_id){
            $status ++;
            }
          }

          $today = Carbon\Carbon::now()->toDateString();

          $user_info = User::where('id',$user_id['user_id'])->select('name')->get()->last();

          $data = ['client_id' => $invitation['client_id'],
                  'supervisor_id' => $supervisor_id == null ? -1 : $supervisor_id,
                  'enduser_id' => $enduser['id'],
                  'push_token' => $device['push_id'],
                  'name' => $enduser['name'],
                  'code' => $invitation_code,
                  'client_name' => $user_info['name'],
                  'phone_number' => $enduser['phone_number'],
                  'today' => $today,
                  'image_url' => $image['picture'] ? $fixed->getStoragePath().$image['picture'] : '',
                  'online' => $mood,
                  'button_message' => $mood == 1 ? "GO OFFLINE" : "GO ONLINE",
                  'button_color' => $mood == 1 ? "#ed1c24" : "#00a651",
                  'unread_message' => count($message_list),
                  'user_visit' => $user_visit,
                  'test_mode' => $test_mode,
                  'distance_feet_update' => $client_setup['distance_feet_update'],
                  'background_location_update' => $client_setup['background_location_update'],
                  'advance_expense' => $advance_expense,
                  'open_task' => $status,
                  'dealer_point_limit' => $dealer_point_limit,
                  'daily_activity' => $daily_activity,
                  'simple_report' => $simple_report,
                  'fcr_report' => $fcr_report,
                  'layer_performance_report' => $layer_performance_report,
                  'layer_life_cycle_report' => $layer_life_cycle_report,
                  'broiler_life_cycle_report' => $broiler_life_cycle_report,
                  'advance_tour_plan' => $advance_tour_plan,
                  'actual_tour_plan' => $actual_tour_plan,
                  'advance_dealer' => $advance_dealer,
                  'sales_order' => $sales_order,
                  'fcr_only' => $fcr,
                  'daily_report' => $daily_report,
                  'monthly_sales_target' => $monthly_sales_target,
                  'notification_update' => $notification_update,
                  'tp_type' => $client_setup['tp_type'],
                  'outlet' => $outlet,
                  'atp_type' => $client_setup['atp_type'],
                  'conveyance_enable' => $conveyance_enable,
                  'task' => $tasks,
                  'non_internet_mood' => $non_internet_mood,
                  'sales_order_type' => $sales_order_type,
                  'advance_activity_suggestion' => $advance_activity_suggestion,
                  'attendance' => $attendance_message,
                  'attendance_color' => $attendance_color,
                  'message' =>'You are already a member.'];

                  if ($version_error) {
                    $data['version_error'] = $version_error;
                    $data['version_url'] = $version_url;
                    $data['version_code'] = $version_code;
                    $data['whats_new'] = $whats_new;
                  }

          return response()->json(['data' => $data], 200);
        }

        $status = $invitation['status'];
        if($status == 2){

          // Check the User Device Capable version
          //dd($phone_migration);
          $phone_change = PhoneChange::where('invitation_id',$invitation['id'] )->select('id','status')->get()->last();
          if($phone_change['status'] == 1){
            $data = ['error' => 'You have already requested for the phone change.
                                 Please wait for the Admin response.'];
            return response()->json(['data' => $data], 200);
          }elseif($phone_change['status'] == 3){
            $data = ['error' => 'Admin denied your request.'];
            return response()->json(['data' => $data], 200);
          }
          if($phone_migration){

            $data = ['phone_migration_error' => 'One device has been registered previously. Do you want to reset the previous device and use new one?'];
            return response()->json(['data' => $data], 200);
          }else{
            $data = ['error' => 'Code Already in Use'];
            return response()->json(['data' => $data], 200);
          }
        }

        DB::table('endusers')->insert(['invitation_id' => $invitation['id'], 'client_id' => $invitation['client_id'], 'supervisor_id' => $supervisor_id,'phone_number' => $invitation['phone_number'], 'name' => $name]);
        //dd($supervisor_id);
        $enduser = EndUser::where('invitation_id',$invitation['id'] )->select('id','name','phone_number')->get()->first();
        DB::table('invitations')->where('id', $invitation['id'])->update(['status' => 2]);

        // Send push to all admin
        $push_ids = Device::where('client_id', $invitation['client_id'])->where('enduser_id', '-1')->select('push_id')->get()->toArray();
        $message = $name. ' registered as a user.';
        foreach ($push_ids as $push_id) {
            PushController::send_push_to_admin($push_id['push_id'],$message);
        }

        $status = Status::where('client_id',$invitation['client_id'])->where('enduser_id',$enduser['id'])->select('mood')->get()->last();
        $mood = $status['mood'];
        $fixed = new Constants();
        $message_list = Message::where('client_id',$invitation['client_id'] )->where('enduser_id',$enduser['id'])->select('id')->get()->toArray();

        $image = Photo::where('client_id',$invitation['client_id'])->where('enduser_id',$enduser['id'])->select('picture')->get()->last();
        $user_id = Client::where('id',$invitation['client_id'])->select('user_id')->get()->last();
        $client_setup = ClientSetup::where('user_id',$user_id['user_id'])->select('user_visit','distance_feet_update','test_mode','background_location_update','advance_expense','dealer_point_limit','daily_activity','simple_report','fcr_report','layer_performance_report','layer_life_cycle_report','broiler_life_cycle_report','advance_dealer','advance_tour_plan','actual_tour_plan',
        'sales_order','fcr_only','daily_report','monthly_sales_target','notification_update','tp_type','outlet','atp_type','conveyance_enable','task',
        'non_internet_mood','sales_order_type','advance_activity_suggestion')->get()->first();
        $user_visit = $client_setup['user_visit'];
        $test_mode = $client_setup['test_mode'];
        $advance_expense = $client_setup['advance_expense'];
        $dealer_point_limit = $client_setup['dealer_point_limit'];
        $daily_activity = $client_setup['daily_activity'];
        $simple_report = $client_setup['simple_report'];
        $fcr_report = $client_setup['fcr_report'];
        $layer_performance_report = $client_setup['layer_performance_report'];
        $layer_life_cycle_report = $client_setup['layer_life_cycle_report'];
        $broiler_life_cycle_report = $client_setup['broiler_life_cycle_report'];
        $advance_dealer = $client_setup['advance_dealer'];
        $advance_tour_plan = $client_setup['advance_tour_plan'];
        $actual_tour_plan = $client_setup['actual_tour_plan'];
        $sales_order = $client_setup['sales_order'];
        $fcr = $client_setup['fcr_only'];
        $daily_report = $client_setup['daily_report'];
        $monthly_sales_target = $client_setup['monthly_sales_target'];
        $notification_update = $client_setup['notification_update'];
        $outlet = $client_setup['outlet'];
        $conveyance_enable = $client_setup['conveyance_enable'];
        $tasks = $client_setup['task'];
        $non_internet_mood = $client_setup['non_internet_mood'];
        $sales_order_type = $client_setup['sales_order_type'];
        $advance_activity_suggestion = $client_setup['advance_activity_suggestion'];

        if($user_visit){
            $user_visit = $user_visit == 2 ? 0 : $user_visit;
        }else{
            $user_visit = 0;
        }

        if($test_mode){
            $test_mode = $test_mode == 2 ? 0 : $test_mode;
        }else{
            $test_mode = 0;
        }

        if($advance_expense){
            $advance_expense = $advance_expense == 2 ? 0 : $advance_expense;
        }else{
            $advance_expense = 0;
        }

        if($daily_activity){
            $daily_activity = $daily_activity == 2 ? 0 : $daily_activity;
        }else{
            $daily_activity = 0;
        }

        if($simple_report){
            $simple_report = $simple_report == 2 ? 0 : $simple_report;
        }else{
            $simple_report = 0;
        }

        if($fcr_report){
            $fcr_report = $fcr_report == 2 ? 0 : $fcr_report;
        }else{
            $fcr_report = 0;
        }

        if($layer_performance_report){
            $layer_performance_report = $layer_performance_report == 2 ? 0 : $layer_performance_report;
        }else{
            $layer_performance_report = 0;
        }

        if($layer_life_cycle_report){
            $layer_life_cycle_report = $layer_life_cycle_report == 2 ? 0 : $layer_life_cycle_report;
        }else{
            $layer_life_cycle_report = 0;
        }

        if($broiler_life_cycle_report){
            $broiler_life_cycle_report = $broiler_life_cycle_report == 2 ? 0 : $broiler_life_cycle_report;
        }else{
            $broiler_life_cycle_report = 0;
        }

        if($advance_tour_plan){
            $advance_tour_plan = $advance_tour_plan == 2 ? 0 : $advance_tour_plan;
        }else{
            $advance_tour_plan = 0;
        }

        if($actual_tour_plan){
            $actual_tour_plan = $actual_tour_plan == 2 ? 0 : $actual_tour_plan;
        }else{
            $actual_tour_plan = 0;
        }

        if($advance_dealer){
            $advance_dealer = $advance_dealer == 2 ? 0 : $advance_dealer;
        }else{
            $advance_dealer = 0;
        }

        if($sales_order){
            $sales_order = $sales_order == 2 ? 0 : $sales_order;
        }else{
            $sales_order = 0;
        }

        if($fcr){
            $fcr = $fcr == 2 ? 0 : $fcr;
        }else{
            $fcr = 0;
        }

        if($daily_report){
            $daily_report = $daily_report == 2 ? 0 : $daily_report;
        }else{
            $daily_report = 0;
        }

        if($monthly_sales_target){
            $monthly_sales_target = $monthly_sales_target == 2 ? 0 : $monthly_sales_target;
        }else{
            $monthly_sales_target = 0;
        }

        if($notification_update){
            $notification_update = $notification_update == 2 ? 0 : $notification_update;
        }else{
            $notification_update = 0;
        }

        if($outlet){
            $outlet = $outlet == 2 ? 0 : $outlet;
        }else{
            $outlet = 0;
        }

        if($conveyance_enable){
            $conveyance_enable = $conveyance_enable == 2 ? 0 : $conveyance_enable;
        }else{
            $conveyance_enable = 0;
        }

        if($tasks){
            $tasks = $tasks == 2 ? 0 : $tasks;
        }else{
            $tasks = 0;
        }

        if($non_internet_mood){
            $non_internet_mood = $non_internet_mood == 2 ? 0 : $non_internet_mood;
        }else{
            $non_internet_mood = 0;
        }

        if($sales_order_type){
            $sales_order_type = $sales_order_type == 2 ? 0 : $sales_order_type;
        }else{
            $sales_order_type = 0;
        }

        if($advance_activity_suggestion){
            $advance_activity_suggestion = $advance_activity_suggestion == 2 ? 0 : $advance_activity_suggestion;
        }else{
            $advance_activity_suggestion = 0;
        }

        $now = Carbon::now();
        $current_date = $now->todateString();
        $attendance_last = Attendance::where('enduser_id',$enduser['id'] )->where('client_id',$invitation['client_id'] )->Where('created_at', 'LIKE', '%' . $current_date . '%')->select('id','status')->get()->last();
        $attendance_message = 'NOT YET';
        $attendance_color = '#FFCA28';
        if ($attendance_last) {
            $attendance_message = $attendance_last['status'] == 1 ? "ENTERED" : "EXITED";
            $attendance_color = $attendance_last['status'] == 1 ? "#00a651" : "#ed1c24";
        }

        $today = Carbon\Carbon::now()->toDateString();

        $user_info = User::where('id',$user_id['user_id'])->select('name')->get()->last();
        $data = ['client_id' => $invitation['client_id'],
                'supervisor_id' => $supervisor_id == null ? -1 : $supervisor_id,
                'enduser_id' => $enduser['id'],
                'push_token' => '',
                'name' => $enduser['name'],
                'code' => $invitation_code,
                'client_name' => $user_info['name'],
                'phone_number' => $enduser['phone_number'],
                'today' => $today,
                'image_url' => $fixed->getStoragePath().$image['picture'],
                'online' => $mood,
                'button_message' => $mood == 1 ? "GO OFFLINE" : "GO ONLINE",
                'button_color' => $mood == 1 ? "#ed1c24" : "#00a651",
                'unread_message' => count($message_list),
                'user_visit' => $user_visit,
                'test_mode' => $test_mode,
                'distance_feet_update' => $client_setup['distance_feet_update'],
                'background_location_update' => $client_setup['background_location_update'],
                'advance_expense' => $advance_expense,
                'open_task' => 0,
                'dealer_point_limit' => $dealer_point_limit,
                'daily_activity' => $daily_activity,
                'simple_report' => $simple_report,
                'fcr_report' => $fcr_report,
                'layer_performance_report' => $layer_performance_report,
                'layer_life_cycle_report' => $layer_life_cycle_report,
                'broiler_life_cycle_report' => $broiler_life_cycle_report,
                'advance_tour_plan' => $advance_tour_plan,
                'actual_tour_plan' => $actual_tour_plan,
                'advance_dealer' => $advance_dealer,
                'sales_order' => $sales_order,
                'fcr_only' => $fcr,
                'daily_report' => $daily_report,
                'monthly_sales_target' => $monthly_sales_target,
                'notification_update' => $notification_update,
                'tp_type' => $client_setup['tp_type'],
                'outlet' => $outlet,
                'atp_type' => $client_setup['atp_type'],
                'conveyance_enable' => $conveyance_enable,
                'task' => $tasks,
                'non_internet_mood' => $non_internet_mood,
                'sales_order_type' => $sales_order_type,
                'advance_activity_suggestion' => $advance_activity_suggestion,
                'attendance' => $attendance_message,
                'attendance_color' => $attendance_color,
                'message' =>'You are connected with the tracking System.Please Add your Photo.'];
        return response()->json(['data' => $data], 200);
    }

    public function authenticate(Request $request)
    {
      $client_id = $request->client_id;
      $enduser_id = $request->enduser_id;
      $code = $request->code;

      $now = Carbon::now();
      $current_date = $now->todateString();

      $attendance_last = Attendance::where('enduser_id',$enduser_id )->where('client_id',$client_id )->Where('created_at', 'LIKE', '%' . $current_date . '%')->select('id','status')->get()->last();
      $attendance_message = 'NOT YET';
      $attendance_color = '#FFCA28';
      if ($attendance_last) {
        $attendance_message = $attendance_last['status'] == 1 ? "ENTERED" : "EXITED";
        $attendance_color = $attendance_last['status'] == 1 ? "#00a651" : "#ed1c24";
      }
      $status = Status::where('client_id',$client_id)->where('enduser_id',$enduser_id)->Where('created_at', 'LIKE', '%' . $current_date . '%')->select('mood')->get()->last();
      $mood = $status['mood'];

      $data = [
              'online' => $mood,
              'button_message' => $mood == 1 ? "GO OFFLINE" : "GO ONLINE",
              'button_color' => $mood == 1 ? "#ed1c24" : "#00a651",
              'attendance' => $attendance_message,
              'attendance_color' => $attendance_color];

      return response()->json(['data' => $data], 200);
    }

    public function inactive_end_user(Request $request)
    {
      $client_id = $request->client_id;
      $enduser_id = $request->enduser_id;
      $push_id = Device::where('client_id', $client_id)->where('enduser_id', $enduser_id)->select('push_id')->get()->last();
      $message ='You are deactivated by Admin.';
      if($push_id)
      PushController::send_push_to_user($push_id['push_id'],$message);

      DB::table('endusers')->where('id',$enduser_id)->where('client_id',$client_id)-> update(['status' => 2]);
      $data = [
                'message' => 'User Deactivated.',
              ];
      return response()->json(['data' => $data], 200);
    }

    public function active_end_user(Request $request)
    {
      $client_id = $request->client_id;
      $enduser_id = $request->enduser_id;

      $enduser_list = EndUser::where('client_id',$client_id )->where('status',1 )->select('id','invitation_id','phone_number','name')->get()->toArray();
      $client = Client::where('id',$client_id)->select('user_id')->get()->first();
      $payment = Payment::where('user_id',$client['user_id'])->select('user_limit')->get()->first();
      $remaining = intval($payment['user_limit']) - count($enduser_list);

      $push_id = Device::where('client_id', $client_id)->where('enduser_id', $enduser_id)->select('push_id')->get()->last();
      $message ='You are activated by Admin.';
      if($push_id)
      PushController::send_push_to_user($push_id['push_id'],$message);

      if ($remaining > 0) {
        DB::table('endusers')->where('id',$enduser_id)->where('client_id',$client_id)-> update(['status' => 1]);
        $data = [
                  'message' => 'User Activated.',
                ];
        return response()->json(['data' => $data], 200);

      }else{

      $data = [
          'error' => 'User Limit Exceed.',
        ];
        return response()->json(['data' => $data], 200);
        }
    }

    public function get_end_user_statictics(Request $request)
    {
      $client_id = $request->client_id;
      $enduser_id = $request->enduser_id;
      $now = Carbon::now();
      $current_date = $now->toDateString();

      $allData = [];

      $fcr_after_sale_list = FcrAfterSale::where('client_id',$client_id )->
                where('enduser_id',$enduser_id )->
                where('created_at', 'LIKE', '%' . $current_date . '%')->
                select('id')->get()->toArray();
      $data = [
        'title' => 'FCR After Sale',
        'count' => count($fcr_after_sale_list),
        ];

      $allData[] = $data;

      $fcr_before_sale_list = FcrBeforeSale::where('client_id',$client_id )->
                where('enduser_id',$enduser_id )->
                where('created_at', 'LIKE', '%' . $current_date . '%')->
                select('id')->get()->toArray();
      $data = [
      'title' => 'FCR Before Sale',
      'count' => count($fcr_before_sale_list),
      ];
      $allData[] = $data;

      $conveyance_list = Conveyance::where('client_id',$client_id )->
                where('enduser_id',$enduser_id )->
                where('created_at', 'LIKE', '%' . $current_date . '%')->
                select('id')->get()->toArray();
      $data = [
      'title' => 'Conveyance',
      'count' => count($conveyance_list),
      ];
      $allData[] = $data;

      $layer_performance_list = LayerPerformance::where('client_id',$client_id )->
                where('enduser_id',$enduser_id )->
                where('created_at', 'LIKE', '%' . $current_date . '%')->
                select('id')->get()->toArray();
      $data = [
      'title' => 'Layer Performance',
      'count' => count($layer_performance_list),
      ];
      $allData[] = $data;

      $layer_life_cycle = LayerLifeCycle::where('client_id',$client_id )->
                where('enduser_id',$enduser_id )->
                where('created_at', 'LIKE', '%' . $current_date . '%')->
                select('id')->get()->toArray();
      $data = [
      'title' => 'Layer Life Cycle',
      'count' => count($layer_life_cycle),
      ];
      $allData[] = $data;

      $broiler_life_cycle = BroilerLifeCycle::where('client_id',$client_id )->
                where('enduser_id',$enduser_id )->
                where('created_at', 'LIKE', '%' . $current_date . '%')->
                select('id')->get()->toArray();
      $data = [
      'title' => 'Broiler Life Cycle',
      'count' => count($broiler_life_cycle),
      ];
      $allData[] = $data;

    return response()->json(['data' => $allData], 200);
    }


    public function join_as_end_user_test(Request $request)
    {
      $data = [
              'message' =>'before invitation abc'];
return response()->json(['data' => $data], 200);

        $invitation_code = $request->invitation_code;
        $name = $request->name;
        $udid = $request->udid;
        $imei = $request->imei;
        $app_version = $request->app_version;
        $fixed = new Constants();


        /*
        $log_details = $name.','.$udid.','.$imei.','.$app_version.','.$invitation_code;
        DB::table('logs')->
                  insert(['client_id' => '0',
                          'enduser_id' => '0',
                          'type' => 'PARAM GET',
                          'details' => $log_details]);
                          */
                          $data = [
                                  'message' =>'before invitation'];
        return response()->json(['data' => $data], 200);

        $invitation = Invitation::where('code',$invitation_code )->select('id','client_id','supervisor_id','phone_number','status','test_mood')->get()->first();


        $data = [
                'message' =>$invitation['id']];



        // SAVE THE LOG
        // if ($invitation['test_mood'] == 1) {
        //   $log_details = $name.','.$udid.','.$imei.','.$app_version.','.$invitation_code;
        //   //$data = ['error' => $log_details];
        //   //return response()->json(['data' => $data], 200);
        //
        //   $log_details = $name.','.$udid.','.$imei.','.$app_version.','.$invitation_code;
        //   DB::table('logs')->
        //             insert(['client_id' => $invitation['client_id'],
        //                     'enduser_id' => '0',
        //                     'type' => 'INIT INFO',
        //                     'details' => $log_details]);
        //
        // }

        $supervisor_id = null;
        if ($invitation['supervisor_id'] != null) {
            $supervisor_id = $invitation['supervisor_id'];
        }

        // Invitation Code Varification
        if(!$invitation){
          $data = ['error' => 'Invalid Invitation Code'];
          return response()->json(['data' => $data], 200);
        }


        // User Active Varification
        $active_status = EndUser::where('invitation_id',$invitation['id'] )->select('status')->get()->first();
        if($active_status['status'] == 2){
          $data = ['error' => 'You are deactivated by Admin.'];
          return response()->json(['data' => $data], 200);
        }

        // Get Device Information
        // TRY WITH UDID
        $device = Device::where('udid',$udid )->where('app_identifier','com.smartmux.etracker.user')->select('id','enduser_id','udid','push_id')->get()->first();
        if (!$device) {
          if($imei){
            $device = Device::where('imei',$imei )->where('app_identifier','com.smartmux.etracker.user')->select('id','enduser_id','udid','push_id')->get()->first();
            // Update UDID here
          }
        }

        // SAVE THE LOG
        // if ($invitation['test_mood'] == 1) {
        //   $log_details = $name.','.$udid.','.$imei.','.$app_version.','.$invitation_code;
        //   //dd($invitation['client_id'].' - '.$device['enduser_id']);
        //   DB::table('logs')->
        //             insert(['client_id' => $invitation['client_id'],
        //                     'enduser_id' => $device['enduser_id'],
        //                     'type' => 'ALL INFO',
        //                     'details' => $log_details]);
        // }

        if ($device) {

          $enduser_id = $device['enduser_id'];
          $udid = $device['udid'];

          $enduser = EndUser::where('invitation_id',$invitation['id'] )->where('id',$enduser_id)->select('id','invitation_id','name','phone_number')->get()->last();
          if($enduser == null){
            $data = ['error' => 'You are already registered to another phone.'];
            return response()->json(['data' => $data], 200);
          }

          $version_error = null;
          $version_url = null;
          $version_code = null;
          $whats_new = null;


          $required_app_version = VersionNotification::where('app_name','USER APP' )->where('client_id', $invitation['client_id'])->select('version','version_code','whats_new','apk_url')->get()->last();
          if($app_version != $required_app_version['version'] ){
              $version_error = "New Version available.Please update.";
              $version_url = $required_app_version['apk_url'];
              $version_code = $required_app_version['version_code'];
              $whats_new = $required_app_version['whats_new'];
            }

          $now = Carbon::now();
          $current_date = $now->todateString();

          $attendance_last = Attendance::where('enduser_id',$enduser['id'] )->where('client_id',$invitation['client_id'] )->Where('created_at', 'LIKE', '%' . $current_date . '%')->select('id','status')->get()->last();
          $attendance_message = 'NOT YET';
          $attendance_color = '#FFCA28';
          if ($attendance_last) {
            $attendance_message = $attendance_last['status'] == 1 ? "ENTERED" : "EXITED";
            $attendance_color = $attendance_last['status'] == 1 ? "#00a651" : "#ed1c24";
          }
          $status = Status::where('client_id',$invitation['client_id'])->where('enduser_id',$enduser['id'])->Where('created_at', 'LIKE', '%' . $current_date . '%')->select('mood')->get()->last();
          $mood = $status['mood'];
          $message_list = Message::where('client_id',$invitation['client_id'] )->where('enduser_id',$enduser['id'])->select('id')->get()->toArray();
          $image = Photo::where('client_id',$invitation['client_id'])->where('enduser_id',$enduser['id'])->select('picture')->get()->last();
          //dd($invitation['client_id']. ' '.$enduser['id']);
          $user_id = Client::where('id',$invitation['client_id'])->select('user_id')->get()->last();
          $client_setup = ClientSetup::where('user_id',$user_id['user_id'])->select('user_visit','distance_feet_update','test_mode','background_location_update','advance_expense','dealer_point_limit','daily_activity','simple_report','fcr_report','layer_performance_report','layer_life_cycle_report','broiler_life_cycle_report','advance_dealer','advance_tour_plan','actual_tour_plan',
          'sales_order','fcr_only','daily_report','monthly_sales_target','notification_update','tp_type','outlet','atp_type','conveyance_enable')->get()->first();
          $user_visit = $client_setup['user_visit'];
          $test_mode = $client_setup['test_mode'];
          $advance_expense = $client_setup['advance_expense'];
          $dealer_point_limit = $client_setup['dealer_point_limit'];
          $daily_activity = $client_setup['daily_activity'];
          $simple_report = $client_setup['simple_report'];
          $fcr_report = $client_setup['fcr_report'];
          $layer_performance_report = $client_setup['layer_performance_report'];
          $layer_life_cycle_report = $client_setup['layer_life_cycle_report'];
          $broiler_life_cycle_report = $client_setup['broiler_life_cycle_report'];
          $advance_dealer = $client_setup['advance_dealer'];
          $advance_tour_plan = $client_setup['advance_tour_plan'];
          $actual_tour_plan = $client_setup['actual_tour_plan'];
          $sales_order = $client_setup['sales_order'];
          $fcr = $client_setup['fcr_only'];
          $daily_report = $client_setup['daily_report'];
          $monthly_sales_target = $client_setup['monthly_sales_target'];
          $notification_update = $client_setup['notification_update'];
          $outlet = $client_setup['outlet'];
          $conveyance_enable = $client_setup['conveyance_enable'];

          if($mood){
              $mood = $mood == 2 ? 0 : $mood;
          }else{
              $mood = 0;
          }

          if($user_visit){
              $user_visit = $user_visit == 2 ? 0 : $user_visit;
          }else{
              $user_visit = 0;
          }

          if($test_mode){
              $test_mode = $test_mode == 2 ? 0 : $test_mode;
          }else{
              $test_mode = 0;
          }

          if($advance_expense){
              $advance_expense = $advance_expense == 2 ? 0 : $advance_expense;
          }else{
              $advance_expense = 0;
          }

          if($daily_activity){
              $daily_activity = $daily_activity == 2 ? 0 : $daily_activity;
          }else{
              $daily_activity = 0;
          }

          if($simple_report){
              $simple_report = $simple_report == 2 ? 0 : $simple_report;
          }else{
              $simple_report = 0;
          }

          if($fcr_report){
              $fcr_report = $fcr_report == 2 ? 0 : $fcr_report;
          }else{
              $fcr_report = 0;
          }

          if($layer_performance_report){
              $layer_performance_report = $layer_performance_report == 2 ? 0 : $layer_performance_report;
          }else{
              $layer_performance_report = 0;
          }

          if($layer_life_cycle_report){
              $layer_life_cycle_report = $layer_life_cycle_report == 2 ? 0 : $layer_life_cycle_report;
          }else{
              $layer_life_cycle_report = 0;
          }

          if($broiler_life_cycle_report){
              $broiler_life_cycle_report = $broiler_life_cycle_report == 2 ? 0 : $broiler_life_cycle_report;
          }else{
              $broiler_life_cycle_report = 0;
          }

          if($advance_tour_plan){
              $advance_tour_plan = $advance_tour_plan == 2 ? 0 : $advance_tour_plan;
          }else{
              $advance_tour_plan = 0;
          }

          if($actual_tour_plan){
              $actual_tour_plan = $actual_tour_plan == 2 ? 0 : $actual_tour_plan;
          }else{
              $actual_tour_plan = 0;
          }

          if($advance_dealer){
              $advance_dealer = $advance_dealer == 2 ? 0 : $advance_dealer;
          }else{
              $advance_dealer = 0;
          }

          if($sales_order){
              $sales_order = $sales_order == 2 ? 0 : $sales_order;
          }else{
              $sales_order = 0;
          }

          if($fcr){
              $fcr = $fcr == 2 ? 0 : $fcr;
          }else{
              $fcr = 0;
          }

          if($daily_report){
              $daily_report = $daily_report == 2 ? 0 : $daily_report;
          }else{
              $daily_report = 0;
          }

          if($monthly_sales_target){
              $monthly_sales_target = $monthly_sales_target == 2 ? 0 : $monthly_sales_target;
          }else{
              $monthly_sales_target = 0;
          }

          if($notification_update){
              $notification_update = $notification_update == 2 ? 0 : $notification_update;
          }else{
              $notification_update = 0;
          }

          if($outlet){
              $outlet = $outlet == 2 ? 0 : $outlet;
          }else{
              $outlet = 0;
          }

          if($conveyance_enable){
              $conveyance_enable = $conveyance_enable == 2 ? 0 : $conveyance_enable;
          }else{
              $conveyance_enable = 0;
          }

          $task_id = Task::where('client_id',$invitation['client_id'])->where('enduser_id',$enduser['id'])->select('id','title')->get()->toArray();
          $status = 0;
          foreach ($task_id as $task)  {
            $status_id = TaskStatus::where('status', 1)->where('task_id', $task['id'])->get()->first();
            if($status_id){
            $status ++;
            }
          }

          $today = Carbon\Carbon::now()->toDateString();

          $user_info = User::where('id',$user_id['user_id'])->select('name')->get()->last();

          $data = ['client_id' => $invitation['client_id'],
                  'supervisor_id' => $supervisor_id == null ? -1 : $supervisor_id,
                  'enduser_id' => $enduser['id'],
                  'push_token' => $device['push_id'],
                  'name' => $enduser['name'],
                  'code' => $invitation_code,
                  'client_name' => $user_info['name'],
                  'phone_number' => $enduser['phone_number'],
                  'today' => $today,
                  'image_url' => $image['picture'] ? $fixed->getStoragePath().$image['picture'] : '',
                  'online' => $mood,
                  'button_message' => $mood == 1 ? "GO OFFLINE" : "GO ONLINE",
                  'button_color' => $mood == 1 ? "#ed1c24" : "#00a651",
                  'unread_message' => count($message_list),
                  'user_visit' => $user_visit,
                  'test_mode' => $test_mode,
                  'distance_feet_update' => $client_setup['distance_feet_update'],
                  'background_location_update' => $client_setup['background_location_update'],
                  'advance_expense' => $advance_expense,
                  'open_task' => $status,
                  'dealer_point_limit' => $dealer_point_limit,
                  'daily_activity' => $daily_activity,
                  'simple_report' => $simple_report,
                  'fcr_report' => $fcr_report,
                  'layer_performance_report' => $layer_performance_report,
                  'layer_life_cycle_report' => $layer_life_cycle_report,
                  'broiler_life_cycle_report' => $broiler_life_cycle_report,
                  'advance_tour_plan' => $advance_tour_plan,
                  'actual_tour_plan' => $actual_tour_plan,
                  'advance_dealer' => $advance_dealer,
                  'sales_order' => $sales_order,
                  'fcr_only' => $fcr,
                  'daily_report' => $daily_report,
                  'monthly_sales_target' => $monthly_sales_target,
                  'notification_update' => $notification_update,
                  'tp_type' => $client_setup['tp_type'],
                  'outlet' => $outlet,
                  'atp_type' => $client_setup['atp_type'],
                  'conveyance_enable' => $conveyance_enable,
                  'attendance' => $attendance_message,
                  'attendance_color' => $attendance_color,
                  'message' =>'You are already a member.'];

                  if ($version_error) {
                    $data['version_error'] = $version_error;
                    $data['version_url'] = $version_url;
                    $data['version_code'] = $version_code;
                    $data['whats_new'] = $whats_new;
                  }

          return response()->json(['data' => $data], 200);
        }

        $status = $invitation['status'];
        if($status == 2){
          $data = ['error' => 'Code Already in Use'];
          return response()->json(['data' => $data], 200);
        }

        DB::table('endusers')->insert(['invitation_id' => $invitation['id'], 'client_id' => $invitation['client_id'], 'supervisor_id' => $supervisor_id,'phone_number' => $invitation['phone_number'], 'name' => $name]);
        //dd($supervisor_id);
        $enduser = EndUser::where('invitation_id',$invitation['id'] )->select('id','name','phone_number')->get()->first();
        DB::table('invitations')->where('id', $invitation['id'])->update(['status' => 2]);

        // Send push to all admin
        $push_ids = Device::where('client_id', $invitation['client_id'])->where('enduser_id', '-1')->select('push_id')->get()->toArray();
        $message = $name. ' registered as a user.';
        foreach ($push_ids as $push_id) {
            PushController::send_push_to_admin($push_id['push_id'],$message);
        }

        $status = Status::where('client_id',$invitation['client_id'])->where('enduser_id',$enduser['id'])->select('mood')->get()->last();
        $mood = $status['mood'];
        $fixed = new Constants();
        $message_list = Message::where('client_id',$invitation['client_id'] )->where('enduser_id',$enduser['id'])->select('id')->get()->toArray();

        $image = Photo::where('client_id',$invitation['client_id'])->where('enduser_id',$enduser['id'])->select('picture')->get()->last();
        $user_id = Client::where('id',$invitation['client_id'])->select('user_id')->get()->last();
        $client_setup = ClientSetup::where('user_id',$user_id['user_id'])->select('user_visit','distance_feet_update','test_mode','background_location_update','advance_expense','dealer_point_limit','daily_activity','simple_report','fcr_report','layer_performance_report','layer_life_cycle_report','broiler_life_cycle_report','advance_dealer','advance_tour_plan','actual_tour_plan',
        'sales_order','fcr_only','daily_report','monthly_sales_target','notification_update','tp_type','outlet','atp_type','conveyance_enable')->get()->first();
        $user_visit = $client_setup['user_visit'];
        $test_mode = $client_setup['test_mode'];
        $advance_expense = $client_setup['advance_expense'];
        $dealer_point_limit = $client_setup['dealer_point_limit'];
        $daily_activity = $client_setup['daily_activity'];
        $simple_report = $client_setup['simple_report'];
        $fcr_report = $client_setup['fcr_report'];
        $layer_performance_report = $client_setup['layer_performance_report'];
        $layer_life_cycle_report = $client_setup['layer_life_cycle_report'];
        $broiler_life_cycle_report = $client_setup['broiler_life_cycle_report'];
        $advance_dealer = $client_setup['advance_dealer'];
        $advance_tour_plan = $client_setup['advance_tour_plan'];
        $actual_tour_plan = $client_setup['actual_tour_plan'];
        $sales_order = $client_setup['sales_order'];
        $fcr = $client_setup['fcr_only'];
        $daily_report = $client_setup['daily_report'];
        $monthly_sales_target = $client_setup['monthly_sales_target'];
        $notification_update = $client_setup['notification_update'];
        $outlet = $client_setup['outlet'];
        $conveyance_enable = $client_setup['conveyance_enable'];

        if($user_visit){
            $user_visit = $user_visit == 2 ? 0 : $user_visit;
        }else{
            $user_visit = 0;
        }

        if($test_mode){
            $test_mode = $test_mode == 2 ? 0 : $test_mode;
        }else{
            $test_mode = 0;
        }

        if($advance_expense){
            $advance_expense = $advance_expense == 2 ? 0 : $advance_expense;
        }else{
            $advance_expense = 0;
        }

        if($daily_activity){
            $daily_activity = $daily_activity == 2 ? 0 : $daily_activity;
        }else{
            $daily_activity = 0;
        }

        if($simple_report){
            $simple_report = $simple_report == 2 ? 0 : $simple_report;
        }else{
            $simple_report = 0;
        }

        if($fcr_report){
            $fcr_report = $fcr_report == 2 ? 0 : $fcr_report;
        }else{
            $fcr_report = 0;
        }

        if($layer_performance_report){
            $layer_performance_report = $layer_performance_report == 2 ? 0 : $layer_performance_report;
        }else{
            $layer_performance_report = 0;
        }

        if($layer_life_cycle_report){
            $layer_life_cycle_report = $layer_life_cycle_report == 2 ? 0 : $layer_life_cycle_report;
        }else{
            $layer_life_cycle_report = 0;
        }

        if($broiler_life_cycle_report){
            $broiler_life_cycle_report = $broiler_life_cycle_report == 2 ? 0 : $broiler_life_cycle_report;
        }else{
            $broiler_life_cycle_report = 0;
        }

        if($advance_tour_plan){
            $advance_tour_plan = $advance_tour_plan == 2 ? 0 : $advance_tour_plan;
        }else{
            $advance_tour_plan = 0;
        }

        if($actual_tour_plan){
            $actual_tour_plan = $actual_tour_plan == 2 ? 0 : $actual_tour_plan;
        }else{
            $actual_tour_plan = 0;
        }

        if($advance_dealer){
            $advance_dealer = $advance_dealer == 2 ? 0 : $advance_dealer;
        }else{
            $advance_dealer = 0;
        }

        if($sales_order){
            $sales_order = $sales_order == 2 ? 0 : $sales_order;
        }else{
            $sales_order = 0;
        }

        if($fcr){
            $fcr = $fcr == 2 ? 0 : $fcr;
        }else{
            $fcr = 0;
        }

        if($daily_report){
            $daily_report = $daily_report == 2 ? 0 : $daily_report;
        }else{
            $daily_report = 0;
        }

        if($monthly_sales_target){
            $monthly_sales_target = $monthly_sales_target == 2 ? 0 : $monthly_sales_target;
        }else{
            $monthly_sales_target = 0;
        }

        if($notification_update){
            $notification_update = $notification_update == 2 ? 0 : $notification_update;
        }else{
            $notification_update = 0;
        }

        if($outlet){
            $outlet = $outlet == 2 ? 0 : $outlet;
        }else{
            $outlet = 0;
        }

        if($conveyance_enable){
            $conveyance_enable = $conveyance_enable == 2 ? 0 : $conveyance_enable;
        }else{
            $conveyance_enable = 0;
        }

        $now = Carbon::now();
        $current_date = $now->todateString();
        $attendance_last = Attendance::where('enduser_id',$enduser['id'] )->where('client_id',$invitation['client_id'] )->Where('created_at', 'LIKE', '%' . $current_date . '%')->select('id','status')->get()->last();
        $attendance_message = 'NOT YET';
        $attendance_color = '#FFCA28';
        if ($attendance_last) {
            $attendance_message = $attendance_last['status'] == 1 ? "ENTERED" : "EXITED";
            $attendance_color = $attendance_last['status'] == 1 ? "#00a651" : "#ed1c24";
        }

        $today = Carbon\Carbon::now()->toDateString();

        $user_info = User::where('id',$user_id['user_id'])->select('name')->get()->last();
        $data = ['client_id' => $invitation['client_id'],
                'supervisor_id' => $supervisor_id == null ? -1 : $supervisor_id,
                'enduser_id' => $enduser['id'],
                'push_token' => '',
                'name' => $enduser['name'],
                'code' => $invitation_code,
                'client_name' => $user_info['name'],
                'phone_number' => $enduser['phone_number'],
                'today' => $today,
                'image_url' => $fixed->getStoragePath().$image['picture'],
                'online' => $mood,
                'button_message' => $mood == 1 ? "GO OFFLINE" : "GO ONLINE",
                'button_color' => $mood == 1 ? "#ed1c24" : "#00a651",
                'unread_message' => count($message_list),
                'user_visit' => $user_visit,
                'test_mode' => $test_mode,
                'distance_feet_update' => $client_setup['distance_feet_update'],
                'background_location_update' => $client_setup['background_location_update'],
                'advance_expense' => $advance_expense,
                'open_task' => 0,
                'dealer_point_limit' => $dealer_point_limit,
                'daily_activity' => $daily_activity,
                'simple_report' => $simple_report,
                'fcr_report' => $fcr_report,
                'layer_performance_report' => $layer_performance_report,
                'layer_life_cycle_report' => $layer_life_cycle_report,
                'broiler_life_cycle_report' => $broiler_life_cycle_report,
                'advance_tour_plan' => $advance_tour_plan,
                'actual_tour_plan' => $actual_tour_plan,
                'advance_dealer' => $advance_dealer,
                'sales_order' => $sales_order,
                'fcr_only' => $fcr,
                'daily_report' => $daily_report,
                'monthly_sales_target' => $monthly_sales_target,
                'notification_update' => $notification_update,
                'tp_type' => $client_setup['tp_type'],
                'outlet' => $outlet,
                'atp_type' => $client_setup['atp_type'],
                'conveyance_enable' => $conveyance_enable,
                'attendance' => $attendance_message,
                'attendance_color' => $attendance_color,
                'message' =>'You are connected with the tracking System.Please Add your Photo.'];
        return response()->json(['data' => $data], 200);
    }
}
