@extends('layouts.main')

@section('title')
    БД -> Зв'язки об'єкта
@endsection

@section('content')
    <tabs>
        <ul class="nav nav-tabs">
            <li role="presentation"><a href="{{ route('objects.edit', ['$object_id' => $object->id]) }}">Об'єкт</a></li>
            <li role="presentation" class="active"><a href="#">Зв'язки</a></li>
            <li role="presentation"><a href="{{ route('objects.history', ['object_id' => $object->id]) }}">Історія</a></li>
            <li role="presentation"><a href="#">База "Р"</a></li>
        </ul>
    </tabs>
    @if(!$objectLinks->isEmpty())
        <div class="row">
            <h3>Текущие связи</h3>
            <div class="col-md-12">
                <table class="table table-hover">
                    <tr>
                        <th>Об'єкт 1</th>
                        <th>Тип</th>
                        <th>Об'єкт 2</th>
                        <th>Опис зв'язку</th>
                        <th>Додано</th>
                        <th>Видалити</th>
                    </tr>
                    @foreach($objectLinks as $objectLink)
                        <tr>
                            <td><a href="{{ route('objects.edit', ['$object_id' => $objectLink->object1info->id]) }}">{{ $objectLink->object1info->fio }}</a></td>
                            <td>{{ $objectLink->linktype }}</td>
                            <td><a href="{{ route('objects.edit', ['$object_id' => $objectLink->object2info->id]) }}">{{ $objectLink->object2info->fio }}</a></td>
                            <td>{{ $objectLink->description }}</td>
                            <td>{{ $objectLink->created_at }}</td>
                            <td><a href="{{ route('objects.delLink', ['$link_id' => $objectLink->id]) }}" id="dellink">x</a></td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    @endif
    <div class="row" id="errors" style="display:none">
        <br>
        <div class="alert alert-danger">
            <ul>
            </ul>
        </div>
    </div>
    <form action="" method="POST">
        <div class="row">
            <h3>Додати зв'язок</h3>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="inputObject1">Об'єкт 1</label>
                    <input type="text" class="form-control" name="inputObject1" id="inputObject1" placeholder="Об'єкт 1"
                           value="{{ $object->fio }}" readonly>
                </div>
            </div>
            <div class="col-md-1">
                <div class="form-group">
                    <label for="inputLinkType">Тип</label>
                    <select class="form-control" id="inputLinkType">
                        <option value="<="><=</option>
                        <option value="=>">=></option>
                        <option value="<=>"><=></option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="inputObject2">Об'єкт 2</label>
                    <input type="text" class="form-control" name="inputObject2" id="inputObject2" placeholder="Введіть повний номер..."
                           value="">
                </div>
            </div>
            <div class="col-md-5">
                <div class="form-group">
                    <label for="inputDescription">Опис зв'язку</label>
                    <textarea class="form-control" rows="3" name="inputDescription" id="inputDescription"
                              placeholder="Опис зв'язку"></textarea>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-offset-5 col-md-2">
                <div class="form-group">
                    <button type="button" class="btn btn-default" id="checklink">Створити</button>
                </div>
                <input type="text" name="_token" value="{{ csrf_token() }}" hidden>
            </div>
        </div>
    </form>
    <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
         id="addlink-modal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Знайдено наступний об'єкт. Додати зв'язок?</h4>
                </div>
                <div class="modal-body">
                    <table class="table table-hover" id="table-modal">
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Відмінити</button>
                    <button type="button" class="btn btn-primary" id="addlink">Так</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <script>
        var token = '{{ Session::token() }}';
        var url = '{{ route('objects.linkModal', ['object_id' => $object->id]) }}';
        var object1id = '{{ $object->id }}';
    </script>
@endsection