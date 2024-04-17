<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Rashidul\RainDrops\Model\RainDropsSupport;
use Jcf\Geocode\Geocode;

class PushNotification extends Model
{

    use RainDropsSupport;
    protected $table = 'push_notifications';
    protected $fillable = [
      'id',
      'sub_agent_id',
      'advance_dealer_id',
      'farm_id',
      'breed_id',
      'visiting_date',
      'no_of_bird',
      'age_of_bird',
      'age_of_farm',
      'farm_density',
      'feed_company',
      'hatchery_name',
      'health_condition',
      'feed_intake',
      'total_feed_intake',
      'avg_body_wt',
      'feeder_no',
      'drinker_no',
      'mortality_no',
      'mortality_percentage',
      'fcr_actual',
      'fcr_standard',
      'performance',
      'created_at'
    ];

    public function getClientName(){
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function getEndUserName(){
        return $this->belongsTo(EndUser::class, 'enduser_id');
    }

    public function getDealerName(){
        $companyId =  $this->advance_dealer_id;
        $sub_agent_id =  $this->sub_agent_id;
        if($companyId){
          $userId = AdvanceDealer::where('id',$companyId)->get()->first()['name'];
          return $userId.' (DEALER)';
        }else{
          $userId = SubAgent::where('id',$sub_agent_id)->get()->first()['sub_agent_name'];
          return $userId.' (SUB AGENT)';
        }

        // return $companyName;
    }

    public function getaddress(){
        $companyId =  $this->advance_dealer_id;
        $sub_agent_id =  $this->sub_agent_id;
        if($companyId){
          $userId = AdvanceDealer::where('id',$companyId)->get()->first()['dealer_address'];
          return $userId;
        }else{
          $userId = SubAgent::where('id',$sub_agent_id)->get()->first()['sub_agent_address'];
          return $userId;
        }

        // return $companyName;
    }

    public function getDealerPhone(){
      $companyId =  $this->advance_dealer_id;
      $sub_agent_id =  $this->sub_agent_id;
      if($companyId){
        $userId = AdvanceDealer::where('id',$companyId)->get()->first()['phone'];
        return $userId;
      }else{
        $userId = SubAgent::where('id',$sub_agent_id)->get()->first()['sub_agent_phone'];
        return $userId;
      }
    }

    public function getbreed(){
      $companyId =  $this->breed_id;
      $userId = Breed::where('id',$companyId)->get()->first()['breed'];
      return $userId;
    }

    public function getFarmName(){
      $companyId =  $this->farm_id;
      $userId = Farm::where('id',$companyId)->get()->first()['name'];
      return $userId;
    }

    public function getFarmNumber(){
        $companyId =  $this->farm_id;
        $userId = Farm::where('id',$companyId)->get()->first()['phone'];
        return $userId;
    }
    public function getFarmAddress(){
        $companyId =  $this->farm_id;
        $userId = Farm::where('id',$companyId)->get()->first()['address'];
        return $userId;
    }

    protected $baseUrl = 'push_notifications';
    protected $entityName = 'PUSH NOTIFICATIONS';
    protected $entityNamePlural = 'PUSH NOTIFICATIONS';

    protected $fields = [
        "id" => [
            "label" => "ID",
            "type" => "number",
            'form' => false,
            'show' => 'exact',
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
            'label' => 'USER NAME',
            "type" => "relation",
            "options" => ['getEndUserName', 'name'],
            "linkable" => true,
            'form' => false,
            'index' => true
        ],
        'advance_dealer_id' => [
            'label' => 'NAME',
            "type" => "method",
            "method" => 'getDealerName',
            "form" => false,
            'index' => true
        ],
        'phone' => [
            'label' => 'PHONE',
            "type" => "method",
            "method" => 'getDealerPhone',
            "form" => false,
            'index' => true
        ],
        'address' => [
            'label' => 'ADDRESS',
            "type" => "method",
            "method" => 'getaddress',
            "form" => false,
            'index' => true
        ],
        'farm_id' => [
            'label' => 'FARM NAME',
            "type" => "method",
            "method" => 'getFarmName',
            'form' => false,
            'index' => true
        ],
        'farm_number' => [
            'label' => 'FARM NUMBER',
            "type" => "method",
            "method" => 'getFarmNumber',
            'form' => false,
            'index' => true
        ],
        'farm_address' => [
            'label' => 'FARM ADDRESS',
            "type" => "method",
            "method" => 'getFarmAddress',
            'form' => false,
            'index' => true
        ],
        "visiting_date" => [
            "label" => "VISITING DATE",
            "type" => "string",
            'index' => true
        ],
        "no_of_bird" => [
            "label" => "NO OF BIRD",
            "type" => "string",
            'index' => true
        ],
        "visiting_date" => [
            "label" => "VISITING DATE",
            "type" => "string",
            'index' => true
        ],
        "age_of_bird" => [
            "label" => "AGE OF BIRD",
            "type" => "string",
            'index' => true
        ],
        "age_of_farm" => [
            "label" => "AGE OF FARM",
            "type" => "string",
            'index' => true
        ],
        "farm_density" => [
            "label" => "FARM DENSITY",
            "type" => "string",
            'index' => true
        ],
        "hatchery_name" => [
            "label" => "HATCHERY NAME",
            "type" => "string",
            'index' => true
        ],
        "feed_company" => [
            "label" => "FEED COMPANY",
            "type" => "string",
            'index' => true
        ],
        "health_condition" => [
            "label" => "HEALTH CONDITION",
            "type" => "string",
            'index' => true
        ],
        "feed_intake" => [
            "label" => "FEED INTAKE",
            "type" => "string",
            'index' => true
        ],
        "total_feed_intake" => [
            "label" => "TOTAL FEED INTAKE",
            "type" => "string",
            'index' => true
        ],
        "avg_body_wt" => [
            "label" => "AVG BODY WT",
            "type" => "string",
            'index' => true
        ],
        "feeder_no" => [
            "label" => "FEEDER NO",
            "type" => "string",
            'index' => true
        ],
        "drinker_no" => [
            "label" => "DRINKER NO",
            "type" => "string",
            'index' => true
        ],
        "mortality_no" => [
            "label" => "MORTALITY NO",
            "type" => "string",
            'index' => true
        ],
        "mortality_percentage" => [
            "label" => "MORTALITY PERCENTAGE",
            "type" => "string",
            'index' => true
        ],
        "fcr_actual" => [
            "label" => "FCR(ACTUAL)",
            "type" => "string",
            'index' => true
        ],
        "fcr_standard" => [
            "label" => "FCR(STANDARD)",
            "type" => "string",
            'index' => true
        ],
        "performance" => [
            "label" => "PERFORMANCE",
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
