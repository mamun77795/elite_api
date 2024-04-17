<?php

namespace App;

use function Aws\recursive_dir_iterator;
use Illuminate\Database\Eloquent\Model;
use Rashidul\RainDrops\Model\RainDropsSupport;
use App\Client;
use App\User;
use Auth;

class ClientServerKey extends Model
{

    use RainDropsSupport;

    protected $table = 'client_server_keys';

    protected $fillable = [
	    'invoice_number',
      'client_id',
      'enduser_id',
      'outlet_id',
	    'gross_sale',
      'created_at',
	];

    protected $baseUrl = 'client_server_keys';
    protected $entityName = 'Client Server Key';
    protected $entityNamePlural = 'Client Server Key';


        public function getClientName(){
            return $this->belongsTo(Client::class, 'client_id');
        }

        public function getEndUserName(){
            return $this->belongsTo(EndUser::class, 'enduser_id');
        }
        public function getdivision(){
            $companyId =  $this->outlet_id;
            $userId = Outlet::where('id',$companyId)->get()->first()['outlet_name'];
            return $userId;
        }
        public function getdistrict(){
            $companyId =  $this->district_id;
            $userId = MacroDistrict::where('id',$companyId)->get()->first()['district'];
            return $userId;
        }
        // public function getdivision(){
        //     return $this->belongsTo(MacroDivision::class, 'division_id');
        // }
        // public function getdistrict(){
        //     return $this->belongsTo(MacroDistrict::class, 'district_id');
        // }


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
      'outlet_id' => [
          'label' => 'OUTLET NAME',
          "type" => "method",
          "method" => 'getdivision',
          'form' => false,
          'index' => true
      ],
      "invoice_number" => [
          "label" => "INVOICE NUMBER",
          "type" => "string",
          'index' => true
      ],
      "gross_sale" => [
          "label" => "NET SALE",
          "type" => "string",
          'index' => true
      ],
      "created_at" => [
          "label" => "DATE TIME",
          "type" => "string",
          'index' => true
      ],
	];
}
