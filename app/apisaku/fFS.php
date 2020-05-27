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

<link href="<?=$folder_css?>/custom.css" rel="stylesheet">
    <div class="container-fluid mt-3">
        <div class="row" id="saku-datatable">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4"><i class='fas fa-cube'></i> Data FS 
                        <button type="button" id="btn-tambah" class="btn btn-info ml-2" style="float:right;"><i class="fa fa-plus-circle"></i> Tambah</button>
                        </h4>
                        <hr>
                        <div class="table-responsive ">
                            <style>
                                td,th{
                                    padding:8px !important;
                                    vertical-align:center;
                                }
                               
                            </style>
                            <table id="table-data" class="table table-bordered table-striped" width="100%">
                                <thead>
                                    <tr>
                                        <th>Kode FS</th>
                                        <th>Nama</th>
                                        <th>Status</th>
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
                        <h4 class="card-title mb-4"><i class='fas fa-cube'></i> Data Form
                        <button type="button" class="btn btn-success ml-2"  style="float:right;" id="btn-save"><i class="fa fa-save"></i> Simpan</button>
                        <button type="button" class="btn btn-secondary ml-2" id="btn-kembali" style="float:right;"><i class="fa fa-undo"></i> Kembali</button>
                        </h4>
                        <hr>
                    </div>
                    <div class="card-body table-responsive pt-0" style='height:450px'>
                            <form class="form" id="form-tambah" style='margin-bottom:100px'>
                                <div class="form-group row" id="row-id">
                                    <div class="col-9">
                                        <input class="form-control" type="hidden" id="id_edit" name="id">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="kode_fs" class="col-3 col-form-label">Kode</label>
                                    <div class="col-3">
                                        <input class="form-control" type="text" placeholder="Kode FS" id="kode_fs" name="kode_fs" required >
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="nama" class="col-3 col-form-label">Nama</label>
                                    <div class="col-9">
                                        <input class="form-control" type="text" placeholder="Nama FS" id="nama" name="nama">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="tgl_awal" class="col-3 col-form-label">Tgl Awal</label>
                                    <div class="col-9">
                                        <input class="form-control" type="date" placeholder="Tgl Awal" id="tgl_awal" name="tgl_awal">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="tgl_akhir" class="col-3 col-form-label">Tgl Akhir</label>
                                    <div class="col-9">
                                        <input class="form-control" type="date" placeholder="Tgl Akhir" id="tgl_akhir" name="tgl_akhir">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="flag_status" class="col-3 col-form-label">Status Aktif</label>
                                    <div class="col-3">
                                        <select class='form-control selectize' id="flag_status" name="flag_status">
                                        <option value=''>--- Pilih Status Aktif ---</option>
                                        <option value='1'>Aktif</option>
                                        <option value='0'>Non Aktif</option>
                                        </select>
                                    </div>
                                </div>
                            </form>
                    </div>
                </div>
            </div>
        </div>
    </div>           
    
    <script src="<?=$folderroot_js?>/sai.js"></script>
    <script>

    $('#saku-datatable').on('click', '#btn-tambah', function(){
        $('#row-id').hide();
        $('#id_edit').val('');
        $('#saku-datatable').hide();
        $('#saku-form').show();
        $('#form-tambah')[0].reset();
    });

    $('#btn-save').click(function(){
        $('#form-tambah').submit();
    });

    $('#saku-datatable').on('click', '#btn-edit', function(){
        var id= $(this).closest('tr').find('td').eq(0).html();

        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/FS.php?fx=getEdit',
            dataType: 'json',
            async:false,
            data: {'kode_fs':id,'kode_lokasi':kode_lokasi},
            success:function(result){
                if(result.status){
                    $('#id_edit').val('edit');
                    $('#kode_fs').val(id);
                    $('#nama').val(result.daftar[0].nama);
                    $('#tgl_awal').val(result.daftar[0].tgl_awal);
                    $('#tgl_akhir').val(result.daftar[0].tgl_akhir);
                    $('#flag_status')[0].selectize.setValue(result.daftar[0].flag_status);
                    $('#saku-datatable').hide();
                    $('#saku-form').show();
                }
            }
        });
    });


    $('#form-tambah').on('change', '#kode_fs', function(){
        var id= $(this).val();

        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/FS.php?fx=getEdit',
            dataType: 'json',
            async:false,
            data: {'kode_fs':id,'kode_lokasi':kode_lokasi},
            success:function(result){
                if(result.status){
                    if(result.daftar.length > 0){

                        $('#id_edit').val('edit');
                        $('#kode_fs').val(id);
                        $('#nama').val(result.daftar[0].nama);
                        $('#tgl_awal').val(result.daftar[0].tgl_awal);
                        $('#tgl_akhir').val(result.daftar[0].tgl_akhir);
                        $('#flag_status')[0].selectize.setValue(result.daftar[0].flag_status);
                    }else{
                        $('#id_edit').val('');
                    }
                }
            }
        });
    });


    $('#saku-form').on('click', '#btn-kembali', function(){
        $('#saku-datatable').show();
        $('#saku-form').hide();
    });

    var action_html = "<a href='#' title='Edit' class='badge badge-info' id='btn-edit'><i class='fas fa-pencil-alt'></i></a> &nbsp; <a href='#' title='Hapus' class='badge badge-danger' id='btn-delete'><i class='fa fa-trash'></i></a>";
    var kode_lokasi = '<?php echo $kode_lokasi ?>';
    var dataTable = $('#table-data').DataTable({
        // 'processing': true,
        // 'serverSide': true,
        'ajax': {
            'url': '<?=$root_ser?>/FS.php?fx=getView',
            'data': {'kode_lokasi':kode_lokasi},
            'async':false,
            'type': 'POST',
            'dataSrc' : function(json) {
                return json.data;   
            }
        },
        'columnDefs': [
            {'targets': 3, data: null, 'defaultContent': action_html }
        ],
        'columns': [
            { data: 'kode_fs' },
            { data: 'nama' },
            { data: 'flag_status' },
        ],
    });

    $('#saku-datatable').on('click','#btn-delete',function(e){
        if(confirm('Apakah anda ingin menghapus data ini?')){
            var kode = $(this).closest('tr').find('td:eq(0)').html(); 
            var kode_lokasi = '<?php echo $kode_lokasi; ?>';        
            
            $.ajax({
                type: 'DELETE',
                url: '<?=$root_ser?>/FS.php',
                dataType: 'json',
                async:false,
                data: {'kode_fs':kode,'kode_lokasi':kode_lokasi},
                success:function(result){
                    alert('Penghapusan data '+result.message);
                    if(result.status){
                        dataTable.ajax.reload();
                    }
                }
            });
        }else{
            return false;
        }
    });   

    $('#saku-form').on('submit', '#form-tambah', function(e){
    e.preventDefault();
        var parameter = $('#id_edit').val();
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
                url: '<?=$root_ser?>/FS.php?fx=simpan',
                dataType: 'json',
                data: formData,
                async:false,
                contentType: false,
                cache: false,
                processData: false, 
                success:function(result){
                    alert('Input data '+result.message);
                    if(result.status){
                        // location.reload();
                        dataTable.ajax.reload();
                        $('#saku-datatable').show();
                        $('#saku-form').hide();
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
                url: '<?=$root_ser?>/FS.php?fx=ubah',
                dataType: 'json',
                data: formData,
                async:false,
                contentType: false,
                cache: false,
                processData: false,  
                success:function(result){
                    alert('Update data '+result.message);
                    if(result.status){
                        dataTable.ajax.reload();
                        $('#saku-datatable').show();
                        $('#saku-form').hide();
                    }
                }
            });
        }
        
    });

    $('#kode_fs,#nama,#flag_aktif').keydown(function(e){
        var code = (e.keyCode ? e.keyCode : e.which);
        var nxt = ['kode_fs','nama','flag_aktif'];
        if (code == 13 || code == 40) {
            e.preventDefault();
            var idx = nxt.indexOf(e.target.id);
            idx++;
            if(idx == 2){
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

    </script>