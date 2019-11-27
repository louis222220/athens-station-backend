<?php

namespace App\Http\Controllers;

use App\Achivement;
use App\Shipment;
use Illuminate\Http\Request;
use Auth;

class AchivementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    {
        $login_id = Auth::user()->id;

        $get_shipment_data = Shipment::where('runner_id', $login_id)->get();
        foreach ($get_shipment_data as $data) {

            $total = $data->start_station_id + $data->des_station_id;

            switch ($total) {
                case 3:
                    Achivement::create([
                        'runner_id' => $login_id,
                        'distance' => 300
                    ]);
                    return response()->json([
                        'message' => '跑者成就增加', 'data' => Achivement::all()
                    ]);

                case 5:
                    Achivement::create([
                        'runner_id' => $login_id,
                        'distance' => 500
                    ]);
                    return response()->json([
                        'message' => '跑者成就增加', 'data' => Achivement::all()
                    ]);

                case 7:
                    Achivement::create([
                        'runner_id' => $login_id,
                        'distance' => 700
                    ]);
                    return response()->json([
                        'message' => '跑者成就增加', 'data' => Achivement::all()
                    ]);
            }
        }
        // $get_shipment_des_id = $get_shipment_data->



    }

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
}
