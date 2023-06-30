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
                    {{-- <div class="form-group row">
                        <label for="departmen_id" class="col-sm-4 col-form-label">Departemen </label>
                        <div class="col-sm-8">
                            <select id="departmen_id" name="departmen_id" class="select2 form-select" style="width: 100%">
                                <option value="">Pilih Departemen</option>
                                @foreach ($departmens as $departmen)
                                <option value="{{ $departmen->id }}">{{ $departmen->code }} - {{ $departmen->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div> --}}
                    <div class="form-group row">
                        <label for="name" class="col-sm-4 col-form-label">Nama Jabatan</label>
                        <div class="col-sm-8">
                            <input type="text" id="name" name="name" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="minimum_employee" class="col-sm-4 col-form-label">Minimal Jumlah Karyawan Per hari
                        </label>
                        <div class="col-sm-8">
                            <input type="number" id="minimum_employee" name="minimum_employee" class="form-control" min="1">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="description" class="col-sm-4 col-form-label">Keterangan </label>
                        <div class="col-sm-8">
                            <input type="text" id="description" name="description" class="form-control">
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
