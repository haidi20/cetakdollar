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
                        <label for="name" class="col-sm-4 col-form-label">Nama Pengguna </label>
                        <div class="col-sm-8">
                            <input type="text" id="name" name="name" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="password" class="col-sm-4 col-form-label">Sandi </label>
                        <div class="col-sm-8">
                            <input type="password" id="password" name="password" class="form-control"
                                autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="email" class="col-sm-4 col-form-label">Email </label>
                        <div class="col-sm-8">
                            <input type="email" id="email" name="email" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="full_name" class="col-sm-4 col-form-label">Nama Lengkap </label>
                        <div class="col-sm-8">
                            <input type="text" id="full_name" name="full_name" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="address" class="col-sm-4 col-form-label">Alamat </label>
                        <div class="col-sm-8">
                            <input type="text" id="address" name="address" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="telegram" class="col-sm-4 col-form-label">Telegram </label>
                        <div class="col-sm-8">
                            <input type="text" id="telegram" name="telegram" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="wa" class="col-sm-4 col-form-label">No. Whatsapp </label>
                        <div class="col-sm-8">
                            <input type="text" id="wa" name="wa" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="role_id" class="col-sm-4 col-form-label">Grup Pengguna </label>
                        <div class="col-sm-8">
                            <select id="role_id" name="role_id" class="select2 form-select" style="width: 100%">
                                <option value="">Pilih Grup Pengguna</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
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
