<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Rashidul\RainDrops\Model\RainDropsSupport;

class RoleSupervisor extends Model
{

    use RainDropsSupport;

    protected $table = 'role_supervisors';

    protected $fillable = [
	    'id',
	    'designation'
	];

    protected $baseUrl = 'role_supervisors';
    protected $entityName = 'Role Supervisor';
    protected $entityNamePlural = 'Role Supervisor';

    protected $fields = [
	    'id' => [
            'label' => 'ID',
            "type" => "number",
            'form' => false,
            "show" => "exact",
            'index' => true
	    ],
	    'designation' => [
	        'label' => 'DESIGNATION',
	        'type' => 'string',
            'index' => true
	    ]
	];



}
