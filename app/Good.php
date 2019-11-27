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

    protected $appends = ['photo_url'];

    
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
