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
?>

<div class="container-fluid mt-3">
    <div class="row" >
        <div class="col-sm-12">
            <div class="card " id="sai-rpt-filter-box">
                <div class="card-header with-border">
                    <h3 class="card-title" style="position:absolute"><i class="fa fa-file-text-o"></i> Laporan Registrasi</h3>
                    <div class="box-tools float-right">
                        <a class="sai-btn-circle" id="sai-rpt-prev-page" title="Back"><i class="fa fa-arrow-circle-left"></i> 
                        </a>
                        <a class="sai-btn-circle" id="sai-rpt-export-excel" title="Excel"><i class="fa fa-file-excel-o"></i>
                        </a>
                        <a class="sai-btn-circle" id="sai-rpt-print" title="Print"><i class="fa fa-print"></i>
                        </a>
                        <a class="sai-btn-circle" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne"><i class="fa fa-minus"></i>
                        </a>
                    </div>
                </div>
                <div id="collapseOne" class="collapse show" style="">
                    <div class="card-body" style="">
                        <div class="row">
                            <div class="col-md-12">
                                <form id="web-LapReg-form" class="">
                                    <div class="row sai-rpt-filter-entry-row mb-1">
                                        <!-- <div class="form-group"> -->
                                            <div class="col-md-4">
                                                <p class="sai-rpt-filter-entry-row-par" hidden="">periode</p>
                                                Periode
                                            </div>
                                            <div class="col-md-2">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <select name="periode[]" class="form-control sai-rpt-filter-type" required="">
                                                            <option value="exact" selected="">=</option><option value="all">All</option><option value="range">Range</option><option value="none">none</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="row sai-rpt-filter-from">  
                                                    <div class="col-md-12">
                                                        <select name="periode[]" class="form-control sai-rpt-filter-selectize periode_from selectize" tabindex="-1" style="display: none;">
                                                        <?php 
                                                            $sql="select distinct periode from dgw_reg where kode_lokasi='$kode_lokasi' ";
                                                            $rs1=execute($sql);
                                                            while($row = $rs1->FetchNextObject($toupper)){
                                                                echo"<option value='$row->periode' >$row->periode</option>";
                                                            }
                                                        ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="row sai-rpt-filter-to" >
                                                    <div class="col-md-12">
                                                        <select name="periode[]" class="form-control sai-rpt-filter-selectize periode_to selectize" tabindex="-1" style="display: none;">
                                                        <?php 
                                                            $sql="select distinct periode from dgw_reg where kode_lokasi='$kode_lokasi' ";
                                                            $rs1=execute($sql);
                                                            while($row = $rs1->FetchNextObject($toupper)){
                                                                echo"<option value='$row->periode' >$row->periode</option>";
                                                            }
                                                        ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        <!-- </div> -->
                                    </div>
                                    <div class="row sai-rpt-filter-entry-row mb-1">
                                        <!-- <div class="form-group"> -->
                                            <div class="col-md-4">
                                                <p class="sai-rpt-filter-entry-row-par" hidden="">paket</p>
                                                Paket
                                            </div>
                                            <div class="col-md-2">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <select name="no_paket[]" class="form-control sai-rpt-filter-type" required="">
                                                            <option value="all" selected="">All</option><option value="range">Range</option><option value="exact">=</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="row sai-rpt-filter-from row_no_paket" >
                                                    <div class="col-md-10">
                                                        <input type="text" name="no_paket[]" class="form-control" id="no_paket_from" value="">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <a class="sai-btn-circle">
                                                            <i class="fa fa-search sai-rpt-data-list-search"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="row sai-rpt-filter-to" >
                                                    <div class="col-md-10">
                                                        <input type="text" name="no_paket[]" class="form-control" id="no_paket_to" value="">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <a class="sai-btn-circle">
                                                            <i class="fa fa-search sai-rpt-data-list-search"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        <!-- </div> -->
                                    </div>
                                    <div class="row sai-rpt-filter-entry-row mb-1">
                                        <!-- <div class="form-group"> -->
                                            <div class="col-md-4">
                                                <p class="sai-rpt-filter-entry-row-par" hidden="">jadwal</p>
                                                Jadwal
                                            </div>
                                            <div class="col-md-2">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <select name="no_jadwal[]" class="form-control sai-rpt-filter-type" required="">
                                                            <option value="all" selected="">All</option><option value="range">Range</option><option value="exact">=</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="row sai-rpt-filter-from row_no_jadwal" >
                                                    <div class="col-md-10">
                                                        <input type="text" name="no_jadwal[]" class="form-control" id="no_jadwal_from" value="">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <a class="sai-btn-circle">
                                                            <i class="fa fa-search sai-rpt-data-list-search"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="row sai-rpt-filter-to" >
                                                    <div class="col-md-10">
                                                        <input type="text" name="no_jadwal[]" class="form-control" id="no_jadwal_to" value="">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <a class="sai-btn-circle">
                                                            <i class="fa fa-search sai-rpt-data-list-search"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        <!-- </div> -->
                                    </div>
                                    <div class="row sai-rpt-filter-entry-row mb-1">
                                        <!-- <div class="form-group"> -->
                                            <div class="col-md-4">
                                                <p class="sai-rpt-filter-entry-row-par" hidden="">no_reg</p>
                                                No Registrasi
                                            </div>
                                            <div class="col-md-2">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <select name="no_reg[]" class="form-control sai-rpt-filter-type" required="">
                                                            <option value="all" selected="">All</option><option value="range">Range</option><option value="exact">=</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="row sai-rpt-filter-from row_no_reg" >
                                                    <div class="col-md-10">
                                                        <input type="text" name="no_reg[]" class="form-control" id="no_reg_from" value="">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <a class="sai-btn-circle">
                                                            <i class="fa fa-search sai-rpt-data-list-search"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="row sai-rpt-filter-to" >
                                                    <div class="col-md-10">
                                                        <input type="text" name="no_reg[]" class="form-control" id="no_reg_to" value="">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <a class="sai-btn-circle">
                                                            <i class="fa fa-search sai-rpt-data-list-search"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        <!-- </div> -->
                                    </div>
                                    <div class="row sai-rpt-filter-entry-row mb-1">
                                        <!-- <div class="form-group"> -->
                                            <div class="col-md-4">
                                                <p class="sai-rpt-filter-entry-row-par" hidden="">no_peserta</p>
                                                No Peserta
                                            </div>
                                            <div class="col-md-2">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <select name="no_peserta[]" class="form-control sai-rpt-filter-type" required="">
                                                            <option value="all" selected="">All</option><option value="range">Range</option><option value="exact">=</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="row sai-rpt-filter-from row_no_peserta" >
                                                    <div class="col-md-10">
                                                        <input type="text" name="no_peserta[]" class="form-control" id="no_peserta_from" value="">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <a class="sai-btn-circle">
                                                            <i class="fa fa-search sai-rpt-data-list-search"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="row sai-rpt-filter-to" >
                                                    <div class="col-md-10">
                                                        <input type="text" name="no_peserta[]" class="form-control" id="no_peserta_to" value="">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <a class="sai-btn-circle">
                                                            <i class="fa fa-search sai-rpt-data-list-search"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        <!-- </div> -->
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                        </div>
                                        <div class="col-md-8">
                                            <button type="submit" class="btn btn-primary" style="margin-left: 6px;" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false"><i class="fa fa-search" ></i> Preview</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 ">
            <div class="card">
                <p id="sai-rpt-active-page-number" hidden="">0</p>
                <p id="sai-rpt-number-of-page" hidden="">5</p>
                <div id="sai-rpt-filter-box-result-page-1"></div>
                <div id="sai-rpt-filter-box-result-page-2" hidden=""></div>
                <div id="sai-rpt-filter-box-result-page-3" hidden=""></div>
                <div id="sai-rpt-filter-box-result-page-4" hidden=""></div>
                <div id="sai-rpt-filter-box-result-page-5" hidden="">
                </div>
            </div>
        </div>
    </div>
