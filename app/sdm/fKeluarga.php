
<!-- TABEL KELUARGA -->
<div class='box-body sai-container-overflow-x'>
<table class='table table-bordered table-striped DataTable' id='table-keluarga'>
  <thead>
    <tr>
      <td>No</td>
      <td>Nama</td>
      <td>Status</td>
      <td>Jenis Kelamin</td>
      <td>Tanggungan</td>
      <td>Tempat Lahir</td>
      <td>Tgl Lahir</td>
      <td>Action</td>
    </tr>
  </thead>
  <tbody>
   
  </tbody>
</table>
</div>
<!-- FORM MODAL TAMBAH/UBAH Keluarga -->
<div class='modal' id='modal-dKeluarga' tabindex='-1' role='dialog'>
    <div class='modal-dialog' role='document'>
        <div class='modal-content'>
            <form id='form-dKeluarga' enctype='multipart/form-data'>
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
                        <input type="text" class="form-control" name="nama" placeholder="" id="modal-dKeluarga-nama" required>
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label class="col-md-3 control-label" style="text-align:left;">Status</label>
                        <div class="col-md-9">
                            <select  class="form-control selectize" id="modal-dKeluarga-jenis" name="jenis" required>
                                <option value=''>--- Pilih Status ---</option>
                                <option value='S'>S - Suami</option>
                                <option value='I'>I - Istri</option>
                                <option value='A'>A - Anak</option>
                            </select>
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label class="col-md-3 control-label" style="text-align:left;">Jenis Kelamin</label>
                        <div class="col-md-9">
                            <div class="radio">
                                <label>
                                <input type="radio" name="jk"  value="L" id="modal-dKeluarga-jk_l" required> L
                                </label>
                                &nbsp;&nbsp;&nbsp;
                                <label>
                                <input type="radio" name="jk" value="P" id="modal-dKeluarga-jk_p"> P
                                </label>
                            </div>
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label class="col-md-3 control-label" style="text-align:left;">Tanggungan</label>
                        <div class="col-md-9">
                            <div class="radio">
                                <label>
                                <input type="radio" name="tgg"  value="Y" id="modal-dKeluarga-tgg_y" required> Y
                                </label>
                                &nbsp;&nbsp;&nbsp;
                                <label>
                                <input type="radio" name="tgg" value="T" id="modal-dKeluarga-tgg_t"> T
                                </label>
                            </div>
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label class="col-md-3 control-label" style="text-align:left;">Tempat Lahir</label>
                        <div class="col-md-9">
                        <input type="text" class="form-control" name="tempat" placeholder="" id="modal-dKeluarga-tempat" required>
                        </div>
                    </div>  
                    <div class="form-group row">
                        <label class="col-md-3 control-label" style="text-align:left;">Tgl Lahir</label>
                        <div class="col-md-9">
                        <input type="date" class="form-control" name="tgl_lahir" placeholder="" id="modal-dKeluarga-tgl_lahir" required>
                        </div>
                    </div>           
                </div>
            </form>
        </div>
    </div>
</div>
<script>
// VIEW TABLE

$(document).ready(function(){

var action_html = "<a href='#' title='Edit' class='sai-btn-circle aKeluarga_edit'><i class='fa fa-pencil-alt'></i></a> &nbsp; <a href='#' title='Hapus' class='tbl-delete sai-btn-circle aKeluarga_del'><i class='fa fa-trash'></i></a>";

var kode_lokasi = '<?php echo $kode_lokasi ?>';
var nik = '<?php echo $nik ?>';
var dataTable = $('#table-keluarga').DataTable({
    'processing': true,
    'serverSide': true,
    'ajax': {
        'url': '<?=$root_ser?>/Keluarga.php?fx=getKeluarga',
        'data': {'kode_lokasi':kode_lokasi,'nik_user':nik},
        'type': 'POST',
        'dataSrc' : function(json) {
            return json.data;   
        }
    },
    'columnDefs': [
        {'targets': 7, data: null, 'defaultContent': action_html }
    ]
});


// SHOW  FORM TAMBAH MODAL
    $('#aKeluarga').click(function(){
        // $('#modal-dKeluarga-nama').val('');
        // $('#modal-dKeluarga-jenis')[0].selectize.setValue('');
        // $('#modal-dKeluarga-jk').val('');
        // $('#modal-dKeluarga-tgg').val('');
        // $('#modal-dKeluarga-tgl_lahir').val('');
        // $('#modal-dKeluarga-tempat').val('');
        
        $('#form-dKeluarga')[0].reset();
        $('#modal-id').val('0');
        $('#header_modal').html("<i class='fa fa-plus'></i> Tambah Keluarga");
        $('#modal-dKeluarga').modal('show');
    });

//SIMPAN DATA
    $('#form-dKeluarga').submit(function(e){
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
            url: '<?=$root_ser?>/Keluarga.php?fx=simpanKeluarga',
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
                    $('#modal-dKeluarga').modal('hide');
                }
            }
        });
        
    });

//SHOW EDIT FORM
    $('#table-keluarga').on('click','.aKeluarga_edit',function(){
        
        var kode = $(this).closest('tr').find('td:eq(1)').text();
        var lokasi = '<?php echo $kode_lokasi; ?>';
        var nik = '<?php echo $nik; ?>';
        var paths='<?php echo $path."/server/media/"; ?>';

        $.ajax({
            type: 'POST',
            url: '<?=$root_ser?>/Keluarga.php?fx=getEditKeluarga',
            dataType: 'json',
            data: {'kode_lokasi':lokasi, 'kode':kode,'nik':nik},
            success:function(res){

                if(res.status){        
                    var line = res.daftar[0];
                    $('#modal-id').val('1');
                    $('#modal-dKeluarga-nama').val(line.nama);
                    $('#modal-dKeluarga-jenis')[0].selectize.setValue(line.jenis);
                    if(line.jk == "L"){
                        $('#modal-dKeluarga-jk_l').prop('checked', true);
                    }else{
                        $('#modal-dKeluarga-jk_p').prop('checked', true);
                    }

                    if(line.status_kes == "Y"){
                        $('#modal-dKeluarga-tgg_y').prop('checked', true);
                    }else{
                        $('#modal-dKeluarga-tgg_t').prop('checked', true);
                    }
                    $('#modal-dKeluarga-tgl_lahir').val(line.tgl);
                    $('#modal-dKeluarga-tempat').val(line.tempat);
                    $('#header_modal').html("<i class='fa fa-pencil'></i> Edit Keluarga");
                    $('#modal-dKeluarga').modal('show');
                    
                }
            },
            fail: function(xhr, textStatus, errorThrown){
                alert('request failed:');
            }
        });
    });

//HAPUS DATA
    $('#table-keluarga').on('click','.aKeluarga_del',function(){
        if(confirm('Apakah anda ingin menghapus data ini?')){
            var kode = $(this).closest('tr').find('td:eq(1)').text();        
            var kode_lokasi='<?php echo $kode_lokasi; ?>' ;
            var nik='<?php echo $nik; ?>' ;

            $.ajax({
                type: 'POST',
                url: '<?=$root_ser?>/Keluarga.php?fx=hapusKeluarga',
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


