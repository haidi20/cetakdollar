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
                        {{-- <li class="breadcrumb-item"><a href="{{ route('setting.permission.index') }}">Fitur</a>
                        </li> --}}
                        <li class="breadcrumb-item active" aria-current="page">Jam Kerja</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <form id="form" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" id="id" name="id" class="form-control" value="{{ $id[0] ?? '' }}">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label for="start_time" class="col-sm-4 col-form-label">Jam Mulai Kerja </label>
                                        <div class="col-sm-8">
                                            <input type="text" id="start_time" name="start_time"
                                                class="form-control datetime" placeholder="contoh: 08:00"
                                                value="{{ $startTime[0] ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label for="after_work" class="col-sm-4 col-form-label">Jam Selesai
                                            Kerja</label>
                                        <div class="col-sm-8">
                                            <input type="text" id="after_work" name="after_work"
                                                class="form-control datetime" placeholder="contoh: 05:00"
                                                value="{{ $afterWork[0] ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label for="after_work_limit" class="col-sm-4 col-form-label">Jam Maksimal
                                            Selesai
                                            Kerja</label>
                                        <div class="col-sm-8">
                                            <input type="text" id="after_work_limit" name="after_work_limit"
                                                class="form-control datetime" placeholder="contoh: 05:00"
                                                value="{{ $afterWorkLimit[0] ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label for="start_rest" class="col-sm-4 col-form-label">Jam Mulai
                                            Istirahat</label>
                                        <div class="col-sm-8">
                                            <input type="text" id="start_rest" name="start_rest"
                                                class="form-control datetime" placeholder="contoh: 13:30"
                                                value="{{ $startRest[0] ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label for="end_rest" class="col-sm-4 col-form-label">Jam Selesai
                                            Istirahat</label>
                                        <div class="col-sm-8">
                                            <input type="text" id="end_rest" name="end_rest"
                                                class="form-control datetime" placeholder="contoh: 13:30"
                                                value="{{ $endRest[0] ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label for="fastest_time" class="col-sm-4 col-form-label">Jam Paling Cepat
                                            Pulang</label>
                                        <div class="col-sm-8">
                                            <input type="text" id="fastest_time" name="fastest_time"
                                                class="form-control datetime" placeholder="contoh: 16:30"
                                                value="{{ $fastestTime[0] ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label for="overtime_work" class="col-sm-4 col-form-label">Jam Mulai
                                            Lembur</label>
                                        <div class="col-sm-8">
                                            <input type="text" id="overtime_work" name="overtime_work"
                                                class="form-control datetime" placeholder="contoh: 16:30"
                                                value="{{ $overtimeWork[0] ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label for="saturday_work_hour" class="col-sm-4 col-form-label">Jam Selesai
                                            Kerja Hari
                                            Sabtu</label>
                                        <div class="col-sm-8">
                                            <input type="text" id="saturday_work_hour" name="saturday_work_hour"
                                                class="form-control datetime" placeholder="contoh: 13:30"
                                                value="{{ $saturdayWorkHour[0] ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-8"></div>
                                <div class="col-md-4" style="text-align:end; ">
                                    {{-- <button type="button" class="btn btn-sm btn-danger"
                                                    data-bs-dismiss="modal">Batal</button> --}}
                                    <button type="submit" class="btn btn-sm btn-success">Perbaharui</button>
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
<link rel="stylesheet" href="{{ asset('assets-mazer/css/pages/bootstrap-timepicker.css') }}" rel="stylesheet" />
@endsection
@section('script')
<script src="{{ asset('assets-mazer/extensions/backup/js/bootstrap-timepicker.min.js') }}"></script>
<script>
    $(document).ready(function () {
        $('.dataTable').DataTable();
        clearForm();
        setupSelect();
        setupTimepicker();
        send();
    });

    function onCreate() {
        clearForm();
        $("#id").val(data.id);
        $("#start_time").val(data.start_time);
        $("#after_work").val(data.after_work);
        $("#after_work_limit").val(data.after_work_limit);
        $("#start_rest").val(data.start_rest);
        $("#end_rest").val(data.end_rest);
        $("#maximum_delay").val(data.maximum_delay);
        $("#fastest_time").val(data.fastest_time);
        $("#overtime_work").val(data.overtime_work);
        $("#saturday_work_hour").val(data.saturday_work_hour);
        // $("#titleForm").html("Tambah Pengguna");
        onModalAction("formModal", "show");
    }

    function onEdit(id) {
        console.info(id);
    }

    function onDelete(id) {
        console.info(id);
    }

    function send() {
        $("#form").submit(function (e) {
            e.preventDefault();
            let fd = new FormData(this);

            $.ajax({
                url: "{{ route('master.workingHour.store') }}",
                method: 'POST',
                data: fd,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function (responses) {

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
                error: function (err) {
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

    function setupTimepicker() {
        $('.datetime').timepicker({
            timeFormat: 'HH:mm',
            dropdown: true,
            scrollbar: true,
            showMeridian: false,
            snapToStep: true,
            minuteStep: 1,
            secondStep: 1,
            icons: {
                time: 'far fa-clock',
                date: 'far fa-calendar',
                up: 'fas fa-arrow-up',
                down: 'fas fa-arrow-down',
                previous: 'fas fa-chevron-left',
                next: 'fas fa-chevron-right',
                today: 'fas fa-calendar-check',
                clear: 'far fa-trash-alt',
                close: 'far fa-times-circle'
            }
        });
    }

    function clearForm() {
        //
    }

</script>
@endsection
