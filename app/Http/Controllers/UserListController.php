<?php

namespace App\Http\Controllers;

use App\Designation;
use App\EmployeeDesignation;
use App\EmployeeShift;
use Auth;
use App\Client;
use App\UserList;
use App\AdvanceExpense;
use Rashidul\RainDrops\Controllers\BaseController;
use Rashidul\RainDrops\Form\Helper;
use Illuminate\Http\Request;
use App\User;
use DB;
use Carbon\Carbon;
use \Crypt;
use App\Supervisor;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class UserListController extends BaseController
{

  protected $modelClass = UserList::class;
  protected $dataTransformer = EmployeeTransformer::class;

  protected $editView = 'employees.custom-edit';


    public function querying()
    {
        if (\Entrust::hasRole('admin')) {
            $this->dataTableQuery->latest();
            //$this->viewData['supervisors'] = 'Raju';
        } elseif (\Entrust::hasRole('client')) {
            $company_id = Client::whereUser_id(Auth::user()->id)->get()->first();
            $companyId = $company_id->id;
            $this->dataTableQuery->latest()->where('client_id', $companyId);
        } elseif (\Entrust::hasRole('bdthai')) {
            $this->dataTableQuery->latest()->where('client_id', '18');
        }elseif (\Entrust::hasRole('getco')) {
            $this->dataTableQuery->latest()->where('client_id', '26');
        } elseif (\Entrust::hasRole('amrit')) {
            $this->dataTableQuery->latest()->where('client_id', '18');
        }elseif (\Entrust::hasRole('nourish')) {
            $this->dataTableQuery->latest()->where('client_id', '5');
        }elseif (\Entrust::hasRole('alpha')) {
            $this->dataTableQuery->latest()->where('client_id', '24');
        } elseif (\Entrust::hasRole('daimond')) {
            $this->dataTableQuery->latest()->where('client_id', '21');
        }elseif (\Entrust::hasRole('developer')) {
//            $emp = Employee::where('user_id', Auth::user()->id)->get();
//            $companyId = $emp[0]->company_id;
//            $company = Company::find($companyId)->get();
//            $userId = $company[0]->user_id;
//            $this->dataTableQuery->latest()->where('user_id', $userId);
              $this->dataTableQuery->latest()->where('client_id', 5);
        }
    }
    public function setup()
    {
        if (\Entrust::hasRole('admin')) {
        } elseif (\Entrust::hasRole('client')) {
            $this->crudAction->restrictActions(['add', 'edit','delete']);
          //  $this->crudAction->permitActions(['add','view','edit','delete']);
        } elseif (\Entrust::hasRole('bdthai')) {
            $this->crudAction->restrictActions(['add', 'edit','delete']);
        }elseif (\Entrust::hasRole('getco')) {
            $this->crudAction->restrictActions(['add', 'edit','delete']);
        }elseif (\Entrust::hasRole('amrit')) {
            $this->crudAction->restrictActions(['add', 'edit','delete']);
        }elseif (\Entrust::hasRole('daimond')) {
            $this->crudAction->restrictActions(['add', 'edit','delete']);
        }elseif (\Entrust::hasRole('nourish')) {
            $this->crudAction->restrictActions(['add', 'edit','delete']);
        }elseif (\Entrust::hasRole('alpha')) {
            $this->crudAction->restrictActions(['add', 'edit','delete']);
        } elseif (\Entrust::hasRole('employees')) {
            $this->crudAction->restrictActions(['edit','delete','view']);
        } elseif (\Entrust::hasRole('viewer')) {
            //$this->crudAction->restrictActions(['add', 'delete', 'edit']);
            $this->crudAction->restrictActions(['edit','delete','add']);
        }elseif (\Entrust::hasRole('developer')) {
          $this->crudAction->restrictActions(['edit','delete','add']);
            //$this->crudAction->restrictActions(['add', 'delete', 'edit']);
        }

    }

    public function testSearch(Request $request)
    {
      return 1;
    }

    public function searchuserlist(Request $request)
    {
      if (\Entrust::hasRole('admin')) {
        $attendanceObj   = new UserList();
        $expenseObj   = new AdvanceExpense();
        $from         = $request->from;
        $to           = $request->to;

        $enduser   = $request->enduser;

        $dates = [];
        //$name = [];
        $s = Carbon::parse($from);
        $e = Carbon::parse($to);

        for($date = $s; $date->lte($e); $date->addDay()) {
            $dates[] = $date->format('Y-m-d');
        }

        $endUsers     = $attendanceObj->where('id', $enduser)

                      ->get();

        return view('search-result.userlist', compact('endUsers', 'from', 'to','dates','name'));
      } elseif (\Entrust::hasRole('client')) {
        $company_id = Client::whereUser_id(Auth::user()->id)->get()->first();
        $companyId = $company_id->id;

        $attendanceObj   = new OutletHit();
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
        return view('search-result.outlet_hit', compact('endUsers', 'from', 'to'));

      }elseif (\Entrust::hasRole('bdthai')) {


        $attendanceObj   = new UserList();
        $from         = $request->from;
        $to           = $request->to;

        $enduser   = $request->enduser;

        $dates = [];
        //$name = [];
        $s = Carbon::parse($from);
        $e = Carbon::parse($to);

        for($date = $s; $date->lte($e); $date->addDay()) {
            $dates[] = $date->format('Y-m-d');
        }

        $endUsers     = $attendanceObj->where('client_id', '18')->where('id', $enduser)

                      ->get();



        // return $request->all();
        // return $supervisorName;
        return view('search-result.userlist', compact('endUsers', 'from', 'to','dates','name'));

      }elseif (\Entrust::hasRole('getco')) {


        $attendanceObj   = new UserList();
        $from         = $request->from;
        $to           = $request->to;

        $enduser   = $request->enduser;

        $dates = [];
        //$name = [];
        $s = Carbon::parse($from);
        $e = Carbon::parse($to);

        for($date = $s; $date->lte($e); $date->addDay()) {
            $dates[] = $date->format('Y-m-d');
        }

        $endUsers     = $attendanceObj->where('client_id', '26')->where('id', $enduser)

                      ->get();



        // return $request->all();
        // return $supervisorName;
        return view('search-result.userlist', compact('endUsers', 'from', 'to','dates','name'));

      }elseif (\Entrust::hasRole('amrit')) {


        $attendanceObj   = new UserList();
        $from         = $request->from;
        $to           = $request->to;

        $enduser   = $request->enduser;

        $dates = [];
        //$name = [];
        $s = Carbon::parse($from);
        $e = Carbon::parse($to);

        for($date = $s; $date->lte($e); $date->addDay()) {
            $dates[] = $date->format('Y-m-d');
        }

        $endUsers     = $attendanceObj->where('client_id', '18')->where('id', $enduser)

                      ->get();



        // return $request->all();
        // return $supervisorName;
        return view('search-result.userlist', compact('endUsers', 'from', 'to','dates','name'));

      }elseif (\Entrust::hasRole('daimond')) {


        $attendanceObj   = new UserList();
        $from         = $request->from;
        $to           = $request->to;

        $enduser   = $request->enduser;

        $dates = [];
        //$name = [];
        $s = Carbon::parse($from);
        $e = Carbon::parse($to);

        for($date = $s; $date->lte($e); $date->addDay()) {
            $dates[] = $date->format('Y-m-d');
        }

        $endUsers     = $attendanceObj->where('client_id', '21')->where('id', $enduser)

                      ->get();



        // return $request->all();
        // return $supervisorName;
        return view('search-result.userlist', compact('endUsers', 'from', 'to','dates','name'));

      }elseif (\Entrust::hasRole('nourish')) {


        $attendanceObj   = new UserList();
        $from         = $request->from;
        $to           = $request->to;

        $enduser   = $request->enduser;

        $dates = [];
        //$name = [];
        $s = Carbon::parse($from);
        $e = Carbon::parse($to);

        for($date = $s; $date->lte($e); $date->addDay()) {
            $dates[] = $date->format('Y-m-d');
        }

        $endUsers     = $attendanceObj->where('client_id', '5')->where('id', $enduser)

                      ->get();



        // return $request->all();
        // return $supervisorName;
        return view('search-result.userlist', compact('endUsers', 'from', 'to','dates','name'));

      }elseif (\Entrust::hasRole('alpha')) {


        $attendanceObj   = new UserList();
        $from         = $request->from;
        $to           = $request->to;

        $enduser   = $request->enduser;

        $dates = [];
        //$name = [];
        $s = Carbon::parse($from);
        $e = Carbon::parse($to);

        for($date = $s; $date->lte($e); $date->addDay()) {
            $dates[] = $date->format('Y-m-d');
        }

        $endUsers     = $attendanceObj->where('client_id', '24')->where('id', $enduser)

                      ->get();



        // return $request->all();
        // return $supervisorName;
        return view('search-result.userlist', compact('endUsers', 'from', 'to','dates','name'));

      }

    }
}
