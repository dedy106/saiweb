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
        padding:8px !important;
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
                <h5>Pencapaian YoY</h5>
                <div class="card-body">
                    <table class='table' id='pencapaian'>
                        <thead>
                            <tr>
                                <th>&nbsp;</th>
                                <th class='text-right'>Realisasi '<span class='thnLalu'></span></th>
                                <th class='text-right'>RKA '<span class='thnIni'></span></th>
                                <th class='text-right'>Realisasi '<span class='thnIni'></span></th>
                                <th class='text-right'>Pencapaian</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card">
                <h5>RKA vs Realisais YTD</h5>
                <div class="card-body">
                    <div id='rkaVSreal' style='height:200px'></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row" >
        <div class="col-6">
            <div class="card">
                <h5>Growth RKA</h5>
                <div class="card-body pt-0">
                    <ul class="nav nav-tabs mb-2">
                        <li class="nav-item">
                            <a class="nav-link active" href="#">Rp</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">%</a>
                        </li>
                    </ul>
                    <div id='growthRKA' style='height:300px'></div>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card">
                <h5>Growth Realisasi</h5>
                <div class="card-body pt-0">
                    <ul class="nav nav-tabs mb-2">
                        <li class="nav-item">
                            <a class="nav-link active" href="#">Rp</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">%</a>
                        </li>
                    </ul>
                    <div id='growthReal' style='height:300px'></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>

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
                    case 'pencapaian' :
                        var html='';
                        var nama = ['Pendapatan','Beban','SHU','OR'];
                        for(var i=0;i<result.daftar.length;i++){
                            var line = result.daftar[i];
                            if(line.kode_neraca == 'OR'){
                                html+=`<tr>
                                <td style='font-weight:bold'>`+nama[i]+`%</td>
                                <td class='text-right'>`+sepNum(line.n1)+`%</td>
                                <td class='text-right'>`+sepNum(line.n2)+`%</td>
                                <td class='text-right'>`+sepNum(line.n3)+`%</td>
                                <td class='text-right' style='color: #4CD964;font-weight:bold'></td>
                            </tr>`;    
                            }else{
                                html+=`<tr>
                                    <td style='font-weight:bold'>`+nama[i]+`</td>
                                    <td class='text-right'>`+toMilyar(line.n1)+`</td>
                                    <td class='text-right'>`+toMilyar(line.n2)+`</td>
                                    <td class='text-right'>`+toMilyar(line.n3)+`</td>
                                    <td class='text-right' style='color: #4CD964;font-weight:bold'>`+sepNum(line.capai)+`%</td>
                                </tr>`;
                            }
                        }
                        $('#pencapaian tbody').html(html);
                    break;
                    case 'rkaVSreal':
                        Highcharts.chart('rkaVSreal', {
                            chart: {
                                type: 'column',
                                renderTo: 'rkaVSreal'
                            },
                            title: {
                                text: null
                            },
                            credits:{
                                    enabled:false
                            },
                            legend:{
                                enabled:false
                            },
                            xAxis: {
                                categories: [
                                    'Pendapatan',
                                    'Beban',
                                    'SHU'
                                ],
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
                                }
                            },
                            tooltip: {
                                // headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                                // pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                                //     '<td style="padding:0"><b>{point.y:.1f} mm</b></td></tr>',
                                // footerFormat: '</table>',
                                // shared: true,
                                // useHTML: true
                                formatter: function () {
                                    return this.series.name+':<b>'+toMilyar(this.y)+'</b>';
                                }
                            },
                            plotOptions: {
                                column: {
                                    pointPadding: 0.2,
                                    borderWidth: 0
                                }
                            },
                            // series: [{
                            //     name: 'RKA',
                            //     color:'#ad1d3e',
                            //     data: [49.9, 71.5, 106.4]

                            // }, {
                            //     name: 'Realisasi',
                            //     color:'#4c4c4c',
                            //     data: [83.6, 78.8, 98.5]

                            // }]
                            series : result.series
                        });

                    break;
                    case 'growthRKA':
                        Highcharts.chart('growthRKA', {
                            
                            title: {
                                text: null
                            },
                            credits:{
                                    enabled:false
                            },
                            tooltip: {
                                // headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                                // pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                                //     '<td style="padding:0"><b>{point.y:.1f} mm</b></td></tr>',
                                // footerFormat: '</table>',
                                // shared: true,
                                // useHTML: true
                                formatter: function () {
                                    return this.series.name+':<b>'+toMilyar(this.y)+'</b>';
                                }
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
                            xAxis: {
                                // accessibility: {
                                //     rangeDescription: 'Range: 14 to 20'
                                // }
                                categories:result.ctg
                            },
                            plotOptions: {
                                series: {
                                    dataLabels: {
                                        enabled: true,
                                        formatter: function () {
                                            return '<b>'+toMilyar(this.y)+'</b>';
                                        }
                                    }
                                }
                            },

                            series: result.series

                            // responsive: {
                            //     rules: [{
                            //         condition: {
                            //             maxWidth: 500
                            //         }
                            //     }]
                            // }

                        });
                    break;
                    case 'growthReal':
                        Highcharts.chart('growthReal', {
                            chart: {
                                type: 'spline'
                            },
                            title: {
                                text: null
                            },
                            credits:{
                                    enabled:false
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
                            xAxis: {
                                // accessibility: {
                                //     rangeDescription: 'Range: 14 to 20'
                                // }
                                categories:result.ctg
                            },
                            plotOptions: {
                                // series: {
                                //     label: {
                                //         connectorAllowed: false
                                //     },
                                //     marker:{
                                //             enabled:false
                                //     }
                                //     // pointStart: 14
                                // },
                                spline: {
                                    dataLabels: {
                                        enabled: true,
                                        formatter: function () {
                                            return '<b>'+toMilyar(this.y)+'</b>';
                                        }
                                    },
                                    enableMouseTracking: false
                                }
                            },

                            series: result.series,

                            // responsive: {
                            //     rules: [{
                            //         condition: {
                            //             maxWidth: 500
                            //         }
                            //     }]
                            // }

                        });
                    break;

                }
            }
        }
    });
}
function initDash(){
    loadService('rkaVSreal','GET','<?=$root_ser?>/dashTelu.php?fx=getRKAVSReal','<?=$kode_lokasi?>|'); 
    loadService('pencapaian','GET','<?=$root_ser?>/dashTelu.php?fx=getPencapaian','<?=$kode_lokasi?>|');
    loadService('growthRKA','GET','<?=$root_ser?>/dashTelu.php?fx=getGrowthRKA','<?=$kode_lokasi?>|'); 
    loadService('growthReal','GET','<?=$root_ser?>/dashTelu.php?fx=getGrowthReal','<?=$kode_lokasi?>|'); 
     
}
$('.thnLalu').text('<?=$thnLalu?>');
$('.thnIni').text('<?=$thnIni?>');
initDash();
</script>