@extends('layouts.main')

@section('title')
    БД -> Отобразить объект
@endsection

@section('content')
    @include('includes.searchform')
    <br>
    <div class="row">
        <div class="col-md-7">
            <h3>{{ $number->number }}</h3>
            <form class="form-horizontal">
                <div class="form-group">
                    <label for="inputFio" class="col-sm-2 control-label">ФІО/Кличка</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="inputFio" id="inputFio" placeholder="ФІО/Кличка" value="{{ $number->object->fio }}" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputBirthDay" class="col-sm-2 control-label">Дата народження</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="inputBirthDay" id="inputBirthDay" placeholder="Дата народження" value="{{ $number->object->birthday }}" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputAddress" class="col-sm-2 control-label">Адреса</label>
                    <div class="col-sm-10">
                        <textarea class="form-control" rows="3" name="inputAddress" id="inputAddress" placeholder="Адреса" readonly>{{ $number->object->address }}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputWork" class="col-sm-2 control-label">Робота</label>
                    <div class="col-sm-10">
                        <textarea class="form-control" rows="3" name="inputWork" id="inputWork" placeholder="Робота" readonly>{{ $number->object->work }}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPassport" class="col-sm-2 control-label">Паспорт</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="inputPassport" id="inputPassport" placeholder="Паспорт" value="{{ $number->object->passport }}" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputCode" class="col-sm-2 control-label">Ід. код</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="inputCode" id="inputCode" placeholder="Ід. код" value="{{ $number->object->code }}" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputOther" class="col-sm-2 control-label">Інше</label>
                    <div class="col-sm-10">
                        <textarea class="form-control" rows="3" name="inputOther" id="inputOther" placeholder="Інше" readonly>{{ $number->object->other }}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputSource" class="col-sm-2 control-label">Джерело</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="inputSource" id="inputSource" placeholder="Джерело" value="{{ $number->object->source }}" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputCreated_at" class="col-sm-2 control-label">Створено</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="inputCreated_at" id="inputCreated_at" placeholder="Створено" value="{{ $number->object->created_at }}" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputUpdated_at" class="col-sm-2 control-label">Оновлено</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="inputUpdated_at" id="inputUpdated_at" placeholder="Оновлено" value="{{ $number->object->updated_at }}" readonly>
                    </div>
                </div>

            </form>
        </div>
        <div class="col-md-offset-2 col-md-3">
            <h3>Дії</h3>
            <div class="list-group">
                <a href="{{ route('objects.edit', ['object_id' => $number->object->id]) }}" class="list-group-item">Редагувати</a>
                <a href="{{ route('objects.destroy', ['object_id' => $number->object->id]) }}" class="list-group-item" id="delobject">Видалити</a>
            </div>
        </div>
    </div>
@endsection
