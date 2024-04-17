<?php

namespace App\Http\Controllers;

use App\Client;
use App\EndUser;
use App\Payment;
use App\Invitation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\MessageController;
use App\User;
use App\Log;
use App\VersionNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use DB;
class InvitationControllerApi extends Controller
{
    public function invite(Request $request)
    {
        $client_id = $request->client_id;
        $supervisor_id = $request->supervisor_id;
        $phone_number = $request->phone_number;

        $client = Client::where('id',$client_id)->select('user_id')->get()->last();
        $user = User::where('id',$client['user_id'])->select('name')->get()->last();

        $version_notification = VersionNotification::where('app_name','USER APP' )->
                    where('client_id', $client_id)->
                    select('apk_url')->
                    get()->
                    last();

        $app_url = 'http://etrackerbd.com/apks/UserApp-12.apk';
        if($version_notification){
           $app_url = $version_notification['apk_url'];
        }
        $invitation = Invitation::where('phone_number',$phone_number )->select('status','code')->get()->last();

        if($invitation){
          $invitation_status = $invitation['status'];
          if($invitation_status == 2){
            $data = ['error' => '['.$phone_number.']'.' Already in Use.'];
            return response()->json(['data' => $data], 200);
          }else{
            $data = ['message' => $user['name']. ' wants to Track you.Please download App '. $app_url .' and use inivitation code '.$invitation['code']];
            return response()->json(['data' => $data], 200);
          }
        }


        $count = strlen($phone_number);

        $prifix = substr($phone_number,0,4);
        //dd($count.' - '.$prifix);

        if ($prifix == "a880"){

          if ($count != 14){
            $data = [
                    'error' => '['.$phone_number.']'.' Invalid Number.[size.14]'];
            return response()->json(['data' => $data], 200);
          }

          $number = substr($phone_number, 3,14);
          //dd($number);

          if(!is_numeric($number)){
              $data = [
                      'error' => '['.$phone_number.']'.' Invalid Number.[non digit]'];
              return response()->json(['data' => $data], 200);
            }
        }else{

        // $prifix = substr($phone_number,0,2);
        // if ($prifix != "01"){
        //   $data = [
        //           'error' => '['.$phone_number.']'.' Invalid Number.[not 01]'];
        //   return response()->json(['data' => $data], 200);
        // }


        // if ($count != 20){
        //     $data = [
        //             'error' => '['.$phone_number.']'.' Invalid Number.[size.15]'];
        //     return response()->json(['data' => $data], 200);
        // }

          if(!is_numeric($phone_number)){
              $data = [
                      'error' => '['.$phone_number.']'.' Invalid Number.[non digit.11]'];
              return response()->json(['data' => $data], 200);
          }
        }


        $client = Client::where('id',$client_id)->select('user_id')->get()->first();
        $enduser_list = EndUser::where('client_id',$client_id )->where('status',1 )->select('id','invitation_id','phone_number','name')->get()->toArray();
      //  dd(count($enduser_list));
        $payment = Payment::where('user_id',$client['user_id'])->select('user_limit')->get()->first();
        $remaining = intval($payment['user_limit']) - count($enduser_list);


        if($remaining > 0){
          $code = Invitation::where('phone_number',$phone_number )->select('code')->get()->last();
          if($code){
            $data = [
                    'error' => '['.$phone_number.']'.' Already in Use'];
            return response()->json(['data' => $data], 200);
          }

          $seed = str_split('ABCDEFGHIJKLMNOPQRSTUVWXYZ'
                   .'0123456789'); // and any other characters
          shuffle($seed);
          $invitation_code = '';
          foreach (array_rand($seed, 6) as $k) $invitation_code .= $seed[$k];

          if($supervisor_id && $supervisor_id != '-1'){
            DB::table('invitations')->insert(['client_id' => $client_id, 'supervisor_id' => $supervisor_id, 'phone_number' => $phone_number, 'code' => $invitation_code]);
          }else{
            DB::table('invitations')->insert(['client_id' => $client_id, 'phone_number' => $phone_number, 'code' => $invitation_code]);
          }

          $data = ['message' => $user['name']. ' invites you to join with the following app.Please download App '. $app_url .' and use inivitation code '.$invitation_code];
          return response()->json(['data' => $data], 200);
        }else{
          $data = [
                  'error' => 'User limit exceed.You used '.count($enduser_list).' users.To access more users,please contact eTracker Admin.'];
          return response()->json(['data' => $data], 200);
        }

    }

