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
                        <label for="name" class="col-sm-4 col-form-label">Nama Bpjs </label>
                        <div class="col-sm-8">
                            <input type="text" id="name" name="name" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="company_percent" class="col-sm-4 col-form-label">Persenan Perusahaan </label>
                        <div class="col-sm-8">
                            <input type="number" id="company_percent" name="company_percent" class="form-control" step="0.01">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="employee_percent" class="col-sm-4 col-form-label">Persenan Karyawan </label>
                        <div class="col-sm-8">
                            <input type="number" id="employee_percent" name="employee_percent" class="form-control" step="0.01">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="company_nominal" class="col-sm-4 col-form-label">Nominal Dari Perusahaan </label>
                        <div class="col-sm-8">
                            <input type="text" id="company_nominal" name="company_nominal" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="employee_nominal" class="col-sm-4 col-form-label">Nominal Dari Perusahaan </label>
                        <div class="col-sm-8">
                            <input type="text" id="employee_nominal" name="employee_nominal" class="form-control" readonly>
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
