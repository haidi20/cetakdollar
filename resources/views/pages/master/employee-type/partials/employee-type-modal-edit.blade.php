<div class="modal fade bd-example-modal-lg" id="formModalEdit" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
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
                        <label for="name" class="col-sm-4 col-form-label">NIP</label>
                        <div class="col-sm-8">
                            <input type="text" id="nik" name="nik" class="form-control" value="KPT0123" disabled>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="name" class="col-sm-4 col-form-label">NIK</label>
                        <div class="col-sm-8">
                            <input type="text" id="nik" name="nik" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="nama" class="col-sm-4 col-form-label">Nama Lengkap</label>
                        <div class="col-sm-8">
                            <input type="text" id="nama" name="nama" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="tempat_lahir" class="col-sm-4 col-form-label">Tempat Lahir</label>
                        <div class="col-sm-8">
                            <input type="text" id="tempat_lahir" name="tempat_lahir" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="tanggal_lahir" class="col-sm-4 col-form-label">Tanggal Lahir</label>
                        <div class="col-sm-8">
                            <input type="text" id="tanggal_lahir" name="tanggal_lahir" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="telepon" class="col-sm-4 col-form-label">Telepon</label>
                        <div class="col-sm-8">
                            <input type="text" id="telepon" name="telepon" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="agama" class="col-sm-4 col-form-label">Agama </label>
                        <div class="col-sm-8">
                            <select id="agama" name="agama" class="select2 form-select" style="width: 100%">
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
                        <label for="alamat" class="col-sm-4 col-form-label">Alamat</label>
                        <div class="col-sm-8">
                            <input type="text" id="alamat" name="alamat" class="form-control">
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
