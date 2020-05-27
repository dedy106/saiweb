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
                        <h4 class="card-title mb-4"><i class='fas fa-cube'></i> Data Kelompok Menu 
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
                            <table id="table-data" class="table table-bordered table-striped" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Kode Kelompok</th>
                                        <th>Nama</th>
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
                        <h4 class="card-title mb-4"><i class='fas fa-cube'></i> Data Kelompok Menu
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
                                    <label for="kode_klp" class="col-3 col-form-label">Kode</label>
                                    <div class="col-3">
                                        <input class="form-control" type="text" placeholder="Kode Kelompok" id="kode_klp" name="kode_klp" required >
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="nama" class="col-3 col-form-label">Nama</label>
                                    <div class="col-9">
                                        <input class="form-control" type="text" placeholder="Nama Kelompok" id="nama" name="nama">
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
            url: '<?=$root_ser?>/MenuKlp.php?fx=getEdit',
            dataType: 'json',
            async:false,
            data: {'kode':id,'kode_lokasi':kode_lokasi},
            success:function(result){
                if(result.status){
                    $('#id_edit').val('edit');
                    $('#kode_klp').val(id);
                    $('#nama').val(result.daftar[0].nama);
                    $('#saku-datatable').hide();
                    $('#saku-form').show();
                }
            }
        });
    });


    $('#form-tambah').on('change', '#kode_klp', function(){
        var id= $(this).val();

        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/MenuKlp.php?fx=getEdit',
            dataType: 'json',
            async:false,
            data: {'kode':id,'kode_lokasi':kode_lokasi},
            success:function(result){
                if(result.status){
                    if(result.daftar.length > 0){

                        $('#id_edit').val('edit');
                        $('#kode_klp').val(id);
                        $('#nama').val(result.daftar[0].nama);
                        $('#form').val(result.daftar[0].form);
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
        'processing': true,
        'serverSide': true,
        'ajax': {
            'url': '<?=$root_ser?>/MenuKlp.php?fx=getView',
            'data': {'kode_lokasi':kode_lokasi},
            'async':false,
            'type': 'POST',
            'dataSrc' : function(json) {
                return json.data;   
            }
        },
        'columnDefs': [
            {'targets': 2, data: null, 'defaultContent': action_html }
        ]
    });

    $('#saku-datatable').on('click','#btn-delete',function(e){
        if(confirm('Apakah anda ingin menghapus data ini?')){
            var kode = $(this).closest('tr').find('td:eq(0)').html(); 
            var kode_lokasi = '<?php echo $kode_lokasi; ?>';        
            
            $.ajax({
                type: 'DELETE',
                url: '<?=$root_ser?>/MenuKlp.php',
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
                url: '<?=$root_ser?>/MenuKlp.php?fx=simpan',
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
                url: '<?=$root_ser?>/MenuKlp.php?fx=ubah',
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

    $('#kode_klp,#nama').keydown(function(e){
        var code = (e.keyCode ? e.keyCode : e.which);
        var nxt = ['kode_klp','nama'];
        if (code == 13 || code == 40) {
            e.preventDefault();
            var idx = nxt.indexOf(e.target.id);
            idx++;
            // if(idx == 3 || idx == 5 || idx == 7){
            //     $('#'+nxt[idx])[0].selectize.focus();
            // }else{
                
                $('#'+nxt[idx]).focus();
            // }
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