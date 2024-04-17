<?php

namespace App;

use function Aws\recursive_dir_iterator;
use Illuminate\Database\Eloquent\Model;
use Rashidul\RainDrops\Model\RainDropsSupport;
use App\Client;
use App\User;
use Auth;

class DealerNegetiveValue extends Model
{

    use RainDropsSupport;

    protected $table = 'dealer_negetive_valus';

    protected $fillable = [

	];

    protected $baseUrl = 'features';
    protected $entityName = 'DealerNegetiveValue';
    protected $entityNamePlural = 'DealerNegetiveValue';


        public function getClientName(){
            return $this->belongsTo(Client::class, 'client_id');
        }

        public function getEndUserName(){
            return $this->belongsTo(EndUser::class, 'enduser_id');
        }
        public function getAgentName(){
            return $this->belongsTo(Agent::class, 'agent_id');
        }

        public function getAgentNumber(){
            $companyId =  $this->agent_id;
            $userId = Agent::where('id',$companyId)->get()->first()['phone'];
            return $userId;
        }
        public function getAgentAddress(){
            $companyId =  $this->agent_id;
            $userId = Agent::where('id',$companyId)->get()->first()['address'];
            return $userId;
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
      "reason_of_mortality" => [
          "label" => "REASON OF MORTALITY",
          "type" => "string",
          'index' => true
      ],
      "feed_brand" => [
          "label" => "FEED BRAND",
          "type" => "string",
          'index' => true
      ],
      "remarks" => [
          "label" => "REMARKS",
          "type" => "string",
          'index' => true
      ],
	];
}
