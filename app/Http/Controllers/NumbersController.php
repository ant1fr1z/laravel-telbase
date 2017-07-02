<?php

namespace App\Http\Controllers;

use App\Number;
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
        $data = 'TEST';
        Excel::create('pol_'.time().'', function($excel) use($data) {
            $excel->sheet('Список телефонов', function($sheet) use($data) {
                $sheet->setOrientation('landscape');

                $sheet->cell('A1', function($cell) use($data) {

                    $cell->setValue($data);

                });

            });
        })->export('xlsx');
        /**
        $number = new Number();
        $number->number = '380666272555';
        $number->save();
        $number = new Number();
        $number->number = '380666272556';
        $number->save();
        $number = new Number();
        $number->number = '380666272557';
        $number->save();
         */
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
