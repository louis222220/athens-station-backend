<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    protected $fillable = [
        'good_id','runner_id','start_station_id','des_station_id','status'
    ];


    public function good()
    {
        return $this->belongsTo('App\Good');
    }
}
