@extends('layouts.master')

@section('content')
    @include('pages.setting.partials.approval-level-modal')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Tingkat Persetujuan</h3>
                    {{-- <p class="text-subtitle text-muted">For user to check they list</p> --}}
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            {{-- <li class="breadcrumb-item"><a href="#">Pengaturan</a></li> --}}
                            <li class="breadcrumb-item active" aria-current="page">Tingkat Persetujuan</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <button onclick="onCreate()" class="btn btn-sm btn-success shadow-sm float-end" id="addData"
                        data-toggle="modal">
                        <i class="fas fa-plus text-white-50"></i> Tambah
                    </button>
                </div>
                <div class="card-body">
                    <table class="table table-striped dataTable" id="table1">
                        <thead>
                            <tr>
                                <th>Nama </th>
                                <th width="30%">Tanggal Pembuatan</th>
                                <th width="10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($approvalLevels as $approvalLevel)
                                <tr>
                                    <td>{{ $approvalLevel->name }}</td>
                                    <td>{{ $approvalLevel->date_read_able }}</td>
                                    <td>
                                        @can('ubah tingkat persetujuan')
                                            <a href="javascript:void(0)" onclick="onEdit({{ $approvalLevel }})"
                                                class="btn btn-sm btn-info">Ubah
                                            </a>
                                        @endcan
                                        @can('hapus tingkat persetujuan')
                                            <a href="javascript:void(0)" onclick="onDelete({{ $approvalLevel }})"
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

@section('script')
    <script>
        const defaultState = {
            count_detail: 0,
            user_old_id: null,
        }

        const state = {
            ...defaultState
        };

        $(document).ready(function() {
            $('.dataTable').DataTable();

            onSend();
        });

        function onCreate() {
            _clearForm();
            $("#titleForm").html("Tambah Approval Level");
            $("#formModal").modal("show");

            let detailNewForm = $('#detailNewForm');
            state.count_detail += 1;

            detailNewForm.before(viewDetailFormGroup());
            setupSelectAuthorized();
        }

        function onEdit(id) {
            $("#titleForm").html("Ubah Approval Level");
            $("#formModal").modal("show");
            _clearForm();

            $.ajax({
                url: "{{ route('api.approvalLevel.edit') }}",
                method: 'GET',
                dataType: 'json',
                data: {
                    id: id
                },
                success: function(responses) {
                    // console.info(responses);
                    const data = responses.data;
                    let detailNewForm = $('#detailNewForm');

                    $('#name').val(data.name);
                    $('#id').val(data.id);

                    data.detail.map(item => {
                        // start authorized
                        state.count_detail += 1;

                        detailNewForm.before(viewDetailFormGroup());
                        let optionSelectedAuthorized = new Option(
                            item.user_name_position,
                            item.user_id,
                            true,
                            true
                        );
                        $("#select_authorized_" + state.count_detail)
                            .append(optionSelectedAuthorized)
                            .trigger('change');

                        setupSelectAuthorized();
                        // end authorized

                        // start status
                        let optionSelectedStatus = new Option(
                            _getNameStatus(item.status),
                            item.status,
                            true,
                            true
                        );
                        $("#select_status_" + +state.count_detail).val(item.status).trigger('change');
                        // end status

                        // start level
                        $("#select_level_" + +state.count_detail).val(item.level)
                        // end level
                    });
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

        function onDelete(id, name) {
            Swal.fire({
                title: 'Perhatian!!!',
                html: `Anda yakin ingin hapus data approval <h2><b> ${name} </b> ?</h2>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('api.approvalLevel.delete') }}",
                        method: 'POST',
                        dataType: 'json',
                        data: {
                            id: id
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

        function onSend() {
            $("#form").submit(function(e) {
                e.preventDefault();
                let fd = new FormData(this);

                $.ajax({
                    url: "{{ route('setting.approvalLevel.store') }}",
                    method: 'post',
                    data: fd,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
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
                        if (responses.success == true) {
                            Toast.fire({
                                icon: 'success',
                                title: responses.message
                            });

                            window.location.reload();
                        }
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
            });
        }

        function onAddApprovalDetail() {
            let detailNewForm = $('#detailNewForm');
            state.count_detail += 1;

            detailNewForm.before(viewDetailFormGroup());
            setupSelectAuthorized();
        }

        function onRemoveDetailForm(id) {
            $('#form_group_detail_' + id).remove();
            $('#form_group_detail_second_' + id).remove();
        }

        function setupSelectAuthorized() {
            $('.select-authorizeds').select2({
                width: '100%',
                height: '100%',
                placeholder: "Pilih Opsi...",
                allowClear: true,
                ajax: {
                    url: "{{ route('api.approvalLevel.selectAuthorizeds') }}",
                    method: 'GET',
                    dataType: 'json',
                    data: function(term, page) {
                        return {
                            search: term.term,
                        };
                    },
                    processResults: function(responses) {
                        // console.info(responses);
                        return {
                            results: $.map(responses.data, function(item) {
                                return {
                                    text: `${item.name} - ${item.group_name}`,
                                    id: item.id,
                                }
                            })
                        };
                    },
                    // cache: true,
                    // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
                },

                // allowClear: true,
            });
        }

        function viewDetailFormGroup() {
            return `
                <div class="form-group row mt-2 form-group-detail" id="form_group_detail_${state.count_detail}">
                    <label for="text" class="col-sm-4 col-form-label text-right">Pejabat yang Berwenang</label>
                    <div class="col-sm-7">
                        <select
                            type="text"
                            class="form-control select-authorizeds"
                            id="select_authorized_${state.count_detail}"
                            name="authorizeds[]"></select>
                    </div>
                    <div class="col-sm-1">
                        <a href="javascript:void(0)" onclick="onRemoveDetailForm(${state.count_detail})"
                            class="btn btn-danger btn-sm btn-circle">
                            <i class="bi bi-x"></i>
                        </a>
                    </div>
                </div>
                <div class="form-group row form-group-detail" id="form_group_detail_second_${state.count_detail}">
                    <label for="text" class="col-sm-1 offset-sm-3 col-form-label label-status">Status</label>
                    <div class="col-sm-4">
                        <select
                            type="text"
                            class="form-control select-status select2"
                            id="select_status_${state.count_detail}"
                            name="status[]">
                            <option value='submit'> Diajukan </option>
                            <option value='knowing'> Mengetahui </option>
                            <option value='approved'> Disetujui oleh</option>
                        </select>
                    </div>
                    <label for="text" class="col-sm-1 col-form-label label-status">Level</label>
                    <div class="col-sm-2">
                        <input type="number" id="select_level_${state.count_detail}" name="levels[]" class="form-control">
                    </div>
                </div>
            `;
        }

        function _clearForm() {
            $("#name").val(null);
            $('#id').val(null);

            $(".form-group-detail").remove();
        }

        function _getNameStatus(status) {
            if (status == "submit") {
                return "Diajukan";
            } else if (status == "knowing") {
                return "Mengetahui";
            } else if (status == "approved") {
                return "Disetujui oleh";
            }
        }
    </script>
@endsection
