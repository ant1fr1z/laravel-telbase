@extends('layouts.main')

@section('title')
    БД -> Связи объекта
@endsection

@section('content')
    <tabs>
        <ul class="nav nav-tabs">
            <li role="presentation"><a href="{{ route('objects.edit', ['$object_id' => $object->id]) }}">Объект</a></li>
            <li role="presentation" class="active"><a href="#">Связи</a></li>
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
        <form action="" method="POST">
            <h3>Добавить связь</h3>
            <div class="col-md-5">
                <div class="form-group">
                    <label for="inputObject1">Объект 1</label>
                        <input type="text" class="form-control" name="inputObject1" id="inputObject1" placeholder="Объект 1" value="{{ $object->forsearch }}" readonly>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label for="inputLinkType">Тип связи</label>
                    <select class="form-control">
                        <option>=></option>
                        <option><=</option>
                        <option><=></option>
                    </select>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-default">Создать</button>
                    </div>
                </div>
                <input type="text" name="_token" value="{{ csrf_token() }}" hidden>
            </div>
        <div class="col-md-5">
            <div class="form-group">
                <label for="inputObject2">Объект 2</label>
                    <input type="text" class="form-control" name="inputObject2" id="inputObject2" placeholder="Объект 2" value="">
            </div>
        </div>
        </form>
    </div>
@endsection