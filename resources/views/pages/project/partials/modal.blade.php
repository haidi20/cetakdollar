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
                        <label for="name" class="col-sm-4 col-form-label">Nama Proyek </label>
                        <div class="col-sm-8">
                            <input type="text" id="name" name="name" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="barge_id" class="col-sm-4 col-form-label">Kapal </label>
                        <div class="col-sm-8">
                            <select name="barge_id" id="barge_id" class="form-control select2" style="width: 100%">
                                <option value="">Kapal A</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="type" class="col-sm-4 col-form-label">Jenis Proyek </label>
                        <div class="col-sm-8">
                            <select name="type" id="type" class="form-control select2" style="width: 100%">
                                <option value="">Harian</option>
                                <option value="">Borongan</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="supervisor_id" class="col-sm-4 col-form-label">Kepala Pemborong </label>
                        <div class="col-sm-8">
                            <select name="supervisor_id" id="supervisor_id" class="form-control select2"
                                style="width: 100%">
                                <option value="">Rudi</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="foreman_id" class="col-sm-4 col-form-label">Pengawas </label>
                        <div class="col-sm-8">
                            <select name="foreman_id" id="foreman_id" class="form-control select2" style="width: 100%">
                                <option value="">Samsu</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="company_id" class="col-sm-4 col-form-label">Perusahaan </label>
                        <div class="col-sm-8">
                            <select name="company_id" id="company_id" class="form-control select2" style="width: 100%">
                                <option value="">PT. Maju Jaya</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="work_type" class="col-sm-4 col-form-label">Jenis Pekerjaan </label>
                        <div class="col-sm-8">
                            <select name="work_type" id="work_type" class="form-control select2" style="width: 100%">
                                <option value="">Produksi (pembuatan dari awal)</option>
                                <option value="">Maintenance (Perbaikan)</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="date_end" class="col-sm-4 col-form-label">Tanggal Selesai </label>
                        <div class="col-sm-8">
                            <input type="date" id="date_end" name="date_end" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="price" class="col-sm-4 col-form-label">Biaya </label>
                        <div class="col-sm-8">
                            <input type="text" id="price" name="price" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="down_payment" class="col-sm-4 col-form-label">DP (Down Payment) </label>
                        <div class="col-sm-8">
                            <input type="text" id="down_payment" name="down_payment" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="remaining_payment" class="col-sm-4 col-form-label">Sisa Yang Dibayarkan</label>
                        <div class="col-sm-8">
                            <input type="text" id="remaining_payment" name="remaining_payment"
                                class="form-control" disabled>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="note" class="col-sm-4 col-form-label">Keterangan </label>
                        <div class="col-sm-8">
                            <input type="text" id="note" name="note" class="form-control">
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
