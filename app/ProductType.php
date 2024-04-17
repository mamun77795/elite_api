<?php

namespace App;

use function Aws\recursive_dir_iterator;
use Illuminate\Database\Eloquent\Model;
use Rashidul\RainDrops\Model\RainDropsSupport;
use App\Client;
use App\User;
use Auth;

class ProductType extends Model
{

    use RainDropsSupport;

    protected $table = 'product_type';

    protected $fillable = [
	    'client_id',
	    'title',
      'status',
	];

    protected $baseUrl = 'product_types';
    protected $entityName = 'PRODUCT TYPES';
    protected $entityNamePlural = 'PRODUCT TYPES';

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
      "title" => [
          "label" => "TITLE",
          "type" => "string",
          'index' => true
      ],
      "status" => [
          "label" => "STATUS",
          "type" => "string",
          'index' => true
      ],
	];
}
