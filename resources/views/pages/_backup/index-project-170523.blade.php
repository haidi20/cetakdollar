@extends('layouts.master')

@section('content')
    @include('pages.project.partials.modal')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Proyek</h3>
                    {{-- <p class="text-subtitle text-muted">For user to check they list</p> --}}
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            {{-- <li class="breadcrumb-item"><a href="#">Pengaturan</a></li> --}}
                            <li class="breadcrumb-item active" aria-current="page">Proyek</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <section class="section">
            <div class="card">
                <div class="card-header">
                    Data
                    <button onclick="onCreate()" class="btn btn-sm btn-success shadow-sm float-end" id="addData"
                        data-toggle="modal">
                        <i class="fas fa-plus text-white-50"></i> Tambah Proyek
                    </button>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="basicInput">Pilih Tanggal</label>
                                <input type="text" class="form-control" id="date_filter" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-2" style="align-self: center">
                            <button type="button" onclick="onFilter()" class="btn btn-sm btn-success">Kirim</button>
                            @can('ekspor project')
                                <button type="button" id="btn_export" onclick="onExport()"
                                    class="btn btn-sm btn-success mt-2 ml-4 mt-md-0">
                                    <i class="fas fa-file-excel"></i> Export
                                </button>
                            @endcan
                        </div>
                    </div>
                    <br>
                    <table class="table table-striped dataTable" id="table1">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Perusahaan</th>
                                <th>Total Job Order</th>
                                <th width="25%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($projects as $project)
                                <tr>
                                    <td>
                                        {{ $project->name }}
                                    </td>
                                    <td>
                                        {{ $project->company_name }}
                                    </td>
                                    <td>
                                        <span data-bs-toggle="tooltip" data-bs-placement="top" title="belum selesai"
                                            class="cursor-pointer">
                                            {{ $project->total_job_order }}
                                        </span>
                                        /
                                        <span data-bs-toggle="tooltip" data-bs-placement="top" title="selesai"
                                            class="cursor-pointer">
                                            {{ $project->total_job_order_finish }}
                                        </span>
                                    </td>
                                    <td>
                                        {{-- <div class="btn-group dropdown me-1 mb-1">
                                            <button type="button"
                                                class="btn btn-primary dropdown-toggle dropdown-toggle-split"
                                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                                data-reference="parent">
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="#">Option 1</a>
                                                <a class="dropdown-item active" href="#">Option 2</a>
                                                <a class="dropdown-item" href="#">Option 3</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item" href="#">Separated link</a>
                                            </div>
                                        </div> --}}
                                        @can('detail proyek')
                                            <a href="javascript:void(0)" onclick="onDetail({{ $project->id }})"
                                                class="btn btn-sm btn-primary">
                                                Detail
                                            </a>
                                        @endcan
                                        @can('proyek job order')
                                            <a href="javascript:void(0)" class="btn btn-sm btn-warning">
                                                Job Order
                                            </a>
                                        @endcan
                                        @can('ubah proyek')
                                            <a href="javascript:void(0)" onclick="onEdit({{ $project->id }})"
                                                class="btn btn-sm btn-info">Ubah
                                            </a>
                                        @endcan
                                        @can('hapus proyek')
                                            <a href="javascript:void(0)" onclick="onDelete({{ $project->id }})"
                                                class="btn btn-sm btn-danger">Hapus
                                            </a>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </section>
    </div>
@endsection

@section('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/litepicker/dist/css/litepicker.css" />
@endsection
@section('script')
    <script src="https://cdn.jsdelivr.net/npm/litepicker/dist/nocss/litepicker.js"></script>

    <script>
        const initialState = {
            projects: [],
        };

        let state = {
            ...initialState
        };

        $(document).ready(function() {
            $('.dataTable').DataTable();

            state.projects = {!! json_encode($projects) !!};
            setupSelect();
            setupDateFilter();
            send();
        });

        function onCreate() {
            clearForm();
            $("#titleForm").html("Tambah Proyek");
            onModalAction("formModal", "show");
            // $("#formModal").modal("show");
        }

        function onDetail(id) {
            onModalAction("formModal", "show");
        }

        function onEdit(data) {
            clearForm();

            $("#id").val(data.id);
            $("#name").val(data.name);
            $("#description").val(data.description);

            $("#titleForm").html("Ubah Proyek");
            onModalAction("formModal", "show");
        }

        function onDelete(data) {
            Swal.fire({
                title: 'Perhatian!!!',
                html: `Anda yakin ingin hapus data Proyek <h2><b> ${data.name} </b> ?</h2>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                onfirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Tidak'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('setting.feature.delete') }}",
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

        function send() {
            $("#form").submit(function(e) {
                e.preventDefault();
                let fd = new FormData(this);

                $.ajax({
                    url: "{{ route('setting.feature.store') }}",
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
                            Toast.fire({
                                icon: 'success',
                                title: responses.message
                            });

                            window.location.reload();
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

        function setupSelect() {
            $(".select2").select2();
        }

        function setupDateFilter() {
            new Litepicker({
                element: document.getElementById('date_filter'),
                singleMode: false,
                tooltipText: {
                    one: 'night',
                    other: 'nights'
                },
                tooltipNumber: (totalDays) => {
                    return totalDays - 1;
                },
                setup: (picker) => {
                    picker.on('selected', (startDate, endDate) => {
                        startDate = moment(startDate.dateInstance).format("yyyy-MM-DD");
                        endDate = moment(endDate.dateInstance).format("yyyy-MM-DD");

                        state.date_start_filter = startDate;
                        state.date_end_filter = endDate;
                    });
                },
            });
        }

        function clearForm() {
            $("#id").val("");
            $("#name").val("");
            $("#description").val("");
        }
    </script>
@endsection
