<?php

namespace App\Http\Controllers;

use App\Client;
use App\EndUser;
use App\Supervisor;
use App\TimeTable;
use App\User;
use Rashidul\RainDrops\Controllers\BaseController;
use Auth;
use Illuminate\Support\Facades\Hash;
use DB;

class SupervisorController extends BaseController
{

    protected $modelClass = Supervisor::class;

    public function setup()
    {
        if (\Entrust::hasRole('admin')) {
        } elseif (\Entrust::hasRole('client')) {
        }elseif (\Entrust::hasRole('bdthai')) {
            $this->crudAction->restrictActions(['add', 'edit','delete']);
        } elseif (\Entrust::hasRole('employees')) {
            $this->crudAction->restrictActions(['delete', 'add']);
        } elseif (\Entrust::hasRole('viewer')) {
            $this->crudAction->restrictActions(['add', 'delete', 'edit']);
        }
    }

//     public function updating()
//     {
//         // get user id
//         $userId = $this->model->user_id;
//         // update user table
//         $userId = $this->model->user_id;
//         $user = user::findorFail($userId);
//         $user->name = $this->request->name;
//         $user->email = $this->request->email;
//         $user->password = Hash::make($this->request->password);
//         $user->save();
//         // update employee table
//         $employee = Employee::where('user_id', $userId)->first();
//         $employee->number = $this->model->number;
//         $employee->dob = $this->model->dob;
//         $employee->code = $this->model->code;
//         $employee->designation = $this->model->designation;
//         $employee->address = $this->model->address;
//         $employee->save();
//         // update role
//         DB::table('role_user')->where('user_id', $userId)->update(['role_id' => $this->request->role_id]);
//         // update role
//         // EmployeeShift::where('employee_id', $this->model->id)->update(['shift_id' => $this->request->shift_id]);
//     }
//
//     public function editing()
//     {
//         $userId = $this->model->user_id;
//         $user = User::where('id', $userId)->get();
//         $this->viewData['name'] = $user[0]->name;
//         $this->viewData['email'] = $user[0]->email;
//         $this->viewData['password'] = $user[0]->password;
//         $this->viewData['picture'] = $user[0]->picture;
//
//         //get own table data
//         $this->viewData['id'] = $this->model->id;
//         $this->viewData['number'] = $this->model->number;
//         $this->viewData['dob'] = $this->model->dob;
//
// //        $this->viewData['code'] = $this->model->code;
//
//
//         $this->viewData['address'] = $this->model->address;
//
//         //get shift
//         $companyId = Company::where('user_id', Auth::user()->id)->first()['id'];
// //        dd($companyId);
//         //
//         $this->viewData['designation'] = $this->model->designation;
//         // $designation_name = Designation::where('company_id',$companyId)->get();
//
//         // $this->viewData['designation'] = $designation_name;
//
//         $companyId = Company::where('user_id', Auth::user()->id)->first()['id'];
//
// //         $shift = Shift::where('company_id', $companyId)->get();
// // //        dd($shift);
// //         $this->viewData['shift'] = $shift;  //input form sift value
//
//         //update role
//         $data = DB::table('role_user')->where('user_id', $userId)->get();
//         $this->viewData['role_id'] = $data[0]->role_id;
//     }

    public function querying()
    {
        if (\Entrust::hasRole('admin')) {
            $this->dataTableQuery->latest();
        } elseif (\Entrust::hasRole('client')) {
            $company = Client::where('user_id', Auth::user()->id)->first();
            $this->dataTableQuery->latest()->where('client_id', $company->id);
        }elseif (\Entrust::hasRole('bdthai')) {
            $this->dataTableQuery->latest()->where('client_id', '18');
        } elseif (\Entrust::hasRole('employees')) {
            $this->dataTableQuery->latest()->where('user_id', Auth::user()->id);
        }
    }

