<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Good extends Model
{
    protected $fillable = [
        'name', 'description', 'weight', 'photo_path', 'start_station_id',
        'des_station_id', 'now_station_id', 'price', 'status'
    ];

    protected $hidden = [
        'photo_path',
    ];

    protected $appends = ['photo_url',
        'start_station', 'des_station', 'now_station'
    ];


    protected $stationNames = [
        1 => '雅典',
        2 => '菲基斯',
        3 => '阿卡迪亞',
        4 => '斯巴達'
    ];

    public function getStartStationAttribute(){
        return [
            'id' => $this->start_station_id,
            'name' => $this->stationNames[$this->start_station_id]
        ];
    }


    public function getDesStationAttribute(){
        return [
            'id' => $this->des_station_id,
            'name' => $this->stationNames[$this->des_station_id]
        ];
    }


    public function getNowStationAttribute(){
        return [
            'id' => $this->now_station_id,
            'name' => $this->stationNames[$this->now_station_id]
        ];
    }


    public function getPhotoUrlAttribute()
    {
        if (Storage::exists($this->photo_path)){
            $publicPath = 'storage/' . substr($this->photo_path, 7);
            return url($publicPath);
        }
        else {
            return null;
        }
        
    }


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
