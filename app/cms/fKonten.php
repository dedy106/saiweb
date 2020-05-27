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
                        <h4 class="card-title mb-4"><i class='fas fa-cube'></i> Data Konten 
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
                                        <th>Tanggal</th>
                                        <th>Judul</th>
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
                        <h4 class="card-title mb-4"><i class='fas fa-cube'></i> Data Konten
                        <button type="button" class="btn btn-success ml-2"  style="float:right;" id="btn-save"><i class="fa fa-save"></i> Simpan</button>
                        <button type="button" class="btn btn-secondary ml-2" id="btn-kembali" style="float:right;"><i class="fa fa-undo"></i> Kembali</button>
                        </h4>
                        <hr>
                    </div>
                    <div class="card-body table-responsive pt-0" style='height:460px'>
                            <form class="form" id="form-tambah" style='margin-bottom:100px'>
                                <div class="form-group row" id="row-id">
                                    <div class="col-12">
                                        <input class="form-control" type="hidden" id="id_edit" name="id">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="judul" class="col-3 col-form-label">Judul</label>
                                    <div class="col-9">
                                        <input class="form-control" type="text" placeholder="Masukkan Judul" id="judul" name="judul" required >
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="tanggal" class="col-3 col-form-label">Tanggal Publish</label>
                                    <div class="col-3">
                                        <input class="form-control" type="date" placeholder="Masukkan Tanggal" id="tanggal" name="tanggal" required >
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="header_url" class="col-3 col-form-label">Header</label>
                                    <div class="col-3">
                                        <select class='form-control selectize' id="header_url" name="header_url">
                                        <option value=''>--- Pilih Header ---</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="keterangan" class="col-3 col-form-label">Isi</label>
                                    <div class="col-9">
                                        <textarea class="form-control tinymce" type="text" placeholder="Masukkan Keterangan" id="keterangan" name="keterangan"></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="kode_klp" class="col-3 col-form-label">Kelompok</label>
                                    <div class="col-3">
                                        <select class='form-control' id="kode_klp" name="kode_klp">
                                        <option value=''>--- Pilih Kelompok ---</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="kode_kategori" class="col-3 col-form-label">Kategori</label>
                                    <div class="col-3">
                                        <select class='form-control' id="kode_kategori" name="kode_kategori">
                                        <option value=''>--- Pilih Kategori ---</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="tag" class="col-3 col-form-label">Tag</label>
                                    <div class="col-9">
                                    <input class="form-control" type="tags" placeholder="ex: investasi, asuransi" id="tag" name="tag" autocomplete="off">
                                    </div>
                                    <button class='removeTags' type='button' id="btnRemoveTag">Remove all tags</button>
                                </div>
                                <div class="row justify-content-center text-center mb-2">
                                    <b class="text-danger">Keterangan</b>
                                </div>
                                <div class="row justify-content-center text-center">
                                    <div class="col">
                                        <div class="form-group">
                                            <div class='alert' style='padding:0px; padding-top:5px; padding-bottom:5px; margin:0px; color: #31708f; border-color: #bce8f1; background-color: #d9edf7;'>
                                            &nbsp; Tag dipisahkan dengan ',' dan maksimal karakter sebanyak 200
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                    </div>
                </div>
            </div>
        </div>
    </div>           
    
    <script src="<?=$folderroot_js?>/sai.js"></script>
    <script src="<?=$folderroot_js?>/inputmask.js"></script>
    <script src="<?=$root?>/vendor/ckeditor/ckeditor.js"></script>
	<script src="<?=$root?>/vendor/ckeditor/adapters/jquery.js"></script>
    <script>
   
    $('#keterangan').ckeditor();
    
    var input = document.querySelector('input[name=tag]');
    
    // init Tagify script on the above inputs
    var tagInp = new Tagify(input);
    
    $('#btnRemoveTag').hide();
    function getKlp(){
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Konten.php?fx=getKlp',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?=$kode_lokasi?>'},
            success:function(result){    
                if(result.status){
                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                        var select = $('#kode_klp').selectize();
                        select = select[0];
                        var control = select.selectize;
                        for(i=0;i<result.daftar.length;i++){
                            control.addOption([{text:result.daftar[i].kode_klp + ' - ' + result.daftar[i].nama, value:result.daftar[i].kode_klp}]);
                        }
                    }
                }
            }
        });
    }

    function getKtg(){
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Konten.php?fx=getKtg',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?=$kode_lokasi?>'},
            success:function(result){    
                if(result.status){
                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                        var select = $('#kode_kategori').selectize();
                        select = select[0];
                        var control = select.selectize;
                        for(i=0;i<result.daftar.length;i++){
                            control.addOption([{text:result.daftar[i].kode_kategori + ' - ' + result.daftar[i].nama, value:result.daftar[i].kode_kategori}]);
                        }
                    }
                }
            }
        });
    }

    function getHeader(){
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Konten.php?fx=getHeader',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?=$kode_lokasi?>'},
            success:function(result){    
                if(result.status){
                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                        var select = $('#header_url').selectize();
                        select = select[0];
                        var control = select.selectize;
                        for(i=0;i<result.daftar.length;i++){
                            control.addOption([{text:result.daftar[i].header_url + ' - ' + result.daftar[i].nama, value:result.daftar[i].header_url}]);
                        }
                    }
                }
            }
        });
    }

    getHeader();
    getKlp();
    getKtg();
    $('#saku-datatable').on('click', '#btn-tambah', function(){
        $('#row-id').hide();
        $('#id_edit').val('');
        $('#saku-datatable').hide();
        $('#saku-form').show();
        $('#form-tambah')[0].reset();
        // tagInp.removeAllTags.bind(tagInp);
        tagInp.removeAllTags();
        $('#header_url')[0].selectize.setValue('');
        $('#kode_klp')[0].selectize.setValue('');                    
        $('#kode_kategori')[0].selectize.setValue('');  
        $('#keterangan').val('');   
    });

    $('#btn-save').click(function(){
        $('#form-tambah').submit();
    });

    $('#saku-datatable').on('click', '#btn-edit', function(){
        var id= $(this).closest('tr').find('td').eq(0).html();

        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Konten.php?fx=getEdit',
            dataType: 'json',
            async:false,
            data: {'kode':id,'kode_lokasi':"<?=$kode_lokasi?>"},
            success:function(result){
                if(result.status){
                    $('#id_edit').val(id);
                    $('#judul').val(result.daftar[0].judul);
                    $('#tanggal').val(result.daftar[0].tanggal);
                    $('#keterangan').val(result.daftar[0].keterangan);
                    $('#header_url')[0].selectize.setValue(result.daftar[0].header_url);
                    $('#kode_klp')[0].selectize.setValue(result.daftar[0].kode_klp);                    
                    $('#kode_kategori')[0].selectize.setValue(result.daftar[0].kode_kategori);
                    tagInp.removeAllTags();
                    if(result.daftar[0].tag == "" || result.daftar[0].tag == " " || result.daftar[0].tag == undefined){
                        //
                    }else{
                        
                        var obj = JSON.parse(result.daftar[0].tag);
                        if(obj.length > 0){
    
                            var tags = [];
                            for(var i=0;i<obj.length;i++){
                                tags.push(obj[i].value);
                            }
                            tagInp.addTags(tags);
                        }
                    }

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
            url: '<?=$root_ser?>/Konten.php?fx=getEdit',
            dataType: 'json',
            async:false,
            data: {'kode':id,'kode_lokasi':"<?=$kode_lokasi?>"},
            success:function(result){
                if(result.status){
                    if(result.daftar.length > 0){

                        $('#id_edit').val(id);
                        $('#judul').val(result.daftar[0].judul);
                        $('#tanggal').val(result.daftar[0].tanggal);
                        $('#keterangan').val(result.daftar[0].keterangan);
                        $('#header_url')[0].selectize.setValue(result.daftar[0].header_url);
                        $('#kode_klp')[0].selectize.setValue(result.daftar[0].kode_klp);                    
                        $('#kode_kategori')[0].selectize.setValue(result.daftar[0].kode_kategori);
                        tagInp.removeAllTags();
                        if(result.daftar[0].tag == "" || result.daftar[0].tag == " " || result.daftar[0].tag == undefined){
                            //
                        }else{
                            
                            var obj = JSON.parse(result.daftar[0].tag);
                            if(obj.length > 0){
        
                                var tags = [];
                                for(var i=0;i<obj.length;i++){
                                    tags.push(obj[i].value);
                                }
                                tagInp.addTags(tags);
                            }
                        }
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
            'url': '<?=$root_ser?>/Konten.php?fx=getView',
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
                url: '<?=$root_ser?>/Konten.php',
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
                url: '<?=$root_ser?>/Konten.php?fx=simpan',
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
                url: '<?=$root_ser?>/Konten.php?fx=ubah',
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

    $('#judul,#tanggal,#header_url,#keterangan,#kode_klp,#kode_kategori,#tag').keydown(function(e){
        var code = (e.keyCode ? e.keyCode : e.which);
        var nxt = ['judul','tanggal','header_url','keterangan','kode_klp','kode_kategori','tag'];
        if (code == 13 || code == 40) {
            e.preventDefault();
            var idx = nxt.indexOf(e.target.id);
            idx++;
            if(idx == 3 || idx == 5 || idx == 6){
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