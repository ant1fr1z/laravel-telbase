@extends('layouts.main')

@section('title')
    БД -> Пошук по об'єкту
@endsection

@section('content')
    <div class="row">
        <div class="col-md-offset-2 col-md-8">
            <form class="form-horizontal" action="{{ route('objects.searchobject') }}" method="POST">
                <div class="form-group">
                    <label for="inputFio" class="col-xs-2 control-label">ФІО/Кличка</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control" name="inputFio" id="inputFio" placeholder="ФІО/Кличка" value="{{ Request::old('inputFio') }}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputAddress" class="col-xs-2 control-label">Адреса</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="inputAddress" id="inputAddress" placeholder="Адреса" value="{{ Request::old('inputAddress') }}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputWork" class="col-xs-2 control-label">Робота</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="inputWork" id="inputWork" placeholder="Робота" value="{{ Request::old('inputWork') }}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPassport" class="col-xs-2 control-label">Паспорт</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="inputPassport" id="inputPassport" placeholder="Паспорт" value="{{ Request::old('inputPassport') }}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputCode" class="col-xs-2 control-label">Ід. код</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="inputCode" id="inputCode" placeholder="Ід. код" value="{{ Request::old('inputCode') }}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputSource" class="col-xs-2 control-label">Джерело</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="inputSource" id="inputSource" placeholder="Джерело" value="{{ Request::old('inputSource') }}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputUpdatedAt" class="col-xs-2 control-label">Дата оновлення</label>
                    <div class="col-xs-5">
                        <input type="text" class="form-control" name="inputUpdatedAt1" id="inputUpdatedAt1" placeholder="з">
                    </div>
                    <div class="col-xs-5">
                        <input type="text" class="form-control" name="inputUpdatedAt2" id="inputUpdatedAt2" placeholder="по">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-offset-5 col-md-2">
                        <button type="submit" class="btn btn-default">Знайти</button>
                    </div>
                </div>
                <input type="text" name="_token" value="{{ csrf_token() }}" hidden>
            </form>
        </div><!-- /.col-md-4 -->
    </div>
    <div class="row">
        @if (count($errors) > 0)
            <br>
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{!! $error !!}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (isset($objects))
            @if ($objects->count() > 0)
                <div class="row">
                    <div class="col-md-12">
                        <h4>Виведено {{ $objects->count() }} із {{ $objects->total() }} знайдених записів</h4>
                        <div class="btn-group btn-group-justified" role="group" aria-label="...">
                            <div class="btn-group" role="group">
                                <form action="{{ route('objects.getexcelfromobjects') }}" method="POST">
                                    <button type="submit" class="btn btn-default" id="getexcelfromobjects">Експортувати до Excel</button>
                                    <input type="text" name="_token" value="{{ csrf_token() }}" hidden>
                                    <input type="text" name="queryList" value="{{ json_encode($_REQUEST) }}" hidden>
                                </form>
                            </div>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-default" disabled="disabled">Звязати</button>
                            </div>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-default" disabled="disabled">Видалити</button>
                            </div>
                        </div>
                    </div>
                </div>
                    <br>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-hover">
                            <tr>
                                <th>Ідентифікатор</th>
                                <th>ФІО</th>
                                <th>Адреса</th>
                                <th>Ред.</th>
                            </tr>
                            @foreach( $objects as $object)
                                <tr>
                                    <td>{{ $object->numbers->implode('number', ', ') }}</td>
                                    <td>{{ $object->fio }}</td>
                                    <td>{{ $object->address }}</td>
                                    <td>
                                        <a href="{{ route('objects.edit', ['object_id' => $object->id]) }}">Ред</a>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        {{ $objects->links() }}
                    </div>
                </div>
    @endif
    @endif

            <script>
                var token = '{{ Session::token() }}';
                var url = '{{ route('objects.getexcelfromobjects') }}';
            </script>
@endsection

@push('scripts')
            <script>
                $(function() {
                    $( "#inputUpdatedAt1" ).datepicker({
                        dateFormat: "yy-mm-dd",
                        changeYear: true,
                        yearRange: "2015:2017",
                        changeMonth: true
                    });
                    $( "#inputUpdatedAt2" ).datepicker({
                        dateFormat: "yy-mm-dd",
                        changeYear: true,
                        yearRange: "2015:2017",
                        changeMonth: true
                    });
                });
            </script>
@endpush