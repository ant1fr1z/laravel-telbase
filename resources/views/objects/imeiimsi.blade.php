@extends('layouts.main')

            @section('title')
                БД -> Пошук по IMEI-IMSI
            @endsection

            @section('content')
                <div class="row">
                    <div class="col-md-4 col-md-offset-4">
                        <form class="form-horizontal" action="{{ route('objects.imeiimsi') }}" method="POST">
                        <div class="input-group">
                            <div class="input-group-btn">
                                <select class="btn btn-default dropdown-toggle" name="type" id="type">
                                    <option value="num">Номер</option>
                                    <option value="imei">IMEI</option>
                                    <option value="imsi">IMSI</option>
                                </select>
                            </div>
                            <input type="text" class="form-control" name="inputValue" id="inputValue" maxlength="15">
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
                            </span>
                            <input type="text" name="_token" value="{{ csrf_token() }}" hidden>
                        </div>
                        </form>
                    </div>
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
                @if(isset($uniqueimeis) && $uniqueimeis->count() > 0)
                    <div class="row">
                        <div class="col-md-12">
                            <h4>Знайдено {{ $uniqueimeis->count() }} запис(-ів)</h4>
                            <div class="btn-group btn-group-justified" role="group" aria-label="...">
                                <div class="btn-group" role="group">
                                    <form action="" method="POST">
                                        <button type="submit" class="btn btn-default" id="getexcelfromlist">Експортувати до Excel</button>
                                        <input type="text" name="_token" value="{{ csrf_token() }}" hidden>
                                        <input type="text" name="type" value="" hidden>
                                        <input type="text" name="inputValue" value="" hidden>
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
                                @foreach( $uniqueimeis as $item)
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
