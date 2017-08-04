<?php

namespace App\Http\Controllers;

use App\Link;
use App\Log;
use App\Number;
use App\Object;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Excel;

class ObjectsController extends Controller
{
    /** ГЛАВНАЯ */
    public function index()
    {
        return view('index');
    }

    /** окно создания объекта */
    public function create($number)
    {
        $num = Number::where('number', $number)->has('object')->first();
        if (empty($num))
        {
            return view('objects.create', ['number' => $number]);
        }
        return redirect()->route('index')->withErrors(['message' => 'Помилка! Даний номер вже привязаний до об\'єкту.']);
    }

    /** сохранение созданного объекта */
    public function store(Request $request, $number)
    {

        $num = Number::where('number', $number)->first();
        if (empty($num))
        {
            $num = Number::create(['number' => $number]);
        }

        $error_messages = [
            'inputFio.required' => 'Поле "ФІО/Кличка" не може бути порожнім.',
            'inputBirthDay.date' => 'Поле "Дата народження" має бути у форматі дати.',
            'inputSource.required' => 'Поле "Джерело" не може бути порожнім.',
        ];

        $this->validate($request, [
            'inputFio' => 'required',
            'inputBirthDay' => 'sometimes|nullable|date',
            'inputSource' => 'required'
        ], $error_messages);

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

        $object->numbers()->save($num);

        return redirect()->route('objects.edit', ['$object_id' => $object->id]);
    }

    /** отображение найденного объекта */
    public function show(Request $request, $inputnumber = null)
    {
        if ($request->isMethod('post')) {

        $error_messages = [
            'inputNumber.required' => 'Поле не може бути порожнім!',
            'inputNumber.telnumber' => 'Помилка! Перевірте введений номер мобільного терміналу.',
        ];

        $this->validate($request, [
            'inputNumber' => 'required|telnumber'
        ], $error_messages);

            $number = Number::with('object')->where('number', $request['inputNumber'])->first();
        } elseif ($request->isMethod('get')) {
            $request['inputNumber'] = $inputnumber;
            $number = Number::with('object')->where('number', $inputnumber)->first();
        }
        if (empty($number) || empty($number->object)) {
            return redirect()->back()->withErrors(['message' => 'Об\'єкт з вказаним номером не знайдено в базі. <a href="' . route('objects.create', ['number' => $request->inputNumber]) . '">Додати?</a>']);
        }
        $request->flash();

            $sessionNumbers = session('numbers');
            if(count($sessionNumbers) >= 10)
            {
                array_shift($sessionNumbers);
                array_push($sessionNumbers, $request['inputNumber']);
                $request->session()->forget('numbers');
                $request->session()->put('numbers', $sessionNumbers);
            } else {
                $request->session()->push('numbers', $request['inputNumber']);
            }
        return view('objects.show', compact('number'));
    }

    /** окно редактирования объекта */
    public function edit($object_id)
    {
        $object = Object::find($object_id);
        return view('objects.edit', compact('object'));
    }

    /** история по объекту */
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

