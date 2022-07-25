<?php

namespace App\CamCyber\Bot;

use App\Http\Controllers\Controller;
use App\Model\Order\Order;
use App\Model\Product\Product;
use Carbon\Carbon;
use Telegram\Bot\Laravel\Facades\Telegram;

class BotRegister extends Controller
{

public static function newRegister($user, $chanel = "JongTinh", $code = ""){
    $chatID = env('JONGTINH_CHANNEL_ID', '-1001224190361'); 

    $ID         = $user->id ?? '';
    $Phone      = $user->phone ?? '';
    $Chanel     = $chanel ?? '';
    $Code       = $code ?? '';
    $Name       = $user->name ?? '';
    $Created    =  Carbon::parse($user->created_at  ?? '')->format('d M Y h:i:s a');

    $text =
    "- ID: $ID\n" 
    ."-  Phone:$Phone\n" 
    ."- Chanel: $Chanel\n"
    ."- Code: $Code\n"
    ."- Name: $Name\n"
    ."- Created Date: $Created\n"

    ;
    $res = Telegram::sendMessage([
        'chat_id' => $chatID, 
        'text' => $text,  
        'parse_mode' => 'HTML'
    ]);
    return $res; 
}

public static function order($order, $chanel = "JongTinh", $products){
    $chatID = env('JONGTINH_CHANNEL_ID', '-1001764353648'); 

    $todayOrder = Order::select('id','status_id','customer_id','total_price','updated_at','ordered_at','total_received')
    ->whereDate('created_at', Carbon::today())
    ->count();

    $totalOrder = Order::select('id','status_id')
    ->where('status_id',1)
    ->count();

    $todayIncome = Order::where('status_id',1)
    ->whereDate('accepted_at', Carbon::today())
    ->sum('total_price');

    $outOfstock = Product::where('qty',0)->count();

    $data = [
        'totalOrderToday'=>$todayOrder,
        'totalOrder'     =>$totalOrder,
        'todayIncome'    => floatval($todayIncome)
    ];

    $status = "";

    if ($order->status_id == 3){
        $status = "Pending";
    }

    if( $order->status_id == 1){
        $status = "Accepted";
    }

    if($order->status_id == 2 ){
        $status = "Rejected";
    }

  

    $text =
    "=========================\n"
    ."                    New Order\n"
    ."=========================\n"
    ."- Invoice Code: $order->receipt_number\n"
    ."- Total Price:$order->total_price_khr Riel\n" 
    ."- Total Price: $order->total_price_usd $\n"
    ."- Discount: $order->discount (%)\n"
    ."- Ordered at: $order->ordered_at\n"
    ."- Status: $status\n"
    ."=========================\n"
    ."         Jong Tenh Daily Report\n"
    ."=========================\n"
    ."- Today Income: $data[todayIncome]\n"
    ."- Today Order: $data[totalOrderToday] times\n"
    ."- Out Of Stock: $outOfstock items\n"

    ;

    // return $text;
    $res = Telegram::sendMessage([
        'chat_id' => $chatID, 
        'text' => $text,  
        'parse_mode' => 'HTML'
    ]);
    return $res; 
}
  
 
}
