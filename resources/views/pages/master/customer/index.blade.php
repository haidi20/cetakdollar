@extends('layouts.master')

@section('content')
@include('pages.master.customer.partials.modal')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Pelanggan</h3>
                {{-- <p class="text-subtitle text-muted">For user to check they list</p> --}}
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        {{-- <li class="breadcrumb-item"><a href="#">Pengaturan</a></li> --}}
                        <li class="breadcrumb-item active" aria-current="page">Pelanggan</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-header">
                <span class="fs-4 fw-bold">Data Pelanggan</span>
                @can('tambah pelanggan')
                <button onclick="onCreate()" class="btn btn-sm btn-success shadow-sm float-end" id="addData"
                    data-toggle="modal">
                    <i class="fas fa-plus text-white-50"></i> Tambah Pelanggan
                </button>
                @endcan
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            {!! $html->table(['class' => 'table table-striped table-bordered']) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@section('script')
{!! $html->scripts() !!}
<script>
    const initialState = {
        customers: [],
    };

    let state = {
        ...initialState
    };

    $(document).ready(function () {
        $('.dataTable').DataTable();

        state.customers = {!!json_encode($customers) !!};
        setupSelect();
        // setupDateFilter();
        send();
    });

    function onCreate() {
        clearForm();
        getLastCode();
        $("#code-new").show();
        $("#code-last").hide();

        $("#titleForm").html("Tambah Pelanggan");
        onModalAction("formModal", "show");
    }

    function onEdit(data) {
        clearForm();

        $("#id").val(data.id);
        $("#code-last").val(data.code);
        $("#code-last").show();
        $("#code-new").hide();
        $("#name").val(data.name);
        $("#address").val(data.address);
        $("#terms").val(data.terms);
        $("#credit_limits").val(data.credit_limits);
        $("#contact_person").val(data.contact_person);
        $("#handphone").val(data.handphone);
        $("#telephone").val(data.telephone);
        $("#company_id").val(data.company_id).trigger("change");
        $("#barge_id").val(data.barge_id).trigger("change");

        $("#titleForm").html("Ubah Pelanggan");
        onModalAction("formModal", "show");
    }

    function send() {
        $("#form").submit(function (e) {
            e.preventDefault();
            let fd = new FormData(this);

            $.ajax({
                url: "{{ route('master.customer.store') }}",
                method: 'POST',
                data: fd,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function (responses) {

                    // console.info(responses);

                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2500,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    });
                    if (responses.success == true) {
                        $('#formModal').modal('hide');
                        Toast.fire({
                            icon: 'success',
                            title: responses.message
                        });

                        window.LaravelDataTables["dataTableBuilder"].ajax.reload(
                            function (json) {});
                    }
                },
                error: function (err) {
                    console.log(err.responseJSON.message);
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 4000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    });

                    Toast.fire({
                        icon: 'error',
                        title: err.responseJSON.message
                    });
                }
            });
        });
    }

    function onDelete(data) {
        Swal.fire({
            title: 'Perhatian!!!',
            html: `Anda yakin ingin hapus data fitur <h2><b> ${data.name} </b> ?</h2>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            onfirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Tidak'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('master.customer.delete') }}",
                    method: 'DELETE',
                    dataType: 'json',
                    data: {
                        id: data.id
                    },
                    success: function (responses) {
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 2500,
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.addEventListener('mouseenter', Swal.stopTimer)
                                toast.addEventListener('mouseleave', Swal.resumeTimer)
                            }
                        });
                        $('#formModal').modal('hide');
                        Toast.fire({
                            icon: 'success',
                            title: responses.message
                        });

                        window.LaravelDataTables["dataTableBuilder"].ajax.reload(
                            function (json) {});
                    },
                    error: function (err) {
                        // console.log(err.responseJSON.message);
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 4000,
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.addEventListener('mouseenter', Swal.stopTimer)
                                toast.addEventListener('mouseleave', Swal.resumeTimer)
                            }
                        });

                        Toast.fire({
                            icon: 'error',
                            title: err.responseJSON.message
                        });
                    }
                });
            }
        });
    }

    function setupSelect() {
        $(".select2").select2();
    }

    function clearForm() {
        $("#id").val("");
        $("#code").val("");
        $("#name").val("");
        $("#address").val("");
        $("#terms").val("");
        $("#credit_limits").val("");
        $("#contact_person").val("");
        $("#handphone").val("");
        $("#telephone").val("");
        $("#company_id").val("");
        $("#barge_id").val("");
    }

    function getLastCode() {
        var codeInput = document.getElementById("code-new");
        var initialCode = "C-00";
        // Kirim permintaan HTTP ke endpoint getLastCode()
        // Ganti "URL_API" dengan URL yang sesuai untuk memanggil fungsi getLastCode() di sisi server
        fetch('customer/get-last-code')
            .then(response => response.json())
            .then(data => {
                // Mendapatkan code terakhir dari respons
                var lastCode = data.lastCode;

                if (lastCode) { // Jika data terisi, tampilkan langsung pada input #code
                    // Mengambil nomor dari code terakhir
                    var lastCodeNumber = parseInt(lastCode.match(/\d+$/)[0]);

                    // Increment nomor
                    var nextCodeNumber = initialCode + lastCodeNumber;
                    codeInput.value = nextCodeNumber;
                    console.log(nextCodeNumber);
                } else { // Jika data kosong, buatkan kode baru
                    // Mengambil nomor dari code terakhir
                    var lastCodeNumber = parseInt(lastCode.match(/\d+$/)[0]);

                    // Increment nomor
                    var nextCodeNumber = lastCodeNumber + 1;

                    // Membentuk code baru dengan nomor yang diincrement
                    var nextCode = initialCode + nextCodeNumber;

                    // Memasukkan code baru ke dalam input
                    codeInput.value = nextCode;
                }
            })
            .catch(error => console.log(error));
    }

</script>
@endsection
