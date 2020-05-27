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
        <div class="row" id="saku-data-klp">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4" ><i class='fas fa-cube'></i> Data Kelompok Barang 
                        <button type="button" id="btn-klp-tambah" class="btn btn-info ml-2" style="float:right;"><i class="fa fa-plus-circle"></i> Tambah</button></h4>
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
                            <table id="table-klp" class="table table-bordered table-striped" style='width: 100%;'>
                                <thead>
                                    <tr>
                                        <th>Kode</th>
                                        <th>Nama</th>
                                        <th>Akun Persediaan</th>
                                        <th>Akun Pendapatan</th>
                                        <th>Akun HPP</th>
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
        <div class="row" id="form-tambah-klp" style="display:none;">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <form class="form" id="form-tambah">
                            <h4 class="card-title">Form Data Kelompok Barang
                            <button type="submit" class="btn btn-success ml-2"  style="float:right;" id="btn-save"><i class="fa fa-save"></i> Simpan</button>
                            <button type="button" class="btn btn-secondary ml-2" id="btn-klp-kembali" style="float:right;"><i class="fa fa-undo"></i> Kembali</button>
                            </h4>
                            <h6 class="card-subtitle">Input Data Kelompk Barang</h6>
                            <hr>
                            <div class="form-group row" id="row-id">
                                <div class="col-9">
                                    <input class="form-control" type="text" id="id" name="id" readonly hidden>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">Kode</label>
                                <div class="col-9">
                                    <input class="form-control" type="text" placeholder="Kode Kelompok Barang" id="kode_klp" name="kode_klp">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">Nama</label>
                                <div class="col-9">
                                    <input class="form-control" type="text" placeholder="Nama Kelompok Barang" id="nama" name="nama">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">Akun Persediaan</label>
                                <div class="col-9">
                                    <select class='form-control' id="akun_pers" name="akun_pers">
                                    <option value=''>--- Pilih Akun Persediaan ---</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">Akun Pendapatan</label>
                                <div class="col-9">
                                    <select class='form-control' id="akun_pdpt" name="akun_pdpt">
                                    <option value=''>--- Pilih Akun Pendapatan ---</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">Akun HPP</label>
                                <div class="col-9">
                                    <select class='form-control' id="akun_hpp" name="akun_hpp">
                                    <option value=''>--- Pilih Akun HPP ---</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>           

    <script>
    function getPers(){
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/BarangKlp.php?fx=getPers',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?=$kode_lokasi?>'},
            success:function(result){    
                if(result.status){
                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                        var select = $('#akun_pers').selectize();
                        select = select[0];
                        var control = select.selectize;
                        for(i=0;i<result.daftar.length;i++){
                            control.addOption([{text:result.daftar[i].kode_akun + ' - ' + result.daftar[i].nama, value:result.daftar[i].kode_akun}]);
                        }
                    }
                }
            }
        });
    }

    function getPdpt(){
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/BarangKlp.php?fx=getPdpt',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?=$kode_lokasi?>'},
            success:function(result){    
                if(result.status){
                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                        var select = $('#akun_pdpt').selectize();
                        select = select[0];
                        var control = select.selectize;
                        for(i=0;i<result.daftar.length;i++){
                            control.addOption([{text:result.daftar[i].kode_akun + ' - ' + result.daftar[i].nama, value:result.daftar[i].kode_akun}]);
                        }
                    }
                }
            }
        });
    }

    function getHPP(){
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/BarangKlp.php?fx=getHPP',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?=$kode_lokasi?>'},
            success:function(result){    
                if(result.status){
                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                        var select = $('#akun_hpp').selectize();
                        select = select[0];
                        var control = select.selectize;
                        for(i=0;i<result.daftar.length;i++){
                            control.addOption([{text:result.daftar[i].kode_akun + ' - ' + result.daftar[i].nama, value:result.daftar[i].kode_akun}]);
                        }
                    }
                }
            }
        });
    }

    getPers();
    getPdpt();
    getHPP();
    $('#saku-data-klp').on('click', '#btn-klp-tambah', function(){
        $('#row-id').hide();
        $('#id').val('');
        $('#kode_klp').attr('readonly', false);
        $('#saku-data-klp').hide();
        $('#form-tambah-klp').show();
        $('#form-tambah')[0].reset();
    });

    $('#saku-data-klp').on('click', '#btn-edit', function(){
        var id= $(this).closest('tr').find('td').eq(0).html();

        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/BarangKlp.php?fx=getEdit',
            dataType: 'json',
            async:false,
            data: {'kode_klp':id,'kode_lokasi':kode_lokasi},
            success:function(result){
                if(result.status){
                    $('#id').val('edit');
                    $('#kode_klp').val(id);
                    $('#kode_klp').attr('readonly', true);
                    $('#nama').val(result.daftar[0].nama);
                    $('#akun_pers')[0].selectize.setValue(result.daftar[0].akun_pers);
                    $('#akun_pdpt')[0].selectize.setValue(result.daftar[0].akun_pdpt);
                    $('#akun_hpp')[0].selectize.setValue(result.daftar[0].akun_hpp);
                    $('#row-id').show();
                    $('#saku-data-klp').hide();
                    $('#form-tambah-klp').show();
                }
            }
        });
    });


    $('#form-tambah-klp').on('click', '#btn-klp-kembali', function(){
        $('#saku-data-klp').show();
        $('#form-tambah-klp').hide();
    });

    var action_html = "<a href='#' title='Edit' class='badge badge-info' id='btn-edit'><i class='fas fa-pencil-alt'></i></a> &nbsp; <a href='#' title='Hapus' class='badge badge-danger' id='btn-delete'><i class='fa fa-trash'></i></a>";
    var kode_lokasi = '<?php echo $kode_lokasi ?>';
    var dataTable = $('#table-klp').DataTable({
        'processing': true,
        'serverSide': true,
        'ajax': {
            'url': '<?=$root_ser?>/BarangKlp.php?fx=getBarangKlp',
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

    $('#saku-data-klp').on('click','#btn-delete',function(e){
        if(confirm('Apakah anda ingin menghapus data ini?')){
            var kode = $(this).closest('tr').find('td:eq(0)').html(); 
            var kode_lokasi = '<?php echo $kode_lokasi; ?>';        
            
            $.ajax({
                type: 'DELETE',
                url: '<?=$root_ser?>/BarangKlp.php',
                dataType: 'json',
                async:false,
                data: {'kode_klp':kode,'kode_lokasi':kode_lokasi},
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

    $('#form-tambah-klp').on('submit', '#form-tambah', function(e){
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
                url: '<?=$root_ser?>/BarangKlp.php?fx=simpan',
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
                        $('#saku-data-klp').show();
                        $('#form-tambah-klp').hide();
                    }
                },
                fail: function(xhr, textStatus, errorThrown){
                    alert('request failed:'+textStatus);
                }
            });
        }else{
            console.log('paramete:ubah');
            formData = $(this).serialize();
            var nik='<?php echo $nik; ?>' ;
            var kode_lokasi='<?php echo $kode_lokasi; ?>' ;
            
            $.ajax({
                type: 'PUT',
                url: '<?=$root_ser?>/BarangKlp.php',
                dataType: 'json',
                async:false,
                data: formData+"&kode_lokasi="+kode_lokasi+"&nik_user="+nik, 
                success:function(result){
                    alert('Update data '+result.message);
                    if(result.status){
                        dataTable.ajax.reload();
                        $('#saku-data-klp').show();
                        $('#form-tambah-klp').hide();
                    }
                }
            });
        }
        
    });
    $('#kode_klp,#nama,#akun_pers,#akun_pdpt,#akun_hpp').keydown(function(e){
        var code = (e.keyCode ? e.keyCode : e.which);
        var nxt = ['kode_klp','nama','akun_pers','akun_pdpt','akun_hpp'];
        if (code == 13 || code == 40) {
            e.preventDefault();
            var idx = nxt.indexOf(e.target.id);
            idx++;
            if(idx == 2 || idx == 3 || idx == 4){
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