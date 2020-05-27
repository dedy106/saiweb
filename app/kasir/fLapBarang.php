<?php
    session_start();
    $root_lib=$_SERVER["DOCUMENT_ROOT"];
    if (substr($root_lib,-1)!="/") {
        $root_lib=$root_lib."/";
    }
    include_once($root_lib.'app/kasir/setting.php');

 
    $kode_lokasi=$_COOKIE['lokasi'];
    $periode=$_COOKIE['periode'];
    $kode_pp=$_COOKIE['kodePP'];
    $nik=$_COOKIE['userLog'];
    $sql="select kode_gudang from brg_gudang where kode_lokasi='$kode_lokasi' ";
    $rs=execute($sql);
    $gudang=$rs->fields[0];
?>
<div class="container-fluid mt-3">
    <div class="row" >
        <div class="col-sm-12">
            <div class="card " id="sai-rpt-filter-box">
                <div class="card-header with-border">
                    <h4 class="card-title">Laporan Barang</h4>
                    <div class="box-tools float-right">
                        <a class="sai-btn-circle" id="sai-rpt-prev-page" title="Back"><i class="fa fa-arrow-circle-left"></i> 
                        </a>
                        <a class="sai-btn-circle" id="sai-rpt-export-excel" title="Excel"><i class="fa fa-file-excel"></i>
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
                                <form id="web-Lap-form" class="">
                                    <div class="row sai-rpt-filter-entry-row mb-1">
                                        <!-- <div class="form-group"> -->
                                            <div class="col-md-4">
                                                <p class="sai-rpt-filter-entry-row-par" hidden="">kasir</p>
                                                Kasir
                                            </div>
                                            <div class="col-md-2">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <select name="kasir[]" class="form-control sai-rpt-filter-type" required="">
                                                            <option value="all" selected="">All</option><option value="exact">=</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="row sai-rpt-filter-from row_nik" >
                                                    <div class="col-md-10">
                                                        <input type="text" name="kasir[]" class="form-control" id="kasir_from" value="">
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
                                                        <input type="text" name="kasir[]" class="form-control" id="kasir_to" value="">
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
                                                <p class="sai-rpt-filter-entry-row-par" hidden="">kd_barang</p>
                                                Kode Barang
                                            </div>
                                            <div class="col-md-2">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <select name="kode_barang[]" class="form-control sai-rpt-filter-type" required="">
                                                            <option value="all" selected="">All</option><option value="range">Range</option><option value="exact">=</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="row sai-rpt-filter-from row_kodebarang" >
                                                    <div class="col-md-10">
                                                        <input type="text" name="kode_barang[]" class="form-control" id="kode_barang_from" value="">
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
                                                        <input type="text" name="kode_barang[]" class="form-control" id="kode_barang_to" value="">
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
                <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                <span aria-hidden='true'>×</span></button>
                <h4 class='modal-title'>Report Data List</h4>
            </div>
            <div class='modal-body'>
                <div style='overflow-y: auto; max-height: 600px; overflow-x:hidden; padding-right:10px;' id='sai-rpt-data-list-modal-body'>
                </div>
            </div>
        </div>
    </div>
