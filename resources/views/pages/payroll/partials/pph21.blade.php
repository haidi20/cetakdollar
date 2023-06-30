<div class="row">
    <div class="col-md-12">
        <table style="width: 100%">
            <tbody id="table-pph21">
                <tr>
                    <td colspan="3">
                        <h6 class="head-color">D. Penghasilan Kotor</h6>
                    </td>
                </tr>
                <tr>
                    <td>
                        1.
                    </td>
                    <td colspan="2">
                        Gaji Kotor - Potongan
                    </td>
                    <td>
                        <span id="gaji_kotor_potongan">
                            Rp
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>
                        2.
                    </td>
                    <td colspan="2">
                        BPJS dibayar Perusahaan

                    </td>
                    <td>
                        <span id="bpjs_dibayar_perusahaan">
                            Rp
                        </span>
                    </td>
                </tr>
                <tr class="summary">
                    <td colspan="3">
                        Total Penghasilan Kotor (D)
                    </td>
                    <td>
                        <span id="total_penghasilan_kotor">
                            Rp
                        </span>
                    </td>
                </tr>
                <tr style="height: 20px">

                </tr>
                <tr>
                    <td colspan="3">
                        <h6 class="head-color">E. Pengurangan</h6>
                    </td>
                </tr>
                <tr>
                    <td>
                        1.
                    </td>
                    <td colspan="2">
                        Biaya Jabatan (5% x (D))
                    </td>
                    <td>
                        <span id="biaya_jabatan">
                            {{--  --}}
                            Rp
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>
                        2.
                    </td>
                    <td colspan="2">
                        BPJS dibayar karyawan
                    </td>
                    <td>
                        <span id="bpjs_dibayar_karyawan">
                            {{--  --}}
                            Rp
                        </span>
                    </td>
                </tr>
                <tr class="summary">
                    <td colspan="3">
                        Jumlah Pengurangan (E)
                    </td>
                    <td>
                        <span id="jumlah_pengurangan">
                            Rp
                        </span>
                    </td>
                </tr>
                <tr style="height: 20px">

                </tr>
                <tr>
                    <td colspan="3">
                        <h6 class="head-color">F. Gaji Bersih 12 Bulan</h6>
                    </td>
                    <td>
                        <span id="gaji_bersih_setahun">
                            Rp
                        </span>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <h6 class="head-color">G. PKP 12 Bulan= (F)-PTKP</h6>
                    </td>
                    <td>
                        <span id="pkp_setahun">
                            Rp
                        </span>
                    </td>
                </tr>
                <tr style="height: 20px">

                </tr>
                <tr id="table-tarif" class="head-color" style="background-color: #F2F7FF;">
                    <td>
                        Tarif
                    </td>
                    <td>
                        Dari PKP
                    </td>
                    <td>
                        Ke PKP
                    </td>
                    <td>
                        Progressive PPH21
                    </td>
                </tr>

                <tr class="table-tarif">
                    <td>5 %</td>
                    <td>0 jt</td>
                    <td>60 jt</td>
                    <td id="pkp_lima_persen">Rp 0</td>
                </tr>
            
                <tr class="table-tarif">
                    <td>15 %</td>
                    <td>60 jt</td>
                    <td>250 jt</td>
                    <td id="pkp_lima_belas_persen">Rp 0</td>
                </tr>
            
                <tr class="table-tarif">
                    <td>25 %</td>
                    <td>250 jt</td>
                    <td>500 jt</td>
                    <td id="pkp_dua_puluh_lima_persen">Rp 0</td>
                </tr>
            
                <tr class="table-tarif">
                    <td>30 %</td>
                    <td>500 jt</td>
                    <td>1.000 jt</td>
                    <td id="pkp_tiga_puluh_persen">Rp 0</td>
                </tr>

                {{-- pajak_pph_dua_satu_setahun --}}
                <tr>
                    <td colspan="3">
                        <h6 class="head-color">G. PPH21 Tahunan</h6>
                    </td>
                    <td id="pajak_pph_dua_satu_setahun">Rp 0</td>
                </tr>
            </tbody>
        </table>
    </div>
    
</div>
