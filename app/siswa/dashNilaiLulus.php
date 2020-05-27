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
                                
                                    $bio = "select a.no_reg, a.id_bank, a.nama, a.kode_ta, g.nama as nama_pp,g.alamat,g.alamat2, c.nilai, c.keterangan from sis_siswareg a inner join sis_sekolah g on a.kode_pp=g.kode_pp and a.kode_lokasi=g.kode_lokasi left join sis_hakakses f on a.no_reg=f.nik and a.kode_lokasi=f.kode_lokasi and a.kode_pp=f.kode_pp inner join sis_nilai_reg c on a.no_reg=c.no_reg and a.kode_pp=c.kode_pp and a.kode_lokasi=c.kode_lokasi where a.no_reg='$nik' AND a.kode_lokasi ='$kode_lokasi' AND a.kode_pp = '$kode_pp'";

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
                            <b style="font-size:large;"><u>KETERANGAN LULUS</u></b><br>
                        </center>
                        <div id='tgh-identitas'>
                        <strong style='padding-left:15px;'>Identitas Siswa</strong><br>
                                <div class='col-xs-2 invoice-col'>
                                    <address>
                                        ID Registrasi<br>
                                        Nama<br>
                                        Status<br>
                                        Nilai<br>
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
                                    <?php echo $row->nama; ?><br>
                                    <?php echo $row->keterangan; ?><br>
                                    <?php echo $row->nilai; ?><br>
                                    </address>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>