</div>
<div class='modal fade' id='sai-rpt-data-list-modal' tabindex='-1' role='dialog' aria-hidden='true'>
    <div class='modal-dialog' role='document' style='min-width:700px;'>
        <div class='modal-content'>
            <div class='modal-header'>
                <h4 class='modal-title'>Report Data List</h4>
                <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                <span aria-hidden='true'>×</span></button>
            </div>
            <div class='modal-body'>
                <div style='overflow-y: auto; max-height: 600px; overflow-x:hidden; padding-right:10px;' id='sai-rpt-data-list-modal-body'>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?=$folderroot_js?>/printThis/printThis.js"></script>
<script src="<?=$folderroot_js?>/jquery.tableToExcel.js"></script>
<script src="<?=$folderroot_js?>/jquery.twbsPagination.min.js"></script>
<script type="text/javascript">

    function sepNum(x){
        if (typeof x === 'undefined' || !x) { 
            return 0;
        }else{
            if(x < 0){
                var x = parseFloat(x).toFixed(0);
            }
            
            var parts = x.toString().split(",");
            parts[0] = parts[0].replace(/([0-9])(?=([0-9]{3})+$)/g,"$1.");
            return parts.join(".");
        }
    }

    function saiPost(post_url, cancel_url, formData, table_refresh_target_id, success_callback, failed_callback, clear){
    if (typeof clear === 'undefined') { clear = true; }
    if (typeof table_refresh_target_id === 'undefined') { table_refresh_target_id = null; }
    if (typeof success_callback === 'undefined') { success_callback = null; }
    if (typeof failed_callback === 'undefined') { failed_callback = null; }
    var status = true;
    $.ajax({
        url: post_url,
        data: formData,
        type: "post",
        dataType: "json",
        contentType: false,       // The content type used when sending data to the server.
        cache: false,             // To unable request pages to be cached
        processData:false, 
        success: function (data) {
            if(data.auth_status == 1){
                if(typeof data.alert !== 'undefined'){
                    alert("Auth success : "+data.alert);
                }
                
                if(success_callback != null){
                    success_callback(data);
                }else{
                    if(data.status == 1){
                        // clear input dan validation box jika berhasil
                        if(table_refresh_target_id != null){
                            $(table_refresh_target_id).DataTable().ajax.reload();
                        }
                        
                        if(clear){
                            // if(confirm('Bersihkan input?')){
                            clearInput();
                            // }
                        }
    
                        if(data.edit){
                            if(cancel_url == null){
                                location.reload();
                            }else{
                                window.location = cancel_url;
                            }
                            // for(i = 0; i < $('.selectize').length; i++){
                            //     $('.selectize')[i].selectize.setValue('');
                            //     alert($('.selectize')[i].selectize.getValue());
                            //     if selectize.length == null ?
                            // }
                        }else{
                            $('.nav-tabs a[href="#sai-container-daftar"]').tab('show');
                        }
                    }
                }

                if(failed_callback != null){
                    failed_callback(data);
                }else{
                    if (data.status == 3){
                        // https://stackoverflow.com/a/26166303
                        var error_array = Object.keys(data.error_input).map(function (key) { return data.error_input[key]; });
    
                        // append input element error
                        var error_list = "<div class='alert alert-danger' style='padding:0px; padding-top:5px; padding-bottom:5px; margin:0px; color: #a94442; background-color: #f2dede; border-color: #ebccd1;'><ul>";
                        for(i = 0; i<error_array.length; i++){
                            error_list += '<li>'+error_array[i]+'</li>';
                        }
                        error_list += "</ul></div>";
                        $('#validation-box').html(error_list);
                    }
                }
            }else{
                alert("Auth Failed : "+data.error);
            }
        }
    });
}

    $('.sai-rpt-filter-to').hide();
    $('.row_no_paket').hide();
    $('.row_no_jadwal').hide();
    $('.row_no_peserta').hide();
    $('.row_no_reg').hide();
    $('#sai-rpt-prev-page').click(function(){
        var active_page = $('#sai-rpt-active-page-number').text();

        if(+active_page > 1){
            // show hide
            $('#sai-rpt-filter-box-result-page-'+ active_page).hide();
            $('#sai-rpt-filter-box-result-page-'+ (+active_page  - 1) ).show();
            $('#sai-rpt-active-page-number').text((+active_page - 1));
        }else{
            return false;
        }
    })

    $('#ajax-content-section').on('click','.web-print-nota',function(){
        // $('#sai-rpt-table-export').printThis();
        // alert('test');
        var kode = $(this).closest('tr').find('td:eq(1)').text();
        window.location.href='<?=$root_app?>/fNota/?param='+kode;
    });
    
    // $('#sai-rpt-filter-box').boxWidget({
    //     animationSpeed: 500,
    //     collapseIcon: 'fa-minus',
    //     expandIcon: 'fa-plus'
    // });
    
    $('#ajax-content-section').on('click', '.sai-rpt-data-list-modal-body-jsonTable-action', function(){
        var val = $(this).closest('tr').find('td:nth-child(1)').text();
        $('#'+target).val(val);
        $('#sai-rpt-data-list-modal').modal('hide');
    });

    $('#ajax-content-section').on('change', '.sai-rpt-filter-type', function(){
        var val = $(this).val();
        if(val == 'all'){
            $(this).closest('.sai-rpt-filter-entry-row').find('.sai-rpt-filter-from').hide();
            $(this).closest('.sai-rpt-filter-entry-row').find('.sai-rpt-filter-to').hide();
        }else if(val == 'exact'){
            $(this).closest('.sai-rpt-filter-entry-row').find('.sai-rpt-filter-from').show();
            $(this).closest('.sai-rpt-filter-entry-row').find('.sai-rpt-filter-to').hide();
        }else{
            $(this).closest('.sai-rpt-filter-entry-row').find('.sai-rpt-filter-from').show();
            $(this).closest('.sai-rpt-filter-entry-row').find('.sai-rpt-filter-to').show();
        }
        // alert(val);
    });

    function resetRptPage(){
        var active_page = $('#sai-rpt-active-page-number').text();
        var number_of_page = $('#sai-rpt-number-of-page').text();

        for(i=number_of_page; i>1; i--){
            $('#sai-rpt-filter-box-result-page-' + i).hide();
        }

        $('#sai-rpt-active-page-number').text(0);
    }

