@extends('layouts.main')

            @section('title')
                БД -> Пошук по списку
            @endsection

            @section('content')
                <div class="row">
                    <div class="col-md-4"></div>
                    <div class="col-md-4">
                        <form class="form-horizontal" action="{{ route('objects.list') }}" method="POST">
                            <div class="form-group">
                                <label for="inputList" class="col-md-2 control-label">Список</label>
                                <div class="col-md-10">
                        <textarea class="form-control" rows="7" name="inputList" id="inputList"
                                  placeholder="Не більше 10000 рядків">{{ Request::old('inputList') }}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-offset-5 col-md-2">
                                    <button type="submit" class="btn btn-default">Знайти</button>
                                </div>
                            </div>
                            <input type="text" name="_token" value="{{ csrf_token() }}" hidden>
                        </form>
                    </div><!-- /.col-md-4 -->
                    <div class="col-md-4"></div>
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
                    @if (isset($objects))
                        @if ($objects->count() > 0)
                            <div class="row">
                                <div class="col-md-12">
                                    <h4>Виведено {{ $objects->count() }} із {{ $objects->total() }} знайдених записів</h4>
                                    <div class="btn-group btn-group-justified" role="group" aria-label="...">
                                        <div class="btn-group" role="group">
                                            <form action="{{ route('objects.getexcelfromlist') }}" method="POST">
                                                <button type="submit" class="btn btn-default" id="getexcelfromlist">Експортувати до Excel</button>
                                                <input type="text" name="_token" value="{{ csrf_token() }}" hidden>
                                                <input type="text" name="numberList" value="{{ json_encode($numberList) }}" hidden>
                                            </form>
                                        </div>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-default" disabled="disabled">Звязати</button>
                                        </div>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-default" disabled="disabled">Видалити</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-hover">
                                        <tr>
                                            <th>Ідентифікатор</th>
                                            <th>ФІО</th>
                                            <th>Адреса</th>
                                            <th>Ред.</th>
                                        </tr>
                                        @foreach( $objects as $object)
                                            <tr>
                                                <td>{{ $object->number }}</td>
                                                <td>{{ $object->object->fio }}</td>
                                                <td>{{ $object->object->address }}</td>
                                                <td>
                                                    <a href="{{ route('objects.edit', ['object_id' => $object->object->id]) }}">Ред</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </table>
                                </div>
                            </div>
                        @endif
                    @endif

                    <script>
                        var token = '{{ Session::token() }}';
                        var url = '{{ route('objects.getexcelfromlist') }}';
                    </script>
@endsection
