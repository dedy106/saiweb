<?php
 session_start();
 $root_lib=$_SERVER["DOCUMENT_ROOT"];
 if (substr($root_lib,-1)!="/") {
     $root_lib=$root_lib."/";
 }
 include_once($root_lib.'app/apv/setting.php');

$kode_lokasi=$_SESSION['lokasi'];
$periode=$_SESSION['periode'];
$kode_pp=$_SESSION['kodePP'];
$nik=$_SESSION['userLog'];
$resource = $_GET["resource"];
$fullId = $_GET["fullId"];

$tmp = explode("|",$_GET['param']);
$no_bukti=$tmp[0];
$jenis = $tmp[1];

$path = "http://".$_SERVER["SERVER_NAME"]."/";	
$poly1 = $path."image/Polygon1.png";
$poly2 = $path."image/Polygon12.png";
$group12 = $path."image/Group12.png";
$group13 = $path."image/Group13.png";
// $backto="$root_app/dashMobile";
$mobile=true;
$judul = "Detail Pengajuan";
if($jenis == 'aju'){
    $style="";
    $backto="$root_app/dashMobile";  
}else{
    $style="display:none";
    $backto="$root_app/dashHistory";
}
?>
<style>
@import url('https://fonts.googleapis.com/css?family=Roboto&display=swap');


