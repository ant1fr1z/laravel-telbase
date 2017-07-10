<?php

namespace App\Http\Controllers;

use App\Link;
use App\Log;
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
            'inputNumber.required' => 'Поле не може бути пустим!',
            'inputNumber.exists' => 'Помилка, швидше кличте адміністратора!',
        ];

        $this->validate($request, [
            'inputNumber' => 'required|exists:numbers,number'
        ], $error_messages);


        $number = Number::with('object')->where('number', $request['inputNumber'])->first();
        //dd($number);

        if (empty($number->object)) {
            return redirect()->back()->withErrors(['message' => 'Номер не знайдено в базі. <a href="' . route('objects.create', ['number_id' => $number->id]) . '">Додати?</a>']);
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

    public function history($object_id)
    {
        $object = Object::with('numbers')->find($object_id);
        $history = collect();
        foreach ($object->numbers as $number)
        {
            $traffic = DB::connection('traffic')->table('traffic_line')->select('numbera', 'imsi', 'imei', 'starttime')->where('numbera', $number->number)->get();
            if (!$traffic->isEmpty())
            {
                $uniqueimeis = $traffic->unique('imei')->filter(function ($value, $key) {
                    return $value->imei;
                })->values();

                foreach ($uniqueimeis as $item)
                {
                    $item->min = $traffic->where('imei', $item->imei)->min('starttime');
                    $item->max = $traffic->where('imei', $item->imei)->max('starttime');
                }

                $history->push($uniqueimeis);
            }
        }
        $history = $history->flatten();
        if ($history->count() > 0)
        {
            return view('objects.history', compact('object', 'history'));

        } else {
            return view('objects.history', compact('object', 'history'))->withErrors(['message' => 'Інформація відсутня у базі. Завантажте до бази трафік по даному номеру.']);
        }

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
        $old = $object;
        $object->fio = $request['inputFio'];
        $object->birthday = $request['inputBirthDay'];
        $object->address = $request['inputAddress'];
        $object->work = $request['inputWork'];
        $object->passport = $request['inputPassport'];
        $object->code = $request['inputCode'];
        $object->other = $request['inputOther'];
        $object->source = $request['inputSource'];
        $new = $object;
        $object->save();

        //логгирование изменений объекта базы
        $log = new Log();
        $log->old = $old;
        $log->new = $new;
        $log->action = 'update';
        $log->ip = $_SERVER['REMOTE_ADDR'];
        $log->object()->associate($object)->save();
        //конец логгирования

        return redirect()->back();
    }

    public function addnumber(Request $request, $object_id)
    {
        $number = Number::where('number', $request['inputAddNumber'])->first();

        //dd($number);
        if (empty($number)) {
            return redirect()->back()->withErrors(['message' => 'Помилка, швидше кличте адміністратора!']);
        } else {

            if (!is_null($number->object)) {
                return redirect()->back()->withErrors(['message' => 'Номер вже прив\'язаний  до <a href="' . route('objects.edit', ['$object_id' => $number->object->id]) . '">об\'єкту</a>!']);
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
    public function destroy($object_id)
    {
        $object = Object::findOrFail($object_id);

        //логгирование удаления объекта из базы
        $log = new Log();
        $log->old = $object;
        $log->action = 'delete';
        $log->ip = $_SERVER['REMOTE_ADDR'];
        $log->object()->associate($object)->save();
        //конец логгирования

        $object->numbers->each(function ($item, $key) {
            $item->object()->dissociate()->save();
        });

        Link::where('object1', $object_id)->orWhere('object2', $object_id)->delete();
        $object->delete();

        return redirect()->route('index');
    }

    /**
     * Страничка связей объекта
     *
     * @param  int $object_id
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
            'object2number.required' => 'Поле "Об\'єкт 2" не може бути порожнім!',
            'object2number.exists' => 'Помилка, швидше кличте адміністратора!',
            'description.required' => 'Опис зв\'язку не може бути порожнім!',
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
        if ($request->isMethod('post')) {
            $error_messages = [
                'inputList.required' => 'Список не може бути порожнім!',
            ];

            $this->validate($request, [
                'inputList' => 'required',
            ], $error_messages);

            $numberList = explode("\r\n", $request['inputList']);
            $objects = Number::has('object')->with('object')->whereIn('number', $numberList)->Paginate(500)->appends(['inputList' => $request['inputList']]);

            if ($objects->count() >0) {
                return view('objects.list', compact('numberList', 'objects'));
            } else {
                return view('objects.list')->withErrors(['messages' => 'Нічого не знайдено!']);
            }


        }
        return view('objects.list');
    }

    public function searchobject(Request $request)
    {
        if ( isset($request->inputFio) || isset($request->inputAddress) || isset($request->inputWork) || isset($request->inputPassport) || isset($request->inputCode) || isset($request->inputSource) || isset($request->inputUpdatedAt1) && isset($request->inputUpdatedAt2) ) {
            if ( isset($request->inputUpdatedAt1) && isset($request->inputUpdatedAt2) )
            {
                $objects = Object::with('numbers')->whereRaw('IFNULL(fio, 0) REGEXP "'.$request['inputFio'].'" AND IFNULL(address, 0) REGEXP "'.$request['inputAddress'].'" AND IFNULL(work,0) REGEXP "'.$request['inputWork'].'" AND IFNULL(passport,0) REGEXP "'.$request['inputPassport'].'" AND IFNULL(code,0) REGEXP "'.$request['inputCode'].'" AND IFNULL(source,0) REGEXP "'.$request['inputSource'].'"')->whereBetween('updated_at', [$request['inputUpdatedAt1'], $request['inputUpdatedAt2']])->Paginate(500)->appends($_REQUEST);
            }
            else
            {
                $objects = Object::with('numbers')->whereRaw('IFNULL(fio, 0) REGEXP "'.$request['inputFio'].'" AND IFNULL(address, 0) REGEXP "'.$request['inputAddress'].'" AND IFNULL(work,0) REGEXP "'.$request['inputWork'].'" AND IFNULL(passport,0) REGEXP "'.$request['inputPassport'].'" AND IFNULL(code,0) REGEXP "'.$request['inputCode'].'" AND IFNULL(source,0) REGEXP "'.$request['inputSource'].'"')->Paginate(500)->appends($_REQUEST);
            }
            $request->flash();
            if ($objects->count() >0) {
                return view('objects.searchobject', compact( 'objects'));
            } else {
                return view('objects.searchobject')->withErrors(['messages' => 'Нічого не знайдено!']);
            }
        }
        return view('objects.searchobject');
    }

    public function getexcelfromlist(Request $request)
    {
        $objects = Number::has('object')->with('object')->whereIn('number', json_decode($request->numberList))->get()->toArray();
        //dd($objects);
        Excel::create('pol_'.time().'', function($excel) use($objects) {
            $excel->sheet('Список телефонів', function($sheet) use($objects) {
                $sheet->setOrientation('landscape');
                $sheet->row(1, array('Ідентифікатор', 'ФІО', 'Дата народження', 'Адреса', 'Робота', 'Паспорт', 'Ід.код', 'Інше', 'Джерело', 'Додано', 'Оновлено'));
                $sheet->row(1, function($row) {

                    $row->setBackground('#D3D3D3');
                    $row->setFontSize(12);
                    $row->setFontWeight('bold');

                });
                foreach ($objects as $object) {
                    $sheet->appendRow(array($object['number'], $object['object']['fio'], $object['object']['birthday'], $object['object']['address'], $object['object']['work'], $object['object']['passport'], $object['object']['code'], $object['object']['other'], $object['object']['source'], $object['object']['created_at'], $object['object']['updated_at']));
                }
            });
        })->export('xlsx');
    }

    public function getexcelfromobjects(Request $request)
    {
        $query = json_decode($request->queryList);
        $objects = Object::with('numbers')->whereRaw('IFNULL(fio, 0) REGEXP "'.$query->inputFio.'" AND IFNULL(address, 0) REGEXP "'.$query->inputAddress.'" AND IFNULL(work,0) REGEXP "'.$query->inputWork.'" AND IFNULL(passport,0) REGEXP "'.$query->inputPassport.'" AND IFNULL(code,0) REGEXP"'.$query->inputCode.'" AND IFNULL(source,0) REGEXP "'.$query->inputSource.'"')->get();

        Excel::create('pol_'.time().'', function($excel) use($objects) {
            $excel->sheet('Список телефонів', function($sheet) use($objects) {
                $sheet->setOrientation('landscape');
                $sheet->row(1, array('Ідентифікатор', 'ФІО', 'Дата народження', 'Адреса', 'Робота', 'Паспорт', 'Ід.код', 'Інше', 'Джерело', 'Додано', 'Оновлено'));
                $sheet->row(1, function($row) {

                    $row->setBackground('#D3D3D3');
                    $row->setFontSize(12);
                    $row->setFontWeight('bold');

                });
                foreach ($objects as $object) {
                    $sheet->appendRow(array($object->numbers->implode('number', ', '), $object['fio'], $object['birthday'], $object['address'], $object['work'], $object['passport'], $object['code'], $object['other'], $object['source'], $object['created_at'], $object['updated_at']));
                }
            });
        })->export('xlsx');
    }

    /**
     * Страничка с логом изменений базы
     *
     * @return \Illuminate\Http\Response
     */
    public function getLog()
    {
        $logs = Log::orderBy('created_at', 'desc')->paginate(5);
        foreach ($logs as $log) {
            $log['old'] = json_decode($log->old, true);
            $log['new'] = json_decode($log->new, true);
        }
        return view('objects.log', compact('logs'));
    }

    /**
     * Страничка поиска IMEI-IMSI
     *
     * @return \Illuminate\Http\Response
     */
    public function imeiimsi()
    {
        return view('objects.imeiimsi');
    }
}
