<?php

namespace App\Http\Controllers;

use App\Achivement;
use App\Shipment;
use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Auth;
//use App\Auth;
use Illuminate\Support\Facades\Auth;

class AchivementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $runner_id = Auth::user()->id;

        $runner_data = Shipment::where('runner_id', $runner_id)->get();

        return response([
            'message' => '跑者個人運送歷史',
            'data' => $runner_data
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { }

    /**
     * Display the specified resource.
     *
     * @param  \App\Achivement  $achivement
     * @return \Illuminate\Http\Response
     */
    public function show(Achivement $achivement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Achivement  $achivement
     * @return \Illuminate\Http\Response
     */
    public function edit(Achivement $achivement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Achivement  $achivement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Achivement $achivement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Achivement  $achivement
     * @return \Illuminate\Http\Response
     */
    public function destroy(Achivement $achivement)
    {
        //
    }

    public function medalStatus()
    {

        $user = Auth::user();
        //dd($user);
        $id = $user->id;
        $data = Achivement::where('runner_id', $id)->first();

        if (! $data){
            $tmpNewAchievement = new Achivement([
                'runner_id' => $id,
                'badge_id' => 1,
                'badge_name' => '跑者級'
            ]);

            return response()->json([
                'message' => '跑者累積紀錄',
                'data' => $tmpNewAchievement
            ]);
        }

        $level1 = 1;
        $level2 = 2;
        $level3 = 3;
        $level4 = 4;
        $distance = $data->distance;

        if ($distance < 500) {
            $data->update(['badge_name' => '跑者級', 'badge_id' => $level1]);
        } elseif ($distance >= 500 &&  $distance < 1000) {
            $data->update(['badge_name' => '刻苦級', 'badge_id' => $level2]);
        } elseif ($distance >= 1000 &&  $distance < 3000) {
            $data->update(['badge_name' => '精進級', 'badge_id' => $level3]);
        } else {
            $data->update(['badge_name' => '運動建將', 'badge_id' => $level4]);
        }

        return response(['message' => '跑者累積紀錄', 'data' => $data]);
    }
}
