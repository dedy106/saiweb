<?php

    $kode_lokasi=$_SESSION['lokasi'];
    $kode_pp=$_SESSION['kodePP'];
    $api_key=$_SESSION['api_key'];
    $nik=$_SESSION['userLog'];

?>
<div id='saiweb_container'>
    <div id='web_datatable'>
      <div class='row'>
        <div class='col-xs-12'>
          <div class='box' >
            <div class='box-header'>
            <h3 class="box-title"><i class="fa fa-inbox"></i> Data Customer</h3> 
              <button class='btn btn-primary pull-right web_datatable_insert' title='Tambah'>
                <i class='fa fa-plus-circle'></i> Tambah
              </button>
              <div class='pull-right' style='margin-right:5px;'>
                
              </div>
            </div>
            <div class='box-body sai-container-overflow-x'>
              <table class='table table-bordered table-striped DataTable' id='table-cust'>
                <thead>
                  <tr>
                    <td>Kode Cust</td>
                    <td>Nama</td>
                    <td>Alamat</td>
                    <td>PIC</td>
                    <td>No Telp PIC</td>
                    <td>Email PIC</td>
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
              <h3 class="box-title" ><i class="fa fa-inbox"></i> Input Data Customer</h3> 
              <button type='submit' class='btn btn-success pull-right'><i class='fa fa-plus-circle'></i> Save</button>
               <a class='btn btn-default pull-right btn-form-exit web_form_back'><i class='fa fa-rotate-left'></i> Back</a>
            </div>
          </div>
          <div class='box box-warning'>
            <div class='box-body pad'>
              <div class='row'>
                <div class='form-group'>
                  <label class='control-label col-sm-3'>Nama Perusahaan</label>
                    <div class='col-sm-9' style='margin-bottom:5px;'>
                      <input type='text' name='nama' class='form-control' placeholder='Masukkan Nama Customer' >
                     </div>
                </div>
              </div>
              <div class='row'>
                <div class='form-group'>
                  <label class='control-label col-sm-3'>Alamat Perusahaan</label>
                    <div class='col-sm-9' style='margin-bottom:5px;'>
                      <input type='text' name='alamat' class='form-control' placeholder='Masukkan Alamat Customer' >
                     </div>
                </div>
              </div>
              <div class='row'>
                <div class='form-group'>
                  <label class='control-label col-sm-3'>Nama PIC</label>
                    <div class='col-sm-9' style='margin-bottom:5px;'>
                      <input type='text' name='pic' class='form-control' placeholder='Masukkan Nama PIC' >
                     </div>
                </div>
              </div>
              <div class='row'>
                <div class='form-group'>
                  <label class='control-label col-sm-3'>No Telp PIC</label>
                    <div class='col-sm-9' style='margin-bottom:5px;'>
                      <input type='text' name='no_telp' class='form-control' placeholder='Masukkan No Telp' >
                     </div>
                </div>
              </div>
              <div class='row'>
                <div class='form-group'>
                  <label class='control-label col-sm-3'>Email PIC</label>
                    <div class='col-sm-9' style='margin-bottom:5px;'>
                      <input type='text' name='email' class='form-control' placeholder='Masukkan Email' >
                     </div>
                </div>
              </div>
              <div class='row'>
                <div class='form-group'>
                  <label class='control-label col-sm-3'>Gambar</label>
                  <div class='col-sm-9' style='margin-bottom:5px;'>
                  <input name='file_gambar' type='file' accept='image/png, image/jpg, image/jpeg'>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
    <!-- FORM EDIT -->
    <form id='web_form_edit' hidden >
      <div class='row'>
        <div class='col-xs-12'>
          <div class='box'>
            <div class='box-header'>
              <h3 class="box-title" ><i class="fa fa-edit"></i> Edit Data Customer</h3> 
              <button type='submit' class='btn btn-success pull-right'><i class='fa fa-plus-circle'></i> Save</button>
              <a class='btn btn-default pull-right btn-form-exit web_form_back'><i class='fa fa-rotate-left'></i> Back</a>
            </div>
          </div>
          <div class='box box-warning'>
            <div class='box-body pad'>
              <div class='row'>
                <div class='form-group'>
                <label class='control-label col-sm-3'>Preview</label>
                  <div class='col-sm-9' style='margin-bottom:5px;' id='preview'>
                  </div>
                </div>
              </div>
              <div class='row'>
                <div class='form-group'>
                  <label class='control-label col-sm-3'>Kode Cust</label>
                    <div class='col-sm-9' style='margin-bottom:5px;'>
                      <input type='text' id='web_form_edit_kode_cust' name='kode_cust' class='form-control' readonly>
                    </div>
                </div>
              </div>
              <div class='row'>
                <div class='form-group'>
                  <label class='control-label col-sm-3'>Nama</label>
                    <div class='col-sm-9' style='margin-bottom:5px;'>
                      <input type='text' id='web_form_edit_nama' placeholder='Masukkan Nama Customer' name='nama' class='form-control'>
                    </div>
                </div>
              </div>
              <div class='row'>
                <div class='form-group'>
                  <label class='control-label col-sm-3'>Alamat Perusahaan</label>
                    <div class='col-sm-9' style='margin-bottom:5px;'>
                      <input type='text' id='web_form_edit_alamat'  name='alamat' class='form-control' placeholder='Masukkan Alamat Customer' >
                     </div>
                </div>
              </div>
              <div class='row'>
                <div class='form-group'>
                  <label class='control-label col-sm-3'>Nama PIC</label>
                    <div class='col-sm-9' style='margin-bottom:5px;'>
                      <input type='text' id='web_form_edit_pic' name='pic' class='form-control' placeholder='Masukkan Nama PIC' >
                     </div>
                </div>
              </div>
              <div class='row'>
                <div class='form-group'>
                  <label class='control-label col-sm-3'>No Telp PIC</label>
                    <div class='col-sm-9' style='margin-bottom:5px;'>
                      <input type='text' id='web_form_edit_no_telp' name='no_telp' class='form-control' placeholder='Masukkan No Telp' >
                     </div>
                </div>
              </div>
              <div class='row'>
                <div class='form-group'>
                  <label class='control-label col-sm-3'>Email PIC</label>
                    <div class='col-sm-9' style='margin-bottom:5px;'>
                      <input type='text' name='email' id='web_form_edit_email' class='form-control' placeholder='Masukkan Email' >
                     </div>
                </div>
              </div>
              <div class='row'>
                    <div class='form-group'>
                        <label class='control-label col-sm-3'>Gambar</label>
                        <div class='col-sm-9' style='margin-bottom:5px;'>
                            <input name='file_gambar' type='file' accept='image/png, image/jpg, image/jpeg' id='web_form_edit_file'>
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

