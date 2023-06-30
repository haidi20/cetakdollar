@extends('layouts.master')

@section('content')
@include('pages.setting.partials.bpjs-calculation-modal')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Perhitungan Bpjs</h3>
                {{-- <p class="text-subtitle text-muted">For user to check they list</p> --}}
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        {{-- <li class="breadcrumb-item"><a href="#">Pengaturan</a></li> --}}
                        <li class="breadcrumb-item active" aria-current="page">Perhitungan Bpjs</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-header">
                Data Perhitungan Bpjs
                {{-- <button onclick="onCreate()" class="btn btn-sm btn-success shadow-sm float-end" id="addData"
                        data-toggle="modal">
                        <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Perhitungan Bpjs
                    </button> --}}
            </div>
            <div class="card-body">
                <table class="table table-striped dataTable" id="table1">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th width="18%">Nama</th>
                            <th>Persenan Perusahaan</th>
                            <th>Persenan Karyawan</th>
                            <th>Nominal Perusahaan</th>
                            <th>Nominal Karyawan</th>
                            <th width="5%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bpjs_calculations as $bpjs_calculation)
                        <tr>
                            <td>
                                {{ $loop->iteration }}
                            </td>
                            <td>
                                {{ $bpjs_calculation->name }}
                            </td>
                            <td>
                                {{ $bpjs_calculation->company_percent . "%" }}
                            </td>
                            <td>
                                {{ $bpjs_calculation->employee_percent . "%" }}
                            </td>
                            <td>
                                {{ "Rp. " . number_format($bpjs_calculation->company_nominal, 0, ",", ".") }}
                            </td>
                            <td>
                                {{ "Rp. " . number_format($bpjs_calculation->employee_nominal, 0, ",", ".") }}
                            </td>
                            <td class="flex flex-row justify-content-around">
                                @can('ubah perhitungan bpjs')
                                <a href="javascript:void(0)" onclick="onEdit({{ $bpjs_calculation }})"
                                    class="btn btn-sm btn-info">Ubah
                                </a>
                                @endcan
                                {{-- @can('hapus perhitungan bpjs')
                                            <a href="javascript:void(0)" onclick="onDelete({{ $bpjs_calculation }})"
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

@section('script')
{{-- <script src="assets/static/js/components/dark.js"></script>
    <script src="assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js"></script>

    <!-- Need: Apexcharts -->
    <script src="assets/extensions/apexcharts/apexcharts.min.js"></script>
    <script src="assets/static/js/pages/dashboard.js"></script> --}}

<script>
    $(document).ready(function () {
        $('.dataTable').DataTable();

        send();
    });

    function onCreate() {
        clearForm();
        $("#titleForm").html("Tambah Perhitungan Bpjs");
        onModalAction("formModal", "show");
    }

    function onEdit(data) {
        clearForm();

        $("#id, #name, #company_percent, #employee_percent").val(function () {
            return data[$(this).attr('id')];
        });

        // Mendapatkan nilai nominal dari model BpjsCalculation
        $.post("{{ route('setting.bpjsCalculation.get-base-wages') }}", {
                _token: "{{ csrf_token() }}"
            })
            .done(function (response) {
                var baseWages = response.baseWages;
                var baseWagesBPJSTK = baseWages.base_wages_bpjs_nominal_1;
                var baseWagesBPJSKES = baseWages.base_wages_bpjs_nominal_2;

                function formatRupiah(angka, prefix) {
                    var rupiah = Number(angka);
                    return prefix + rupiah.toLocaleString();
                }

                var calculateNominal = function (percent, baseWage) {
                    var nominal = Math.floor(baseWage * percent);
                    var last3Digits = nominal.toString().slice(-3);
                    var roundedValue = Math.round(last3Digits / 100) * 100;
                    nominal = nominal - last3Digits + roundedValue;

                    if (last3Digits === "000" && nominal.toString().slice(-2, -1) === "0") {
                        nominal = nominal.toString().slice(0, -2);
                    } else {
                        nominal = nominal.toString().slice(0, -2);
                    }

                    return formatRupiah(nominal, "Rp. ");
                };

                $("#company_nominal").val(calculateNominal(data.company_percent, baseWagesBPJSTK));
                $("#employee_nominal").val(calculateNominal(data.employee_percent, baseWagesBPJSKES));

                $("#titleForm").html("Ubah Perhitungan Bpjs");
                onModalAction("formModal", "show");
            })
            .fail(function (error) {
                console.log(error);
            });
    }

    $(document).on("change", "#company_percent, #employee_percent", function () {
        // Mendapatkan nilai nominal dari model BpjsCalculation
        $.post("{{ route('setting.bpjsCalculation.get-base-wages') }}", {
                _token: "{{ csrf_token() }}"
            })
            .done(function (response) {
                var baseWages = response.baseWages;
                var baseWagesBPJSTK = baseWages.base_wages_bpjs_nominal_1;
                var baseWagesBPJSKES = baseWages.base_wages_bpjs_nominal_2;

                function formatRupiah(angka, prefix) {
                    var rupiah = Number(angka);
                    return prefix + rupiah.toLocaleString();
                }

                var calculateNominal = function (percent, baseWage) {
                    var nominal = Math.floor(baseWage * percent);
                    var last3Digits = nominal.toString().slice(-3);
                    var roundedValue = Math.round(last3Digits / 100) * 100;
                    nominal = nominal - last3Digits + roundedValue;

                    if (last3Digits === "000" && nominal.toString().slice(-2, -1) === "0") {
                        nominal = nominal.toString().slice(0, -2);
                    } else {
                        nominal = nominal.toString().slice(0, -2);
                    }

                    return formatRupiah(nominal, "Rp. ");
                };

                var companyPercent = $("#company_percent").val();
                var employeePercent = $("#employee_percent").val();

                $("#company_nominal").val(calculateNominal(companyPercent, baseWagesBPJSTK));
                $("#employee_nominal").val(calculateNominal(employeePercent, baseWagesBPJSKES));
            })
            .fail(function (error) {
                console.log(error);
            });
    });

    function onDelete(data) {
        Swal.fire({
            title: 'Perhatian!!!',
            html: `Anda yakin ingin hapus data perhitungan bpjs <h2><b> ${data.name} </b> ?</h2>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            onfirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Tidak'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('setting.bpjsCalculation.delete') }}",
                    method: 'DELETE',
                    dataType: 'json',
                    data: {
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
        $("#form").submit(function (e) {
            e.preventDefault();
            let fd = new FormData(this);

            $.ajax({
                url: "{{ route('setting.bpjsCalculation.store') }}",
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
                        $('#formModal').modal('hide');
                        Toast.fire({
                            icon: 'success',
                            title: responses.message
                        });

                        window.LaravelDataTables["dataTableBuilder"].ajax.reload(
                        function(json) {});
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

    function clearForm() {
        $("#id").val("");
        $("#name").val("");
        $("#company_percent").val("");
        $("#employee_percent").val("");
    }

</script>
@endsection
