<?php

namespace App;

use function Aws\recursive_dir_iterator;
use Illuminate\Database\Eloquent\Model;
use Rashidul\RainDrops\Model\RainDropsSupport;
use App\Client;
use App\Payment;
use App\User;
use Auth;

class Receipt extends Model
{

    use RainDropsSupport;

    protected $table = 'receipts';

    protected $fillable = [
	    'user_id',
	    'payment_id',
      'total_amount',
      'paid',
      'due',
      'payment_method',
      'transaction_detail',
	];

    protected $baseUrl = 'receipts';
    protected $entityName = 'RECEIPTS';
    protected $entityNamePlural = 'RECEIPTS';

//    public function getName()
//    {
//       $userData = User::where('id', Auth::user()->id)->first();
//        return $name = $userData->name;
//    }
        public function getTotalAmount(){

              //$userId = Company::where('id',$companyId)->get()->first()['user_id'];
            $receipt = Receipt::where('user_id',$this->user_id)->get()->first()['total_amount'];
            // $paymenttype = $company['paymenttype'];
            // $user_limit = $company['user_limit'];
            //dd($company);
            // $user = User::find($company);
            //     return $user->name;
            // $userId = $company[0]->user_id;
            // $user = \App\User::where('id',$userId)->get();
            // $name = $user[0]->name;
            // $link = url('users/'. $userId);
            return $receipt. ' TAKA';
        }
        public function getClientName(){
            return $this->belongsTo(User::class, 'user_id');
        }

        public function getPayment(){
            return $this->belongsTo(Payment::class, 'payment_id');
        }

        public function getDue(){
            $receipt = Receipt::where('user_id',$this->user_id)->get()->first()['due'];
            return $receipt. ' TAKA';
        }

        public function getPaid(){
            $receipt = Receipt::where('user_id',$this->user_id)->get()->first()['paid'];
            return $receipt. ' TAKA';
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
          'label' => 'CLIENT NAME',
          "type" => "relation",
          "options" => ['getClientName', 'name'],
          "linkable" => true,
          'index' => true
      ],
      'payment_id' => [
          'label' => 'PAYMENT NAME',
          "type" => "relation",
          "options" => ['getPayment', 'paymenttype'],
          "linkable" => true,
          'index' => true
      ],
      "total_amount" => [
          "label" => "TOTAL AMOUNT",
          "type" => "method",
          "method" => 'getTotalAmount',
          'index' => true
      ],
      "paid" => [
          "label" => "PAID",
          "type" => "method",
          "method" => 'getPaid',
          'index' => true
      ],
      "due" => [
          "label" => "DUE",
          "type" => "method",
          "method" => 'getDue',
          'index' => true
      ],
      "payment_method" => [
          "label" => "PAYMENT METHOD",
          "type" => "select",
          'index' => true,
          "options" => [
              'BKASH' => 'BKASH',
              'CASH' => 'CASH',
              'FREE' => 'FREE',
          ],
          'labels' => [
            'BKASH' => 'CONNECTED',
            'CASH' => 'CANCELLED',
            'FREE' => 'REQUESTED',

          ],
      ],
      "transaction_detail" => [
          "label" => "TRANSACTION",
          "type" => "string",
          'index' => true
      ],
	];
}
