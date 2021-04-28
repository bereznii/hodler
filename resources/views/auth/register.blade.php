<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <title>Логин</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
        <link rel="icon" href="{{ asset('img/logo.png') }}" sizes="32x32" type="image.png">
        <meta name="theme-color" content="#7952b3">
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

        <link href="{{ asset('css/custom/register.css') }}" rel="stylesheet">
    </head>
    <body class="text-center">
        <main class="form-signin">
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <img class="mb-4" src="{{ asset('img/logo.png') }}" alt="" width="80" height="75">
                <h1 class="h3 mb-3 fw-normal">Зарегистрируйтесь</h1>

                <div class="form-floating">
                    <input type="email" class="form-control" id="floatingInput" name="email">
                    <label for="floatingInput">Почта</label>
                </div>
                <div class="form-floating">
                    <input type="text" class="form-control" id="floatingName" name="name">
                    <label for="floatingName">Имя</label>
                </div>
                <div class="form-floating">
                    <input type="password" class="form-control" id="floatingPassword" name="password">
                    <label for="floatingPassword">Пароль</label>
                </div>
                <div class="form-floating">
                    <input type="password" class="form-control" id="floatingPasswordCopy" name="password_confirmation" required autocomplete="new-password">
                    <label for="floatingPasswordCopy">Подтвердите пароль</label>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <button class="w-100 btn btn-lg btn-primary" type="submit">Создать аккаунт</button>

                <a class="btn btn-link" href="{{ route('login') }}">
                    Войти
                </a>
            </form>
        </main>
    </body>
</html>
