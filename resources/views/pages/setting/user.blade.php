@extends('layouts.master')

@section('content')
    @include('pages.setting.partials.user-modal')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Pengguna</h3>
                    {{-- <p class="text-subtitle text-muted">For user to check they list</p> --}}
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            {{-- <li class="breadcrumb-item"><a href="{{ route('setting.permission.index') }}">Fitur</a>
                        </li> --}}
                            <li class="breadcrumb-item active" aria-current="page">Pengguna</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <section class="section">
            <div class="card">
                <div class="card-header">
                    {{-- <span class="fs-4 fw-bold">Data Karyawan</span> --}}
                    @can('lihat grup pengguna')
                        <a href="{{ route('setting.role.index') }}" class="btn btn-sm btn-primary shadow-sm float-end ml-2"
                            id="addData" data-toggle="modal">
                            Grup Pengguna
                        </a>
                    @endcan
                    @can('tambah pengguna')
                        <button onclick="onCreate()" class="btn btn-sm btn-success shadow-sm float-end ml-2" id="addData"
                            data-toggle="modal">
                            <i class="fas fa-plus text-white-50"></i> Tambah Pengguna
                        </button>
                    @endcan
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                {!! $html->table(['class' => 'table table-striped table-bordered']) !!}
                            </div>
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
    {!! $html->scripts() !!}
    <script>
        const initialState = {
            users: [],
        };

        let state = {
            ...initialState
        };

        $(document).ready(function() {
            $('.dataTable').DataTable();

            state.users = {!! json_encode($users) !!};
            send();
        });

        function onCreate() {
            clearForm();
            $("#titleForm").html("Tambah Pengguna");
            onModalAction("formModal", "show");
        }

        function onEdit(data) {
            // console.info(data);
            clearForm();
            $("#id").val(data.id);
            $("#name").val(data.name);
            $("#email").val(data.email);
            $("#location_id").val(data.location_id).trigger("change");
            $("#role_id").val(data.role_id).trigger("change");

            $("#titleForm").html("Ubah Pengguna");
            onModalAction("formModal", "show");
        }

        function send() {
            $("#form").submit(function(e) {
                e.preventDefault();
                let fd = new FormData(this);

                $.ajax({
                    url: "{{ route('setting.user.store') }}",
                    method: 'POST',
                    data: fd,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function(responses) {

                        // console.info(responses);

                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 2500,
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.addEventListener('mouseenter', Swal.stopTimer)
                                toast.addEventListener('mouseleave', Swal.resumeTimer)
                            }
                        });
                        if (responses.success == true) {
                            $('#formModal').modal('hide');
                            Toast.fire({
                                icon: 'success',
                                title: responses.message
                            });

                            window.LaravelDataTables["dataTableBuilder"].ajax.reload(
                                function(json) {});
                        }
                    },
                    error: function(err) {
                        console.log(err.responseJSON.message);
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 4000,
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.addEventListener('mouseenter', Swal.stopTimer)
                                toast.addEventListener('mouseleave', Swal.resumeTimer)
                            }
                        });

                        Toast.fire({
                            icon: 'error',
                            title: err.responseJSON.message
                        });
                    }
                });
            });
        }

        function onDelete(data) {
            Swal.fire({
                title: 'Perhatian!!!',
                html: `Anda yakin ingin hapus data pengguna <h2><b> ${data.name} </b> ?</h2>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                onfirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Tidak'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('setting.user.delete') }}",
                        method: 'DELETE',
                        dataType: 'json',
                        data: {
                            id: data.id
                        },
                        success: function(responses) {
                            const Toast = Swal.mixin({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 2500,
                                timerProgressBar: true,
                                didOpen: (toast) => {
                                    toast.addEventListener('mouseenter', Swal.stopTimer)
                                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                                }
                            });
                            $('#formModal').modal('hide');
                            Toast.fire({
                                icon: 'success',
                                title: responses.message
                            });

                            window.LaravelDataTables["dataTableBuilder"].ajax.reload(
                                function(json) {});
                        },
                        error: function(err) {
                            // console.log(err.responseJSON.message);
                            const Toast = Swal.mixin({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 4000,
                                timerProgressBar: true,
                                didOpen: (toast) => {
                                    toast.addEventListener('mouseenter', Swal.stopTimer)
                                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                                }
                            });

                            Toast.fire({
                                icon: 'error',
                                title: err.responseJSON.message
                            });
                        }
                    });
                }
            });
        }

        function setupSelect() {
            $(".select2").select2();
        }

        function clearForm() {

            $("#name").val("");
            $("#email").val("");
            $("#role_id").val("").trigger("change");

        }
    </script>
@endsection
