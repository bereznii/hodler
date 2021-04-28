@extends('layouts.layout')

@section('content')
    <div class="container">
        <div class="row">
            <form method="post" action="{{ route('profile.update') }}">
                @csrf
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="col-sm-4">
                    <div class="mb-3">
                        <label class="form-label">Имя</label>
                        <input type="text" class="form-control" placeholder="Имя" value="{{ $user->name }}" name="name" required>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="mb-3">
                        <label class="form-label">Почта</label>
                        <input type="email" class="form-control" placeholder="example@gmail.com" value="{{ $user->email }}" name="email" disabled>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="mb-3">
                        <label class="form-label">Пароль</label>
                        <input type="password" class="form-control" placeholder="***********" name="password">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <button class="btn btn-success" type="submit">Сохранить</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if(session()->exists('notification'))
        <!-- Toast -->
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 5">
            <div id="liveToast" data-autohide="true" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <img src="{{ asset('img/logo.png') }}" width="20" class="rounded me-2" alt="">
                    <strong class="me-auto">Новое уведомление</strong>
                    <small>Только что</small>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    {{ session()->pull('notification') }}
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function() {
                $(".toast").toast('show');
            });
        </script>
    @endif
@endsection
