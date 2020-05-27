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
$backto="$root_app/dashMobile";
$mobile=true;
$judul = "Riwayat daftar Pengajuan";
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
</style>

<div class="container-fluid mt-2">
    <div class="row">
        <?php 
            include($root2.'app/apv/back.php');
        ?>
        <div id="history" class="col-md-12" style="padding:0">
        
        </div>
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


function ubahNamaHari(hari)
{
        var tmp ='';
		switch (hari)
		{
			case "Sunday":
			case "SUN":
				tmp="Minggu";
			break;
			case "Monday":
			case "MON":
				tmp="Senin";
			break;
			case "Tuesday":
			case "TUE":
				tmp="Selasa";
			break;
			case "Wednesday":
			case "WED":
				tmp="Rabu";
			break;
			case "Thursday":
			case "THU":
				tmp="Kamis";
			break;
			case "Friday":
			case "FRI":
				tmp="Jumat";
			break;
			case "Saturday":
			case "SAT":
				tmp="Sabtu";
			break;
		}
	return tmp;
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
                    case 'getHistory' :
                        var html = '';
                        for(var i=0;i<result.data.length;i++){
                            var line = result.data[i];
                            if(line.status == "2"){
                                var status = `<div style="border-radius:10px;padding: 5px;background: #11cb11;color: white;width: 80px;text-align: center;position: absolute;margin-top: -25px;right: 0;margin-right:10px">Approve</div>`;
                            }else{
                                var status = `<div style="border-radius:10px;padding: 5px;background: #d1d1d4;color: white;width: 80px;text-align: center;position: absolute;margin-top: -25px;right: 0;margin-right:10px">Return</div>`;
                            }
                            html +=`<div class='col-md-12 mb-3 pengajuan' style=''>
                            <div class='card' style='border-radius: 5px;border:1px solid rgba(0,0,0,.1);margin-bottom:10px'>
                                <div class='card-body' style='padding:10px 10px 0px 10px'>
                                    `+status+`
                                    <h1 style='display:none'>`+line.no_bukti+`</h1>
                                    <div class="row mt-1">
                                        <div class="col-2 text-center">
                                            <img src="<?=$folderroot_img?>/document.svg" style='width:30px;height:30px'>
                                        </div>
                                        <div class="col-10 pl-2">
                                            <h6 style='margin-bottom: 0;font-weight: bold !important;'>`+line.kegiatan+`</h6>
                                            <p style='font-size:12px'>`+line.keterangan+`</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-2 text-center" >
                                            <img src="<?=$folderroot_img?>/calendar.svg" style='width:20px;height:20px'>
                                        </div>
                                        <div class="col-5 pl-2">
                                            <p style='font-size:12px'>`+ubahNamaHari(line.nama_hari)+` `+line.tanggal+`</p>
                                        </div>
                                        <div class="col-5 text-right">
                                            <img src="<?=$folderroot_img?>/coins.svg" style='width:20px;height:20px'>
                                            <span style='font-size:12px'>Rp. `+sepNumPas(line.nilai)+`</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>`;
                        }
                        $('#history').html(html);
                    break;

                }
            }
        }
    });
}
function initDash(){
    loadService('getHistory','GET','<?=$root_ser?>/Approval.php?fx=getHistory','<?=$kode_lokasi?>|'); 
     
}
initDash();

$('.container-fluid').on('click','.pengajuan',function(e){
    e.preventDefault();
    var no_bukti = $(this).closest('div').find('h1').text();
    // alert(no_bukti);
    window.location.href='<?=$root_app."/dashApprove/?param="; ?>'+no_bukti+'|history';
})
</script>