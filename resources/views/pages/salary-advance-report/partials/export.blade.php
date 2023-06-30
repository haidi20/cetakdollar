<!DOCTYPE html>
<html lang="en">

<table>
    <tr>
        <td colspan="9" style="font-size: 28px; font-weight: bold; text-align: center; vertical-align: middle;">
            {{-- <img src="{{ public_path('/assets/img/logo.png') }}" alt="" class="" width="80%"> --}}
            <h3 style="margin-top: 10px; padding-left: 30px;">PT KARYA PACIFIC TEKNIK SHIPYARD</h3>
        </td>
    </tr>
    <thead>
        <tr>
            <th>Nama Karyawan</th>
            <th>Jabatan</th>
            <th>Mengajukan</th>
            <th>Nominal</th>
            <th>Alasan</th>
            <th>Sisa Pinjaman Sebelumnya</th>
            <th>Selesai di Bulan</th>
            <th>Status</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $index => $item)
            <tr>
                <td>{{ $item['employee_name'] }}</td>
                <td>{{ $item['position_name'] }}</td>
                <td>{{ $item['creator_name'] }}</td>
                <td>{{ $item['loan_amount_readable'] }}</td>
                <td>{{ $item['reason'] }}</td>
                <td>{{ $item['remaining_debt_readable'] }}</td>
                <td>{{ $item['month_loan_complite_readable'] }}</td>
                <td>{{ $item['approval_status_readable'] }}</td>
                <td>{{ $item['approval_description'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
