<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Black Sweet') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- CSS de Bootstrap desde CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- CSS compilado desde SCSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- JS de Bootstrap desde CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" defer></script>

    <!-- JS -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Scripts
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])-->
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-dark bg-dark shadow-sm">
            <div class="container">
                <!-- Nombre de la aplicación -->
                <a class="navbar-brand" href="{{ url('/home') }}">
                    {{ config('app.name', 'Black Sweet') }}
                </a>

                <!-- Botón de hamburguesa para pantallas pequeñas -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Contenido del menú colapsable -->
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Enlaces de navegación agrupados en el menú de hamburguesa -->
                    <ul class="navbar-nav me-auto">
                        @can('remate-list')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('remates.index') }}">Remates</a>
                            </li>
                        @endcan
                          @can('remate-list')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('remates.lista_remates') }}">Registros de Remates</a>
                            </li>
                        @endcan
                        @can('cliente-list')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('clientes.index') }}">Clientes</a>
                            </li>
                        @endcan
                        @can('producto-list')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('productos.index') }}">Productos</a>
                            </li>
                        @endcan
                        @can('dolar-list')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('precios.index') }}">Precio del Dólar</a>
                            </li>
                        @endcan
                         @can('total-venta')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('totales.index') }}">Total de Ventas</a>
                            </li>
                        @endcan
                    </ul>

                    <!-- Enlaces de autenticación y opciones del usuario -->
                    <ul class="navbar-nav ms-auto">
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    @can('show-admin')
                                        <a class="dropdown-item" href="{{ route('adminhome') }}">
                                            {{ __('Vista de Administrador') }}
                                        </a>
                                    @endcan
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>
