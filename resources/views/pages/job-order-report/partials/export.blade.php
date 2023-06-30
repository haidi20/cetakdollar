@php
    use Carbon\Carbon;
@endphp

<table>
    <tr>
        <td colspan="10" style="font-size: 28px; font-weight: bold; text-align: center; vertical-align: middle;">
            {{-- <img src="{{ public_path('/assets/img/logo.png') }}" alt="" class="" width="80%"> --}}
            <h3 style="margin-top: 10px; padding-left: 30px;">PT KARYA PACIFIC TEKNIK SHIPYARD</h3>
        </td>
    </tr>
    {{-- <tr></tr> --}}
    <thead>
        <tr>
            <th nowrap>Nama Pengawas</th>
            <th>Nama Proyek</th>
            <th>Nama Pekerjaan</th>
            <th>Catatan Pekerjaan</th>
            <th>Status</th>
            <th>Kategori</th>
            <th>Waktu Mulai</th>
            <th>Waktu Selesai</th>
            <th>Waktu Estimasi Selesai</th>
            <th>Catatan</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $index => $item)
            <tr>
                <td>{{ $item['creator_name'] }}</td>
                <td>{{ $item['project_name'] }}</td>
                <td>{{ $item['job_name'] }}</td>
                <td>{{ $item['job_note'] }}</td>
                <td>{{ $item['status_readable'] }}</td>
                <td>{{ $item['category_name'] }}</td>
                <td>{{ $item['datetime_start_readable'] }}</td>
                <td>{{ $item['datetime_end_readable'] }}</td>
                <td>{{ $item['datetime_estimation_end_readable'] }}</td>
                <td>{{ $item['note'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