    /** сохранение отредактированого объекта */
    public function update(Request $request, $object_id)
    {
        $error_messages = [
            'inputFio.required' => 'Поле "ФІО/Кличка" не може бути порожнім.',
            'inputBirthDay.date' => 'Поле "Дата народження" має бути у форматі дати.',
            'inputSource.required' => 'Поле "Джерело" не може бути порожнім.',
        ];

        $this->validate($request, [
            'inputFio' => 'required',
            'inputBirthDay' => 'sometimes|nullable|date',
            'inputSource' => 'required'
        ], $error_messages);

        $object = Object::findOrFail($object_id);
        $old = $object->replicate();
        $object->fio = $request['inputFio'];
        $object->birthday = $request['inputBirthDay'];
        $object->address = $request['inputAddress'];
        $object->work = $request['inputWork'];
        $object->passport = $request['inputPassport'];
        $object->code = $request['inputCode'];
        $object->other = $request['inputOther'];
        $object->source = $request['inputSource'];
        $object->save();
        $new = $object->replicate();

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

    /** добавление привязанного номера */
    public function addnumber(Request $request, $object_id)
    {
        $error_messages = [
            'inputAddNumber.required' => 'Поле з додатковим номером не може бути порожнім.',
            'inputAddNumber.telnumber' => 'Помилка! Номер мобільного терміналу має включати 12 цифр (380xxxxxxxxx).',
        ];

        $this->validate($request, [
            'inputAddNumber' => 'required|telnumber'
        ], $error_messages);

        $number = Number::where('number', $request['inputAddNumber'])->first();
        if (empty($number))
        {
            $number = Number::create(['number' => $request['inputAddNumber']]);
        }

            if (!is_null($number->object)) {
                return redirect()->back()->withErrors(['message' => 'Номер вже прив\'язаний  до <a href="' . route('objects.edit', ['$object_id' => $number->object->id]) . '">об\'єкту</a>!']);
            }
            $object = Object::findOrFail($object_id);
            $number->object()->associate($object)->save();

            return redirect()->back();
    }

    /** удаление привязанного номера */
    public function delnumber($object_id, $number_id)
    {
        $number = Number::findOrFail($number_id);
        $number->object()->dissociate()->save();
        return redirect()->back();
    }

    /** удаление объекта со всеми его связими */
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

    /** страничка связей объекта */
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

    /** модальное окно связей объекта */
    public function linkModal(Request $request)
    {
        $error_messages = [
            'object2number.required' => 'Поле "Об\'єкт 2" не може бути порожнім.',
            'object2number.telnumber' => 'Помилка! Номер мобільного терміналу має включати 12 цифр (380xxxxxxxxx).',
            'description.required' => 'Опис зв\'язку не може бути порожнім.',
        ];

        $this->validate($request, [
            'object2number' => 'required|telnumber',
            'description' => 'required',
        ], $error_messages);

        $object2number = Number::with('object')->where('number', $request['object2number'])->first();
        if(empty($object2number) || empty($object2number->object))
        {
            return response()->json(['errors' => ['0' => 'Об\'єкт з вказаним номером не знайдено в базі. <a href="' . route('objects.create', ['number' => $request['object2number']]) . '">Додати?</a>']], 404);
        }
            return response()->json(['number2' => $object2number]);
    }

    /** добавление связи */
    public function addLink(Request $request)
    {
        $link = new Link();
        $link->object1 = $request['object1id'];
        $link->object2 = $request['object2id'];
        $link->linktype = $request['linktype'];
        $link->description = $request['description'];
        $link->save();
    }

    /** удаление связи */
    public function delLink($link_id)
    {
        Link::destroy($link_id);
        return redirect()->back();
    }

    /** поиска по списку */
    public function searchlist(Request $request)
    {
        if ($request->isMethod('post')) {
            $error_messages = [
                'inputList.required' => 'Список не може бути порожнім.',
            ];

            $this->validate($request, [
                'inputList' => 'required',
            ], $error_messages);

            $numberList = explode("\r\n", $request['inputList']);
            $objects = Number::has('object')->with('object')->whereIn('number', $numberList)->Paginate(500)->appends(['inputList' => $request['inputList']]);

            if ($objects->count() >0) {
                return view('objects.list', compact('numberList', 'objects'));
            } else {
                return view('objects.list')->withErrors(['messages' => 'Нічого не знайдено.']);
            }


        }
        return view('objects.list');
    }

    /** поиск по объекту */
    public function searchobject(Request $request)
    {
        if ( isset($request->inputFio) || isset($request->inputAddress) || isset($request->inputWork) || isset($request->inputPassport) || isset($request->inputCode) || isset($request->inputSource) || isset($request->inputUpdatedAt1) && isset($request->inputUpdatedAt2) ) {
            if ( isset($request->inputUpdatedAt1) && isset($request->inputUpdatedAt2) )
            {
                $objects = Object::with('numbers')->whereRaw('IFNULL(fio, 0) LIKE "%'.$request['inputFio'].'%" AND IFNULL(address, 0) LIKE "%'.$request['inputAddress'].'%" AND IFNULL(work,0) LIKE "%'.$request['inputWork'].'%" AND IFNULL(passport,0) LIKE "%'.$request['inputPassport'].'%" AND IFNULL(code,0) LIKE "%'.$request['inputCode'].'%" AND IFNULL(source,0) LIKE "%'.$request['inputSource'].'%"')->whereBetween('updated_at', [$request['inputUpdatedAt1'], $request['inputUpdatedAt2']])->Paginate(500)->appends($_REQUEST);
            }
            else
            {
                $objects = Object::with('numbers')->whereRaw('IFNULL(fio, 0) LIKE "%'.$request['inputFio'].'%" AND IFNULL(address, 0) LIKE "%'.$request['inputAddress'].'%" AND IFNULL(work,0) LIKE "%'.$request['inputWork'].'%" AND IFNULL(passport,0) LIKE "%'.$request['inputPassport'].'%" AND IFNULL(code,0) LIKE "%'.$request['inputCode'].'%" AND IFNULL(source,0) LIKE "%'.$request['inputSource'].'%"')->Paginate(500)->appends($_REQUEST);
            }
            $request->flash();
            if ($objects->count() >0) {
                return view('objects.searchobject', compact( 'objects'));
            } else {
                return view('objects.searchobject')->withErrors(['messages' => 'Нічого не знайдено.']);
            }
        }
        return view('objects.searchobject');
    }

    /** експорт объектов из поиска по списку в ексель */
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

    /** експорт объектов из поиска по объекту в ексель */
    public function getexcelfromobjects(Request $request)
    {
        $query = json_decode($request->queryList);
        $objects = Object::with('numbers')->whereRaw('IFNULL(fio, 0) LIKE "%'.$query->inputFio.'%" AND IFNULL(address, 0) LIKE "%'.$query->inputAddress.'%" AND IFNULL(work,0) LIKE "%'.$query->inputWork.'%" AND IFNULL(passport,0) LIKE "%'.$query->inputPassport.'%" AND IFNULL(code,0) LIKE "%'.$query->inputCode.'%" AND IFNULL(source,0) LIKE "%'.$query->inputSource.'%"')->get();

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

    /** експорт объекта в ексель */
    public function getexcelfromobject($object_id)
    {
        /** вкладка Объект */
        $object = Object::with('numbers')->findOrFail($object_id);

        /** вкладка Связи */
        $objectLinks = Link::where('object1', $object_id)->orWhere('object2', $object_id)->get();
        $objectLinks->each(function ($item, $key) {
            $item->object1info = Object::find($item->object1);
            $item->object2info = Object::find($item->object2);
        });

        /** вкладка История */
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

        /** формируем ексаль файл из данных */
        Excel::create('pol_'.time().'_objectcard', function($excel) use($object, $objectLinks, $history) {
            $excel->sheet('Об\'єкт', function($sheet) use($object) {
                $sheet->setOrientation('landscape');
                $sheet->row(1, array('Ідентифікатор', 'ФІО', 'Дата народження', 'Адреса', 'Робота', 'Паспорт', 'Ід.код', 'Інше', 'Джерело', 'Додано', 'Оновлено'));
                $sheet->row(1, function($row) {

                    $row->setBackground('#D3D3D3');
                    $row->setFontSize(12);
                    $row->setFontWeight('bold');

                });
                    $sheet->appendRow(array($object->numbers->implode('number', ', '), $object['fio'], $object['birthday'], $object['address'], $object['work'], $object['passport'], $object['code'], $object['other'], $object['source'], $object['created_at'], $object['updated_at']));
            });
            $excel->sheet('Зв\'язки', function($sheet) use($objectLinks) {
                $sheet->setOrientation('landscape');
                $sheet->row(1, array('Об\'єкт 1', 'Тип', 'Об\'єкт 2', 'Опис'));
                $sheet->row(1, function($row) {

                    $row->setBackground('#D3D3D3');
                    $row->setFontSize(12);
                    $row->setFontWeight('bold');

                });
                foreach ($objectLinks as $objectLink)
                {
                    $sheet->appendRow(array($objectLink['object1'], $objectLink['linktype'], $objectLink['object2'], $objectLink['description']));
                }
            });
            $excel->sheet('Історія', function($sheet) use($history) {
                $sheet->setOrientation('landscape');
                    $sheet->row(1, array('Номер', 'IMSI', 'IMEI', 'Початкова дата', 'Кінцева дата'));
                $sheet->row(1, function($row) {

                    $row->setBackground('#D3D3D3');
                    $row->setFontSize(12);
                    $row->setFontWeight('bold');

                });
                foreach ($history as $item)
                {
                    $sheet->appendRow(array($item->numbera, $item->imsi, $item->imei, $item->min, $item->max));
                }
            });
        })->export('xlsx');
    }

    /** страничка с Логом */
    public function getLog()
    {
        if (Auth::check()) {
            $logs = Log::orderBy('created_at', 'desc')->paginate(5);
            foreach ($logs as $log) {
                $log['old'] = json_decode($log->old, true);
                $log['new'] = json_decode($log->new, true);
            }
            return view('objects.log', compact('logs'));
        }
        return redirect('/login');
    }

    /** страничка поиска IMEI-IMSI */
    public function imeiimsi(Request $request)
    {
        if ($request->isMethod('post')) {
            $error_messages = [
                'inputValue.required' => 'Поле не може бути порожнім.',
            ];
            $this->validate($request, [
                'inputValue' => 'required',
            ], $error_messages);

            /** по номеру */
            if ($request->type == 'num') {
                $error_messages = [
                    'inputValue.digits_between' => 'Перевірте номер, має бути 11-12 цифр (380..., 79...)',
                ];
                $this->validate($request, [
                    'inputValue' => 'digits_between:11,12',
                ], $error_messages);

                    $traffic = DB::connection('traffic')->table('traffic_line')->select('numbera', 'imsi', 'imei', 'starttime')->where('numbera', $request->inputValue)->get();
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

                        return view('objects.imeiimsi', compact('uniqueimeis'));
                    }
                return view('objects.imeiimsi', compact('uniqueimeis'))->withErrors(['message' => 'Інформація відсутня у базі. Завантажте до бази трафік по даному номеру.']);
            }

            /** по имеи */
            if ($request->type == 'imei') {
                $error_messages = [
                    'inputValue.digits' => 'Перевірте IMEI, має бути 14 цифр.',
                ];
                $this->validate($request, [
                    'inputValue' => 'digits:14',
                ], $error_messages);

                $traffic = DB::connection('traffic')->table('traffic_line')->select('numbera', 'imsi', 'imei', 'starttime')->where('imei', $request->inputValue)->get();
                if (!$traffic->isEmpty())
                {
                    $uniqueimeis = $traffic->unique('imsi')->filter(function ($value, $key) {
                        return $value->imsi;
                    })->values();

                    foreach ($uniqueimeis as $item)
                    {
                        $item->min = $traffic->where('imsi', $item->imsi)->min('starttime');
                        $item->max = $traffic->where('imsi', $item->imsi)->max('starttime');
                    }
                    return view('objects.imeiimsi', compact('uniqueimeis'));
                }
                return view('objects.imeiimsi', compact('uniqueimeis'))->withErrors(['message' => 'Інформація відсутня у базі. Завантажте до бази трафік по даному IMEI.']);
            }

            /** по имси */
            if ($request->type == 'imsi') {
                $error_messages = [
                    'inputValue.digits' => 'Перевірте IMSI, має бути 15 цифр.',
                ];
                $this->validate($request, [
                    'inputValue' => 'digits:15',
                ], $error_messages);

                $traffic = DB::connection('traffic')->table('traffic_line')->select('numbera', 'imsi', 'imei', 'starttime')->where('imsi', $request->inputValue)->get();
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

                    return view('objects.imeiimsi', compact('uniqueimeis'));
                }
                return view('objects.imeiimsi', compact('uniqueimeis'))->withErrors(['message' => 'Інформація відсутня у базі. Завантажте до бази трафік по даному номеру.']);
            }
        }
        return view('objects.imeiimsi');
    }

