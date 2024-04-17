<?php

namespace App\Http\Controllers;

use Auth;
use App\Depo;
use App\Pack;
use App\User;
use App\Point;
use App\Client;
use App\Device;
use App\BarCode;
use App\EndUser;
use App\Product;
use App\SubGroup;
use App\BaseGroup;
use App\Constants;
use App\QrProduct;
use App\ScanPoint;
use Carbon\Carbon;
use App\BonusPoint;
use App\DealerUser;
use App\PlaceOrder;
use App\EliteMember;
use App\PainterUser;
use App\ProductType;
use App\RedeemPoint;
use App\Notification;
use App\MacroDistrict;
use App\MacroDivision;
use App\VolumeTranfer;
use App\MessageController;
use App\DealerNegetiveStock;
use App\DealerNegetiveValue;
use App\PointTransfer;
use App\Testing;
use App\VersionNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Edujugon\PushNotification\PushNotification;
use Illuminate\Support\Str;
use Rashidul\RainDrops\Controllers\BaseController;

class DealerUserControllerApi extends BaseController
{

//            public function __construct()
//            {
//                $method = Route::current()->getActionMethod();
//                info($method);
//            }
    protected $modelClass = DealerUser::class;

//    public function DealerSMS($user_type, $user_id)
//    {
//        if($user_type=='dealer'){
//            $scanpoint_year = ScanPoint::where('painter_id', $user_id)
//                ->get()->toArray();
//            $redeems = RedeemPoint::where('painter_id', $user_id)
//                ->get()->toArray();
//            $bonus_point = BonusPoint::where('painter_id', $painter['id'])->where('soft_delete', 1)
//                ->sum('bonus_point');
//        }
//        if($user_type=='painter'){
//
//        }
//        $scanpoint_year = ScanPoint::where('painter_id', $painter['id'])
//            ->get()->toArray();
//        $redeems = RedeemPoint::where('painter_id', $painter['id'])
//            ->get()->toArray();
//        $bonus_point = BonusPoint::where('painter_id', $painter['id'])->where('soft_delete', 1)
//            ->sum('bonus_point');
//
//        $all_total = 0;
//        $total_redeem_point = 0;
//        $total_volume_point = 0;
//        $total_scan_point = 0;
//        foreach ($scanpoint_year as $scan_point) {
//            $total_scan_point += $scan_point['point'];
//        }
//        $volumes = VolumeTranfer::where('painter_id', $painter['id'])->where('status', '!=', 2)
//            ->select('id', 'painter_point')->get()->toArray();
//        foreach ($volumes as $volume) {
//
//            $total_volume_point += $volume['painter_point'];
//        }
//        $all_total = $total_volume_point + $total_scan_point + $bonus_point;
//        $sms = 'Your Token No ' . $request->content . ' point ' .  $token_point->point . ', Total Point ' . $all_total . '. Somriddhi Club -Elite Paint.';
//        $sms_response = $this->sendSMS($sms, $request->from);
//        DB::table('testings')->where('id', $testings->id)->update([
//            'response' => $sms_response,
//            'sms' => $sms,
//
//        ]);
//    }
    public function generateRandomString($prefix = false, $length = 6)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        if ($prefix) {
            $randomString = $prefix . '-' . $randomString;
        }
        $exists = DB::table('volume_tranfers')->where('code2', $randomString)->exists();
        if ($exists) {
            $this->generateRandomString();
        }
        return strtoupper($randomString);
    }

    public function generateRandomUserToken($user,$user_type = 'dealer')
    {
        $randomString = $user->id.strtoupper($user_type).$user->phone;
        if ($user_type == 'dealer'){
            $exists = DB::table('dealer_users')->where('id','!=',$user->id)->where('user_token', $randomString)->exists();
        }elseif($user_type == 'painter'){
            $exists = DB::table('painter_users')->where('id','!=',$user->id)->where('user_token', $randomString)->exists();
        }
        if ($exists) {
            $randomString = $randomString.$user->phone;
        }
        return $randomString;
    }

    //painter information api
    public function all_painter_info(Request $request)
    {
        set_time_limit(6000);
        ini_set("pcre.backtrack_limit", "100000000");
        //get painter code as parameter
        $painter_code = $request->painter_code;

        $now = Carbon::now();
        $month = $now->year . '-' . $now->month;
        $year = $now->year;
        $start = Carbon::parse($month)->startOfMonth()->todateString();
        $start_year = Carbon::parse($year)->startOfYear();
        $end = Carbon::parse($month)->today()->todateString();
        $start_date = $start . ' 00:00:00';
        $end_date = $end . ' 23:59:59';
        $last_year = $year - 1;

        //get the painter information from painter table
        $dealer = PainterUser::where('status', 1)->where('code', $painter_code)->where('soft_delete', 1)->where('disable', 1)->select(
            'id',
            'password',
            'status',
            'code',
            'name',
            'phone',
            'email',
            'rocket_number',
            'picture_type',
            'nid_picture_type',
            'nid',
            'depo',
            'dealer_id',
            'nid_picture',
            'division_id',
            'district_id',
            'thana_id',
            'picture',
            'alternative_number'
        )->get()->last();
        //get painter last year bonus point information
        $bonus_point_last_year = BonusPoint::where('painter_id', $dealer['id'])
            ->where('created_at', 'LIKE', '%' . $last_year . '%')->where('soft_delete', 1)
            ->select('id', 'bonus_point')->sum('bonus_point');
        //get painter this year bonus point information
        $bonus_point_year = BonusPoint::where('painter_id', $dealer['id'])
            ->where('created_at', 'LIKE', '%' . $year . '%')->where('soft_delete', 1)
            ->select('id', 'bonus_point')->sum('bonus_point');
        //get painter this year scan point information
        $scanpoint_year = ScanPoint::where('painter_id', $dealer['id'])
            ->where('created_at', 'LIKE', '%' . $year . '%')
            ->select('id', 'bar_code_id')->get()->toArray();
        //get painter this year redeem point information
        $redeems = RedeemPoint::where('painter_id', $dealer['id'])
            ->where('created_at', 'LIKE', '%' . $year . '%')
            ->select('id', 'redeem_point')->get()->toArray();
        //get painter this year volume point information
        $volumes = VolumeTranfer::where('painter_id', $dealer['id'])->where('status', '!=', 2)->where('soft_delete', 1)
            ->where('created_at', 'LIKE', '%' . $year . '%')
            ->select('id', 'painter_point')->get()->toArray();
        //get painter last year scan point information
        $scanpoint_last_year = ScanPoint::where('painter_id', $dealer['id'])
            ->where('created_at', '>=', Carbon::now()->subYear()->startOfYear())
            ->where('created_at', '<=', Carbon::now()->subYear()->endOfYear())
            ->select('id', 'bar_code_id')->get()->toArray();
        //get painter last year redeem point information
        $redeems_last_year = RedeemPoint::where('painter_id', $dealer['id'])
            ->where('created_at', '>=', Carbon::now()->subYear()->startOfYear())
            ->where('created_at', '<=', Carbon::now()->subYear()->endOfYear())
            ->select('id', 'redeem_point')->get()->toArray();
        //get painter last year volume point information
        $volumes_last_year = VolumeTranfer::where('painter_id', $dealer['id'])->where('status', '!=', 2)->where('soft_delete', 1)
            ->where('created_at', '>=', Carbon::now()->subYear()->startOfYear())
            ->where('created_at', '<=', Carbon::now()->subYear()->endOfYear())
            ->select('id', 'painter_point')->get()->toArray();
        $all_total = 0;
        $total_redeem_point = 0;
        $total_volume_point = 0;
        $total_scan_point = 0;

        //get total redeem point this year by foreach loop
        foreach ($redeems as $redeem) {

            $total_redeem_point += $redeem['redeem_point'];
        }
        //get total volume point this year by foreach loop
        foreach ($volumes as $volume) {

            $total_volume_point += $volume['painter_point'];
        }
        //get total scan point this year by foreach loop
        foreach ($scanpoint_year as $scan_point) {
            $barcode = BarCode::where('id', $scan_point['bar_code_id'])
                ->select('id', 'product_id', 'point')->get()->last();

            $total_scan_point += $barcode['point'];
        }
        //this year total point
        $all_total = $total_volume_point + $total_scan_point + $bonus_point_year;
        //available point
        $available_points = $all_total - $total_redeem_point;

        $all_total_last_year = 0;
        $total_redeem_point_last_year = 0;
        $total_volume_point_last_year = 0;
        $total_scan_point_last_year = 0;

        //get total redeem point last year by foreach loop
        foreach ($redeems_last_year as $redeem) {

            $total_redeem_point_last_year += $redeem['redeem_point'];
        }
        //get total volume point last year by foreach loop
        foreach ($volumes_last_year as $volume) {

            $total_volume_point_last_year += $volume['painter_point'];
        }
        //get total scan point last year by foreach loop
        foreach ($scanpoint_last_year as $scan_point) {
            $barcode = BarCode::where('id', $scan_point['bar_code_id'])
                ->select('id', 'product_id', 'point')->get()->last();

            $total_scan_point_last_year += $barcode['point'];
        }
        //last year total point
        $all_total_last_year = $total_volume_point_last_year + $total_scan_point_last_year + $bonus_point_last_year;
        //find painter division
        $division = MacroDivision::where('id', $dealer['division_id'])->orderBy('created_at', 'desc')->select('id', 'division', 'created_at')->get()->first();
        //find dealer information
        $d = DealerUser::where('id', $dealer['dealer_id'])
            ->select(
                'id',
                'password',
                'status',
                'code',
                'name',
                'phone',
                'email',
                'depo',
                'nid',
                'nid_picture',
                'division_id',
                'district_id',
                'thana_id',
                'picture',
                'alternative_number'
            )->get()->last();
        //find painter district
        $district = MacroDistrict::where('id', $dealer['district_id'])->orderBy('created_at', 'desc')->select('id', 'district', 'created_at')->get()->first();
        //find painter thana
        $thana = DB::table('thanas')->where('id', $dealer['thana_id'])->select('id', 'thana')->get()->first();
        if (!$division) {
            $division['id'] = '';
            $division['division'] = '';
        }
        if (!$district) {
            $district['id'] = '';
            $district['district'] = '';
        }
        if (!$d) {
            $d['id'] = '';
            $d['name'] = '';
            $d['code'] = '';
        }

        // start section find painter membership level
        $scanpoint_years = ScanPoint::where('painter_id', $dealer['id'])
            ->select('id', 'bar_code_id')->get()->toArray();

        $volumess = VolumeTranfer::where('painter_id', $dealer['id'])->where('status', '!=', 2)
            ->select('id', 'painter_point')->get()->toArray();
        $all_totals = 0;
        $total_redeem_points = 0;
        $total_volume_points = 0;
        $total_scan_points = 0;

        foreach ($volumess as $volumesss) {

            $total_volume_points += $volumesss['painter_point'];
        }

        foreach ($scanpoint_years as $scan_points) {
            $barcode = BarCode::where('id', $scan_points['bar_code_id'])
                ->select('id', 'product_id', 'point')->get()->last();

            $total_scan_points += $barcode['point'];
        }
        $all_totals = $total_volume_points + $total_scan_points;
        $members = EliteMember::get()->toArray();

        foreach ($members as $member) {
            if ($all_totals < $member['point']) {
                $current_membership_status = $member['member_type'];
                break;
            }
        }
        // end section find painter membership level

        if (!$thana) {
            $dashboard = [
                'code' => $dealer['code'],
                'name' => $dealer['name'],
                'phone' => $dealer['phone'],
                'rocket_number' => $dealer['rocket_number'] == NULL ? '' : $dealer['rocket_number'],
                'email' => $dealer['email'],
                'dealer_id' => $d['id'],
                'dealer_name' => $d['name'],
                'dealer_code' => $d['code'],
                'depo' => $dealer['depo'] == NULL ? '' : $dealer['depo'],
                'division_id' => $division['id'],
                'division' => $division['division'],
                'district' => $district['district'],
                'district_id' => $district['id'],
                'thana' => '',
                'thana_id' => '',
                'alternative_number' => $dealer['alternative_number'] == NULL ? '' : $dealer['alternative_number'],
                'nid' => $dealer['nid'] == NULL ? '' : $dealer['nid'],
                'type' => $current_membership_status,
                'token_claim_point' => round($total_scan_points, 2),
                'volume_point' => round($total_volume_points, 2),
                'this_year_point' => round($all_total, 2),
                'last_year_point' => round($all_total_last_year, 2),
            ];
        } else {
            $dashboard = [
                'code' => $dealer['code'],
                'name' => $dealer['name'],
                'phone' => $dealer['phone'],
                'rocket_number' => $dealer['rocket_number'] == NULL ? '' : $dealer['rocket_number'],
                'email' => $dealer['email'],
                'dealer_id' => $d['id'],
                'dealer_name' => $d['name'],
                'dealer_code' => $d['code'],
                'depo' => $dealer['depo'] == NULL ? '' : $dealer['depo'],
                'division_id' => $division['id'],
                'division' => $division['division'],
                'district' => $district['district'],
                'district_id' => $district['id'],
                'thana' => $thana->thana,
                'thana_id' => $thana->id,
                'alternative_number' => $dealer['alternative_number'] == NULL ? '' : $dealer['alternative_number'],
                'nid' => $dealer['nid'] == NULL ? '' : $dealer['nid'],
                'type' => $current_membership_status,
                'token_claim_point' => round($total_scan_points, 2),
                'volume_point' => round($total_volume_points, 2),
                'this_year_point' => round($all_total, 2),
                'last_year_point' => round($all_total_last_year, 2),
            ];
        }


        return response()->json(['data' => $dashboard], 200);
    }

    //product point update api
    public function point_update(Request $request)
    {
        set_time_limit(6000);
        ini_set("pcre.backtrack_limit", "100000000");
        $elite_member_id = $request->elite_member_id;
        $product_category_id = $request->product_category_id;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $point = $request->point;

        $products = DB::table('points')->where('elite_member_id', $elite_member_id)->where('product_category_id', $product_category_id)->where('soft_delete', 1)
            ->select('product_id')->distinct('product_id')->get()->toArray();

        foreach ($products as $product) {
            DB::table('points')->insert([
                'product_id' => $product->product_id,
                'point' => $point,
                'elite_member_id' => $elite_member_id,
                'start_date' => $start_date,
                'product_category_id' => $product_category_id,
                'end_date' => $end_date,
                'type' => 'PRODUCT',
            ]);
        }
        $data = ['message' => 'Thank you for adding new Farm.', 'type' => "message"];
        return response()->json(['data' => $data], 200);
    }

    //update imei api while login
    public function update_imei(Request $request)
    {

        $app_identifier = $request->app_identifier;
        $imei = $request->imei;
        $phone = $request->phone;

        if ($app_identifier == 'com.ets.elitepaint.dealer') {
            $multiple = DealerUser::where(['phone'=> $phone,'soft_delete'=> 1,'disable'=>1,'status'=>1])->count();
            if ($multiple > 1){
                $data = [
                    'error' => 'Contact with Administrator',
                ];
                return response()->json(['data' => $data], 200);
            }
            $dealer = DB::table('dealer_users')->where(['phone'=> $phone,'soft_delete'=> 1,'disable'=>1,'status'=>1])->latest()->first();

            $log_data = [
                'user_code'=>  $dealer->code,
                'user_id'=>  $dealer->id,
                'user_type'=>  'Dealer',
                'user_phone'=>  $dealer->phone,
                'new_imei'=>$imei,
                'old_device'=>$dealer->device,
                'old_imei'=>$dealer->imei,
                'time'=>Carbon::now(),
            ];
            DB::table('imei_update_info')->insert($log_data);
            DB::table('dealer_users')->where(['phone'=> $phone,'soft_delete'=> 1,'disable'=>1,'status'=>1])->update(['imei' => $imei]);
        } elseif ($app_identifier == 'com.ets.elitepaint.painter') {
            $multiple = PainterUser::where(['phone'=> $phone,'soft_delete'=> 1,'disable'=>1,'status'=>1])->count();
            if ($multiple > 1){
                $data = [
                    'error' => 'Contact with Administrator',
                ];
                return response()->json(['data' => $data], 200);
            }
            $painter = DB::table('painter_users')->where(['phone'=> $phone,'soft_delete'=> 1,'disable'=>1,'status'=>1])->latest()->first();
            $log_data = [
                'user_code'=>  $painter->code,
                'user_id'=>  $painter->id,
                'user_type'=>  'Painter',
                'user_phone'=>  $painter->phone,
                'new_imei'=>$imei,
                'old_device'=>$painter->device,
                'old_imei'=>$painter->imei,
                'time'=>Carbon::now(),
            ];
            DB::table('imei_update_info')->insert($log_data);
            DB::table('painter_users')->where(['phone'=> $phone,'soft_delete'=> 1,'disable'=>1,'status'=>1])->update(['imei' => $imei]);
        }

        $data = ['message' => 'আপনার IMEI সফলভাবে পরির্বতন হয়েছে', 'type' => "message"];
        return response()->json(['data' => $data], 200);
    }

    public function user_login(Request $request)
    {
        $phone = $request->phone;
        $udid = $request->uid;
        $imei = $request->imei;
        $app_version = $request->app_version;
        $platform = $request->platform;
        $pass = $request->password;
        $device = $request->device;
        $app_identifier = $request->app_identifier;
        $push_id = $request->push_token;
        $current_date = NULL;
        $now = Carbon::now();

        $current_date = $now->toDateTimeString();
        $cur = $now->toDateString();
        if (!$phone) {
            $data = [
                'error' => 'আপনি কোন মোবাইল নম্বর প্রবেশ করাননি',
            ];
            return response()->json(['data' => $data], 200);
        }
        if (!$pass) {
            $data = [
                'error' => 'আপনি কোন পাসওর্য়াড ‍প্রবেশ করাননিি',
            ];
            return response()->json(['data' => $data], 200);
        }
        $seed = str_split('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxy'
            . '0123456789'); // and any other characters
        shuffle($seed);
        $code = '';
        foreach (array_rand($seed, 36) as $k) $code .= $seed[$k];

        if ($app_identifier == 'com.ets.elitepaint.dealer') {
            $multiple = DealerUser::where(['phone'=> $phone,'soft_delete'=> 1,'disable'=>1,'status'=>1])->count();
            if ($multiple > 1){
                $data = [
                    'error' => 'Contact with Administrator',
                ];
                return response()->json(['data' => $data], 200);
            }
            $dealer = DealerUser::where('phone', $phone)->where('soft_delete', 1)->get()->last();
            $user_token = $this->generateRandomUserToken($dealer);
            if (!$dealer) {
                $data = [
                    'error' => 'আপনার মোবাইল নম্বরটি নিবদ্ধিত নয়',
                ];
                return response()->json(['data' => $data], 200);
            }
            if ($dealer) {
                if (Hash::check($pass, $dealer['password'])) {
                    if ($dealer['disable'] == 2) {
                        $data = [
                            'error' => 'Your Id is Disable.',
                        ];
                        return response()->json(['data' => $data], 200);
                    }
                    if ($dealer['status'] == 0) {
                        $data = [
                            'error' => 'আপনার আইডিটি এডমিনের অনুমোদনের অপেক্ষায় রয়েছে',
                        ];
                        return response()->json(['data' => $data], 200);
                    } else {
//                        if (!$dealer['imei']) {
//                            DB::table('dealer_users')->where('phone', $phone)->where('soft_delete', 1)->update([
//                                'uid' => $udid,
//                                'app_version' => $app_version,
//                                'imei' => $imei,
//                                'platform' => $platform,
//                                'device' => $device,
//                                'push_token' => $push_id,
//                                'user_token' => $code,
//                            ]);
//                            DB::table('login_logs')->insert([
//                                'uid' => $udid,
//                                'app_version' => $app_version,
//                                'platform' => $platform,
//                                'device' => $device,
//                                'app_identifier' => $app_identifier,
//                                'dealer_painter_id' => $dealer['id'],
//                                'created_at' => $current_date,
//                                'push_token' => $push_id
//                            ]);
//                        } else
                            if ($dealer['imei'] != $imei) {

                            if ("01307782510" == $phone) {
//                                DB::table('dealer_users')->where('id', $dealer['id'])->where('soft_delete', 1)->update([
//                                    'uid' => $udid,
//                                    'app_version' => $app_version,
//                                    'imei' => $imei,
//                                    'platform' => $platform,
//                                    'device' => $device,
//                                    'push_token' => $push_id,
//                                    'user_token' => $user_token,
//                                ]);
                                DB::table('login_logs')->insert([
                                    'uid' => $udid,
                                    'app_version' => $app_version,
                                    'platform' => $platform,
                                    'device' => $device,
                                    'app_identifier' => $app_identifier,
                                    'dealer_painter_id' => $dealer['id'],
                                    'created_at' => $current_date,
                                    'push_token' => $push_id
                                ]);
                            } else {
                               $log_data = [
                                    'user_code'=>  $dealer['code'],
                                    'user_id'=>  $dealer['id'],
                                    'user_type'=>  'Dealer',
                                    'user_phone'=>  $dealer['phone'],
                                    'request_login_from_device'=>$device,
                                    'request_login_from_imei'=>$imei,
                                    'time'=>$current_date,
                                    'ip_address'=>$request->ip(),
                               ];
                                DB::table('imei_update_requests')->insert($log_data);
                                $data = [
                                    'error' => 'Imei not matched',
                                ];
                                return response()->json(['data' => $data], 200);
                            }
                        } else {
                            DB::table('dealer_users')->where('id', $dealer['id'])->where('soft_delete', 1)->update([
                                'uid' => $udid,
                                'app_version' => $app_version,
                                'platform' => $platform,
                                'device' => $device,
                                'push_token' => $push_id,
                                'user_token' => $user_token,
                            ]);
                            DB::table('login_logs')->insert([
                                'uid' => $udid,
                                'app_version' => $app_version,
                                'platform' => $platform,
                                'device' => $device,
                                'app_identifier' => $app_identifier,
                                'dealer_painter_id' => $dealer['id'],
                                'created_at' => $current_date,
                                'push_token' => $push_id
                            ]);
                        }
                    }
                } else {
                    $data = [
                        'error' => 'আপনি ভুল পাসওর্য়াড ‍দিয়েছেন',
                    ];
                    return response()->json(['data' => $data], 200);
                }
            }
        } elseif ($app_identifier == 'com.ets.elitepaint.painter') {
            $multiple = PainterUser::where(['phone'=> $phone,'soft_delete'=> 1,'disable'=>1,'status'=>1])->count();
            if ($multiple > 1){
                $data = [
                    'error' => 'Contact with Administrator',
                ];
                return response()->json(['data' => $data], 200);
            }
            $painter = PainterUser::where('phone', $phone)->where('soft_delete', 1)->get()->last();
            $user_token = $this->generateRandomUserToken($painter, 'painter');
            if (!$painter) {
                $data = [
                    'error' => 'আপনার মোবাইল নম্বরটি নিবদ্ধিত নয়',
                ];
                return response()->json(['data' => $data], 200);
            }
            if ($painter) {
                if (Hash::check($pass, $painter['password'])) {
                    if ($painter['disable'] == 2) {
                        $data = [
                            'error' => 'Your Id is Disable.',
                        ];
                        return response()->json(['data' => $data], 200);
                    }
                    if ($painter['status'] == 0) {
                        $data = [
                            'error' => 'আপনার আইডিটি এডমিনের অনুমোদনের অপেক্ষায় রয়েছে',
                        ];
                        return response()->json(['data' => $data], 200);
                    } else {
                        //dd($painter['id']);
//                        if (!$painter['imei']) {
//                            DB::table('painter_users')->where('phone', $phone)->where('soft_delete', 1)->update([
//                                'uid' => $udid,
//                                'app_version' => $app_version,
//                                'imei' => $imei,
//                                'platform' => $platform,
//                                'device' => $device,
//                                'push_token' => $push_id,
//                                'user_token' => $code,
//                            ]);
//                            DB::table('login_logs')->insert([
//                                'uid' => $udid,
//                                'app_version' => $app_version,
//                                'platform' => $platform,
//                                'device' => $device,
//                                'app_identifier' => $app_identifier,
//                                'dealer_painter_id' => $painter['id'],
//                                'created_at' => $current_date,
//                                'push_token' => $push_id
//                            ]);
//                        } else
                            if ($painter['imei'] != $imei) {
                            if ("01885996709" == $phone) {
//                                DB::table('painter_users')->where('id', $painter['id'])->where('soft_delete', 1)->update([
//                                    'uid' => $udid,
//                                    'app_version' => $app_version,
//                                    'platform' => $platform,
//                                    'device' => $device,
//                                    'push_token' => $push_id,
//                                    'user_token' =>  $user_token,
//                                ]);
                                DB::table('login_logs')->insert([
                                    'uid' => $udid,
                                    'app_version' => $app_version,
                                    'platform' => $platform,
                                    'device' => $device,
                                    'app_identifier' => $app_identifier,
                                    'dealer_painter_id' => $painter['id'],
                                    'created_at' => $current_date,
                                    'push_token' => $push_id
                                ]);
                            } else {
                                $log_data = [
                                    'user_code'=>  $painter['code'],
                                    'user_id'=>  $painter['id'],
                                    'user_type'=>  'Painter',
                                    'user_phone'=>  $painter['phone'],
                                    'request_login_from_device'=>$device,
                                    'request_login_from_imei'=>$imei,
                                    'time'=>$current_date,
                                    'ip_address'=>$request->ip(),
                                ];
                                DB::table('imei_update_requests')->insert($log_data);
                                $data = [
                                    'error' => 'Imei not matched',
                                ];
                                return response()->json(['data' => $data], 200);
                            }
                        } else {
                            DB::table('painter_users')->where('id', $painter['id'])->where('soft_delete', 1)->update([
                                'uid' => $udid,
                                'app_version' => $app_version,
                                'platform' => $platform,
                                'device' => $device,
                                'push_token' => $push_id,
                                'user_token' => $user_token,
                            ]);
                            DB::table('login_logs')->insert([
                                'uid' => $udid,
                                'app_version' => $app_version,
                                'platform' => $platform,
                                'device' => $device,
                                'app_identifier' => $app_identifier,
                                'dealer_painter_id' => $painter['id'],
                                'created_at' => $current_date,
                                'push_token' => $push_id
                            ]);
                        }
                    }
                } else {
                    $data = [
                        'error' => 'আপনি ভুল পাসওর্য়াড ‍দিয়েছেন',
                    ];
                    return response()->json(['data' => $data], 200);
                }
            }
        }
        if ($app_identifier == 'com.ets.elitepaint.dealer') {
            $dealer = DealerUser::where('id', $dealer['id'])->where('soft_delete', 1)->select('id', 'password', 'status', 'disable')->get()->last();
            if ($dealer) {
                //  dd($dealer);

                $notification = Notification::where('app_identifier', 'com.ets.elitepaint.dealer')->whereNotNull('image')->where('soft_delete', 1)->where('date', '>=', $cur)
                    ->select('id', 'image', 'title', 'details', 'duration')->get()
                    ->last();
                if ($notification) {
                    $notifications = 'http://somriddhi.elitepaint.com.bd/images/' . $notification['image'];
                    if (!$notification['duration']) {
                        $duration = 0;
                    } else {
                        $duration = $notification['duration'];
                    }

                    $data = ['message' => 'সফল লগইন',
                        'type' => "message",
                        'duration' => $duration,
                        'title' => $notification['title'],
                        'details' => $notification['details'],
                        'notification_image' => $notifications,
                        'user-token' => $user_token];
                    return response()->json(['data' => $data], 200);
                } else {
                    $data = ['message' => 'সফল লগইন', 'type' => "message",
                        'user-token' => $user_token];
                    return response()->json(['data' => $data], 200);
                }
            }
        } else {
            $painter = PainterUser::where('id', $painter['id'])->where('soft_delete', 1)->select('id', 'password', 'status', 'disable')->get()->last();
            if ($painter) {

                $notification = Notification::where('app_identifier', 'com.ets.elitepaint.painter')->whereNotNull('image')->where('soft_delete', 1)->where('date', '>=', $cur)
                    ->select('id', 'image', 'title', 'details', 'duration')->get()
                    ->last();
                if ($notification) {
                    $notifications = 'http://somriddhi.elitepaint.com.bd/images/' . $notification['image'];
                    if (!$notification['duration']) {
                        $duration = 0;
                    } else {
                        $duration = $notification['duration'];
                    }
                    $data = ['message' => 'সফল লগইন',
                        'type' => "message",
                        'title' => $notification['title'],
                        'details' => $notification['details'],
                        'duration' => $duration,
                        'notification_image' => $notifications,
                        'user-token' => $user_token];
                    return response()->json(['data' => $data], 200);
                } else {
                    $data = ['message' => 'সফল লগইন',
                        'type' => "message",
                        'user-token' => $user_token];
                    return response()->json(['data' => $data], 200);
                }
            }
        }
    }

    public function login_two(Request $request)
    {

        $phone = $request->phone;
        $udid = $request->uid;
        $imei = $request->imei;
        $app_version = $request->app_version;
        $platform = $request->platform;
        $pass = $request->password;
        $device = $request->device;
        $app_identifier = $request->app_identifier;
        $push_id = $request->push_token;
        $current_date = NULL;
        $now = Carbon::now();


        $current_date = $now->toDateTimeString();
        $cur = $now->toDateString();
        if (!$phone) {
            $data = [
                'error' => 'আপনি কোন মোবাইল নম্বর প্রবেশ করাননি',
            ];
            return response()->json(['data' => $data], 200);
        }
        if (!$pass) {
            $data = [
                'error' => 'আপনি কোন পাসওর্য়াড ‍প্রবেশ করাননিি',
            ];
            return response()->json(['data' => $data], 200);
        }
        $seed = str_split('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxy'
            . '0123456789'); // and any other characters
        shuffle($seed);
        $code = '';
        foreach (array_rand($seed, 36) as $k) $code .= $seed[$k];

        if ($app_identifier == 'com.ets.elitepaint.dealer') {
            $dealer = DealerUser::where('phone', $phone)->where('soft_delete', 1)->select('id', 'imei', 'password', 'status', 'disable')->get()->last();

            if (!$dealer) {
                $data = [
                    'error' => 'আপনার মোবাইল নম্বরটি নিবদ্ধিত নয়',
                ];
                return response()->json(['data' => $data], 200);
            }
            if ($dealer) {
                if (Hash::check($pass, $dealer['password'])) {
                    if ($dealer['disable'] == 2) {
                        $data = [
                            'error' => 'Your Id is Disable.',
                        ];
                        return response()->json(['data' => $data], 200);
                    }
                    if ($dealer['status'] == 0) {
                        $data = [
                            'error' => 'আপনার আইডিটি এডমিনের অনুমোদনের অপেক্ষায় রয়েছে',
                        ];
                        return response()->json(['data' => $data], 200);
                    } else {

                        if ($dealer['imei'] != $imei) {
                            $data = [
                                'error' => 'Imei not matched',
                            ];
                            return response()->json(['data' => $data], 200);
                        } else {
//                            DB::table('dealer_users')->where('phone', $phone)->where('soft_delete', 1)->update([
//                                'uid' => $udid,
//                                'app_version' => $app_version,
//                                'platform' => $platform,
//                                'device' => $device,
//                                'push_token' => $push_id,
//                                'user_token' => $code,
//                            ]);
                            DB::table('login_logs')->insert([
                                'uid' => $udid,
                                'app_version' => $app_version,
                                'platform' => $platform,
                                'device' => $device,
                                'app_identifier' => $app_identifier,
                                'dealer_painter_id' => $dealer['id'],
                                'created_at' => $current_date,
                                'push_token' => $push_id
                            ]);
                        }
                    }
                } else {
                    $data = [
                        'error' => 'আপনি ভুল পাসওর্য়াড ‍দিয়েছেন',
                    ];
                    return response()->json(['data' => $data], 200);
                }
            }
        } elseif ($app_identifier == 'com.ets.elitepaint.painter') {
            $painter = PainterUser::where('phone', $phone)->where('soft_delete', 1)->select('id', 'imei', 'password', 'status', 'disable')->get()->last();

            if (!$painter) {
                $data = [
                    'error' => 'আপনার মোবাইল নম্বরটি নিবদ্ধিত নয়',
                ];
                return response()->json(['data' => $data], 200);
            }
            if ($painter) {
                if (Hash::check($pass, $painter['password'])) {
                    if ($painter['disable'] == 2) {
                        $data = [
                            'error' => 'Your Id is Disable.',
                        ];
                        return response()->json(['data' => $data], 200);
                    }
                    if ($painter['status'] == 0) {
                        $data = [
                            'error' => 'আপনার আইডিটি এডমিনের অনুমোদনের অপেক্ষায় রয়েছে',
                        ];
                        return response()->json(['data' => $data], 200);
                    } else {
                        if ($painter['imei'] != $imei) {
                            $data = [
                                'error' => 'Imei not matched',
                            ];
                            return response()->json(['data' => $data], 200);
                        } else {
//                            DB::table('painter_users')->where('phone', $phone)->where('soft_delete', 1)->update([
//                                'uid' => $udid,
//                                'app_version' => $app_version,
//                                'platform' => $platform,
//                                'device' => $device,
//                                'push_token' => $push_id,
//                                'user_token' => $code,
//                            ]);
                            DB::table('login_logs')->insert([
                                'uid' => $udid,
                                'app_version' => $app_version,
                                'platform' => $platform,
                                'device' => $device,
                                'app_identifier' => $app_identifier,
                                'dealer_painter_id' => $painter['id'],
                                'created_at' => $current_date,
                                'push_token' => $push_id
                            ]);
                        }
                    }
                } else {
                    $data = [
                        'error' => 'আপনি ভুল পাসওর্য়াড ‍দিয়েছেন',
                    ];
                    return response()->json(['data' => $data], 200);
                }
            }
        }
        if ($app_identifier == 'com.ets.elitepaint.dealer') {
            $dealer = DealerUser::where('phone', $phone)->where('soft_delete', 1)->select('id', 'password', 'status', 'disable')->get()->last();
            if ($dealer) {
                //  dd($dealer);
                $version_notification = VersionNotification::where('app_identifier', 'com.ets.elitepaint.dealer')
                    ->select('id', 'latest_version')->get()
                    ->last();
                //dd($version_notification);
                if ($app_version != $version_notification['latest_version']) {
                    $data = [
                        'version_error' => 'New Update Available.',
                        'title' => 'New Update Available.',
                        'message' => 'There is a newer version of app available,Please update it Now.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
                $notification = Notification::where('app_identifier', 'com.ets.elitepaint.dealer')->whereNotNull('image')->where('soft_delete', 1)->where('date', '>=', $cur)
                    ->select('id', 'image', 'title', 'details', 'duration')->get()
                    ->last();
                if ($notification) {
                    $notifications = 'http://somriddhi.elitepaint.com.bd/images/' . $notification['image'];
                    if (!$notification['duration']) {
                        $duration = 0;
                    } else {
                        $duration = $notification['duration'];
                    }

                    $data = ['message' => 'সফল লগইন', 'type' => "message", 'duration' => $duration, 'title' => $notification['title'], 'details' => $notification['details'], 'notification_image' => $notifications, 'user-token' => $code];
                    return response()->json(['data' => $data], 200);
                } else {
                    $data = ['message' => 'সফল লগইন', 'type' => "message", 'user-token' => $code];
                    return response()->json(['data' => $data], 200);
                }
            }
        } else {
            $painter = PainterUser::where('phone', $phone)->where('soft_delete', 1)->select('id', 'password', 'status', 'disable')->get()->last();
            if ($painter) {
                $version_notification = VersionNotification::where('app_identifier', 'com.ets.elitepaint.painter')
                    ->select('id', 'latest_version')->get()
                    ->last();
                //dd($version_notification);
                if ($app_version != $version_notification['latest_version']) {
                    $data = [
                        'version_error' => 'New Update Available.',
                        'title' => 'New Update Available.',
                        'message' => 'There is a newer version of app available,Please update it Now.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
                $notification = Notification::where('app_identifier', 'com.ets.elitepaint.painter')->whereNotNull('image')->where('soft_delete', 1)->where('date', '>=', $cur)
                    ->select('id', 'image', 'title', 'details', 'duration')->get()
                    ->last();
                if ($notification) {
                    $notifications = 'http://somriddhi.elitepaint.com.bd/images/' . $notification['image'];
                    if (!$notification['duration']) {
                        $duration = 0;
                    } else {
                        $duration = $notification['duration'];
                    }
                    $data = ['message' => 'সফল লগইন', 'type' => "message", 'title' => $notification['title'], 'details' => $notification['details'], 'duration' => $duration, 'notification_image' => $notifications, 'user-token' => $code];
                    return response()->json(['data' => $data], 200);
                } else {
                    $data = ['message' => 'সফল লগইন', 'type' => "message", 'user-token' => $code];
                    return response()->json(['data' => $data], 200);
                }
            }
        }
    }

    //ponner mojud list api
    public function dealer_product_stock_checking(Request $request)
    {

        $user_token = $request->header('USER-TOKEN');

        if ($user_token) {

            $dealer = DealerUser::where('user_token', $user_token)
                ->select(
                    'id',
                    'password',
                    'status',
                    'code',
                    'name',
                    'phone'
                )->get()->last();

            if ($dealer) {
                //get all basegroup list
                //                $allData = \App\Classes\Stock::dealer_wise_stocks($dealer->id);
                $allData = \App\Classes\Stock::dealer_wise_basegroup_stocks2($dealer->id);
            } else {
                $data = [
                    'error' => 'USER TOKEN NOT MATCHED.',
                ];
                return response()->json(['data' => $data], 200);
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }

        return response()->json(['data' => $allData], 200);
    }

    //ponner mojud details api
    public function product_stock_details(Request $request)
    {

        $user_token = $request->header('USER-TOKEN');
        $product_code = $request->product_code;
        $product_id = $request->product_id;

        if ($user_token) {
            $dealer = DealerUser::where('user_token', $user_token)
                ->select(
                    'id',
                    'password',
                    'status',
                    'code',
                    'name',
                    'phone'
                )->get()->last();

            if ($dealer) {
                //find subgroup list where product_id match with base group.
                $subgroup_list = SubGroup::select('id', 'subgroup_code', 'subgroup_name')->where('basegroup_id', $product_id)->where('soft_delete', 1)->get()->toArray();
                $total_ltr = 0;
                $allData = [];
                if ($subgroup_list) {
                    foreach ($subgroup_list as $subgroup) {
                        //get invoice information through sub group
                        $invoices = DB::table('invoices')->select('id', 'date', 'invoice', 'product_code', 'product_name', 'shade_name', 'pack_size', 'quantity')
                            ->where('dealer_id', $dealer['id'])
                            ->whereYear('date', Carbon::now()->year)
                            ->Where('product_code', 'LIKE', '%' . $subgroup['subgroup_code'] . '%')->orderBy('id', 'desc')->get()->toArray();
                        $total_ltr = 0;
                        foreach ($invoices as $invoice) {
                            //calculate total ltr
                            $total_ltr = $invoice->quantity * $invoice->pack_size;

                            $dashboard = [
                                'invoice_id' => $invoice->id,
                                'date' => $invoice->date,
                                'invoice' => $invoice->invoice,
                                'product_code' => $invoice->product_code,
                                'product_name' => $invoice->product_name,
                                'shade_name' => $invoice->shade_name,
                                'pack_size' => $invoice->pack_size,
                                'quantity' => $invoice->quantity,
                                'volume' => round($total_ltr, 2),
                            ];

                            $allData[] = $dashboard;
                        }
                    }
                } else {
                    //get invoice information direct under base group
                    $invoices = DB::table('invoices')->select('id', 'date', 'invoice', 'product_code', 'product_name', 'shade_name', 'pack_size', 'quantity')->where('dealer_id', $dealer['id'])
                        ->Where('product_code', 'LIKE', '%' . $product_code . '%')
                        ->whereYear('date', Carbon::now()->year)
                        ->orderBy('id', 'desc')->get()->toArray();
                    foreach ($invoices as $invoice) {
                        //calculate total ltr
                        $total_ltr = $invoice->quantity * $invoice->pack_size;
                        $dashboard = [
                            'invoice_id' => $invoice->id,
                            'date' => $invoice->date,
                            'invoice' => $invoice->invoice,
                            'product_code' => $invoice->product_code,
                            'product_name' => $invoice->product_name,
                            'shade_name' => $invoice->shade_name,
                            'pack_size' => $invoice->pack_size,
                            'quantity' => $invoice->quantity,
                            'volume' => round($total_ltr, 2),
                        ];
                        $allData[] = $dashboard;
                    }
                }
            } else {
                $data = [
                    'error' => 'USER TOKEN NOT MATCHED.',
                ];
                return response()->json(['data' => $data], 200);
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }
        return response()->json(['data' => $allData], 200);
    }

    //unused api
    public function dealer_negitive_stock_checkings(Request $request)
    {
        set_time_limit(600);
        $dealer_id = $request->dealer_id;
        $feedback_list = DB::table('invoices')->select('dealer_id')->distinct('dealer_id')->get()->toArray();
        foreach ($feedback_list as $dealer_single_user) {
            $subgroup_list = SubGroup::select('id', 'subgroup_code', 'subgroup_name')->where('soft_delete', 1)->get()->toArray();

            $allData = [];
            foreach ($subgroup_list as $subgroup) {
                $invoices = DB::table('invoices')->select('id', 'pack_size', 'quantity')->where('dealer_id', $dealer_single_user->dealer_id)
                    ->Where('product_code', 'LIKE', '%' . $subgroup['subgroup_code'] . '%')->get()->toArray();
                $total_ltr = 0;
                $dealer_all_user = DB::table('dealer_users')->where('id', $dealer_single_user->dealer_id)->get()->last();
                if ($invoices) {
                    foreach ($invoices as $invoice) {

                        $total_ltr += $invoice->quantity * $invoice->pack_size;
                    }
                    $dashboard = [
                        'dealer_code' => $dealer_all_user->code,
                        'dealer_name' => $dealer_all_user->name,
                        'dealer_id' => $dealer_single_user->dealer_id,
                        'product_name' => $subgroup['subgroup_name'] . '(' . $subgroup['subgroup_code'] . ')',
                        'stock' => round($total_ltr, 2)
                    ];
                    $allData[] = $dashboard;
                }
            }
            DealerNegetiveValue::insert($allData);
        }
        $data = [
            'dealer' => 'gfd',
        ];
        return response()->json(['data' => $data], 200);
    }

    //unused api
    public function dealer_type_checking(Request $request)
    {

        $dealer_all_user = DB::table('dealer_users')->where('disable', 1)->where('soft_delete', 1)->where('status', 1)->get()->toArray();
        foreach ($dealer_all_user as $dealer_single_user) {
            if ($dealer_single_user->code == 'CF') {
                $dealer_id = $dealer_single_user->id;
                $volume_tranfers = DB::table('volume_tranfers')->where('dealer_id', $dealer_id)->get()->toArray();
                foreach ($volume_tranfers as $volume_tranfer) {
                    $points = DB::table('points')->where('product_id', $volume_tranfer->product_id)->where('elite_member_id', 8)->where('soft_delete', 1)->get()->last();
                    if ($points) {
                        $dealer_point = $points->point * $volume_tranfer->quantity;
                    } else {
                        $dealer_point = 0;
                    }
                    DB::table('volume_tranfers')->where('id', $volume_tranfer->id)->update(['dealer_point' => $dealer_point]);
                }
            } else {
                $dealer_id = $dealer_single_user->id;
                $volume_tranfers = DB::table('volume_tranfers')->where('dealer_id', $dealer_id)->get()->toArray();
                foreach ($volume_tranfers as $volume_tranfer) {
                    $points = DB::table('points')->where('product_id', $volume_tranfer->product_id)->where('elite_member_id', 9)->where('soft_delete', 1)->get()->last();
                    if ($points) {
                        $dealer_point = $points->point * $volume_tranfer->quantity;
                    } else {
                        $dealer_point = 0;
                    }
                    DB::table('volume_tranfers')->where('id', $volume_tranfer->id)->update(['dealer_point' => $dealer_point]);
                }
            }
        }
        $data = [
            'dealer' => 'gfd',
        ];
        return response()->json(['data' => $data], 200);
    }

    //unused api
    public function duplicate_token_info(Request $request)
    {
        $duplicate_tokens = DB::table('bar_code_duplicates')->select('bar_code', 'no_of_duplicates')->get()->toArray();
        foreach ($duplicate_tokens as $duplicate_token) {
            $tokens = DB::table('bar_codes')->where('bar_code', $duplicate_token->bar_code)->select('bar_code', 'product_id', 'point', 'identifier')->get()->toArray();
            if ($tokens) {
                $identifier = '';
                $point = '';
                $product = '';
                foreach ($tokens as $token) {
                    $Pack = Pack::where('id', $token->product_id)
                        ->select('id', 'subgroup_id', 'pack_size', 'size_code')->get()->last();
                    $subgroup = SubGroup::where('id', $Pack->subgroup_id)
                        ->select('id', 'subgroup_name', 'subgroup_code')->get()->last();

                    $point .= $token->point . ',';
                    $identifier .= $token->identifier . ',';
                    $product .= $subgroup->subgroup_code . $Pack->size_code . '-' . $subgroup->subgroup_name . '-' . $Pack->pack_size . ',';
                }
                $point = substr($point, 0, -1);
                $identifier = substr($identifier, 0, -1);
                $product = substr($product, 0, -1);
                DB::table('bar_code_duplicate_infos')->insert([
                    'token_identifier' => $identifier,
                    'token_no' => $duplicate_token->bar_code,
                    'no_of_duplicates' => $duplicate_token->no_of_duplicates,
                    'product_name_code' => $product,
                    'token_amount' => $point
                ]);
            }
        }
        $data = [
            'message' => 'Success',
        ];
        return response()->json(['data' => $data], 200);
    }


    // sms Gateway new api for dealer and painter
    public function newToken(Request $request)
    {
        $fixed = new Constants();
        $now1 = Carbon::now();
        $plus = substr($request->from, 2);
        DB::table('testings')->insert([
            'number' => $request->from,
            'content' => $request->content,
            'extra_number' => $plus,
        ]);
        if ($fixed->getsms_api_key() == $request->api_key) {
            $now = Carbon::now();
            $current_date = $now->toDateTimeString();
            if ($request->from) {
                $dealer = DealerUser::where('phone', $plus)->where('soft_delete', 1)
                    ->select(
                        'id',
                        'password',
                        'status',
                        'code',
                        'name',
                        'phone'
                    )->get()->last();
                $dealer2 = DealerUser::where('phone', $request->from)->where('soft_delete', 1)
                    ->select(
                        'id',
                        'password',
                        'status',
                        'code',
                        'name',
                        'phone'
                    )->get()->last();
                $painter = PainterUser::where('phone', $plus)->where('soft_delete', 1)
                    ->select(
                        'id',
                        'password',
                        'status',
                        'code',
                        'name',
                        'phone'
                    )->get()->last();
                $painter2 = PainterUser::where('phone', $request->from)->where('soft_delete', 1)
                    ->select(
                        'id',
                        'password',
                        'status',
                        'code',
                        'name',
                        'phone'
                    )->get()->last();
                if ($dealer || $dealer2) {
                    $token_point = DB::table('bar_codes')->where('bar_code', $request->content)->select('id', 'point', 'product_id', 'bar_code')->get()->last();
                    if (!$token_point) {
                        $token_point = DB::table('bar_codes_18405')->where('bar_code', $request->content)->select('id', 'point', 'product_id', 'bar_code')->get()->last();
                    }
                    if ($token_point) {
                        $scan = DB::table('scan_points')->where('bar_code_id', $token_point->id)->select('id')->get()->last();
                        if ($scan) {
                            $sms = $request->content . ' this token number is already used.';
                            $sms_response = $this->sendSMS($sms, $request->from);
                            DB::table('testings')->where('id', $testings->id)->update([
                                'response' => $sms_response,
                                'sms' => $sms,

                            ]);
                            $data = [
                                'message' => 'Success',
                            ];
                            return response()->json(['data' => $data], 200);
                        } else {
                            DB::table('scan_points')->insert([
                                'bar_code_id' => $token_point->id,
                                'point' => $token_point->point,
                                'product_id' => $token_point->product_id,
                                'bar_code' => $token_point->bar_code,
                                'dealer_id' => $dealer['id'],
                                'status' => 'Sms',
                                'created_at' => $current_date
                            ]);

                            $scanpoint_year = ScanPoint::where('dealer_id', $dealer['id'])
                                ->select('id', 'bar_code_id')->get()->toArray();

                            $redeems = RedeemPoint::where('dealer_id', $dealer['id'])
                                ->select('id', 'redeem_point')->get()->toArray();
                            $bonus_point = BonusPoint::where('dealer_id', $dealer['id'])->where('soft_delete', 1)
                                ->select('id', 'bonus_point')->sum('bonus_point');

                            $all_total = 0;
                            $total_redeem_point = 0;
                            $total_volume_point = 0;
                            $total_scan_point = 0;
                            foreach ($scanpoint_year as $scan_point) {
                                $barcode = BarCode::where('id', $scan_point['bar_code_id'])
                                    ->select('id', 'product_id', 'point')->get()->last();

                                if (!$barcode) {
                                    $barcode_point = $barcode['point'];
                                } else {
                                    $barcode = DB::table('bar_codes_18405')->where('id', $scan_point['bar_code_id'])
                                        ->select('id', 'product_id', 'point')->get()->last();
                                    $barcode_point = $barcode->point;
                                }
                                $total_scan_point += $barcode_point;
                            }
                            $volumes = PlaceOrder::where('dealer_id', $dealer['id'])->where('status', '=', 2)
                                ->select('id', 'dealer_point')->get()->toArray();
                            foreach ($volumes as $volume) {
                                $total_volume_point += $volume['dealer_point'];
                            }
                            $all_total = $total_volume_point + $total_scan_point + $bonus_point;
                            $sms = 'Your Token No ' . $request->content . ' point ' . $token_point->point . ', Total Point ' . $all_total . '. Somriddhi Club -Elite Paint.';

                            $sms_response = $this->sendSMS($sms, $request->from);
                            DB::table('testings')->where('id', $testings->id)->update([
                                'response' => $sms_response,
                                'sms' => $sms,

                            ]);
                            $data = [
                                'message' => 'Success',
                            ];
                            return response()->json(['data' => $data], 200);
                        }
                    } else {
                        $sms = $request->content . ' Invalid Token Number.';
                        $sms_response = $this->sendSMS($sms, $request->from);
                        DB::table('testings')->where('id', $testings->id)->update([
                            'response' => $sms_response,
                            'sms' => $sms,

                        ]);
                        $data = [
                            'message' => $sms,
                        ];
                        return response()->json(['data' => $data], 200);
                    }
                } elseif ($painter || $painter2) {
                    $token_point = DB::table('bar_codes')->where('bar_code', $request->content)->select('id', 'point', 'product_id', 'bar_code')->get()->last();
                    if (!$token_point) {
                        $token_point = DB::table('bar_codes_18405')->where('bar_code', $request->content)->select('id', 'point', 'product_id', 'bar_code')->get()->last();
                    }
                    if ($token_point) {
                        $scan = DB::table('scan_points')->where('bar_code_id', $token_point->id)->select('id')->get()->last();
                        if ($scan) {
                            $sms = $request->content . ' this token number is already used.';
                            $sms_response = $this->sendSMS($sms, $request->from);
                            DB::table('testings')->where('id', $testings->id)->update([
                                'response' => $sms_response,
                                'sms' => $sms,

                            ]);
                            $data = [
                                'message' => 'Success',
                            ];
                            return response()->json(['data' => $data], 200);
                        } else {
                            DB::table('scan_points')->insert([
                                'bar_code_id' => $token_point->id,
                                'point' => $token_point->point,
                                'product_id' => $token_point->product_id,
                                'bar_code' => $token_point->bar_code,
                                'painter_id' => $painter['id'],
                                'status' => 'Sms',
                                'created_at' => $current_date
                            ]);

                            $scanpoint_year = ScanPoint::where('painter_id', $painter['id'])
                                ->select('id', 'bar_code_id')->get()->toArray();
                            $redeems = RedeemPoint::where('painter_id', $painter['id'])
                                ->select('id', 'redeem_point')->get()->toArray();
                            $bonus_point = BonusPoint::where('painter_id', $painter['id'])->where('soft_delete', 1)
                                ->select('id', 'bonus_point')->sum('bonus_point');

                            $all_total = 0;
                            $total_redeem_point = 0;
                            $total_volume_point = 0;
                            $total_scan_point = 0;
                            foreach ($scanpoint_year as $scan_point) {
                                $barcode = BarCode::where('id', $scan_point['bar_code_id'])
                                    ->select('id', 'product_id', 'point')->get()->last();
                                if (!$barcode) {
                                    $barcode_point = $barcode['point'];
                                } else {
                                    $barcode = DB::table('bar_codes_18405')->where('id', $scan_point['bar_code_id'])
                                        ->select('id', 'product_id', 'point')->get()->last();
                                    $barcode_point = $barcode->point;
                                }
                                $total_scan_point += $barcode_point;
                            }
                            $volumes = VolumeTranfer::where('painter_id', $painter['id'])->where('status', '!=', 2)
                                ->select('id', 'painter_point')->get()->toArray();
                            foreach ($volumes as $volume) {

                                $total_volume_point += $volume['painter_point'];
                            }
                            $all_total = $total_volume_point + $total_scan_point + $bonus_point;
                            $sms = 'Your Token No ' . $request->content . ' point ' . $token_point->point . ', Total Point ' . $all_total . '. Somriddhi Club -Elite Paint.';
                            $sms_response = $this->sendSMS($sms, $request->from);
                            DB::table('testings')->where('id', $testings->id)->update([
                                'response' => $sms_response,
                                'sms' => $sms,

                            ]);
                            $data = [
                                'message' => 'Success',
                            ];
                            return response()->json(['data' => $data], 200);
                        }
                    } else {
                        $sms = $request->content . ' Invalid Token Number.';
                        $sms_response = $this->sendSMS($sms, $request->from);
                        DB::table('testings')->where('id', $testings->id)->update([
                            'response' => $sms_response,
                            'sms' => $sms,

                        ]);
                        $data = [
                            'message' => 'Success',
                        ];
                        return response()->json(['data' => $data], 200);
                    }
                } else {
                    $sms = 'You Are Not Registerd.';
                    $sms_response = $this->sendSMS($sms, $request->from);
                    DB::table('testings')->where('id', $testings->id)->update([
                        'response' => $sms_response,
                        'sms' => $sms,

                    ]);
                    $data = [
                        'message' => 'Success',
                    ];
                    return response()->json(['data' => $data], 200);
                }
            } else {
                $data = [
                    'error' => 'NO PHONE NUMBER SEND.',
                ];
                return response()->json(['data' => $data], 200);
            }
            return response()->json(['data' => $data], 200);
        } else {
            $sms = 'Unregistered Mobile Number.';

            $sms_response = $this->sendSMS($sms, $request->from);
            DB::table('testings')->where('id', $testings->id)->update([
                'response' => $sms_response,
                'sms' => $sms,

            ]);
            $data = [
                'message' => 'Success',
            ];
        }
    }

    //old sms Gateway api
    public function token(Request $request)
    {
        $fixed = new Constants();
        $plus = substr($request->from, 2);
        $phone_number = substr($request->from, -11);

        $test = new Testing();
        $test->number = $request->from;
        $test->content = $request->content;
        $test->extra_number = $plus;
        $test->save();
        $testings = $test;

        if ($fixed->getsms_api_key() == $request->api_key) {
            $now = Carbon::now();

            if ($request->from) {
                $dealer = DealerUser::where('phone', 'like', '%' . $phone_number . '%')->where('soft_delete', 1)->first();
                $painter = PainterUser::where('phone', 'like', '%' . $phone_number . '%')->where('soft_delete', 1)->first();

                if ($dealer) {
                   //Double Scan Check Start
                    $scanned_today = DB::table('scan_points')->whereDate('created_at',Carbon::now()->format('Y-m-d'))->where([
                        'dealer_id'=>$dealer->id,
                        'bar_code'=>$request->content
                    ])->exists();
                    if ($scanned_today){
                        $sms = $request->content . ' this token number is already used today.';
                        $sms_response = $this->sendSMS($sms, $request->from);
                        DB::table('testings')->where('id', $testings->id)->update([
                            'response' => $sms_response,
                            'sms' => $sms,
                        ]);
                        $data = [
                            'message' => 'Success',
                        ];
                        return response()->json(['data' => $data], 200);
                    }
                    //Double Scan Check End
                    $token_point = DB::table('bar_codes')->where('bar_code', $request->content)->select('id', 'point', 'product_id', 'bar_code')->get()->last();
                    if (!$token_point) {
                        $token_point = DB::table('bar_code_duplicates')->where('bar_code', $request->content)
                            ->select('id', 'dealer_id', 'painter_id', 'bar_code', 'identifier', 'no_of_duplicates', 'no_of_used', 'point')->get()->last();
                    }
                    if ($token_point) {
                        $point = $token_point->point;
                        $scan = DB::table('scan_points')->where('bar_code_id', $token_point->id)->select('id')->get()->last();
                        $duplicate = DB::table('bar_codes_18405')->where('bar_code', $request->content)->select('id', 'point', 'product_id', 'bar_code')->get()->last();
                        if ($duplicate) {
                            $bar_code = $request->content;
                            if ($token_point->no_of_duplicates > $token_point->no_of_used) {
                                $new = DB::select(
                                    "SELECT *
                                FROM bar_codes_18405 c
                                WHERE c.id = (SELECT min(id)
                                                FROM bar_codes_18405 b
                                                WHERE (b.bar_code ,b.POINT) IN (SELECT a.bar_code,MIN(a.POINT)
                                                                                        FROM bar_codes_18405 a
                                                                                        WHERE a.bar_code='$bar_code'
                                                                                        AND a.DUPLICATE=0)
                                                AND b.DUPLICATE=0)"
                                );
                                $barcode18405 = $new[0];
                                DB::table('bar_codes_18405')->where('id', $barcode18405->id)->update(['duplicate' => 1,]);
                                $no_of_used = (int)$token_point->no_of_used + 1;
                                DB::table('bar_code_duplicates')->where('bar_code', $token_point->bar_code)->update([
                                    'dealer_id' => $dealer->id,
                                    'no_of_used' => $no_of_used,
                                ]);
                                $point = $barcode18405->point;
                                try {
                                    DB::table('scan_points')->insert([
                                        'bar_code_id' => $barcode18405->id,
                                        'point' => $barcode18405->point,
                                        'product_id' => $barcode18405->product_id,
                                        'bar_code' => $barcode18405->bar_code,
                                        'dealer_id' => $dealer->id,
                                        'status' => 'Sms',
                                        'created_at' => $now
                                    ]);
                                    goto dealer_sms;
                                } catch (\Exception $ex) {
                                    if (strpos($ex->getMessage(), 'Integrity constraint violation') !== false) {
                                        goto dealer_sms;
                                    } else {
                                        $data = [
                                            'error' => 'USER TOKEN NOT MATCHED.',
                                        ];
                                        return response()->json(['data' => $data], 200);
                                    }
                                }
                            } else {
                                goto Already_Used_Text_Dealer;
                            }
                        } else {
                            if ($scan) {
                                Already_Used_Text_Dealer:
                                $sms = $request->content . ' this token number is already used.';
                                $sms_response = $this->sendSMS($sms, $request->from);
                                DB::table('testings')->where('id', $testings->id)->update([
                                    'response' => $sms_response,
                                    'sms' => $sms,
                                ]);
                                $data = [
                                    'message' => 'Success',
                                ];
                                return response()->json(['data' => $data], 200);
                            } else {
                                try {
                                    DB::table('scan_points')->insert([
                                        'bar_code_id' => $token_point->id,
                                        'point' => $token_point->point,
                                        'product_id' => $token_point->product_id,
                                        'bar_code' => $token_point->bar_code,
                                        'dealer_id' => $dealer['id'],
                                        'status' => 'Sms',
                                        'created_at' => $now
                                    ]);
                                } catch (\Exception $ex) {
                                    if (strpos($ex->getMessage(), 'Integrity constraint violation') !== false) {
                                        goto dealer_sms;
                                    } else {
                                        $data = [
                                            'error' => 'USER TOKEN NOT MATCHED.',
                                        ];
                                        return response()->json(['data' => $data], 200);
                                    }
                                }
                            }

                            \App\Classes\PointUpdate::dealer_total_earning_point($dealer['id'], $token_point->point);
                            dealer_sms:
                            $scanpoint_year = ScanPoint::where('dealer_id', $dealer['id'])
                                ->get()->toArray();
                            $bonus_point = BonusPoint::where('dealer_id', $dealer['id'])->where('soft_delete', 1)->sum('bonus_point');

                            $redeems = RedeemPoint::where('dealer_id', $dealer['id'])
                                ->get()->toArray();


                            $all_total = 0;
                            $total_redeem_point = 0;
                            $total_volume_point = 0;
                            $total_scan_point = 0;
                            foreach ($scanpoint_year as $scan_point) {
                                $total_scan_point += $scan_point['point'];
                            }
                            $volumes = PlaceOrder::where('dealer_id', $dealer['id'])->where('status', '=', 2)
                                ->select('id', 'dealer_point')->get()->toArray();
                            foreach ($volumes as $volume) {
                                $total_volume_point += $volume['dealer_point'];
                            }
                            $all_total = $total_volume_point + $total_scan_point + $bonus_point;
                            $sms = 'Your Token No ' . $request->content . ' point ' . $point . ', Total Point ' . $all_total . '. Somriddhi Club -Elite Paint.';

                            $sms_response = $this->sendSMS($sms, $request->from);
                            DB::table('testings')->where('id', $testings->id)->update([
                                'response' => $sms_response,
                                'sms' => $sms,

                            ]);
                            $data = [
                                'message' => 'Success',
                            ];
                            return response()->json(['data' => $data], 200);
                        }
                    } else {
                        $sms = $request->content . ' Invalid Token Number.';
                        $sms_response = $this->sendSMS($sms, $request->from);
                        DB::table('testings')->where('id', $testings->id)->update([
                            'response' => $sms_response,
                            'sms' => $sms,

                        ]);
                        $data = [
                            'message' => $sms,
                        ];
                        return response()->json(['data' => $data], 200);
                    }
                } elseif ($painter) {
                    //Double Scan Check Start
                    $scanned_today = DB::table('scan_points')->whereDate('created_at',Carbon::now()->format('Y-m-d'))->where([
                        'painter_id'=>$painter->id,
                        'bar_code'=>$request->content
                    ])->exists();
                    if ($scanned_today){
                        $sms = $request->content . ' this token number is already used today.';
                        $sms_response = $this->sendSMS($sms, $request->from);
                        DB::table('testings')->where('id', $testings->id)->update([
                            'response' => $sms_response,
                            'sms' => $sms,
                        ]);
                        $data = [
                            'message' => 'Success',
                        ];
                        return response()->json(['data' => $data], 200);
                    }
                    //Double Scan Check End
                    $token_point = DB::table('bar_codes')->where('bar_code', $request->content)->select('id', 'point', 'product_id', 'bar_code')->get()->last();
                    if (!$token_point) {
                        $token_point = DB::table('bar_code_duplicates')->where('bar_code', $request->content)
                            ->select('id', 'dealer_id', 'painter_id', 'bar_code', 'identifier', 'no_of_duplicates', 'no_of_used', 'point')->get()->last();
                    }
                    if ($token_point) {
                        $point = $token_point->point;
                        $scan = DB::table('scan_points')->where('bar_code_id', $token_point->id)->select('id')->get()->last();
                        $duplicate = DB::table('bar_codes_18405')->where('bar_code', $request->content)->select('id', 'point', 'product_id', 'bar_code')->get()->last();
                        if ($duplicate) {
                            $bar_code = $request->content;
                            if ($token_point->no_of_duplicates > $token_point->no_of_used) {
                                $new = DB::select(
                                    "SELECT *
                                FROM bar_codes_18405 c
                                WHERE c.id = (SELECT min(id)
                                                FROM bar_codes_18405 b
                                                WHERE (b.bar_code ,b.POINT) IN (SELECT a.bar_code,MIN(a.POINT)
                                                                                        FROM bar_codes_18405 a
                                                                                        WHERE a.bar_code='$bar_code'
                                                                                        AND a.DUPLICATE=0)
                                                AND b.DUPLICATE=0)"
                                );
                                $barcode18405 = $new[0];
                                DB::table('bar_codes_18405')->where('id', $barcode18405->id)->update(['duplicate' => 1,]);
                                $no_of_used = (int)$token_point->no_of_used + 1;
                                DB::table('bar_code_duplicates')->where('bar_code', $token_point->bar_code)->update([
                                    'painter_id' => $painter->id,
                                    'no_of_used' => $no_of_used,
                                ]);
                                $point = $barcode18405->point;
                                try {
                                    DB::table('scan_points')->insert([
                                        'bar_code_id' => $barcode18405->id,
                                        'point' => $barcode18405->point,
                                        'product_id' => $barcode18405->product_id,
                                        'bar_code' => $barcode18405->bar_code,
                                        'painter_id' => $painter->id,
                                        'status' => 'Sms',
                                        'created_at' => $now
                                    ]);
                                } catch (\Exception $ex) {
                                    if (strpos($ex->getMessage(), 'Integrity constraint violation') !== false) {
                                        goto painter_sms;
                                    } else {
                                        $data = [
                                            'error' => 'USER TOKEN NOT MATCHED.',
                                        ];
                                        return response()->json(['data' => $data], 200);
                                    }
                                }
                            } else {
                                goto Already_Used_Text_Painter;
                            }

                        } else {
                            if ($scan) {
                                Already_Used_Text_Painter:
                                $sms = $request->content . ' this token number is already used.';
                                $sms_response = $this->sendSMS($sms, $request->from);
                                DB::table('testings')->where('id', $testings->id)->update([
                                    'response' => $sms_response,
                                    'sms' => $sms,

                                ]);
                                $data = [
                                    'message' => 'Success',
                                ];
                                return response()->json(['data' => $data], 200);
                            } else {

                                try {
                                    DB::table('scan_points')->insert([
                                        'bar_code_id' => $token_point->id,
                                        'point' => $token_point->point,
                                        'product_id' => $token_point->product_id,
                                        'bar_code' => $token_point->bar_code,
                                        'painter_id' => $painter->id,
                                        'status' => 'Sms',
                                        'created_at' => $now
                                    ]);
                                } catch (\Exception $ex) {
                                    //   return $ex->getMessage();
                                    if (strpos($ex->getMessage(), 'Integrity constraint violation') !== false) {
                                        goto   painter_sms;
                                    } else {
                                        $data = [
                                            'error' => 'USER TOKEN NOT MATCHED.',
                                        ];
                                        return response()->json(['data' => $data], 200);
                                    }
                                }
                            }
                            \App\Classes\PointUpdate::painter_level_update($painter['id']);
                            painter_sms:
                            $scanpoint_year = ScanPoint::where('painter_id', $painter['id'])
                                ->get()->toArray();
                            $redeems = RedeemPoint::where('painter_id', $painter['id'])
                                ->get()->toArray();
                            $bonus_point = BonusPoint::where('painter_id', $painter['id'])->where('soft_delete', 1)
                                ->sum('bonus_point');

                            $all_total = 0;
                            $total_redeem_point = 0;
                            $total_volume_point = 0;
                            $total_scan_point = 0;
                            foreach ($scanpoint_year as $scan_point) {
                                $total_scan_point += $scan_point['point'];
                            }
                            $volumes = VolumeTranfer::where('painter_id', $painter['id'])->where('status', '!=', 2)
                                ->select('id', 'painter_point')->get()->toArray();
                            foreach ($volumes as $volume) {

                                $total_volume_point += $volume['painter_point'];
                            }
                            $all_total = $total_volume_point + $total_scan_point + $bonus_point;
                            $sms = 'Your Token No ' . $request->content . ' point ' . $point . ', Total Point ' . $all_total . '. Somriddhi Club -Elite Paint.';
                            $sms_response = $this->sendSMS($sms, $request->from);
                            DB::table('testings')->where('id', $testings->id)->update([
                                'response' => $sms_response,
                                'sms' => $sms,

                            ]);
                            $data = [
                                'message' => 'Success',
                            ];
                            return response()->json(['data' => $data], 200);
                        }

                    } else {
                        $sms = $request->content . ' Invalid Token Number.';
                        $sms_response = $this->sendSMS($sms, $request->from);
                        DB::table('testings')->where('id', $testings->id)->update([
                            'response' => $sms_response,
                            'sms' => $sms,

                        ]);
                        $data = [
                            'message' => 'Success',
                        ];
                        return response()->json(['data' => $data], 200);
                    }
                } else {
                    $sms = 'You Are Not Registerd.';
                    $sms_response = $this->sendSMS($sms, $request->from);
                    DB::table('testings')->where('id', $testings->id)->update([
                        'response' => $sms_response,
                        'sms' => $sms,

                    ]);
                    $data = [
                        'message' => 'Success',
                    ];
                    return response()->json(['data' => $data], 200);
                }
            } else {
                $data = [
                    'error' => 'NO PHONE NUMBER SEND.',
                ];
                return response()->json(['data' => $data], 200);
            }
        } else {
            $sms = 'Unregistered Mobile Number.';

            $sms_response = $this->sendSMS($sms, $request->from);
            DB::table('testings')->where('id', $testings->id)->update([
                'response' => $sms_response,
                'sms' => $sms,

            ]);
            $data = [
                'message' => 'Success',
            ];
            return response()->json(['data' => $data], 200);
        }
    }

    //sms send function
    public function sendSMS($sms, $to)
    {

        $apikey = urlencode("C200107361495ffa156de2.55720789");
        $mobileno = $to;
        $senderId = urlencode("8809612113344");
        $type = urlencode("text");
        $mesg = urlencode($sms);

        $api_params = '?api_key=' . $apikey . '&type=' . $type . '&contacts=' . $mobileno . '&senderid=' . $senderId . '&msg=' . $mesg;
        $smsGatewayUrl = "http://portal.metrotel.com.bd/smsapi";
        $smsgatewaydata = $smsGatewayUrl . $api_params;
        $url = $smsgatewaydata;

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        //for debug only!
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $resp = curl_exec($curl);
        curl_close($curl);
        return $resp;
    }


    public function credit_note_date_history(Request $request)
    {
        $user_token = $request->header('USER-TOKEN');

        if ($user_token) {

            $dealer = DealerUser::where('user_token', $user_token)
                ->select(
                    'id',
                    'password',
                    'status',
                    'code',
                    'name',
                    'phone'
                )->get()->last();
            //dd($dealer);
            if ($dealer) {
                $feedback_list = DB::table('credit_notes')->where('dealer_id', $dealer['id'])->orderBy('date', 'desc')->select('date')->distinct('date')->get()->toArray();
                //dd($feedback_list);
                //dd($feedback_list);
                if (!$feedback_list) {
                    $data = [
                        'message' => 'NO CREDIT NOTE ADDED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
                $allData = [];
                //$total_quantity = 0;
                foreach ($feedback_list as $feedback) {

                    //dd($feedback->date);
                    $total_amount = DB::table('credit_notes')->where('date', $feedback->date)->where('dealer_id', $dealer['id'])->sum('amount');
                    $no_of_credit = DB::table('credit_notes')->where('date', $feedback->date)->where('dealer_id', $dealer['id'])->count();
                    $entry_date = Carbon::parse($feedback->date);
                    //  dd($no_of_credit);
                    $dashboard = [
                        'date' => $entry_date->todateString(),
                        'day' => $entry_date->format('l'),
                        'total_amount' => round($total_amount, 2),
                        'no_of_credit' => $no_of_credit,
                    ];

                    $allData[] = $dashboard;
                }
            } else {
                $data = [
                    'error' => 'USER TOKEN NOT MATCHED.',
                ];
                return response()->json(['data' => $data], 200);
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }

        return response()->json(['data' => $allData], 200);
    }

    public function account_statement_date_history(Request $request)
    {

        $user_token = $request->header('USER-TOKEN');

        if ($user_token) {

            $dealer = DealerUser::where('user_token', $user_token)
                ->select(
                    'id',
                    'password',
                    'status',
                    'code',
                    'name',
                    'phone'
                )->get()->last();
            //dd($dealer);
            if ($dealer) {

                $feedback_list = DB::table('account_statements')->where('dealer_id', $dealer['id'])->orderBy('date', 'desc')->select('date')->distinct('date')->get()->toArray();
                //dd($feedback_list);
                //dd($feedback_list);
                if (!$feedback_list) {
                    $data = [
                        'message' => 'NO ACCOUNT STATEMENT ADDED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
                $allData = [];
                //$total_quantity = 0;
                foreach ($feedback_list as $feedback) {

                    //dd($feedback->date);
                    $total_amount = DB::table('account_statements')->where('dealer_id', $dealer['id'])->where('date', $feedback->date)->sum('invoice_amount');
                    $no_of_invoice = DB::table('account_statements')->where('dealer_id', $dealer['id'])->where('date', $feedback->date)->count();
                    $entry_date = Carbon::parse($feedback->date);
                    //  dd($no_of_credit);
                    $dashboard = [
                        'date' => $entry_date->todateString(),
                        'day' => $entry_date->format('l'),
                        'total_invoice_amount' => round($total_amount, 2),
                        'no_of_statement' => $no_of_invoice,
                    ];

                    $allData[] = $dashboard;
                }
            } else {
                $data = [
                    'error' => 'USER TOKEN NOT MATCHED.',
                ];
                return response()->json(['data' => $data], 200);
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }

        return response()->json(['data' => $allData], 200);
    }

    public function add_dealer(Request $request)
    {
        $dealer_code = $request->code;
        $dealer_name = $request->name;
        $dealer_email = $request->email;
        $dealer_phone = $request->phone;
        $rocket_number = $request->rocket_number;
        $depo = $request->depo;
        $member_type = $request->member_type;
        $nid = $request->nid;
        $erp_api_key = $request->erp_api_key;

        $fixed = new Constants();
        $current_date = NULL;
        $now = Carbon::now();
        //dd($nid_pic);

        $current_date = $now->toDateTimeString();
        if ($fixed->geterp_api_key() == $erp_api_key) {
            $dealer = DealerUser::where('code', $dealer_code)->where('soft_delete', 1)->select('id', 'status')->get()->last();
            if ($dealer) {
                $data = [
                    'error' => 'Code already used.',
                ];
                return response()->json(['data' => $data], 200);
            }

            $deale = DealerUser::where('email', $dealer_email)->where('soft_delete', 1)->select('id', 'email', 'status')->get()->last();
            //dd($deale['email']);
            if ($deale && $deale['email'] != NULL) {
                //dd('d');
                $data = [
                    'error' => 'Email already used.',
                ];
                return response()->json(['data' => $data], 200);
            }

            $random_password = rand(100000,999999);
            DB::table('dealer_users')->insert([
                'code' => $dealer_code,
                'name' => $dealer_name,
                'email' => $dealer_email,
                'phone' => $dealer_phone,
                'rocket_number' => $rocket_number,
                'password' => Hash::make($random_password),
                'nid' => $nid,
                'member_type' => $member_type,
                'depo' => $depo,
                'status' => 1,
                'created_at' => $current_date
            ]);


            $sms = 'Congratulations! You are registered as a Dealer of SOMRIDDHI CLUB Application. Your Login Credentials are Phone : ' . $dealer_phone . ' and Password : '.$random_password . ' Download App : https://cutt.ly/SomriddhiDealer';

            $this->sendSMS($sms, $dealer_phone);


            $data = ['message' => 'Congratulations', 'type' => "message"];
            return response()->json(['data' => $data], 200);
        } else {
            $data = [
                'error' => 'Unauthorized Access.',
            ];
            return response()->json(['data' => $data], 200);
        }
    }

    public function invoice(Request $request)
    {
        $erp_api_key = $request->erp_api_key;
        $code = $request->code;
        $name = $request->name;
        $email = $request->email;
        $phone = $request->phone;
        $rocket_number = $request->rocket_number;
        $alternative_number = $request->alternative_number;
        $date = $request->date;
        $invoice = $request->invoice;
        $product_code = $request->product_code;
        $product_name = $request->product_name;
        $pack_size = $request->pack_size;
        $shade_name = $request->shade_name;
        $quantity = $request->quantity;
        $net_amount = $request->net_amount;
        $fixed = new Constants();
        //dd($fixed->geterp_api_key());
        if ($fixed->geterp_api_key() == $erp_api_key) {
            $dealer = DealerUser::where(['soft_delete'=>1,'disable'=>1,'status'=>1])->where('code', $code)->select('id')->get()->first();


            if (!$dealer) {

                $data = [
                    'error' => 'DEALER CODE NOT MATCHED.',
                ];
                return response()->json(['data' => $data], 200);
            }

            $dealer_id = DB::table('invoices')->where('dealer_id', $dealer['id'])->where('date', $date)->where('invoice', $invoice)->where('product_code', $product_code)->select('id')->get()->last();

            $subgroup = \Illuminate\Support\Facades\DB::table('subgroups')->where('subgroup_code', substr($product_code, 0, 4))->first();
            if (!$subgroup->basegroup_id) {
                $basegroup = \Illuminate\Support\Facades\DB::table('basegroups')->insertGetId([
                    'basegroup_code' => $subgroup->subgroup_code,
                    'basegroup_name' => $subgroup->subgroup_name,
                    'delivery_percentage' => 0,
                    'created_at' => Carbon::now()
                ]);
                \Illuminate\Support\Facades\DB::table('subgroups')->where('id', $subgroup->id)->update(['basegroup_id' => $basegroup]);
            }

            $delivery_percentage = \Illuminate\Support\Facades\DB::table('basegroups')->where('id', $subgroup->basegroup_id)->first()->delivery_percentage;
            if ($dealer_id) {
                DB::table('invoices')->where('id', $dealer_id->id)->update([
                    'invoice' => $invoice,
                    'basegroup_id' => $subgroup->basegroup_id,
                    'subgroup_id' => $subgroup->id,
                    'product_code' => $product_code,
                    'product_name' => $product_name,
                    'date' => $date,
                    'pack_size' => $pack_size,
                    'shade_name' => $shade_name,
                    'quantity' => $quantity,
                    'net_amount' => $net_amount,
                    'total_volume' => $pack_size * $quantity,
                    'delivery_percentage' => $delivery_percentage,
                    'transferable_stock' => (($pack_size * $quantity) * $delivery_percentage) / 100,
                    'updated_at' => Carbon::now()

                ]);
            } else {
                DB::table('invoices')->insert([
                    'dealer_id' => $dealer['id'],
                    'invoice' => $invoice,
                    'basegroup_id' => $subgroup->basegroup_id,
                    'subgroup_id' => $subgroup->id,
                    'product_code' => $product_code,
                    'product_name' => $product_name,
                    'date' => $date,
                    'pack_size' => $pack_size,
                    'shade_name' => $shade_name,
                    'quantity' => $quantity,
                    'net_amount' => $net_amount,
                    'total_volume' => $pack_size * $quantity,
                    'delivery_percentage' => $delivery_percentage,
                    'transferable_stock' => (($pack_size * $quantity) * $delivery_percentage) / 100,
                    'created_at' => Carbon::now()
                ]);
            }
            $data = [
                'message' => 'Success',
            ];


            return response()->json(['data' => $data], 200);
        } else {
            $data = [
                'error' => 'Unauthorized Access.',
            ];
            return response()->json(['data' => $data], 200);
        }
    }

    //credit note insert api through elite erp
    public function credit_note(Request $request)
    {
        $erp_api_key = $request->erp_api_key;
        $code = $request->code;
        $date = $request->date;
        $credit_no = $request->credit_no;
        $type = $request->type;
        $amount = $request->amount;
        $fixed = new Constants();
        if ($fixed->geterp_api_key() == $erp_api_key) {
            $dealer = DealerUser::where('code', $code)->select('id')->get()->first();
            if (!$dealer) {
                $data = [
                    'error' => 'DEALER CODE NOT MATCHED.',
                ];
                return response()->json(['data' => $data], 200);
            }

            $dealer_id = DB::table('credit_notes')->where('dealer_id', $dealer['id'])->where('date', $date)->where('type', $type)->select('id')->get()->last();
            if ($dealer_id) {
                DB::table('credit_notes')->where('id', $dealer_id->id)->update([
                    'credit_no' => $credit_no,
                    'type' => $type,
                    'amount' => $amount, 'date' => $date,
                ]);
            } else {
                DB::table('credit_notes')->insert([
                    'dealer_id' => $dealer['id'],
                    'credit_no' => $credit_no,
                    'type' => $type,
                    'amount' => $amount, 'date' => $date,
                ]);
            }
            $data = [
                'message' => 'Success',
            ];
            return response()->json(['data' => $data], 200);
        } else {
            $data = [
                'error' => 'Unauthorized Access.',
            ];
            return response()->json(['data' => $data], 200);
        }
    }

    //account statement insert api through elite erp
    public function account_statement(Request $request)
    {
        $erp_api_key = $request->erp_api_key;
        $code = $request->code;
        $invoice_id = $request->invoice_id;
        $invoice_amount = $request->invoice_amount;
        $paid_amount = $request->paid_amount;
        $remaining_amount = $request->remaining_amount;
        $description = $request->description;
        $date = $request->date;
        $fixed = new Constants();
        if ($fixed->geterp_api_key() == $erp_api_key) {
            $dealer = DealerUser::where('code', $code)->select('id')->get()->first();
            if (!$dealer) {
                $data = [
                    'error' => 'DEALER CODE NOT MATCHED.',
                ];
                return response()->json(['data' => $data], 200);
            }

            $dealer_id = DB::table('account_statements')->where('dealer_id', $dealer['id'])->where('invoice_id', $invoice_id)->select('id')->get()->last();
            if ($dealer_id) {
                DB::table('account_statements')->where('id', $dealer_id->id)->update([
                    'invoice_id' => $invoice_id,
                    'invoice_amount' => $invoice_amount,
                    'paid_amount' => $paid_amount,
                    'remaining_amount' => $remaining_amount,
                    'description' => $description,
                    'date' => $date,
                ]);
            } else {
                DB::table('account_statements')->insert([
                    'dealer_id' => $dealer['id'],
                    'invoice_id' => $invoice_id,
                    'invoice_amount' => $invoice_amount,
                    'paid_amount' => $paid_amount,
                    'remaining_amount' => $remaining_amount,
                    'description' => $description,
                    'date' => $date,
                ]);
            }
            $data = [
                'message' => 'Success',
            ];
            return response()->json(['data' => $data], 200);
        } else {
            $data = [
                'error' => 'Unauthorized Access.',
            ];
            return response()->json(['data' => $data], 200);
        }
    }

    //account summary insert api through elite erp
    public function account_summary(Request $request)
    {
        $erp_api_key = $request->erp_api_key;
        $code = $request->code;
        $balance = $request->balance;
        $sales = $request->sales;
        $payment = $request->payment;
        $fixed = new Constants();
        if ($fixed->geterp_api_key() == $erp_api_key) {
            $dealer = DealerUser::where('code', $code)->select('id')->get()->first();
            if (!$dealer) {
                $data = [
                    'error' => 'DEALER CODE NOT MATCHED.',
                ];
                return response()->json(['data' => $data], 200);
            }
            $dealer_id = DB::table('account_summery')->where('dealer_id', $dealer['id'])->select('id')->get()->last();
            if ($dealer_id) {
                DB::table('account_summery')->where('id', $dealer_id->id)->update([
                    'balance' => $balance,
                    'sales' => $sales,
                    'payment' => $payment,
                ]);
            } else {
                DB::table('account_summery')->insert([
                    'dealer_id' => $dealer['id'],
                    'balance' => $balance,
                    'sales' => $sales,
                    'payment' => $payment,
                ]);
            }
            $data = [
                'message' => 'Success',
            ];
            return response()->json(['data' => $data], 200);
        } else {
            $data = [
                'error' => 'Unauthorized Access.',
            ];
            return response()->json(['data' => $data], 200);
        }
    }

    //total sales insert api through elite erp
    public function test_api(Request $request)
    {
        $erp_api_key = $request->erp_api_key;
        $code = $request->code;
        $total_sales = $request->total_sales;

        $fixed = new Constants();
        if ($fixed->geterp_api_key() == $erp_api_key) {

            $dealer = DealerUser::where('code', $code)->select('id')->get()->first();
            if (!$dealer) {
                $data = [
                    'error' => 'DEALER CODE NOT MATCHED.',
                ];
                return response()->json(['data' => $data], 200);
            }
            $dealer_id = DB::table('total_sales')->where('dealer_id', $dealer['id'])->select('id')->get()->last();
            if ($dealer_id) {
                DB::table('total_sales')->where('id', $dealer_id->id)->update([
                    'total_sales' => $total_sales,
                ]);
            } else {
                DB::table('total_sales')->insert([
                    'dealer_id' => $dealer['id'],
                    'total_sales' => $total_sales,
                ]);
            }
            $data = [
                'message' => 'Success',
            ];
            return response()->json(['data' => $data], 200);
        } else {
            $data = [
                'error' => 'Unauthorized Access.',
            ];
            return response()->json(['data' => $data], 200);
        }
    }

    public static function add_photo(Request $request)
    {

        $app_identifier = $request->app_identifier;
        $user_token = $request->header('USER-TOKEN');
        $picture = $request->picture;
        $fixed = new Constants();
        if ($user_token) {
            if ($app_identifier == 'com.ets.elitepaint.dealer') {
                $dealer = DealerUser::where('user_token', $user_token)
                    ->select(
                        'id',
                        'password',
                        'status',
                        'code',
                        'name',
                        'phone'
                    )->get()->last();
                //dd($dealer);
                if ($dealer) {
                    //dd('s');
                    $photo = new DealerUser();
                    $pic = base64_decode($picture);

                    $milliseconds = round(microtime(true) * 1000);

                    $image_name = $milliseconds . '_' . uniqid() . '.jpeg';

                    Storage::url('app/public/' . '' . $image_name);
                    Storage::disk('local')->put($image_name, $pic);

                    $photo->picture = $image_name;
                    DB::table('dealer_users')->where('user_token', $user_token)->update(['picture' => $image_name, 'picture_type' => 'api']);

                    $fixed = new Constants();
                } else {
                    $data = [
                        'error' => 'USER TOKEN NOT MATCHED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
            } elseif ($app_identifier == 'com.ets.elitepaint.painter') {
                $dealer = PainterUser::where('user_token', $user_token)
                    ->select(
                        'id',
                        'password',
                        'status',
                        'code',
                        'name',
                        'phone'
                    )->get()->last();
                //dd($dealer);
                if ($dealer) {
                    $photo = new DealerUser();
                    $pic = base64_decode($picture);

                    $milliseconds = round(microtime(true) * 1000);

                    $image_name = $milliseconds . '_' . uniqid() . '.jpeg';

                    Storage::url('app/public/' . '' . $image_name);
                    Storage::disk('local')->put($image_name, $pic);

                    $photo->picture = $image_name;
                    DB::table('painter_users')->where('user_token', $user_token)->update(['picture' => $image_name, 'picture_type' => 'api']);

                    $fixed = new Constants();
                } else {
                    $data = [
                        'error' => 'USER TOKEN NOT MATCHED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }


        $data = ['image_url' => $fixed->getStoragePath() . $image_name];
        return response()->json(['data' => $data], 200);
    }

    public function phone_available(Request $request)
    {

        $phone = $request->phone;
        $app_identifier = $request->app_identifier;
        $plus = substr($phone, 2);
        //dd($plus);
        if ($app_identifier == 'com.ets.elitepaint.dealer') {
            $dealer = DealerUser::where('phone', $phone)->where('soft_delete', 1)
                ->select('id', 'password', 'status')->get()->last();
            $painter = PainterUser::where('phone', $phone)->where('soft_delete', 1)
                ->select('id', 'password', 'status')->get()->last();

            $dealers = DealerUser::where('phone', $plus)->where('soft_delete', 1)
                ->select('id', 'password', 'status')->get()->last();
            $painters = PainterUser::where('phone', $plus)->where('soft_delete', 1)
                ->select('id', 'password', 'status')->get()->last();
            if ($dealer || $painter) {
                $data = [
                    'error' => 'এই নম্বরটি ইতোপূর্বে নিবন্ধিত হয়েছে',

                ];
                return response()->json(['data' => $data], 200);
            } elseif ($dealers || $painters) {
                $data = [
                    'error' => 'এই নম্বরটি ইতোপূর্বে নিবন্ধিত হয়েছে',

                ];
                return response()->json(['data' => $data], 200);
            } else {
                $data = [
                    'message' => 'ok.',
                ];
                return response()->json(['data' => $data], 200);
            }
        } elseif ($app_identifier == 'com.ets.elitepaint.painter') {
            $painter = PainterUser::where('phone', $phone)->where('soft_delete', 1)
                ->select('id', 'password', 'status')->get()->last();
            $dealer = DealerUser::where('phone', $phone)->where('soft_delete', 1)
                ->select('id', 'password', 'status')->get()->last();

            $painters = PainterUser::where('phone', $plus)->where('soft_delete', 1)
                ->select('id', 'password', 'status')->get()->last();
            $dealers = DealerUser::where('phone', $plus)->where('soft_delete', 1)
                ->select('id', 'password', 'status')->get()->last();
            if ($dealer || $painter) {
                $data = [
                    'error' => 'এই নম্বরটি ইতোপূর্বে নিবন্ধিত হয়েছে',

                ];
                return response()->json(['data' => $data], 200);
            } elseif ($dealers || $painters) {
                $data = [
                    'error' => 'এই নম্বরটি ইতোপূর্বে নিবন্ধিত হয়েছে',

                ];
                return response()->json(['data' => $data], 200);
            } else {
                $data = [
                    'message' => 'ok.',
                ];
                return response()->json(['data' => $data], 200);
            }
        }
    }

    public function registration(Request $request)
    {
        $dealer_id = $request->dealer_id;
        $dealer_code = $request->code;
        $dealer_name = $request->name;
        $dealer_email = $request->email;
        $dealer_phone = $request->phone;
        $rocket_number = $request->rocket_number;
        $division_id = $request->division_id;
        $district_id = $request->district_id;
        $thana_id = $request->thana_id;
        $depo = $request->depo;
        $nid = $request->nid;
        $picture = $request->picture;
        $nid_pic = $request->nid_pic;
        $pass = $request->password;
        $app_identifier = $request->app_identifier;
        $date = $request->created_at;
        $password = Hash::make($pass);
        $fixed = new Constants();
        $current_date = NULL;
        $now = Carbon::now();
        if ($request->depo) {
            $depo_info = DB::table('depos')->where('depo', $depo)->first();
        }
        //dd($nid_pic);
        if ($date != NULL) {
            $current_date = $date;
        } else {
            $current_date = $now->toDateTimeString();
        }
        if ($app_identifier == 'com.ets.elitepaint.dealer') {
            $dealer = DealerUser::where('code', $dealer_code)->where('soft_delete', 1)->select('id', 'status')->get()->last();
            if ($dealer) {
                $data = [
                    'error' => 'কোডটি ইতোমধ্যে নিবন্ধিত হয়েছে',
                ];
                return response()->json(['data' => $data], 200);
            }

            $deale = DealerUser::where('email', $dealer_email)->where('soft_delete', 1)->select('id', 'email', 'status')->get()->last();
            //dd($deale['email']);
            if ($deale && $deale['email'] != NULL) {
                //dd('d');
                $data = [
                    'error' => 'ইমেইলটি ইতোমধ্যে নিবন্ধিত হয়েছে',
                ];
                return response()->json(['data' => $data], 200);
            }
            $photo = new DealerUser();
            $pic = base64_decode($nid_pic);
            $pro_pic = base64_decode($picture);

            $milliseconds = round(microtime(true) * 1000);

            $image_name = $milliseconds . '_' . uniqid() . '.jpeg';
            $image_name1 = $milliseconds . '_' . uniqid() . '.jpeg';

            Storage::url('app/public/' . '' . $image_name);
            Storage::disk('local')->put($image_name, $pic);


            Storage::url('app/public/' . '' . $image_name1);
            Storage::disk('local')->put($image_name1, $pro_pic);

            $photo->nid_picture = $image_name;
            $photo->picture = $image_name1;
            //dd($nid_pic);
            if ($nid_pic || $picture) {
                //dd($image_name);
                //dd('$image_name');
                DB::table('dealer_users')->insert([
                    'code' => $dealer_code,
                    'name' => $dealer_name,
                    'email' => $dealer_email,
                    'phone' => $dealer_phone,
                    'rocket_number' => $rocket_number,
                    'password' => $password,
                    'division_id' => $division_id,
                    'district_id' => $district_id,
                    'thana_id' => $thana_id,
                    'nid' => $nid,
                    'register_by' => 'SELF',
                    'depo' => $depo,
                    'depo_id' => $depo_info ? $depo_info->id : NULL,
                    'nid_picture' => $image_name,
                    'picture' => $image_name1,
                    'status' => 0,
                    'created_at' => $current_date
                ]);
                $data = ['message' => 'নিবন্ধন করার জন্য আপনাকে ধন্যবাদ', 'type' => "message"];
                return response()->json(['data' => $data], 200);
            } elseif ($nid_pic) {
                DB::table('dealer_users')->insert([
                    'code' => $dealer_code,
                    'name' => $dealer_name,
                    'email' => $dealer_email,
                    'phone' => $dealer_phone,
                    'rocket_number' => $rocket_number,
                    'password' => $password,
                    'division_id' => $division_id,
                    'district_id' => $district_id,
                    'thana_id' => $thana_id,
                    'nid' => $nid,
                    'depo' => $depo,
                    'depo_id' => $depo_info ? $depo_info->id : NULL,
                    'register_by' => 'SELF',
                    'nid_picture' => $image_name,
                    'status' => 0,
                    'created_at' => $current_date
                ]);
                $data = ['message' => 'নিবন্ধন করার জন্য আপনাকে ধন্যবাদ', 'type' => "message"];
                return response()->json(['data' => $data], 200);
            } elseif ($picture) {
                DB::table('dealer_users')->insert([
                    'code' => $dealer_code,
                    'name' => $dealer_name,
                    'email' => $dealer_email,
                    'phone' => $dealer_phone,
                    'rocket_number' => $rocket_number,
                    'password' => $password,
                    'division_id' => $division_id,
                    'district_id' => $district_id,
                    'thana_id' => $thana_id,
                    'nid' => $nid,
                    'depo' => $depo,
                    'depo_id' => $depo_info ? $depo_info->id : NULL,
                    'register_by' => 'SELF',
                    'picture' => $image_name1,
                    'status' => 0,
                    'created_at' => $current_date
                ]);
                $data = ['message' => 'নিবন্ধন করার জন্য আপনাকে ধন্যবাদ', 'type' => "message"];
                return response()->json(['data' => $data], 200);
            } else {
                //dd('asadff');
                DB::table('dealer_users')->insert([
                    'code' => $dealer_code,
                    'name' => $dealer_name,
                    'email' => $dealer_email,
                    'phone' => $dealer_phone,
                    'rocket_number' => $rocket_number,
                    'password' => $password,
                    'division_id' => $division_id,
                    'district_id' => $district_id,
                    'thana_id' => $thana_id,
                    'nid' => $nid,
                    'depo' => $depo,
                    'depo_id' => $depo_info ? $depo_info->id : NULL,
                    'register_by' => 'SELF',
                    'status' => 0,
                    'created_at' => $current_date
                ]);
                $data = ['message' => 'নিবন্ধন করার জন্য আপনাকে ধন্যবাদ', 'type' => "message"];
                return response()->json(['data' => $data], 200);
            }
        } elseif ($app_identifier == 'com.ets.elitepaint.painter') {


            $deale = PainterUser::where('email', $dealer_email)->where('soft_delete', 1)->select('id', 'status')->get()->last();
            if ($deale && $deale['email'] != NULL) {
                $data = [
                    'error' => 'ইমেইলটি ইতোমধ্যে নিবন্ধিত হয়েছে',
                ];
                return response()->json(['data' => $data], 200);
            }
            $pain = PainterUser::get()->last();
            //dd($pain);
            if (!$pain) {
                $x = 1;
            } else {
                $x = $pain['id'];
                $x++;
                //d($x);
            }

            $invitation_code = 'PNT-' . $x;

            $photo = new DealerUser();
            $pic = base64_decode($nid_pic);
            $pro_pic = base64_decode($picture);

            $milliseconds = round(microtime(true) * 1000);

            $image_name = $milliseconds . '_' . uniqid() . '.jpeg';
            $image_name1 = $milliseconds . '_' . uniqid() . '.jpeg';
            if ($nid_pic) {
                Storage::url('app/public/' . '' . $image_name);
                Storage::disk('local')->put($image_name, $pic);
            }


            if ($picture) {
                Storage::url('app/public/' . '' . $image_name1);
                Storage::disk('local')->put($image_name1, $pro_pic);
            }

            $photo->nid_picture = $image_name;
            $photo->picture = $image_name1;
            if ($nid_pic && $picture) {
                DB::table('painter_users')->insert([
                    'dealer_id' => $dealer_id,
                    'code' => $invitation_code,
                    'name' => $dealer_name,
                    'email' => $dealer_email,
                    'phone' => $dealer_phone,
                    'rocket_number' => $rocket_number,
                    'password' => $password,
                    'division_id' => $division_id,
                    'district_id' => $district_id,
                    'thana_id' => $thana_id,
                    'nid' => $nid,
                    'depo' => $depo,
                    'depo_id' => $depo_info ? $depo_info->id : NULL,
                    'register_by' => 'SELF',
                    'nid_picture' => $image_name,
                    'picture' => $image_name1,
                    'status' => 0,
                    'created_at' => $current_date
                ]);
                $data = ['message' => 'নিবন্ধন করার জন্য আপনাকে ধন্যবাদ', 'type' => "message"];
                return response()->json(['data' => $data], 200);
            } elseif ($nid_pic) {
                DB::table('painter_users')->insert([
                    'dealer_id' => $dealer_id,
                    'code' => $invitation_code,
                    'name' => $dealer_name,
                    'email' => $dealer_email,
                    'phone' => $dealer_phone,
                    'rocket_number' => $rocket_number,
                    'password' => $password,
                    'division_id' => $division_id,
                    'district_id' => $district_id,
                    'thana_id' => $thana_id,
                    'nid' => $nid,
                    'depo' => $depo,
                    'depo_id' => $depo_info ? $depo_info->id : NULL,
                    'register_by' => 'SELF',
                    'nid_picture' => $image_name,
                    'status' => 0,
                    'created_at' => $current_date
                ]);
                $data = ['message' => 'নিবন্ধন করার জন্য আপনাকে ধন্যবাদ', 'type' => "message"];
                return response()->json(['data' => $data], 200);
            } elseif ($picture) {
                DB::table('painter_users')->insert([
                    'dealer_id' => $dealer_id,
                    'code' => $invitation_code,
                    'name' => $dealer_name,
                    'email' => $dealer_email,
                    'phone' => $dealer_phone,
                    'rocket_number' => $rocket_number,
                    'password' => $password,
                    'division_id' => $division_id,
                    'district_id' => $district_id,
                    'thana_id' => $thana_id,
                    'nid' => $nid,
                    'depo' => $depo,
                    'depo_id' => $depo_info ? $depo_info->id : NULL,
                    'register_by' => 'SELF',
                    'picture' => $image_name1,
                    'status' => 0,
                    'created_at' => $current_date
                ]);
                $data = ['message' => 'নিবন্ধন করার জন্য আপনাকে ধন্যবাদ', 'type' => "message"];
                return response()->json(['data' => $data], 200);
            } else {
                DB::table('painter_users')->insert([
                    'dealer_id' => $dealer_id,
                    'code' => $invitation_code,
                    'name' => $dealer_name,
                    'email' => $dealer_email,
                    'phone' => $dealer_phone,
                    'rocket_number' => $rocket_number,
                    'password' => $password,
                    'division_id' => $division_id,
                    'district_id' => $district_id,
                    'thana_id' => $thana_id,
                    'nid' => $nid,
                    'depo' => $depo,
                    'depo_id' => $depo_info ? $depo_info->id : NULL,
                    'register_by' => 'SELF',
                    'status' => 0,
                    'created_at' => $current_date
                ]);
                $data = ['message' => 'নিবন্ধন করার জন্য আপনাকে ধন্যবাদ', 'type' => "message"];
                return response()->json(['data' => $data], 200);
            }
        }
    }

    //old login api for dealer and painter
    public function login(Request $request)
    {
        $data = [
            'error' => 'Update Android Version Please',
        ];
        return response()->json(['data' => $data], 200);
        $phone = $request->phone;
        $udid = $request->uid;
        $app_version = $request->app_version;
        $platform = $request->platform;
        $pass = $request->password;
        $device = $request->device;
        $app_identifier = $request->app_identifier;
        $push_id = $request->push_token;
        $current_date = NULL;
        $now = Carbon::now();
        $current_date = $now->toDateTimeString();
        $cur = $now->toDateString();
        if (!$phone) {
            $data = [
                'error' => 'আপনি কোন মোবাইল নম্বর প্রবেশ করাননি',
            ];
            return response()->json(['data' => $data], 200);
        }
        if (!$pass) {
            $data = [
                'error' => 'আপনি কোন পাসওর্য়াড ‍প্রবেশ করাননিি',
            ];
            return response()->json(['data' => $data], 200);
        }
        $seed = str_split('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxy'
            . '0123456789'); // and any other characters
        shuffle($seed);
        $code = '';
        foreach (array_rand($seed, 36) as $k) $code .= $seed[$k];
        if ($app_identifier == 'com.ets.elitepaint.dealer') {
            $dealer = DealerUser::where('phone', $phone)->where('soft_delete', 1)->select('id', 'password', 'status', 'disable')->get()->last();
            if (!$dealer) {
                $data = [
                    'error' => 'আপনার মোবাইল নম্বরটি নিবদ্ধিত নয়',
                ];
                return response()->json(['data' => $data], 200);
            }
            if ($dealer) {
                if (Hash::check($pass, $dealer['password'])) {
                    if ($dealer['disable'] == 2) {
                        $data = [
                            'error' => 'Your Id is Disable.',
                        ];
                        return response()->json(['data' => $data], 200);
                    }
                    if ($dealer['status'] == 0) {
                        $data = [
                            'error' => 'আপনার আইডিটি এডমিনের অনুমোদনের অপেক্ষায় রয়েছে',
                        ];
                        return response()->json(['data' => $data], 200);
                    } else {

                        DB::table('dealer_users')->where('phone', $phone)->where('soft_delete', 1)->update([
                            'uid' => $udid,
                            'app_version' => $app_version,
                            'platform' => $platform,
                            'device' => $device,
                            'push_token' => $push_id,
                            'user_token' => $code,
                        ]);
                        DB::table('login_logs')->insert([
                            'uid' => $udid,
                            'app_version' => $app_version,
                            'platform' => $platform,
                            'device' => $device,
                            'app_identifier' => $app_identifier,
                            'dealer_painter_id' => $dealer['id'],
                            'created_at' => $current_date,
                            'push_token' => $push_id
                        ]);
                    }
                } else {
                    $data = [
                        'error' => 'আপনি ভুল পাসওর্য়াড ‍দিয়েছেন',
                    ];
                    return response()->json(['data' => $data], 200);
                }
            }
        } elseif ($app_identifier == 'com.ets.elitepaint.painter') {
            $painter = PainterUser::where('phone', $phone)->where('soft_delete', 1)->select('id', 'password', 'status', 'disable')->get()->last();

            if (!$painter) {
                $data = [
                    'error' => 'আপনার মোবাইল নম্বরটি নিবদ্ধিত নয়',
                ];
                return response()->json(['data' => $data], 200);
            }
            if ($painter) {
                if (Hash::check($pass, $painter['password'])) {
                    if ($painter['disable'] == 2) {
                        $data = [
                            'error' => 'Your Id is Disable.',
                        ];
                        return response()->json(['data' => $data], 200);
                    }
                    if ($painter['status'] == 0) {
                        $data = [
                            'error' => 'আপনার আইডিটি এডমিনের অনুমোদনের অপেক্ষায় রয়েছে',
                        ];
                        return response()->json(['data' => $data], 200);
                    } else {
                        DB::table('painter_users')->where('phone', $phone)->where('soft_delete', 1)->update([
                            'uid' => $udid,
                            'app_version' => $app_version,
                            'platform' => $platform,
                            'device' => $device,
                            'push_token' => $push_id,
                            'user_token' => $code,
                        ]);
                        DB::table('login_logs')->insert([
                            'uid' => $udid,
                            'app_version' => $app_version,
                            'platform' => $platform,
                            'device' => $device,
                            'app_identifier' => $app_identifier,
                            'dealer_painter_id' => $painter['id'],
                            'created_at' => $current_date,
                            'push_token' => $push_id
                        ]);
                    }
                } else {
                    $data = [
                        'error' => 'আপনি ভুল পাসওর্য়াড ‍দিয়েছেন',
                    ];
                    return response()->json(['data' => $data], 200);
                }
            }
        }
        if ($app_identifier == 'com.ets.elitepaint.dealer') {
            $dealer = DealerUser::where('phone', $phone)->where('soft_delete', 1)->select('id', 'password', 'status', 'disable')->get()->last();
            if ($dealer) {
                $notification = Notification::where('app_identifier', 'com.ets.elitepaint.dealer')->whereNotNull('image')->where('soft_delete', 1)->where('date', '>=', $cur)
                    ->select('id', 'image', 'duration')->get()
                    ->last();
                if ($notification) {
                    $notifications = 'http://somriddhi.elitepaint.com.bd/images/' . $notification['image'];
                    if (!$notification['duration']) {
                        $duration = 0;
                    } else {
                        $duration = $notification['duration'];
                    }

                    $data = ['message' => 'সফল লগইন', 'type' => "message", 'duration' => $duration, 'notification_image' => $notifications, 'user-token' => $code];
                    return response()->json(['data' => $data], 200);
                } else {
                    $data = ['message' => 'সফল লগইন', 'type' => "message", 'user-token' => $code];
                    return response()->json(['data' => $data], 200);
                }
            }
        } else {
            $painter = PainterUser::where('phone', $phone)->where('soft_delete', 1)->select('id', 'password', 'status', 'disable')->get()->last();
            if ($painter) {
                $notification = Notification::where('app_identifier', 'com.ets.elitepaint.painter')->whereNotNull('image')->where('soft_delete', 1)->where('date', '>=', $cur)
                    ->select('id', 'image', 'duration')->get()
                    ->last();
                if ($notification) {
                    $notifications = 'http://somriddhi.elitepaint.com.bd/images/' . $notification['image'];
                    if (!$notification['duration']) {
                        $duration = 0;
                    } else {
                        $duration = $notification['duration'];
                    }
                    $data = ['message' => 'সফল লগইন', 'type' => "message", 'duration' => $duration, 'notification_image' => $notifications, 'user-token' => $code];
                    return response()->json(['data' => $data], 200);
                } else {
                    $data = ['message' => 'সফল লগইন', 'type' => "message", 'user-token' => $code];
                    return response()->json(['data' => $data], 200);
                }
            }
        }
    }

    //forget_password api for dealer/painter
    public function forget_password(Request $request)
    {
        $phone = $request->phone;
        $app_identifier = $request->app_identifier;
        $user_token = $request->header('USER-TOKEN');

        if ($user_token) {
            if ($app_identifier == 'com.ets.elitepaint.dealer') {
                $dealer = DealerUser::where('phone', $phone)->where('soft_delete', 1)
                    ->select('id', 'password', 'status')->get()->last();
                if ($dealer) {
                    $dashboard = [
                        'password' => $dealer['password']
                    ];
                } else {
                    $data = [
                        'error' => 'আপনার নম্বরটি নিবন্ধিত নয়।',
                    ];
                    return response()->json(['data' => $data], 200);
                }
            } elseif ($app_identifier == 'com.ets.elitepaint.painter') {
                $dealer = PainterUser::where('phone', $phone)->where('soft_delete', 1)
                    ->select('id', 'password', 'status')->get()->last();

                if ($dealer) {
                    $dashboard = [
                        'password' => $dealer['password']
                    ];
                } else {
                    $data = [
                        'error' => 'আপনার নম্বরটি নিবন্ধিত নয়।',
                    ];
                    return response()->json(['data' => $data], 200);
                }
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }

        return response()->json(['data' => $dashboard], 200);
    }

    //old update password api for dealer/painter
    public function update_password(Request $request)
    {

        $new_password = $request->new_password;
        $app_identifier = $request->app_identifier;
        $user_token = $request->header('USER-TOKEN');
        $password = Hash::make($new_password);
        if ($user_token) {
            if ($app_identifier == 'com.ets.elitepaint.dealer') {
                DB::table('dealer_users')->where('user_token', $user_token)->update(['password' => $password]);
            } elseif ($app_identifier == 'com.ets.elitepaint.painter') {
                DB::table('painter_users')->where('user_token', $user_token)->update(['password' => $password]);
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }
        $data = ['message' => 'আপনার পাসওর্য়াডটি সফলভাবে পরির্বতন হয়েছে', 'type' => "message"];
        return response()->json(['data' => $data], 200);
    }

    //new update password api for dealer/painter
    public function new_update_password(Request $request)
    {

        $new_password = $request->new_password;
        $app_identifier = $request->app_identifier;
        $phone = $request->phone;
        $user_token = $request->header('USER-TOKEN');
        $password = Hash::make($new_password);
        if ($app_identifier == 'com.ets.elitepaint.dealer') {
            DB::table('dealer_users')->where('phone', $phone)->where('soft_delete', 1)->update(['password' => $password]);
        } elseif ($app_identifier == 'com.ets.elitepaint.painter') {
            DB::table('painter_users')->where('phone', $phone)->where('soft_delete', 1)->update(['password' => $password]);
        }
        $data = ['message' => 'আপনার পাসওর্য়াডটি সফলভাবে পরির্বতন হয়েছে', 'type' => "message"];
        return response()->json(['data' => $data], 200);
    }

    //get profile information api for dealer/painter
    public function get_profile(Request $request)
    {

        $app_identifier = $request->app_identifier;
        $user_token = $request->header('USER-TOKEN');
        $fixed = new Constants();

        $now = Carbon::now();
        $month = $now->year . '-' . $now->month;
        $year = $now->year;
        $start = Carbon::parse($month)->startOfMonth()->todateString();
        $start_year = Carbon::parse($year)->startOfYear();
        $end = Carbon::parse($month)->today()->todateString();
        $start_date = $start . ' 00:00:00';
        $end_date = $end . ' 23:59:59';
        $last_year = $year - 1;
        //checking user token send or not
        if ($user_token) {
            //for dealer
            if ($app_identifier == 'com.ets.elitepaint.dealer') {
                $dealer = DealerUser::where('user_token', $user_token)
                    ->get()->last();
                if ($dealer) {
                    //get bonus point last year
                    $bonus_point_last_year = BonusPoint::where('dealer_id', $dealer['id'])
                        ->whereYear('created_at', Carbon::now()->subYear(1))
                        ->where('soft_delete', 1)
                        ->sum('bonus_point');
                    //get bonus point this year
                    $bonus_point_year = BonusPoint::where('dealer_id', $dealer['id'])
                        ->whereYear('created_at', Carbon::now()->year)
                        ->where('soft_delete', 1)
                        ->sum('bonus_point');
                    //get scan point last year
                    $scanpoint_last_year = ScanPoint::where('dealer_id', $dealer['id'])
                        ->whereYear('created_at', Carbon::now()->subYear(1))
                        ->sum('point');

                    //get scan point this year
                    $scanpoint_year = ScanPoint::where('dealer_id', $dealer['id'])
                        ->whereYear('created_at', Carbon::now()->year)
                        ->sum('point');
                    //get redeem point this year
                    $redeems = RedeemPoint::where('dealer_id', $dealer['id'])
                        ->whereYear('created_at', Carbon::now()->year)
                        ->where('status', 1)
                        ->sum('redeem_point');

                    //get volume point last year
                    $volumes_last_year = DB::table('volume_tranfers')
                        ->where('dealer_id', $dealer['id'])
                        ->where('status', '!=', 2)
                        ->where('soft_delete', 1)
                        ->whereYear('created_at', Carbon::now()->subYear(1))
                        ->sum('dealer_point');

                    //get volume point this year
                    $volumes = DB::table('volume_tranfers')
                        ->where('dealer_id', $dealer['id'])
                        ->where('status', '!=', 2)
                        ->where('soft_delete', 1)
                        ->whereYear('created_at', Carbon::now()->year)
                        ->sum('dealer_point');

                    //get redeem point last year
                    $redeems_last_year = RedeemPoint::where('dealer_id', $dealer['id'])
                        ->whereYear('created_at', Carbon::now()->subYear(1))
                        ->where('status', 1)
                        ->sum('redeem_point');


                    $all_total = $volumes + $scanpoint_year + $bonus_point_year;

                    $all_total_last_year = $volumes_last_year + $scanpoint_last_year + $bonus_point_last_year;

                    $division = MacroDivision::where('id', $dealer['division_id'])->orderBy('created_at', 'desc')->select('id', 'division', 'created_at')->get()->first();
                    $district = MacroDistrict::where('id', $dealer['district_id'])->orderBy('created_at', 'desc')->select('id', 'district', 'created_at')->get()->first();
                    $thana = DB::table('thanas')->where('id', $dealer['thana_id'])->select('id', 'thana')->get()->first();
                    if (!$division) {
                        $division['id'] = '';
                        $division['division'] = '';
                    }
                    if (!$district) {
                        $district['id'] = '';
                        $district['district'] = '';
                    }

                    if ($dealer['nid_picture']) {
                        if ($dealer['nid_picture_type'] == 'api') {
                            $nid_pic = $fixed->getStoragePath() . $dealer['nid_picture'];
                        } else {
                            $nid_pic = $fixed->getStoragePathWeb() . $dealer['nid_picture'];
                        }
                    } else {
                        $nid_pic = '';
                    }

                    if ($dealer['picture']) {
                        if ($dealer['picture_type'] == 'api') {
                            $picture = $fixed->getStoragePath() . $dealer['picture'];
                        } else {
                            $picture = $fixed->getStoragePathWeb() . $dealer['picture'];
                        }
                    } else {
                        $picture = '';
                    }

                    $dashboard = [
                        'code' => $dealer['code'],
                        'name' => $dealer['name'],
                        'phone' => $dealer['phone'],
                        'rocket_number' => $dealer['rocket_number'] == NULL ? '' : $dealer['rocket_number'],
                        'email' => $dealer['email'] == NULL ? '' : $dealer['email'],
                        'division_id' => $division['id'],
                        'division' => $division['division'],
                        'district' => $district['district'],
                        'district_id' => $district['id'],
                        'thana' => $thana ? $thana->thana : '',
                        'thana_id' => $thana ? $thana->id : '',
                        'alternative_number' => $dealer['alternative_number'] == NULL ? '' : $dealer['alternative_number'],
                        'nid' => $dealer['nid'] == NULL ? '' : $dealer['nid'],
                        'depo' => $dealer['depo'] == NULL ? '' : $dealer['depo'],
                        'image_url' => $picture,
                        'nid_picture' => $nid_pic,
                        'type' => $dealer['member_type'],
                        'this_year_point' => round($all_total, 2),
                        'last_year_point' => round($all_total_last_year, 2),
                    ];
                } else {
                    $data = [
                        'error' => 'USER TOKEN NOT MATCHED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
            } elseif ($app_identifier == 'com.ets.elitepaint.painter') {
                //this condition is used for painter
                $dealer = PainterUser::where('user_token', $user_token)->get()->last();

                if ($dealer) {
                    //get bonus point last year
                    $bonus_point_last_year = BonusPoint::where('painter_id', $dealer['id'])
                        ->whereYear('created_at', Carbon::now()->subYear(1))
                        ->where('soft_delete', 1)
                        ->sum('bonus_point');

                    //get bonus point this year
                    $bonus_point_year = BonusPoint::where('painter_id', $dealer['id'])
                        ->whereYear('created_at', Carbon::now()->year)
                        ->where('soft_delete', 1)
                        ->sum('bonus_point');
                    //get scan point last year
                    $scanpoint_last_year = ScanPoint::where('painter_id', $dealer['id'])
                        ->whereYear('created_at', Carbon::now()->subYear(1))
                        ->sum('point');

                    //get scan point this year
                    $scanpoint_year = ScanPoint::where('painter_id', $dealer['id'])
                        ->whereYear('created_at', Carbon::now()->year)
                        ->sum('point');

                    //get volume point last year
                    $volumes_last_year = DB::table('volume_tranfers')
                        ->where('painter_id', $dealer['id'])
                        ->where('status', '!=', 2)
                        ->where('soft_delete', 1)
                        ->whereYear('created_at', Carbon::now()->subYear(1))
                        ->sum('painter_point');

                    //get volume point this year
                    $volumes = DB::table('volume_tranfers')
                        ->where('painter_id', $dealer['id'])
                        ->where('status', '!=', 2)
                        ->where('soft_delete', 1)
                        ->whereYear('created_at', Carbon::now()->year)
                        ->sum('painter_point');

                    $all_total = $volumes + $scanpoint_year + $bonus_point_year;

                    $all_total_last_year = $volumes_last_year + $scanpoint_last_year + $bonus_point_last_year;

                    $division = MacroDivision::where('id', $dealer['division_id'])->orderBy('created_at', 'desc')->select('id', 'division', 'created_at')->get()->first();
                    $d = DealerUser::where('id', $dealer['dealer_id'])->get()->last();
                    $district = MacroDistrict::where('id', $dealer['district_id'])->orderBy('created_at', 'desc')->select('id', 'district', 'created_at')->get()->first();
                    $thana = DB::table('thanas')->where('id', $dealer['thana_id'])->select('id', 'thana')->get()->first();
                    if (!$division) {
                        $division['id'] = '';
                        $division['division'] = '';
                    }
                    if (!$district) {
                        $district['id'] = '';
                        $district['district'] = '';
                    }
                    if (!$d) {
                        $d['id'] = '';
                        $d['name'] = '';
                        $d['code'] = '';
                    }
                    if ($dealer['nid_picture']) {
                        if ($dealer['nid_picture_type'] == 'api') {
                            $nid_pic = $fixed->getStoragePath() . $dealer['nid_picture'];
                        } else {
                            $nid_pic = $fixed->getStoragePathWeb() . $dealer['nid_picture'];
                        }
                    } else {
                        $nid_pic = '';
                    }

                    if ($dealer['picture']) {
                        if ($dealer['picture_type'] == 'api') {
                            $picture = $fixed->getStoragePath() . $dealer['picture'];
                        } else {
                            $picture = $fixed->getStoragePathWeb() . $dealer['picture'];
                        }
                    } else {
                        $picture = '';
                    }

                    $dashboard = [
                        'code' => $dealer['code'],
                        'name' => $dealer['name'],
                        'phone' => $dealer['phone'],
                        'rocket_number' => $dealer['rocket_number'] == NULL ? '' : $dealer['rocket_number'],
                        'email' => $dealer['email'],
                        'dealer_id' => $d['id'],
                        'dealer_name' => $d['name'],
                        'dealer_code' => $d['code'],
                        'depo' => $dealer['depo'] == NULL ? '' : $dealer['depo'],
                        'division_id' => $division['id'],
                        'division' => $division['division'],
                        'district' => $district['district'],
                        'district_id' => $district['id'],
                        'thana' => $thana ? $thana->thana : '',
                        'thana_id' => $thana ? $thana->id : '',
                        'alternative_number' => $dealer['alternative_number'] == NULL ? '' : $dealer['alternative_number'],
                        'nid' => $dealer['nid'] == NULL ? '' : $dealer['nid'],
                        'image_url' => $picture,
                        'nid_picture' => $nid_pic,
                        'type' => $dealer['member_type'],
                        'this_year_point' => round($all_total, 2),
                        'last_year_point' => round($all_total_last_year, 2),
                    ];

                } else {
                    $data = [
                        'error' => 'USER TOKEN NOT MATCHED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }
        return response()->json(['data' => $dashboard], 200);
    }

    //initial information for profile api
    public function get_initial_info_profile(Request $request)
    {

        $app_identifier = $request->app_identifier;
        $user_token = $request->header('USER-TOKEN');
        $fixed = new Constants();
        if ($user_token) {
            if ($app_identifier == 'com.ets.elitepaint.dealer') {
                $dealer = DealerUser::where('user_token', $user_token)
                    ->select(
                        'id',
                        'password',
                        'status',
                        'code',
                        'name',
                        'phone',
                        'email',
                        'picture',
                        'alternative_number'
                    )->get()->last();

                if ($dealer) {
                    //deport list
                    $buisness_array = array(
                        "Dhaka North Depot", "Barishal Depot", "Mymensing Depot", "Bogura Depot", "Rajshahi Depot", "Sylhet Depot", "Khulna Depot", "Cumilla Depot", "Factory Office", "Chattogram Depot", "Dhaka South Depot", "Rangpur Depot"
                    );

                    $buisness_arrays = [];
                    foreach ($buisness_array as $buisness) {
                        $data = [
                            'title' => $buisness
                        ];
                        $buisness_arrays[] = $data;
                    }
                    //division list
                    $division_list = MacroDivision::select('id', 'division')->get()->toArray();
                    $divisions = [];
                    foreach ($division_list as $division) {
                        $data = [
                            'id' => $division['id'],
                            'title' => $division['division'],
                        ];
                        $divisions[] = $data;
                    }
                } else {
                    $data = [
                        'error' => 'USER TOKEN NOT MATCHED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
            } elseif ($app_identifier == 'com.ets.elitepaint.painter') {
                $dealer = PainterUser::where('user_token', $user_token)
                    ->select(
                        'id',
                        'password',
                        'status',
                        'code',
                        'name',
                        'phone',
                        'email',
                        'picture',
                        'alternative_number'
                    )->get()->last();

                if ($dealer) {
                    //deport list
                    $buisness_array = array(
                        "Dhaka North Depot", "Barishal Depot", "Mymensing Depot", "Bogura Depot", "Rajshahi Depot", "Sylhet Depot", "Khulna Depot", "Cumilla Depot", "Factory Office", "Chattogram Depot", "Dhaka South Depot", "Rangpur Depot"
                    );

                    $buisness_arrays = [];
                    foreach ($buisness_array as $buisness) {
                        $data = [
                            'title' => $buisness
                        ];
                        $buisness_arrays[] = $data;
                    }
                    //division list
                    $division_list = MacroDivision::select('id', 'division')->get()->toArray();
                    $divisions = [];
                    foreach ($division_list as $division) {
                        $data = [
                            'id' => $division['id'],
                            'title' => $division['division'],
                        ];
                        $divisions[] = $data;
                    }
                } else {
                    $data = [
                        'error' => 'USER TOKEN NOT MATCHED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }
        return response()->json(['data' => ['divisions' => $divisions, 'depo' => $buisness_arrays]], 200);
    }

    public function get_district(Request $request)
    {
        $division_id = $request->division_id;
        $divisions = MacroDistrict::where('division_id', $division_id)->orderBy('created_at', 'desc')->select('id', 'district')->get()->toArray();
        if (!$divisions) {
            $data = [
                'message' => 'NO DISTRICT FOUND.',
            ];
            return response()->json(['data' => $data], 200);
        }
        $allData = [];
        foreach ($divisions as $division) {
            $data = [
                'id' => $division['id'],
                'title' => $division['district']
            ];
            $allData[] = $data;
        }
        return response()->json(['data' => $allData], 200);
    }

    public function get_thana(Request $request)
    {
        $division_id = $request->district_id;
        $divisions = DB::table('thanas')->where('district_id', $division_id)
            ->get()->toArray();
        if (!$divisions) {
            $data = [
                'message' => 'NO DISTRICT FOUND.',
            ];
            return response()->json(['data' => $data], 200);
        }
        $allData = [];
        foreach ($divisions as $division) {
            $data = [
                'id' => $division->id,
                'title' => $division->thana
            ];
            $allData[] = $data;
        }
        return response()->json(['data' => $allData], 200);
    }

    //profile update api
    public function update_profile(Request $request)
    {
        $dealer_id = $request->dealer_id;
        $name = $request->name;
        $email = $request->email;
        $division_id = $request->division_id;
        $district_id = $request->district_id;
        $thana_id = $request->thana_id;
        $nid = $request->nid;
        $depo = $request->depo;
        $nid_pic = $request->nid_picture;
        $alternative_number = $request->alternative_number;
        $rocket_number = $request->rocket_number;
        $app_identifier = $request->app_identifier;
        $user_token = $request->header('USER-TOKEN');

        if ($user_token) {
            if ($app_identifier == 'com.ets.elitepaint.dealer') {
                $dealer = DealerUser::where('user_token', $user_token)
                    ->select(
                        'id',
                        'password',
                        'status',
                        'code',
                        'name',
                        'phone'
                    )->get()->last();
                if ($dealer) {
                    if ($nid_pic) {
                        $photo = new DealerUser();
                        $pic = base64_decode($nid_pic);

                        $milliseconds = round(microtime(true) * 1000);

                        $image_name = $milliseconds . '_' . uniqid() . '.jpeg';

                        Storage::url('app/public/' . '' . $image_name);
                        Storage::disk('local')->put($image_name, $pic);

                        $photo->nid_picture = $image_name;
                        DB::table('dealer_users')->where('user_token', $user_token)->update([
                            'name' => $name,
                            'alternative_number' => $alternative_number,
                            'rocket_number' => $rocket_number,
                            'division_id' => $division_id,
                            'district_id' => $district_id,
                            'thana_id' => $thana_id,
                            'nid' => $nid,
                            'depo' => $depo,
                            'nid_picture' => $image_name,
                            'email' => $email
                        ]);
                    } else {
                        DB::table('dealer_users')->where('user_token', $user_token)->update([
                            'name' => $name,
                            'alternative_number' => $alternative_number,
                            'rocket_number' => $rocket_number,
                            'division_id' => $division_id,
                            'district_id' => $district_id,
                            'thana_id' => $thana_id,
                            'nid' => $nid,
                            'depo' => $depo,
                            'email' => $email
                        ]);
                    }
                } else {
                    $data = [
                        'error' => 'USER TOKEN NOT MATCHED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
            } elseif ($app_identifier == 'com.ets.elitepaint.painter') {
                $dealer = PainterUser::where('user_token', $user_token)
                    ->select(
                        'id',
                        'password',
                        'status',
                        'code',
                        'name',
                        'phone'
                    )->get()->last();
                if ($dealer) {
                    if ($nid_pic) {
                        $photo = new DealerUser();
                        $pic = base64_decode($nid_pic);

                        $milliseconds = round(microtime(true) * 1000);

                        $image_name = $milliseconds . '_' . uniqid() . '.jpeg';

                        Storage::url('app/public/' . '' . $image_name);
                        Storage::disk('local')->put($image_name, $pic);

                        $photo->nid_picture = $image_name;
                        DB::table('painter_users')->where('user_token', $user_token)->update([
                            'name' => $name,
                            'division_id' => $division_id,
                            'district_id' => $district_id,
                            'dealer_id' => $dealer_id,
                            'thana_id' => $thana_id,
                            'depo' => $depo,
                            'nid' => $nid,
                            'nid_picture' => $image_name,
                            'alternative_number' => $alternative_number,
                            'rocket_number' => $rocket_number,
                            'email' => $email
                        ]);
                    } else {
                        DB::table('painter_users')->where('user_token', $user_token)->update([
                            'name' => $name,
                            'dealer_id' => $dealer_id,
                            'division_id' => $division_id,
                            'district_id' => $district_id,
                            'thana_id' => $thana_id,
                            'depo' => $depo,
                            'nid' => $nid,
                            'alternative_number' => $alternative_number,
                            'rocket_number' => $rocket_number,
                            'email' => $email
                        ]);
                    }
                } else {
                    $data = [
                        'error' => 'USER TOKEN NOT MATCHED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }

        $data = ['message' => 'আপনার প্রোফাইলটি আপডেট হয়েছে।', 'type' => "message"];
        return response()->json(['data' => $data], 200);
    }

    //token update api when dealer/painter login
    public function update_token(Request $request)
    {

        $push_token = $request->push_token;
        $app_identifier = $request->app_identifier;
        $user_token = $request->header('USER-TOKEN');

        if ($user_token) {
            if ($app_identifier == 'com.ets.elitepaint.dealer') {
                DB::table('dealer_users')->where('user_token', $user_token)->update(['push_token' => $push_token]);
            } elseif ($app_identifier == 'com.ets.elitepaint.painter') {
                DB::table('painter_users')->where('user_token', $user_token)->update(['push_token' => $push_token]);
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }

        $data = ['message' => 'Thank you for Registration.', 'type' => "message"];
        return response()->json(['data' => $data], 200);
    }

    //get notification api
    public function notification(Request $request)
    {

        $app_identifier = $request->app_identifier;
        $user_token = $request->header('USER-TOKEN');
        $now = Carbon::now()->toDateString();

        if ($user_token) {
            if ($app_identifier == 'com.ets.elitepaint.dealer') {
                $dealers = Notification::where('app_identifier', 'com.ets.elitepaint.dealer')->where('soft_delete', 1)
                    ->select('id', 'title', 'details', 'image', 'date', 'created_at')->get()
                    ->toArray();
                if (!$dealers) {
                    $data = [
                        'message' => 'NO NOTIFICATION AVAILABLE.',
                    ];
                    return response()->json(['data' => $data], 200);
                }

                $allData = [];

                foreach ($dealers as $dealer) {
                    if ($dealer['image']) {
                        $image = 'http://somriddhi.elitepaint.com.bd/images/' . $dealer['image'];
                    } else {
                        $image = '';
                    }
                    if ($dealer['date'] >= $now) {
                        $data = [
                            'id' => $dealer['id'],
                            'title' => $dealer['title'],
                            'details' => $dealer['details'],
                            'image' => $image,
                            'created_at' => $dealer['created_at']
                        ];
                        $allData[] = $data;
                    }
                }
            } elseif ($app_identifier == 'com.ets.elitepaint.painter') {
                $dealers = Notification::where('app_identifier', 'com.ets.elitepaint.painter')->where('soft_delete', 1)
                    ->select('id', 'title', 'details', 'date', 'image', 'created_at')->get()
                    ->toArray();
                if (!$dealers) {
                    $data = [
                        'message' => 'NO NOTIFICATION AVAILABLE.',
                    ];
                    return response()->json(['data' => $data], 200);
                }

                $allData = [];

                foreach ($dealers as $dealer) {

                    if ($dealer['image']) {
                        $image = 'http://somriddhi.elitepaint.com.bd/images/' . $dealer['image'];
                    } else {
                        $image = '';
                    }
                    if ($dealer['date'] >= $now) {
                        $data = [
                            'id' => $dealer['id'],
                            'title' => $dealer['title'],
                            'details' => $dealer['details'],
                            'image' => $image,
                            'created_at' => $dealer['created_at']
                        ];
                        $allData[] = $data;
                    }
                }
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }


        return response()->json(['data' => $allData], 200);
    }

    //scan point information api for dealer/painter
    public function get_dealer_claim_points(Request $request)
    {

        $app_identifier = $request->app_identifier;
        $user_token = $request->header('USER-TOKEN');
        $now = Carbon::now();
        $month = $now->year . '-' . $now->month;
        $year = $now->year;
        $start = Carbon::parse($month)->startOfMonth()->todateString();
        $start_year = Carbon::parse($year)->startOfYear();
        $end = Carbon::parse($month)->today()->todateString();
        $start_date = $start . ' 00:00:00';
        $end_date = $end . ' 23:59:59';
        $last_year = $year - 1;

        if ($user_token) {
            if ($app_identifier == 'com.ets.elitepaint.dealer') {
                $dealer = DealerUser::where('user_token', $user_token)
                    ->get()->last();
                if ($dealer) {

                    $scanpoint_month = ScanPoint::select(DB::raw('count(id) as count, sum(point) as point'))
                        ->where('dealer_id', $dealer['id'])
                        ->whereBetween('created_at', [$start_date, $end_date])
                        ->get();

                    $scanpoint_year = ScanPoint::select(DB::raw('count(id) as count, sum(point) as point'))
                        ->where('dealer_id', $dealer['id'])
                        ->whereYear('created_at', Carbon::now()->year)
                        ->get();

                    $scanpoint_last_year = ScanPoint::select(DB::raw('count(id) as count, sum(point) as point'))
                        ->where('dealer_id', $dealer['id'])
                        ->whereYear('created_at', Carbon::now()->subYear(1))
                        ->get();

                    $this_month_point = is_null($scanpoint_month[0]->point) ? 0 : $scanpoint_month[0]->point;
                    $this_month_count = is_null($scanpoint_month[0]->count) ? 0 : $scanpoint_month[0]->count;
                    $this_year_point = is_null($scanpoint_year[0]->point) ? 0 : $scanpoint_year[0]->point;
                    $this_year_count = is_null($scanpoint_year[0]->count) ? 0 : $scanpoint_year[0]->count;
                    $last_year_point = is_null($scanpoint_last_year[0]->point) ? 0 : $scanpoint_last_year[0]->point;

                    $dashboard = [
                        'points_scanned_month' => $this_month_point,
                        'no_of_scan_month' => $this_month_count,
                        'points_scanned_year' => $this_year_point,
                        'no_of_scan_year' => $this_year_count,
                        'points_scanned_last_year' => $last_year_point
                    ];
                } else {
                    $data = [
                        'error' => 'USER TOKEN NOT MATCHED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
            } elseif ($app_identifier == 'com.ets.elitepaint.painter') {
                $dealer = PainterUser::where('user_token', $user_token)
                    ->select(
                        'id',
                        'password',
                        'status',
                        'code',
                        'name',
                        'phone'
                    )->get()->last();
                if ($dealer) {
                    $scanpoint_month = ScanPoint::select(DB::raw('count(id) as count, sum(point) as point'))
                        ->where('painter_id', $dealer['id'])
                        ->whereBetween('created_at', [$start_date, $end_date])
                        ->get();
                    $scanpoint_year = ScanPoint::select(DB::raw('count(id) as count, sum(point) as point'))
                        ->where('painter_id', $dealer['id'])
                        ->whereYear('created_at', Carbon::now()->year)
                        ->get();
                    $scanpoint_last_year = ScanPoint::select(DB::raw('count(id) as count, sum(point) as point'))
                        ->where('painter_id', $dealer['id'])
                        ->whereYear('created_at', Carbon::now()->subYear(1))
                        ->get();

                    $this_month_point = is_null($scanpoint_month[0]->point) ? 0 : $scanpoint_month[0]->point;
                    $this_month_count = is_null($scanpoint_month[0]->count) ? 0 : $scanpoint_month[0]->count;
                    $this_year_point = is_null($scanpoint_year[0]->point) ? 0 : $scanpoint_year[0]->point;
                    $this_year_count = is_null($scanpoint_year[0]->count) ? 0 : $scanpoint_year[0]->count;
                    $last_year_point = is_null($scanpoint_last_year[0]->point) ? 0 : $scanpoint_last_year[0]->point;

                    $dashboard = [
                        'points_scanned_month' => $this_month_point,
                        'no_of_scan_month' => $this_month_count,
                        'points_scanned_year' => $this_year_point,
                        'no_of_scan_year' => $this_year_count,
                        'points_scanned_last_year' => $last_year_point
                    ];
                } else {
                    $data = [
                        'error' => 'USER TOKEN NOT MATCHED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }
        return response()->json(['data' => $dashboard], 200);
    }

    //dealer dashboard page api
    public function dealer_dashboard(Request $request)
    {

        $user_token = $request->header('USER-TOKEN');
        $now = Carbon::now();
        $month = $now->year . '-' . $now->month;
        $year = $now->year;
        $start = Carbon::parse($month)->startOfMonth()->todateString();
        $start_year = Carbon::parse($year)->startOfYear();
        $end = Carbon::parse($month)->today()->todateString();
        $start_date = $start . ' 00:00:00';
        $end_date = $end . ' 23:59:59';
        $last_year = $year - 1;

        if ($user_token) {
            $dealer = DealerUser::where('user_token', $user_token)
                ->get()->last();
            if ($dealer) {

                $bonuspoint_year = BonusPoint::where('dealer_id', $dealer['id'])
                    ->whereYear('created_at', Carbon::now()->year)
                    ->where('soft_delete', 1)
                    ->sum('bonus_point');

                $scanpoint_year = ScanPoint::where('dealer_id', $dealer['id'])
                    ->whereYear('created_at', Carbon::now()->year)
                    ->sum('point');

                $volume_year = VolumeTranfer::where('dealer_id', $dealer['id'])
                    ->where('status', '!=', 2)
                    ->where('soft_delete', 1)
                    ->whereYear('created_at', Carbon::now()->year)
                    ->sum('dealer_point');

                $total = $bonuspoint_year + $scanpoint_year + $volume_year;

                $total_sales = VolumeTranfer::where('dealer_id', $dealer['id'])
                    ->where('soft_delete', 1)
                    ->where('status', '!=', 2)
                    ->whereYear('created_at', Carbon::now()->year)
                    ->sum('quantity');


                $dashboard = [
                    'total_sales' => number_format($total_sales, 2),
                    'total_points' => number_format($total, 2),
                    'total_scan_point' => number_format($scanpoint_year, 2),
                    'total_volume_point' => number_format($volume_year, 2),
                ];
                $login_logs = DB::table('login_logs')->where('dealer_painter_id', $dealer['id'])->select('id')->get()->last();
                DB::table('login_logs')->where('id', $login_logs->id)->update(['status' => 'Success']);
            } else {
                $data = [
                    'error' => 'USER TOKEN NOT MATCHED.',
                ];
                return response()->json(['data' => $data], 200);
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }
        return response()->json(['data' => ['sales' => $dashboard]], 200);
    }

    //unused fuction
    private function generateDateRange(Carbon $start_date, Carbon $end_date)
    {
        $dates = [];
        for ($date = $start_date->copy(); $date->lte($end_date); $date->addDay()) {
            $dates[] = $date->format('Y-m-d');
        }
        return $dates;
    }

    //unused fuction
    private function findExpenseByDay($date, $a)
    {

        $totalExpense = 5 + $a;
        $day = Carbon::parse($date)->format('l');
        $dateName = Carbon::parse($date)->format('d-M');

        $result = ['sales' => $totalExpense, 'date' => $dateName, 'day' => $day];
        return $result;
    }

    //painter dashboard information api
    public function painter_dashboard(Request $request)
    {
        $user_token = $request->header('USER-TOKEN');
        $fixed = new Constants();
        $now = Carbon::now();
        $month = $now->year . '-' . $now->month;
        $year = $now->year;
        $start = Carbon::parse($month)->startOfMonth()->todateString();
        $start_year = Carbon::parse($year)->startOfYear();
        $end = Carbon::parse($month)->today()->todateString();
        $start_date = $start . ' 00:00:00';
        $end_date = $end . ' 23:59:59';
        $last_year = $year - 1;

        if ($user_token) {

            $painter = PainterUser::where('user_token', $user_token)
                ->get()->last();
            if ($painter) {
                $bonuspoint_year = BonusPoint::where('painter_id', $painter['id'])
                    ->whereDate('created_at', '<=', '2022-12-31')->where('soft_delete', 1)
                    ->select('id', 'bonus_point')->sum('bonus_point');

                $total_scan_point = ScanPoint::where('painter_id', $painter['id'])
                    ->whereDate('created_at', '<=', '2022-12-31')
                    ->sum('point');

                $total_volume_point = VolumeTranfer::where('painter_id', $painter['id'])->where('status', '!=', 2)->where('soft_delete', 1)
                    ->where('created_at', 'LIKE', '%' . $year . '%')
                    ->sum('painter_point');
                $total_volume_point_last_year = VolumeTranfer::where('painter_id', $painter['id'])->where('status', '!=', 2)->where('soft_delete', 1)
                    ->where('created_at', 'LIKE', '%' . $last_year . '%')
                    ->sum('painter_point');

                $total = VolumeTranfer::where('painter_id', $painter['id'])
                    ->where('status', '!=', 2)
                    ->where('soft_delete', 1)
                    ->where('created_at', 'LIKE', '%' . $year . '%')
                    ->sum('painter_point');

                $members = EliteMember::where('category', 'PNT')->where('soft_delete', 1)->where('id', $painter['elite_member_id'])->get();

                if ($members[0]['member_type'] == 'Platinum Member') {
                    $next_point = $members[0]['point'];
                } else {
                    $next_point = $members[0]['point'] + 1;
                }
                $target = EliteMember::where('category', 'PNT')->where('soft_delete', 1)->whereRaw($next_point . ' between from_point and to_point')->get();

                $current_membership_status = $painter['member_type'];

                if ($painter['picture']) {
                    if ($painter['picture_type'] == 'api') {
                        $picture = $fixed->getStoragePath() . $painter['picture'];
                    } else {
                        $picture = $fixed->getStoragePathWeb() . $painter['picture'];
                    }
                } else {
                    $picture = '';
                }

                $level2 = explode(' ', $target[0]['member_type'])[0];

                if ($current_membership_status == 'Platinum Member') {
                    $level2 = '∞';
                }
                $x = $target[0]['from_point'];
                $diff = round($x, 2) - round($total, 2);
                if ($current_membership_status == 'Platinum Member') {
                    $message = 'You are achieved highest level.';
                    $progress_bar = 100;
                } else {
                    $message = round($diff, 2) . ' points to ' . $target[0]['member_type'];
                    $j = 100 * round($total, 2);
                    $progress_bar = $j / round($x, 2);
                }
                $total_current_points = total_current_points($painter['id']);

                $dashboard = [
                    'total_current_points' => (string)round($total_current_points, 2),
                    'current_points' => (string)round($total, 2),
                    'current_membership_status' => $painter['member_type'],
                    'target_membership_status' => $target[0]['member_type'],
                    'level1' => explode(' ', $painter['member_type'])[0],
                    'level2' => $level2,
                    'message' => $message,
                    'progress_bar' => (int)$progress_bar,
                    'total_points' => (string)round($x, 2),
                    'image_url' => $picture,
                    'name' => $painter['name']
                ];

                $login_logs = DB::table('login_logs')->where('dealer_painter_id', $painter['id'])->select('id')->get()->last();
                DB::table('login_logs')->where('id', $login_logs->id)->update(['status' => 'Success']);
            } else {
                $data = [
                    'error' => 'USER TOKEN NOT MATCHED.',
                ];
                return response()->json(['data' => $data], 200);
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }

        return response()->json(['data' => $dashboard], 200);
    }

    //painterTotalPointHistories for total Points
    public function painterTotalPointHistories(Request $request)
    {
        $user_token = $request->header('USER-TOKEN');
        info('called Histories ' . $user_token);

        if ($user_token) {

            $painter = PainterUser::where('user_token', $user_token)->first();
            if ($painter) {
                $total_volume_point = DB::table('volume_tranfers')
                    ->where('painter_id', $painter->id)
                    ->where('status', '!=', 2)
                    ->where('soft_delete', '=', 1)
                    ->whereYear('created_at', Carbon::now()->year)
                    ->sum('painter_point');
                $total_scan_point = DB::table('scan_points')
                    ->where('painter_id', $painter->id)
                    ->whereYear('created_at', Carbon::now()->year)
                    ->sum('point');
                $total_bonus_point = DB::table('bonus_points')->where('painter_id', $painter->id)
                    ->where('soft_delete', '=', 1)
                    ->whereYear('created_at', Carbon::now()->year)
                    ->sum('bonus_point');
                $total_point = $total_volume_point + $total_scan_point + $total_bonus_point;
                $total_redeem_point = DB::table('redeem_points')
                    ->where('painter_id', $painter->id)
                    ->whereYear('end_date', Carbon::now()->year)
                    ->where('status', 1)
                    ->sum('redeem_point');
                $pending_redeem_point = $total_point - $total_redeem_point;

                $pending = DB::table('redeem_points')
                    ->where('status', 2)
                    ->where('painter_id', $painter->id)
                    ->whereYear('end_date', Carbon::now()->year - 1);

                $last_year_scan_point = $pending->sum('total_scan_point');
                $last_year_scan_point = max($last_year_scan_point, 0);
                $last_year_bonus_point = $pending->sum('bonus_point');
                $last_year_bonus_point = max($last_year_bonus_point, 0);
                $last_year_volume_point = $pending->sum('volumes');
                $last_year_volume_point = max($last_year_volume_point, 0);

                $dashboard = [
                    'total_scan_point' => (string)round($total_scan_point, 2),
                    'total_volume_point' => (string)round($total_volume_point, 2),
                    'total_bonus_point' => (string)round($total_bonus_point, 2),
                    'total_point' => (string)round($total_point, 2),
                    'total_redeem_point' => (string)round($total_redeem_point, 2),
                    'pending_redeem_point' => (string)round(max($pending_redeem_point, 0), 2),
                    'last_year_scan_point' => (string)round($last_year_scan_point, 2),
                    'last_year_bonus_point' => (string)round($last_year_bonus_point, 2),
                    'last_year_volume_point' => (string)round($last_year_volume_point, 2),
                ];

            } else {
                $data = [
                    'error' => 'USER TOKEN NOT MATCHED.',
                ];
                return response()->json(['data' => $data], 200);
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }

        return response()->json(['data' => $dashboard], 200);
    }

    //get promotional offer api
    public function promotional_offers(Request $request)
    {

        $app_identifier = $request->app_identifier;
        $user_token = $request->header('USER-TOKEN');

        if ($user_token) {
            if ($app_identifier == 'com.ets.elitepaint.dealer') {
                $dealers = Notification::where('app_identifier', 'com.ets.elitepaint.dealer')
                    ->select('id', 'title', 'details', 'created_at')->get()
                    ->toArray();
                if (!$dealers) {
                    $data = [
                        'message' => 'NO NOTIFICATION AVAILABLE.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
                $allData = [];
                foreach ($dealers as $dealer) {
                    $data = [
                        'id' => $dealer['id'],
                        'title' => $dealer['title'],
                        'details' => $dealer['details'],
                        'created_at' => $dealer['created_at']
                    ];
                    $allData[] = $data;
                }
            } elseif ($app_identifier == 'com.ets.elitepaint.painter') {
                $dealers = Notification::where('app_identifier', 'com.ets.elitepaint.painter')
                    ->select('id', 'title', 'details', 'created_at')->get()
                    ->toArray();
                if (!$dealers) {
                    $data = [
                        'message' => 'NO NOTIFICATION AVAILABLE.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
                $allData = [];
                foreach ($dealers as $dealer) {
                    $data = [
                        'id' => $dealer['id'],
                        'title' => $dealer['title'],
                        'details' => $dealer['details'],
                        'created_at' => $dealer['created_at']
                    ];
                    $allData[] = $data;
                }
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }
        return response()->json(['data' => $allData], 200);
    }

    //get redeem info for dealer/painter
    public function get_redeem_info(Request $request)
    {

        $app_identifier = $request->app_identifier;
        $user_token = $request->header('USER-TOKEN');

        if ($user_token) {
            if ($app_identifier == 'com.ets.elitepaint.dealer') {
                $dealer = DealerUser::where('user_token', $user_token)
                    ->get()->last();
                if ($dealer) {
                    $bonus_point = BonusPoint::where('dealer_id', $dealer['id'])
                        ->where('soft_delete', 1)
                        ->whereYear('created_at', Carbon::now()->year)
                        ->sum('bonus_point');

                    $scan_point = ScanPoint::where('dealer_id', $dealer['id'])
                        ->whereYear('created_at', Carbon::now()->year)
                        ->sum('point');

                    $volumes = VolumeTranfer::where('dealer_id', $dealer['id'])
                        ->where('status', '!=', 2)
                        ->where('soft_delete', 1)
                        ->whereYear('created_at', Carbon::now()->year)
                        ->sum('dealer_point');

                    $redeems = RedeemPoint::where('dealer_id', $dealer['id'])
                        ->where('status', 1)
                        ->whereYear('start_date', Carbon::now()->year)
                        ->sum('redeem_point');

                    $all_total = $bonus_point + $scan_point + $volumes;
                    $available_points = $all_total - $redeems;

                    $dashboard = [
                        'available_points' => round($available_points, 2),
                        'available_amount' => round($available_points, 2),

                    ];
                } else {
                    $data = [
                        'error' => 'USER TOKEN NOT MATCHED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
            } elseif ($app_identifier == 'com.ets.elitepaint.painter') {
                $dealer = PainterUser::where('user_token', $user_token)
                    ->get()->last();
                if ($dealer) {
                    $bonus_point = BonusPoint::where('painter_id', $dealer['id'])
                        ->where('soft_delete', 1)
                        ->whereYear('created_at', Carbon::now()->year)
                        ->sum('bonus_point');

                    $scan_point = ScanPoint::where('painter_id', $dealer['id'])
                        ->whereYear('created_at', Carbon::now()->year)
                        ->sum('point');

                    $volumes = VolumeTranfer::where('painter_id', $dealer['id'])
                        ->where('status', '!=', 2)
                        ->where('soft_delete', 1)
                        ->whereYear('created_at', Carbon::now()->year)
                        ->sum('painter_point');

                    $redeems = RedeemPoint::where('painter_id', $dealer['id'])
                        ->where('status', 1)
                        ->whereYear('start_date', Carbon::now()->year)
                        ->sum('redeem_point');

                    $all_total = $bonus_point + $scan_point + $volumes;
                    $available_points = $all_total - $redeems;

                    $dashboard = [
                        'available_points' => round($available_points, 2),
                        'available_amount' => round($available_points, 2),
                    ];
                } else {
                    $data = [
                        'error' => 'USER TOKEN NOT MATCHED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }
        return response()->json(['data' => $dashboard], 200);
    }

    //unused api
    public function get_redeem_history(Request $request)
    {

        $app_identifier = $request->app_identifier;
        $user_token = $request->header('USER-TOKEN');

        $now = Carbon::now();
        $month = $now->year . '-' . $now->month;
        $start = Carbon::parse($month)->startOfMonth();
        $end = Carbon::parse($month)->today();

        $all_dates = [];

        while ($start->lte($end)) {
            $all_dates[] = $start->copy();
            $start->addDay();
        }
        $all_dates = array_reverse($all_dates);
        if ($user_token) {
            if ($app_identifier == 'com.ets.elitepaint.dealer') {
                $dealer = DealerUser::where('user_token', $user_token)
                    ->select(
                        'id',
                        'password',
                        'status',
                        'code',
                        'name',
                        'phone'
                    )->get()->last();

                if ($dealer) {
                    $allData = [];
                    foreach ($all_dates as $date) {
                        $actual_date = $date->todateString();
                        $scanpoint_year = RedeemPoint::where('dealer_id', $dealer['id'])
                            ->where('created_at', 'LIKE', '%' . $actual_date . '%')
                            ->select('id', 'redeem_point')->get()->toArray();
                        $no_of_scan_month = count($scanpoint_year);
                        $all_total_year = 0;
                        foreach ($scanpoint_year as $scanpoint) {
                            $all_total_year += $scanpoint['redeem_point'];
                        }
                        if ($scanpoint_year) {
                            $data = [
                                'date' => $date->todateString(),
                                'day' => $date->format('l'),
                                'enable' => ($scanpoint_year == null) ? 0 : 1,
                                'points' => $all_total_year,
                                'no_of_redeem' => $no_of_scan_month
                            ];
                            $allData[] = $data;
                        }
                    }
                    if (empty($allData)) {
                        $datas = [
                            'message' => 'NO DATE AVAILABLE',
                        ];
                        return response()->json(['data' => $datas], 200);
                    }
                } else {
                    $data = [
                        'error' => 'USER TOKEN NOT MATCHED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
            } elseif ($app_identifier == 'com.ets.elitepaint.painter') {
                $dealer = PainterUser::where('user_token', $user_token)
                    ->select(
                        'id',
                        'password',
                        'status',
                        'code',
                        'name',
                        'phone'
                    )->get()->last();

                if ($dealer) {
                    $allData = [];
                    foreach ($all_dates as $date) {
                        $actual_date = $date->todateString();
                        $scanpoint_year = RedeemPoint::where('painter_id', $dealer['id'])
                            ->where('created_at', 'LIKE', '%' . $actual_date . '%')
                            ->select('id', 'redeem_point')->get()->toArray();
                        $no_of_scan_month = count($scanpoint_year);
                        $all_total_year = 0;
                        foreach ($scanpoint_year as $scanpoint) {
                            $all_total_year += $scanpoint['redeem_point'];
                        }
                        if ($scanpoint_year) {
                            $data = [
                                'date' => $date->todateString(),
                                'day' => $date->format('l'),
                                'enable' => ($scanpoint_year == null) ? 0 : 1,
                                'points' => $all_total_year,
                                'no_of_redeem' => $no_of_scan_month
                            ];
                            $allData[] = $data;
                        }
                    }
                    if (empty($allData)) {
                        $datas = [
                            'message' => 'NO DATE AVAILABLE',
                        ];
                        return response()->json(['data' => $datas], 200);
                    }
                } else {
                    $data = [
                        'error' => 'USER TOKEN NOT MATCHED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }
        return response()->json(['data' => $allData], 200);
    }

    public function get_claim_history(Request $request)
    {

        $app_identifier = $request->app_identifier;
        $user_token = $request->header('USER-TOKEN');

        $now = Carbon::now();
        $month = $now->year . '-' . $now->month;
        $start = Carbon::parse($month)->startOfMonth();
        $end = Carbon::parse($month)->today();

        $all_dates = [];

        while ($start->lte($end)) {
            $all_dates[] = $start->copy();
            $start->addDay();
        }

        $all_dates = array_reverse($all_dates);

        if ($user_token) {
            if ($app_identifier == 'com.ets.elitepaint.dealer') {
                $dealer = DealerUser::where('user_token', $user_token)
                    ->select(
                        'id',
                        'password',
                        'status',
                        'code',
                        'name',
                        'phone'
                    )->get()->last();

                if ($dealer) {
                    $allData = [];
                    foreach ($all_dates as $date) {
                        $actual_date = $date->todateString();
                        $scanpoint_year = ScanPoint::where('dealer_id', $dealer['id'])
                            ->where('created_at', 'LIKE', '%' . $actual_date . '%')
                            ->select('id', 'bar_code_id')->get()->toArray();
                        $no_of_scan_month = count($scanpoint_year);
                        $all_total_year = 0;
                        foreach ($scanpoint_year as $scanpoint) {
                            $barcode = BarCode::where('id', $scanpoint['bar_code_id'])
                                ->select('id', 'product_id', 'point')->get()->last();

                            $all_total_year += $barcode['point'];
                        }
                        if ($scanpoint_year) {
                            $data = [
                                'date' => $date->todateString(),
                                'day' => $date->format('l'),
                                'enable' => ($scanpoint_year == null) ? 0 : 1,
                                'points' => $all_total_year,
                                'no_of_scan' => $no_of_scan_month
                            ];
                            $allData[] = $data;
                        }
                    }
                    if (empty($allData)) {
                        $datas = [
                            'message' => 'NO DATE AVAILABLE',
                        ];
                        return response()->json(['data' => $datas], 200);
                    }
                } else {
                    $data = [
                        'error' => 'USER TOKEN NOT MATCHED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
            } elseif ($app_identifier == 'com.ets.elitepaint.painter') {
                $dealer = PainterUser::where('user_token', $user_token)
                    ->select(
                        'id',
                        'password',
                        'status',
                        'code',
                        'name',
                        'phone'
                    )->get()->last();

                if ($dealer) {
                    $allData = [];
                    foreach ($all_dates as $date) {
                        $actual_date = $date->todateString();
                        $scanpoint_year = ScanPoint::where('painter_id', $dealer['id'])
                            ->where('created_at', 'LIKE', '%' . $actual_date . '%')
                            ->select('id', 'bar_code_id')->get()->toArray();
                        $no_of_scan_month = count($scanpoint_year);
                        $all_total_year = 0;
                        foreach ($scanpoint_year as $scanpoint) {
                            $barcode = BarCode::where('id', $scanpoint['bar_code_id'])
                                ->select('id', 'product_id', 'point')->get()->last();
                            $all_total_year += $barcode['point'];
                        }
                        if ($scanpoint_year) {
                            $data = [
                                'date' => $date->todateString(),
                                'day' => $date->format('l'),
                                'enable' => ($scanpoint_year == null) ? 0 : 1,
                                'points' => $all_total_year,
                                'no_of_scan' => $no_of_scan_month
                            ];
                            $allData[] = $data;
                        }
                    }
                    if (empty($allData)) {
                        $datas = [
                            'message' => 'NO DATE AVAILABLE',
                        ];
                        return response()->json(['data' => $datas], 200);
                    }
                } else {
                    $data = [
                        'error' => 'USER TOKEN NOT MATCHED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }
        return response()->json(['data' => $allData], 200);
    }

    public function get_claim_history_by_date_range(Request $request)
    {

        $app_identifier = $request->app_identifier;
        $user_token = $request->header('USER-TOKEN');
        $starts = $request->start_date;
        $ends = $request->end_date;
        $now = Carbon::now();
        $month = $now->year . '-' . $now->month;
        $start = Carbon::parse($starts)->toDateString();
        $end = Carbon::parse($ends)->toDateString();

        $start_date = $start . ' 00:00:00';
        $end_date = $end . ' 23:59:59';

        if ($user_token) {
            if ($app_identifier == 'com.ets.elitepaint.dealer') {
                $dealer = DealerUser::where('user_token', $user_token)
                    ->select(
                        'id',
                        'password',
                        'status',
                        'code',
                        'name',
                        'phone'
                    )->get()->last();

                if ($dealer) {
                    $scanpoint_year = ScanPoint::where('dealer_id', $dealer['id'])
                        ->whereBetween('created_at', [$start_date, $end_date])
                        ->select('id', 'bar_code_id', 'created_at')->get()->toArray();

                    $allData = [];
                    $all_total_year = 0;
                    foreach ($scanpoint_year as $scanpoint) {
                        $barcode = BarCode::where('id', $scanpoint['bar_code_id'])
                            ->select('id', 'product_id', 'point', 'bar_code')->get()->last();
                        $all_total_year += $barcode['point'];
                        $entry_time = Carbon::parse($scanpoint['created_at'])->format('g:i A');
                        $entry_date = Carbon::parse($scanpoint['created_at'])->toDateString();
                        $product = QrProduct::where('id', $barcode['product_id'])
                            ->select('id', 'product_name', 'pack_size')->get()->last();
                        $dashboard = [
                            'token_no' => $barcode['bar_code'],
                            'scan_time' => $entry_time,
                            'scan_date' => $entry_date,
                            'product' => $product['product_name'],
                            'shade_name' => '',
                            'pack_size' => $product['pack_size'],
                            'points' => $barcode['point']
                        ];
                        $allData[] = $dashboard;
                    }
                } else {
                    $data = [
                        'error' => 'USER TOKEN NOT MATCHED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
            } elseif ($app_identifier == 'com.ets.elitepaint.painter') {
                $dealer = PainterUser::where('user_token', $user_token)
                    ->select(
                        'id',
                        'password',
                        'status',
                        'code',
                        'name',
                        'phone'
                    )->get()->last();

                if ($dealer) {
                    $scanpoint_year = ScanPoint::where('painter_id', $dealer['id'])
                        ->whereBetween('created_at', [$start_date, $end_date])
                        ->select('id', 'bar_code_id', 'created_at')->get()->toArray();

                    $allData = [];
                    $all_total_year = 0;
                    foreach ($scanpoint_year as $scanpoint) {
                        $barcode = BarCode::where('id', $scanpoint['bar_code_id'])
                            ->select('id', 'product_id', 'point', 'bar_code')->get()->last();
                        $all_total_year += $barcode['point'];
                        $entry_time = Carbon::parse($scanpoint['created_at'])->format('g:i A');
                        $entry_date = Carbon::parse($scanpoint['created_at'])->toDateString();
                        $product = QrProduct::where('id', $barcode['product_id'])
                            ->select('id', 'product_name', 'pack_size')->get()->last();

                        $dashboard = [
                            'token_no' => $barcode['bar_code'],
                            'scan_time' => $entry_time,
                            'scan_date' => $entry_date,
                            'product' => $product['product_name'],
                            'shade_name' => '',
                            'pack_size' => $product['pack_size'],
                            'points' => $barcode['point']
                        ];
                        $allData[] = $dashboard;
                    }
                } else {
                    $data = [
                        'error' => 'USER TOKEN NOT MATCHED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }
        return response()->json(['data' => $allData], 200);
    }

    //redeem history api
    public function get_redeem_details(Request $request)
    {

        $app_identifier = $request->app_identifier;
        $date = $request->date;
        $user_token = $request->header('USER-TOKEN');

        $now = Carbon::now();
        $month = $now->year . '-' . $now->month;
        $year = $now->year;
        $start = Carbon::parse($month)->startOfMonth()->todateString();
        $start_year = Carbon::parse($year)->startOfYear();
        $end = Carbon::parse($month)->today()->todateString();
        $start_date = $start . ' 00:00:00';
        $end_date = $end . ' 23:59:59';
        $last_year = $year - 1;
        if ($user_token) {
            if ($app_identifier == 'com.ets.elitepaint.dealer') {
                $dealer = DealerUser::where('user_token', $user_token)
                    ->select(
                        'id',
                        'password',
                        'status',
                        'code',
                        'name',
                        'phone'
                    )->get()->last();

                if ($dealer) {
                    //get redeem point information current month
                    $scanpoint_year = RedeemPoint::where('dealer_id', $dealer['id'])
                        ->whereBetween('created_at', [$start, $end_date])->orderBy('created_at', 'desc')
                        ->select('id', 'redeem_point', 'code', 'transaction_code', 'status', 'created_at')->get()->toArray();

                    if (!$scanpoint_year) {
                        $data = [
                            'message' => 'NO HISTORY AVAILABLE',
                        ];
                        return response()->json(['data' => $data], 200);
                    }
                    $allData = [];
                    $all_total_year = 0;
                    foreach ($scanpoint_year as $scanpoint) {
                        $entry_time = Carbon::parse($scanpoint['created_at'])->format('g:i A');
                        $entry_date = Carbon::parse($scanpoint['created_at'])->toDateString();

                        $dashboard = [
                            'redeem_points' => $scanpoint['redeem_point'],
                            'redeem_date' => $entry_date,
                            'redeem_time' => $entry_time,
                            'received_money' => $scanpoint['redeem_point'],
                            'status' => $scanpoint['status'] == 2 ? 'PENDING' : 'RECEIVED',
                            'payment' => $scanpoint['status'] == 2 ? '' : $scanpoint['code'],
                            'transaction_id' => $scanpoint['transaction_code']
                        ];
                        $allData[] = $dashboard;
                    }
                } else {
                    $data = [
                        'error' => 'USER TOKEN NOT MATCHED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
            } elseif ($app_identifier == 'com.ets.elitepaint.painter') {
                $dealer = PainterUser::where('user_token', $user_token)
                    ->select(
                        'id',
                        'password',
                        'status',
                        'code',
                        'name',
                        'phone'
                    )->get()->last();
                if ($dealer) {
                    //get redeem point information current month
                    $scanpoint_year = RedeemPoint::where('painter_id', $dealer['id'])
                        ->whereBetween('created_at', [$start, $end_date])->orderBy('created_at', 'desc')
                        ->select('id', 'redeem_point', 'code', 'transaction_code', 'status', 'created_at')->get()->toArray();
                    if (!$scanpoint_year) {
                        $data = [
                            'message' => 'NO HISTORY AVAILABLE',
                        ];
                        return response()->json(['data' => $data], 200);
                    }
                    $allData = [];
                    $all_total_year = 0;
                    foreach ($scanpoint_year as $scanpoint) {

                        $entry_time = Carbon::parse($scanpoint['created_at'])->format('g:i A');
                        $entry_date = Carbon::parse($scanpoint['created_at'])->toDateString();
                        $dashboard = [
                            'redeem_points' => $scanpoint['redeem_point'],
                            'redeem_date' => $entry_date,
                            'redeem_time' => $entry_time,
                            'status' => $scanpoint['status'] == 2 ? 'PENDING' : 'RECEIVED ',
                            'payment' => $scanpoint['status'] == 2 ? '' : $scanpoint['code'],
                            'transaction_id' => $scanpoint['transaction_code']
                        ];
                        $allData[] = $dashboard;
                    }
                } else {
                    $data = [
                        'error' => 'USER TOKEN NOT MATCHED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }
        return response()->json(['data' => $allData], 200);
    }

    //scan history api
    public function get_claim_details(Request $request)
    {
        $app_identifier = $request->app_identifier;
        $user_token = $request->header('USER-TOKEN');

        $now = Carbon::now();
        $month = $now->year . '-' . $now->month;
        $year = $now->year;
        $start = Carbon::parse($month)->startOfMonth()->todateString();
        $start_year = Carbon::parse($year)->startOfYear();
        $end = Carbon::parse($month)->today()->todateString();
        $start_date = $start . ' 00:00:00';
        $end_date = $end . ' 23:59:59';
        $last_year = $year - 1;

        if ($user_token) {
            if ($app_identifier == 'com.ets.elitepaint.dealer') {
                $dealer = DealerUser::where('user_token', $user_token)
                    ->select(
                        'id',
                        'password',
                        'status',
                        'code',
                        'name',
                        'phone'
                    )->get()->last();
                if ($dealer) {
                    //get scan point information of current month
                    $scanpoint_year = ScanPoint::where('dealer_id', $dealer['id'])
                        ->whereBetween('created_at', [$start_date, $end_date])->orderBy('created_at', 'desc')
                        ->select('id', 'bar_code_id', 'created_at')->get()->toArray();
                    $allData = [];
                    $all_total_year = 0;
                    foreach ($scanpoint_year as $scanpoint) {
                        $barcode = BarCode::where('id', $scanpoint['bar_code_id'])
                            ->select('id', 'product_id', 'point', 'bar_code')->get()->last();
                        $all_total_year += $barcode['point'];
                        $entry_time = Carbon::parse($scanpoint['created_at'])->format('g:i A');
                        $entry_date = Carbon::parse($scanpoint['created_at'])->toDateString();
                        $pack = Pack::where('id', $barcode['product_id'])
                            ->select('id', 'subgroup_id', 'pack_size')->get()->last();
                        $product = SubGroup::where('id', $pack['subgroup_id'])
                            ->select('id', 'subgroup_name')->get()->last();
                        $dashboard = [
                            'token_no' => $barcode['bar_code'],
                            'scan_time' => $entry_time,
                            'scan_date' => $entry_date,
                            'product' => $product['subgroup_name'],
                            'shade_name' => '',
                            'pack_size' => $pack['pack_size'],
                            'points' => $barcode['point']
                        ];
                        $allData[] = $dashboard;
                    }
                } else {
                    $data = [
                        'error' => 'USER TOKEN NOT MATCHED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
            } elseif ($app_identifier == 'com.ets.elitepaint.painter') {
                $dealer = PainterUser::where('user_token', $user_token)
                    ->select(
                        'id',
                        'password',
                        'status',
                        'code',
                        'name',
                        'phone'
                    )->get()->last();

                if ($dealer) {
                    //get scan point information of current month
                    $scanpoint_year = ScanPoint::where('painter_id', $dealer['id'])
                        ->whereBetween('created_at', [$start_date, $end_date])->orderBy('created_at', 'desc')
                        ->select('id', 'bar_code_id', 'created_at')->get()->toArray();

                    $allData = [];
                    $all_total_year = 0;
                    foreach ($scanpoint_year as $scanpoint) {
                        $barcode = BarCode::where('id', $scanpoint['bar_code_id'])
                            ->select('id', 'product_id', 'point', 'bar_code')->get()->last();
                        $all_total_year += $barcode['point'];
                        $entry_time = Carbon::parse($scanpoint['created_at'])->format('g:i A');
                        $entry_date = Carbon::parse($scanpoint['created_at'])->toDateString();
                        $pack = Pack::where('id', $barcode['product_id'])
                            ->select('id', 'subgroup_id', 'pack_size')->get()->last();
                        $product = SubGroup::where('id', $pack['subgroup_id'])
                            ->select('id', 'subgroup_name')->get()->last();
                        $dashboard = [
                            'token_no' => $barcode['bar_code'],
                            'scan_time' => $entry_time,
                            'scan_date' => $entry_date,
                            'product' => $product['subgroup_name'],
                            'shade_name' => '',
                            'pack_size' => $pack['pack_size'],
                            'points' => $barcode['point']
                        ];
                        $allData[] = $dashboard;
                    }
                } else {
                    $data = [
                        'error' => 'USER TOKEN NOT MATCHED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }
        return response()->json(['data' => $allData], 200);
    }

    //purchase information api for painter
    public function purchase_info(Request $request)
    {
        $user_token = $request->header('USER-TOKEN');

        $now = Carbon::now();
        $month = $now->year . '-' . $now->month;
        $year = $now->year;
        $start = Carbon::parse($month)->startOfMonth()->todateString();
        $start_year = Carbon::parse($year)->startOfYear();
        $end = Carbon::parse($month)->today()->todateString();
        $start_date = $start . ' 00:00:00';
        $end_date = $end . ' 23:59:59';
        $last_year = $year - 1;
        if ($user_token) {

            $dealer = PainterUser::where('user_token', $user_token)
                ->select(
                    'id',
                    'password',
                    'status',
                    'code',
                    'name',
                    'phone'
                )->get()->last();
            if ($dealer) {
                $purchase_points_months = VolumeTranfer::where('painter_id', $dealer['id'])
                    ->where('status', '!=', 2)
                    ->where('soft_delete', 1)
                    ->whereBetween('created_at', [$start_date, $end_date])
                    ->orderBy('created_at', 'desc')->get()->toArray();

                $purchase_points_years = VolumeTranfer::where('painter_id', $dealer['id'])
                    ->where('status', '!=', 2)
                    ->where('soft_delete', 1)
                    ->whereYear('created_at', Carbon::now()->year)
                    ->select('id', 'painter_point')->get()->toArray();

                $purchase_bonus_years = DB::table('bonus_points')
                    ->where('painter_id', $dealer['id'])
                    ->where('soft_delete', 1)
                    ->whereYear('created_at', Carbon::now()->year)
                    ->sum('bonus_point');

                $purchase_points_last_years = VolumeTranfer::where('painter_id', $dealer['id'])
                    ->where('status', '!=', 2)
                    ->whereYear('created_at', Carbon::now()->subYear(1))
                    ->where('soft_delete', 1)
                    ->select('id', 'painter_point')->get()->toArray();

                $total_purchase_points_month = 0;
                $total_purchase_points_year = 0;
                $total_purchase_points_last_year = 0;
                $total_purchase_quantity_month = 0;

                foreach ($purchase_points_months as $purchase_points_month) {
                    $total_purchase_points_month += $purchase_points_month['painter_point'];
                    $total_purchase_quantity_month += floatval($purchase_points_month['quantity']);
                }
                foreach ($purchase_points_years as $purchase_points_year) {
                    $total_purchase_points_year += $purchase_points_year['painter_point'];
                }
                foreach ($purchase_points_last_years as $purchase_points_last_year) {
                    $total_purchase_points_last_year += $purchase_points_last_year['painter_point'];
                }
                $dashboard = [
                    'purchase_points_month' => round($total_purchase_points_month, 2),
                    'purchase_bonus' => round($purchase_bonus_years, 2),
                    'purchase_quantity' => round($total_purchase_quantity_month, 2),
                    'total_purchase_point_this_year' => round($total_purchase_points_year, 2),
                    'total_purchase_point_last_year' => round($total_purchase_points_last_year, 2)

                ];
                return response()->json(['data' => $dashboard], 200);
            } else {
                $data = [
                    'error' => 'USER TOKEN NOT MATCHED.',
                ];
                return response()->json(['data' => $data], 200);
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }
    }

    //get purchase details information
    public function get_purchase_details(Request $request)
    {

        $code = $request->code;
        $user_token = $request->header('USER-TOKEN');

        if ($user_token) {
            //painter information
            $dealer = PainterUser::where('user_token', $user_token)
                ->select(
                    'id',
                    'password',
                    'status',
                    'code',
                    'name',
                    'phone'
                )->get()->last();
            //dealer information
            $painter = DealerUser::where('user_token', $user_token)
                ->select(
                    'id',
                    'password',
                    'status',
                    'code',
                    'name',
                    'phone'
                )->get()->last();
            if ($dealer) {
                $orders = VolumeTranfer::where('code', $code)->where('soft_delete', 1)->orderBy('created_at', 'desc')->select(
                    'id',
                    'code',
                    'dealer_id',
                    'painter_id',
                    'product_id',
                    'quantity',
                    'dealer_point',
                    'painter_point',
                    'status',
                    'created_at'
                )->get()->toArray();
                $product_data = [];
                foreach ($orders as $order) {
                    $product = Product::where('id', $order['product_id'])->select('id', 'pack_size_id', 'shade_code', 'product_name', 'shade_name', 'price')->get()->first();
                    $pack_size = Pack::where('id', $product['pack_size_id'])->select('id', 'subgroup_id', 'pack_name', 'pack_size', 'size_code', 'uom')->get()->first();
                    $subgroup = SubGroup::where('id', $order['product_id'])->select('id', 'subgroup_code', 'subgroup_name')->get()->first();
                    $data = [
                        'product_id' => $product['id'],
                        'product_name' => $subgroup['subgroup_name'],
                        'quantity' => round($order['quantity'], 2),
                        'status' => $order['status'] == 2 ? 'PENDING' : 'ACCEPTED',
                        'painter_point' => round($order['painter_point'], 2)
                    ];
                    $product_data[] = $data;
                }
                $final_data = [
                    'products' => $product_data
                ];
            } elseif ($painter) {
                $orders = VolumeTranfer::where('code', $code)->where('soft_delete', 1)->orderBy('created_at', 'desc')->select(
                    'id',
                    'code',
                    'dealer_id',
                    'painter_id',
                    'product_id',
                    'quantity',
                    'dealer_point',
                    'painter_point',
                    'status',
                    'created_at'
                )->get()->toArray();
                $product_data = [];
                foreach ($orders as $order) {
                    $product = Product::where('id', $order['product_id'])->select('id', 'pack_size_id', 'shade_code', 'product_name', 'shade_name', 'price')->get()->first();

                    $pack_size = Pack::where('id', $product['pack_size_id'])->select('id', 'subgroup_id', 'pack_name', 'pack_size', 'size_code', 'uom')->get()->first();
                    $subgroup = SubGroup::where('id', $order['product_id'])->select('id', 'subgroup_code', 'subgroup_name')->get()->first();
                    $data = [
                        'product_id' => $product['id'],
                        'product_name' => $subgroup['subgroup_name'],
                        'quantity' => round($order['quantity'], 2),
                        'status' => $order['status'] == 2 ? 'PENDING' : 'ACCEPTED',
                        'painter_point' => round($order['painter_point'], 2)
                    ];
                    $product_data[] = $data;
                }
                $final_data = [
                    'products' => $product_data
                ];
            } else {
                $data = [
                    'error' => 'USER TOKEN NOT MATCHED.',
                ];
                return response()->json(['data' => $data], 200);
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }
        return response()->json(['data' => $final_data], 200);
    }

    //unused api
    public function get_purchase_history(Request $request)
    {

        $user_token = $request->header('USER-TOKEN');

        $now = Carbon::now();
        $month = $now->year . '-' . $now->month;
        $start = Carbon::parse($month)->startOfMonth();
        $end = Carbon::parse($month)->today();

        $all_dates = [];

        while ($start->lte($end)) {
            $all_dates[] = $start->copy();
            $start->addDay();
        }
        $all_dates = array_reverse($all_dates);
        if ($user_token) {

            $dealer = PainterUser::where('user_token', $user_token)
                ->select(
                    'id',
                    'password',
                    'status',
                    'code',
                    'name',
                    'phone'
                )->get()->last();
            if ($dealer) {
                $allData = [];
                // $transfer_point = PointTransfer::where('painter_id', $dealer['id'])->get();
                // foreach($transfer_point as $t_point){
                //     $data = [
                //         'date' => $t_point->created_at,
                //         'day' => $t_point->created_at,
                //         'enable' => 10,
                //         'purchase' => $t_point->point,
                //         'ltr' => 'Transfer to dealer',
                //     ];
                //     $allData[] = $data;
                // }
                foreach ($all_dates as $date) {
                    $actual_date = $date->todateString();
                    $complete_total = VolumeTranfer::where('painter_id', $dealer['id'])
                        ->where('created_at', 'LIKE', '%' . $actual_date . '%')->where('soft_delete', 1)->distinct('code')->count('code');
                    $total_codes = VolumeTranfer::where('painter_id', $dealer['id'])
                        ->where('created_at', 'LIKE', '%' . $actual_date . '%')->where('soft_delete', 1)->distinct('code')->select('id', 'code')->get();
                    $total_ltr = 0;
                    foreach ($total_codes as $total_code) {
                        $volum = VolumeTranfer::where('id', $total_code['id'])
                            ->select('id', 'product_id', 'quantity')->get()->last();
                        $product = Product::where('id', $volum['product_id'])
                            ->select('id', 'pack_size_id')->get()->last();
                        $pack_size = Pack::where('id', $product['pack_size_id'])
                            ->select('id', 'pack_size')->get()->last();
                        $total = $volum['quantity'] * $pack_size['pack_size'];
                        $total_ltr += $volum['quantity'];
                    }
                    if ($complete_total > 0) {
                        $data = [
                            'date' => $date->todateString(),
                            'day' => $date->format('l'),
                            'enable' => ($complete_total == null) ? 0 : 1,
                            'purchase' => $complete_total,
                            'ltr' => round($total_ltr, 2),
                        ];
                        $allData[] = $data;
                    }
                }
                if (empty($allData)) {
                    $datas = [
                        'message' => 'NO DATE AVAILABLE',
                    ];
                    return response()->json(['data' => $datas], 200);
                }
            } else {
                $data = [
                    'error' => 'USER TOKEN NOT MATCHED.',
                ];
                return response()->json(['data' => $data], 200);
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }
        return response()->json(['data' => $allData], 200);
        // return response()->json(['data'=> 'successs']);
    }

    //point itihash api
    public function points(Request $request)
    {
        $app_identifier = $request->app_identifier;
        $user_token = $request->header('USER-TOKEN');

        $now = Carbon::now();
        $month = $now->year . '-' . $now->month;
        $year = $now->year;
        $start = Carbon::parse($month)->startOfMonth()->todateString();
        $start_year = Carbon::parse($year)->startOfYear();
        $end = Carbon::parse($month)->today()->todateString();
        $start_date = $start . ' 00:00:00';
        $end_date = $end . ' 23:59:59';
        $last_year = $year - 1;

        if ($user_token) {
            if ($app_identifier == 'com.ets.elitepaint.dealer') {
                $dealer = DealerUser::where('user_token', $user_token)
                    ->get()->last();
                if ($dealer) {
                    $bonuspoint_last_year = BonusPoint::where('dealer_id', $dealer['id'])
                        ->whereYear('created_at', Carbon::now()->subYear(1))
                        ->where('soft_delete', 1)
                        ->sum('bonus_point');

                    $bonuspoint_year = BonusPoint::where('dealer_id', $dealer['id'])
                        ->whereYear('created_at', Carbon::now()->year)
                        ->where('soft_delete', 1)
                        ->sum('bonus_point');

                    $scanpoint_last_year = ScanPoint::where('dealer_id', $dealer['id'])
                        ->whereYear('created_at', Carbon::now()->subYear(1))
                        ->sum('point');

                    $scanpoint_year = ScanPoint::where('dealer_id', $dealer['id'])
                        ->whereYear('created_at', Carbon::now()->year)
                        ->sum('point');

                    $volumes_last_year = VolumeTranfer::where('dealer_id', $dealer['id'])
                        ->where('status', '!=', 2)
                        ->where('soft_delete', 1)
                        ->whereYear('created_at', Carbon::now()->subYear(1))
                        ->sum('dealer_point');

                    $volumes = VolumeTranfer::where('dealer_id', $dealer['id'])
                        ->where('status', '!=', 2)
                        ->where('soft_delete', 1)
                        ->whereYear('created_at', Carbon::now()->year)
                        ->sum('dealer_point');

                    $redeems_last_year = RedeemPoint::where('dealer_id', $dealer['id'])
                        ->whereYear('created_at', Carbon::now()->subYear(1))
                        ->where('status', 1)
                        ->select('id', 'redeem_point')->get()->toArray();

                    $redeems = RedeemPoint::where('dealer_id', $dealer['id'])
                        ->whereYear('end_date', Carbon::now()->year)
                        ->where('status', 1)
                        ->sum('redeem_point');
                    
                    
                    // point transfers //
                    $total_transfered_point = PointTransfer::where('dealer_id', $dealer['id'])
                        ->where('status', 2)
                        ->whereYear('created_at', Carbon::now()->year)
                        ->sum('point');    

                    $all_total_last_year = $volumes_last_year + $scanpoint_last_year + $bonuspoint_last_year;
                    $all_total = $bonuspoint_year + $scanpoint_year + $volumes+$total_transfered_point;
                    $total = $all_total + $all_total_last_year;
                    $available_points = $all_total - $redeems;

                    $dashboard = [
                        'earned_points_this_year' => round($all_total, 2),
                        'total_points_last_year' => round($all_total_last_year, 2),
                        'redeem_points' => round($redeems, 2),
                        'bonus_points' => round($bonuspoint_year, 2),
                        'available_points' => round($available_points, 2),
                        'received_money' => round($redeems, 2)
                    ];

                    return response()->json(['data' => $dashboard], 200);

                } else {
                    $data = [
                        'error' => 'USER TOKEN NOT MATCHED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
            } else {
                $dealer = PainterUser::where('user_token', $user_token)
                    ->get()->last();
                if ($dealer) {

                    $bonuspoint_last_year = BonusPoint::where('painter_id', $dealer['id'])
                        ->whereYear('created_at', Carbon::now()->subYear(1))
                        ->where('soft_delete', 1)
                        ->sum('bonus_point');

                    $bonuspoint_year = BonusPoint::where('painter_id', $dealer['id'])
                        ->whereYear('created_at', Carbon::now()->year)
                        ->where('soft_delete', 1)
                        ->sum('bonus_point');

                    $scanpoint_last_year = ScanPoint::where('painter_id', $dealer['id'])
                        ->whereYear('created_at', Carbon::now()->subYear(1))
                        ->sum('point');

                    $scanpoint_year = ScanPoint::where('painter_id', $dealer['id'])
                        ->whereYear('created_at', Carbon::now()->year)
                        ->sum('point');

                    $volumes_last_year = VolumeTranfer::where('painter_id', $dealer['id'])
                        ->where('status', '!=', 2)
                        ->where('soft_delete', 1)
                        ->whereYear('created_at', Carbon::now()->subYear(1))
                        ->sum('painter_point');

                    $volumes = VolumeTranfer::where('painter_id', $dealer['id'])
                        ->where('status', '!=', 2)
                        ->where('soft_delete', 1)
                        ->whereYear('created_at', Carbon::now()->year)
                        ->sum('painter_point');

                    $redeems_last_year = RedeemPoint::where('painter_id', $dealer['id'])
                        ->whereYear('created_at', Carbon::now()->subYear(1))
                        ->where('status', 1)
                        ->select('id', 'redeem_point')->get()->toArray();

                    $redeems = RedeemPoint::where('painter_id', $dealer['id'])
                        ->whereYear('end_date', Carbon::now()->year)
                        ->where('status', 1)
                        ->sum('redeem_point');
                    
                    $transfered_points = PointTransfer::where('dealer_id', $dealer['id'])
                    ->where('status', 2)
                    ->whereYear('created_at', Carbon::now()->year)
                    ->sum('point');                      


                    $all_total_last_year = $volumes_last_year + $scanpoint_last_year + $bonuspoint_last_year;
                    $all_total = $bonuspoint_year + $scanpoint_year + $volumes;
                    $total = $all_total + $all_total_last_year;
                    $available_points = $all_total ;

                    $dashboard = [
                        'earned_points_this_year' => round($all_total, 2),
                        'total_points_last_year' => round(($all_total_last_year -$transfered_points), 2),
                        'redeem_points' => round($redeems, 2),
                        'bonus_points' => round($bonuspoint_year, 2),
                        'available_points' => round($available_points, 2),
                        'received_money' => round($redeems, 2)
                    ];
                    return response()->json(['data' => $dashboard], 200);
                } else {
                    $data = [
                        'error' => 'USER TOKEN NOT MATCHED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }
    }

    //unused api
    public function get_transaction_history(Request $request)
    {
        $user_token = $request->header('USER-TOKEN');

        $now = Carbon::now();
        $month = $now->year . '-' . $now->month;
        $start = Carbon::parse($month)->startOfMonth();
        $end = Carbon::parse($month)->today();

        $all_dates = [];
        // get all dates
        while ($start->lte($end)) {
            $all_dates[] = $start->copy();
            $start->addDay();
        }
        $all_dates = array_reverse($all_dates);
        if ($user_token) {
            $dealer = DealerUser::where('user_token', $user_token)
                ->select(
                    'id',
                    'password',
                    'status',
                    'code',
                    'name',
                    'phone'
                )->get()->last();
            if ($dealer) {
                foreach ($all_dates as $date) {
                    $actual_date = $date->todateString();
                    $data = [
                        'date' => $date->todateString(),
                        'day' => $date->format('l'),
                        'enable' => 1
                    ];
                    $allData[] = $data;
                }
            } else {
                $data = [
                    'error' => 'USER TOKEN NOT MATCHED.',
                ];
                return response()->json(['data' => $data], 200);
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }
        return response()->json(['data' => $allData], 200);
    }

    //unused api
    public function volume_transfer_initil_info(Request $request)
    {
        $user_token = $request->header('USER-TOKEN');

        if ($user_token) {
            $dealer = DealerUser::where('user_token', $user_token)
                ->select(
                    'id',
                    'password',
                    'status',
                    'code',
                    'name',
                    'phone'
                )->get()->last();
            if ($dealer) {
                $advance_dealer_list = PainterUser::all()->toArray();
                $dealer_data = [];
                foreach ($advance_dealer_list as $advance_dealer) {
                    $data = [
                        'id' => $advance_dealer['id'],
                        'code' => $advance_dealer['code'],
                        'name' => $advance_dealer['name'],
                        'email' => $advance_dealer['email'],
                        'phone' => $advance_dealer['phone'],
                        'rank' => 'GOLD'
                    ];
                    $dealer_data[] = $data;
                }
            } else {
                $data = [
                    'error' => 'USER TOKEN NOT MATCHED.',
                ];
                return response()->json(['data' => $data], 200);
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }
        return response()->json(['data' => ['painters' => $dealer_data]], 200);
    }

    //unused api
    public function get_product_list(Request $request)
    {
        $app_identifier = $request->app_identifier;
        $user_token = $request->header('USER-TOKEN');

        if ($user_token) {
            if ($app_identifier == 'com.ets.elitepaint.dealer') {
                $dealer = DealerUser::where('user_token', $user_token)
                    ->select(
                        'id',
                        'password',
                        'status',
                        'code',
                        'name',
                        'phone'
                    )->get()->last();

                if ($dealer) {
                    $product_list = Product::all()->toArray();
                    $product_data = [];
                    foreach ($product_list as $product) {
                        $product_type = ProductType::where('id', $product['product_type_id'])->select('id', 'title')->get()->first();
                        $data = [
                            'id' => $product['id'],
                            'title' => $product['name'],
                            'price' => $product['price'],
                            'type_id' => $product_type['id'],
                            'type_title' => $product_type['title'],
                            'price' => $product['price'],
                            'description' => $product['description']
                        ];
                        $product_data[] = $data;
                    }
                } else {
                    $data = [
                        'error' => 'USER TOKEN NOT MATCHED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
            } elseif ($app_identifier == 'com.ets.elitepaint.painter') {
                $dealer = PainterUser::where('user_token', $user_token)
                    ->select(
                        'id',
                        'password',
                        'status',
                        'code',
                        'name',
                        'phone'
                    )->get()->last();
                if ($dealer) {
                    $product_list = Product::all()->toArray();
                    $product_data = [];
                    foreach ($product_list as $product) {
                        $product_type = ProductType::where('id', $product['product_type_id'])->select('id', 'title')->get()->first();
                        $data = [
                            'id' => $product['id'],
                            'title' => $product['name'],
                            'price' => $product['price'],
                            'type_id' => $product_type['id'],
                            'type_title' => $product_type['title'],
                            'price' => $product['price'],
                            'description' => $product['description']
                        ];
                        $product_data[] = $data;
                    }
                } else {
                    $data = [
                        'error' => 'USER TOKEN NOT MATCHED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }
        return response()->json(['data' => ['products' => $product_data]], 200);
    }

    //unused api
    public function purchase_initil_info(Request $request)
    {
        $user_token = $request->header('USER-TOKEN');
        if ($user_token) {
            $dealer = PainterUser::where('user_token', $user_token)
                ->select(
                    'id',
                    'password',
                    'status',
                    'code',
                    'name',
                    'phone'
                )->get()->last();

            if ($dealer) {
                $advance_dealer_list = DealerUser::all()->toArray();
                $dealer_data = [];
                foreach ($advance_dealer_list as $advance_dealer) {
                    $data = [
                        'id' => $advance_dealer['id'],
                        'code' => $advance_dealer['code'],
                        'name' => $advance_dealer['name'],
                        'email' => $advance_dealer['email'],
                        'phone' => $advance_dealer['phone'],
                        'rank' => 'GOLD'
                    ];
                    $dealer_data[] = $data;
                }
            } else {
                $data = [
                    'error' => 'USER TOKEN NOT MATCHED.',
                ];
                return response()->json(['data' => $data], 200);
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }
        return response()->json(['data' => ['dealers' => $dealer_data]], 200);
    }

    //token information api after scan
    public function scan_point(Request $request)
    {
        $app_identifier = $request->app_identifier;
        $bar_code = $request->bar_code;
        $user_token = $request->header('USER-TOKEN');

        if ($user_token) {
            if ($app_identifier == 'com.ets.elitepaint.dealer') {
                $dealer = DealerUser::where('user_token', $user_token)
                    ->select(
                        'id',
                        'password',
                        'status',
                        'code',
                        'name',
                        'phone'
                    )->get()->last();

                if ($dealer) {
                    //Double Scan Check Start
                    $scanned_today = DB::table('scan_points')->whereDate('created_at',Carbon::now()->format('Y-m-d'))->where([
                        'dealer_id'=>$dealer['id'],
                        'bar_code'=>$bar_code
                    ])->exists();
                    if ($scanned_today){
                        $data = [
                            'error' => 'বারকোডটি ইতিমধ্যে ব্যবহৃত হয়েছে।',
                        ];
                        return response()->json(['data' => $data], 200);
                    }
                    //Double Scan Check End
                    //check the code in duplicate tabele or not
                    $barcode_duplicate = DB::table('bar_code_duplicates')->where('bar_code', $bar_code)->exists();
                    if ($barcode_duplicate) {
                        $barcode_duplicate_dealer = DB::table('bar_code_duplicates')->where('bar_code', $bar_code)->where('dealer_id', $dealer['id'])->whereDate('created_at', date('Y-m-d'))->exists();
                        if ($barcode_duplicate_dealer) {
                            $data = [
                                'error' => 'বারকোডটি ইতিমধ্যে ব্যবহৃত হয়েছে।',
                            ];
                            return response()->json(['data' => $data], 200);
                        }
                        $duplicate = DB::table('bar_code_duplicates')->where('bar_code', $bar_code)
                            ->select('id', 'dealer_id', 'painter_id', 'bar_code', 'identifier', 'no_of_duplicates', 'no_of_used', 'point')->get()->last();

                        if ($duplicate->no_of_duplicates > $duplicate->no_of_used) {
                            $new = DB::select(
                                "SELECT *
                                FROM bar_codes_18405 c
                                WHERE c.id = (SELECT min(id)
                                                FROM bar_codes_18405 b
                                                WHERE (b.bar_code ,b.POINT) IN (SELECT a.bar_code,MIN(a.POINT)
                                                                                        FROM bar_codes_18405 a
                                                                                        WHERE a.bar_code='$bar_code'
                                                                                        AND a.DUPLICATE=0)
                                                AND b.DUPLICATE=0)"
                            );

                            $barcode18405 = $new[0];
                            $barcode['id'] = $barcode18405->id;
                            $barcode['point'] = $barcode18405->point;
                            $product = Pack::where('id', $barcode18405->product_id) //product_id=pack_id
                            ->select('id', 'subgroup_id', 'pack_size')->get()->last();

                            $subgroup = SubGroup::where('id', $pack['subgroup_id'])
                                ->select('id', 'subgroup_name')->get()->last();
                        } else {
                            $data = [
                                'error' => 'বারকোডটি ইতিমধ্যে ব্যবহৃত হয়েছে।',
                            ];
                            return response()->json(['data' => $data], 200);
                        }
                    } else {
                        $barcode = BarCode::where('bar_code', $bar_code)
                            ->select('id', 'product_id', 'point')->get()->last();
                        $check = ScanPoint::where('bar_code_id', $barcode['id'])->select('id')->get()->last();
                        $product = Pack::where('id', $barcode['product_id'])
                            ->select('id', 'subgroup_id', 'pack_size')->get()->last();
                        //dd
                        $subgroup = SubGroup::where('id', $product['subgroup_id'])
                            ->select('id', 'subgroup_name')->get()->last();

                        if ($check) {
                            $data = [
                                'error' => 'বারকোডটি ইতিমধ্যে ব্যবহৃত হয়েছে।',
                            ];
                            return response()->json(['data' => $data], 200);
                        }
                    }


                    if ($barcode) {
                        $dashboard = [
                            'bar_code_id' => $barcode['id'],
                            'point' => $barcode['point'],
                            'product_name' => $subgroup['subgroup_name'],
                            'shade_name' => '',
                            'pack_size' => $product['pack_size'],
                        ];
                    } else {
                        $data = [
                            'error' => 'বারকোড মিলছে না।',
                        ];
                        return response()->json(['data' => $data], 200);
                    }
                } else {
                    $data = [
                        'error' => 'USER TOKEN NOT MATCHED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
            } elseif ($app_identifier == 'com.ets.elitepaint.painter') {
                $dealer = PainterUser::where('user_token', $user_token)
                    ->select(
                        'id',
                        'password',
                        'status',
                        'code',
                        'name',
                        'phone'
                    )->get()->last();
                //dd($dealer);
                if ($dealer) {
                    $scanned_today = DB::table('scan_points')->whereDate('created_at',Carbon::now()->format('Y-m-d'))->where([
                        'painter_id'=>$dealer['id'],
                        'bar_code'=>$bar_code
                    ])->exists();
                    if ($scanned_today){
                        $data = [
                            'error' => 'বারকোডটি ইতিমধ্যে ব্যবহৃত হয়েছে।',
                        ];
                        return response()->json(['data' => $data], 200);
                    }
                    $barcode_duplicate = DB::table('bar_code_duplicates')->where('bar_code', $bar_code)->exists();

                    if ($barcode_duplicate) {
                        $barcode_duplicate_dealer = DB::table('bar_code_duplicates')->where('bar_code', $bar_code)->where('painter_id', $dealer['id'])->exists();
                        if ($barcode_duplicate_dealer) {
                            $data = [
                                'error' => 'বারকোডটি ইতিমধ্যে ব্যবহৃত হয়েছে।',
                            ];
                            return response()->json(['data' => $data], 200);
                        }

                        $duplicate = DB::table('bar_code_duplicates')->where('bar_code', $bar_code)
                            ->select('id', 'dealer_id', 'painter_id', 'bar_code', 'identifier', 'no_of_duplicates', 'no_of_used', 'point')->get()->last();

                        if ($duplicate->no_of_duplicates > $duplicate->no_of_used) {
                            $barcodes = DB::table('bar_codes_18405')->where('bar_code', $bar_code)->where('duplicate', 0)
                                ->select('id', 'product_id', 'point')->get()->toArray();
                            $numbers = array_column($barcodes, 'point');
                            $min = min($numbers);
                            //dd($min);
                            $barcode18405 = DB::table('bar_codes_18405')->where('bar_code', $bar_code)->where('duplicate', 0)->where('point', $min)
                                ->select('id', 'product_id', 'point')->get()->last();
                            //dd($barcode);
                            $barcode['id'] = $barcode18405->id;
                            $barcode['point'] = $barcode18405->point;
                            $product = Pack::where('id', $barcode18405->product_id)
                                ->select('id', 'subgroup_id', 'pack_size')->get()->last();

                            $subgroup = SubGroup::where('id', $product['subgroup_id'])
                                ->select('id', 'subgroup_name')->get()->last();
                        } else {
                            $data = [
                                'error' => 'বারকোডটি ইতিমধ্যে ব্যবহৃত হয়েছে।',
                            ];
                            return response()->json(['data' => $data], 200);
                        }
                    } else {
                        $barcode = BarCode::where('bar_code', $bar_code)
                            ->select('id', 'product_id', 'point')->get()->last();
                        $check = ScanPoint::where('bar_code_id', $barcode['id'])->select('id')->get()->last();
                        $product = Pack::where('id', $barcode['product_id'])
                            ->select('id', 'subgroup_id', 'pack_size')->get()->last();
                        //dd
                        $subgroup = SubGroup::where('id', $product['subgroup_id'])
                            ->select('id', 'subgroup_name')->get()->last();

                        if ($check) {
                            $data = [
                                'error' => 'বারকোডটি ইতিমধ্যে ব্যবহৃত হয়েছে।',
                            ];
                            return response()->json(['data' => $data], 200);
                        }
                    }


                    if ($barcode) {
                        $dashboard = [
                            'bar_code_id' => $barcode['id'],
                            'point' => $barcode['point'],
                            'product_name' => $subgroup['subgroup_name'],
                            'shade_name' => '',
                            'pack_size' => $product['pack_size'],
                        ];
                    } else {
                        $data = [
                            'error' => 'বারকোড মিলছে না।',
                        ];
                        return response()->json(['data' => $data], 200);
                    }
                } else {
                    $data = [
                        'error' => 'USER TOKEN NOT MATCHED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }

        return response()->json(['data' => $dashboard], 200);
    }

    public function submit_point(Request $request)
    {

        $app_identifier = $request->app_identifier;
        $bar_code = $request->bar_code_id;
        $user_token = $request->header('USER-TOKEN');
        $date = $request->created_at;

        $current_date = NULL;
        $now = Carbon::now();

        if ($date != NULL) {
            $current_date = $date;
        } else {
            $current_date = $now->toDateTimeString();
        }


        if ($user_token) {
            if ($app_identifier == 'com.ets.elitepaint.dealer') {
                $dealer = DealerUser::where('user_token', $user_token)
                    ->select(
                        'id',
                        'password',
                        'status',
                        'code',
                        'name',
                        'phone'
                    )->get()->last();
                //dd($dealer);
                if ($dealer) {
                    $check = ScanPoint::where('bar_code_id', $bar_code)->select('id')->get()->last();
                    if ($check) {
                        $data = [
                            'error' => 'বারকোডটি ইতিমধ্যে ব্যবহৃত হয়েছে।',
                        ];
                        return response()->json(['data' => $data], 200);
                    }
                    $checks18405 = DB::table('bar_codes_18405')->where('id', $bar_code)->where('status', '!=', 2)
                        ->select('id', 'bar_code', 'point', 'product_id', 'bar_code')->get()->last();
                    $checksss18405 = DB::table('bar_codes_18405')->where('id', $bar_code)->where('status', null)
                        ->select('id', 'bar_code', 'point', 'product_id', 'bar_code')->get()->last();
                    if ($checks18405 || $checksss18405) {
                        $data = [
                            'message' => 'Token not ready to submit.',
                        ];
                        return response()->json(['data' => $data], 200);
                    }

                    $checks = BarCode::where('id', $bar_code)->where('status', '!=', 2)
                        ->select('id', 'bar_code', 'point', 'product_id', 'bar_code')->get()->last();
                    $checksss = BarCode::where('id', $bar_code)->where('status', null)
                        ->select('id', 'bar_code', 'point', 'product_id', 'bar_code')->get()->last();
                    if ($checks || $checksss) {
                        $data = [
                            'message' => 'Token not ready to submit.',
                        ];
                        return response()->json(['data' => $data], 200);
                    }


                    $barcode = DB::table('bar_codes_18405')->where('id', $bar_code)
                        ->select('id', 'bar_code', 'point', 'product_id', 'bar_code')->get()->last();
                    //dd($barcode['bar_code']);
                    if ($barcode) {
                        $duplicate = DB::table('bar_code_duplicates')->where('bar_code', $barcode->bar_code)
                            ->select('id', 'dealer_id', 'painter_id', 'bar_code', 'identifier', 'no_of_duplicates', 'no_of_used', 'point')->get()->last();
                        if ($duplicate) {
                            $no_of_used = (int)$duplicate->no_of_used + 1;
                            //dd($no_of_used);
                            DB::table('bar_code_duplicates')->where('bar_code', $barcode->bar_code)->update([
                                'dealer_id' => $dealer['id'],
                                'no_of_used' => $no_of_used,
                            ]);
                            DB::table('bar_codes_18405')->where('id', $bar_code)->update(['duplicate' => 1,]);
                        }
                    } else {
                        $barcode = DB::table('bar_codes')->where('id', $bar_code)
                            ->select('id', 'bar_code', 'point', 'product_id', 'bar_code')->get()->last();
                    }

                    try {
                        DB::table('scan_points')->insert([
                            'bar_code_id' => $bar_code,
                            'dealer_id' => $dealer['id'],
                            'status' => 'Scan',
                            'point' => $barcode->point,
                            'product_id' => $barcode->product_id,
                            'bar_code' => $barcode->bar_code,
                            'created_at' => $current_date
                        ]);

                    } catch (\Exception $ex) {
                        //   return $ex->getMessage();
                        if (strpos($ex->getMessage(), 'Integrity constraint violation') !== false) {
                            $data = [
                                'message' => 'পয়েন্ট জমা সফল হয়েছে!',
                            ];
                            return response()->json(['data' => $data], 200);
                        } else {
                            $data = [
                                'error' => 'USER TOKEN NOT MATCHED.',
                            ];
                            return response()->json(['data' => $data], 200);
                        }
                    }
                    \App\Classes\PointUpdate::dealer_total_earning_point($dealer['id'], $barcode->point);
                } else {
                    $data = [
                        'error' => 'USER TOKEN NOT MATCHED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
            } elseif ($app_identifier == 'com.ets.elitepaint.painter') {
                $dealer = PainterUser::where('user_token', $user_token)
                    ->select(
                        'id',
                        'password',
                        'status',
                        'code',
                        'name',
                        'phone'
                    )->get()->last();
                //dd($dealer);
                if ($dealer) {
                    $check = ScanPoint::where('bar_code_id', $bar_code)->select('id')->get()->last();
                    if ($check) {
                        $data = [
                            'error' => 'বারকোডটি ইতিমধ্যে ব্যবহৃত হয়েছে।',
                        ];
                        return response()->json(['data' => $data], 200);
                    }
                    $checks18405 = DB::table('bar_codes_18405')->where('id', $bar_code)->where('status', '!=', 2)
                        ->select('id', 'bar_code', 'point', 'product_id', 'bar_code')->get()->last();
                    $checksss18405 = DB::table('bar_codes_18405')->where('id', $bar_code)->where('status', null)
                        ->select('id', 'bar_code', 'point', 'product_id', 'bar_code')->get()->last();
                    if ($checks18405 || $checksss18405) {
                        $data = [
                            'message' => 'Token not ready to submit.',
                        ];
                        return response()->json(['data' => $data], 200);
                    }
                    $checks = BarCode::where('id', $bar_code)->where('status', '!=', 2)
                        ->select('id', 'bar_code', 'point', 'product_id', 'bar_code')->get()->last();
                    $checksss = BarCode::where('id', $bar_code)->where('status', null)
                        ->select('id', 'bar_code', 'point', 'product_id', 'bar_code')->get()->last();
                    if ($checks || $checksss) {
                        $data = [
                            'message' => 'Token not ready to submit.',
                        ];
                        return response()->json(['data' => $data], 200);
                    }
                    $barcode = DB::table('bar_codes_18405')->where('id', $bar_code)
                        ->select('id', 'bar_code', 'point', 'product_id', 'bar_code')->get()->last();
                    if ($barcode) {
                        $duplicate = DB::table('bar_code_duplicates')->where('bar_code', $barcode->bar_code)
                            ->select('id', 'dealer_id', 'painter_id', 'bar_code', 'identifier', 'no_of_duplicates', 'no_of_used', 'point')->get()->last();
                        if ($duplicate) {
                            $no_of_used = (int)$duplicate->no_of_used + 1;
                            DB::table('bar_code_duplicates')->where('bar_code', $barcode->bar_code)->update([
                                'painter_id' => $dealer['id'],
                                'no_of_used' => $no_of_used,
                            ]);
                            DB::table('bar_codes_18405')->where('id', $bar_code)->update(['duplicate' => 1,]);
                        }
                    } else {
                        $barcode = DB::table('bar_codes')->where('id', $bar_code)
                            ->select('id', 'bar_code', 'point', 'product_id', 'bar_code')->get()->last();
                    }
                    try {
                        DB::table('scan_points')->insert([
                            'bar_code_id' => $bar_code,
                            'painter_id' => $dealer['id'],
                            'status' => 'Scan',
                            'point' => $barcode->point,
                            'product_id' => $barcode->product_id,
                            'bar_code' => $barcode->bar_code,
                            'created_at' => $current_date
                        ]);
                    } catch (\Exception $ex) {
                        if (strpos($ex->getMessage(), 'unique constraint') !== false) {
                            $data = [
                                'message' => 'পয়েন্ট জমা সফল হয়েছে !',
                            ];
                            return response()->json(['data' => $data], 200);
                        } else {
                            $data = [
                                'error' => 'USER TOKEN NOT MATCHED.',
                            ];
                            return response()->json(['data' => $data], 200);
                        }
                    }

                    \App\Classes\PointUpdate::painter_level_update($dealer['id']);
                } else {
                    $data = [
                        'error' => 'USER TOKEN NOT MATCHED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }

        $data = ['message' => 'পয়েন্ট জমা সফল হয়েছে', 'type' => "message"];
        return response()->json(['data' => $data], 200);
    }

    public function redeem_point(Request $request)
    {

        $app_identifier = $request->app_identifier;
        $redeem_point = $request->redeem_point;
        $user_token = $request->header('USER-TOKEN');
        $date = $request->created_at;

        $current_date = NULL;
        $now = Carbon::now();

        if ($date != NULL) {
            $current_date = $date;
        } else {
            $current_date = $now->toDateTimeString();
        }

        $transaction_code = '';

        if ($user_token) {
            if ($app_identifier == 'com.ets.elitepaint.dealer') {
                $dealer = DealerUser::where('user_token', $user_token)
                    ->select(
                        'id',
                        'password',
                        'status',
                        'code',
                        'name',
                        'nid_picture',
                        'nid',
                        'phone',
                        'depo'
                    )->get()->last();
                //dd($dealer);
                if ($dealer) {
                    if ($redeem_point < 100) {
                        $data = [
                            'error' => 'ন্যূনতম ১০০ পয়েন্ট উত্তোলনযোগ্য।',
                        ];
                        return response()->json(['data' => $data], 200);
                    }

                    if ($dealer['depo']) {
                        $depo = Depo::where('depo', $dealer['depo'])
                            ->select('id', 'depo', 'depo_code')->get()->last();
                    } else {
                        $data = [
                            'error' => 'PLEASE INPUT YOUR DEPO FIRST.',
                        ];
                        return response()->json(['data' => $data], 200);
                    }

                    $totalactive_status = RedeemPoint::where('dealer_id', '!=', NULL)->where('transaction_code', 'LIKE', '%' . $depo['depo_code'] . '%')->select('transaction_code')->distinct('transaction_code')->get()->count();
                    $totalactive_status++;
                    //dd($totalactive_status);
                    $year = $now->format('Y');
                    //dd($year);
                    $invitation_code = 'DRP-' . $depo['depo_code'] . '-' . $totalactive_status . '/' . $year;

                    if ($dealer['nid_picture'] && $dealer['nid']) {
                        $scanpoint_year = ScanPoint::where('dealer_id', $dealer['id'])
                            ->select('id', 'bar_code_id')->get()->toArray();

                        $redeems = RedeemPoint::where('dealer_id', $dealer['id'])
                            ->select('id', 'redeem_point')->get()->toArray();
                        $volumes = PlaceOrder::where('dealer_id', $dealer['id'])->where('status', '=', 2)
                            ->select('id', 'dealer_point')->get()->toArray();
                        $all_total = 0;
                        $total_redeem_point = 0;
                        $total_volume_point = 0;
                        $total_scan_point = 0;
                        //dd($dealer_list);
                        foreach ($volumes as $volume) {

                            $total_volume_point += $volume['dealer_point'];
                        }

                        //dd($dealer_list);
                        foreach ($redeems as $redeem) {

                            $total_redeem_point += $redeem['redeem_point'];
                        }
                        //dd($total_redeem_point + $redeem_point);
                        foreach ($scanpoint_year as $scan_point) {
                            $barcode = BarCode::where('id', $scan_point['bar_code_id'])
                                ->select('id', 'product_id', 'point')->get()->last();

                            $total_scan_point += $barcode['point'];
                        }
                        $all_total = $total_volume_point + $total_scan_point;

                        DB::table('redeem')->insert([
                            'redeem_point' => $redeem_point,
                            'transaction_code' => $invitation_code,
                            'dealer_id' => $dealer['id'],
                            'status' => 2,
                            'created_at' => $current_date
                        ]);
                    } else {
                        $data = [
                            'error' => 'অনুগ্রহপূর্বক প্রোফাইলে জাতীয় পরিচয়পত্রের তথ্য প্রদান করুন।',
                        ];
                        return response()->json(['data' => $data], 200);
                    }
                } else {
                    $data = [
                        'error' => 'USER TOKEN NOT MATCHED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
            } elseif ($app_identifier == 'com.ets.elitepaint.painter') {
                $dealer = PainterUser::where('user_token', $user_token)
                    ->select(
                        'id',
                        'password',
                        'status',
                        'code',
                        'name',
                        'nid_picture',
                        'nid',
                        'phone',
                        'depo'
                    )->get()->last();
                //dd($dealer);
                if ($dealer) {
                    if ($redeem_point < 100) {
                        $data = [
                            'error' => 'ন্যূনতম ১০০ পয়েন্ট উত্তোলনযোগ্য।',
                        ];
                        return response()->json(['data' => $data], 200);
                    }

                    if ($dealer['depo']) {
                        $depo = Depo::where('depo', $dealer['depo'])
                            ->select('id', 'depo', 'depo_code')->get()->last();
                    } else {
                        $data = [
                            'error' => 'PLEASE INPUT YOUR DEPO FIRST.',
                        ];
                        return response()->json(['data' => $data], 200);
                    }
                    $totalactive_status = RedeemPoint::where('painter_id', '!=', NULL)->where('transaction_code', 'LIKE', '%' . $depo['depo_code'] . '%')->select('transaction_code')->distinct('transaction_code')->get()->count();
                    $totalactive_status++;
                    //dd($totalactive_status);
                    $year = $now->format('Y');
                    //dd($year);
                    $invitation_code = 'PRP-' . $depo['depo_code'] . '-' . $totalactive_status . '/' . $year;

                    if ($dealer['nid_picture'] && $dealer['nid']) {
                        $scanpoint_year = ScanPoint::where('painter_id', $dealer['id'])
                            ->select('id', 'bar_code_id')->get()->toArray();
                        $redeems = RedeemPoint::where('painter_id', $dealer['id'])
                            ->select('id', 'redeem_point')->get()->toArray();
                        $volumes = VolumeTranfer::where('painter_id', $dealer['id'])->where('status', '!=', 2)
                            ->select('id', 'painter_point')->get()->toArray();
                        $all_total = 0;
                        $total_redeem_point = 0;
                        $total_volume_point = 0;
                        $total_scan_point = 0;
                        //dd($dealer_list);
                        foreach ($volumes as $volume) {

                            $total_volume_point += $volume['painter_point'];
                        }
                        foreach ($redeems as $redeem) {

                            $total_redeem_point += $redeem['redeem_point'];
                        }

                        foreach ($scanpoint_year as $scan_point) {
                            $barcode = BarCode::where('id', $scan_point['bar_code_id'])
                                ->select('id', 'product_id', 'point')->get()->last();

                            $total_scan_point += $barcode['point'];
                        }
                        $all_total = $total_volume_point + $total_scan_point;

                        DB::table('redeem')->insert([
                            'redeem_point' => $redeem_point,
                            'transaction_code' => $invitation_code,
                            'painter_id' => $dealer['id'],
                            'status' => 2,
                            'created_at' => $current_date
                        ]);
                    } else {
                        $data = [
                            'error' => 'অনুগ্রহপূর্বক প্রোফাইলে জাতীয় পরিচয়পত্রের তথ্য প্রদান করুন।',
                        ];
                        return response()->json(['data' => $data], 200);
                    }
                } else {
                    $data = [
                        'error' => 'USER TOKEN NOT MATCHED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }

        $data = ['message' => 'পয়েন্ট জমা সফল হয়েছে', 'type' => "message"];
        return response()->json(['data' => $data], 200);
    }

    public function volume_transfer(Request $request)
    {
        if (config('custom.VOLUME_TRANSFER_OFF') && ($request->painter_id != 13 && $request->painter_id != 671)) {
            $data = ['message' => 'এই ফিচারটি আপডেটের কাজ চলছে অনুগ্রহ করে অপেক্ষা করুন', 'type' => "message"];
            return response()->json(['data' => $data], 200);
        }

        $user_token = $request->header('USER-TOKEN');
        $painter_id = (int)$request->painter_id;
        $product_ids = $request->product_ids;
        $quantities = $request->quantities;
        $date = $request->created_at;
        $accepted_by = 'Volume Transfer';
        $accepted_at = Carbon::now();
        $status = 1;


        if ($user_token) {

            $dealer = DealerUser::where('user_token', $user_token)->where(['status' => 1, 'disable' => 1, 'soft_delete' => 1])->first();
            //dd($dealer);
            if ($dealer) {
                $depo_code = DB::table('depos')->where('id', $dealer->depo_id)->first()->depo_code ?? 'EP';
                //dd($current_date);
                $product_list = explode(",", $product_ids);
                $quatity_list = explode(",", $quantities);


                $total_quantity = 0;
                foreach ($quatity_list as $quantity) {
                    $total_quantity += $quantity;
                }

                //Volume Transfer Condition Start
                $except_dealer_codes = array("NG-0263", "NG-1749", "KUL-0033", "KUL-1218", "SYL-0016", "DK-1592", "RJ-2052", "RJ-2046", "RJ-2184", "SY-0364", "CT-0118", "RJ-2184");

                if ((date("Y-m-d") <= '2022-12-31') && in_array($dealer->code, $except_dealer_codes)) {

                    $painter_limit = 3500;
                    $dealer_limit = 7000;
                } else {
                    $painter_limit = config('custom.PAINTER_TRANSFER');
                    $dealer_limit = config('custom.DEALER_TRANSFER');
                }

                $dealer_limit_times = config('custom.TRANSFER_TIMES');
                $painter_limit_times = config('custom.TRANSFER_TIMES');

//                $distinctCount = VolumeTransfer::where('dealer_id', 5592)
//                    ->where('soft_delete', 1)
//                    ->whereDate('created_at', Carbon::today())
//                    ->selectRaw('COUNT(DISTINCT NULLIF(CODE2,created_at))')
//                    ->value('COUNT(DISTINCT NULLIF(CODE2,created_at))');

                $today_dealer_transfer = DB::table('volume_tranfers')->where('dealer_id', $dealer->id)->where('soft_delete', '=', 1)->whereDate('created_at', '=', date('Y-m-d'))->sum('quantity');
                $today_dealer_transfer_time = DB::table('volume_tranfers')->where('dealer_id', $dealer->id)->where('soft_delete', '=', 1)->whereDate('created_at', '=', date('Y-m-d'))->selectRaw('COUNT(DISTINCT IFNULL(code2,created_at))')
                    ->value('COUNT(DISTINCT IFNULL(code2,created_at))');
                $today_painter_transfer = DB::table('volume_tranfers')->where('painter_id', $painter_id)->whereDate('created_at', '=', date('Y-m-d'))->sum('quantity');
                $today_painter_transfer_time = DB::table('volume_tranfers')->where('painter_id', $painter_id)->where('soft_delete', '=', 1)->whereDate('created_at', '=', Carbon::today())->selectRaw('COUNT(DISTINCT IFNULL(code2,created_at))')
                    ->value('COUNT(DISTINCT IFNULL(code2,created_at))');

                $today_dealer_total = $today_dealer_transfer + $total_quantity;
                $today_painter_total = $today_painter_transfer + $total_quantity;
                $today_total_transfer_time = $today_dealer_transfer_time + 1;
                $today_total_transfer_time_painter = $today_painter_transfer_time + 1;

                if ($today_dealer_total > $dealer_limit) {
                    $data = ['message' => 'আপনার নির্বাচিত ডিলারের ' . $dealer_limit . ' লিটার সীমা ইতিমধ্যে অতিক্রম করেছে ', 'type' => "message"];
                    return response()->json(['data' => $data], 200);
                }
                if ($today_total_transfer_time > $dealer_limit_times) {
                    $data = ['message' => 'আপনার নির্বাচিত ডিলারের ' . $dealer_limit_times . ' বার লেনদেন সীমা ইতিমধ্যে অতিক্রম করেছে.', 'type' => "message"];
                    return response()->json(['data' => $data], 200);
                }
                if ($today_painter_total > $painter_limit) {
                    $data = ['message' => ' আপনি আজকে ' . $painter_limit . ' লিটারের বেশি নিতে পারবেন না.', 'type' => "message"];
                    return response()->json(['data' => $data], 200);
                }
                if ($today_total_transfer_time_painter > $painter_limit_times) {
                    $data = ['message' => ' আপনি আজকে  ' . $painter_limit_times . ' বারের বেশি লেনদেন করতে পারবেন না.', 'type' => "message"];
                    return response()->json(['data' => $data], 200);
                }
                //Volume Transfer Condition End

                $invitation_code = generateDPUCode($depo_code);

                $code2 = $this->generateRandomString();
                $total_dealer_point = 0;
                $total_painter_point = 0;
                foreach ($product_list as $key => $product_id) {

                    $volume_info = volume_transfer_point($dealer['id'], $painter_id, $product_id, $quatity_list[$key]);
                    if (isset($volume_info->message)) {
                        $data = [
                            'error' => $volume_info->message,
                        ];
                        return response()->json(['data' => $data], 200);
                    }

                    $dealer_point = $volume_info->dealer_point;
                    $painter_point = $volume_info->painter_point;
                    $offer = Point::where('type', 'OFFER')->where('product_id', $product_id)->where('end_date', '>=', date('Y-m-d'))
                        ->select('id', 'point')->get()->last();
                    if ($offer) {
                        $dealer_point += $offer['point'] * $quatity_list[$key];
                        $painter_point += $offer['point'] * $quatity_list[$key];
                    }

                    $subgroup = \Illuminate\Support\Facades\DB::table('subgroups')->find($product_id);
                    if (!$subgroup->basegroup_id) {
                        $basegroup = \Illuminate\Support\Facades\DB::table('basegroups')->insertGetId([
                            'basegroup_code' => $subgroup->subgroup_code,
                            'basegroup_name' => $subgroup->subgroup_name,
                            'delivery_percentage' => 0,
                            'created_at' => Carbon::now()
                        ]);
                        \Illuminate\Support\Facades\DB::table('subgroups')->where('id', $product_id)->update(['basegroup_id' => $basegroup]);
                    }

                    DB::table('volume_tranfers')->insert([
                        'dealer_id' => $dealer['id'],
                        'painter_id' => $painter_id,
                        'basegroup_id' => $subgroup->basegroup_id,
                        'product_id' => $product_id,
                        'quantity' => $quatity_list[$key],
                        'code' => $invitation_code,
                        'code2' => $code2,
                        'dealer_member_type_id' => $volume_info->dealer_member_type_id,
                        'dealer_point' => $dealer_point,
                        'painter_member_type_id' => $volume_info->painter_member_type_id,
                        'painter_point' => $painter_point,
                        'accepted_by' => $accepted_by,
                        'accepted_at' => $accepted_at,
                        'status' => $status,
                        'created_at' => $accepted_at,
                    ]);
                    $total_dealer_point += $dealer_point;
                    $total_painter_point += $painter_point;
                }

                painter_level_update($painter_id);

                $painter = PainterUser::where('id', $painter_id)->first();

                $message = $painter_point . ' ক্রয় পয়েন্ট যুক্ত হয়েছে , ক্রয় নং ' . $invitation_code . '. ' . $dealer->name;

                PushController::push_send_to_parent($painter->push_token, 'VOLUME TRANSFER', $message);
            } else {
                $data = [
                    'error' => 'USER TOKEN NOT MATCHED.',
                ];
                return response()->json(['data' => $data], 200);
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }
        // $dashboard = [
        //       'transaction_id' => $invitation_code,
        //       'dealer_point' => $all_total

        //         ];
        $data = ['message' => 'TRANSACTION ID : ' . $invitation_code, 'type' => "message"];
        return response()->json(['data' => $data], 200);
    }

    public function get_volume_transfer_details(Request $request)
    {

        $code = $request->code;
        $user_token = $request->header('USER-TOKEN');

        // $dealer = DealerUser::where('dealer_code',$dealer_code)->where('user_token',$user_token)
        //                       ->select('id','password','status','app_identifier')->get()->last();
        //dd($user_token);

        if ($user_token) {

            $dealer = DealerUser::where('user_token', $user_token)
                ->select(
                    'id',
                    'password',
                    'status',
                    'code',
                    'name',
                    'phone'
                )->get()->last();
            //dd($dealer);
            if ($dealer) {
                $orders = VolumeTranfer::where('dealer_id', $dealer['id'])->where('code', $code)->orderBy('created_at', 'desc')->select(
                    'id',
                    'code',
                    'dealer_id',
                    'painter_id',
                    'product_id',
                    'quantity',
                    'dealer_point',
                    'painter_point',
                    'created_at'
                )->get()->toArray();

                $product_data = [];

                foreach ($orders as $order) {
                    $product = Product::where('id', $order['product_id'])->select('id', 'pack_size_id', 'shade_code', 'product_name', 'shade_name', 'price')->get()->first();

                    $pack_size = Pack::where('id', $product['pack_size_id'])->select('id', 'subgroup_id', 'pack_name', 'pack_size', 'size_code', 'uom')->get()->first();
                    $subgroup = SubGroup::where('id', $order['product_id'])->select('id', 'subgroup_code', 'subgroup_name')->get()->first();


                    $data = [

                        'product_id' => $subgroup['id'],
                        'product_name' => $subgroup['subgroup_name'],
                        'quantity' => round($order['quantity'], 2),
                        'dealer_point' => round($order['dealer_point'], 2)
                    ];
                    $product_data[] = $data;
                }
                $final_data = [
                    'products' => $product_data
                ];
            } else {
                $data = [
                    'error' => 'USER TOKEN NOT MATCHED.',
                ];
                return response()->json(['data' => $data], 200);
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }

        return response()->json(['data' => $final_data], 200);
    }

    public function place_order(Request $request)
    {

        $user_token = $request->header('USER-TOKEN');
        $product_ids = $request->product_ids;
        $quantities = $request->quantities;
        $date = $request->created_at;

        $current_date = NULL;
        $now = Carbon::now();

        if ($date != NULL) {
            $current_date = $date;
        } else {
            $current_date = $now->toDateTimeString();
        }

        if ($user_token) {

            $dealer = DealerUser::where('user_token', $user_token)
                ->select(
                    'id',
                    'password',
                    'status',
                    'code',
                    'name',
                    'phone',
                    'depo',
                    'member_type'
                )->get()->last();
            //dd($dealer);
            if ($dealer) {
                $product_list = explode(",", $product_ids);
                $quatity_list = explode(",", $quantities);
                //dd($quatity_list);
                if ($dealer['depo']) {
                    $depo = Depo::where('depo', $dealer['depo'])
                        ->select('id', 'depo', 'depo_code')->get()->last();
                } else {
                    $data = [
                        'error' => 'PLEASE INPUT YOUR DEPO FIRST.',
                    ];
                    return response()->json(['data' => $data], 200);
                }

                $totalactive_status = PlaceOrder::where('code', 'LIKE', '%' . $depo['depo_code'] . '%')->select('code')->distinct('code')->get()->count();
                $totalactive_status++;
                //dd($totalactive_status);
                $year = $now->format('Y');
                //dd($year);
                $invitation_code = 'IND-' . $depo['depo_code'] . '-' . $totalactive_status . '/' . $year;

                $seed = str_split('ABCDEFGHIJKLMNOPQRSTUVWXYZ'
                    . '0123456789'); // and any other characters
                shuffle($seed);
                $code2 = '';
                foreach (array_rand($seed, 6) as $k) $code2 .= $seed[$k];

                $index = 0;
                $all_total = 0;
                foreach ($product_list as $product_id) {
                    $a = Product::where('id', $product_id)
                        ->select('id', 'pack_size_id')->get()->last();

                    $b = Pack::where('id', $a['pack_size_id'])
                        ->select('id', 'subgroup_id')->get()->last();

                    $p = SubGroup::where('id', $b['subgroup_id'])
                        ->select('id', 'subgroup_name')->get()->last();
                    $scanpoint_year = ScanPoint::where('dealer_id', $dealer['id'])
                        ->select('id', 'bar_code_id')->get()->toArray();

                    $volumes = VolumeTranfer::where('dealer_id', $dealer['id'])->where('status', '!=', 2)
                        ->select('id', 'dealer_point')->get()->toArray();
                    $all_total = 0;
                    $total_redeem_point = 0;
                    $total_volume_point = 0;
                    $total_scan_point = 0;
                    //dd($dealer_list);
                    foreach ($volumes as $volume) {

                        $total_volume_point += $volume['dealer_point'];
                    }
                    //dd($total_redeem_point + $redeem_point);
                    foreach ($scanpoint_year as $scan_point) {
                        $barcode = BarCode::where('id', $scan_point['bar_code_id'])
                            ->select('id', 'product_id', 'point')->get()->last();

                        $total_scan_point += $barcode['point'];
                    }
                    $all_total = $total_volume_point + $total_scan_point;
                    //dd($all_total);
                    $dealer_members = EliteMember::where('category', $dealer['member_type'])->get()->toArray();
                    //$members = EliteMember::where('category','=','PNT')->get()->toArray();
                    //dd($dealer_members);
                    if (!$dealer_members) {
                        $dealer_point = 0 * $quatity_list[$index];
                    } else {
                        foreach ($dealer_members as $dealer_member) {
                            //dd($member['member_type']);
                            if ($all_total < $dealer_member['point']) {
                                //dd(count($dealer_member));
                                $id = $dealer_member['id'];
                                break;
                            }
                        }
                        if ($id) {
                            $a = Product::where('id', $product_id)
                                ->select('id', 'pack_size_id')->get()->last();

                            $b = Pack::where('id', $a['pack_size_id'])
                                ->select('id', 'subgroup_id')->get()->last();

                            $p = SubGroup::where('id', $b['subgroup_id'])
                                ->select('id', 'subgroup_name')->get()->last();
                            $ponts = Point::where('elite_member_id', $id)->where('product_id', $p['id'])->where('end_date', '>=', $now->toDateString())
                                ->select('id', 'point')->get()->last();
                            //dd($ponts);
                            if ($ponts) {
                                $dealer_point = $ponts['point'] * $quatity_list[$index];
                            } else {
                                $dealer_point = 0 * $quatity_list[$index];
                            }
                        } else {
                            $dealer_point = 0 * $quatity_list[$index];
                        }
                    }

                    $offer = Point::where('type', 'OFFER')->where('product_id', $p['id'])->where('end_date', '>=', $now->toDateString())
                        ->select('id', 'point')->get()->last();
                    if ($offer) {
                        $dealer_point += $offer['point'] * $quatity_list[$index];
                    }
                    DB::table('place_orders')->insert([
                        'dealer_id' => $dealer['id'],
                        'product_id' => $product_id,
                        'quantity' => $quatity_list[$index++],
                        'code' => $invitation_code,
                        'code2' => $code2,
                        'dealer_point' => $dealer_point,
                        'status' => 1,
                        'created_at' => $current_date
                    ]);
                }
            } else {
                $data = [
                    'error' => 'USER TOKEN NOT MATCHED.',
                ];
                return response()->json(['data' => $data], 200);
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }
        $data = ['message' => 'আপনার অর্ডারটি সফলভাবে জমা হয়েছেে', 'type' => "message"];
        return response()->json(['data' => $data], 200);
    }

    public function get_place_order_history(Request $request)
    {

        $app_identifier = $request->app_identifier;
        $user_token = $request->header('USER-TOKEN');

        $now = Carbon::now();
        $month = $now->year . '-' . $now->month;
        $start = Carbon::parse($month)->startOfMonth();
        $end = Carbon::parse($month)->today();

        $all_dates = [];

        // get all dates
        while ($start->lte($end)) {
            $all_dates[] = $start->copy();
            $start->addDay();
        }

        $all_dates = array_reverse($all_dates);


        // $dealer = DealerUser::where('dealer_code',$dealer_code)->where('user_token',$user_token)
        //                       ->select('id','password','status','app_identifier')->get()->last();
        //dd($user_token);

        if ($user_token) {
            if ($app_identifier == 'com.ets.elitepaint.dealer') {
                $dealer = DealerUser::where('user_token', $user_token)
                    ->select(
                        'id',
                        'password',
                        'status',
                        'code',
                        'name',
                        'phone'
                    )->get()->last();
                //dd($dealer);
                if ($dealer) {
                    $allData = [];
                    foreach ($all_dates as $date) {
                        $actual_date = $date->todateString();
                        $complete_total = PlaceOrder::where('dealer_id', $dealer['id'])
                            ->where('created_at', 'LIKE', '%' . $actual_date . '%')->distinct('code')->count('code');
                        if ($complete_total > 0) {
                            $data = [
                                'date' => $date->todateString(),
                                'day' => $date->format('l'),
                                'enable' => ($complete_total == null) ? 0 : 1,
                                'orders' => $complete_total
                            ];
                            $allData[] = $data;
                        }
                    }
                    if (empty($allData)) {
                        //dd('s');
                        $datas = [
                            'message' => 'NO DATE AVAILABLE',
                        ];
                        return response()->json(['data' => $datas], 200);
                    }
                } else {
                    $data = [
                        'error' => 'USER TOKEN NOT MATCHED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
            } elseif ($app_identifier == 'com.ets.elitepaint.painter') {
                $dealer = PainterUser::where('user_token', $user_token)
                    ->select(
                        'id',
                        'password',
                        'status',
                        'code',
                        'name',
                        'phone'
                    )->get()->last();
                //dd($dealer);
                if ($dealer) {
                    foreach ($all_dates as $date) {
                        $actual_date = $date->todateString();
                        $scanpoint_year = ScanPoint::where('painter_id', $dealer['id'])
                            ->where('created_at', 'LIKE', '%' . $actual_date . '%')
                            ->select('id', 'bar_code_id')->get()->toArray();
                        $no_of_scan_month = count($scanpoint_year);
                        $all_total_year = 0;
                        //dd($dealer_list);
                        foreach ($scanpoint_year as $scanpoint) {
                            $barcode = BarCode::where('id', $scanpoint['bar_code_id'])
                                ->select('id', 'product_id', 'point')->get()->last();

                            $all_total_year += $barcode['point'];
                        }
                        $data = [
                            'date' => $date->todateString(),
                            'day' => $date->format('l'),
                            'enable' => ($scanpoint_year == null) ? 0 : 1,
                            'points' => $all_total_year,
                            'no_of_scan' => $no_of_scan_month
                        ];
                        $allData[] = $data;
                    }
                } else {
                    $data = [
                        'error' => 'USER TOKEN NOT MATCHED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }

        return response()->json(['data' => $allData], 200);
    }

    public function get_place_order_history_by_date_range(Request $request)
    {

        $app_identifier = $request->app_identifier;
        $user_token = $request->header('USER-TOKEN');
        $starts = $request->start_date;
        $ends = $request->end_date;
        $now = Carbon::now();
        $month = $now->year . '-' . $now->month;
        $start = Carbon::parse($starts)->toDateString();
        $end = Carbon::parse($ends)->toDateString();

        $start_date = $start . ' 00:00:00';
        $end_date = $end . ' 23:59:59';

        if ($user_token) {

            $dealer = DealerUser::where('user_token', $user_token)
                ->select(
                    'id',
                    'password',
                    'status',
                    'code',
                    'name',
                    'phone'
                )->get()->last();
            //dd($dealer);
            if ($dealer) {
                $feedback_list = PlaceOrder::where('dealer_id', $dealer['id'])->whereBetween('created_at', [$start_date, $end_date])->orderBy('created_at', 'desc')->select(
                    'code',
                    'created_at',
                    'status'
                )->distinct('code')->get()->toArray();

                //dd($feedback_list);

                //dd($feedback_l);
                if (!$feedback_list) {
                    $data = [
                        'message' => 'NO PLACE ORDER ADDED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
                $allData = [];

                foreach ($feedback_list as $feedback) {

                    //dd($thana);
                    $entry_date = Carbon::parse($feedback['created_at'])->toDateString();
                    $feedback_l = PlaceOrder::where('code', $feedback['code'])
                        ->count('code');

                    $feedback_2 = PlaceOrder::where('code', $feedback['code'])
                        ->sum('dealer_point');
                    $dashboard = [
                        'dealer_id' => $dealer['id'],
                        'dealer_code' => $dealer['code'],
                        'dealer_name' => $dealer['name'],
                        'product' => $feedback_l . ' ' . 'Products',
                        'status' => $feedback['status'] == 1 ? 'PENDING' : 'ACCEPTED',
                        'code' => $feedback['code'],
                        'dealer_point' => $feedback_2,
                        'place_order_date' => $entry_date,
                    ];

                    $allData[] = $dashboard;
                }
            } else {
                $data = [
                    'error' => 'USER TOKEN NOT MATCHED.',
                ];
                return response()->json(['data' => $data], 200);
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }

        return response()->json(['data' => $allData], 200);
    }

    public function get_volume_transfer_history(Request $request)
    {
        $app_identifier = $request->app_identifier;
        $user_token = $request->header('USER-TOKEN');

        $now = Carbon::now();
        $month = $now->year . '-' . $now->month;
        $start = Carbon::parse($month)->startOfMonth();
        $end = Carbon::parse($month)->today();

        $all_dates = [];

        // get all dates
        while ($start->lte($end)) {
            $all_dates[] = $start->copy();
            $start->addDay();
        }

        $all_dates = array_reverse($all_dates);


        // $dealer = DealerUser::where('dealer_code',$dealer_code)->where('user_token',$user_token)
        //                       ->select('id','password','status','app_identifier')->get()->last();
        //dd($user_token);

        if ($user_token) {
            if ($app_identifier == 'com.ets.elitepaint.dealer') {
                $dealer = DealerUser::where('user_token', $user_token)
                    ->select(
                        'id',
                        'password',
                        'status',
                        'code',
                        'name',
                        'phone'
                    )->get()->last();
                //dd($dealer);
                if ($dealer) {
                    $allData = [];
                    foreach ($all_dates as $date) {
                        $actual_date = $date->todateString();
                        $complete_total = VolumeTranfer::where('dealer_id', $dealer['id'])->where('status', '!=', 2)
                            ->where('created_at', 'LIKE', '%' . $actual_date . '%')->where('soft_delete', 1)->distinct('code')->count('code');
                        $total_codes = VolumeTranfer::where('dealer_id', $dealer['id'])
                            ->where('created_at', 'LIKE', '%' . $actual_date . '%')->where('soft_delete', 1)->distinct('code')->select('id', 'code', 'dealer_point')->get();

                        //dd($total_codes);
                        $total_codess = VolumeTranfer::where('dealer_id', $dealer['id'])->where('status', '!=', 2)
                            ->where('created_at', 'LIKE', '%' . $actual_date . '%')->where('soft_delete', 1)->select('id', 'code', 'dealer_point')->get()->toArray();
                        $total_ltr = 0;
                        $total_point = 0;
                        foreach ($total_codess as $total_codeaa) {
                            //dd($total_code['id']);

                            $total_point += $total_codeaa['dealer_point'];
                        }
                        foreach ($total_codes as $total_code) {
                            //dd($total_code['id']);
                            $volum = VolumeTranfer::where('id', $total_code['id'])
                                ->select('id', 'product_id', 'quantity')->get()->last();
                            $product = Product::where('id', $volum['product_id'])
                                ->select('id', 'pack_size_id')->get()->last();
                            $pack_size = Pack::where('id', $product['pack_size_id'])
                                ->select('id', 'pack_size')->get()->last();
                            $totals = $volum['quantity'] * $pack_size['pack_size'];
                            $total_ltr += $volum['quantity'];
                            //$total_point += $total_code['dealer_point'];

                        }
                        //dd($total_point);
                        if ($complete_total > 0) {
                            $data = [
                                'date' => $date->todateString(),
                                'day' => $date->format('l'),
                                'enable' => ($complete_total == null) ? 0 : 1,
                                'volume_transfer' => $complete_total,
                                'ltr' => $total_ltr,
                                'points' => $total_point,
                            ];
                            $allData[] = $data;
                        }
                    }
                    if (empty($allData)) {
                        //dd('s');
                        $datas = [
                            'message' => 'NO DATE AVAILABLE',
                        ];
                        return response()->json(['data' => $datas], 200);
                    }
                } else {
                    $data = [
                        'error' => 'USER TOKEN NOT MATCHED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
            } elseif ($app_identifier == 'com.ets.elitepaint.painter') {
                $dealer = PainterUser::where('user_token', $user_token)
                    ->select(
                        'id',
                        'password',
                        'status',
                        'code',
                        'name',
                        'phone'
                    )->get()->last();
                //dd($dealer);
                if ($dealer) {
                    foreach ($all_dates as $date) {
                        $actual_date = $date->todateString();
                        $scanpoint_year = ScanPoint::where('painter_id', $dealer['id'])
                            ->where('created_at', 'LIKE', '%' . $actual_date . '%')
                            ->select('id', 'bar_code_id')->get()->toArray();
                        $no_of_scan_month = count($scanpoint_year);
                        $all_total_year = 0;
                        //dd($dealer_list);
                        foreach ($scanpoint_year as $scanpoint) {
                            $barcode = BarCode::where('id', $scanpoint['bar_code_id'])
                                ->select('id', 'product_id', 'point')->get()->last();

                            $all_total_year += $barcode['point'];
                        }
                        $data = [
                            'date' => $date->todateString(),
                            'day' => $date->format('l'),
                            'enable' => ($scanpoint_year == null) ? 0 : 1,
                            'points' => $all_total_year,
                            'no_of_scan' => $no_of_scan_month
                        ];
                        $allData[] = $data;
                    }
                } else {
                    $data = [
                        'error' => 'USER TOKEN NOT MATCHED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }

        return response()->json(['data' => $allData], 200);
    }

    public function get_volume_transfer_history_by_date_range(Request $request)
    {

        $app_identifier = $request->app_identifier;
        $user_token = $request->header('USER-TOKEN');
        $starts = $request->start_date;
        $ends = $request->end_date;
        $now = Carbon::now();
        $month = $now->year . '-' . $now->month;
        $start = Carbon::parse($starts)->toDateString();
        $end = Carbon::parse($ends)->toDateString();

        $start_date = $start . ' 00:00:00';
        $end_date = $end . ' 23:59:59';
        if ($user_token) {

            $dealer = DealerUser::where('user_token', $user_token)
                ->select(
                    'id',
                    'password',
                    'status',
                    'code',
                    'name',
                    'phone'
                )->get()->last();
            //dd($dealer);
            if ($dealer) {
                $feedback_list = VolumeTranfer::where('dealer_id', $dealer['id'])->where('status', '!=', 2)->whereBetween('created_at', [$start, $end_date])->orderBy('created_at', 'desc')->select(
                    'code',
                    'painter_id',
                    'dealer_id',
                    'created_at'
                )->distinct('code')->get()->toArray();

                //dd($feedback_list);

                //dd($feedback_l);
                if (!$feedback_list) {
                    $data = [
                        'message' => 'NO volume ADDED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
                $allData = [];

                foreach ($feedback_list as $feedback) {
                    $entry_date = Carbon::parse($feedback['created_at'])->toDateString();
                    $deal = PainterUser::where('id', $feedback['painter_id'])->select(
                        'id',
                        'password',
                        'status',
                        'code',
                        'name',
                        'phone'
                    )->get()->last();
                    //dd($thana);
                    $feedback_l = VolumeTranfer::where('code', $feedback['code'])
                        ->count('code');
                    $points = VolumeTranfer::where('code', $feedback['code'])
                        ->sum('dealer_point');
                    //dd($points);

                    $dashboard = [
                        'painter_id' => $deal['id'],
                        'painter_code' => $deal['code'],
                        'painter_name' => $deal['name'],
                        'painter_phone' => $deal['phone'],
                        'product' => $feedback_l . ' ' . 'Products',
                        'code' => $feedback['code'],
                        'volume_date' => $entry_date,
                        'points' => round($points, 2) . ' ' . 'PTS',
                    ];

                    $allData[] = $dashboard;
                }
            } else {
                $data = [
                    'error' => 'USER TOKEN NOT MATCHED.',
                ];
                return response()->json(['data' => $data], 200);
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }

        return response()->json(['data' => $allData], 200);
    }

    public function get_volume_transfer_list(Request $request)
    {
        $date = $request->date;
        $user_token = $request->header('USER-TOKEN');
        $user_token = $request->header('USER-TOKEN');
        $now = Carbon::now();
        $month = $now->year . '-' . $now->month;
        $year = $now->year;
        $start = Carbon::parse($month)->startOfMonth()->todateString();
        $start_year = Carbon::parse($year)->startOfYear();
        $end = Carbon::parse($month)->today()->todateString();
        $start_date = $start . ' 00:00:00';
        $end_date = $end . ' 23:59:59';
        $last_year = $year - 1;
        if ($user_token) {

            $dealer = DealerUser::where('user_token', $user_token)
                ->select(
                    'id',
                    'password',
                    'status',
                    'code',
                    'name',
                    'phone'
                )->get()->last();
            //dd($dealer);
            if ($dealer) {
                $feedback_list = VolumeTranfer::where('dealer_id', $dealer['id'])->where('status', '!=', 2)->where('soft_delete', 1)->orderBy('created_at', 'desc')->select(
                    'code',
                    'painter_id',
                    'dealer_id',
                    'accepted_by',
                    'created_at'
                )->distinct('code')->get()->toArray();

                //dd($feedback_list);

                //dd($feedback_l);
                if (!$feedback_list) {
                    $data = [
                        'message' => 'NO VOLUME ADDED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
                $allData = [];

                foreach ($feedback_list as $feedback) {
                    $entry_date = Carbon::parse($feedback['created_at'])->toDateString();
                    $deal = PainterUser::where('id', $feedback['painter_id'])->select(
                        'id',
                        'password',
                        'status',
                        'code',
                        'name',
                        'phone'
                    )->get()->last();
                    //dd($thana);
                    $feedback_l = VolumeTranfer::where('dealer_id', $dealer['id'])->where('code', $feedback['code'])
                        ->count('code');
                    $points = VolumeTranfer::where('dealer_id', $dealer['id'])->where('code', $feedback['code'])
                        ->sum('dealer_point');
                    //dd($points);

                    $dashboard = [
                        'painter_id' => $deal['id'],
                        'painter_code' => $deal['code'],
                        'painter_name' => $deal['name'],
                        'painter_phone' => $deal['phone'],
                        'product' => $feedback_l . ' ' . 'Products',
                        'code' => $feedback['code'],
                        'accepted_by' => $feedback['accepted_by'] == NULL ? '' : $feedback['accepted_by'],
                        'volume_date' => $entry_date,
                        'points' => round($points, 2) . ' ' . 'PTS',
                    ];

                    $allData[] = $dashboard;
                }
            } else {
                $data = [
                    'error' => 'USER TOKEN NOT MATCHED.',
                ];
                return response()->json(['data' => $data], 200);
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }

        return response()->json(['data' => $allData], 200);
    }

    public function get_redeem_history_by_date_range(Request $request)
    {

        $app_identifier = $request->app_identifier;
        $user_token = $request->header('USER-TOKEN');
        $starts = $request->start_date;
        $ends = $request->end_date;
        $now = Carbon::now();
        $month = $now->year . '-' . $now->month;
        $start = Carbon::parse($starts)->toDateString();
        $end = Carbon::parse($ends)->toDateString();

        $start_date = $start . ' 00:00:00';
        $end_date = $end . ' 23:59:59';
        //  dd($start_date.' '.$end_date );
        if ($user_token) {
            if ($app_identifier == 'com.ets.elitepaint.dealer') {
                $dealer = DealerUser::where('user_token', $user_token)
                    ->select(
                        'id',
                        'password',
                        'status',
                        'code',
                        'name',
                        'phone'
                    )->get()->last();
                //dd($dealer);
                if ($dealer) {
                    $scanpoint_year = RedeemPoint::where('dealer_id', $dealer['id'])
                        ->whereBetween('created_at', [$start, $end_date])
                        ->select('id', 'redeem_point', 'code', 'transaction_code', 'status', 'created_at')->get()->toArray();
                    //dd($scanpoint_year);
                    if (!$scanpoint_year) {
                        $data = [
                            'message' => 'NO HISTORY AVAILABLE',
                        ];
                        return response()->json(['data' => $data], 200);
                    }
                    $allData = [];
                    $all_total_year = 0;
                    foreach ($scanpoint_year as $scanpoint) {

                        $entry_time = Carbon::parse($scanpoint['created_at'])->format('g:i A');
                        $entry_date = Carbon::parse($scanpoint['created_at'])->toDateString();
                        //dd($product['name']);
                        $dashboard = [
                            'redeem_points' => $scanpoint['redeem_point'],
                            'redeem_date' => $entry_date,
                            'redeem_time' => $entry_time,
                            'received_money' => $scanpoint['redeem_point'],
                            'status' => $scanpoint['status'] == 2 ? 'PENDING' : 'RECEIVED',
                            'payment' => $scanpoint['status'] == 2 ? '' : $scanpoint['code'],
                            'transaction_id' => $scanpoint['transaction_code']
                        ];
                        $allData[] = $dashboard;
                    }
                } else {
                    $data = [
                        'error' => 'USER TOKEN NOT MATCHED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
            } elseif ($app_identifier == 'com.ets.elitepaint.painter') {
                $dealer = PainterUser::where('user_token', $user_token)
                    ->select(
                        'id',
                        'password',
                        'status',
                        'code',
                        'name',
                        'phone'
                    )->get()->last();
                //dd($dealer);
                if ($dealer) {
                    $scanpoint_year = RedeemPoint::where('painter_id', $dealer['id'])
                        ->whereBetween('created_at', [$start_date, $end_date])
                        ->select('id', 'redeem_point', 'code', 'transaction_code', 'status', 'created_at')->get()->toArray();
                    //dd($scanpoint_year);
                    if (!$scanpoint_year) {
                        $data = [
                            'message' => 'NO HISTORY AVAILABLE',
                        ];
                        return response()->json(['data' => $data], 200);
                    }
                    $allData = [];
                    $all_total_year = 0;
                    foreach ($scanpoint_year as $scanpoint) {

                        $entry_time = Carbon::parse($scanpoint['created_at'])->format('g:i A');
                        $entry_date = Carbon::parse($scanpoint['created_at'])->toDateString();
                        //dd($product['name']);
                        $dashboard = [
                            'redeem_points' => $scanpoint['redeem_point'],
                            'redeem_date' => $entry_date,
                            'redeem_time' => $entry_time,
                            'status' => $scanpoint['status'] == 2 ? 'PENDING' : 'RECEIVED ',
                            'payment' => $scanpoint['status'] == 2 ? '' : $scanpoint['code'],
                            'transaction_id' => $scanpoint['transaction_code']
                        ];
                        $allData[] = $dashboard;
                    }
                } else {
                    $data = [
                        'error' => 'USER TOKEN NOT MATCHED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }

        return response()->json(['data' => $allData], 200);
    }

    public function get_place_order_details(Request $request)
    {

        $code = $request->code;
        $user_token = $request->header('USER-TOKEN');

        // $dealer = DealerUser::where('dealer_code',$dealer_code)->where('user_token',$user_token)
        //                       ->select('id','password','status','app_identifier')->get()->last();
        //dd($user_token);

        if ($user_token) {

            $dealer = DealerUser::where('user_token', $user_token)
                ->select(
                    'id',
                    'password',
                    'status',
                    'code',
                    'name',
                    'phone'
                )->get()->last();
            //dd($dealer);
            if ($dealer) {
                $orders = PlaceOrder::where('code', $code)->orderBy('created_at', 'desc')->select(
                    'id',
                    'code',
                    'dealer_id',
                    'product_id',
                    'status',
                    'quantity',
                    'dealer_point',
                    'created_at'
                )->get()->toArray();
                $order_count = PlaceOrder::where('code', $code)->orderBy('created_at', 'desc')->select('id')->count();
                $order_date = PlaceOrder::where('code', $code)->orderBy('created_at', 'desc')->select(
                    'id',
                    'code',
                    'dealer_id',
                    'product_id',
                    'quantity',
                    'created_at'
                )->get()->first();

                $product_data = [];
                $total_quantity = 0;
                $total_amount = 0;
                foreach ($orders as $order) {
                    $product = Product::where('id', $order['product_id'])->select('id', 'pack_size_id', 'shade_code', 'product_code', 'product_name', 'shade_name', 'price')->get()->first();

                    $pack_size = Pack::where('id', $product['pack_size_id'])->select('id', 'subgroup_id', 'pack_name', 'pack_size', 'size_code', 'uom')->get()->first();
                    $subgroup = SubGroup::where('id', $pack_size['subgroup_id'])->select('id', 'subgroup_code', 'subgroup_name')->get()->first();

                    $total_quantity += $order['quantity'];
                    $total_amount += $product['price'];

                    $data = [

                        'product_id' => $product['id'],
                        'product_code' => $product['product_code'],
                        'product_name' => $product['product_name'],
                        'pack_size' => $pack_size['pack_size'],
                        'shade_name' => $product['shade_name'],
                        'dealer_point' => $order['dealer_point'],
                        'status' => $order['status'] == 1 ? 'PENDING' : 'ACCEPTED',
                        'quantity' => $order['quantity']
                    ];
                    $product_data[] = $data;
                }
                $final_data = [
                    'products' => $product_data
                ];

                //dd($order_date);
                $info = [

                    'no_of_product' => $order_count,
                    'order_date' => $order_date['created_at']->toDateString(),
                    'total_quantity' => $total_quantity,
                    'invoice_amount' => $total_amount
                ];
            } else {
                $data = [
                    'error' => 'USER TOKEN NOT MATCHED.',
                ];
                return response()->json(['data' => $data], 200);
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }

        return response()->json(['data' => ['info' => $info, 'products' => $product_data]], 200);
    }

    public function get_place_order_list(Request $request)
    {

        $date = $request->date;
        $user_token = $request->header('USER-TOKEN');
        $now = Carbon::now();
        $month = $now->year . '-' . $now->month;
        $year = $now->year;
        $start = Carbon::parse($month)->startOfMonth()->todateString();
        $start_year = Carbon::parse($year)->startOfYear();
        $end = Carbon::parse($month)->today()->todateString();
        $start_date = $start . ' 00:00:00';
        $end_date = $end . ' 23:59:59';
        $last_year = $year - 1;
        if ($user_token) {

            $dealer = DealerUser::where('user_token', $user_token)
                ->select(
                    'id',
                    'password',
                    'status',
                    'code',
                    'name',
                    'phone'
                )->get()->last();
            //dd($dealer);
            if ($dealer) {
                $feedback_list = PlaceOrder::where('dealer_id', $dealer['id'])->orderBy('created_at', 'desc')->select(
                    'code',
                    'created_at',
                    'status'
                )->distinct('code')->get()->toArray();

                if (!$feedback_list) {
                    $data = [
                        'message' => 'NO PLACE ORDER ADDED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
                $allData = [];

                foreach ($feedback_list as $feedback) {

                    //dd($thana);
                    $entry_date = Carbon::parse($feedback['created_at'])->toDateString();
                    $feedback_l = PlaceOrder::where('code', $feedback['code'])
                        ->count('code');

                    $feedback_2 = PlaceOrder::where('code', $feedback['code'])
                        ->sum('dealer_point');

                    $dashboard = [
                        'dealer_id' => $dealer['id'],
                        'dealer_code' => $dealer['code'],
                        'dealer_name' => $dealer['name'],
                        'product' => $feedback_l . ' ' . 'Products',
                        'status' => $feedback['status'] == 1 ? 'PENDING' : 'ACCEPTED',
                        'code' => $feedback['code'],
                        'dealer_point' => $feedback_2,
                        'place_order_date' => $entry_date,
                    ];

                    $allData[] = $dashboard;
                }
            } else {
                $data = [
                    'error' => 'USER TOKEN NOT MATCHED.',
                ];
                return response()->json(['data' => $data], 200);
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }

        return response()->json(['data' => $allData], 200);
    }

    public function purchase(Request $request)
    {
        if (config('custom.VOLUME_TRANSFER_OFF') && ($request->dealer_id != 34 && $request->dealer_id != 5592 && $request->dealer_id != 3349)) {
            $data = ['message' => 'এই ফিচারটি আপডেটের কাজ চলছে অনুগ্রহ করে অপেক্ষা করুন', 'type' => "message"];
            return response()->json(['data' => $data], 200);
        }
        $user_token = $request->header('USER-TOKEN');
        $dealer_id = $request->dealer_id;
        $product_ids = $request->product_ids;
        $quantities = $request->quantities;
        $date = $request->created_at;

        $accepted_by = NULL;
        $accepted_at = NULL;
        $status = 2;


        $current_date = NULL;
        $now = Carbon::now();

        if ($date != NULL) {
            $current_date = $date;
        } else {
            $current_date = $now->toDateTimeString();
        }

        if ($user_token) {

            $painter = PainterUser::where('user_token', $user_token)->where(['status' => 1, 'disable' => 1, 'soft_delete' => 1])->get()->last();
            $dealer = DealerUser::where('id', $dealer_id)->where(['status' => 1, 'disable' => 1, 'soft_delete' => 1])->get()->last();
            if ($painter) {
                $product_list = explode(",", $product_ids);
                $quatity_list = explode(",", $quantities);
                $liter = 0;
                foreach ($quatity_list as $qu) {
                    $liter += $qu;
                }

                $date = Carbon::parse($current_date)->format('Y-m-d');

                $painter_limit = config('custom.PAINTER_TRANSFER');
                $dealer_limit = config('custom.DEALER_TRANSFER');
                $dealer_limit_times = config('custom.TRANSFER_TIMES');
                $painter_limit_times = config('custom.TRANSFER_TIMES');

                $today_dealer_transfer = DB::table('volume_tranfers')->where('dealer_id', $dealer['id'])->where('soft_delete', '=', 1)->whereDate('created_at', '=', $date)->sum('quantity');
                $today_dealer_transfer_time = DB::table('volume_tranfers')->where('dealer_id', $dealer['id'])->where('soft_delete', '=', 1)->whereDate('created_at', '=', $date)->selectRaw('COUNT(DISTINCT IFNULL(code2,created_at))')
                    ->value('COUNT(DISTINCT IFNULL(code2,created_at))');
                $today_painter_transfer = DB::table('volume_tranfers')->where('soft_delete', '=', 1)->where('painter_id', $painter['id'])->whereDate('created_at', '=', $date)->sum('quantity');
                $today_painter_transfer_time = DB::table('volume_tranfers')->where('painter_id', $painter['id'])->where('soft_delete', '=', 1)->whereDate('created_at', '=', $date)->selectRaw('COUNT(DISTINCT IFNULL(code2,created_at))')
                    ->value('COUNT(DISTINCT IFNULL(code2,created_at))');

                $today_dealer_total = $today_dealer_transfer + $liter;
                $today_painter_total = $today_painter_transfer + $liter;
                $today_total_transfer_time = $today_dealer_transfer_time + 1;
                $today_total_transfer_time_painter = $today_painter_transfer_time + 1;

                if ($today_dealer_total > $dealer_limit) {
                    $data = ['message' => 'আপনার নির্বাচিত ডিলারের ' . $dealer_limit . ' লিটার সীমা ইতিমধ্যে অতিক্রম করেছে ', 'type' => "message"];
                    return response()->json(['data' => $data], 200);
                }
                if ($today_total_transfer_time > $dealer_limit_times) {
                    $data = ['message' => 'আপনার নির্বাচিত ডিলারের ' . $dealer_limit_times . ' বার লেনদেন সীমা ইতিমধ্যে অতিক্রম করেছে.', 'type' => "message"];
                    return response()->json(['data' => $data], 200);
                }
                if ($today_painter_total > $painter_limit) {
                    $data = ['message' => ' আপনি আজকে ' . $painter_limit . ' লিটারের বেশি নিতে পারবেন না.', 'type' => "message"];
                    return response()->json(['data' => $data], 200);
                }
                if ($today_total_transfer_time_painter > $painter_limit_times) {
                    $data = ['message' => ' আপনি আজকে  ' . $painter_limit_times . ' বারের বেশি লেনদেন করতে পারবেন না.', 'type' => "message"];
                    return response()->json(['data' => $data], 200);
                }
                //dd($quatity_list);
                if ($dealer['depo']) {
                    $depo = Depo::where('depo', $dealer['depo'])
                        ->select('id', 'depo', 'depo_code')->get()->last();
                } elseif ($painter['depo']) {
                    $depo = Depo::where('depo', $painter['depo'])
                        ->select('id', 'depo', 'depo_code')->get()->last();
                } else {
                    $data = [
                        'error' => 'PLEASE INPUT YOUR DEPO FIRST.',
                    ];
                    return response()->json(['data' => $data], 200);
                }


                $invitation_code = generateDPUCode($depo['depo_code']);

                $code2 = $this->generateRandomString();

                $index = 0;
                $all_total = 0;

                foreach ($product_list as $key => $product_id) {
                    $bonus_point = BonusPoint::where('dealer_id', $dealer_id)->where('soft_delete', 1)
                        ->sum('bonus_point');

                    $scanpoint_year = ScanPoint::where('dealer_id', $dealer_id)
                        ->get()->toArray();

                    $volumes = VolumeTranfer::where('dealer_id', $dealer_id)->where('status', '!=', 2)
                        ->get()->toArray();
                    $all_total = 0;
                    $total_redeem_point = 0;
                    $total_volume_point = 0;
                    $total_scan_point = 0;
                    //dd($dealer_list);
                    foreach ($volumes as $volume) {

                        $total_volume_point += $volume['dealer_point'];
                    }
                    //dd($total_redeem_point + $redeem_point);
                    foreach ($scanpoint_year as $scan_point) {
                        $total_scan_point += $scan_point['point'];
                    }
                    $all_total = $total_volume_point + $total_scan_point + $bonus_point;
                    //dd($all_total);

                    $dealer_members = EliteMember::where('category', '!=', 'PNT')->where('category', $dealer['member_type'])->get()->toArray();
                    if (!$dealer_members) {
                        $dealer_point = 0 * $quatity_list[$index];
                    } else {
                        foreach ($dealer_members as $dealer_member) {

                            if ($all_total < $dealer_member['point']) {
                                $id = $dealer_member['id'];
                                break;
                            }
                        }
                        if ($id) {
                            $ponts = Point::where('elite_member_id', $id)->where('product_id', $product_id)->where('end_date', '>=', $now->toDateString())
                                ->select('id', 'point')->get()->last();
                            //dd($ponts);
                            if ($ponts) {
                                $dealer_point = $ponts['point'] * $quatity_list[$index];
                            } else {
                                $dealer_point = 0 * $quatity_list[$index];
                            }
                        } else {
                            $dealer_point = 0 * $quatity_list[$index];
                        }
                    }

                    //for painter
                    $bonus_point_painter = BonusPoint::where('painter_id', $painter['id'])->where('soft_delete', 1)
                        ->select('id', 'bonus_point')->sum('bonus_point');
                    $scanpoint_year_painter = ScanPoint::where('painter_id', $painter['id'])
                        ->select('id', 'bar_code_id')->get()->toArray();

                    $volumes_painter = VolumeTranfer::where('painter_id', $painter['id'])->where('status', '!=', 2)
                        ->select('id', 'painter_point')->get()->toArray();
                    $all_total_painter = 0;
                    $total_redeem_point_painter = 0;
                    $total_volume_point_painter = 0;
                    $total_scan_point_painter = 0;
                    //dd($dealer_list);
                    foreach ($volumes_painter as $volume_painter) {

                        $total_volume_point_painter += $volume_painter['painter_point'];
                    }
                    //dd($total_redeem_point + $redeem_point);
                    foreach ($scanpoint_year as $scan_point) {
                        $total_scan_point += $scan_point['point'];
                    }
                    $all_total_painter = $total_volume_point_painter + $total_scan_point_painter + $bonus_point_painter;
                    //dd($all_total);
                    $members = EliteMember::where('category', '=', 'PNT')->get()->toArray();
                    //dd($members);
                    foreach ($members as $member) {
                        //dd($member['member_type']);
                        if ($all_total_painter < $member['point']) {
                            //dd(count($member));
                            $id = $member['id'];
                            break;
                        }
                    }
                    $volume_info = volume_transfer_point($dealer_id, $painter['id'], $product_id, $quatity_list[$key]);
                    if (isset($volume_info->message)) {
                        $data = [
                            'error' => $volume_info->message,
                        ];
                        return response()->json(['data' => $data], 200);
                    }

                    $dealer_point = $volume_info->dealer_point;
                    $painter_point = $volume_info->painter_point;

                    $offer = Point::where('type', 'OFFER')->where('product_id', $product_id)->where('end_date', '>=', $now->toDateString())
                        ->select('id', 'point')->get()->last();
                    if ($offer) {
                        $dealer_point += $offer['point'] * $quatity_list[$key];
                        $painter_point += $offer['point'] * $quatity_list[$key];
                    }

                    $subgroup = \Illuminate\Support\Facades\DB::table('subgroups')->find($product_id);
                    if (!$subgroup->basegroup_id) {
                        $basegroup = \Illuminate\Support\Facades\DB::table('basegroups')->insertGetId([
                            'basegroup_code' => $subgroup->subgroup_code,
                            'basegroup_name' => $subgroup->subgroup_name,
                            'delivery_percentage' => 0,
                            'created_at' => Carbon::now()
                        ]);
                        \Illuminate\Support\Facades\DB::table('subgroups')->where('id', $product_id)->update(['basegroup_id' => $basegroup]);
                    }
                    DB::table('volume_tranfers')->insert([
                        'painter_id' => $painter['id'],
                        'dealer_id' => $dealer_id,
                        'basegroup_id' => $subgroup->basegroup_id,
                        'product_id' => $product_id,
                        'quantity' => $quatity_list[$index++],
                        'code' => $invitation_code,
                        'code2' => $code2,
                        'dealer_member_type_id' => $volume_info->dealer_member_type_id,
                        'dealer_point' => $dealer_point,
                        'painter_member_type_id' => $volume_info->painter_member_type_id,
                        'painter_point' => $painter_point,
                        'status' => $status,
                        'accepted_at' => $accepted_at,
                        'accepted_by' => $accepted_by,
                        'created_at' => $current_date
                    ]);
                    //$liter += $quatity_list[$index++];
                }


                $message = $liter . ' লিটার ক্রয়ের অনুমতি সুনিশ্চিত করুন, ক্রয় নং ' . $invitation_code . ', ' . $painter['name'];

                PushController::push_send_to_parent($dealer['push_token'], 'PURCHASE', $message);
            } else {
                $data = [
                    'error' => 'USER TOKEN NOT MATCHED.',
                ];
                return response()->json(['data' => $data], 200);
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }
        // $dashboard = [
        //       'transaction_id' => $invitation_code,
        //       'dealer_point' => $all_total

        //         ];
        $data = ['message' => 'TRANSACTION ID : ' . $invitation_code, 'type' => "message"];
        return response()->json(['data' => $data], 200);
    }

    public function get_purchase_history_by_date_range(Request $request)
    {

        $user_token = $request->header('USER-TOKEN');
        $starts = $request->start_date;
        $ends = $request->end_date;
        $now = Carbon::now();
        $month = $now->year . '-' . $now->month;
        $start = Carbon::parse($starts)->toDateString();
        $end = Carbon::parse($ends)->toDateString();

        $start_date = $start . ' 00:00:00';
        $end_date = $end . ' 23:59:59';

        if ($user_token) {

            $dealer = PainterUser::where('user_token', $user_token)
                ->select(
                    'id',
                    'password',
                    'status',
                    'code',
                    'name',
                    'phone'
                )->get()->last();
            //dd($dealer);
            if ($dealer) {
                $feedback_list = VolumeTranfer::where('painter_id', $dealer['id'])->where('soft_delete', 1)->whereBetween('created_at', [$start_date, $end_date])->orderBy('created_at', 'desc')->select(
                    'code',
                    'painter_id',
                    'dealer_id',
                    'status',
                    'created_at'
                )->distinct('code')->get()->toArray();

                //dd($feedback_list);

                //dd($feedback_l);
                if (!$feedback_list) {
                    $data = [
                        'message' => 'NO PURCHASE ADDED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
                $allData = [];
                //$total_ltr = 0;
                foreach ($feedback_list as $feedback) {
                    $entry_date = Carbon::parse($feedback['created_at'])->toDateString();
                    $total_codes = VolumeTranfer::where('code', $feedback['code'])->select('id', 'code')->get();
                    //dd($total_codes);
                    $total_ltr = 0;
                    foreach ($total_codes as $total_code) {
                        //dd($total_code['id']);
                        $volum = VolumeTranfer::where('id', $total_code['id'])
                            ->select('id', 'product_id', 'quantity')->get()->last();
                        // $product = Product::where('id',$volum['product_id'])
                        //                   ->select('id','pack_size_id')->get()->last();
                        // $pack_size = Pack::where('id',$product['pack_size_id'])
                        //                   ->select('id','pack_size')->get()->last();
                        //$total = $volum['quantity']* $pack_size['pack_size'];
                        $total_ltr += $volum['quantity'];
                    }
                    $deal = DealerUser::where('id', $feedback['dealer_id'])->select(
                        'id',
                        'password',
                        'status',
                        'code',
                        'name',
                        'phone'
                    )->get()->last();
                    //dd($thana);
                    $feedback_l = VolumeTranfer::where('code', $feedback['code'])
                        ->count('code');

                    $points = VolumeTranfer::where('code', $feedback['code'])
                        ->sum('painter_point');
                    $dashboard = [
                        'dealer_id' => $deal['id'],
                        'dealer_code' => $deal['code'],
                        'dealer_name' => $deal['name'],
                        'dealer_phone' => $deal['phone'],
                        'product' => $feedback_l . ' ' . 'Products',
                        'ltr' => round($total_ltr, 2) . ' ' . 'LTR',
                        'code' => $feedback['code'],
                        'purchase_date' => $entry_date,
                        'points' => round($points, 2) . ' ' . 'PTS',
                        'status' => $feedback['status'] == 2 ? 'PENDING' : 'ACCEPTED',
                    ];

                    $allData[] = $dashboard;
                }
            } else {
                $data = [
                    'error' => 'USER TOKEN NOT MATCHED.',
                ];
                return response()->json(['data' => $data], 200);
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }

        return response()->json(['data' => $allData], 200);
    }

    public function get_purchase_list(Request $request)
    {
        $user_token = $request->header('USER-TOKEN');
        $now = Carbon::now();
        $month = $now->year . '-' . $now->month;
        $year = $now->year;
        $start = Carbon::parse($month)->startOfMonth()->todateString();
        $start_year = Carbon::parse($year)->startOfYear();
        $end = Carbon::parse($month)->today()->todateString();
        $start_date = $start . ' 00:00:00';
        $end_date = $end . ' 23:59:59';
        $last_year = $year - 1;
        if ($user_token) {

            $dealer = PainterUser::where('user_token', $user_token)
                ->select(
                    'id',
                    'password',
                    'status',
                    'code',
                    'name',
                    'phone'
                )->get()->last();
            //dd($dealer);
            if ($dealer) {
                $feedback_list = VolumeTranfer::where('painter_id', $dealer['id'])->where('soft_delete', 1)->orderBy('created_at', 'desc')->select(
                    'code',
                    'painter_id',
                    'dealer_id',
                    'status',
                    'created_at',
                    'accepted_by'
                )->distinct('code')->get()->toArray();

                //dd($feedback_list);

                //dd($feedback_l);
                if (!$feedback_list) {
                    $data = [
                        'message' => 'NO PURCHASE ADDED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
                $allData = [];
                //$total_ltr = 0;
                
                foreach ($feedback_list as $feedback) {
                    $entry_date = Carbon::parse($feedback['created_at'])->toDateString();
                    $total_codes = VolumeTranfer::where('code', $feedback['code'])->select('id', 'code')->get();
                    //dd($total_codes);
                    $total_ltr = 0;
                    foreach ($total_codes as $total_code) {
                        //dd($total_code['id']);
                        $volum = VolumeTranfer::where('id', $total_code['id'])
                            ->select('id', 'product_id', 'quantity')->get()->last();
                        // $product = Product::where('id',$volum['product_id'])
                        //                   ->select('id','pack_size_id')->get()->last();
                        // $pack_size = Pack::where('id',$product['pack_size_id'])
                        //                   ->select('id','pack_size')->get()->last();
                        //$total = $volum['quantity']* $pack_size['pack_size'];
                        $total_ltr += floatval($volum['quantity']);
                    }
                    $deal = DealerUser::where('id', $feedback['dealer_id'])->select(
                        'id',
                        'password',
                        'status',
                        'code',
                        'name',
                        'phone'
                    )->get()->last();
                    //dd($thana);
                    $feedback_l = VolumeTranfer::where('code', $feedback['code'])
                        ->count('code');


                    $points = VolumeTranfer::where('code', $feedback['code'])
                        ->sum('painter_point');

                    $dashboard = [
                        'dealer_id' => $deal['id'],
                        'dealer_code' => $deal['code'],
                        'dealer_name' => $deal['name'],
                        'dealer_phone' => $deal['phone'],
                        'product' => $feedback_l . ' ' . 'Products',
                        'ltr' => round($total_ltr, 2) . ' ' . 'LTR',
                        'code' => $feedback['code'],
                        'accepted_by' => $feedback['accepted_by'] == NULL ? '' : $feedback['accepted_by'],
                        'purchase_date' => $entry_date,
                        'points' => round($points, 2) . ' ' . 'PTS',
                        'status' => $feedback['status'] == 2 ? 'PENDING' : 'ACCEPTED',
                    ];

                    $allData[] = $dashboard;

                    $allData[] = $dashboard;
                }
            } else {
                $data = [
                    'error' => 'USER TOKEN NOT MATCHED.',
                ];
                return response()->json(['data' => $data], 200);
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }

        return response()->json(['data' => $allData], 200);
    }

    public function get_dpu_information(Request $request)
    {

        $user_token = $request->header('USER-TOKEN');
        $date = $request->date_history;

        $now = Carbon::now();
        $month = $now->year . '-' . $now->month;
        $year = $now->year;
        $start = Carbon::parse($month)->startOfMonth()->todateString();
        $start_year = Carbon::parse($year)->startOfYear();
        $end = Carbon::parse($month)->today()->todateString();
        $start_date = $start . ' 00:00:00';
        $end_date = $end . ' 23:59:59';
        $last_year = $year - 1;

        if ($user_token) {

            $dealer = DealerUser::where('user_token', $user_token)
                ->select(
                    'id',
                    'password',
                    'status',
                    'code',
                    'name',
                    'phone'
                )->get()->last();
            //dd($dealer);
            if ($dealer) {
                if ($date) {
                    $feedback_list = VolumeTranfer::where('dealer_id', $dealer['id'])->where('status', 3)->where('soft_delete', 1)->orderBy('created_at', 'desc')->select('code', 'painter_id', 'dealer_id', 'created_at')->distinct('code')->get()->toArray();
                } else {
                    $feedback_list = VolumeTranfer::where('dealer_id', $dealer['id'])->where('status', 2)->where('soft_delete', 1)->orderBy('created_at', 'desc')->select('code', 'painter_id', 'dealer_id', 'created_at')->distinct('code')->get()->toArray();
                }


                //dd($feedback_list);

                //dd($feedback_l);
                if (!$feedback_list) {
                    $data = [
                        'message' => 'NO DPU ADDED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
                $allData = [];

                foreach ($feedback_list as $feedback) {
                    //dd($feedback);
                    $entry_date = Carbon::parse($feedback['created_at'])->toDateString();
                    $deal = PainterUser::where('id', $feedback['painter_id'])->select(
                        'id',
                        'password',
                        'status',
                        'code',
                        'name',
                        'phone'
                    )->get()->last();
                    //dd($thana);
                    $feedback_l = VolumeTranfer::where('dealer_id', $dealer['id'])->where('code', $feedback['code'])
                        ->count('code');
                    $points = VolumeTranfer::where('dealer_id', $dealer['id'])->where('code', $feedback['code'])
                        ->sum('dealer_point');

                    $dashboard = [
                        'dealer_id' => $dealer['id'],
                        'dealer_code' => $dealer['code'],
                        'dealer_name' => $dealer['name'],
                        'painter_id' => $deal['id'],
                        'painter_code' => $deal['code'],
                        'painter_name' => $deal['name'],
                        'painter_phone' => $deal['phone'],
                        'product' => $feedback_l . ' ' . 'Products',
                        'code' => $feedback['code'],
                        'dpu_date' => $entry_date,
                        'dealer_points' => round($points, 2),
                        'created_at' => $feedback['created_at'],
                    ];

                    $allData[] = $dashboard;
                }
            } else {
                $data = [
                    'error' => 'USER TOKEN NOT MATCHED.',
                ];
                return response()->json(['data' => $data], 200);
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }

        return response()->json(['data' => $allData], 200);
    }

    public function get_dpu_details(Request $request)
    {

        $code = $request->code;
        $user_token = $request->header('USER-TOKEN');

        if ($user_token) {

            $dealer = DealerUser::where('user_token', $user_token)
                ->select(
                    'id',
                    'password',
                    'status',
                    'code',
                    'name',
                    'phone'
                )->get()->last();
            //dd($dealer);
            if ($dealer) {
                $orders = VolumeTranfer::where('dealer_id', $dealer['id'])->where('code', $code)->where('soft_delete', 1)->orderBy('created_at', 'desc')->select(
                    'id',
                    'code',
                    'dealer_id',
                    'painter_id',
                    'product_id',
                    'quantity',
                    'dealer_point',
                    'painter_point',
                    'created_at'
                )->get()->toArray();

                $product_data = [];

                foreach ($orders as $order) {

                    $basegroup = BaseGroup::where('id', $order['product_id'])->where('soft_delete', 1)->select('id', 'basegroup_code', 'basegroup_name')->get()->first();


                    $subgroup_list = SubGroup::select('id', 'subgroup_code', 'subgroup_name')->where('basegroup_id', $basegroup['id'])->where('soft_delete', 1)->get()->toArray();
                    if ($subgroup_list) {
                        $volume_tranfers = 0;
                        $total_ltr = 0;
                        $stock = 0;
                        foreach ($subgroup_list as $subgroup) {
                            $invoices = DB::table('invoices')->select('id', 'pack_size', 'quantity')->where('dealer_id', $dealer['id'])
                                ->Where('product_code', 'LIKE', '%' . $subgroup['subgroup_code'] . '%')->get()->toArray();


                            $volume_tranfers += DB::table('volume_tranfers')
                                ->where('dealer_id', $dealer['id'])
                                ->where('product_id', $subgroup['id'])
                                ->where('soft_delete', 1)
                                ->where('status', '!=', 2)->sum('quantity');
                            //dd($volume_tranfers);

                            $total_ltr_sub = 0;
                            foreach ($invoices as $invoice) {

                                $total_ltr_sub += $invoice->quantity * $invoice->pack_size;
                            }
                            $total_ltr += $total_ltr_sub;
                            $stock = $total_ltr - $volume_tranfers;
                        }
                    } else {
                        $volume_tranfers = 0;
                        $total_ltr = 0;
                        $stock = 0;
                        $invoices = DB::table('invoices')->select('id', 'pack_size', 'quantity')->where('dealer_id', $dealer['id'])
                            ->Where('product_code', 'LIKE', '%' . $basegroup['basegroup_code'] . '%')->get()->toArray();


                        $volume_tranfers += DB::table('volume_tranfers')
                            ->where('dealer_id', $dealer['id'])
                            ->where('product_id', $basegroup['id'])
                            ->where('soft_delete', 1)
                            ->where('status', '!=', 2)->sum('quantity');
                        //dd($volume_tranfers);


                        foreach ($invoices as $invoice) {

                            $total_ltr += $invoice->quantity * $invoice->pack_size;
                        }
                        $stock = $total_ltr - $volume_tranfers;
                    }
                    $data = [

                        'product_id' => $basegroup['id'],
                        'product_name' => $basegroup['basegroup_name'],
                        'quantity' => round($order['quantity'], 2),
                        'stock' => \App\Classes\Stock::basegroup_transferable_stock($basegroup['id'], $dealer['id'], $order['created_at']),
                        'dealer_point' => round($order['dealer_point'], 2)
                    ];
                    $product_data[] = $data;
                }
                $final_data = [
                    'products' => $product_data
                ];
            } else {
                $data = [
                    'error' => 'USER TOKEN NOT MATCHED.',
                ];
                return response()->json(['data' => $data], 200);
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }

        return response()->json(['data' => $final_data], 200);
    }

    public function get_dpu_history_by_date_range(Request $request)
    {

        $app_identifier = $request->app_identifier;
        $user_token = $request->header('USER-TOKEN');
        $starts = $request->start_date;
        $ends = $request->end_date;
        $now = Carbon::now();
        $month = $now->year . '-' . $now->month;
        $start = Carbon::parse($starts)->toDateString();
        $end = Carbon::parse($ends)->toDateString();

        $start_date = $start . ' 00:00:00';
        $end_date = $end . ' 23:59:59';

        if ($user_token) {

            $dealer = DealerUser::where('user_token', $user_token)
                ->select(
                    'id',
                    'password',
                    'status',
                    'code',
                    'name',
                    'phone'
                )->get()->last();
            //dd($dealer);
            if ($dealer) {
                $feedback_list = VolumeTranfer::where('dealer_id', $dealer['id'])->where('status', 3)->where('soft_delete', 1)->whereBetween('created_at', [$start_date, $end_date])->orderBy('created_at', 'desc')->select('code', 'painter_id', 'dealer_id', 'created_at')->distinct('code')->get()->toArray();


                //dd($feedback_list);

                //dd($feedback_l);
                if (!$feedback_list) {
                    $data = [
                        'message' => 'NO DPU ADDED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
                $allData = [];

                foreach ($feedback_list as $feedback) {
                    //dd($feedback);
                    $entry_date = Carbon::parse($feedback['created_at'])->toDateString();
                    $deal = PainterUser::where('id', $feedback['painter_id'])->select(
                        'id',
                        'password',
                        'status',
                        'code',
                        'name',
                        'phone'
                    )->get()->last();
                    //dd($thana);
                    $feedback_l = VolumeTranfer::where('code', $feedback['code'])
                        ->count('code');
                    $points = VolumeTranfer::where('code', $feedback['code'])
                        ->sum('dealer_point');

                    $dashboard = [
                        'dealer_id' => $dealer['id'],
                        'dealer_code' => $dealer['code'],
                        'dealer_name' => $dealer['name'],
                        'painter_id' => $deal['id'],
                        'painter_code' => $deal['code'],
                        'painter_name' => $deal['name'],
                        'painter_phone' => $deal['phone'],
                        'product' => $feedback_l . ' ' . 'Products',
                        'code' => $feedback['code'],
                        'dpu_date' => $entry_date,
                        'dealer_points' => round($points, 2),
                        'created_at' => $feedback['created_at'],
                    ];

                    $allData[] = $dashboard;
                }
            } else {
                $data = [
                    'error' => 'USER TOKEN NOT MATCHED.',
                ];
                return response()->json(['data' => $data], 200);
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }

        return response()->json(['data' => $allData], 200);
    }

    public function date_history_for_dealer(Request $request)
    {


        $user_token = $request->header('USER-TOKEN');

        $now = Carbon::now();
        $month = $now->year . '-' . $now->month;
        $start = Carbon::parse($month)->startOfMonth();
        $end = Carbon::parse($month)->today();

        $all_dates = [];

        // get all dates
        while ($start->lte($end)) {
            $all_dates[] = $start->copy();
            $start->addDay();
        }

        $all_dates = array_reverse($all_dates);


        // $dealer = DealerUser::where('dealer_code',$dealer_code)->where('user_token',$user_token)
        //                       ->select('id','password','status','app_identifier')->get()->last();
        //dd($user_token);

        if ($user_token) {

            $dealer = DealerUser::where('user_token', $user_token)
                ->select(
                    'id',
                    'password',
                    'status',
                    'code',
                    'name',
                    'phone'
                )->get()->last();
            //dd($dealer);
            if ($dealer) {
                foreach ($all_dates as $date) {

                    $data = [
                        'date' => $date->todateString(),
                        'day' => $date->format('l'),
                        'enable' => 1,
                    ];
                    $allData[] = $data;
                }
            } else {
                $data = [
                    'error' => 'USER TOKEN NOT MATCHED.',
                ];
                return response()->json(['data' => $data], 200);
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }

        return response()->json(['data' => $allData], 200);
    }

    public function get_dpu_history(Request $request)
    {


        $user_token = $request->header('USER-TOKEN');

        $now = Carbon::now();
        $month = $now->year . '-' . $now->month;
        $start = Carbon::parse($month)->startOfMonth();
        $end = Carbon::parse($month)->today();

        $all_dates = [];

        // get all dates
        while ($start->lte($end)) {
            $all_dates[] = $start->copy();
            $start->addDay();
        }

        $all_dates = array_reverse($all_dates);


        // $dealer = DealerUser::where('dealer_code',$dealer_code)->where('user_token',$user_token)
        //                       ->select('id','password','status','app_identifier')->get()->last();
        //dd($user_token);

        if ($user_token) {

            $dealer = DealerUser::where('user_token', $user_token)
                ->select(
                    'id',
                    'password',
                    'status',
                    'code',
                    'name',
                    'phone'
                )->get()->last();
            //dd($dealer);
            if ($dealer) {
                $allData = [];
                foreach ($all_dates as $date) {
                    $actual_date = $date->todateString();
                    $complete_total = VolumeTranfer::where('dealer_id', $dealer['id'])
                        ->where('status', 3)
                        ->where('created_at', 'LIKE', '%' . $actual_date . '%')->distinct('code')->count('code');
                    if ($complete_total > 0) {
                        $data = [
                            'date' => $date->todateString(),
                            'day' => $date->format('l'),
                            'enable' => ($complete_total == null) ? 0 : 1,
                            'dpu' => $complete_total
                        ];
                        $allData[] = $data;
                    }
                }
                if (empty($allData)) {
                    //dd('s');
                    $datas = [
                        'message' => 'NO DATE AVAILABLE',
                    ];
                    return response()->json(['data' => $datas], 200);
                }
            } else {
                $data = [
                    'error' => 'USER TOKEN NOT MATCHED.',
                ];
                return response()->json(['data' => $data], 200);
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }

        return response()->json(['data' => $allData], 200);
    }

    public function update_dpu(Request $request)
    {

        if (config('custom.VOLUME_TRANSFER_OFF') && ($request->painter_id != 13 && $request->painter_id != 671)) {
            $data = ['message' => 'এই ফিচারটি আপডেটের কাজ চলছে অনুগ্রহ করে অপেক্ষা করুন', 'type' => "message"];
            return response()->json(['data' => $data], 200);
        }

        $code = $request->code;
        $painter_id = $request->painter_id;
        $product_ids = $request->product_ids;
        $quantities = $request->quantities;
        $user_token = $request->header('USER-TOKEN');


        $current_date = NULL;
        $now = Carbon::now();

        $accepted_by = 'Dealer';
        $accepted_at = Carbon::now();
        $status = 3;


        if ($user_token) {

            $dealer = DealerUser::where('user_token', $user_token)
                ->select(
                    'id',
                    'password',
                    'member_type',
                    'status',
                    'code',
                    'name',
                    'phone',
                    'depo'
                )->where(['status' => 1, 'disable' => 1, 'soft_delete' => 1])->get()->last();
            if ($dealer) {
                // DB::table('volume_tranfers')->where('code', $code)->delete();
                $product_list = explode(",", $product_ids);
                $quatity_list = explode(",", $quantities);

                $volume_data = DB::table('volume_tranfers')->where('code', $code)->whereNotIn('product_id', $product_list)->update([
                    'soft_delete' => 2
                ]);

                if ($dealer['depo']) {
                    $depo = Depo::where('depo', $dealer['depo'])
                        ->select('id', 'depo', 'depo_code')->get()->last();
                } else {
                    $data = [
                        'error' => 'PLEASE INPUT YOUR DEPO FIRST.',
                    ];
                    return response()->json(['data' => $data], 200);
                }

                //New


                $total_quantity = 0;
                foreach ($product_list as $key => $product) {
                    $total_quantity += $quatity_list[$key];
                }

                $dpu_date = DB::table('volume_tranfers')->where('code', $code)->first()->created_at;
                $date = Carbon::parse($dpu_date)->format('Y-m-d');
                //Volume Transfer Condition Start
                $painter_limit = config('custom.PAINTER_TRANSFER');
                $dealer_limit = config('custom.DEALER_TRANSFER');
                $dealer_limit_times = config('custom.TRANSFER_TIMES');
                $painter_limit_times = config('custom.TRANSFER_TIMES');

                $today_dealer_transfer = DB::table('volume_tranfers')->where('code', $code)->where('dealer_id', $dealer['id'])->where('soft_delete', '=', 1)->whereDate('created_at', '=', $date)->sum('quantity');
                $today_dealer_transfer_time = DB::table('volume_tranfers')->where('code', $code)->where('dealer_id', $dealer['id'])->where('soft_delete', '=', 1)->whereDate('created_at', '=', $date)->selectRaw('COUNT(DISTINCT IFNULL(code2,created_at))')
                    ->value('COUNT(DISTINCT IFNULL(code2,created_at))');
                $today_painter_transfer = DB::table('volume_tranfers')->where('code', $code)->where('soft_delete', '=', 1)->where('painter_id', $painter_id)->whereDate('created_at', '=', $date)->sum('quantity');
                $today_painter_transfer_time = DB::table('volume_tranfers')->where('code', $code)->where('painter_id', $painter_id)->where('soft_delete', '=', 1)->whereDate('created_at', '=', $date)->selectRaw('COUNT(DISTINCT IFNULL(code2,created_at))')
                    ->value('COUNT(DISTINCT IFNULL(code2,created_at))');

                $today_dealer_total = $today_dealer_transfer + $total_quantity;
                $today_painter_total = $today_painter_transfer + $total_quantity;
                $today_total_transfer_time = $today_dealer_transfer_time + 1;
                $today_total_transfer_time_painter = $today_painter_transfer_time + 1;


                if ($today_dealer_total > $dealer_limit) {
                    $data = ['message' => 'আপনার নির্বাচিত ডিলারের ' . $dealer_limit . ' লিটার সীমা ইতিমধ্যে অতিক্রম করেছে ', 'type' => "message"];
                    return response()->json(['data' => $data], 200);
                }
                if ($today_total_transfer_time > $dealer_limit_times) {
                    $data = ['message' => 'আপনার নির্বাচিত ডিলারের ' . $dealer_limit_times . ' বার লেনদেন সীমা ইতিমধ্যে অতিক্রম করেছে.', 'type' => "message"];
                    return response()->json(['data' => $data], 200);
                }
                if ($today_painter_total > $painter_limit) {
                    $data = ['message' => ' আপনি আজকে ' . $painter_limit . ' লিটারের বেশি নিতে পারবেন না.', 'type' => "message"];
                    return response()->json(['data' => $data], 200);
                }
                if ($today_total_transfer_time_painter > $painter_limit_times) {
                    $data = ['message' => ' আপনি আজকে  ' . $painter_limit_times . ' বারের বেশি লেনদেন করতে পারবেন না.', 'type' => "message"];
                    return response()->json(['data' => $data], 200);
                }
                //Volume Transfer Condition End

                $code2 = $this->generateRandomString();
                $total_dealer_point = 0;
                $total_painter_point = 0;
                foreach ($product_list as $key => $product_id) {

                    $volume_info = volume_transfer_point($dealer['id'], $painter_id, $product_id, $quatity_list[$key]);
                    if (isset($volume_info->message)) {
                        $data = [
                            'error' => $volume_info->message,
                        ];
                        return response()->json(['data' => $data], 200);
                    }
                    $dealer_point = $volume_info->dealer_point;
                    $painter_point = $volume_info->painter_point;
                    //dd($all_total_painter. '-'.$all_total);
                    $offer = Point::where('type', 'OFFER')->where('product_id', $product_id)->where('end_date', '>=', $now->toDateString())
                        ->select('id', 'point')->get()->last();
                    if ($offer) {
                        $dealer_point += $offer['point'] * $quatity_list[$key];
                        $painter_point += $offer['point'] * $quatity_list[$key];
                    }


                    $subgroup = \Illuminate\Support\Facades\DB::table('subgroups')->find($product_id);
                    if (!$subgroup->basegroup_id) {
                        $basegroup = \Illuminate\Support\Facades\DB::table('basegroups')->insertGetId([
                            'basegroup_code' => $subgroup->subgroup_code,
                            'basegroup_name' => $subgroup->subgroup_name,
                            'delivery_percentage' => 0,
                            'created_at' => Carbon::now()
                        ]);
                        \Illuminate\Support\Facades\DB::table('subgroups')->where('id', $product_id)->update(['basegroup_id' => $basegroup]);
                    }
                    $stock = \App\Classes\Stock::basegroup_stock($subgroup->basegroup_id, $dealer['id'], $date);
                    DB::table('volume_tranfers')->where([
                        'code' => $code,
                        'dealer_id' => $dealer['id'],
                        'painter_id' => $painter_id,
                        'basegroup_id' => $subgroup->basegroup_id,
                        'product_id' => $product_id
                    ])->update([
                        'quantity' => $quatity_list[$key],
                        'dealer_member_type_id' => $volume_info->dealer_member_type_id,
                        'dealer_point' => $dealer_point,
                        'painter_member_type_id' => $volume_info->painter_member_type_id,
                        'painter_point' => $painter_point,
                        'accepted_by' => $accepted_by,
                        'accepted_at' => $accepted_at,
                        'status' => $status,
                    ]);

                    $total_dealer_point += $dealer_point;
                    $total_painter_point += $painter_point;
                }
                \App\Classes\PointUpdate::painter_level_update($painter_id);
                $painter = PainterUser::where('id', $painter_id)
                    ->select(
                        'id',
                        'push_token',
                        'status',
                        'code',
                        'name',
                        'phone'
                    )->get()->last();
                $message = $total_painter_point . ' ক্রয় পয়েন্ট যুক্ত হয়েছে , ক্রয় নং ' . $code . '. ' . $dealer['name'];

                PushController::push_send_to_parent($painter['push_token'], 'VOLUME TRANSFER', $message);
            } else {
                $data = [
                    'error' => 'USER TOKEN NOT MATCHED.',
                ];
                return response()->json(['data' => $data], 200);
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }

        $data = ['message' => 'TRANSACTION ID : ' . $code . ' AND POINTS : ' . $total_dealer_point, 'type' => "message"];
        return response()->json(['data' => $data], 200);
    }

    public function delete_dpu(Request $request)
    {

        $code = $request->code;
        $user_token = $request->header('USER-TOKEN');

        if ($user_token) {

            $dealer = DealerUser::where('user_token', $user_token)
                ->select(
                    'id',
                    'password',
                    'status',
                    'code',
                    'name',
                    'phone'
                )->get()->last();
            if ($dealer) {
                DB::table('volume_tranfers')->where('code', $code)->update(['soft_delete' => 2]);
            } else {
                $data = [
                    'error' => 'USER TOKEN NOT MATCHED.',
                ];
                return response()->json(['data' => $data], 200);
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }

        $data = ['message' => 'Deleted', 'type' => "message"];
        return response()->json(['data' => $data], 200);
    }

    public function get_my_accounts(Request $request)
    {

        $user_token = $request->header('USER-TOKEN');

        if ($user_token) {

            $dealer = DealerUser::where('user_token', $user_token)
                ->select(
                    'id',
                    'password',
                    'status',
                    'code',
                    'name',
                    'phone'
                )->get()->last();
            //dd($dealer);
            if ($dealer) {
                $account = DB::table('account_summery')->where('dealer_id', $dealer['id'])->select('id', 'balance', 'sales', 'payment')->get()->last();
                if ($account) {
                    $balance = number_format($account->balance, 2);
                    $sales = number_format($account->sales, 2);
                    $payment = number_format($account->payment, 2);
                } else {
                    $balance = 0;
                    $sales = 0;
                    $payment = 0;
                }

                $data = [

                    'opening_balance' => (string)$balance,
                    'current_balance' => (string)$sales,
                    'outstanding_balance' => (string)$payment
                ];


                $feedback_list = PlaceOrder::where('dealer_id', $dealer['id'])->orderBy('created_at', 'desc')->select(
                    'code',
                    'status',
                    'created_at'
                )->distinct('code')->latest()->take(5)->get()->toArray();
                //dd($feedback_list);
                // if(!$feedback_list){
                //   $data = [
                //       'error' => 'NO ORDER ADDED.',
                //   ];
                // }
                $allData = [];
                //$total_quantity = 0;
                foreach ($feedback_list as $feedback) {

                    //dd($thana);
                    $feedback_l = PlaceOrder::where('code', $feedback['code'])
                        ->count('code');
                    $total_quantity = PlaceOrder::where('code', $feedback['code'])
                        ->sum('quantity');

                    $dashboard = [
                        'no_of_product' => $feedback_l,
                        'order_date' => $feedback['created_at'],
                        'order_id' => $feedback['code'],
                        'status' => $feedback['status'] == 2 ? 'ACCEPTED' : 'PENDING',
                        'quantity' => $total_quantity,
                    ];

                    $allData[] = $dashboard;
                }
            } else {
                $data = [
                    'error' => 'USER TOKEN NOT MATCHED.',
                ];
                return response()->json(['data' => $data], 200);
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }

        return response()->json(['data' => ['account_summary' => $data, 'last_five_order' => $allData]], 200);
    }

    public function get_invoice_details_for_five_order(Request $request)
    {

        $user_token = $request->header('USER-TOKEN');
        $code = $request->code;

        if ($user_token) {

            $dealer = DealerUser::where('user_token', $user_token)
                ->select(
                    'id',
                    'password',
                    'status',
                    'code',
                    'name',
                    'phone'
                )->get()->last();
            //dd($dealer);
            if ($dealer) {
                $orders = PlaceOrder::where('code', $code)->orderBy('created_at', 'desc')->select(
                    'id',
                    'code',
                    'dealer_id',
                    'product_id',
                    'quantity',
                    'status',
                    'created_at'
                )->get()->toArray();
                $order_count = PlaceOrder::where('code', $code)->orderBy('created_at', 'desc')->select('id')->count();
                $order_date = PlaceOrder::where('code', $code)->orderBy('created_at', 'desc')->select(
                    'id',
                    'code',
                    'dealer_id',
                    'product_id',
                    'quantity',
                    'status',
                    'created_at'
                )->get()->first();

                $live_order = [];
                $total_quantity = 0;
                $total_amount = 0;
                foreach ($orders as $order) {
                    $product = Product::where('id', $order['product_id'])->select('id', 'pack_size_id', 'shade_code', 'product_code', 'product_name', 'shade_name', 'price')->get()->first();

                    $pack_size = Pack::where('id', $product['pack_size_id'])->select('id', 'subgroup_id', 'pack_name', 'pack_size', 'size_code', 'uom')->get()->first();
                    $subgroup = SubGroup::where('id', $pack_size['subgroup_id'])->select('id', 'subgroup_code', 'subgroup_name')->get()->first();
                    $net_amount = $product['price'] * $order['quantity'];
                    $total_quantity += $order['quantity'];
                    $total_amount += $net_amount;


                    $datas = [
                        'item_code' => $product['product_code'],
                        'product_name' => $product['product_name'],
                        'net_amount' => $net_amount,
                        'pack_size' => $pack_size['pack_size'],
                        'shade_name' => $product['shade_name'],
                        'status' => $order['status'] == 2 ? 'ACCEPTED' : 'PENDING',
                        'quantity' => $order['quantity']
                    ];
                    $live_order[] = $datas;
                }
                $final_data = [
                    'products' => $live_order
                ];

                //dd($order_date['created_at']->toDateString());
                $data = [

                    'no_of_product' => $order_count,
                    'invoice_date' => $order_date['created_at']->toDateString(),
                    'total_quantity' => $total_quantity,
                    'status' => $order_date['status'] == 2 ? 'ACCEPTED' : 'PENDING',
                    'invoice_amount' => $total_amount
                ];
            } else {
                $data = [
                    'error' => 'USER TOKEN NOT MATCHED.',
                ];
                return response()->json(['data' => $data], 200);
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }

        return response()->json(['data' => ['order_info' => $data, 'details' => $live_order]], 200);
    }

    public function get_account_statement(Request $request)
    {

        $user_token = $request->header('USER-TOKEN');
        $date = $request->date;

        if ($user_token) {

            $dealer = DealerUser::where('user_token', $user_token)
                ->select(
                    'id',
                    'password',
                    'status',
                    'code',
                    'name',
                    'phone'
                )->get()->last();
            //dd($dealer);
            if ($dealer) {
                $account_statement = DB::table('account_statements')->where('date', $date)->where('dealer_id', $dealer['id'])->select(
                    'id',
                    'invoice_id',
                    'invoice_amount',
                    'paid_amount',
                    'remaining_amount',
                    'description',
                    'date'
                )->get()->toArray();
                if (!$account_statement) {
                    $data = [
                        'message' => 'NO ACCOUNT STATEMENT ADDED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
                $live_order = [];
                //$total_quantity = 0;
                foreach ($account_statement as $account) {
                    //dd($account->invoice_id);
                    $live = [
                        'order_id' => $account->invoice_id,
                        'order_date' => $account->date,
                        'invoice_amount' => round($account->invoice_amount, 2),
                        'paid_amount' => round($account->paid_amount, 2),
                        'remaining_amount' => round($account->remaining_amount, 2),
                        'description' => $account->description,
                    ];
                    $live_order[] = $live;
                }
            } else {
                $data = [
                    'error' => 'USER TOKEN NOT MATCHED.',
                ];
                return response()->json(['data' => $data], 200);
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }

        return response()->json(['data' => $live_order], 200);
    }

    public function get_credit_note(Request $request)
    {

        $user_token = $request->header('USER-TOKEN');

        if ($user_token) {

            $dealer = DealerUser::where('user_token', $user_token)
                ->select(
                    'id',
                    'password',
                    'status',
                    'code',
                    'name',
                    'phone'
                )->get()->last();
            //dd($dealer);
            if ($dealer) {


                $live_order = [];
                $live = [

                    'name' => 'XYZ TRADERS',
                    'cash_back' => '5000',
                    'shop_boy_incentive' => '2000',
                    'product_scheme' => '3000',
                    'additional_commission' => '0',
                    'foreign_tour' => '50000',
                    'toc' => '2000',
                    'exclusivity' => '1000',
                ];
                $live_order[] = $live;
            } else {
                $data = [
                    'error' => 'USER TOKEN NOT MATCHED.',
                ];
                return response()->json(['data' => $data], 200);
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }

        return response()->json(['data' => $live], 200);
    }

    public function get_credit_note_history_by_date_range(Request $request)
    {

        $user_token = $request->header('USER-TOKEN');
        $starts = $request->start_date;
        $ends = $request->end_date;
        $now = Carbon::now();
        $month = $now->year . '-' . $now->month;
        $start = Carbon::parse($starts)->toDateString();
        $end = Carbon::parse($ends)->toDateString();

        $start_date = $start;
        $end_date = $end;

        if ($user_token) {

            $dealer = DealerUser::where('user_token', $user_token)
                ->select(
                    'id',
                    'password',
                    'status',
                    'code',
                    'name',
                    'phone'
                )->get()->last();
            //dd($dealer);
            if ($dealer) {

                $feedback_list = DB::table('credit_notes')->whereBetween('date', [$start_date, $end_date])->where('dealer_id', $dealer['id'])->orderBy('date', 'desc')->select('date')->distinct('date')->get()->toArray();
                //dd($feedback_list);
                //dd($feedback_list);
                if (!$feedback_list) {
                    $data = [
                        'message' => 'NO CREDIT NOTE ADDED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
                $allData = [];
                //$total_quantity = 0;
                foreach ($feedback_list as $feedback) {

                    //dd($feedback->date);
                    $total_amount = DB::table('credit_notes')->where('date', $feedback->date)->where('dealer_id', $dealer['id'])->sum('amount');
                    $no_of_credit = DB::table('credit_notes')->where('date', $feedback->date)->where('dealer_id', $dealer['id'])->count();
                    $entry_date = Carbon::parse($feedback->date);
                    //  dd($no_of_credit);
                    $dashboard = [
                        'date' => $entry_date->todateString(),
                        'day' => $entry_date->format('l'),
                        'total_amount' => $total_amount,
                        'no_of_credit' => $no_of_credit,
                    ];

                    $allData[] = $dashboard;
                }
            } else {
                $data = [
                    'error' => 'USER TOKEN NOT MATCHED.',
                ];
                return response()->json(['data' => $data], 200);
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }

        return response()->json(['data' => $allData], 200);
    }

    public function get_order_history_by_date_range(Request $request)
    {

        $user_token = $request->header('USER-TOKEN');
        $starts = $request->start_date;
        $ends = $request->end_date;
        $now = Carbon::now();
        $month = $now->year . '-' . $now->month;
        $start = Carbon::parse($starts)->toDateString();
        $end = Carbon::parse($ends)->toDateString();

        $start_date = $start;
        $end_date = $end;

        if ($user_token) {

            $dealer = DealerUser::where('user_token', $user_token)
                ->select(
                    'id',
                    'password',
                    'status',
                    'code',
                    'name',
                    'phone'
                )->get()->last();
            //dd($dealer);
            if ($dealer) {

                $feedback_list = PlaceOrder::where('dealer_id', $dealer['id'])->whereBetween('created_at', [$start_date, $end_date])->orderBy('created_at', 'desc')->select(
                    'code',
                    'created_at',
                    'status'
                )->distinct('code')->get()->toArray();
                //dd($feedback_list);
                if (!$feedback_list) {
                    $data = [
                        'message' => 'NO PLACE ORDER ADDED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
                $allData = [];
                //$total_quantity = 0;
                foreach ($feedback_list as $feedback) {

                    //dd($thana);
                    $feedback_l = PlaceOrder::where('code', $feedback['code'])
                        ->count('code');
                    $total_quantity = PlaceOrder::where('code', $feedback['code'])
                        ->sum('quantity');

                    $dashboard = [
                        'no_of_product' => $feedback_l,
                        'order_date' => $feedback['created_at'],
                        'order_id' => $feedback['code'],
                        'status' => $feedback['status'] == 1 ? 'PENDING' : 'ACCEPTED',
                        'quantity' => $total_quantity,
                    ];

                    $allData[] = $dashboard;
                }
            } else {
                $data = [
                    'error' => 'USER TOKEN NOT MATCHED.',
                ];
                return response()->json(['data' => $data], 200);
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }

        return response()->json(['data' => $allData], 200);
    }

    public function get_account_statement_history_by_date_range(Request $request)
    {

        $user_token = $request->header('USER-TOKEN');
        $starts = $request->start_date;
        $ends = $request->end_date;
        $now = Carbon::now();
        $month = $now->year . '-' . $now->month;
        $start = Carbon::parse($starts)->toDateString();
        $end = Carbon::parse($ends)->toDateString();

        $start_date = $start;
        $end_date = $end;

        if ($user_token) {

            $dealer = DealerUser::where('user_token', $user_token)
                ->select(
                    'id',
                    'password',
                    'status',
                    'code',
                    'name',
                    'phone'
                )->get()->last();
            //dd($dealer);
            if ($dealer) {

                $feedback_list = DB::table('account_statements')->whereBetween('date', [$start_date, $end_date])->where('dealer_id', $dealer['id'])->orderBy('date', 'desc')->select('date')->distinct('date')->get()->toArray();
                //dd($feedback_list);
                //dd($feedback_list);
                if (!$feedback_list) {
                    $data = [
                        'message' => 'NO ACCOUNT STATEMENT ADDED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
                $allData = [];
                //$total_quantity = 0;
                foreach ($feedback_list as $feedback) {

                    //dd($feedback->date);
                    $total_amount = DB::table('account_statements')->where('dealer_id', $dealer['id'])->where('date', $feedback->date)->sum('invoice_amount');
                    $no_of_invoice = DB::table('account_statements')->where('dealer_id', $dealer['id'])->where('date', $feedback->date)->count();
                    $entry_date = Carbon::parse($feedback->date);
                    //  dd($no_of_credit);
                    $dashboard = [
                        'date' => $entry_date->todateString(),
                        'day' => $entry_date->format('l'),
                        'total_invoice_amount' => round($total_amount, 2),
                        'no_of_statement' => $no_of_invoice,
                    ];

                    $allData[] = $dashboard;
                }
            } else {
                $data = [
                    'error' => 'USER TOKEN NOT MATCHED.',
                ];
                return response()->json(['data' => $data], 200);
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }

        return response()->json(['data' => $allData], 200);
    }

    public function get_invoice_history_by_date_range(Request $request)
    {

        $user_token = $request->header('USER-TOKEN');
        $starts = $request->start_date;
        $ends = $request->end_date;
        $now = Carbon::now();
        $month = $now->year . '-' . $now->month;
        $start = Carbon::parse($starts)->toDateString();
        $end = Carbon::parse($ends)->toDateString();

        $start_date = $start;
        $end_date = $end;

        if ($user_token) {

            $dealer = DealerUser::where('user_token', $user_token)
                ->select(
                    'id',
                    'password',
                    'status',
                    'code',
                    'name',
                    'phone'
                )->get()->last();
            //dd($dealer);
            if ($dealer) {

                //dd($feedback_list);
                $account = DB::table('invoices')->whereBetween('date', [$start_date, $end_date])->where('dealer_id', $dealer['id'])->orderBy('created_at', 'desc')->select('date', 'invoice')->distinct('invoice')->get()->toArray();
                //dd($account);
                if (!$account) {
                    $data = [
                        'message' => 'NO INVOICE ADDED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
                $live_order = [];

                foreach ($account as $acc) {

                    $feedback_l = DB::table('invoices')->where('invoice', $acc->invoice)
                        ->count('invoice');
                    $quantity = DB::table('invoices')->where('invoice', $acc->invoice)
                        ->sum('quantity');

                    $points = DB::table('invoices')->where('invoice', $acc->invoice)
                        ->sum('point');

                    $live = [

                        'invoice_id' => $acc->invoice,
                        'invoice_date' => $acc->date,
                        'quantity' => $quantity,
                        'point' => $points,
                        'no_of_product' => $feedback_l,
                    ];
                    $live_order[] = $live;
                }
            } else {
                $data = [
                    'error' => 'USER TOKEN NOT MATCHED.',
                ];
                return response()->json(['data' => $data], 200);
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }

        return response()->json(['data' => $live_order], 200);
    }

    public function get_invoice(Request $request)
    {
        set_time_limit(60000);
        ini_set("pcre.backtrack_limit", "100000000");
        $user_token = $request->header('USER-TOKEN');

        if ($user_token) {

            $dealer = DealerUser::where('user_token', $user_token)
                ->select(
                    'id',
                    'password',
                    'status',
                    'code',
                    'name',
                    'phone'
                )->get()->last();
            //dd($dealer);
            if ($dealer) {
                $current_year = Carbon::now()->year;
                $query = DB::raw('SELECT invoice AS invoice_id, SUM(quantity) as quantity, SUM(point) as point, date as invoice_date, COUNT(invoice) as no_of_product  FROM `invoices` WHERE `dealer_id`= ' . $dealer->id . ' AND YEAR(date)=2023 GROUP BY invoice');
                $fetchedData = DB::select($query);
                $account = DB::table('invoices')->where('dealer_id', $dealer['id'])->orderBy('created_at', 'desc')->select('date', 'invoice')->distinct('invoice')->take(5)->get()->toArray();
                //dd($account);
                if (!$account) {
                    $data = [
                        'message' => 'NO INVOICE ADDED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
                $live_order = $fetchedData;

                //                foreach ($account as $key=>$acc) {
                //                    if ($acc && $acc->invoice){
                //                        info('invoice loop '.$key);
                //                        $feedback_l = DB::table('invoices')->where('invoice',$acc->invoice )
                //                            ->count('invoice');
                //                        $quantity = DB::table('invoices')->where('invoice',$acc->invoice )
                //                            ->sum('quantity');
                //
                //                        $points = DB::table('invoices')->where('invoice',$acc->invoice )
                //                            ->sum('point');
                //
                //                        $live = [
                //
                //                            'invoice_id' => $acc->invoice,
                //                            'invoice_date' => $acc->date,
                //                            'quantity' => $quantity,
                //                            'point' => $points,
                //                            'no_of_product' => $feedback_l,
                //                        ];
                //                        $live_order[] = $live;
                //                        info('pushed into variable');
                //                    }
                //                }
                //                info('loop finished');
                return response()->json(['data' => $live_order], 200);
            } else {
                $data = [
                    'error' => 'USER TOKEN NOT MATCHED.',
                ];
                return response()->json(['data' => $data], 200);
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }

        return response()->json(['data' => $live_order], 200);
    }

    public function get_order_history(Request $request)
    {

        $user_token = $request->header('USER-TOKEN');

        $now = Carbon::now();
        $month = $now->year . '-' . $now->month;
        $start = Carbon::parse($month)->startOfMonth()->todateString() . ' 00:00:00';
        $end = Carbon::parse($month)->today()->todateString() . ' 23:59:59';

        //dd($start.'-'.$end);
        if ($user_token) {

            $dealer = DealerUser::where('user_token', $user_token)
                ->select(
                    'id',
                    'password',
                    'status',
                    'code',
                    'name',
                    'phone'
                )->get()->last();
            //dd($dealer);
            if ($dealer) {

                $feedback_list = PlaceOrder::where('dealer_id', $dealer['id'])->whereBetween('created_at', [$start, $end])->orderBy('created_at', 'desc')->select(
                    'code',
                    'created_at',
                    'status'
                )->distinct('code')->get()->toArray();
                //dd($feedback_list);
                if (!$feedback_list) {
                    $data = [
                        'message' => 'NO PLACE ORDER ADDED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
                $allData = [];
                //$total_quantity = 0;
                foreach ($feedback_list as $feedback) {

                    //dd($thana);
                    $feedback_l = PlaceOrder::where('code', $feedback['code'])
                        ->count('code');
                    $total_quantity = PlaceOrder::where('code', $feedback['code'])
                        ->sum('quantity');

                    $dashboard = [
                        'no_of_product' => $feedback_l,
                        'order_date' => $feedback['created_at'],
                        'order_id' => $feedback['code'],
                        'status' => $feedback['status'] == 1 ? 'PENDING' : 'ACCEPTED',
                        'quantity' => $total_quantity,
                    ];

                    $allData[] = $dashboard;
                }
            } else {
                $data = [
                    'error' => 'USER TOKEN NOT MATCHED.',
                ];
                return response()->json(['data' => $data], 200);
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }

        return response()->json(['data' => $allData], 200);
    }

    public function get_order_details(Request $request)
    {

        $user_token = $request->header('USER-TOKEN');
        $code = $request->code;

        if ($user_token) {

            $dealer = DealerUser::where('user_token', $user_token)
                ->select(
                    'id',
                    'password',
                    'status',
                    'code',
                    'name',
                    'phone'
                )->get()->last();
            //dd($dealer);
            if ($dealer) {
                $orders = PlaceOrder::where('code', $code)->orderBy('created_at', 'desc')->select(
                    'id',
                    'code',
                    'dealer_id',
                    'product_id',
                    'quantity',
                    'status',
                    'created_at'
                )->get()->toArray();
                $order_count = PlaceOrder::where('code', $code)->orderBy('created_at', 'desc')->select('id')->count();
                $order_date = PlaceOrder::where('code', $code)->orderBy('created_at', 'desc')->select(
                    'id',
                    'code',
                    'dealer_id',
                    'product_id',
                    'status',
                    'quantity',
                    'created_at'
                )->get()->first();

                $live_order = [];
                $total_quantity = 0;
                $total_amount = 0;
                foreach ($orders as $order) {
                    $product = Product::where('id', $order['product_id'])->select('id', 'pack_size_id', 'shade_code', 'product_code', 'product_name', 'shade_name', 'price')->get()->first();

                    $pack_size = Pack::where('id', $product['pack_size_id'])->select('id', 'subgroup_id', 'pack_name', 'pack_size', 'size_code', 'uom')->get()->first();
                    $subgroup = SubGroup::where('id', $pack_size['subgroup_id'])->select('id', 'subgroup_code', 'subgroup_name')->get()->first();
                    $net_amount = $product['price'] * $order['quantity'];
                    $total_quantity += $order['quantity'];
                    $total_amount += $net_amount;


                    $datas = [
                        'item_code' => $product['product_code'],
                        'product_name' => $product['product_name'],
                        'net_amount' => $net_amount,
                        'pack_size' => $pack_size['pack_size'],
                        'shade_name' => $product['shade_name'],
                        'status' => $order['status'] == 1 ? 'PENDING' : 'ACCEPTED',
                        'quantity' => $order['quantity']
                    ];
                    $live_order[] = $datas;
                }
                $final_data = [
                    'products' => $live_order
                ];

                //dd($order_date['created_at']->toDateString());
                $data = [

                    'no_of_product' => $order_count,
                    'order_date' => $order_date['created_at']->toDateString(),
                    'total_quantity' => $total_quantity,
                    'status' => $order_date['status'] == 1 ? 'PENDING' : 'ACCEPTED',
                    'order_amount' => $total_amount
                ];
            } else {
                $data = [
                    'error' => 'USER TOKEN NOT MATCHED.',
                ];
                return response()->json(['data' => $data], 200);
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }

        return response()->json(['data' => ['order_info' => $data, 'details' => $live_order]], 200);
    }

    public function get_invoice_details(Request $request)
    {

        $user_token = $request->header('USER-TOKEN');
        $invoice = $request->code;

        if ($user_token) {

            $dealer = DealerUser::where('user_token', $user_token)
                ->select(
                    'id',
                    'password',
                    'status',
                    'code',
                    'name',
                    'phone'
                )->get()->last();
            //dd($dealer);
            if ($dealer) {
                //$account = DB::table('invoices')->where('dealer_id',$dealer['id'])->orderBy('created_at','desc')->select('date','invoice')->distinct('invoice')->get()->toArray();
                $orders = DB::table('invoices')->where('invoice', $invoice)->orderBy('created_at', 'desc')->select(
                    'id',
                    'date',
                    'invoice',
                    'product_code',
                    'product_name',
                    'point',
                    'pack_size',
                    'shade_name',
                    'quantity',
                    'net_amount',
                    'created_at'
                )->get()->toArray();
                //dd($orders[0]->date);

                $order_count = DB::table('invoices')->where('invoice', $invoice)->orderBy('created_at', 'desc')->select('id')->count();

                $total_quantity = DB::table('invoices')->where('invoice', $invoice)->sum('quantity');
                $total_point = DB::table('invoices')->where('invoice', $invoice)->sum('point');
                $invoice_amount = DB::table('invoices')->where('invoice', $invoice)->sum('net_amount');
                //dd(intval($invoice_amount));
                $data = [

                    'invoice_date' => $orders[0]->date,
                    'no_of_product' => $order_count,
                    'total_quantity' => round($total_quantity, 2),
                    'total_point' => round($total_point, 2),
                    'invoice_amount' => round($invoice_amount, 2),
                ];


                $live_order = [];
                foreach ($orders as $order) {
                    $product = DB::table('invoices')->where('id', $order->id)->orderBy('created_at', 'desc')->select(
                        'id',
                        'date',
                        'invoice',
                        'product_code',
                        'product_name',
                        'point',
                        'pack_size',
                        'shade_name',
                        'quantity',
                        'net_amount',
                        'created_at'
                    )->get()->last();
                    //dd($product);
                    $live = [

                        'item_code' => $product->product_code,
                        'product_name' => $product->product_name,
                        'pack_size' => $product->pack_size,
                        'shade_name' => $product->shade_name,
                        'net_amount' => round($product->net_amount, 2),
                        'point' => round($product->point, 2),
                        'status' => 'ACCEPTED',
                        'quantity' => round($product->quantity, 2),
                    ];
                    $live_order[] = $live;
                }
            } else {
                $data = [
                    'error' => 'USER TOKEN NOT MATCHED.',
                ];
                return response()->json(['data' => $data], 200);
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }

        return response()->json(['data' => ['order_info' => $data, 'details' => $live_order]], 200);
    }

    public function get_cash_back(Request $request)
    {

        $user_token = $request->header('USER-TOKEN');
        $date = $request->date;

        if ($user_token) {

            $dealer = DealerUser::where('user_token', $user_token)
                ->select(
                    'id',
                    'password',
                    'status',
                    'code',
                    'name',
                    'phone'
                )->get()->last();
            //dd($dealer);
            if ($dealer) {
                $credit_note = DB::table('credit_notes')->where('dealer_id', $dealer['id'])->where('date', $date)->select(
                    'id',
                    'date',
                    'credit_no',
                    'type',
                    'amount'
                )->get()->toArray();
                //dd($credit_note);
                if (!$credit_note) {
                    $data = [
                        'message' => 'NO CREDIT NOTE ADDED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }

                $live_order = [];
                //$total_quantity = 0;
                foreach ($credit_note as $credit) {
                    $datas = [

                        'credit_date' => $credit->date,
                        'credit_no' => $credit->credit_no,
                        'type' => $credit->type,
                        'amount' => $credit->amount,
                    ];
                    $live_order[] = $datas;
                }
            } else {
                $data = [
                    'error' => 'USER TOKEN NOT MATCHED.',
                ];
                return response()->json(['data' => $data], 200);
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }

        return response()->json(['data' => $live_order], 200);
    }

    public function get_shopboy_incentive(Request $request)
    {

        $user_token = $request->header('USER-TOKEN');

        if ($user_token) {

            $dealer = DealerUser::where('user_token', $user_token)
                ->select(
                    'id',
                    'password',
                    'status',
                    'code',
                    'name',
                    'phone'
                )->get()->last();
            //dd($dealer);
            if ($dealer) {

                $data = [

                    'credit_date' => '01/01/2021',
                    'credit_no' => 'JV260500',
                    'type' => 'Cash Back',
                    'amount' => '40000',
                ];
            } else {
                $data = [
                    'error' => 'USER TOKEN NOT MATCHED.',
                ];
                return response()->json(['data' => $data], 200);
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }

        return response()->json(['data' => $data], 200);
    }

    public function get_product_scheme(Request $request)
    {

        $user_token = $request->header('USER-TOKEN');

        if ($user_token) {

            $dealer = DealerUser::where('user_token', $user_token)
                ->select(
                    'id',
                    'password',
                    'status',
                    'code',
                    'name',
                    'phone'
                )->get()->last();
            //dd($dealer);
            if ($dealer) {

                $data = [

                    'credit_date' => '01/01/2021',
                    'credit_no' => 'JV260500',
                    'type' => 'Cash Back',
                    'amount' => '40000',
                ];
            } else {
                $data = [
                    'error' => 'USER TOKEN NOT MATCHED.',
                ];
                return response()->json(['data' => $data], 200);
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }

        return response()->json(['data' => $data], 200);
    }

    public function get_additional_commission(Request $request)
    {

        $user_token = $request->header('USER-TOKEN');

        if ($user_token) {

            $dealer = DealerUser::where('user_token', $user_token)
                ->select(
                    'id',
                    'password',
                    'status',
                    'code',
                    'name',
                    'phone'
                )->get()->last();
            //dd($dealer);
            if ($dealer) {

                $data = [

                    'credit_date' => '01/01/2021',
                    'credit_no' => 'JV260500',
                    'type' => 'Cash Back',
                    'amount' => '40000',
                ];
            } else {
                $data = [
                    'error' => 'USER TOKEN NOT MATCHED.',
                ];
                return response()->json(['data' => $data], 200);
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }

        return response()->json(['data' => $data], 200);
    }

    public function get_foreign_tour(Request $request)
    {

        $user_token = $request->header('USER-TOKEN');

        if ($user_token) {

            $dealer = DealerUser::where('user_token', $user_token)
                ->select(
                    'id',
                    'password',
                    'status',
                    'code',
                    'name',
                    'phone'
                )->get()->last();
            //dd($dealer);
            if ($dealer) {

                $data = [

                    'credit_date' => '01/01/2021',
                    'credit_no' => 'JV260500',
                    'type' => 'Cash Back',
                    'amount' => '40000',
                ];
            } else {
                $data = [
                    'error' => 'USER TOKEN NOT MATCHED.',
                ];
                return response()->json(['data' => $data], 200);
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }

        return response()->json(['data' => $data], 200);
    }

    public function get_toc(Request $request)
    {

        $user_token = $request->header('USER-TOKEN');

        if ($user_token) {

            $dealer = DealerUser::where('user_token', $user_token)
                ->select(
                    'id',
                    'password',
                    'status',
                    'code',
                    'name',
                    'phone'
                )->get()->last();
            //dd($dealer);
            if ($dealer) {

                $data = [

                    'credit_date' => '01/01/2021',
                    'credit_no' => 'JV260500',
                    'type' => 'Cash Back',
                    'amount' => '40000',
                ];
            } else {
                $data = [
                    'error' => 'USER TOKEN NOT MATCHED.',
                ];
                return response()->json(['data' => $data], 200);
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }

        return response()->json(['data' => $data], 200);
    }

    public function get_exclusivity(Request $request)
    {

        $user_token = $request->header('USER-TOKEN');

        if ($user_token) {

            $dealer = DealerUser::where('user_token', $user_token)
                ->select(
                    'id',
                    'password',
                    'status',
                    'code',
                    'name',
                    'phone'
                )->get()->last();
            //dd($dealer);
            if ($dealer) {

                $data = [

                    'credit_date' => '01/01/2021',
                    'credit_no' => 'JV260500',
                    'type' => 'Cash Back',
                    'amount' => '40000',
                ];
            } else {
                $data = [
                    'error' => 'USER TOKEN NOT MATCHED.',
                ];
                return response()->json(['data' => $data], 200);
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }

        return response()->json(['data' => $data], 200);
    }

    public function initial_info_for_product(Request $request)
    {

        $app_identifier = $request->app_identifier;
        $user_token = $request->header('USER-TOKEN');
        if (!$user_token) {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }

        $type = $request->type;

        if (!$type) {
            if ($app_identifier == 'com.ets.elitepaint.dealer') {
                $dealer = DealerUser::where('user_token', $user_token)
                    ->select(
                        'id',
                        'password',
                        'status',
                        'code',
                        'name',
                        'phone'
                    )->get()->last();

                if (!$dealer) {
                    $data = [
                        'error' => 'USER TOKEN NOT MATCHED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
                $painter_list = PainterUser::select('id', 'code', 'name', 'phone')->where('status', 1)->where('dealer_id', $dealer->id)->where('disable', 1)->where('soft_delete', 1)->get();
                $product_list = \App\Classes\Stock::dealer_wise_basegroup_stocks($dealer->id);
                // $product_list = $this->initialInfoProductForDealer($request);


                $data = [
                    'response' => 'painters and products',

                    'subgroups' => $product_list,
                    'painters' => $painter_list
                ];
            } else {
                $dealer_list = DealerUser::select('id', 'code', 'name', 'phone')->where('status', 1)->where('disable', 1)->where('soft_delete', 1)->get();
                $product_list = $this->initialInfoProductForPainter($request);

                $data = [
                    'response' => 'dealers and products',
                    'dealers' => $dealer_list,
                    'subgroups' => $product_list
                ];
            }
            return response()->json(['data' => $data], 200);
        }

        // Type Exist
        if ($type == 'ORDER') {
            if ($app_identifier == 'com.ets.elitepaint.dealer') {
                $dealer = DealerUser::where('user_token', $user_token)
                    ->select(
                        'id',
                        'password',
                        'status',
                        'code',
                        'name',
                        'phone'
                    )->get()->last();

                if (!$dealer) {
                    $data = [
                        'error' => 'USER TOKEN NOT MATCHED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
                //                $product_list = $this->initialInfoProductForDealer($request);
                //                $product_list = \App\Classes\Stock::initialInfoProductForDealer($dealer);
                //                info(count($product_list));
                //                $product_list = \App\Classes\Stock::dealer_wise_basegroup_stocks($dealer->id);
                $product_list = \App\Classes\Stock::all_basegroup_stocks();
                //TODO : do thias
            }
            $data = [
                'response' => 'products',
                'subgroups' => $product_list
            ];
            return response()->json(['data' => $data], 200);
        }
        if ($type == 'VOLUME') {
            if ($app_identifier == 'com.ets.elitepaint.dealer') {
                $dealer = DealerUser::where('user_token', $user_token)
                    ->select(
                        'id',
                        'password',
                        'status',
                        'code',
                        'name',
                        'phone'
                    )->get()->last();

                if (!$dealer) {
                    $data = [
                        'error' => 'USER TOKEN NOT MATCHED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
                $painter_list = PainterUser::select('id', 'code', 'name', 'phone')->where('status', 1)->where('disable', 1)->where('soft_delete', 1)->get();
                // $product_list = $this->initialInfoProductForDealerVolumetest($request);
                $product_list = \App\Classes\Stock::dealer_wise_basegroup_stocks($dealer->id);
                $data = [
                    'response' => 'products and painters',
                    'subgroups' => $product_list,
                    'painters' => $painter_list,

                ];
            }
            return response()->json(['data' => $data], 200);
        } else if ($type == 'PURCHASE') {
            if ($app_identifier == 'com.ets.elitepaint.painter') {
                $dealer = painterUser::where('user_token', $user_token)
                    ->select(
                        'id',
                        'password',
                        'status',
                        'code',
                        'name',
                        'phone'
                    )->get()->last();

                if (!$dealer) {
                    $data = [
                        'error' => 'USER TOKEN NOT MATCHED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
                $dealer_list = DealerUser::select('id', 'code', 'name', 'phone')->where('status', 1)->where('disable', 1)->where('soft_delete', 1)->get();
                $product_list = $this->initialInfoProductForPainter($request);
                $data = [
                    'response' => 'products and dealers',
                    'dealers' => $dealer_list,
                    'subgroups' => $product_list
                ];
            }
            return response()->json(['data' => $data], 200);
        }
    }

    private function initialInfoProductForDealerVolumetest(Request $request)
    {
        $user_token = $request->header('USER-TOKEN');

        $dealer = DealerUser::where('user_token', $user_token)->select('id')->get()->last();
        $product_list = [];
        $date = '2022-06-15';
        $datessss = Carbon::parse($date)->toDateString();
        if ($dealer) {
            $productssss = DB::table('invoices')->where('dealer_id', $dealer['id'])
                ->select('product_name')->distinct('product_name')->get()->toArray();
            //dd($productssss);
            foreach ($productssss as $prod) {
                $basegrup_list = BaseGroup::select('id', 'basegroup_code', 'basegroup_name', 'delivery_percentage')->where('basegroup_name', $prod->product_name)->get()->last();
                if ($basegrup_list) {
                    $subgroup_list = SubGroup::select('id', 'subgroup_code', 'subgroup_name')
                        ->where('basegroup_id', $basegrup_list['id'])
                        ->where('soft_delete', 1)->get()->toArray();
                    //dd($subgroup_list);
                    if ($subgroup_list) {
                        //dd('a');
                        $total_ltr = 0;
                        $total_ltr_sub = 0;
                        $total_ltr_15_june = 0;
                        $volumes = 0;
                        foreach ($subgroup_list as $subgroup) {
                            $invoices = DB::table('invoices')->select('id', 'pack_size', 'quantity', 'date')->where('dealer_id', $dealer['id'])
                                ->Where('product_code', 'LIKE', '%' . $subgroup['subgroup_code'] . '%')->get()->toArray();
                            //  dd($invoices);


                            foreach ($invoices as $invoice) {

                                $inv_date = Carbon::parse($invoice->date)->toDateString();
                                if ($inv_date < $datessss) {
                                    $total_ltr_sub += $invoice->quantity * $invoice->pack_size;
                                } else {
                                    $total_ltr_15_june += $invoice->quantity * $invoice->pack_size;
                                }
                            }
                            //dd($total_ltr_sub);
                            $total_ltr += $total_ltr_sub + $total_ltr_15_june;
                            $volumes += VolumeTranfer::where('dealer_id', $dealer['id'])->where('product_id', $subgroup['id'])
                                ->where('status', '!=', 2)->where('soft_delete', 1)->sum('quantity');
                        }
                    } else {
                        //dd('b');
                        $invoices = DB::table('invoices')->select('id', 'pack_size', 'quantity', 'date')->where('dealer_id', $dealer['id'])
                            ->Where('product_code', 'LIKE', '%' . $basegrup_list['basegroup_code'] . '%')->get()->toArray();
                        //  dd($invoices);
                        $total_ltr = 0;
                        $volumes = 0;
                        $total_ltr_sub = 0;
                        $total_ltr_15_june = 0;
                        foreach ($invoices as $invoice) {

                            $inv_date = Carbon::parse($invoice->date)->toDateString();
                            if ($inv_date < $datessss) {
                                $total_ltr_sub += $invoice->quantity * $invoice->pack_size;
                            } else {
                                $total_ltr_15_june += $invoice->quantity * $invoice->pack_size;
                            }
                        }
                        $total_ltr += $total_ltr_sub + $total_ltr_15_june;
                        $volumes += VolumeTranfer::where('dealer_id', $dealer['id'])->where('product_id', $basegrup_list['id'])
                            ->where('status', '!=', 2)->where('soft_delete', 1)->sum('quantity');
                    }


                    //dd($total_ltr);

                    if (!$basegrup_list['delivery_percentage']) {
                        //dd('a');
                        $percentage = 0;
                    } else {
                        //dd($total_ltr_15_june);
                        $p = $basegrup_list['delivery_percentage'] * $total_ltr_15_june;

                        $percentage = $p / 100;
                    }
                    //dd($total_ltr_sub);
                    $ss = $percentage + $total_ltr_sub;
                    $s = $ss - $volumes;
                    if ($total_ltr > 0) {
                        $data = [
                            'id' => $basegrup_list['id'],
                            'subgroup_name' => $basegrup_list['basegroup_name'],
                            'stock' => round($s, 2),
                        ];
                        $product_list[] = $data;
                    }
                } else {
                    $basegrup_list = DB::table('subgroups')->select('id', 'subgroup_code', 'subgroup_name', 'basegroup_id')->where('subgroup_name', $prod->product_name)->get()->last();

                    if ($basegrup_list) {
                        $base = DB::table('basegroups')->select('id', 'basegroup_code', 'basegroup_name', 'delivery_percentage')->where('id', $basegrup_list->basegroup_id)->get()->last();
                        $subgroup_list = SubGroup::select('id', 'subgroup_code', 'subgroup_name')
                            ->where('basegroup_id', $basegrup_list->basegroup_id)
                            ->where('soft_delete', 1)->get()->toArray();
                        //dd($subgroup_list);
                        if ($subgroup_list) {
                            //dd('a');
                            $total_ltr = 0;
                            $total_ltr_sub = 0;
                            $total_ltr_15_june = 0;
                            $volumes = 0;
                            foreach ($subgroup_list as $subgroup) {
                                $invoices = DB::table('invoices')->select('id', 'pack_size', 'quantity', 'date')->where('dealer_id', $dealer['id'])
                                    ->Where('product_code', 'LIKE', '%' . $subgroup['subgroup_code'] . '%')->get()->toArray();
                                //  dd($invoices);


                                foreach ($invoices as $invoice) {

                                    $inv_date = Carbon::parse($invoice->date)->toDateString();
                                    if ($inv_date < $datessss) {
                                        $total_ltr_sub += $invoice->quantity * $invoice->pack_size;
                                    } else {
                                        $total_ltr_15_june += $invoice->quantity * $invoice->pack_size;
                                    }
                                }
                                //dd($total_ltr_sub);
                                $total_ltr += $total_ltr_sub + $total_ltr_15_june;
                                $volumes += VolumeTranfer::where('dealer_id', $dealer['id'])->where('product_id', $subgroup['id'])
                                    ->where('status', '!=', 2)->where('soft_delete', 1)->sum('quantity');
                            }
                        } else {
                            //dd('b');
                            $invoices = DB::table('invoices')->select('id', 'pack_size', 'quantity', 'date')->where('dealer_id', $dealer['id'])
                                ->Where('product_code', 'LIKE', '%' . $basegrup_list['basegroup_code'] . '%')->get()->toArray();
                            //  dd($invoices);
                            $total_ltr = 0;
                            $volumes = 0;
                            $total_ltr_sub = 0;
                            $total_ltr_15_june = 0;
                            foreach ($invoices as $invoice) {

                                $inv_date = Carbon::parse($invoice->date)->toDateString();
                                if ($inv_date < $datessss) {
                                    $total_ltr_sub += $invoice->quantity * $invoice->pack_size;
                                } else {
                                    $total_ltr_15_june += $invoice->quantity * $invoice->pack_size;
                                }
                            }
                            $total_ltr += $total_ltr_sub + $total_ltr_15_june;
                            $volumes += VolumeTranfer::where('dealer_id', $dealer['id'])->where('product_id', $basegrup_list['id'])
                                ->where('status', '!=', 2)->where('soft_delete', 1)->sum('quantity');
                        }


                        //dd($total_ltr);

                        if (!$base->delivery_percentage) {
                            //dd('a');
                            $percentage = 0;
                        } else {
                            //dd($total_ltr_15_june);
                            $p = $base->delivery_percentage * $total_ltr_15_june;

                            $percentage = $p / 100;
                        }
                        //dd($total_ltr_sub);
                        $ss = $percentage + $total_ltr_sub;
                        $s = $ss - $volumes;
                        if ($total_ltr > 0) {
                            $data = [
                                'id' => $base->id,
                                'subgroup_name' => $base->basegroup_name,
                                'stock' => round($s, 2),
                            ];
                            $product_list[] = $data;
                        }
                    }
                }
            }
        }
        $p_list = array_unique($product_list, SORT_REGULAR);
        $array2 = array_values($p_list);
        //dd($p_list);
        return $array2;
    }

    private function initialInfoProductForDealerVolume(Request $request)
    {

        $user_token = $request->header('USER-TOKEN');

        $dealer = DealerUser::where('user_token', $user_token)->select('id')->get()->last();
        $product_list = [];
        $date = '2022-06-15';
        $datessss = Carbon::parse($date)->toDateString();
        if ($dealer) {
            $basegrup_list = BaseGroup::select('id', 'basegroup_code', 'basegroup_name', 'delivery_percentage')->where('soft_delete', 1)->get()->toArray();
            //  dd($basegrup_list);
            foreach ($basegrup_list as $basegrup) {
                $subgroup_list = SubGroup::select('id', 'subgroup_code', 'subgroup_name')
                    ->where('basegroup_id', $basegrup['id'])
                    ->where('soft_delete', 1)->get()->toArray();
                //dd($subgroup_list);
                if ($subgroup_list) {
                    //dd('a');
                    $total_ltr = 0;
                    $total_ltr_sub = 0;
                    $total_ltr_15_june = 0;
                    $volumes = 0;
                    foreach ($subgroup_list as $subgroup) {
                        $invoices = DB::table('invoices')->select('id', 'pack_size', 'quantity', 'date')->where('dealer_id', $dealer['id'])
                            ->Where('product_code', 'LIKE', '%' . $subgroup['subgroup_code'] . '%')->get()->toArray();
                        //  dd($invoices);


                        foreach ($invoices as $invoice) {

                            $inv_date = Carbon::parse($invoice->date)->toDateString();
                            if ($inv_date < $datessss) {
                                $total_ltr_sub += $invoice->quantity * $invoice->pack_size;
                            } else {
                                $total_ltr_15_june += $invoice->quantity * $invoice->pack_size;
                            }
                        }
                        //dd($total_ltr_sub);
                        $total_ltr += $total_ltr_sub + $total_ltr_15_june;
                        $volumes += VolumeTranfer::where('dealer_id', $dealer['id'])->where('product_id', $subgroup['id'])
                            ->where('status', '!=', 2)->where('soft_delete', 1)->sum('quantity');
                    }
                } else {
                    //dd('b');
                    $invoices = DB::table('invoices')->select('id', 'pack_size', 'quantity', 'date')->where('dealer_id', $dealer['id'])
                        ->Where('product_code', 'LIKE', '%' . $basegrup['basegroup_code'] . '%')->get()->toArray();
                    //  dd($invoices);
                    $total_ltr = 0;
                    $volumes = 0;
                    $total_ltr_sub = 0;
                    $total_ltr_15_june = 0;
                    foreach ($invoices as $invoice) {

                        $inv_date = Carbon::parse($invoice->date)->toDateString();
                        if ($inv_date < $datessss) {
                            $total_ltr_sub += $invoice->quantity * $invoice->pack_size;
                        } else {
                            $total_ltr_15_june += $invoice->quantity * $invoice->pack_size;
                        }
                    }
                    $total_ltr += $total_ltr_sub + $total_ltr_15_june;
                    $volumes += VolumeTranfer::where('dealer_id', $dealer['id'])->where('product_id', $basegrup['id'])
                        ->where('status', '!=', 2)->where('soft_delete', 1)->sum('quantity');
                }


                //dd($total_ltr);

                if (!$basegrup['delivery_percentage']) {
                    //dd('a');
                    $percentage = 0;
                } else {
                    //dd($total_ltr_15_june);
                    $p = $basegrup['delivery_percentage'] * $total_ltr_15_june;

                    $percentage = $p / 100;
                }
                //dd($total_ltr_sub);
                $ss = $percentage + $total_ltr_sub;
                $s = $ss - $volumes;
                if ($total_ltr > 0) {
                    $data = [
                        'id' => $basegrup['id'],
                        'subgroup_name' => $basegrup['basegroup_name'],
                        'stock' => round($s, 2),
                    ];
                    $product_list[] = $data;
                }
            }
        }
        return $product_list;
    }

    private function initialInfoProductForDealer(Request $request)
    {

        $user_token = $request->header('USER-TOKEN');

        $dealer = DealerUser::where('user_token', $user_token)->select('id')->get()->last();
        $product_list = [];

        if ($dealer) {
            $basegrup_list = BaseGroup::select('id', 'basegroup_code', 'basegroup_name')->where('soft_delete', 1)->get()->toArray();
            //  dd($basegrup_list);
            foreach ($basegrup_list as $basegrup) {
                $subgroup_list = SubGroup::select('id', 'subgroup_code', 'subgroup_name')
                    ->where('basegroup_id', $basegrup['id'])
                    ->where('soft_delete', 1)->get()->toArray();
                //dd($subgroup_list);
                if ($subgroup_list) {
                    //dd('a');
                    $total_ltr = 0;
                    $volumes = 0;
                    foreach ($subgroup_list as $subgroup) {
                        $invoices = DB::table('invoices')->select('id', 'pack_size', 'quantity')->where('dealer_id', $dealer['id'])
                            ->Where('product_code', 'LIKE', '%' . $subgroup['subgroup_code'] . '%')->get()->toArray();
                        //  dd($invoices);
                        $total_ltr_sub = 0;
                        foreach ($invoices as $invoice) {

                            $total_ltr_sub += $invoice->quantity * $invoice->pack_size;
                        }
                        //dd($total_ltr_sub);
                        $total_ltr += $total_ltr_sub;
                        $volumes += VolumeTranfer::where('dealer_id', $dealer['id'])->where('product_id', $subgroup['id'])
                            ->where('status', '!=', 2)->where('soft_delete', 1)->sum('quantity');
                    }
                } else {
                    //dd('b');
                    $invoices = DB::table('invoices')->select('id', 'pack_size', 'quantity')->where('dealer_id', $dealer['id'])
                        ->Where('product_code', 'LIKE', '%' . $basegrup['basegroup_code'] . '%')->get()->toArray();
                    //  dd($invoices);
                    $total_ltr = 0;
                    $volumes = 0;
                    foreach ($invoices as $invoice) {

                        $total_ltr += $invoice->quantity * $invoice->pack_size;
                    }
                    $volumes += VolumeTranfer::where('dealer_id', $dealer['id'])->where('product_id', $basegrup['id'])
                        ->where('status', '!=', 2)->where('soft_delete', 1)->sum('quantity');
                }


                //dd($total_ltr);
                $s = $total_ltr - $volumes;
                $data = [
                    'id' => $basegrup['id'],
                    'subgroup_name' => $basegrup['basegroup_name'],
                    'stock' => round($s, 2),
                ];
                $product_list[] = $data;
            }
        }
        return $product_list;
    }

    private function initialInfoProductForPainter(Request $request)
    {
        $user_token = $request->header('USER-TOKEN');

        $dealer = PainterUser::where('user_token', $user_token)->select('id')->get()->last();
        $product_list = [];
        if ($dealer) {
            $subgroup_list = BaseGroup::select('id', 'basegroup_code', 'basegroup_name')->where('soft_delete', 1)->get()->toArray();
            foreach ($subgroup_list as $subgroup) {
                $data = [
                    'id' => $subgroup['id'],
                    'subgroup_name' => $subgroup['basegroup_name'],
                    'stock' => '500',
                ];
                $product_list[] = $data;
            }
        }
        return $product_list;
    }

    public function get_pack_size(Request $request)
    {
        $subgroup_id = $request->subgroup_id;
        $divisions = Pack::where('subgroup_id', $subgroup_id)->where('soft_delete', 1)->orderBy('created_at', 'desc')->select('id', 'pack_name', 'pack_size', 'size_code')->get()->toArray();
        //dd($divisions);
        if (!$divisions) {
            $data = [
                'message' => 'NO PACK SIZE FOUND.',
            ];
            return response()->json(['data' => $data], 200);
        }
        $allData = [];
        foreach ($divisions as $division) {
            $data = [
                'id' => $division['id'],
                'pack_size' => $division['pack_size']
            ];
            $allData[] = $data;
        }
        return response()->json(['data' => $allData], 200);
    }

    public function get_shade_name(Request $request)
    {

        $app_identifier = $request->app_identifier;
        $pack_size_id = $request->pack_size_id;
        $user_token = $request->header('USER-TOKEN');
        $fixed = new Constants();
        // $dealer = DealerUser::where('dealer_code',$dealer_code)->where('user_token',$user_token)
        //                       ->select('id','password','status','app_identifier')->get()->last();
        //dd($user_token);

        if ($user_token) {
            if ($app_identifier == 'com.ets.elitepaint.dealer') {
                $dealer = DealerUser::where('user_token', $user_token)
                    ->select(
                        'id',
                        'password',
                        'status',
                        'code',
                        'name',
                        'phone',
                        'email',
                        'picture',
                        'alternative_number'
                    )->get()->last();
                //dd($dealer);
                if ($dealer) {

                    $division_list = Product::where('pack_size_id', $pack_size_id)->where('soft_delete', 1)->orderBy('created_at', 'desc')->select('id', 'shade_code', 'shade_name', 'product_code', 'product_name', 'price', 'discount')->get()->toArray();
                    //dd($division_list);
                    $divisions = [];
                    foreach ($division_list as $division) {
                        $data = [
                            'id' => $division['id'],
                            'shade_name' => $division['shade_name'],
                            'price' => $division['price'],
                            'discount' => $division['discount']
                        ];
                        $divisions[] = $data;
                    }
                } else {
                    $data = [
                        'error' => 'USER TOKEN NOT MATCHED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
            } elseif ($app_identifier == 'com.ets.elitepaint.painter') {
                $dealer = PainterUser::where('user_token', $user_token)
                    ->select(
                        'id',
                        'password',
                        'status',
                        'code',
                        'name',
                        'phone',
                        'email',
                        'picture',
                        'alternative_number'
                    )->get()->last();
                //dd($dealer);
                if ($dealer) {

                    $division_list = Product::where('pack_size_id', $pack_size_id)->where('soft_delete', 1)->orderBy('created_at', 'desc')->select('id', 'shade_code', 'shade_name', 'product_code', 'product_name', 'price', 'discount')->get()->toArray();
                    //dd($division_list);
                    $divisions = [];
                    foreach ($division_list as $division) {
                        $data = [
                            'id' => $division['id'],
                            'shade_name' => $division['shade_name'],
                            'price' => $division['price'],
                            'discount' => $division['discount']
                        ];
                        $divisions[] = $data;
                    }
                } else {
                    $data = [
                        'error' => 'USER TOKEN NOT MATCHED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }
        return response()->json(['data' => $divisions], 200);
        //return response()->json(['data' => $divisions], 200);
    }

    public function get_claim_details_this_month(Request $request)
    {

        $app_identifier = $request->app_identifier;
        $user_token = $request->header('USER-TOKEN');
        $now = Carbon::now();
        $month = $now->year . '-' . $now->month;
        $year = $now->year;
        $start = Carbon::parse($month)->startOfMonth()->todateString();
        $start_year = Carbon::parse($year)->startOfYear();
        $end = Carbon::parse($month)->today()->todateString();
        $start_date = $start . ' 00:00:00';
        $end_date = $end . ' 23:59:59';
        $last_year = $year - 1;
        //dd($start_year);
        // $dealer = DealerUser::where('dealer_code',$dealer_code)->where('user_token',$user_token)
        //                       ->select('id','password','status','app_identifier')->get()->last();
        //dd($user_token);

        if ($user_token) {
            if ($app_identifier == 'com.ets.elitepaint.dealer') {
                $dealer = DealerUser::where('user_token', $user_token)
                    ->select(
                        'id',
                        'password',
                        'status',
                        'code',
                        'name',
                        'phone'
                    )->get()->last();
                //dd($dealer);
                if ($dealer) {
                    //dd('ss');
                    $scanpoint_year = ScanPoint::where('dealer_id', $dealer['id'])
                        ->whereBetween('created_at', [$start_date, $end_date])
                        ->select('id', 'bar_code_id', 'created_at')->get()->toArray();
                    //dd($scanpoint_year);
                    $allData = [];
                    $all_total_year = 0;
                    foreach ($scanpoint_year as $scanpoint) {
                        $barcode = BarCode::where('id', $scanpoint['bar_code_id'])
                            ->select('id', 'product_id', 'point', 'bar_code')->get()->last();
                        $all_total_year += $barcode['point'];
                        $entry_time = Carbon::parse($scanpoint['created_at'])->format('g:i A');
                        $pack = Pack::where('id', $barcode['product_id'])
                            ->select('id', 'subgroup_id', 'pack_size')->get()->last();

                        $product = SubGroup::where('id', $pack['subgroup_id'])
                            ->select('id', 'subgroup_name')->get()->last();

                        //dd($product['name']);
                        $dashboard = [
                            'token_no' => $barcode['bar_code'],
                            'scan_time' => $scanpoint['created_at'],
                            'product' => $product['subgroup_name'],
                            'shade_name' => '',
                            'pack_size' => $pack['pack_size'],
                            'points' => $barcode['point'],
                            'created_at' => $scanpoint['created_at'],
                        ];
                        $allData[] = $dashboard;
                    }
                } else {
                    $data = [
                        'error' => 'USER TOKEN NOT MATCHED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
            } elseif ($app_identifier == 'com.ets.elitepaint.painter') {
                $dealer = PainterUser::where('user_token', $user_token)
                    ->select(
                        'id',
                        'password',
                        'status',
                        'code',
                        'name',
                        'phone'
                    )->get()->last();
                //dd($dealer);
                if ($dealer) {
                    $scanpoint_year = ScanPoint::where('painter_id', $dealer['id'])
                        ->whereBetween('created_at', [$start_date, $end_date])
                        ->select('id', 'bar_code_id', 'created_at')->get()->toArray();
                    //dd($scanpoint_year);
                    $allData = [];
                    $all_total_year = 0;
                    foreach ($scanpoint_year as $scanpoint) {
                        $barcode = BarCode::where('id', $scanpoint['bar_code_id'])
                            ->select('id', 'product_id', 'point', 'bar_code')->get()->last();
                        $all_total_year += $barcode['point'];
                        $entry_time = Carbon::parse($scanpoint['created_at'])->format('g:i A');
                        $pack = Pack::where('id', $barcode['product_id'])
                            ->select('id', 'subgroup_id', 'pack_size')->get()->last();

                        $product = SubGroup::where('id', $pack['subgroup_id'])
                            ->select('id', 'subgroup_name')->get()->last();

                        //dd($product['name']);
                        $dashboard = [
                            'token_no' => $barcode['bar_code'],
                            'scan_time' => $scanpoint['created_at'],
                            'product' => $product['subgroup_name'],
                            'shade_name' => '',
                            'pack_size' => $pack['pack_size'],
                            'points' => $barcode['point'],
                            'created_at' => $scanpoint['created_at'],
                        ];
                        $allData[] = $dashboard;
                    }
                } else {
                    $data = [
                        'error' => 'USER TOKEN NOT MATCHED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }

        return response()->json(['data' => $allData], 200);
    }

    public function get_claim_details_this_year(Request $request)
    {
        $app_identifier = $request->app_identifier;
        $user_token = $request->header('USER-TOKEN');
        $now = Carbon::now();
        $month = $now->year . '-' . $now->month;
        $year = $now->year;
        $start = Carbon::parse($month)->startOfMonth()->todateString();
        $start_year = Carbon::parse($year)->startOfYear();
        $end = Carbon::parse($month)->today()->todateString();
        $start_date = $start . ' 00:00:00';
        $end_date = $end . ' 23:59:59';
        $last_year = $year - 1;
        //dd($start_year);
        // $dealer = DealerUser::where('dealer_code',$dealer_code)->where('user_token',$user_token)
        //                       ->select('id','password','status','app_identifier')->get()->last();
        //dd($user_token);

        if ($user_token) {
            if ($app_identifier == 'com.ets.elitepaint.dealer') {
                $dealer = DealerUser::where('user_token', $user_token)
                    ->select(
                        'id',
                        'password',
                        'status',
                        'code',
                        'name',
                        'phone'
                    )->get()->last();
                //dd($dealer);
                if ($dealer) {
                    //dd('ss');
                    $scanpoint_year = ScanPoint::where('dealer_id', $dealer['id'])
                        ->whereBetween('created_at', [$start_year, $end_date])
                        ->select('id', 'bar_code_id', 'created_at')->get()->toArray();
                    //dd($scanpoint_year);
                    $allData = [];
                    $all_total_year = 0;

                    /// transfered_point ---added 12/02/2024 ///

                    $transfered_point = PointTransfer::where('dealer_id', $dealer['id'])
                        ->where('status', 2)
                        ->whereYear('created_at', '=', Carbon::now()->year)
                        ->get()->toArray();

                    foreach($transfered_point as $t_point){
                        $dashboard = [
                            'token_no' => $t_point['transaction_id'],
                            'scan_time' => $t_point['created_at'],
                            'product' => 'POINT TRANSFERRED from '.$t_point['painter_code'],
                            'shade_name' => '',
                            'pack_size' => 'N/A',
                            'points' => $t_point['point'],
                            'created_at' => $t_point['created_at'],
                        ];
                        $allData[] = $dashboard;
                    }

                    ///end transfered_point ---added  12/02/2024  ////


                    foreach ($scanpoint_year as $scanpoint) {
                        $barcode = BarCode::where('id', $scanpoint['bar_code_id'])
                            ->select('id', 'product_id', 'point', 'bar_code')->get()->last();
                        $all_total_year += $barcode['point'];
                        $entry_time = Carbon::parse($scanpoint['created_at'])->format('g:i A');
                        $pack = Pack::where('id', $barcode['product_id'])
                            ->select('id', 'subgroup_id', 'pack_size')->get()->last();

                        $product = SubGroup::where('id', $pack['subgroup_id'])
                            ->select('id', 'subgroup_name')->get()->last();

                        //dd($product['name']);
                        $dashboard = [
                            'token_no' => $barcode['bar_code'],
                            'scan_time' => $scanpoint['created_at'],
                            'product' => $product['subgroup_name'],
                            'shade_name' => '',
                            'pack_size' => $pack['pack_size'],
                            'points' => $barcode['point'],
                            'created_at' => $scanpoint['created_at'],
                        ];
                        $allData[] = $dashboard;
                    }
                } else {
                    $data = [
                        'error' => 'USER TOKEN NOT MATCHED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
            } elseif ($app_identifier == 'com.ets.elitepaint.painter') {

                // return response()->json(['ys'=>'nnn']);
                $dealer = PainterUser::where('user_token', $user_token)
                    ->select(
                        'id',
                        'password',
                        'status',
                        'code',
                        'name',
                        'phone'
                    )->get()->last();
                //dd($dealer);
                if ($dealer) {
                    $scanpoint_year = ScanPoint::where('painter_id', $dealer['id'])
                        ->whereBetween('created_at', [$start_year, $end_date])
                        ->select('id', 'bar_code_id', 'created_at')->get()->toArray();
                    //dd($scanpoint_year);
                    $allData = [];
                    $all_total_year = 0;


                    /// transfered_point ---added 12/02/2024 ///

                    $transfered_point = PointTransfer::where('painter_id', $dealer['id'])
                        ->where('status', 2)
                        ->whereYear('created_at', '=', Carbon::now()->year)
                        ->get()->toArray();

                    foreach($transfered_point as $t_point){
                        $dashboard = [
                            'token_no' => $t_point['transaction_id'],
                            'scan_time' => $t_point['created_at'],
                            'product' => 'POINT TRANSFERRED to '.$t_point['dealer_code'],
                            'shade_name' => '',
                            'pack_size' => 'N/A',
                            'points' => $t_point['point'],
                            'created_at' => $t_point['created_at'],
                        ];
                        $allData[] = $dashboard;
                    }

                    ///end transfered_point ---added  12/02/2024  ////


                    foreach ($scanpoint_year as $scanpoint) {
                        $barcode = BarCode::where('id', $scanpoint['bar_code_id'])
                            ->select('id', 'product_id', 'point', 'bar_code')->get()->last();
                        $all_total_year += $barcode['point'];
                        $entry_time = Carbon::parse($scanpoint['created_at'])->format('g:i A');
                        $pack = Pack::where('id', $barcode['product_id'])
                            ->select('id', 'subgroup_id', 'pack_size')->get()->last();

                        $product = SubGroup::where('id', $pack['subgroup_id'])
                            ->select('id', 'subgroup_name')->get()->last();

                        //dd($product['name']);
                        $dashboard = [
                            'token_no' => $barcode['bar_code'],
                            'scan_time' => $scanpoint['created_at'],
                            'product' => $product['subgroup_name'],
                            'shade_name' => '',
                            'pack_size' => $pack['pack_size'],
                            'points' => $barcode['point'],
                            'created_at' => $scanpoint['created_at'],
                        ];
                        $allData[] = $dashboard;
                        
                    }
                } else {
                    $data = [
                        'error' => 'USER TOKEN NOT MATCHED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }

        return response()->json(['data' => $allData], 200);
    }

    public function get_claim_details_last_year(Request $request)
    {

        $app_identifier = $request->app_identifier;
        $user_token = $request->header('USER-TOKEN');
        $now = Carbon::now();
        $month = $now->year . '-' . $now->month;
        $year = $now->year;
        $start = Carbon::parse($month)->startOfMonth()->todateString();
        $start_year = Carbon::parse($year)->startOfYear();
        $end = Carbon::parse($month)->today()->todateString();
        $start_date = $start . ' 00:00:00';
        $end_date = $end . ' 23:59:59';
        $last_year = $year - 1;
        //dd($start_year);
        // $dealer = DealerUser::where('dealer_code',$dealer_code)->where('user_token',$user_token)
        //                       ->select('id','password','status','app_identifier')->get()->last();
        //dd($user_token);

        if ($user_token) {
            if ($app_identifier == 'com.ets.elitepaint.dealer') {
                $dealer = DealerUser::where('user_token', $user_token)
                    ->select(
                        'id',
                        'password',
                        'status',
                        'code',
                        'name',
                        'phone'
                    )->get()->last();
                //dd($dealer);
                if ($dealer) {
                    //dd('ss');
                    $scanpoint_year = ScanPoint::where('dealer_id', $dealer['id'])
                        ->where('created_at', '>=', Carbon::now()->subYear()->startOfYear())
                        ->where('created_at', '<=', Carbon::now()->subYear()->endOfYear())
                        ->get()->toArray();
                    $allData = [];
                    $all_total_year = 0;
                    foreach ($scanpoint_year as $scanpoint) {
                        $entry_time = Carbon::parse($scanpoint['created_at'])->format('d-m-Y, g:i A');
                        $pack = Pack::where('id', $scanpoint['product_id'])
                            ->select('id', 'subgroup_id', 'pack_size')->get()->last();

                        $product = SubGroup::where('id', $pack['subgroup_id'])
                            ->select('id', 'subgroup_name')->get()->last();

                        $dashboard = [
                            'token_no' => $scanpoint['bar_code'],
                            'scan_time' => $entry_time,
                            'product' => $product['subgroup_name'],
                            'shade_name' => '',
                            'pack_size' => $pack['pack_size'],
                            'points' => $scanpoint['point'],
                            'created_at' => $scanpoint['created_at'],
                        ];
                        $allData[] = $dashboard;
                    }
                } else {
                    $data = [
                        'error' => 'USER TOKEN NOT MATCHED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
            } elseif ($app_identifier == 'com.ets.elitepaint.painter') {
                $dealer = PainterUser::where('user_token', $user_token)
                    ->select(
                        'id',
                        'password',
                        'status',
                        'code',
                        'name',
                        'phone'
                    )->get()->last();
                //dd($dealer);
                if ($dealer) {
                    $scanpoint_year = ScanPoint::where('painter_id', $dealer['id'])
                        ->where('created_at', '>=', Carbon::now()->subYear()->startOfYear())
                        ->where('created_at', '<=', Carbon::now()->subYear()->endOfYear())
                        ->get()->toArray();
                    //dd($scanpoint_year);


                    $allData = [];
                    $all_total_year = 0;

                    /// transfered_point added 12-02-24

                    $transfered_point = PointTransfer::where('painter_id', $dealer['id'])
                        ->where('created_at', '>=', Carbon::now()->subYear()->startOfYear())
                        ->where('created_at', '<=', Carbon::now()->subYear()->endOfYear())
                        ->get()->toArray();

                    foreach($transfered_point as $t_point){
                        $dashboard = [
                            'token_no' => $t_point['transaction_id'],
                            'scan_time' => $t_point['created_at'],
                            'product' => 'POINT TRANSFER',
                            'shade_name' => '',
                            'pack_size' => 'N/A',
                            'points' => $t_point['point'],
                            'created_at' => $t_point['created_at'],
                        ];
                        $allData[] = $dashboard;
                    }

                    foreach ($scanpoint_year as $scanpoint) {
                        $barcode = BarCode::where('id', $scanpoint['bar_code_id'])
                            ->select('id', 'product_id', 'point', 'bar_code')->get()->last();
                        $all_total_year += $scanpoint['point'];
                        $entry_time = Carbon::parse($scanpoint['created_at'])->format('d-m-Y, g:i A');
                        $pack = Pack::where('id', $scanpoint['product_id'])
                            ->select('id', 'subgroup_id', 'pack_size')->get()->last();

                        $product = SubGroup::where('id', $pack['subgroup_id'])
                            ->select('id', 'subgroup_name')->get()->last();

                        //dd($product['name']);
                        $dashboard = [
                            'token_no' => $scanpoint['bar_code'],
                            'scan_time' => $entry_time,
                            'product' => $product['subgroup_name'],
                            'shade_name' => '',
                            'pack_size' => $pack['pack_size'],
                            'points' => $scanpoint['point'],
                            'created_at' => $scanpoint['created_at'],
                        ];
                        $allData[] = $dashboard;
                    }
                } else {
                    $data = [
                        'error' => 'USER TOKEN NOT MATCHED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }

        return response()->json(['data' => $allData], 200);
    }

    public function get_purchase_point_this_month(Request $request)
    {

        $user_token = $request->header('USER-TOKEN');
        $now = Carbon::now();
        $month = $now->year . '-' . $now->month;
        $year = $now->year;
        $start = Carbon::parse($month)->startOfMonth()->todateString();
        $start_year = Carbon::parse($year)->startOfYear();
        $end = Carbon::parse($month)->today()->todateString();
        $start_date = $start . ' 00:00:00';
        $end_date = $end . ' 23:59:59';
        $last_year = $year - 1;
        if ($user_token) {

            $dealer = PainterUser::where('user_token', $user_token)
                ->select(
                    'id',
                    'password',
                    'status',
                    'code',
                    'name',
                    'phone'
                )->get()->last();
            //dd($dealer);
            if ($dealer) {
                $feedback_list = VolumeTranfer::where('painter_id', $dealer['id'])->whereBetween('created_at', [$start_date, $end_date])->where('soft_delete', 1)->orderBy('created_at', 'desc')->select(
                    'code',
                    'painter_id',
                    'dealer_id',
                    'status',
                    'created_at'
                )->distinct('code')->get()->toArray();

                //dd($feedback_list);

                //dd($feedback_l);
                if (!$feedback_list) {
                    $data = [
                        'message' => 'NO PURCHASE ADDED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
                $allData = [];
                //$total_ltr = 0;
                foreach ($feedback_list as $feedback) {
                    $total_codes = VolumeTranfer::where('code', $feedback['code'])->select('id', 'code')->get();
                    //dd($total_codes);
                    $total_ltr = 0;
                    foreach ($total_codes as $total_code) {
                        //dd($total_code['id']);
                        $volum = VolumeTranfer::where('id', $total_code['id'])
                            ->select('id', 'product_id', 'quantity')->get()->last();
                        // $product = Product::where('id',$volum['product_id'])
                        //                   ->select('id','pack_size_id')->get()->last();
                        // $pack_size = Pack::where('id',$product['pack_size_id'])
                        //                   ->select('id','pack_size')->get()->last();
                        //$total = $volum['quantity']* $pack_size['pack_size'];
                        $total_ltr += floatval($volum['quantity']);
                    }
                    $deal = DealerUser::where('id', $feedback['dealer_id'])->select(
                        'id',
                        'password',
                        'status',
                        'code',
                        'name',
                        'phone'
                    )->get()->last();
                    //dd($thana);
                    $feedback_l = VolumeTranfer::where('code', $feedback['code'])
                        ->count('code');

                    $points = VolumeTranfer::where('code', $feedback['code'])
                        ->sum('painter_point');
                    $dashboard = [
                        'dealer_id' => $deal['id'],
                        'dealer_code' => $deal['code'],
                        'dealer_name' => $deal['name'],
                        'dealer_phone' => $deal['phone'],
                        'product' => $feedback_l . ' ' . 'Products',
                        'ltr' => round($total_ltr, 2) . ' ' . 'LTR',
                        'code' => $feedback['code'],
                        'points' => round($points, 2) . ' ' . 'PTS',
                        'created_at' => $feedback['created_at'],
                        'status' => $feedback['status'] == 2 ? 'PENDING' : 'ACCEPTED',
                    ];

                    $allData[] = $dashboard;
                }
            } else {
                $data = [
                    'error' => 'USER TOKEN NOT MATCHED.',
                ];
                return response()->json(['data' => $data], 200);
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }

        return response()->json(['data' => $allData], 200);
    }

    public function get_bonus_point_last_year(Request $request)
    {
        $app_identifier = $request->app_identifier;
        $user_token = $request->header('USER-TOKEN');

        $now = Carbon::now();
        $month = $now->year . '-' . $now->month;
        $year = $now->year;
        $start = Carbon::parse($month)->startOfMonth()->todateString();
        $start_year = Carbon::parse($year)->startOfYear();
        $end = Carbon::parse($month)->today()->todateString();
        $start_date = $start . ' 00:00:00';
        $end_date = $end . ' 23:59:59';
        $last_year = $year - 1;

        $now = Carbon::now();
        $month = $now->year . '-' . $now->month;
        $year = $now->year;
        $start = Carbon::parse($month)->startOfMonth()->todateString();
        $start_year = Carbon::parse($year)->startOfYear();
        $end = Carbon::parse($month)->today()->todateString();
        $start_date = $start . ' 00:00:00';
        $end_date = $end . ' 23:59:59';
        $last_year = $year - 1;
        if ($user_token) {
            if ($app_identifier == 'com.ets.elitepaint.dealer') {
                $dealer = DealerUser::where('user_token', $user_token)
                    ->select(
                        'id',
                        'password',
                        'status',
                        'code',
                        'name',
                        'phone'
                    )->get()->last();
                //dd($dealer);
                if ($dealer) {
                    $feedback_list = DB::table('bonus_points')->where('dealer_id', $dealer['id'])->where('soft_delete', 1)
                        ->where('created_at', '>=', Carbon::now()->subYear()->startOfYear())
                        ->where('created_at', '<=', Carbon::now()->subYear()->endOfYear())
                        ->orderBy('created_at', 'desc')->select(
                            'id',
                            'dealer_id',
                            'token_number',
                            'token_product',
                            'token_product_size',
                            'actual_product',
                            'actual_product_size',
                            'scan_point',
                            'project_name',
                            'project_address',
                            'project_volume',
                            'member_type',
                            'type',
                            'remarks',
                            'bonus_point',
                            'created_at'
                        )->get()->toArray();

                    //dd($feedback_list);

                    //dd($feedback_l);

                    $allData = [];
                    //$total_ltr = 0;
                    foreach ($feedback_list as $feedback) {
                        if ($feedback->type == 'TOKEN') {
                            $dashboard = [
                                'id' => $feedback->id,
                                'token_number' => $feedback->token_number,
                                'token_product' => $feedback->token_product,
                                'token_product_size' => $feedback->token_product_size,
                                'actual_product' => $feedback->actual_product,
                                'actual_product_size' => $feedback->actual_product_size,
                                'scan_point' => $feedback->scan_point,
                                'project_name' => $feedback->project_name,
                                'project_address' => $feedback->project_address,
                                'project_volume' => $feedback->project_volume,
                                'member_type' => $feedback->member_type,
                                'type' => $feedback->type,
                                'remarks' => $feedback->remarks,
                                'bonus_point' => $feedback->bonus_point,
                                'created_at' => $feedback->created_at,
                            ];
                            $allData[] = $dashboard;
                        } else {
                            $dashboard = [
                                'id' => $feedback->id,
                                'token_number' => $feedback->token_number,
                                'token_product' => $feedback->token_product,
                                'token_product_size' => $feedback->token_product_size,
                                'actual_product' => $feedback->actual_product,
                                'actual_product_size' => $feedback->actual_product_size,
                                'scan_point' => $feedback->scan_point,
                                'project_name' => $feedback->project_name,
                                'project_address' => $feedback->project_address,
                                'project_volume' => $feedback->project_volume,
                                'member_type' => $feedback->member_type,
                                'type' => $feedback->type,
                                'remarks' => $feedback->remarks,
                                'bonus_point' => $feedback->bonus_point,
                                'created_at' => $feedback->created_at,
                            ];
                            $allData[] = $dashboard;
                        }
                    }
                } else {
                    $data = [
                        'error' => 'USER TOKEN NOT MATCHED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
            } else {
                $dealer = PainterUser::where('user_token', $user_token)
                    ->select(
                        'id',
                        'password',
                        'status',
                        'code',
                        'name',
                        'phone'
                    )->get()->last();
                //dd($dealer);
                if ($dealer) {
                    $feedback_list = DB::table('bonus_points')->where('painter_id', $dealer['id'])->where('soft_delete', 1)
                        ->where('created_at', '>=', Carbon::now()->subYear()->startOfYear())
                        ->where('created_at', '<=', Carbon::now()->subYear()->endOfYear())
                        ->orderBy('created_at', 'desc')->select(
                            'id',
                            'painter_id',
                            'token_number',
                            'token_product',
                            'token_product_size',
                            'actual_product',
                            'actual_product_size',
                            'scan_point',
                            'project_name',
                            'project_address',
                            'project_volume',
                            'member_type',
                            'type',
                            'remarks',
                            'bonus_point',
                            'created_at'
                        )->get()->toArray();

                    //dd($feedback_list);

                    //dd($feedback_l);

                    $allData = [];
                    //$total_ltr = 0;
                    foreach ($feedback_list as $feedback) {
                        if ($feedback->type == 'TOKEN') {
                            $dashboard = [
                                'id' => $feedback->id,
                                'token_number' => $feedback->token_number,
                                'token_product' => $feedback->token_product,
                                'token_product_size' => $feedback->token_product_size,
                                'actual_product' => $feedback->actual_product,
                                'actual_product_size' => $feedback->actual_product_size,
                                'scan_point' => $feedback->scan_point,
                                'project_name' => $feedback->project_name,
                                'project_address' => $feedback->project_address,
                                'project_volume' => $feedback->project_volume,
                                'member_type' => $feedback->member_type,
                                'type' => $feedback->type,
                                'remarks' => $feedback->remarks,
                                'bonus_point' => $feedback->bonus_point,
                                'created_at' => $feedback->created_at,
                            ];

                            $allData[] = $dashboard;
                        } else {
                            $dashboard = [
                                'id' => $feedback->id,
                                'token_number' => $feedback->token_number,
                                'token_product' => $feedback->token_product,
                                'token_product_size' => $feedback->token_product_size,
                                'actual_product' => $feedback->actual_product,
                                'actual_product_size' => $feedback->actual_product_size,
                                'scan_point' => $feedback->scan_point,
                                'project_name' => $feedback->project_name,
                                'project_address' => $feedback->project_address,
                                'project_volume' => $feedback->project_volume,
                                'member_type' => $feedback->member_type,
                                'type' => $feedback->type,
                                'remarks' => $feedback->remarks,
                                'bonus_point' => $feedback->bonus_point,
                                'created_at' => $feedback->created_at,
                            ];

                            $allData[] = $dashboard;
                        }
                    }
                } else {
                    $data = [
                        'error' => 'USER TOKEN NOT MATCHED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }

        return response()->json(['data' => $allData], 200);
    }

    public function get_bonus_point_this_year(Request $request)
    {
        $app_identifier = $request->app_identifier;
        $user_token = $request->header('USER-TOKEN');
        $now = Carbon::now();
        $month = $now->year . '-' . $now->month;
        $year = $now->year;
        $start = Carbon::parse($month)->startOfMonth()->todateString();
        $start_year = Carbon::parse($year)->startOfYear();
        $end = Carbon::parse($month)->today()->todateString();
        $start_date = $start . ' 00:00:00';
        $end_date = $end . ' 23:59:59';
        $last_year = $year - 1;
        if ($user_token) {
            if ($app_identifier == 'com.ets.elitepaint.dealer') {
                $dealer = DealerUser::where('user_token', $user_token)
                    ->select(
                        'id',
                        'password',
                        'status',
                        'code',
                        'name',
                        'phone'
                    )->get()->last();
                //dd($dealer);
                if ($dealer) {
                    $feedback_list = DB::table('bonus_points')->where('dealer_id', $dealer['id'])->where('soft_delete', 1)
                        ->whereBetween('created_at', [$start_year, $end_date])->orderBy('created_at', 'desc')->select(
                            'id',
                            'dealer_id',
                            'token_number',
                            'token_product',
                            'token_product_size',
                            'actual_product',
                            'actual_product_size',
                            'scan_point',
                            'project_name',
                            'project_address',
                            'project_volume',
                            'member_type',
                            'type',
                            'remarks',
                            'bonus_point',
                            'created_at'
                        )->get()->toArray();

                    //dd($feedback_list);

                    //dd($feedback_l);

                    $allData = [];
                    //$total_ltr = 0;
                    foreach ($feedback_list as $feedback) {
                        if ($feedback->type == 'TOKEN') {
                            $dashboard = [
                                'id' => $feedback->id,
                                'token_number' => $feedback->token_number,
                                'token_product' => $feedback->token_product,
                                'token_product_size' => $feedback->token_product_size,
                                'actual_product' => $feedback->actual_product,
                                'actual_product_size' => $feedback->actual_product_size,
                                'scan_point' => $feedback->scan_point,
                                'project_name' => $feedback->project_name,
                                'project_address' => $feedback->project_address,
                                'project_volume' => $feedback->project_volume,
                                'member_type' => $feedback->member_type,
                                'type' => $feedback->type,
                                'remarks' => $feedback->remarks,
                                'bonus_point' => $feedback->bonus_point,
                                'created_at' => $feedback->created_at,
                            ];
                            $allData[] = $dashboard;
                        } else {
                            $dashboard = [
                                'id' => $feedback->id,
                                'token_number' => $feedback->token_number,
                                'token_product' => $feedback->token_product,
                                'token_product_size' => $feedback->token_product_size,
                                'actual_product' => $feedback->actual_product,
                                'actual_product_size' => $feedback->actual_product_size,
                                'scan_point' => $feedback->scan_point,
                                'project_name' => $feedback->project_name,
                                'project_address' => $feedback->project_address,
                                'project_volume' => $feedback->project_volume,
                                'member_type' => $feedback->member_type,
                                'type' => $feedback->type,
                                'remarks' => $feedback->remarks,
                                'bonus_point' => $feedback->bonus_point,
                                'created_at' => $feedback->created_at,
                            ];
                            $allData[] = $dashboard;
                        }
                    }
                } else {
                    $data = [
                        'error' => 'USER TOKEN NOT MATCHED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
            } else {
                $dealer = PainterUser::where('user_token', $user_token)
                    ->select(
                        'id',
                        'password',
                        'status',
                        'code',
                        'name',
                        'phone'
                    )->get()->last();
                //dd($dealer);
                if ($dealer) {
                    $feedback_list = DB::table('bonus_points')->where('painter_id', $dealer['id'])->where('soft_delete', 1)
                        ->whereBetween('created_at', [$start_year, $end_date])->orderBy('created_at', 'desc')->select(
                            'id',
                            'painter_id',
                            'token_number',
                            'token_product',
                            'token_product_size',
                            'actual_product',
                            'actual_product_size',
                            'scan_point',
                            'project_name',
                            'project_address',
                            'project_volume',
                            'member_type',
                            'type',
                            'remarks',
                            'bonus_point',
                            'created_at'
                        )->get()->toArray();

                    //dd($feedback_list);

                    //dd($feedback_l);

                    $allData = [];
                    //$total_ltr = 0;
                    foreach ($feedback_list as $feedback) {
                        if ($feedback->type == 'TOKEN') {
                            $dashboard = [
                                'id' => $feedback->id,
                                'token_number' => $feedback->token_number,
                                'token_product' => $feedback->token_product,
                                'token_product_size' => $feedback->token_product_size,
                                'actual_product' => $feedback->actual_product,
                                'actual_product_size' => $feedback->actual_product_size,
                                'scan_point' => $feedback->scan_point,
                                'project_name' => $feedback->project_name,
                                'project_address' => $feedback->project_address,
                                'project_volume' => $feedback->project_volume,
                                'member_type' => $feedback->member_type,
                                'type' => $feedback->type,
                                'remarks' => $feedback->remarks,
                                'bonus_point' => $feedback->bonus_point,
                                'created_at' => $feedback->created_at,
                            ];

                            $allData[] = $dashboard;
                        } else {
                            $dashboard = [
                                'id' => $feedback->id,
                                'token_number' => $feedback->token_number,
                                'token_product' => $feedback->token_product,
                                'token_product_size' => $feedback->token_product_size,
                                'actual_product' => $feedback->actual_product,
                                'actual_product_size' => $feedback->actual_product_size,
                                'scan_point' => $feedback->scan_point,
                                'project_name' => $feedback->project_name,
                                'project_address' => $feedback->project_address,
                                'project_volume' => $feedback->project_volume,
                                'member_type' => $feedback->member_type,
                                'type' => $feedback->type,
                                'remarks' => $feedback->remarks,
                                'bonus_point' => $feedback->bonus_point,
                                'created_at' => $feedback->created_at,
                            ];

                            $allData[] = $dashboard;
                        }
                    }
                } else {
                    $data = [
                        'error' => 'USER TOKEN NOT MATCHED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }

        return response()->json(['data' => $allData], 200);
    }

    public function get_purchase_point_this_year(Request $request)
    {
        $app_identifier = $request->app_identifier;
        $user_token = $request->header('USER-TOKEN');
        $now = Carbon::now();
        $month = $now->year . '-' . $now->month;
        $year = $now->year;
        $start = Carbon::parse($month)->startOfMonth()->todateString();
        $start_year = Carbon::parse($year)->startOfYear();
        $end = Carbon::parse($month)->today()->todateString();
        $start_date = $start . ' 00:00:00';
        $end_date = $end . ' 23:59:59';
        $last_year = $year - 1;
        if ($user_token) {
            if ($app_identifier == 'com.ets.elitepaint.dealer') {
                $dealer = DealerUser::where('user_token', $user_token)
                    ->select(
                        'id',
                        'password',
                        'status',
                        'code',
                        'name',
                        'phone'
                    )->get()->last();
                //dd($dealer);
                if ($dealer) {
                    $feedback_list = VolumeTranfer::where('dealer_id', $dealer['id'])->where('soft_delete', 1)
                        ->whereBetween('created_at', [$start_year, $end_date])->where('soft_delete', 1)->orderBy('created_at', 'desc')->select(
                            'code',
                            'painter_id',
                            'dealer_id',
                            'status',
                            'created_at',
                            'accepted_by'
                        )->distinct('code')->get()->toArray();

                    //dd($feedback_list);

                    //dd($feedback_l);

                    $allData = [];

                    //$total_ltr = 0;
                    foreach ($feedback_list as $feedback) {
                        $total_codes = VolumeTranfer::where('code', $feedback['code'])->select('id', 'code')->get();
                        //dd($total_codes);
                        $total_ltr = 0;
                        foreach ($total_codes as $total_code) {
                            //dd($total_code['id']);
                            $volum = VolumeTranfer::where('id', $total_code['id'])
                                ->select('id', 'product_id', 'quantity')->get()->last();
                            // $product = Product::where('id',$volum['product_id'])
                            //                   ->select('id','pack_size_id')->get()->last();
                            // $pack_size = Pack::where('id',$product['pack_size_id'])
                            //                   ->select('id','pack_size')->get()->last();
                            //$total = $volum['quantity']* $pack_size['pack_size'];
                            $total_ltr += floatval($volum['quantity']);
                        }
                        $deal = PainterUser::where('id', $feedback['painter_id'])->select(
                            'id',
                            'password',
                            'status',
                            'code',
                            'name',
                            'phone'
                        )->get()->last();
                        //dd($thana);
                        $feedback_l = VolumeTranfer::where('code', $feedback['code'])
                            ->count('code');

                        $points = VolumeTranfer::where('code', $feedback['code'])
                            ->sum('dealer_point');
                        $dashboard = [
                            'dealer_id' => $deal['id'],
                            'dealer_code' => $deal['code'],
                            'dealer_name' => $deal['name'],
                            'dealer_phone' => $deal['phone'],
                            'product' => $feedback_l . ' ' . 'Products',
                            'ltr' => round($total_ltr, 2) . ' ' . 'LTR',
                            'code' => $feedback['code'],
                            'points' => round($points, 2) . ' ' . 'PTS',
                            'accepted_by' => $feedback['accepted_by'],
                            'created_at' => $feedback['created_at'],
                            'status' => $feedback['status'] == 2 ? 'PENDING' : 'ACCEPTED',
                        ];



                        $allData[] = $dashboard;
                    }
                } else {
                    $data = [
                        'error' => 'USER TOKEN NOT MATCHED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
            } else {
                $dealer = PainterUser::where('user_token', $user_token)
                    ->select(
                        'id',
                        'password',
                        'status',
                        'code',
                        'name',
                        'phone'
                    )->get()->last();
                //dd($dealer);
                if ($dealer) {
                    $feedback_list = VolumeTranfer::where('painter_id', $dealer['id'])->where('soft_delete', 1)
                        ->whereBetween('created_at', [$start_year, $end_date])->where('soft_delete', 1)->orderBy('created_at', 'desc')->select(
                            'code',
                            'painter_id',
                            'dealer_id',
                            'status',
                            'created_at',
                            'accepted_by'
                        )->distinct('code')->get()->toArray();

                    //dd($feedback_list);

                    //dd($feedback_l);

                    $allData = [];
                    //$total_ltr = 0;
                    foreach ($feedback_list as $feedback) {
                        $total_codes = VolumeTranfer::where('code', $feedback['code'])->select('id', 'code')->get();
                        //dd($total_codes);
                        $total_ltr = 0;
                        foreach ($total_codes as $total_code) {
                            //dd($total_code['id']);
                            $volum = VolumeTranfer::where('id', $total_code['id'])
                                ->select('id', 'product_id', 'quantity')->get()->last();
                            // $product = Product::where('id',$volum['product_id'])
                            //                   ->select('id','pack_size_id')->get()->last();
                            // $pack_size = Pack::where('id',$product['pack_size_id'])
                            //                   ->select('id','pack_size')->get()->last();
                            //$total = $volum['quantity']* $pack_size['pack_size'];
                            $total_ltr += floatval($volum['quantity']);
                        }
                        $deal = DealerUser::where('id', $feedback['dealer_id'])->select(
                            'id',
                            'password',
                            'status',
                            'code',
                            'name',
                            'phone'
                        )->get()->last();
                        //dd($thana);
                        $feedback_l = VolumeTranfer::where('code', $feedback['code'])
                            ->count('code');

                        $points = VolumeTranfer::where('code', $feedback['code'])
                            ->sum('painter_point');
                        $dashboard = [
                            'dealer_id' => $deal['id'],
                            'dealer_code' => $deal['code'],
                            'dealer_name' => $deal['name'],
                            'dealer_phone' => $deal['phone'],
                            'product' => $feedback_l . ' ' . 'Products',
                            'ltr' => round($total_ltr, 2) . ' ' . 'LTR',
                            'code' => $feedback['code'],
                            'points' => round($points, 2) . ' ' . 'PTS',
                            'accepted_by' => $feedback['accepted_by'],
                            'created_at' => $feedback['created_at'],
                            'status' => $feedback['status'] == 2 ? 'PENDING' : 'ACCEPTED',
                        ];

                        $allData[] = $dashboard;
                    }
                } else {
                    $data = [
                        'error' => 'USER TOKEN NOT MATCHED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }

        return response()->json(['data' => $allData], 200);
    }

    public function get_purchase_point_last_year(Request $request)
    {
        $app_identifier = $request->app_identifier;
        $user_token = $request->header('USER-TOKEN');
        $now = Carbon::now();
        $month = $now->year . '-' . $now->month;
        $year = $now->year;
        $start = Carbon::parse($month)->startOfMonth()->todateString();
        $start_year = Carbon::parse($year)->startOfYear();
        $end = Carbon::parse($month)->today()->todateString();
        $start_date = $start . ' 00:00:00';
        $end_date = $end . ' 23:59:59';
        $last_year = $year - 1;
        if ($user_token) {
            if ($app_identifier == 'com.ets.elitepaint.dealer') {
                $dealer = DealerUser::where('user_token', $user_token)
                    ->select(
                        'id',
                        'password',
                        'status',
                        'code',
                        'name',
                        'phone'
                    )->get()->last();
                //dd($dealer);
                if ($dealer) {
                    $feedback_list = VolumeTranfer::where('dealer_id', $dealer['id'])
                        ->where('created_at', '>=', Carbon::now()->subYear()->startOfYear())
                        ->where('created_at', '<=', Carbon::now()->subYear()->endOfYear())
                        ->where('soft_delete', 1)
                        ->orderBy('created_at', 'desc')->select(
                            'code',
                            'painter_id',
                            'dealer_id',
                            'status',
                            'created_at',
                            'accepted_by'
                        )->distinct('code')->get()->toArray();

                    //dd($feedback_list);

                    //dd($feedback_l);

                    $allData = [];
                    //$total_ltr = 0;
                    foreach ($feedback_list as $feedback) {
                        $total_codes = VolumeTranfer::where('code', $feedback['code'])->select('id', 'code')->get();
                        //dd($total_codes);
                        $total_ltr = 0;
                        foreach ($total_codes as $total_code) {
                            //dd($total_code['id']);
                            $volum = VolumeTranfer::where('id', $total_code['id'])
                                ->select('id', 'product_id', 'quantity')->get()->last();
                            // $product = Product::where('id',$volum['product_id'])
                            //                   ->select('id','pack_size_id')->get()->last();
                            // $pack_size = Pack::where('id',$product['pack_size_id'])
                            //                   ->select('id','pack_size')->get()->last();
                            //$total = $volum['quantity']* $pack_size['pack_size'];
                            $total_ltr += $volum['quantity'];
                        }
                        $deal = PainterUser::where('id', $feedback['painter_id'])->select(
                            'id',
                            'password',
                            'status',
                            'code',
                            'name',
                            'phone'
                        )->get()->last();
                        //dd($thana);
                        $feedback_l = VolumeTranfer::where('code', $feedback['code'])
                            ->count('code');

                        $points = VolumeTranfer::where('code', $feedback['code'])
                            ->sum('dealer_point');
                        $dashboard = [
                            'dealer_id' => $deal['id'],
                            'dealer_code' => $deal['code'],
                            'dealer_name' => $deal['name'],
                            'dealer_phone' => $deal['phone'],
                            'product' => $feedback_l . ' ' . 'Products',
                            'ltr' => round($total_ltr, 2) . ' ' . 'LTR',
                            'code' => $feedback['code'],
                            'points' => round($points, 2) . ' ' . 'PTS',
                            'accepted_by' => $feedback['accepted_by'],
                            'created_at' => $feedback['created_at'],
                            'status' => $feedback['status'] == 2 ? 'PENDING' : 'ACCEPTED',
                        ];

                        $allData[] = $dashboard;
                    }
                } else {
                    $data = [
                        'error' => 'USER TOKEN NOT MATCHED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
            } else {
                $dealer = PainterUser::where('user_token', $user_token)
                    ->select(
                        'id',
                        'password',
                        'status',
                        'code',
                        'name',
                        'phone'
                    )->get()->last();
                //dd($dealer);
                if ($dealer) {
                    $feedback_list = VolumeTranfer::where('painter_id', $dealer['id'])
                        ->where('created_at', '>=', Carbon::now()->subYear()->startOfYear())
                        ->where('created_at', '<=', Carbon::now()->subYear()->endOfYear())
                        ->where('soft_delete', 1)
                        ->orderBy('created_at', 'desc')->select(
                            'code',
                            'painter_id',
                            'dealer_id',
                            'status',
                            'created_at',
                            'accepted_by'
                        )->distinct('code')->get()->toArray();

                    //dd($feedback_list);

                    //dd($feedback_l);

                    $allData = [];
                    //$total_ltr = 0;
                    foreach ($feedback_list as $feedback) {
                        $total_codes = VolumeTranfer::where('code', $feedback['code'])->select('id', 'code')->get();
                        //dd($total_codes);
                        $total_ltr = 0;
                        foreach ($total_codes as $total_code) {
                            //dd($total_code['id']);
                            $volum = VolumeTranfer::where('id', $total_code['id'])
                                ->select('id', 'product_id', 'quantity')->get()->last();
                            // $product = Product::where('id',$volum['product_id'])
                            //                   ->select('id','pack_size_id')->get()->last();
                            // $pack_size = Pack::where('id',$product['pack_size_id'])
                            //                   ->select('id','pack_size')->get()->last();
                            //$total = $volum['quantity']* $pack_size['pack_size'];
                            $total_ltr += $volum['quantity'];
                        }
                        $deal = DealerUser::where('id', $feedback['dealer_id'])->select(
                            'id',
                            'password',
                            'status',
                            'code',
                            'name',
                            'phone'
                        )->get()->last();
                        //dd($thana);
                        $feedback_l = VolumeTranfer::where('code', $feedback['code'])
                            ->count('code');

                        $points = VolumeTranfer::where('code', $feedback['code'])
                            ->sum('painter_point');
                        $dashboard = [
                            'dealer_id' => $deal['id'],
                            'dealer_code' => $deal['code'],
                            'dealer_name' => $deal['name'],
                            'dealer_phone' => $deal['phone'],
                            'product' => $feedback_l . ' ' . 'Products',
                            'ltr' => round($total_ltr, 2) . ' ' . 'LTR',
                            'code' => $feedback['code'],
                            'points' => round($points, 2) . ' ' . 'PTS',
                            'accepted_by' => $feedback['accepted_by'],
                            'created_at' => $feedback['created_at'],
                            'status' => $feedback['status'] == 2 ? 'PENDING' : 'ACCEPTED',
                        ];

                        $allData[] = $dashboard;
                    }
                } else {
                    $data = [
                        'error' => 'USER TOKEN NOT MATCHED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }

        return response()->json(['data' => $allData], 200);
    }

    public function get_redeem_point_this_year(Request $request)
    {
        $app_identifier = $request->app_identifier;
        $user_token = $request->header('USER-TOKEN');
        $now = Carbon::now();
        $month = $now->year . '-' . $now->month;
        $year = $now->year;
        $start = Carbon::parse($month)->startOfMonth()->todateString();
        $start_year = Carbon::parse($year)->startOfYear();
        $end = Carbon::parse($month)->today()->todateString();
        $start_date = $start . ' 00:00:00';
        $end_date = $end . ' 23:59:59';
        $last_year = $year - 1;
        if ($user_token) {
            if ($app_identifier == 'com.ets.elitepaint.dealer') {
                $dealer = DealerUser::where('user_token', $user_token)
                    ->select(
                        'id',
                        'password',
                        'status',
                        'code',
                        'name',
                        'phone'
                    )->get()->last();
                //dd($dealer);
                if ($dealer) {
                    $scanpoint_year = RedeemPoint::where('dealer_id', $dealer['id'])
                        ->whereBetween('created_at', [$start_year, $end_date])
                        ->where('status', 1)
                        ->select('id', 'redeem_point', 'code', 'transaction_code', 'created_at')->get()->toArray();
                    //dd($scanpoint_year);
                    $allData = [];
                    $all_total_year = 0;
                    foreach ($scanpoint_year as $scanpoint) {

                        $entry_time = Carbon::parse($scanpoint['created_at'])->format('g:i A');
                        //dd($product['name']);
                        $dashboard = [
                            'redeem_points' => $scanpoint['redeem_point'],
                            'redeem_time' => $scanpoint['created_at'],
                            'received_money' => $scanpoint['redeem_point'],
                            'payment' => $scanpoint['code'],
                            'transaction_id' => $scanpoint['transaction_code']
                        ];
                        $allData[] = $dashboard;
                    }
                } else {
                    $data = [
                        'error' => 'USER TOKEN NOT MATCHED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
            } else {
                $dealer = PainterUser::where('user_token', $user_token)
                    ->select(
                        'id',
                        'password',
                        'status',
                        'code',
                        'name',
                        'phone'
                    )->get()->last();
                //dd($dealer);
                if ($dealer) {
                    $scanpoint_year = RedeemPoint::where('painter_id', $dealer['id'])
                        ->whereBetween('created_at', [$start_year, $end_date])
                        ->where('status', 1)
                        ->select('id', 'redeem_point', 'code', 'transaction_code', 'created_at')->get()->toArray();
                    //dd($scanpoint_year);
                    $allData = [];
                    $all_total_year = 0;
                    foreach ($scanpoint_year as $scanpoint) {

                        $entry_time = Carbon::parse($scanpoint['created_at'])->format('g:i A');
                        //dd($product['name']);
                        $dashboard = [
                            'redeem_points' => $scanpoint['redeem_point'],
                            'redeem_time' => $scanpoint['created_at'],
                            'received_money' => $scanpoint['redeem_point'],
                            'payment' => $scanpoint['code'],
                            'transaction_id' => $scanpoint['transaction_code']
                        ];
                        $allData[] = $dashboard;
                    }
                } else {
                    $data = [
                        'error' => 'USER TOKEN NOT MATCHED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }

        return response()->json(['data' => $allData], 200);
    }

    public function get_division_depo_list(Request $request)
    {

        $app_identifier = $request->app_identifier;
        $fixed = new Constants();
        // $dealer = DealerUser::where('dealer_code',$dealer_code)->where('user_token',$user_token)
        //                       ->select('id','password','status','app_identifier')->get()->last();
        //dd($user_token);


        if ($app_identifier == 'com.ets.elitepaint.dealer') {

            $buisness_array = array(
                "Dhaka North Depot", "Barishal Depot", "Mymensing Depot", "Bogura Depot", "Rajshahi Depot", "Sylhet Depot", "Khulna Depot", "Cumilla Depot", "Factory Office", "Chattogram Depot", "Dhaka South Depot", "Rangpur Depot"
            );

            $buisness_arrays = [];
            foreach ($buisness_array as $buisness) {
                $data = [
                    'title' => $buisness
                ];
                $buisness_arrays[] = $data;
            }

            $division_list = MacroDivision::select('id', 'division')->get()->toArray();
            //dd($division_list);
            $divisions = [];
            foreach ($division_list as $division) {
                $data = [
                    'id' => $division['id'],
                    'title' => $division['division'],
                ];
                $divisions[] = $data;
            }
        } elseif ($app_identifier == 'com.ets.elitepaint.painter') {

            $buisness_array = array(
                "Dhaka North Depot", "Barishal Depot", "Mymensing Depot", "Bogura Depot", "Rajshahi Depot", "Sylhet Depot", "Khulna Depot", "Cumilla Depot", "Factory Office", "Chattogram Depot", "Dhaka South Depot", "Rangpur Depot"
            );

            $buisness_arrays = [];
            foreach ($buisness_array as $buisness) {
                $data = [
                    'title' => $buisness
                ];
                $buisness_arrays[] = $data;
            }
            $division_list = MacroDivision::select('id', 'division')->get()->toArray();
            //dd($division_list);
            $divisions = [];
            foreach ($division_list as $division) {
                $data = [
                    'id' => $division['id'],
                    'title' => $division['division'],
                ];
                $divisions[] = $data;
            }
        }

        return response()->json(['data' => ['divisions' => $divisions, 'depo' => $buisness_arrays]], 200);
        //return response()->json(['data' => $divisions], 200);
    }

    public function get_dealer_list(Request $request)
    {

        $app_identifier = $request->app_identifier;
        $fixed = new Constants();
        // $dealer = DealerUser::where('dealer_code',$dealer_code)->where('user_token',$user_token)
        //                       ->select('id','password','status','app_identifier')->get()->last();
        //dd($user_token);
        $dealer_list = DealerUser::select(
            'id',
            'password',
            'status',
            'code',
            'name',
            'phone',
            'email',
            'depo',
            'picture',
            'alternative_number'
        )->get()->toArray();
        //dd($division_list);
        $deals = [];
        foreach ($dealer_list as $deal) {
            $data = [
                'id' => $deal['id'],
                'name' => $deal['name'],
                'code' => $deal['code'],
                'depo' => $deal['depo'],
            ];
            $deals[] = $data;
        }
        $painter_list = PainterUser::select(
            'id',
            'password',
            'status',
            'code',
            'name',
            'phone',
            'email',
            'depo',
            'picture',
            'alternative_number'
        )->get()->toArray();
        //dd($division_list);
        $painterss = [];
        foreach ($painter_list as $painter) {
            $data = [
                'id' => $painter['id'],
                'name' => $painter['name'],
                'code' => $painter['code'],
                'depo' => $painter['depo'],
            ];
            $painterss[] = $data;
        }

        if ($app_identifier == 'com.ets.elitepaint.dealer') {

            $division_list = SubGroup::select('id', 'subgroup_code', 'subgroup_name')->get()->toArray();
            //dd($division_list);
            $divisions = [];
            foreach ($division_list as $division) {
                $data = [
                    'id' => $division['id'],
                    'subgroup_name' => $division['subgroup_name'],
                ];
                $divisions[] = $data;
            }
        } elseif ($app_identifier == 'com.ets.elitepaint.painter') {

            $division_list = SubGroup::select('id', 'subgroup_code', 'subgroup_name')->get()->toArray();
            //dd($division_list);
            $divisions = [];
            foreach ($division_list as $division) {
                $data = [
                    'id' => $division['id'],
                    'subgroup_name' => $division['subgroup_name'],
                ];
                $divisions[] = $data;
            }
        }

        return response()->json(['data' => ['dealers' => $deals]], 200);
        //return response()->json(['data' => $divisions], 200);
    }

    public function get_transaction_history_by_date_range(Request $request)
    {

        $app_identifier = $request->app_identifier;
        $user_token = $request->header('USER-TOKEN');
        $starts = $request->start_date;
        $ends = $request->end_date;
        $now = Carbon::now();
        $month = $now->year . '-' . $now->month;
        $start = Carbon::parse($starts);
        $end = Carbon::parse($ends);
        //dd($start);
        $all_dates = [];

        // get all dates
        while ($start->lte($end)) {
            $all_dates[] = $start->copy();
            $start->addDay();
        }

        $all_dates = array_reverse($all_dates);


        // $dealer = DealerUser::where('dealer_code',$dealer_code)->where('user_token',$user_token)
        //                       ->select('id','password','status','app_identifier')->get()->last();
        //dd($user_token);

        if ($user_token) {
            if ($app_identifier == 'com.ets.elitepaint.dealer') {
                $dealer = DealerUser::where('user_token', $user_token)
                    ->select(
                        'id',
                        'password',
                        'status',
                        'code',
                        'name',
                        'phone'
                    )->get()->last();
                //dd($dealer);
                if ($dealer) {
                    //dd('ss');
                    $allData = [];
                    foreach ($all_dates as $date) {
                        $actual_date = $date->todateString();
                        $scanpoint_year = ScanPoint::where('dealer_id', $dealer['id'])
                            ->where('created_at', 'LIKE', '%' . $actual_date . '%')
                            ->select('id', 'bar_code_id')->get()->toArray();
                        $no_of_scan_month = count($scanpoint_year);
                        $all_total_year = 0;
                        //dd($dealer_list);
                        foreach ($scanpoint_year as $scanpoint) {
                            $barcode = BarCode::where('id', $scanpoint['bar_code_id'])
                                ->select('id', 'product_id', 'point')->get()->last();

                            $all_total_year += $barcode['point'];
                        }
                        $data = [
                            'date' => $date->todateString(),
                            'day' => $date->format('l'),
                            'enable' => 1,
                        ];
                        $allData[] = $data;
                    }
                    if (empty($allData)) {
                        //dd('s');
                        $datas = [
                            'message' => 'NO DATE AVAILABLE',
                        ];
                        return response()->json(['data' => $datas], 200);
                    }
                } else {
                    $data = [
                        'error' => 'USER TOKEN NOT MATCHED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
            } elseif ($app_identifier == 'com.ets.elitepaint.painter') {
                $dealer = PainterUser::where('user_token', $user_token)
                    ->select(
                        'id',
                        'password',
                        'status',
                        'code',
                        'name',
                        'phone'
                    )->get()->last();
                //dd($dealer);
                if ($dealer) {
                    $allData = [];
                    foreach ($all_dates as $date) {
                        $actual_date = $date->todateString();
                        $scanpoint_year = ScanPoint::where('painter_id', $dealer['id'])
                            ->where('created_at', 'LIKE', '%' . $actual_date . '%')
                            ->select('id', 'bar_code_id')->get()->toArray();
                        $no_of_scan_month = count($scanpoint_year);
                        $all_total_year = 0;
                        //dd($dealer_list);
                        foreach ($scanpoint_year as $scanpoint) {
                            $barcode = BarCode::where('id', $scanpoint['bar_code_id'])
                                ->select('id', 'product_id', 'point')->get()->last();

                            $all_total_year += $barcode['point'];
                        }
                        $data = [
                            'date' => $date->todateString(),
                            'day' => $date->format('l'),
                            'enable' => 1,
                        ];
                        $allData[] = $data;
                    }
                    if (empty($allData)) {
                        //dd('s');
                        $datas = [
                            'message' => 'NO DATE AVAILABLE',
                        ];
                        return response()->json(['data' => $datas], 200);
                    }
                } else {
                    $data = [
                        'error' => 'USER TOKEN NOT MATCHED.',
                    ];
                    return response()->json(['data' => $data], 200);
                }
            }
        } else {
            $data = [
                'error' => 'NO USER TOKEN SEND.',
            ];
            return response()->json(['data' => $data], 200);
        }

        return response()->json(['data' => $allData], 200);
    }

    public function updateVolumeTransfer(Request $request)
    {

        $erp_api_key = $request->erp_api_key;
        $volume_transfer_id = $request->volume_transfer_id;
        $quantity = $request->quantity;
        $dealer_point = $request->dealer_point;
        $painter_point = $request->painter_point;
        $soft_delete = $request->soft_delete;

        $fixed = new Constants();
        if ($fixed->geterp_api_key() == $erp_api_key) {
            $volume_transfer_exist = DB::table('volume_tranfers')
                ->where('id', $volume_transfer_id)->exists();
            if ($volume_transfer_exist) {
                $volume_transfer = DB::table('volume_tranfers')
                    ->where('id', $volume_transfer_id)
                    ->update([
                        'quantity' => $quantity,
                        'dealer_point' => $dealer_point,
                        'painter_point' => $painter_point,
                        'soft_delete' => $soft_delete,
                        'updated_at' => Carbon::now(),
                    ]);
                if ($volume_transfer) {
                    $data = [
                        'message' => 'Volume Transfer Updated',
                    ];
                } else {
                    $data = [
                        'error' => 'Something Wrong',
                    ];
                }
            } else {
                $data = [
                    'error' => 'Volume Transfer Not Found',
                ];
            }
        } else {
            $data = [
                'error' => 'Unauthorized Access.',
            ];
            return response()->json(['data' => $data], 200);
        }
        return response()->json(['data' => $data], 200);
    }

    public function updateBonusPoint(Request $request)
    {
        $created_at = Carbon::parse('2022-12-31');
        $painter_id = $request->painter_id;
        $dealer_id = $request->dealer_id;
        $remarks = $request->remarks;
        $bonus_point = $request->bonus_point;
        $erp_api_key = $request->erp_api_key;

        $fixed = new Constants();
        if ($fixed->geterp_api_key() == $erp_api_key) {
            $updated = DB::table('bonus_points')->insert([
                'dealer_id' => $dealer_id ?? NULL,
                'painter_id' => $painter_id ?? NULL,
                'remarks' => $remarks ?? NULL,
                'bonus_point' => $bonus_point ?? 0,
                'created_at' => $created_at,
            ]);

            if ($updated) {
                $data = [
                    'message' => 'Bonus Point Inserted',
                ];
            } else {
                $data = [
                    'error' => 'Something Wrong',
                ];
            }

        } else {
            $data = [
                'error' => 'Unauthorized Access.',
            ];
        }
        return response()->json(['data' => $data], 200);
    }
// painter information get
    public function painter_info(Request $request)
    {
        if ($request->painter_code) {
            $painter = PainterUser::where('code', $request->painter_code)->first();

          return $this->singlePainterData($painter);
        }

        $all_painter_data =[];

        $painters = PainterUser::query()->get();
        foreach ($painters as $painter){

            $all_painter_data[] = $this->singlePainterData($painter);;
        }
        return $all_painter_data;
    }

// single painter data
    public function singlePainterData($painter)
    {
        // for profile picture url
        if ($painter->picture_type=='api' && $painter->picture) {
            $profile_picture = 'http://club.elitepaint.com.bd/storage/' . $painter->picture;
        } else if ($painter->picture_type=='web' && $painter->picture) {
            $profile_picture = 'http://somriddhi.elitepaint.com.bd/images/' . $painter->picture;
        } else {
            $profile_picture = null;
        }
//for nid picture
        if ($painter->nid_picture_type=='api' && $painter->nid_picture) {
            $nid_picture = 'http://club.elitepaint.com.bd/storage/' . $painter->nid_picture;
        } else if ($painter->nid_picture_type=='web' && $painter->nid_picture) {
            $nid_picture = 'http://somriddhi.elitepaint.com.bd/images/' . $painter->nid_picture;
        } else {
            $nid_picture = null;
        }
        $data = [
            "id" => $painter->id,
            "elite_member_id" => $painter->elite_member_id,
            "member_type" => $painter->member_type,
            "member_type_point" => $painter->member_type_point,
            "painter_member_id" => $painter->painter_member_id,
            "dealer_id" => $painter->dealer_id,
            "dealer_name" => $painter->dealer ? $painter->dealer->name : 'No Data Found',
            "code" => $painter->code,
            "name" => $painter->name,
            "email" => $painter->email,
            "phone" => $painter->phone,
            "rocket_number" => $painter->rocket_number,
            "alternative_number" => $painter->alternative_number,
            "division_name" => $painter->division ? $painter->division->division : 'No Data Found',
            "division_" => $painter->division_id,
            "district_name" => $painter->district ? $painter->district->district : 'No Data Found',
            "district_id" => $painter->district_id,
            "thana_name" => $painter->thana ? $painter->thana->thana : 'No Data Found',
            "thana_id" => $painter->thana_id,
            "uid" => $painter->uid,
            "app_version" => $painter->app_version,
            "platform" => $painter->platform,
            "device" => $painter->device,
            "imei" => $painter->imei,
            "user_token" => $painter->user_token,
            "picture" => $profile_picture,
            "picture_type" => $painter->picture_type,
            "depo" => $painter->depo,
            "depo_id" => $painter->depo_id,
            "nid" => $painter->nid,
            "nid_picture" => $nid_picture,
            "nid_picture_type" => $painter->nid_picture_type,
            "disable" => $painter->disable,
            "status" => $painter->status,
            "register_by" => $painter->register_by,
            "updated_by" => $painter->updated_by,
            "soft_delete" => $painter->soft_delete,
            "approved_at" => $painter->approved_at,
            "created_at" => $painter->created_at,
            "updated_at" => $painter->updated_at,
            "membership" => $painter->membership
        ];
        return $data;

}
    public function sales_person_insert(Request $request){



        try {
            $emp_id = $request->emp_id;
            $name = $request->name;
            $depo_name = $request->depo_name;
            $designation = $request->designation;
            $phone = $request->phone;
            $salesperson = DB::table('sales_persons')->where('emp_id', $emp_id)->first();

            if ($salesperson) {
                DB::table('sales_persons')
                    ->where('emp_id', $emp_id)
                    ->update(['depo_name' => $depo_name, 'designation' => $designation]);
                return response()->json(['message' => 'Sales person updated successfully.'], 200);
            } else {
                DB::table('sales_persons')->insert(['emp_id' => $emp_id, 'name' => $name, 'phone' => $phone, 'depo_name' => $depo_name, 'designation' => $designation]);
            }
            return response()->json(['message' => 'Sales person inserted successfully.'], 200);
        }
        catch(\Exception $error){
            return $error->getMessage();
        }


    }

    public function doubleBarcodeCheck($user_type, $user_id, $code)
    {
        if ($user_type == 'dealer'){
            $scanned_today = DB::table('scan_points')->whereDate('created_at',Carbon::now()->format('Y-m-d'))->where([
                'dealer_id'=>$user_id,
                'bar_code'=>$code
            ])->exists();
        }
        if ($user_type == 'painter'){
            $scanned_today = DB::table('scan_points')->whereDate('created_at',Carbon::now()->format('Y-m-d'))->where([
                'dealer_id'=>$user_id,
                'bar_code'=>$code
            ])->exists();
        }

        if ($scanned_today){
            $sms = $request->content . ' this token number is already used today.';
            $sms_response = $this->sendSMS($sms, $request->from);
            $data = [
                'message' => 'Success',
            ];
            return response()->json(['data' => $data], 200);
        }
    }

}
