@extends('layouts.master')

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Jam Kerja</h3>
                    {{-- <p class="text-subtitle text-muted">For user to check they list</p> --}}
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            {{-- <li class="breadcrumb-item"><a href="{{ route('setting.permission.index') }}">Fitur</a></li> --}}
                            <li class="breadcrumb-item active" aria-current="page">Jam Kerja</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <section class="section">
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">

                        </div>

                        <div class="card-body">
                            <form id="form" action="{{ route('working.store') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="hidden" id="id" name="id" class="form-control">
                                        <div class="form-group row">
                                            <label for="name" class="col-sm-4 col-form-label">Jam Mulai Kerja </label>
                                            <div class="col-sm-8">
                                                <input type="time" id="name" name="name" class="form-control"
                                                    placeholder="contoh: 08:00">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="hidden" id="id" name="id" class="form-control">
                                        <div class="form-group row">
                                            <label for="name" class="col-sm-4 col-form-label">Jam Selesai Kerja </label>
                                            <div class="col-sm-8">
                                                <input type="time" id="name" name="name" class="form-control"
                                                    placeholder="contoh: 05:00">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="hidden" id="id" name="id" class="form-control">
                                        <div class="form-group row">
                                            <label for="name" class="col-sm-4 col-form-label">Jam Maksimal Keterlambatan
                                            </label>
                                            <div class="col-sm-8">
                                                <input type="time" id="name" name="name" class="form-control"
                                                    placeholder="contoh: 08:30">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="hidden" id="id" name="id" class="form-control">
                                        <div class="form-group row">
                                            <label for="name" class="col-sm-4 col-form-label">Jam Paling Cepat Pulang
                                            </label>
                                            <div class="col-sm-8">
                                                <input type="time" id="name" name="name" class="form-control"
                                                    placeholder="contoh: 16:30">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-8"></div>
                                    <div class="col-md-4" style="text-align:end; ">
                                        <button type="button" class="btn btn-sm btn-danger"
                                            data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-sm btn-success">Kirim</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </div>
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('assets/vendors/choices.js/choices.min.css') }}" />
@endsection

@section('script')
    <script src="{{ asset('assets/vendors/choices.js/choices.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.dataTable').DataTable();

            setupSelect();
            send();
        });

        function onCreate() {
            clearForm();
            $("#titleForm").html("Tambah Pengguna");
            onModalAction("formModal", "show");
        }

        function onEdit(id) {
            console.info(id);
        }

        function onDelete(id) {
            console.info(id);
        }

        function send() {
            $("#form").submit(function(e) {
                e.preventDefault();
                let fd = new FormData(this);

                console.info(fd);
            });
        }

        function setupSelect() {
            $(".select2").select2();
        }

        function clearForm() {
            //
        }
    </script>
@endsection
