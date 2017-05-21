@extends('layouts.main')

@section('title')
    БД -> Отобразить объект
@endsection

@section('content')
    @include('includes.searchform')
    <br>
    <div class="row">
        <div class="col-md-6">
            <form class="form-horizontal">
                <div class="form-group">
                    <label for="inputSecondName" class="col-sm-2 control-label">Фамилия</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="inputSecondName" id="inputSecondName" placeholder="Фамилия" value="{{ $number->object->secondname }}" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputFirstName" class="col-sm-2 control-label">Имя</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="inputFirstName" id="inputFirstName" placeholder="Имя" value="{{ $number->object->firstname }}" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputMiddleName" class="col-sm-2 control-label">Отчество</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="inputMiddleName" id="inputMiddleName" placeholder="Отчество" value="{{ $number->object->middlename }}" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputNickname" class="col-sm-2 control-label">Кличка</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="inputNickname" id="inputNickname" placeholder="Кличка" value="{{ $number->object->nickname }}" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputBirthDay" class="col-sm-2 control-label">Дата рождения</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="inputBirthDay" id="inputBirthDay" placeholder="Дата рождения" value="{{ $number->object->birthday }}" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputAddress" class="col-sm-2 control-label">Адрес</label>
                    <div class="col-sm-10">
                        <textarea class="form-control" rows="3" name="inputAddress" id="inputAddress" placeholder="Адрес" readonly>{{ $number->object->address }}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputWork" class="col-sm-2 control-label">Работа</label>
                    <div class="col-sm-10">
                        <textarea class="form-control" rows="3" name="inputWork" id="inputWork" placeholder="Работа" readonly>{{ $number->object->work }}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPassport" class="col-sm-2 control-label">Паспорт</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="inputPassport" id="inputPassport" placeholder="Паспорт" value="{{ $number->object->passport }}" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputCode" class="col-sm-2 control-label">Ид. код</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="inputCode" id="inputCode" placeholder="Ид. код" value="{{ $number->object->code }}" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputOther" class="col-sm-2 control-label">Другое</label>
                    <div class="col-sm-10">
                        <textarea class="form-control" rows="3" name="inputOther" id="inputOther" placeholder="Другое" readonly>{{ $number->object->other }}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputSource" class="col-sm-2 control-label">Источник</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="inputSource" id="inputSource" placeholder="Источник" value="{{ $number->object->source }}" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputCreated_at" class="col-sm-2 control-label">Создано</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="inputCreated_at" id="inputCreated_at" placeholder="" value="{{ $number->object->created_at }}" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputUpdated_at" class="col-sm-2 control-label">Обновлено</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="inputUpdated_at" id="inputUpdated_at" placeholder="" value="{{ $number->object->updated_at }}" readonly>
                    </div>
                </div>

            </form>
        </div>
        <div class="col-md-3"></div>
        <div class="col-md-3">
        </div>
    </div>
@endsection
