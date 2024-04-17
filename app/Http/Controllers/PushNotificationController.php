<?php

namespace App\Http\Controllers;

use App\Client;
use App\EndUser;
use App\PushNotification;
use App\TimeTable;
use App\User;
use Illuminate\Http\Request;
use Rashidul\RainDrops\Controllers\BaseController;
use Auth;
use Illuminate\Support\Facades\Hash;
use Jcf\Geocode\Geocode;
use DB;

class PushNotificationController extends BaseController
{
    protected $modelClass = PushNotification::class;

    // public function storing()
    // {
    //     $this->model->date_time = date('Y-m-d H:i:s');
    // }
    public function setup()
    {
        if (\Entrust::hasRole('admin')) {
          $this->crudAction->restrictActions(['add']);
        }elseif (\Entrust::hasRole('client')) {
            $this->crudAction->restrictActions(['add', 'edit','delete']);
        }elseif (\Entrust::hasRole('amrit')) {
            $this->crudAction->restrictActions(['add', 'edit','delete']);
        } elseif (\Entrust::hasRole('employees')) {
            $this->crudAction->restrictActions(['delete', 'add']);
        } elseif (\Entrust::hasRole('viewer')) {
            $this->crudAction->restrictActions(['add', 'delete', 'edit']);
        }
    }

//    public function setup(){
//        $company_id = Company::whereUser_id(Auth::user()->id)->get()->first();
//        $companyId = $company_id->id;
//        dd($companyId);
////        $this->dataTableQuery->latest()->where('company_id', $companyId);
//
//    }


    public function querying()
    {
        if (\Entrust::hasRole('admin')) {
            $this->dataTableQuery->latest();
        } elseif (\Entrust::hasRole('client')) {
            $company_id = Client::whereUser_id(Auth::user()->id)->get()->first();
            $companyId = $company_id->id;
            $this->dataTableQuery->latest()->where('client_id', $companyId);
        }elseif (\Entrust::hasRole('amrit')) {
            $this->dataTableQuery->latest()->where('client_id', '7');
        } elseif (\Entrust::hasRole('employees')) {
    //            $emp = Employee::where('user_id', Auth::user()->id)->get();
    //            $companyId = $emp[0]->company_id;
    //            $company = Company::find($companyId)->get();
    //            $userId = $company[0]->user_id;
    //            $this->dataTableQuery->latest()->where('user_id', $userId);
        }
    }

    public function postSearchLayerPerformance(Request $request)
    {
      if (\Entrust::hasRole('admin')) {
        $attendanceObj   = new LayerPerformance();
        $from         = $request->from;
        $to           = $request->to;

        $enduser   = $request->enduser;

        $endUsers     = $attendanceObj->where('enduser_id', $enduser)
                      ->whereDate('created_at', '>=', $from)
                      ->whereDate('created_at', '<=', $to)
                      ->get();
        $dateranges     = $attendanceObj->whereDate('created_at', '>=', $from)
                      ->whereDate('created_at', '<=', $to)
                      ->get();



        // return $request->all();
        // return $supervisorName;
        return view('search-result.layer_performance', compact('endUsers', 'from', 'to','dateranges'));
      } elseif (\Entrust::hasRole('client')) {
        $company_id = Client::whereUser_id(Auth::user()->id)->get()->first();
        $companyId = $company_id->id;

        $attendanceObj   = new LayerPerformance();
        $from         = $request->from;
        $to           = $request->to;

        $enduser   = $request->enduser;

        $endUsers     = $attendanceObj->where('client_id', $companyId)
                      ->where('enduser_id', $enduser)
                      ->whereDate('created_at', '>=', $from)
                      ->whereDate('created_at', '<=', $to)
                      ->get();



        // return $request->all();
        // return $supervisorName;
        return view('search-result.layer_performance', compact('endUsers', 'from', 'to'));

      }

    }

    public function postSearchLayerPerformancedate(Request $request)
    {
      if (\Entrust::hasRole('admin')) {
        $attendanceObj   = new LayerPerformance();
        $from         = $request->from;
        $to           = $request->to;

        $enduser   = $request->enduser;

        $endUsers     = $attendanceObj
                      ->whereDate('created_at', '>=', $from)
                      ->whereDate('created_at', '<=', $to)
                      ->get();




        // return $request->all();
        // return $supervisorName;
        return view('search-result.layer_performance', compact('endUsers', 'from', 'to','dateranges'));
      } elseif (\Entrust::hasRole('client')) {
        $company_id = Client::whereUser_id(Auth::user()->id)->get()->first();
        $companyId = $company_id->id;

        $attendanceObj   = new LayerPerformance();
        $from         = $request->from;
        $to           = $request->to;

        $enduser   = $request->enduser;

        $endUsers     = $attendanceObj->where('client_id', $companyId)
                      ->whereDate('created_at', '>=', $from)
                      ->whereDate('created_at', '<=', $to)
                      ->get();



        // return $request->all();
        // return $supervisorName;
        return view('search-result.layer_performance', compact('endUsers', 'from', 'to'));

      }

    }
    public function postSearchLayerPerformancebreed(Request $request)
    {
      if (\Entrust::hasRole('admin')) {
        $attendanceObj   = new LayerPerformance();
        $from         = $request->from;
        $to           = $request->to;

        $enduser   = $request->breed;

        $endUsers     = $attendanceObj->where('breed_id', $enduser)
                      ->whereDate('created_at', '>=', $from)
                      ->whereDate('created_at', '<=', $to)
                      ->get();




        // return $request->all();
        // return $supervisorName;
        return view('search-result.layer_performance', compact('endUsers', 'from', 'to','dateranges'));
      } elseif (\Entrust::hasRole('client')) {
        $company_id = Client::whereUser_id(Auth::user()->id)->get()->first();
        $companyId = $company_id->id;

        $attendanceObj   = new LayerPerformance();
        $from         = $request->from;
        $to           = $request->to;

        $enduser   = $request->breed;

        $endUsers     = $attendanceObj->where('client_id', $companyId)
                      ->where('breed_id', $enduser)
                      ->whereDate('created_at', '>=', $from)
                      ->whereDate('created_at', '<=', $to)
                      ->get();



        // return $request->all();
        // return $supervisorName;
        return view('search-result.layer_performance', compact('endUsers', 'from', 'to'));

      }

    }

    public function postSearchLayerPerformancefeedmill(Request $request)
    {
      if (\Entrust::hasRole('admin')) {
        $attendanceObj   = new LayerPerformance();
        $from         = $request->from;
        $to           = $request->to;

        $enduser   = $request->feed_mill;

        $endUsers     = $attendanceObj->where('feed_mill_id', $enduser)
                      ->whereDate('created_at', '>=', $from)
                      ->whereDate('created_at', '<=', $to)
                      ->get();




        // return $request->all();
        // return $supervisorName;
        return view('search-result.layer_performance', compact('endUsers', 'from', 'to','dateranges'));
      } elseif (\Entrust::hasRole('client')) {
        $company_id = Client::whereUser_id(Auth::user()->id)->get()->first();
        $companyId = $company_id->id;

        $attendanceObj   = new LayerPerformance();
        $from         = $request->from;
        $to           = $request->to;

        $enduser   = $request->feed_mill;

        $endUsers     = $attendanceObj->where('client_id', $companyId)
                      ->where('feed_mill_id', $enduser)
                      ->whereDate('created_at', '>=', $from)
                      ->whereDate('created_at', '<=', $to)
                      ->get();



        // return $request->all();
        // return $supervisorName;
        return view('search-result.layer_performance', compact('endUsers', 'from', 'to'));

      }

    }
}
