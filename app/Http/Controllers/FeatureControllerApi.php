<?php

namespace App\Http\Controllers;

use App\Client;
use App\Constants;
use App\ActivitySuggestion;
use App\Feature;
use App\Attendance;
use App\EndUser;
use App\Photo;
use App\CompanyProperty;
use App\MessageController;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Rashidul\RainDrops\Controllers\BaseController;
use Auth;
use DB;

class FeatureControllerApi extends BaseController
{
  protected $modelClass = Feature::class;

  public function get_user_full_feature(Request $request){

    $client_id  = $request->client_id;
    $enduser_id = $request->enduser_id;
    $type       = $request->type;

    $now = Carbon::now();
    $today = $now->toDateString();

    $app_feature_list = Feature::where('client_id',$client_id )->
                                 where('type',$type)->
                                 where('visible','1')->
                                 orderBy('feature_serial', 'ASC')->
                                 select('feature_title',
                                        'feature_image',
                                        'feature_activity',
                                        'visible',
                                        'enable')->get()->toArray();

    if(!$app_feature_list){
      $data = [
          'error' => 'NO APP FEATURE AVAILABLE.Contact Admin.',
      ];
      return response()->json(['data' => $data], 200);
    }

    $features = [];
    foreach ($app_feature_list as $app_feature) {
      $data = [
            'title' => $app_feature['feature_title'],
            'image' => $app_feature['feature_image'],
            'activity' => $app_feature['feature_activity'],
            'enable' => $app_feature['enable']
      ];
      $features[] = $data;
    }

    if ($type == 'report') {
        return response()->json(['data' => ['features' => $features]], 200);
    }

    // SUGGESTIONS INFORMATION
    $suggestions = [];
    $suggestion_list = ActivitySuggestion::where('client_id',$client_id )->
                                          select('id','suggestion')->
                                          get()->
                                          toArray();
      foreach ($suggestion_list as $suggestion) {
        $data = [
          'id' => $suggestion['id'],
          'suggestion' => $suggestion['suggestion']
          ];
        $suggestions[] = $data;
      }

      // ATTENDANCE INFORMATION
      $attendance_last = Attendance::where('client_id',$client_id )->
                                     where('enduser_id',$enduser_id )->
                                     where('created_at', 'like',$today . '%')->
                                     select('status','created_at','latitude','longitude')->get()->last();
      $attendance = NULL;
      if(!$attendance_last){
        $attendance = [
              'time' => '',
              'status' => 'NOT YET',
              'color' => '#F7A627'
            ];
      }else{
        $attendance_time = $attendance_last['created_at'];
        $attendance_status = $attendance_last['status'];
        $attendance_latitude = $attendance_last['latitude'];
        $attendance_longitude = $attendance_last['longitude'];
        $attendance = [
              'time' => $attendance_time->format('g:i A'),
              'status' => $attendance_status == '1' ? 'ENTERED' : 'EXITED',
              'color' => $attendance_status == '1' ? '#6CBD6E' : '#fc3e3b',
              'latitude' => $attendance_latitude,
              'longitude' => $attendance_longitude
      ];
     }

     // USER INFORMATION
    $user_last = EndUser::where('client_id',$client_id )->
                                   where('id',$enduser_id )->
                                   select('name','phone_number')->get()->last();
    $photo = Photo::where('enduser_id',$enduser_id)->
                    select('picture')->get()->last();
    $user = NULL;
    if(!$user_last){
      $user = [
            'name' => '',
            'phone' => '',
            'profile_url' => ''
          ];
    }else{
      $attendance_time = $attendance_last['created_at'];
      $attendance_status = $attendance_last['status'];
      $attendance_latitude = $attendance_last['latitude'];
      $attendance_longitude = $attendance_last['longitude'];
      $fixed = new Constants();
      $user = [
            'name' => $user_last['name'],
            'phone' => $user_last['phone_number'],
            'profile_url' => ($photo) ? $fixed->getStoragePath().$photo['picture'] : ''
          ];
    }

    // Optional Settings
    $company_props = CompanyProperty::where('client_id',$client_id )->
                                          select('type','value')->
                                          get()->
                                          toArray();

    return response()->json(['data' => ['features' => $features,
                                        'suggestions' => $suggestions,
                                        'attendance' => $attendance,
                                        'user' => $user,
                                        'company_props' => $company_props]], 200);
  }

  public function get_user_updated_feature(Request $request){

    $client_id  = $request->client_id;
    $enduser_id = $request->enduser_id;

    $now = Carbon::now();
    $today = $now->toDateString();

      // ATTENDANCE INFORMATION
      $attendance_last = Attendance::where('client_id',$client_id )->
                                     where('enduser_id',$enduser_id )->
                                     where('created_at', 'like',$today . '%')->
                                     select('status','created_at','latitude','longitude')->get()->last();
      $attendance = NULL;
      if(!$attendance_last){
        $attendance = [
              'time' => '',
              'status' => 'NOT YET',
              'color' => '#F7A627'
            ];
      }else{
        $attendance_time = $attendance_last['created_at'];
        $attendance_status = $attendance_last['status'];
        $attendance_latitude = $attendance_last['latitude'];
        $attendance_longitude = $attendance_last['longitude'];
        $attendance = [
              'time' => $attendance_time->format('g:i A'),
              'status' => $attendance_status == '1' ? 'ENTERED' : 'EXITED',
              'color' => $attendance_status == '1' ? '#6CBD6E' : '#fc3e3b',
              'latitude' => $attendance_latitude,
              'longitude' => $attendance_longitude
      ];
     }

     // USER INFORMATION
    $user_last = EndUser::where('client_id',$client_id )->
                                   where('id',$enduser_id )->
                                   select('name','phone_number')->get()->last();
    $photo = Photo::where('enduser_id',$enduser_id)->
                    select('picture')->get()->last();
    $user = NULL;
    if(!$user_last){
      $user = [
            'name' => '',
            'phone' => '',
            'profile_url' => ''
          ];
    }else{
      $attendance_time = $attendance_last['created_at'];
      $attendance_status = $attendance_last['status'];
      $attendance_latitude = $attendance_last['latitude'];
      $attendance_longitude = $attendance_last['longitude'];
      $fixed = new Constants();
      $user = [
            'name' => $user_last['name'],
            'phone' => $user_last['phone_number'],
            'profile_url' => ($photo) ? $fixed->getStoragePath().$photo['picture'] : ''
    ];
    }
    return response()->json(['data' => ['attendance' => $attendance,
                                        'user' => $user]], 200);
  }
}
