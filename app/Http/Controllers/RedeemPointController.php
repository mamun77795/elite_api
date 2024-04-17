<?php

namespace App\Http\Controllers;
use App\User;
use App\Client;
use Auth;
use App\RedeemPoint;
use Rashidul\RainDrops\Form\Helper;
use Rashidul\RainDrops\Controllers\BaseController;

class RedeemPointController extends BaseController
{

    protected $modelClass = RedeemPoint::class;

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
        } elseif (\Entrust::hasRole('employees')) {
//            $emp = Employee::where('user_id', Auth::user()->id)->get();
//            $companyId = $emp[0]->company_id;
//            $company = Company::find($companyId)->get();
//            $userId = $company[0]->user_id;
//            $this->dataTableQuery->latest()->where('user_id', $userId);
        }
    }

    public function storing()
    {

      $user_id = $this->request->user_id;
      $suggestion = $this->request->suggestion;
      $client_id = Client::where('user_id',$user_id)->get()->first()['id'];

      $formdata = array(
          "client_id" => $client_id,
          "suggestion" => $suggestion
      );
      $employee = ActivitySuggestion::create($formdata);

      Session()->flash('message', 'Your data has been inserted');
      return redirect()->back();
    }

    public function setup()
    {
        if (\Entrust::hasRole('admin')) {
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
