<?php

namespace App;

use function Aws\recursive_dir_iterator;
use Illuminate\Database\Eloquent\Model;
use Rashidul\RainDrops\Model\RainDropsSupport;
use App\Company;
use Auth;

class UnusedInvitation extends Model
{

    use RainDropsSupport;

    protected $table = 'invitations';

    protected $fillable = [
	    'client_id',
      'supervisor_id',
	    'code',
      'phone_number',
      'status',
      "created_at",
	];

    protected $baseUrl = 'unused_invitations';
    protected $entityName = 'UNUSED INVITATIONS';
    protected $entityNamePlural = 'UNUSED INVITATIONS';

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
      "code" => [
          "label" => "CODE",
          "type" => "string",
          'index' => true
      ],
      "phone_number" => [
          "label" => "PHONE NUMBER",
          "type" => "string",
          'index' => true
      ],
      'status' => [
          'label' => 'STATUS',
            "type" => "select",
            'form' => false,
            'index' => true,
            "options" => [
                '1' => 'UNUSED',
                '2' => 'IN USE',
                '3' => 'BLOCK',
            ],
            'labels' => [
                '1' => 'CONNECTED',
                '2' => 'CANCELLED',
                '3' => 'REQUESTED',
            ],
        ],
        "created_at" => [
            "label" => "DATE TIME",
            "type" => "string",
            'index' => true
        ],
	];
}
