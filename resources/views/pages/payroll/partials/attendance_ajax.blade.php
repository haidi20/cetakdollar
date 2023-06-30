<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-12">
                <table style="width: 100%" class="table">
                    <tr>
                        <td><br></td>
                        <td>Kode Hari Kerja</td>
                        <td>Tanggal</td>
                        <td>Hari</td>
                        <td>Masuk</td>
                        <td>Keluar</td>
                        <td>Durasi</td>
                        <td>Koreksi Jam</td>
                        <td>Istirahat</td>
                        <td>Jam Kerja</td>
                        <td>Normal</td>
                        <td colspan="4">Perhitungan Lembur</td>
                       
                        <td>Aksi</td>
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
                        <td><br></td>
                        <td>x1</td>
                        <td>x2</td>
                        <td>x3</td>
                        <td>x4</td>
                        <td><br></td>
                    </tr>

                    @php
                        $iter=0;
                    @endphp
                    @foreach ($attendance as $a)

                     
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
                                <td>{{\floor($employee->duration_rest/60)}}</td>
                                <td>{{\floor((($a->duration_work + $a->duration_overtime) - $a->duration_rest)/60)}}</td>
                                <td>{{\floor($employee->working_hour/60)}} </td>
                                <td><br></td>
                                <td><br></td>
                                <td><br></td>
                                <td><br></td>
                                <td>
                                    <a href="#" class="btn icon btn-primary"><i class="bi bi-pencil"></i></a>
                                </td>
                            </tr>
                      

                    
                    
                        @endforeach
                </table>
            </div>
        </div>

    </div>
</div>