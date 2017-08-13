<nav class="navbar navbar-default navbar-static-top" style="background-color: #337ab7" >
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
            <a class="navbar-brand" href="{{ route ('home') }}">
                {{ config('app.name', 'Lucalza') }}
                <img src="{{ asset('images/logoLucalza.PNG') }}" alt="" width="175" style="position:relative; top:-30px;">
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
                    <li><a href="{{ route('login') }}">{{ trans('validation.attributes.login') }}</a></li>
                    {{--<li><a href="{{ route('register') }}">Register</a></li>--}}
                @else

                    @if (auth()->user()->hasRole('superAdmin', 'master', 'administrador'))
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" data-toggle="tooltip" data-placement="top" title="Cátalogos"><span class="glyphicon glyphicon-cog" aria-hidden="true" style="font-size:32px;"></span> <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                          @can('ver monedas')
                            <li><a href="{{ route('monedas.index') }}">Monedas</a></li>
                          @endcan
                          @can('ver empresas')
                            <li><a href="{{ route('empresas.index') }}">Empresas</a></li>
                          @endcan
                          @can('ver usuarios por empresa')
                            <li><a href="{{ route('createUsuarioEmpresa') }}">Usuarios por Empresa</a></li>
                          @endcan
                          @can('ver seguridad')
                          <li role="separator" class="divider"></li>
                          <li class="dropdown-header">Seguridad</li>
                          @can('ver permisos')
                                <li><a href="{{ route('permisos.index') }}">Permisos</a></li>
                          @endcan
                          <li><a href="{{ route('roles.index') }}">Roles</a></li>
                          <li><a href="{{ route('usuarios.index') }}">Usuarios</a></li>
                          <li><a href="{{ route('asignaPermisosRole') }}">Permisos por Rol</a></li>
                          <li><a href="{{ route('asignaRoleUsuario') }}">Roles por Usuario</a></li>
                          @endcan
                        </ul>
                      </li>
                    @endif
                    {{-- @if (auth()->user()->hasRole('superAdmin')) --}}
                        @can('ver presupuestos')
                      <li class=""><a href="{{ route('presupuestos.index') }}" data-toggle="tooltip" data-placement="top" title="Presupuestos"><span class="glyphicon glyphicon-usd" aria-hidden="true" style="font-size:32px;"></span></a></li>
                        @endcan
                        @can('ver liquidaciones')
                      <li class=""><a href="{{ route('liquidaciones.index') }}" data-toggle="tooltip" data-placement="top" title="Liquidaciones"><span class="glyphicon glyphicon-list-alt" aria-hidden="true" style="font-size:32px;"></span></a></li>
                        @endcan
                        @can('ver supervision')
                      <li class=""><a href="{{ route('supervisor') }}" data-toggle="tooltip" data-placement="top" title="Revisión Supervisor"><span class="glyphicon glyphicon-inbox" aria-hidden="true" style="font-size:32px;"></span></a></li>
                        @endcan
                        @can('ver contabilidad')
                      <li class=""><a href="{{ route('contabilidad') }}" data-toggle="tooltip" data-placement="top" title="Revisión Cotabilidad"><span class="glyphicon glyphicon-floppy-saved" aria-hidden="true" style="font-size:32px;"></span></a></li>
                        @endcan
                    {{-- @endif --}}
                    {{--<li class=""><a href="#"><img src="{{ asset('images/abogado.png') }}" alt=""> Sender</a></li>
                    <li class=""><a href="#"><img src="{{ asset('images/admon.png') }}" alt=""></a></li>--}}
                    <li class="">
                      <a href="{{ route('logout') }}" data-toggle="tooltip" data-placement="top" title="Cerrar Sesión"><span class="glyphicon glyphicon-off" aria-hidden="true" style="font-size:32px;"></span></a>
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
