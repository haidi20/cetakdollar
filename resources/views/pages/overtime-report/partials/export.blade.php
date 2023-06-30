@php
    use Carbon\Carbon;
@endphp

<table>
    <tr>
        <td colspan="7" style="font-size: 28px; font-weight: bold; text-align: center; vertical-align: middle;">
            {{-- <img src="{{ public_path('/assets/img/logo.png') }}" alt="" class="" width="80%"> --}}
            <h3 style="margin-top: 10px; padding-left: 30px;">PT KARYA PACIFIC TEKNIK SHIPYARD</h3>
        </td>
    </tr>
    {{-- <tr></tr> --}}
    <thead>
        <tr>
            <th nowrap>Nama Karyawan</th>
            <th nowrap>Jabatan</th>
            <th nowrap>Pekerjaan</th>
            <th nowrap>Waktu Mulai</th>
            <th nowrap>Waktu Selesai</th>
            <th nowrap>Durasi</th>
            <th nowrap>Catatan</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $index => $item)
            <tr>
                <td>{{ $item['employee_name'] }}</td>
                <td>{{ $item['position_name'] }}</td>
                <td>{{ $item['job_name'] }}</td>
                <td>{{ $item['datetime_start_readable'] }}</td>
                <td>{{ $item['datetime_end_readable'] }}</td>
                <td>{{ $item['duration_readable'] }}</td>
                <td>{{ $item['note_start'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
