<?php

namespace App\Http\Controllers;

use App\Client;
use App\EndUser;
use App\TimeTable;
use App\Invitation;
use Rashidul\RainDrops\Controllers\BaseController;
use Auth;
use Illuminate\Support\Facades\Hash;
use DB;

class InvitationController extends BaseController
{

    protected $modelClass = Invitation::class;

    // public function creating()
    // {
    //     $this->viewData['form']
    //         ->add('name', [
    //             'label' => 'NAME',
    //             'type' => 'text'
    //         ])->add('email', [
    //             'label' => 'EMAIL',
    //             'type' => 'text'
    //         ])->add('password', [
    //             'label' => 'PASSWORD',
    //             'type' => 'text'
    //         ]);
    // }

    // public function updating()
    // {
//        // get user id
//        $userId = $this->model->user_id;
//        // update user table
//        $userId = $this->model->user_id;
//        $user = user::findorFail($userId);
//        $user->name = $this->request->name;
//        $user->email = $this->request->email;
//        $user->password = Hash::make($this->request->password);
//        $user->save();
//        // update employee table
//        $employee = Employee::where('user_id',$userId)->first();
//        $employee->number = $this->model->number;
//        $employee->dob = $this->model->dob;
//        $employee->code = $this->model->code;
//        $employee->designation = $this->model->designation;
//        $employee->address = $this->model->address;
//        $employee->save();
//        // update role
//        DB::table('role_user')->where('user_id', $userId)->update(['role_id' => $this->request->role_id]);
    // }

//    public function storing(){
//            $this->model->user_id = Auth::user()->id;
//            $this->model->status = 1;
//    }
//     public function storing()
//     {
//         //get emp name/email/pass
//         $name = $this->request->name;
//         $email = $this->request->email;
//         $pass = $this->request->password;
//         //store user info into user tbl
//         $password = Hash::make($pass);
//         $data = array("name" => $name, "email" => $email, "password" => $password);
//         $comUId = User::create($data);
//         $companyUserId = $comUId->id;
//
//         //create company info
//         $number = $this->request->number;
//         $address = $this->request->address;
//         $city = $this->request->city;
//         $url = $this->request->url;
//         $code = $this->request->code;
//
//         $formdata = array(
//             "user_id" => $companyUserId, "number" => $number, "address" => $address,
//             "city" => $city,"code" => $code, "url" => $url
//         );
//         Company::create($formdata);
// //        //create last company id
// //        $id = $company->id;
// //        //get start time and end for time table
// //        $start = $this->request->start_time;
// //        $end = $this->request->end_time;
// //        $timetable = array(
// //            "company_id" => $id, "start_time" => $start, "end_time" => $end,
// //        );
// //        TimeTable::create($timetable);
//
//         //create this user define role
//         DB::table('role_user')->insert(['user_id' => $companyUserId, 'role_id' => 2]);
//         Session()->flash('message', 'Your data has been inserted');
//         return redirect()->back();
//     }

    public function querying()
    {
        if (\Entrust::hasRole('admin')) {
            $this->dataTableQuery->latest();
        } elseif (\Entrust::hasRole('client')) {
            $company_id = Client::whereUser_id(Auth::user()->id)->get()->first();
            $companyId = $company_id->id;
            $this->dataTableQuery->latest()->where('client_id', $companyId);
        }  elseif (\Entrust::hasRole('employees')) {
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
