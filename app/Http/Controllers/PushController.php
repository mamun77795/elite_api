<?php

namespace App\Http\Controllers;
use Edujugon\PushNotification\PushNotification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Device;
use App\EndUser;
use App\Client;
use Carbon\Carbon;
use DB;
use App\Constants;
use App\ClientServerKey;

class PushController extends Controller
{

  public static function send_push_to_user($client_id,$deviceToken,$title,$message,$enduser_id)
  {
    $clientServerKey = ClientServerKey::where('client_id',$client_id)->
                                      orderBy('created_at','desc')->
                                      select('server_key')->get()->first();
    $serverApiPushKey = NULL;

    $now = Carbon::now();

    $current_date = $now->toDateTimeString();

    $EndUser = EndUser::where('id',$enduser_id)->
                                      orderBy('created_at','desc')->
                                      select('supervisor_id')->get()->first();


    DB::table('push_notifications')->
        insert(['client_id' => $client_id,
                'enduser_id' => $enduser_id,
                'supervisor_id' => $EndUser['supervisor_id'],
                'message' => $message,
                'created_at'=> $current_date]);

    if($clientServerKey){
        $serverApiPushKey = $clientServerKey['server_key'];
    }

    $push = new PushNotification('fcm');
    $fixed = new Constants();

    $push->setMessage([
              'notification' => [
              'title'=>$title,
              'body'=> $message,
              'sound' => 'default'
            ],

    'data' => ['push_title' => $title,
                'push_message' => $message,
                'extraPayLoad1' => 'value1',
                'extraPayLoad2' => 'value2'
                ]
            ])
            ->setApiKey($serverApiPushKey)
            ->setDevicesToken([$deviceToken])
            ->send();
            return response()->json(['data'=>$push->getFeedback()],200);
  }

  public static function send_push_to_admin($deviceToken,$message)
  {
        $push = new PushNotification;
        $fixed = new Constants();
        $push->setMessage([
                'notification' => [
                    'title'=>'eTracker',
                    'body'=> $message,
                    'sound' => 'default'
                ],
            'data' => [
                'push_title' => 'eTracker User Update',
                'push_message' => $message,
                'extraPayLoad1' => 'value1',
                'extraPayLoad2' => 'value2'
                ]
            ])
            ->setApiKey($fixed->getPushApiKeyAdmin())
            ->setDevicesToken([$deviceToken])
            ->send();
            return response()->json(['data'=>$push->getFeedback()],200);
  }

  public static function push_send_to_parent($deviceToken,$title,$message)
  {
    $push = new PushNotification('fcm');
    $fixed = new Constants();

    $serverApiPushKey = 'AAAAIBH3HP4:APA91bE8RKT-raB5XxOrxdIAPXmfXHMcjGPfAVuQQvuojS3HaDrhwDU-3fEjlo9KRWbis39SQuqLSpOG0Dc3br7633LokeaCUdh4QVJZN07TtV3A8GKp_zUFqQoHpeyA6fW31bNflNTa';

    $push->setMessage([
              'notification' => [
              'title'=>$title,
              'body'=> $message,
              'sound' => 'default'
            ],

    'data' => ['push_title' => $title,
                'push_message' => $message,
                'extraPayLoad1' => 'value1',
                'extraPayLoad2' => 'value2'
                ]
            ])
            ->setApiKey($serverApiPushKey)
            ->setDevicesToken([$deviceToken])
            ->send();
            return response()->json(['data'=>$push->getFeedback()],200);
  }

  public static function push_send_to_all_parent($client_id,$deviceToken,$title,$message,$enduser_id)
  {
    $clientServerKey = ClientServerKey::where('client_id',$client_id)->
                                      orderBy('created_at','desc')->
                                      select('server_key')->get()->first();
    $serverApiPushKey = NULL;

    $now = Carbon::now();

    $current_date = $now->toDateTimeString();

    $EndUser = EndUser::where('id',$enduser_id)->
                                      orderBy('created_at','desc')->
                                      select('supervisor_id')->get()->first();


    DB::table('push_notifications')->
        insert(['client_id' => $client_id,
                'enduser_id' => $enduser_id,
                'supervisor_id' => $EndUser['supervisor_id'],
                'message' => $message,
                'created_at'=> $current_date]);

    if($clientServerKey){
        $serverApiPushKey = $clientServerKey['server_key'];
    }

    $push = new PushNotification('fcm');
    $fixed = new Constants();

    $push->setMessage([
              'notification' => [
              'title'=>$title,
              'body'=> $message,
              'sound' => 'default'
            ],

    'data' => ['push_title' => $title,
                'push_message' => $message,
                'extraPayLoad1' => 'value1',
                'extraPayLoad2' => 'value2'
                ]
            ])
            ->setApiKey($serverApiPushKey)
            ->setDevicesToken([$deviceToken])
            ->send();
            return response()->json(['data'=>$push->getFeedback()],200);
  }

  public static function test_push(Request $request)
  {
    $server_key = $request->server_key;
    $token = $request->token;
    $title = $request->title;
    $message = $request->message;

    //dd($request);
    $push = new PushNotification('fcm');
    $fixed = new Constants();

    $push->setMessage([
              'notification' => [
              'title'=>$title,
              'body'=> $message,
              'sound' => 'default'
            ],

    'data' => ['push_title' => $title,
                'push_message' => $message,
                'extraPayLoad1' => 'value1',
                'extraPayLoad2' => 'value2'
                ]
            ])
            ->setApiKey($server_key)
            ->setDevicesToken([$token])
            ->send();
            return response()->json(['data'=>$push->getFeedback()],200);
  }

}
