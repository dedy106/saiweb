
<!-- TABEL penghargaan -->
<div class='box-body sai-container-overflow-x'>
<table class='table table-bordered table-striped DataTable' id='table-penghargaan'>
  <thead>
    <tr>
      <td>No</td>
      <td>Nama</td>
      <td>Tanggal</td>
      <td>Dokumen</td>
      <td>Action</td>
    </tr>
  </thead>
  <tbody>
   
  </tbody>
</table>
</div>
<!-- FORM MODAL TAMBAH/UBAH penghargaan -->
<div class='modal' id='modal-dPenghargaan' tabindex='-1' role='dialog'>
    <div class='modal-dialog' role='document'>
        <div class='modal-content'>
            <form id='form-dPenghargaan' enctype='multipart/form-data'>
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
                        <input type="text" class="form-control" name="nama" placeholder="" id="modal-dPenghargaan-nama" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 control-label" style="text-align:left;">Tanggal</label>
                        <div class="col-md-9">
                        <input type="date" class="form-control" name="tanggal" placeholder="" id="modal-dPenghargaan-tgl" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 control-label" style="text-align:left;">Dokumen</label>
                        <div class="col-md-9">
                        <input type="file" class="form-control" name="file_gambar" placeholder="" id="modal-dPenghargaan-file_gambar" >
                        </div>
                    </div>       
                </div>
            </form>
        </div>
    </div>
</div>
<script>

$(document).ready(function(){

    var action_html = "<a href='#' title='Edit' class='sai-btn-circle aPenghargaan_edit'><i class='fa fa-pencil-alt'></i></a> &nbsp; <a href='#' title='Hapus' class='tbl-delete sai-btn-circle aPenghargaan_del'><i class='fa fa-trash'></i></a>";

    var kode_lokasi = '<?php echo $kode_lokasi ?>';
    var nik = '<?php echo $nik ?>';
    var dataTable = $('#table-penghargaan').DataTable({
        'processing': true,
        'serverSide': true,
        'ajax': {
            'url': '<?=$root_ser?>/Penghargaan.php?fx=getPenghargaan',
            'data': {'kode_lokasi':kode_lokasi,'nik_user':nik},
            'type': 'POST',
            'dataSrc' : function(json) {
                return json.data;   
            }
        },
        'columnDefs': [
            {'targets': 4, data: null, 'defaultContent': action_html }
        ]
    });


// SHOW  FORM TAMBAH MODAL
    $('#aPenghargaan').click(function(){
        // $('#modal-dPenghargaan-nama').val('');
        // $('#modal-dPenghargaan-tgl').val('');
        
        $('#form-dPenghargaan')[0].reset();
        $('#modal-id').val('0');
        $('#header_modal').html("<i class='fa fa-plus'></i> Tambah Penghargaan");
        $('#modal-dPenghargaan').modal('show');
    });

//SIMPAN DATA
    $('#form-dPenghargaan').submit(function(e){
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
            url: '<?=$root_ser?>/Penghargaan.php?fx=simpanPenghargaan',
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
                    $('#modal-dPenghargaan').modal('hide');

                }
            }
        });
        
    });

//SHOW EDIT FORM
    $('#table-penghargaan').on('click','.aPenghargaan_edit',function(){
        
        var kode = $(this).closest('tr').find('td:eq(1)').text();
        var lokasi = '<?php echo $kode_lokasi; ?>';
        var nik = '<?php echo $nik; ?>';
        var paths='<?php echo $path."/server/media/"; ?>';

        $.ajax({
            type: 'POST',
            url: '<?=$root_ser?>/Penghargaan.php?fx=getEditPenghargaan',
            dataType: 'json',
            data: {'kode_lokasi':lokasi, 'kode':kode,'nik':nik},
            success:function(res){

                if(res.status){        
                    var line = res.daftar[0];
                    $('#modal-id').val('1');
                    $('#modal-dPenghargaan-nama').val(line.nama);
                    $('#modal-dPenghargaan-tgl').val(line.tgl);
                    $('#header_modal').html("<i class='fa fa-pencil'></i> Edit Penghargaan");
                    $('#modal-dPenghargaan').modal('show');
                    
                }
            },
            fail: function(xhr, textStatus, errorThrown){
                alert('request failed:');
            }
        });
    });

//HAPUS DATA
    $('#table-penghargaan').on('click','.aPenghargaan_del',function(){
        if(confirm('Apakah anda ingin menghapus data ini?')){
            var kode = $(this).closest('tr').find('td:eq(1)').text();        
            var kode_lokasi='<?php echo $kode_lokasi; ?>' ;
            var nik='<?php echo $nik; ?>' ;

            $.ajax({
                type: 'POST',
                url: '<?=$root_ser?>/Penghargaan.php?fx=hapusPenghargaan',
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


