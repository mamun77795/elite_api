<?php

namespace App\Http\Controllers;

use App\Designation;
use App\EmployeeDesignation;
use App\EmployeeShift;
use Auth;
use App\Client;
use App\EndUser;
use Rashidul\RainDrops\Controllers\BaseController;
use Rashidul\RainDrops\Form\Helper;
use Illuminate\Http\Request;
use App\User;
use DB;
use \Crypt;
use App\Supervisor;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class EndUserController extends BaseController
{

  protected $modelClass = EndUser::class;
  protected $dataTransformer = EmployeeTransformer::class;

  protected $editView = 'employees.custom-edit';

    // public function storing(){
    //
    //     $this->model->users_id = Auth::user()->id;
    // }
//     public function creating()
//     {
//         $this->viewData['form']
//             ->add('name', [
//                 'label' => 'Name',
//                 'type' => 'text'
//             ])->add('email', [
//                 'label' => 'EMAIL',
//                 'type' => 'text'
//             ])->add('role', [
//                 'label' => 'ROLE',
//                 "type" => "select",
//                 "options" => [
//                     '3' => 'EMPLOYEE',
// //                    '4' => 'MODERATOR',
//                     '5' => 'VIEWER',
//                 ],
//             ])->add('picture', [
//                 'label' => 'PICTURE',
//                 "type" => "image",
//                 "path" => "employee",
//                 "disk" => "custom",
//                 'classes' => 'custom-picture',
//                 'index' => true
//             ]);
//             }
            // public function updating()
            // {
            //     // get user id
            //     $userId = $this->model->user_id;
            //     // update user table
            //     $userId = $this->model->user_id;
            //     $user = user::findorFail($userId);
            //     $user->name = $this->request->name;
            //     $user->email = $this->request->email;
            //     $user->password = Hash::make($this->request->password);
            //     $user->save();
            //     // update employee table
            //     $employee = Employee::where('user_id', $userId)->first();
            //     $employee->number = $this->model->number;
            //     $employee->dob = $this->model->dob;
            //     $employee->designation = $this->model->designation;
            //     $employee->address = $this->model->address;
            //     $employee->save();
            //     // update role
            //     DB::table('role_user')->where('user_id', $userId)->update(['role_id' => $this->request->role_id]);
            //     // update role
            //     // EmployeeShift::where('employee_id', $this->model->id)->update(['shift_id' => $this->request->shift_id]);
            // }

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
        //         $this->viewData['designation'] = $this->model->designation;
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
        //         // $this->viewData['designation'] = $this->model->designation;
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
        //     public function storing()
        //     {
        //         //get user id
        //         $loginId = Auth::user()->id;
        //         // get company id
        //         $company = Company::where('user_id', $loginId)->first();
        //         if ($company == '') {
        //             Session()->flash('warning', 'Insert Company Information First');
        //             return redirect()->back();
        // //            return Redirect::route('companies');
        //             die;
        //         } else {
        //             $companyId = $company->id;
        //         }
        //         //get emp name/email/pass
        //         $name = $this->request->name;
        //         $email = $this->request->email;
        //         $pass = $this->request->number;
        //         $picture = $this->request->file('picture')->store('picture');
        //         //store user info into user tbl
        //         $password = Hash::make($pass);
        //         $data = array("name" => $name, "email" => $email, "password" => $password, "picture" => $picture);
        //         $empUId = User::create($data);
        //         $employeeUserId = $empUId->id;
        //
        //         // get other information
        //         $number = $this->request->number;
        //         $dob = $this->request->dob;
        //         $designation = $this->request->designation;
        //         //generate code
        //         $name = substr(Auth::user()->name, 0, 4);
        //         $range = substr(md5(rand()), 0, 6);
        //         $code = $name . '-' . $range;
        // //        $code = $this->request->code;
        //         $address = $this->request->address;
        // //        $designation = $this->request->designation;
        //
        //         $formdata = array(
        //             "user_id" => $employeeUserId,
        //             "company_id" => $companyId,
        //             "number" => $number,
        //             "dob" => $dob,
        //            "designation" => $designation,
        //             "address" => $address
        //         );
        //         $employee = Employee::create($formdata);
        //         $emp_id = $employee->id;
        //         //create this user define role
        //         $role = $this->request->role;
        //         DB::table('role_user')->insert(['user_id' => $employeeUserId, 'role_id' => $role]);
        //         $shift_id = $this->model->shift_id;
        //         // $designation_id = $this->request->designation_id;
        //         // input employee shift
        //         // employe id-> it's employee generate id
        //         $data = array(
        //             "employee_id" => $emp_id,
        //             "shift_id" => $shift_id
        //         );
        //         // EmployeeShift::create($data);
        //         // //store designation
        //         // $designation = array(
        //         //     "employee_id" => $emp_id,
        //         //     "designation_id" => $designation_id
        //         // );
        //         // EmployeeDesignation::create($designation);
        //         //
        //         // Session()->flash('message', 'Your data has been inserted');
        //         // return redirect()->back();
        //     }
    public function querying()
    {
        if (\Entrust::hasRole('admin')) {
            $this->dataTableQuery->latest();
            //$this->viewData['supervisors'] = 'Raju';
        } elseif (\Entrust::hasRole('client')) {
            $company_id = Client::whereUser_id(Auth::user()->id)->get()->first();
            $companyId = $company_id->id;
            $this->dataTableQuery->latest()->where('client_id', $companyId);
        } elseif (\Entrust::hasRole('bdthai')) {
            $this->dataTableQuery->latest()->where('client_id', '18');
        }elseif (\Entrust::hasRole('amrit')) {
            $this->dataTableQuery->latest()->where('client_id', '7');
        }elseif (\Entrust::hasRole('daimond')) {
            $this->dataTableQuery->latest()->where('client_id', '21');
        }elseif (\Entrust::hasRole('developer')) {
//            $emp = Employee::where('user_id', Auth::user()->id)->get();
//            $companyId = $emp[0]->company_id;
//            $company = Company::find($companyId)->get();
//            $userId = $company[0]->user_id;
//            $this->dataTableQuery->latest()->where('user_id', $userId);
              $this->dataTableQuery->latest()->where('client_id', 5);
        }
    }
    public function setup()
    {
        if (\Entrust::hasRole('admin')) {
        } elseif (\Entrust::hasRole('client')) {
            $this->crudAction->restrictActions(['add', 'edit','delete']);
          //  $this->crudAction->permitActions(['add','view','edit','delete']);
        } elseif (\Entrust::hasRole('bdthai')) {
            $this->crudAction->restrictActions(['add', 'edit','delete']);
        }elseif (\Entrust::hasRole('amrit')) {
            $this->crudAction->restrictActions(['add', 'edit','delete']);
        }elseif (\Entrust::hasRole('daimond')) {
            $this->crudAction->restrictActions(['add', 'edit','delete']);
        } elseif (\Entrust::hasRole('employees')) {
            $this->crudAction->restrictActions(['edit','delete','view']);
        } elseif (\Entrust::hasRole('viewer')) {
            //$this->crudAction->restrictActions(['add', 'delete', 'edit']);
            $this->crudAction->restrictActions(['edit','delete','add']);
        }elseif (\Entrust::hasRole('developer')) {
          $this->crudAction->restrictActions(['edit','delete','add']);
            //$this->crudAction->restrictActions(['add', 'delete', 'edit']);
        }

    }

    public function testSearch(Request $request)
    {
      return 1;
    }

    // public function postSearch(Request $request)
    // {
    //   $endUserObj   = new EndUser();
    //   $from         = $request->from;
    //   $to           = $request->to;
    //
    //   $supervisorID   = $request->supervisor;
    //
    //   // $endUsers     = $endUserObj->where('supervisor_id', $supervisorID)
    //   //               ->whereDate('created_at', '>=', $from)
    //   //               ->whereDate('created_at', '<=', $to)
    //   //               ->get();
    //   $supervisorName   = '';
    //   if($supervisorID == 'NULL'){
    //     $endUsers     = $endUserObj->whereDate('created_at', '>=', $from.' 00:00:00')
    //                   ->whereDate('created_at', '<=', $to.' 11:59:59')
    //                   ->WhereNull('supervisor_id')
    //                   ->get();
    //
    //     $supervisorName = 'Admin';
    //   }
    //   else{
    //     $endUsers     = $endUserObj->whereDate('created_at', '>=', $from.' 00:00:00')
    //                   ->whereDate('created_at', '<=', $to.' 11:59:59')
    //                   ->where('supervisor_id', $supervisorID)
    //                   ->get();
    //
    //     $superVisorObj  = new Supervisor();
    //     $supervisor     = $superVisorObj->find($supervisorID);
    //
    //     $userObj        = new User();
    //     $user           = $userObj->find($supervisor->user_id);
    //
    //     $supervisorName = $user->name;
    //   }
    //
    //
    //   // return $request->all();
    //   // return $supervisorName;
    //   return view('search-result.enduser', compact('supervisorName', 'endUsers', 'from', 'to'));
    // }


}
