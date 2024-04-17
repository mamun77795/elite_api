<?php

namespace App\Http\Controllers;

use App\Attendance;
use App\Company;
use App\Employee;
use App\EmployeeShift;
use App\Shift;
use App\Calendar;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Carbon;
use Illuminate\Support\Facades\Hash;

class HistoryControllerApi extends Controller
{
    public function history(Request $request)
    {
        $companyId = $request->get('company_id');
        $employee_list = Employee::where('company_id', $companyId)->get();
        //get current date
        $now = Carbon::now();
        $current_date = $now->todateString();

        $allData = [];
        foreach ($employee_list as $employee) {
            $user_id = $employee['user_id'];
            $user = User::where('id', $user_id)->select('name', 'picture', 'email')->get()->first();
            //
            $lastAttendance = Attendance::where('employee_id', $employee['id'])->Where('date_time', 'LIKE', '%' . $current_date . '%')->select('employee_id', 'date_time', 'status')->get()->last();
            $count = count($lastAttendance);
            $entry_status = 'ABSENT';
            $entry_status_color = '#D50000';
            $time_status = '';
            $time_status_color = '';

            $shift_ids = EmployeeShift::where('employee_id', $employee['id'])->select('shift_id')->get()->last();
            $start_time = Shift::whereId($shift_ids['shift_id'])->select('start_time')->get()->last()['start_time'];
            $end_time = Shift::whereId($shift_ids['shift_id'])->select('end_time')->get()->last()['end_time'];
       // dd($start_time);
            if ($count > 0) {
                $status = $lastAttendance['status'];
                $entry_status = $status == 1 ? 'AT OFFICE' : 'OUT OF OFFICE';
                $entry_status_color = $status == 1 ? '#43A047' : '#EF6C00';

                $my_date_time = $lastAttendance['date_time'];
                $my_source = Carbon::parse($my_date_time);
                $target_source = Carbon::parse($start_time);
                $time_diff_in_minutes = $target_source->diffInMinutes($my_source, false);
                if($status == 1){
                    $time_status = $time_diff_in_minutes > 0 ? 'LATE' : 'INTIME';
                    $time_status_color = $time_diff_in_minutes > 0 ? '#D50000' : '#43A047';
                }
            }

            $allHistory = Attendance::where('employee_id', $employee['id'])->Where('date_time', 'LIKE', '%' . $current_date . '%')->select('date_time', 'status')->get();
            $details = [];
            foreach ($allHistory as $history) {
                $time = $history['date_time'];
                $data = [
                    'target_time' => $history['status'] == 1 ? Carbon::parse($start_time)->toTimeString() : Carbon::parse($end_time)->toTimeString(),
                    'inputted_time' => Carbon::parse($time)->toTimeString(),
                    'status' => $history['status'] == 1 ? 'ENTRY' : 'EXIT',
                    'status_color' => $history['status'] == 1 ? '#43A047' : '#D50000',
                ];
                $details[] = $data;
                // dd($details);
            }

            $data = [
                'id' => $employee['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'image_url' => 'http://ebusiness-solution.com/etracker/storage/app/public/' . $user['picture'],
                'designation' => $employee['designation'],
                'phone_number' => $employee['number'],
                'address' => $employee['address'],
                'entry_status' => $entry_status,
                'entry_status_color' => $entry_status_color,
                'time_status' => $time_status,
                'time_status_color' => $time_status_color,
                'details' => $details,
            ];
            $allData[] = $data;
        }
        return response()->json(['data' => $allData], 200);
    }
    public function history_details(Request $request){
          $companyId = $request['company_id'];
          $employeeId = $request['employee_id'];

          $now = Carbon::now();
          $month = $now->year.'-'.$now->month;
          $start = Carbon::parse($month)->startOfMonth();
          $end = Carbon::parse($month)->endOfMonth();
          $all_dates = [];

          // get all dates
          while ($start->lte($end)) {
            $all_dates[] = $start->copy();
            $start->addDay();
          }

          $allData = [];
          $day_type = 'WEEKEND DAY';

          $attendance_list = Attendance::where('employee_id', $employeeId)->select('date_time')->get()->toArray();
          //dd($attendance_list);
          $event_list = Calendar::where('company_id', $companyId)->select('date','title')->get()->toArray();
          //dd($event_list);
          foreach ($all_dates as $date) {

            $day_type = 'WORKING DAY';
            $day_type_color = '#43A047';
            $attendance_status = 'ABSENT';
            $attendance_status_color = '#D50000';

            foreach ($event_list as $event) {

              foreach ($attendance_list as $attendance) {
                  $attendance_date = Carbon::parse($attendance['date_time']);
                  if($date->todateString() == $attendance_date->todateString()){
                    $attendance_status = 'PRESENT';
                    $attendance_status_color = '#43A047';
                    break;
                  }
              }

              if($event['date'] == $date->todateString()){
                $day_type = $event['title'];
                $day_type_color = '#D50000';
                break;
              }
            }

            $data = [
                'date' => $date->todateString(),
                'day' => $date->format('l'),
                'day_type' => $day_type,
                'day_type_color' => $day_type_color,
                'attendance_status' => $attendance_status,
                'attendance_status_color' => $attendance_status_color,
            ];
            $allData[] = $data;
          }


          //$entry_time = Attendance::whereCompany_id($companyId)->whereEmployee_id($employeeId)->

          return response()->json(['data' => $allData], 200);
    }
}
