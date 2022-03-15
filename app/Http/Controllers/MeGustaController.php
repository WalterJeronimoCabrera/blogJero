<?php

namespace App\Http\Controllers;

use App\Models\MeGusta;
use Illuminate\Http\Request;

class MeGustaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return MeGusta::get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $meGusta = new MeGusta;
        $meGusta->create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MeGusta  $meGusta
     * @return \Illuminate\Http\Response
     */
    public function show(MeGusta $meGusta)
    {
        return $meGusta;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MeGusta  $meGusta
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MeGusta $meGusta)
    {
        $meGusta->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MeGusta  $meGusta
     * @return \Illuminate\Http\Response
     */
    public function destroy(MeGusta $meGusta)
    {
        $meGusta->delete();
    }
}
