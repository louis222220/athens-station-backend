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
        $runner_id = Auth::user()->id;

        $runner_data =Shipment::where('runner_id',$runner_id)->get();

        //Achivement::where('runnder_id',)

        return response(['message'=>'跑者個人運送歷史',
        'data'=>$runner_data
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
    {

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
