@extends('layouts.layout')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-12 mb-3">
                <button type="button" class="btn btn-primary " data-bs-toggle="modal" data-bs-target="#exampleModal2">
                    Добавить актив
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Актив</th>
                            <th>Цена покупки</th>
                            <th>Текущая цена</th>
                            <th>Разница цен</th>
                            <th>Количество</th>
                            <th>Стоимость актива</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach(range(1,10) as $value)
                            <tr>
                                <td>Bitcoin</td>
                                <td>59,000$</td>
                                <td>58,000$</td>
                                <td>2%</td>
                                <td>19,000,000</td>
                                <td>65,000$</td>
                                <td>
                                    <div class="input-group">
                                        <button class="btn btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                            Действие
                                        </button>
                                        <button disabled class="btn btn-outline-success" type="button" data-bs-target="#offcanvasWithBackdrop" aria-controls="offcanvasWithBackdrop">
                                            История
                                        </button>
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

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <form>
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Транзакция</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Действие</label>
                <select class="form-select" aria-label="Default select example">
                </select>
                </div>
                <div class="mb-3">
                  <label for="exampleFormControlInput1" class="form-label">Количество</label>
                  <input type="number" class="form-control" placeholder="1.000">
                </div>
                <div class="mb-3">
                  <label for="exampleFormControlInput1" class="form-label">Цена, $</label>
                  <input type="input" class="form-control" placeholder="550">
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary" id="liveToastBtn">Сохранить</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Modal V2.0-->
    <div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel2" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <form>
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
                  <input type="input" class="form-control" placeholder="1.000">
                </div>
                <div class="mb-3">
                  <label for="exampleFormControlInput1" class="form-label">Цена, $</label>
                  <input type="input" class="form-control" placeholder="650$">
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary" id="liveToastBtn">Сохранить</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Offcanvas -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasWithBackdrop" aria-labelledby="offcanvasWithBackdropLabel">
      <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasWithBackdropLabel">История транзакций</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body">
        <p><div class="list-group">
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

    <!-- Toast -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 5">
      <div id="liveToast" data-autohide="true" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
          <img src="#" class="rounded me-2" alt="...">
          <strong class="me-auto">Bootstrap</strong>
          <small>11 mins ago</small>
          <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
          Hello, world! This is a toast message.
        </div>
      </div>
    </div>
@endsection
