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
            <a class="navbar-brand" href="{{ url('/home') }}">
                {{-- config('app.name', 'Lucalza') --}}
                <img src="{{ asset('images/logoLucalza.png') }}" alt="" width="175" style="position:relative; top:-70px; left: -200px; ">
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

                    <li class="dropdown">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-cog" aria-hidden="true" style="font-size:32px;"></span> <span class="caret"></span></a>
                      <ul class="dropdown-menu">
                        <li><a href="{{ route('monedas.index') }}">Monedas</a></li>
                        <li><a href="{{ route('empresas.index') }}">Empresas</a></li>                        
                        <li role="separator" class="divider"></li>
                        <li class="dropdown-header">Nav header</li>
                        <li><a href="#">Separated link</a></li>
                        <li><a href="#">One more separated link</a></li>
                      </ul>
                    </li>
                    <li class=""><a href="#"><span class="glyphicon glyphicon-usd" aria-hidden="true" style="font-size:32px;"></span></a></li>
                    <li class=""><a href="#"><span class="glyphicon glyphicon-list-alt" aria-hidden="true" style="font-size:32px;"></span></a></li>
                    <li class=""><a href="#"><span class="glyphicon glyphicon-floppy-saved" aria-hidden="true" style="font-size:32px;"></span></a></li>
                    <li class=""><a href="#"><span class="glyphicon glyphicon-share" aria-hidden="true" style="font-size:32px;"></span></a></li>
                    {{--<li class=""><a href="#"><img src="{{ asset('images/abogado.png') }}" alt=""> Sender</a></li>
                    <li class=""><a href="#"><img src="{{ asset('images/admon.png') }}" alt=""></a></li>--}}
                    <li class="">
                      <a href="{{ route('logout') }}"><span class="glyphicon glyphicon-off" aria-hidden="true" style="font-size:32px;"></span></a>
                    </li>
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
