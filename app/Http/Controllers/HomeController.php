<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Payment;
use App\Receipt;
use App\Client;
use App\EndUser;
use App\VisitDetail;
use App\Supervisor;
use Carbon\Carbon;
use DB;
use App\Status;
use Illuminate\Support\Facades\Auth;
use Rashidul\RainDrops\Facades\DataTable;
use App\User;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (\Entrust::hasRole('admin')) {

            $data = Client::all()->first();
            $companyId = $data->id;
            //get attendance 1 milinial
           // $attendance1 = Attendance::where('company_id', $companyId)->get();
             $location1 = Receipt::all();

            $totalCompany = Client::all()->count();
            $totalEmployee = EndUser::all()->count();
            $totalactive_status = Client::where('status', '1')->count();
            $totinalactive_status = Client::where('status', '2')->count();
            $Receipt = Receipt::all();
            $totalpayment = collect($Receipt)->sum('total_amount');
            $paid = collect($Receipt)->sum('paid');
            $due = collect($Receipt)->sum('due');
            $totalactive_enduser = EndUser::where('status', '1')->count();
            $totinalactive_enduser = EndUser::where('status', '2')->count();
            // $now = Carbon::now();
            // $current_date = $now->todateString();
            // $online = DB::table('statuses')->Where('created_at', 'LIKE', '%' . $current_date . '%')->where('mood', 1)->select('mood')->get()->toArray();
            // $offline = DB::table('statuses')->Where('created_at', 'LIKE', '%' . $current_date . '%')->where('mood', 2)->select('mood')->get()->toArray();
            // $online_count =count($online);
            // $offline_count =count($offline);
            // dd($totalEmployee);

            $userId = $data->user_id;

            // get company 1 name
            $findCompany = User::find($userId);


            // get company 2 id
            $companyId = $data->id;
            // get company 2 name
            $companyName1 = $findCompany->name;
            $userId = $data->user_id;
            $findCompany2 = User::find($userId);
            // $companyName2 = $findCompany2->name;

            // get attendance 2 shahabag
            //$attendance2 = Attendance::where('company_id', $companyId)->get();

            return view('home',
                [
                     'companyName1' => $companyName1,
                     'location1' => $location1,
                     'totalpayment' => $totalpayment,
                     'paid' => $paid,
                     'due' => $due,
                     'totinalactive_status' => $totinalactive_status,
                     'totalstatus' => $totalactive_status,
                     'totalactive_enduser' => $totalactive_enduser,
                     'totinalactive_enduser' => $totinalactive_enduser,
                     'totalCompany' => $totalCompany,
                     'totalEmployee' => $totalEmployee,


                ]);
        } elseif (\Entrust::hasRole('viewer')) {
          $data = Client::all()->first();
          $companyId = $data->id;
          //get attendance 1 milinial
         // $attendance1 = Attendance::where('company_id', $companyId)->get();
           $location1 = Receipt::all();

          $totalCompany = Client::all()->count();
          $totalEmployee = EndUser::all()->count();
          $totalactive_status = Client::where('status', '1')->count();
          $totinalactive_status = Client::where('status', '2')->count();
          $Receipt = Receipt::all();
          $totalpayment = collect($Receipt)->sum('total_amount');
          $paid = collect($Receipt)->sum('paid');
          $due = collect($Receipt)->sum('due');
          $totalactive_enduser = EndUser::where('status', '1')->count();
          $totinalactive_enduser = EndUser::where('status', '2')->count();
          // $now = Carbon::now();
          // $current_date = $now->todateString();
          // $online = DB::table('statuses')->Where('created_at', 'LIKE', '%' . $current_date . '%')->where('mood', 1)->select('mood')->get()->toArray();
          // $offline = DB::table('statuses')->Where('created_at', 'LIKE', '%' . $current_date . '%')->where('mood', 2)->select('mood')->get()->toArray();
          // $online_count =count($online);
          // $offline_count =count($offline);
          // dd($totalEmployee);

          $userId = $data->user_id;

          // get company 1 name
          $findCompany = User::find($userId);


          // get company 2 id
          $companyId = $data->id;
          // get company 2 name
          $companyName1 = $findCompany->name;
          $userId = $data->user_id;
          $findCompany2 = User::find($userId);
          // $companyName2 = $findCompany2->name;

          // get attendance 2 shahabag
          //$attendance2 = Attendance::where('company_id', $companyId)->get();

          return view('home',
              [
                   'companyName1' => $companyName1,
                   'location1' => $location1,
                   'totalpayment' => $totalpayment,
                   'paid' => $paid,
                   'due' => $due,
                   'totinalactive_status' => $totinalactive_status,
                   'totalstatus' => $totalactive_status,
                   'totalactive_enduser' => $totalactive_enduser,
                   'totinalactive_enduser' => $totinalactive_enduser,
                   'totalCompany' => $totalCompany,
                   'totalEmployee' => $totalEmployee,


              ]);

        }elseif (\Entrust::hasRole('client')) {
          $company_id = Client::whereUser_id(Auth::user()->id)->get()->first();
          $companyId = $company_id->id;
          //get attendance 1 milinial
         // $attendance1 = Attendance::where('company_id', $companyId)->get();
           $location1 = EndUser::where('client_id', $companyId)->get();

          $totalCompany = Client::all()->count();
          $totalEmployee = EndUser::where('client_id', $companyId)->count();
          $totalSupervisor = Supervisor::where('client_id', $companyId)->count();
          $total_amount = VisitDetail::where('client_id',$companyId )->sum('amount');

          $totalactive_status = Client::where('status', '1')->count();
          $totinalactive_status = Client::where('status', '2')->count();
          $Receipt = Receipt::all();
          $totalpayment = collect($Receipt)->sum('total_amount');
          $paid = collect($Receipt)->sum('paid');
          $due = collect($Receipt)->sum('due');
          $totalactive_enduser = EndUser::where('status', '1')->count();
          $totinalactive_enduser = EndUser::where('status', '2')->count();
          // $now = Carbon::now();
          // $current_date = $now->todateString();
          // $online = DB::table('statuses')->Where('created_at', 'LIKE', '%' . $current_date . '%')->where('mood', 1)->select('mood')->get()->toArray();
          // $offline = DB::table('statuses')->Where('created_at', 'LIKE', '%' . $current_date . '%')->where('mood', 2)->select('mood')->get()->toArray();
          // $online_count =count($online);
          // $offline_count =count($offline);
          // dd($totalEmployee);

          //$userId = $data->user_id;

          // get company 1 name
        //  $findCompany = User::find($userId);


          // get company 2 id
        //  $companyId = $data->id;
          // get company 2 name
          // $companyName1 = $findCompany->name;
          // $userId = $data->user_id;
          // $findCompany2 = User::find($userId);
          // $companyName2 = $findCompany2->name;

          // get attendance 2 shahabag
          //$attendance2 = Attendance::where('company_id', $companyId)->get();

          return view('home',
              [
                   //'companyName1' => $companyName1,
                   'location1' => $location1,
                   'totalpayment' => $totalpayment,
                   'paid' => $paid,
                   'due' => $due,
                   'totinalactive_status' => $totinalactive_status,
                   'totalstatus' => $totalactive_status,
                   'totalactive_enduser' => $totalactive_enduser,
                   'totinalactive_enduser' => $totinalactive_enduser,
                   'totalCompany' => $totalCompany,
                   'totalEmployee' => $totalEmployee,
                   'totalSupervisor' => $totalSupervisor,
                   'total_amount' => $total_amount,


              ]);

        }elseif (\Entrust::hasRole('amrit')) {
          $company_id = Client::whereUser_id(Auth::user()->id)->get()->first();
        //  $companyId = $company_id->id;
          //get attendance 1 milinial
         // $attendance1 = Attendance::where('company_id', $companyId)->get();
           $location1 = EndUser::where('client_id','7')->get();

          $totalCompany = Client::all()->count();
          $totalEmployee = EndUser::where('client_id', '7')->count();
          $totalSupervisor = Supervisor::where('client_id', '7')->count();
          $total_amount = VisitDetail::where('client_id','7')->sum('amount');

          $totalactive_status = Client::where('status', '1')->count();
          $totinalactive_status = Client::where('status', '2')->count();
          $Receipt = Receipt::all();
          $totalpayment = collect($Receipt)->sum('total_amount');
          $paid = collect($Receipt)->sum('paid');
          $due = collect($Receipt)->sum('due');
          $totalactive_enduser = EndUser::where('status', '1')->count();
          $totinalactive_enduser = EndUser::where('status', '2')->count();

          return view('home',
              [
                   //'companyName1' => $companyName1,
                   'location1' => $location1,
                   'totalpayment' => $totalpayment,
                   'paid' => $paid,
                   'due' => $due,
                   'totinalactive_status' => $totinalactive_status,
                   'totalstatus' => $totalactive_status,
                   'totalactive_enduser' => $totalactive_enduser,
                   'totinalactive_enduser' => $totinalactive_enduser,
                   'totalCompany' => $totalCompany,
                   'totalEmployee' => $totalEmployee,
                   'totalSupervisor' => $totalSupervisor,
                   'total_amount' => $total_amount,


              ]);

        }elseif (\Entrust::hasRole('nourish')) {
          $company_id = Client::whereUser_id(Auth::user()->id)->get()->first();
        //  $companyId = $company_id->id;
          //get attendance 1 milinial
         // $attendance1 = Attendance::where('company_id', $companyId)->get();
           $location1 = EndUser::where('client_id','5')->get();

          $totalCompany = Client::all()->count();
          $totalEmployee = EndUser::where('client_id', '5')->count();
          $totalSupervisor = Supervisor::where('client_id', '5')->count();
          $total_amount = VisitDetail::where('client_id','5')->sum('amount');

          $totalactive_status = Client::where('status', '1')->count();
          $totinalactive_status = Client::where('status', '2')->count();
          $Receipt = Receipt::all();
          $totalpayment = collect($Receipt)->sum('total_amount');
          $paid = collect($Receipt)->sum('paid');
          $due = collect($Receipt)->sum('due');
          $totalactive_enduser = EndUser::where('status', '1')->count();
          $totinalactive_enduser = EndUser::where('status', '2')->count();

          return view('home',
              [
                   //'companyName1' => $companyName1,
                   'location1' => $location1,
                   'totalpayment' => $totalpayment,
                   'paid' => $paid,
                   'due' => $due,
                   'totinalactive_status' => $totinalactive_status,
                   'totalstatus' => $totalactive_status,
                   'totalactive_enduser' => $totalactive_enduser,
                   'totinalactive_enduser' => $totinalactive_enduser,
                   'totalCompany' => $totalCompany,
                   'totalEmployee' => $totalEmployee,
                   'totalSupervisor' => $totalSupervisor,
                   'total_amount' => $total_amount,


              ]);

        }elseif (\Entrust::hasRole('alpha')) {
          $company_id = Client::whereUser_id(Auth::user()->id)->get()->first();
        //  $companyId = $company_id->id;
          //get attendance 1 milinial
         // $attendance1 = Attendance::where('company_id', $companyId)->get();
           $location1 = EndUser::where('client_id','24')->get();

          $totalCompany = Client::all()->count();
          $totalEmployee = EndUser::where('client_id', '24')->count();
          $totalSupervisor = Supervisor::where('client_id', '24')->count();
          $total_amount = VisitDetail::where('client_id','24')->sum('amount');

          $totalactive_status = Client::where('status', '1')->count();
          $totinalactive_status = Client::where('status', '2')->count();
          $Receipt = Receipt::all();
          $totalpayment = collect($Receipt)->sum('total_amount');
          $paid = collect($Receipt)->sum('paid');
          $due = collect($Receipt)->sum('due');
          $totalactive_enduser = EndUser::where('status', '1')->count();
          $totinalactive_enduser = EndUser::where('status', '2')->count();

          return view('home',
              [
                   //'companyName1' => $companyName1,
                   'location1' => $location1,
                   'totalpayment' => $totalpayment,
                   'paid' => $paid,
                   'due' => $due,
                   'totinalactive_status' => $totinalactive_status,
                   'totalstatus' => $totalactive_status,
                   'totalactive_enduser' => $totalactive_enduser,
                   'totinalactive_enduser' => $totinalactive_enduser,
                   'totalCompany' => $totalCompany,
                   'totalEmployee' => $totalEmployee,
                   'totalSupervisor' => $totalSupervisor,
                   'total_amount' => $total_amount,


              ]);

        }elseif (\Entrust::hasRole('bdthai')) {
          $company_id = Client::whereUser_id(Auth::user()->id)->get()->first();
        //  $companyId = $company_id->id;
          //get attendance 1 milinial
         // $attendance1 = Attendance::where('company_id', $companyId)->get();
           $location1 = EndUser::where('client_id','18')->get();

          $totalCompany = Client::all()->count();
          $totalEmployee = EndUser::where('client_id', '18')->count();
          $totalSupervisor = Supervisor::where('client_id', '18')->count();
          $total_amount = VisitDetail::where('client_id','18')->sum('amount');

          $totalactive_status = Client::where('status', '1')->count();
          $totinalactive_status = Client::where('status', '2')->count();
          $Receipt = Receipt::all();
          $totalpayment = collect($Receipt)->sum('total_amount');
          $paid = collect($Receipt)->sum('paid');
          $due = collect($Receipt)->sum('due');
          $totalactive_enduser = EndUser::where('status', '1')->count();
          $totinalactive_enduser = EndUser::where('status', '2')->count();

          return view('home',
              [
                   //'companyName1' => $companyName1,
                   'location1' => $location1,
                   'totalpayment' => $totalpayment,
                   'paid' => $paid,
                   'due' => $due,
                   'totinalactive_status' => $totinalactive_status,
                   'totalstatus' => $totalactive_status,
                   'totalactive_enduser' => $totalactive_enduser,
                   'totinalactive_enduser' => $totinalactive_enduser,
                   'totalCompany' => $totalCompany,
                   'totalEmployee' => $totalEmployee,
                   'totalSupervisor' => $totalSupervisor,
                   'total_amount' => $total_amount,


              ]);

        }elseif (\Entrust::hasRole('getco')) {
          $company_id = Client::whereUser_id(Auth::user()->id)->get()->first();
        //  $companyId = $company_id->id;
          //get attendance 1 milinial
         // $attendance1 = Attendance::where('company_id', $companyId)->get();
           $location1 = EndUser::where('client_id','26')->get();

          $totalCompany = Client::all()->count();
          $totalEmployee = EndUser::where('client_id', '26')->count();
          $totalSupervisor = Supervisor::where('client_id', '26')->count();
          $total_amount = VisitDetail::where('client_id','26')->sum('amount');

          $totalactive_status = Client::where('status', '1')->count();
          $totinalactive_status = Client::where('status', '2')->count();
          $Receipt = Receipt::all();
          $totalpayment = collect($Receipt)->sum('total_amount');
          $paid = collect($Receipt)->sum('paid');
          $due = collect($Receipt)->sum('due');
          $totalactive_enduser = EndUser::where('status', '1')->count();
          $totinalactive_enduser = EndUser::where('status', '2')->count();

          return view('home',
              [
                   //'companyName1' => $companyName1,
                   'location1' => $location1,
                   'totalpayment' => $totalpayment,
                   'paid' => $paid,
                   'due' => $due,
                   'totinalactive_status' => $totinalactive_status,
                   'totalstatus' => $totalactive_status,
                   'totalactive_enduser' => $totalactive_enduser,
                   'totinalactive_enduser' => $totinalactive_enduser,
                   'totalCompany' => $totalCompany,
                   'totalEmployee' => $totalEmployee,
                   'totalSupervisor' => $totalSupervisor,
                   'total_amount' => $total_amount,


              ]);

        }elseif (\Entrust::hasRole('daimond')) {
          $company_id = Client::whereUser_id(Auth::user()->id)->get()->first();
        //  $companyId = $company_id->id;
          //get attendance 1 milinial
         // $attendance1 = Attendance::where('company_id', $companyId)->get();
           $location1 = EndUser::where('client_id','21')->get();

          $totalCompany = Client::all()->count();
          $totalEmployee = EndUser::where('client_id', '21')->count();
          $totalSupervisor = Supervisor::where('client_id', '21')->count();
          $total_amount = VisitDetail::where('client_id','21')->sum('amount');

          $totalactive_status = Client::where('status', '1')->count();
          $totinalactive_status = Client::where('status', '2')->count();
          $Receipt = Receipt::all();
          $totalpayment = collect($Receipt)->sum('total_amount');
          $paid = collect($Receipt)->sum('paid');
          $due = collect($Receipt)->sum('due');
          $totalactive_enduser = EndUser::where('status', '1')->count();
          $totinalactive_enduser = EndUser::where('status', '2')->count();

          return view('home',
              [
                   //'companyName1' => $companyName1,
                   'location1' => $location1,
                   'totalpayment' => $totalpayment,
                   'paid' => $paid,
                   'due' => $due,
                   'totinalactive_status' => $totinalactive_status,
                   'totalstatus' => $totalactive_status,
                   'totalactive_enduser' => $totalactive_enduser,
                   'totinalactive_enduser' => $totinalactive_enduser,
                   'totalCompany' => $totalCompany,
                   'totalEmployee' => $totalEmployee,
                   'totalSupervisor' => $totalSupervisor,
                   'total_amount' => $total_amount,


              ]);

        }elseif (\Entrust::hasRole('developer')) {
          $data = Client::all()->first();
          $companyId = $data->id;
          //get attendance 1 milinial
         // $attendance1 = Attendance::where('company_id', $companyId)->get();
           $location1 = Receipt::all();

          $totalCompany = Client::all()->count();
          $totalEmployee = EndUser::all()->count();
          $totalactive_status = Client::where('status', '1')->count();
          $totinalactive_status = Client::where('status', '2')->count();
          $Receipt = Receipt::all();
          $totalpayment = collect($Receipt)->sum('total_amount');
          $paid = collect($Receipt)->sum('paid');
          $due = collect($Receipt)->sum('due');
          $totalactive_enduser = EndUser::where('status', '1')->count();
          $totinalactive_enduser = EndUser::where('status', '2')->count();
          // $now = Carbon::now();
          // $current_date = $now->todateString();
          // $online = DB::table('statuses')->Where('created_at', 'LIKE', '%' . $current_date . '%')->where('mood', 1)->select('mood')->get()->toArray();
          // $offline = DB::table('statuses')->Where('created_at', 'LIKE', '%' . $current_date . '%')->where('mood', 2)->select('mood')->get()->toArray();
          // $online_count =count($online);
          // $offline_count =count($offline);
          // dd($totalEmployee);

          $userId = $data->user_id;

          // get company 1 name
          $findCompany = User::find($userId);


          // get company 2 id
          $companyId = $data->id;
          // get company 2 name
          $companyName1 = $findCompany->name;
          $userId = $data->user_id;
          $findCompany2 = User::find($userId);
          // $companyName2 = $findCompany2->name;

          // get attendance 2 shahabag
          //$attendance2 = Attendance::where('company_id', $companyId)->get();

          return view('home',
              [
                   'companyName1' => $companyName1,
                   'location1' => $location1,
                   'totalpayment' => $totalpayment,
                   'paid' => $paid,
                   'due' => $due,
                   'totinalactive_status' => $totinalactive_status,
                   'totalstatus' => $totalactive_status,
                   'totalactive_enduser' => $totalactive_enduser,
                   'totinalactive_enduser' => $totinalactive_enduser,
                   'totalCompany' => $totalCompany,
                   'totalEmployee' => $totalEmployee,


              ]);

        } elseif (\Entrust::hasRole('employees')) {
            //get designation
            $empData = Employee::where('user_id', Auth::user()->id)->get();
            // get emp attendance individual
            $empId = $empData[0]->id;
            $empAttendance = Attendance::where('employee_id', $empId)->get();
//            dd($empAttendance->status);
            return view('home',
                [
//                    'designation' => $designation,
                    'empData' => $empData,
                    'empAttendance' => $empAttendance,
                ]);
        } elseif (\Entrust::hasRole('viewer')) {
            $empData = Employee::where('user_id', Auth::user()->id)->get();
            // get emp attendance individual
            $empId = $empData[0]->id;
            $empAttendance = Attendance::where('employee_id', $empId)->get();
//            dd($empAttendance->status);
            return view('home',
                [
//                    'designation' => $designation,
                    'empData' => $empData,
                    'empAttendance' => $empAttendance,
                ]);
        }
    }
}
