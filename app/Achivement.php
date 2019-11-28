<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Shipment;

class Achivement extends Model
{
    protected $fillable = [
        'runner_id', 'distance', 'badge_id', 'badge_name'
    ];


    protected $appends = ['income'];


    public function getIncomeAttribute()
    {
        $runnerArrivedShipments = Shipment::where('runner_id', $this->attributes['runner_id'])
                                    ->where('status', '已抵達')
                                    ->get();
        
        $totalRunnerIncome = 0;
        foreach ($runnerArrivedShipments as $aRunnerArrivedShipment){
            $totalRunnerIncome += $aRunnerArrivedShipment->price;
        }

        return $totalRunnerIncome;
    }
}
//     protected $appends = ['medal'];

//     protected function calculateTotalRunnerOfInstance()
//     {
//         $runner_id = Auth::user()->id;
//         $shipment_runner_instance = Shipment::where('runnder_id', $runner_id)
//             ->value('istance');

//         return $shipment_runner_instance;
//     }


//     public function getMedalAttribute()
//     {
//         $medals = [1, 2, 3, 4, 5];
//         $levelInterval = [0, 100, 500, 1000, 2000];
//         // level 1 : 0~99, level 2 : 100~499 ...

//         $totalWeight = $this->calculateTotalRunnerOfInstance();

//         for ($i = count($medals) - 1; $i >= 0; $i--) {
//             if ($totalWeight >= $levelInterval[$i]) {
//                 return $medals[$i];
//             }
//         }
//         return 0;
//     }

//     public function getTotalItAttribute()
//     {
//         return $this->calculateTotalWeightOfShipment();
//     }
// }
