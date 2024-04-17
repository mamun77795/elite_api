<?php

namespace App;

use function Aws\recursive_dir_iterator;
use Illuminate\Database\Eloquent\Model;
use Rashidul\RainDrops\Model\RainDropsSupport;
use App\Client;
use App\User;
use Auth;

class Product extends Model
{

    use RainDropsSupport;

    protected $table = 'products';

    protected $fillable = [
      'client_id',
      'name',
	    'price',
      'product_type_id',
      'description',
	];

    protected $baseUrl = 'products';
    protected $entityName = 'PRODUCTS';
    protected $entityNamePlural = 'PRODUCTS';

    public function getClientName(){
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function getProducttype(){
        $companyId =  $this->product_type_id;
        $userId = ProductType::where('id',$companyId)->get()->first()['title'];
        return $userId;
        // return $companyName;
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
      'product_type_id' => [
        'label' => 'PRODUCT TYPE',
        "type" => "method",
        "method" => 'getProducttype',
        "form" => false,
        'index' => true
      ],
      "name" => [
          "label" => "NAME",
          "type" => "string",
          'index' => true
      ],
      "price" => [
          "label" => "PRICE",
          "type" => "string",
          'index' => true
      ],
      "description" => [
          "label" => "DESCRIPTION",
          "type" => "string",
          'index' => true
      ],
	];
}
