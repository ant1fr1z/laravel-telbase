@extends('layouts.main')

@section('title')
    БД -> Редактировать объект
@endsection

@section('content')
    <tabs>
        <ul class="nav nav-tabs">
            <li role="presentation" class="active"><a href="#">Объект</a></li>
            <li role="presentation"><a href="{{ route('objects.links', ['$object_id' => $object->id]) }}">Связи</a></li>
            <li role="presentation"><a href="#">База "Р"</a></li>
        </ul>
    </tabs>
    <div class="row">
        @if (count($errors) > 0)
            <br>
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
    <div class="row">
        <div class="col-md-6">
            <h3>Редактировать объект для {{ $object->id }}</h3>
            <form class="form-horizontal" action="{{ route('objects.update', ['$object_id' => $object->id]) }}" method="POST">
                <div class="form-group">
                    <label for="inputSecondName" class="col-sm-2 control-label">Фамилия</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="inputSecondName" id="inputSecondName" placeholder="Фамилия" value="{{ $object->secondname }}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputFirstName" class="col-sm-2 control-label">Имя</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="inputFirstName" id="inputFirstName" placeholder="Имя" value="{{ $object->firstname }}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputMiddleName" class="col-sm-2 control-label">Отчество</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="inputMiddleName" id="inputMiddleName" placeholder="Отчество" value="{{ $object->middlename }}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputNickname" class="col-sm-2 control-label">Кличка</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="inputNickname" id="inputNickname" placeholder="Кличка" value="{{ $object->nickname }}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputBirthDay" class="col-sm-2 control-label">Дата рождения</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="inputBirthDay" id="inputBirthDay" placeholder="Дата рождения" value="{{ $object->birthday }}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputAddress" class="col-sm-2 control-label">Адрес</label>
                    <div class="col-sm-10">
                        <textarea class="form-control" rows="3" name="inputAddress" id="inputAddress" placeholder="Адрес">{{ $object->address }}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputWork" class="col-sm-2 control-label">Работа</label>
                    <div class="col-sm-10">
                        <textarea class="form-control" rows="3" name="inputWork" id="inputWork" placeholder="Работа">{{ $object->work }}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPassport" class="col-sm-2 control-label">Паспорт</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="inputPassport" id="inputPassport" placeholder="Паспорт" value="{{ $object->passport }}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputCode" class="col-sm-2 control-label">Ид. код</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="inputCode" id="inputCode" placeholder="Ид. код" value="{{ $object->code }}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputOther" class="col-sm-2 control-label">Другое</label>
                    <div class="col-sm-10">
                        <textarea class="form-control" rows="3" name="inputOther" id="inputOther" placeholder="Другое">{{ $object->other }}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputSource" class="col-sm-2 control-label">Источник</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="inputSource" id="inputSource" placeholder="Источник" value="{{ $object->source }}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputCreated_at" class="col-sm-2 control-label">Создано</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="inputCreated_at" id="inputCreated_at" placeholder="" value="{{ $object->created_at }}" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputUpdated_at" class="col-sm-2 control-label">Обновлено</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="inputUpdated_at" id="inputUpdated_at" placeholder="" value="{{ $object->updated_at }}" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-default">Сохранить</button>
                    </div>
                </div>
                <input type="text" name="_token" value="{{ csrf_token() }}" hidden>
            </form>
        </div>
        <div class="col-md-3"></div>
        <div class="col-md-3">
            <h3>Вторые номера</h3>
            <form action="{{ route('objects.addnumber', ['$object_id' => $object->id]) }}" method="POST">
            <div class="input-group">
                <input type="text" class="form-control" name="inputAddNumber" id="inputAddNumber" placeholder="Введите номер..." value="{{ Request::old('inputAddNumber') }}" >
                  <span class="input-group-btn">
                      <button type="submit" class="btn btn-default" type="button">+</button>
                  </span>
            </div><!-- /input-group -->
                <input type="text" name="_token" value="{{ csrf_token() }}" hidden>
            </form>
            <br>
            @if($object->numbers->isEmpty())
                pysto
            @else
                <table class="table table-hover">
                    <tr>
                        <th>№</th>
                        <th>Del</th>
                    </tr>
                    @if($object->numbers->count() <= 1)
                        @foreach($object->numbers as $number)
                            <tr>
                                <td>{{ $number->number }}</td>
                                <td>x</td>
                            </tr>
                        @endforeach
                    @else
                        @foreach($object->numbers as $number)
                        <tr>
                            <td>{{ $number->number }}</td>
                            <td><a href="{{ route('objects.delnumber', ['$object_id' => $object->id, 'number_id' => $number->id]) }}" class="href">x</a></td>
                        </tr>
                        @endforeach
                    @endif
            @endif
            </table>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(function() {
        $( "#inputBirthDay" ).datepicker({
            dateFormat: "yy-mm-dd",
            changeYear: true,
            yearRange: "1950:2017",
            changeMonth: true
        });
    });
</script>
@endpush
