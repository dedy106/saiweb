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
                        <h4 class="card-title">Biaya Wajib dan Opsional/Tambahan 
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
                                        <th>Kode Biaya</th>
                                        <th>Nama</th>
                                        <th>Nilai</th>
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
                            <h4 class="card-title mb-2">Biaya Wajib
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
								<label for="kode" class="col-3 col-form-label">Kode</label>
                                <div class="col-9">
                                    <input class="form-control" type="text" placeholder="Kode Biaya" id="kode_biaya" name="kode_biaya">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">Nama</label>
                                <div class="col-9">
                                    <input class="form-control" type="text" placeholder="Nama" id="nama" name="nama">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nilai" class="col-3 col-form-label">Nilai</label>
                                <div class="col-9">
                                    <input class="form-control currency2" type="text" placeholder="Nilai" id="nilai" name="nilai">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="akun_pdpt" class="col-3 col-form-label">Akun Pendapatan</label>
                                <div class="col-9">
                                    <select name="akun_pdpt" id="akun_pdpt" class="form-control">
                                        <!-- <option hidden selected disabled>--- Pilih Akun ---</option> -->
                                    </select>
                                    <!-- <input class="form-control" type="number" placeholder="Akun PDPT" id="akun_pdpt" name="akun_pdpt"> -->
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="jenis" class="col-3 col-form-label">Status</label>
                                <div class="col-9">
                                    <select name="jenis" id="jenis" class="form-control">
                                        <!-- <option hidden selected disabled>--- Pilih Status ---</option> -->
                                        <option value="TAMBAHAN">TAMBAHAN</option>
                                        <option value="DOKUMEN">DOKUMEN</option>
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

    $('.currency2').inputmask("numeric", {
                            radixPoint: ",",
                            groupSeparator: ".",
                            digits: 2,
                            autoGroup: true,
                            rightAlign: true,
                            oncleared: function () { self.Value(''); }
                        });

        var select = $('#jenis').selectize({
            placeholder: '--- Pilih Status ---'
        });
        select = select[0];
        var control = select.selectize;
        control.addOption([{text:'TAMBAHAN', value:'TAMBAHAN'}]);
        control.addOption([{text:'DOKUMEN', value:'DOKUMEN'}]);
    });

    function clearSelectize(target){
           var select = $(target).selectize();
            var control = select[0].selectize;
            control.clear();
    }
    

    function getAkunPDPT(){
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/BiayaWajib.php?fx=getAkunPDPT',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?=$kode_lokasi?>'},
            success:function(result){    
                if(result.status){
                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                        var select = $('#akun_pdpt').selectize({
                            placeholder: '--- Pilih Akun Pendapatan ---'
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

    $('#form-view').on('click', '#btn-tambah', function(){
        $('#row-id').hide();
        $('#form-tambah')[0].reset();
        $('#id').val('');
        $('#kode_biaya').attr('readonly', false);
        $('.preview').html('');
        clearSelectize('#akun_pdpt');
        clearSelectize('#jenis');
        $('#form-view').hide();
        $('#form-tambah-jenis').show();
    });

    $('#form-view').on('click', '#btn-edit', function(){
        var id= $(this).closest('tr').find('td').eq(0).html();

        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/BiayaWajib.php?fx=getEdit',
            dataType: 'json',
            async:false,
            data: {'kode_biaya':id,'kode_lokasi':kode_lokasi},
            success:function(result){
                if(result.status){
                    $('#id').val('edit');
                    $('#kode_biaya').val(id);
                    $('#kode_biaya').attr('readonly', true);
                    $('#nama').val(result.daftar[0].nama);
                    $('#nilai').val(result.daftar[0].nilai);
                    $('#akun_pdpt')[0].selectize.setValue(result.daftar[0].akun_pdpt);
                    $('#jenis')[0].selectize.setValue(result.daftar[0].jenis);
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
            'url': '<?=$root_ser?>/BiayaWajib.php?fx=getView',
            'data': {'kode_lokasi':kode_lokasi},
            'async':false,
            'type': 'GET',
            'dataSrc' : function(json) {
                return json.data;   
            }
        },
        'columnDefs': [
            {
                'targets': [2], 
                'className': 'text-right',
                'render': $.fn.dataTable.render.number( '.', ',', 0, '' ) },
            {
                'targets': 3, 
                data: null, 
                'defaultContent': action_html 
            }
        ]
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
                    url: '<?=$root_ser?>/BiayaWajib.php?fx=hapus',
                    dataType: 'json',
                    async:false,
                    data: {'kode_biaya':kode,'kode_lokasi':kode_lokasi},
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
                url: '<?=$root_ser?>/BiayaWajib.php?fx=simpan',
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
                url: '<?=$root_ser?>/BiayaWajib.php?fx=ubah',
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

    $('#kode_biaya,#nama').keydown(function(e){
        var code = (e.keyCode ? e.keyCode : e.which);
        var nxt = ['kode_biaya','nama'];
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