<?php

namespace App\Services;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\User;
use Charts;
use App\Activity;
use DB;
use Auth;
class ChartsService{

    public function getCharts(){
        $id = Auth::user()->id;

        $data = Activity::where('users_id', $id)->get()->groupBy('date_time');
        $label = [];
        $dataset = [];
        foreach ($data as $key => $value) {

            $label[] = $key;

            $login_counter = 0;

            foreach ($value as $item) {
                if($item->status === 1){
                    $login_counter++;
                }
            }

            $dataset[] = $login_counter;

        }

        $name = Auth::user()->name;


        $chart = Charts::multi('areaspline', 'highcharts')
            ->title('My login chart')
            ->colors(['#47a8e8'])
            ->labels($label)
            ->dataset($name, $dataset);

        return $chart;
    }

}