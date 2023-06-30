@extends('layouts.master')

@section('content')
    @include('pages.overtime-report.partials.modal')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Laporan Surat Perintah Lembur</h3>
                    {{-- <p class="text-subtitle text-muted">For user to check they list</p> --}}
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            {{-- <li class="breadcrumb-item"><a href="#">Pengaturan</a></li> --}}
                            <li class="breadcrumb-item active" aria-current="page">SPL</li>
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
                        <div class="col-md-4" style="align-self: center">
                            <button type="button" onclick="onFilter()" class="btn btn-sm btn-success">Kirim</button>
                            @can('ekspor laporan surat perintah lembur')
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
                                <th>Departemen</th>
                                <th>Job Order</th>
                                <th>Waktu Mulai</th>
                                <th>Waktu Selesai</th>
                                <th>Durasi</th>
                                <th>Keterangan</th>
                                {{-- <th width="10%"></th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($overtimes as $overtime)
                                <tr>
                                    <td>
                                        {{ $overtime->employee_name }}
                                    </td>
                                    <td>
                                        {{ $overtime->position_name }}
                                    </td>
                                    <td>
                                        {{ $overtime->job_order_code }} | {{ $overtime->job_order_name }}
                                    </td>
                                    <td>
                                        {{ $overtime->date_time_start }}
                                    </td>
                                    <td>
                                        {{ $overtime->date_time_end }}
                                    </td>
                                    <td>
                                        {{ $overtime->duration }}
                                    </td>
                                    <td>
                                        {{ $overtime->note }}
                                    </td>
                                    {{-- <td>
                                        @can('detail laporan surat perintah lembur')
                                            <a href="javascript:void(0)" onclick="onEdit({{ $overtime->id }})"
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
            overtimes: [],
        };

        let state = {
            ...initialState
        };

        $(document).ready(function() {
            $('.dataTable').DataTable();

            state.overtimes = {!! json_encode($overtimes) !!};
            setupDateFilter();
            send();
        });

        function onFilter() {
            console.info("filter data");
        }

        function onExport() {
            console.info("filter data");
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
