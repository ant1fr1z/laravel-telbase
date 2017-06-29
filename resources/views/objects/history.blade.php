@extends('layouts.main')

@section('title')
    БД -> Історія об'єкту
@endsection

@section('content')
    <tabs>
        <ul class="nav nav-tabs">
            <li role="presentation"><a href="{{ route('objects.edit', ['$object_id' => $object->id]) }}">Об'єкт</a></li>
            <li role="presentation"><a href="{{ route('objects.links', ['object_id' => $object->id]) }}">Зв'язки</a></li>
            <li role="presentation" class="active"><a href="#">Історія</a></li>
            <li role="presentation"><a href="#">База "Р"</a></li>
        </ul>
    </tabs>
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
    @if($history->count() > 0)
    <div class="row">
        <div class="col-md-12">
            <h4>Знайдено {{ $history->count() }} записів</h4>
            <div class="btn-group btn-group-justified" role="group" aria-label="...">
                <div class="btn-group" role="group">
                    <form action="" method="POST">
                        <button type="submit" class="btn btn-default" id="getexcelfromlist">Експортувати до Excel
                        </button>
                        <input type="text" name="_token" value="{{ csrf_token() }}" hidden>
                        <input type="text" name="numberList" value="" hidden>
                    </form>
                </div>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-default" disabled="disabled">&nbsp;</button>
                </div>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-default" disabled="disabled">&nbsp;</button>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-hover">
                <tr>
                    <th>Телефон</th>
                    <th>IMSI</th>
                    <th>IMEI</th>
                    <th>Початкова дата</th>
                    <th>Кінцева дата</th>
                </tr>
                @foreach( $history as $item)
                    <tr>
                        <td>{{ $item->numbera }}</td>
                        <td>{{ $item->imsi }}</td>
                        <td>{{ $item->imei }}</td>
                        <td>{{ $item->min }}</td>
                        <td>{{ $item->max }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
    @endif
@endsection