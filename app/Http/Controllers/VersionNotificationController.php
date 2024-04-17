<?php

namespace App\Http\Controllers;
use App\User;
use App\Client;
use Auth;
use App\VersionNotification;
use Rashidul\RainDrops\Form\Helper;
use Rashidul\RainDrops\Controllers\BaseController;

class VersionNotificationController extends BaseController
{

    protected $modelClass = VersionNotification::class;

    // public function storing(){
    //
    //     $this->model->users_id = Auth::user()->id;
    // }

    public function querying()
    {
        if (\Entrust::hasRole('admin')) {
            $this->dataTableQuery->latest();
        } elseif (\Entrust::hasRole('company')) {
            $this->dataTableQuery->latest()->where('user_id', Auth::user()->id);
        } elseif (\Entrust::hasRole('employees')) {
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
        }elseif (\Entrust::hasRole('employees')) {
            $this->crudAction->restrictActions(['edit','delete','view']);
        } elseif (\Entrust::hasRole('viewer')) {
            //$this->crudAction->restrictActions(['add', 'delete', 'edit']);
            $this->crudAction->restrictActions(['edit','delete','add']);
        }elseif (\Entrust::hasRole('developer')) {
          $this->crudAction->restrictActions(['edit','delete','add']);
            //$this->crudAction->restrictActions(['add', 'delete', 'edit']);
        }

    }
}
