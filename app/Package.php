<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Rashidul\RainDrops\Model\RainDropsSupport;
use Jcf\Geocode\Geocode;

class Package extends Model
{

    use RainDropsSupport;

    protected $table = 'packages';

    protected $fillable = [
	    'id',
	    'enduser_id',
      'client_id',
	    'status',
      'latitude',
      'longitude',
	    'created_at',
	];

    protected $baseUrl = 'packages';
    protected $entityName = 'PACKAGE';
    protected $entityNamePlural = 'PACKAGE';

    public function getAddress(){
      if ($this->latitude && $this->longitude) {
        $response = Geocode::make()->latLng($this->latitude,$this->longitude);
        if ($response) {
            return $response->formattedAddress();
          }
      }
      return 'NO ADDRESS FOUND';
    }

//     public function company()
//     {
//         return $this->belongsTo(Company::class, 'company_id');
//     }
//     public function getCompanyName(){
//         $companyId = $this->company_id;
// //        $company = Company::where('id',$companyId)->get();
//         $company = Company::find($companyId)->get();
//         $id = $company[0]->user_id;
//
//         $company = User::where('id',$id)->get();
//         $name = $company[0]->name;
//         //return $name;
//         $link = url('users/'. $id);
//         return sprintf('<u><a href="%s">%s</a>', $link, $name);
//     }
//
//     public function getEmployeeName(){
//         $employee = Employee::where('id',$this->employee_id)->get();
//         $userId = $employee[0]->user_id;
//         $user = User::where('id',$userId)->get();
//         $name = $user[0]->name;
//         $link = url('users/'. $userId);
//         return sprintf('<u><a href="%s">%s</a>', $link, $name);
//         //return $name;
//     }

        // public function getCompanyName(){
        //     $client_id =  $this->client_id;
        //     $userId = Client::where('id',$client_id)->get()->first()['user_id'];
        //     $name = User::where('id',$userId)->get()->first()['name'];
        //     $link = url('users/'. $userId);
        //     return sprintf('<u><a href="%s">%s</a>', $link, $name);
        // }
        //
        // public function getEmployeeName(){
        //     $employeeId =  $this->enduser_id;
        //     $employeeName = EndUser::where('id',$employeeId)->get()->first()['name'];
        //     $link = url('endusers/'. $employeeId);
        //     return sprintf('<u><a href="%s">%s</a>', $link, $employeeName);
        // }

        public function getClientName(){
            return $this->belongsTo(Client::class, 'client_id');
        }

        public function getEndUserName(){
            return $this->belongsTo(EndUser::class, 'enduser_id');
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
	    'status' => [
	        'label' => 'STATUS',
            "type" => "select",
            "options" => [
                '1' => 'ENTRY',
                '2' => 'EXIT',
            ],
            'labels' => [
                '1' => 'CONNECTED',
                '2' => 'REQUESTED',
            ],
            'index' => true
	    ],

      // 'latitude' => [
      //     'label' => 'LATITUDE',
      //     'type' => 'string',
      //       'index' => true
      // ],
      // 'longitude' => [
      //     'label' => 'LONGITUDE',
      //     'type' => 'string',
      //       'index' => true
      // ],
      'address' => [
          'label' => 'ADDRESS',
          "type" => "method",
          "method" => 'getAddress',
          'form' => false,
          'index' => true
      ],
      "created_at" => [
          "label" => "DATE TIME",
          "type" => "string",
          'index' => true
      ],
	];
}
