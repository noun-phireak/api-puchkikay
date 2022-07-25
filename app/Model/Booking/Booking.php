<?php

namespace App\Model\Booking;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
   	use SoftDeletes;

    protected $table = 'booking';

    public function table() {  //M-1
        return $this->belongsTo('App\Model\Customer\Customer', 'table_id')
        ;
    }

    public function customer() {  //M-1
        return $this->belongsTo('App\Model\Customer\Customer', 'customer_id')
        ->with(['user:id,name,phone'])
        ;
    }

    public function status() {  //M-1
        return $this->belongsTo('App\Model\Setup\Status', 'status_id');
    }


    
}