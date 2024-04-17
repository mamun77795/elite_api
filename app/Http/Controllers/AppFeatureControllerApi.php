<?php

namespace App\Http\Controllers;

use App\AppFeature;
use App\Client;
use App\EndUser;
use App\Constants;
use App\Agent;
use App\VisitedAgent;
use App\VisitedFarmer;
use App\VisitedSubAgent;
use App\FcrBeforeSale;
use App\MessageController;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Rashidul\RainDrops\Controllers\BaseController;
use Edujugon\PushNotification\PushNotification;
use App\Device;
use App\User;
use Auth;
use DB;

class AppFeatureControllerApi extends BaseController
{
  protected $modelClass = AppFeature::class;

  public function test_api(Request $request){

    $client_id = $request->client_id;

      $data = [
            'message' => 'testing',
      ];
      
    
    return response()->json(['data' => $data], 200);
  }
}
