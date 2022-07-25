<?php

namespace App\Model\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Table extends Model
{
   	use SoftDeletes;

    protected $table = 'tables';

    public function booking(){
        return $this->hasMany('App\Model\Booking\Booking', 'table_id');
    }

}