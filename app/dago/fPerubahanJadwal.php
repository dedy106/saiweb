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
                        <h4 class="card-title">Data Perubahan Jadwal
                        <!-- <button type="button" id="btn-tambah" class="btn btn-info ml-2" style="float:right;"><i class="fa fa-plus-circle"></i> Tambah</button> -->
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
                                        <th>Kode Paket</th>
                                        <th>Nama</th>
                                        <th>Kode Curr</th>
                                        <th>Jenis Produk</th>
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
                            <h4 class="card-title mb-2">Ubah Jadwal
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
								<label for="kode" class="col-3 col-form-label">No Paket</label>
                                <div class="col-9">
                                    <input class="form-control" type="text" placeholder="No Paket" id="no_paket" name="no_paket">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">Nama Paket</label>
                                <div class="col-9">
                                    <input class="form-control" type="text" placeholder="Nama Paket" id="nama" name="nama">
                                </div>
                            </div>
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#btambah" role="tab" aria-selected="true"><span class="hidden-xs-down">Data Jadwal</span></a> </li>
                                <!-- <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#bdok" role="tab" aria-selected="true"><span class="hidden-xs-down">Biaya Dokumen</span></a> </li> -->
                                <!-- <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#dok" role="tab" aria-selected="true"><span class="hidden-xs-down">Data Dokumen</span></a> </li> -->
                            </ul>
                            <div class="tab-content tabcontent-border">
                                <div class="tab-pane active" id="btambah" role="tabpanel">
                                    <div class='col-xs-12 mt-2' style='overflow-y: scroll; height:300px; margin:0px; padding:0px;'>
                                        <style>
                                        th,td{
                                            padding:8px !important;
                                            vertical-align:middle !important;
                                        }
                                        </style>
                                        <table class="table table-striped table-bordered table-condensed" id="table-btambah">
                                            <thead>
                                            <tr>
                                            <th width="20%">ID</th>
                                            <th width="40%">Jadwal Lama</th>
                                            <th width="40%">Jadwal Baru</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>           
    <script>

function getJadwal(no_paket){
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/PerubahanJadwal.php?fx=getJadwal',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?=$kode_lokasi?>','no_paket':no_paket},
            success:function(result){    
                if(result.status){
                    if(result.daftar.length > 0){
                        var html ='';
                        var no=1;
                        for(var i=0;i<result.daftar.length;i++){
                            var line =result.daftar[i];
                            html+=`<tr class='row-btambah'>
                            <td width='20%'><input type='text' readonly name='no_jadwal[]' class='form-control inp-btambah_jumlah currency'  value='`+line.no_jadwal+`' required></td>
                            <td width='40%'><input type='text' name='jadwal_lama[]' class='form-control datepicker inp-btambah_kode_biaya' value='`+line.tgl_berangkat+`' required></td>
                            <td width='40%'><input type='text' name='jadwal_baru[]' class='form-control datepicker inp-btambah_nama'  value='`+line.tgl_berangkat+`' required></td>
                            </tr>`;
                            no++;
                        }
                        $('#table-btambah tbody').html(html);
                        // $('.currency').inputmask("numeric", {
                        //     radixPoint: ",",
                        //     groupSeparator: ".",
                        //     digits: 2,
                        //     autoGroup: true,
                        //     rightAlign: true,
                        //     oncleared: function () { self.Value(''); }
                        // });
                        $('.datepicker').datepicker({
                            format: 'dd/mm/yyyy'
                        });
                    }
                }
            }
        });
    }
   
    $('#form-view').on('click', '#btn-tambah', function(){
        $('#row-id').hide();
        $('#form-tambah')[0].reset();
        $('#id').val('');
        $('#no_paket').attr('readonly', false);
        $('.preview').html('');
        $('#form-view').hide();
        $('#form-tambah-jenis').show();
    });

    $('#form-view').on('click', '#btn-edit', function(){
        var id= $(this).closest('tr').find('td').eq(0).html();

        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/PerubahanJadwal.php?fx=getEdit',
            dataType: 'json',
            async:false,
            data: {'no_paket':id,'kode_lokasi':kode_lokasi},
            success:function(result){
                if(result.status){
                    $('#id').val('edit');
                    $('#no_paket').val(id);
                    $('#no_paket').attr('readonly', true);
                    $('#nama').val(result.daftar[0].nama);
                   
                    $('#row-id').show();
                    $('#form-view').hide();
                    $('#form-tambah-jenis').show();
                }
            }
        });

        getJadwal(id);
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
            'url': '<?=$root_ser?>/PerubahanJadwal.php?fx=getView',
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
                    url: '<?=$root_ser?>/Pekerjaan.php',
                    dataType: 'json',
                    async:false,
                    data: {'no_paket':kode,'kode_lokasi':kode_lokasi},
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
                url: '<?=$root_ser?>/Jenis.php?fx=simpan',
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
                url: '<?=$root_ser?>/PerubahanJadwal.php?fx=ubah',
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

    $('#no_paket,#nama').keydown(function(e){
        var code = (e.keyCode ? e.keyCode : e.which);
        var nxt = ['no_paket','nama'];
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