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
                        <h4 class="card-title">Data Agen
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
                                        <th>Kode Agen</th>
                                        <th>Nama Agen</th>
                                        <th>Tgl Lahir</th>
                                        <th>Alamat</th>
                                        <th>No HP</th>
                                        <th>Email</th>
                                        <th>Bank</th>
                                        <th>No Rekening</th>
                                        <th>Kode Marketing</th>
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
                            <h4 class="card-title mb-2">Input Agen
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
								<label for="kode_agen" class="col-3 col-form-label">Kode</label>
                                <div class="col-9">
                                    <input class="form-control" type="text" placeholder="Kode Agen" id="kode_agen" name="kode_agen">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">Nama</label>
                                <div class="col-9">
                                    <input class="form-control" type="text" placeholder="Nama" id="nama" name="nama">
                                </div>
                            </div>
                            <div class="form-group row ">
								<label for="tempat_lahir" class="col-3 col-form-label">Tempat Lahir</label>
                                <div class="col-9">
                                    <input class="form-control" type="text" placeholder="Tempat Lahir" id="tempat_lahir" name="tempat_lahir" autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group row ">
								<label for="tgl_lahir" class="col-3 col-form-label">Tanggal Lahir</label>
                                <div class="col-9">
                                    <input class="form-control datepicker" type="text" placeholder="yyyy/mm/dd" id="tgl_lahir" name="tgl_lahir" autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group row ">
								<label for="alamat" class="col-3 col-form-label">Alamat</label>
                                <div class="col-9">
                                    <textarea class="form-control" name="alamat" id="alamat" cols="30" rows="5"></textarea>
                                    <!-- <input class="form-control" type="text" placeholder="Alamat" id="alamat" name="alamat"> -->
                                </div>
                            </div>
                            <div class="form-group row ">
								<label for="no_hp" class="col-3 col-form-label">No Handphone</label>
                                <div class="col-9">
                                    <input class="form-control" type="text" placeholder="08xxxxxxxxxx" id="no_hp" name="no_hp">
                                </div>
                            </div>
                            <div class="form-group row ">
								<label for="email" class="col-3 col-form-label">Email</label>
                                <div class="col-9">
                                    <input class="form-control" type="email" placeholder="john.doe@email.com" id="email" name="email">
                                </div>
                            </div>
                            <div class="form-group row ">
								<label for="bank" class="col-3 col-form-label">Bank</label>
                                <div class="col-9">
                                    <input class="form-control" type="text" placeholder="Bank" id="bank" name="bank">
                                </div>
                            </div>
                            <div class="form-group row ">
								<label for="cabang" class="col-3 col-form-label">Cabang</label>
                                <div class="col-9">
                                    <input class="form-control" type="text" placeholder="Cabang" id="cabang" name="cabang">
                                </div>
                            </div>
                            <div class="form-group row ">
								<label for="norek" class="col-3 col-form-label">No Rekening</label>
                                <div class="col-9">
                                    <input class="form-control" type="number" placeholder="No Rekening" id="norek" name="norek">
                                </div>
                            </div>
                            <div class="form-group row ">
								<label for="namarek" class="col-3 col-form-label">Nama Rekening</label>
                                <div class="col-9">
                                    <input class="form-control" type="text" placeholder="Nama Rekening" id="namarek" name="namarek">
                                </div>
                            </div>
                            <div class="form-group row ">
								<label for="kode_marketing" class="col-3 col-form-label">Kode Marketing</label>
                                <div class="col-9">
                                    <select name="kode_marketing" id="kode_marketing" class="form-control">
                                        <!-- <option disabled hidden selected>--- Pilih Kode Marketing ---</option> -->
                                    </select>
                                    <!-- <input class="form-control" type="text" placeholder="Kode Marketing" id="kode_marketing" name="kode_marketing"> -->
                                </div>
                            </div>
                            <div class="form-group row ">
								<label for="sts_aktif" class="col-3 col-form-label">Status Aktif</label>
                                <div class="col-9">
                                    <select name="sts_aktif" id="sts_aktif" class="form-control">
                                        <!-- <option disabled hidden selected>--- Pilih Status ---</option>
                                        <option value="1.AKTIF">AKTIF</option>
                                        <option value="0.NON">NON</option> -->
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
        $(document).ready(function() {
        var select = $('#sts_aktif').selectize({
            placeholder: '--- Pilih Status Aktif ---'
        });
        select = select[0];
        var control = select.selectize;
        control.addOption([{text:'AKTIF', value:'1.AKTIF'}]);
        control.addOption([{text:'NON', value:'0.NON'}]);

        $('.datepicker').datepicker({
            format: 'yyyy/mm/dd'
        });
    });

    function clearSelectize(target){
            var select = $(target).selectize();
            var control = select[0].selectize;
            control.clear();
    }
   
    $('#form-view').on('click', '#btn-tambah', function(){
        $('#row-id').hide();
        $('#form-tambah')[0].reset();
        $('#id').val('');
        clearSelectize('#sts_aktif');
        clearSelectize('#kode_marketing');
        $('#kode_agen').attr('readonly', false);
        $('.preview').html('');
        $('#form-view').hide();
        $('#form-tambah-jenis').show();
    });

    function getMarketing(){
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Agen.php?fx=getMarketing',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?=$kode_lokasi?>'},
            success:function(result){    
                if(result.status){
                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                        var select = $('#kode_marketing').selectize({
                            placeholder: '--- Pilih Kode Marketing ---'
                        });
                        select = select[0];
                        var control = select.selectize;
                        for(i=0;i<result.daftar.length;i++){
                            control.addOption([{text:result.daftar[i].no_marketing + ' - ' + result.daftar[i].nama_marketing, value:result.daftar[i].no_marketing}]);
                        }
                    }
                }
            }
        });
    }

    getMarketing();

    $('#form-view').on('click', '#btn-edit', function(){
        var id= $(this).closest('tr').find('td').eq(0).html();

        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Agen.php?fx=getEdit',
            dataType: 'json',
            async:false,
            data: {'kode_agen':id,'kode_lokasi':kode_lokasi},
            success:function(result){
                if(result.status){
                    $('#id').val('edit');
                    $('#kode_agen').val(id);
                    $('#kode_agen').attr('readonly', true);
                    $('#nama').val(result.daftar[0].nama_agen);
                    $('#tempat_lahir').val(result.daftar[0].tempat_lahir);
                    $('#tgl_lahir').val(result.daftar[0].tgl_lahir);
                    $('#alamat').val(result.daftar[0].alamat);
                    $('#no_hp').val(result.daftar[0].no_hp);
                    $('#email').val(result.daftar[0].email);
                    $('#bank').val(result.daftar[0].bank);
                    $('#cabang').val(result.daftar[0].cabang);
                    $('#norek').val(result.daftar[0].norek);
                    $('#namarek').val(result.daftar[0].namarek);
                    $('#kode_marketing')[0].selectize.setValue(result.daftar[0].kode_marketing);
                    // $('#kode_marketing').val(result.daftar[0].kode_marketing);
                    $('#sts_aktif')[0].selectize.setValue(result.daftar[0].flag_aktif);
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

    // var action_html = "<a href='#' title='Edit' class='badge badge-info' id='btn-edit'><i class='fas fa-pencil-alt'></i></a> &nbsp; <a href='#' title='Hapus' class='badge badge-danger' id='btn-delete'><i class='fa fa-trash'></i></a>";
    var action_html = "<a href='#' title='Edit' class='badge badge-info' id='btn-edit'><i class='fas fa-pencil-alt'></i></a>";
    var kode_lokasi = '<?php echo $kode_lokasi ?>';
    var dataTable = $('#table-jenis').DataTable({
        'processing': true,
        'serverSide': true,
        'ajax': {
            'url': '<?=$root_ser?>/Agen.php?fx=getView',
            'data': {'kode_lokasi':kode_lokasi},
            'async':false,
            'type': 'GET',
            'dataSrc' : function(json) {
                return json.data;   
            }
        },
        'columnDefs': [
            {'targets': 9, data: null, 'defaultContent': action_html }
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
                    url: '<?=$root_ser?>/Agen.php',
                    dataType: 'json',
                    async:false,
                    data: {'kode_agen':kode,'kode_lokasi':kode_lokasi},
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
                url: '<?=$root_ser?>/Agen.php?fx=simpan',
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
                url: '<?=$root_ser?>/Agen.php?fx=ubah',
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

    $('#kode_agen,#nama').keydown(function(e){
        var code = (e.keyCode ? e.keyCode : e.which);
        var nxt = ['kode_agen','nama'];
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