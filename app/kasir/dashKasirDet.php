<?php
 session_start();
 $root_lib=$_SERVER["DOCUMENT_ROOT"];
 if (substr($root_lib,-1)!="/") {
     $root_lib=$root_lib."/";
 }
 include_once($root_lib.'app/kasir/setting.php');

$tmp = explode("|",$_GET['param']);
$kode_lokasi=$tmp[0];
$periode=$tmp[1];
$kode_pp=$tmp[2];
$nik=$tmp[3];
$kode_fs=$tmp[4];
$kunci = $tmp[5];

// echo $_GET["param"];

$tahun = substr($periode,0,4);
$bulan = substr($periode,5,2);

$sql2 = "SELECT DATEADD(s,-1,DATEADD(mm, DATEDIFF(m,0,'$tahun-$bulan-01')+1,0))";

$rs=execute($sql2);
$temp = explode(" ",$rs->fields['0']);
$tgl_akhir=$temp[0];

$path = $folderroot_img;
$icon_back = $path."/icon_back.png";
$icon_close = $path."/icon_close.png";
$icon_refresh = $path."/icon_refresh.png";

$root_ser = $root_ser="http://".$_SERVER['SERVER_NAME']."/server/kasir";

?>
<style>
            /* @import url('https://fonts.googleapis.com/css?family=Roboto&display=swap'); */
            @font-face {
                font-family: "Roboto";
                src: url(<?=$root?>'/assets/fonts/Roboto/Roboto-Regular.ttf');
            }
            
            body {
                font-family: 'Roboto', sans-serif !important;
            }
            h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6 {
                font-family: 'Roboto', sans-serif !important;
                font-weight: normal !important;
            }
            h3{
                margin-bottom: 5px;
                font-size:18px !important
            }
            h2{
                margin-bottom: 5px;
                margin-top:10px;
            }
            .judul-box{
                font-weight:bold;
                font-size:18px !important;
            }
            .inner{
                padding:5px !important;
            }
            
            .box-nil{
                margin-bottom: 20px !important;
            }
            
            .pad-more{
                padding-left:10px !important;
                padding-right:0px !important;
            }
            .mar-mor{
                margin-bottom:10px !important;
            }
            .box-wh{
                box-shadow: 0 3px 3px 3px rgba(0,0,0,.05);
            }
            #dash_chart_alokasi_kas {
                max-width: 400px;
                height: 70px;
                
            }
            #dash_chart_alokasi_ebt {
                max-width: 400px;
                height: 70px;
                
            }
            #dash_chart_alokasi_saham {
                max-width: 400px;
                height: 70px;
                
            }
            #dash_chart_alokasi_propensa {
                max-width: 400px;
                height: 70px;
                
            }
            .bg-blue{
                background: #007AFF !important;
            }
            .bg-green{
                background: #4CD964 !important;
            }
            .bg-maroon{
                background: #FF2D55 !important;
            }
            .bg-yellow{
                background: #ff9500 !important;
            }
            .viewVendor{
                cursor:pointer;
            }
            .viewMissing{
                cursor:pointer;
            }
        </style>
    
