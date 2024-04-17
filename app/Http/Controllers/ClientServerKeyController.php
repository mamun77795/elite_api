<?php

namespace App\Http\Controllers;
use App\User;
use App\Client;
use Auth;
use App\ClientServerKey;
use Rashidul\RainDrops\Form\Helper;
use Illuminate\Http\Request;
use Rashidul\RainDrops\Controllers\BaseController;

class ClientServerKeyController extends BaseController
{

    protected $modelClass = ClientServerKey::class;

    // public function storing(){
    //
    //     $this->model->users_id = Auth::user()->id;
    // }

    public function querying()
    {
        if (\Entrust::hasRole('admin')) {
            $this->dataTableQuery->latest();
        } elseif (\Entrust::hasRole('client')) {
            $company_id = Client::whereUser_id(Auth::user()->id)->get()->first();
            $companyId = $company_id->id;
            $this->dataTableQuery->latest()->where('client_id', $companyId);
        } elseif (\Entrust::hasRole('bdthai')) {
            $this->dataTableQuery->latest()->where('client_id', '18');
        }elseif (\Entrust::hasRole('employees')) {
//            $emp = Employee::where('user_id', Auth::user()->id)->get();
//            $companyId = $emp[0]->company_id;
//            $company = Company::find($companyId)->get();
//            $userId = $company[0]->user_id;
//            $this->dataTableQuery->latest()->where('user_id', $userId);
        }
    }

    public function setup()
    {
        if (\Entrust::hasRole('admin')) {
        } elseif (\Entrust::hasRole('bdthai')) {
          $this->crudAction->restrictActions(['edit','delete','add']);
        } elseif (\Entrust::hasRole('employees')) {
            $this->crudAction->restrictActions(['edit','delete','view']);
        } elseif (\Entrust::hasRole('viewer')) {
            $this->crudAction->restrictActions(['add', 'delete', 'edit']);
        }elseif (\Entrust::hasRole('developer')) {
          $this->crudAction->restrictActions(['edit','delete','add']);
            //$this->crudAction->restrictActions(['add', 'delete', 'edit']);
        }

    }

    public function postSearchSalesOrder(Request $request)
    {
      if (\Entrust::hasRole('admin')) {
        $attendanceObj   = new SalesOrder();
        $from         = $request->from;
        $to           = $request->to;

        $enduser   = $request->enduser;

        $endUsers     = $attendanceObj->where('enduser_id', $enduser)
                      ->whereDate('created_at', '>=', $from)
                      ->whereDate('created_at', '<=', $to)
                      ->get();



        // return $request->all();
        // return $supervisorName;
        return view('search-result.sales_order', compact('endUsers', 'from', 'to'));
      } elseif (\Entrust::hasRole('client')) {
        $company_id = Client::whereUser_id(Auth::user()->id)->get()->first();
        $companyId = $company_id->id;

        $attendanceObj   = new SalesOrder();
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
        return view('search-result.sales_order', compact('endUsers', 'from', 'to'));

      }elseif (\Entrust::hasRole('bdthai')) {


        $attendanceObj   = new SalesOrder();
        $from         = $request->from;
        $to           = $request->to;

        $enduser   = $request->enduser;

        $endUsers     = $attendanceObj->where('client_id', '18')
                      ->where('enduser_id', $enduser)
                      ->whereDate('created_at', '>=', $from)
                      ->whereDate('created_at', '<=', $to)
                      ->get();



        // return $request->all();
        // return $supervisorName;
        return view('search-result.sales_order', compact('endUsers', 'from', 'to'));

      }

    }
}
