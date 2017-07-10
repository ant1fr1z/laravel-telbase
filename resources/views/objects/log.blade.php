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
                    @if ($log->action == 'update')
                    <tr>
                        <td>{{ $log->created_at }}</td>
                        <td class="success">{{ $log->action }}</td>
                        <td>{{ $log->ip }}</td>
                        <td>{{ $log->object_id }}</td>
                        <td>
                            <table  class="table table-condensed">
                                <tr><td>ФІО</td><td>{{ $log->old['fio'] }}</td></tr>
                                <tr><td>ДН</td><td>{{ $log->old['birthday'] }}</td></tr>
                                <tr><td>Адреса</td><td>{{ $log->old['address'] }}</td></tr>
                                <tr><td>Робота</td><td>{{ $log->old['work'] }}</td></tr>
                                <tr><td>Паспорт</td><td>{{ $log->old['passport'] }}</td></tr>
                                <tr><td>Код</td><td>{{ $log->old['code'] }}</td></tr>
                                <tr><td>Інше</td><td>{{ $log->old['other'] }}</td></tr>
                                <tr><td>Джерело</td><td>{{ $log->old['source'] }}</td></tr>
                            </table>
                        </td>
                        <td>
                            <table  class="table table-condensed">
                                <tr><td>ФІО</td><td>{{ $log->new['fio'] }}</td></tr>
                                <tr><td>ДН</td><td>{{ $log->new['birthday'] }}</td></tr>
                                <tr><td>Адреса</td><td>{{ $log->new['address'] }}</td></tr>
                                <tr><td>Робота</td><td>{{ $log->new['work'] }}</td></tr>
                                <tr><td>Паспорт</td><td>{{ $log->new['passport'] }}</td></tr>
                                <tr><td>Код</td><td>{{ $log->new['code'] }}</td></tr>
                                <tr><td>Інше</td><td>{{ $log->new['other'] }}</td></tr>
                                <tr><td>Джерело</td><td>{{ $log->new['source'] }}</td></tr>
                            </table>
                        </td>
                    </tr>
                    @else
                        <tr>
                            <td>{{ $log->created_at }}</td>
                            <td class="danger">{{ $log->action }}</td>
                            <td>{{ $log->ip }}</td>
                            <td>{{ $log->object_id }}</td>
                            <td>
                                <table  class="table table-condensed">
                                    <tr><td>ФІО</td><td>{{ $log->old['fio'] }}</td></tr>
                                    <tr><td>ДН</td><td>{{ $log->old['birthday'] }}</td></tr>
                                    <tr><td>Адреса</td><td>{{ $log->old['address'] }}</td></tr>
                                    <tr><td>Робота</td><td>{{ $log->old['work'] }}</td></tr>
                                    <tr><td>Паспорт</td><td>{{ $log->old['passport'] }}</td></tr>
                                    <tr><td>Код</td><td>{{ $log->old['code'] }}</td></tr>
                                    <tr><td>Інше</td><td>{{ $log->old['other'] }}</td></tr>
                                    <tr><td>Джерело</td><td>{{ $log->old['source'] }}</td></tr>
                                </table>
                            </td>
                            <td>
                            </td>
                        </tr>
                    @endif
                @endforeach
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            {{ $logs->links() }}
        </div>
    </div>
@endsection
