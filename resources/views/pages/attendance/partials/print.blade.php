@php
use Carbon\Carbon;
@endphp
<html>

<head>
    <title>Print Absensi Karyawan</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <style>
        table {
            border-collapse: collapse;
            page-break-inside: auto;
            width: 100%;
        }

        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }

        thead {
            display: table-header-group;
        }

        th,
        td {
            border: black 1px solid;
            padding-left: 5px;
            padding-right: 5px;
            min-width: 50px;
        }

        @page {
            size: legal landscape;
            margin: 1cm;
        }

        #logo {
            width: 7rem;
            margin-bottom: 1rem;
            vertical-align: middle;
        }

        #judul {
        text-align: center;
        }

        header {
        /* position: fixed; */
        text-align: center;
        height: 10px;
        margin-bottom: 30px;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
        }

        .header span {
            font-size: 18px;
        }

        .info {
            margin-bottom: 1rem;
        }

        .info span {
            display: block;
            margin-bottom: 0.5rem;
        }
    </style>
</head>

<body onload="window.print()">
    {{-- <div class="header">
        <img src="{{ asset('assets/img/logo.png') }}" id="logo" alt="">
        <span>CV. KARYA PACIFIC TEHNIK</span>
    </div> --}}

    <div style="display: flex; align-items: center; text-align: center; margin-left: 230px;">
        <img src="{{ asset('assets/img/logo.png') }}" alt="" class="" style="width: 20%; margin-right: 50px;">
        <div>
            <span style="font-size: 32px; color: red; font-weight: bold; text-align: center;">PT. KARYA PACIFIC TEHNIK</span>
            <hr>
            <span>
                Jalan Raya Anggana KM.20 RT.17, Sungai Mariam, Anggana, Samarinda, Kaltim - Indonesia
                <br>
                Jalan OLAH BEBAYA RT.03, KEL. Pulau Atas, Samarinda, Kaltim - Indonesia
                <br>
                Tel. 0811-555-882
                <br>
                karyapacifictehnik@yahoo.com
                <br>
                www.karyapacifictehnik.com
            </span>
        </div>
    </div>
    <br>
    <br>

    <hr>

    <div class="info">
        <span>Nama : {{ $employee->name }}</span>
        <span>Jabatan : {{ $employee->position_name }}</span>
    </div>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Hari</th>
                <th>Jam Masuk</th>
                <th>Jam Pulang</th>
                <th>Durasi</th>
                <th>Jam Istirahat</th>
                <th>Jam Selesai Istirahat</th>
                <th>Durasi Jam Istirahat</th>
                <th>Jam Lembur</th>
                <th>Jam Selesai Lembur</th>
                <th>Durasi Jam Lembur</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $index => $item)
            <tr>
                <td>{{ $item->date }}</td>
                <td>{{ $item->day }}</td>
                @if ($item->is_exists)
                <td>{{ $item->hour_start }}</td>
                <td>{{ $item->hour_end }}</td>
                <td>{{ $item->duration_work }}</td>
                <td>{{ $item->hour_rest_start }}</td>
                <td>{{ $item->hour_rest_end }}</td>
                <td>{{ $item->duration_rest }}</td>
                <td>{{ $item->hour_overtime_start }}</td>
                <td>{{ $item->hour_overtime_end }}</td>
                <td>{{ $item->duration_overtime }}</td>
                @else
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
