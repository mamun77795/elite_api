<?php


namespace App\Classes;


use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\BaseGroup;
use App\SubGroup;
use App\VolumeTranfer;

class Stock
{
    public static function dealer_wise_basegroup_stocks($dealer_id)
    {
        $last_year =  Carbon::now()->year-1;
        $data = [];
        $invoices = DB::table('invoices')
//            ->where('stock_closed', false)
            ->where('dealer_id', $dealer_id)
//            ->whereYear('created_at', Carbon::now()->year)
            ->get()->groupBy('basegroup_id');
        foreach ($invoices as $key => $invoice) {
            $in_total = 0;
            foreach ($invoice as $in) {
                if (!$in->stock_closed && Carbon::parse($in->created_at)->year == Carbon::now()->year) {
                    $in_total += $in->total_volume;
                }
            }
            $stock_out = DB::table('volume_tranfers')
                ->where('stock_closed', false)
                ->where('dealer_id', $dealer_id)
                ->where('basegroup_id', $key)
                ->where('status', '!=', 2)
                ->where('soft_delete', 1)
                ->whereYear('created_at', Carbon::now()->year)
                ->sum('quantity');

            $closed_stock = DB::table('year_stocks')
                ->where('dealer_id', $dealer_id)
                ->where('basegroup_id', $key)
                ->where('year',$last_year)
                ->sum('transferable_stock');

            $baseGroup = DB::table('basegroups')->where('id', $key)->first();

            $transferable_stock = ($in_total);

            $check_minus_stock = round($transferable_stock , 2) < 1 ? 0 : round($transferable_stock , 2);

            $available_stock=$baseGroup->delivery_percentage == 0 ? 0 :  (($check_minus_stock * $baseGroup->delivery_percentage) / 100) + $closed_stock ;

            $data[] = [
                'id' => $key,
                'subgroup_name' => $baseGroup ? $baseGroup->basegroup_name : '-',
                'stock' =>round(($available_stock -$stock_out), 2) ,
            ];
        }
        return $data;

        $data = [];

        $invoices = DB::table('invoices')->where('dealer_id', $dealer_id)->take(50)->get();
        foreach ($invoices as $invoice) {
            $data[] = [
                'id' => $invoice->basegroup_id,
                'subgroup_name' => DB::table('basegroups')->where('basegroup_id', $invoice->basegroup_id)->first()->basegroup_name,
                'stock' => self::basegroup_stock($invoice->basegroup_id, $dealer_id),
            ];
        }

        return $data;
    }

    public static function dealer_wise_basegroup_stocks2($dealer_id)
    {
        $last_year =  Carbon::now()->year-1;
        $data = [];
        $invoices = DB::table('invoices')
//            ->where('stock_closed', false)
            ->where('dealer_id', $dealer_id)
//            ->whereYear('created_at', Carbon::now()->year)
            ->get()->groupBy('basegroup_id');
        foreach ($invoices as $key => $invoice) {
            $in_total = 0;
            foreach ($invoice as $in) {
                if (!$in->stock_closed && Carbon::parse($in->created_at)->year == Carbon::now()->year) {
                    $in_total += $in->total_volume;
                }
            }
            $stock_out = DB::table('volume_tranfers')
                ->where('stock_closed', false)
                ->where('dealer_id', $dealer_id)
                ->where('basegroup_id', $key)
                ->where('status', '!=', 2)
                ->where('soft_delete', 1)
                ->whereYear('created_at', Carbon::now()->year)
                ->sum('quantity');

            $closed_stock = DB::table('year_stocks')
                ->where('dealer_id', $dealer_id)
                ->where('basegroup_id', $key)
                ->where('year',$last_year)
                ->sum('transferable_stock');

            $baseGroup = DB::table('basegroups')->where('id', $key)->first();
            $transferable_stock = $in_total;
            $check_minus_stock = round($transferable_stock, 2) < 1 ? 0 : round($transferable_stock , 2);
            $purchase_stock=$baseGroup->delivery_percentage == 0 ? 0 :  (($in_total * $baseGroup->delivery_percentage) / 100);
            $available_stock=$baseGroup->delivery_percentage == 0 ? 0 :  $purchase_stock + $closed_stock;

            $data[] = [
                'product_id' => $key,
                'product_name' => DB::table('basegroups')->where('id', $key)->first()->basegroup_name,
                'product_code' => DB::table('basegroups')->where('id', $key)->first()->basegroup_code,
                'purchase' => round($purchase_stock,2),
                'sales' => round($stock_out,2),
                'stock' => round(($available_stock-$stock_out), 2),
            ];

        }
        return $data;

        $data = [];

        $invoices = DB::table('invoices')->where('dealer_id', $dealer_id)->take(50)->get();
        foreach ($invoices as $invoice) {
            $data[] = [
                'id' => $invoice->basegroup_id,
                'subgroup_name' => DB::table('basegroups')->where('basegroup_id', $invoice->basegroup_id)->first()->basegroup_name,
                'stock' => self::basegroup_stock($invoice->basegroup_id, $dealer_id),
            ];
        }

        return $data;
    }

