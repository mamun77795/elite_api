<?php

namespace App\Http\Controllers;

use App\Client;
use App\EndUser;
use App\TimeTable;
use App\User;
use Rashidul\RainDrops\Controllers\BaseController;
use Auth;
use Illuminate\Support\Facades\Hash;
use DB;

class ClientController extends BaseController
{

    protected $modelClass = Client::class;

    public function creating()
    {
        $this->viewData['form']
            ->add('name', [
                'label' => 'CLIENT NAME',
                'type' => 'text'
            ])->add('email', [
                'label' => 'EMAIL',
                'type' => 'text'
            ])->add('password', [
                'label' => 'PASSWORD',
                'type' => 'text'
            ]);
    }

    public function updating()
    {
       // get user id
       $userId = $this->model->id;
       // update user table
       // $userId = $this->model->user_id;
       // $user = user::findorFail($userId);
       // $user->name = $this->request->name;
       // $user->email = $this->request->email;
       // $user->password = Hash::make($this->request->password);
       // $user->save();
       // update employee table
       // $employee = Client::where('id',$userId)->first();
       // $employee->phone_number = $this->model->phone_number;
       // $employee->address = $this->model->address;
       // $employee->code = $this->model->code;
       // $employee->status = $this->model->status;
       // $employee->type = $this->model->type;
       Client::where('id', $this->model->id)->update(['type' => $this->request->type]);
       Client::where('id', $this->model->id)->update(['status' => $this->request->status]);
       // $phone_number = $this->request->phone_number;
       // $address = $this->request->address;
       // $code = $this->request->code;
       // $status = $this->request->status;
       // $type = $this->request->type;

       //$employee->save();
       // update role
       // DB::table('role_user')->where('user_id', $userId)->update(['role_id' => $this->request->role_id]);
    }

//    public function storing(){
//            $this->model->user_id = Auth::user()->id;
//            $this->model->status = 1;
//    }
    public function storing()
    {
        //get emp name/email/pass
        $name = $this->request->name;
        $email = $this->request->email;
        $pass = $this->request->password;
        //store user info into user tbl
        $password = Hash::make($pass);
        $data = array("name" => $name, "email" => $email, "password" => $password);
        $cliUId = User::create($data);
        $clientUserId = $cliUId->id;

        //create company info
        $seed = str_split('ABCDEFGHIJKLMNOPQRSTUVWXYZ'
                 .'0123456789'); // and any other characters
        shuffle($seed);
        $code = '';
        foreach (array_rand($seed, 6) as $k) $code .= $seed[$k];


        $phone_number = $this->request->phone_number;
        $address = $this->request->address;
        // $code = $this->request->code;
        $status = $this->request->status;
        $type = $this->request->type;

        $formdata = array(
            "user_id" => $clientUserId, "phone_number" => $phone_number, "address" => $address,"status" => $status,"type" => $type,
          "code" => $code
        );
        Client::create($formdata);
//        //create last company id
//        $id = $company->id;
//        //get start time and end for time table
//        $start = $this->request->start_time;
//        $end = $this->request->end_time;
//        $timetable = array(
//            "company_id" => $id, "start_time" => $start, "end_time" => $end,
//        );
//        TimeTable::create($timetable);

        //create this user define role
        DB::table('role_user')->insert(['user_id' => $clientUserId, 'role_id' => 2]);
        Session()->flash('message', 'Your data has been inserted');
        return redirect()->back();
    }

    public function querying()
    {
        if (\Entrust::hasRole('admin')) {
            $this->dataTableQuery->latest();
        } elseif (\Entrust::hasRole('company')) {
            $this->dataTableQuery->latest()->where('user_id', Auth::user()->id);
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
//            $this->crudAction->permitActions(['add','view','edit','delete']);
        } elseif (\Entrust::hasRole('company')) {
            $this->crudAction->restrictActions(['add', 'delete']);
        } elseif (\Entrust::hasRole('employees')) {
            $this->crudAction->restrictActions(['edit', 'delete', 'add']);
        } elseif (\Entrust::hasRole('viewer')) {
          //  $this->crudAction->restrictActions(['add', 'delete', 'edit']);
          $this->crudAction->restrictActions(['edit','delete','add']);
        }elseif (\Entrust::hasRole('developer')) {
          $this->crudAction->restrictActions(['edit','delete','add']);
            //$this->crudAction->restrictActions(['add', 'delete', 'edit']);
        }

    }
}
