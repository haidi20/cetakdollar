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
                        <label for="user_id" class="col-sm-4 col-form-label">Pengguna </label>
                        <div class="col-sm-8">
                            <select id="user_id" name="user_id" class="select2 form-select" style="width: 100%">
                                <option value="">Pilih Pengguna</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="account_number" class="col-sm-4 col-form-label">Nomor Akun </label>
                        <div class="col-sm-8">
                            <input type="text" id="account_number" name="account_number" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="server_trade" class="col-sm-4 col-form-label">server trade </label>
                        <div class="col-sm-8">
                            <input type="text" id="server_trade" name="server_trade" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="password_trading" class="col-sm-4 col-form-label"> Password Trading </label>
                        <div class="col-sm-8">
                            <input type="text" id="password_trading" name="password_trading" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="password_investor" class="col-sm-4 col-form-label"> Password Investor </label>
                        <div class="col-sm-8">
                            <input type="text" id="password_investor" name="password_investor" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="vps_location" class="col-sm-4 col-form-label"> Lokasi VPS </label>
                        <div class="col-sm-8">
                            <input type="text" id="vps_location" name="vps_location" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="key_expired" class="col-sm-4 col-form-label"> Masa Aktif </label>
                        <div class="col-sm-8">
                            <input type="number" id="key_expired" name="key_expired" class="form-control">
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
