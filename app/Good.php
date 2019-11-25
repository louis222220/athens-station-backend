<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Good extends Model
{
    protected $fillable = [
        'name', 'description', 'weight', 'photo_path', 'start_station_id',
        'des_station_id', 'now_station_id', 'price', 'status'
    ];

    protected $hidden = [
        'photo_path',
    ];

    public function start_station()
    {
        return  $this->belongsTo('App\Station', 'start_station_id');
    }

    public function des_station()
    {
        return  $this->belongsTo('App\Station', 'des_station_id');
    }

    public function now_station()
    {
        return  $this->belongsTo('App\Station', 'now_station_id');
    }
}
