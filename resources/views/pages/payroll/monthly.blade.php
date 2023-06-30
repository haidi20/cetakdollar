@extends('layouts.master')

@section('content')
    @include('pages.master.position.partials.modal')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Penggajian Bulanan</h3>
                    {{-- <p class="text-subtitle text-muted">For user to check they list</p> --}}
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            {{-- <li class="breadcrumb-item"><a href="#">Pengaturan</a></li> --}}
                            <li class="breadcrumb-item active" aria-current="page">Penggajian Bulanan</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="basicInput">Pilih Bulan</label>
                                <input type="month" class="form-control" id="month_filter" autocomplete="off"
                                    value="{{ $monthNow }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="employees">Pilih karyawan</label>
                                <select name="employees" id="employees" class="form-control select2" style="width: 100%;">
                                    @foreach ($employees as $employee)
                                        <option value="{{ $employee->id }}">
                                            {{ $employee->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3" style="align-self: center;">
                            <button type="button" onclick="onFilter()" class="btn btn-sm btn-success mt-2 ml-4 mt-md-0">
                                Kirim
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="information-tab" data-bs-toggle="tab" href="#information"
                                role="tab" aria-controls="information" aria-selected="true">Informasi</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link " id="salary-tab" data-bs-toggle="tab" href="#salary" role="tab"
                                aria-controls="salary" aria-selected="true">Perhitungan Gaji</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link " id="attendance-tab" data-bs-toggle="tab" href="#attendance" role="tab"
                                aria-controls="attendance" aria-selected="true">Absensi</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link " id="bpjs-tab" data-bs-toggle="tab" href="#bpjs" role="tab"
                                aria-controls="bpjs" aria-selected="true">Perhitungan BPJS</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link " id="pph21-tab" data-bs-toggle="tab" href="#pph21" role="tab"
                                aria-controls="pph21" aria-selected="true">Perhitungan Pajak Penghasilan (PPH 21)</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="information" role="tabpanel">
                            @include('pages.payroll.partials.information')
                        </div>
                        <div class="tab-pane fade show " id="salary" role="tabpanel">
                            @include('pages.payroll.partials.salary')
                        </div>
                        <div class="tab-pane fade show " id="attendance" role="tabpanel">
                            @include('pages.payroll.partials.attendance')
                        </div>
                        <div class="tab-pane fade show " id="bpjs" role="tabpanel">
                            @include('pages.payroll.partials.bpjs')
                        </div>
                        <div class="tab-pane fade show " id="pph21" role="tabpanel">
                            @include('pages.payroll.partials.pph21')
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </div>
@endsection

@section('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/litepicker/dist/css/litepicker.css" />

    <style>
        .head-color {
            color: #435ebe;
        }

        .summary {
            border-top: 1px solid black;
            border-bottom: 1px solid black;
        }

        .left-line-vertical {
            border-left: 1px solid #A6CDF5;
        }

        .bpjs-row {
            border-bottom: 1px solid gray;
        }
    </style>
@endsection
@section('script')
    <script src="https://cdn.jsdelivr.net/npm/litepicker/dist/nocss/litepicker.js"></script>

    <script>

    function formatRupiah(angka='', prefix=0){

        if(angka==''){
            return '';
        }
                var number_string = parseInt(angka).toString().replace(/[^,\d]/g, ''),
                split   		= number_string.split(','),
                sisa     		= split[0].length % 3,
                rupiah     		= split[0].substr(0, sisa),
                ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);
    
                // tambahkan titik jika yang di input sudah menjadi angka ribuan
                if(ribuan){
                    separator = sisa ? '.' : '';
                    rupiah += separator + ribuan.join('.');
                }
    
                rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
                return prefix == undefined ? rupiah : (rupiah ? '' + rupiah : '');
            }


        const initialState = {
            //
        };

        let state = {
            ...initialState
        };

        $(document).ready(function() {
            $('.dataTable').DataTable();

            fetchBpjs();
            fetchAttendance();
            // fetchPph21();
            fetchSalary();
            fetchInformation();

            setupSelect();
        });

        function onFilter() {
            fetchBpjs();
            fetchAttendance()
            // fetchPph21();
            fetchSalary();
            fetchInformation();
        }

        function fetchAttendance() {
            $.ajax({
                url: "{{ route('api.payroll.attendance') }}",
                method: 'GET',
                data: {
                    month_filter: $("#month_filter").val(),
                    employee_id : $('#employees').val(),
                },
                beforeSend: function() {
                    // empty view
                },
                success: function(responses) {
                    // console.info(responses);
                    $('#div_attendace').html(responses);
                    

                },
                error: function(err) {}
            });
        }


        function fetchInformation() {
            $.ajax({
                url: "{{ route('api.payroll.fetchInformation') }}",
                method: 'GET',
                data: {
                    month_filter: $("#month_filter").val(),
                    employee_id : $('#employees').val(),
                },
                beforeSend: function() {
                    // empty view
                },
                success: function(responses) {
                    // console.info(responses);
                    const employee = responses.employee;
                    const month_readable = responses.monthReadAble;

                    $("#month_year").text(month_readable);
                    $("#employee_name").text(employee.name);
                    $("#position_name").text(employee.position_name);
                    $("#employee_number_identity").text(employee.nip);
                    $("#gaji_dasar").text(`Rp. ${formatRupiah(employee.basic_salary)}`);
                    $("#tunjangan_tetap").text(`Rp. ${formatRupiah(employee.allowance)}`);
                    $("#rate_lembur").text(`Rp. ${formatRupiah(employee.overtime_rate_per_hour)}`);
                    $("#tunjangan_makan").text(`Rp. ${formatRupiah(employee.meal_allowance_per_attend)}`);
                    $("#tunjangan_transportasi").text(`Rp. ${formatRupiah(employee.transport_allowance_per_attend)}`);
                    $("#tunjangan_kehadiran").text(`Rp. ${formatRupiah(employee.attend_allowance_per_attend)}`);
                    $("#ptkp_karyawan").text(`Rp. ${formatRupiah(employee.ptkp_karyawan)}`);
                    $("#jumlah_cuti_ijin").text(`${formatRupiah(employee.jumlah_cuti_ijin)}`);
                    $("#sisa_cuti").text(`${formatRupiah(employee.sisa_cuti)}`);

                },
                error: function(err) {}
            });
        }

        function fetchSalary() {
            $.ajax({
                url: "{{ route('api.payroll.fetchSalary') }}",
                method: 'GET',
                data: {
                    month_filter: $("#month_filter").val(),
                    employee_id : $('#employees').val(),
                },
                beforeSend: function() {
                    // empty view
                },
                success: function(responses) {
                    // console.info(responses);
                    const data = responses.data;

                    $("#jumlah_gaji_dasar").text("1");
                    $("#nominal_gaji_dasar").text(`Rp ${formatRupiah(data.pendapatan_gaji_dasar)}`);
                    $("#jumlah_tunjangan_tetap").text("1");
                    $("#nominal_tunjangan_tetap").text(`Rp ${formatRupiah(data.pendapatan_tunjangan_tetap)}`);
                    $("#jumlah_uang_makan").text(formatRupiah(data.jumlah_hari_tunjangan_makan));
                    $("#nominal_uang_makan").text(`Rp ${formatRupiah(data.pendapatan_uang_makan)}`);
                    $("#jumlah_lembur").text(formatRupiah(data.jumlah_jam_rate_lembur));
                    $("#nominal_lembur").text(`Rp ${formatRupiah(data.pendapatan_lembur)}`);
                    $("#nominal_tambahan_lain_lain").text(`Rp ${formatRupiah(data.pendapatan_tambahan_lain_lain)}`);
                    $("#jumlah_pendapatan_kotor").text(`Rp ${formatRupiah(data.jumlah_pendapatan)}`);


                    $("#nominal_bpjs_dibayar_karyawan").text(`Rp ${formatRupiah(data.pemotongan_bpjs_dibayar_karyawan)}`);
                    $("#nominal_pajak_penghasilan_pph21").text(`Rp ${formatRupiah(data.pemotongan_pph_dua_satu)}`);
                    $("#nominal_potongan_lain_lain").text(`Rp ${formatRupiah(data.pemotongan_potongan_lain_lain)}`);
                    $("#jumlah_potongan").text(`Rp ${formatRupiah(data.jumlah_pemotongan)}`);
                    $("#gaji_bersih").text(`Rp ${formatRupiah(data.gaji_bersih)}`);


                    ////////////////

                    $("#gaji_kotor_potongan").text(`Rp ${formatRupiah(data.pajak_gaji_kotor_kurang_potongan)}`);
                    $("#bpjs_dibayar_perusahaan").text(`Rp ${formatRupiah(data.pajak_bpjs_dibayar_perusahaan)}`);
                    $("#total_penghasilan_kotor").text(`Rp ${formatRupiah(data.pajak_total_penghasilan_kotor)}`);
                    $("#biaya_jabatan").text(`Rp ${formatRupiah(data.pajak_biaya_jabatan)}`);
                    $("#bpjs_dibayar_karyawan").text(`Rp ${formatRupiah(data.pajak_bpjs_dibayar_karyawan)}`);
                    $("#jumlah_pengurangan").text(`Rp ${formatRupiah(data.pajak_total_pengurang)}`);
                    $("#gaji_bersih_setahun").text(`Rp ${formatRupiah(data.pajak_gaji_bersih_setahun)}`);
                    $("#pkp_setahun").text(`Rp ${formatRupiah(data.pkp_setahun)}`);

                    $("#pkp_lima_persen").text(`Rp ${formatRupiah(data.pkp_lima_persen)}`);
                    $("#pkp_lima_belas_persen").text(`Rp ${formatRupiah(data.pkp_lima_belas_persen)}`);
                    $("#pkp_dua_puluh_lima_persen").text(`Rp ${formatRupiah(data.pkp_dua_puluh_lima_persen)}`);
                    $("#pkp_tiga_puluh_persen").text(`Rp ${formatRupiah(data.pkp_tiga_puluh_persen)}`);

                    $("#pajak_pph_dua_satu_setahun").text(`Rp ${formatRupiah(data.pajak_pph_dua_satu_setahun)}`);

                    





                },
                error: function(err) {}
            });
        }

        function fetchBpjs() {
            const contentJaminanSosial = $("#content-jaminan-sosial");

            $.ajax({
                url: "{{ route('api.payroll.fetchBpjs') }}",
                method: 'GET',
                data: {
                    month_filter: $("#month_filter").val(),
                    employee_id : $('#employees').val(),
                },
                beforeSend: function() {
                    // empty view

                    contentJaminanSosial.empty();
                },
                success: function(responses) {
                    // console.info(responses);

                    const data = responses.data;
                    const jaminanSosial = responses.jaminanSosial;

                    let dataJaminanSosial = "";

                    $("#dasar_upah_bpjs_tk").text(`Rp ${data.dasar_upah_bpjs_tk}`);
                    $("#dasar_upah_bpjs_kesehatan").text(`Rp ${data.dasar_upah_bpjs_kesehatan}`);

                    jaminanSosial.map((data, index) => {
                        dataJaminanSosial += `
                            <tr class="bpjs-row">
                                <td>${index + 1}</td>
                                <td>${data.nama}</td>
                                <td>${data.perusahaan_persen} %</td>
                                <td>${data.karyawan_persen} %</td>
                                <td>Rp ${data.perusahaan_nominal}</td>
                                <td>Rp ${data.karyawan_nominal}</td>
                            </tr>
                        `
                    });

                    dataJaminanSosial += `
                        <tr id="space-content-total" style="height: 15px;">
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="2">Total</td>
                            <td>11,74%</td>
                            <td>4,00%</td>
                            <td>Rp 398.516</td>
                            <td>Rp 135.781</td>
                        </tr>
                    `;

                    contentJaminanSosial.append(dataJaminanSosial);

                },
                error: function(err) {}
            });
        }

        

        function onCreate() {
            clearForm();
            $("#titleForm").html("Tambah Fitur");
            onModalAction("formModal", "show");
        }

        function setupDateFilter() {
            new Litepicker({
                element: document.getElementById('month_filter'),
                format: 'YYYY-MM',
                singleMode: true,
                tooltipText: {
                    one: 'night',
                    other: 'nights'
                },
                tooltipNumber: (totalDays) => {
                    return totalDays - 1;
                },
            });
        }

        function setupSelect() {
            $(".select2").select2();
        }
    </script>
@endsection