var token = $('meta[name="_token"]').attr('content');
var action_html = "<a href='#' title='Edit' class='sai-btn-circle web_datatable_edit'><i class='fa fa-pencil'></i></a> &nbsp; <a href='#' title='Hapus' class='tbl-delete sai-btn-circle web_datatable_del'><i class='fa fa-trash'></i></a>";

var kode_lokasi = '<?php echo $kode_lokasi ?>';
var dataTable = $('#table-cust').DataTable({
    'processing': true,
    'serverSide': true,
    'ajax': {
        'url': '<?=$root_ser?>/Cust.php?fx=getDatatableCust',
        'data': {'token':token},
        'type': 'POST',
        'dataSrc' : function(json) {
            return json.data;   
        }
    },
    'columnDefs': [
        {'targets': 6, data: null, 'defaultContent': action_html }
    ]
});

  $('#saiweb_container').on('click', '.web_datatable_insert', function(){
    $('#web_datatable').hide();
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
        url: '<?=$root_ser?>/Cust.php?fx=getEditCust',
        dataType: 'json',
        data: {'kode_cust':kode,'token':token},
        success:function(res){
            if(res.status){
                var path_file='<?php echo $root_img; ?>'+res.daftar[0].gambar;

                if(res.daftar[0].gambar == "-"){
                    $('#preview').html("");
                }else{
                    $('#preview').html("<img src='"+path_file+"' style='width:25%; height:25%; min-width:200px; min-height:200px; display:block; margin-left: auto; margin-right: auto;'><br><br><center><b>Url:</b> <i>"+path_file+"</i></center>");
                }       
                $('#web_form_edit_kode_cust').val(res.daftar[0].kode_cust);
                $('#web_form_edit_nama').val(res.daftar[0].nama);
                $('#web_form_edit_alamat').val(res.daftar[0].alamat);
                $('#web_form_edit_pic').val(res.daftar[0].pic);
                $('#web_form_edit_no_telp').val(res.daftar[0].no_telp);
                $('#web_form_edit_email').val(res.daftar[0].email);

                $('#web_datatable').hide();
                $('#web_form_edit').show();
            }else{
                alert(res.message);
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
    formData.append('token',token);
    $.ajax({
        type: 'POST',
        url: '<?=$root_ser?>/Cust.php',
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
                $('#web_form_insert').hide();
            }else{
                alert(res.message);
            }
        },
        fail: function(xhr, textStatus, errorThrown){
            alert('request failed:'+textStatus);
        }
    });
  });
  
  $('#saiweb_container').on('submit', '#web_form_edit', function(e){
    e.preventDefault();
    
    // formData = $(this).serialize();
    var formData = new FormData(this);
    for(var pair of formData.entries()) {
         console.log(pair[0]+ ', '+ pair[1]); 
        }
    
    formData.append('token',token);
    $.ajax({
        type: 'POST',
        url: '<?php echo $root_ser?>/Cust.php?fx=updateCust',
        dataType: 'json',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success:function(result){
            alert('Update data '+result.message);
            if(result.status){
                dataTable.ajax.reload();
                $('#web_datatable').show();
                $('#web_form_edit').hide();
            }else{
                alert(res.message);
            }
        }
    });
   });

   $('#saiweb_container').on('click', '.web_datatable_del', function(){
        if(confirm('Apakah anda ingin menghapus data ini?')){
            var kode = $(this).closest('tr').find('td:eq(0)').text();
            $.ajax({
                type: 'DELETE',
                url: '<?=$root_ser?>/Cust.php',
                dataType: 'json',
                data: {'kode_cust':kode,'token':token},
                success:function(result){
                    alert('Penghapusan data '+result.message);
                    if(result.status){
                        dataTable.ajax.reload();
                    }else{
                        alert(res.message);
                    }
                }
            });
        }else{
            return false;
        }
                    
   });

});
		
</script>