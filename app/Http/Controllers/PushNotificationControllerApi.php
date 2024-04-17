<?php

namespace App\Http\Controllers;

use App\PushNotification;
use App\Client;
use App\User;
use App\Constants;
use App\Photo;
use Auth;
use Carbon;
use App\EndUser;
use Illuminate\Http\Request;
use DB;
use App\Device;

use Illuminate\Support\Facades\Storage;

class PushNotificationControllerApi extends Controller
{
  public function get_user_activity_list(Request $request)
  {
    $client_id = $request->client_id;
    $supervisor_id = $request->supervisor_id;
    $fixed = new Constants();
    $now = Carbon::now();
    $today = $now->todateString();

    if($supervisor_id == '-1' || $supervisor_id == 'null'){
      $push_notifications = PushNotification::where('client_id',$client_id )->
                                          Where('created_at', 'LIKE', '%' . $today . '%')->
                                          orderBy('created_at','desc')->
                                          select('enduser_id','message','created_at')->
                                          get()->
                                          toArray();

    }else{
      $push_notifications = PushNotification::where('client_id',$client_id )->
                                          where('supervisor_id',$supervisor_id )->
                                          Where('created_at', 'LIKE', '%' . $today . '%')->
                                          orderBy('created_at','desc')->
                                          select('enduser_id','message','created_at')->
                                          get()->
                                          toArray();
    }

    if (!$push_notifications) {
      $data = [
          'message' => 'NO USER ACTIVITY FOUND.',
      ];
      return response()->json(['data' => $data], 200);
    }

    $allData = [];
    foreach ($push_notifications as $push_notification) {

      $photo = Photo::where('enduser_id',$push_notification['enduser_id'])->
                                        orderBy('created_at','desc')->
                                        select('picture')->get()->last();

      $data = [
            'message' => $push_notification['message'],
            'image_url' => $photo['picture'] ? $fixed->getStoragePath().$photo['picture'] : '',
            'date_time' => $push_notification['created_at'],
            'short_time' => Carbon::parse($push_notification['created_at'])->diffForHumans()
      ];
      $allData[] = $data;
    }
    return response()->json(['data' => $allData], 200);
  }

}
