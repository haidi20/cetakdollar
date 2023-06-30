@extends('layouts.master')

@section('content')
    @include('pages.salary-advance-report.partials.modal')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Kasbon</h3>
                    {{-- <p class="text-subtitle text-muted">For user to check they list</p> --}}
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            {{-- <li class="breadcrumb-item"><a href="#">Pengaturan</a></li> --}}
                            <li class="breadcrumb-item active" aria-current="page">Kasbon</li>
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
                                <label for="basicInput">Pilih Status</label>
                                <select name="status" id="status" class="form-control select2" style="width: 100%;">
                                    <option value="">Semua</option>
                                    <option value="waiting" selected>Menunggu Persetujuan</option>
                                    <option value="settled">Lunas</option>
                                    <option value="unpaid">Belum Lunas</option>
                                    <option value="accept">Diterima
                                    <option value="reject">Ditolak
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2" style="align-self: center">
                            <button type="button" onclick="onFilter()" class="btn btn-sm btn-success">Kirim</button>
                            @can('ekspor laporan kasbon')
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
                                <th>Nama karyawan</th>
                                <th>Jumlah Nominal</th>
                                <th>Potongan Per Bulan</th>
                                <th>Lama Waktu</th>
                                <th>Sisa Gaji</th>
                                <th>Sisa Hutang</th>
                                {{-- <th>Tanggal Disetujui</th> --}}
                                <th>Alasan</th>
                                <th width="15%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($salaryAdvances as $salaryAdvance)
                                <tr>
                                    <td>
                                        {{ $salaryAdvance->employee_name }}
                                    </td>
                                    <td>
                                        Rp. {{ $salaryAdvance->amount }}
                                    </td>
                                    <td>
                                        Rp. {{ $salaryAdvance->monthly_deduction }}
                                    </td>
                                    <td>
                                        {{ $salaryAdvance->duration }}
                                    </td>
                                    <td>
                                        Rp. {{ $salaryAdvance->net_salary }}
                                    </td>
                                    <td>
                                        Rp. {{ $salaryAdvance->remaining_debt }}
                                    </td>
                                    <td>
                                        {{ $salaryAdvance->reason }}
                                    </td>
                                    <td>
                                        @can('persetujuan kasbon')
                                            <a href="javascript:void(0)" onclick="onApprove({{ $salaryAdvance->id }})"
                                                class="btn btn-sm btn-success">Terima
                                            </a>
                                            <a href="javascript:void(0)" onclick="onReject({{ $salaryAdvance->id }})"
                                                class="btn btn-sm btn-danger">Tolak
                                            </a>
                                        @endcan
                                        @can('perwakilan persetujuan kasbon')
                                            @if ($salaryAdvance->status == 'accept')
                                                <a href="javascript:void(0)" onclick="onEdit({{ $salaryAdvance->id }})"
                                                    class="btn btn-sm btn-primary">Terima Perwakilan Direktur
                                                </a>
                                            @endif
                                        @endcan
                                        @can('ubah kasbon')
                                            <a href="javascript:void(0)" onclick="onEdit({{ $salaryAdvance->id }})"
                                                class="btn btn-sm btn-info">Ubah
                                            </a>
                                        @endcan
                                        {{-- @can('hapus kasbon')
                                            <a href="javascript:void(0)" onclick="onDelete({{ $salaryAdvance->id }})"
                                                class="btn btn-sm btn-danger">Hapus
                                            </a>
                                        @endcan --}}
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
            //
        };

        let state = {
            ...initialState
        };

        $(document).ready(function() {
            $('.dataTable').DataTable();

            setupDateFilter();
            send();
        });

        function onCreate() {
            clearForm();
            $("#titleForm").html("Tambah Kasbon");
            onModalAction("formModal", "show");
        }

        function onEdit(data) {
            clearForm();

            $("#id").val(data.id);
            $("#name").val(data.name);

            $("#titleForm").html("Ubah Kasbon");
            onModalAction("formModal", "show");
        }

        function onDelete(data) {
            Swal.fire({
                title: 'Perhatian!!!',
                html: `Anda yakin ingin hapus data kasbon <h2><b> ${data.employee_name} </b> ?</h2>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                onfirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Tidak'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('api.salaryAdvance.delete') }}",
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
                    url: "{{ route('api.salaryAdvance.store') }}",
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

        function clearForm() {
            $("#id").val("");
            $("#name").val("");
            $("#description").val("");
        }
    </script>
@endsection
