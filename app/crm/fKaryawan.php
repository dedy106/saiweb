<div id='saiweb_container'>
    <div id='web_datatable'>
      <div class='row'>
        <div class='col-xs-12'>
          <div class='box' >
            <div class='box-header'>
            <h3 class="box-title"><i class="fa fa-inbox"></i> Data Karyawan</h3> 
              <button class='btn btn-primary pull-right web_datatable_insert' title='Tambah'>
                <i class='fa fa-plus-circle'></i> Tambah
              </button>
              <div class='pull-right' style='margin-right:5px;'>
                
              </div>
            </div>
            <div class='box-body sai-container-overflow-x'>
              <table class='table table-bordered table-striped DataTable' id='table-kar'>
                <thead>
                  <tr>
                    <td>NIK</td>
                    <td>Nama</td>
                    <td>Action</td>
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
    <!-- FORM INSERT -->
    <form id='web_form_insert' hidden>
      <div class='row'>
        <div class='col-xs-12'>
          <div class='box'>
            <div class='box-header'>
              <h3 class="box-title" ><i class="fa fa-inbox"></i> Input Data Karyawan</h3> 
              <button type='submit' class='btn btn-success pull-right'><i class='fa fa-plus-circle'></i> Save</button>
               <a class='btn btn-default pull-right btn-form-exit web_form_back'><i class='fa fa-rotate-left'></i> Back</a>
            </div>
          </div>
          <div class='box box-warning'>
            <div class='box-body pad'> 
              <div class='row'>
                <div class='form-group'>
                  <label class='control-label col-sm-3'>NIK</label>
                    <div class='col-sm-9' style='margin-bottom:5px;'>
                      <input type='text' id="nik" name='nik' placeholder='Masukkan NIK Karyawan' class='form-control'>
                     </div>
                </div>
                <div class='form-group'>
                  <label class='control-label col-sm-3'>Nama</label>
                    <div class='col-sm-9' style='margin-bottom:5px;'>
                      <input type='text' id="nama" name='nama' placeholder='Masukkan Nama Karyawan' class='form-control'>
                     </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
    <!-- FORM EDIT -->
    <form id='web_form_edit' hidden>
      <div class='row'>
        <div class='col-xs-12'>
          <div class='box'>
            <div class='box-header'>
              <h3 class="box-title" ><i class="fa fa-edit"></i> Edit Data Karyawan</h3> 
              <button type='submit' class='btn btn-success pull-right'><i class='fa fa-plus-circle'></i> Save</button>
              <a class='btn btn-default pull-right btn-form-exit web_form_back'><i class='fa fa-rotate-left'></i> Back</a>
            </div>
          </div>
          <div class='box box-warning'>
            <div class='box-body pad'>
              <div class='row'>
                <div class='form-group'>
                  <label class='control-label col-sm-3'>NIK</label>
                    <div class='col-sm-9' style='margin-bottom:5px;'>
                      <input type='text' id='web_form_edit_nik' name='nik' class='form-control' readonly>
                    </div>
                </div>
              </div>
              <div class='row'>
                <div class='form-group'>
                  <label class='control-label col-sm-3'>Nama</label>
                    <div class='col-sm-9' style='margin-bottom:5px;'>
                      <input type='text' id='web_form_edit_nama' placeholder='Masukkan Nama Karyawan'  name='nama' class='form-control'>
                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
</div>

<script>
$(document).ready(function(){


  var action_html = "<a href='#' title='Edit' class='sai-btn-circle web_datatable_edit'><i class='fa fa-pencil'></i></a> &nbsp; <a href='#' title='Hapus' class='tbl-delete sai-btn-circle web_datatable_del'><i class='fa fa-trash'></i></a>";

  var dataTable = $('#table-kar').DataTable({
      'processing': true,
      'serverSide': true,
      'ajax': {
          'url': '<?=$root_ser?>/Karyawan.php?fx=getDatatableKaryawan',
          'data': {},
          'type': 'POST',
          'dataSrc' : function(json) {
              return json.data;   
          }
      },
      'columnDefs': [
          {'targets': 2, data: null, 'defaultContent': action_html }
      ]
  });

  $('#saiweb_container').on('click', '.web_datatable_insert', function(){
    $('#web_datatable').hide();
    $('#nama').val('');
    $('#nik').val('');
    $('#web_form_insert').show();
    // alert("hello");
  });

  $('#saiweb_container').on('click', '.web_form_back', function(){
     var id = $(this).closest('form').attr('id');
     $('#'+id).hide();
     $('#web_datatable').show();
  });

  $('#saiweb_container').on('click', '.web_datatable_edit', function(){
                    // getset value
     var kode = $(this).closest('tr').find('td:eq(0)').text();
     $.ajax({
        type: 'GET',
        url: '<?=$root_ser?>/Karyawan.php?fx=getEditKaryawan',
        dataType: 'json',
        data: {'nik':kode},
        success:function(res){
            if(res.status){
                $('#web_form_edit_nik').val(res.daftar[0].nik);
                $('#web_form_edit_nama').val(res.daftar[0].nama);

                $('#web_datatable').hide();
                $('#web_form_edit').show();
            }
        },
        fail: function(xhr, textStatus, errorThrown){
            alert('request failed:'+textStatus);
        }
    });
    
     
  });

  $('#saiweb_container').on('submit', '#web_form_insert', function(e){
  e.preventDefault();
    var formData = new FormData(this);
    for(var pair of formData.entries()) {
         console.log(pair[0]+ ', '+ pair[1]); 
        }
    $.ajax({
        type: 'POST',
        url: '<?=$root_ser?>/Karyawan.php',
        dataType: 'json',
        data: formData,
        contentType: false,
        cache: false,
        processData: false, 
        success:function(result){
            alert('Input data '+result.message);
            if(result.status){
                dataTable.ajax.reload();
                $('#web_datatable').show();
                $('#nama').val('');
                $('#web_form_insert').hide();
            }
        },
        fail: function(xhr, textStatus, errorThrown){
            alert('request failed:'+textStatus);
        }
    });
  });
  
  $('#saiweb_container').on('submit', '#web_form_edit', function(e){
    e.preventDefault();
    formData = $(this).serialize();
    $.ajax({
        type: 'PUT',
        url: '<?=$root_ser?>/Karyawan.php',
        dataType: 'json',
        data: formData,
        success:function(result){
            alert('Update data '+result.message);
            if(result.status){
                dataTable.ajax.reload();
                $('#web_datatable').show();
                $('#web_form_edit').hide();
            }
        }
    });
   });

   $('#saiweb_container').on('click', '.web_datatable_del', function(){
        if(confirm('Apakah anda ingin menghapus data ini?')){
            var kode = $(this).closest('tr').find('td:eq(0)').text();
            
            $.ajax({
                type: 'DELETE',
                url: '<?=$root_ser?>/Karyawan.php',
                dataType: 'json',
                data: {'nik':kode},
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

});
		
</script>