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
                        <a href='printPreview.php?hal=app/ypt/dashSaldoPDD.php&print=1' target='_blank' class='btn btn-primary pull-right'>
                            <i class='fa fa-print'></i> Print
                        </a>
                    </div>
                </div>
            </div>
        </div>";
}
?>


<div id='dash_page_saldo_pdd'><!-- title row -->
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
                                    $sql="select a.nis,a.kode_lokasi,a.nama,a.kode_kelas,a.kode_akt,a.kode_pp,b.kode_jur,f.nama as nama_jur, isnull(c.nilai,0) as so_akhir from sis_siswa a inner join sis_kelas b on a.kode_kelas=b.kode_kelas and a.kode_lokasi=b.kode_lokasi and a.kode_pp=b.kode_pp inner join sis_jur f on b.kode_jur=f.kode_jur and b.kode_lokasi=f.kode_lokasi and b.kode_pp=f.kode_pp left join (select a.nis,a.kode_lokasi,a.kode_pp,sum(case when a.dc='D' then nilai else -nilai end) as nilai from sis_cd_d a inner join sis_siswa b on a.nis=b.nis and a.kode_lokasi=b.kode_lokasi and a.kode_pp=b.kode_pp where a.periode<='$periode' and a.kode_lokasi='$kode_lokasi' and a.kode_pp='$kode_pp' group by a.nis,a.kode_lokasi,a.kode_pp )c on a.nis=c.nis and a.kode_lokasi=c.kode_lokasi and a.kode_pp=c.kode_pp where a.kode_lokasi='$kode_lokasi' and a.kode_pp='$kode_pp' order by a.kode_kelas,a.nis  ";	
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
                            <b style="font-size:large;"><u>SALDO PDD</u></b><br>
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
                                        <th style="background-color:#cc0000 !important; color:white !important;">NIS</th>
                                        <th style="background-color:#cc0000 !important; color:white !important;">Nama</th>
                                        <th style="background-color:#cc0000 !important; color:white !important;">Saldo PDD</th>
                                    </tr>
                                </thead>
                                <tbody id='tgh-tbody'>
                                <?php
                                $sql2="select a.nis,a.kode_lokasi,a.nama,a.kode_kelas,a.kode_akt,a.kode_pp,b.kode_jur,f.nama as nama_jur, isnull(c.nilai,0) as so_akhir from sis_siswa a inner join sis_kelas b on a.kode_kelas=b.kode_kelas and a.kode_lokasi=b.kode_lokasi and a.kode_pp=b.kode_pp inner join sis_jur f on b.kode_jur=f.kode_jur and b.kode_lokasi=f.kode_lokasi and b.kode_pp=f.kode_pp left join (select a.nis,a.kode_lokasi,a.kode_pp,sum(case when a.dc='D' then nilai else -nilai end) as nilai from sis_cd_d a inner join sis_siswa b on a.nis=b.nis and a.kode_lokasi=b.kode_lokasi and a.kode_pp=b.kode_pp where a.periode<='$periode' and a.kode_lokasi='$kode_lokasi' and a.kode_pp='$kode_pp' group by a.nis,a.kode_lokasi,a.kode_pp )c on a.nis=c.nis and a.kode_lokasi=c.kode_lokasi and a.kode_pp=c.kode_pp where a.kode_lokasi='$kode_lokasi' and a.kode_pp='$kode_pp' order by a.kode_kelas,a.nis ";

                                $rs1=execute($sql2);

                                $tgh_tabel = "";
                                $total_saldo = 0;
                                $i=1;

                                while($row1 = $rs1->FetchNextObject($toupper=false)){
                                    $total_saldo += +$row1->so_akhir;
                                    
                                    echo "<tr>
                                                    <td>".$i."</td>
                                                    <td>".$row1->nis."</td>
                                                    <td>".$row1->nama."</td>
                                                    <td>".number_format($row1->so_akhir,0,",",".")."</td>
                                                    </tr>";
                                    $i++;
                                }

                                echo "<tr>
                                        <td style='text-align:right;' colspan='3'><strong>Total</strong></td>
                                        <td>".number_format($total_saldo,0,",",".")."</td>
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