<?php


namespace App\Classes;


use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class PointUpdate
{

    public static function dealer_total_earning_point($dealer_id, $point)
    {
        $dealer = DB::table('dealer_users')
            ->where('id', $dealer_id)
            ->first();
        if ($dealer) {

            $update_total_earn_point = $dealer->total_earn_point + $point;

            DB::table('dealer_users')
                ->where('id', $dealer_id)
                ->update(['total_earn_point' => $update_total_earn_point]);
        }
    }
    public static function painter_total_earning_point($painter_id, $point)
    {
        $painter = DB::table('painter_users')
            ->where('id', $painter_id)
            ->first();
        if ($painter) {

            $update_total_earn_point = $painter->total_earn_point + $point;

            DB::table('painter_users')
                ->where('id', $painter_id)
                ->update([
                    'total_earn_point' => $update_total_earn_point,
                    'member_type' => self::painter_level($painter_id)
                ]);
        }
    }
    public static function painter_level($painter_id)
    {
        $last_year = date('Y') - 1;
        $current_year = date('Y');

        $total_bonus_point = DB::table('bonus_points')
            ->where('painter_id', $painter_id)
            ->where('soft_delete', '=', 1)
            ->whereIn(DB::raw('year(created_at)'), [$last_year, $current_year])
            ->sum('bonus_point');

        $total_volume_transfer_point = DB::table('volume_tranfers')
            ->where('painter_id', $painter_id)
            ->where('status', '!=', 2)
            ->whereIn(DB::raw('year(created_at)'), [$last_year, $current_year])
            ->where('soft_delete', '=', 1)
            ->sum('painter_point');

        $total_scan_point = DB::table('scan_points')
            ->where('painter_id', $painter_id)
            ->whereIn(DB::raw('year(created_at)'), [$last_year, $current_year])
            ->sum('point');

        $total_point = $total_bonus_point + $total_volume_transfer_point + $total_scan_point;

        $elite_member = DB::table('elite_members')
            ->where('category', 'PNT')
            ->where('from_point', '<=', $total_point)
            ->where('to_point', '>=', $total_point)
            ->first();
        return $elite_member->member_type;
    }
    public static function painter_level_update($painter_id)
    {
        $last_year = date('Y') - 1;
        $current_year = date('Y');

        $total_bonus_point = DB::table('bonus_points')
            ->where('painter_id', $painter_id)
            ->where('soft_delete', '=', 1)
            ->whereDate('created_at','<=','2022-12-31')
            ->sum('bonus_point');

        $total_volume_tranfers_point = DB::table('volume_tranfers')
            ->where('painter_id', $painter_id)
            ->where('status', '!=', 2)
            ->whereYear('created_at', Carbon::now()->year)
            ->where('soft_delete', '=', 1)
            ->sum('painter_point');

        $total_scan_point = DB::table('scan_points')
            ->where('painter_id', $painter_id)
//            ->whereIn(DB::raw('year(created_at)'), [$last_year, $current_year])
            ->whereDate('created_at','<=','2022-12-31')
            ->sum('point');

//        $total_point = $total_bonus_point + $total_volume_tranfers_point + $total_scan_point;
        $total_point = $total_volume_tranfers_point ;

        $painter = DB::table('painter_users')->where('id',$painter_id)->first();

        $elite_member = DB::table('elite_members')
            ->where('category', 'PNT')
            ->where('from_point', '<=', $painter->member_type_point)
            ->where('to_point', '>=', $painter->member_type_point)
            ->first();


        if ($painter->elite_member_id < $elite_member->id)
        {
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
        }else{
            if ($elite_member) {
                DB::table('painter_users')
                    ->where('id', $painter_id)
                    ->update([
                        'member_type_point' => $total_point,
                        'updated_at' => \Carbon\Carbon::now(),
                    ]);
            }
        }

    }
}
