<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'laravel') }}</title>

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet">

    </head>
    <body>
        <div id="app">
            <nav class="navbar navbar-inverse navbar-static-top">
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
                            {{ config('app.name', 'laravel') }}
                        </a>

                    </div>

                    <div class="collapse navbar-collapse" id="app-navbar-collapse">
                        <!-- Left Side Of Navbar -->
                        <ul class="nav navbar-nav">
                            &nbsp;
                            @if (!Auth::guest())
                           
                            <li><a href="{{ url('/inventario') }}">Inventario</a></li>                  


                            <li><a href="{{url('/compromiso')}}">Compromisos</a></li>

                            <li><a href="{{ url('/persona/c') }}">Clientes</a></li>

                             @if(Auth::user()->rol != 'vendedor')
                            <li><a href="{{ url('/persona/u') }}">Usuarios</a></li>
                            @endif
                            <li><a href="{{url('/cierre')}}">Cierre de caja</a></li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    Otros <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{url('/novedad')}}">
                                            Registrar novedad
                                        </a>    
                                        <a href="{{url('/sede')}}">
                                            Sedes
                                        </a>  
                                        <a href="{{ url('/proveedor') }}">Proveedores</a>
                                        @if(Auth::user()->rol == 'administrador')
                                        
                                         <a href="{{ url('/custodia') }}">Custodia</a>
                                        @endif
                                        <a href="{{url('/compromiso/pendientes')}}">Compromisos pendientes</a>
                                        <a href="{{url('/salida')}}">Salidas de caja</a>
                                        <a href="{{url('/venta')}}">Vender producto</a>
                                        <a href="{{url('/prestamo')}}">Prestar producto </a>
                                        @if(Auth::user()->rol != 'vendedor')
                                        <a href="{{url('/base')}}">Base</a>
                                        @endif
                                        
                                          <a href="{{url('/abonocaja')}}">Abonos a caja</a>

                                    </li>
                                </ul>
                            </li>


                            @endif
                        </ul>

                        <!-- Right Side Of Navbar -->
                        <ul class="nav navbar-nav navbar-right">
                            <!-- Authentication Links -->
                            @if (Auth::guest())
                            <li><a href="{{ route('login') }}">Iniciar sesión</a></li>
                            <!--   <li><a href="{{ route('register') }}">Register</a></li> -->
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
                                            Salir
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </nav>

            @yield('content')
        </div>

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}"></script>
        <script src="{{ asset('js/preloader/jquery.preloaders.js') }}"></script>
        <script>

                                               $(document).ready(function () {

                                                   $(document).ajaxStart(function () {
                                                       $.preloader.start({

                                                           modal: true

                                                       });
                                                   });
                                                   $(document).ajaxComplete(function () {
                                                       $.preloader.stop();

                                                   });




                                                   $('.datatable').DataTable({
                                                       "language": {
                                                           "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json"
                                                       }

                                                   });
                                               })
        </script>
        <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
        <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
        @yield('scripts');
    </body>
</html>
