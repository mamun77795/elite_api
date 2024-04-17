<?php

namespace App\Http\Controllers;

use App\Constants;
use App\DealerUser;
use App\PainterUser;
use App\PointTranfer;
use App\PointTransfer;
use App\RedeemPoint;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class PointTransferApi extends Controller
{
    public function getPointTransfer(Request $request)
    {
        // return response()->json($request);

        $app_identifier = $request->app_identifier;
        $erp_api_key = $request->erp_api_key;
        $fixed = new Constants();
        $user_token = $request->header('USER-TOKEN');

        if ($fixed->geterp_api_key() == $erp_api_key) {
            if ($user_token) {
                if ($app_identifier == 'com.ets.elitepaint.painter') {
                    $painter = PainterUser::where('user_token', $user_token)
                        ->get()->last();
                    if ($painter) {
                        $data = PointTransfer::where('painter_id', $painter->id)->get();
                        return response()->json($data, 200);
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
        } else {
            $data = [
                'error' => 'Unauthorized Access.',
            ];
            return response()->json(['data' => $data], 200);
        }
    }

    public function sendPoint(Request $request)
    {
        $app_identifier = $request->app_identifier;
        $erp_api_key = $request->erp_api_key;
        $fixed = new Constants();
        $user_token = $request->header('USER-TOKEN');

        if ($fixed->geterp_api_key() == $erp_api_key) {
            if ($user_token) {
                if ($app_identifier == 'com.ets.elitepaint.painter') {
                    $painter = PainterUser::where('user_token', $user_token)
                        ->get()->last();

                    if ($painter) {
                        $painter_id = $painter->id;
                        $painter_code = $painter->code;

                        $dealer_id = $request->dealer_id;
                        $dealer = DealerUser::where('id', $dealer_id)->first();
                        $dealer_code = $dealer->code;

                        $month = $request->month;
                        $year = $request->year;

                        $pending = PointTransfer::where('painter_id', $painter_id)
                            ->where('status', 0)->count();

                        if ($pending) {
                            $data = ['data' => 'Pending Previous approval'];
                            return response()->json($data, 200);
                        }

                        $redeem_point = RedeemPoint::where('painter_id', $painter_id)
                            ->whereRaw('MONTH(start_date) = ?', [$month])
                            ->whereRaw('YEAR(start_date) = ?', [$request->year])
                            ->where('status', 2)
                            ->where('redeem_point', '!=', 0)
                            ->first();

                        if (!$redeem_point) {
                            $data = ['point' => 0];
                            return response()->json($data, 200);
                        }

                        $redeem_point = $redeem_point->redeem_point;

                        $transferred_point = PointTransfer::where('painter_id', $painter_id)
                            ->where('status', '!=', 1)
                            ->where('month', $month)->sum('point');

                        $point = $redeem_point - $transferred_point;

                        // return response()->json(['data'=>$transferred_point], 200);

                        if ($point >= $request->transfer_point && $point != 0) {
                            $transaction_id = $this->generateRandomString();
                            $data = [
                                'dealer_id' =>  $dealer_id,
                                'dealer_code' =>  $dealer_code,
                                'painter_id' =>  $painter_id,
                                'painter_code' =>  $painter_code,
                                'transaction_id' => $transaction_id,
                                'point' => $request->transfer_point,
                                'month' => $request->month,
                                'year' => $year,
                                'remarks' => $request->remark,
                                'created_at' => Carbon::now()
                            ];

                            DB::table('point_transfers')->insert($data);

                            $data = ['data' => 'point successfully transferred'];
                            return response()->json($data, 200);
                        } else {
                            $data = ['data' => 'Insufficient Point: ' . $point];
                            return response()->json($data, 200);
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
        } else {
            $data = [
                'error' => 'Unauthorized Access.',
            ];
            return response()->json(['data' => $data], 200);
        }
    }

    public function generateRandomString($prefix = false, $length = 8)
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
        $exists = DB::table('point_transfers')->where('transaction_id', $randomString)->exists();
        if ($exists) {
            $this->generateRandomString();
        }
        return strtoupper($randomString);
    }

    public function approval(Request $request)
    {
        $app_identifier = $request->app_identifier;
        $erp_api_key = $request->erp_api_key;
        $fixed = new Constants();
        $user_token = $request->header('USER-TOKEN');


        if ($fixed->geterp_api_key() == $erp_api_key) {
            if ($user_token) {
                if ($app_identifier == 'com.ets.elitepaint.dealer') {
                    $id = $request->transfer_id;
                    DB::table('point_transfers')->where('id', $id)->update(['status' => 2, 'updated_at' => Carbon::now()]);
                    $transfer = DB::table('point_transfers')->select('*')->where('id', $id)->first();
                    $redeem_point = RedeemPoint::where('painter_id', $transfer->painter_id)
                        ->whereRaw('MONTH(start_date) = ?', [$transfer->month])
                        ->whereRaw('YEAR(start_date) = ?', [$transfer->year])
                        ->where('status', 2)
                        ->sum('redeem_point');

                    $t_point = DB::table('point_transfers')->where('painter_id', $transfer->painter_id)->where('status', 2)->sum('point');
                    $point = $redeem_point - $t_point;

                    $data = ['status' => 'Point transfer approved'];

                    if ($point == 0) {
                        RedeemPoint::whereRaw('MONTH(start_date) = ?', [$transfer->month])->whereRaw('YEAR(start_date)', [$transfer->year])->update(['status' => 1]);
                    }
                    return response()->json($data, 200);
                }
            } else {
                $data = [
                    'error' => 'NO USER TOKEN SEND.',
                ];
                return response()->json(['data' => $data], 200);
            }
        } else {
            $data = [
                'error' => 'Unauthorized Access.',
            ];
            return response()->json(['data' => $data], 200);
        }
    }

    public function cancel(Request $request)
    {
        $app_identifier = $request->app_identifier;
        $erp_api_key = $request->erp_api_key;
        $fixed = new Constants();
        $user_token = $request->header('USER-TOKEN');

        if ($fixed->geterp_api_key() == $erp_api_key) {
            if ($user_token) {
                if ($app_identifier == 'com.ets.elitepaint.painter' || $app_identifier == 'com.ets.elitepaint.dealer') {
                    $id = $request->transfer_id;
                    DB::table('point_transfers')->where('id', $id)->update(['status' => 1]);

                    $data = [
                        'data' => 'Canceled',
                    ];

                    return response()->json($data, 200);
                }
            } else {
                $data = [
                    'error' => 'NO USER TOKEN SEND.',
                ];

                return response()->json(['data' => $data], 200);
            }
        } else {
            $data = [
                'error' => 'Unauthorized Access.',
            ];

            return response()->json(['data' => $data], 200);
        }
    }
}