
<!-- TABEL PELATIHAN -->
<div class='box-body sai-container-overflow-x'>
<table class='table table-bordered table-striped DataTable' id='table-pelatihan'>
  <thead>
    <tr>
      <td>No</td>
      <td>Nama</td>
      <td>Penyelenggara</td>
      <td>Tgl Mulai</td>
      <td>Tgl Selesai</td>
      <td>Dokumen</td>
      <td>Action</td>
    </tr>
  </thead>
  <tbody>
   
  </tbody>
</table>
</div>
<!-- FORM MODAL TAMBAH/UBAH pelatihan -->
<div class='modal' id='modal-dPelatihan' tabindex='-1' role='dialog'>
    <div class='modal-dialog' role='document'>
        <div class='modal-content'>
            <form id='form-dPelatihan' enctype='multipart/form-data'>
                <div class='modal-header'>
                    <div class='row' style='width:100%'>
                        <div class='col-6'>
                            <h5 class='modal-title' id='header_modal'></h5>
                        </div>
                        <div class='col-6 text-right'>
                            <button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>
                            <button type='submit' class='btn btn-primary'>Simpan</button> 
                        </div>
                    </div>
                </div>
                <div class='modal-body'>
                    <div class='row' >
                        <div class='form-group'>
                            <div class='col-sm-9' style='margin-bottom:5px;'>
                            <input type='hidden' name='id_edit' class='form-control' id='modal-id' maxlength='10' required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 control-label" style="text-align:left;">Nama</label>
                        <div class="col-md-9">
                        <input type="text" class="form-control" name="nama" placeholder="" id="modal-dPelatihan-nama" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 control-label" style="text-align:left;">Penyelenggara</label>
                        <div class="col-md-9">
                        <input type="text" class="form-control" name="penyelenggara" placeholder="" id="modal-dPelatihan-penyelenggara" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 control-label" style="text-align:left;">Tgl Mulai</label>
                        <div class="col-md-9">
                        <input type="date" class="form-control" name="tgl_mulai" placeholder="" id="modal-dPelatihan-tgl_mulai" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 control-label" style="text-align:left;">Tgl Selesai</label>
                        <div class="col-md-9">
                        <input type="date" class="form-control" name="tgl_selesai" placeholder="" id="modal-dPelatihan-tgl_selesai" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 control-label" style="text-align:left;">Dokumen</label>
                        <div class="col-md-9">
                        <input type="file" class="form-control" name="file_gambar" placeholder="" id="modal-dPelatihan-file_gambar" >
                        </div>
                    </div>             
                </div>
            </form>
        </div>
    </div>
</div>
<script>
$(document).ready(function(){

    var action_html = "<a href='#' title='Edit' class='sai-btn-circle aPelatihan_edit'><i class='fa fa-pencil-alt'></i></a> &nbsp; <a href='#' title='Hapus' class='tbl-delete sai-btn-circle aPelatihan_del'><i class='fa fa-trash'></i></a>";

    var kode_lokasi = '<?php echo $kode_lokasi ?>';
    var nik = '<?php echo $nik ?>';
    var dataTable = $('#table-pelatihan').DataTable({
        'processing': true,
        'serverSide': true,
        'ajax': {
            'url': '<?=$root_ser?>/Pelatihan.php?fx=getPelatihan',
            'data': {'kode_lokasi':kode_lokasi,'nik_user':nik},
            'type': 'POST',
            'dataSrc' : function(json) {
                return json.data;   
            }
        },
        'columnDefs': [
            {'targets': 6, data: null, 'defaultContent': action_html }
        ]
    });

// SHOW  FORM TAMBAH MODAL
    $('#aPelatihan').click(function(){
        // $('#modal-dPelatihan-nama').val('');
        // $('#modal-dPelatihan-penyelenggara').val('');
        // $('#modal-dPelatihan-tgl_mulai').val('');
        // $('#modal-dPelatihan-tgl_selesai').val('');
        
        $('#form-dPelatihan')[0].reset();
        $('#modal-id').val('0');
        $('#header_modal').html("<i class='fa fa-plus'></i> Tambah Pelatihan");
        $('#modal-dPelatihan').modal('show');
    });

//SIMPAN DATA
    $('#form-dPelatihan').submit(function(e){
        e.preventDefault();
        var formData = new FormData(this);
        
        for(var pair of formData.entries()) {
            console.log(pair[0]+ ', '+ pair[1]); 
        }

        var nik='<?php echo $nik; ?>' ;
        var kode_lokasi='<?php echo $kode_lokasi; ?>' ;

        formData.append('nik', nik);
        formData.append('kode_lokasi', kode_lokasi);
        
        $.ajax({
            type: 'POST',
            url: '<?=$root_ser?>/Pelatihan.php?fx=simpanPelatihan',
            dataType: 'json',
            data: formData,
            contentType: false,
            cache: false,
            processData: false, 
            success:function(result){
                alert('Update data '+result.message);
                if(result.status){
                    // location.reload();
                    dataTable.ajax.reload();
                    $('#modal-dPelatihan').modal('hide');


                }
            }
        });
        
    });

//SHOW EDIT FORM
    $('#table-pelatihan').on('click','.aPelatihan_edit',function(){
        
        var kode = $(this).closest('tr').find('td:eq(1)').text();
        var lokasi = '<?php echo $kode_lokasi; ?>';
        var nik = '<?php echo $nik; ?>';
        var paths='<?php echo $path."/server/media/"; ?>';

        $.ajax({
            type: 'POST',
            url: '<?=$root_ser?>/Pelatihan.php?fx=getEditPelatihan',
            dataType: 'json',
            data: {'kode_lokasi':lokasi, 'kode':kode,'nik':nik},
            success:function(res){

                if(res.status){        
                    var line = res.daftar[0];
                    $('#modal-id').val('1');
                    $('#modal-dPelatihan-nama').val(line.nama);
                    $('#modal-dPelatihan-penyelenggara').val(line.panitia);
                    $('#modal-dPelatihan-tgl_mulai').val(line.tglm);
                    $('#modal-dPelatihan-tgl_selesai').val(line.tgls);
                    $('#header_modal').html("<i class='fa fa-pencil'></i> Edit Pelatihan");
                    $('#modal-dPelatihan').modal('show');
                    
                }
            },
            fail: function(xhr, textStatus, errorThrown){
                alert('request failed:');
            }
        });
    });

//HAPUS DATA
    $('#table-pelatihan').on('click','.aPelatihan_del',function(){
        if(confirm('Apakah anda ingin menghapus data ini?')){
            var kode = $(this).closest('tr').find('td:eq(1)').text();        
            var kode_lokasi='<?php echo $kode_lokasi; ?>' ;
            var nik='<?php echo $nik; ?>' ;

            $.ajax({
                type: 'POST',
                url: '<?=$root_ser?>/Pelatihan.php?fx=hapusPelatihan',
                dataType: 'json',
                data: {'id':kode,'kode_lokasi':kode_lokasi,'nik':nik},
                success:function(result){
                    alert('Penghapusan data '+result.message);
                    if(result.status){
                        // location.reload();
                        dataTable.ajax.reload();
                    }
                }
            });
        }else{
            return false;
        }
                    
   });

});
</script>


