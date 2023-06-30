<table>
    <tr>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
    </tr>
    
    <tr>
        <td><br></td>
        <td>PERHITUNGAN GAJI</td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
    </tr>
    <tr>
        <td><br></td>
        <td>Bulan</td>
        <td><br></td>
        <td>{{\Carbon\Carbon::parse($period_payroll->period)->translatedFormat('F')}}</td>
        <td>{{\Carbon\Carbon::parse($period_payroll->period)->translatedFormat('Y')}}</td>
        <td>Gaji Dasar</td>
        <td><br></td>
        <td data-format="Rp* #,##" colspan="2">{{$employee->basic_salary}}</td>
        <td>/bulan</td>
        <td>Tunj. Makan</td>
        <td><br></td>
        <td  data-format="Rp* #,##" colspan="2">{{$employee->meal_allowance_per_attend}}</td>
        <td>/hadir</td>
    </tr>
    <tr>
        <td><br></td>
        <td>Id</td>
        <td><br></td>
        <td colspan="2">{{$employee->nip}}</td>
        <td>Tunjangan Tetap</td>
        <td><br></td>
        <td data-format="Rp* #,##" colspan="2">{{$employee->allowance}}</td>
        <td>/bulan</td>
        <td>Tunj. Transport</td>
        <td><br></td>
        <td data-format="Rp* #,##" colspan="2">{{$employee->transport_allowance_per_attend}}</td>
        <td>/hadir</td>
    </tr>
    <tr>
        <td><br></td>
        <td>Nama</td>
        <td><br></td>
        <td colspan="2">{{$employee->name}}</td>
        <td>Rate Lembur</td>
        <td><br></td>
        <td data-format="Rp* #,##" colspan="2">{{$employee->overtime_rate_per_hour}}</td>
        <td>/jam</td>
        <td>Tunj. Kehadiran</td>
        <td><br></td>
        <td data-format="Rp* #,##" colspan="2">{{$employee->attend_allowance_per_attend}}</td>
        <td>/hadir</td>
    </tr>

    {{-- position --}}
    <tr>
        <td><br></td>
        <td>Posisi</td>
        <td><br></td>
        <td colspan="2">{{$employee->position->name}}</td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td>PTKP Karyawan</td>
        <td><br></td>
        <td data-format="Rp* #,##" colspan="2">{{$employee->ptkp_karyawan}}</td>
        <td>/tahun</td>
    </tr>
    <tr>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td>Jumlah Cuti/Ijin</td>
        <td><br></td>
        <td colspan="2">-</td>
        <td>/bulan</td>
    </tr>
    <tr>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td>Sisa Cuti</td>
        <td><br></td>
        <td colspan="2">-</td>
        <td>hari/tahun</td>
    </tr>

    <tr>
        <td><br></td>
        <td rowspan="2">Kode Hari Kerja</td>
        <td rowspan="2" style="background-color: #92d050">Tanggal</td>
        <td rowspan="2" style="background-color: #92d050">Hari</td>
        <td>Masuk</td>
        <td>Keluar</td>
        <td style="background-color: #92d050">Durasi</td>
        <td>Koreksi</td>
        <td style="background-color: #92d050">Istirahat</td>
        <td style="background-color: #92d050">Jam Kerja</td>
        <td style="background-color: #92d050">Normal</td>
        <td colspan="4" style="background-color: #92d050">Perhitungan Lembur</td>

    </tr>

    <tr>
        
        
        <td><br></td>
        <td>(hh:mm)</td>
        <td>(hh:mm)</td>
        <td>(jam)</td>
        <td>(jam)</td>
        <td>(jam)</td>
        <td>(jam)</td>
        <td>(jam)</td>
        <td>x1</td>
        <td>x2</td>
        <td>x3</td>
        <td>x4</td>
    </tr>


    @php
        $iter=0;
    @endphp
    @foreach ($attendance as $a)

    @if (($iter > 0 && $tanggal_lama != $a->date) or ($iter==0))
    @php

 

    $tanggal_lama  = $a->date;



    $iter++;
    $tanggal  = \Carbon\Carbon::parse($a->date);

    $d_hour = \floor($a->duration_work / 60);
    $d_minute = $a->duration_work%60 ;