    /** страничка с Картой */
    public function map(Request $request)
    {
        if ($request->isMethod('post')) {
            $error_messages = [
                'inputValue.required' => 'Поле не може бути порожнім.',
            ];
            $this->validate($request, [
                'inputValue' => 'required',
            ], $error_messages);

            /** по номеру */
            if ($request->type == 'num') {
                $error_messages = [
                    'inputValue.digits_between' => 'Перевірте номер, має бути 11-12 цифр (380..., 79...)',
                ];
                $this->validate($request, [
                    'inputValue' => 'digits_between:11,12',
                ], $error_messages);
            }

            /** по имеи */
            if ($request->type == 'imei') {
                $error_messages = [
                    'inputValue.digits' => 'Перевірте IMEI, має бути 14 цифр.',
                ];
                $this->validate($request, [
                    'inputValue' => 'digits:14',
                ], $error_messages);

                $request['inputValue'] = $request->inputValue.'0';
                $locations = DB::table('locations')->where('imei', $request->inputValue)->get();
                $locations = $locations->unique(function ($item) {
                    return $item->imei.$item->imsi.$item->ll;
                })->each(function ($item) {
                    $item->rxtx = $this->getRxTx($item->rxtx);
                    $item->ll = $this->getLonLat($item->ll);
                    $item->date = $this->getDate($item->sdate);
                });

                if ($locations->count() > 0)
                {
                    return view('objects.map', compact('locations'));
                }
                else
                {
                    return view('objects.map', compact('locations'))->withErrors(['message' => 'Геодані відсутні у базі.']);
                }

            }

            /** по имси */
            if ($request->type == 'imsi') {
                $error_messages = [
                    'inputValue.digits' => 'Перевірте IMSI, має бути 15 цифр.',
                ];
                $this->validate($request, [
                    'inputValue' => 'digits:15',
                ], $error_messages);

                $locations = DB::table('locations')->where('imsi', $request->inputValue)->get();
                $locations = $locations->unique(function ($item) {
                    return $item->imei.$item->imsi.$item->ll;
                })->each(function ($item) {
                    $item->rxtx = $this->getRxTx($item->rxtx);
                    $item->ll = $this->getLonLat($item->ll);
                    $item->date = $this->getDate($item->sdate);
                });

                if ($locations->count() > 0)
                {
                    return view('objects.map', compact('locations'));
                }
                else
                {
                    return view('objects.map', compact('locations'))->withErrors(['message' => 'Геодані відсутні у базі.']);
                }
            }
        }

        return view('objects.map');
    }

