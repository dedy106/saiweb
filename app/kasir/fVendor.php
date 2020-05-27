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
        <div class="row" id="saku-data-vendor">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4" ><i class='fas fa-cube'></i> Data Vendor 
                        <button type="button" id="btn-vendor-tambah" class="btn btn-info ml-2" style="float:right;"><i class="fa fa-plus-circle"></i> Tambah</button></h4>
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
                            <table id="table-vendor" class="table table-bordered table-striped" style='width: 100%;'>
                                <thead>
                                    <tr>
                                        <th>Kode</th>
                                        <th>Nama</th>
                                        <th>Alamat</th>
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
        <div class="row" id="form-tambah-vendor" style="display:none;">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <form class="form" id="form-tambah">
                            <h4 class="card-title">Form Data Vendor
                            <button type="submit" class="btn btn-success ml-2"  style="float:right;" id="btn-save"><i class="fa fa-save"></i> Simpan</button>
                            <button type="button" class="btn btn-secondary ml-2" id="btn-vendor-kembali" style="float:right;"><i class="fa fa-undo"></i> Kembali</button>
                            </h4>
                            <h6 class="card-subtitle">Input Data Vendor</h6>
                            <hr>
                            <div class="form-group row" id="row-id">
                                <div class="col-9">
                                    <input class="form-control" type="text" id="id" name="id" readonly hidden>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">Kode</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" placeholder="Kode Vendor" id="kode_vendor" name="kode_vendor">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">Nama</label>
                                <div class="col-9">
                                    <input class="form-control" type="text" placeholder="Nama Vendor" id="nama" name="nama">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">Alamat</label>
                                <div class="col-9">
                                    <input class="form-control" type="text" placeholder="Alamat Vendor" id="alamat" name="alamat">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">No Telp</label>
                                <div class="col-9">
                                    <input class="form-control" type="text" placeholder="No Telp Vendor" id="no_telp" name="no_telp">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">No Faximile</label>
                                <div class="col-9">
                                    <input class="form-control" type="text" placeholder="No Fax Vendor" id="no_fax" name="no_fax">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">Email</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" placeholder="Email Vendor" id="email" name="email">
                                </div>
                                <label for="nama" class="col-3 col-form-label">NPWP</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" placeholder="NPWP Vendor" id="npwp" name="npwp">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">Alamat NPWP</label>
                                <div class="col-9">
                                    <input class="form-control" type="text" placeholder="Alamat NPWP Vendor" id="alamat_npwp" name="alamat_npwp">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">PIC</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" placeholder="PIC" id="pic" name="pic">
                                </div>
                                <label for="nama" class="col-3 col-form-label">No Tel PIC</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" placeholder="no_tel2" id="no_tel2" name="no_tel2">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">Bank</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" placeholder="Bank" id="bank" name="bank">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">Cabang</label>
                                <div class="col-9">
                                    <input class="form-control" type="text" placeholder="Cabang" id="cabang" name="cabang">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">No Rekening</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" placeholder="No Rekening" id="no_rek" name="no_rek">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">Nama Rekening</label>
                                <div class="col-9">
                                    <input class="form-control" type="text" placeholder="Nama Rekening" id="nama_rek" name="nama_rek">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">Akun Hutang</label>
                                <div class="col-3">
                                    <select class='form-control selectize' id="akun_hutang" name="akun_hutang">
                                    <option value=''>--- Pilih Akun Hutang ---</option>
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
    
    function getAkun(){
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Vendor.php?fx=getAkun',
            dataType: 'json',
            data: {'kode_lokasi':'<?=$kode_lokasi?>'},
            success:function(result){    
                if(result.status){
                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                        for(i=0;i<result.daftar.length;i++){
                            $('#akun_hutang')[0].selectize.addOption([{text:result.daftar[i].kode_akun + ' - ' + result.daftar[i].nama, value:result.daftar[i].kode_akun}]);
                        }
                    }
                }
            }
        });
    }

    getAkun();

    $('#saku-data-vendor').on('click', '#btn-vendor-tambah', function(){
        $('#row-id').hide();
        $('#id').val('');
        $('#kode_vendor').attr('readonly', false);
        $('#saku-data-vendor').hide();
        $('#form-tambah-vendor').show();
        $('#form-tambah')[0].reset();
    });

    $('#saku-data-vendor').on('click', '#btn-edit', function(){
        var id= $(this).closest('tr').find('td').eq(0).html();

        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Vendor.php?fx=getEdit',
            dataType: 'json',
            data: {'kode_vendor':id,'kode_lokasi':kode_lokasi},
            success:function(result){
                if(result.status){
                    $('#id').val('edit');
                    $('#kode_vendor').val(id);
                    $('#kode_vendor').attr('readonly', true);
                    $('#nama').val(result.daftar[0].nama);
                    $('#alamat').val(result.daftar[0].alamat);
                    $('#no_telp').val(result.daftar[0].no_tel);
                    $('#no_fax').val(result.daftar[0].no_fax);
                    $('#email').val(result.daftar[0].email);
                    $('#npwp').val(result.daftar[0].npwp);
                    $('#alamat_npwp').val(result.daftar[0].alamat2);
                    $('#pic').val(result.daftar[0].pic);
                    
                    $('#no_tel2').val(result.daftar[0].no_pictel);
                    $('#bank').val(result.daftar[0].bank);
                    $('#cabang').val(result.daftar[0].cabang);
                    $('#no_rek').val(result.daftar[0].no_rek);
                    $('#nama_rek').val(result.daftar[0].nama_rek);
                    $('#akun_hutang')[0].selectize.setValue(result.daftar[0].akun_hutang);
                    $('#row-id').show();
                    $('#saku-data-vendor').hide();
                    $('#form-tambah-vendor').show();
                }
            }
        });
    });


    $('#form-tambah-vendor').on('click', '#btn-vendor-kembali', function(){
        $('#saku-data-vendor').show();
        $('#form-tambah-vendor').hide();
    });

    var action_html = "<a href='#' title='Edit' class='badge badge-info' id='btn-edit'><i class='fas fa-pencil-alt'></i></a> &nbsp; <a href='#' title='Hapus' class='badge badge-danger' id='btn-delete'><i class='fa fa-trash'></i></a>";
    var kode_lokasi = '<?php echo $kode_lokasi ?>';
    var dataTable = $('#table-vendor').DataTable({
        'processing': true,
        'serverSide': true,
        'ajax': {
            'url': '<?=$root_ser?>/Vendor.php?fx=getVendor',
            'data': {'kode_lokasi':kode_lokasi},
            'type': 'POST',
            'dataSrc' : function(json) {
                return json.data;   
            }
        },
        'columnDefs': [
            {'targets': 3, data: null, 'defaultContent': action_html }
            ]
    });

    $('#saku-data-vendor').on('click','#btn-delete',function(e){
        if(confirm('Apakah anda ingin menghapus data ini?')){
            var kode = $(this).closest('tr').find('td:eq(0)').html(); 
            var kode_lokasi = '<?php echo $kode_lokasi; ?>';        
            
            $.ajax({
                type: 'DELETE',
                url: '<?=$root_ser?>/Vendor.php',
                dataType: 'json',
                data: {'kode_vendor':kode,'kode_lokasi':kode_lokasi},
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

    $('#form-tambah-vendor').on('submit', '#form-tambah', function(e){
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
                url: '<?=$root_ser?>/Vendor.php?fx=simpan',
                dataType: 'json',
                data: formData,
                contentType: false,
                cache: false,
                processData: false, 
                success:function(result){
                    alert('Input data '+result.message);
                    if(result.status){
                        // location.reload();
                        dataTable.ajax.reload();
                        $('#saku-data-vendor').show();
                        $('#form-tambah-vendor').hide();
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
                url: '<?=$root_ser?>/Vendor.php',
                dataType: 'json',
                data: formData+"&kode_lokasi="+kode_lokasi+"&nik="+nik, 
                success:function(result){
                    alert('Update data '+result.message);
                    if(result.status){
                        dataTable.ajax.reload();
                        $('#saku-data-vendor').show();
                        $('#form-tambah-vendor').hide();
                    }
                }
            });
        }
        
    });

    $('#kode_vendor,#nama,#alamat,#no_telp,#no_fax,#email,#npwp,#alamat_npwp,#pic,#no_tel2,#bank,#cabang,#no_rek,#nama_rek,#akun_hutang').keydown(function(e){
        var code = (e.keyCode ? e.keyCode : e.which);
        var nxt = ['kode_vendor','nama','alamat','no_telp','no_fax','email','npwp','alamat_npwp','pic','no_tel2','bank','cabang','no_rek','nama_rek','akun_hutang'];
        if (code == 13 || code == 40) {
            e.preventDefault();
            var idx = nxt.indexOf(e.target.id);
            idx++;
            if(idx == 14){
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