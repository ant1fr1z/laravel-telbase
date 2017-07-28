<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8"/>
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ URL::to('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ URL::to('css/jquery-ui.min.css') }}">

    <style>
        #historySlideout {
            position: fixed;
            top: 100px;
            left: 0;
            -webkit-transition-duration: 0.3s;
            -moz-transition-duration: 0.3s;
            -o-transition-duration: 0.3s;
            transition-duration: 0.3s;
        }
        #historySlideout_inner {
            position: fixed;
            top: 100px;
            left: -130px;
            -webkit-transition-duration: 0.3s;
            -moz-transition-duration: 0.3s;
            -o-transition-duration: 0.3s;
            transition-duration: 0.3s;
        }
        #historySlideout:hover {
            left: 130px;
        }
        #historySlideout:hover #historySlideout_inner {
            left: 0px;
        }

    </style>
@stack('styles')
</head>
<body>
@include('includes.header')
</br>
<div class="container">
    @if(Session::has('numbers'))
    <div id="historySlideout">
        <a href="" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-repeat"></span></a>
        <div id="historySlideout_inner" class="col-md-1">
        @foreach(Session::get('numbers') as $number)
                <a href="{{ route('objects.getShow', ['inputnumber' => $number ]) }}" class="href">{{ $number }}</a>
                <br>
        @endforeach
        </div>
    </div>
    @endif
    @yield('content')
</div>

<script src="{{ URL::to('js/jquery-3.2.1.min.js') }}"></script>
<script src="{{ URL::to('js/bootstrap.min.js') }}"></script>
<script src="{{ URL::to('js/jquery-ui.min.js') }}"></script>
<script src="{{ URL::to('js/jquery.validate.min.js') }}"></script>
<script src="{{ URL::to('js/my.js') }}"></script>
@stack('scripts')
</body>
</html>