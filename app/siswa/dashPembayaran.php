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
                        <a href='printPreview.php?hal=app/ypt/dashPDD.php&print=1' target='_blank' class='btn btn-primary pull-right'>
                            <i class='fa fa-print'></i> Print
                        </a>
                        <a href='download.php?hal=app/ypt/dashPDD.php&download=1' target='_blank' class='btn btn-primary pull-right'><i class='fa fa-download'></i> Download
                        </a>
                    </div>
                </div>
            </div>
        </div>";
}

?>
<div id='dash_page_pdd'><!-- title row -->
    <div class="row">
        <div class='col-xs-12'>
            <div class='box'>
                <div class='box-body'>
                    <div class="row">
                        <div class="col-xs-12">
                            <h2 class="page-header" style="overflow:hidden;">
                                <img src='<?php echo $logo; ?>' style='height: 80px;float: left;margin-right: 10px;margin-left: 10px; '>
                                <div>
                                <?php
                                
                                    $bio = "select a.no_reg, a.id_bank, a.nama, a.kode_ta, g.nama as nama_pp,g.alamat,g.alamat2 from sis_siswareg a inner join sis_sekolah g on a.kode_pp=g.kode_pp and a.kode_lokasi=g.kode_lokasi left join sis_hakakses f on a.no_reg=f.nik and a.kode_lokasi=f.kode_lokasi and a.kode_pp=f.kode_pp where a.no_reg='$nik' AND a.kode_lokasi ='$kode_lokasi' AND a.kode_pp = '$kode_pp'";

                                    $rs=execute($bio);
                                    $row = $rs->FetchNextObject($toupper=false);	
                                ?>                               
                                    <b class='tgh-nama-pp'><?php echo $row->nama_pp; ?></b><br>
                                    <small class='tgh-alamat-pp'><?php echo $row->alamat; ?></small><br>
                                    <small class='tgh-alamat2-pp'><?php echo $row->alamat2; ?></small>
                                </div>
                            </h2>
                        </div>
                    <!-- /.col -->
                    </div>
                    <!-- info row -->
                    <div class="row invoice-info sai-container-overflow">
                        <center>
                            <b style="font-size:large;"><u>KARTU PEMBAYARAN REGISTRASI</u></b><br>
                        </center>
                        <div id='tgh-identitas'>
                        <strong style='padding-left:15px;'>Identitas Siswa</strong><br>
                                <div class='col-xs-2 invoice-col'>
                                    <address>
                                        ID Registrasi<br>
                                        ID Bank<br>
                                        Nama<br>
                                        Tahun Akademik<br>
                                    </address>
                                </div>
                                <div class='col-xs-2 invoice-col'>
                                    <address>
                                        :<br>
                                        :<br>
                                        :<br>
                                        :<br>
                                    </address>
                                </div>
                                <div class='col-xs-8 invoice-col'>
                                    <address>
                                    <strong><?php echo $row->no_reg; ?></strong><br>
                                    <?php echo $row->id_bank; ?><br>
                                    <?php echo $row->nama; ?><br>
                                    <?php echo $row->kode_ta; ?><br>
                                    </address>
                                </div>
                        </div>
                    </div>
                    <!-- /.row -->

                    <!-- Table row -->
                    <div class="row">
                        <div class="col-xs-12 table-responsive sai-container-overflow">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th style="background-color:#cc0000 !important; color:white !important;">No</th>
                                        <th style="background-color:#cc0000 !important; color:white !important;">Tanggal</th>
                                        <th style="background-color:#cc0000 !important; color:white !important;">No Bukti</th>
                                        <th style="background-color:#cc0000 !important; color:white !important;">Keterangan</th>
                                        <th style="background-color:#cc0000 !important; color:white !important;">Debet</th>
                                        <th style="background-color:#cc0000 !important; color:white !important;">Kredit</th>
                                        <th style="background-color:#cc0000 !important; color:white !important;">Saldo</th>
                                    </tr>
                                </thead>
                                <tbody id='tgh-tbody'>
                                <?php
                                $tgh_tabel = "";
                                $total_d= 0;
                                $total_k = 0;
                                $total_saldo = 0;

                                $sql2 = "select a.no_bukti,a.tgl,a.keterangan,a.modul,a.debet,a.kredit from (select a.no_bukti,a.nilai,convert(varchar(20),b.tanggal,103) as tgl,b.keterangan,b.modul,b.tanggal, a.nilai as debet,0 as kredit from sis_cd_d a inner join kas_m b on a.no_bukti=b.no_kas and a.kode_lokasi=b.kode_lokasi where a.nis='$nik' and a.kode_lokasi='$kode_lokasi' and a.kode_pp='$kode_pp' and a.dc='D' union all select a.no_bukti,a.nilai,convert(varchar(20),b.tanggal,103) as tgl,b.keterangan,b.modul,b.tanggal, 0 as debet,case when a.dc='C' then a.nilai else 0 end as kredit from sis_cd_d a inner join sis_rekon_m b on a.no_bukti=b.no_rekon and a.kode_lokasi=b.kode_lokasi where a.nis='$nik' and a.kode_lokasi='$kode_lokasi' and a.kode_pp='$kode_pp' and a.dc='C' union all select a.no_bukti,a.nilai,convert(varchar(20),b.tanggal,103) as tgl,b.keterangan,b.modul,b.tanggal, 0 as debet,case when a.dc='C' then a.nilai else 0 end as kredit from sis_cd_d a inner join kas_m b on a.no_bukti=b.no_kas and a.kode_lokasi=b.kode_lokasi where a.nis='$nik' and a.kode_lokasi='$kode_lokasi' and a.kode_pp='$kode_pp' and a.dc='C')a order by a.tanggal";

                                $rs1=execute($sql2);
                                $i=1;
                                while($row1 = $rs1->FetchNextObject($toupper=false)){
                                    $total_d += +$row1->debet;
                                    $total_k += +$row1->kredit;
                                    $total_saldo += +$total_d - $total_k;
                                    
                                    echo "<tr>
                                            <td>$i</td>
                                            <td>$row1->tgl</td>
                                            <td>$row1->no_bukti</td>
                                            <td>$row1->keterangan</td>
                                            <td>".number_format($row1->debet,0,",",".")."</td>
                                            <td>".number_format($row1->kredit,0,",",".")."</td>
                                            <td>".number_format($total_saldo,0,",",".")."</td>
                                        </tr>";
                                    $i++;
                                }

                                echo "<tr>
                                        <td style='text-align:right;' colspan='4'><strong>Total</strong></td>
                                        <td>".number_format($total_d,0,",",".")."</td>
                                        <td>".number_format($total_k,0,",",".")."</td>
                                        <td></td>
                                    </tr>";

                                if($total_saldo > 0){
                                    echo "<tr>
                                        <td style='text-align:right;' colspan='6'><strong>Saldo</strong></td>
                                        <td>".number_format($total_saldo)."</td>
                                        </tr>";
                                }else{
                                   echo "<tr>
                                        <td style='text-align:right;' colspan='6'><strong>Saldo</strong></td>
                                        <td></td>
                                        </tr>";
                                }
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