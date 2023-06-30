@extends('layouts.master')

@section('content')
    @include('pages.overtime-report.partials.modal')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Laporan Job Order</h3>
                    {{-- <p class="text-subtitle text-muted">For user to check they list</p> --}}
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            {{-- <li class="breadcrumb-item"><a href="#">Pengaturan</a></li> --}}
                            <li class="breadcrumb-item active" aria-current="page">Job Order</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <section class="section">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="basicInput">Pilih Tanggal</label>
                                <input type="text" class="form-control" id="date_filter" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="project">Pilih Proyek</label>
                                <select name="project" id="project" class="form-control select2" style="width: 100%;">
                                    <option value="" selected>Semua</option>
                                    <option value="1">Kapal A</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="status">Pilih Status</label>
                                <select name="status" id="status" class="form-control select2" style="width: 100%;">
                                    <option value="" selected>Semua</option>
                                    <option value="repair">Perbaikan</option>
                                    <option value="finish">Selesai</option>
                                    <option value="unfinished">Belum Selesai</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4" style="align-self: center">
                            <button type="button" onclick="onFilter()" class="btn btn-sm btn-success">Kirim</button>
                            @can('ekspor laporan job order')
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
                                <th>Nama Proyek</th>
                                <th>Kategori</th>
                                <th>Pekerjaan</th>
                                <th>Catatan Pekerjaan</th>
                                <th>Tingkat Kesusahan</th>
                                <th>Waktu Mulai</th>
                                <th>Waktu Selesai</th>
                                <th>Lama Pengerjaan</th>
                                <th>Keterangan</th>
                                {{-- <th width="10%"></th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($jobOrders as $jobOrder)
                                <tr>
                                    <td>
                                        {{ $jobOrder->project_name }}
                                    </td>
                                    <td>
                                        {{ $jobOrder->job_order_category_name }}
                                    </td>
                                    <td>
                                        {{ $jobOrder->job_name }}
                                    </td>
                                    <td>
                                        {{ $jobOrder->job_note }}
                                    </td>
                                    <td>
                                        {{ $jobOrder->level }}
                                    </td>
                                    <td>
                                        {{ $jobOrder->date_time_start }}
                                    </td>
                                    <td>
                                        {{ $jobOrder->date_time_end }}
                                    </td>
                                    <td>
                                        {{ $jobOrder->duration }}
                                    </td>
                                    <td>
                                        {{ $jobOrder->note }}
                                    </td>
                                    {{-- <td>
                                        @can('detail laporan job order')
                                            <a href="javascript:void(0)" onclick="onEdit({{ $jobOrder->id }})"
                                                class="btn btn-sm btn-info">Detail
                                            </a>
                                        @endcan
                                    </td> --}}
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
            jobOrders: [],
        };

        let state = {
            ...initialState
        };

        $(document).ready(function() {
            $('.dataTable').DataTable();

            state.jobOrders = {!! json_encode($jobOrders) !!};
            setupSelect();
            setupDateFilter();
            send();
        });

        function onFilter() {
            console.info("filter data");
        }

        function onExport() {
            console.info("filter data");
        }

        function onCreate() {
            clearForm();
            $("#titleForm").html("Tambah Fitur");
            onModalAction("detailModal", "show");
        }

        function onEdit(data) {
            clearForm();

            $("#id").val(data.id);
            $("#name").val(data.name);
            $("#description").val(data.description);

            $("#titleForm").html("Ubah Fitur");
            onModalAction("detailModal", "show");
        }

        function onDelete(data) {
            Swal.fire({
                title: 'Perhatian!!!',
                html: `Anda yakin ingin hapus data fitur <h2><b> ${data.name} </b> ?</h2>`,
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

        function setupSelect() {
            $(".select2").select2();
        }

        function clearForm() {
            $("#id").val("");
            $("#name").val("");
            $("#description").val("");
        }
    </script>
@endsection
