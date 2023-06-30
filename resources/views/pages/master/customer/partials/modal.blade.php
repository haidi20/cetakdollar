<div class="modal fade bd-example-modal-lg" id="formModal" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
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
                    <div class="form-group row">
                        <label for=code class="col-sm-4 col-form-label">Kode Pelanggan</label>
                        <div class="col-sm-8">
                            <input type="text" id="code-new" name="code" class="form-control" readonly>
                            <input type="text" id="code-last" name="code" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="name" class="col-sm-4 col-form-label">Nama Perusahaan Pelanggan</label>
                        <div class="col-sm-8">
                            <input type="text" id="name" name="name" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="address" class="col-sm-4 col-form-label">Alamat Pelanggan</label>
                        <div class="col-sm-8">
                            <input type="text" id="address" name="address" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="terms" class="col-sm-4 col-form-label">Ketentuan Pelanggan</label>
                        <div class="col-sm-8">
                            <input type="text" id="terms" name="terms" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="credit_limits" class="col-sm-4 col-form-label">Batas Kredit Pelanggan</label>
                        <div class="col-sm-8">
                            <input type="number" id="credit_limits" name="credit_limits" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="contact_person" class="col-sm-4 col-form-label">Kontak Person Pelanggan</label>
                        <div class="col-sm-8">
                            <input type="text" id="contact_person" name="contact_person" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="handphone" class="col-sm-4 col-form-label">Nomor Handphone Pelanggan</label>
                        <div class="col-sm-8">
                            <input type="number" id="handphone" name="handphone" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="telepon" class="col-sm-4 col-form-label">Nomor Telepon Perusahaan Pelanggan</label>
                        <div class="col-sm-8">
                            <input type="number" id="telepon" name="telepon" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="company_id" class="col-sm-4 col-form-label">Perusahaan </label>
                        <div class="col-sm-8">
                            <select id="company_id" name="company_id" class="select2 form-select" style="width: 100%">
                                <option value="">Pilih Perusahaan</option>
                                @foreach ($companies as $company)
                                <option value="{{ $company->id }}">{{ $company->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="barge_id" class="col-sm-4 col-form-label">Kapal </label>
                        <div class="col-sm-8">
                            <select id="barge_id" name="barge_id" class="select2 form-select" style="width: 100%">
                                <option value="">Pilih Kapal</option>
                                @foreach ($barges as $barge)
                                <option value="{{ $barge->id }}">{{ $barge->name }}
                                </option>
                                @endforeach
                            </select>
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
