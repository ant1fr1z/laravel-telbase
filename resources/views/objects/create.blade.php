@extends('layouts.main')

@section('title')
    БД -> Создать объект
@endsection

@section('content')
    <div class="row">
        @if (count($errors) > 0)
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
            <h3>Создать объект для {{ $number }}</h3>
            <form class="form-horizontal" action="{{ route('objects.store', ['number' => $number]) }}" method="POST">
                <div class="form-group">
                    <label for="inputFio" class="col-sm-2 control-label">ФИО/Кличка</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="inputFio" id="inputFio" placeholder="ФИО/Кличка" value="{{ Request::old('inputFio') }}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputBirthDay" class="col-sm-2 control-label">Дата рождения</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="inputBirthDay" id="inputBirthDay" placeholder="Дата рождения" value="{{ Request::old('inputBirthDay') }}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputAddress" class="col-sm-2 control-label">Адрес</label>
                    <div class="col-sm-10">
                        <textarea class="form-control" rows="3" name="inputAddress" id="inputAddress" placeholder="Адрес">{{ Request::old('inputAddress') }}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputWork" class="col-sm-2 control-label">Работа</label>
                    <div class="col-sm-10">
                        <textarea class="form-control" rows="3" name="inputWork" id="inputWork" placeholder="Работа">{{ Request::old('inputWork') }}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPassport" class="col-sm-2 control-label">Паспорт</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="inputPassport" id="inputPassport" placeholder="Паспорт" value="{{ Request::old('inputPassport') }}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputCode" class="col-sm-2 control-label">Ид. код</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="inputCode" id="inputCode" placeholder="Ид. код" value="{{ Request::old('inputCode') }}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputOther" class="col-sm-2 control-label">Другое</label>
                    <div class="col-sm-10">
                        <textarea class="form-control" rows="3" name="inputOther" id="inputOther" placeholder="Другое">{{ Request::old('inputOther') }}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputSource" class="col-sm-2 control-label">Источник</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="inputSource" id="inputSource" placeholder="Источник" value="{{ Request::old('inputSource') }}">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-default">Создать</button>
                    </div>
                </div>
                <input type="text" name="_token" value="{{ csrf_token() }}" hidden>
            </form>
        </div>
        <div class="col-md-3"></div>
        <div class="col-md-3">
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
