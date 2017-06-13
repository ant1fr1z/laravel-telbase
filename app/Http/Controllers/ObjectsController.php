<?php

namespace App\Http\Controllers;

use App\Link;
use App\Number;
use App\Object;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Excel;

class ObjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('index');
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $number_id)
    {

        $number = Number::findOrFail($number_id);
        $this->validate($request, [
            'inputFio' => 'required',
            'inputBirthDay' => 'sometimes|nullable|date',
            'inputSource' => 'required'
        ]);

        $object = new Object();
        $object->fio = $request['inputFio'];
        $object->birthday = $request['inputBirthDay'];
        $object->address = $request['inputAddress'];
        $object->work = $request['inputWork'];
        $object->passport = $request['inputPassport'];
        $object->code = $request['inputCode'];
        $object->other = $request['inputOther'];
        $object->source = $request['inputSource'];
        $object->save();

        $object->numbers()->save($number);

        return redirect()->route('objects.edit', ['$object_id' => $object->id]);

    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $error_messages = [
            'inputNumber.required' => 'Поле не может быть пустым!',
            'inputNumber.exists' => 'Внутренняя ошибка, срочно зовите администратора!',
        ];

        $this->validate($request, [
            'inputNumber' => 'required|exists:numbers,number'
        ], $error_messages);


        $number = Number::with('object')->where('number', $request['inputNumber'])->first();
        //dd($number);

        if (empty($number->object)) {
            return redirect()->back()->withErrors(['message' => 'Номер не найден в базе. <a href="' . route('objects.create', ['number_id' => $number->id]) . '">Добавить?</a>']);
        }
        $request->flash();
        return view('objects.show', compact('number'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
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
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $object_id)
    {
        $this->validate($request, [
            'inputFio' => 'required',
            'inputBirthDay' => 'sometimes|nullable|date',
            'inputSource' => 'required'
        ]);

        $object = Object::findOrFail($object_id);
        $object->fio = $request['inputFio'];
        $object->birthday = $request['inputBirthDay'];
        $object->address = $request['inputAddress'];
        $object->work = $request['inputWork'];
        $object->passport = $request['inputPassport'];
        $object->code = $request['inputCode'];
        $object->other = $request['inputOther'];
        $object->source = $request['inputSource'];
        $object->save();

        return redirect()->back();

    }

    public function addnumber(Request $request, $object_id)
    {
        $number = Number::where('number', $request['inputAddNumber'])->first();

        //dd($number);
        if (empty($number)) {
            return redirect()->back()->withErrors(['message' => 'Внутренняя ошибка, срочно зовите администратора!']);
        } else {

            if (!is_null($number->object)) {
                return redirect()->back()->withErrors(['message' => 'Номер уже имеет привязку к <a href="' . route('objects.edit', ['$object_id' => $number->object->id]) . '">объекту</a>!']);
            }
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
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Страничка связей объекта
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function links($object_id)
    {
        $object = Object::find($object_id);
        $objectLinks = Link::where('object1', $object_id)->orWhere('object2', $object_id)->get();
        $objectLinks->each(function ($item, $key) {
            $item->object1info = Object::find($item->object1);
            $item->object2info = Object::find($item->object2);
        });

        return view('objects.links', compact('object', 'objectLinks'));

    }

    /**
     * Страничка связей объекта
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function linkModal(Request $request)
    {
        $error_messages = [
            'object2number.required' => 'Поле "Объект 2" не может быть пустым!',
            'object2number.exists' => 'Внутренняя ошибка, срочно зовите администратора!',
            'description.required' => 'Описание связи не может быть пустым!',
        ];

        $this->validate($request, [
            'object2number' => 'required|exists:numbers,number',
            'description' => 'required',
        ], $error_messages);

        $object2number = Number::with('object')->where('number', $request['object2number'])->first();
        if (!is_null($object2number->object)) {
            return response()->json(['number2' => $object2number]);
        } else {
            return response()->json(['errors' => ['0' => 'Второй объект не найден в базе. <a href="' . route('objects.create', ['number_id' => $object2number->id]) . '">Добавить?</a>']], 404);
        }
    }

    public function addLink(Request $request)
    {
        $link = new Link();
        $link->object1 = $request['object1id'];
        $link->object2 = $request['object2id'];
        $link->linktype = $request['linktype'];
        $link->description = $request['description'];
        $link->save();
    }

    public function delLink($link_id)
    {
        Link::destroy($link_id);
        return redirect()->back();
    }

    public function searchlist(Request $request)
    {
        if(!is_null($request->inputList)) {
            $numberList = explode("\r\n", $request['inputList']);
            $request->flash();
            $objects = Number::has('object')->with('object')->whereIn('number', $numberList)->Paginate(1000);
            $objects->appends(['inputList' => $_REQUEST['inputList']]);
            return view('objects.list', compact('objects'));
        }
            return view('objects.list');
    }

    public function getexcel($data)
    {
        Excel::create('pol_'.time().'', function($excel) use($data) {
            $excel->sheet('Список телефонов', function($sheet) use($data) {
                $sheet->setOrientation('landscape');

                $sheet->cell('A1', function($cell) use($data) {

                    $cell->setValue($data);

                });

            });
        })->export('xlsx');
    }
}
