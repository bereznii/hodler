<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Hodler - Crypto Analytics Service</title>

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

        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
        <!-- Font Awesome Icons -->
        <link rel="stylesheet" href="{{ asset('css/adminlte/fontawesome-free/css/all.min.css') }}">
        <!-- IonIcons -->
        <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">

        <link href="{{ asset('css/custom/index.css') }}" rel="stylesheet">

        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    </head>

    <body class="hold-transition sidebar-mini">
    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>
            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="btn btn-secondary" href="{{ route('logout') }}"
                       onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="{{ route('home') }}" class="brand-link">
                <img src="{{ asset('img/logo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle bg-white elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">Hodler alpha</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="info">
                        <a class="d-block" href="{{ route('profile') }}">{{ Auth::user()->email }}</a>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <li class="nav-header">Дашборды</li>
                        <li class="nav-item">
                            <a href="{{ route('home') }}" class="nav-link {{ Route::currentRouteName() === 'home' ? 'active' : '' }}">
                                <i class="nav-icon fas fa-th-large"></i>
                                <p>
                                    Основной
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('advanced') }}" class="nav-link {{ Route::currentRouteName() === 'advanced' ? 'active' : '' }}">
                                <i class="nav-icon fas fa-th"></i>
                                <p>
                                    Расширенный
                                </p>
                            </a>
                        </li>
                        <li class="nav-header">Настройки</li>
                        <li class="nav-item">
                            <a href="{{ route('fiat') }}" class="nav-link {{ Route::currentRouteName() === 'fiat' ? 'active' : '' }}">
                                <i class="nav-icon fas fa-money-bill-wave"></i>
                                <p>
                                    Фиатные вложения
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('profile') }}" class="nav-link {{ Route::currentRouteName() === 'profile' ? 'active' : '' }}">
                                <i class="nav-icon fas fa-user-alt"></i>
                                <p>
                                    Профиль
                                </p>
                            </a>
                        </li>
    {{--                    <li class="nav-item">--}}
    {{--                        <a href="{{ route('home') }}" class="nav-link {{ Route::currentRouteName() === 'home' ? 'active' : '' }}">--}}
    {{--                            <i class="nav-icon fas fa-th"></i>--}}
    {{--                            <p>--}}
    {{--                                Расширенный--}}
    {{--                                <span class="right badge badge-danger">New</span>--}}
    {{--                            </p>--}}
    {{--                        </a>--}}
    {{--                    </li>--}}
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">

            @if(session()->exists('notification'))
                <div class="toast" role="alert" data-autohide="true" data-delay="3000" style="position: absolute; top: 1%; right: 40%; left: 45%; z-index: 10000">
                    <div class="toast-header">
                        <img src="{{ asset('img/logo.png') }}" width="20" class="rounded mr-2" alt="...">
                        <strong class="mr-auto">Уведомление</strong>
                        <small>Только что</small>
                        <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="toast-body">
                        {{ session()->pull('notification') }}
                    </div>
                </div>
                <script>
                    $(document).ready(function(){
                        $('.toast').toast('show');
                    });
                </script>
            @endif

            @yield('content')

        </div>
        <!-- /.content-wrapper -->

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->

        <!-- Main Footer -->
        <footer class="main-footer">
            <strong>Copyright &copy; {{ date('Y') }} <a href="https://adminlte.io">AdminLTE.io</a>.</strong>
            All rights reserved.
            <div class="float-right d-none d-sm-inline-block">
                <b>Version</b> 0.2
            </div>
        </footer>
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/js/adminlte.min.js"></script>
    <script src="{{ asset('js/custom/index.js') }}"></script>

    </body>
</html>
