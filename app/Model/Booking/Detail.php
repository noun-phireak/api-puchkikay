<?php

namespace App\Model\Booking;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Detail extends Model
{
   	use SoftDeletes;

    protected $table = 'booking_detail';

    public function booking() {  //M-1
        return $this->belongsTo('App\Model\Booking\Booking', 'booking_id');
    }

    public function order(){
        return $this->belongsTo('App\Model\Order\Order', 'order_id');
    }

}