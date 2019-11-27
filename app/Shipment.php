<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Good;

class Shipment extends Model
{
    protected $fillable = [
        'good_id','runner_id','start_station_id','des_station_id','status','good_name'
    ];

    protected $appends = ['weight', 'price', 'photo_url',
        'start_station_name', 'des_station_name',
        'distance'
    ];

    public function good()
    {
        return $this->belongsTo('App\Good');
    }


    public function getDistanceAttribute()
    {
        $distancesBetweenStation = [100, 200, 300];
        // 雅典-菲基斯, 菲基斯-阿卡迪亞, 阿卡迪亞-斯巴達

        $distanceIndex = min($this->start_station_id, $this->des_station_id);
        return $distancesBetweenStation[$distanceIndex - 1];
    }


    public function getStartStationNameAttribute()
    {
        return Station::find($this->start_station_id)->name;
    }


    public function getDesStationNameAttribute()
    {
        return Station::find($this->des_station_id)->name;
    }

    
    public function getPhotoUrlAttribute()
    {
        $good = Good::find($this->good_id);
        return $good->photo_url;
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
