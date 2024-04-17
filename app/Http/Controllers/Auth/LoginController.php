<?php

namespace App\Http\Controllers\Auth;

use App\Activity;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use Session;
use Auth;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        $users_id = $user->id;
        $date_time = Carbon::now();
        $status = 1;
        $ip = $request->ip();

        $data=array("users_id"=>$users_id,"status"=>$status,"date_time"=>$date_time,"ip"=>$ip);

        Activity::create($data);
        //DB::table('activities')->insert($data);
        return redirect('/home');

    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        // logout record
        $users_id = Auth::user()->id;
      //  dd($users_id);
        $date_time = Carbon::now();
        $status = 2;
        $ip = $request->ip();

        $data=array("users_id"=>$users_id,"status"=>$status,"date_time"=>$date_time,"ip"=>$ip);
        Activity::create($data);
//        DB::table('activities')->insert($data);

        $this->guard()->logout();
        $request->session()->flush();
        $request->session()->regenerate();

        return redirect('/login');
    }



}
