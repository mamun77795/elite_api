<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Rashidul\RainDrops\Model\RainDropsSupport;
use App\EndUser;

class DeviceUser extends Model
{

    use RainDropsSupport;

    protected $table = 'devices';

    protected $guarded = [];

    protected $baseUrl = 'device_user';
    protected $entityName = 'DEVICE USERS';
    protected $entityNamePlural = 'DEVICE USERS';

    // public function getPushId(){
    //     return  $len = substr($this->push_id, 1,32);
    // }

    public function getCompanyName(){
        $companyId =  $this->company_id;
        $userId = Company::where('id',$companyId)->get()->first()['user_id'];
        $companyName = User::where('id',$userId)->get()->first()['name'];
        $link = url('users/'. $userId);
        return sprintf('<u><a href="%s">%s</a>', $link, $companyName);
        // return $companyName;
    }

    public function getClientName(){
        return $this->belongsTo(Client::class, 'client_id');
    }
    public function getSupervisorName(){
      $supervisor = Supervisor::where('id',$this->supervisor_id)->select('id','role_supervisor_id')->get()->last();
        if($supervisor){
            $user_id = Supervisor::where('id',$supervisor['id'])->get()->first()['user_id'];
            $supervisor_name = User::where('id',$user_id)->get()->first()['name'];
            $supervisor_role_name = RoleSupervisor::where('id',$supervisor['role_supervisor_id'])->get()->first()['designation'];
            $link = url('supervisors/'.$supervisor['id']);
            return sprintf('<u><a href="%s">%s</a>', $link, $supervisor_name.' ('.$supervisor_role_name.')');
        }else{
            return "ADMIN";
        }
    }
    public function getAppIdentifier(){
      $app_identifier = Device::where('client_id',$this->client_id)->where('enduser_id',$this->enduser_id)->select('id','app_identifier')->get()->last();
        if($app_identifier['app_identifier'] == 'com.smartmux.etracker.user'){
            return "USER";
        }else{
            return "ADMIN";
        }
    }
    public function getEndUserName(){
      $enduser = EndUser::where('id',$this->enduser_id)->select('id','name')->get()->last();
      $id = EndUser::where('id',$this->enduser_id)->get()->first()['id'];
      if(!$id == 0){
        $companyName = EndUser::where('id',$this->enduser_id)->get()->first()['name'];
        $userId = EndUser::where('id',$this->enduser_id)->get()->first()['id'];
        $link = url('endusers/'.$userId);
        return sprintf('<u><a href="%s">%s</a>', $link, $companyName);
        //return $companyName;
      }else{
        return "CLIENT";
        }
    }

    protected $fields = [
	    'id' => [
	        'label' => 'ID',
            "type" => "number",
            'form' => false,
            "show" => "exact",
            'index' => true
	    ],
      'client_id' => [
          'label' => 'CLIENT NAME',
          "type" => "relation",
          "options" => ['getClientName', 'name'],
          "linkable" => true,
          'form' => false,
          'index' => true
      ],
      'supervisor_id' => [
          'label' => 'SUPERVISOR NAME',
          "type" => "method",
          "method" => 'getSupervisorName',
          'form' => false,
          'index' => true
      ],
      'enduser_id' => [
          'label' => 'USER NAME',
          "type" => "method",
          "method" => 'getEndUserName',
          "form" => false,
          'index' => true
      ],
      // 'platform' => [
      //       'label' => 'PLATFORM',
      //       'type' => 'string',
      //       'index' => true
      // ],
      'app_identifier' => [
        'label' => 'DEVICE TYPE',
        "type" => "method",
        "method" => 'getAppIdentifier',
        "form" => false,
        'index' => true
      ],
      'device' => [
            'label' => 'DEVICE',
            'type' => 'string',
            'index' => true
      ],
      'app_version' => [
            'label' => 'APP VERSION',
            'type' => 'string',
            'index' => true
      ],
	    // 'udid' => [
	    //     'label' => 'UDID',
	    //     'type' => 'string',
      //       'index' => true
	    // ],
	    'push_id' => [
	        'label' => 'PUSH ID',
	        'type' => 'string',
	    ],
      'imei' => [
	        'label' => 'IMEI',
	        'type' => 'string',
          'index' => true
	    ],
	];
}
