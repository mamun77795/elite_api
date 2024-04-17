<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use Rashidul\RainDrops\Model\RainDropsSupport;
use Laravel\Scout\Searchable;

class User extends Authenticatable
{
    use RainDropsSupport;
    use Notifiable;
    use EntrustUserTrait;
//    use Searchable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

//    public function searchableAs()
//    {
//        return 'users';
//    }
    protected $fillable = [
        'name', 'email', 'password','status', 'picture'
    ];

    public function getUserRole()
    {
        $user = User::find($this->id);
        $role = ($user->roles)->toArray();
        if ($role == null) {
            return '<span class="label label-warning">NO ROLE</span>';
        }
        $roles  = $role['0']['name'];
        return ($roles);
    }
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $baseUrl = 'users';
    protected $entityName = 'USERS';
    protected $entityNamePlural = 'USERS';

    protected $fields = [
        "id" => [
            "label" => "ID",
            "type" => "number",
            'form' => false,
            'show' => 'exact',
            'index' => true
        ],
        "name" => [
            "label" => "NAME",
            "type" => "string",
            'index' => true
        ],
        "role" => [
            "label" => "ROLE",
            "type" => "method",
            "method" => 'getUserRole',
            'index' => true,
            'form' => false
        ],
        "email" => [
            "label" => "EMAIL",
            "type" => "string",
            'index' => true
        ],
        "status" => [
            "label" => "STATUS",
            "type" => "select",
            "options" => [
                '1'=> 'ACTIVE',
                '2'=> 'SUSPENDED'
            ],
            'labels' => [
                '1' => 'CONNECTED',
                '2' =>  'CANCELLED'
            ],
            'index' => true
        ],
    ];

}
