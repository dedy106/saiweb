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
            <h3 class="box-title"><i class="fa fa-inbox"></i> Data Dokumen</h3> 
              <button class='btn btn-primary pull-right web_datatable_insert' title='Tambah'>
                <i class='fa fa-plus-circle'></i> Tambah
              </button>
              <div class='pull-right' style='margin-right:5px;'>
                
              </div>
            </div>
            <div class='box-body sai-container-overflow-x'>
              <table class='table table-bordered table-striped DataTable' id='table-dok'>
                <thead>
                  <tr>
                    <td>Kode Dokumen</td>
                    <td>Nama</td>
                    <td>Kode Proses</td>
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
              <h3 class="box-title" ><i class="fa fa-inbox"></i> Input Data Dokumen</h3> 
              <button type='submit' class='btn btn-success pull-right'><i class='fa fa-plus-circle'></i> Save</button>
               <a class='btn btn-default pull-right btn-form-exit web_form_back'><i class='fa fa-rotate-left'></i> Back</a>
            </div>
          </div>
          <div class='box box-warning'>
            <div class='box-body pad'>
              <div class='row'>
                <div class='form-group'>
                  <label class='control-label col-sm-3'>Nama</label>
                    <div class='col-sm-9' style='margin-bottom:5px;'>
                      <input type='text' name='nama' id='nama' class='form-control' placeholder='Masukkan Nama Dokumen' >
                     </div>
                </div>
              </div>
              <div class='row'>
                <div class='form-group'>
                  <label class='control-label col-sm-3'>Kode Proses</label>
                    <div class='col-sm-9' style='margin-bottom:5px;'>
                      <select name='kode_proses' id='kode_proses' class='form-control selectize' >
                      <option value='' disabled>Pilih Kode Proses</option>
                      </select>
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
              <h3 class="box-title" ><i class="fa fa-edit"></i> Edit Data Dokumen</h3> 
              <button type='submit' class='btn btn-success pull-right'><i class='fa fa-plus-circle'></i> Save</button>
              <a class='btn btn-default pull-right btn-form-exit web_form_back'><i class='fa fa-rotate-left'></i> Back</a>
            </div>
          </div>
          <div class='box box-warning'>
            <div class='box-body pad'>
              <div class='row'>
                <div class='form-group'>
                  <label class='control-label col-sm-3'>Kode Dokumen</label>
                    <div class='col-sm-9' style='margin-bottom:5px;'>
                      <input type='text' id='web_form_edit_kode_dok' name='kode_dok' class='form-control' readonly>
                    </div>
                </div>
              </div>
              <div class='row'>
                <div class='form-group'>
                  <label class='control-label col-sm-3'>Nama</label>
                    <div class='col-sm-9' style='margin-bottom:5px;'>
                      <input type='text' id='web_form_edit_nama' placeholder='Masukkan Nama Dokumen' name='nama' class='form-control'>
                    </div>
                </div>
              </div>
              <div class='row'>
                <div class='form-group'>
                  <label class='control-label col-sm-3'>Kode Proses</label>
                    <div class='col-sm-9' style='margin-bottom:5px;'>
                      <select name='kode_proses' id='web_form_edit_kode_proses' class='form-control selectize' >
                      <option value='' disabled>Pilih Kode Proses</option>
                      </select>
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
function getProses(){
    $.ajax({
        type: 'GET',
        url: '<?=$root_ser?>/Dok.php?fx=getProses',
        dataType: 'json',
        data: {'token':$('meta[name="_token"]').attr('content')},
        success:function(result){    
            if(result.status){
                if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                    for(i=0;i<result.daftar.length;i++){
                        $('#kode_proses')[0].selectize.addOption([{text:result.daftar[i].kode_proses + ' - ' + result.daftar[i].nama, value:result.daftar[i].kode_proses}]);
                        $('#web_form_edit_kode_proses')[0].selectize.addOption([{text:result.daftar[i].kode_proses + ' - ' + result.daftar[i].nama, value:result.daftar[i].kode_proses}]);  
                    }
                }
            }else{
                alert(result.message);
            }
        }
    });
}
$(document).ready(function(){

var token = $('meta[name="_token"]').attr('content');
var action_html = "<a href='#' title='Edit' class='sai-btn-circle web_datatable_edit'><i class='fa fa-pencil'></i></a> &nbsp; <a href='#' title='Hapus' class='tbl-delete sai-btn-circle web_datatable_del'><i class='fa fa-trash'></i></a>";

var kode_lokasi = '<?php echo $kode_lokasi ?>';
var dataTable = $('#table-dok').DataTable({
    'processing': true,
    'serverSide': true,
    'ajax': {
        'url': '<?=$root_ser?>/Dok.php?fx=getDatatableDok',
        'data': {'token':token},
        'type': 'POST',
        'dataSrc' : function(json) {
            return json.data;   
        }
    },
    'columnDefs': [
        {'targets': 3, data: null, 'defaultContent': action_html }
    ]
});

getProses();

  $('#saiweb_container').on('click', '.web_datatable_insert', function(){
    $('#web_datatable').hide();
    $('#nama').val('');
    $('#kode_proses').val('');
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
        url: '<?=$root_ser?>/Dok.php?fx=getEditDok',
        dataType: 'json',
        data: {'kode_dok':kode,'token':token},
        success:function(res){
            if(res.status){
                $('#web_form_edit_kode_dok').val(res.daftar[0].kode_dok);
                $('#web_form_edit_nama').val(res.daftar[0].nama);
                $('#web_form_edit_kode_proses').val(res.daftar[0].kode_proses);

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
        url: '<?=$root_ser?>/Dok.php',
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
    
    formData = $(this).serialize();
    $.ajax({
        type: 'PUT',
        url: '<?=$root_ser?>/Dok.php',
        dataType: 'json',
        data: formData+"&token="+token,
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
                url: '<?=$root_ser?>/Dok.php',
                dataType: 'json',
                data: {'kode_dok':kode,'token':token},
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