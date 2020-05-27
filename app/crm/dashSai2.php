<style>
    @import url("https://fonts.googleapis.com/css?family=Roboto&display=swap");

   
    body {
        font-family: 'Roboto', sans-serif !important;
    }
    h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6 {
        font-family: 'Roboto', sans-serif !important;
        font-weight: normal !important;
    }
    /* h3{
        margin-bottom: 5px;
        font-size:25px !important
    }
    h2{
        margin-bottom: 5px;
        margin-top:5px;
    } */
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
        border-bottom-left-radius: 10px;
        border-bottom-right-radius: 10px;
        padding: 2px;
        font-size: 12px;
    }
    .icon-blue {
        color:white;
        background: #0073b7;
        border: 1px solid #0073b7;
        border-bottom-left-radius: 10px;
        border-bottom-right-radius: 10px;
        padding: 2px;
        font-size: 12px;
    }
    .icon-purple {
        color:white;
        background: #605ca8 !important;
        border: 1px solid #605ca8 !important;
        border-bottom-left-radius: 10px;
        border-bottom-right-radius: 10px;
        padding: 2px;
        font-size: 12px;
    }
    .icon-pink {
        color:white;
        background: #d81b60;
        border: 1px solid #d81b60;
        border-bottom-left-radius: 10px;
        border-bottom-right-radius: 10px;
        padding: 2px;
        font-size: 12px;
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

    .pad-more{
        padding-left:0px !important;
        padding-right:10px !important;
    }
    .mar-mor{
        margin-bottom:10px !important;
    }
    #ajax-content-section{
        background:white;
    }
