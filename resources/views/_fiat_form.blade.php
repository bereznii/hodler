@extends('layouts.adminlte')

@section('content')

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Добавить вложение</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Домой</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('fiat') }}">Фиатные вложения</a></li>
                        <li class="breadcrumb-item active">Добавить вложение</li>
                    </ol>
                </div>
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <form method="post" action="{{ route('fiat.create') }}">
                                @csrf
                                <div class="col-sm-4">
                                    <div class="mb-3">
                                        <label for="exampleFormControlInput1" class="form-label">Размер, $</label>
                                        <input type="text" class="form-control" name="price" placeholder="0.0000000" value="{{ old('price') }}">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="mb-3">
                                        <button class="btn btn-success" type="submit">Сохранить</button>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
