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

$path = "http://".$_SERVER["SERVER_NAME"]."/";	
$poly1 = $path."image/Polygon1.png";
$poly2 = $path."image/Polygon12.png";
$group12 = $path."image/Group12.png";
$group13 = $path."image/Group13.png";
?>

<link href="<?=$folder_assets?>/node_modules/footable/css/footable.bootstrap.min.css" rel="stylesheet">
<style>
@import url('https://fonts.googleapis.com/css?family=Roboto&display=swap');


body {
    /* font-family: 'Roboto', sans-serif !important; */
}
h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6 {
    /* font-family: 'Roboto', sans-serif !important; */
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

.col-md-2dot4{
    cursor:pointer;
}
</style>

<div class="container-fluid mt-3">
    <div class="row" >
        <div class="col-md-12 mb-2">
            <h3 style='position:absolute'>Dashboard</h3> 
            <button type='button' class='float-right' id='btn-refresh' style='border: 1px solid #d5d5d5;border-radius: 20px;padding: 5px 20px;background: white;'>Refresh
            </button>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2dot4">
                            <div class="card">
                                <div class="box bg-info text-center">
                                    <p hidden>JK</p>
                                    <h1 class="font-light text-white" id="juskeb">0</h1>
                                    <h6 class="text-white">Justifikasi Kebutuhan<br>&nbsp;</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2dot4">
                            <div class="card">
                                <div class="box bg-success text-center">
                                    <p hidden>VR</p>
                                    <h1 class="font-light text-white" id="ver">0</h1>
                                    <h6 class="text-white">Verifikasi<br>&nbsp;</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2dot4">
                            <div class="card">
                                <div class="box bg-primary text-center">
                                    <p hidden>AJK</p>
                                    <h1 class="font-light text-white" id="appjuskeb">0</h1>
                                    <h6 class="text-white">Approval Justifikasi Kebutuhan</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2dot4">
                            <div class="card">
                                <div class="box bg-warning text-center">
                                    <p hidden>JP</p>
                                    <h1 class="font-light text-white" id="juspeng">0</h1>
                                    <h6 class="text-white">Justifikasi Pengadaan<br>&nbsp;</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2dot4">
                            <div class="card">
                                <div class="box bg-danger text-center">
                                    <p hidden>AJP</p>
                                    <h1 class="font-light text-white" id="appjuspeng">0</h1>
                                    <h6 class="text-white">Approval Justifikasi Pengadaan</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="table-posisi" class="table m-t-30 table-hover no-wrap contact-list" data-paging="true" data-paging-size="5" width="100%">
                            <thead>
                                <tr>
                                    <th width="10%">#No Bukti</th>
                                    <th width="20%">No Dokumen</th>
                                    <th width="5%">PP</th>
                                    <th width="10%">Waktu</th>
                                    <th width="25%">Kegiatan</th>
                                    <th width="10%">Nilai</th>
                                    <th width="10%">Posisi Justifikasi</th>
                                    <th width="10%">Posisi Pengadaan</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="8">
                                        <div class="text-right">
                                        <ul class="pagination"> </ul>
                                        </div>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- <div class='col-md-12'>
            <div class="card-group">
                    <div class="card" id="card-aju">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="d-flex no-block align-items-center">
                                        <div>
                                            <h3><i class="icon-doc"></i></h3>
                                            <p class="text-muted">Pengajuan</p>
                                        </div>
                                        <div class="ml-auto">
                                            <h2 class="counter text-primary" id="pengajuan">23</h2>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="progress" id="prog_aju">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: 85%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card" id="card-approval">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="d-flex no-block align-items-center">
                                        <div>
                                            <h3><i class="icon-note"></i></h3>
                                            <p class="text-muted">Approval</p>
                                        </div>
                                        <div class="ml-auto">
                                            <h2 class="counter text-purple" id="approval">169</h2>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="progress" id="prog_app">
                                        <div class="progress-bar bg-purple" role="progressbar" style="width: 85%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card" id="card-approved">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="d-flex no-block align-items-center">
                                        <div>
                                            <h3><i class="icon-check"></i></h3>
                                            <p class="text-muted">Approved</p>
                                        </div>
                                        <div class="ml-auto">
                                            <h2 class="counter text-success" id="approved">157</h2>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="progress" id="prog_appd">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 85%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card" id="card-return">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="d-flex no-block align-items-center">
                                        <div>
                                            <h3><i class="icon-reload"></i></h3>
                                            <p class="text-muted">Return</p>
                                        </div>
                                        <div class="ml-auto">
                                            <h2 class="counter text-danger" id="return">431</h2>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="progress" id="prog_rtn">
                                        <div class="progress-bar bg-danger" role="progressbar" style="width: 85%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div> -->
        <!-- <div class='col-md-12' style='display:none'>
            <div class='card'>
                <div class='card-body'>
                    <button class='btn btn-secondary float-right mb-2'> Back
                    </button>
                    <style>
                        td,th{
                            padding:8px !important;
                            vertical-align:middle !important;
                        }
                    </style>
                    <table class='table table-bordered table-striped'>
                        <thead>
                            <tr>
                            <th>No Bukti</th>
                            <th>No Dokumen</th>
                            <th>PP</th>
                            <th>Waktu</th>
                            <th>Kegiatan</th>
                            <th>Posisi</th>
                            <th>Nilai</th>
                            <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div> -->
    </div>
</div>

<script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async=""></script>
<script>
  var OneSignal = window.OneSignal || [];
  OneSignal.push(function() {
    OneSignal.init({
      appId: "17d5726f-3bc0-4e97-8567-8ad802ccb9ff",
    });
    OneSignal.isPushNotificationsEnabled().then(function(isEnabled) {
        if (isEnabled){
            OneSignal.getUserId().then(function(userId) {
                console.log(userId);
                idUser = userId;
                $.ajax({
                    type: 'POST',
                    url: '<?=$root_ser?>/Notif.php?fx=register',
                    dataType: 'json',
                    async:false,
                    data: {nik:'<?=$nik?>', kode_lokasi:'<?=$kode_lokasi?>', token:userId},
                    success:function(result){
                        console.log(result.message);
                    },
                    fail: function(xhr, textStatus, errorThrown){
                        alert('request failed:'+textStatus);
                    }
                });
            });
        }
        else{
            console.log('Push notifications are not enabled');    
        }  
    });
  });
</script>

<script src="<?=$folder_assets?>/node_modules/moment/moment.js"></script>
<script src="<?=$folder_assets?>/node_modules/footable/js/footable.min.js"></script>
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
                        $('#juskeb').text(sepNumPas(result.juskeb));
                        $('#ver').text(sepNumPas(result.ver));
                        $('#appjuskeb').text(sepNumPas(result.appjuskeb));
                        $('#juspeng').text(sepNumPas(result.juspeng));
                        $('#appjuspeng').text(sepNumPas(result.appjuspeng));

                    break;
                    case 'tablePosisi' :
                        var html='';
                        for(var i=0;i<result.daftar.length;i++){
                            var line = result.daftar[i];
                            html+=`<tr>
                                <td>`+line.no_bukti+`</td>
                                <td>`+line.no_dokumen+`</td>
                                <td>`+line.kode_pp+`</td>
                                <td>`+line.waktu+`</td>
                                <td>`+line.kegiatan+`</td>
                                <td>`+sepNumPas(line.nilai)+`</td>`;
                                if(line.progress == 'S'){
                                    var label="primary";
                                }else if(line.progress == 'R'){
                                    var label="danger";
                                }else if(line.progress == 'F'){
                                    var label="dark";
                                }else{
                                    var label="success";
                                }
                            html+=`
                                <td><span class="label label-`+label+`">`+line.posisi+`</span></td>`;
                                if(line.progress2 == 'S'){
                                    var label="primary";
                                }else if(line.progress2 == 'R'){
                                    var label="danger";
                                }else if(line.progress2 == 'F'){
                                    var label="dark";
                                }else{
                                    var label="success";
                                }
                            html+=`
                                <td><span class="label label-`+label+`">`+line.posisi2+`</span></td>`;
                            html+=`
                            </tr>`;
                        }
                        $('#table-posisi tbody').html(html);
                        $('#table-posisi').footable();
                    break;

                }
            }
        }
    });
}
function initDash(){
    loadService('box','GET','<?=$root_ser?>/dashApv.php?fx=getDataBox','<?=$kode_lokasi?>|'); 
    loadService('tablePosisi','GET','<?=$root_ser?>/dashApv.php?fx=getPosisi','<?=$kode_lokasi?>|'); 
     
}
initDash();

$('.col-md-2dot4').click(function(){
    var kode = $(this).closest('div').find('p').text();
    // alert(kode);
    loadService('tablePosisi','GET','<?=$root_ser?>/dashApv.php?fx=getPosisi','<?=$kode_lokasi?>|'+kode); 
})
</script>