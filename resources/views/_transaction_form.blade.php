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
                <div class="col-md-4 col-xl-3 col-12">
                    <div class="info-box shadow-none">
                        <span class="info-box-icon bg-warning"><i class="fas fa-money-bill-wave"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Фиатные вложения</span>
                            <span class="info-box-number">{{ $fiatInvested }}$</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                </div>
                <div class="col-md-4 col-xl-3 col-12">
                    <div class="info-box shadow-none">
                        <span class="info-box-icon {{ $fiatInvested < $assetPrice ? 'bg-success' : 'bg-danger' }}">
                            <i class="fas fa-wallet"></i>
                        </span>

                        <div class="info-box-content">
                            <span class="info-box-text">Стоимость актива</span>
                            <span class="info-box-number {{ $fiatInvested < $assetPrice ? 'text-success' : 'text-danger' }}">
                                {{ $assetPrice }}$
                            </span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                </div>
                <div class="col-md-4 col-xl-3 col-12">
                    <div class="info-box shadow-none">
                        <span class="info-box-icon {{ $pnl['percentDifference'] > 0 ? 'bg-success' : 'bg-danger' }}">
                            <i class="fas fa-exchange-alt"></i>
                        </span>

                        <div class="info-box-content">
                            <span class="info-box-text">
                                PNL по активу
                            </span>
                            <span class="info-box-number {{ $pnl['percentDifference'] > 0 ? 'text-success' : 'text-danger' }}">
                                {!! $pnl['percentDifference'] > 0 ? '<i class="far fa-arrow-alt-circle-up"></i>' : '<i class="far fa-arrow-alt-circle-down"></i>' !!}
                                {{ $pnl['moneyDifference'] }}$ | {{ $pnl['percentDifference'] }}%
                            </span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-xl-3 col-12">
                    <div class="info-box shadow-none">
                        <span class="info-box-icon bg-info">
                            <i class="fas fa-dollar-sign"></i>
                        </span>

                        <div class="info-box-content">
                            <span class="info-box-text">Средняя цена покупки</span>
                            <span class="info-box-number">
                                {{ $asset->getAveragePrice() }}$
                            </span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                </div>
                <div class="col-md-4 col-xl-3 col-12">
                    <div class="info-box shadow-none">
                        <span class="info-box-icon {{ $asset->currency->getCurrentPrice() > $asset->getAveragePrice() ? 'bg-success' : 'bg-danger' }}">
                            <i class="fas fa-dollar-sign"></i>
                        </span>

                        <div class="info-box-content">
                            <span class="info-box-text">Текущая цена</span>
                            <span class="info-box-number {{ $asset->currency->getCurrentPrice() > $asset->getAveragePrice() ? 'text-success' : 'text-danger' }}">
                                {{ $asset->currency->getCurrentPrice() }}$
                            </span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                </div>
                <div class="col-md-4 col-xl-3 col-12">
                    <div class="info-box shadow-none">
                        <span class="info-box-icon {{ $pnl['percentDifference'] > 0 ? 'bg-success' : 'bg-danger' }}">
                            <i class="fas fa-exchange-alt"></i>
                        </span>

                        <div class="info-box-content">
                            <span class="info-box-text">
                                PNL по цене монеты
                            </span>
                            <span class="info-box-number {{ $coinPricePnl['percentDifference'] > 0 ? 'text-success' : 'text-danger' }}">
                                {!! $coinPricePnl['percentDifference'] > 0 ? '<i class="far fa-arrow-alt-circle-up"></i>' : '<i class="far fa-arrow-alt-circle-down"></i>' !!}
                                {{ $coinPricePnl['percentDifference'] }}%
                            </span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-4 col-lg-6 col-sm-12 col-12">
                    <div class="card">
                        <div class="card-header">
                            Добавить транзакцию
                        </div>
                        <div class="card-body">
                            <form method="post" action="{{ route('transaction.create', ['id' => $asset->id]) }}">
                                @csrf
                                <div class="col-sm-12">
                                    <div class="mb-3">
                                        <label for="exampleFormControlInput1" class="form-label">Действие</label>
                                        <select class="form-control" name="result" aria-label="Action">
                                            <option value="buy" {{ old('result') === 'buy' ? 'selected' : '' }}>Докупить</option>
                                            <option value="sell" {{ old('result') === 'sell' ? 'selected' : '' }}>Продать</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="mb-3">
                                        <label for="exampleFormControlInput1" class="form-label">Количество монет</label>
                                        <input type="text" class="form-control" name="quantity" placeholder="0.0000000" value="{{ old('quantity') }}">
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="mb-3">
                                        <label for="exampleFormControlInput1" class="form-label">Цена за монету, $</label>
                                        <input type="text" class="form-control" name="price" placeholder="0.0000000" value="{{ old('price') }}">
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <button class="btn btn-success" type="submit">Сохранить</button>
                                </div>
                                <div class="col-sm-12">
                                    @if ($errors->any())
                                        <div class="mt-3">
                                            <div class="alert alert-danger">
                                                <ul>
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6 col-sm-12 col-12">
                    <div class="card">
                        <div class="card-header">
                            Калькулятор
                        </div>
                        <div class="card-body">
                            <form id="buyCalculator">
                                <div class="col-sm-12">
                                    <div class="mb-3">
                                        <label for="buyCalculator_fiat" class="form-label">Фиатные вложения, $</label>
                                        <input type="text" class="form-control" id="buyCalculator_fiat" placeholder="0.0000000">
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="mb-3">
                                        <label for="buyCalculator_price" class="form-label">Цена за монету, $</label>
                                        <input type="text" class="form-control" id="buyCalculator_price" placeholder="0.0000000">
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="mb-3">
                                        <label for="buyCalculator_result" class="form-label">Можно купить монет</label>
                                        <input type="text" class="form-control" id="buyCalculator_result" placeholder="0.0000000">
                                    </div>
                                </div>
                            </form>
                        </div>
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
                                        <th>Количество</th>
                                        <th>Цена за монету</th>
                                        <th>Транзакция</th>
                                        <th>Время</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($transactions as $transaction)
                                        <tr>
                                            <td>
                                                <div class="d-flex flex-row justify-content-start">
                                                    <img class="coin-logo mr-1" src="https://s2.coinmarketcap.com/static/img/coins/64x64/{{ $transaction->asset->currency_id }}.png" width="22" height="22">
                                                    {{ $transaction->asset->currency->name }}
                                                </div>
                                            </td>
                                            <td>
                                                {{ $transaction->quantity }}
                                            </td>
                                            <td>
                                                {{ $transaction->price }}$
                                            </td>
                                            <td>
                                                {{ $transaction->getResultName() }}
                                            </td>
                                            <td>
                                                {{ $transaction->created_at }}
                                            </td>
                                            <td>
                                                <a class="btn btn-outline-danger confirm-delete" href="#" data-confirm="Удалить транзакцию?" data-delete-form="delete-transaction-{{ $transaction->id }}">
                                                    Удалить
                                                </a>
                                                <form id="delete-transaction-{{ $transaction->id }}" action="{{ route('transaction.delete', ['asset_id' => $transaction->asset_id, 'id' => $transaction->id]) }}" method="POST" class="d-none">
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
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            function calculate() {
                let fiat = parseFloat($('#buyCalculator_fiat').val());
                let price = parseFloat($('#buyCalculator_price').val());
                fiat = fiat ? fiat : 1;
                price = price ? price : 1;
                let result = fiat / price;
                $('#buyCalculator_result').val(result);
            }

            $('#buyCalculator_fiat').on('keyup', function () {
                calculate();
            });

            $('#buyCalculator_price').on('keyup', function () {
                calculate();
            });
        });
    </script>
@endsection
