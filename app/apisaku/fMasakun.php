<?php
    session_start();
    $root_lib=$_SERVER["DOCUMENT_ROOT"];
    if (substr($root_lib,-1)!="/") {
        $root_lib=$root_lib."/";
    }
    include_once($root_lib.'app/apisaku/setting.php');
   $kode_lokasi=$_SESSION['lokasi'];
   $nik=$_SESSION['userLog'];
?>
<style>
.form-group{
    margin-bottom:15px !important;
}
.page-wrapper{
    min-height:600px !important;
}
</style>

<link href="<?=$folder_css?>/custom.css" rel="stylesheet">
    <div class="container-fluid mt-3">
        <div class="row" id="saku-data">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4"><i class='fas fa-cube'></i> Data Master Akun 
                        <button type="button" id="btn-tambah" class="btn btn-info ml-2" style="float:right;"><i class="fa fa-plus-circle"></i> Tambah</button>
                        </h4>
                        <!-- <h6 class="card-subtitle">Tabel Pengajuan</h6> -->
                        <hr>
                        <div class="table-responsive ">
                            <table id="table-data" class="table table-bordered table-striped" width="100%">
                                <thead>
                                    <tr>
                                        <th width="10%">Kode</th>
                                        <th width="50%">Nama</th>
                                        <th width="5%">Curr</th>
                                        <th width="10%">Modul</th>
                                        <th width="10%">Jenis</th>
                                        <th width="15%">Action</th>
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
        <div class="row" id="saku-form" style="display:none;">
            <div class="col-sm-12" style="height: 90px;">
                <div class="card">
                    <div class="card-body pb-0">
                        <h4 class="card-title mb-4"><i class='fas fa-cube'></i> Data Masakun
                        <button type="button" class="btn btn-success ml-2"  style="float:right;" id="btn-save"><i class="fa fa-save"></i> Simpan</button>
                        <button type="button" class="btn btn-secondary ml-2" id="btn-kembali" style="float:right;"><i class="fa fa-undo"></i> Kembali</button>
                        </h4>
                        <hr>
                    </div>
                    <div class="card-body table-responsive pt-0" style='height:450px'>
                        <form class="form mb-5" id="form-tambah" >
                            <div class="form-group row" id="row-id">
                                <div class="col-9">
                                    <input class="form-control" type="text" id="id" name="id" readonly hidden>
                                </div>
                            </div>
                            <div class="form-group row mt-3">   
                                <label for="kode_akun" class="col-3 col-form-label">Kode Akun</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" placeholder="Kode Akun" id="kode_akun" name="kode_akun">
                                </div>
                            </div>
                            <div class="form-group row">   
                                <label for="nama" class="col-3 col-form-label">Nama Akun</label>
                                <div class="col-9">
                                    <input class="form-control" type="text" placeholder="Kode Akun" id="nama" name="nama">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="curr" class="col-3 col-form-label">Currency</label>
                                <div class="col-3">
                                    <select class='form-control' id="curr" name="curr" required>
                                    <option value=''>--- Pilih Curr ---</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="modul" class="col-3 col-form-label">Modul</label>
                                <div class="col-3">
                                    <select class='form-control' id="modul" name="modul" required>
                                    <option value=''>--- Pilih Modul ---</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="jenis" class="col-3 col-form-label">Jenis</label>
                                <div class="col-3">
                                    <select class='form-control selectize' id="jenis" name="jenis" required>
                                    <option value=''>--- Pilih Jenis ---</option>
                                    <option value='Neraca'>Neraca</option>
                                    <option value='Pendapatan'>Pendapatan</option>
                                    <option value='Beban'>Beban</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="block" class="col-3 col-form-label">Status Aktifasi</label>
                                <div class="col-3">
                                    <select class='form-control selectize' id="block" name="block" required>
                                    <option value=''>--- Pilih Status ---</option>
                                    <option value='0'>AKTIF</option>
                                    <option value='1'>BLOCK</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="gar" class="col-3 col-form-label">Status Budget</label>
                                <div class="col-3">
                                    <select class='form-control selectize' id="gar" name="gar" required>
                                    <option value=''>--- Pilih Status ---</option>
                                    <option value='0'>0 - NON</option>
                                    <option value='1'>1 - BUDGET</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="normal" class="col-3 col-form-label">Normal Account</label>
                                <div class="col-3">
                                    <select class='form-control selectize' id="normal" name="normal" required>
                                    <option value=''>--- Pilih Normal Account ---</option>
                                    <option value='C'>C - Kredit</option>
                                    <option value='D'>D - Debet</option>
                                    </select>
                                </div>
                            </div>
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#flag" role="tab" aria-selected="true"><span class="hidden-xs-down">Flag Akun</span></a> </li>
                                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#keu" role="tab" aria-selected="false"><span class="hidden-xs-down">Lap Keuangan</span></a> </li>
                                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#agg" role="tab" aria-selected="false"><span class="hidden-xs-down">Lap Anggaran</span></a> </li>
                            </ul>
                            <div class="tab-content tabcontent-border">
                                <div class="tab-pane active" id="flag" role="tabpanel">
                                    <div class='col-xs-12 mt-2' style='overflow-y: scroll; height:300px; margin:0px; padding:0px;'>
                                        <style>
                                            th,td{
                                                padding:8px !important;
                                                vertical-align:middle !important;
                                            }
                                        </style>
                                        <table class="table table-striped table-bordered table-condensed" id="input-flag">
                                        <thead>
                                            <tr>
                                                <th width="5%">No</th>
                                                <th width="80%">Kode Flag</th>
                                                <th width="15%"><button type="button" href="#" id="add-row-flag" class="btn btn-default"><i class="fa fa-plus-circle"></i></button></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane" id="keu" role="tabpanel">
                                    <div class='col-xs-12 mt-2' style='overflow-y: scroll; height:300px; margin:0px; padding:0px;'>
                                        <style>
                                            th,td{
                                                padding:8px !important;
                                                vertical-align:middle !important;
                                            }
                                        </style>
                                        <table class="table table-striped table-bordered table-condensed" id="input-keu">
                                        <thead>
                                            <tr>
                                                <th width="5%">No</th>
                                                <th width="40%">Kode FS</th>
                                                <th width="40%">Kode Lap</th>
                                                <th width="15%"><button type="button" href="#" id="add-row-keu" class="btn btn-default"><i class="fa fa-plus-circle"></i></button></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane" id="agg" role="tabpanel">
                                    <div class='col-xs-12 mt-2' style='overflow-y: scroll; height:300px; margin:0px; padding:0px;'>
                                        <style>
                                            th,td{
                                                padding:8px !important;
                                                vertical-align:middle !important;
                                            }
                                        </style>
                                        <table class="table table-striped table-bordered table-condensed" id="input-agg">
                                        <thead>
                                            <tr>
                                                <th width="5%">No</th>
                                                <th width="40%">Kode FS</th>
                                                <th width="40%">Kode Lap</th>
                                                <th width="15%"><button type="button" href="#" id="add-row-agg" class="btn btn-default"><i class="fa fa-plus-circle"></i></button></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" id="slide-history" style="display:none;">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="btn btn-secondary ml-2" id="btn-kembali" style="float:right;"><i class="fa fa-undo"></i> Kembali</button>
                        <div class="profiletimeline mt-5">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" id="slide-print" style="display:none;">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="btn btn-secondary ml-2" id="btn-kembali" style="float:right;"><i class="fa fa-undo"></i> Kembali</button>
                        <button type="button" class="btn btn-info ml-2" id="btn-print" style="float:right;"><i class="fa fa-print"></i> Print</button>
                        <div id="print-area" class="mt-5" width='100%' style='border:none;min-height:480px'>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>     
    <script>
    
    function getCurr(){
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Masakun.php?fx=getCurr',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?=$kode_lokasi?>'},
            success:function(result){    
                if(result.status){
                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                        var select = $('#curr').selectize();
                        select = select[0];
                        var control = select.selectize;
                        for(i=0;i<result.daftar.length;i++){
                            control.addOption([{text:result.daftar[i].kode_curr, value:result.daftar[i].kode_curr}]);
                        }
                    }
                }
            }
        });
    }

    function getModul(){
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Masakun.php?fx=getModul',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?=$kode_lokasi?>'},
            success:function(result){    
                if(result.status){
                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                        var select = $('#modul').selectize();
                        select = select[0];
                        var control = select.selectize;
                        for(i=0;i<result.daftar.length;i++){
                            control.addOption([{text:result.daftar[i].kode_tipe +' - '+ result.daftar[i].nama_tipe, value:result.daftar[i].kode_tipe}]);
                        }
                    }
                }
            }
        });
    }

    getCurr();
    getModul();

    var $iconLoad = $('.preloader');
    var action_html = "<a href='#' title='Edit' class='badge badge-warning' id='btn-edit'><i class='fas fa-pencil-alt'></i></a> &nbsp; <a href='#' title='Hapus' class='badge badge-danger' id='btn-delete'><i class='fa fa-trash'></i></a>";
    var kode_lokasi = '<?php echo $kode_lokasi ?>';

    var dataTable = $('#table-data').DataTable({
        // 'processing': true,
        // 'serverSide': true,
        'ajax': {
            'url': '<?=$root_ser?>/Masakun.php?fx=getView',
            'data': {'kode_lokasi':kode_lokasi},
            'async':false,
            'type': 'GET',
            'dataSrc' : function(json) {
                return json.data;   
            }
        },
        'columnDefs': [
            {'targets': 5, data: null, 'defaultContent': action_html },
            // {   'targets': 6, 
            //     'className': 'text-right',
            //     'render': $.fn.dataTable.render.number( '.', ',', 0, '' ) 
            // }
        ],
        'columns': [
            { data: 'kode_akun' },
            { data: 'nama' },
            { data: 'kode_curr' },
            { data: 'modul' },
            { data: 'jenis' }
        ],
    });
    
    function getFlag(param){
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Masakun.php?fx=getFlag',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?=$kode_lokasi?>'},
            success:function(result){    
                if(result.status){
                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                        var select = $('.'+param).selectize();
                        select = select[0];
                        var control = select.selectize;
                        for(i=0;i<result.daftar.length;i++){
                            control.addOption([{text:result.daftar[i].kode_flag + ' - ' + result.daftar[i].nama, value:result.daftar[i].kode_flag}]);
                        }
                    }
                }
            }
        });
    }

    function getNeraca(id,param){
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Masakun.php?fx=getNeraca',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?=$kode_lokasi?>','kode_fs':id},
            success:function(result){    
                if(result.status){
                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                        var select = $('.'+param).selectize();
                        select = select[0];
                        var control = select.selectize;
                        for(i=0;i<result.daftar.length;i++){
                            control.addOption([{text:result.daftar[i].kode_neraca + ' - ' + result.daftar[i].nama, value:result.daftar[i].kode_neraca}]);
                        }
                    }
                }
            }
        });
    }

    function getNeracaGar(id,param){
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Masakun.php?fx=getNeracaGar',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?=$kode_lokasi?>','kode_fs':id},
            success:function(result){    
                if(result.status){
                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                        var select = $('.'+param).selectize();
                        select = select[0];
                        var control = select.selectize;
                        for(i=0;i<result.daftar.length;i++){
                            control.addOption([{text:result.daftar[i].kode_neraca + ' - ' + result.daftar[i].nama, value:result.daftar[i].kode_neraca}]);
                        }
                    }
                }
            }
        });
    }

    function getFS(param,param2){
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Masakun.php?fx=getFS',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?=$kode_lokasi?>'},
            success:function(result){    
                if(result.status){
                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                        var select = $('.'+param).selectize({
                            selectOnTab: true,
                            onChange: function (val){
                                var id = val;
                                getNeraca(id,param2);
                            }
                        });

                        select = select[0];
                        var control = select.selectize;
                        for(i=0;i<result.daftar.length;i++){
                            control.addOption([{text:result.daftar[i].kode_fs + ' - ' + result.daftar[i].nama, value:result.daftar[i].kode_fs}]);
                        }

                    }
                }
            }
        });
    }

    function getFSGar(param,param2){
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Masakun.php?fx=getFSGar',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?=$kode_lokasi?>'},
            success:function(result){    
                if(result.status){
                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                        var select = $('.'+param).selectize({
                            selectOnTab: true,
                            onChange: function (val){
                                var id = val;
                                getNeracaGar(id,param2);
                            }
                        });

                        select = select[0];
                        var control = select.selectize;
                        for(i=0;i<result.daftar.length;i++){
                            control.addOption([{text:result.daftar[i].kode_fs + ' - ' + result.daftar[i].nama, value:result.daftar[i].kode_fs}]);
                        }

                    }
                }
            }
        });
    }
    
    $('.selectize').selectize();

    $('#btn-save').click(function(){
        $('#form-tambah').submit();
    });

    $('#saku-data').on('click', '#btn-tambah', function(){
        $('#row-id').hide();
        $('#id').val('');
        $('#saku-data').hide();
        $('#saku-form').show();
        $('#form-tambah')[0].reset();
    });

    $('#form-tambah').on('click', '#add-row-flag', function(){
        var no=$('#input-flag .row-flag:last').index();
        no=no+2;
        var input = "";
        input += "<tr class='row-flag'>";
        input += "<td width='5%' class='no-flag'>"+no+"</td>";
        input += "<td width='60%'><select name='kode_flag[]' class='form-control inp-flag flagke"+no+"' value='' required></select></td>";
        input += "<td width='5%'><a class='btn btn-danger btn-sm hapus-item' style='font-size:8px'><i class='fa fa-times fa-1'></i></td>";
        input += "</tr>";
        $('#input-flag tbody').append(input);
        getFlag('flagke'+no);
        $('#input-flag tbody tr:last').find('.inp-flag')[0].selectize.focus();
    });

    $('#form-tambah').on('click', '#add-row-keu', function(){
        var no=$('#input-keu .row-keu:last').index();
        no=no+2;
        var input = "";
        input += "<tr class='row-keu'>";
        input += "<td width='5%' class='no-keu'>"+no+"</td>";
        input += "<td width='40%'><select name='kode_fs[]' class='form-control inp-fs fske"+no+"' value='' required></select></td>";
        input += "<td width='40%'><select name='kode_neraca[]' class='form-control inp-nrc nrcke"+no+"' value='' required></select></td>";
        input += "<td width='5%'><a class='btn btn-danger btn-sm hapus-item' style='font-size:8px'><i class='fa fa-times fa-1'></i></td>";
        input += "</tr>";
        $('#input-keu tbody').append(input);
        getFS('fske'+no,'nrcke'+no);
        $('#input-keu tbody tr:last').find('.inp-fs')[0].selectize.focus();
    });

    $('#form-tambah').on('click', '#add-row-agg', function(){
        var no=$('#input-agg .row-agg:last').index();
        no=no+2;
        var input = "";
        input += "<tr class='row-agg'>";
        input += "<td width='5%' class='no-agg'>"+no+"</td>";
        input += "<td width='40%'><select name='kode_fsgar[]' class='form-control inp-fsgar fsgarke"+no+"' value='' required></select></td>";
        input += "<td width='40%'><select name='kode_neracagar[]' class='form-control inp-nrcgar nrcgarke"+no+"' value='' required></select></td>";
        input += "<td width='5%'><a class='btn btn-danger btn-sm hapus-item' style='font-size:8px'><i class='fa fa-times fa-1'></i></td>";
        input += "</tr>";
        $('#input-agg tbody').append(input);
        getFSGar('fsgarke'+no,'nrcgarke'+no);
        $('#input-agg tbody tr:last').find('.inp-fsgar')[0].selectize.focus();
    });

    $('#saku-data').on('click', '#btn-edit', function(){
        var id= $(this).closest('tr').find('td').eq(0).html();
       
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Masakun.php?fx=getEdit',
            dataType: 'json',
            async:false,
            data: {'kode_akun':id,'kode_lokasi':kode_lokasi},
            success:function(result){
                if(result.status){
                    $('#id').val('edit');
                    $('#kode_akun').val(id);
                    $('#nama').val(result.daftar[0].nama);
                    $('#curr')[0].selectize.setValue(result.daftar[0].curr);
                    $('#modul')[0].selectize.setValue(result.daftar[0].modul);
                    $('#jenis')[0].selectize.setValue(result.daftar[0].jenis);
                    $('#block')[0].selectize.setValue(result.daftar[0].block);
                    $('#gar')[0].selectize.setValue(result.daftar[0].status_gar);
                    $('#normal')[0].selectize.setValue(result.daftar[0].normal);
                    var input="";
                    var no=1;
                    if(result.daftar2.length > 0){

                        for(var x=0;x<result.daftar2.length;x++){
                            var line = result.daftar2[x];
                            input += "<tr class='row-flag'>";
                            input += "<td width='5%' class='no-flag'>"+no+"</td>";
                            input += "<td width='60%'><select name='kode_flag[]' class='form-control inp-flag flagke"+no+"' value='' required></select></td>";
                            input += "<td width='5%'><a class='btn btn-danger btn-sm hapus-item' style='font-size:8px'><i class='fa fa-times fa-1'></i></td>";
                            input += "</tr>";
                            no++;
                        }
                    }

                    var input2 = "";
                    var no=1;
                    if(result.daftar3.length > 0){

                        for(var i=0;i< result.daftar3.length;i++){
                            var line2 = result.daftar3[i];
                            input2 += "<tr class='row-keu'>";
                            input2 += "<td width='5%' class='no-keu'>"+no+"</td>";
                            input2 += "<td width='40%'><select name='kode_fs[]' class='form-control inp-fs fske"+no+"' value='' required></select></td>";
                            input2 += "<td width='40%'><select name='kode_neraca[]' class='form-control inp-nrc nrcke"+no+"' value='' required></select></td>";
                            input2 += "<td width='5%'><a class='btn btn-danger btn-sm hapus-item' style='font-size:8px'><i class='fa fa-times fa-1'></i></td>";
                            input2 += "</tr>";
                            no++;
                        }
                    }

                    var input3 = "";
                    var no=1;
                    if(result.daftar4.length > 0){

                        for(var i=0;i< result.daftar4.length;i++){
                            var line3 = result.daftar4[i];
                            input3 += "<tr class='row-agg'>";
                            input3 += "<td width='5%' class='no-agg'>"+no+"</td>";
                            input3 += "<td width='40%'><select name='kode_fsgar[]' class='form-control inp-fsgar fsgarke"+no+"' value='' required></select></td>";
                            input3 += "<td width='40%'><select name='kode_neracagar[]' class='form-control inp-nrcgar nrcgarke"+no+"' value='' required></select></td>";
                            input3 += "<td width='5%'><a class='btn btn-danger btn-sm hapus-item' style='font-size:8px'><i class='fa fa-times fa-1'></i></td>";
                            input3 += "</tr>";
                            no++;
                        }
                    }

                    $('#input-flag tbody').html(input);
                    $('#input-keu tbody').html(input2);
                    $('#input-agg tbody').html(input3);

                    var input="";
                    var no=1;
                    if(result.daftar2.length > 0){

                        for(var x=0;x<result.daftar2.length;x++){
                            var line = result.daftar2[x];
                            
                            getFlag('flagke'+no);
                            $('.flagke'+no)[0].selectize.setValue(line.kode_flag);
                            no++;
                        }
                    }

                    var input2 = "";
                    var no=1;
                    if(result.daftar3.length > 0){

                        for(var i=0;i< result.daftar3.length;i++){
                            var line2 = result.daftar3[i];
                               
                            getFS('fske'+no,'nrcke'+no);
                            $('.fske'+no)[0].selectize.setValue(line2.kode_fs);
                            $('.nrcke'+no)[0].selectize.setValue(line2.kode_neraca);
                            no++;
                        }
                    }

                    var input3 = "";
                    var no=1;
                    if(result.daftar4.length > 0){

                        for(var i=0;i< result.daftar4.length;i++){
                            var line3 = result.daftar4[i];
                            getFSGar('fsgarke'+no,'nrcgarke'+no);
                            $('.fsgarke'+no)[0].selectize.setValue(line3.kode_fs);
                            $('.nrcgarke'+no)[0].selectize.setValue(line3.kode_neraca);
                            no++;
                        }
                    }

                    $('#saku-data').hide();
                    $('#saku-form').show();
                }
            }
        });
    });


    $('#saku-form').on('click', '#btn-kembali', function(){
        $('#saku-data').show();
        $('#saku-form').hide();
    });

    $('#saku-form').on('submit', '#form-tambah', function(e){
    e.preventDefault();
        var parameter = $('#id').val();
        var total = $('#total').val();
        if(total == 0){
            alert('Total pengajuan tidak boleh 0');
        }else{
            // tambah
            $iconLoad.show();
            console.log('parameter:tambah');
            var formData = new FormData(this);
            for(var pair of formData.entries()) {
                    console.log(pair[0]+ ', '+ pair[1]); 
                }

            var nik='<?php echo $nik; ?>' ;
            var kode_lokasi='<?php echo $kode_lokasi; ?>' ;

            formData.append('nik_user', nik);
            formData.append('kode_lokasi', kode_lokasi);

            $.ajax({
                type: 'POST',
                url: '<?=$root_ser?>/Masakun.php?fx=simpan',
                dataType: 'json',
                data: formData,
                async:false,
                contentType: false,
                cache: false,
                processData: false, 
                success:function(result){
                    if(result.status){
                        dataTable.ajax.reload();
                        Swal.fire(
                            'Great Job!',
                            'Your data has been saved.'+result.message,
                            'success'
                        )
                        $('#saku-data').show();
                        $('#saku-form').hide();
                        $iconLoad.hide();
                        
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong!',
                            footer: '<a href>'+result.message+'</a>'
                        })
                    }
                },
                fail: function(xhr, textStatus, errorThrown){
                    alert('request failed:'+textStatus);
                }
            });   
        }     
    });

    $('#saku-data').on('click','#btn-delete',function(e){
        Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.value) {
                var kode = $(this).closest('tr').find('td:eq(0)').html(); 
                var kode_lokasi = '<?php echo $kode_lokasi; ?>';        
                
                $.ajax({
                    type: 'DELETE',
                    url: '<?=$root_ser?>/Masakun.php',
                    dataType: 'json',
                    async:false,
                    data: {'kode_akun':kode,'kode_lokasi':kode_lokasi},
                    success:function(result){
                        if(result.status){
                            dataTable.ajax.reload();
                            Swal.fire(
                                'Deleted!',
                                'Your data has been deleted.',
                                'success'
                            )
                        }else{
                            Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong!',
                            footer: '<a href>'+result.message+'</a>'
                            })
                        }
                    }
                });
                
            }else{
                return false;
            }
        })
    });
    
    $('#input-flag').on('click', '.hapus-item', function(){
        $(this).closest('tr').remove();
        no=1;
        $('.row-flag').each(function(){
            var nom = $(this).closest('tr').find('.no-flag');
            nom.html(no);
            no++;
        });
        $("html, body").animate({ scrollTop: $(document).height() }, 1000);
    });


    $('#input-keu').on('click', '.hapus-item', function(){
        $(this).closest('tr').remove();
        no=1;
        $('.row-keu').each(function(){
            var nom = $(this).closest('tr').find('.no-keu');
            nom.html(no);
            no++;
        });
        $("html, body").animate({ scrollTop: $(document).height() }, 1000);
    });

    $('#input-agg').on('click', '.hapus-item', function(){
        $(this).closest('tr').remove();
        no=1;
        $('.row-agg').each(function(){
            var nom = $(this).closest('tr').find('.no-agg');
            nom.html(no);
            no++;
        });
        $("html, body").animate({ scrollTop: $(document).height() }, 1000);
    });

    $('#tanggal,#no_dokumen,#kode_pp,#waktu,#kegiatan,#dasar').keydown(function(e){
        var code = (e.keyCode ? e.keyCode : e.which);
        var nxt = ['tanggal','no_dokumen','kode_pp','waktu','kegiatan','dasar'];
        if (code == 13 || code == 40) {
            e.preventDefault();
            var idx = nxt.indexOf(e.target.id);
            idx++;
            if(idx == 2){
                $('#'+nxt[idx])[0].selectize.focus();
            }else if(idx == 6){
                $('#add-row').click();
            }
            else{
                $('#'+nxt[idx]).focus();
            }
        }else if(code == 38){
            e.preventDefault();
            var idx = nxt.indexOf(e.target.id);
            idx--;
            if(idx != -1){ 
                $('#'+nxt[idx]).focus();
            }
        }
    });

    </script>

    
