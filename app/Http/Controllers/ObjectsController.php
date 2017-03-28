<?php

namespace App\Http\Controllers;

use App\Number;
use App\Object;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ObjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($number_id)
    {
        Number::findOrFail($number_id);
        return view('objects.create', ['number_id' => $number_id]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $number_id)
    {

        $this->validate($request, [
            'inputSecondName' => 'required_without_all:inputFirstName,inputMiddleName,inputNickname',
            'inputFirstName' => 'required_without_all:inputSecondName,inputMiddleName,inputNickname',
            'inputMiddleName' => 'required_without_all:inputSecondName,inputFirstName,inputNickname',
            'inputNickname' => 'required_without_all:inputSecondName,inputFirstName,inputMiddleName',
            'inputBirthDay' => 'sometimes|nullable|date',
            'inputSource' => 'required'
        ]);

        $object = new Object();
        $object->secondname = $request['inputSecondName'];
        $object->firstname = $request['inputFirstName'];
        $object->middlename = $request['inputMiddleName'];
        $object->nickname = $request['inputNickname'];
        $object->birthday = $request['inputBirthDay'];
        $object->address = $request['inputAddress'];
        $object->work = $request['inputWork'];
        $object->passport = $request['inputPassport'];
        $object->code = $request['inputCode'];
        $object->other = $request['inputOther'];
        $object->source = $request['inputSource'];
        $forsearch = $object->secondname.' '.$object->firstname.' '.$object->middlename.' '.$object->nickname;
        $object->forsearch = $forsearch;
        $object->save();

        $object->numbers()->attach($number_id);

        return redirect()->route('objects.edit', ['$object_id' => $object->id]);

    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($number_id, $object_id)
    {
        return view('objects.show', ['number_id' => $number_id, 'object_id' => $object_id]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($object_id)
    {
        $object = Object::find($object_id);
        return view('objects.edit', compact('object'), ['object_id' => $object_id]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $object_id)
    {
        $this->validate($request, [
            'inputSecondName' => 'required_without_all:inputFirstName,inputMiddleName,inputNickname',
            'inputFirstName' => 'required_without_all:inputSecondName,inputMiddleName,inputNickname',
            'inputMiddleName' => 'required_without_all:inputSecondName,inputFirstName,inputNickname',
            'inputNickname' => 'required_without_all:inputSecondName,inputFirstName,inputMiddleName',
            'inputBirthDay' => 'sometimes|nullable|date',
            'inputSource' => 'required'
        ]);

        $object = Object::findOrFail($object_id);
        $object->secondname = $request['inputSecondName'];
        $object->firstname = $request['inputFirstName'];
        $object->middlename = $request['inputMiddleName'];
        $object->nickname = $request['inputNickname'];
        $object->birthday = $request['inputBirthDay'];
        $object->address = $request['inputAddress'];
        $object->work = $request['inputWork'];
        $object->passport = $request['inputPassport'];
        $object->code = $request['inputCode'];
        $object->other = $request['inputOther'];
        $object->source = $request['inputSource'];
        $forsearch = $object->secondname.' '.$object->firstname.' '.$object->middlename.' '.$object->nickname;
        $object->forsearch = $forsearch;
        $object->save();

        return redirect()->back();

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
