<?php

namespace App;

use function Aws\recursive_dir_iterator;
use Illuminate\Database\Eloquent\Model;
use Rashidul\RainDrops\Model\RainDropsSupport;
use App\User;
use Auth;
class Supervisor extends Model
{

    use RainDropsSupport;

    protected $table = 'supervisors';

    protected $fillable = [
      'id',
      'user_id',
      'name',
	    'email',
	    'password',
	    'client_id',
      'role_supervisor_id',
	    'code',
      'phone_number'
	];

    protected $baseUrl = 'supervisors';
    protected $entityName = 'SUPERVISORS';
    protected $entityNamePlural = 'SUPERVISORS';

    public function getName()
    {

        $user = User::find($this->user_id);
        return $user->name;
    }
   public function getInvitationCode()
   {
       return $this->belongsTo(Invitation::class, 'invitation_id');
   }

   public function getRoleSupervisor(){
       return $this->belongsTo(RoleSupervisor::class, 'role_supervisor_id');
   }
   // public function getName()
   // {
   //
   //     $user = User::find($this->user_id);
   //     return $user->name;
   // }

   public function getClientName(){
       return $this->belongsTo(Client::class, 'client_id');
   }

    protected $fields = [
	    'id' => [
	        'label' => 'ID',
            "type" => "number",
            'form' => false,
            "show" => "exact",
            'index' => true
	    ],
	    'user_id' => [
	        'label' => 'USER ID ',
	        'type' => 'string',
            'show' => false,
            'form' => false,
	    ],
      'client_id' => [
          'label' => 'CLIENT NAME',
          "type" => "relation",
          "options" => ['getClientName', 'name'],
          "linkable" => true,
          'form' => false,
          'index' => true
      ],
	    'name' => [
	        'label' => 'SUPERVISOR NAME',
           "type" => "method",
           "method" => 'getName',
           "linkable" => true,
           'form' => false,
           'index' => true
	    ],
      'code' => [
	        'label' => 'CODE',
	        'type' => 'string',
           'form' => false,
            'index' => true
	    ],
      'phone_number' => [
	        'label' => 'PHONE NUMBER',
	        'type' => 'string',
            'index' => true
	    ],
      'role_supervisor_id' => [
          'label' => 'ROLE',
          "type" => "relation",
          "options" => ['getRoleSupervisor', 'designation'],
          "linkable" => true,
          'index' => true
      ],
	];
}
