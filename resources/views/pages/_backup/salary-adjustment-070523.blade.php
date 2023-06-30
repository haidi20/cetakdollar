@extends('layouts.master')

@section('content')
    @include('pages.setting.partials.salary-adjustment-modal')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Penyesuaian Gaji</h3>
                    {{-- <p class="text-subtitle text-muted">For user to check they list</p> --}}
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            {{-- <li class="breadcrumb-item"><a href="{{ route('setting.permission.index') }}">Fitur</a></li> --}}
                            <li class="breadcrumb-item active" aria-current="page">Penyesuaian Gaji</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <section class="section">
            <div class="card">
                <div class="card-header">
                    Data
                    @can('tambah penyesuaian gaji')
                        <button onclick="onCreate()" class="btn btn-sm btn-success shadow-sm float-end ml-2" id="addData"
                            data-toggle="modal">
                            <i class="fas fa-plus text-white-50"></i> Tambah Penyesuaian Gaji
                        </button>
                    @endcan
                </div>

                <div class="card-body">
                    <table class="table table-striped dataTable" id="table1">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th width="15%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($salaryAdjustments as $index => $data)
                                <tr>
                                    <td>{{ $data->name }}</td>
                                    <td class="flex flex-row justify-content-around ">
                                        @can('ubah penyesuaian gaji')
                                            <a href="javascript:void(0)" onclick="onEdit({{ $data->id }})"
                                                class="btn btn-sm btn-primary">
                                                Ubah
                                            </a>
                                        @endcan
                                        @can('hapus penyesuaian gaji')
                                            <a href="javascript:void(0)" onclick="onDelete({{ $data->id }})"
                                                class="btn btn-sm btn-danger">
                                                Hapus
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
@endsection

@section('script')
    <script>
        const initialState = {
            salaryAdjustments: [],
        };

        let state = {
            ...initialState
        };

        $(document).ready(function() {
            $('.dataTable').DataTable();

            state.salaryAdjustments = {!! json_encode($salaryAdjustments) !!};
            // state.salaryAdjustments = JSON.parse(salaryAdjustments.replace(/&quot;/g, '"'));

            // console.info(state);

            setupSelect();
            send();
        });

        function onCreate() {
            clearForm();
            $("#titleForm").html("Tambah Penyesuaian Gaji");
            onModalAction("formModal", "show");
        }

        function onEdit(id) {
            // console.info(id);
            const findData = state.salaryAdjustments.find(data => data.id = id);

            console.info(findData);
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
