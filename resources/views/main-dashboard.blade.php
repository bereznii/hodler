@extends('layouts.adminlte')

@section('content')

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Основной дашборд</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Домой</a></li>
                        <li class="breadcrumb-item active">Основной дашборд</li>
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
                <div class="col-md-6 col-xl-3 col-12">
                    <div class="info-box shadow-none">
                        <span class="info-box-icon {{ $fiatInvested < $overallPrice ? 'bg-success' : 'bg-danger' }}">
                            <i class="fas fa-wallet"></i>
                        </span>

                        <div class="info-box-content">
                            <span class="info-box-text">Стоимость портфеля</span>
                            <span class="info-box-number {{ $isPositive ? 'text-success' : 'text-danger' }}">
                                {{ $overallPrice }}$
                            </span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                </div>
                <div class="col-md-6 col-xl-3 col-12">
                    <div class="info-box shadow-none">
                        <span class="info-box-icon {{ $isPositive ? 'bg-success' : 'bg-danger' }}">
                            <i class="fas fa-exchange-alt"></i>
                        </span>

                        <div class="info-box-content">
                            <span class="info-box-text">
                                Совокупный PNL
                                <i class="far fa-question-circle" title="Profit and Loss"></i>
                            </span>
                            <span class="info-box-number {{ $isPositive ? 'text-success' : 'text-danger' }}">
                                {!! $isPositive ? '<i class="far fa-arrow-alt-circle-up"></i>' : '<i class="far fa-arrow-alt-circle-down"></i>' !!}
                                {{ $totalPnl['moneyDifference'] }}$ | {{ $totalPnl['percentDifference'] }}%
                            </span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Актив</th>
                                            <th>Изменение</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($assets as $value)
                                        <tr>
                                            <td>
                                                <div class="d-flex flex-row justify-content-start">
                                                    <img class="coin-logo mr-1" src="https://s2.coinmarketcap.com/static/img/coins/64x64/{{ $value->currency_id }}.png" width="22" height="22">
                                                    {{ $value->currency->name }}
                                                </div>
                                            </td>
                                            <td class="{{ $value->getPriceDifference('money') >= 0 ? 'text-success' : 'text-danger' }}">
                                                <b>
                                                    {{ $value->getPriceDifference('percent') }}%
                                                    <br>
                                                    {{ $value->getPriceDifference('money') }}$
                                                </b>
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
