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
                        <h4 class="card-title mb-4"><i class='fas fa-cube'></i> Data Lokasi 
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
                                        <th>Kode Lokasi</th>
                                        <th>Nama</th>
                                        <th>Alamat</th>
                                        <th>Kota</th>
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
                        <h4 class="card-title mb-4"><i class='fas fa-cube'></i> Form Data Lokasi
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
                                <label for="kode_lokasi" class="col-3 col-form-label">Kode Lokasi</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" placeholder="Kode Lokasi" id="kode_lokasi" name="kode_lokasi" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">Nama</label>
                                <div class="col-9">
                                    <input class="form-control" type="text" placeholder="Nama Lokasi" id="nama" name="nama">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="alamat" class="col-3 col-form-label">Alamat</label>
                                <div class="col-9">
                                    <input class="form-control" type="text" placeholder="Alamat Lokasi" id="alamat" name="alamat">
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
                                <label for="no_telp" class="col-3 col-form-label">No Telp</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" placeholder="No Telepon Karyawan" id="no_telp" name="no_telp">
                                </div>
                                <label for="no_hp" class="col-3 col-form-label">No Fax</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" placeholder="No HP Karyawan" id="no_hp" name="no_hp">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="flag_konsol" class="col-3 col-form-label">Flag Konsol</label>
                                <div class="col-3">
                                    <select class='form-control selectize' id="flag_konsol" name="flag_konsol">
                                    <option value=''>--- Pilih Flag Konsol ---</option>
                                    <option value='1'>AKTIF</option>
                                    <option value='0'>NON AKTIF</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-3 col-form-label">Logo</label>
                                <div class="input-group col-9">
                                    <div class="custom-file">
                                        <input type="file" name="file_gambar" class="custom-file-input" id="file_gambar">
                                        <label class="custom-file-label" for="file_gambar">Choose file</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="email" class="col-3 col-form-label">Email</label>
                                <div class="col-9">
                                    <input class="form-control" type="text" placeholder="Email Karyawan" id="email" name="email">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="website" class="col-3 col-form-label">Website</label>
                                <div class="col-9">
                                    <input class="form-control" type="text" placeholder="Website" id="website" name="website">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="npwp" class="col-3 col-form-label">NPWP</label>
                                <div class="col-9">
                                    <input class="form-control" type="text" placeholder="NPWP" id="npwp" name="npwp">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="pic" class="col-3 col-form-label">PIC</label>
                                <div class="col-9">
                                    <input class="form-control" type="text" placeholder="PIC" id="pic" name="pic">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="kode_lokkonsol" class="col-3 col-form-label">Kode Lokasi Konsol</label>
                                <div class="col-9">
                                    <input class="form-control" type="text" placeholder="Kode Lokasi Konsol" id="kode_lokkonsol" name="kode_lokkonsol">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="tgl_pkp" class="col-3 col-form-label">Tgl PKP</label>
                                <div class="col-9">
                                    <input class="form-control" type="date" placeholder="Tgl PKP" id="tgl_pkp" name="tgl_pkp">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="flag_pusat" class="col-3 col-form-label">Flag Pusat</label>
                                <div class="col-3">
                                    <select class='form-control selectize' id="flag_pusat" name="flag_pusat">
                                    <option value=''>--- Pilih Flag Pusat ---</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="no_rek" class="col-3 col-form-label">No Rek.</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" placeholder="No Rekening Bank Karyawan" id="no_rek" name="no_rek">
                                </div>
                                <label for="bank" class="col-3 col-form-label">Bank</label>
                                <div class="col-9">
                                    <input class="form-control" type="text" placeholder="Bank Karyawan" id="bank" name="bank">
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
                                <div class="preview col-3">
                                </div>
                                <div class="col-2">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>           
    <script>

    // function getPP(){
    //     $.ajax({
    //         type: 'GET',
    //         url: '<?=$root_ser?>/Lokasi.php?fx=getPP',
    //         dataType: 'json',
    //         async:false,
    //         data: {'kode_lokasi':'<?=$kode_lokasi?>'},
    //         success:function(result){    
    //             if(result.status){
    //                 if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
    //                     var select = $('#kode_pp').selectize();
    //                     select = select[0];
    //                     var control = select.selectize;
    //                     for(i=0;i<result.daftar.length;i++){
    //                         control.addOption([{text:result.daftar[i].kode_pp + ' - ' + result.daftar[i].nama, value:result.daftar[i].kode_pp}]);
    //                     }
    //                 }
    //             }
    //         }
    //     });
    // }
   
    // getPP();
    $('.selectize').selectize();
    $('#saku-datatable').on('click', '#btn-tambah', function(){
        $('#row-id').hide();
        $('#form-tambah')[0].reset();
        $('#id').val('');
        $('#kode_lokasi').attr('readonly', false);
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
            url: '<?=$root_ser?>/Lokasi.php?fx=getEdit',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':id},
            success:function(result){
                if(result.status){
                    $('#id').val('edit');
                    $('#kode_lokasi').val(id);
                    $('#kode_lokasi').attr('readonly', true);
                    $('#nama').val(result.daftar[0].nama);
                    $('#alamat').val(result.daftar[0].alamat);
                    $('#kota').val(result.daftar[0].kota);
                    $('#kodepos').val(result.daftar[0].kodepos);
                    $('#no_telp').val(result.daftar[0].no_telp);
                    $('#no_fax').val(result.daftar[0].no_fax);
                    $('#flag_konsol')[0].selectize.setValue(result.daftar[0].flag_konsol);
                    $('#email').val(result.daftar[0].email);                    
                    $('#website').val(result.daftar[0].website);
                    $('#npwp').val(result.daftar[0].npwp);
                    $('#pic').val(result.daftar[0].pic);
                    $('#kode_lokkonsol').val(result.daftar[0].kode_lokkonsol);
                    $('#tgl_pkp').val(result.daftar[0].tgl_pkp);
                    $('#flag_pusat')[0].selectize.setValue(result.daftar[0].flag_pusat);
                    $('#no_rek').val(result.daftar[0].no_rek);
                    $('#bank').val(result.daftar[0].bank);
                    $('#flag_aktif').val(result.daftar[0].flag_aktif);
                    var html = "<img style='width:120px' src='<?=$path?>/upload/"+result.daftar[0].logo+"'>";
                    $('.preview').html(html);                    
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
            'url': '<?=$root_ser?>/Lokasi.php?fx=getView',
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
                
                $.ajax({
                    type: 'DELETE',
                    url: '<?=$root_ser?>/Lokasi.php',
                    dataType: 'json',
                    async:false,
                    data: {'kode_lokasi':kode},
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

            $.ajax({
                type: 'POST',
                url: '<?=$root_ser?>/Lokasi.php?fx=simpan',
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
            
            $.ajax({
                type: 'POST',
                url: '<?=$root_ser?>/Lokasi.php?fx=ubah',
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

    $('#kode_lokasi,#nama,#alamat,#kota,#kodepos,#no_telp,#no_fax,#flag_konsol,#logo,#email,#website,#npwp,#pic,#kode_lokkonsol,#tgl_pkp,#flag_pusat,#no_rek,#bank,#flag_aktif ').keydown(function(e){
        var code = (e.keyCode ? e.keyCode : e.which);
        var nxt = ['kode_lokasi','nama',' alamat',' kota','kodepos','no_telp',' no_fax','flag_konsol','logo',' email','website','npwp',' pic','kode_lokkonsol','tgl_pkp','flag_pusat','no_rek','bank','flag_aktif'];
        if (code == 13 || code == 40) {
            e.preventDefault();
            var idx = nxt.indexOf(e.target.id);
            idx++;
            if(idx == 8 || idx == 16 || idx == 19){
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