</style>
<div class='row'>
    <div class='col-md-12 col-md-3' id='home_cust'>
        <div class="small-box bg-white">
            <div class="inner">
                <center>
                    <p>Customer</p>
                    <h3 id='home_customer_box'></h3>
                </center>
            </div>
            <div class="icon"><i class="fa fa-line-chart icon-green"></i></div>
            
        </div>
    </div>
    <div class='col-md-12 col-md-3'id='home_prod'>
        <div class="small-box bg-white">
            <div class="inner">
                <center>
                    <p>Produk</p>
                    <h3 id='home_produk_box'></h3>
                </center>
            </div>
            <div class="icon"><i class="fa fa-money icon-blue"></i></div>
            
        </div>
    </div>
    <div class='col-md-12 col-md-3' id='home_kontrak'>
        <div class="small-box bg-white">
            <div class="inner">
                <center>
                    <p>Kontrak</p>
                    <h3 id='home_kontrak_box'></h3>
                </center>
            </div>
            <div class="icon"><i class="fa fa-pie-chart icon-pink"></i></div>
            
        </div>
    </div>
        <div class='col-md-12 col-md-3' id='home_pbyr'>
            <div class="small-box bg-white">
                <div class="inner">
                    <center>
                        <p>Pembayaran</p>
                        <h3 id='home_pembayaran_box'></h3>
                    </center>
                </div>
                <div class="icon"><i class="fa fa-credit-card icon-purple"></i></div>
                
            </div>
        </div>
    </div>
    <div >
        <div class='row' id='sai_home_list'>
            <div class="col-md-4">
                <div class="box box-widget widget-user-2">
                    <div class="widget-user-header bg-red">
                        <h3 class="widget-user-desc" style="margin-left:-12px; line-height: 0; padding-bottom:10px;">Project</h3>
                    </div>
                    <div class="box-footer no-padding">
                        <ul class="nav nav-stacked" id="project-monitoring-list">
                            <div class="col-xs-5" style="padding-left:25px;"><b>Nama</b></div>
                            <div class="col-xs-2"><b>Jumlah</b></div>
                            <div class="col-xs-5" style="padding-right:30px; text-align:right;"><b>Nilai</b></div>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="box box-widget widget-user-2">
                    <div class="widget-user-header bg-blue">
                        <h3 class="widget-user-desc" style="margin-left:-12px; line-height: 0; padding-bottom:10px;">Customer</h3>
                    </div>
                    <div class="box-footer no-padding">
                        <ul class="nav nav-stacked" id="customer-monitoring-list">
                            <div class="row" style="padding-bottom:7px;padding-top:7px;">
                                <div class="col-xs-5" style="padding-left:25px;"><b>Nama</b></div>
                                <div class="col-xs-2"><b>Jumlah</b></div>
                                <div class="col-xs-5" style="padding-right:30px; text-align:right;"><b>Nilai</b></div>
                            </div>
                            
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="box box-widget widget-user-2">
                    <div class="widget-user-header bg-yellow">
                        <h3 class="widget-user-desc" style="margin-left:-12px; line-height: 0; padding-bottom:10px;">Produk</h3>
                    </div>
                    <div class="box-footer no-padding">
                        <ul class="nav nav-stacked" id="produk-monitoring-list">
                            <div class="row" style="padding-bottom:7px;padding-top:7px;">
                                <div class="col-xs-5" style="padding-left:25px;"><b>Nama</b></div>
                                <div class="col-xs-2"><b>Jumlah</b></div>
                                <div class="col-xs-5" style="padding-right:30px; text-align:right;"><b>Nilai</b></div>
                            </div>
                            
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div id='sai_home_timeline' hidden>
        </div>
        <div id='sai_home_tracing' hidden>
        </div>
    </div>
    <div id='sai_home_grafik'>
        <div class='row'>
            <div class='col-md-6'>
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs pull-right">
                        <li class="active"><a href="#tab_1" data-toggle="tab">Jumlah</a></li>
                        <li><a href="#tab_2" data-toggle="tab">Nilai</a></li>
                        <li class="pull-left header"><i class="fa fa-bar-chart"></i> Prosfecting</li>
                    </ul>
                    <div class="tab-content sai-container-overflow">
                        <div class="tab-pane active" id="tab_1">
                            <div id='home_pros_chartJum'></div>
                        </div>
                        <div class="tab-pane" id="tab_2">
                            <div id='home_pros_chartNil'></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class='col-md-6'>
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs pull-right">
                        <li class="active"><a href="#tab_3" data-toggle="tab">Jumlah</a></li>
                        <li><a href="#tab_4" data-toggle="tab">Nilai</a></li>
                        <li class="pull-left header"><i class="fa fa-bar-chart"></i> Pembayaran</li>
                    </ul>
                    <div class="tab-content sai-container-overflow">
                        <div class="tab-pane active" id="tab_3">
                            <div id='home_pem_chartJum'></div>
                        </div>
                        <div class="tab-pane" id="tab_4">
                            <div id='home_pem_chartNil'></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>    
</div>
<script>
function sepNumKoma(x){
    var num = parseFloat(x).toFixed(2);
    var parts = num.toString().split(".");
    var len = num.toString().length;
    // parts[1] = parts[1]/(Math.pow(10, len));
    parts[0] = parts[0].replace(/(.)(?=(.{3})+$)/g,"$1.");
    return parts.join(",");
}
function sepNum(x){
    var num = parseInt(x);
    var parts = num.toString().split(".");
    var len = num.toString().length;
    // parts[1] = parts[1]/(Math.pow(10, len));
    parts[0] = parts[0].replace(/(.)(?=(.{3})+$)/g,"$1.");
    return parts.join(",");
}

function toJuta(x) {
    var nil = x / 1000000;
    return sepNum(nil) + " JT";
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
        return (parseFloat(num/pembagi).toFixed(2) * -1) + ' ' +str;
    }else if (num > 0 && num >= 1000){
        return parseFloat(num/pembagi).toFixed(2) + ' ' +str;
    }else if(num > 0 && num < 1000){
        return num;
    }else{
        return num;
    }
}

