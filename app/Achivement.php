<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Achivement extends Model
{
    protected $fillable = [
        'runner_id', 'distance', 'badge_id', 'badge_name'
    ];
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
