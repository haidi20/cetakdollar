<table class="table" width="100%">
    <tr>
        <td colspan="8" style="font-size: 28px; font-weight: bold; text-align: center; vertical-align: middle;">
            {{-- <img src="{{ public_path('/assets/img/logo.png') }}" alt="" class="" width="80%"> --}}
            <h3 style="margin-top: 10px; padding-left: 30px;">PT KARYA PACIFIC TEKNIK SHIPYARD</h3>
        </td>
    </tr>
    <tr></tr>
    <tr>
        <td colspan="8" style="font-size: 20px; text-align: center; vertical-align: middle;">Laporan Pegawai Jabatan : {{ $employees->first()->position->name }}</td>
    </tr>
    <tr></tr>
    <thead>
        <tr>
            <td style="font-size: 16px; font-weight: bold;">No.</td>
            <td style="font-size: 16px; font-weight: bold;" width="100%">NIP</td>
            <td style="font-size: 16px; font-weight: bold;" width="100%">Nama Pegawai</td>
            <td style="font-size: 16px; font-weight: bold;">Telepon</td>
            <td style="font-size: 16px; font-weight: bold;">Perusahaan</td>
            <td style="font-size: 16px; font-weight: bold;">Jabatan</td>
            <td style="font-size: 16px; font-weight: bold;">Lokasi</td>
            <td style="font-size: 16px; font-weight: bold;">Status Pegawai</td>
        </tr>
        @foreach ($employees as $employee)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $employee->nip }}</td>
            <td>{{ $employee->name }}</td>
            <td>{{ $employee->phone ?? 'Data Masih Kosong'}}</td>
            <td>{{ $employee->company->name ?? 'Data Masih Kosong'}}</td>
            <td>{{ $employee->position->name ?? 'Data Masih Kosong'}}</td>
            <td>{{ $employee->location->name ?? 'Data Masih Kosong' }}</td>
            <td>{{ $employee->employee_status == 'aktif' ? 'AKTIF' : 'TIDAK AKTIF' }}</td>
        </tr>
        @endforeach
    </thead>
</table>
