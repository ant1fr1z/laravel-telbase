@extends('layouts.main')

@section('title')
    БД -> Лог бази
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
            <table class="table table-bordered">
                <tr>
                    <th>Дата зміни</th>
                    <th>Дія</th>
                    <th>IP користувача</th>
                    <th>ID об'єкту</th>
                    <th>Було</th>
                    <th>Стало</th>
                </tr>
                @foreach($logs as $log)
                    <tr>
                        <td>{{ $log->created_at }}</td>
                        <td>{{ $log->action }}</td>
                        <td>{{ $log->ip }}</td>
                        <td>{{ $log->object_id }}</td>
                        <td>
                            <table  class="table table-bordered">
                                <tr><td>{{ $log->old['id'] }}</td></tr>
                                <tr><td></td></tr>
                                <tr><td></td></tr>
                                <tr><td></td></tr>
                                <tr><td></td></tr>
                                <tr><td></td></tr>
                                <tr><td></td></tr>
                            </table>
                        </td>
                        <td>
                            <table  class="table table-bordered">
                                <tr><td></td></tr>
                                <tr><td></td></tr>
                                <tr><td></td></tr>
                                <tr><td></td></tr>
                                <tr><td></td></tr>
                                <tr><td></td></tr>
                                <tr><td></td></tr>
                            </table>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
@endsection