function drawChart(type, selector, series_array, categories, exporting, click_callback){
    if (typeof categories === 'undefined') { categories = null; }
    if (typeof exporting === 'undefined') { exporting = false; }
    if (typeof click_callback === 'undefined') { click_callback = null; }
    var options = {
        chart: {
            renderTo: selector,
            type: type
        },
        title:{
            text:''
        },
        exporting: { 
            enabled: exporting
        },
        series: [],
        xAxis: {
            title: {
                text: null
            },
            categories: []
        },
        yAxis:{
            title: {
                text: null
            },
            labels: {
                formatter: function () {
                    return sepNumKoma(singkatNilai(this.value));
                }
            }
        },
        credits: {
            enabled: false
        },
    };
    
    options.series = series_array;
    options.xAxis.categories = categories;

    if(click_callback !== null){
        options.plotOptions = click_callback;
    }

    new Highcharts.Chart(options);
}


function loadService(index,method,url,param=null){
    $.ajax({
        type: method,
        url: url,
        dataType: 'json',
        data: {'param':param},
        success:function(result){    
            if(result.status){
                switch(index){
                    case "saihomebox" :
                        $('#home_customer_box').text(result.CUST['cust']);
                        $('#home_produk_box').text(result.PROD['prod']);
                        $('#home_kontrak_box').text(sepNum(result.KONTRAK['kontrak']));
                        $('#home_pembayaran_box').text(sepNum(result.PBYR['pbyr']));
                    break;
                    case "listBox" :
                        var detProject = ``;
                        if(result.project.length > 0){
                            for(var i=0;i<result.project.length;i++){
                                detProject +=`
                                <div class="row sai-monitor-project" style="padding-bottom:7px;padding-top:7px; cursor:pointer">
                                    <p hidden="">`+result.project[i].kode_proses+`</p>
                                    <div class="col-xs-5" style="padding-left:25px;">`+result.project[i].nama+`</div>
                                    <div class="col-xs-2"><span class="pull-right badge bg-blue" style="margin-right:5px;">`+result.project[i].jumlah+`</span></div>
                                    <div class="col-xs-5" style="padding-right:30px;"><span class="pull-right badge bg-yellow">`+sepNum(result.project[i].nilai)+`</span></div>
                                </div>
                                `;
                            }
                            var project = `
                                <div class="row" style="padding-bottom:7px;padding-top:7px;">
                                    <div class="col-xs-5" style="padding-left:25px;"><b> Proses</b></div>
                                    <div class="col-xs-2"><b>Jumlah</b></div>
                                    <div class="col-xs-5" style="padding-right:30px; text-align:right;"><b>Nilai</b></div>
                                </div>
                                `+detProject;
                            $('#project-monitoring-list').html('');
                            $('#project-monitoring-list').html(project);
                        }

                        var detCust = ``;
                        if(result.customer.length > 0){
                            for(var i=0;i<result.customer.length;i++){
                                detCust +=`
                                <div class="row sai-monitor-customer" style="padding-bottom:7px;padding-top:7px; cursor:pointer">
                                    <p hidden="">`+result.customer[i].kode_cust+`</p>
                                    <div class="col-xs-5" style="padding-left:25px;">`+result.customer[i].nama+`</div>
                                    <div class="col-xs-2"><span class="pull-right badge bg-blue" style="margin-right:5px;">`+result.customer[i].jumlah+`</span></div>
                                    <div class="col-xs-5" style="padding-right:30px;"><span class="pull-right badge bg-yellow">`+sepNum(result.customer[i].nilai)+`</span></div>
                                </div>
                                `;
                            }
                            var customer = `
                                <div class="row" style="padding-bottom:7px;padding-top:7px;">
                                    <div class="col-xs-5" style="padding-left:25px;"><b> Proses</b></div>
                                    <div class="col-xs-2"><b>Jumlah</b></div>
                                    <div class="col-xs-5" style="padding-right:30px; text-align:right;"><b>Nilai</b></div>
                                </div>
                                `+detCust;
                            $('#customer-monitoring-list').html('');
                            $('#customer-monitoring-list').html(customer);
                        }

                        var detProduk = ``;
                        if(result.produk.length > 0){
                            for(var i=0;i<result.produk.length;i++){
                                detProduk +=`
                                <div class="row sai-monitor-customer" style="padding-bottom:7px;padding-top:7px; cursor:pointer">
                                    <p hidden="">`+result.produk[i].kode_produk+`</p>
                                    <div class="col-xs-5" style="padding-left:25px;">`+result.produk[i].nama+`</div>
                                    <div class="col-xs-2"><span class="pull-right badge bg-blue" style="margin-right:5px;">`+result.produk[i].jumlah+`</span></div>
                                    <div class="col-xs-5" style="padding-right:30px;"><span class="pull-right badge bg-yellow">`+sepNum(result.produk[i].nilai)+`</span></div>
                                </div>
                                `;
                            }
                            var produk = `
                                <div class="row" style="padding-bottom:7px;padding-top:7px;">
                                    <div class="col-xs-5" style="padding-left:25px;"><b> Proses</b></div>
                                    <div class="col-xs-2"><b>Jumlah</b></div>
                                    <div class="col-xs-5" style="padding-right:30px; text-align:right;"><b>Nilai</b></div>
                                </div>
                                `+detCust;
                            $('#produk-monitoring-list').html('');
                            $('#produk-monitoring-list').html(produk);
                        }
                    break;
                    case "grafik" :
                        var data = result;
                    drawChart('bar', 'home_pros_chartNil', [data.prn.series], data.prn.categories, false, 
                        {
                            bar: {
                                dataLabels: {
                                    enabled: true
                                },
                                cursor: 'pointer',
                                point: {
                                    events: {
                                        click: function() {
                                            var id = this.key;
                                            loadService('Monitor','GET','<?=$root_ser?>/DashSai.php?fx=getMonitoringGrafik',id);
                                        }
                                    }
                                }
                            }
                        }
                        );
                        drawChart('bar', 'home_pros_chartJum', [data.prj.series], data.prj.categories, false, 
                        {
                            bar: {
                                dataLabels: {
                                    enabled: true
                                },
                                cursor: 'pointer',
                                point: {
                                    events: {
                                        click: function() {
                                            // alert(this.name);
                                            // post_data.tgl = this.name;
                                            var id = this.key;
                                            loadService('Monitor','GET','<?=$root_ser?>/DashSai.php?fx=getMonitoringGrafik',id);
                                        }
                                    }
                                }
                            }
                        }
                        );

                        drawChart('bar', 'home_pem_chartNil', [data.pen.series], data.pen.categories, false, 
                        {
                            bar: {
                                dataLabels: {
                                    enabled: true
                                },
                                cursor: 'pointer',
                                point: {
                                    events: {
                                        click: function() {
                                            var id = this.key;
                                            loadService('Monitor','GET','<?=$root_ser?>/DashSai.php?fx=getMonitoringGrafik',id);
                                        }
                                    }
                                }
                            }
                        }
                        );
                        drawChart('bar', 'home_pem_chartJum', [data.pej.series], data.pej.categories, false, 
                        {
                            bar: {
                                dataLabels: {
                                    enabled: true
                                },
                                cursor: 'pointer',
                                point: {
                                    events: {
                                        click: function() {
                                            var id = this.key;
                                            loadService('Monitor','GET','<?=$root_ser?>/DashSai.php?fx=getMonitoringGrafik',id);
                                        }
                                    }
                                }
                            }
                        }
                    );
                    break;
                    case 'Monitor':
                        var data = result;
                        var mon_html = 
                        "<div class='col-xs-12'>"+
                            "<div class='box'>"+
                                "<div class='box-body'>"+
                                    "<a class='btn btn-default pull-right' id='sai-monitor-back'><i class='fa fa-ban'></i> Back</a></div></div>"+
                            "<div class='box'>"+
                                "<div class='box-body'>"+
                                    "<table class='table table-striped' id='monitoring-table'>"+
                                        "<thead>"+
                                            "<tr>"+
                                                "<th>Progress</th>"+
                                                "<th>No. Bukti</th>"+
                                                "<th>Tanggal</th>"+
                                                "<th>Customer</th>"+
                                                "<th>Produk</th>"+
                                                "<th>Karyawan</th>"+
                                                "<th>Nilai</th>"+
                                                "<th>Action</th>"+
                                            "</tr>"+
                                        "</thead><tbody>";

                        if(data.daftar.length > 0){
                            for(i=0; i<data.daftar.length; i++){
                                mon_html += 
                                    "<tr>"+
                                        "<td><p class='label label-warning'>"+data.daftar[i].nama_progress+"</p></td>"+
                                        "<td class='sai-trace-no'>"+data.daftar[i].no_bukti+"</td>"+
                                        "<td>"+data.daftar[i].tanggal+"</td>"+
                                        "<td>"+data.daftar[i].nama_cust+"</td>"+
                                        "<td>"+data.daftar[i].nama_prod+"</td>"+
                                        "<td>"+data.daftar[i].nama_karyawan+"</td>"+
                                        "<td align='right'>"+sepNum(data.daftar[i].nilai)+"</td>"+
                                        "<td align='right'><a class='btn btn-success btn-sm sai-trace-btn'>Detail</a></td>"+
                                    "</tr>";
                                
                            }

                            mon_html += "</tbody></table></div></div></div>";
                        }else{
                            mon_html =  "<div class='col-xs-12'><div class='box'><div class='box-body'><center>Tidak ada data dalam proses ini<hr><a class='btn btn-default' id='sai-monitor-back'><i class='fa fa-ban'></i> Back</a></center></div></div></div>";
                        }

                        
                        // alert(mon_html);
                        $('#sai_home_timeline').html(mon_html);
                        var table = $('#monitoring-table').DataTable({
                            // fixedHeader: true,
                            // "scrollY": "300px",
                            "scrollY":        "200px",
                            // "scrollX": "0px",
                            "scrollCollapse": true,
                            "order": [[ 2, "asc" ]]
                        });

                        // table.columns.adjust().responsive.recalc();

                        $('#sai_home_list').hide();
                        $('#home_cust').hide();
                        $('#home_prod').hide();
                        $('#home_kontrak').hide();
                        $('#home_pbyr').hide();
                        $('#sai_home_grafik').hide();
                        $('#sai_home_timeline').show();
                        table.columns.adjust().draw();
                    break;
                    case 'Trace Progress':
                        var body = '';
                        var data = result;
                        var mon_html = "<div class='col-xs-12'><div class='box'><div class='box-body'><a class='btn btn-default pull-right' id='sai-tracing-back'><i class='fa fa-ban'></i> Back</a></div></div><ul class='timeline'>";
                        if(data.daftar.length > 0){
                            for(i=0; i<data.daftar.length; i++){

                                if(data.daftar[i].data.no_bukti != null){
                                    switch(data.daftar[i].kode){
                                        case "01":
                                            body = 
                                            "<div class='row'>"+
                                                "<div class='col-md-2'><b>"+
                                                    "No"+
                                                "</b></div>"+
                                                "<div class='col-md-10 sai-trace-no'>"+
                                                    data.daftar[i].data.no_bukti+
                                                "</div>"+
                                            "</div>"+
                                            "<div class='row'>"+
                                                "<div class='col-md-2'><b>"+
                                                    "Customer"+
                                                "</b></div>"+
                                                "<div class='col-md-10'>"+
                                                    data.daftar[i].data.nama_cust+
                                                "</div>"+
                                            "</div>"+
                                            "<div class='row'>"+
                                                "<div class='col-md-2'><b>"+
                                                    "Tanggal"+
                                                "</b></div>"+
                                                "<div class='col-md-10'>"+
                                                    data.daftar[i].data.tanggal+
                                                "</div>"+
                                            "</div>"+
                                            "<div class='row'>"+
                                                "<div class='col-md-2'><b>"+
                                                    "Nilai"+
                                                "</b></div>"+
                                                "<div class='col-md-10'>"+
                                                    sepNum(data.daftar[i].data.nilai)+
                                                "</div>"+
                                            "</div>"
                                        break;
                                        case "02":
                                            body = 
                                            "<div class='row'>"+
                                                "<div class='col-md-2'><b>"+
                                                    "No"+
                                                "</b></div>"+
                                                "<div class='col-md-10 sai-trace-no'>"+
                                                    data.daftar[i].data.no_bukti+
                                                "</div>"+
                                            "</div>"+
                                            "<div class='row'>"+
                                                "<div class='col-md-2'><b>"+
                                                    "Tanggal"+
                                                "</b></div>"+
                                                "<div class='col-md-10'>"+
                                                    data.daftar[i].data.tanggal+
                                                "</div>"+
                                            "</div>"+
                                            "<div class='row'>"+
                                                "<div class='col-md-2'><b>"+
                                                    "Keterangan"+
                                                "</b></div>"+
                                                "<div class='col-md-10'>"+
                                                    data.daftar[i].data.keterangan+
                                                "</div>"+
                                            "</div>"+
                                            "<div class='row'>"+
                                                "<div class='col-md-2'><b>"+
                                                    "Nilai"+
                                                "</b></div>"+
                                                "<div class='col-md-10'>"+
                                                    sepNum(data.daftar[i].data.nilai)+
                                                "</div>"+
                                            "</div>";
                                        break;
                                        case "03":
                                            body = 
                                            "<div class='row'>"+
                                                "<div class='col-md-2'><b>"+
                                                    "No Bukti"+
                                                "</b></div>"+
                                                "<div class='col-md-10 sai-trace-no'>"+
                                                    data.daftar[i].data.no_bukti+
                                                "</div>"+
                                            "</div>"+
                                            "<div class='row'>"+
                                                "<div class='col-md-2'><b>"+
                                                    "Tanggal"+
                                                "</b></div>"+
                                                "<div class='col-md-10'>"+
                                                    data.daftar[i].data.tanggal+
                                                "</div>"+
                                            "</div>"+
                                            "<div class='row'>"+
                                                "<div class='col-md-2'><b>"+
                                                    "Keterangan"+
                                                "</b></div>"+
                                                "<div class='col-md-10'>"+
                                                    data.daftar[i].data.keterangan+
                                                "</div>"+
                                            "</div>"+
                                            "<div class='row'>"+
                                                "<div class='col-md-2'><b>"+
                                                    "Nilai"+
                                                "</b></div>"+
                                                "<div class='col-md-10'>"+
                                                    sepNum(data.daftar[i].data.nilai)+
                                                "</div>"+
                                            "</div>";
                                        break;
                                        case "04":
                                            body = 
                                            "<div class='row'>"+
                                                "<div class='col-md-2'><b>"+
                                                    "No Bukti"+
                                                "</b></div>"+
                                                "<div class='col-md-10 sai-trace-no'>"+
                                                    data.daftar[i].data.no_bukti+
                                                "</div>"+
                                            "</div>"+
                                            "<div class='row'>"+
                                                "<div class='col-md-2'><b>"+
                                                    "Tanggal"+
                                                "</b></div>"+
                                                "<div class='col-md-10'>"+
                                                    data.daftar[i].data.tanggal+
                                                "</div>"+
                                            "</div>"+
                                            "<div class='row'>"+
                                                "<div class='col-md-2'><b>"+
                                                    "Keterangan"+
                                                "</b></div>"+
                                                "<div class='col-md-10'>"+
                                                    data.daftar[i].data.keterangan+
                                                "</div>"+
                                            "</div>"+
                                            "<div class='row'>"+
                                                "<div class='col-md-2'><b>"+
                                                    "Nilai"+
                                                "</b></div>"+
                                                "<div class='col-md-10'>"+
                                                    sepNum(data.daftar[i].data.nilai)+
                                                "</div>"+
                                            "</div>";
                                        break;
                                        case "05":
                                            body = 
                                            "<div class='row'>"+
                                                "<div class='col-md-2'><b>"+
                                                    "No Bukti"+
                                                "</b></div>"+
                                                "<div class='col-md-10 sai-trace-no'>"+
                                                    data.daftar[i].data.no_bukti+
                                                "</div>"+
                                            "</div>"+
                                            "<div class='row'>"+
                                                "<div class='col-md-2'><b>"+
                                                    "Tanggal"+
                                                "</b></div>"+
                                                "<div class='col-md-10'>"+
                                                    data.daftar[i].data.tanggal+
                                                "</div>"+
                                            "</div>"+
                                            "<div class='row'>"+
                                                "<div class='col-md-2'><b>"+
                                                    "Keterangan"+
                                                "</b></div>"+
                                                "<div class='col-md-10'>"+
                                                    data.daftar[i].data.keterangan+
                                                "</div>"+
                                            "</div>"+
                                            "<div class='row'>"+
                                                "<div class='col-md-2'><b>"+
                                                    "Nilai"+
                                                "</b></div>"+
                                                "<div class='col-md-10'>"+
                                                    sepNum(data.daftar[i].data.nilai)+
                                                "</div>"+
                                            "</div>";
                                        break;
                                        case "06":
                                            body = 
                                            "<div class='row'>"+
                                                "<div class='col-md-2'><b>"+
                                                    "No Bukti"+
                                                "</b></div>"+
                                                "<div class='col-md-10 sai-trace-no'>"+
                                                    data.daftar[i].data.no_bukti+
                                                "</div>"+
                                            "</div>"+
                                            "<div class='row'>"+
                                                "<div class='col-md-2'><b>"+
                                                    "Tanggal"+
                                                "</b></div>"+
                                                "<div class='col-md-10'>"+
                                                    data.daftar[i].data.tanggal+
                                                "</div>"+
                                            "</div>"+
                                            "<div class='row'>"+
                                                "<div class='col-md-2'><b>"+
                                                    "Keterangan"+
                                                "</b></div>"+
                                                "<div class='col-md-10'>"+
                                                    data.daftar[i].data.keterangan+
                                                "</div>"+
                                            "</div>"+
                                            "<div class='row'>"+
                                                "<div class='col-md-2'><b>"+
                                                    "Nilai"+
                                                "</b></div>"+
                                                "<div class='col-md-10'>"+
                                                    sepNum(data.daftar[i].data.nilai)+
                                                "</div>"+
                                            "</div>";
                                        break;
                                        case "07":
                                            body = 
                                            "<div class='row'>"+
                                                "<div class='col-md-2'><b>"+
                                                    "No Bukti"+
                                                "</b></div>"+
                                                "<div class='col-md-10 sai-trace-no'>"+
                                                    data.daftar[i].data.no_bukti+
                                                "</div>"+
                                            "</div>"+
                                            "<div class='row'>"+
                                                "<div class='col-md-2'><b>"+
                                                    "Tanggal"+
                                                "</b></div>"+
                                                "<div class='col-md-10'>"+
                                                    data.daftar[i].data.tanggal+
                                                "</div>"+
                                            "</div>"+
                                            "<div class='row'>"+
                                                "<div class='col-md-2'><b>"+
                                                    "Keterangan"+
                                                "</b></div>"+
                                                "<div class='col-md-10'>"+
                                                    data.daftar[i].data.keterangan+
                                                "</div>"+
                                            "</div>"+
                                            "<div class='row'>"+
                                                "<div class='col-md-2'><b>"+
                                                    "Nilai"+
                                                "</b></div>"+
                                                "<div class='col-md-10'>"+
                                                    sepNum(data.daftar[i].data.nilai)+
                                                "</div>"+
                                            "</div>";
                                        break;
                                        default:
                                            body = "style unset";
                                        break;
                                    }
                                    mon_html +=   
                                        "<li>"+
                                            "<i class='fa fa-map-marker bg-blue'></i>"+
                                            "<div class='timeline-item'>"+
                                                "<span class='time'><i class='fa fa-clock-o'></i> "+data.daftar[i].data.tanggal+"</span>"+
                                                "<h3 class='timeline-header'><p class='label label-primary'><p class='label label-warning' style='margin-right:5px;'>"+data.daftar[i].data.nama_progress+" </p></h3>"+
                                                // "+
                                                // data.daftar[i].data.no_bukti+"
                                                "<div class='timeline-body'>"+
                                                    body+
                                                "</div>"+
                                                "<div class='timeline-footer'>"+
                                                    "<a class='btn btn-success btn-xs sai-doc-btn' data-toggle='collapse' data-target='#doc-"+i+"'>Dokumen</a><a class='btn btn-warning btn-xs sai-doc-keep' data-toggle='collapse' data-target='#keep-"+i+"' style='margin-left:5px;'>Keep</a>"+
                                                    "<div class='sai-doc-list collapse' id='doc-"+i+"'></div>"+
                                                "</div>"+
                                            "</div>"+
                                        "</li>";
                                }
                            }

                            mon_html += "<li><i class='fa fa-clock-o'></i></li></ul></div>";
                        }else{
                            mon_html =  "<div class='col-xs-12'><div class='box'><div class='box-body'><center>Tidak ada data dalam proses ini<hr><a class='btn btn-default' id='sai-monitor-back'><i class='fa fa-ban'></i> Back</a></center></div></div></div>";
                        }

                        $('#sai_home_tracing').html(mon_html);

                        $('#sai_home_timeline').hide();
                        $('#sai_home_tracing').show();
                    break;
                    
                }
            }
        }
    });
}
function initDash(){
    loadService('saihomebox','GET','<?=$root_ser?>/DashSai.php?fx=getDataBox');
    loadService('listBox','GET','<?=$root_ser?>/DashSai.php?fx=getDataListBox');
    loadService('grafik','GET','<?=$root_ser?>/DashSai.php?fx=getDataGrafik');
}

