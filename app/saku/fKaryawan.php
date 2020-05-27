<?php
    session_start();
    $root_lib=$_SERVER["DOCUMENT_ROOT"];
    if (substr($root_lib,-1)!="/") {
        $root_lib=$root_lib."/";
    }
    include_once($root_lib.'app/saku/setting.php');


   $kode_lokasi=$_SESSION['lokasi'];
   $nik=$_SESSION['userLog'];
?>
    <div class="container-fluid mt-3">
        <div class="row" id="saku-datatable">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4"><i class='fas fa-cube'></i> Data Karyawan 
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
                            </style>
                            <table id="table-data" class="table table-bordered table-striped" style='width:100%'>
                                <thead>
                                    <tr>
                                        <th>NIK</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>No Telp</th>
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
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body pb-0">
                        <h4 class="card-title mb-4"><i class='fas fa-cube'></i> Form Data Karyawan
                        <button type="button" class="btn btn-success ml-2"  style="float:right;" id="btn-save"><i class="fa fa-save"></i> Simpan</button>
                        <button type="button" class="btn btn-secondary ml-2" id="btn-kembali" style="float:right;"><i class="fa fa-undo"></i> Kembali</button>
                        </h4>
                        <hr>
                    </div>
                    <div class="card-body table-responsive pt-0" style='height:441px'>
                        <form class="form" id="form-tambah" style='margin-bottom:100px'>
                            <div class="form-group row" id="row-id">
                                <div class="col-9">
                                    <input class="form-control" type="text" id="id" name="id" readonly hidden>
                                </div>
                            </div>
                            <div class="form-group row mt-3">
                                <label for="nama" class="col-3 col-form-label">NIK</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" placeholder="NIK Karyawan" id="nik" name="nik" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">Nama</label>
                                <div class="col-9">
                                    <input class="form-control" type="text" placeholder="Nama Karyawan" id="nama" name="nama">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="alamat" class="col-3 col-form-label">Alamat</label>
                                <div class="col-9">
                                    <input class="form-control" type="text" placeholder="Alamat Karyawan" id="alamat" name="alamat">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="jabatan" class="col-3 col-form-label">Jabatan</label>
                                <div class="col-9">
                                    <input class="form-control" type="text" placeholder="Jabatan Karyawan" id="jabatan" name="jabatan">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="email" class="col-3 col-form-label">Email</label>
                                <div class="col-9">
                                    <input class="form-control" type="text" placeholder="Email Karyawan" id="email" name="email">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="no_telp" class="col-3 col-form-label">No Telp</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" placeholder="No Telepon Karyawan" id="no_telp" name="no_telp">
                                </div>
                                <label for="no_hp" class="col-3 col-form-label">No HP</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" placeholder="No HP Karyawan" id="no_hp" name="no_hp">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="kode_pp" class="col-3 col-form-label">Kode PP</label>
                                <div class="col-3">
                                    <select class='form-control' id="kode_pp" name="kode_pp">
                                    <option value=''>--- Pilih PP ---</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="npwp" class="col-3 col-form-label">NPWP</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" placeholder="NPWP Karyawan" id="npwp" name="npwp">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="bank" class="col-3 col-form-label">Bank</label>
                                <div class="col-9">
                                    <input class="form-control" type="text" placeholder="Bank Karyawan" id="bank" name="bank">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="cabang" class="col-3 col-form-label">Cabang</label>
                                <div class="col-9">
                                    <input class="form-control" type="text" placeholder="Cabang Bank Karyawan" id="cabang" name="cabang">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="no_rek" class="col-3 col-form-label">No Rek.</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" placeholder="No Rekening Bank Karyawan" id="no_rek" name="no_rek">
                                </div>
                                <label for="nama_rek" class="col-3 col-form-label">Nama Rek.</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" placeholder="Nama Rekening Bank Karyawan" id="nama_rek" name="nama_rek">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="status" class="col-3 col-form-label">Status</label>
                                <div class="col-3">
                                    <select class='form-control selectize' id="status" name="status">
                                    <option value=''>--- Pilih Status ---</option>
                                    <option value='ORGANIK'>ORGANIK</option>
                                    <option value='NON'>NON</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="grade" class="col-3 col-form-label">Grade</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" placeholder="Grade Karyawan" id="grade" name="grade">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="kota" class="col-3 col-form-label">Kota</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" placeholder="Kota" id="kota" name="kota">
                                </div>
                                <label for="kode_pos" class="col-3 col-form-label">Kode POS</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" placeholder="Kode POS" id="kode_pos" name="kode_pos">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="flag_aktif" class="col-3 col-form-label">Status Aktif</label>
                                <div class="col-3">
                                    <select class='form-control selectize' id="flag_aktif" name="flag_aktif">
                                    <option value=''>--- Pilih Status ---</option>
                                    <option value='1'>AKTIF</option>
                                    <option value='0'>NON AKTIF</option>
                                    </select>
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
                                <label class="col-3 col-form-label">Tanda Tangan</label>
                                <div class="input-group col-9">
                                    <div class="custom-file">
                                        <input type="file" name="ttd" class="custom-file-input" id="ttd">
                                        <label class="custom-file-label" for="ttd">Choose file</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="kode_unit" class="col-3 col-form-label">Kode Unit</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" placeholder="Kode Unit" id="kode_unit" name="kode_unit">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="kode_jab" class="col-3 col-form-label">Kode Jabatan 1</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" placeholder="Kode Jabatan 2" id="kode_jab" name="kode_jab">
                                </div>
                                <label for="kode_jab2" class="col-3 col-form-label">Kode Jabatan 2</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" placeholder="Kode Jabatan 1" id="kode_jab2" name="kode_jab2">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nip" class="col-3 col-form-label">NIP</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" placeholder="NIP" id="nip" name="nip">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="flag_sppd" class="col-3 col-form-label">Status SPPD</label>
                                <div class="col-3">
                                    <select class='form-control selectize' id="flag_sppd" name="flag_sppd">
                                    <option value=''>--- Pilih Status ---</option>
                                    <option value='1'>AKTIF</option>
                                    <option value='0'>NON AKTIF</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="preview col-3">
                                </div>
                                <div class="col-2">
                                </div>
                                <div class="preview2 col-3">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>           
    <script>

    function getPP(){
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Karyawan.php?fx=getPP',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?=$kode_lokasi?>'},
            success:function(result){    
                if(result.status){
                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                        var select = $('#kode_pp').selectize();
                        select = select[0];
                        var control = select.selectize;
                        for(i=0;i<result.daftar.length;i++){
                            control.addOption([{text:result.daftar[i].kode_pp + ' - ' + result.daftar[i].nama, value:result.daftar[i].kode_pp}]);
                        }
                    }
                }
            }
        });
    }
   
    getPP();
    $('.selectize').selectize();
    $('#saku-datatable').on('click', '#btn-tambah', function(){
        $('#row-id').hide();
        $('#form-tambah')[0].reset();
        $('#id').val('');
        $('#nik').attr('readonly', false);
        $('.preview').html('');
        $('#saku-datatable').hide();
        $('#saku-form').show();
    });

    $('.custom-file-input').on('change',function(){
        //get the file name
        var fileName = $(this).val();
        //replace the "Choose a file" label
        $(this).next('.custom-file-label').html(fileName);
    })

    
    $('#btn-save').click(function(){
        $('#form-tambah').submit();
    });

    $('#saku-datatable').on('click', '#btn-edit', function(){
        var id= $(this).closest('tr').find('td').eq(0).html();

        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Karyawan.php?fx=getEdit',
            dataType: 'json',
            async:false,
            data: {'nik':id,'kode_lokasi':kode_lokasi},
            success:function(result){
                if(result.status){
                    $('#id').val('edit');
                    $('#nik').val(id);
                    $('#nik').attr('readonly', true);
                    $('#nama').val(result.daftar[0].nama);
                    $('#alamat').val(result.daftar[0].alamat);
                    $('#jabatan').val(result.daftar[0].jabatan);
                    $('#email').val(result.daftar[0].email);
                    $('#no_telp').val(result.daftar[0].no_telp);
                    $('#kode_pp')[0].selectize.setValue(result.daftar[0].kode_pp);
                    $('#npwp').val(result.daftar[0].npwp);                    
                    $('#bank').val(result.daftar[0].bank);
                    $('#cabang').val(result.daftar[0].cabang);
                    $('#no_rek').val(result.daftar[0].no_rek);
                    $('#nama_rek').val(result.daftar[0].nama_rek);
                    $('#status')[0].selectize.setValue(result.daftar[0].status);
                    $('#grade').val(result.daftar[0].grade);
                    $('#kota').val(result.daftar[0].kota);
                    $('#kode_pos').val(result.daftar[0].kode_pos);
                    $('#no_hp').val(result.daftar[0].no_hp);
                    $('#flag_aktif').val(result.daftar[0].flag_aktif);
                    var html = "<img style='width:120px' src='<?=$path?>/upload/"+result.daftar[0].foto+"'>";
                    $('.preview').html(html);
                    var html2 = "<img style='width:120px' src='<?=$path?>/upload/"+result.daftar[0].ttd+"'>";
                    $('.preview2').html(html2);
                    $('#kode_unit').val(result.daftar[0].kode_unit);
                    $('#kode_jab').val(result.daftar[0].kode_jab);
                    $('#kode_jab2').val(result.daftar[0].kode_jab2);
                    $('#nip').val(result.daftar[0].nip);
                    $('#flag_sppd')[0].selectize.setValue(result.daftar[0].flag_sppd);
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
            'url': '<?=$root_ser?>/Karyawan.php?fx=getView',
            'data': {'kode_lokasi':kode_lokasi},
            'async':false,
            'type': 'GET',
            'dataSrc' : function(json) {
                return json.data;   
            }
        },
        'columnDefs': [
            {'targets': 4, data: null, 'defaultContent': action_html }
            ]
    });

    $('#saku-datatable').on('click','#btn-delete',function(e){
        
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
                    url: '<?=$root_ser?>/Karyawan.php',
                    dataType: 'json',
                    async:false,
                    data: {'nik':kode,'kode_lokasi':kode_lokasi},
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
                url: '<?=$root_ser?>/Karyawan.php?fx=simpan',
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
                        $('#saku-datatable').show();
                        $('#saku-form').hide();
                        
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
                url: '<?=$root_ser?>/Karyawan.php?fx=ubah',
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
                        $('#saku-datatable').show();
                        $('#saku-form').hide();
                        
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

    $('#nik,#nama,#alamat,#jabatan,#email,#no_telp,#kode_pp,#npwp,#bank,#cabang,#no_rek,#nama_rek,#status,#grade,#kota,#kode_pos,#no_hp,#flag_aktif,#foto,#ttd,#kode_unit,#kode_jab,#kode_jab2,#nip,#flag_sppd ').keydown(function(e){
        var code = (e.keyCode ? e.keyCode : e.which);
        var nxt = ['nik','nama','alamat','jabatan','email','no_telp','kode_pp','npwp','bank','cabang','no_rek','nama_rek','status','grade','kota','kode_pos','no_hp','flag_aktif','foto','ttd','kode_unit','kode_jab','kode_jab2','nip','flag_sppd'];
        if (code == 13 || code == 40) {
            e.preventDefault();
            var idx = nxt.indexOf(e.target.id);
            idx++;
            if(idx == 7 || idx == 13 || idx == 18 || idx == 25){
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