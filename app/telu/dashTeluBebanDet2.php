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

$tahun= substr($periode,0,4);
$tahunLalu = intval($tahun)-1;
$thnIni = substr($tahun,2,2);
$thnLalu = substr($tahunLalu,2,2);

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
    h5{
        font-weight:bold;
        color:#ad1d3e;
        padding-left:20px;
    }
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
        <div class="col-12 text-right">
            <button class='btn btn-red btn-sm' id='btnBack'>Back</button>
        </div>
    </div>
    <div class="row mt-2" >
        <div class="col-6">
            <div class="card">
                <h5>Beban per tahun tiap Jurusan Fakultas <span class='nama_fakultas'></span></h5>
                <div class="card-body pt-0">
                    <div id='BebanJur' style='height:350px'>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card" style="background:#f5f5f5;border-radius:15px">
                <h5 class="mt-2">Beban <span class='tahunPilih'></span></h5>
                <div class="card-body pt-0">
                    <table class='no-border' id='tableBeban' style="width:100%">
                        <thead>
                            <tr>
                                <th></th>
                                <th class="text-right">RKA '<span class='thnPilih'></span></th>
                                <th class="text-right">Realisasi '<span class='thnPilih'></span></th>
                                <th class="text-right">Pencapaian</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
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
                    case 'BebanJur':
                        Highcharts.chart('BebanJur', {
                            chart: {
                                type: 'bar'
                            },
                            title: {
                                text: null
                            },
                            xAxis: {
                                categories: result.ctg,
                                crosshair: true
                            },
                            yAxis: {
                                title: {
                                    text: ''
                                },
                                labels: {
                                    formatter: function () {
                                        return singkatNilai(this.value);
                                    }
                                },
                            },
                            credits:{
                                    enabled:false
                            },
                            tooltip: {
                                // headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                                // pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                                //     '<td style="padding:0"><b>{point.y:.2f}</b></td></tr>',
                                // footerFormat: '</table>',
                                // // shared: true,
                                // useHTML: true
                                formatter: function () {
                                    return this.series.name+':<b>'+toMilyar(this.y)+'</b>';
                                }
                            },
                            plotOptions: {
                                bar: {
                                    pointPadding: 0.2,
                                    borderWidth: 0,
                                    cursor: 'pointer',
                                    point: {
                                        events: {
                                            click: function() {  
                                                $kd2 = this.options.key;
                                                
                                            }
                                        }
                                    }
                                }
                            },
                            series: result.series
                        });
                    break;
                    case 'tableBeban':
                        var html='';
                        for(var i=0;i<result.daftar.length;i++){
                            var line = result.daftar[i];
                            
                            html+=`<tr>
                            <td style='font-weight:bold'>`+line.nama+`</td>
                            <td class='text-right'>`+toMilyar(line.n4)+`</td>
                            <td class='text-right'>`+toMilyar(line.n5)+`</td>
                            <td class='text-right'>`+sepNum(line.capai)+`%</td>
                            </tr>`;
                            
                        }
                        $('#tableBeban tbody').html(html);
                    break;

                }
            }
        }
    });
}
function initDash(){
    loadService('BebanJur','GET','<?=$root_ser?>/dashTeluBebanDet2.php?fx=getBebanJur','<?=$kode_lokasi?>|'+$kd+'|'+$kd2); 
    loadService('tableBeban','GET','<?=$root_ser?>/dashTeluBebanDet2.php?fx=getTableBebanJur','<?=$kode_lokasi?>|'+$kd+'|'+$kd2); 
     
}

var tmp = $kd2.split("|");
var th = tmp[1];


$('.tahunPilih').text('20'+th);
$('.thnPilih').text(th);
initDash();

$('.container-fluid').on('click','#btnBack',function(e){
    loadForm("<?=$root?>/app/telu/dashTeluBebanDet.php");
})
</script>