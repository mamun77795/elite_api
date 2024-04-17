<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Rashidul\RainDrops\Model\RainDropsSupport;
use Jcf\Geocode\Geocode;

class VisitedSubAgent extends Model
{

    use RainDropsSupport;
    protected $table = 'visiting_sub_agents';
    protected $fillable = [
        "id",
        "enduser_id",
        "client_id",
        "name_agent",
        "address",
        "phone",
        "name_sub_agent",
        "customer_feedback",
        "created_at",
    ];

    public function getClientName(){
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function getEndUserName(){
        return $this->belongsTo(EndUser::class, 'enduser_id');
    }

    protected $baseUrl = 'visiting_sub_agents';
    protected $entityName = 'VISITED SUB AGENTS';
    protected $entityNamePlural = 'VISITED SUB AGENTS';

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
            'label' => 'END USER NAME',
            "type" => "relation",
            "options" => ['getEndUserName', 'name'],
            "linkable" => true,
            'form' => false,
            'index' => true
        ],
        "name_agent" => [
            "label" => "NAME AGENT",
            "type" => "string",
            'index' => true
        ],
        "name_sub_agent" => [
            "label" => "NAME SUB AGENT",
            "type" => "string",
            'index' => true
        ],
        "address" => [
            "label" => "ADDRESS",
            "type" => "string",
            'index' => true
        ],
        "phone" => [
            "label" => "PHONE",
            "type" => "string",
            'index' => true
        ],

        "customer_feedback" => [
            "label" => "CUSTOMER FEEDBACK",
            "type" => "string",
            'index' => true
        ],
        "created_at" => [
            "label" => "TIME",
            "type" => "string",
            'index' => true
        ],

    ];

}
