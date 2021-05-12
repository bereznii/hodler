@extends('layouts.adminlte')

@section('content')

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Расширенный дашборд</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Домой</a></li>
                        <li class="breadcrumb-item active">Расширенный дашборд</li>
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
                        <span class="info-box-icon bg-dark"><i class="fas fa-sync-alt"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Цены актуальны на</span>
                            <span class="info-box-number">{{ $currencyUpdate }}</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                </div>
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
                        <span class="info-box-icon bg-info"><i class="fas fa-dollar-sign"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Стоимость покупок</span>
                            <span class="info-box-number">{{ $investedPrice }}$</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                </div>
                <div class="col-md-6 col-xl-3 col-12">
                    <div class="info-box shadow-none">
                        <span class="info-box-icon {{ $isPositive ? 'bg-success' : 'bg-danger' }}"><i class="fas fa-wallet"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Стоимость портфеля</span>
                            <span class="info-box-number {{ $isPositive ? 'text-success' : 'text-danger' }}">{{ $overallPrice }}$</span>
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
                        <div class="card-header border-0">
                            <a href="{{ route('asset.create.form') }}" class="btn btn-primary">
                                Добавить актив
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Актив</th>
                                        <th>Цена покупки</th>
                                        <th>Текущая цена</th>
                                        <th>Количество</th>
                                        <th>Вложения</th>
                                        <th>Стоимость актива</th>
                                        <th>Изменение</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($assets as $value)
                                        <tr>
                                            <td>{{ $value->currency->cmc_rank }}</td>
                                            <td>
                                                <div class="d-flex flex-row justify-content-start">
                                                    <img class="coin-logo mr-1" src="https://s2.coinmarketcap.com/static/img/coins/64x64/{{ $value->currency_id }}.png" width="22" height="22">
                                                    {{ $value->currency->name }}
                                                </div>
                                            </td>
                                            <td>
                                                {{ $value->getAveragePrice() }}$
                                            </td>
                                            <td>
                                                {{ $value->currency->getCurrentPrice() }}$
                                            </td>
                                            <td>{{ $value->getAssetQuantity() }}</td>
                                            <td>{{ $value->getBuyPrice() }}$</td>
                                            <td>{{ $value->getAssetPrice() }}$</td>
                                            <td class="{{ $value->getPriceDifference('money') >= 0 ? 'text-success' : 'text-danger' }}">
                                                <b>
                                                    {{ $value->getPriceDifference('percent') }}%
                                                    <br>
                                                    {{ $value->getPriceDifference('money') }}$
                                                </b>
                                            </td>
                                            <td>
                                                <a class="btn btn-outline-info" target="_blank" href="https://coinmarketcap.com/currencies/{{ $value->currency->slug }}/">
                                                    <img src="{{ asset('img/coinmarketcap-logo.png') }}" alt="CMC" width="20" height="20">
                                                </a>
                                                <a href="{{ route('transaction.create.form', ['id' => $value->id]) }}" class="btn btn-outline-primary">
                                                    Транзакция
                                                </a>
                                                <a class="btn btn-outline-danger confirm-delete" href="#" data-confirm="Удалить актив?" data-delete-form="delete-asset-{{ $value->id }}">
                                                    Удалить
                                                </a>
                                                <form id="delete-asset-{{ $value->id }}" action="{{ route('asset.delete', ['id' => $value->id]) }}" method="POST" class="d-none">
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
