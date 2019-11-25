<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    protected $fillable = [
        'name',
    ];

    // public function goods(){

    //     return  $this->hasMany('App\Good');
    //  }


    public function start_from_here_goods(){
        return  $this->hasMany('App\Good','start_station_id');
    }

    public function des_to_here_goods(){
        return  $this->hasMany('App\Good','des_station_id');
    }

    public function now_here_goods(){
        return  $this->hasMany('App\Good','now_station_id');
    }
}

