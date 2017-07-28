<?php

namespace App\Http\Controllers;

use App\Number;
use Carbon\Carbon;
use Faker\Provider\DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Excel;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;

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
    public function test(Request $request)
    {
        dd($request->session()->all());

        $dayCount = DB::table('objects')->whereBetween('created_at', [Carbon::now()->subDay(), Carbon::now()])->orWhereBetween('updated_at', [Carbon::now()->subDay(), Carbon::now()])->count();
        $weekCount = DB::table('objects')->whereBetween('created_at', [Carbon::now()->subWeek(), Carbon::now()])->orWhereBetween('updated_at', [Carbon::now()->subWeek(), Carbon::now()])->count();
        $monthCount = DB::table('objects')->whereBetween('created_at', [Carbon::now()->subMonth(), Carbon::now()])->orWhereBetween('updated_at', [Carbon::now()->subMonth(), Carbon::now()])->count();
        dd($dayCount);


        //обработка rxtx
        $a = 7979;
        $b = ($a >> 8) - 110;
        echo '<br>';
        $c = unpack("C", pack("C", $a));
        $d = $c[1] - 110;
        echo ''.$b.'/'.$d.'';
        echo '<br>';

        //обработка координат
        $num = 64169494916941148;
        $num3 = $num >> 30;
        $num3 = $num3 >> 24;
        $num3 .= 1;
        $num2 = $num >> 29;
        $num2 = $num2 >> 24;
        $num2 .= 1;
        $num12 = $num << 11;
        $num = $num12 >> 11;
        $num4 = $num / 54000000;
        $num5 = $num - ((int)$num4 * 54000000);
        $num6 = $num4 / 600000;
        $num7 = ($num4 - ((int)$num6 * 600000)) / 10000;
        $num8 = ((int)$num4 - ((int)$num6 * 600000)) - ((int)$num7 * 10000);
        $num9 = $num5 / 600000;
        $num10 = ($num5 - ((int)$num9 * 600000)) / 10000;
        $num11 = ($num5 - ((int)$num9 * 600000)) - ((int)$num10 * 10000);
        $lat = (int)$num9 + (((int)$num10 + (((double)$num11) / 10000)) / 60.0);
        $lon = (int)$num6 + (((int)$num7 + (((double)$num8) / 10000)) / 60.0);

        if ($num3 == 0)
        {
            $lon = -$lon;
        }
        if ($num2 == 0)
        {
            $lat = -$lat;
        }

        print_r($lat);
        echo '<br>';
        print_r($lon);

        //обработка даты
        $date = 562265147;
        $ignoreSeconds = false;

        $hour = 0;
        $minute = 0;
        $second = 0;
        $tmp = (int) ($date / 86400);
        $tmp3 = intval($tmp / 372);
        $month = intval((($tmp - ($tmp3 * 372)) / 31) + 1);
        $day = intval((($tmp - ($tmp3 * 372)) - (($month - 1) * 31)) + 1);
        $tmp2 = ((int) $date) - ($tmp * 86400);
        //dd($date);
        if (!$ignoreSeconds)
        {
            $hour = intval($tmp2 / 3600);
            $tmp9 = intval($hour * 3600);
            $minute = intval(($tmp2 - $tmp9) / 60);
            $tmp9 += $minute * 60;
            $second = intval($tmp2 - $tmp9);
        }

        $dt = Carbon::create($tmp3+2000, $month, $day, $hour, $minute, $second);
        $dt->timezone = 'Europe/Kiev';
        echo $dt->toDateTimeString();
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
