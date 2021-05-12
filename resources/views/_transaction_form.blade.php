@extends('layouts.adminlte')

@section('content')

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Транзакция по {{ $asset->currency->name }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Домой</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('advanced') }}">Расширенный дашборд</a></li>
                        <li class="breadcrumb-item active">Транзакция по {{ $asset->currency->name }}</li>
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
                            <form method="post" action="{{ route('transaction.create', ['id' => $asset->id]) }}">
                                @csrf
                                <div class="col-sm-4">
                                    <div class="mb-3">
                                        <label for="exampleFormControlInput1" class="form-label">Действие</label>
                                        <select class="form-control" name="result" aria-label="Action">
                                            <option value="buy" {{ old('result') === 'buy' ? 'selected' : '' }}>Докупить</option>
                                            <option value="sell" {{ old('result') === 'sell' ? 'selected' : '' }}>Продать</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="mb-3">
                                        <label for="exampleFormControlInput1" class="form-label">Количество монет</label>
                                        <input type="text" class="form-control" name="quantity" placeholder="0.0000000" value="{{ old('quantity') }}">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="mb-3">
                                        <label for="exampleFormControlInput1" class="form-label">Цена за монету, $</label>
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
