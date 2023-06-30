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
                    <div class="form-group row">
                        <label for="name" class="col-sm-4 col-form-label">Periode</label>
                        <div class="col-sm-8">
                            <input name="period" type="month" class="form-control" id="month_filter" autocomplete="off"
                                    value="{{ \Carbon\Carbon::now()->format('Y-m') }}">
                        </div>
                    </div>
                    <div class="form-group row">
                       <label for="description" class="col-sm-4 col-form-label">Tanggal Mulai Kerja </label>
                       <div class="col-sm-8">
                        <input name="date_start" type="text" class="form-control" id="start_of_workdays" autocomplete="off"
                                value="{{ \Carbon\Carbon::now()->addMonths(-1)->format('Y-m') }}-26">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="description" class="col-sm-4 col-form-label">Tanggal Akhir Kerja </label>
                        <div class="col-sm-8">
                         <input name="date_end" type="text" class="form-control" id="end_of_workdays" autocomplete="off"
                                 value="{{ \Carbon\Carbon::now()->format('Y-m') }}-25">
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
