<?php

return [
    "feature_private" => ["Bahan", "Lokasi", "Jadwal Kerja", "Jenis Karyawan", "Tingkat Persetujuan", "Departemen", "Log"],
    "permission_private" => [
        "lihat bahan", "lihat departemen",
        "lihat lokasi", "lihat jadwal kerja", "lihat jenis karyawan", "lihat tingkat persetujuan",
        "hapus hak akses", "lihat log",
    ],
    "permission_added" => [
        [
            "name" => "detail fitur",
            "featurer_id" => 7,
        ],
        [
            "name" => "detail grup pengguna",
            "featurer_id" => 7,
        ],
        [
            "name" => "detail proyek",
            "featurer_id" => 8,
        ],
        [
            // untuk tombol detail job order di proyek
            "name" => "proyek job order",
            "featurer_id" => 8,
        ],
        // start kasbon
        [
            "name" => "ekspor laporan kasbon",
            "featurer_id" => 4,
        ],
        [
            "name" => "perwakilan laporan kasbon",
            "featurer_id" => 4,
        ],
        [
            "name" => "persetujuan laporan kasbon",
            "featurer_id" => 4,
        ],
        // [
        //     "name" => "hapus laporan kasbon",
        //     "featurer_id" => 4,
        // ],

        // end kasbon

        // start SPL
        [
            "name" => "detail laporan surat perintah lembur",
            "featurer_id" => 11,
        ],
        [
            "name" => "ekspor laporan surat perintah lembur",
            "featurer_id" => 11,
        ],

        // end SPL
        [
            "name" => "ekspor laporan job order",
            "featurer_id" => 9,
        ],
        [
            "name" => "ekspor laporan cuti",
            "featurer_id" => 13,
        ],
    ],
    "category" => [
        'reguler' => "Reguler",
        'daily' => "Harian",
        'fixed_price' => "Borongan",
    ],
    "status" => [
        "review" => [
            "readable" => "menunggu persetujuan",
            "short_readable" => "proses",
            "color" => "warning",
        ],
        "accept" => [
            "readable" => "di terima",
            "short_readable" => "terima",
            "color" => "success",
        ],
        "reject" => [
            "readable" => "di tolak",
            "short_readable" => "tolak",
            "color" => "danger",
        ],
        "revision" => [
            "readable" => "perlu diperbaiki",
            "short_readable" => "perbaiki",
            "color" => "info",
        ],
        "not yet" => [
            "readable" => "belum dikonfirmasi",
            "short_readable" => "belum",
            "color" => "info",
        ],
        // start job order
        "active" => [
            "readable" => "sedang aktif",
            "short_readable" => "aktif",
            "color" => "success",
        ],
        "pending" => [
            "readable" => "tunda sementara",
            "short_readable" => "tunda",
            "color" => "warning",
        ],
        "finish" => [
            "readable" => "sudah selesai",
            "short_readable" => "selesai",
            "color" => "primary",
        ],
        "overtime" => [
            "readable" => "sedang lembur",
            "short_readable" => "lembur",
            "color" => "info",
        ],
        "correction" => [
            "readable" => "perbaikan ulang",
            "short_readable" => "perbaikan",
            "color" => "warning",
        ],
        "assessment" => [
            "readable" => "proses penilaian",
            "short_readable" => "penilaian",
            "color" => "info",
        ],
        // end job order
    ],
    // start untuk penyesuaian gaji
    "type_times" => [
        "forever" => "selamanya",
        "base_time" => "berdasarkan bulan",
    ],
    "type_amounts" => [
        "nominal" => "jumlah uang",
        "percent" => "persen dari gaji karyawan",
    ],
    "type_adjustments" => [
        "deduction" => "pengurangan",
        "addition" => "penambahan",
    ],
    // end untuk penyesuaian gaji
];
