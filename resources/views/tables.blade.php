@extends('layouts.layout')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-4 mb-3 border-end">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#assetCreateModal">
                    Добавить актив
                </button>
            </div>
            <div class="col-sm-4 mb-3 d-flex justify-content-center border-end">
                <p>Цены актуальны на: {{ $currencyUpdate }}</p>
            </div>
            <div class="col-sm-4 mb-3 d-flex justify-content-end">
                <p>Стоимость портфеля: {{ $overallPrice }}$</p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Актив</th>
                            <th>Цена покупки</th>
                            <th>Текущая цена</th>
                            <th>Процент изменения</th>
                            <th>Количество</th>
                            <th>Стоимость актива</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($assets as $value)
                            <tr>
                                <td>{{ $value->currency->cmc_rank }}</td>
                                <td>
                                    <div class="d-flex flex-row justify-content-start">
                                        <img class="coin-logo me-1" src="https://s2.coinmarketcap.com/static/img/coins/64x64/{{ $value->currency_id }}.png" width="22" height="22">
                                        {{ $value->currency->name }}
                                    </div>
                                </td>
                                <td>{{ $value->getAveragePrice() }}$</td>
                                <td>{{ $value->currency->getCurrentPrice() }}$</td>
                                <td class="{{ $value->getPriceDifference() >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ $value->getPriceDifference() }}%
                                </td>
                                <td>{{ $value->getAssetQuantity() }}</td>
                                <td>{{ $value->getAssetPrice() }}$</td>
                                <td>
                                    <div class="input-group">
                                        <button class="btn btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#exampleModal{{ $value->id }}">
                                            Действие
                                        </button>
                                        <button class="btn btn-outline-success" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
                                            История
                                        </button>
                                        <a class="btn btn-outline-danger" type="button" onclick="return confirm('Удалить актив?');">
                                            Удалить
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @foreach($assets as $value)
        <div class="modal fade" id="exampleModal{{ $value->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" action="{{ route('transaction.create') }}">
                        @csrf
                        <input type="hidden" name="asset_id" value="{{ $value->id }}">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Транзакция по {{ $value->currency->name }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Действие</label>
                                <select class="form-select" name="result" aria-label="Action">
                                    <option value="buy">Докупить</option>
                                    <option value="sell">Продать</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Количество</label>
                                <input type="text" class="form-control" name="quantity" placeholder="0.0000000">
                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Цена, $</label>
                                <input type="text" class="form-control" name="price" placeholder="0.0000000">
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
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" id="liveToastBtn">Сохранить</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    <div class="modal fade" id="assetCreateModal" tabindex="-1" aria-labelledby="exampleModalLabel2" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('asset.create') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Добавить актив</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Актив</label>
                            <select class="form-select" name="currency" aria-label="Currrency select">
                                @foreach($currencies as $key => $currency)
                                    <option value="{{ $key }}">{{ $currency }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Количество</label>
                            <input type="text" class="form-control" name="quantity" placeholder="0.0000000">
                        </div>
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Цена, $</label>
                            <input type="text" class="form-control" name="price" placeholder="0.0000000">
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
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="liveToastBtn">Сохранить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasExampleLabel">История транзакций</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div class="list-group">
                <a href="#" class="list-group-item" aria-current="true">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1">Продажа LTC</h5>
                        <small>3 days ago</small>
                    </div>
                    <p class="mb-1">Количество 0.87 по стоимости 270.6$</p>
                    <small>Зафиксирован убыток в размере 130$</small>
                </a>
            </div>
        </div>
    </div>


    @if(session()->pull('asset.create.error'))
        <script>
            $(document).ready(function() {
                let assetCreateModal = new bootstrap.Modal(document.getElementById('assetCreateModal'))
                assetCreateModal.show()
            });
        </script>
    @endif

    @if(session()->pull('transaction.create.error'))
        <script>
            $(document).ready(function() {
                let assetCreateModal = new bootstrap.Modal(document.getElementById('exampleModal'))
                assetCreateModal.show()
            });
        </script>
    @endif

    @if(session()->exists('notification'))
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
