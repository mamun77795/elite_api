<?php

namespace App;

use function Aws\recursive_dir_iterator;
use Illuminate\Database\Eloquent\Model;
use Rashidul\RainDrops\Model\RainDropsSupport;
use App\Client;
use App\User;
use Auth;

class VersionNotification extends Model
{

    use RainDropsSupport;

    protected $table = 'version_notifications';

    protected $fillable = [
	    'app_name',
      'client_id',
	    'version',
      'created_at',
	];

    protected $baseUrl = 'version_notifications';
    protected $entityName = 'VERSION NOTIFICATIONS';
    protected $entityNamePlural = 'VERSION NOTIFICATIONS';

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
      'client_id' => [
          'label' => 'CLIENT NAME',
          "type" => "relation",
          "options" => ['getClientName', 'name'],
          "linkable" => true,
          'form' => false,
          'index' => true
      ],
      "app_name" => [
          "label" => "APP NAME",
          "type" => "select",
          'index' => true,
          "options" => [
              'USER APP' => 'USER APP',
              'ADMIN APP' => 'ADMIN APP',
          ],
          'labels' => [
              'ADMIN APP' => 'CONNECTED',
              'USER APP' => 'CANCELLED',
          ],
      ],
      "version" => [
          "label" => "VERSIONS",
          "type" => "string",
          'index' => true
      ],
      "created_at" => [
          "label" => "DATE TIME",
          "type" => "string",
          'form' => false,
          'index' => true
      ],
	];
}
