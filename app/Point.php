<?php

namespace App;

use function Aws\recursive_dir_iterator;
use Illuminate\Database\Eloquent\Model;
use Rashidul\RainDrops\Model\RainDropsSupport;
use App\Client;
use App\User;
use Auth;

class Point extends Model
{

    use RainDropsSupport;

    protected $table = 'points';

    protected $fillable = [
	    'sales_incharge',
      'client_id',
      'enduser_id',
      'area_name',
	    'region_name',
      'designation',
      'week_tour_from',
      'week_tour_to',
      'week_number',
      'date',
      'farm',
      'farmer_name',
      'farmer_address',
      'flock_size',
      'chicks_brand',
      'age_days',
      'body_weight_bird_standard',
      'body_weight_bird_actual',
      'fcr_standard',
      'fcr_actual',
      'mortality_pieces',
      'reason_of_mortality',
      'feed_brand',
      'feed_type_bs',
      'feed_type_bg',
      'feed_type_bf',
      'total_feed_consumption',
      'feed_consumption_actual',
      'feed_consumption_standard',
      'remarks',
	];

    protected $baseUrl = 'districts';
    protected $entityName = 'DISTRICT';
    protected $entityNamePlural = 'DISTRICT';


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
      "sales_incharge" => [
          "label" => "SALES INCHARGE",
          "type" => "string",
          'index' => true
      ],
      "area_name" => [
          "label" => "AREA NAME",
          "type" => "string",
          'index' => true
      ],
      "region_name" => [
          "label" => "REGION NAME",
          "type" => "string",
          'index' => true
      ],
      "designation" => [
          "label" => "DESIGNATION",
          "type" => "string",
          'index' => true
      ],
      "week_tour_from" => [
          "label" => "WEEK TOUR FROM",
          "type" => "string",
          'index' => true
      ],
      "week_tour_to" => [
          "label" => "WEEK TOUR TO",
          "type" => "string",
          'index' => true
      ],
      "total_feed_cons" => [
          "label" => "TOTAL FOOD CONS.",
          "type" => "string",
          'index' => true
      ],
      "date" => [
          "label" => "DATE",
          "type" => "string",
          'index' => true
      ],
      "farm" => [
          "label" => "FARM",
          "type" => "string",
          'index' => true
      ],
      "farmer_name" => [
          "label" => "FARMER NAME",
          "type" => "string",
          'index' => true
      ],
      "farmer_address" => [
          "label" => "FARMER ADDRESS",
          "type" => "string",
          'index' => true
      ],
      "flock_size" => [
          "label" => "FLOCK SIZE",
          "type" => "string",
          'index' => true
      ],
      "chicks_brand" => [
          "label" => "CHICKS BRAND",
          "type" => "string",
          'index' => true
      ],
      "age_days" => [
          "label" => "AGE DAYS",
          "type" => "string",
          'index' => true
      ],
      "body_weight_bird_standard" => [
          "label" => "BODY WEIGHT STANDARD",
          "type" => "string",
          'index' => true
      ],
      "body_weight_bird_actual" => [
          "label" => "BODY WEIGHT ACTUAL",
          "type" => "string",
          'index' => true
      ],
      "fcr_standard" => [
          "label" => "FCR STANDARD",
          "type" => "string",
          'index' => true
      ],
      "fcr_standard" => [
          "label" => "FCR ACTUAL",
          "type" => "string",
          'index' => true
      ],
      "mortality_pieces" => [
          "label" => "MORTALITY PIECES",
          "type" => "string",
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
      "feed_type_bs" => [
          "label" => "FEED TYPE B/S",
          "type" => "string",
          'index' => true
      ],
      "feed_type_bg" => [
          "label" => "FEED TYPE B/G",
          "type" => "string",
          'index' => true
      ],
      "feed_type_bf" => [
          "label" => "FEED TYPE B/F",
          "type" => "string",
          'index' => true
      ],
      "total_feed_consumption" => [
          "label" => "TOTAL FEED CONSUMPTION",
          "type" => "string",
          'index' => true
      ],
      "feed_consumption_actual" => [
          "label" => "FEED CONSUMPTION ACTUAL",
          "type" => "string",
          'index' => true
      ],
      "feed_consumption_standard" => [
          "label" => "FEED CONSUMPTION STANDARD",
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
