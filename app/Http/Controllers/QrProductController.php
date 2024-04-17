<?php

namespace App\Http\Controllers;
use App\User;
use App\Client;
use Auth;
use App\QrProduct;
use App\ProductType;
use Rashidul\RainDrops\Form\Helper;
use Rashidul\RainDrops\Controllers\BaseController;

class QrProductController extends BaseController
{

    protected $modelClass = QrProduct::class;

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
        }elseif (\Entrust::hasRole('amrit')) {
            $this->dataTableQuery->latest()->where('client_id', '7');
        } elseif (\Entrust::hasRole('employees')) {
            // $emp = Employee::where('user_id', Auth::user()->id)->get();
            // $companyId = $emp[0]->company_id;
            // $company = Company::find($companyId)->get();
            // $userId = $company[0]->user_id;
            // $this->dataTableQuery->latest()->where('user_id', $userId);
        }
    }

    public function creating()
    {

        // ------------for shift-----------------
        $html = '
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">SHIFT</label>
                    <select name="product_type_id" class="form-control select2">%s</select>
                </div>
            </div>';
        //$companyId = Company::where('user_id', Auth::user()->id)->first()['id'];
        $options = Helper::collectionToOptions(ProductType::where('client_id', 1)->get(),
            ['id', 'title']);
        $this->viewData['form']->addHtml('product_type', sprintf($html, $options));

    }

    public function setup()
    {
        if (\Entrust::hasRole('admin')) {
          // $this->crudAction->restrictActions(['edit','delete','view']);
//            $this->crudAction->permitActions(['add','view','edit','delete']);
        }elseif (\Entrust::hasRole('amrit')) {
            $this->crudAction->restrictActions(['add', 'edit','delete']);
        } elseif (\Entrust::hasRole('school')) {
        } elseif (\Entrust::hasRole('employees')) {
            $this->crudAction->restrictActions(['edit','delete','view']);
        } elseif (\Entrust::hasRole('viewer')) {
            $this->crudAction->restrictActions(['add', 'delete', 'edit']);
        }

    }
}
