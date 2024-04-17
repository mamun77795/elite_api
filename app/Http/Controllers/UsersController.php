<?php

namespace App\Http\Controllers;
use App\User;
use Rashidul\RainDrops\Controllers\BaseController;
use Auth;

class UsersController extends BaseController
{
    protected $modelClass = User::class;
}
