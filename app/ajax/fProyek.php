<?php
	session_start();
	$root_lib=$_SERVER["DOCUMENT_ROOT"];
	if (substr($root_lib,-1)!="/") {
		$root_lib=$root_lib."/";
	}
	include_once($root_lib.'app/ajax/setting.php');
	
	$nik=$_SESSION['userLog'];
	$kode_lokasi=$_SESSION['lokasi'];
	$nik=$_SESSION['userLog'];
?>
    <div class="container-fluid mt-3">
        <div class="row" id="saku-data-cust">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Data Proyek 
                        <button type="button" id="btn-cust-tambah" class="btn btn-info ml-2" style="float:right;"><i class="fa fa-plus-circle"></i> Tambah</button>
                        </h4>
                        <!-- <h6 class="card-subtitle">Tabel Data Proyek</h6> -->
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
                            <table id="table-cust" class="table table-bordered table-striped" style='width:100%'>
                                <thead>
                                    <tr>
                                        <th>No Proyek</th>
                                        <th>Nama Proyek</th>
                                        <th>Kode Cust</th>
                                        <th>Tgl Mulai</th>
                                        <th>Tgl Selesai</th>
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
                            <h4 class="card-title mb-2">Form Data Proyek
                            <button type="submit" class="btn btn-success ml-2"  style="float:right;" id="btn-save"><i class="fa fa-save"></i> Simpan</button>
                            <button type="button" class="btn btn-secondary ml-2" id="btn-cust-kembali" style="float:right;"><i class="fa fa-undo"></i> Kembali</button>
                            </h4>
                            <div class="form-group row" id="row-id">
                                <div class="col-9">
                                    <input class="form-control" type="text" id="id" name="id" readonly hidden>
                                </div>
                            </div>
                            <div class="form-group row mt-3">
                                <div class="col-3">
                                    <input class="form-control" type="hidden" placeholder="Kode Proyek" id="no_proyek" name="no_proyek" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">Nama Proyek</label>
                                <div class="col-9">
                                    <input class="form-control" type="text" placeholder="Nama Proyek" id="nama" name="nama">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">Customer</label>
                                <div class="col-3">
                                    <select class='form-control' id="kode_cust" name="kode_cust">
                                    <option value=''>--- Pilih Customer ---</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="tgl_mulai" class="col-3 col-form-label">Tgl Mulai</label>
                                <div class="col-3">
                                    <input class="form-control" type="date" id="tgl_mulai" name="tgl_mulai">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="tgl_selesai" class="col-3 col-form-label">Tgl Selesai</label>
                                <div class="col-3">
                                    <input class="form-control" type="date" id="tgl_selesai" name="tgl_selesai">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-3 col-form-label">File</label>
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

    function getCust(){
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Proyek.php?fx=getCust',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?=$kode_lokasi?>'},
            success:function(result){    
                if(result.status){
                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                        var select = $('#kode_cust').selectize();
                        select = select[0];
                        var control = select.selectize;
                        for(i=0;i<result.daftar.length;i++){
                            control.addOption([{text:result.daftar[i].kode_cust + ' - ' + result.daftar[i].nama, value:result.daftar[i].kode_cust}]);
                        }
                    }
                }
            }
        });
    }

    getCust();
    $('#saku-data-cust').on('click', '#btn-cust-tambah', function(){
        $('#row-id').hide();
        $('#form-tambah')[0].reset();
        $('#id').val('');
        $('#no_proyek').attr('readonly', false);
        $('.preview').html('');
        $('#saku-data-cust').hide();
        $('#form-tambah-cust').show();
    });

    $('#saku-data-cust').on('click', '#btn-edit', function(){
        var id= $(this).closest('tr').find('td').eq(0).html();

        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Proyek.php?fx=getEdit',
            dataType: 'json',
            async:false,
            data: {'no_proyek':id,'kode_lokasi':kode_lokasi},
            success:function(result){
                if(result.status){
                    $('#id').val('edit');
                    $('#no_proyek').val(id);
                    $('#no_proyek').attr('readonly', true);
                    $('#nama').val(result.daftar[0].nama);
                    $('#kode_cust')[0].selectize.setValue(result.daftar[0].kode_cust);
                    $('#tgl_mulai').val(result.daftar[0].tgl_mulai);
                    $('#tgl_selesai').val(result.daftar[0].tgl_selesai);
                    if(result.daftar.file_dok != "" || result.daftar.file_dok  != "-"){
                        var html = result.daftar[0].file_dok+" <a href='<?=$path?>/upload/"+result.daftar[0].file_dok+"' target='_blank'><i class='fa fa-download'></i></a>";
                        $('.preview').html(html);
                    }
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
            'url': '<?=$root_ser?>/Proyek.php?fx=getProyek',
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

    $('#saku-data-cust').on('click','#btn-delete',function(e){
        
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
                    url: '<?=$root_ser?>/Proyek.php',
                    dataType: 'json',
                    async:false,
                    data: {'no_proyek':kode,'kode_lokasi':kode_lokasi},
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
                url: '<?=$root_ser?>/Proyek.php?fx=simpan',
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
                        $('#saku-data-cust').show();
                        $('#form-tambah-cust').hide();
                        
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
                url: '<?=$root_ser?>/Proyek.php?fx=ubah',
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
                        $('#saku-data-cust').show();
                        $('#form-tambah-cust').hide();
                        
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

    $('#no_proyek,#nama,#kode_cust,#tgl_awal,#tgl_selesai,#file_gambar').keydown(function(e){
        var code = (e.keyCode ? e.keyCode : e.which);
        var nxt = ['no_proyek','nama','kode_cust','tgl_awal','tgl_selesai','file_gambar'];
        if (code == 13 || code == 40) {
            e.preventDefault();
            var idx = nxt.indexOf(e.target.id);
            idx++;
            if(idx == 1){
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