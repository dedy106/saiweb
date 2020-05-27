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
    </style>
    <div class="row" style="">
        <div style="z-index: 1;position: fixed;right: auto;left: auto;margin-right: 15px;margin-left: 25px;" class="col-sm-12" id="subFixbar">
            <div class="card " id="sai-rpt-filter-box;" style="padding:10px;">
                <div class="card-body" style="padding: 0px;">
                    <h4 class="card-title">Laporan Barang</h4>
                    <form id="formFilter">
                        <div class="row" style="margin-left: -5px;">
                            <div class="col-sm-3">
                                <div class="form-group" style='margin-bottom:0'>
                                    <label for="kode_barang-selectized">Kode Barang</label>
                                    <select name="kode_barang" id="kode_barang" class="form-control">
                                    <option value="">Pilih Barang</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <button type="submit" class="btn btn-primary" style="margin-left: 6px;margin-top: 28px;" id="btnPreview"><i class="fa fa-search"></i></button>
                                <button type="button" id='btn-lanjut' class="btn btn-secondary" style="margin-left: 6px;margin-top: 28px;"><i class="fa fa-filter"></i> Filter</button>
                                
                            </div>
                            <div class='col-sm-4'>
                                <div id="pager" style='padding-top: 30px;'>
                                    <ul id="pagination" class="pagination pagination-sm"></ul>
                                </div>
                            </div>
                            <div class='col-sm-1' style='padding-top: 28px;'>
                                <select name="show" id="show" class="form-control selectize" style=''>
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                    <option value="All">All</option>
                                </select>
                            </div>
                            <div class='col-sm-2'>
                                <button type="button" class="btn btn-info float-right" style="margin-left: 6px;margin-top: 28px;" id="sai-rpt-print"><i class="fa fa-print"></i></button>
                                <button type="button" class="btn btn-success float-right" style="margin-left: 6px;margin-top: 28px;" id="btnExport"><i class="fa fa-file-excel"></i></button>
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
    <div class="container-fluid" style="margin-top:140px">
        <div class="row" >
            <div class="col-sm-12">
                <div class="card " id="sai-rpt-filter-box;">
                    <div class="card-body" id="content-lap">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
<script src="<?=$folderroot_js?>/inputmask.js"></script>
<script src="<?=$folderroot_js?>/sai.js"></script>

<script type="text/javascript">
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
        xurl ="<?=$root?>/app/kasir/rptBarang.php";
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
        xurl ="<?=$root?>/app/kasir/rptBarang.php";
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

    
</script>
   