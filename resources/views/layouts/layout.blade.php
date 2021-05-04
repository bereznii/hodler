<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <title>Hodler - Crypto Analytics Service</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet">

        <link rel="shortcut icon" href="{{ asset('img/apple/favicon.ico') }}" type="image/x-icon" />
        <link rel="apple-touch-icon" href="{{ asset('img/apple/apple-touch-icon.png') }}" />
        <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('img/apple/apple-touch-icon-57x57.png') }}" />
        <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('img/apple/apple-touch-icon-72x72.png') }}" />
        <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('img/apple/apple-touch-icon-76x76.png') }}" />
        <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('img/apple/apple-touch-icon-114x114.png') }}" />
        <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('img/apple/apple-touch-icon-120x120.png') }}" />
        <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('img/apple/apple-touch-icon-144x144.png') }}" />
        <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('img/apple/apple-touch-icon-152x152.png') }}" />
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('img/apple/apple-touch-icon-180x180.png') }}" />

        <style>
            .bd-placeholder-img {
                font-size: 1.125rem;
                text-anchor: middle;
                -webkit-user-select: none;
                -moz-user-select: none;
                user-select: none;
            }

            @media (min-width: 768px) {
                .bd-placeholder-img-lg {
                    font-size: 3.5rem;
                }
            }
        </style>

        <link href="{{ asset('css/custom/index.css') }}" rel="stylesheet">

        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    </head>
    <body>
        <div class="container">
            <header class="d-flex flex-wrap justify-content-center py-3 mb-4 border-bottom">
                <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-dark text-decoration-none">
                    <img class="bi me-2" src="{{ asset('img/logo.png') }}" width="42" height="40" alt="Logo">
                    <span class="fs-4"> Hodler</span>
                </a>
                <ul class="nav nav-pills">
                    <li class="nav-item"><a href="{{ route('home') }}" class="nav-link {{ Route::currentRouteName() === 'home' ? 'text-primary' : 'text-secondary' }}">Криптовалюты</a></li>
                    <li class="nav-item"><a href="{{ route('profile') }}" class="nav-link {{ Route::currentRouteName() === 'profile' ? 'text-primary' : 'text-secondary' }}">Профиль</a></li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('logout') }}"
                           onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </header>
        </div>

        @yield('content')

    </body>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
</html>
