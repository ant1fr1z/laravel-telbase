<?php

namespace App\Http\Controllers;

use App\Number;
use Carbon\Carbon;
use Faker\Provider\DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Excel;

class NumbersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $number = new Number();
        $number->number = '380666272599';
        $number->save();
        $number = new Number();
        $number->number = '380666272552';
        $number->save();
        $number = new Number();
        $number->number = '380666272555';
        $number->save();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function test()
    {
        $a = 200;
        $b = ($a >> 8) - 110;
        echo ($a >> 8);
        echo '<br>';
        $c = unpack("C", pack("C", $a));
        $d = $c[1] - 110;
        echo ''.$b.'/'.$d.'';
        echo '<br>';
        $dt = Carbon::createFromTimestamp(543046447)->toDateTimeString();
        Carbon::setToStringFormat('d/M/y  H:m:s');
        echo $dt;
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
