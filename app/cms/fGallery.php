<?php
    session_start();
    $root_lib=$_SERVER["DOCUMENT_ROOT"];
    if (substr($root_lib,-1)!="/") {
        $root_lib=$root_lib."/";
    }
    include_once($root_lib.'app/cms/setting.php');

    $kode_lokasi=$_SESSION['lokasi'];
    $nik=$_SESSION['userLog'];
?>
    <div class="container-fluid mt-3">
        <div class="row" id="saku-datatable">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4"><i class='fas fa-cube'></i> Data Galeri 
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
                                        <th>ID</th>
                                        <th>Nama</th>
                                        <th>Jenis</th>
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
                        <h4 class="card-title mb-4"><i class='fas fa-cube'></i> Data Galeri
                        <button type="button" class="btn btn-success ml-2"  style="float:right;" id="btn-save"><i class="fa fa-save"></i> Simpan</button>
                        <button type="button" class="btn btn-secondary ml-2" id="btn-kembali" style="float:right;"><i class="fa fa-undo"></i> Kembali</button>
                        </h4>
                        <hr>
                    </div>
                    <div class="card-body table-responsive pt-0" style='height:460px'>
                        <form class="form" id="form-tambah" style='margin-bottom:100px'>
                            <div class="form-group row" id="row-id">
                                <div class="col-9">
                                    <input class="form-control" type="text" id="id_edit" name="id" readonly hidden>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-3 col-form-label">Foto</label>
                                <div class="input-group col-9">
                                    <div class="custom-file">
                                        <input type="file" name="file_gambar" class="custom-file-input" id="file_gambar" required>
                                        <label class="custom-file-label" for="file_gambar">Choose file</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="preview col-3" style='margin: 0 auto;'>
                                </div>
                                <div class="preview-url col-12" style='text-align:center'>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">Nama</label>
                                <div class="col-9">
                                    <input class="form-control" type="text" placeholder="Masukkan Nama" id="nama" name="nama" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="keterangan" class="col-3 col-form-label">Keterangan</label>
                                <div class="col-9">
                                <textarea class="form-control" type="text" placeholder="Masukkan Keterangan" id="keterangan" name="keterangan"></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="jenis" class="col-3 col-form-label">Untuk</label>
                                <div class="col-3">
                                <select class='form-control selectize' id="jenis" name="jenis">
                                <option value=''>--- Pilih Untuk ---</option>
                                <option value='Galeri'>Galeri</option>
                                <option value='Konten'>Konten</option>
                                <option value='Slider'>Slider</option>
                                </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="kode_ktg" class="col-3 col-form-label">Kategori Halaman Galeri</label>
                                <div class="col-3">
                                <select class='form-control' id="kode_ktg" name="kode_ktg">
                                <option value=''>--- Pilih Kategori ---</option>
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

    function getKtgGaleri(){
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Gallery.php?fx=getKtgGaleri',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?=$kode_lokasi?>'},
            success:function(result){    
                if(result.status){
                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                        var select = $('#kode_ktg').selectize();
                        select = select[0];
                        var control = select.selectize;
                        for(i=0;i<result.daftar.length;i++){
                            control.addOption([{text:result.daftar[i].kode_ktg + ' - ' + result.daftar[i].nama, value:result.daftar[i].kode_ktg}]);
                        }
                    }
                }
            }
        });
    }

    $('.custom-file-input').on('change',function(){
        //get the file name
        var fileName = $(this).val();
        //replace the "Choose a file" label
        $(this).next('.custom-file-label').html(fileName);
    })

    getKtgGaleri();
    $('#saku-datatable').on('click', '#btn-tambah', function(){
        $('#row-id').hide();
        $('#id_edit').val('');
        $('#saku-datatable').hide();
        $('#saku-form').show();
        $('#form-tambah')[0].reset();
        $('.preview').html('');
        $('.preview-url').html('');

    });

    $('#btn-save').click(function(){
        $('#form-tambah').submit();
    });

    $('#saku-datatable').on('click', '#btn-edit', function(){
        var id= $(this).closest('tr').find('td').eq(0).html();

        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Gallery.php?fx=getEdit',
            dataType: 'json',
            async:false,
            data: {'kode':id,'kode_lokasi':"<?=$kode_lokasi?>"},
            success:function(result){
                if(result.status){
                    $('#id_edit').val(id);
                    $('#nama').val(result.daftar[0].nama);
                    $('#keterangan').val(result.daftar[0].keterangan);
                    $('#jenis')[0].selectize.setValue(result.daftar[0].jenis);
                    $('#kode_ktg')[0].selectize.setValue(result.daftar[0].kode_ktg);  
                    var html = "<img style='width:120px' src='<?=$root?>/upload/"+result.daftar[0].file_gambar+"'>";
                    $('.preview').html(html);
                    var url = "<?=$root?>/upload/"+result.daftar[0].file_gambar;
                    $('.preview-url').html(url);
                    $('#saku-datatable').hide();
                    $('#saku-form').show();
                }
            }
        });
    });


    $('#form-tambah').on('change', '#id', function(){
        var id= $(this).val();

        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Gallery.php?fx=getEdit',
            dataType: 'json',
            async:false,
            data: {'kode':id,'kode_lokasi':"<?=$kode_lokasi?>"},
            success:function(result){
                if(result.status){
                    if(result.daftar.length > 0){
                        $('#id_edit').val(id);
                        $('#nama').val(result.daftar[0].nama);
                        $('#keterangan').val(result.daftar[0].keterangan);
                        $('#jenis')[0].selectize.setValue(result.daftar[0].jenis);
                        $('#kode_ktg')[0].selectize.setValue(result.daftar[0].kode_ktg);  
                        var html = "<img style='width:120px' src='<?=$root?>/upload/"+result.daftar[0].file_gambar+"'>";
                        $('.preview').html(html);
                        var url = "<?=$root?>/upload/"+result.daftar[0].file_gambar;
                        $('.preview-url').html(url);
                        $('#saku-datatable').hide();
                        $('#saku-form').show();
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
            'url': '<?=$root_ser?>/Gallery.php?fx=getView',
            'data': {'kode_lokasi':kode_lokasi},
            'async':false,
            'type': 'POST',
            'dataSrc' : function(json) {
                return json.data;   
            }
        },
        'columnDefs': [
            {'targets': 3, data: null, 'defaultContent': action_html }
        ]
    });

    $('#saku-datatable').on('click','#btn-delete',function(e){
        if(confirm('Apakah anda ingin menghapus data ini?')){
            var kode = $(this).closest('tr').find('td:eq(0)').html(); 
            var kode_lokasi = '<?php echo $kode_lokasi; ?>';        
            
            $.ajax({
                type: 'DELETE',
                url: '<?=$root_ser?>/Gallery.php',
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
            var nik='<?php echo $nik; ?>' ;
            var kode_lokasi='<?php echo $kode_lokasi; ?>' ;

            formData.append('nik_user', nik);
            formData.append('kode_lokasi', kode_lokasi);

            for(var pair of formData.entries()) {
                    console.log(pair[0]+ ', '+ pair[1]); 
                }

            $.ajax({
                type: 'POST',
                url: '<?=$root_ser?>/Gallery.php?fx=simpan',
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
            var nik='<?php echo $nik; ?>' ;
            var kode_lokasi='<?php echo $kode_lokasi; ?>' ;

            formData.append('nik_user', nik);
            formData.append('kode_lokasi', kode_lokasi);
            for(var pair of formData.entries()) {
                    console.log(pair[0]+ ', '+ pair[1]); 
                }

            
            $.ajax({
                type: 'POST',
                url: '<?=$root_ser?>/Gallery.php?fx=ubah',
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

    $('#nama,#keterangan,#jenis,#kode_ktg').keydown(function(e){
        var code = (e.keyCode ? e.keyCode : e.which);
        var nxt = ['nama','keterangan','jenis','kode_ktg'];
        if (code == 13 || code == 40) {
            e.preventDefault();
            var idx = nxt.indexOf(e.target.id);
            idx++;
            if(idx == 3 || idx == 4){
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