initDash();

$('#ajax-content-section').on('click', '#sai-monitor-back', function(){
    $('#sai_home_list').show();
    $('#home_cust').show();
    $('#home_prod').show();
    $('#home_kontrak').show();
    $('#home_pbyr').show();
    $('#sai_home_grafik').show();
    $('#sai_home_timeline').hide();
});

$('#ajax-content-section').on('click', '#sai-tracing-back', function(){
    $('#sai_home_timeline').show();
    $('#sai_home_tracing').hide();
});

$('#ajax-content-section').on('click', '.sai-monitor-project', function(){
    var id = $(this).find('p').text();
    // console.log(id);
    loadService('Monitor','GET','<?=$root_ser?>/DashSai.php?fx=getMonitoringList',id);
});

$('#ajax-content-section').on('click', '.sai-monitor-customer', function(){
    var id = $(this).find('p').text();
    // console.log(id);
    loadService('Monitor','GET','<?=$root_ser?>/DashSai.php?fx=getMonitoringListCust',id);
});

$('#ajax-content-section').on('click', '.sai-monitor-produk', function(){
    var id = $(this).find('p').text();
    // console.log(id);
    loadService('Monitor','GET','<?=$root_ser?>/DashSai.php?fx=getMonitoringListProd',id);
});

$('#ajax-content-section').on('click', '.sai-trace-btn', function(){
    var id = $(this).closest('tr').find('.sai-trace-no').text();
    
    loadService('Trace Progress','GET','<?=$root_ser?>/DashSai.php?fx=getTraceProgress',id);
});



</script>
