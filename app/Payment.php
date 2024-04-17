<?php

namespace App;

use function Aws\recursive_dir_iterator;
use Illuminate\Database\Eloquent\Model;
use Rashidul\RainDrops\Model\RainDropsSupport;
use App\Client;
use App\User;
use Auth;

class Payment extends Model
{

    use RainDropsSupport;

    protected $table = 'payments';

    protected $fillable = [
	    'user_id',
	    'user_limit',
      'paymenttype',
      'expiration_date',
      'status_payment',
	];

    protected $baseUrl = 'payments';
    protected $entityName = 'PAYMENTS';
    protected $entityNamePlural = 'PAYMENTS';

//    public function getName()
//    {
//       $userData = User::where('id', Auth::user()->id)->first();
//        return $name = $userData->name;
//    }
        // public function getCompanyName(){
        //
        //
            // $company = Company::where('id',$this->company_id)->get();
            // //dd($company);
            // $user = User::find($company);
            //     return $user->name;
            // $userId = $company[0]->user_id;
            // $user = \App\User::where('id',$userId)->get();
            // $name = $user[0]->name;
            // $link = url('users/'. $userId);
            // return $name;
        // }
        public function getClientName(){
            return $this->belongsTo(User::class, 'user_id');
        }
        // public function getCompanyName()
        // {
        //
        //     $user = User::find($this->company_id);
        //     return $user->name;
        // }


    protected $fields = [
	    'id' => [
	        'label' => 'ID',
            "type" => "number",
            'form' => false,
            "show" => "exact",
            'index' => true
	    ],
      'user_id' => [
          'label' => 'CLIENT NAME',
          "type" => "relation",
          "options" => ['getClientName', 'name'],
          "linkable" => true,
          'index' => true
      ],
      "user_limit" => [
          "label" => "USER LIMIT",
          "type" => "string",
          'index' => true
      ],
      "paymenttype" => [
          "label" => "PAYMENT TYPE",
          "type" => "select",
          'index' => true,
          "options" => [
              'TRIAL' => 'TRIAL',
              'MONTHLY' => 'MONTHLY',
              'YEARLY' => 'YEARLY',
          ],
          'labels' => [
              'YEARLY' => 'CONNECTED',
              'MONTHLY' => 'CANCELLED',
              'TRIAL' => 'REQUESTED',
          ],
      ],
      "expiration_date" => [
          "label" => "EXPIRATION DATE",
          "type" => "string",
          'index' => true
      ],
      "status_payment" => [
          "label" => "PAYMENT STATUS",
          "type" => "select",
          'index' => true,
          "options" => [
              'FREE' => 'FREE',
              'PAID' => 'PAID',
              'DUE' => 'DUE',
          ],
          'labels' => [
              'PAID' => 'CONNECTED',
              'DUE' => 'CANCELLED',
              'FREE' => 'REQUESTED',
          ],
      ],
	];
}