    public function creating()
    {
        $this->viewData['form']
            ->add('name', [
                'label' => 'Name',
                'type' => 'text'
            ])->add('email', [
                'label' => 'EMAIL',
                'type' => 'text'
            ])->add('password', [
                'label' => 'PASSWORD',
                'type' => 'text'
            ]);
        // ------------for shift-----------------
        // $html = '
        //     <div class="col-md-6">
        //         <div class="form-group">
        //             <label class="control-label">SHIFT</label>
        //             <select name="shift_id" class="form-control select2">%s</select>
        //         </div>
        //     </div>';
        // $companyId = Supervisor::where('user_id', Auth::user()->id)->first()['id'];
        // $options = Helper::collectionToOptions(Shift::where('company_id', $companyId)->get(),
        //     ['id', 'title']);
        // $this->viewData['form']->addHtml('shift_id', sprintf($html, $options));
//----------------------for designation ---------------
        // $html = '
        //     <div class="col-md-6">
        //         <div class="form-group ">
        //             <label class="control-label">DESIGNATION</label>
        //             <select name="designation_id" class="form-control select2">%s</select>
        //         </div>
        //     </div>';
        // $companyId = Company::where('user_id', Auth::user()->id)->first()['id'];
        // $options = Helper::collectionToOptions(Designation::where('company_id', $companyId)->get(),
        //     ['id', 'designation']);
        // $this->viewData['form']->addHtml('designation', sprintf($html, $options));
    }

    public function storing()
    {
        //get user id
        $loginId = Auth::user()->id;
        // get company id
        $company = Client::where('user_id', $loginId)->first();
        if ($company == '') {
            Session()->flash('warning', 'Insert Company Information First');
            return redirect()->back();
//            return Redirect::route('companies');
            die;
        } else {
            $companyId = $company->id;
        }
        //get emp name/email/pass
        $name = $this->request->name;
        $email = $this->request->email;
        $pass = $this->request->password;
        // $picture = $this->request->file('picture')->store('picture');
        //store user info into user tbl
        $password = Hash::make($pass);
        $data = array("name" => $name, "email" => $email, "password" => $password);
        $empUId = User::create($data);
        $employeeUserId = $empUId->id;

        // get other information
        //$invitation_id = $this->request->invitation_id;
        $phone_number = $this->request->phone_number;
        $role_supervisor_id = $this->request->role_supervisor_id;

        $seed = str_split('ABCDEFGHIJKLMNOPQRSTUVWXYZ'
                 .'0123456789'); // and any other characters
        shuffle($seed);
        $code = '';
        foreach (array_rand($seed, 6) as $k) $code .= $seed[$k];
        //generate code
        // $name = substr(Auth::user()->name, 0, 4);
        // $range = substr(md5(rand()), 0, 6);
        // $code = $name . '-' . $range;
//        $code = $this->request->code;
      //  $address = $this->request->address;
//        $designation = $this->request->designation;

        $formdata = array(
            "user_id" => $employeeUserId,
            "client_id" => $companyId,
            "code" => $code,
            "role_supervisor_id" => $role_supervisor_id,
            "phone_number" => $phone_number
        );
        $employee = Supervisor::create($formdata);
        $emp_id = $employee->id;
        //create this user define role
        // $role = $this->request->role;
        // DB::table('role_user')->insert(['user_id' => $employeeUserId, 'role_id' => $role]);
        DB::table('role_user')->insert(['user_id' => $employeeUserId, 'role_id' => 6]);
        Session()->flash('message', 'Your data has been inserted');
        return redirect()->back();
        // $shift_id = $this->model->shift_id;
        // $designation_id = $this->request->designation_id;
        // // input employee shift
        // // employe id-> it's employee generate id
        // $data = array(
        //     "employee_id" => $emp_id,
        //     "shift_id" => $shift_id
        // );
        // EmployeeShift::create($data);
        // //store designation
        // $designation = array(
        //     "employee_id" => $emp_id,
        //     "designation_id" => $designation_id
        // );
        // EmployeeDesignation::create($designation);
        //
        // Session()->flash('message', 'Your data has been inserted');
        // return redirect()->back();
    }
}
