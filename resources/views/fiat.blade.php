@extends('layouts.adminlte')

@section('content')

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Фиатные вложения</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Домой</a></li>
                        <li class="breadcrumb-item active">Фиатные вложения</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 col-xl-3 col-12">
                    <div class="info-box shadow-none">
                        <span class="info-box-icon bg-warning"><i class="fas fa-money-bill-wave"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Фиатные вложения</span>
                            <span class="info-box-number">{{ $fiatInvested }}$</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header border-0">
                            <a href="{{ route('fiat.create.form') }}" class="btn btn-primary">
                                Добавить вложение
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Размер вложения</th>
                                            <th>Дата</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($fiats as $fiat)
                                            <tr>
                                                <td>{{ $fiat->price }}$</td>
                                                <td style="white-space: nowrap;">{{ $fiat->created_at }}</td>
                                                <td>
                                                    <div class="btn-group">
                                                        <a class="btn btn-outline-danger confirm-delete" href="#" data-confirm="Удалить вложение?" data-delete-form="delete-asset-{{ $fiat->id }}">
                                                            Удалить
                                                        </a>
                                                    </div>
                                                    <form id="delete-asset-{{ $fiat->id }}" action="{{ route('fiat.delete', ['id' => $fiat->id]) }}" method="POST" class="d-none">
                                                        @csrf
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- /.content -->

@endsection
