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
                    <label for="inputList" class="col-sm-2 control-label">Список</label>
                    <div class="col-sm-10">
                        <textarea class="form-control" rows="7" name="inputList" id="inputList" placeholder="Список"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
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
    </div>
@endsection
