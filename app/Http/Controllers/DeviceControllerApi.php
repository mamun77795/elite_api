<?php

namespace App\Http\Controllers;

use App\Device;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use DB;
use Illuminate\Support\Facades\Crypt;
class DeviceControllerApi extends Controller
{
    public function store(Request $request){
        $clientId = $request->get('client_id');
        $enduserId = $request->get('enduser_id');
        $supervisorId = $request->get('supervisor_id');
        $platform = $request->get('platform');
        $device = $request->get('device');
        $app_version = $request->get('app_version');
        $app_identifier = $request->get('app_identifier');
        $udid = $request->get('udid');
        $push_id = $request->get('push_id');
        $imei = $request->get('imei');

        if($enduserId == -1){
          $exist = Device::whereClient_id($clientId)->whereSupervisor_id($supervisorId)->whereEnduser_id($enduserId)->
                    where('platform',$platform )->where('device', $device)->
                    where('app_version',$app_version )->where('app_identifier', $app_identifier)->
                    where('udid',$udid )->where('push_id', $push_id)->where('imei', $imei)->select('id')->get()->first();
          if($exist){
            DB::table('devices')->where(['client_id' => $clientId])->where(['enduser_id' => $enduserId])->where(['supervisor_id' => $supervisorId])->update(['client_id' => $clientId,
            'enduser_id' => $enduserId,
            'supervisor_id' => $supervisorId,
            'platform' => $platform,
            'device' => $device,
            'app_version' => $app_version,
            'app_identifier' => $app_identifier,
            'udid' => $udid,
            'imei' => $imei,
            'push_id'=> $push_id]);
            $data = [
                'message' => 'Data Updated',
            ];
            return response()->json(['data' => $data], 200);
          }
          Device::insert([
              'client_id' => $clientId,
              'supervisor_id' => $supervisorId,
              'enduser_id' => $enduserId,
              'platform' => $platform,
              'device' => $device,
              'app_version' => $app_version,
              'app_identifier' => $app_identifier,
              'udid' => $udid,
              'imei' => $imei,
              'push_id' => $push_id,
          ]);
          $data = [
              'message' => 'Successfully Inserted',
          ];
          return response()->json(['data' => $data], 200);
        }else{
          $exist = Device::whereClient_id($clientId)->whereSupervisor_id($supervisorId)->whereEnduser_id($enduserId)->select('id')->get()->first();
          //dd($exist);

          if($exist){

            DB::table('devices')->where(['client_id' => $clientId])->where(['enduser_id' => $enduserId])->where(['supervisor_id' => $supervisorId])->update(['client_id' => $clientId,
            'enduser_id' => $enduserId,
            'supervisor_id' => $supervisorId,
            'platform' => $platform,
            'device' => $device,
            'app_version' => $app_version,
            'app_identifier' => $app_identifier,
            'udid' => $udid,
            'imei' => $imei,
            'push_id'=> $push_id]);

            $data = [
                'message' => 'Data Updated',
            ];
            return response()->json(['data' => $data], 200);
          }
          Device::insert([
              'client_id' => $clientId,
              'supervisor_id' => $supervisorId,
              'enduser_id' => $enduserId,
              'platform' => $platform,
              'device' => $device,
              'app_version' => $app_version,
              'app_identifier' => $app_identifier,
              'udid' => $udid,
              'imei' => $imei,
              'push_id' => $push_id,
          ]);
          $data = [
              'message' => 'Successfully Inserted',
          ];
          return response()->json(['data' => $data], 200);
        }

    }

    public function update_app_version(Request $request){
      $clientId = $request->get('client_id');
      $enduserId = $request->get('enduser_id');
      $app_version = $request->get('app_version');

      $appversion = Device::where('client_id',$clientId )->where('enduser_id',$enduserId )->select('app_version')->get()->first();
      if($appversion){
        DB::table('devices')->where('client_id',$clientId )->where('enduser_id',$enduserId )->update(['app_version' => $app_version]);
        $data = [
            'message' => 'Successfully Updated',
        ];
        return response()->json(['data' => $data], 200);
      }
    }

    public function push_token_update(Request $request)
    {
      $enduser_id = $request->enduser_id;
      $push_token = $request->push_token;

      DB::table('devices')->where('enduser_id', $enduser_id)->
                                    update(['push_id' => $push_token]);
       $data = ['message' => 'Push Token Updated.'];
       return response()->json(['data' => $data], 200);
    }
}
