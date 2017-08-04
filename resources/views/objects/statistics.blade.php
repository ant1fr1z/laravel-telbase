@extends('layouts.main')

@section('title')
    БД -> Статистика бази
@endsection

@section('content')
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
            <h3>Статистика бази</h3>
            В процесі розробки
        </div>
    </div>
@endsection
