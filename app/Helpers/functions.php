<?php

use Carbon\Carbon;
use App\VolumeTranfer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use PhpParser\Node\Expr\Cast\Object_;

function getEncrypt($id)
{
    $encrypt = Crypt::encrypt($id);
    return $encrypt;
}
function getDecrypt($id)
{
    $decrypt = Crypt::decrypt($id);
    return $decrypt;
}
function basegroup_stock($dealer_id, $basegroup_id)
{


    $openning_stock = DB::table('year_stocks')
        ->where('dealer_id', $dealer_id)
        ->where('basegroup_id', $basegroup_id)
        ->sum('opening_balance');

    $current_stock = DB::table('invoices')
        ->where('stock_closed', false)
        ->where('dealer_id', $dealer_id)
        ->where('basegroup_id', $basegroup_id)
        ->whereYear('created_at', Carbon::now()->year)
        ->sum('total_volume');

    $stock_out = DB::table('volume_tranfers')
        ->where('stock_closed', false)
        ->where('dealer_id', $dealer_id)
        ->where('basegroup_id', $basegroup_id)
        ->where('status', '!=', 2)
        ->where('soft_delete', 1)
        ->whereYear('created_at', Carbon::now()->year)
        ->sum('quantity');

    $baseGroup = DB::table('basegroups')->where('id', $basegroup_id)->first();
    $stock = ($openning_stock + $current_stock);

    $transferable_stock = $baseGroup->delivery_percentage == 0 ? 0 : (($stock) * $baseGroup->delivery_percentage) / 100;
    return round(($transferable_stock - $stock_out), 2);
}
function volume_transfer_condition($dealer_id, $painter_id, $total_quantity)
{
    $painter_limit = env('PAINTER_TRANSFER') ?? 1500;
    $dealer_limit = env('DEALER_TRANSFER') ?? 3000;
    $dealer_limit_times = env('TRANSFER_TIMES') ?? 3;

    $today_dealer_transfer = DB::table('volume_tranfers')->where('dealer_id', $dealer_id)->whereDate('created_at', '=', date('Y-m-d'))->sum('quantity');
    $today_dealer_transfer_time = DB::table('volume_tranfers')->where('dealer_id', $dealer_id)->whereDate('created_at', '=', date('Y-m-d'))->groupBy('code2')->get()->count();
    $today_painter_transfer = DB::table('volume_tranfers')->where('dealer_id', $dealer_id)->where('painter_id', $painter_id)->whereDate('created_at', '=', date('Y-m-d'))->sum('quantity');

    $today_dealer_total = $today_dealer_transfer + $total_quantity;
    $today_painter_total = $today_painter_transfer + $total_quantity;
    $today_total_transfer_time = $today_dealer_transfer_time + 1;

    if ($today_dealer_total >  $dealer_limit) {
        return redirect()->back()->withInput()->withErrors('You can not transfer more then ' . $dealer_limit . ' LTR in current day.');
    }
    if ($today_total_transfer_time > $dealer_limit_times) {
        return redirect()->back()->withInput()->withErrors('You can not transfer more then ' . $dealer_limit_times . ' times.');
    }
    if ($today_painter_total > $painter_limit) {
        return redirect()->back()->withInput()->withErrors('You can not transfer to this painter more then ' . $painter_limit . ' LTR in current day.');
    }
}

function volume_transfer_point($dealer_id, $painter_id, $subgroup_id, $quantity)
{
    $painter_user = DB::table('painter_users')->where('id', $painter_id)->first();
    $dealer_user = DB::table('dealer_users')->where('id', $dealer_id)->first();

//    $dealer_points = DB::table('points')->where('elite_member_id', $dealer_user->member_type_id)->where('product_id', $subgroup_id)->where('end_date', '>=', Carbon::now()->format('Y-m-d'))->where('soft_delete', 1)->latest()->first();
//    $painter_points = DB::table('points')->where('elite_member_id', $painter_user->elite_member_id)->where('product_id', $subgroup_id)->where('end_date', '>=', Carbon::now()->format('Y-m-d'))->where('soft_delete', 1)->latest()->first();

    $dealer_points = DB::table('points')->where('elite_member_id', $dealer_user->member_type_id)->where('product_id', $subgroup_id)->where('start_date','<=',Carbon::now()->format('Y-m-d'))->where('end_date', '>=', Carbon::now()->format('Y-m-d'))->where('soft_delete', 1)->get();
    $painter_points = DB::table('points')->where('elite_member_id', $painter_user->elite_member_id)->where('product_id', $subgroup_id)->where('start_date', '<=', Carbon::now()->format('Y-m-d'))->where('end_date', '>=', Carbon::now()->format('Y-m-d'))->where('soft_delete', 1)->get();
    if(count($dealer_points) > 1 ||count($painter_points) > 1) {
        $data = [
            'message' => 'Please contact with Administrator',
        ];
        return (object) $data;
    }
    else{
        $data = [
            'dealer_member_type_id' => $dealer_user->member_type_id,
            'painter_member_type_id' => $painter_user->elite_member_id,
            'quantity' => $quantity,
            'dealer_point' => count($dealer_points) == 1 ? ($dealer_points[0]->point * $quantity) : 0,
            'painter_point' => count($painter_points) == 1 ? ($painter_points[0]->point * $quantity) : 0,
        ];
        return (object) $data;
    }

}