    /** страничка статистики базы */
    public function getStatistics()
    {
        return view('objects.statistics');
    }

    /** страничка с картой для объекта */
    public function getLocations($object_id)
    {
        $object = Object::with('numbers')->find($object_id);
        $history = collect();
        foreach ($object->numbers as $number)
        {
            $traffic = DB::connection('traffic')->table('traffic_line')->select('numbera', 'imsi', 'imei')->where('numbera', $number->number)->get();
            if (!$traffic->isEmpty())
            {
                $uniqueimeis = $traffic->unique('imei')->filter(function ($value) {
                    return $value->imei;
                })->values();
                $history->push($uniqueimeis);
            }
        }
        $history = $history->flatten();
        if ($history->count() > 0)
        {
            $locations = collect();
            foreach ($history as $item)
            {
                $loc = DB::table('locations')->where('imsi', $item->imsi)->orWhere('imei', $item->imei)->get();
                if (!$loc->isEmpty())
                {
                    $locations->push($loc);
                }
            }
            $locations = $locations->flatten()->unique(function ($item) {
                return $item->imei.$item->imsi.$item->ll;
            })->each(function ($item) {
                $item->rxtx = $this->getRxTx($item->rxtx);
                $item->ll = $this->getLonLat($item->ll);
                $item->date = $this->getDate($item->sdate);
            });
            if ($locations->count() > 0)
            {
                return view('objects.locations', compact('object', 'locations'));
            }
            else
                {
                    return view('objects.locations', compact('object', 'locations'))->withErrors(['message' => 'Геодані відсутні у базі.']);
                }
        }
        else
            {
                return view('objects.locations', compact('object'))->withErrors(['message' => 'У базі відсутні IMEI/IMSI даного номера. Завантажте до бази трафік по даному номеру для перевірки наявності геоданих.']);
            }
    }

