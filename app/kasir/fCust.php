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
        <div class="row" id="saku-data-cust">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                    <h4 class="card-title mb-4" ><i class='fas fa-cube'></i> Data Customer 
                        <button type="button" id="btn-cust-tambah" class="btn btn-info ml-2" style="float:right;"><i class="fa fa-plus-circle"></i> Tambah</button></h4>
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
                            <table id="table-cust" class="table table-bordered table-striped" style='width: 100%;'>
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
        <div class="row" id="form-tambah-cust" style="display:none;">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <form class="form" id="form-tambah">
                            <h4 class="card-title">Form Data Customer
                            <button type="submit" class="btn btn-success ml-2"  style="float:right;" id="btn-save"><i class="fa fa-save"></i> Simpan</button>
                            <button type="button" class="btn btn-secondary ml-2" id="btn-cust-kembali" style="float:right;"><i class="fa fa-undo"></i> Kembali</button>
                            </h4>
                            <h6 class="card-subtitle">Input Data Customer</h6>
                            <hr>
                            <div class="form-group row" id="row-id">
                                <div class="col-9">
                                    <input class="form-control" type="text" id="id" name="id" readonly hidden>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">Kode</label>
                                <div class="col-9">
                                    <input class="form-control"  type="text" placeholder="Kode Customer" id="kode_cust" name="kode_cust" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">Nama</label>
                                <div class="col-9">
                                    <input class="form-control" type="text" placeholder="Nama Customer" id="nama" name="nama">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">Alamat</label>
                                <div class="col-9">
                                    <input class="form-control" type="text" placeholder="Alamat Customer" id="alamat" name="alamat">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">No Telp</label>
                                <div class="col-9">
                                    <input class="form-control" type="text" placeholder="No Telp Customer" id="no_telp" name="no_telp">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">No Faximile</label>
                                <div class="col-9">
                                    <input class="form-control" type="text" placeholder="No Fax Customer" id="no_fax" name="no_fax">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">Email</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" placeholder="Email Customer" id="email" name="email">
                                </div>
                                <label for="nama" class="col-3 col-form-label">NPWP</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" placeholder="NPWP Customer" id="npwp" name="npwp">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">Alamat NPWP</label>
                                <div class="col-9">
                                    <input class="form-control" type="text" placeholder="Alamat NPWP Customer" id="alamat_npwp" name="alamat_npwp">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">PIC</label>
                                <div class="col-9">
                                    <input class="form-control" type="text" placeholder="PIC" id="pic" name="pic">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">Akun Piutang</label>
                                <div class="col-9">
                                    <select class='form-control' id="akun_piutang" name="akun_piutang">
                                    <option value=''>--- Pilih Akun Piutang ---</option>
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
    function nextField(event,ID){
        
        if (event.which == 13){
            $('#'+ID).focus();            
        }
        alert(event.which);
        
    }
    function getAkun(){
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Cust.php?fx=getAkun',
            dataType: 'json',
            data: {'kode_lokasi':'<?=$kode_lokasi?>'},
            success:function(result){    
                if(result.status){
                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                        var select = $('#akun_piutang').selectize();
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

    getAkun();
    $('#saku-data-cust').on('click', '#btn-cust-tambah', function(){
        $('#row-id').hide();
        $('#id').val('');
        $('#kode_cust').attr('readonly', false);
        $('#saku-data-cust').hide();
        $('#form-tambah-cust').show();
        $('#form-tambah')[0].reset();
    });

    $('#saku-data-cust').on('click', '#btn-edit', function(){
        var id= $(this).closest('tr').find('td').eq(0).html();

        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Cust.php?fx=getEdit',
            dataType: 'json',
            data: {'kode_cust':id,'kode_lokasi':kode_lokasi},
            success:function(result){
                if(result.status){
                    $('#id').val('edit');
                    $('#kode_cust').val(id);
                    $('#kode_cust').attr('readonly', true);
                    $('#nama').val(result.daftar[0].nama);
                    $('#alamat').val(result.daftar[0].alamat);
                    $('#no_telp').val(result.daftar[0].no_tel);
                    $('#no_fax').val(result.daftar[0].no_fax);
                    $('#email').val(result.daftar[0].email);
                    $('#npwp').val(result.daftar[0].npwp);
                    $('#alamat_npwp').val(result.daftar[0].alamat2);
                    $('#pic').val(result.daftar[0].pic);
                    $('#akun_piutang')[0].selectize.setValue(result.daftar[0].akun_piutang);
                    $('#row-id').show();
                    $('#saku-data-cust').hide();
                    $('#form-tambah-cust').show();
                }
            }
        });
    });


    $('#form-tambah-cust').on('click', '#btn-cust-kembali', function(){
        $('#saku-data-cust').show();
        $('#form-tambah-cust').hide();
    });

    var action_html = "<a href='#' title='Edit' class='badge badge-info' id='btn-edit'><i class='fas fa-pencil-alt'></i></a> &nbsp; <a href='#' title='Hapus' class='badge badge-danger' id='btn-delete'><i class='fa fa-trash'></i></a>";
    var kode_lokasi = '<?php echo $kode_lokasi ?>';
    var dataTable = $('#table-cust').DataTable({
        'processing': true,
        'serverSide': true,
        'ajax': {
            'url': '<?=$root_ser?>/Cust.php?fx=getCust',
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

    $('#saku-data-cust').on('click','#btn-delete',function(e){
        if(confirm('Apakah anda ingin menghapus data ini?')){
            var kode = $(this).closest('tr').find('td:eq(0)').html(); 
            var kode_lokasi = '<?php echo $kode_lokasi; ?>';        
            
            $.ajax({
                type: 'DELETE',
                url: '<?=$root_ser?>/Cust.php',
                dataType: 'json',
                data: {'kode_cust':kode,'kode_lokasi':kode_lokasi},
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

    $('#form-tambah-cust').on('submit', '#form-tambah', function(e){
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
                url: '<?=$root_ser?>/Cust.php?fx=simpan',
                dataType: 'json',
                data: formData,
                contentType: false,
                cache: false,
                processData: false, 
                success:function(result){
                   
                    if(result.status){
                        // location.reload();
                        alert('Input data '+result.message);
                        dataTable.ajax.reload();
                        $('#saku-data-cust').show();
                        $('#form-tambah-cust').hide();
                    }else{
                        alert(result.message);
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
                url: '<?=$root_ser?>/Cust.php',
                dataType: 'json',
                data: formData+"&kode_lokasi="+kode_lokasi+"&nik="+nik, 
                success:function(result){
                    alert('Update data '+result.message);
                    if(result.status){
                        dataTable.ajax.reload();
                        $('#saku-data-cust').show();
                        $('#form-tambah-cust').hide();
                    }
                }
            });
        }
        
    });


    $('#kode_cust,#nama,#alamat,#no_telp,#no_fax,#email,#npwp,#alamat_npwp,#pic,#akun_piutang').keydown(function(e){
        var code = (e.keyCode ? e.keyCode : e.which);
        var nxt = ['kode_cust','nama','alamat','no_telp','no_fax','email','npwp','alamat_npwp','pic','akun_piutang'];
        if (code == 13 || code == 40) {
            e.preventDefault();
            var idx = nxt.indexOf(e.target.id);
            idx++;
            if(idx == 9){
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