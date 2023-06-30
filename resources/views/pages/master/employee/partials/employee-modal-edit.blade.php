<div class="modal fade bd-example-modal-lg" id="formModal" role="dialog" aria-labelledby="addModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">

        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleForm"></h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="id" name="id" class="form-control">

                    <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="personal-tab" data-bs-toggle="tab" href="#personal"
                                role="tab" aria-controls="personal" aria-selected="true">Data Personal</a>
                        </li>
                        <li class="nav-item" role="presentation" id="tab-kepegawaian">
                            <a class="nav-link" id="kepegawaian-tab" data-bs-toggle="tab" href="#kepegawaian" role="tab"
                                aria-controls="kepegawaian" aria-selected="false">Data Kepegawaian</a>
                        </li>
                        {{-- <li class="nav-item" role="presentation" id="tab-keluarga">
                            <a class="nav-link" id="keluarga-tab" data-bs-toggle="tab" href="#keluarga" role="tab"
                                aria-controls="keluarga" aria-selected="false">Data Keluarga</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="finger-tab" data-bs-toggle="tab" href="#finger" role="tab"
                                aria-controls="finger" aria-selected="false">Data Alat Finger</a>
                        </li>
                        {{-- <li class="nav-item" role="presentation">
                            <a class="nav-link" id="rekening-tab" data-bs-toggle="tab" href="#rekening" role="tab"
                                aria-controls="rekening" aria-selected="false">Data Rekening</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="dokumen-tab" data-bs-toggle="tab" href="#dokumen" role="tab"
                                aria-controls="dokumen" aria-selected="false">Data Dokumen</a>
                        </li> --}}
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        {{-- DATA PERSONAL --}}
                        <div class="tab-pane fade show active" id="personal" role="tabpanel"
                            aria-labelledby="personal-tab">
                            <div class="form-group row">
                                <label for="nip" class="col-sm-4 col-form-label">NIP</label>
                                <div class="col-sm-8">
                                    <input type="number" id="nip" name="nip" class="form-control">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nik" class="col-sm-4 col-form-label">NIK</label>
                                <div class="col-sm-8">
                                    <input type="number" id="nik" name="nik" class="form-control">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="name" class="col-sm-4 col-form-label">Nama Lengkap</label>
                                <div class="col-sm-8">
                                    <input type="text" id="name" name="name" class="form-control">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="birth_place" class="col-sm-4 col-form-label">Tempat Lahir</label>
                                <div class="col-sm-8">
                                    <input type="text" id="birth_place" name="birth_place" class="form-control">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="birth_date" class="col-sm-4 col-form-label">Tanggal Lahir</label>
                                <div class="col-sm-8">
                                    <input type="date" id="birth_date" name="birth_date" class="form-control">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="phone" class="col-sm-4 col-form-label">Telepon</label>
                                <div class="col-sm-8">
                                    <input type="text" id="phone" name="phone" class="form-control">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="religion" class="col-sm-4 col-form-label">Agama </label>
                                <div class="col-sm-8">
                                    <select id="religion" name="religion" class="select2 form-select"
                                        style="width: 100%">
                                        <option value="">Pilih Agama</option>
                                        <option value="islam">Islam
                                        </option>
                                        <option value="kristen">
                                            Kristen</option>
                                        <option value="katholik">Katholik</option>
                                        <option value="hindu">Hindu
                                        </option>
                                        <option value="budha">Budha
                                        </option>
                                        <option value="konghucu">Konghucu</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="address" class="col-sm-4 col-form-label">Alamat</label>
                                <div class="col-sm-8">
                                    {{-- <input type="text" id="address" name="address" class="form-control"> --}}
                                    <textarea name="address" id="address" cols="60" rows="5"
                                        class="form-control"></textarea>
                                </div>
                            </div>
                        </div>

                        {{-- DATA KEPEGAWAIAN --}}
                        <div class="tab-pane fade" id="kepegawaian" role="tabpanel" aria-labelledby="kepegawaian-tab">
                            <div class="form-group row">
                                <label for="company" class="col-sm-4 col-form-label">Perusahaan</label>
                                <div class="col-sm-8">
                                    <select name="company" id="company" class="form-control select2"
                                        style="width: 100%;">
                                        <option value="">-- Pilih Perusahaan --</option>
                                        @foreach ($companies as $company)
                                        <option value="{{ $company->id }}">
                                            {{ $company->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="departmen" class="col-sm-4 col-form-label">Departemen</label>
                                <div class="col-sm-8">
                                    <select name="departmen" id="departmen" class="form-control select2"
                                        style="width: 100%;">
                                        <option value="">-- Pilih Departemen --</option>
                                        @foreach ($departmens as $departmen)
                                        <option value="{{ $departmen->id }}">{{ $departmen->company->name }} -
                                            {{ $departmen->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="position" class="col-sm-4 col-form-label">Jabatan</label>
                                <div class="col-sm-8">
                                    <select name="position" id="position" class="form-control select2"
                                        style="width: 100%;">
                                        <option value="">-- Pilih Jabatan --</option>
                                        @foreach ($positions as $position)
                                        <option value="{{ $position->id }}">{{ $position->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="agama" class="col-sm-4 col-form-label"> Jenis Kepegawaian </label>
                                <div class="col-sm-8">
                                    <select id="agama" name="agama" class="select2 form-select" required
                                        style="width: 100%">
                                        <option value="6,1">
                                            6 - 1 (6 hari kerja dan 1 hari off)
                                        </option>
                                        <option value="5,2">
                                            5 - 2 (5 hari kerja dan 2 hari off)
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="tanggal_masuk" class="col-sm-4 col-form-label">Tanggal Masuk</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control datepicker" id="tanggal_masuk"
                                        name="tanggal_masuk">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="npwp" class="col-sm-4 col-form-label">NPWP</label>
                                <div class="col-sm-8">
                                    <input type="number" class="form-control" id="npwp" name="npwp">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="pendidikan_terakhir" class="col-sm-4 col-form-label">Pendidikan
                                    Terakhir</label>
                                <div class="col-sm-8">
                                    <select name="pendidikan_terakhir" id="pendidikan_terakhir"
                                        class="form-control select2" style="width: 100%;">
                                        <option value="">-- Pilih Pendidikan Terakhir --</option>
                                        <option value="sd">SD</option>
                                        <option value="smp">SMP</option>
                                        <option value="sma">SMA</option>
                                        <option value="d3">D3</option>
                                        <option value="s1">S1</option>
                                        <option value="s2">S2</option>
                                        <option value="s3">S3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="status_pegawai" class="col-sm-4 col-form-label">Jenis Karyawan</label>
                                <div class="col-sm-8">
                                    <select name="status_pegawai" id="status_pegawai" class="form-control select2"
                                        style="width: 100%;">
                                        <option value="">-- Pilih Status Pegawai --</option>
                                        {{-- @foreach ($status_pegawais as $status_pegawai)
                                                                                    <option value="{{ $status_pegawai->id }}"
                                        <?php if ($pegawai->status_pegawai_id == $status_pegawai->id) {
                                                                                            echo 'selected';
                                                                                        } ?>>
                                        {{ $status_pegawai->nama_status_pegawai }}
                                        </option>
                                        @endforeach --}}
                                        <option value="aktif">Tetap</option>
                                        <option value="aktif">Harian</option>
                                        <option value="aktif">Borongan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="kawin" class="col-sm-4 col-form-label">Status Nikah</label>
                                <div class="col-sm-8">
                                    <select name="kawin" id="kawin" class="form-control select2" style="width: 100%;">
                                        <option value="">-- Pilih Nikah --</option>
                                        <option value="aktif">Sudah Menikah</option>
                                        <option value="aktif">Belum Menikah</option>
                                        <option value="aktif">Janda</option>
                                        <option value="aktif">Duda</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="status" class="col-sm-4 col-form-label">Status Pegawai</label>
                                <div class="col-sm-8">
                                    <select name="status" id="status" class="form-control select2" style="width: 100%;">
                                        <option value="">-- Pilih Status --</option>
                                        <option value="aktif">Aktif</option>
                                        <option value="keluar">Keluar</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="tanggal_keluar" class="col-sm-4 col-form-label">Tanggal Keluar</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control datepicker" id="tanggal_keluar"
                                        name="tanggal_keluar" placeholder="Tanggal Keluar">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="bpjs">BPJS</label><br>
                                        <div class="form-check form-switch">
                                            <form>
                                                <label class="switch form-check-label" title="Aktif / Non-Aktif : BPJS"
                                                    for="flexSwitchCheckChecked">
                                                    <input type="checkbox" name="toggle"
                                                        class="form-check-input bpjsCheck" data-toggle="toggle"
                                                        id="flexSwitchCheckChecked" data-off="Disabled" data-target="1"
                                                        data-on="Enabled">
                                            </form>
                                            </label>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="jkk">BPJS TK CV</label><br>
                                        <div class="form-check form-switch">
                                            <form>
                                                <label class="switch form-check-label" title="Aktif / Non-Aktif : JKK"
                                                    for="flexSwitchCheckChecked">
                                                    <input type="checkbox" name="toggle"
                                                        class="form-check-input jkkCheck" data-toggle="toggle"
                                                        id="flexSwitchCheckChecked" data-off="Disabled" data-target="2"
                                                        data-on="Enabled">
                                            </form>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="jkm">BPJS KES</label><br>
                                        <div class="form-check form-switch">
                                            <form>
                                                <label class="switch form-check-label" title="Aktif / Non-Aktif : JKM"
                                                    for="flexSwitchCheckChecked">
                                                    <input type="checkbox" name="toggle"
                                                        class="form-check-input jkmCheck" data-toggle="toggle"
                                                        id="flexSwitchCheckChecked" data-off="Disabled" data-target="3"
                                                        data-on="Enabled">
                                            </form>
                                            </label>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="jht">BPJS KES CV</label><br>
                                        <div class="form-check form-switch">
                                            <form>
                                                <label class="switch form-check-label" title="Aktif / Non-Aktif : JHT"
                                                    for="flexSwitchCheckChecked">
                                                    <input type="checkbox" name="toggle"
                                                        class="form-check-input jhtCheck" data-toggle="toggle"
                                                        id="flexSwitchCheckChecked" data-off="Disabled" data-target="4"
                                                        data-on="Enabled">
                                            </form>
                                            </label>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="ip">Training</label><br>
                                        <div class="form-check form-switch">
                                            <form>
                                                <label class="switch form-check-label" title="Aktif / Non-Aktif : IP"
                                                    for="flexSwitchCheckChecked">
                                                    <input type="checkbox" name="toggle"
                                                        class="form-check-input ipCheck" data-toggle="toggle"
                                                        id="flexSwitchCheckChecked" data-off="Disabled" data-target="5"
                                                        data-on="Enabled">
                                            </form>
                                            </label>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- @else --}}

                        {{-- @endif --}}

                        <div class="tab-pane fade" id="finger" role="tabpanel" aria-labelledby="finger-tab">
                            <div class="form-group row">
                                <label for="tanggal_masuk" class="col-sm-4 col-form-label">Nomor ID di Finger Doc
                                    1</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control datepicker" id="tanggal_masuk"
                                        name="tanggal_masuk">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="tanggal_masuk" class="col-sm-4 col-form-label">Nomor ID di Finger Doc
                                    2</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control datepicker" id="tanggal_masuk"
                                        name="tanggal_masuk">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-success">Kirim</button>
                </div>
            </form>
        </div>
    </div>
</div>
