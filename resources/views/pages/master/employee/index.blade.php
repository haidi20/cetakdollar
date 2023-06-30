@extends('layouts.master')

@section('content')
@include('pages.master.employee.partials.employee-modal')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Karyawan</h3>
                {{-- <p class="text-subtitle text-muted">For user to check they list</p> --}}
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        {{-- <li class="breadcrumb-item"><a href="#">Pengaturan</a></li> --}}
                        <li class="breadcrumb-item active" aria-current="page">Karyawan</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-header">
                <span class="fs-4 fw-bold">Data Karyawan</span>
                <button onclick="onCreate()" class="btn btn-sm btn-success shadow-sm float-end" id="addData"
                    data-toggle="modal">
                    <i class="fas fa-plus text-white-50"></i> Tambah Karyawan
                </button>
            </div>
            <div class="card-body">
                {{-- <div class="row">
                <div class="col-12 d-flex align-items-center">
                    <h5>Filter berdasarkan : </h5>
                </div>

            </div> --}}
                <div class="row">
                    <div class="col-sm-6 col-lg-2 form-group">
                        <label for="jabatanFilter" class="col-form-label">Jabatan :</label>
                        <div style="width: 100%;">
                            <select name="jabatanFilter" id="jabatanFilter" class="form-control select2"
                                style="width: 100%;">
                                <option value="">-- Pilih Jabatan --</option>
                                @foreach ($positions as $position)
                                <option value="{{ $position->name }}" data-position-id="{{ $position->id }}">
                                    {{ $position->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <a href="" target="_blank" class="btn btn-sm btn-secondary mt-2" id="exportPositionBtn"
                            style="display: none;"><i class="bi bi-file-earmark-spreadsheet"></i> Export Excel</a>
                    </div>
                    <div class="col-sm-6 col-lg-2 form-group">
                        <label for="locationFilter" class="col-form-label">Lokasi :</label>
                        <div style="width: 100%;">
                            <select name="locationFilter" id="locationFilter" class="form-control select2"
                                style="width: 100%;">
                                <option value="">-- Pilih Lokasi Karyawan --</option>
                                @foreach ($locations as $location)
                                <option value="{{ $location->name }}" data-location-id="{{ $location->id }}">
                                    {{ $location->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <a href="" target="_blank" class="btn btn-sm btn-secondary mt-2" id="exportLocationBtn"
                            style="display: none;"><i class="bi bi-file-earmark-spreadsheet"></i> Export Excel</a>
                    </div>

                </div>
                <hr>
                <div class="row">
                    {{-- <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="utama-tab" data-bs-toggle="tab" href="#utama" role="tab"
                                aria-controls="utama" aria-selected="true">Data Utama Pegawai</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="exp-tab" data-bs-toggle="tab" href="#exp" role="tab"
                                aria-controls="exp" aria-selected="false">Data Pegawai Habis Kontrak</a>
                        </li>
                    </ul> --}}
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="utama" role="tabpanel" aria-labelledby="utama-tab">
                            <div class="col-12">
                                <div class="table-responsive">
                                    {!! $dataTableEmployee->table(['id' => 'employee-table', 'class' => 'table
                                    table-striped table-bordered']) !!}
                                </div>
                            </div>
                        </div>
                        {{-- <div class="tab-pane fade" id="exp" role="tabpanel" aria-labelledby="exp-tab">
                            <div class="col-12">
                                <div class="table-responsive">
                                    {!! $dataTableExpEmployee->table(['class' => 'table table-striped table-bordered']) !!}
                                </div>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@section('style')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/litepicker/dist/css/litepicker.css" />
<link rel="stylesheet" href="{{ asset('assets-mazer/css/pages/form-element-select.css') }}" rel="stylesheet" />
<link rel="stylesheet" href="{{ asset('assets-mazer/css/pages/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" />
<link rel="stylesheet" href="{{ asset('assets-mazer/css/pages/datepicker3.css') }}" rel="stylesheet" />
<link rel="stylesheet" href="{{ asset('assets-mazer/css/pages/daterangepicker.css') }}" rel="stylesheet" />

{{-- <link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css"> --}}
@endsection
@section('script')
{!! $dataTableEmployee->scripts() !!}
{{-- {!! $dataTableExpBuilder->scripts() !!} --}}
<script>
    const initialState = {
        employees: [],
    };

    let state = {
        ...initialState
    };

    $(document).ready(function () {
        // $('.dataTable').DataTable();

        state.employees = {
            !!json_encode($employees) !!
        };

        setupSelect();
        // setupDateFilter();
        send();

    });

    // SETUP FILTER POSITION
    $(document).ready(function () {
        var table = $('.dataTable').DataTable();
        var exportPositionBtn = $('#exportPositionBtn');

        $('#jabatanFilter').select2();

        $('#jabatanFilter').on('change', function () {
            var selectedJabatan = $(this).val();
            var selectedPositionId = $(this).find(':selected').data('position-id');
            table.column(3).search(selectedJabatan)
                .draw(); // Mengubah angka kolom menjadi 3 untuk pencarian berdasarkan jabatan

            // Update export URL and set initial export URL
            var exportUrl =
                "{{ route('master.employee.exportExcelPosition', ['position_id' => ':position_id']) }}";
            exportUrl = exportUrl.replace(':position_id', selectedPositionId);
            exportPositionBtn.attr('href', exportUrl);

            // Toggle export button visibility and display message
            if (selectedPositionId != null) {
                exportPositionBtn.show();
                $('#exportPositionMessage').hide();
            } else {
                exportPositionBtn.hide();
                $('#exportPositionMessage').show();
            }
        });
    });

    // SETUP FILTER LOCATION
    $(document).ready(function () {
        var table = $('.dataTable').DataTable();
        var exportLocationBtn = $('#exportLocationBtn');

        $('#locationFilter').select2();

        $('#locationFilter').on('change', function () {
            var selectedLocation = $(this).val();
            var selectedLocationId = $(this).find(':selected').data('location-id');
            table.column(4).search(selectedLocation)
                .draw(); // Mengubah angka kolom menjadi 4 untuk pencarian berdasarkan lokasi

            // Update export URL and set initial export URL
            var exportUrl =
                "{{ route('master.employee.exportExcelLocation', ['location_id' => ':location_id']) }}";
            exportUrl = exportUrl.replace(':location_id', selectedLocationId);
            exportLocationBtn.attr('href', exportUrl);

            // Toggle export button visibility and display message
            if (selectedLocationId != null) {
                exportLocationBtn.show();
                $('#exportLocationMessage').hide();
            } else {
                exportLocationBtn.hide();
                $('#exportLocationMessage').show();
            }
        });
    });

    // SETUP FILTER ENTER DATE
    $(document).ready(function () {
        var table = $('.dataTable').DataTable();
        var startDate = '';
        var endDate = '';

        // Mengambil nilai awal enter_date_start dan enter_date_end dari tabel
        var enterDateValues = table.column(6).data().unique().sort().toArray();
        if (enterDateValues.length > 0) {
            var minDate = new Date(enterDateValues[0]);
            var maxDate = new Date(enterDateValues[enterDateValues.length - 1]);

            startDate = formatDate(minDate);
            endDate = formatDate(maxDate);
            $('#enter_date_start').val(startDate);
            $('#enter_date_end').val(endDate);
        }

        // Setup format untuk tanggal masuk
        $('#rangeContractFilter input').each(function () {
            $(this).datepicker({
                autoclose: true,
                format: "dd-mm-yyyy"
            });
        });

        $('#rangeContractFilter').on('change', function () {
            startDate = $('#enter_date_start').val();
            endDate = $('#enter_date_end').val();

            table.column(6).search(startDate + ' - ' + endDate).draw();
        });
    });

    function formatDate(date) {
        var day = date.getDate();
        var month = date.getMonth() + 1;
        var year = date.getFullYear();

        return padZero(day) + '-' + padZero(month) + '-' + year;
    }

    function padZero(number) {
        return number.toString().padStart(2, '0');
    }

    function onCreate() {
        clearFormCreate();
        $("#personal-tab").tab("show");
        $("#titleForm").html("Tambah Karyawan");
        $("#kepegawaian-tab").hide();
        $("#salary-tab").hide();
        $("#finger-tab").hide();
        // $("#kepegawaian").hide();

        $('#birth_date').each(function () {
            $(this).datepicker({
                autoclose: true,
                format: "yyyy-mm-dd",
                // viewMode: "months",
                // minViewMode: "months"
            });
            $(this).datepicker('clearDates');
        });

        $("#photo").change(function (e) {
            var file = e.target.files[0];
            var reader = new FileReader();

            reader.onload = function (e) {
                $("#photoPreview").html('<img src="' + e.target.result + '" alt="Foto" width="100%">');
            };

            reader.readAsDataURL(file);
        });
        onModalAction("formModal", "show");
    }

    function onEdit(data) {
        clearForm();

        // MEMUNCULKAN TAB
        $("#kepegawaian-tab").show();
        $("#salary-tab").show();
        $("#finger-tab").show();

        // SETUP FORMAT UNTUK TANGGAL MASUK
        function formatEnterDate(dateString) {
            var days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            var date = new Date(dateString);
            var dayName = days[date.getDay()];
            var day = date.getDate();
            var month = date.getMonth() + 1;
            var year = date.getFullYear();
            var hours = date.getHours();
            var minutes = date.getMinutes();
            var formattedDate = dayName + ', ' + day + '-' + month + '-' + year;
            return formattedDate;
        }

        // SETUP FORMAT UNTUK TANGGAL KELUAR
        $('#out_date').each(function () {
            $(this).datepicker({
                autoclose: true,
                format: "yyyy-mm-dd",
                // viewMode: "months",
                // minViewMode: "months"
            });
            $(this).datepicker('setDate', new Date());
        });

        // SETUP FORMAT UNTUK TANGGAL ULANG TAHUN
        $('#birth_date').each(function () {
            $(this).datepicker({
                autoclose: true,
                format: "yyyy-mm-dd",
                // viewMode: "months",
                // minViewMode: "months"
            });
            $(this).datepicker('clearDates');
        });

        // SETUP FORMAT UNTUK KONTRAK
        $('#contract_range input').each(function () {
            $(this).datepicker({
                autoclose: true,
                format: "dd-mm-yyyy",
                // viewMode: "months",
                // minViewMode: "months"
            });
            $(this).datepicker('contract_range');
        });

        // SETUP KONDISI UNTUK JENIS KARYAWAN
        $("#employee_type_id").change(function () {
            var selectedValue = $(this).val();

            if (selectedValue === "1") {
                $("#contract_range_row").hide();
                $("#contract_start").val("");
                $("#contract_end").val("");
            } else if (selectedValue === "2") {
                $("#contract_range_row").show();
                $("#contract_start").val("");
                $("#contract_end").val("");
                $("#titleContactRange").html("Jangka Waktu Kontrak");
            } else if (selectedValue === "3") {
                $("#contract_range_row").show();
                $("#contract_start").val("");
                $("#contract_end").val("");
                $("#titleContactRange").html("Jangka Waktu Harian");
            } else {
                $("#contract_range_row").hide();
            }
        });

        // SETUP KONDISI UNTUK STATUS KARYAWAN
        $("#employee_status").change(function () {
            var employeeStatus = $(this).val();

            if (employeeStatus === "aktif") {
                $("#out_date_row").hide();
                $("#reason_row").hide();
                $("#reason").val("");
                $("#out_date").val("");
            } else if (employeeStatus === "tidak_aktif") {
                $("#out_date_row").show();
                $("#reason_row").show();
            }
        });

        // EDIT VALUE
        // DATA PERSONAL
        $("#id").val(data.id);
        $("#nip").val(data.nip);
        $("#nik").val(data.nik);
        $("#name").val(data.name);
        $("#birth_place").val(data.birth_place);
        $("#birth_date").val(data.birth_date);
        $("#phone").val(data.phone);
        $("#religion").val(data.religion).trigger("change");
        $("#address").val(data.address);
        $("#photo").change(function (e) {
            var file = e.target.files[0];
            var reader = new FileReader();

            reader.onload = function (e) {
                $("#photoPreview").html('<img src="' + e.target.result + '" alt="Foto" width="50%">');
            };

            reader.readAsDataURL(file);
        });
        $("#photoPreviewReady").show();
        var photoUrl = "{{ Storage::url('') }}" + data.photo;

        var imageElement = document.createElement("img");
        imageElement.src = photoUrl;
        imageElement.alt = "Employee Photo";
        imageElement.style.width = "50%";

        var photoPreviewReady = document.getElementById("photoPreviewReady");
        photoPreviewReady.innerHTML = "";
        photoPreviewReady.appendChild(imageElement);

        // DATA KEPEGAWAIAN
        $("#enter_date").val(formatEnterDate(data.enter_date));
        $("#npwp").val(data.npwp);
        $("#company_id").val(data.company_id).trigger("change");
        $("#position_id").val(data.position_id).trigger("change");
        $("#location_id").val(data.location_id).trigger("change");
        $("#employee_type_id").val(data.employee_type_id).trigger("change");
        $("#finger_tool_id").val(data.finger_tool_id).trigger("change");
        $("#contract_start").val(data.contract_start);
        $("#contract_end").val(data.contract_end);
        $("#latest_education").val(data.latest_education).trigger("change");
        $("#working_hour").val(data.working_hour).trigger("change");
        $("#married_status").val(data.married_status).trigger("change");

        $("#bpjs_tk").prop("checked", data.bpjs_tk === "Y");
        $("#bpjs_tk").attr("data-target", data.id);

        $("#bpjs_tk_pt").prop("checked", data.bpjs_tk_pt === "Y");
        $("#bpjs_tk_pt").attr("data-target", data.id);

        $("#bpjs_kes").prop("checked", data.bpjs_kes === "Y");
        $("#bpjs_kes").attr("data-target", data.id);

        $("#bpjs_kes_pt").prop("checked", data.bpjs_kes_pt === "Y");
        $("#bpjs_kes_pt").attr("data-target", data.id);

        $("#bpjs_training").prop("checked", data.bpjs_training === "Y");
        $("#bpjs_training").attr("data-target", data.id);

        $("#employee_status").val(data.employee_status).trigger("change");
        $("#reason").val(data.reason);
        $("#out_date").val(data.out_date);

        // DATA GAJI DAN REKENING
        $("#basic_salary").val(data.basic_salary);
        $("#allowance").val(data.allowance);
        $("#meal_allowance_per_attend").val(data.meal_allowance_per_attend);
        $("#transport_allowance_per_attend").val(data.transport_allowance_per_attend);
        $("#attend_allowance_per_attend").val(data.attend_allowance_per_attend);
        $("#overtime_rate_per_hour").val(data.overtime_rate_per_hour);
        $("#vat_per_year").val(data.vat_per_year);
        $("#rekening_number").val(data.rekening_number);
        $("#rekening_name").val(data.rekening_name);
        $("#bank_name").val(data.bank_name).trigger("change");
        $("#branch").val(data.branch);

        // DATA FINGER
        // $("#finger_tool_id").val(data.finger_tool_id).trigger("change");
        // $("#id_finger").val(data.id_finger);

        $("#titleForm").html("Ubah Karyawan");

        // CHECK SLIDER CONDITION
        $('.bpjsTKCheck').change(function () {
            var mode = $(this).prop('checked');
            var id = $(this).attr('data-target');
            var _token = "{{ csrf_token() }}";

            var dataHide = $(this).attr('data-hide');

            $.ajax({
                type: 'POST',
                dataType: 'JSON',
                url: "{{ route('master.employee.bpjsTK') }}",
                data: {
                    id: id,
                    mode: mode,
                    _token: _token
                },

                success: function (data) {
                    if (mode) {
                        $(dataHide).css('display', 'block');
                        console.log("Data Berhasil Diaktifkan");
                    } else {
                        $(dataHide).css("display", "none");
                        console.log("Data Berhasil Dinonaktifkan");
                    }
                }
            });
        });

        $('.bpjsTKPTCheck').change(function () {
            var mode = $(this).prop('checked');
            var id = $(this).attr('data-target');
            var _token = "{{ csrf_token() }}";

            var dataHide = $(this).attr('data-hide');


            $.ajax({
                type: 'POST',
                dataType: 'JSON',
                url: "{{ route('master.employee.bpjsTKPT') }}",
                data: {
                    id: id,
                    mode: mode,
                    _token: _token
                },

                success: function (data) {
                    if (mode) {
                        $(dataHide).css('display', 'block');
                        console.log("Data Berhasil Diaktifkan");
                    } else {
                        $(dataHide).css("display", "none");
                        console.log("Data Berhasil Dinonaktifkan");
                    }
                }
            });
        });

        $('.bpjsKESCheck').change(function () {
            var mode = $(this).prop('checked');
            var id = $(this).attr('data-target');
            var _token = "{{ csrf_token() }}";

            var dataHide = $(this).attr('data-hide');


            $.ajax({
                type: 'POST',
                dataType: 'JSON',
                url: "{{ route('master.employee.bpjsKES') }}",
                data: {
                    id: id,
                    mode: mode,
                    _token: _token
                },

                success: function (data) {
                    if (mode) {
                        $(dataHide).css('display', 'block');
                        console.log("Data Berhasil Diaktifkan");
                    } else {
                        $(dataHide).css("display", "none");
                        console.log("Data Berhasil Dinonaktifkan");
                    }
                }
            });
        });

        $('.bpjsKESPTCheck').change(function () {
            var mode = $(this).prop('checked');
            var id = $(this).attr('data-target');
            var _token = "{{ csrf_token() }}";

            var dataHide = $(this).attr('data-hide');


            $.ajax({
                type: 'POST',
                dataType: 'JSON',
                url: "{{ route('master.employee.bpjsKESPT') }}",
                data: {
                    id: id,
                    mode: mode,
                    _token: _token
                },

                success: function (data) {
                    if (mode) {
                        $(dataHide).css('display', 'block');
                        console.log("Data Berhasil Diaktifkan");
                    } else {
                        $(dataHide).css("display", "none");
                        console.log("Data Berhasil Dinonaktifkan");
                    }
                }
            });
        });

        $('.bpjsTRAININGCheck').change(function () {
            var mode = $(this).prop('checked');
            var id = $(this).attr('data-target');
            var _token = "{{ csrf_token() }}";

            var dataHide = $(this).attr('data-hide');


            $.ajax({
                type: 'POST',
                dataType: 'JSON',
                url: "{{ route('master.employee.bpjsTRAINING') }}",
                data: {
                    id: id,
                    mode: mode,
                    _token: _token
                },

                success: function (data) {
                    if (mode) {
                        $(dataHide).css('display', 'block');
                        console.log("Data Berhasil Diaktifkan");
                    } else {
                        $(dataHide).css("display", "none");
                        console.log("Data Berhasil Dinonaktifkan");
                    }
                }
            });
        });

        $(document).ready(function () {
            var employeeId = data.id;

            $.ajax({
                url: "{{ route('master.employee.getEmployeeFingers', ['employeeId' => ':employeeId']) }}"
                    .replace(
                        ":employeeId",
                        employeeId
                    ),
                type: "GET",
                success: function (response) {
                    if (response.success) {
                        var fingers = response.data;

                        $("#finger_tool_id").val("").trigger("change");
                        $("#id_finger").val("");
                        $("#fingerTableBody").empty();

                        if (fingers.length > 0) {
                            for (var i = 0; i < fingers.length; i++) {
                                var finger = fingers[i];
                                var fingerToolName = finger.finger_tool &&
                                    finger.finger_tool.name ? finger.finger_tool.name : "";
                                var row = $("<tr>").append(
                                    $("<td>").text(fingerToolName),
                                    $("<td>").text(finger.id_finger),
                                    $("<td>").append(
                                        $("<button>")
                                        .addClass("btn btn-warning btn-sm")
                                        .data("finger", finger)
                                        .attr("type", "button")
                                        .html('<i class="bi bi-pen"></i>')
                                        .click(function () {
                                            var fingerData = $(this).data("finger");
                                            $("#finger_tool_id")
                                                .val(fingerData.finger_tool_id)
                                                .trigger("change");
                                            $("#id_finger").val(fingerData.id_finger);
                                        }),
                                        $("<button>")
                                        .addClass("btn btn-danger btn-sm ml-2")
                                        .data("finger", finger)
                                        .attr("type", "button")
                                        .html('<i class="bi bi-trash"></i>')
                                        .click(function () {
                                            var fingerData = $(this).data("finger");
                                            $.ajax({
                                                url: "{{ route('master.employee.deleteEmployeeFingers', ['employeeId' => ':employeeId']) }}"
                                                    .replace(
                                                        ":employeeId",
                                                        employeeId
                                                    ),
                                                type: "DELETE",
                                                data: {
                                                    fingerId: fingerData.id,
                                                },
                                                success: function (responses) {
                                                    if (responses.success) {
                                                        const Toast = Swal
                                                            .mixin({
                                                                toast: true,
                                                                position: "top-end",
                                                                showConfirmButton: false,
                                                                timer: 2500,
                                                                timerProgressBar: true,
                                                                didOpen: (
                                                                    toast
                                                                ) => {
                                                                    toast
                                                                        .addEventListener(
                                                                            "mouseenter",
                                                                            Swal
                                                                            .stopTimer
                                                                        );
                                                                    toast
                                                                        .addEventListener(
                                                                            "mouseleave",
                                                                            Swal
                                                                            .resumeTimer
                                                                        );
                                                                },
                                                            });
                                                        $("#formModal").modal(
                                                            "hide");
                                                        Toast.fire({
                                                            icon: "success",
                                                            title: responses
                                                                .message,
                                                        });

                                                        window
                                                            .LaravelDataTables[
                                                                "employee-table"
                                                            ].ajax.reload(
                                                                function (
                                                                    json) {});
                                                        $(this).closest("tr")
                                                            .remove();
                                                    } else {
                                                        console.log(
                                                            responses
                                                            .message);
                                                    }
                                                },
                                                error: function (xhr, status,
                                                    error) {
                                                    console.log(error);
                                                },
                                            });
                                        })
                                    )
                                );

                                $("#fingerTableBody").append(row);
                            }
                        } else {
                            var row = $("<tr>").append(
                                $('<td colspan="3" class="text-center">').text(
                                    "Data Finger Masih Kosong"
                                )
                            );
                            $("#fingerTableBody").append(row);
                        }

                        // console.log(employeeId);
                    } else {
                        console.log(response.message);
                    }
                },
                error: function (xhr, status, error) {
                    console.log(error);
                },
            });
        });

        onModalAction("formModal", "show");
    }

    function send() {
        $("#form").submit(function (e) {
            e.preventDefault(); // Batalkan tindakan submit default

            let fd = new FormData(this);

            $.ajax({
                url: "{{ route('master.employee.store') }}",
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
                            toast.addEventListener('mouseenter', Swal
                                .stopTimer)
                            toast.addEventListener('mouseleave', Swal
                                .resumeTimer)
                        }
                    });
                    if (responses.success == true) {
                        $('#formModal').modal('hide');
                        Toast.fire({
                            icon: 'success',
                            title: responses.message
                        });

                        window.LaravelDataTables["employee-table"].ajax.reload(function (json) {});
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
                            toast.addEventListener('mouseenter', Swal
                                .stopTimer)
                            toast.addEventListener('mouseleave', Swal
                                .resumeTimer)
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
            html: `Anda yakin ingin hapus data karyawan <h2><b> ${data.name} </b> ?</h2>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Tidak'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('master.employee.delete') }}",
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        _method: 'DELETE',
                        id: data.id
                    },
                    success: function (responses) {
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 2500,
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.addEventListener('mouseenter', Swal
                                    .stopTimer)
                                toast.addEventListener('mouseleave', Swal
                                    .resumeTimer)
                            }
                        });
                        if (responses.success == true) {
                            Toast.fire({
                                icon: 'success',
                                title: responses.message
                            });

                            window.LaravelDataTables["employee-table"].ajax.reload(function (
                                json) {});
                        }
                    },
                    error: function (err) {
                        // console.log(err.responseJSON.message);
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 4000,
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.addEventListener('mouseenter', Swal
                                    .stopTimer)
                                toast.addEventListener('mouseleave', Swal
                                    .resumeTimer)
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

    // $(document).ready(function() {
    //     $('#finger_tool_id').change(function() {
    //         var selectedValue = $(this).val();
    //         if (selectedValue !== '') {
    //             $('#id_finger_container').show();
    //         } else {
    //             $('#id_finger_container').hide();
    //         }
    //     });

    //     $('#tambah_data').click(function() {
    //         // Tambahkan logika untuk menambahkan data ke dalam formulir atau melakukan tindakan lain
    //         console.log('Data tambah di klik');
    //     });
    // });

    function clearForm() {
        // $("#id").val("");
        // $("#nip").val("");
        // $("#nik").val("");
        // $("#name").val("");
        // $("#birth_place").val("");
        // $("#birth_date").val("");
        // $("#phone").val("");
        // $("#religion").val("").trigger("change");
        // $("#address").val("");
        // $("#photo").val("");
        // $("#photoPreview").val("");
        // $("#photoPreviewReady").hide();
    }

    function clearFormCreate() {
        $("#id").val("");
        $("#nip").val("");
        $("#nik").val("");
        $("#name").val("");
        $("#birth_place").val("");
        $("#birth_date").val("");
        $("#phone").val("");
        $("#religion").val("").trigger("change");
        $("#address").val("");
        $("#photo").val("");
        $("#photoPreview").val("");
        $("#photoPreviewReady").hide();
    }

</script>
@endsection
