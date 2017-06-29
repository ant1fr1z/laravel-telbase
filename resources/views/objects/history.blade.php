@extends('layouts.main')

@section('title')
    БД -> История объекта
@endsection

@section('content')
    <tabs>
        <ul class="nav nav-tabs">
            <li role="presentation"><a href="{{ route('objects.edit', ['$object_id' => $object->id]) }}">Объект</a></li>
            <li role="presentation"><a href="{{ route('objects.links', ['object_id' => $object->id]) }}">Связи</a></li>
            <li role="presentation" class="active"><a href="#">История</a></li>
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
    <div class="row">
        <div class="col-md-12">
            <h3>История</h3>
        </div>
    </div>

@endsection