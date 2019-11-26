<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    protected $fillable = [
        'name',
    ];

    protected $appends = ['level', 'totalWeight'];


    public function start_from_here_goods(){
        return  $this->hasMany('App\Good','start_station_id');
    }

    public function des_to_here_goods(){
        return  $this->hasMany('App\Good','des_station_id');
    }

    public function now_here_goods(){
        return  $this->hasMany('App\Good','now_station_id');
    }


    protected function tmp()
    {
        return 200;
    }

    public function getLevelAttribute()
    {
        $levels = [1, 2, 3 ,4];
        $levelInterval = [100, 500, 1000, 5000];
        // level 1 : 0~99, level 2 : 100~499 ...
        
        // FIXME: 
        return $levels[
            rand(0, 3) % count($levels)
        ];
    }

    public function getTotalWeightAttribute()
    {   
        // FIXME: 
        $tmpTotalWeights = [10, 120, 700, 1200, 6500];
        return $tmpTotalWeights[
            rand(0, 5000) % count($tmpTotalWeights)
        ];
    }
}

