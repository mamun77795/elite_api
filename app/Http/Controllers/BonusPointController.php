<?php

namespace App\Http\Controllers;
use App\User;
use App\Client;
use Auth;
use App\BonusPoint;
use Rashidul\RainDrops\Form\Helper;
use Rashidul\RainDrops\Controllers\BaseController;

class BonusPoint extends BaseController
{

    protected $modelClass = BonusPoint::class;

    // public function storing(){
    //
    //     $this->model->users_id = Auth::user()->id;
    // }

    public function querying()
    {
        if (\Entrust::hasRole('admin')) {
            $this->dataTableQuery->latest();
        }elseif (\Entrust::hasRole('client')) {
            $company_id = Client::whereUser_id(Auth::user()->id)->get()->first();
            $companyId = $company_id->id;
            $this->dataTableQuery->latest()->where('client_id', $companyId);
        }  elseif (\Entrust::hasRole('employees')) {
            // $emp = Employee::where('user_id', Auth::user()->id)->get();
            // $companyId = $emp[0]->company_id;
            // $company = Company::find($companyId)->get();
            // $userId = $company[0]->user_id;
            // $this->dataTableQuery->latest()->where('user_id', $userId);
        }
    }

    public function setup()
    {
        if (\Entrust::hasRole('admin')) {
          // $this->crudAction->restrictActions(['edit','delete','view']);
//            $this->crudAction->permitActions(['add','view','edit','delete']);
        } elseif (\Entrust::hasRole('school')) {
        } elseif (\Entrust::hasRole('employees')) {
            $this->crudAction->restrictActions(['edit','delete','view']);
        } elseif (\Entrust::hasRole('viewer')) {
            $this->crudAction->restrictActions(['add', 'delete', 'edit']);
        }

    }
}