    public static function dealer_wise_stocks($dealer_id)
    {
        $invoices = DB::table('invoices')
            ->where('dealer_id', $dealer_id)
            ->where('stock_closed', false)
            ->whereYear('created_at', Carbon::now()->year)
            ->get()->groupBy('basegroup_id');
        if(count($invoices)>0){
            foreach ($invoices as $key => $invoice) {
                $in_total = 0;
                foreach ($invoice as $in) {
                    $in_total += $in->total_volume;
                }
                $stock_out = DB::table('volume_tranfers')
                    ->where('stock_closed', false)
                    ->where('dealer_id', $dealer_id)
                    ->where('basegroup_id', $key)
                    ->where('status', '!=', 2)
                    ->where('soft_delete', 1)
                    ->sum('quantity');
                $stock = $in_total - $stock_out;
                $data[] = [
                    'product_id' => $key,
                    'product_name' => DB::table('basegroups')->where('id', $key)->first()->basegroup_name,
                    'product_code' => DB::table('basegroups')->where('id', $key)->first()->basegroup_name,
                    'purchase' => round($in_total,2),
                    'sales' => round($stock_out,2),
                    'stock' => round($stock,2),
                ];
            }
            return $data;
        }else{
            $data=[];
            return $data;
        }

    }
    public static function basegroup_stock($basegroup_id, $dealer_id)
    {
        $stock_in = DB::table('invoices')
            ->where('stock_closed', false)
            ->whereYear('created_at', Carbon::now()->year)
            ->where(['dealer_id' => $dealer_id, 'basegroup_id' => $basegroup_id])->sum('total_volume');
        $stock_out = DB::table('volume_tranfers')
            ->where('stock_closed', false)
            ->where('dealer_id', $dealer_id)
            ->where('basegroup_id', $basegroup_id)
            ->where('status', '!=', 2)
            ->where('soft_delete', 1)
            ->sum('quantity');

        $baseGroup = DB::table('basegroups')->where('id', $basegroup_id)->first();
        $available_stock=$baseGroup->delivery_percentage == 0 ? 0 :  (($stock_in * $baseGroup->delivery_percentage) / 100) ;
        if ($available_stock) {
            $stock = $available_stock - $stock_out;
            return  round($stock, 2);
        } else {
            return 0;
        }
    }
//user for get_dpu_details
    public static function basegroup_transferable_stock($basegroup_id, $dealer_id,$dpu_date)
    {
        $last_year =  Carbon::parse($dpu_date)->year-1;
        $stock_in = DB::table('invoices')
            ->where('stock_closed', false)
            ->where(['dealer_id' => $dealer_id, 'basegroup_id' => $basegroup_id])
            ->whereDate('created_at', ">=", Carbon::now()->startOfYear()->format('Y-m-d'))
            ->whereDate('created_at', "<=",Carbon::parse($dpu_date)->format('Y-m-d'))->sum('total_volume');
        $stock_out = DB::table('volume_tranfers')
            ->where('stock_closed', false)
            ->whereYear('created_at', Carbon::now()->year)
            ->where('dealer_id', $dealer_id)
            ->where('basegroup_id', $basegroup_id)
            ->where('status', '!=', 2)
            ->where('soft_delete', 1)
            ->sum('quantity');
        $closed_stock = DB::table('year_stocks')
            ->where('dealer_id', $dealer_id)
            ->where('year',$last_year)
            ->where('basegroup_id', $basegroup_id)
            ->sum('opening_balance');

        $baseGroup = DB::table('basegroups')->where('id', $basegroup_id)->first();
        $available_stock = ($stock_in);
        $check_minus_stock = round($available_stock, 2) < 1 ? 0 : round($available_stock, 2);
        $transferable_stock = $baseGroup->delivery_percentage == 0 ? 0 : (($check_minus_stock * $baseGroup->delivery_percentage) / 100) + $closed_stock;

        if ($transferable_stock) {
            return  round(($transferable_stock -$stock_out),2);
        } else {
            return 0;
        }
    }

    public static function initialInfoProductForDealer($dealer){

        $product_list = [];
        $basegroup_list = BaseGroup::select('id','basegroup_code','basegroup_name')->where('soft_delete',1)->get()->toArray();
        //  dd($basegrup_list);
        foreach ($basegroup_list as $basegroup) {
            $data = [
                'id' => $basegroup['id'],
                'subgroup_name' => $basegroup['basegroup_name'],
                'stock' => 0,
//                'stock' => self::basegroup_stock($basegroup['id'],$dealer['id']),
            ];
            $product_list[] = $data;
        }
        return $product_list;

    }

    public static function all_basegroup_stocks()
    {
        $data = [];

        $groups = DB::table('basegroups')->where('soft_delete',1)->get();
        foreach ($groups as $group){
            $data[] = [
                'id' => $group->id,
                'subgroup_name' =>$group->basegroup_name,
                'stock' => 0,
            ];
        }
        return $data;
    }

}
