@extends('layouts.main')

@section('title')
    БД -> Создать объект1
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <h3>Создать объект</h3>
            <form class="form-horizontal">
                <div class="form-group">
                    <label for="inputSecondName" class="col-sm-2 control-label">Фамилия</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputSecondName" placeholder="Фамилия">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputFirstName" class="col-sm-2 control-label">Имя</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputFirstName" placeholder="Имя">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputMiddleName" class="col-sm-2 control-label">Отчество</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputMiddleName" placeholder="Отчество">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputNickname" class="col-sm-2 control-label">Кличка</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputNickname" placeholder="Кличка">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputBirthDay" class="col-sm-2 control-label">Дата рождения</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputBirthDay" placeholder="Дата рождения">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputAddress" class="col-sm-2 control-label">Адрес</label>
                    <div class="col-sm-10">
                        <textarea class="form-control" rows="3" id="inputAddress" placeholder="Адрес"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputWork" class="col-sm-2 control-label">Работа</label>
                    <div class="col-sm-10">
                        <textarea class="form-control" rows="3" id="inputWork" placeholder="Работа"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPassport" class="col-sm-2 control-label">Паспорт</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputPassport" placeholder="Паспорт">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputCode" class="col-sm-2 control-label">Ид. код</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputCode" placeholder="Ид. код">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputOther" class="col-sm-2 control-label">Другое</label>
                    <div class="col-sm-10">
                        <textarea class="form-control" rows="3" id="inputOther" placeholder="Другое"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputSource" class="col-sm-2 control-label">Источник</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputSource" placeholder="Источник">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-default">Создать</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-3"></div>
        <div class="col-md-3">
            <h3>Добавить номера</h3>
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Введите номер...">
                  <span class="input-group-btn">
                      <button class="btn btn-default" type="button">+</button>
                  </span>
            </div><!-- /input-group -->
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