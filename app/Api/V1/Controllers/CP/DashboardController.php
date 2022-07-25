<?php
namespace App\Api\V1\Controllers\CP;
use App\Api\V1\Controllers\ApiController;
use App\Model\Order\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;

use function PHPSTORM_META\map;

class DashboardController extends ApiController{

    function dashboard(Request $request){

        $todayOrder = Order::select('id','status_id','customer_id','total_price','updated_at','ordered_at','total_received')
        ->whereDate('accepted_at', Carbon::today())
        ->count();

        $totalOrder = Order::select('id','status_id')
        ->where('status_id',1)
        ->count();

        $todayIncome = Order::where('status_id',1)
        ->whereDate('accepted_at', Carbon::today())
        ->sum('total_price');

        $data = [
            'totalOrderToday'=>$todayOrder,
            'totalOrder'     =>$totalOrder,
            'todayIncome'    => floatval($todayIncome)
        ];

        return $data;
    }

    function graph(){

        $date = Carbon::now();


        $AYear = Order::select('id','status_id','customer_id','total_price','updated_at','ordered_at','total_received')
        ->whereDate('accepted_at',$date->subMonth(12))
        ->sum('total_price');

        $fiveMonth = Order::select('id','status_id')
        ->whereDate('accepted_at',$date->subMonth(1))
        ->sum('total_price');

        $lastMonth = Order::select('id','status_id')
        ->whereDate('accepted_at',$date->subMonth(1))
        ->sum('total_price');

        $todayIncome = Order::where('status_id',1)
        ->whereDate('accepted_at', Carbon::today())
        ->sum('total_price');

        $name = [
            'A Year Ago',
            'Five Months Ago',
            'Last Month',
            'Today Income'
        ];
        $value = [
            $AYear,
            $fiveMonth,
            $lastMonth,
            floatval($todayIncome)
        ];

        $data = array();


        for ($i = 0; $i<sizeof($name);$i++){

            for($j = 0; $j<sizeof($value); $j++){
                // $data = array_merge($data,['name'=>$name[$i]],['value'=>$value[$j]]);

                $data = array_push($data,(object)[
                    'name'  => $name[$i],
                    'value' => $value[$j]
                ]);

            }
            
        }
        

    }

   

}