<div class="container-fluid mt-3">
        <div class='card' style=''>
            <div class='card-heading' style='font-size:18px;padding:10px 0px 1px 20px;background:#f6f6f6;'> 
                <div class='float-right navigasi' style='margin-right: 10px;'>
                    <span id='back_btn' style='cursor:pointer'><img src='<?=$icon_back?>' width='25px'></span>
                    <span id='refresh_btn' style='cursor:pointer'><img src='<?=$icon_refresh?>' width='25px'></span>
                    <span id='close_btn'style='cursor:pointer'><img src='<?=$icon_close?>' width='25px'></span>
                </div>
            <?php switch($kunci){ 
                case "topSell":
                case "sellCtg":
            ?>
                <div class='row invoice-info' style='background:#f6f6f6;margin-left:0px;margin-right:0px;margin-top:30px'>
                    <div class='col-md-2 invoice-col'>
                        <address>
                            <strong>
                            Periode Awal
                            </strong>
                            <br>
                            <style>
                            .selectize-input{
                                border:none;
                                border-bottom:1px solid #8080806b;
                            }
                            </style>
                            <select class='form-control input-sm selectize' id='periode_awal' style='margin-bottom:5px;border:none;border-bottom:1px solid #8080806b'>
                            <option value=''>Pilih Periode</option>
                            <?php
                            $sql = "select distinct periode from brg_trans_dloc where kode_lokasi='$kode_lokasi' order by periode desc";
                            $rsper = execute($sql);
                            while ($row = $rsper->FetchNextObject($toupper=false)) {
                                echo"<option value='$row->periode'>$row->periode</option>";
                            }
                            ?>
                            </select>
                        </address>
                    </div>
                    <div class='col-md-2 invoice-col'>
                        <address>
                            <strong>
                            Periode Akhir
                            </strong>
                            <br>
                            <style>
                            .selectize-input{
                                border:none;
                                border-bottom:1px solid #8080806b;
                            }
                            </style>
                            <select class='form-control input-sm selectize' id='periode_akhir' style='margin-bottom:5px;border:none;border-bottom:1px solid #8080806b'>
                            <option value=''>Pilih Periode</option>
                            <?php 
                            $sql2 = "select distinct periode from brg_trans_dloc where kode_lokasi='$kode_lokasi' order by periode desc";
                            $rsper2 = execute($sql2);
                            while ($row = $rsper2->FetchNextObject($toupper=false)) {
                                echo"<option value='$row->periode'>$row->periode</option>";
                            }
                            ?>
                            </select>
                        </address>
                    </div>
                    <div class='col-md-3 invoice-col'>
                        <address>
                            <strong>
                            Kategori Barang
                            </strong>
                            <br>
                            <style>
                            .selectize-input{
                                border:none;
                                border-bottom:1px solid #8080806b;
                            }
                            </style>
                            <select class='form-control input-sm selectize' id='kode_klp' style='margin-bottom:5px;border:none;border-bottom:1px solid #8080806b'>
                            <option value=''>Pilih Kategori</option>
                            <?php
                            $sql = "select distinct kode_klp,nama from brg_barangklp where kode_lokasi='$kode_lokasi' order by kode_klp";
                            $rsper = execute($sql);
                            while ($row = $rsper->FetchNextObject($toupper=false)) {
                                echo"<option value='$row->kode_klp'>$row->kode_klp - $row->nama</option>";
                            }
                            ?>
                            </select>
                        </address>
                    </div>
                    <div class='col-md-2 invoice-col'>
                        <address>
                            <strong>
                            Urutkan
                            </strong>
                            <br>
                            <style>
                            .selectize-input{
                                border:none;
                                border-bottom:1px solid #8080806b;
                            }
                            </style>
                            <select class='form-control input-sm selectize' id='order_by' style='margin-bottom:5px;border:none;border-bottom:1px solid #8080806b'>
                            <option value=''>Pilih Pengurutan</option>
                            <option value='a.kode_barang'>Kode Produk</option>
                            <option value='a.nama'>Nama Produk</option>
                            <option value='b.jumlah'>Terjual</option>
                            </select>
                        </address>
                    </div>
                    <div class='col-md-2 invoice-col'>
                        <strong>
                        &nbsp;
                        </strong>
                        <address>
                        <a class='btn btn-primary' style='cursor:pointer' id='bTampil'>Tampil</a>
                        </address>
                    </div>        
                </div>
            </div>
            <div class='card-body'>
                <div class='row'>
                    <div class="col-md-12">
                    <h3>LAPORAN PENJUALAN BARANG</h3>
                    <br>
                    <table class='table table-striped' id='table-jual'>
                        <thead class='bg-yellow text-center' >
                            <tr>
                                <td>No</td>
                                <td>Kode Produk</td>
                                <td>Nama Produk</td>
                                <td>Kuantitas</td>
                                <td>Terjual</td>
                                <td>Persentase</td>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    </div>
                </div>
            </div>
            <script>
            $('#bTampil').click(function(){
                var per1 = $('#periode_awal')[0].selectize.getValue();
                var per2 = $('#periode_akhir')[0].selectize.getValue();
                var kode_klp = $('#kode_klp')[0].selectize.getValue();
                var order_by = $('#order_by')[0].selectize.getValue();
                
                loadService('lapPnj','GET','<?=$root_ser?>/dashKasir.php?fx=getLapPnj',per1+'|'+per2+'|'+kode_klp+'|'+order_by);
    
            });
            </script>
            <?php
                break;
                case "vendor":
            ?>
                <div class='row invoice-info' style='background:#f6f6f6;margin-left:0px;margin-right:0px;margin-top:30px'>
                    <div class='col-md-2 invoice-col'>
                        <address>
                            <strong>
                            Urutkan
                            </strong>
                            <br>
                            <style>
                            .selectize-input{
                                border:none;
                                border-bottom:1px solid #8080806b;
                            }
                            </style>
                            <select class='form-control input-sm selectize' id='order' style='margin-bottom:5px;border:none;border-bottom:1px solid #8080806b'>
                            <option value=''>Pilih Pengurutan</option>
                            <option value='a.kode_vendor'>Kode Vendor</option>
                            <option value='a.nama'>Nama Vendor</option>
                            <option value='b.total'>Nilai</option>
                            </select>
                        </address>
                    </div>
                    <div class='col-md-2 invoice-col'>
                        <address>
                            <strong>
                            Cari vendor
                            </strong>
                            <br>
                            <input type='text' name='cari' id='cari' class='form-control'>
                        </address>
                    </div>
                    <div class='col-md-2 invoice-col'>
                        <strong>
                        &nbsp;
                        </strong>
                        <address>
                        <a class='btn btn-primary' style='cursor:pointer' id='bTampil'>Tampil</a>
                        </address>
                    </div>        
                </div>
            </div>
            <div class='card-body'>
                <div class='row'>
                    <div class="col-md-12">
                    <h3 class='text-center'>LAPORAN VENDOR</h3>
                    <br>
                    <table class='table table-striped' id='table-vendor'>
                        <thead class='bg-yellow'>
                            <tr>
                                <td>No</td>
                                <td>Kode Vendor</td>
                                <td>Nama Vendor</td>
                                <td>Nilai</td>
                                <td>Nilai Jatuh Tempo</td>
                                <td>Tanggal Jatuh Tempo</td>
                                <td>Invoice Jatuh Tempo</td>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    </div>
                </div>
                <div class='modal fade' id='modalVendor' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'>
                    <div class='modal-dialog' role='document'>
                        <div class='modal-content'>
                            <div class='modal-header'>
                                <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                                <h4 class='modal-title' id='myModalLabel'></h4>
                            </div>
                            <div class='modal-body'>
                                <div class='row'>
                                    <div class='col-md-3'>Kode Vendor
                                    </div>
                                    <div class='col-md-9' id='kd_vendor'>
                                    </div>
                                </div>
                                <div class='row'>
                                    <div class='col-md-3'>Nama Vendor
                                    </div>
                                    <div class='col-md-9' id='nm_vendor'>
                                    </div>
                                </div>
                                <div class='row'>
                                    <div class='col-md-3'>Alamat
                                    </div>
                                    <div class='col-md-9' id='alamat'>
                                    </div>
                                </div>
                                <div class='row'>
                                    <div class='col-md-3'>No Telp
                                    </div>
                                    <div class='col-md-9' id='no_telp'>
                                    </div>
                                </div>
                                <div class='row'>
                                    <div class='col-md-12'>
                                    <table class='table table-striped' id='detVendor'>
                                        <thead class='bg-yellow'>
                                            <tr>
                                                <td>No</td>
                                                <td>No Invoice</td>
                                                <td>Nilai Tagihan</td>
                                            </tr>
                                        </thead>
                                    </table>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <script>
            $('#bTampil').click(function(){
                var order_by = $('#order')[0].selectize.getValue();
                var vendor = $('#cari').val();
                
                loadService('lapVendor','GET','<?=$root_ser?>/dashKasir.php?fx=getLapVendor',order_by+'|'+vendor);
    
            });
            </script>
            <?php
                break;
                case "missing":
            ?>
            </div>
            <div class='card-body'>
                <div class='row'>
                    <div class="col-md-12">
                    <h3 class='text-center'>LAPORAN STOK OPNAME</h3>
                    <br>
                    <table class='table table-striped' id='table-stock'>
                        <thead class='bg-yellow'>
                            <tr>
                                <td>No</td>
                                <td>Tanggal</td>
                                <td>Jumlah Checking</td>
                                <td>Perbedaan</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class='viewMissing'>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                    </div>
                </div>
                <div class='modal fade' id='modalStok' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'>
                    <div class='modal-dialog' role='document'>
                        <div class='modal-content'>
                            <div class='modal-header'>
                                <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                                <h4 class='modal-title' id='myModalLabel'></h4>
                            </div>
                            <div class='modal-body'>
                                <div class='row'>
                                    <div class='col-md-3'>Tanggal Stok Opname
                                    </div>
                                    <div class='col-md-9' id='tgl_stok'>
                                    </div>
                                </div>
                                <div class='row'>
                                    <div class='col-md-3'>Persentase Perbedaan
                                    </div>
                                    <div class='col-md-9' id='persen'>
                                    </div>
                                </div>
                                <div class='row'>
                                    <div class='col-md-12'>
                                    <table class='table table-striped' id='detMissing'>
                                        <thead class='bg-yellow'>
                                            <tr>
                                                <td>No</td>
                                                <td>NIK</td>
                                                <td>Nama</td>
                                                <td>No Telp</td>
                                                <td>Perbedaan</td>
                                            </tr>
                                        </thead>
                                    </table>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
                break;
                case "cash":
            ?>
                <div class='row invoice-info' style='background:#f6f6f6;margin-left:0px;margin-right:0px;margin-top:30px'>
                    <div class='col-md-2 invoice-col'>
                        <address>
                            <strong>
                            Periode
                            </strong>
                            <br>
                            <span id='per'></span>
                        </address>
                    </div>
                    <div class='col-md-3 invoice-col'>
                        <address style='margin-bottom:0px'>
                            <strong>
                            Tgl Awal
                            </strong>
                            <br>
                            <div class='input-group date col-md-9' style=''>
                                <div class='input-group-addon'>
                                <i class='fa fa-calendar'></i>
                                </div>
                                <input name='tgl_awal' class='form-control' value='' id='tgl-awal'>
                            </div>
                        </address>
                    </div>
                    <div class='col-md-3 invoice-col'>
                        <address style='margin-bottom:0px'>
                            <strong>
                            Tgl Akhir
                            </strong>
                            <br>
                            <div class='input-group date col-md-9' style=''>
                                <div class='input-group-addon'>
                                <i class='fa fa-calendar'></i>
                                </div>
                                <input name='tgl_akhir' class='form-control' id='tgl-akhir' value=''>
                            </div>
                        </address>
                    </div>
                    <div class='col-md-2 invoice-col'>
                        <address>
                            <strong>
                            Kode Akun
                            </strong>
                            <br>
                            <style>
                            .selectize-input{
                                border:none;
                                border-bottom:1px solid #8080806b;
                            }
                            </style>
                            <select class='form-control input-sm selectize' id='kd_akun' style='margin-bottom:5px;border:none;border-bottom:1px solid #8080806b'>
                            <option value=''>Pilih Akun</option>
                            </select>
                        </address>
                    </div>
                    <div class='col-md-2 invoice-col'>
                        <strong>
                        &nbsp;
                        </strong>
                        <address>
                        <a class='btn btn-primary' style='cursor:pointer' id='bTampil'>Tampil</a>
                        </address>
                    </div>        
                </div>
            </div>
            <div class='card-body'>
                <div id='isiBukuBesar'>
                </div>
            </div>
            <div class='modal fade' id='modalJurnal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'>
                <div class='modal-dialog modal-lg' role='document'>
                    <div class='modal-content'>
                        <div class='modal-header'>
                            <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                            <h4 class='modal-title' id='myModalLabel'></h4>
                        </div>
                        <div class='modal-body'>
                            <div class='row'>
                            <div class='col-md-12'>
                            <div class='box-header with-border'>
                                <i class='fa fa-list-alt'></i>
                                <h3 class='box-title'>Jurnal</h3>
                            </div>
                            <div class='box-body'>
                                <div class='row invoice-info' style='margin-bottom:30px'>
                                    <div class='col-md-2 invoice-col'>
                                    <address>
                                        <strong>
                                        No Bukti
                                        </strong>
                                        <br>
                                        Tanggal
                                    </address>
                                    </div>
                                    <div class='col-md-4 invoice-col'>
                                        <address>
                                            <strong id='no_bukti'>
                                            </strong>
                                            <br>
                                            <span id='tgl'></span>
                                        </address>
                                    </div>
                                </div>
                                <!-- /.row -->
                                <!-- Table row -->
                                <div class='row'>
                                    <div class='col-md-12 table-responsive'>
                                    <table class='table table-striped' id='detJurnal'>
                                        <thead>
                                        <tr>
                                            <th width='30' >NO</th>
                                            <th width='100' >KODE AKUN </th>
                                            <th width='200' >NAMA AKUN </th>
                                            <th width='270' >KETERANGAN</th>
                                            <th width='60' >PP</th>
                                            <th width='100' >DEBET</th>
                                            <th width='100' >KREDIT</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                    </div>
                                    <!-- /.col -->
                                </div>
                                <!-- /.row -->
                                <div class='row'>
                                    
                                </div>
                                <!-- /.row -->
                            </div>
                        </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script>
            function getAkun(){
                $.ajax({
                    type: 'GET',
                    url: '<?=$root_ser?>/dashKasir.php?fx=getAkun',
                    dataType: 'json',
                    data: {'periode':'<?=$periode?>'},
                    success:function(result){    
                        if(result.status){
                            if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                                for(i=0;i<result.daftar.length;i++){
                                    $('#kd_akun')[0].selectize.addOption([{text:result.daftar[i].kode_akun + ' - ' + result.daftar[i].nama, value:result.daftar[i].kode_akun}]);  
                                }
                            }
                        }
                    }
                });
            }

            var from = $('#tgl-awal').datepicker({ autoclose: true,format:'yyyy-mm-dd',startDate: new Date('<?=$tahun."-".$bulan."-01"?>'),endDate: new Date('<?=$tgl_akhir?>') }).on('changeDate', function(e){
                $('#tgl-akhir').datepicker({ autoclose: true,format:'yyyy-mm-dd'}).datepicker('setStartDate', e.date).datepicker('setEndDate',new Date('<?=$tgl_akhir?>')).focus();
            });
    
            $('.datepicker, .daterangepicker,.tgl-awal').on('keydown keyup keypress', function(e){
                e.preventDefault();
                return false;
            });
    
            $('#bTampil').click(function(){
                var akun = $('#kd_akun')[0].selectize.getValue();
                var tgl1 = $('#tgl-awal').val();
                var tgl2 = $('#tgl-akhir').val();
                
                loadService('getBukuBesar','GET','<?=$root_ser?>/dashKasir.php?fx=getBukuBesar',akun+'|'+tgl1+'|'+tgl2);
    
            });

            getAkun();

            $('#isiBukuBesar').on('click','.viewJurnal',function(){
                var tgl = $(this).closest('tr').find('td').eq(2).html();
                var no_bukti = $(this).closest('tr').find('a').text();
                loadService('getJurnal','GET','<?=$root_ser?>/dashKasir.php?fx=getJurnal',no_bukti+'|'+tgl);
                $('#modalJurnal').modal('show');
            });
            </script>
            <?php
                break;

            }
            ?>
        </div>
    </div>
        <script>
            $('#per').text('<?=$periode?>');

            $('.navigasi').on('click','#close_btn',function(){
                window.history.go(-1); 
                return false;
            });
            $('.navigasi').on('click','#back_btn',function(){
                window.history.go(-1); 
                return false;
            });

            $('.navigasi').on('click','#refresh_btn',function(){
                location.reload();
            });

            $('#table-vendor').on('click','.viewVendor',function(){
                $('#modalVendor').modal('show');
            });

            $('#table-stock').on('click','.viewMissing',function(){
                $('#modalStok').modal('show');
            });

            function sepNum(x){
                var num = parseFloat(x).toFixed(2);
                var parts = num.toString().split('.');
                var len = num.toString().length;
                // parts[1] = parts[1]/(Math.pow(10, len));
                parts[0] = parts[0].replace(/(.)(?=(.{3})+$)/g,'$1.');
                return parts.join(',');
            }
            function sepNumPas(x){
                var num = parseInt(x);
                var parts = num.toString().split('.');
                var len = num.toString().length;
                // parts[1] = parts[1]/(Math.pow(10, len));
                parts[0] = parts[0].replace(/(.)(?=(.{3})+$)/g,'$1.');
                return parts.join(',');
            }

            function toJuta(x) {
                var nil = x / 1000000;
                return sepNum(nil) + '';
            }

            function loadService(index,method,url,param=null){
                $.ajax({
                    type: method,
                    url: url,
                    dataType: 'json',
                    data: {'periode':'<?=$periode?>','param':param},
                    success:function(result){    
                        if(result.status){
                            switch(index){
                                case 'getBukuBesar' :
                                var html='';
                                for(var i=0;i<result.daftar.length;i++){
                                    var line=result.daftar[i];
                                    html += `<div class='row'>
                                        <div class='col-md-12 table-responsive'>
                                            <table class='table table-striped' id='table-BB'>
                                                <thead class='bg-yellow'>
                                                    <tr>
                                                    <th width='100' height='23' >No Bukti</th>
                                                    <th width='80' height='23' >No Dokumen</th>
                                                    <th width='60' >Tanggal</th>
                                                    <th width='250' >Keterangan</th>
                                                    <th width='60' >Kode PP</th>
                                                    <th width='90' >Debet</th>
                                                    <th width='90' >Kredit</th>
                                                    <th width='90' >Balance</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td colspan='6'>Kode Akun : `+line.kode_akun+` | `+line.nama+`</td>
                                                    <td height='23' class='header_laporan' align='right'><b>Saldo Awal<b> </td>
                                                    <td class='header_laporan' align='right'>`+sepNumPas(line.so_awal)+`</td>
                                                </tr>`;
                                                var detHtml ='';
                                                var saldo=line.so_awal;
                                                var debet=0;
                                                var kredit=0;
                                                for(var j=0;j<result.daftar2.length;j++){
                                                    var line2 = result.daftar2[j];
                                                    if(line2.kode_akun == line.kode_akun){

                                                        saldo = parseInt(saldo) + parseInt(line2.debet) - parseInt(line2.kredit);	
                                                        debet=debet + parseInt(line2.debet);
                                                        kredit=kredit + parseInt(line2.kredit);	
                                                        detHtml +=`<tr><td valign='top' ><a class='viewJurnal' href='#' data-no_bukti='`+line2.no_bukti+`' style='color:blue'>`+line2.no_bukti+`</a></td>
                                                        <td valign='top' >`+line2.no_dokumen+`</td>
                                                        <td height='23' valign='top' >`+line2.tgl+`</td>
                                                        <td valign='top' >`+line2.keterangan+`</td>
                                                        <td valign='top'  >`+line2.kode_pp+`</td>
                                                        <td valign='top'  align='right'>`+sepNumPas(line2.debet)+`</td>
                                                        <td valign='top'  align='right'>`+sepNumPas(line2.kredit)+`</td>
                                                        <td valign='top'  align='right'>`+sepNumPas(saldo)+`</td>
                                                        </tr>`;
                                                    }
                                                }
                                                detHtml +=`
                                                <tr>
                                                    <td height='23' colspan='5' valign='top'  align='right'><b>Total<b>&nbsp;</td>
                                                    <td valign='top'  align='right'><b>`+sepNumPas(debet)+`</b></td>
                                                    <td valign='top'  align='right'><b>`+sepNumPas(kredit)+`</b></td>
                                                    <td valign='top'  align='right'><b>`+sepNumPas(saldo)+`</b></td>
                                                </tr>`;
                                                
                                            html+=detHtml+`
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>`;
                                }
                                $('#isiBukuBesar').html(html);
                                break;
                                case 'lapPnj':
                                var html='';
                                var no =1;
                                for(var i=0;i<result.daftar.length;i++){
                                    var line=result.daftar[i];
                                    html += `<tr>
                                    <td>`+no+`</td>
                                    <td>`+line.kode_barang+`</td>
                                    <td>`+line.nama+`</td>
                                    <td>`+line.stok+`</td>
                                    <td>`+line.jumlah+`</td>
                                    <td>`+line.persen+`</td>
                                    </tr>`;
                                    no++;
                                }
                                $('#table-jual tbody').html(html);
                                break;
                                case 'lapVendor':
                                var html='';
                                var no =1;
                                for(var i=0;i<result.daftar.length;i++){
                                    var line=result.daftar[i];
                                    html += `<tr class='viewVendor'>
                                    <td>`+no+`</td>
                                    <td>`+line.kode_vendor+`</td>
                                    <td>`+line.nama+`</td>
                                    <td>`+line.total+`</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    </tr>`;
                                    no++;
                                }
                                $('#table-vendor tbody').html(html);
                                break;
                                case 'getJurnal':
                                $('#no_bukti').text(result.no_bukti);
                                $('#tgl').text(result.tgl);
                                var html='';
                                var no =1;
                                var tot_debet=0;
                                var tot_kredit=0;
                                for(var i=0;i<result.daftar.length;i++){
                                    var line=result.daftar[i];
                                    var debet=sepNumPas(line.debet);
                                    var kredit=sepNumPas(line.kredit);
                                    var tot_debet=parseInt(tot_debet)+parseInt(line.debet);
                                    var tot_kredit=parseInt(tot_kredit)+parseInt(line.kredit);
                                    html += `<tr>
                                    <td>`+no+`</td>
                                    <td>`+line.kode_akun+`</td>
                                    <td>`+line.nama_akun+`</td>
                                    <td>`+line.keterangan+`</td>
                                    <td>`+line.kode_pp+`</td>
                                    <td class='text-right'>`+sepNumPas(line.debet)+`</td>
                                    <td class='text-right'>`+sepNumPas(line.kredit)+`</td>
                                    </tr>`;
                                    no++;
                                }
                                tot_debet1=sepNumPas(tot_debet);
                                tot_kredit1=sepNumPas(tot_debet);
                                html+=`<tr>
                                            <td colspan='5'  align='right'><b>Total</b></td>
                                            <td  align='right'><b>`+tot_debet1+`</b></td>
                                            <td  align='right'><b>`+tot_kredit1+`</b></td>
                                        </tr>`;
                                $('#detJurnal tbody').html(html);
                                break;
                            }
                        }
                    }
                });
            }
            function initDash(){
                var kunci = '<?=$kunci?>';
                switch(kunci){
                    case "topSell" :
                    case "sellCtg" :
                    loadService('getBukuBesar','GET','<?=$root_ser?>/dashKasir.php?fx=getBukuBesar'); 
                    loadService('lapPnj','GET','<?=$root_ser?>/dashKasir.php?fx=getLapPnj'); 
                    break;
                    case "vendor":
                    loadService('lapVendor','GET','<?=$root_ser?>/dashKasir.php?fx=getLapVendor');  
                    break;
                    case "missing":
                    break;
                    case "cash":
                    loadService('getBukuBesar','GET','<?=$root_ser?>/dashKasir.php?fx=getBukuBesar'); 
                     break;
                
                }
            }

            initDash();
            </script>
