
<!-- TABEL PENDIDIKAN -->
<div class='box-body sai-container-overflow-x'>
<table class='table table-bordered table-striped DataTable' id='table-pendidikan'>
  <thead>
    <tr>
      <td>No</td>
      <td>Nama</td>
      <td>Tahun</td>
      <td>Jurusan</td>
      <td>Strata</td>
      <td>Dokumen</td>
      <td>Action</td>
    </tr>
  </thead>
  <tbody>
   
  </tbody>
</table>
</div>
<!-- FORM MODAL TAMBAH/UBAH Pendidikan -->
<div class='modal' id='modal-dPendidikan' tabindex='-1' role='dialog'>
    <div class='modal-dialog' role='document'>
        <div class='modal-content'>
            <form id='form-dPendidikan' enctype='multipart/form-data'>
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
                        <input type="text" class="form-control" name="nama" placeholder="" id="modal-dPendidikan-nama" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 control-label" style="text-align:left;">Tahun</label>
                        <div class="col-md-9">
                        <input type="text" class="form-control" name="tahun" placeholder="" id="modal-dPendidikan-tahun" required>
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label class="col-md-3 control-label" style="text-align:left;">Jurusan</label>
                        <div class="col-md-9">
                            <select  class="form-control selectize" id="modal-dPendidikan-jur" name="kode_jur" required>
                                <option value='' disabled>--- Pilih Jurusan ---</option>
                            </select>
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label class="col-md-3 control-label" style="text-align:left;">Jurusan</label>
                        <div class="col-md-9">
                            <select  class="form-control selectize" id="modal-dPendidikan-strata" name="kode_strata" required>
                                <option value='' disabled>--- Pilih Strata ---</option>
                            </select>
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label class="col-md-3 control-label" style="text-align:left;">Dokumen</label>
                        <div class="col-md-9">
                        <input name='file_gambar' type='file'>
                        </div>
                    </div> 
                </div>
                <div class="modal-footer">
                </div>
            </form>
        </div>
    </div>
</div>
<script>

function getStrata(){
    $.ajax({
        type: 'POST',
        url: '<?=$root_ser?>/Pendidikan.php?fx=getStrata',
        dataType: 'json',
        data: {'kode_lokasi':'<?php echo $kode_lokasi ?>'},
        success:function(result){    
            if(result.status){
                if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                    for(i=0;i<result.daftar.length;i++){
                        $('#modal-dPendidikan-strata')[0].selectize.addOption([{text:result.daftar[i].kode_strata + ' - ' + result.daftar[i].nama, value:result.daftar[i].kode_strata}]); 
                    }
                }
            }
        }
    });
}

function getJur(){
    $.ajax({
        type: 'POST',
        url: '<?=$root_ser?>/Pendidikan.php?fx=getJur',
        dataType: 'json',
        data: {'kode_lokasi':'<?php echo $kode_lokasi ?>'},
        success:function(result){    
            if(result.status){
                if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                    for(i=0;i<result.daftar.length;i++){
                        $('#modal-dPendidikan-jur')[0].selectize.addOption([{text:result.daftar[i].kode_jur + ' - ' + result.daftar[i].nama, value:result.daftar[i].kode_jur}]); 
                    }
                }
            }
        }
    });
}

$(document).ready(function(){

    var action_html = "<a href='#' title='Edit' class='sai-btn-circle aPendidikan_edit'><i class='fa fa-pencil-alt'></i></a> &nbsp; <a href='#' title='Hapus' class='tbl-delete sai-btn-circle aPendidikan_del'><i class='fa fa-trash'></i></a>";

    var kode_lokasi = '<?php echo $kode_lokasi ?>';
    var nik = '<?php echo $nik ?>';
    var dataTable = $('#table-pendidikan').DataTable({
        'processing': true,
        'serverSide': true,
        'ajax': {
            'url': '<?=$root_ser?>/Pendidikan.php?fx=getPendidikan',
            'data': {'kode_lokasi':kode_lokasi,'nik_user':nik},
            'type': 'GET',
            'dataSrc' : function(json) {
                return json.data;   
            }
        },
        'columnDefs': [
            {'targets': 6, data: null, 'defaultContent': action_html }
        ]
    });

// SHOW  FORM TAMBAH MODAL
    $('#aPendidikan').click(function(){
        // $('#modal-dPendidikan-nama').val('');
        // $('#modal-dPendidikan-tahun').val('');
        // $('#modal-dPendidikan-jur')[0].selectize.setValue('');
        // $('#modal-dPendidikan-strata')[0].selectize.setValue('');
        
        $('#form-dPendidikan')[0].reset();
        $('#modal-id').val('0');
        $('#header_modal').html("<i class='fa fa-plus'></i> Tambah Pendidikan");
        getStrata();
        getJur();
        $('#modal-dPendidikan').modal('show');
    });

//SIMPAN DATA
    $('#form-dPendidikan').submit(function(e){
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
            url: '<?=$root_ser?>/Pendidikan.php?fx=simpanPendidikan',
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
                    $('#modal-dPendidikan').modal('hide');
                }
            }
        });
        
    });

//SHOW EDIT FORM
    $('#table-pendidikan').on('click','.aPendidikan_edit',function(){
        
        getStrata();
        getJur();
        var kode = $(this).closest('tr').find('td:eq(1)').text();
        var lokasi = '<?php echo $kode_lokasi; ?>';
        var nik = '<?php echo $nik; ?>';
        var paths='<?php echo $path."/server/media/"; ?>';

        $.ajax({
            type: 'POST',
            url: '<?=$root_ser?>/Pendidikan.php?fx=getEditPendidikan',
            dataType: 'json',
            data: {'kode_lokasi':lokasi, 'kode':kode,'nik':nik},
            success:function(res){

                if(res.status){        
                    var line = res.daftar[0];
                    $('#modal-id').val('1');
                    $('#modal-dPendidikan-nama').val(line.nama);
                    $('#modal-dPendidikan-tahun').val(line.tahun);
                    $('#modal-dPendidikan-jur')[0].selectize.setValue(line.kode_jurusan);
                    $('#modal-dPendidikan-strata')[0].selectize.setValue(line.kode_strata);
                    $('#header_modal').html("<i class='fa fa-pencil'></i> Edit Pendidikan");
                    $('#modal-dPendidikan').modal('show');
                    
                }
            },
            fail: function(xhr, textStatus, errorThrown){
                alert('request failed:');
            }
        });
    });

//HAPUS DATA
    $('#table-pendidikan').on('click','.aPendidikan_del',function(){
        if(confirm('Apakah anda ingin menghapus data ini?')){
            var kode = $(this).closest('tr').find('td:eq(1)').text();        
            var kode_lokasi='<?php echo $kode_lokasi; ?>' ;
            var nik='<?php echo $nik; ?>' ;

            $.ajax({
                type: 'POST',
                url: '<?=$root_ser?>/Pendidikan.php?fx=hapusPendidikan',
                dataType: 'json',
                data: {'id':kode,'kode_lokasi':kode_lokasi,'nik':nik},
                success:function(result){
                    alert('Penghapusan data '+result.message);
                    if(result.status){
                        dataTable.ajax.reload();
                        $('#modal-dPendidikan').modal('hide');
                    }
                }
            });
        }else{
            return false;
        }
                    
   });

});
</script>


