<div class="row">
    <div class="col-md-4"></div>
    <div class="col-md-4">
        <form class="form-horizontal" id="searchform" action="{{ route('objects.show') }}" method="POST">
            <div class="input-group">
                <input type="text" class="form-control" name="inputNumber" id="inputNumber" placeholder="Введите номер..." value="{{ Request::old('inputNumber') }}">
                  <span class="input-group-btn">
                      <button type="submit" class="btn btn-default">Go!</button>
                  </span>
            </div><!-- /input-group -->
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