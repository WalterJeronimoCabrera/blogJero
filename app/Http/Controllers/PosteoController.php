<?php

namespace App\Http\Controllers;

use App\Models\Posteo;
use Illuminate\Http\Request;

class PosteoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Posteo::orderBy('id', 'desc')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $posteo = new Posteo;
        $posteo->create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Posteo  $posteo
     * @return \Illuminate\Http\Response
     */
    public function show(Posteo $posteo)
    {
        return $posteo;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Posteo  $posteo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Posteo $posteo)
    {
        $posteo->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Posteo  $posteo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Posteo $posteo)
    {
        $posteo->delete();
    }
    
}
