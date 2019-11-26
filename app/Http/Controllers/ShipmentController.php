<?php

namespace App\Http\Controllers;

use App\Shipment;
use App\Station;
use App\Good;
use Illuminate\Http\Request;
use Auth;

class ShipmentController extends Controller
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Shipment  $shipment
     * @return \Illuminate\Http\Response
     */
    public function show(Shipment $shipment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Shipment  $shipment
     * @return \Illuminate\Http\Response
     */
    public function edit(Shipment $shipment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Shipment  $shipment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Shipment $shipment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Shipment  $shipment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Shipment $shipment)
    {
        //
    }


    public function task(Request $request){

        // $out = new \Symfony\Component\Console\Output\ConsoleOutput();
        // $out->writeln(Carbon::now());
        //$out->writeln($a);

        $shipment_id = $request->shipment_id;
        $runner_id = Auth::user()->id;

        //拿到所有的物品紀錄
        $allGoods = Good::all();
        //$toarray = $allGoods->toArray();

        foreach($allGoods as $allGood){

            $star_id = $allGood->start_station_id;
            $des_id = $allGood->des_station_id;
            $good_id = $allGood->id;

            if($star_id < $des_id){

                for($i = $star_id;$i<$des_id; $i--){


                    Shipment::create(['good_id'=>$good_id,
                    'runner_id'=>$runner_id,
                    'start_station_id'=>$star_id,
                    'des_station_id'=>$des_id]);
                }
            }




        //     for($i = $good->start_station;$i<$good->des_station; $i++){

        // }



        }







    }
}
