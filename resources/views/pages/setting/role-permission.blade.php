@extends('layouts.master')

@section('content')
@include('pages.setting.partials.role-permission-modal')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Hak Akses - {{ $nameGroupUser }}</h3>
                {{-- <p class="text-subtitle text-muted">For user to check they list</p> --}}
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('setting.role.index') }}">Grup Pengguna</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Hak Akses</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-header">
                Data
                @can('tambah fitur')
                <a href="{{ route('setting.feature.index') }}" class="btn btn-sm btn-success shadow-sm  ml-2 float-end"
                id="addData" data-toggle="modal">
                <i class="fas fa-plus text-white-50"></i> Tambah Fitur
            </a>
            @endcan
            @can('lihat grup pengguna')
            <a href="{{ route('setting.role.index') }}" class="btn btn-sm btn-primary shadow-sm float-end">
                <i class="fas fa-angle-left text-white-50"></i>
                Kembali
            </a>
        </div>
        @endcan
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        {!! $html->table(['class' => 'table table-striped table-bordered']) !!}
                    </div>
                </div>
            </div>
            {{-- <table class="table table-striped dataTable" id="table1">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama</th>
                        <th>Deskripsi</th>
                        <th></th>
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
                        <td>
                            @can('ubah hak akses')
                            <a href="javascript:void(0)" onclick="onEdit({{ $feature }})" class="btn btn-sm btn-primary">
                                Ubah
                            </a>
                            @endcan
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table> --}}
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
{!! $html->scripts() !!}
<script>
    const initialState = {
        role: [],
        permissions_by_role: [],
        permissions_by_feature: [],
    }

    let state = {
        ...initialState
    };

    $(document).ready(function() {
        $('.dataTable').DataTable();
    });

    function onEdit(feature) {
        $.ajax({
            url: "{{ route('setting.rolePermission.show', ['roleId' => $roleId]) }}",
            method: 'GET',
            data: {
                role_id: "{{ $roleId }}",
                feature_id: feature.id,
            },
            beforeSend: function() {
                // empty view

                state.permission_by_user = [];
                state.permission_by_feature = [];
            },
            success: function(responses) {
                // console.info(responses);
                state = {
                    permissions_by_role: changeToArray(responses.permissionsByRole),
                    permissions_by_feature: changeToArray(responses.permissionsByFeature),
                }

                // console.info(state.permission_by_user);

                // memunculkan list checkbox permissions
                let li = "";
                state.permissions_by_feature.map((item, index) => {
                    // console.info(state.role.permissions[index]?.id, item.id);
                    let checked = null;

                    checked = state.permissions_by_role.some(permission => permission.id == item
                    .id);
                    checked = checked && "checked";

                    let radio =
                    `<input class="form-check-input" type="checkbox" id="checkbox_${item.id}" ${checked}>
                    <span for="checkbox_${item.id}">${item.name}</span>`;
                    li += `<li onchange="onChange(${item.id})"> ${radio} </li>`;
                });
                $('#listPermissions').empty();
                $('#listPermissions').append(li);
            },
            error: function(err) {}
        });

        $("#titleForm").html(`Ubah Hak Akses - ${feature.name}`);
        onModalAction("formModal", "show");
    }

    function onChange(id) {
        let checkCheckbox = $("#checkbox_" + id).prop('checked');
        let getPermission = state.permissions_by_feature.filter((item, index) => item.id == id)[0];
        let stateRolePermissions = state.permissions_by_role;

        if (checkCheckbox) {
            stateRolePermissions.push(getPermission);
        } else {
            // hapus kembali jika di uncentang checkboxnya
            stateRolePermissions.splice(stateRolePermissions.findIndex(e => e.id == id), 1);
        }
    }

    function onSend() {
        const getFeatureId = state.permissions_by_feature[0].feature_id;
        const getPermissionsByRole = state.permissions_by_role.filter(item => item.feature_id == getFeatureId);

        // console.info(state);
        const data = {
            ...state,
            role_id: "{{ $roleId }}",
            permissions_by_role: getPermissionsByRole,
        };

        // console.info(data);

        // return false;

        $.ajax({
            url: "{{ route('setting.rolePermission.store', ['roleId' => $roleId]) }}",
            method: 'POST',
            data: data,
            // cache: false,
            success: function(responses) {
                // console.info(responses);

                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2500,
                    timerProgressBar: true,
                    customClass: {
                        width: '4000px' // set the width to 400 pixels
                    },
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
    }

    function clearForm() {
        //
    }

    function changeToArray(value) {
        return Array.isArray(value) ? value : Object.keys(value).map(key =>
        value[key]);
    }
</script>
@endsection