@endphp
        <tr>
            <td><br></td>
            <td><br></td>
            <td>{{round($tanggal->translatedFormat('d'))}}</td>
            <td>{{$tanggal->translatedFormat('l')}}</td>
            <td>{{\Carbon\Carbon::parse($a->hour_start)->translatedFormat('H:i')}}</td>
            <td>{{\Carbon\Carbon::parse($a->hour_end)->translatedFormat('H:i')}}</td>
            <td>{{$d_hour}} : {{$d_minute}}</td>
            <td><br></td>
            <td> <br></td>
            <td> <br></td>
            <td><br></td>
            <td><br></td>
            <td><br></td>
            <td><br></td>
            <td><br></td>
            <td><br></td>
        </tr>
    @endif

   
   
    @endforeach

    @if ($iter < 32)
    @for ($i = 0; $i < (32 - $iter); $i++)
    <tr>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
    </tr>
    @endfor
@endif
    

    <tr>
        <td><br></td>
        <td>TOTAL</td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td>t1</td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
    </tr>
    <tr>
        <td><br></td>
        <td>Jumlah Masuk Hari Kerja</td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td>{{$payroll->jumlah_hari_tunjangan_makan}}</td>
        <td>hari</td>
        <td><br></td>
        <td>Total Jam Lembur</td>
        <td>{{$payroll->jumlah_jam_rate_lembur}}</td>
        <td>jam</td>
        <td><br></td>
        <td><br></td>
    </tr>

    <tr>
        <td><br></td>
        <td>Perhitungan Gaji</td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td>t1</td>
        <td><br></td>
        <td>II. Perhitungan BPJS</td>
        <td><br></td>
        <td><br></td>
        <td>Dasar Upah BPJS TK</td>
        <td><br></td>
        <td data-format="Rp* #,##" colspan="2">{{$payroll->dasar_updah_bpjs_tk}}</td>
    </tr>
    <tr>
        <td><br></td>
        <td>A. Pendapatan Gaji</td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td>Dasar Upah BPJS KES</td>
        <td><br></td>
        <td data-format="Rp* #,##" colspan="2">{{$payroll->dasar_updah_bpjs_kes}}</td>
    </tr>

    <tr>
        <td><br></td>
        <td>1</td>
        <td>Gaji Dasar</td>
        <td><br></td>
        <td>1</td>
        <td>Bulan</td>
        <td data-format="Rp* #,##" colspan="2">{{$payroll->gaji_dasar}}</td>
        <td>Jaminan Sosial</td>
        <td><br></td>
        <td><br></td>
        <td>%Perusahaan</td>
        <td>%Karyawan</td>
        <td>Rp Perusahaan</td>
        <td>Rp Karyawan</td>
        
    </tr>

    <tr>
        <td><br></td>
        <td>2</td>
        <td>Tunjangan Tetap</td>
        <td><br></td>
        <td>1</td>
        <td>Bulan</td>
        <td data-format="Rp* #,##" colspan="2">{{$payroll->pendapatan_tunjangan_tetap}}</td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        
    </tr>

    <tr >
        <td><br></td>
        <td>3</td>
        <td >Uang Makan</td>
        <td><br></td>
        <td >{{$payroll->jumlah_hari_tunjangan_makan}}</td>
        <td>Hari</td>
        <td data-format="Rp* #,##" colspan="2">{{$payroll->pendapatan_uang_makan}}</td>
        <td>1. Hari Tua (JHT)</td>
        <td><br></td>
        <td><br></td>
        <td>{{$payroll->jht_perusahaan_persen}} % </td>
        <td>{{$payroll->jht_karyawan_persen}} % </td>
        <td data-format="#,##" >{{$payroll->jht_perusahaan_rupiah}}</td>
        <td data-format="#,##">{{$payroll->jht_karyawan_rupiah}}</td>
        
    </tr>

    <tr>
        <td><br></td>
        <td>4</td>
        <td>Lembur</td>
        <td><br></td>
        <td>{{$payroll->jumlah_jam_rate_lembur}}</td>
        <td>Jam</td>
        <td data-format="#,##" colspan="2">{{$payroll->pendapatan_lembur}}</td>
        <td>2. Kecelakaan (JKK)</td>
        <td><br></td>
        <td><br></td>
        <td>{{$payroll->jkk_perusahaan_persen}} % </td>
        <td>{{$payroll->jkk_karyawan_persen}} % </td>
        <td data-format="#,##">{{$payroll->jkk_perusahaan_rupiah}}</td>
        <td data-format="#,##">{{$payroll->jkk_karyawan_rupiah}}</td>
        
    </tr>

    <tr>
        <td><br></td>
        <td>5</td>
        <td>Tambahan Lain Lain</td>
        <td><br></td>
        <td></td>
        <td></td>
        <td data-format="Rp* #,##" colspan="2">{{$payroll->pendapatan_tambahan_lain_lain}}</td>
        <td>3. Kematian (JKM)</td>
        <td><br></td>
        <td><br></td>
        <td>{{$payroll->jkm_perusahaan_persen}} % </td>
        <td>{{$payroll->jkm_karyawan_persen}} % </td>
        <td data-format="#,##">{{$payroll->jkm_perusahaan_rupiah}}</td>
        <td data-format="#,##">{{$payroll->jkm_karyawan_rupiah}}</td>
        
    </tr>

    <tr>
        <td><br></td>
        <td><br></td>
        <td>Jumlah Pendapatan Kotor A</td>
        <td><br></td>
        <td></td>
        <td></td>
        <td data-format="Rp* #,##" colspan="2">{{$payroll->jumlah_pendapatan}}</td>
        <td>4. Pensiun (JP)</td>
        <td><br></td>
        <td><br></td>
        <td>{{$payroll->jp_perusahaan_persen}} % </td>
        <td>{{$payroll->jp_karyawan_persen}} % </td>
        <td data-format="#,##">{{$payroll->jp_perusahaan_rupiah}}</td>
        <td data-format="#,##">{{$payroll->jp_karyawan_rupiah}}</td>
        
    </tr>

    <tr>
        <td><br></td>
        <td>B. Pemotongan</td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        
        <td>5. Kesehatan (BPJS)</td>
        <td><br></td>
        <td><br></td>
        <td>{{$payroll->bpjs_perusahaan_persen}} % </td>
        <td>{{$payroll->bpjs_karyawan_persen}} % </td>
        <td data-format="#,##">{{$payroll->bpjs_perusahaan_rupiah}}</td>
        <td data-format="#,##">{{$payroll->bpjs_karyawan_rupiah}}</td>
        
    </tr>

    <tr>
        <td><br></td>
        <td>1</td>
        <td>BPJS dibayar Karyawan</td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td data-format="Rp* #,##" colspan="2">{{\round($payroll->pemotongan_bpjs_dibayar_karyawan)}}</td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        
    </tr>

    <tr>
        <td><br></td>
        <td>2</td>
        <td>Pajak Penghasilan PPH21((H)/12)</td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td data-format="Rp* #,##" colspan="2">{{\round($payroll->pemotongan_pph_dua_satu)}}</td>
        <td>Total</td>
        <td><br></td>
        <td><br></td>

        <td>{{$payroll->total_bpjs_perusahaan_persen}} %</td>
        <td>{{$payroll->total_bpjs_karyawan_persen}} %</td>
        <td data-format="#,##">{{$payroll->total_bpjs_perusahaan_rupiah}}</td>
        <td data-format="#,##">{{$payroll->total_bpjs_karyawan_rupiah}}</td>
        
    </tr>


    <tr>
        <td><br></td>
        <td>3</td>
        <td>Potongan Lain-Lain</td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td data-format="Rp* #,##" colspan="2">{{\round($payroll->pemotongan_potongan_lain_lain)}}</td>
        <td>IV. Perhitungan Pajak Penghasilan (PPH21) </td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        
    </tr>

    <tr>
        <td><br></td>
        <td><br></td>
        <td>Jumlah Potongan (B)</td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td data-format="Rp* #,##" colspan="2">{{\round($payroll->jumlah_pemotongan)}}</td>
        <td>D. Penghasilan kotor </td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        
    </tr>
    <tr>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td>1. Gaji Kotor - Potongan </td>
        <td><br></td>
        <td><br></td>
        <td colspan="2" data-format="Rp* #,##">{{$payroll->pajak_gaji_kotor_kurang_potongan}}</td>
        
        <td><br></td>
        <td><br></td>
        
    </tr>

    <tr>
        <td><br></td>
        <td>C. Gaji Bersih (A)-(B)</td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td style="background-color:#fcfc04" data-format="Rp* #,##" colspan="2">{{round($payroll->gaji_bersih)}}</td>
        <td>2. BPJS dibayar Perusahaan </td>
        <td><br></td>
        <td><br></td>
        <td  colspan="2" data-format="Rp* #,##">{{$payroll->pajak_bpjs_dibayar_perusahaan}}</td>
       
        <td><br></td>
        <td><br></td>
        
    </tr>

    <tr>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td>Total Penghasilan Kotor (D) </td>
        <td><br></td>
        <td><br></td>
        <td colspan="2" data-format="Rp* #,##">{{$payroll->pajak_total_penghasilan_kotor}}</td>
        
        <td><br></td>
        <td><br></td>
        
    </tr>

    <tr>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td>E. Pengurang</td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        
    </tr>

    <tr>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td>1. Biaya Jabatan (5% x (D)) </td>
        <td><br></td>
        <td><br></td>
        <td colspan="2" data-format="Rp* #,##">{{$payroll->pajak_biaya_jabatan}}</td>
        <td><br></td>
        <td><br></td>
        
    </tr>

    <tr>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td>2. BPJS dibayar Karyawan </td>
        <td><br></td>
        <td><br></td>
        <td colspan="2" data-format="Rp* #,##">{{$payroll->pajak_bpjs_dibayar_karyawan}}</td>
        
        <td><br></td>
        <td><br></td>
    </tr>

    <tr>
        <td><br></td>
        <td>Dihitung Oleh</td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td>Diterima Oleh</td>
        <td><br></td>
        <td><br></td>
        <td>Jumlah Pengurang (E) </td>
        <td><br></td>
        <td><br></td>
        <td colspan="2" data-format="Rp* #,##">{{$payroll->pajak_total_pengurang}}</td>
        <td><br></td>
        <td><br></td>
    </tr>
    <tr>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td>F. Gaji Bersih 12 Bulan</td>
        <td><br></td>
        <td><br></td>
        <td colspan="2" data-format="Rp* #,##">{{$payroll->pajak_gaji_bersih_setahun}}</td>
        <td><br></td>
        <td><br></td>
    </tr>
    <tr>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td>G. PKP 12 Bulan = (F)- PTKP</td>
        <td><br></td>
        <td><br></td>
        <td colspan="2" data-format="Rp* #,##">{{$payroll->pkp_setahun > 0 ? $payroll->pkp_setahun : 0}}</td>
        
        <td><br></td>
        <td><br></td>
    </tr>

    <tr>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td>Tarif</td>
        <td>Dari PKP</td>
        <td>Ke PKP</td>
        <td>Progressive PPH 21</td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
    </tr>
    <tr>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td>5%</td>
        <td> 0 Jt</td>
        <td>60 Jt</td>
        <td colspan="2" data-format="Rp* #,##">{{$payroll->pkp_lima_persen ?? 0}}</td>
       
        <td><br></td>
        <td><br></td>
    </tr>
    <tr>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td>15%</td>
        <td> 60 Jt</td>
        <td>250 Jt</td>
        <td colspan="2" data-format="Rp* #,##">{{$payroll->pkp_lima_belas_persen ?? 0}}</td>
        
        <td><br></td>
        <td><br></td>
    </tr>
    <tr>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td>25%</td>
        <td> 250 Jt</td>
        <td> 500 Jt</td>
        <td colspan="2" data-format="Rp* #,##">{{$payroll->pkp_dua_puluh_lima_persen ?? 0}}</td>
       
        <td><br></td>
        <td><br></td>
    </tr>
    <tr>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td>30%</td>
        <td> 500 Jt</td>
        <td> 1000 Jt</td>
        <td colspan="2" data-format="Rp* #,##">{{$payroll->pkp_tiga_puluh_persen ?? 0}}</td>
       
        <td><br></td>
        <td><br></td>
    </tr>

    <tr>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td>G. PPH21 Setahun</td>
        <td><br></td>
        <td><br></td>
        <td colspan="2" data-format="Rp* #,##">{{$payroll->pajak_pph_dua_satu_setahun > 0 ? $payroll->pajak_pph_dua_satu_setahun : 0}}</td>
       
        <td><br></td>
        <td><br></td>
    </tr>






















</table>