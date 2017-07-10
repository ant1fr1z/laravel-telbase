@extends('layouts.main')

            @section('title')
                БД -> Пошук по IMEI-IMSI
            @endsection

            @section('content')
                <div class="row">
                    <div class="col-md-4 col-md-offset-4">

                        <div class="input-group">
                            <div class="input-group-btn">
                                <select class="btn btn-default dropdown-toggle">
                                    <option>Номер</option>
                                    <option>IMEI</option>
                                    <option>IMSI</option>
                                </select>
                            </div>
                            <input type="text" class="form-control" aria-label="...">
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
                            </span>
                        </div>

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
@endsection
