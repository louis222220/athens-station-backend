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

        if ($req_station_name == $db_station_id) {
            $updateStatus = Shipment::where('runner_id', $id)->first()->update(['status' => '運送中']);

            $db_shipment_good_id = $shipment->good_id;
            $updateGoodStatus = Good::where('id', $db_shipment_good_id)->first()->update(['status' => '運送中']);

            $results = [
                'message' => '運送中',
                'data' => $shipment
            ];

            return response()->json($results);
        } else {
            return response(['message' => '請跟驛站人員確認！']);
        }
    }

    public function checkout(Request $request)
    {
        $des_station_name = $request->des_station_name;

        $id = Auth::user()->id;

        $shipment = Shipment::where('runner_id', $id)->first(); //該跑者的運送資料

        $status = $shipment->status;

        if ($status !== '運送中') {
            return response(['message' => '非運送中，請與驛站人員確認']);
        }

        $req_station_name = Station::where('name', $des_station_name)->value('id'); //比對cityname與station id一致

        $db_station_id = $shipment->des_station_id;
        $db_shipment_good_id = $shipment->good_id;

        $db_good = Good::where('id', $db_shipment_good_id)->first();
        $good_des_id = $db_good->des_station_id;

        if ($req_station_name == $db_station_id) {
            $shipment->update(['status' => '已抵達']);

            if ($good_des_id == $shipment->good->des_station_id) {
                $shipment->good->update(['status' => '已抵達']);
            }

            $results = [
                'message' => '此段運送結束',
                'data' => $shipment
            ];

            return response()->json($results);
        } else {
            return response(['message' => '請跟驛站人員確認！']);
        }
    }
}
