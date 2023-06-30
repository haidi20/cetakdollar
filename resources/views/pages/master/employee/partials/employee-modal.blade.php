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
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="salary-tab" data-bs-toggle="tab" href="#salary" role="tab"
                                aria-controls="salary" aria-selected="false">Data Gaji & Rekening</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="finger-tab" data-bs-toggle="tab" href="#finger" role="tab"
                                aria-controls="finger" aria-selected="false">Data Finger</a>
                        </li>
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
                                    <input type="text" id="birth_date" name="birth_date" class="form-control" readonly
                                        style="cursor: pointer;">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="phone" class="col-sm-4 col-form-label">Telepon</label>
                                <div class="col-sm-8">
                                    <input type="number" id="phone" name="phone" class="form-control">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="religion" class="col-sm-4 col-form-label">Agama </label>
                                <div class="col-sm-8">
                                    <select id="religion" name="religion" class="select2 form-select"
                                        style="width: 100%">
                                        <option value="">-- Pilih Agama --</option>
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
                            <div class="form-group row">
                                <label for="photo" class="col-sm-4 col-form-label">Foto</label>
                                <div class="col-sm-8">
                                    <input class="form-control" type="file" id="photo" name="photo">
                                    <label id="photoPreview"></label>
                                    <label id="photoPreviewReady" width="50%"></label>
                                </div>
                            </div>
                        </div>

                        {{-- DATA KEPEGAWAIAN --}}
                        <div class="tab-pane fade" id="kepegawaian" role="tabpanel" aria-labelledby="kepegawaian-tab">
                            <div class="form-group row">
                                <label for="enter_date" class="col-sm-4 col-form-label">Tanggal Masuk</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control datepicker" id="enter_date" name="enter_date"
                                        readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="npwp" class="col-sm-4 col-form-label">NPWP</label>
                                <div class="col-sm-8">
                                    <input type="number" class="form-control" id="npwp" name="npwp">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="no_bpjs" class="col-sm-4 col-form-label">Nomor BPJS</label>
                                <div class="col-sm-8">
                                    <input type="number" class="form-control" id="no_bpjs" name="no_bpjs">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="company_id" class="col-sm-4 col-form-label">Perusahaan</label>
                                <div class="col-sm-8">
                                    <select name="company_id" id="company_id" class="form-control select2"
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
                                <label for="position_id" class="col-sm-4 col-form-label">Jabatan</label>
                                <div class="col-sm-8">
                                    <select name="position_id" id="position_id" class="form-control select2"
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
                                <label for="location_id" class="col-sm-4 col-form-label"> Lokasi Karyawan
                                </label>
                                <div class="col-sm-8">
                                    <select id="location_id" name="location_id" class="select2 form-select"
                                        style="width: 100%">
                                        <option value="">-- Pilih Lokasi Karyawan --</option>
                                        @foreach ($locations as $location)
                                        <option value="{{ $location->id }}">{{ $location->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="employee_type_id" class="col-sm-4 col-form-label"> Jenis Karyawan
                                </label>
                                <div class="col-sm-8">
                                    <select id="employee_type_id" name="employee_type_id" class="select2 form-select"
                                        style="width: 100%">
                                        <option value="">-- Pilih Jenis Karyawan --</option>
                                        @foreach ($employee_types as $employee_type)
                                        <option value="{{ $employee_type->id }}">{{ $employee_type->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row" id="contract_range_row">
                                <label for="contract_range" id="titleContactRange"
                                    class="col-sm-4 col-form-label"></label>
                                <div class="col-sm-8">
                                    <div class="input-group input-daterange" id="contract_range">
                                        <input type="text" class="form-control" name="contract_start"
                                            id="contract_start" autocomplete="off">
                                        <div class="input-group-addon" style="padding-right: 8px;">S/D</div>
                                        <input type="text" class="form-control" name="contract_end" id="contract_end"
                                            autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="latest_education" class="col-sm-4 col-form-label">Pendidikan
                                    Terakhir</label>
                                <div class="col-sm-8">
                                    <select name="latest_education" id="latest_education" class="form-control select2"
                                        style="width: 100%;">
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
                                <label for="working_hour" class="col-sm-4 col-form-label">Jam Kerja Karyawan</label>
                                <div class="col-sm-8">
                                    <select name="working_hour" id="working_hour" class="form-control select2"
                                        style="width: 100%;">
                                        <option value="">-- Pilih Jam Kerja Karyawan --</option>
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
                                <label for="married_status" class="col-sm-4 col-form-label">Status Nikah</label>
                                <div class="col-sm-8">
                                    <select name="married_status" id="married_status" class="form-control select2"
                                        style="width: 100%;">
                                        <option value="">-- Pilih Status Nikah --</option>
                                        <option value="sudah_menikah">Sudah Menikah</option>
                                        <option value="belum_menikah">Belum Menikah</option>
                                        <option value="janda">Janda</option>
                                        <option value="duda">Duda</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="bpjs" class="col-sm-4 col-form-label">Status BPJS</label>
                                <div class="col-sm-8">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label for="bpjs_tk" class="col-form-label">TK</label>
                                            <div class="form-check form-switch">
                                                <label class="switch form-check-label" title="Aktif / Non-Aktif : IP"
                                                    for="flexSwitchCheckChecked">
                                                    <input type="checkbox" name="bpjs_tk"
                                                        class="form-check-input bpjsTKCheck" data-toggle="toggle"
                                                        id="bpjs_tk" data-off="Disabled" data-on="Enabled">
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="bpjs_tk_pt" class="col-form-label">TK PT</label>
                                            <div class="form-check form-switch">
                                                <label class="switch form-check-label" title="Aktif / Non-Aktif : IP"
                                                    for="flexSwitchCheckChecked">
                                                    <input type="checkbox" name="bpjs_tk_pt"
                                                        class="form-check-input bpjsTKPTCheck" data-toggle="toggle"
                                                        id="bpjs_tk_pt" data-off="Disabled" data-on="Enabled">
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="bpjs_kes" class="col-form-label">KES</label>
                                            <div class="form-check form-switch">
                                                <label class="switch form-check-label" title="Aktif / Non-Aktif : IP"
                                                    for="flexSwitchCheckChecked">
                                                    <input type="checkbox" name="bpjs_kes"
                                                        class="form-check-input bpjsKESCheck" data-toggle="toggle"
                                                        id="bpjs_kes" data-off="Disabled" data-on="Enabled">
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="bpjs_kes_pt" class="col-form-label">KES PT</label>
                                            <div class="form-check form-switch">
                                                <label class="switch form-check-label" title="Aktif / Non-Aktif : IP"
                                                    for="flexSwitchCheckChecked">
                                                    <input type="checkbox" name="bpjs_kes_pt"
                                                        class="form-check-input bpjsKESPTCheck" data-toggle="toggle"
                                                        id="bpjs_kes_pt" data-off="Disabled" data-on="Enabled">
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="bpjs_training" class="col-form-label">TRAINING</label>
                                            <div class="form-check form-switch">
                                                <label class="switch form-check-label" title="Aktif / Non-Aktif : IP"
                                                    for="flexSwitchCheckChecked">
                                                    <input type="checkbox" name="bpjs_training"
                                                        class="form-check-input bpjsTRAININGCheck" data-toggle="toggle"
                                                        id="bpjs_training" data-off="Disabled" data-on="Enabled">
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row">
                                <label for="employee_status" class="col-sm-4 col-form-label">Status Pegawai</label>
                                <div class="col-sm-8">
                                    <select name="employee_status" id="employee_status" class="form-control select2"
                                        style="width: 100%;">
                                        <option value="">-- Pilih Status --</option>
                                        <option value="aktif">Aktif</option>
                                        <option value="tidak_aktif">Tidak Aktif</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row" id="reason_row">
                                <label for="reason" class="col-sm-4 col-form-label">Alasan Tidak Aktif</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="reason" name="reason">
                                </div>
                            </div>
                            <div class="form-group row" id="out_date_row">
                                <label for="out_date" class="col-sm-4 col-form-label">Tanggal Keluar</label>
                                <div class="col-sm-8">
                                    <input type="text" id="out_date" name="out_date" class="form-control" readonly
                                        style="cursor: pointer;">
                                </div>
                            </div>
                        </div>

                        {{-- DATA GAJI DAN REKENING --}}
                        <div class="tab-pane fade" id="salary" role="tabpanel" aria-labelledby="salary-tab">
                            <div class="form-group row">
                                <label for="basic_salary" class="col-sm-4 col-form-label">Gaji Pokok</label>
                                <div class="col-sm-8">
                                    <input type="number" id="basic_salary" name="basic_salary" min="0" class="form-control">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="allowance" class="col-sm-4 col-form-label">Uang Saku</label>
                                <div class="col-sm-8">
                                    <input type="number" id="allowance" name="allowance" min="0" class="form-control">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="meal_allowance_per_attend" class="col-sm-4 col-form-label">Tunjangan Makan Per Hadir</label>
                                <div class="col-sm-8">
                                    <input type="number" id="meal_allowance_per_attend" name="meal_allowance_per_attend" min="0" class="form-control">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="transport_allowance_per_attend" class="col-sm-4 col-form-label">Tunjangan Transport Per Hadir</label>
                                <div class="col-sm-8">
                                    <input type="number" id="transport_allowance_per_attend" name="transport_allowance_per_attend" min="0" class="form-control">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="attend_allowance_per_attend" class="col-sm-4 col-form-label">Tunjangan Kehadiran Per Hadir</label>
                                <div class="col-sm-8">
                                    <input type="number" id="attend_allowance_per_attend" name="attend_allowance_per_attend" min="0" class="form-control">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="overtime_rate_per_hour" class="col-sm-4 col-form-label">Tunjangan Lembur Per Jam</label>
                                <div class="col-sm-8">
                                    <input type="number" id="overtime_rate_per_hour" name="overtime_rate_per_hour" min="0" class="form-control">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="vat_per_year" class="col-sm-4 col-form-label">PPN Per Tahun</label>
                                <div class="col-sm-8">
                                    <input type="number" id="vat_per_year" name="vat_per_year" min="0" class="form-control">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="rekening_number" class="col-sm-4 col-form-label">Nomor Rekening</label>
                                <div class="col-sm-8">
                                    <input type="number" id="rekening_number" name="rekening_number"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="rekening_name" class="col-sm-4 col-form-label">Nama Rekening</label>
                                <div class="col-sm-8">
                                    <input type="number" id="rekening_name" name="rekening_name" class="form-control">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="bank_name" class="col-sm-4 col-form-label">Nama Bank </label>
                                <div class="col-sm-8">
                                    <select id="bank_name" name="bank_name" class="select2 form-select"
                                        style="width: 100%">
                                        <option value="">-- Pilih Bank --</option>
                                        <option value="bca">BCA</option>
                                        <option value="bri">BRI</option>
                                        <option value="mandiri">MANDIRI</option>
                                        <option value="bsi">BSI</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="branch" class="col-sm-4 col-form-label">Cabang Bank</label>
                                <div class="col-sm-8">
                                    <input type="text" id="branch" name="branch" class="form-control">
                                </div>
                            </div>
                        </div>

                        {{-- DATA FINGER --}}
                        <div class="tab-pane fade" id="finger" role="tabpanel" aria-labelledby="finger-tab">
                            <div class="form-group row">
                                <label for="finger_tool_id" class="col-sm-4 col-form-label">Pilih</label>
                                <div class="col-sm-8">
                                    <select id="finger_tool_id" name="finger_tool_id" class="select2 form-select" style="width: 100%">
                                        <option value="">-- Pilih Lokasi Absen --</option>
                                        @foreach ($finger_tools as $finger_tool)
                                        <option value="{{ $finger_tool->id }}">{{ $finger_tool->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="id_finger" class="col-sm-4 col-form-label">ID Finger</label>
                                <div class="col-sm-8">
                                    <input type="text" id="id_finger" name="id_finger" class="form-control">
                                </div>
                            </div>

                            {{-- <div class="form-group row">
                                <div class="col-sm-12 float-end">
                                    <button id="addFingerButton" class="btn btn-primary">Tambah Finger</button>
                                </div>
                            </div> --}}

                            <table id="fingerTable" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Lokasi Finger</th>
                                        <th>ID Finger</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="fingerTableBody"></tbody>
                            </table>
                            {{-- <div class="form-group row">
                                <label for="finger_doc_1" class="col-sm-4 col-form-label">Nomor ID di Finger Doc
                                    1</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control datepicker" id="finger_doc_1" name="finger_doc_1">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="finger_doc_2" class="col-sm-4 col-form-label">Nomor ID di Finger Doc
                                    2</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control datepicker" id="finger_doc_2" name="finger_doc_2">
                                </div>
                            </div> --}}
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
