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

        $number = Number::findOrFail($number_id);
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

        $object->numbers()->save($number);

        return redirect()->route('objects.edit', ['$object_id' => $object->id]);

    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $this->validate($request, [
            'inputNumber' => 'required|exists:numbers,number'
        ]);


        $number = Number::with('object')->where('number', $request['inputNumber'])->first();
        //dd($number);

        if (empty($number->object))
        {
            return redirect()->back()->withErrors(['message' => 'Номер не найден в базе. <a href="' . route('objects.create', ['number_id' => $number->id]) . '">Добавить?</a>']);
        }
        return view('objects.show', compact('number'));
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
        return view('objects.edit', compact('object'));

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

    public function addnumber(Request $request, $object_id)
    {
        $number = Number::where('number', $request['inputAddNumber'])->first();
        //dd($number);
        if(empty($number)) {
            return redirect()->back()->withErrors(['message' => 'Номера нету в базе, срочно зовите кого-то!']);
        } else {
            $object = Object::findOrFail($object_id);
            $number->object()->associate($object)->save();

            return redirect()->back();
        }
    }

    public function delnumber($object_id, $number_id)
    {
        $number = Number::findOrFail($number_id);
        $number->object()->dissociate()->save();
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

    /**
     * Страничка связей объекта
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function links($object_id)
    {
        $object = Object::find($object_id);
        return view('objects.links', compact('object'));

    }

    /**
     * Страничка связей объекта
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function linkModal(Request $request)
    {
        $this->validate($request, [
            'object2number' => 'required|exists:numbers,number'
        ]);

        $object2number = Number::with('object')->where('number', $request['object2number'])->first();
        if (!empty($object2number))
        {
            return response()->json(['object2' => $object2number]);
        } else {
            return response()->json(['error' => 'Второй объект не найден в базе. Добавить?']);
        }
    }

}