body {
    font-family: 'Roboto', sans-serif !important;
}
h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6 {
    font-family: 'Roboto', sans-serif !important;
    font-weight: normal !important;
}
h2{
    margin-bottom: 5px;
    margin-top:5px;
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
.small-box .icon{
    top: 0px !important;
    font-size: 20px !important;
}
.bg-white{
    background:white
}
.small-box .inner{
    background: white;
    border: 1px solid white;
    border-radius: 10px !important;
}
.small-box{
    border-radius:10px !important;
    box-shadow: 1px 2px 2px 2px #e6e0e0e6;
}
.widget-user-2 .widget-user-header {

    padding: 20px;
    border-top-right-radius: 10px;
    border-top-left-radius: 10px;
    box-shadow: 1px 2px 2px 2px #e6e0e0e6;
}
.icon-green {
    color:white;
    background: #00a65a;
    border: 1px solid #00a65a;
    padding: 2px;
    font-size: 12px;
    transition: all .3s linear;
    position: absolute;
    top: -10px;right: 10px;
    z-index: 0;
    padding: 2px 12px;
    border-bottom-left-radius: 15px;
    border-bottom-right-radius: 15px;
    margin-right: 10px;
}
.icon-blue {
    color:white;
    background: #0073b7;
    border: 1px solid #0073b7;
    border-bottom-left-radius: 10px;
    border-bottom-right-radius: 10px;
    padding: 2px;
    font-size: 12px;
    transition: all .3s linear;
    position: absolute;
    top: -10px;right: 10px;
    z-index: 0;
    padding: 2px 12px;
    border-bottom-left-radius: 15px;
    border-bottom-right-radius: 15px;
    margin-right: 10px;
}
.icon-purple {
    color:white;
    background: #605ca8 !important;
    border: 1px solid #605ca8 !important;
    border-bottom-left-radius: 10px;
    border-bottom-right-radius: 10px;
    padding: 2px;
    font-size: 12px;
    transition: all .3s linear;
    position: absolute;
    top: -10px;right: 10px;
    z-index: 0;
    padding: 2px 12px;
    border-bottom-left-radius: 15px;
    border-bottom-right-radius: 15px;
    margin-right: 10px;
}
.icon-pink {
    color:white;
    background: #d81b60;
    border: 1px solid #d81b60;
    border-bottom-left-radius: 10px;
    border-bottom-right-radius: 10px;
    padding: 2px;
    font-size: 12px;
    transition: all .3s linear;
    position: absolute;
    top: -10px;right: 10px;
    z-index: 0;
    padding: 2px 12px;
    border-bottom-left-radius: 15px;
    border-bottom-right-radius: 15px;
    margin-right: 10px;
}
.box-footer {

border-top-left-radius: 0;
border-top-right-radius: 0;
border-bottom-right-radius: 10px;
border-bottom-left-radius: 10px;
border-top: 1px solid #f4f4f4;
padding: 10px;
background-color: #fff;
box-shadow: 1px 2px 2px 2px #e6e0e0e6;

}

.box-nil{
    margin-bottom: 20px !important;
}

.icon{
    padding: 2px 12px;
    border-bottom-left-radius: 15px;
    border-bottom-right-radius: 15px;
}

.judulBox:hover{
    color:#0073b7
}

.row {
    display:block;
}
</style>

<div class="container-fluid mt-2">
    <div class="row">
        <?php 
            include($root2.'app/apv/back.php');
        ?>
        <form id="form-approve">
            <div class='col-md-12 mb-3' style=''>
                <div class='card' style='border-radius: 5px;border:1px solid rgba(0,0,0,.1);margin-bottom:10px'>
                    <div class='card-body' style='padding:10px 10px 0px 10px'>
                        <div class="row" style='border-bottom: 1px solid #f0eded;padding: 5px 10px;'>
                            <h6 style='margin-bottom:0'>Tanggal</h6>
                            <h6 style='font-weight:bold !important' id='tanggal'></h6>
                        </div>
                        <div class="row" style='border-bottom: 1px solid #f0eded;padding: 5px 10px;'>
                            <h6 style='margin-bottom:0'>Kode PP</h6>
                            <h6 style='font-weight:bold !important' id='kode_pp'></h6>
                        </div>
                        <div class="row" style='border-bottom: 1px solid #f0eded;padding: 5px 10px;'>
                            <h6 style='margin-bottom:0'>No Aju</h6>
                            <h6 style='font-weight:bold !important' id='no_bukti'></h6>
                            <input type="hidden" name="no_aju" id="no_aju" value="">
                            <input type="hidden" name="nu" id="no_urut" value="">
                            <input type="hidden" name="tanggal" id="no_urut" value="<?=date('Y-m-d')?>">
                        </div>
                        <div class="row" style='padding: 10px;'>
                            <h6 style='margin-bottom:0'>No Dokumen</h6>
                            <h6 style='font-weight:bold !important' id='no_dokumen'></h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class='col-md-12 mb-2' style=''>
                <div class='card' style='border-radius: 5px;border:1px solid rgba(0,0,0,.1);margin-bottom:10px'>
                    <div class='card-body' style='padding:10px 10px 0px 10px;min-height: 50px;'>
                        <div style="width: 80px;position: absolute;margin-top: -20px;margin-right:10px">Keterangan</div>
                        <textarea class="form-control" value="" name="keterangan" type="text" style="border: none;color: black;" required id="keterangan"></textarea>
                    </div>
                </div>
            </div>
            <div class='col-md-12 mb-2' style=''>
                <div class='card' style='border-radius: 5px;border:1px solid rgba(0,0,0,.1);margin-bottom:10px'>
                    <div class='card-body' style='padding:10px 10px 0px 10px;min-height: 50px;'>
                        <div style="width: 80px;position: absolute;margin-top: -20px;margin-right:10px"  >Kegiatan</div>
                        <h6 id="kegiatan"></h6>
                    </div>
                </div>
            </div>
            <div class='col-md-12 mb-2' style=''>
                <div class='card' style='border-radius: 5px;border:1px solid rgba(0,0,0,.1);margin-bottom:10px'>
                    <div class='card-body' style='padding:10px 10px 0px 10px;min-height: 50px;'>
                        <div style="width: 80px;position: absolute;margin-top: -20px;margin-right:10px">Dasar</div>
                        <h6 id="dasar"></h6>
                    </div>
                </div>
            </div>
            <div class='col-md-12 mb-2' style=''>
                <div class='card' style='border-radius: 5px;border:1px solid rgba(0,0,0,.1);margin-bottom:10px'>
                    <div class='card-body' style='padding:10px 10px 0px 10px;min-height: 50px;'>
                        <div class="row" style='padding: 5px 10px;'>
                            <h6 style='margin-bottom:0'>Total Nilai Pengajuan</h6>
                            <h6 style='font-weight:bold !important' id="nilai"></h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class='col-md-12 mb-2' style=''>
                <div class='card' style='border-radius: 5px;border:1px solid rgba(0,0,0,.1);margin-bottom:10px'>
                    <div class='card-body' style='padding:0px 10px 0px 10px;min-height: 50px;'>
                        <div class="row" style='padding: 5px 10px;border-bottom: 1px solid #f2f2f2;'>
                            <a style='margin-bottom:0;padding:0' class="btn btn-block text-left"  data-toggle="collapse" href="#rincian" role="button" aria-expanded="true" aria-controls="rincian">Rincian Pengajuan <i class='fas fa-angle-down float-right'></i> </a>
                        </div>
                        <div id="rincian" class="multi-collapse collapse show"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 mb-2" style='<?=$style?>'>
                <button type="button" style='background:#d60d0d' id="btn-approve" class="btn waves-effect waves-light btn-block btn-danger">Approve</button>
            </div>
            <div class="col-lg-2 col-md-4 mb-2" style='<?=$style?>'>
                <button type="button" id="btn-return" class="btn waves-effect waves-light btn-block btn-secondary">Return</button>
            </div>
        </form>
    </div>
</div>
<script>

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

function ubah_bulan(bulan)
{
    var tmp ='';
    switch (bulan) 
    {
        case "01":
            tmp="Januari";
        break;
        case "02":
            tmp="Februari";
        break;
        case "03":
            tmp="Maret";
        break;
        case "04":
            tmp="April";
        break;
        case "05":
            tmp="Mei";
        break;
        case "06":
            tmp="Juni";
        break;
        case "07":
            tmp="Juli";
        break;
        case "08":
            tmp="Agustus";
        break;  
        case "09":
            tmp="September";
        break;  
        case "10":
            tmp="Oktober";
        break;  
        case "11":
            tmp="November";
        break;  
        case "12":
            tmp="Desember";
        break;  
        case "13":
            tmp="Desember 2";
        break;    
        case "14":
            tmp="Desember 3";	      
        break;    
        case "15":
            tmp="Desember 4";	      
        break;    
        case "16":
            tmp="Desember 5";	      
        break;    
    }
    return tmp;
}

var $status = "";
var $iconLoad = $('.preloader');
function loadService(index,method,url,param=null){
    $.ajax({
        type: method,
        url: url,
        dataType: 'json',
        data: {'periode':'<?=$periode?>','param':param,'kode_lokasi':'<?=$kode_lokasi?>','no_aju':'<?=$no_bukti?>'},
        success:function(result){    
            if(result.status){
                switch(index){
                    case 'getDetail' :
                    if(result.daftar.length > 0){
                        $('#tanggal').text(result.daftar[0].waktu.substr(8,2)+' '+ubah_bulan(result.daftar[0].waktu.substr(5,2))+' '+result.daftar[0].waktu.substr(0,4));
                        $('#kode_pp').text(result.daftar[0].kode_pp+' - '+result.daftar[0].nama_pp);
                        $('#no_aju').val(result.daftar[0].no_bukti);
                        $('#no_bukti').text(result.daftar[0].no_bukti);
                        $('#no_urut').val(result.daftar[0].no_urut);
                        $('#no_dokumen').text(result.daftar[0].no_dokumen);
                        $('#kegiatan').text(result.daftar[0].kegiatan);
                        $('#dasar').text(result.daftar[0].dasar);
                        $('#nilai').text(sepNumPas(result.daftar[0].nilai));

                    }
                    var html='';
                    if(result.daftar2.length > 0){
                        for(var i=0;i<result.daftar2.length;i++){
                            var line = result.daftar2[i];
                            html+=`<div class="row" style='padding: 5px 10px;'>
                            <h6 style='margin-bottom:0;font-weight:bold !important'>`+line.barang+`</h6>
                            <p style='margin-bottom:0;'>Qty `+line.jumlah+`</p>
                            <p style='margin-bottom:0;'>Satuan Rp. `+sepNumPas(line.harga)+`</p>
                            <p>Total Rp. `+sepNumPas(line.nilai)+`</p>
                        </div>
                        `;
                        }
                        $('#rincian').html(html);
                    }

                    break;
                    case 'getDetail2' :
                    if(result.daftar.length > 0){
                        $('#tanggal').text(result.daftar[0].waktu.substr(8,2)+' '+ubah_bulan(result.daftar[0].waktu.substr(5,2))+' '+result.daftar[0].waktu.substr(0,4));
                        $('#kode_pp').text(result.daftar[0].kode_pp+' - '+result.daftar[0].nama_pp);
                        $('#no_aju').val(result.daftar[0].no_bukti);
                        $('#no_bukti').text(result.daftar[0].no_bukti);
                        $('#no_urut').val(result.daftar[0].no_urut);
                        $('#no_dokumen').text(result.daftar[0].no_dokumen);
                        $('#kegiatan').text(result.daftar[0].kegiatan);
                        $('#keterangan').val(result.daftar[0].keterangan);
                        $('#dasar').text(result.daftar[0].dasar);
                        $('#nilai').text(sepNumPas(result.daftar[0].nilai));

                    }
                    var html='';
                    if(result.daftar2.length > 0){
                        for(var i=0;i<result.daftar2.length;i++){
                            var line = result.daftar2[i];
                            html+=`<div class="row" style='padding: 5px 10px;'>
                            <h6 style='margin-bottom:0;font-weight:bold !important'>`+line.barang+`</h6>
                            <p style='margin-bottom:0;'>Qty `+line.jumlah+`</p>
                            <p style='margin-bottom:0;'>Satuan Rp. `+sepNumPas(line.harga)+`</p>
                            <p>Total Rp. `+sepNumPas(line.nilai)+`</p>
                        </div>
                        `;
                        }
                        $('#rincian').html(html);
                    }

                    break;
                }
            }
        }
    });
}
function initDash(){
    var jenis = '<?=$jenis?>';
    if(jenis == 'aju'){

        loadService('getDetail','GET','<?=$root_ser?>/Approval.php?fx=getData',''); 
    }else{
        loadService('getDetail2','GET','<?=$root_ser?>/Approval.php?fx=getData2',''); 
    }
     
}
initDash();

$('#form-approve').on('click','#btn-approve',function(e){
    e.preventDefault();
    $status = "2";
    $('#form-approve').submit();
});


$('#form-approve').on('click','#btn-return',function(e){
    e.preventDefault();
    $status = "3";
    $('#form-approve').submit();
});

$('#form-approve').submit(function(e){
    e.preventDefault();
    var formData = new FormData(this);
    
    var nik='<?php echo $nik; ?>' ;
    var kode_lokasi='<?php echo $kode_lokasi; ?>' ;
    
    formData.append('nik_user', nik);
    formData.append('kode_lokasi', kode_lokasi);
    formData.append('status', $status);

    for(var pair of formData.entries()) {
        console.log(pair[0]+ ', '+ pair[1]); 
    }
    $iconLoad.show();
    $.ajax({
        type: 'POST',
        url: '<?=$root_ser?>/Approval.php?fx=simpan',
        dataType: 'json',
        data: formData,
        async:false,
        contentType: false,
        cache: false,
        processData: false, 
        success:function(result){
            // alert('Input data '+result.message);
            if(result.status){
                $iconLoad.hide();
                Swal.fire({
                    title: 'Saved!',
                    text: 'You data has been saved.'+result.message,
                    type: 'success'
                }).then(function() {
                    window.location.href='<?=$root_app."/dashMobile";?>';    
                });
                                   
            }else{
                $iconLoad.hide();
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong!',
                    footer: '<a href>'+result.message+'</a>'
                });
            }
        },
        fail: function(xhr, textStatus, errorThrown){
            alert('request failed:'+textStatus);
        }
        
    });     
})
</script>