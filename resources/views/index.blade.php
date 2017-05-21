@extends('layouts.main')

@section('title')
    БД -> Главная
@endsection

@section('content')
    @include('includes.searchform')
    <div class="row">
        <div class="col-md-12">
            <table class="table table-hover">
                <tr>
                    <th>№</th>
                    <th>Идентификатор</th>
                    <th>ФИО</th>
                    <th>Ред.</th>
                </tr>
                <tr>
                    <td>1</td>
                    <td>1</td>
                    <td>1</td>
                    <td>1</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>2</td>
                    <td>2</td>
                    <td>2</td>
                </tr>
            </table>
        </div>
    </div>

    </div>
@endsection