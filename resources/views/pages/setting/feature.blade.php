@extends('layouts.master')

@section('content')
@include('pages.setting.partials.feature-modal')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Fitur</h3>
                {{-- <p class="text-subtitle text-muted">For user to check they list</p> --}}
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        {{-- <li class="breadcrumb-item"><a href="#">Pengaturan</a></li> --}}
                        <li class="breadcrumb-item active" aria-current="page">Fitur</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-header">
                Data Fitur
                <button onclick="onCreate()" class="btn btn-sm btn-success shadow-sm float-end" id="addData"
                data-toggle="modal">
                <i class="fas fa-plus text-white-50"></i> Tambah Fitur
            </button>
        </div>
        <div class="card-body">
            {{-- <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        {!! $html->table(['class' => 'table table-striped table-bordered']) !!}
                    </div>
                </div>
            </div> --}}
            <table class="table table-striped dataTable" id="table1">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama</th>
                        <th>Deskripsi</th>
                        <th width="20%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($features as $feature)
                    <tr>
                        <td>
                            {{ $loop->iteration }}
                        </td>
                        <td>
                            {{ $feature->name }}
                        </td>
                        <td>
                            {{ $feature->description }}
                        </td>
                        <td class="flex flex-row justify-content-around">
                            @can('detail fitur')
                            <a href="{{ route('setting.permission.index', ['featureId' => $feature->id]) }}"
                                class="btn btn-sm btn-primary">Detail
                            </a>
                            @endcan
                            @can('ubah fitur')
                            @if ($feature->created_by == auth()->user()->id)
                            <a href="javascript:void(0)" onclick="onEdit({{ $feature }})" class="btn btn-sm btn-info">Ubah
                            </a>
                            @endif
                            @endcan
                            @can('hapus fitur')
                            @if ($feature->created_by == auth()->user()->id)
                            <a href="javascript:void(0)" onclick="onDelete({{ $feature }})" class="btn btn-sm btn-danger">Hapus
                            </a>
                            @endif
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

@section('script')
{{-- <script src="assets/static/js/components/dark.js"></script>
<script src="assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js"></script>

<!-- Need: Apexcharts -->
<script src="assets/extensions/apexcharts/apexcharts.min.js"></script>
<script src="assets/static/js/pages/dashboard.js"></script> --}}
{{-- {!! $html->scripts() !!} --}}
<script>
    $(document).ready(function() {
        $('.dataTable').DataTable();

        send();
    });

    function onCreate() {
        clearForm();
        $("#titleForm").html("Tambah Fitur");
        onModalAction("formModal", "show");
    }

    function onEdit(data) {
        clearForm();

        $("#id").val(data.id);
        $("#name").val(data.name);
        $("#description").val(data.description);

        $("#titleForm").html("Ubah Fitur");
        onModalAction("formModal", "show");
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

    function clearForm() {
        $("#id").val("");
        $("#name").val("");
        $("#description").val("");
    }
</script>
@endsection
