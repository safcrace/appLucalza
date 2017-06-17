<nav class="navbar navbar-default navbar-static-top">
    <div class="container">
        <div class="navbar-header">

            <!-- Collapsed Hamburger -->
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <!-- Branding Image -->
            <a class="navbar-brand" href="{{ url('/') }}">
                {{-- config('app.name', 'Lucalza') --}}
                <img src="{{ asset('images/Logotipo-Quetzalito-s.png') }}" alt="" width="175" style="position:relative; top:-20px; left: -60px; ">
            </a>
        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Left Side Of Navbar -->
            <ul class="nav navbar-nav">
                &nbsp;
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">
                <!-- Authentication Links -->
                @if (Auth::guest())
                    <li><a href="{{ route('login') }}">Login</a></li>
                    <li><a href="{{ route('register') }}">Register</a></li>
                @else
                    <li class=""><a href="{{ route('home') }}" data-toggle="tooltip" data-placement="top" title="Anterior"><img src="{{ asset('images/anterior.png') }}" alt=""></a></li>
                    <li class=""><a href="{{ route('home') }}" data-toggle="tooltip" data-placement="top" title="Paso 1"><img src="{{ asset('images/ayuda.png') }}" alt=""></a></li>
                    <li class=""><a href="{{ route('generalData', $individual->id) }}" data-toggle="tooltip" data-placement="top" title="Paso 2"><img src="{{ asset('images/ayuda.png') }}" alt=""></a></li>
                    <li class=""><a href="{{ route('work', $individual->id) }}" data-toggle="tooltip" data-placement="top" title="Paso 3"><img src="{{ asset('images/ayuda.png') }}" alt=""></a></li>
                    <li class=""><a href="{{ route('bussiness', $individual->id) }}" data-toggle="tooltip" data-placement="top" title="Paso 4"><img src="{{ asset('images/ayuda.png') }}" alt=""></a></li>
                    <li class=""><a href="{{ route('home') }}" data-toggle="tooltip" data-placement="top" title="Siguiente"><img src="{{ asset('images/siguiente.png') }}" alt=""></a></li>

                    <li class=""><a href="{{ route('home') }}" data-toggle="tooltip" data-placement="top" title="Inicio"><img src="{{ asset('images/cancelar2.png') }}" alt=""></a></li>

                    {{--<li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">
                            <li>
                                <a href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">

                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                    </li>--}}
                @endif
            </ul>
        </div>
    </div>
</nav>
