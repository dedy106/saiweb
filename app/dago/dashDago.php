<?php
session_start();
$root_lib=$_SERVER["DOCUMENT_ROOT"];
if (substr($root_lib,-1)!="/") {
    $root_lib=$root_lib."/";
}
include_once($root_lib.'app/dago/setting.php');

$kode_lokasi=$_SESSION['lokasi'];
$periode=$_SESSION['periode'];
$kode_pp=$_SESSION['kodePP'];
$nik=$_SESSION['userLog'];

$root_ser = $root_ser="http://".$_SERVER['SERVER_NAME']."/server/dago";
$resource = $_GET["resource"];
$fullId = $_GET["fullId"];

$path = "http://".$_SERVER["SERVER_NAME"]."/";	
$poly1 = $path."image/Polygon1.png";
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
</style>

<div class="container-fluid mt-3">
    <div class="row" >
        <div class="col-md-12 mb-2">
            <h3 style='position:absolute'>Dashboard</h3> 
            <button type='button' class='float-right' id='btn-refresh' style='border: 1px solid #d5d5d5;border-radius: 20px;padding: 5px 20px;background: white;'>Refresh
            </button>
        </div>
        <div class='col-md-12'>
            <div class='row'>
                <div class='col-md-12'>
                    <div class='card-group mb-3'>
                         <div class="card" id="card-jamaah">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="d-flex no-block align-items-center">
                                            <div>
                                                <h3><i class="icon-user"></i></h3>
                                                <p class="text-muted">Jamaah</p>
                                            </div>
                                            <div class="ml-auto">
                                                <h2 class="counter text-warning" id="jamaah"></h2>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="progress" id="prog_jamaah">
                                            <div class="progress-bar bg-warning" role="progressbar" style="width: 85%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                         </div>
                         <div class="card" id="card-registrasi">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="d-flex no-block align-items-center">
                                            <div>
                                                <h3><i class="icon-user-follow"></i></h3>
                                                <p class="text-muted">Registrasi</p>
                                            </div>
                                            <div class="ml-auto">
                                                <h2 class="counter text-info" id="registrasi"></h2>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="progress" id="prog_registrasi">
                                            <div class="progress-bar bg-info" role="progressbar" style="width: 85%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                         </div>
                         <div class="card" id="card-pbyr">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="d-flex no-block align-items-center">
                                            <div>
                                                <h3><i class="icon-wallet"></i></h3>
                                                <p class="text-muted">Pembayaran</p>
                                            </div>
                                            <div class="ml-auto">
                                                <h2 class="counter text-success" id="pbyr"></h2>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="progress" id="prog_pbyr">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 85%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                         </div>
                    </div>
                </div>
            </div>    
            <div class='row'>   
                <div class='col-md-6'>
                    <div class='card mar-mor box-wh' style='border-radius:5px'>
                        <div class='card-body' style='padding: 0 10px 10px 10px;'>
                            <div class="row">
                                <div style='border: 1px solid #ff9500;padding: 5px;border-bottom-right-radius: 20px;border-top-right-radius: 20px;background: #ff9500;color: white;width: 140px;cursor:pointer;height: 40px;' class='col-md-6' id='vendorClick'>Top Agen</div>
                                <div class='col-md-12' style='margin-top:10px;height: 340px;'>
                                    <table class='table no-margin' id='top_agen'>
                                    <style>
                                        th,td{
                                            padding:8px !important;
                                        }
                                    </style>
                                    <thead>
                                    <th>Kode Agen</th>
                                    <th>Nama Agen</th>
                                    <th>Total</th>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>   
                <div class='col-md-6'>
                    <div class='card mar-mor box-wh' style='border-radius:5px'>
                        <div class='card-body' style='padding: 0 10px 10px 10px;'>
                            <div class="row">
                                <div style='border: 1px solid #ff9500;padding: 5px;border-bottom-right-radius: 20px;border-top-right-radius: 20px;background: #ff9500;color: white;width: 140px;cursor:pointer;height: 40px;' class='col-md-6' id='regHariClick'>Registrasi per Hari</div>
                                <div id='regHari' class='col-md-12' style='margin-top:10px;height: 340px;'></div>
                            </div>
                        </div>
                    </div>
                </div> 
                <div class='col-md-6'>
                    <div class='card mar-mor box-wh' style='border-radius:5px'>
                        <div class='card-body' style='padding: 0 10px 10px 10px;'>
                            <div class="row">
                                <div style='border: 1px solid #ff9500;padding: 5px;border-bottom-right-radius: 20px;border-top-right-radius: 20px;background: #ff9500;color: white;width: 140px;cursor:pointer;height: 40px;' class='col-md-6' id='vendorClick'>Daftar Kuota per Paket</div>
                                <div class='col-md-12' style='margin-top:10px;'>
                                    <table class='table no-margin' id='quota'>
                                    <style>
                                        th,td{
                                            padding:8px !important;
                                        }
                                    </style>
                                    <thead>
                                    <th>Nama Paket</th>
                                    <th>Tgl Berangkat</th>
                                    <th>Kuota</th>
                                    <th>Sisa Quota</th>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>   
            </div>                        
        </div>
    </div>
