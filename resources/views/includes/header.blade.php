<header>
    <nav class="navbar navbar-default">

        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/">TelBase</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li><a href="{{ route('index') }}">Головна</a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Пошук<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ route('objects.list') }}">По списку</a></li>
                            <li><a href="{{ route('objects.searchobject') }}">По об'єкту</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="{{ route('objects.imeiimsi') }}">IMEI-IMSI</a></li>
                        </ul>
                    </li>
                    @if (Auth::check())
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Адміністрування<span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="{{ route('objects.getLog') }}">Лог</a></li>
                                    <li role="separator" class="divider"></li>
                                    <li><a href="">Управление группами</a></li>
                                    <li role="separator" class="divider"></li>
                                    <li><a href="">Регистрация пользователя</a></li>
                                </ul>
                            </li>
                    @endif
                </ul>

                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        <li><a href="{{ route('login') }}">Увійти</a></li>
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <a href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        Вийти
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>
</header>