</div>
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
    $('.row_kodebarang').hide();
    $('.row_nik').hide();
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
        $('#sai-rpt-print').click(function(){
            $('#'+table_id).printThis();
        });
        $("#sai-rpt-export-excel").click(function(e) {
            e.preventDefault();
        // exportable(table_id, 'documents', ['xlsx'], {'xlsx':'#sai-rpt-export-excel'});

            //getting data from our table
            // var data_type = 'data:application/vnd.ms-excel';
            // var table_div = document.getElementById(table_id);
            // var table_html = table_div.outerHTML.replace(/ /g, '%20');

            // var a = document.createElement('a');
            // a.href = data_type + ', ' + table_html;
            // a.download = 'exported_table_' + Math.floor((Math.random() * 9999999) + 1000000) + '.xls';
            // a.click();
            $('#'+table_id).tblToExcel();
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

    function drawLap(formData){
       
        saiPost('<?=$root_ser?>/LapBarang.php?fx=getLapBarang', null, formData, null, function(data){
            if(data.result.length > 0){
            //    $('#sai-rpt-filter-box').boxWidget('toggle');

                var mon_html = "<div align='center' id='sai-rpt-table-export-tbl-daftar-pnj'>";
                var arr_tl = [0,0,0,0,0,0,0,0,0];
                var x=1;
               
                    
                    mon_html+=`
                    <table width="750px" cellspacing="2" cellpadding="1" border="0">
                        <tbody>
                            <tr>
                                <td class="style16" align="center">
                                    <b>Toko Asrama Putra TJ<br>Jl.Telekomunikasi No. 1 Trs.Buahbatu <br>Bandung</b>
                                </td>
                            </tr>
                            <tr>
                                <td class="style16" align="center"><b>LAPORAN BARANG</b></td>
                            </tr>
                            <tr>
                                <td class="style16" align="center">Periode `+data.periode+`</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td align="center">
                                    <table width="100%" cellspacing="2" cellpadding="1" border="1">
                                        <tbody>
                                            <tr bgcolor="#CCCCCC">
                                                <td class="header_laporan" width="20" align="center">No</td>
                                                <td class="header_laporan" width="70" align="center">Kode Barang</td>
                                                <td class="header_laporan" width="250" align="center">Nama Barang</td>
                                                <td class="header_laporan" width="60" align="center">Satuan</td>
                                                <td class="header_laporan" width="90" align="center">Harga</td>
                                                <td class="header_laporan" width="90" align="center">Kelompok Barang</td>
                                                <td class="header_laporan" width="90" align="center">Barcode</td>
                                            </tr>`;
                                            
                                            var total=0; 
                                            var det = '';
                                            var no=1;
                                            for (var x=0;x<data.result.length;x++)
                                            {
                                                var line2 = data.result[x];
                                              
                                                det+=`<tr>
                                                <td align='center' class='isi_laporan'>`+no+`</td>
                                                <td  class='isi_laporan'>`+line2.kode_barang+`</td>
                                                <td class='isi_laporan'>`+line2.nama+`</td>
                                                <td class='isi_laporan'>`+line2.satuan+`</td>
                                                <td align='right' class='isi_laporan'>`+sepNum(line2.harga)+`</td>
                                                <td  class='isi_laporan'>`+line2.kode_klp+`</td>
                                                <td  class='isi_laporan'>`+line2.barcode+`</td>
                                                </tr>`;	
                                                no++;
                                            
                                                
                                            }
                                            mon_html+=det+`
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table><br>
                    <div style="page-break-after:always"></div>`;
                        
               mon_html+="</div>"; 
               
               drawRptPage(mon_html, 'sai-rpt-table-export-tbl-daftar-pnj');  
           }
  
       });
   }

    $('#ajax-content-section').on('submit', '#web-Lap-form', function(e){
        e.preventDefault();
        var formData = new FormData(this);
        // formData.append('api_key', localStorage.api_key);
        var kode_lokasi='<?php echo $kode_lokasi; ?>';
        var nik='<?php echo $nik; ?>';
        formData.append('kode_lokasi', kode_lokasi);
        formData.append('nik', nik);
        resetRptPage();
        drawLap(formData);
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
            case 'kode_barang': 
                header = ['Kode Barang','Nama']
            break;
            // case 'kasir': 
            //     header = ['NIK','Nama']
            // break;
        }

        var header_html = '';
        for(i=0; i<header.length; i++){
            header_html +=  "<th>"+header[i]+"</th>";
        }
        header_html +=  "<th></th>";

        var table = "<table class='table table-bordered table-striped' id='sai-rpt-data-list-modal-body-jsonTable'><thead><tr>"+header_html+"</tr></thead>";
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
                "url": '<?=$root_ser?>/LapBarang.php?fx=getDataList',
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
   