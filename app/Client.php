<?php

namespace App;

use function Aws\recursive_dir_iterator;
use Illuminate\Database\Eloquent\Model;
use Rashidul\RainDrops\Model\RainDropsSupport;
use App\User;
use Auth;
class Client extends Model
{

    use RainDropsSupport;

    protected $table = 'clients';

    protected $fillable = [
      'id',
      'user_id',
	    'name',
	    'email',
	    'password',
      'code',
	    'phone_number',
	    'address',
      'type',
	    'status'
	];

    protected $baseUrl = 'clients';
    protected $entityName = 'CLIENTS';
    protected $entityNamePlural = 'CLIENTS';

   // public function getName()
   // {
   //    $userData = User::where('id', Auth::user()->id)->first();
   //     return $name = $userData->name;
   // }

   public function getName()
   {

       $user = User::find($this->user_id);
       return $user->name;
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
	    'name' => [
	        'label' => 'CLIENT NAME',
           "type" => "method",
           "method" => 'getName',
           "linkable" => true,
           'form' => false,
           'index' => true
	    ],
	    // 'name' => [
	    //     'label' => 'CLIENT NAME',
	    //     'type' => 'string',
      //       'index' => true
	    // ],
	    // 'email' => [
	    //     'label' => 'EMAIL',
	    //     'type' => 'string',
      //       'index' => true
	    // ],
      // 'password' => [
	    //     'label' => 'PASSWORD',
	    //     'type' => 'string',
      //       'index' => true
	    // ],
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
      'address' => [
	        'label' => 'ADDRESS',
	        'type' => 'string',
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
        'type' => [
  	        'label' => 'SERVICE TYPE',
              "type" => "select",
              'form' => true,
              'index' => true,
              "options" => [
                  'ONE TO ONE' => 'ONE TO ONE',
                  'SME' => 'SME',
                  'CORPORATE' => 'CORPORATE',

              ],
              'labels' => [
                  'ONE TO ONE' => 'CONNECTED',
                  'SME' => 'CANCELLED',
                  'CORPORATE' => 'REQUESTED',

              ],
          ],

	];
}
