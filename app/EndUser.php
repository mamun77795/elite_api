<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Rashidul\RainDrops\Model\RainDropsSupport;
use App\User;

class EndUser extends Model
{
    use RainDropsSupport;

    protected $table = 'endusers';

    protected $fillable = [
	    'invitation_id',
      'supervisor_id',
	    'client_id',
	    'name',
      'status',
	    'phone_number'
    ];

    protected $baseUrl = 'endusers';
    protected $entityName = 'END USERS';
    protected $entityNamePlural = 'END USERS';
    public function getInvitationCode()
    {
        return $this->belongsTo(Invitation::class, 'invitation_id');
    }

    public function getPicture(){
        $user = User::find($this->user_id);
        return 'app/public/'.$user->picture;
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
      "name" => [
          "label" => "NAME",
          "type" => "string",
          'index' => true
      ],
      'phone_number' => [
          'label' => 'PHONE NUMBER',
          'type' => 'string',
          'index' => true
      ],
      'invitation_id' => [
          'label' => 'INVITATION CODE',
          "type" => "relation",
          "options" => ['getInvitationCode', 'code'],
          "linkable" => true,
          'form' => false,
          'index' => true
      ],
      'status' => [
	        'label' => 'STATUS',
            "type" => "select",
            'form' => true,
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
