<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Good;

class Shipment extends Model
{
    protected $fillable = [
        'good_id','runner_id','start_station_id','des_station_id','status','good_name'
    ];

    protected $appends = ['weight', 'price'];

    public function good()
    {
        return $this->belongsTo('App\Good');
    }


    public function getWeightAttribute()
    {
        $good = Good::find($this->attributes['good_id']);
        return (int) $good->weight;
    }


    public function getPriceAttribute()
    {
        return 20;
    }


    // protected $appends = ['goodName'];

    // public function getGoodNameAttribute()
    // {
    //     return something;
    // }
}
