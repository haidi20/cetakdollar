<!DOCTYPE html>
<html lang="en">

<table>
    <tr>
        <td colspan="7" style="font-size: 28px; font-weight: bold; text-align: center; vertical-align: middle;">
            {{-- <img src="{{ public_path('/assets/img/logo.png') }}" alt="" class="" width="80%"> --}}
            <h3 style="margin-top: 10px; padding-left: 50px;">PT KARYA PACIFIC TEKNIK SHIPYARD</h3>
        </td>
    </tr>
    <thead>
        <tr>
            <th>Di Buat Oleh</th>
            <th>Nama Karyawan</th>
            <th>Jabatan</th>
            <th>Tanggal Mulai</th>
            <th>Tanggal Selesai</th>
            <th>Jangka Waktu</th>
            <th>Catatan</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $index => $item)
            <tr>
                <td>{{ $item['creator_name'] }}</td>
                <td>{{ $item['employee_name'] }}</td>
                <td>{{ $item['position_name'] }}</td>
                <td>{{ $item['date_start_readable'] }}</td>
                <td>{{ $item['date_end_readable'] }}</td>
                <td>{{ $item['duration_readable'] }}</td>
                <td>{{ $item['note'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
