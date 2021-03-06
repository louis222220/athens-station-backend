<?php

namespace App\Http\Controllers;

use App\Good;
use App\Station;
use App\Shipment;
use Illuminate\Http\Request;
use Validator;
use Carbon\Carbon;

class GoodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allGoods = Good::get();
        $result = [
            'message' => '取得所有貨物',
            'data' => $allGoods
        ];
        return response()->json($result);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    { }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function test(Request $request)
    {
        $role = $request->user()->role_id;
        //$path = $request->photo->store('images');

        if ($role !== 2) {
            return response(['message' => 'Can not comming'], 404);
        }

        $input = $request->all();

        $rules = [
            'name' => 'required',
            'description' => 'required',
            'weight' => 'required',
            // 'photo_path' => 'required',
            'des_station_name' => 'required',
            'price' => 'required',
            "start_station_name" => 'required',
        ];

        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()]);
        }

        $destination = Station::where('name', $request->des_station_name)->first();
        //if判斷輸入城市是否正確
        $destination_id = $destination->id;

        $start_station = Station::where('name', $request->start_station_name)->first();
        $start_station_id = $start_station->id;



        $addGood = Good::create([
            'name' => $request->name,
            'description' => $request->description,
            'weight' => $request->weight,
            //'photo_path'=>"path",
            'des_station_id' => $destination_id,
            'price' => $request->price,
            'status' => '準備中',
            'start_station_id' => $start_station_id,
            'now_station_id' => $start_station_id
        ]);
        $Goods = Good::with('start_station', 'des_station', 'now_station')->get();

        $result = [
            'message' => '已登錄運送貨品',
            'data' => $Goods,
        ];
        return response()->json($result);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Good  $good
     * @return \Illuminate\Http\Response
     */
    public function show(Good $good)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Good  $good
     * @return \Illuminate\Http\Response
     */
    public function edit(Good $good)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Good  $good
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Good $good)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Good  $good
     * @return \Illuminate\Http\Response
     */
    public function destroy(Good $good)
    {
        //
    }

    public function upload(Request $request)
    {
        $path = $request->photo->store('public/images');

        $good_id = $request->good_id;

        $addPhoto = Good::where('id', $good_id)->first();

        $createPhoto = $addPhoto->update(['photo_path' => $path]);

        return response(['message' => 'save']);
    }


    public function store(Request $request)
    {

        //$role = $request->user()->role_id;
        //$path = $request->photo->store('images');

        // if($role !== 2){
        //     return response(['message' => 'Can not comming'], 404);
        // }

        $input = $request->all();

        $rules = [
            'name' => 'required',
            'description' => 'required',
            'weight' => 'required',
            // 'photo_path' => 'required',
            'des_station_name' => 'required',
            'price' => 'required',
            "start_station_name" => 'required',
        ];

        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()]);
        }

        $destination = Station::where('name', $request->des_station_name)->first();
        //if判斷輸入城市是否正確
        $destination_id = $destination->id;

        $start_station = Station::where('name', $request->start_station_name)->first();
        $start_station_id = $start_station->id;



        $addGood = Good::create([
            'name' => $request->name,
            'description' => $request->description,
            'weight' => $request->weight,
            //'photo_path'=>"path",
            'des_station_id' => $destination_id,
            'price' => $request->price,
            'status' => '準備中',
            'start_station_id' => $start_station_id,
            'now_station_id' => $start_station_id
        ]);

        $Goods = Good::with('start_station', 'des_station', 'now_station')->get();

        $star_id = $addGood->start_station_id;
        $des_id = $addGood->des_station_id;

        $count = abs($star_id - $des_id);
        $diff = $des_id - $star_id;
        $posOrMinusOne = (int) ($diff / $count);
        $good_id = $addGood->id;

        for ($i=0; $i < $count; $i++) {
            $now = $star_id + $posOrMinusOne * $i;
            $next = $star_id + $posOrMinusOne * ($i + 1);

            if ($now == $star_id){
                $status = '準備中';
            }
            else {
                $status = '未準備';
            }

            Shipment::create(['good_id'=>$good_id,
                'start_station_id'=>$now,
                'des_station_id'=>$next,
                'good_name'=>$addGood->name,
                'status' => $status,
            ]);

        }

        $result = [
            'message' => '已登錄運送貨品',
            'data' => $addGood,
        ];

        return response()->json($result);
    }
}