</div>
<script>

$('.card').on('click', '#btn-refresh', function(){
    location.reload();
    // alert('test');
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
        async:false,
        data: {'periode':'<?=$periode?>','param':param},
        success:function(result){    
            if(result.status){
                switch(index){
                    case 'box' :
                        $('#jamaah').text(result.jamaah);
                        $('#registrasi').text(result.registrasi);
                        $('#pbyr').text(result.pembayaran);
                    break;
                    case 'topAgen' :
                        if(result.daftar.length>0){
                            var html='';
                            for(var i=0;i<result.daftar.length;i++){
                                html+=`<tr>
                                    <td>`+result.daftar[i].no_agen+`</td>
                                    <td>`+result.daftar[i].nama_agen+`</td>
                                    <td style='text-align:right'>`+result.daftar[i].jum+`</td>
                                </tr>`;
                            }
                            // console.log(html);
                            $('#top_agen tbody').html(html);
                        }
                    break;
                    case 'regHari':
                        Highcharts.chart('regHari', {
                            chart: {
                                type: 'column',
                                // height: (12 / 16 * 100) + '%' // 16:8 ratio
                            },
                            title: {
                                text: ''
                            },
                            xAxis: {
                                title: {
                                    text: null
                                },
                                categories: result.ctg,
                            },
                            yAxis: {
                                min: 0,
                                title: {
                                    text: ''
                                }
                            },
                            credits:{
                                enabled:false
                            },
                            series: result.series
                        });
                        // console.log(result.series);
                    break;
                    case 'kuota' :
                        if(result.daftar.length>0){
                            var html='';
                            for(var i=0;i<result.daftar.length;i++){
                                html+=`<tr>
                                    <td>`+result.daftar[i].nama+`</td>
                                    <td>`+result.daftar[i].tgl_berangkat+`</td>
                                    <td style='text-align:right'>`+sepNumPas(result.daftar[i].quota)+`</td>
                                    <td style='text-align:right'>`+sepNumPas(result.daftar[i].sisa)+`</td>
                                </tr>`;
                            }
                            // console.log(html);
                            $('#quota tbody').html(html);
                        }
                    break;
                }
            }
        }
    });
}
function initDash(){
    loadService('box','GET','<?=$root_ser?>/dashboard.php?fx=getDataBox');  
    loadService('topAgen','GET','<?=$root_ser?>/dashboard.php?fx=getTopAgen'); 
    loadService('regHari','GET','<?=$root_ser?>/dashboard.php?fx=getRegHari'); 
    loadService('kuota','GET','<?=$root_ser?>/dashboard.php?fx=getKuota');  
}

initDash();
</script>