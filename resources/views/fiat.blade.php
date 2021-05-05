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
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header border-0">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#fiatCreateModal">
                                Добавить вложение
                            </button>
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
                                                <td>{{ $fiat->created_at }}</td>
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

    <div class="modal fade" id="fiatCreateModal" tabindex="-1" aria-labelledby="exampleModalLabel2" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('fiat.create') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Добавить вложение</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Размер, $</label>
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

    @if(session()->pull('fiat.create.error'))
        <script>
            $(document).ready(function() {
                let fiatCreateModal = new bootstrap.Modal(document.getElementById('fiatCreateModal'))
                fiatCreateModal.show()
            });
        </script>
    @endif

@endsection