function drawRptPage(html, table_id){
    if(typeof table_id == 'undefined'){table_id = null}
    // console.log(table_id);

    var active_page = $('#sai-rpt-active-page-number').text();
    var number_of_page = $('#sai-rpt-number-of-page').text();

    // console.log('active : ' + active_page);
    if(+active_page == 0){
        // $('#sai-rpt-filter-box-result-page-2').hide();
        $('#sai-rpt-filter-box-result-page-1').html(html);
        $('#sai-rpt-filter-box-result-page-1').show();
        $('#sai-rpt-active-page-number').text(1);
        // console.log('target : 0');
    }else if((+active_page + 1) <= +number_of_page){
        $('#sai-rpt-filter-box-result-page-' + active_page).hide();
        $('#sai-rpt-filter-box-result-page-' + (+active_page + 1)).html(html);
        $('#sai-rpt-filter-box-result-page-' + (+active_page + 1)).show();
        $('#sai-rpt-active-page-number').text((+active_page + 1));
        // console.log('target : ' + (+active_page + 1));
    }else{
        // $('#sai-rpt-filter-box-result-page-2').hide();
        $('#sai-rpt-filter-box-result-page-1').html(html);
        $('#sai-rpt-filter-box-result-page-1').show();
        $('#sai-rpt-active-page-number').text(1);
        // console.log('target : 1');
    }
    
    if(table_id != null){
        // exportable(table_id, 'documents', ['xlsx'], {'xlsx':'#sai-rpt-export-excel'});
        $('#sai-rpt-print').click(function(){
           
        //     var w = window.open();
        //     var html =`<html><head>
        //         <meta charset="utf-8">
        //         <meta http-equiv="X-UA-Compatible" content="IE=edge">
        //         <meta name="viewport" content="width=device-width, initial-scale=1">
        //         <meta name="description" content="">
        //         <meta name="author" content="">
        //         <title>SAKU | Sistem Akuntansi Keuangan Digital</title>
        //         <link href="<?php echo $folder_css?>/style.min.css" rel="stylesheet">
        //         <!-- Dashboard 1 Page CSS -->
        //         <link href="<?php echo $folder_css?>/pages/dashboard1.css" rel="stylesheet">
        //         <link rel="stylesheet" type="text/css" href="<?php echo $folder_assets?>/node_modules/datatables.net-bs4/css/dataTables.bootstrap4.css">
        //         <!-- SAI CSS -->
        //         <link href="<?php echo $folder_css?>/sai.css" rel="stylesheet">
        //         <style>
        //         @media print {
        //             body, html, #main-wrapper {
        //                 width: 100%;
        //                 height:100%;
        //             }
        //         }
        //         </style>
        //     </head>
        //     <!--
        //     <body class="skin-default fixed-layout" >-->
        //         <div id="main-wrapper" style='color:black'>
        //             <div class="page-wrapper" style='min-height: 674px;margin: 0;padding: 10px;background: white;color: black !important;'>
        //                 <section class="content" id='ajax-content-section' style='color:black !important'>
        //                     <div class="container-fluid mt-3">
        //                         <div class="row" id="slide-print">
        //                             <div class="col-md-12">
        //                                 <div class="card">
        //                                     <div class="card-body">`+$('#'+table_id).html()+`
        //                                     </div>
        //                                 </div>
        //                             </div>
        //                         </div>
        //                     </div>
        //                 </section>
        //             </div>
        //         </div>
        //     <!--</body></html>-->
        //     `;
        //     w.document.write(html);
        //     setTimeout(function(){
        //         w.print();
        //         w.close();
        //     }, 1500);
            
            $('#'+table_id).printThis();
        });

    }
}
function terbilang(int){
        angka = ["", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas"];
        int = Math.floor(int);
        if (int < 12)
            return " " + angka[int];
        else if (int < 20)
            return terbilang(int - 10) + " belas ";
        else if (int < 100)
            return terbilang(int / 10) + " puluh " + terbilang(int % 10);
        else if (int < 200)
            return "seratus" + terbilang(int - 100);
        else if (int < 1000)
            return terbilang(int / 100) + " ratus " + terbilang(int % 100);
        else if (int < 2000)
            return "seribu" + terbilang(int - 1000);
        else if (int < 1000000)
            return terbilang(int / 1000) + " ribu " + terbilang(int % 1000);
        else if (int < 1000000000)
            return terbilang(int / 1000000) + " juta " + terbilang(int % 1000000);
        else if (int < 1000000000000)
            return terbilang(int / 1000000) + " milyar " + terbilang(int % 1000000000);
        else if (int >= 1000000000000)
            return terbilang(int / 1000000) + " trilyun " + terbilang(int % 1000000000000);
    }

function drawLapReg(formData){
       
       saiPost('<?=$root_ser?>/Laporan.php?fx=getLapReg', null, formData, null, function(data){
           if(data.result.length > 0){
            //    $('#sai-rpt-filter-box').boxWidget('toggle');

               var mon_html = `<div align='center' style='padding:10px' id='sai-rpt-table-export-tbl-daftar-reg'>
               `;
               var arr_tl = [0,0,0,0,0,0,0,0,0];
               var x=1;
               for(var i=0;i<data.result.length;i++){
                   var line = data.result[i];
                   mon_html +=`
                        <table width='100%' class='table no-border' cellspacing='1' cellpadding='2'>
                        <style>
                            td,th{
                                padding:5px !important;
                            }
                        </style>
                        <tr>
                        <td colspan='2' align='center' style='font-weight:bold;'>FORMULIR PENDAFATARAN UMROH </td>
                        </tr>
                        <tr>
                        <td colspan='2'>&nbsp;</td>
                        </tr>
                        <tr>
                        <td colspan='2' style='font-weight:bold;'>DATA PRIBADI </td>
                        </tr>
                        <tr>
                        <td width='30%' style='font-weight:bold;'>NAMA LENGKAP </td>
                        <td width='70%'>:&nbsp;`+line.peserta+`</td>
                        </tr>
                        <tr>
                        <td style='font-weight:bold;'>NO ID CARD </td>
                        <td>:&nbsp;`+line.id_peserta+`</td>
                        </tr>
                        <tr>
                        <td style='font-weight:bold;'>STATUS</td>
                        <td>:&nbsp;`+line.status+`</td>
                        </tr>
                        <tr>
                        <td style='font-weight:bold;'>JENIS KELAMIN </td>
                        <td>:&nbsp;`+line.jk+`</td>
                        </tr>
                        <tr>
                        <td style='font-weight:bold;'>TEMPAT &amp; TGL LAHIR </td>
                        <td>:&nbsp;`+line.tempat+` `+line.tgl_lahir+`</td>
                        </tr>
                        <tr>
                        <td style='font-weight:bold;'>BERANGKAT DENGAN </td>
                        <td>:&nbsp;`+line.brkt_dgn+`<br> Hubungan : `+line.hubungan+`</td>
                        </tr>
                        <tr>
                        <td style='font-weight:bold;'>PERNAH UMROH / HAJI </td>
                        <td>:&nbsp;`+line.th_umroh+`/`+line.th_haji+`</td>
                        </tr>
                        <tr>
                        <td style='font-weight:bold;'>PEKERJAAN</td>
                        <td>:&nbsp;`+line.pekerjaan+`</td>
                        </tr>
                        <tr>
                        <td style='font-weight:bold;'>NO PASSPORT </td>
                        <td>:&nbsp;`+line.nopass+`</td>
                        </tr>
                        <tr>
                        <td style='font-weight:bold;'>KANTOR IMIGRASI </td>
                        <td>:&nbsp;`+line.kantor_mig+`</td>
                        </tr>
                        <tr>
                        <td style='font-weight:bold'>HP</td>
                        <td>:&nbsp;`+line.hp+`</td>
                        </tr>
                        <tr>
                        <td style='font-weight:bold;'>TELEPON</td>
                        <td>:&nbsp;`+line.telp+`</td>
                        </tr>
                        <tr>
                        <td style='font-weight:bold;'>EMAIL</td>
                        <td>:&nbsp;`+line.email+`</td>
                        </tr>
                        <tr>
                        <td style='font-weight:bold;'>ALAMAT</td>
                        <td>:&nbsp;`+line.alamat+`</td>
                        </tr>
                        <tr>
                        <td style='font-weight:bold;'>NO EMERGENCY </td>
                        <td>:&nbsp;`+line.ec_telp+`</td>
                        </tr>
                        <tr>
                        <td style='font-weight:bold;'>MARKETING</td>
                        <td>:&nbsp;`+line.nama_marketing+`</td>
                        </tr>
                        <tr>
                        <td style='font-weight:bold;'>AGEN</td>
                        <td>:&nbsp;`+line.nama_agen+`</td>
                        </tr>
                        <tr>
                        <td style='font-weight:bold;'>REFERAL</td>
                        <td>:&nbsp;`+line.referal+`</td>
                        </tr>
                        <tr>
                        <td colspan='2'>&nbsp;</td>
                        </tr>
                        <tr>
                        <td colspan='2' style='font-weight:bold;'>DATA KELANGKAPAN PERJALANAN </td>
                        </tr>
                        <tr>
                        <td style='font-weight:bold;'>PAKET</td>
                        <td>:&nbsp;`+line.no_paket+`</td>
                        </tr>
                        <tr>
                        <td style='font-weight:bold;'>PROGRAM UMROH / HAJI </td>
                        <td>:&nbsp;`+line.namapaket+`</td>
                        </tr>
                        <tr>
                        <td style='font-weight:bold;'>TYPE ROOM </td>
                        <td>:&nbsp;`+line.no_type+`</td>
                        </tr>
                        <tr>
                        <td style='font-weight:bold;'>BIAYA PAKET </td>
                        <td>:&nbsp;`+sepNum(line.harga)+`</td>
                        </tr>
                        <tr>
                        <td style='font-weight:bold;'>DISKON</td>
                        <td>:&nbsp;`+sepNum(line.diskon)+`</td>
                        </tr>
                        <tr>
                        <td style='font-weight:bold;'>TGL KEBERANGKATAN </td>
                        <td>:&nbsp;`+line.tgl_berangkat+`</td>
                        </tr>
                        <tr>
                        <td style='font-weight:bold;'>UKURAN PAKAIAN </td>
                        <td>:&nbsp;`+line.uk_pakaian+`</td>
                        </tr>
                        <tr>
                        <td style='font-weight:bold;'>SUMBER INFORMASI </td>
                        <td>:&nbsp;`+line.info+`</td>
                        </tr>
                        <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        </tr>
                        <tr>
                        <td align='center'>Calon Jamaah</td>
                        <td align='center'>MO</td>
                        </tr>
                        <tr>
                        <td height='60'>&nbsp;</td>
                        <td>&nbsp;</td>
                        </tr>
                        <tr>
                        <td style='text-align:center'>(..............................................)</td>
                        <td style='text-align:center'>(..............................................)</td>
                        </tr>
                        </table>
                        <br><DIV style='page-break-after:always'></DIV>`;
                        
               }
               mon_html+=`</div>`;
               drawRptPage(mon_html, 'sai-rpt-table-export-tbl-daftar-reg');  
           }
  
       });
   }

    $('#ajax-content-section').on('submit', '#web-LapReg-form', function(e){
        e.preventDefault();
        var formData = new FormData(this);
        // formData.append('api_key', localStorage.api_key);
        var kode_lokasi='<?php echo $kode_lokasi; ?>';
        var nik='<?php echo $nik; ?>';
        formData.append('kode_lokasi', kode_lokasi);
        formData.append('nik', nik);
        resetRptPage();
        drawLapReg(formData);
        // $('#sai-rpt-filter-box').boxWidget('toggle');\
    });

    $('#ajax-content-section').on('click', '.sai-rpt-data-list-search', function(){
        var par = $(this).closest('.sai-rpt-filter-entry-row').find('.sai-rpt-filter-entry-row-par').text();
        target = $(this).closest('.row').find('input').attr('id');

        var kode_lokasi='<?php echo $kode_lokasi; ?>';
        var nik='<?php echo $nik; ?>';
       
        var post_data = {'username':nik, 'kode_lokasi':kode_lokasi}
        var modul = '';
        var header = [];

        switch(par){
            case 'paket': 
                header = ['No Paket','Nama']
            break;
            case 'jadwal': 
                header = ['No Jadwal','Tgl Berangkat']
            break;
            case 'no_reg': 
                header = ['No Reg','Keterangan']
            break;
            case 'no_peserta': 
                header = ['No Peserta','Nama']
            break;
        }

        var header_html = '';
        for(i=0; i<header.length; i++){
            header_html +=  "<th>"+header[i]+"</th>";
        }
        header_html +=  "<th></th>";

        var table = "<table class='table table-bordered table-striped' id='sai-rpt-data-list-modal-body-jsonTable' style='width:100%'><thead><tr>"+header_html+"</tr></thead>";
        table += "<tbody></tbody></table>";

        post_data.parameter = par;
        post_data.periode=$('.periode_from').val();

        $('#sai-rpt-data-list-modal-body').html(table);

        var datatable = $("#sai-rpt-data-list-modal-body-jsonTable").DataTable({
            // fixedHeader: true,
            "scrollY": "300px",
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": '<?=$root_ser?>/Laporan.php?fx=getDataList',
                "data": post_data,
                "type": "POST",
                "dataSrc" : function(json) {
                    return json.data_list;
                }
            },
            "columnDefs": [{"targets": -1, "data": null, "defaultContent": "<a class='sai-btn-circle sai-rpt-data-list-modal-body-jsonTable-action'><i class='fa fa-check'></i></a>", "orderable": false}],
            "iDisplayLength": 25,
        });

        $('#sai-rpt-data-list-modal').modal('show');
        
        datatable.columns.adjust().draw();
    });
</script>
   