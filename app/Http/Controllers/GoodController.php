<?php

namespace App\Http\Controllers;

use App\Good;
use Illuminate\Http\Request;
use Validator;

class GoodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allGoods =Good::with('start_station','des_station','now_station')->get();
        $result = [
            'message'=>'取得所有貨物',
            'data'=>$allGoods
        ];
        return response()->json($result);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $role = $request->user()->role_id;

        if($role !== 1){
            return response(['message' => 'Can not comming'], 404);
        }

        $input =$request->all();

        $rules = [
            'name' => 'required',
            'description' => 'required',
            'weight' => 'required',
           // 'photo_path' => 'required',
            'des_station_id' => 'required',
            'price'=>'required',
            "start_station_id"=>'required',
        ];

        $validator = Validator::make($input,$rules);

        if($validator->fails()){
            return response(['message'=>$validator->errors()]);
        }

        $addGood = Good::create([
            'name'=>$request->name,
            'description'=>$request->description,
            'weight'=>$request->weight,
            'photo_path'=>'path',
            'des_station_id'=>$request->des_station_id,
            'price'=>$request->price,
            'status'=>'準備中',
            'start_station_id'=>$request->start_station_id,
            'now_station_id'=>$request->start_station_id
        ]);
        $Goods =Good::with('start_station','des_station','now_station')->get();

        $result = [
            'message' => '已登錄運送貨品',
            'data' =>$Goods,
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

    public function upload(){

        // $path = $request->
    }
}
