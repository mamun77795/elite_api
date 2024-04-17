<?php

namespace App\Http\Controllers;
use App\Client;
use Auth;
use App\Registration;
use Rashidul\RainDrops\Controllers\BaseController;

class RegistrationController extends BaseController
{

    protected $modelClass = Registration::class;

    public function storing(){

        $this->model->users_id = Auth::user()->id;
    }

    public function querying()
    {
        if (\Entrust::hasRole('admin')) {
            $this->dataTableQuery->latest()->where('app_identifier', 'com.ets.salesassistant.company');
        } elseif (\Entrust::hasRole('company')) {
            $company_id = Company::whereUser_id(Auth::user()->id)->get()->first();
            $companyId = $company_id->id;
            $this->dataTableQuery->latest()->where('company_id', $companyId);
        } elseif (\Entrust::hasRole('employees')) {
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
        } elseif (\Entrust::hasRole('company')) {
            $this->crudAction->restrictActions(['add', 'delete']);
        } elseif (\Entrust::hasRole('employees')) {
            $this->crudAction->restrictActions(['edit','delete','view']);
        } elseif (\Entrust::hasRole('viewer')) {
            $this->crudAction->restrictActions(['add', 'delete', 'edit']);
        }elseif (\Entrust::hasRole('developer')) {
          $this->crudAction->restrictActions(['edit','delete','add']);
            //$this->crudAction->restrictActions(['add', 'delete', 'edit']);
        }

    }
}
