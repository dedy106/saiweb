<?php
$kode_lokasi=$_SESSION['lokasi'];
$periode=$_SESSION['periode'];
$kode_pp=$_SESSION['kodePP'];
$nik=$_SESSION['userLog'];

$path = "http://".$_SERVER["SERVER_NAME"]."/";	

$logo = $path . "assets/img/yspt2.png";

$tmp=explode("|",$_GET['param']);
$stsPrint=$_GET['print'];
?>


<div class="container-fluid mt-3">
    <div class="row" id="saku-data-profile">
        <div class="col-12">
            <div class="card">
                <?php
                
                if(ISSET($_GET['print']) OR ISSET($_GET['download'])){
                    echo"";
                } else{
                    echo "
                    <div class='card-header'>
                        <a  href='$root_print/dashPiutang/?print=1' target='_blank' class='btn btn-primary float-right'>
                            <i class='fa fa-print'></i> Print
                        </a>
                    </div>
                    ";
                }
                ?>
                <style>
                td,th{
                    padding:4px !important;
                }
                </style>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <h4 class="page-header" style="overflow:hidden;" >
                                <img src='<?php echo $logo; ?>' style='height: 80px;float: left;margin-right: 10px;margin-left: 10px; '>
                                <div id='headPiu'> 
                                </div>
                            </h4>
                        </div>
                    <!-- /.col -->
                    </div>
                    <!-- info row -->
                    <div class="row">
                        <div class='text-center col-12'>
                            <b style="font-size:large;"><u>KARTU SISWA</u></b><br>
                        </div>
                        <div id='tgh-identitas' class='col-12'>
                            <div class="row">
                                <strong style='padding-left:10px;'>Identitas Siswa</strong>
                            </div>
                            <div class="row">
                                <div class='col-2'>
                                    NIS<br>
                                    ID Bank<br>
                                    Nama<br>
                                    Kelas<br>
                                    Angkatan<br>
                                    Jurusan<br>
                                    Saldo PDD
                                </div>
                                <div class='col-1'>
                                    :<br>
                                    :<br>
                                    :<br>
                                    :<br>
                                    :<br>
                                    :<br>
                                    :
                                </div>
                                <div class='col-9' id='subPiu'>
                               
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.row -->

                    <!-- Table row -->
                    <div class="row mt-3">
                        <div class="col-12 table-responsive">
                            <table class="table table-striped" id='table-piu'>
                                <thead style='background:#4286f5'>
                                    <tr>
                                        <th style="color:white !important;">No</th>
                                        <th style="color:white !important;">Tanggal</th>
                                        <th style="color:white !important;">No Bukti</th>
                                        <th style="color:white !important;">Parameter</th>
                                        <th style="color:white !important;">Keterangan</th>
                                        <th style="color:white !important;">Tagihan</th>
                                        <th style="color:white !important;">Pembayaran</th>
                                        <th style="color:white !important;">Saldo</th>
                                    </tr>
                                </thead>
                                <tbody >
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
<script>
var stsPrint = "<?=$stsPrint?>";
var root = "<?php echo $root_print; ?>";
var $pr = false;
if(stsPrint == 0) { 
    var printValue = false;
}else{
    var printValue = true ;
}

function printPiu(){
    window.open(root+'/dashPiutang/?print='+stsPrint,'_blank'); 
}

function executePrint(){
    setTimeout(function() { 
      window.print();
    }, 1500);
}

function sepNum(x){
    var num = parseFloat(x).toFixed(0);
    var parts = num.toString().split(".");
    var len = num.toString().length;
    // parts[1] = parts[1]/(Math.pow(10, len));
    parts[0] = parts[0].replace(/(.)(?=(.{3})+$)/g,"$1.");
    return parts.join(",");
}

function loadPiutang(){
    $.ajax({
        type: 'GET',
        url: '<?=$root_ser?>/Piutang.php?fx=getPiutang',
        dataType: 'json',
        async:false,
        data: {'kode_lokasi':'<?=$kode_lokasi?>','nik':'<?=$nik?>','kode_pp':'<?=$kode_pp?>'},
        success:function(result){    
            if(result.status){
                var html = '';
                var line1= result.daftar[0];
                var headPiu = `<b class='tgh-nama-pp'>`+line1.nama_pp+`</b><br>
                                <small class='tgh-alamat-pp'>`+line1.alamat+`</small><br>
                                <small class='tgh-alamat2-pp'>`+line1.alamat2+`</small>
                              `;
                var subPiu =`<strong>`+line1.nis+`</strong><br>`+line1.id_bank+`<br>`+line1.nama+`<br>`+line1.nama_kelas+`<br>`+line1.kode_akt+`<br>`+line1.nama_jur+`<br>`+sepNum(result.so_akhir);

                $('#headPiu').html(headPiu);
                $('#subPiu').html(subPiu);
                var tgh_tabel = "";
                var total_tgh= 0;
                var total_pmb = 0;
                var total_saldo = 0;
                var total_jml = 0;
                var no=1;
                for(var i =0; i<result.daftar2.length;i++){
                    
                    var line= result.daftar2[i];
                    total_tgh +=+parseFloat(line.tagihan);
                    total_pmb +=+parseFloat(line.bayar);
                    total_saldo +=+parseFloat(line.tagihan) - parseFloat(line.bayar);
                    html+=`<tr>
                        <td>`+no+`</td>
                        <td>`+line.tgl+`</td>
                        <td>`+line.no_bukti+`</td>
                        <td>`+line.kode_param+`</td>
                        <td>`+line.keterangan+`</td>
                        <td class='text-right'>`+sepNum(line.tagihan)+`</td>
                        <td class='text-right'>`+sepNum(line.bayar)+`</td>
                        <td class='text-right'>`+sepNum(parseFloat(line.tagihan)-parseFloat(line.bayar))+`</td>
                    </tr>`;
                    no++;
                }
                html+=`<tr>
                        <td style='text-align:right;' colspan='5'><strong>Total</strong></td>
                        <td class='text-right'>`+sepNum(total_tgh)+`</td>
                        <td class='text-right'>`+sepNum(total_pmb)+`</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style='text-align:right;' colspan='7'><strong>Saldo</strong></td>
                        <td class='text-right'>`+sepNum(total_saldo)+`</td>
                    </tr>`;
                
                $('#table-piu tbody').html(html);
               
                if(printValue){
                   executePrint();
                }
                
            }
        }
    });
}

loadPiutang();
</script>