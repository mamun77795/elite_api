<?php

namespace App\Http\Controllers;

use App\Client;
use App\EndUser;
use App\TimeTable;
use App\UsedInvitation;
use Rashidul\RainDrops\Controllers\BaseController;
use Auth;
use Illuminate\Support\Facades\Hash;
use DB;

class UsedInvitationController extends BaseController
{

    protected $modelClass = UsedInvitation::class;

    public function querying()
    {
        if (\Entrust::hasRole('admin')) {
            $this->dataTableQuery->latest()->where('status', 2);
        } elseif (\Entrust::hasRole('client')) {
            $company_id = Client::whereUser_id(Auth::user()->id)->get()->first();
            $companyId = $company_id->id;
            $this->dataTableQuery->latest()->where('client_id', $companyId)->where('status', 2);
        } elseif (\Entrust::hasRole('bdthai')) {
            $this->dataTableQuery->latest()->where('client_id', '18')->where('status', 2);
        }elseif (\Entrust::hasRole('getco')) {
            $this->dataTableQuery->latest()->where('client_id', '26')->where('status', 2);
        } elseif (\Entrust::hasRole('amrit')) {
            $this->dataTableQuery->latest()->where('client_id', '7')->where('status', 2);
        }elseif (\Entrust::hasRole('nourish')) {
            $this->dataTableQuery->latest()->where('client_id', '5')->where('status', 2);
        }elseif (\Entrust::hasRole('alpha')) {
            $this->dataTableQuery->latest()->where('client_id', '24')->where('status', 2);
        }elseif (\Entrust::hasRole('daimond')) {
            $this->dataTableQuery->latest()->where('client_id', '21')->where('status', 2);
        } elseif (\Entrust::hasRole('employees')) {
            $emp = Employee::where('user_id', Auth::user()->id)->get();
            $companyId = $emp[0]->company_id;
            $company = Company::find($companyId)->get();
            $userId = $company[0]->user_id;
            $this->dataTableQuery->latest()->where('user_id', $userId);
        }
    }


    public function setup()
    {
        if (\Entrust::hasRole('admin')) {
        } elseif (\Entrust::hasRole('client')) {
            $this->crudAction->restrictActions(['add', 'edit','delete']);
        }elseif (\Entrust::hasRole('bdthai')) {
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
            $this->crudAction->restrictActions(['edit', 'delete', 'add']);
        } elseif (\Entrust::hasRole('viewer')) {
            $this->crudAction->restrictActions(['add', 'delete', 'edit']);
        }elseif (\Entrust::hasRole('developer')) {
          $this->crudAction->restrictActions(['edit','delete','add']);
            //$this->crudAction->restrictActions(['add', 'delete', 'edit']);
        }

    }
}
