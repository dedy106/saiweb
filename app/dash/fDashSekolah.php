<?php


$kode_lokasi=$_COOKIE['lokasi'];
$periode=$_COOKIE['periode'];
$kode_pp=$_COOKIE['kodePP'];
$nik=$_COOKIE['userLog'];

$root_ser = $root_ser="http://".$_SERVER['SERVER_NAME']."/server/kasir";
$resource = $_GET["resource"];
$fullId = $_GET["fullId"];

$path = "http://".$_SERVER["SERVER_NAME"]."/";	
$poly1 = $path."image/Polygon1.png";
$poly2 = $path."image/Polygon12.png";
$group12 = $path."image/Group12.png";
$group13 = $path."image/Group13.png";
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
#persentase{
    font-size: 50px;
}
</style>

<div class="container-fluid mt-3">
    <div class="row" >
        <div class="col-md-12 mb-2">
            <h3 style='position:absolute'>Piutang</h3> 
            <button type='button' class='float-right' id='btn-refresh' style='border: 1px solid #d5d5d5;border-radius: 20px;padding: 5px 20px;background: white;'>Refresh
            </button>
        </div>
    </div>
    <div class="row">
    <div class="col-4">
        <h6 class="font-weight-bold">Piutang Tertagih Januari 2020</h6>
        <p id="persentase">92,39%</p>
    </div>
    <div class="col-4">
    <div id="pie-piutang"></div>
    </div>
    </div>
</div>
<script>

Highcharts.chart('pie-piutang', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie',
        backgroundColor: 'rgba(0,0,0,0)',
        // backgroundColor: 'transparent'
    },
    credits: {
    enabled: false
    },
    title: {
        text: ''
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    accessibility: {
        point: {
            valueSuffix: '%'
        }
    },
    plotOptions: {
        pie: {
            size: '40%',
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b>',
                style:{
                    fontSize: 9.5 + 'px'
                }
            }
        }
    },
    series: [{
        name: 'Piutang',
        colorByPoint: true,
        data: [{
            name: 'Terbayar',
            y: 60,
            sliced: false,
            selected: false
        }, {
            name: 'Terlambat',
            y: 30
        }, {
            name: 'Overdue',
            y: 10
        }]
            }]
        });

$('.card').on('click', '#btn-refresh', function(){
    location.reload();
    // alert('test');
});

$('.card').on('click', '#topSellClick', function(){
    window.location.href='<?=$root_app."/dashKasirDet/?param=$kode_lokasi|$periode|$kode_pp|$nik|$kode_fs|topSell"?>';
});

$('.card').on('click', '#SellCtgClick', function(){
    
    window.location.href='<?=$root_app."/dashKasirDet/?param=$kode_lokasi|$periode|$kode_pp|$nik|$kode_fs|sellCtg"?>';});

$('.card').on('click', '#vendorClick', function(){
   
    window.location.href='<?=$root_app."/dashKasirDet/?param=$kode_lokasi|$periode|$kode_pp|$nik|$kode_fs|vendor"?>';
});

$('.card').on('click', '#missingClick', function(){
    
    window.location.href='<?=$root_app."/dashKasirDet/?param=$kode_lokasi|$periode|$kode_pp|$nik|$kode_fs|missing"?>';
});

