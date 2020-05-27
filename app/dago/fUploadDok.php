<?php
    session_start();
    $root_lib=$_SERVER["DOCUMENT_ROOT"];
    if (substr($root_lib,-1)!="/") {
        $root_lib=$root_lib."/";
    }
    include_once($root_lib.'app/dago/setting.php');
    $kode_lokasi=$_SESSION['lokasi'];
    $nik=$_SESSION['userLog'];
    $kode_pp=$_SESSION['kodePP'];
    $periode=$_SESSION['periode'];
?>
<style>
.form-group{
    margin-bottom:15px !important;
}

.dataTables_wrapper{
    padding:5px
}
</style>
    <div class="container-fluid mt-3">
        <div class="row" id="saku-data-reg">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Data Dokumen Registrasi 
                        <!-- <button type="button" id="btn-reg-tambah" class="btn btn-info ml-2" style="float:right;"><i class="fa fa-plus-circle"></i> Tambah</button> -->
                        </h4>
                        <hr>
                        <div class="table-responsive ">
                            <table id="table-reg" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No Registrasi</th>
                                        <th>No Peserta</th>
                                        <th>Nama</th>
                                        <th>Tanggal</th>
                                        <th>Paket</th>
                                        <th>Jadwal</th>
                                        <th>Jumlah Upload</th>
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
        <!-- UPLOAD DOK -->
        <div class="row" id="form-upload-reg" style='display:none'>
            <div class="col-sm-12" style="height: 90px;">
                <div class="card">
                    <form class="form" id="form-tambah" >
                        <div class="card-body pb-0">
                            <h4 class="card-title mb-4"><i class='fas fa-cube'></i> Form Upload Dokumen
                            <button type="submit" class="btn btn-success ml-2"  style="float:right;" id="btn-save"><i class="fa fa-save"></i> Simpan</button>
                            <button type="button" class="btn btn-secondary ml-2" id="btn-upload-kembali" style="float:right;"><i class="fa fa-undo"></i> Kembali</button>
                            
                            </h4>
                            <hr>
                        </div>
                        <div class="card-body table-responsive pt-0" style='height:450px' >
                            <div class="form-group row" id="row-id_upload">
                                <div class="col-9">
                                    <input class="form-control" type="text" id="id" name="id" readonly hidden>
                                    <input class="form-control" type="hidden" placeholder="No Bukti" id="upload_no_bukti" name="upload_no_bukti" >
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="upload_no_reg" class="col-3 col-form-label">No Registrasi</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" readonly id="upload_no_reg" name="upload_no_reg">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="upload_jamaah" class="col-3 col-form-label">Jamaah</label>
                                <div class="col-6">
                                    <input class="form-control" type="text" readonly id="upload_jamaah" name="upload_jamaah">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="upload_paket" class="col-3 col-form-label">Paket</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" readonly  id="upload_paket" name="upload_paket"  required>
                                </div>
                                <label for="upload_jadwal" class="col-3 col-form-label">Jadwal</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" readonly id="upload_jadwal" name="upload_jadwal" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="upload_alamat" class="col-3 col-form-label">Alamat</label>
                                <div class="col-9">
                                    <input class="form-control" type="text" readonly id="upload_alamat" name="upload_alamat"  required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="upload_tgl_terima" class="col-3 col-form-label">Tgl Terima</label>
                                <div class="col-3">
                                    <input class="form-control" type="date" id="upload_tgl_terima" name="upload_tgl_terima" required value='<?=date('Y-m-d')?>'>
                                </div>
                            </div>
                            
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#detDok" role="tab" aria-selected="true"><span class="hidden-xs-down">Detail Dokumen</span></a> </li>
                                
                            </ul>
                            <div class="tab-content tabcontent-border">
                                <div class="tab-pane active" id="detDok" role="tabpanel">
                                    <div class='col-xs-12 mt-2' style='overflow-y: scroll; height:300px; margin:0px; padding:0px;'>
                                        <style>
                                            th,td{
                                                padding:8px !important;
                                                vertical-align:middle !important;
                                            }
                                        </style>
                                        <table class="table table-striped table-bordered table-condensed" id="input-dok">
                                        <thead>
                                            <tr>
                                                <th width="5%">No</th>
                                                <th width="10%">Kode Jenis</th>
                                                <th width="20%">Jenis Dokumen</th>
                                                <th width="20%">Path File</th>
                                                <!-- <th width="20%">User</th> -->
                                                <th width="20%">Upload File</th>
                                                <th width="5%">Download</th>
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
    
    <script src="<?=$folderroot_js?>/inputmask.js"></script>
    <script>
    
    function getNamaBulan(no_bulan){
        switch (no_bulan){
            case 1 : case '1' : case '01': bulan = "Januari"; break;
            case 2 : case '2' : case '02': bulan = "Februari"; break;
            case 3 : case '3' : case '03': bulan = "Maret"; break;
            case 4 : case '4' : case '04': bulan = "April"; break;
            case 5 : case '5' : case '05': bulan = "Mei"; break;
            case 6 : case '6' : case '06': bulan = "Juni"; break;
            case 7 : case '7' : case '07': bulan = "Juli"; break;
            case 8 : case '8' : case '08': bulan = "Agustus"; break;
            case 9 : case '9' : case '09': bulan = "September"; break;
            case 10 : case '10' : case '10': bulan = "Oktober"; break;
            case 11 : case '11' : case '11': bulan = "November"; break;
            case 12 : case '12' : case '12': bulan = "Desember"; break;
            default: bulan = null;
        }

        return bulan;
    }

    var $iconLoad = $('.preloader');
    
    var kode_lokasi = '<?php echo $kode_lokasi ?>';
    var dataTable = $('#table-reg').DataTable({
        'processing': true,
        'serverSide': true,
        "ordering": true,
        "order": [[0, "desc"]],
        'ajax': {
            'url': '<?=$root_ser?>/UploadDok.php?fx=getReg',
            'data': {'kode_lokasi':kode_lokasi},
            'async':false,
            'type': 'GET',
            'dataSrc' : function(json) {
                return json.data;   
            }
        }
    });

    // $('#upload_no_reg').selectize({
    //     selectOnTab: true,
    //     onChange: function (value){
    //         getUpload(value);
    //     }
    // });

    // UPLOAD DOKUMEN
    $('#form-upload-reg').on('click', '#btn-upload-kembali', function(){
        $('#saku-data-reg').show();
        $('#form-upload-reg').hide();
    });
    
    $('#saku-data-reg').on('click','#btn-upload',function(e){
        var id = $(this).closest('tr').find('td').eq(0).html();
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/UploadDok.php?fx=getUpload',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?=$kode_lokasi?>','no_reg':id},
            success:function(result){    
                if(result.status){
                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                        var line = result.daftar[0];
                        $('#upload_no_reg').val(line.no_reg);
                        $('#upload_jamaah').val(line.no_peserta+' - '+line.nama_peserta);
                        $('#upload_paket').val(line.nama_paket);
                        $('#upload_jadwal').val(line.tgl_berangkat);
                        $('#upload_alamat').val(line.alamat);
                        if(typeof result.daftar2 !== 'undefined' && result.daftar2.length>0){
                            var html='';
                            var no=1;
                            for(var i=0;i<result.daftar2.length;i++){
                                var line2 = result.daftar2[i];
                                
                                html+= `<tr class='row-upload-dok'>"
                                <td width='5%'  class='no-dok'>`+no+`</td>
                                <td width='10%' >`+line2.no_dokumen+`<input type='hidden' name='upload_no_dokumen[]' class='form-control upload_no_dokumen' value='`+line2.no_dokumen+`' required></td>
                                <td width='20%'  class='upload_deskripsi'>`+line2.deskripsi+`</td>
                                <td width='20%'  class='upload_path'>`+line2.fileaddres+`</td>
                                <td width='20%' hidden><input type='text' name='upload_nik[]' class='form-control upload_nik' value='`+line2.nik+`' ></td>`;
                                if(line2.fileaddres == "-" || line2.fileaddres == ""){

                                    html+=`
                                    <td width='20%'>
                                    <input type='file' name='file_dok[]'>
                                    </td>`;

                                }else{
                                    
                                    html+=`
                                    <td width='20%'>
                                    <input type='file' name='file_dok[]'>
                                    </td>`;
                                }
                                html+=`
                                <td width='5%'>`;
                                if(line2.fileaddres != "-"){

                                   var link =`<a class='btn btn-success btn-sm download-dok' style='font-size:8px' href='<?=$root?>/upload/`+line2.fileaddres+`'target='_blank'><i class='fa fa-download fa-1'></i></a>`;
                                   
                                }else{
                                    var link =``;
                                }
                                html+=link+`</td>
                                </tr>`;
                                no++;
                            }
                            $('#input-dok tbody').html(html);
                        }
                        $('#form-upload-reg').show();
                        $('#saku-data-reg').hide();
                    }

                    
                }
            }
        });

    });

    $('#form-upload-reg').on('submit', '#form-tambah', function(e){
    e.preventDefault();
        var parameter = $('#id').val();
        $iconLoad.show();
        console.log('parameter:tambah');
        var formData = new FormData(this);
        for(var pair of formData.entries()) {
            console.log(pair[0]+ ', '+ pair[1]); 
        }
        
        var nik='<?php echo $nik; ?>' ;
        var kode_lokasi='<?php echo $kode_lokasi; ?>' ;
        var kode_pp='<?php echo $kode_pp; ?>' ;
        var periode='<?php echo $periode; ?>' ;
        
        formData.append('nik_user', nik);
        formData.append('kode_lokasi', kode_lokasi);
        formData.append('kode_pp', kode_pp);
        formData.append('periode', periode);
        // formData.append('no_jadwal', no_jadwal);
        
        $.ajax({
            type: 'POST',
            url: '<?=$root_ser?>/UploadDok.php?fx=simpanUpload',
            dataType: 'json',
            data: formData,
            async:false,
            contentType: false,
            cache: false,
            processData: false, 
            success:function(result){
                if(result.status){
                    dataTable.ajax.reload();
                    Swal.fire(
                        'Great Job!',
                        'Your data has been saved.'+result.message,
                        'success'
                    )
                    $('#form-tambah')[0].reset();
                    // $('#upload_no_reg')[0].selectize.setValue('');
                    $('#input-dok tbody').html('');
                        
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong!',
                        footer: '<a href>'+result.message+'</a>'
                    })
                }
                
                $iconLoad.hide();
                
                $('#form-upload-reg').hide();
                $('#saku-data-reg').show();
            },
            fail: function(xhr, textStatus, errorThrown){
                alert('request failed:'+textStatus);
            }
        });      
    });

    </script>

    
