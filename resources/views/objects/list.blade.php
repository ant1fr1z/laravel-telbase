@extends('layouts.main')

@section('title')
    БД -> Поиск по списку
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4">
            <form class="form-horizontal" action="{{ route('objects.list') }}" method="POST">
                <div class="form-group">
                    <label for="inputList" class="col-md-2 control-label">Список</label>
                    <div class="col-md-10">
                        <textarea class="form-control" rows="7" name="inputList" id="inputList"
                                  placeholder="Список">{{ Request::old('inputList') }}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-offset-5 col-md-2">
                        <button type="submit" class="btn btn-default">Найти</button>
                    </div>
                </div>
                <input type="text" name="_token" value="{{ csrf_token() }}" hidden>
            </form>
        </div><!-- /.col-md-4 -->
        <div class="col-md-4"></div>
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
                        <table class="table table-hover">
                            <tr>
                                <th>№</th>
                                <th>Идентификатор</th>
                                <th>ФИО</th>
                                <th>Ред.</th>
                            </tr>
                            @foreach( $objects as $object)
                                <tr>
                                    <td>1</td>
                                    <td>{{ $object->number }}</td>
                                    <td>{{ $object->object->fio }}</td>
                                    <td>
                                        <a href="{{ route('objects.edit', ['object_id' => $object->object->id]) }}">Ред</a>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                        <a href="{{ route('objects.getexcel', ['data' => 'govno']) }}">test</a>
                    </div>
                </div>

    {{ $objects->appends(['inputList' => $_REQUEST['inputList']])->links() }}
    @endif
    @endif
@endsection
