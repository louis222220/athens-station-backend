<?php

namespace App\Http\Controllers;

use App\Shipment;
use App\Station;
use App\Good;
use Auth;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ShipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $tasklist = Shipment::all();

        $result = [
            'message' => '任務列表',
            'data' => $tasklist
        ];

        return response()->json($result);
    }


    public function getMyTask(Request $request)
    {
        $runner_id = Auth::user()->id;


        $findShipment = Shipment::where('runner_id', $runner_id)
                                ->where('status', '準備中')->first();

        if (findShipment){
            return response()->json($findShipment);
        }
        else {
            return response()->json([
                'message' => "尚未接單"
            ], 404);
        }
        
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
        $shipment_id = $request->shipment_id;
        $runner_id = Auth::user()->id;

        $authData = Auth::user();

        $findShipment = Shipment::where('id', $shipment_id)->first();

        $update = $findShipment->update(['runner_id' => $runner_id]);

        return response()->json($findShipment);
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

    public function checkin(Request $request)
    {

        $start_station_name = $request->start_station_name;

        $id = Auth::user()->id;

        $shipment = Shipment::where('runner_id', $id)->first(); //該跑者的運送資料

        $req_station_name = Station::where('name', $start_station_name)->value('id'); //比對cityname與station id一致

        $db_station_id = $shipment->start_station_id;

        if($req_station_name == $db_station_id){

            $updateStatus = Shipment::where('runner_id', $id)->first()->update(['status'=>'運送中']);

            $results=[
                'message'=>'運送中',
                'data'=>$shipment
            ];

            return response()->json($results);

        }else{

            return response(['message'=>'請跟驛站人員確認！']);
        }

    }

    public function checkout(Request $request)
    {

        $start_station_name = $request->start_station_name;

        $id = Auth::user()->id;

        $shipment = Shipment::where('runner_id', $id)->first(); //該跑者的運送資料

        $req_station_name = Station::where('name', $start_station_name)->value('id'); //比對cityname與station id一致

        $db_station_id = $shipment->start_station_id;

        if($req_station_name == $db_station_id){

            $updateStatus = Shipment::where('runner_id', $id)->first()->update(['status'=>'運送中']);

            $results=[
                'message'=>'運送中',
                'data'=>$shipment
            ];

            return response()->json($results);

        }else{

            return response(['message'=>'請跟驛站人員確認！']);
        }
}
}
