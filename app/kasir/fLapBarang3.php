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
    <style>
        .sidepanel  {
            width: 0px;
            position: fixed;
            z-index: 1;
            height: 500px;
            top: 10px !important;
            right: 0;
            background-color:white;
            overflow-x: hidden;
            transition: 0.5s;
            padding: 10px;
            margin-top: 60px;
            border:1px solid #e9e9e9;
        }
        
        .close{
            width:0px;
            right: -30px;
        }
        .open{
            width:300px;
        }
        #subFixbar{
            width: calc(100% - 250px);
        }
        .mini-sidebar #subFixbar{
            width: calc(100% - 100px);
        }

        .pagination-sm2 .page-link {
            padding: 0.28rem 0.6rem;
            font-size: 1rem;
            line-height: 1.25;
        }
        
        .pagination-sm2 .page-item:first-child .page-link {
            border-top-left-radius: 0.22rem;
            border-bottom-left-radius: 0.22rem;
        }
        
        .pagination-sm2 .page-item:last-child .page-link {
            border-top-right-radius: 0.22rem;
            border-bottom-right-radius: 0.22rem;
        }
    </style>
    <div class="row" style="">
        <div style="z-index: 1;position: fixed;right: auto;left: auto;margin-right: 15px;margin-left: 25px;margin-top:15px" class="col-sm-12" id="subFixbar">
            <div class="card " id="sai-rpt-filter-box;" style="padding:10px;">
                <div class="card-body" style="padding: 0px;">
                    <h4 class="card-title pl-1"><i class='fas fa-file'></i> Laporan Barang</h4>
                    <hr>
                    <form id="formFilter">
                        <div class="row" style="margin-left: -5px;">
                            <div class="col-sm-3">
                                <div class="form-group" style='margin-bottom:0'>
                                    <select name="kode_barang" id="kode_barang" class="form-control">
                                    <option value="">Pilih Barang</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <button type="submit" class="btn btn-primary" style="margin-left: 6px;margin-top: 0" id="btnPreview"><i class="far fa-list-alt"></i> Preview</button>
                                <button type="button" id='btn-lanjut' class="btn btn-secondary" style="margin-left: 6px;margin-top: 0"><i class="fa fa-filter"></i> Filter</button>
<!--                                 
                            </div>
                            <div class='col-sm-3'> -->
                                <div id="pager" style='padding-top: 0px;position: absolute;top: 0;right: 0;'>
                                    <ul id="pagination" class="pagination pagination-sm2"></ul>
                                </div>
                            </div>
                            <div class='col-sm-1' style='padding-top: 0'>
                                <select name="show" id="show" class="form-control" style=''>
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                    <option value="All">All</option>
                                </select>
                            </div>
                            <div class='col-sm-2'>
                                <button type="button" class="btn btn-info float-right" style="margin-left: 6px;margin-top: 0" id="sai-rpt-print"><i class="fa fa-print"></i></button>
                                <button type="button" class="btn btn-success float-right" style="margin-left: 6px;margin-top: 0" id="btnExport"><i class="fa fa-file-excel"></i></button>
                                <button type="button" class="btn btn-primary float-right" style="margin-left: 6px;margin-top: 0" id="btnEmail" alt="Email"><i class="fa far fa-envelope"></i></button>
                                
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div id='mySidepanel' class='sidepanel close'>
        <h3 style='margin-bottom:20px;position: absolute;'>Filter Laporan</h3>
        <a href='#' id='btn-close'><i class="float-right ti-close" style="margin-top: 10px;margin-right: 10px;"></i></a>
        <form id="formFilter2" style='margin-top:50px'>
        <div class="row" style="margin-left: -5px;">
            <div class="col-sm-12">
                <div class="form-group" style='margin-bottom:0'>
                    <label for="kode_barang">Kode Barang</label>
                    <select name="kode_barang" id="kode_barang2" class="form-control">
                    <option value="">Pilih Kode Barang</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <button type="submit" class="btn btn-primary" style="margin-left: 6px;margin-top: 28px;"><i class="fa fa-search" id="btnPreview2"></i> Preview</button>
            </div>
        </div>
        </form>
    </div>
    <div class="container-fluid" style="margin-top:135px">
        <div class="row" >
            <div class="col-sm-12">
                <div class="card " id="sai-rpt-filter-box;">
                    <div class='card-body' style='padding: 10px;'>
                        <div id="loading-bar" style="display:none"><button type="button" disabled="" class="btn btn-info">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Loading...
                        </button></div>
                    </div>
                    <div class="card-body table-responsive" id="content-lap" style='height:380px'>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="modalEmail" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id='formEmail'>
                    <div class='modal-header'>
                        <h5 class='modal-title'>Kirim Email</h5>
                        <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                        </button>
                    </div>
                    <div class='modal-body'>
                        <div class='form-group row'>
                            <label for="modal-email" class="col-3 col-form-label">Email</label>
                            <div class="col-9">
                                <input type='text' class='form-control' maxlength='100' name='email' id='modal-email' required>
                            </div>
                        </div>
                        <div class='form-group row'>
                            <label for="modal-nama" class="col-3 col-form-label">Nama</label>
                            <div class="col-9">
                                <input type='text' class='form-control' maxlength='100' name='nama' id='modal-nama' required >
                            </div>
                        </div>
                    </div>
                    <div class='modal-footer'>
                        <button type="button" disabled="" style="display:none" id='loading-bar2' class="btn btn-info">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Loading...
                        </button>
                        <button type='submit' id='email-submit' class='btn btn-primary'>Kirim</button> 
                        <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
                    </div>
                </div>
            </div>
        <!-- /.modal-content -->
        </div>
    <!-- /.modal-dialog -->
    </div>
