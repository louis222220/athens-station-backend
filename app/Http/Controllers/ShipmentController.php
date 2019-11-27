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

        if ($findShipment){
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

        $alreadyShipment = Shipment::where('runner_id', $runner_id)
                                        ->where('status', '準備中')->first();
        if ($alreadyShipment){
            return response()->json([
                'message' => '已接單，請勿重複接單'
            ], 409);
        }


        $findShipment = Shipment::where('id', $shipment_id)->first();
        if (! $findShipment) {
            return response()->json([
                'message' => "無此訂單",
            ], 404);
        }

        if ($findShipment->status != '準備中') {
            return response()->json([
                'message' => "無法接單，該訂單狀態為$findShipment->status",
            ], 409);
        }

        $findShipment->update(['runner_id' => $runner_id]);
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
        $reqStartStationName = $request->start_station_name;

        $runnerId = Auth::user()->id;

        $toShipShipment = Shipment::where('runner_id', $runnerId)
                                ->where('status', '準備中')->first(); //該跑者的運送資料

        if (! $toShipShipment) {
            return response()->json([
                'message' => '尚未接單'
            ], 409);
        }

        $reqStartStationId = Station::where('name', $reqStartStationName)->value('id'); //比對cityname與station id一致

        $toShipShipmentStartStationId = $toShipShipment->start_station_id;

        if ($reqStartStationId == $toShipShipmentStartStationId) {
            $toShipShipment->update(['status' => '運送中']);

            $toShipShipment->good->update(['status' => '運送中']);

            $results = [
                'message' => '運送中',
                'data' => $toShipShipment
            ];

            return response()->json($results);
        }
        else {
            return response()->json([
                'message' => '請查詢貨品位置，並掃描正確驛站'
            ]);
        }
    }

    public function checkout(Request $request)
    {
        $reqDesStationName = $request->des_station_name;

        $runnerId = Auth::user()->id;

        $shippingShipment = Shipment::where('runner_id', $runnerId)
                                ->where('status', '運送中')->first(); //該跑者的運送資料

        if (! $shippingShipment){
            return response()->json([
                'message' => '未運送貨品，請至列表接單'
            ], 409);
        }

        $reqDesStationId = Station::where('name', $reqDesStationName)->value('id'); //比對cityname與station id一致

        $shippingShipmentDesStationId = $shippingShipment->des_station_id;
        $shippingGood = $shippingShipment->good;

        $shippingGoodDesStationId = $shippingGood->des_station_id;

        if ($reqDesStationId == $shippingShipmentDesStationId) {
            $shippingShipment->update(['status' => '已抵達']);
            $shippingGood->update(['now_station_id' => $shippingShipmentDesStationId]);

            if ($shippingGoodDesStationId == $shippingShipmentDesStationId) {
                $shippingGood->update(['status' => '已抵達']);
            }
            else{
                $nextShipment = Shipment::where('good_id', $shippingGood->id)
                                        ->where('start_station_id', $shippingShipmentDesStationId)
                                        ->first();
                $nextShipment->update(['status' => '準備中']);
            }

            $results = [
                'message' => '此段運送結束',
                'data' => $shippingShipment
            ];

            return response()->json($results);
        } 
        else {
            return response()->json([
                'message' => '請查詢貨品資訊，並送至正確目的地'
            ], 409);
        }
    }
}
