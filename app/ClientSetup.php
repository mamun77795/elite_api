<?php

namespace App;

use function Aws\recursive_dir_iterator;
use Illuminate\Database\Eloquent\Model;
use Rashidul\RainDrops\Model\RainDropsSupport;
use App\User;
use Auth;
class ClientSetup extends Model
{

    use RainDropsSupport;

    protected $table = 'client_setups';

    protected $fillable = [
      'id',
	    'user_id',
	    'user_activation',
      'user_visit',
      'sound',
      'test_mode',
      'distance_feet_update',
      'refresh_duration',
      'background_location_update',
      'version_check',
      'single_track_report',
      'advance_expense',
      'monthly_report_download'

	];
  public function getClientName(){
      return $this->belongsTo(User::class, 'user_id');
  }

  public function getDuration(){
      $duration = ClientSetup::where('user_id',$this->user_id)->get()->first()['refresh_duration'];
      return $duration. ' secs';
  }

  public function getDistance(){
      $distance = ClientSetup::where('user_id',$this->user_id)->get()->first()['distance_feet_update'];
      return $distance. ' feet';
  }

  public function getBackgroundLocationUpdate(){
      $duration = ClientSetup::where('user_id',$this->user_id)->get()->first()['background_location_update'];
      return $duration. ' secs';
  }

    protected $baseUrl = 'client_setups';
    protected $entityName = 'CLIENT SETUPS';
    protected $entityNamePlural = 'CLIENT SETUPS';


    protected $fields = [
    "id" => [
        "label" => "ID",
        "type" => "number",
        'form' => false,
        'show' => 'exact',
        'index' => true
    ],
    'user_id' => [
        'label' => 'CLIENT NAME',
        "type" => "relation",
        "options" => ['getClientName', 'name'],
        "linkable" => true,
        'index' => true
    ],
    "user_activation" => [
        "label" => "USER ACTIVATION",
        "type" => "select",
        'index' => true,
        "options" => [
          '1' => 'ACTIVE',
          '2' => 'INACTIVE',
        ],
        'labels' => [
          '1' => 'CONNECTED',
          '2' => 'CANCELLED',
        ],
    ],
    "user_visit" => [
        "label" => "USER VISIT",
        "type" => "select",
        'index' => true,
        "options" => [
            '1' => 'ACTIVE',
            '2' => 'INACTIVE',
        ],
        'labels' => [
          '1' => 'CONNECTED',
          '2' => 'CANCELLED',
        ],
    ],
    "test_mode" => [
        "label" => "TEST MOOD",
        "type" => "select",
        'index' => true,
        "options" => [
            '1' => 'ACTIVE',
            '2' => 'INACTIVE',
        ],
        'labels' => [
          '1' => 'CONNECTED',
          '2' => 'CANCELLED',
        ],
    ],
    "refresh_duration" => [
        "label" => "REFRESH DURATION",
        "type" => "method",
        "method" => 'getDuration',
        'index' => true
    ],
    "distance_feet_update" => [
        "label" => "DISTANCE FEET",
        "type" => "method",
        "method" => 'getDistance',
        'index' => true
    ],
    "version_check" => [
        "label" => "VERSION CHECK",
        "type" => "select",
        'index' => true,
        "options" => [
            '1' => 'ACTIVE',
            '2' => 'INACTIVE',
        ],
        'labels' => [
          '1' => 'CONNECTED',
          '2' => 'CANCELLED',
        ],
    ],
    "background_location_update" => [
        "label" => "LOCATION UPDATE",
        "type" => "method",
        "method" => 'getBackgroundLocationUpdate',
        'index' => true
    ],
    "single_track_report" => [
        "label" => "SINGLE TRACK",
        "type" => "select",
        'index' => true,
        "options" => [
            '1' => 'ACTIVE',
            '2' => 'INACTIVE',
        ],
        'labels' => [
          '1' => 'CONNECTED',
          '2' => 'CANCELLED',
        ],
    ],
    "advance_expense" => [
        "label" => "ADVANCE EXPENSE",
        "type" => "select",
        'index' => true,
        "options" => [
            '1' => 'ACTIVE',
            '2' => 'INACTIVE',
        ],
        'labels' => [
          '1' => 'CONNECTED',
          '2' => 'CANCELLED',
        ],
    ],
    "monthly_report_download" => [
        "label" => "MONTHLY REPORT DOWNLOAD",
        "type" => "select",
        'index' => true,
        "options" => [
            '1' => 'ACTIVE',
            '2' => 'INACTIVE',
        ],
        'labels' => [
          '1' => 'CONNECTED',
          '2' => 'CANCELLED',
        ],
    ],
    "sound" => [
        "label" => "SOUND",
        "type" => "select",
        'index' => true,
        "options" => [
            '1' => 'ACTIVE',
            '2' => 'INACTIVE',
        ],
        'labels' => [
          '1' => 'CONNECTED',
          '2' => 'CANCELLED',
        ],
    ],

	];
}