<script src="<?=$folderroot_js?>/inputmask.js"></script>
<script src="<?=$folderroot_js?>/sai.js"></script>

<script type="text/javascript">
    var $loadBar = $('#loading-bar');
    var $loadBar2 = $('#loading-bar2');
    function openNav() {
        var element = $('#mySidepanel');
        
        var x = $('#mySidepanel').attr('class');
        var y = x.split(' ');
        if(y[1] == 'close'){
            element.removeClass('close');
            element.addClass('open');
        }else{
            element.removeClass('open');
            element.addClass('close');
        }
    }

    $('.card-body').on('click', '#btn-lanjut', function(e){
        e.preventDefault();
        openNav();
    });

    $('.sidepanel').on('click', '#btn-close', function(e){
        e.preventDefault();
        openNav();
    });  
    
    $('#show').selectize();

    function getKodeBarang(){
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/LapBarang.php?fx=getKodeBarang',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?=$kode_lokasi?>'},
            success:function(result){    
                if(result.status){
                    var select = $('#kode_barang').selectize();
                    select = select[0];
                    var control = select.selectize;
                    control.clearOptions();

                    var select2 = $('#kode_barang2').selectize();
                    select2 = select2[0];
                    var control2 = select2.selectize;
                    control2.clearOptions();
                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                        for(i=0;i<result.daftar.length;i++){
                            control.addOption([{text:result.daftar[i].kode_barang+'-'+result.daftar[i].nama, value:result.daftar[i].kode_barang}]);

                            control2.addOption([{text:result.daftar[i].kode_barang+'-'+result.daftar[i].nama, value:result.daftar[i].kode_barang}]);
                        }
                    }
                }
            }
        });
    }

    getKodeBarang();
    
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

    var $formData = "";
    $('.card-body').on('submit', '#formFilter', function(e){
        e.preventDefault();
        $formData = new FormData(this);
        // formData.append('api_key', localStorage.api_key);
        var kode_lokasi='<?php echo $kode_lokasi; ?>';
        var nik='<?php echo $nik; ?>';
        $formData.append('kode_lokasi', kode_lokasi);
        $formData.append('nik', nik);
        xurl ="<?=$root?>/app/kasir/rptBarang3.php";
        $('#content-lap').load(xurl);
        // drawLapReg(formData);
    });

    $('.sidepanel').on('submit', '#formFilter2', function(e){
        e.preventDefault();
        $formData = new FormData(this);
        // formData.append('api_key', localStorage.api_key);
        var kode_lokasi='<?php echo $kode_lokasi; ?>';
        var nik='<?php echo $nik; ?>';
        $formData.append('kode_lokasi', kode_lokasi);
        $formData.append('nik', nik);
        xurl ="<?=$root?>/app/kasir/rptBarang3.php";
        $('#content-lap').load(xurl);
        // drawLapReg(formData);
    });

    $('#sai-rpt-print').click(function(){
        $('#canvasPreview').printThis();
    });

    $("#btnExport").click(function(e) {
        e.preventDefault();
        $('#canvasPreview').tblToExcel();
    });

    $("#btnEmail").click(function(e) {
        e.preventDefault();
        $('#formEmail')[0].reset();
        $('#modalEmail').modal('show');
        // var html= $('#canvasPreview').html();
        // $loadBar.show();
        // $.ajax({
        //     type: 'POST',
        //     url: '<?=$root_ser?>/LapBarang.php?fx=sendEmail',
        //     dataType: 'json',
        //     async:false,
        //     data: {'kode_lokasi':'<?=$kode_lokasi?>','html':html},
        //     success:function(result){
        //         alert(result.message);
        //         $loadBar.hide();
        //     }
        // });
    });

    $('#modalEmail').on('submit','#formEmail',function(e){
        e.preventDefault();
        var formData = new FormData(this);
        for(var pair of formData.entries()) {
            console.log(pair[0]+ ', '+ pair[1]); 
        }
        
        var nik='<?php echo $nik; ?>' ;
        var kode_lokasi='<?php echo $kode_lokasi; ?>' ;
        var html= $('#canvasPreview').html();

        formData.append('nik_user', nik);
        formData.append('kode_lokasi', kode_lokasi);
        formData.append('html', html);
        $loadBar2.show();
        $.ajax({
            type: 'POST',
            url: '<?=$root_ser?>/LapBarang.php?fx=sendEmail',
            dataType: 'json',
            data: formData,
            async:false,
            contentType: false,
            cache: false,
            processData: false, 
            success:function(result){
                alert(result.message);
                if(result.status){
                    $('#modalEmail').modal('hide');
                }
                $loadBar2.hide();
            },
            fail: function(xhr, textStatus, errorThrown){
                alert('request failed:'+textStatus);
            }
        });
        
    });

    
</script>
   