    /**
     * ВСПОМАГАТЕЛЬНЫЕ
     * ФУНКЦИИ
     * ПРЕОБРАЗОВАНИЯ
     * ДАННЫХ ИЗ БД "VARAN`a"
     */

    /** функция преобразования rxtx из базы в массив rx,tx */
    public static function getRxTx($value)
    {
        //обработка rxtx
        $rxtx['rx'] = ($value >> 8) - 110;
        $temp = unpack("C", pack("C", $value));
        $rxtx['tx'] = $temp[1] - 110;
        return $rxtx;
    }

    /** функция преобразования LL из базы в массив координат lat,lon*/
    public static function getLonLat($value)
    {
        //обработка координат
        $num3 = $value >> 30;
        $num3 = $num3 >> 24;
        $num3 .= 1;
        $num2 = $value >> 29;
        $num2 = $num2 >> 24;
        $num2 .= 1;
        $num12 = $value << 11;
        $value = $num12 >> 11;
        $num4 = $value / 54000000;
        $num5 = $value - ((int)$num4 * 54000000);
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
        $lonlat['lon'] = $lon;
        $lonlat['lat'] = $lat;

        return $lonlat;
    }

    /** функция преобразования SDATE из базы в строку date*/
    public static function getDate($value)
    {
        //обработка даты

        $hour = 0;
        $minute = 0;
        $second = 0;
        $tmp = (int) ($value / 86400);
        $tmp3 = intval($tmp / 372);
        $month = intval((($tmp - ($tmp3 * 372)) / 31) + 1);
        $day = intval((($tmp - ($tmp3 * 372)) - (($month - 1) * 31)) + 1);
        $tmp2 = ((int) $value) - ($tmp * 86400);
        $hour = intval($tmp2 / 3600);
        $tmp9 = intval($hour * 3600);
        $minute = intval(($tmp2 - $tmp9) / 60);
        $tmp9 += $minute * 60;
        $second = intval($tmp2 - $tmp9);

        $dt = Carbon::create($tmp3+2000, $month, $day, $hour, $minute, $second);
        $dt->timezone = 'Europe/Kiev';

        return $dt->toDateTimeString();;
    }
}
