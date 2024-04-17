<?php

namespace App;

use function Aws\recursive_dir_iterator;
use Illuminate\Database\Eloquent\Model;
use Rashidul\RainDrops\Model\RainDropsSupport;
use App\Client;
use App\User;
use Auth;

class Log extends Model
{

    use RainDropsSupport;

    protected $table = 'logs';

    protected $fillable = [
      'client_id',
      'enduser_id',
      'advance_dealer_id',
	    'product_id',
      'advance_payment_id',
	    'quantity',
      'status',
	];

    protected $baseUrl = 'logs';
    protected $entityName = 'LOGS';
    protected $entityNamePlural = 'LOGS';

    public function getClientName(){
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function getEndUserName(){
        return $this->belongsTo(EndUser::class, 'enduser_id');
    }

    public function getProductName(){
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function getDealerName(){
        $companyId =  $this->advance_dealer_id;
        $userId = AdvanceDealer::where('id',$companyId)->get()->first()['name'];
        return $userId;
        // return $companyName;
    }

    public function getProductPrice(){
        $companyId =  $this->product_id;
        $userId = Product::where('id',$companyId)->get()->first()['price'];
        return $userId;
        // return $companyName;
    }

    public function getDealerCode(){
        $companyId =  $this->advance_dealer_id;
        $userId = AdvanceDealer::where('id',$companyId)->get()->first()['code'];
        return $userId;
        // return $companyName;
    }

    public function get_paymenttile(){
        $companyId =  $this->advance_payment_id;
        $userId = AdvancePayment::where('id',$companyId)->get()->first()['payment_type_id'];
        $title = PaymentType::where('id',$userId)->get()->first()['title'];
        return $title;
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
      'enduser_id' => [
          'label' => 'END USER NAME',
          "type" => "relation",
          "options" => ['getEndUserName', 'name'],
          "linkable" => true,
          'form' => false,
          'index' => true
      ],
      'advance_dealer_id' => [
          'label' => 'DEALER NAME',
          "type" => "method",
          "method" => 'getDealerName',
          "form" => false,
          'index' => true
      ],
      'code' => [
          'label' => 'DEALER CODE',
          "type" => "method",
          "method" => 'getDealerCode',
          "form" => false,
          'index' => true
      ],
      'product_id' => [
          'label' => 'PRODUCT NAME',
          "type" => "relation",
          "options" => ['getProductName', 'name'],
          "linkable" => true,
          'form' => false,
          'index' => true
      ],
      'product_price' => [
        'label' => 'PAYMENT TYPE',
        "type" => "method",
        "method" => 'getProductPrice',
        "form" => false,
        'index' => true
      ],
      'advance_payment_id' => [
          'label' => 'PAYMENT TYPE',
          "type" => "method",
          "method" => 'get_paymenttile',
          "form" => false,
          'index' => true
      ],
      "quantity" => [
          "label" => "QUANTITY",
          "type" => "string",
          'index' => true
      ],
	];
}
