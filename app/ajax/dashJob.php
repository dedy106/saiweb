<?php
	session_start();
	$root_lib=$_SERVER["DOCUMENT_ROOT"];
	if (substr($root_lib,-1)!="/") {
		$root_lib=$root_lib."/";
	}
	include_once($root_lib.'app/ajax/setting.php');
	
	$nik=$_SESSION['userLog'];
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

.feeds > li{
    border-bottom:1px solid #f6f6f6;
}
</style>

<div class="container-fluid mt-3">
    <div class="row" >
        <div class="col-md-12 mb-2">
            <h3 style='position:absolute'>Dashboard</h3> 
        </div>
        <div class="row" id='detJob'></div>
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

function loadService(index,method,url,param=null){
    $.ajax({
        type: method,
        url: url,
        dataType: 'json',
        data: {'periode':'<?=$periode?>','param':param},
        success:function(result){    
            if(result.status){
                switch(index){
                    case 'daftarJob' :
                    if(result.daftar.length > 0){
                        var html='';
                        var color = ['info','success','danger','warning','dark','primary','info','success','danger','warning','dark','primary','info','success','danger','warning','dark','primary','info','success','danger','warning','dark','primary','info','success','danger','warning','dark','primary','info','success','danger','warning','dark','primary','info','success','danger','warning','dark','primary','info','success','danger','warning','dark','primary'];
                        var warna = '';
                        for(var i=0;i<result.daftar.length;i++){
                            var line =result.daftar[i];
                            if(line.foto == "" || line.foto=="-" || line.foto == 'undefined'){
                                var user_icon='usericonbaru.png';
                            }else{
                                var user_icon=line.foto;
                            }

                            html+=`<div class="col-lg-4 mt-4">
                                <div class="card" style="border-radius: 10px;">
                                    <div class="card-header bg-`+color[i]+`" style="border-top-right-radius: 5px;border-top-left-radius: 5px;color:white">
                                        <span class=""><img class="img-circle" src="<?=$path?>/vendor/asset_elite/images/users/`+user_icon+`" style="width: 60px;" width="100">
                                        <span class="ml-2">`+line.nama+`</span></span>
                                    </div>
                                    <div class="card-body" style="padding:0;border-bottom-right-radius: 5px;border-bottom-left-radius: 5px;">
                                        <ul class="feeds">
                                            <li>
                                            Job Total <span class="badge badge-pill badge-info">`+line.job_total+`</span></li>
                                            <li>
                                            Job Completed <span class="badge badge-pill badge-success">`+line.job_finish+`</span></li>
                                            <li>
                                            Job in Progress <span class="badge badge-pill badge-warning">`+line.job_inprog+`</span></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>`;
                        }
                        $('#detJob').html(html);
                    }

                    break;

                }
            }
        }
    });
}
function initDash(){
    loadService('daftarJob','GET','<?=$root_ser?>/dashJob.php?fx=getDaftarJob','<?=$kode_lokasi?>|'); 
     
}
initDash();
</script>