$('.container-fluid').on('click', '#cash', function(){
    
    window.location.href='<?=$root_app."/dashKasirDet/?param=$kode_lokasi|$periode|$kode_pp|$nik|$kode_fs|cash"?>';
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
                    case 'pie-piutang' :
                        Highcharts.chart('pie-piutang', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: ''
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
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
                format: '<b>{point.name}</b>'
            }
        }
    },
    series: [{
        name: 'Piutang',
        colorByPoint: true,
        data: [{
            name: 'Terbayar',
            y: 60,
            sliced: false,
            selected: false
        }, {
            name: 'Terlambat',
            y: 30
        }, {
            name: 'Overdue',
            y: 10
        }]
            }]
        });
                    break;
                    case 'top_selling' :
                        var html ='';
                        for(var i=0;i<result.daftar.length;i++){
                            html += `<tr>
                            <td>`+result.daftar[i].nama+`</td>
                            <td>`+result.daftar[i].jumlah+`</td>
                            <td width='5%'><div style='position: absolute;'>80%</div></td>
                            <td width='50%'>
                            <div class='progress sm' style='position: relative;margin-left: 15px;'>
                            <div class='progress-bar progress-bar-aqua' style='width: 80%'></div></div></td>
                        </tr>`;
                        }
                        $('#top_selling tbody').html(html);
                    break;
                    case 'selling_category' :
                    Highcharts.chart('selling_category', {
                        chart: {
                            type: 'column',
                            height: (12 / 16 * 100) + '%' // 16:8 ratio
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
                        legend: {
                            reversed: true
                        },
                        plotOptions: {
                            series: {
                                stacking: 'normal'
                            }
                        },
                        credits:{
                            enabled:false
                        },
                        series: result.daftar
                    });
                    break;
                    case 'vendor_duedate' :
                        var html ='';
                        for(var i=0;i<result.daftar.length;i++){
                            html += `<tr>
                                <td width='50%'>`+result.daftar[i].nama+`</td>
                                <td width='25%'>`+sepNumPas(result.daftar[i].total)+`</td>
                                <td width='25%'>due date</td>
                            </tr>`;
                        }
                        $('#vendor_duedate tbody').html(html);
                    break;
                    
                    case 'missing_stock':
                    // for(var i=0; i<result.data.length;i++){
                    //     var data = result.data[i].data;
                    //     data.reverse();
                        
                    //     data.forEach(function(point) {
                    //         point[0] = new Date(point[0]).getTime();
                    //     });

                    //     result.data[i].data = data;
                    // }

                      Highcharts.chart('missing_stock', {
                        chart: {
                            zoomType: 'x',
                            height: (10 / 16 * 100) + '%' // 16:8 ratio
                        },
                        title: {
                            text: ''
                        },
                        subtitle: {
                            text: ''
                        },
                        xAxis: {
                            categories: [
                                'Jan',
                                'Feb',
                                'Mar',
                                'Apr',
                                'Mei',
                                'Jun',
                                'Jul',
                                'Ags',
                                'Sep',
                                'Okt',
                                'Nov',
                                'Des'
                            ],
                        },
                        yAxis: {
                            title: {
                                text: ''
                            }
                        },
                        legend: {
                            enabled: true
                        },
                        plotOptions: {
                            area: {
                                marker: {
                                    radius: 2
                                },
                                lineWidth: 1,
                                states: {
                                    hover: {
                                        lineWidth: 1
                                    }
                                },
                                threshold: null
                            }
                        },
                        credits:{
                            enabled:false
                        },
                        series: [{
                            name: 'John',
                            data: [3, 4, 3, 5, 4, 5,10, 12,0,0,0,0],
                            type:'areaspline'
                        }]
                    });
                    break;
                    case 'income':
                      if(result.persen >= 0){
                          var img = '$poly1';
                      }else{
                          var img = '$poly2';
                      }
                      var html = `<span style='font-size:18px'>`+toJuta(result.actpend)+`</span><br>
                      <span style='color:green;'><img src='`+img+`' style='position:absolute;width:15px'></img><div class='pull-right' style='text-align: left;'>`+result.persen+`%</div></span><br><br>
                      <p style='color: #808080;font-size:9px'>`+toJuta(result.budpend)+` (Budget)</p>`;

                      $('#labelIncome').html(html);

                      Highcharts.chart('income', {
                        chart: {
                            zoomType: 'x',
                            height: (10 / 16 * 100) + '%' ,// 16:8 ratio
                            type:'areaspline'
                        },
                        title: {
                            text: ''
                        },
                        xAxis: {
                            title: {
                                text: null
                            },
                            categories: ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'],
                        },
                        yAxis: {
                            title: {
                                text: ''
                            }, 
                            opposite: true
                        },
                        legend: {
                            enabled: true
                        },
                        plotOptions: {
                            area: {
                                marker: {
                                    radius: 2
                                },
                                lineWidth: 1,
                                states: {
                                    hover: {
                                        lineWidth: 1
                                    }
                                },
                                threshold: null
                            }
                        },
                        credits:{
                            enabled:false
                        },
                        series: result.series
                    });
                    break;
                    case 'cogs':
                    if(result.persen >= 0){
                        var img = '$poly1';
                    }else{
                        var img = '$poly2';
                    }
                    var html = `<span style='font-size:18px'>`+toJuta(result.actcogs)+`</span><br>
                    <span style='color:green;'><img src='`+img+`' style='position:absolute;width:15px'></img><div class='pull-right' style='text-align: left;'>`+result.persen+`%</div></span><br><br>
                    <p style='color: #808080;font-size:9px'>`+toJuta(result.budcogs)+` (Budget)</p>`;
                    $('#labelCOGS').html(html);

                    Highcharts.chart('cogs', {
                        chart: {
                            zoomType: 'x',
                            height: (10 / 16 * 100) + '%' ,// 16:8 ratio
                            type:'areaspline'
                        },
                        title: {
                            text: ''
                        },
                        xAxis: {
                            title: {
                                text: null
                            },
                            categories: ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'],
                        },
                        yAxis: {
                            title: {
                                text: ''
                            }, 
                            opposite: true
                        },
                        legend: {
                            enabled: true
                        },
                        plotOptions: {
                            area: {
                                marker: {
                                    radius: 2
                                },
                                lineWidth: 1,
                                states: {
                                    hover: {
                                        lineWidth: 1
                                    }
                                },
                                threshold: null
                            }
                        },
                        credits:{
                            enabled:false
                        },
                        series: result.series
                    });
                    break;
                    case 'expenses':
                    if(result.persen >= 0){
                        var img = '$poly1';
                    }else{
                        var img = '$poly2';
                    }
                    var html = `<span style='font-size:18px'>`+toJuta(result.actbeb)+`</span><br>
                    <span style='color:green;'><img src='`+img+`' style='position:absolute;width:15px'></img><div class='pull-right' style='text-align: left;'>`+result.persen+`%</div></span><br><br>
                    <p style='color: #808080;font-size:9px'>`+toJuta(result.budbeb)+` (Budget)</p>`;
                    console.log(html);
                    $('#labelExpenses').html(html);

                    Highcharts.chart('expenses', {
                        chart: {
                            zoomType: 'x',
                            height: (10 / 16 * 100) + '%' ,// 16:8 ratio
                            type:'areaspline'
                        },
                        title: {
                            text: ''
                        },
                        xAxis: {
                            title: {
                                text: null
                            },
                            categories: ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'],
                        },
                        yAxis: {
                            title: {
                                text: ''
                            }, 
                            opposite: true
                        },
                        legend: {
                            enabled: true
                        },
                        plotOptions: {
                            area: {
                                marker: {
                                    radius: 2
                                },
                                lineWidth: 1,
                                states: {
                                    hover: {
                                        lineWidth: 1
                                    }
                                },
                                threshold: null
                            }
                        },
                        credits:{
                            enabled:false
                        },
                        series: result.series
                    });
                    break;
                    case 'net_profit':
                    if(result.persen >= 0){
                        var img = '$poly1';
                    }else{
                        var img = '$poly2';
                    }
                    var html = `<span style='font-size:18px'>`+toJuta(result.actnet)+`</span><br>
                    <span style='color:green;'><img src='`+img+`' style='position:absolute;width:15px'></img><div class='pull-right' style='text-align: left;'>`+result.persen+`%</div></span><br><br>
                    <p style='color: #808080;font-size:9px'>`+toJuta(result.budnet)+` (Budget)</p>`;
                    $('#labelNetProfit').html(html);

                    Highcharts.chart('net_profit', {
                        chart: {
                            zoomType: 'x',
                            height: (10 / 16 * 100) + '%' ,// 16:8 ratio
                            type:'areaspline'
                        },
                        title: {
                            text: ''
                        },
                        xAxis: {
                            title: {
                                text: null
                            },
                            categories: ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'],
                        },
                        yAxis: {
                            title: {
                                text: ''
                            }, 
                            opposite: true
                        },
                        legend: {
                            enabled: true
                        },
                        plotOptions: {
                            area: {
                                marker: {
                                    radius: 2
                                },
                                lineWidth: 1,
                                states: {
                                    hover: {
                                        lineWidth: 1
                                    }
                                },
                                threshold: null
                            }
                        },
                        credits:{
                            enabled:false
                        },
                        series: result.series
                    });
                    break;

                }
            }
        }
    });
}
function initDash(){
    loadService('box','GET','<?=$root_ser?>/dashKasir.php?fx=getDataBox'); 
    loadService('top_selling','GET','<?=$root_ser?>/dashKasir.php?fx=getTopSelling');   
    loadService('selling_category','GET','<?=$root_ser?>/dashKasir.php?fx=getSellingCtg'); 
    loadService('vendor_duedate','GET','<?=$root_ser?>/dashKasir.php?fx=getTopVendor'); 
    loadService('missing_stock','GET','<?=$root_ser?>/dashKasir.php?fx=getPeriode2'); 
}

initDash();
</script>