    public function resend_invitation(Request $request)
    {
        //dd('resend_invitation');
        $client_id = $request->client_id;
        $supervisor_id = $request->supervisor_id;
        $phone_number = $request->phone_number;

        $client = Client::where('id',$client_id)->select('user_id')->get()->last();
        $user = User::where('id',$client['user_id'])->select('name')->get()->last();

        $invitation = Invitation::where('phone_number',$phone_number )->select('status','code')->get()->last();

        if($client_id == 18){
          if($invitation){
            $invitation_status = $invitation['status'];
            if($invitation_status == 2){
              $data = ['error' => '['.$phone_number.']'.' Already in Use.'];
              return response()->json(['data' => $data], 200);
            }else{
              $data = ['message' => $user['name']. ' wants to Track you.Please download App http://bit.ly/2WbYr81 and use inivitation code '.$invitation['code']];
              return response()->json(['data' => $data], 200);
            }
          }
        }else{
          if($invitation){
            $invitation_status = $invitation['status'];
            if($invitation_status == 2){
              $data = ['error' => '['.$phone_number.']'.' Already in Use.'];
              return response()->json(['data' => $data], 200);
            }else{
              $data = ['message' => $user['name']. ' invites you to join with the following app.Please download App https://bit.ly/32JZEUM and use inivitation code '.$invitation['code']];
              return response()->json(['data' => $data], 200);
            }
          }
        }
    }

    public function remove_invitation(Request $request)
    {
      $invitation_id = $request->invitation_id;

      $invitaion = Invitation::where('id',$invitation_id )
                              ->select('status')->get()->last();
      if ($invitaion) {
        $status = $invitaion['status'];
        if ($status == 2) {
          $data = ['error' => 'Invitation Already in Use'];
          return response()->json(['data' => $data], 200);
        }else{
          // Delete the Invitation
          //$row = Invitation::where('id',$invitation_id)->delete();
          DB::table('invitations')->where('id', $invitation_id)->delete();
          $data = ['message' => 'Invitation Removed'];
          return response()->json(['data' => $data], 200);

        }
      }
    }

    public function get_invitation_list(Request $request)
    {
        $client_id = $request->client_id;
        $supervisor_id = $request->supervisor_id;

        $invitaions = [];
        if($supervisor_id && $supervisor_id != '-1'){
          $invitaions = Invitation::where('client_id',$client_id )->where('supervisor_id',$supervisor_id )->orderBy('status', 'asc')->select('id','client_id','supervisor_id','code','phone_number','status','created_at')->get()->toArray();
        }else {
          $invitaions = Invitation::where('client_id',$client_id )->orderBy('status', 'asc')->select('id','client_id','supervisor_id','code','phone_number','status','created_at')->get()->toArray();
        }

        if (count($invitaions) == 0) {
          $data = ['message' => 'NO INVITATIONS FOUND.'];
          return response()->json(['data' => $data], 200);
        }

        $allData = [];

        foreach ($invitaions as $invitaion) {
          $user_name = EndUser::where('invitation_id',$invitaion['id'] )->
                                    select('name')->get()->first();
          $data = [
                'id' => $invitaion['id'],
                'client_id' => $invitaion['client_id'],
                'supervisor_id' => $invitaion['supervisor_id'],
                'code' => $invitaion['code'],
                'phone_number' => $invitaion['phone_number'],
                'status' => $invitaion['status'],
                'status_text' => $invitaion['status'] == 2 ? 'IN USE' : 'UNUSED',
                'status_color' => $invitaion['status'] == 2 ? '#ed1c24' : '#00a651',
                'name' => $invitaion['status'] == 2 ? $user_name['name'] : '',
                'created_at' => $invitaion['created_at']
          ];

          $allData[] = $data;
        }

        return response()->json(['count' => count($invitaions), 'data' => $allData], 200);
    }

    public function get_log_info(Request $request)
    {
        $invitation_code = $request->invitation_code;

        $invitation = Invitation::where('code',$invitation_code )->select('id','test_mood')->get()->last();

        $enduser = EndUser::where('invitation_id',$invitation['id'] )->select('id')->get()->first();
        //dd($enduser['id']);

        if($invitation['test_mood'] == 1){
          $log_details = Log::where('enduser_id',$enduser['id'] )->select('id','type','details')->get()->first();

          $data = [
                'id' => $log_details['id'],
                'type' => $log_details['type'],
                'details' => $log_details['details']
          ];

          return response()->json(['data' => $data], 200);

        }

    }
}
