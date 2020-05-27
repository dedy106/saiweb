<?php
    session_start();
    $root_lib=$_SERVER["DOCUMENT_ROOT"];
    if (substr($root_lib,-1)!="/") {
        $root_lib=$root_lib."/";
    }
    include_once($root_lib.'app/tarbak/setting.php');
    $kode_lokasi=$_SESSION['lokasi'];
    $nik=$_SESSION['userLog'];
    $kode_pp=$_SESSION['kodePP'];
?>

<link href="<?=$folder_css?>/custom.css" rel="stylesheet">
    <div class="container-fluid mt-3">
        <div class="row" id="saku-datatable">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4"><i class='fas fa-cube'></i> Data KKM 
                        <button type="button" id="btn-tambah" class="btn btn-info ml-2" style="float:right;"><i class="fa fa-plus-circle"></i> Tambah</button>
                        </h4>
                        <hr>
                        <div class="table-responsive ">
                            <style>
                            th,td{
                                padding:8px !important;
                                vertical-align:middle !important;
                            }
                            .form-group{
                                margin-bottom:15px !important;
                            }
                            
                            .dataTables_wrapper{
                                padding:5px
                            }
                            </style>
                            <table id="table-data" class="table table-bordered table-striped" style='width:100%'>
                                <thead>
                                    <tr>
                                        <th>Kode KKM</th>
                                        <th>Kode TA</th>
                                        <th>Kode Tingkat</th>
                                        <th>Kode Jurusan</th>
                                        <th>Kode PP</th>
                                        <th>Action</th>
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
                        <h4 class="card-title mb-4"><i class='fas fa-cube'></i> Form KKM
                        <button type="button" class="btn btn-success ml-2"  style="float:right;" id="btn-save"><i class="fa fa-save"></i> Simpan</button>
                        <button type="button" class="btn btn-secondary ml-2" id="btn-kembali" style="float:right;"><i class="fa fa-undo"></i> Kembali</button>
                        </h4>
                        <hr>
                    </div>
                    <div class="card-body table-responsive pt-0" style='height:460px'>
                        <form class="form" id="form-tambah" style='margin-bottom:10px'>
                            <div class="form-group row" id="row-id">
                                <div class="col-9">
                                    <input class="form-control" type="text" id="id" name="id" readonly hidden>
                                    <input class="form-control" type="text" id="no_bukti" name="no_bukti" readonly hidden>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="kode_pp" class="col-3 col-form-label">Kode PP</label>
                                <div class="col-3">
                                    <select class='form-control' id="kode_pp" name="kode_pp">
                                    <option value='' disabled>--- Pilih PP ---</option>
                                    </select>
                                </div>
                                <label for="kode_ta" class="col-3 col-form-label">Tahun Ajaran</label>
                                <div class="col-3">
                                    <select class='form-control' id="kode_ta" name="kode_ta">
                                    <option value='' disabled>--- Pilih Tahuan Ajaran ---</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="kode_tingkat" class="col-3 col-form-label">Tingkat</label>
                                <div class="col-3">
                                    <select class='form-control' id="kode_tingkat" name="kode_tingkat">
                                    <option value='' disabled>--- Pilih Tingkat ---</option>
                                    </select>
                                </div>
                                <label for="kode_jur" class="col-3 col-form-label">Jurusan</label>
                                <div class="col-3">
                                    <select class='form-control' id="kode_jur" name="kode_jur">
                                    <option value='' disabled>--- Pilih Jurusan ---</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="flag_aktif" class="col-3 col-form-label">Status</label>
                                <div class="col-3">
                                    <select class='form-control selectize' id="flag_aktif" name="flag_aktif">
                                        <option value='' disabled>--- Pilih Status Aktif ---</option>
                                        <option value='1'>Aktif</option>
                                        <option value='0'>Non Aktif</option>
                                    </select>
                                </div>
                            </div>
                            <div class='col-xs-12 mt-2' style='overflow-y: scroll; height:250px; margin:0px; padding:0px;'>
                                <style>
                                th,td{
                                    padding:8px !important;
                                    vertical-align:middle !important;
                                }
                                </style>
                                <table class="table table-striped table-bordered table-condensed" id="input-grid">
                                    <thead>
                                        <tr>
                                            <th width="10%">No</th>
                                            <th width="50%">Mata Pelajaran</th>
                                            <th width="30%">KKM</th>
                                            <th width="10%" class="text-center"><button type="button" style='padding:0' href="#" id="add-row" class="btn btn-default"><i class="fa fa-plus-circle"></i></button></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                            
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div id='mySidepanel' class='sidepanel close'>
            <h3 style='margin-bottom:20px;position: absolute;'>Filter Data</h3>
            <a href='#' id='btnClose'><i class="float-right ti-close" style="margin-top: 10px;margin-right: 10px;"></i></a>
            <form id="formFilter2" style='margin-top:50px'>
            <div class="row" style="margin-left: -5px;">
                <div class="col-sm-12">
                    <div class="form-group" style='margin-bottom:0'>
                        <label for="kode_pp">PP</label>
                        <select name="kode_pp" id="kode_pp2" class="form-control">
                        <option value="">Pilih PP</option>
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
    </div> 

    <script src="<?=$folderroot_js?>/sai.js"></script>
    <script src="<?=$folderroot_js?>/inputmask.js"></script>            
    <script>

    function openFilter() {
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

    $('#kode_pp').selectize({
        selectOnTab:true,
        onChange: function (val){
            var id = val;
            if (id != "" && id != null && id != undefined){
                getTA(id);
                // getTingkat(id);
                getJurusan(id);
            }
        }
    });

   function getPP(){
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Kkm.php?fx=getPP',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?=$kode_lokasi?>'},
            success:function(result){    
                if(result.status){
                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                        var select = $('#kode_pp').selectize();
                        select = select[0];
                        var control = select.selectize;

                        var select2 = $('#kode_pp2').selectize();
                        select2 = select2[0];
                        var control2 = select2.selectize;
                        for(i=0;i<result.daftar.length;i++){
                            control.addOption([{text:result.daftar[i].kode_pp + ' - ' + result.daftar[i].nama, value:result.daftar[i].kode_pp}]);
                            control2.addOption([{text:result.daftar[i].kode_pp + ' - ' + result.daftar[i].nama, value:result.daftar[i].kode_pp + '-' + result.daftar[i].nama}]);
                        }
                    }
                }
            }
        });
    }

    getPP();

    function getTA(id=null){
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Kkm.php?fx=getTA',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?=$kode_lokasi?>','kode_pp':id},
            success:function(result){    
                var select = $('#kode_ta').selectize();
                select = select[0];
                var control = select.selectize;
                var kode = control.getValue();
                control.clearOptions();
                if(result.status){
                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                        for(i=0;i<result.daftar.length;i++){
                            control.addOption([{text:result.daftar[i].kode_ta + ' - ' + result.daftar[i].nama, value:result.daftar[i].kode_ta}]);

                        }
                    }
                }
                if(kode != ""){
                    control.setValue(kode);
                }
            }
        });
    }

    getTA();


    function getTingkat(){
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Kkm.php?fx=getTingkat',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?=$kode_lokasi?>'},
            success:function(result){    
                var select = $('#kode_tingkat').selectize();
                select = select[0];
                var control = select.selectize;
                control.clearOptions();
                if(result.status){
                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                        for(i=0;i<result.daftar.length;i++){
                            control.addOption([{text:result.daftar[i].kode_tingkat + ' - ' + result.daftar[i].nama, value:result.daftar[i].kode_tingkat}]);

                        }
                    }
                }
                
            }
        });
    }

    getTingkat();


    function getJurusan(id=null){
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Kkm.php?fx=getJurusan',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?=$kode_lokasi?>','kode_pp':id},
            success:function(result){    
                var select = $('#kode_jur').selectize();
                select = select[0];
                var control = select.selectize;
                var kode = control.getValue();
                control.clearOptions();
                if(result.status){
                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                        for(i=0;i<result.daftar.length;i++){
                            control.addOption([{text:result.daftar[i].kode_jur + ' - ' + result.daftar[i].nama, value:result.daftar[i].kode_jur}]);
                            
                        }
                    }
                }
                if(kode != ""){
                    control.setValue(kode);
                }
                
            }
        });
    }

    getJurusan();
   
    function getMatpel(id=null,param){
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Kkm.php?fx=getMatpel',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?=$kode_lokasi?>','kode_pp':id},
            success:function(result){    
                var select = $('.'+param).selectize();
                select = select[0];
                var control = select.selectize;
                var kode = control.getValue();
                control.clearOptions();
                if(result.status){
                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                        for(i=0;i<result.daftar.length;i++){
                            control.addOption([{text:result.daftar[i].kode_matpel + ' - ' + result.daftar[i].nama, value:result.daftar[i].kode_matpel}]);
                            
                        }
                    }
                }
                if(kode != ""){
                    control.setValue(kode);
                }
                
            }
        });
    }

    var editor ='';
    var action_html = "<a href='#' title='Edit' class='badge badge-info' id='btn-edit'><i class='fas fa-pencil-alt'></i></a> &nbsp; <a href='#' title='Hapus' class='badge badge-danger' id='btn-delete'><i class='fa fa-trash'></i></a>";
    var kode_lokasi = '<?php echo $kode_lokasi ?>';
    var kode_pp = '<?php echo $kode_pp ?>';
    var dataTable = $('#table-data').DataTable({
        'processing': true,
        'serverSide': true,
        'ajax': {
            'url': '<?=$root_ser?>/Kkm.php?fx=getView',
            'data': {'kode_lokasi':kode_lokasi,'kode_pp':kode_pp},
            'async':false,
            'type': 'GET',
            'dataSrc' : function(json) {
                return json.data;   
            }
        },
        'columnDefs': [
            {'targets': 5, data: null, 'defaultContent': action_html }
        ],
        dom: 'lBfrtip',
        buttons: [
            {
                text: '<i class="fa fa-filter"></i> Filter',
                action: function ( e, dt, node, config ) {
                    openFilter();
                },
                className: 'btn btn-default ml-2' 
            }
        ]
    });