function dealer_level($dealer_id)
{

    $dealer_user = DB::table('dealer_users')
        ->where('id', $dealer_id)
        ->first();

    $elite_member = DB::table('elite_members')
        ->where('category', $dealer_user->member_type)
        ->first();
    return $elite_member;
}
function painter_level($painter_id)
{
    $painter_user = DB::table('painter_users')
        ->where('id', $painter_id)
        ->first();

    $elite_member = DB::table('elite_members')
        ->where('id', $painter_user->elite_member_id)
        ->first();
    return $elite_member;
}
function painter_level_update($painter_id)
{
    $last_year = date('Y') - 1;
    $current_year = date('Y');

    $total_bonus_point = DB::table('bonus_points')
        ->where('painter_id', $painter_id)
        ->where('soft_delete', '=', 1)
        ->whereIn(DB::raw('year(created_at)'), [$last_year, $current_year])
        ->sum('bonus_point');

    $total_volume_tranfers_point = DB::table('volume_tranfers')
        ->where('painter_id', $painter_id)
        ->where('status', '!=', 2)
        ->whereIn(DB::raw('year(created_at)'), [$last_year, $current_year])
        ->where('soft_delete', '=', 1)
        ->sum('painter_point');

    $total_scan_point = DB::table('scan_points')
        ->where('painter_id', $painter_id)
        ->whereIn(DB::raw('year(created_at)'), [$last_year, $current_year])
        ->sum('point');

    $total_point = $total_bonus_point + $total_volume_tranfers_point + $total_scan_point;

    $elite_member = DB::table('elite_members')
        ->where('category', 'PNT')
        ->where('from_point', '<=', $total_point)
        ->where('to_point', '>=', $total_point)
        ->first();
    if ($elite_member) {
        DB::table('painter_users')
            ->where('id', $painter_id)
            ->update([
                'elite_member_id' => $elite_member->id,
                'member_type' => $elite_member->member_type,
                'member_type_point' => $total_point,
                'updated_at' => Carbon::now(),
            ]);
    }
}
function level_check($painter_id)
{
    $last_year = date('Y') - 1;
    $current_year = date('Y');

    $total_bonus_point = DB::table('bonus_points')
        ->where('painter_id', $painter_id)
        ->where('soft_delete', '=', 1)
        ->whereIn(DB::raw('year(created_at)'), [$last_year, $current_year])
        ->sum('bonus_point');

    $total_volume_tranfers_point = DB::table('volume_tranfers')
        ->where('painter_id', $painter_id)
        ->where('status', '!=', 2)
        ->whereIn(DB::raw('year(created_at)'), [$last_year, $current_year])
        ->where('soft_delete', '=', 1)
        ->sum('painter_point');

    $total_scan_point = DB::table('scan_points')
        ->where('painter_id', $painter_id)
        ->whereIn(DB::raw('year(created_at)'), [$last_year, $current_year])
        ->sum('point');

    $total_point = $total_bonus_point + $total_volume_tranfers_point + $total_scan_point;

    $elite_member = DB::table('elite_members')
        ->where('category', 'PNT')
        ->where('from_point', '<=', $total_point)
        ->where('to_point', '>=', $total_point)
        ->first();

    return $elite_member;
}
function generateDPUCode($depo_code=0000)
{
    $DPU_Code = 'DPU-'.$depo_code;
    $array =  DB::table('volume_tranfers')->where('code','like','%'.$DPU_Code.'%')->distinct('code')->pluck('code');
    $last_value =  collect($array)->map(function ($q) use ($DPU_Code){
        $removed_dpu_from_code =  str_ireplace($DPU_Code.'-','',$q);
        return (int) substr($removed_dpu_from_code, 0, strpos($removed_dpu_from_code, "/"));
    })->toArray();
    $code =  max($last_value)+1;
    return 'DPU-' . $depo_code . '-' . $code . '/' . date('Y');
}
//bellow this function usese painter_dashboard
function total_current_points($painter_id)
{

    $total_bonus_point = DB::table('bonus_points')
        ->where('painter_id', $painter_id)
        ->where('soft_delete', '=', 1)
        ->whereYear('created_at', Carbon::now()->year)
        ->sum('bonus_point');

    $total_volume_tranfers_point = DB::table('volume_tranfers')
        ->where('painter_id', $painter_id)
        ->where('status', '!=', 2)
        ->where('soft_delete', '=', 1)
        ->whereYear('created_at', Carbon::now()->year)
        ->sum('painter_point');

    $total_scan_point = DB::table('scan_points')
        ->where('painter_id', $painter_id)
        ->whereYear('created_at', Carbon::now()->year)
        ->sum('point');

    $total_point = $total_bonus_point + $total_volume_tranfers_point + $total_scan_point;



    return $total_point;
}
