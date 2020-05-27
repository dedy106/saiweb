<?php
 session_start();
 $root_lib=$_SERVER["DOCUMENT_ROOT"];
 if (substr($root_lib,-1)!="/") {
     $root_lib=$root_lib."/";
 }
 include_once($root_lib.'app/telu/setting.php');

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
    .page-wrapper{
        background:white;
    }
    .card{
        border:none;
        box-shadow:none;
    }
    /* h5{
        font-weight:bold;
        color:#ad1d3e;
        padding-left:20px;
    } */
    td,th{
        padding:4px !important;
    }
    .btn-red{
        padding: 2px 20px;
        border-radius: 15px; 
        background:#ad1d3e;
        color:white;
        border-color: #ad1d3e;
    }

    /* NAV TABS */
    .nav-tabs {
        border:none;
    }

    .nav-tabs .nav-link{
        border: 1px solid #ad1d3e;
        border-radius: 20px;
        padding: 2px 25px;
        color:#ad1d3e;
    }
    .nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link.active {
        color: white;
        background-color: #ad1d3e;
        border-color: #ad1d3e;
    }

    .nav-tabs .nav-item {
        margin-bottom: -1px;
        padding: 0px 10px 0px 0px;
    }
</style>

<div class="container-fluid mt-3">
    <div class="row" >
        <div class="col-6">
            <div class="card">
                <h5 style='font-weight:bold;color:#ad1d3e;padding-left:20px;'>Komposisi Pendapatan</h5>
                <div class="card-body pt-0">
                    <div id='komposisi' style='height:350px'>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6" style="background:#ad1d3e;color:white;height:50px;text-align:center">
                            <h5 style='margin: 15px auto;'>Operasional : <span id='opr'></span></h5>
                        </div>
                        <div class="col-6" style="background:#4c4c4c;color:white;height:50px;text-align:center">
                            <h5 style='margin: 15px auto;'>Non Operasional : <span id='nonopr'></span></h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card">
                <h5 style='font-weight:bold;color:#ad1d3e;padding-left:20px;'>Presentase RKA VS Realisasi</h5>
                <p style='font-size:9px;padding-left:20px'>Klik bar untuk melihat detail</p>
                <div class="card-body pt-0">
                    <div id='rkaVSreal' style='height:350px'></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
var $kd = "";
function sepNum(x){
    if(!isNaN(x)){
        if (typeof x === undefined || !x || x == 0) { 
            return 0;
        }else if(!isFinite(x)){
            return 0;
        }else{
            var x = parseFloat(x).toFixed(2);
            // console.log(x);
            var tmp = x.toString().split('.');
            // console.dir(tmp);
            var y = tmp[1].substr(0,2);
            var z = tmp[0]+'.'+y;
            var parts = z.split('.');
            parts[0] = parts[0].replace(/([0-9])(?=([0-9]{3})+$)/g,'$1.');
            return parts.join(',');
        }
    }else{
        return 0;
    }
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

function toMilyar(x) {
    var nil = x / 1000000000;
    return sepNum(nil) + ' M';
}

function singkatNilai(num){
    if(num < 0){
        num = num * -1;
    }
    
    if(num >= 1000 && num < 1000000){
        var str = 'Rb';
        var pembagi = 1000;
    }else if(num >= 1000000 && num < 1000000000){
        var str = 'Jt';
        var pembagi = 1000000;
    }else if(num >= 1000000000 && num < 1000000000000){
        var str = 'M';
        var pembagi = 1000000000;
    }else if(num >= 1000000000000){
        var str = 'T';
        var pembagi = 1000000000000;
    }
    
    if(num < 0){
        return (parseFloat(num/pembagi).toFixed(0) * -1) + ' ' +str;
    }else if (num > 0 && num >= 1000){
        return parseFloat(num/pembagi).toFixed(0) + ' ' +str;
    }else if(num > 0 && num < 1000){
        return num;
    }else{
        return num;
    }
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
                    case 'komposisi':
                        Highcharts.chart('komposisi', {
                            chart: {
                                plotBackgroundColor: null,
                                plotBorderWidth: null,
                                plotShadow: false,
                                type: 'pie'
                            },
                            title: {
                                text: null
                            },
                            credits:{
                                enabled:false
                            },
                            tooltip: {
                                pointFormat: '{series.name}: <b>{point.percentage:.2f}%</b>'
                            },
                            accessibility: {
                                point: {
                                    valueSuffix: '%'
                                }
                            },
                            plotOptions: {
                                pie: {
                                    allowPointSelect: true,
                                    cursor: 'pointer',
                                    dataLabels: {
                                        enabled: true,
                                        // distance:-30,
                                        formatter: function () {
                                            return Highcharts.numberFormat(this.percentage,2,",",".")+' %';
                                        }
                                    },
                                    showInLegend: true
                                }
                            },
                            series: [{
                                name: 'Pendapatan',
                                colorByPoint: true,
                                data: result.data
                            }]
                        });
                    break;
                    case 'rkaVSreal':
                        Highcharts.chart('rkaVSreal', {
                            chart: {
                                type: 'bar'
                            },
                            title: {
                                text:  null
                            },
                            xAxis: {
                                categories: result.category,
                                title: {
                                    text: null
                                }
                            },
                            yAxis: {
                                min: 0,
                                title: {
                                    text: '',
                                    align: 'high'
                                },
                                labels: {
                                    overflow: 'justify'
                                }
                            },
                            tooltip: {
                                formatter: function () {
                                    return this.point.name+':<b>'+sepNum(this.y)+'</b> %';
                                }
                            },
                            plotOptions: {
                                bar: {
                                    dataLabels: {
                                        enabled: true,
                                        formatter: function () {
                                            return sepNum(this.y,2,",",".")+' %';
                                        }
                                    },
                                    cursor: 'pointer',
                                    point: {
                                        events: {
                                            click: function() {  
                                                $kd= this.options.key;
                                                // alert(kd);
                                                loadForm("<?=$root?>/app/telu/dashTeluPdptDet.php");
                                            }
                                        }
                                    }
                                }
                            },
                            legend: {
                            enabled:false
                            },
                            credits: {
                                enabled: false
                            },
                            series: [{
                                name: null,
                                color:'#ad1d3e',
                                data: result.data
                            }]
                        });
                    break;
                    case 'totalOprNon':
                        $('#opr').text(sepNum(result.opr)+'%');
                        $('#nonopr').text(sepNum(result.nonopr)+'%');
                    break;

                }
            }
        }
    });
}
function initDash(){
    loadService('komposisi','GET','<?=$root_ser?>/dashTeluPdpt.php?fx=getKomposisi','<?=$kode_lokasi?>|'); 
    loadService('rkaVSreal','GET','<?=$root_ser?>/dashTeluPdpt.php?fx=perRKAvsReal','<?=$kode_lokasi?>|'); 
    loadService('totalOprNon','GET','<?=$root_ser?>/dashTeluPdpt.php?fx=getTotalOprNon','<?=$kode_lokasi?>|'); 
     
}
initDash();
</script>