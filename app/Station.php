<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Shipment;

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


    protected function calculateTotalWeightOfShipment()
    {
        $shipmentFinishedStr = "已完成";
        
        $shipments = Shipment::where('start_station_id', $this->id)
                                ->where('status', $shipmentFinishedStr)->get();

        $totalWeight = 0;
        foreach($shipments as $shipment)
        {
            $totalWeight += $shipment->good->weight;
        }

        return $totalWeight;
    }

    public function getLevelAttribute()
    {
        $levels = [1, 2, 3 ,4];
        $levelInterval = [100, 500, 1000, 5000];
        // level 1 : 0~99, level 2 : 100~499 ...
        
        $totalWeight = $this->calculateTotalWeightOfShipment();
        
        for($i = count($levels) - 1; $i >= 0; $i--){
            if ($totalWeight >= $levelInterval[$i]){
                return $levels[$i];
            }
        }
        return 0;
    }

    public function getTotalWeightAttribute()
    {   
        return $this->calculateTotalWeightOfShipment();
    }
}

