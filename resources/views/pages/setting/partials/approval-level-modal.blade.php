<div class="modal fade bd-example-modal-lg" id="formModal" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleForm">Tambah Approval</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="id" name="id" class="form-control">
                    <div class="form-group row">
                        <label for="name" class="col-sm-4 col-form-label">Nama Approval </label>
                        <div class="col-sm-8">
                            <input type="text" id="name" name="name" class="form-control">
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <b>Detail</b>
                            <a href="javascript:void(0)" onclick="onAddApprovalDetail()"
                                class="btn btn-success btn-sm btn-circle ml-1">
                                <i class="bi bi-plus"></i>
                            </a>
                        </div>
                        <div class="col-md-6">

                        </div>
                    </div>
                    <!-- form unit dan operator generate dari javascript -->
                    <div id="detailNewForm"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-success">Kirim</button>
                </div>
            </form>
        </div>
    </div>
</div>
