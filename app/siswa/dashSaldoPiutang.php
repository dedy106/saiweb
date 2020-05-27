<?php
$kode_lokasi=$_SESSION['lokasi'];
$periode=$_SESSION['periode'];
$kode_pp=$_SESSION['kodePP'];
$nik=$_SESSION['userLog'];

$path = "http://".$_SERVER["SERVER_NAME"]."/";	

$logo = $path . "image/yspt2.png";

if(ISSET($_GET['print']) OR ISSET($_GET['download'])){
    echo"";
} else{
    echo "
        <div class='row'>
            <div class='col-xs-12'>
                <div class='box'>
                    <div class='box-body'>
                        <a href='printPreview.php?hal=app/ypt/dashSaldoPiutang.php&print=1' target='_blank' class='btn btn-primary pull-right'>
                            <i class='fa fa-print'></i> Print
                        </a>
                    </div>
                </div>
            </div>
        </div>";
}
?>


<div id='dash_page_saldo_piutang'><!-- title row -->
    <div class="row">
        <div class='col-xs-12'>
            <div class='box'>
                <div class='box-body'>
                    <div class="row">
                        <div class="col-xs-12">
                            <h2 class="page-header" style="overflow:hidden;">
                                <img src='<?php echo $logo ?>' style='height: 80px;float: left;margin-right: 10px;margin-left: 10px; '>
                                <div>
                                <?php
                                    $sql="select a.nis,a.kode_lokasi,a.nama,a.kode_kelas,a.kode_akt,a.kode_pp,f.kode_jur,g.nama as nama_jur
                                    ,isnull(b.n1,0)-isnull(c.n1,0) as dsp
                                        ,isnull(b.n2,0)-isnull(c.n2,0) as spp
                                        ,isnull(b.n3,0)-isnull(c.n3,0) as lain
                                        ,isnull(b.total,0)-isnull(c.total,0) as total
                                    from sis_siswa a 
                                    inner join sis_kelas f on a.kode_kelas=f.kode_kelas and a.kode_lokasi=f.kode_lokasi and a.kode_pp=f.kode_pp
                                    inner join sis_jur g on f.kode_jur=g.kode_jur and f.kode_lokasi=g.kode_lokasi and f.kode_pp=g.kode_pp
                                    left join (select y.nis,y.kode_lokasi,  
                                                        sum(case when x.kode_param in ('DSP') then (case when x.dc='D' then x.nilai else -x.nilai end) else 0 end) as n1, 
                                                    sum(case when x.kode_param in ('SPP') then (case when x.dc='D' then x.nilai else -x.nilai end) else 0 end)  as n2, 
                                                    sum(case when x.kode_param not in ('DSP','SPP') then (case when x.dc='D' then x.nilai else -x.nilai end) else 0 end)  as n3,
                                                    sum(case when x.dc='D' then x.nilai else -x.nilai end) as total		
                                                from sis_bill_d x 			
                                                inner join sis_siswa y on x.nis=y.nis and x.kode_lokasi=y.kode_lokasi and x.kode_pp=y.kode_pp
                                                where y.flag_aktif=1 and (x.kode_lokasi = '$kode_lokasi')and(x.periode <= '$periode') and y.kode_pp='$kode_pp'			
                                                group by y.nis,y.kode_lokasi 			
                                                )b on a.nis=b.nis and a.kode_lokasi=b.kode_lokasi
                                    left join (select y.nis,y.kode_lokasi,  
                                                    sum(case when x.kode_param in ('DSP') then (case when x.dc='D' then x.nilai else -x.nilai end) else 0 end) as n1, 
                                                    sum(case when x.kode_param in ('SPP') then (case when x.dc='D' then x.nilai else -x.nilai end) else 0 end)  as n2, 
                                                    sum(case when x.kode_param not in ('DSP','SPP') then (case when x.dc='D' then x.nilai else -x.nilai end) else 0 end)  as n3,
                                                    sum(case when x.dc='D' then x.nilai else -x.nilai end) as total				
                                                from sis_rekon_d x 	
                                                inner join sis_siswa y on x.nis=y.nis and x.kode_lokasi=y.kode_lokasi and x.kode_pp=y.kode_pp
                                                where y.flag_aktif=1 and (x.kode_lokasi = '$kode_lokasi')and(x.periode <= '$periode') and y.kode_pp='$kode_pp'			
                                                group by y.nis,y.kode_lokasi 			
                                                )c on a.nis=c.nis and a.kode_lokasi=c.kode_lokasi
                                    where a.flag_aktif=1 and  a.kode_lokasi='$kode_lokasi' and a.kode_pp='$kode_pp'
                                    order by a.kode_kelas,a.nis ";	
                                    $rs=execute($sql);
                                    $row = $rs->FetchNextObject($toupper=false);
                
                                ?>  
                                    <b class='tgh-nama-pp'>Sekolah YPT</b><br>
                                    <small class='tgh-alamat1-pp'><?php echo $row->kode_kelas; ?></small><br>
                                    <small class='tgh-alamat2-pp'><?php echo $row->kode_akt; ?></small><br>
                                    <small class='tgh-alamat3-pp'><?php echo $row->nama_jur; ?></small>


                                </div>
                            </h2>
                        </div>
                    <!-- /.col -->
                    </div>
                    <!-- info row -->
                    <div class="row invoice-info sai-container-overflow">
                        <center>
                            <b style="font-size:large;"><u>SALDO PIUTANG</u></b><br>
                        </center>
                    </div>
                    <!-- /.row -->

                    <!-- Table row -->
                    <div class="row">
                        <div class="col-xs-12 table-responsive sai-container-overflow">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th style="background-color:#cc0000 !important; color:white !important;">No</th>
                                        <th style="background-color:#cc0000 !important; color:white !important;">Nis</th>
                                        <th style="background-color:#cc0000 !important; color:white !important;">Nama</th>
                                        <th style="background-color:#cc0000 !important; color:white !important;">DSP</th>
                                        <th style="background-color:#cc0000 !important; color:white !important;">SPP</th>
                                        <th style="background-color:#cc0000 !important; color:white !important;">Lainnya</th>
                                        <th style="background-color:#cc0000 !important; color:white !important;">Total</th>
                                    </tr>
                                </thead>
                                <tbody id='tgh-tbody'>
                                <?php
                                $sql2="select a.nis,a.kode_lokasi,a.nama,a.kode_kelas,a.kode_akt,a.kode_pp, f.kode_jur,g.nama as nama_jur
                                    ,isnull(b.n1,0)-isnull(c.n1,0) as dsp
                                    ,isnull(b.n2,0)-isnull(c.n2,0) as spp
                                    ,isnull(b.n3,0)-isnull(c.n3,0) as lain
                                    ,isnull(b.total,0)-isnull(c.total,0) as total
                                from sis_siswa a 
                                inner join sis_kelas f on a.kode_kelas=f.kode_kelas and a.kode_lokasi=f.kode_lokasi and a.kode_pp=f.kode_pp
                                inner join sis_jur g on f.kode_jur=g.kode_jur and f.kode_lokasi=g.kode_lokasi and f.kode_pp=g.kode_pp
                                left join (select y.nis,y.kode_lokasi,  
                                                    sum(case when x.kode_param in ('DSP') then (case when x.dc='D' then x.nilai else -x.nilai end) else 0 end) as n1, 
                                                sum(case when x.kode_param in ('SPP') then (case when x.dc='D' then x.nilai else -x.nilai end) else 0 end)  as n2, 
                                                sum(case when x.kode_param not in ('DSP','SPP') then (case when x.dc='D' then x.nilai else -x.nilai end) else 0 end)  as n3,
                                                sum(case when x.dc='D' then x.nilai else -x.nilai end) as total		
                                            from sis_bill_d x 			
                                            inner join sis_siswa y on x.nis=y.nis and x.kode_lokasi=y.kode_lokasi and x.kode_pp=y.kode_pp
                                            where y.flag_aktif=1 and (x.kode_lokasi = '$kode_lokasi')and(x.periode <= '$periode')  and y.kode_pp='$kode_pp'			
                                            group by y.nis,y.kode_lokasi 			
                                            )b on a.nis=b.nis and a.kode_lokasi=b.kode_lokasi
                                left join (select y.nis,y.kode_lokasi,  
                                                sum(case when x.kode_param in ('DSP') then (case when x.dc='D' then x.nilai else -x.nilai end) else 0 end) as n1, 
                                                sum(case when x.kode_param in ('SPP') then (case when x.dc='D' then x.nilai else -x.nilai end) else 0 end)  as n2, 
                                                sum(case when x.kode_param not in ('DSP','SPP') then (case when x.dc='D' then x.nilai else -x.nilai end) else 0 end)  as n3,
                                                sum(case when x.dc='D' then x.nilai else -x.nilai end) as total				
                                            from sis_rekon_d x 	
                                            inner join sis_siswa y on x.nis=y.nis and x.kode_lokasi=y.kode_lokasi and x.kode_pp=y.kode_pp
                                            where y.flag_aktif=1 and (x.kode_lokasi = '$kode_lokasi')and(x.periode <= '$periode') and y.kode_pp='$kode_pp'			
                                            group by y.nis,y.kode_lokasi 			
                                            )c on a.nis=c.nis and a.kode_lokasi=c.kode_lokasi
                                where a.flag_aktif=1 and a.kode_lokasi='$kode_lokasi' and a.kode_pp='$kode_pp'
                                order by a.kode_kelas,a.nis ";

                                // echo $sql2;

                                $tgh_tabel = "";
                                $dsp = 0;
                                $spp = 0;
                                $lain = 0;
                                $total = 0;
                                $rs1=execute($sql2);
                                $i=1;

                                while($row1 = $rs1->FetchNextObject($toupper=false)){
                                    $dsp += +$row1->dsp;
                                    $spp += +$row1->spp;
                                    $lain += +$row1->lain;
                                    $total += +$row1->total;

                                    echo "<tr>
                                            <td>$i</td>
                                            <td>".$row1->nis."</td>
                                            <td>".$row1->nama."</td>
                                            <td>".number_format($row1->dsp,0,",",".")."</td>
                                            <td>".number_format($row1->spp,0,",",".")."</td>
                                            <td>".number_format($row1->lain,0,",",".")."</td>
                                            <td>".number_format($ow1->total,0,",",".")."</td>
                                        </tr>";
                                        $i++;
                                }

                                echo    "<tr>
                                            <td style='text-align:right;' colspan='3'><strong>Total</strong></td>
                                            <td>".number_format($dsp,0,",",".")."</td>
                                            <td>".number_format($spp,0,",",".")."</td>
                                            <td>".number_format($lain,0,",",".")."</td>
                                            <td>".number_format($total,0,",",".")."</td>
                                        </tr>";
                                ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
            </div>
        </div>
    </div>
</div>