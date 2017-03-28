@extends('layouts.main')

@section('title')
    БД -> Главная
@endsection

@section('content')
    <div class="row">
        <h3>Все объекты</h3>
        <div class="col-md-4"></div>
        <div class="col-md-4">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Введите номер...">
                  <span class="input-group-btn">
                      <button class="btn btn-default" type="button">Go!</button>
                  </span>
            </div><!-- /input-group -->
        </div><!-- /.col-md-4 -->
        <div class="col-md-4"></div>
    </div>
    <br>
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