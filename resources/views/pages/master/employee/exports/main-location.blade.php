<table class="table" width="100%">
    <tr>
        <td colspan="8" style="font-size: 28px; font-weight: bold; text-align: center; vertical-align: middle;">
            {{-- <img src="{{ public_path('/assets/img/logo.png') }}" alt="" class="" width="80%"> --}}
            <h3 style="margin-top: 10px; padding-left: 30px;">PT KARYA PACIFIC TEKNIK SHIPYARD</h3>
        </td>
    </tr>
    <tr></tr>
    <tr>
        <td colspan="8" style="font-size: 20px; text-align: center; vertical-align: middle;">Laporan Pegawai Perusahaan : {{ $employees->first()->location->name }}</td>
    </tr>
    <tr></tr>
    <thead>
        <tr>
            <td class="isi font-kecil">No.</td>
            <td class="isi font-kecil" width="50px">NIP</td>
            <td class="isi font-kecil" width="100px">Nama Pegawai</td>
            <td class="isi font-kecil">Telepon</td>
            <td class="isi font-kecil">Perusahaan</td>
            <td class="isi font-kecil">Jabatan</td>
            <td class="isi font-kecil">Lokasi</td>
            <td class="isi font-kecil">Status Pegawai</td>
        </tr>
        @foreach ($employees as $employee)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $employee->nip }}</td>
            <td>{{ $employee->name }}</td>
            <td>{{ $employee->phone }}</td>
            <td>{{ $employee->company->name }}</td>
            <td>{{ $employee->position->name }}</td>
            <td>{{ $employee->location->name ?? '' }}</td>
            <td>{{ $employee->employee_status == 'aktif' ? 'AKTIF' : 'TIDAK AKTIF' }}</td>
        </tr>
        @endforeach
    </thead>
</table>
