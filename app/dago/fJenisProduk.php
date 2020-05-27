<?php
    session_start();
    $root_lib=$_SERVER["DOCUMENT_ROOT"];
    if (substr($root_lib,-1)!="/") {
        $root_lib=$root_lib."/";
    }
    include_once($root_lib.'app/dago/setting.php');
    $kode_lokasi=$_SESSION['lokasi'];
    $nik=$_SESSION['userLog'];
?>
    <div class="container-fluid mt-3">
        <div class="row" id="form-view">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Data Jenis Produk
                        <button type="button" id="btn-tambah" class="btn btn-info ml-2" style="float:right;"><i class="fa fa-plus-circle"></i> Tambah</button>
                        </h4>
                        <!-- <h6 class="card-subtitle">Tabel Data Customer</h6> -->
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
                            </style>
                            <table id="table-jenis" class="table table-bordered table-striped" style='width:100%'>
                                <thead>
                                    <tr>
                                        <th>Kode Produk</th>
                                        <th>Nama</th>
                                        <th>Kode Akun</th>
                                        <th>Akun Pendapatan</th>
                                        <th>Akun Piutang</th>
                                        <th>Aksi</th>
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
        <div class="row" id="form-tambah-jenis" style="display:none;">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <form class="form" id="form-tambah">
                            <h4 class="card-title mb-2">Data Jenis Produk
                            <button type="submit" class="btn btn-success ml-2"  style="float:right;" id="btn-save"><i class="fa fa-save"></i> Simpan</button>
                            <button type="button" class="btn btn-secondary ml-2" id="btn-kembali" style="float:right;"><i class="fa fa-undo"></i> Kembali</button>
                            </h4>
							<hr>
                            <div class="form-group row" id="row-id">
                                <div class="col-9">
                                    <input class="form-control" type="text" id="id" name="id" readonly hidden>
                                </div>
                            </div>
                            <div class="form-group row ">
								<label for="kode_produk" class="col-3 col-form-label">Kode Produk</label>
                                <div class="col-9">
                                    <input class="form-control" type="text" placeholder="Kode Produk" id="kode_produk" name="kode_produk">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">Nama</label>
                                <div class="col-9">
                                    <input class="form-control" type="text" placeholder="Nama Jenis" id="nama" name="nama">
                                </div>
                            </div>
                            <div class="form-group row ">
								<label for="kode_akun" class="col-3 col-form-label">Kode Akun</label>
                                <div class="col-9">
                                    <!-- <input class="form-control" type="text" placeholder="ex: 213000001" id="kode_akun" name="kode_akun"> -->
                                    <select name="kode_akun" id="kode_akun" class="form-control">
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row ">
                                <label for="akun_pdpt" class="col-3 col-form-label">Akun PDPT</label>
                                <div class="col-9">
                                    <!-- <input class="form-control" type="text" placeholder="ex: 411000001" id="akun_pdpt" name="akun_pdpt"> -->
                                    <select name="akun_pdpt" id="akun_pdpt" class="form-control">
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row ">
                                <label for="akun_piutang" class="col-3 col-form-label">Akun Piutang</label>
                                <div class="col-9">
                                    <!-- <input class="form-control" type="text" placeholder="ex: 113100001" id="akun_piutang" name="akun_piutang"> -->
                                    <select name="akun_piutang" id="akun_piutang" class="form-control">
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

    function clearSelectize(target){
            var select = $(target).selectize();
            var control = select[0].selectize;
            control.clear();
    }
   
    $('#form-view').on('click', '#btn-tambah', function(){
        $('#row-id').hide();
        $('#form-tambah')[0].reset();
        $('#id').val('');
        $('#kode_produk').attr('readonly', false);
        clearSelectize('#kode_akun');
        clearSelectize('#akun_pdpt');
        clearSelectize('#akun_piutang');
        $('.preview').html('');
        $('#form-view').hide();
        $('#form-tambah-jenis').show();
    });

    function getAkunPiutang(){
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/JenisProduk.php?fx=getAkunPiutang',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?=$kode_lokasi?>'},
            success:function(result){    
                if(result.status){
                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                        var select = $('#akun_piutang').selectize({
                            placeholder: '--- Pilih Kode Akun Piutang ---'
                        });
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

    function getKodeAkun(){
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/JenisProduk.php?fx=getAkun',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?=$kode_lokasi?>'},
            success:function(result){    
                if(result.status){
                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                        var select = $('#kode_akun').selectize({
                            placeholder: '--- Pilih Kode Akun ---'
                        });
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

    function getAkunPDPT(){
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/JenisProduk.php?fx=getPDPT',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?=$kode_lokasi?>'},
            success:function(result){    
                if(result.status){
                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                        var select = $('#akun_pdpt').selectize({
                            placeholder: '--- Pilih Kode Akun Pendapatan ---'
                        });
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

    getAkunPDPT();
    getAkunPiutang();
    getKodeAkun();

    $('#form-view').on('click', '#btn-edit', function(){
        var id= $(this).closest('tr').find('td').eq(0).html();

        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/JenisProduk.php?fx=getEdit',
            dataType: 'json',
            async:false,
            data: {'kode_produk':id,'kode_lokasi':kode_lokasi},
            success:function(result){
                if(result.status){
                    $('#id').val('edit');
                    $('#kode_produk').val(id);
                    $('#kode_produk').attr('readonly', true);
                    $('#nama').val(result.daftar[0].nama);
                    $('#kode_akun')[0].selectize.setValue(result.daftar[0].kode_akun);
                    $('#akun_pdpt')[0].selectize.setValue(result.daftar[0].akun_pdpt);
                    $('#akun_piutang')[0].selectize.setValue(result.daftar[0].akun_piutang);
                    $('#row-id').show();
                    $('#form-view').hide();
                    $('#form-tambah-jenis').show();
                }
            }
        });
    });


    $('#form-tambah').on('click', '#btn-kembali', function(){
        $('#form-view').show();
        $('#form-tambah-jenis').hide();
    });

    var action_html = "<a href='#' title='Edit' class='badge badge-info' id='btn-edit'><i class='fas fa-pencil-alt'></i></a> &nbsp; <a href='#' title='Hapus' class='badge badge-danger' id='btn-delete'><i class='fa fa-trash'></i></a>";
    var kode_lokasi = '<?php echo $kode_lokasi ?>';
    var dataTable = $('#table-jenis').DataTable({
        'processing': true,
        'serverSide': true,
        'ajax': {
            'url': '<?=$root_ser?>/JenisProduk.php?fx=getView',
            'data': {'kode_lokasi':kode_lokasi},
            'async':false,
            'type': 'GET',
            'dataSrc' : function(json) {
                return json.data;   
            }
        },
        'columnDefs': [
            {'targets': 5, data: null, 'defaultContent': action_html }
            ]
        // 'columnDefs': [
        //     {'targets': 3, 
        //      'className': 'text-right',
        //     'render': $.fn.dataTable.render.number( '.', ',', 0, '' ) }
        // ]
    });

    $('#form-view').on('click','#btn-delete',function(e){
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
                
                $.ajax({
                    type: 'DELETE',
                    url: '<?=$root_ser?>/JenisProduk.php?fx=hapus',
                    dataType: 'json',
                    async:false,
                    data: {'kode_produk':kode,'kode_lokasi':kode_lokasi},
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

    $('#form-tambah-jenis').on('submit', '#form-tambah', function(e){
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
                url: '<?=$root_ser?>/JenisProduk.php?fx=simpan',
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
                        $('#form-view').show();
                        $('#form-tambah-jenis').hide();
                        
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
                url: '<?=$root_ser?>/JenisProduk.php?fx=ubah',
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
                        $('#form-view').show();
                        $('#form-tambah-jenis').hide();
                        
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

    $('#kode_produk,#nama').keydown(function(e){
        var code = (e.keyCode ? e.keyCode : e.which);
        var nxt = ['kode_produk','nama'];
        if (code == 13 || code == 40) {
            e.preventDefault();
            var idx = nxt.indexOf(e.target.id);
            idx++;
            // if(idx == 2 || idx == 3){
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