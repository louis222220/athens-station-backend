<?php

namespace App\Http\Controllers;

use App\Achivement;
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

        if ($findShipment) {
            return response()->json($findShipment);
        } else {
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

        $alreadyShipment = Shipment::where('runner_id', $runner_id)->first();
        if ($alreadyShipment) {
            return response()->json([
                'message' => '已接單，請勿重複接單'
            ], 409);
        }

        $findShipment = Shipment::where('id', $shipment_id)->first();
        $update = $findShipment->update(['runner_id' => $runner_id]);

        return response()->json($findShipment);
    }

    public function teststore(Request $request)
    {
        $shipment_id = $request->shipment_id;
        $runner_id = Auth::user()->id;

        $shipment_status = Shipment::where('id', $shipment_id)->value('status');

        switch ($shipment_status) {
            case "準備中":
                $alreadyShipment = Shipment::where('runner_id', $runner_id)
                    ->where('status', '準備中')->first();

                if ($alreadyShipment) {
                    return response()->json([
                        'message' => '已接單，請勿重複接單'
                    ], 409);
                } else {
                    $findShipment = Shipment::where('id', $shipment_id)->first();
                    $update = $findShipment->update(['runner_id' => $runner_id]);

                    return response()->json([
                        'message' => '貨物尚未出發', 'data' => $findShipment
                    ]);
                }

                // no break
            case "運送中":
                return response()->json([
                    'message' => '千里迢迢送到你手中'
                ]);


            case "已抵達":

                $findShipment = Shipment::where('id', $shipment_id)->first();
                return response()->json([
                    'message' => '貨物已抵達', 'data' => $findShipment
                ]);
        }
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

        $updateStatus = Shipment::where('runner_id', $id)
            ->where('status', '準備中')->first();

        $db_shipment_id = Shipment::where('runner_id', $id)
            ->where('status', '準備中')->value('start_station_id');

        if ($db_shipment_id == null) {
            return response(['message' => 'checkin驛站與任務不符']);
        }
        $db_station_name = Station::where('id', $db_shipment_id)->value('name');

        if ($db_station_name !== $start_station_name) {
            return response(['message' => 'checkin驛站與任務不符']);
        }

        if (!$updateStatus) {
            return response(['message' => 'checkin重複，或checkin驛站與任務不符']);
        }
        $updateStatus->update(['status' => '運送中']);


        $db_shipment_good_id = $updateStatus->good_id;
        $updateGoodStatus = Good::where('id', $db_shipment_good_id)
            ->first()->update(['status' => '運送中']);

        $newStatus = Shipment::where('runner_id', $id)
            ->where('status', '運送中')->first();

        $results = [
            'message' => '運送中',
            'data' => $newStatus
        ];

        return response()->json($results);
    }



    public function checkout(Request $request)
    {
        $des_station_name = $request->des_station_name;

        $id = Auth::user()->id;

        //只拿運送中的資料
        $updateStatus = Shipment::where('runner_id', $id)
            ->where('status', '運送中')->first();

        $db_shipment_id = Shipment::where('runner_id', $id)
            ->where('status', '運送中')->value('des_station_id');

        $db_station_name = Station::where('id', $db_shipment_id)->value('name');

        if ($db_station_name !== $des_station_name) {
            return response(['message' => 'checkin重複，或checkin驛站與任務不符']);
        }
        if ($db_shipment_id == null) {
            return response(['message' => 'checkin重複，或checkin驛站與任務不符']);
        }

        if (!$updateStatus) {
            return response(['message' => 'checkin重複，或checkin驛站與任務不符']);
        } else {
            $updateStatus->update(['status' => '已抵達']);
            //------------------------------------------------------------------------//
            $login_id = Auth::user()->id;

            $data = Shipment::where('runner_id', $id)
                ->where('status', '已抵達')->first();

            $runner = Achivement::where('runner_id', $login_id)->first();

            if (!$runner) {
                $create_runner = Achivement::create([
                    'runner_id' => $login_id,
                ]);
            }
            $newRunner = Achivement::where('runner_id', $login_id)->first();

            $runner_distance = $newRunner->distance;

            $total = $data->start_station_id + $data->des_station_id;


            switch ($total) {

                case 3:
                    $newRunner->update(['distance' => $runner_distance + 300]);

                    return response()->json([
                        'message' => '跑者成就增加', 'data' => Achivement::all()
                    ]);

                case 5:
                    $runner->update(['distance' => $runner_distance + 500]);

                    return response()->json([
                        'message' => '跑者成就增加', 'data' => Achivement::all()
                    ]);

                case 7:

                    $runner->update(['distance' => $runner_distance + 700]);

                    return response()->json([
                        'message' => '跑者成就增加', 'data' => Achivement::all()
                    ]);
            }

            $newStatus = Achivement::where('runner_id', $id)->get();

            $results = [
                'message' => '此段運送結束',
                'data' => $newStatus
            ];

            return response()->json($results);
        }
    }
}
