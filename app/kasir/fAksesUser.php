<?php

    session_start();
    $root_lib=$_SERVER["DOCUMENT_ROOT"];
    if (substr($root_lib,-1)!="/") {
        $root_lib=$root_lib."/";
    }
    include_once($root_lib.'app/kasir/setting.php');

    $kode_lokasi=$_COOKIE['lokasi'];
    $nik=$_COOKIE['userLog'];
?>
    <div class="container-fluid mt-3">
        <div class="row" id="saku-datatable">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4"><i class='fas fa-cube'></i> Data Akses User 
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
                            <table id="table-data" class="table table-bordered table-striped" style='width: 100%;'>
                                <thead>
                                    <tr>
                                        <th>NIK</th>
                                        <th>Nama</th>
                                        <th>Kode Menu</th>
                                        <th>Kode Lokasi</th>
                                        <th>Status Admin</th>
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
                        <h4 class="card-title mb-4"><i class='fas fa-cube'></i> Data Akses User
                        <button type="button" class="btn btn-success ml-2"  style="float:right;" id="btn-save"><i class="fa fa-save"></i> Simpan</button>
                        <button type="button" class="btn btn-secondary ml-2" id="btn-kembali" style="float:right;"><i class="fa fa-undo"></i> Kembali</button>
                        </h4>
                        <hr>
                    </div>
                    <div class="card-body table-responsive pt-0" style='height:450px'>
                        <form class="form" id="form-tambah" style="margin-bottom:100px">
                                <div class="form-group row" id="row-id">
                                    <div class="col-9">
                                        <input class="form-control" type="hidden" id="id_edit" name="id">
                                    </div>
                                </div>
                                <div class="form-group row mt-3">
                                <label for="nama" class="col-3 col-form-label">NIK</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" placeholder="NIK" id="nik" name="nik" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">Nama</label>
                                <div class="col-9">
                                    <input class="form-control" type="text" placeholder="Nama" id="nama" name="nama">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">Kode Klp Menu</label>
                                <div class="col-3">
                                    <select class='form-control' id="kode_klp_menu" name="kode_klp_menu">
                                    <option value=''>--- Pilih Kode Klp Menu ---</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">Status Admin</label>
                                <div class="col-3">
                                    <select class='form-control selectize' id="status_admin" name="status_admin">
                                    <option value=''>--- Pilih Status Admin ---</option>
                                    <option value='A'>Admin</option>
                                    <option value='U'>User</option>
                                    <option value='P'>P</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">Password</label>
                                <div class="col-9">
                                    <input class="form-control" type="password" placeholder="Password" id="password" name="pass">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">Klp Akses</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" placeholder="Klp Akses" id="klp_akses" name="klp_akses">
                                </div>
                            </div>
                            <div class="form-group row" style='display:none'>
                                <label for="nama" class="col-3 col-form-label">Menu Mobile</label>
                                <div class="col-3">
                                    <select class='form-control' id="menu_mobile" name="menu_mobile">
                                    <option value=''>--- Pilih Form ---</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">Path View</label>
                                <div class="col-3">
                                <select class='form-control' id="path_view" name="path_view">
                                    <option value=''>--- Pilih Form ---</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row" style='display:none'>
                                <label for="nama" class="col-3 col-form-label">Kode Menu Lab</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" placeholder="Kode Menu Lab" id="kode_menu_lab" name="kode_menu_lab">
                                </div>
                            </div>
                            </form>
                    </div>
                </div>
            </div>
        </div>
    </div>           
    
    <script>
     function getForm(){
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/AksesUser.php?fx=getForm',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?=$kode_lokasi?>'},
            success:function(result){    
                if(result.status){
                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                        var select = $('#menu_mobile').selectize();
                        select = select[0];
                        var control = select.selectize;
                        var select2 = $('#path_view').selectize();
                        select2 = select2[0];
                        var control2 = select2.selectize;
                        for(i=0;i<result.daftar.length;i++){
                            control.addOption([{text:result.daftar[i].kode_form + ' - ' + result.daftar[i].nama, value:result.daftar[i].kode_form}]);
                            control2.addOption([{text:result.daftar[i].kode_form + ' - ' + result.daftar[i].nama, value:result.daftar[i].kode_form}]);

                        }
                    }
                }
            }
        });
    }

    function getMenu(){
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/AksesUser.php?fx=getMenu',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?=$kode_lokasi?>'},
            success:function(result){    
                if(result.status){
                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                        var select = $('#kode_klp_menu').selectize();
                        select = select[0];
                        var control = select.selectize;
                        for(i=0;i<result.daftar.length;i++){
                            control.addOption([{text:result.daftar[i].kode_klp, value:result.daftar[i].kode_klp}]);
                        }
                    }
                }
            }
        });
    }

    getForm();
    getMenu();

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

    $('#status_admin').selectize();

    $('#saku-datatable').on('click', '#btn-edit', function(){
        var id= $(this).closest('tr').find('td').eq(0).html();

        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/AksesUser.php?fx=getEdit',
            dataType: 'json',
            async:false,
            data: {'kode':id,'kode_lokasi':kode_lokasi},
            success:function(result){
                if(result.status){
                    $('#id_edit').val('edit');
                    $('#nik').val(id);
                    $('#nik').attr('readonly', true);
                    $('#nama').val(result.daftar[0].nama);
                    $('#password').val(result.daftar[0].pass);
                    $('#kode_klp_menu')[0].selectize.setValue(result.daftar[0].kode_klp_menu);
                    $('#status_admin')[0].selectize.setValue(result.daftar[0].status_admin);
                    $('#klp_akses').val(result.daftar[0].klp_akses);
                    $('#menu_mobile')[0].selectize.setValue(result.daftar[0].menu_mobile);
                    $('#path_view')[0].selectize.setValue(result.daftar[0].path_view);
                    $('#kode_menu_lab').val(result.daftar[0].kode_menu_lab);
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

    var action_html = "<a href='#' title='Edit' class='badge badge-info' id='btn-edit'><i class='fas fa-pencil-alt'></i></a> &nbsp; <a href='#' title='Hapus' class='badge badge-danger' id='btn-delete'><i class='fa fa-trash'></i></a>";
    var kode_lokasi = '<?php echo $kode_lokasi ?>';
    var dataTable = $('#table-data').DataTable({
        'processing': true,
        'serverSide': true,
        'ajax': {
            'url': '<?=$root_ser?>/AksesUser.php?fx=getView',
            'data': {'kode_lokasi':kode_lokasi},
            'async':false,
            'type': 'POST',
            'dataSrc' : function(json) {
                return json.data;   
            }
        },
        'columnDefs': [
            {'targets': 5, data: null, 'defaultContent': action_html }
        ]
    });

    $('#saku-datatable').on('click','#btn-delete',function(e){
        if(confirm('Apakah anda ingin menghapus data ini?')){
            var kode = $(this).closest('tr').find('td:eq(0)').html(); 
            var kode_lokasi = '<?php echo $kode_lokasi; ?>';        
            
            $.ajax({
                type: 'DELETE',
                url: '<?=$root_ser?>/AksesUser.php',
                dataType: 'json',
                async:false,
                data: {'kode':kode,'kode_lokasi':kode_lokasi},
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
                url: '<?=$root_ser?>/AksesUser.php?fx=simpan',
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
                url: '<?=$root_ser?>/AksesUser.php?fx=ubah',
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

    $('#nik,#nama,#kode_pp,#kode_jab,#file_gambar').keydown(function(e){
        var code = (e.keyCode ? e.keyCode : e.which);
        var nxt = ['nik','nama','kode_pp','kode_jab','file_gambar'];
        if (code == 13 || code == 40) {
            e.preventDefault();
            var idx = nxt.indexOf(e.target.id);
            idx++;
            if(idx == 2 || idx == 3){
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