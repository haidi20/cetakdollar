
// BACKUP JS *JANGAN DIHAPUS
// Mengatur #departmen menjadi nonaktif saat halaman dimuat
$('#departmen').prop('disabled', true);

// Mengatur peristiwa saat pilihan pada #company berubah
$('#company').on('change', function() {
    var companyId = $(this).val();

    // Memuat departemen berdasarkan perusahaan yang dipilih
    $.ajax({
        url: 'employee/get-departmens/' + companyId,
        method: 'GET',
        success: function(response) {
            // Menghapus opsi sebelumnya pada #departmen
            $('#departmen').find('option').remove();

            // Menambahkan opsi "-- Pilih Jabatan --"
            $('#departmen').append('<option value="">-- Pilih Departemen --</option>');

            // Menambahkan opsi departemen baru
            if (response.length > 0) {
                $.each(response, function(key, value) {
                    $('#departmen').append('<option value="' + value.id + '">' + value.name + '</option>');
                });

                // Mengaktifkan #departmen
                $('#departmen').prop('disabled', false);
            } else {
                // Tidak ada departemen yang tersedia
                $('#departmen').append('<option value="">Tidak ada departemen yang tersedia</option>');

                // Menonaktifkan #departmen
                $('#departmen').prop('disabled', true);
            }
        },
        error: function() {
            // Menampilkan pesan error jika terjadi masalah saat memuat departemen
            console.log('Terjadi kesalahan saat memuat departemen.');
        }
    });
});

// Mengatur #position menjadi nonaktif saat halaman dimuat
$('#position').prop('disabled', true);

// Mengatur peristiwa saat pilihan pada #company berubah
$('#departmen').on('change', function() {
    var departmenId = $(this).val();

    // Memuat departemen berdasarkan perusahaan yang dipilih
    $.ajax({
        url: 'employee/get-positions/' + departmenId,
        method: 'GET',
        success: function(response) {
            // Menghapus opsi sebelumnya pada #departmen
            $('#position').find('option').remove();

            // Menambahkan opsi "-- Pilih Jabatan --"
            $('#position').append('<option value="">-- Pilih Jabatan --</option>');

            // Menambahkan opsi departemen baru
            if (response.length > 0) {
                $.each(response, function(key, value) {
                    $('#position').append('<option value="' + value.id + '">' + value.name + '</option>');
                });

                // Mengaktifkan #position
                $('#position').prop('disabled', false);
            } else {
                // Tidak ada departemen yang tersedia
                $('#position').append('<option value="">Tidak ada departemen yang tersedia</option>');

                // Menonaktifkan #position
                $('#position').prop('disabled', true);
            }
        },
        error: function() {
            // Menampilkan pesan error jika terjadi masalah saat memuat departemen
            console.log('Terjadi kesalahan saat memuat departemen.');
        }
    });
});
// END BACKUP