$(document).ready(function() {

    $('.sidepanel').on('submit', '#formFilter2', function(e){
        e.preventDefault();
        var kode_pp= $('#kode_pp2')[0].selectize.getValue();
        dataTable.search(kode_pp).draw();
    });
 
    $('.sidepanel').on('click', '#btnClose', function(e){
        e.preventDefault();
        openFilter();
    });

    $('#saku-datatable').on('click', '#btn-tambah', function(){
        $('#row-id').hide();
        $('#form-tambah')[0].reset();
        $('#id').val('');
        $('#kode_pp')[0].selectize.setValue('');
        $('#kode_ta')[0].selectize.setValue('');
        $('#kode_tingkat')[0].selectize.setValue('');
        $('#kode_jur')[0].selectize.setValue('');
        $('#flag_aktif')[0].selectize.setValue('');
        $('#input-grid tbody').html('');
        $('#saku-datatable').hide();
        $('#saku-form').show();
    });

    $('#saku-datatable').on('click', '#btn-edit', function(){
        var id= $(this).closest('tr').find('td').eq(0).html();
        var tmp= $(this).closest('tr').find('td').eq(4).html();
        var tmp = tmp.split("-");
        var kode_pp = tmp[0];     

        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Kkm.php?fx=getEdit',
            dataType: 'json',
            async:false,
            data: {'kode_kkm':id,'kode_lokasi':kode_lokasi,'kode_pp':kode_pp},
            success:function(result){
                if(result.status){
                    $('#id').val('edit');
                    $('#kode_kkm').val(id);
                    $('#kode_pp')[0].selectize.setValue(result.daftar[0].kode_pp);
                    $('#kode_ta')[0].selectize.setValue(result.daftar[0].kode_ta);
                    $('#kode_tingkat')[0].selectize.setValue(result.daftar[0].kode_tingkat);
                    $('#kode_jur')[0].selectize.setValue(result.daftar[0].kode_jur);
                    $('#flag_aktif')[0].selectize.setValue(result.daftar[0].flag_aktif);

                    if(result.daftar2.length > 0){
                        var input = '';
                        var no=1;
                        for(var i=0;i<result.daftar2.length;i++){
                            var line =result.daftar2[i];
                                input += "<tr class='row-kkm'>";
                                input += "<td width='5%' class='no-kkm'>"+no+"</td>";
                                input += "<td width='50%'><select name='kode_matpel[]' class='form-control inp-matpel ke"+no+"' value='' required></select></td>";
                                input += "<td width='30%'><input name='kkm[]' class='form-control inp-kkm ' value='"+line.kkm+"' required></td>";
                                input += "<td width='5%' class='text-center'><a class='btn btn-danger btn-sm hapus-item' style='font-size:8px'><i class='fa fa-times fa-1'></i></td>";
                                input += "</tr>";
                            no++;
                        }
                        $('#input-grid tbody').html(input);

                        $('.inp-kkm').inputmask("numeric", {
                            radixPoint: ",",
                            groupSeparator: ".",
                            digits: 2,
                            autoGroup: true,
                            rightAlign: true,
                            oncleared: function () { self.Value(''); }
                        });
                        var no=1;
                        for(var i=0;i<result.daftar2.length;i++){
                            var line =result.daftar2[i];
                            getMatpel(result.daftar[0].kode_pp,'ke'+no);
                            $('.ke'+no)[0].selectize.setValue(line.kode_matpel);
                            no++;
                        }
                    }
                   
                    $('#row-id').show();
                    $('#saku-datatable').hide();
                    $('#saku-form').show();
                }
            }
        });
    });

    $('#saku-form').on('click', '#btn-kembali', function(){
        $('#saku-datatable').show();
        $('#saku-form').hide();
    });

    
    $('#btn-save').click(function(){
        $('#form-tambah').submit();
    });

    $('#form-tambah').on('click', '#add-row', function(){
        var no=$('#input-grid .row-kkm:last').index();
        no=no+2;
        var input = "";
        input += "<tr class='row-kkm'>";
        input += "<td width='5%' class='no-kkm'>"+no+"</td>";
        input += "<td width='50%'><select name='kode_matpel[]' class='form-control inp-matpel ke"+no+"' value='' required></select></td>";
        input += "<td width='30%'><input name='kkm[]' class='form-control inp-kkm ' value='' required></td>";
        input += "<td width='5%' class='text-center'><a class='btn btn-danger btn-sm hapus-item' style='font-size:8px'><i class='fa fa-times fa-1'></i></td>";
        input += "</tr>";
        $('#input-grid tbody').append(input);
        var kode_pp=$('#kode_pp')[0].selectize.getValue();
        getMatpel(kode_pp,'ke'+no);

        $('.inp-kkm').inputmask("numeric", {
            radixPoint: ",",
            groupSeparator: ".",
            digits: 2,
            autoGroup: true,
            rightAlign: true,
            oncleared: function () { self.Value(''); }
        });
        $('#input-grid tbody tr:last').find('.inp-matpel')[0].selectize.focus();
    });

     
    $('#input-grid').on('click', '.hapus-item', function(){
        $(this).closest('tr').remove();
        no=1;
        $('.row-kkm').each(function(){
            var nom = $(this).closest('tr').find('.no-kkm');
            nom.html(no);
            no++;
        });
        $("html, body").animate({ scrollTop: $(document).height() }, 1000);
    });

    $('#saku-datatable').on('click','#btn-delete',function(e){
        e.preventDefault();
        Swal.fire({
        title: 'Yakin Data Akan Dihapus?',
        text: "Data tidak bisa dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Dihapus!'
        }).then((result) => {
            if (result.value) {
                var kode = $(this).closest('tr').find('td:eq(0)').html(); 
                var kode_lokasi = '<?php echo $kode_lokasi; ?>'; 
                var tmp= $(this).closest('tr').find('td').eq(4).html();
                var tmp = tmp.split("-");
                var kode_pp = tmp[0];
       
                
                $.ajax({
                    type: 'DELETE',
                    url: '<?=$root_ser?>/Kkm.php',
                    dataType: 'json',
                    async:false,
                    data: {'kode_kkm':kode,'kode_lokasi':kode_lokasi,'kode_pp':kode_pp},
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
                            title: 'Error',
                            text: result.message
                            })
                        }
                    }
                });
                
            }else{
                return false;
            }
        })
    });

    $('#saku-form').on('submit', '#form-tambah', function(e){
    e.preventDefault();
        var parameter = $('#id').val();
        if(parameter==''){
            // tambah
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
                url: '<?=$root_ser?>/Kkm.php?fx=simpan',
                dataType: 'json',
                data: formData,
                async:false,
                contentType: false,
                cache: false,
                processData: false, 
                success:function(result){
                    // alert('Input data '+result.message);
                    var pesan=result.message;
                    if(result.status){
                        // location.reload();
                        dataTable.ajax.reload();
                        Swal.fire(
                            'Great Job!',
                            'Data Berhasil Disimpan',
                            'success'
                        )
                        $('#saku-datatable').show();
                        $('#saku-form').hide();
                        
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: result.message
                        })
                    }
                },
                fail: function(xhr, textStatus, errorThrown){
                    alert('request failed:'+textStatus);
                }
            });
        }else{
            console.log('parameter:ubah');
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
                url: '<?=$root_ser?>/Kkm.php?fx=ubah',
                dataType: 'json',
                data: formData,
                async:false,
                contentType: false,
                cache: false,
                processData: false,  
                success:function(result){
                    // alert('Update data '+result.message);
                    if(result.status){
                        // location.reload();
                        dataTable.ajax.reload();
                        Swal.fire(
                            'Great Job!',
                            'Data Berhasil di Update',
                            'success'
                        )
                        $('#saku-datatable').show();
                        $('#saku-form').hide();
                        
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: result.message
                        })
                    }
                }
            });
        }
        
    });

    $('#kode_pp,#kode_ta,#kode_tingkat,#kode_jur,#flag_aktif').keydown(function(e){
        var code = (e.keyCode ? e.keyCode : e.which);
        var nxt = ['kode_pp','kode_ta','kode_tingkat','kode_jur','flag_aktif'];
        if (code == 13 || code == 40) {
            e.preventDefault();
            var idx = nxt.indexOf(e.target.id);
            idx++;
            if(idx == 1 || idx == 2 || idx == 3 || idx == 4 || idx == 5){
                $('#'+nxt[idx])[0].selectize.focus();
            }else{
                
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
});
    </script>