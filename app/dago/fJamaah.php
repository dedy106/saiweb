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
        <div class="row" id="saku-data-jamaah">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Data Jamaah 
                        <button type="button" id="btn-jamaah-tambah" class="btn btn-info ml-2" style="float:right;"><i class="fa fa-plus-circle"></i> Tambah</button>
                        </h4>
                        <!-- <h6 class="card-subtitle">Tabel Data Karyawan</h6> -->
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
                            <table id="table-jamaah" class="table table-bordered table-striped" style='width:100%'>
                                <thead>
                                    <tr>
                                        <th>No Jamaah</th>
                                        <th>ID Jamaah</th>
                                        <th>Nama</th>
                                        <th>Jenis Kelamin</th>
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
        <div class="row" id="form-tambah-jamaah" style="display:none;">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <form class="form" id="form-tambah">
                            <h4 class="card-title mb-2">Form Data Jamaah
                            <button type="submit" class="btn btn-success ml-2"  style="float:right;" id="btn-save"><i class="fa fa-save"></i> Simpan</button>
                            <button type="button" class="btn btn-secondary ml-2" id="btn-jamaah-kembali" style="float:right;"><i class="fa fa-undo"></i> Kembali</button>
                            </h4>
                            <div class="form-group row" id="row-id">
                                <div class="col-9">
                                    <input class="form-control" type="text" id="id" name="id" readonly hidden>
                                </div>
                            </div>
                            <div class="form-group row mt-3">
                                <div class="col-3">
                                    <input class="form-control" type="hidden" placeholder="No Jamaah" id="no_peserta" name="no_peserta" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="id_peserta" class="col-3 col-form-label">No KTP</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" placeholder="Masukkan No KTP Jamaah" id="id_peserta" name="id_peserta" required minlength="16" maxlength="16">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">Nama</label>
                                <div class="col-4">
                                    <input class="form-control" type="text" placeholder="Masukkan Nama Jamaah" id="nama" name="nama" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="tempat" class="col-3 col-form-label">Tempat Lahir</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" placeholder="Masukkan Tempat Lahir Jamaah" id="tempat" name="tempat" required>
                                </div>
                                <label for="tgl_lahir" class="col-3 col-form-label">Tgl Lahir</label>
                                <div class="col-3">
                                    <input class="form-control" type="date"  id="tgl_lahir" name="tgl_lahir" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="jk" class="col-3 col-form-label">Jenis Kelamin</label>
                                <div class="col-3">
                                    <select class='form-control' id="jk" name="jk" required>
                                    <option value=''>--- Pilih Jenis Kelamin ---</option>
                                    <option value='Laki-laki'>Laki-laki</option>
                                    <option value='Perempuan'>Perempuan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="status" class="col-3 col-form-label">Status</label>
                                <div class="col-3">
                                    <select class='form-control' id="status" name="status" required>
                                    <option value=''>--- Pilih Status ---</option>
                                    <option value='Menikah'>Menikah</option>
                                    <option value='Belum Menikah'>Belum Menikah</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="pendidikan" class="col-3 col-form-label">Pendidikan Terakhir</label>
                                <div class="col-4">
                                    <input class="form-control" type="text" placeholder="Masukkan Pendidikan Terakhir" id="pendidikan" name="pendidikan" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="ibu" class="col-3 col-form-label">Nama Ibu</label>
                                <div class="col-4">
                                    <input class="form-control" type="text" placeholder="Masukkan Nama Ibu" id="ibu" name="ibu" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="ibu" class="col-3 col-form-label">Nama Ayah</label>
                                <div class="col-4">
                                    <input class="form-control" type="text" placeholder="Masukkan Nama Ayah" id="ayah" name="ayah" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="alamat" class="col-3 col-form-label">Alamat</label>
                                <div class="col-9">
                                    <input class="form-control" type="text" placeholder="Masukkan Alamat" id="alamat" name="alamat" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="kode_pos" class="col-3 col-form-label">Kode POS</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" placeholder="Masukkan Kode POS" id="kode_pos" name="kode_pos" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="telp" class="col-3 col-form-label">No Telp</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" placeholder="No Telepon Jamaah" id="telp" name="telp">
                                </div>
                                <label for="hp" class="col-3 col-form-label">No HP</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" placeholder="No HP Jamaah" id="hp" name="hp">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="email" class="col-3 col-form-label">Email</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" placeholder="Masukkan Email" id="email" name="email" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="pekerjaan" class="col-3 col-form-label">Pekerjaan</label>
                                <div class="col-3">
                                    <select class='form-control' id="pekerjaan" name="pekerjaan" required>
                                    <option value=''>--- Pilih Pekerjaan ---</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="bank" class="col-3 col-form-label">Bank</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" placeholder="Masukkan Bank" id="bank" name="bank" required>
                                </div>
                                <label for="norek" class="col-3 col-form-label">No Rekening</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" placeholder="Masukkan No Rekening Bank" id="norek" name="norek" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="cabang" class="col-3 col-form-label">Cabang</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" placeholder="Masukkan Cabang" id="cabang" name="cabang" required>
                                </div>
                                <label for="namarek" class="col-3 col-form-label">Nama Rekening</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" placeholder="Masukkan Nama Rekening" id="namarek" name="namarek" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nopass" class="col-3 col-form-label">No Passport</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" placeholder="Masukkan No Passport" id="nopass" name="nopass" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="issued" class="col-3 col-form-label">Issued</label>
                                <div class="col-3">
                                    <input class="form-control" type="date" id="issued" name="issued" required>
                                </div>
                                <label for="ex_pass" class="col-3 col-form-label">Expired</label>
                                <div class="col-3">
                                    <input class="form-control" type="date" id="ex_pass" name="ex_pass" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="kantor_mig" class="col-3 col-form-label">Kantor Imigrasi</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" id="kantor_mig" name="kantor_mig" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="ec_telp" class="col-3 col-form-label">Telp Emergency</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" placeholder="No Telepon Emergency" id="ec_telp" name="ec_telp" required>
                                </div>
                                <label for="ec_hp" class="col-3 col-form-label">HP Emergency</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" placeholder="No HP Emergency" id="ec_hp" name="ec_hp" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="sp" class="col-3 col-form-label">Status Pemesanan</label>
                                <div class="col-3">
                                    <select class='form-control' id="sp" name="sp" required>
                                    <option value=''>--- Pilih Status Pemesanan ---</option>
                                    <option value='Belum Pernah'>Belum Pernah</option>
                                    <option value='Pernah'>Pernah</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="th_haji" class="col-3 col-form-label">Th Haji Terakhir dengan Dago</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" placeholder="Tahun Haji Terakhir" id="th_haji" name="th_haji">
                                </div>
                                <label for="th_umroh" class="col-3 col-form-label">Umroh Terakhir dengan Dago</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" placeholder="Tahun Umroh Terakhir" id="th_umroh" name="th_umroh">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-3 col-form-label">Foto</label>
                                <div class="input-group col-9">
                                    <div class="custom-file">
                                        <input type="file" name="file_gambar" class="custom-file-input" id="file_gambar">
                                        <label class="custom-file-label" for="file_gambar">Choose file</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="preview col-3">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>           
    <script>

    function getPekerjaan(){
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Jamaah.php?fx=getPekerjaan',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?=$kode_lokasi?>'},
            success:function(result){    
                if(result.status){
                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                        var select = $('#pekerjaan').selectize();
                        select = select[0];
                        var control = select.selectize;
                        for(i=0;i<result.daftar.length;i++){
                            control.addOption([{text:result.daftar[i].id_pekerjaan + ' - ' + result.daftar[i].nama, value:result.daftar[i].id_pekerjaan}]);
                        }
                    }
                }
            }
        });
    }

    getPekerjaan();
   
    $('#jk').selectize();    
    $('#status').selectize();    
    $('#sp').selectize();
    $('#saku-data-jamaah').on('click', '#btn-jamaah-tambah', function(){
        $('#row-id').hide();
        $('#form-tambah')[0].reset();
        $('#id').val('');
        $('.preview').html('');
        $('#saku-data-jamaah').hide();
        $('#form-tambah-jamaah').show();
    });

    $('.custom-file-input').on('change',function(){
        //get the file name
        var fileName = $(this).val();
        //replace the "Choose a file" label
        $(this).next('.custom-file-label').html(fileName);
    })

    $('#saku-data-jamaah').on('click', '#btn-edit', function(){
        var id= $(this).closest('tr').find('td').eq(0).html();

        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Jamaah.php?fx=getEdit',
            dataType: 'json',
            async:false,
            data: {'no_peserta':id,'kode_lokasi':kode_lokasi},
            success:function(result){
                if(result.status){
                    $('#id').val('edit');
                    $('#no_peserta').val(result.daftar[0].no_peserta);
                    $('#id_peserta').val(result.daftar[0].id_peserta);
                    $('#nama').val(result.daftar[0].nama);
                    $('#tempat').val(result.daftar[0].tempat); 
                    $('#tgl_lahir').val(result.daftar[0].tgl_lahir);
                    $('#jk')[0].selectize.setValue(result.daftar[0].jk);
                    $('#status')[0].selectize.setValue(result.daftar[0].status); 
                    $('#ibu').val(result.daftar[0].ibu);
                    $('#ayah').val(result.daftar[0].ayah);
                    $('#alamat').val(result.daftar[0].alamat);
                    $('#kode_pos').val(result.daftar[0].kode_pos); 
                    $('#telp').val(result.daftar[0].telp);
                    $('#hp').val(result.daftar[0].hp);
                    $('#email').val(result.daftar[0].email);
                    $('#pekerjaan')[0].selectize.setValue(result.daftar[0].pekerjaan);
                    $('#bank').val(result.daftar[0].bank);
                    $('#norek').val(result.daftar[0].norek);
                    $('#cabang').val(result.daftar[0].cabang);
                    $('#namarek').val(result.daftar[0].namarek);
                    $('#nopass').val(result.daftar[0].nopass);
                    $('#issued').val(result.daftar[0].issued);
                    $('#ex_pass').val(result.daftar[0].ex_pass);
                    $('#kantor_mig').val(result.daftar[0].kantor_mig);
                    $('#ec_telp').val(result.daftar[0].ec_telp);
                    $('#ec_hp').val(result.daftar[0].ec_hp);
                    $('#sp')[0].selectize.setValue(result.daftar[0].sp); 
                    $('#th_haji').val(result.daftar[0].th_haji);
                    $('#th_umroh').val(result.daftar[0].th_umroh);
                    $('#pendidikan').val(result.daftar[0].pendidikan);
                    var html = "<img style='width:120px' src='<?=$path?>/upload/"+result.daftar[0].foto+"'>";
                    $('.preview').html(html);
                    $('#row-id').show();
                    $('#saku-data-jamaah').hide();
                    $('#form-tambah-jamaah').show();
                }
            }
        });
    });

    $('#form-tambah-jamaah').on('change', '#id_peserta', function(){
        var id= $('#id_peserta').val();

        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Jamaah.php?fx=getEditByKTP',
            dataType: 'json',
            async:false,
            data: {'id_peserta':id,'kode_lokasi':kode_lokasi},
            success:function(result){
                if(result.status){
                    if(id == ""){
                        $('#id').val('');
                        $('#form-tambah')[0].reset();
                    }else if (result.daftar.length == 0) {
                        $('#id').val('');
                        $('#form-tambah')[0].reset();
                        $('#id_peserta').val(id);
                    }else if (result.daftar[0].id_peserta=='') {
                        $('#id').val('');
                        $('#form-tambah')[0].reset();
                        $('#id_peserta').val(id);
                    }else{
                        $('#id').val('edit');
                        $('#no_peserta').val(result.daftar[0].no_peserta);
                        $('#id_peserta').val(result.daftar[0].id_peserta);
                        $('#nama').val(result.daftar[0].nama);
                        $('#tempat').val(result.daftar[0].tempat); 
                        $('#tgl_lahir').val(result.daftar[0].tgl_lahir);
                        $('#jk')[0].selectize.setValue(result.daftar[0].jk);
                        $('#status')[0].selectize.setValue(result.daftar[0].status); 
                        $('#ibu').val(result.daftar[0].ibu);
                        $('#ayah').val(result.daftar[0].ayah);
                        $('#alamat').val(result.daftar[0].alamat);
                        $('#kode_pos').val(result.daftar[0].kode_pos); 
                        $('#telp').val(result.daftar[0].telp);
                        $('#hp').val(result.daftar[0].hp);
                        $('#email').val(result.daftar[0].email);
                        $('#pekerjaan')[0].selectize.setValue(result.daftar[0].pekerjaan);
                        $('#bank').val(result.daftar[0].bank);
                        $('#norek').val(result.daftar[0].norek);
                        $('#cabang').val(result.daftar[0].cabang);
                        $('#namarek').val(result.daftar[0].namarek);
                        $('#nopass').val(result.daftar[0].nopass);
                        $('#issued').val(result.daftar[0].issued);
                        $('#ex_pass').val(result.daftar[0].ex_pass);
                        $('#kantor_mig').val(result.daftar[0].kantor_mig);
                        $('#ec_telp').val(result.daftar[0].ec_telp);
                        $('#ec_hp').val(result.daftar[0].ec_hp);
                        $('#sp')[0].selectize.setValue(result.daftar[0].sp); 
                        $('#th_haji').val(result.daftar[0].th_haji);
                        $('#th_umroh').val(result.daftar[0].th_umroh);
                        $('#pendidikan').val(result.daftar[0].pendidikan);
                        var html = "<img style='width:120px' src='<?=$path?>/upload/"+result.daftar[0].foto+"'>";
                        $('.preview').html(html);
                        $('#row-id').show();
                        $('#saku-data-jamaah').hide();
                        $('#form-tambah-jamaah').show();
                    }
                }
            }
        });
    });


    $('#form-tambah-jamaah').on('click', '#btn-jamaah-kembali', function(){
        $('#saku-data-jamaah').show();
        $('#form-tambah-jamaah').hide();
    });

    var action_html = "<a href='#' title='Edit' class='badge badge-info' id='btn-edit'><i class='fas fa-pencil-alt'></i></a> &nbsp; <a href='#' title='Hapus' class='badge badge-danger' id='btn-delete'><i class='fa fa-trash'></i></a>";
    var kode_lokasi = '<?php echo $kode_lokasi ?>';
    var dataTable = $('#table-jamaah').DataTable({
        'processing': true,
        'serverSide': true,
        'ajax': {
            'url': '<?=$root_ser?>/Jamaah.php?fx=getJamaah',
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
    });

    $('#saku-data-jamaah').on('click','#btn-delete',function(e){
        
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
                    url: '<?=$root_ser?>/Jamaah.php',
                    dataType: 'json',
                    async:false,
                    data: {'no_peserta':kode,'kode_lokasi':kode_lokasi},
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

    $('#form-tambah-jamaah').on('submit', '#form-tambah', function(e){
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
                url: '<?=$root_ser?>/Jamaah.php?fx=simpan',
                dataType: 'json',
                data: formData,
                async:false,
                contentType: false,
                cache: false,
                processData: false, 
                success:function(result){
                    // alert('Input data '+result.message);
                    if(result.status){
                        // location.reload();
                        dataTable.ajax.reload();
                        Swal.fire(
                            'Great Job!',
                            'Your data has been saved',
                            'success'
                        )
                        $('#saku-data-jamaah').show();
                        $('#form-tambah-jamaah').hide();
                        
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
                url: '<?=$root_ser?>/Jamaah.php?fx=ubah',
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
                            'Your data has been updated',
                            'success'
                        )
                        $('#saku-data-jamaah').show();
                        $('#form-tambah-jamaah').hide();
                        
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
        }
        
    });

    $('#id_peserta,#nama,#tempat,#tgl_lahir,#jk,#status,#pendidikan,#ibu,#alamat,#kode_pos,#telp,#hp,#email,#pekerjaan,#bank,#norek,#cabang,#namarek,#nopass,#issued,#ex_pass,#kantor_mig,#ec_telp,#ec_hp,#sp,#th_haji,#th_umroh,#file_gambar').keydown(function(e){
        var code = (e.keyCode ? e.keyCode : e.which);
        var nxt = ['id_peserta','nama','tempat','tgl_lahir','jk','status','ibu','alamat','kode_pos','telp','hp','email','pekerjaan','bank','norek','cabang','namarek','nopass','issued','ex_pass','kantor_mig','ec_telp','ec_hp','sp','th_haji','th_umroh','file_gambar'];
        if (code == 13 || code == 40) {
            e.preventDefault();
            var idx = nxt.indexOf(e.target.id);
            idx++;
            if(idx == 4 || idx == 5 || idx == 23){
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