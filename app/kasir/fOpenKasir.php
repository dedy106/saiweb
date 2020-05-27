<?php
     session_start();
     $root_lib=$_SERVER["DOCUMENT_ROOT"];
     if (substr($root_lib,-1)!="/") {
         $root_lib=$root_lib."/";
     }
     include_once($root_lib.'app/kasir/setting.php');
 
 
   $kode_lokasi=$_COOKIE['lokasi'];
   $nik=$_COOKIE['userLog'];
?>

<div class="container-fluid mt-3">
<div id='saiweb_container'>
   <div id='web_datatable'>
     <div class='row'>
       <div class='col-md-12'>
         <div class='card' >
           <div class='card-body pb-0'>
                <h4 class="card-title mb-4" style="position: absolute;"><i class='fas fa-cube'></i> Open Kasir 
                </h4>
             <button class='btn btn-primary float-right web_datatable_insert' title='Tambah'>
               <i class='fa fa-plus-circle'></i> Tambah
             </button>
           </div>
           <div class='card-body sai-container-overflow-x'>
           <style>
           th,td{
            padding:8px !important;
            vertical-align:middle !important;
          }
          .form-group{
            margin-bottom:15px !important;
          }
          
          .dataTables_wrapper{
            padding:5px
          }
          </style>
             <table class='table table-bordered table-striped DataTable' id='table-konten' style='width:100%'>
               <thead>
                 <tr>
                   <td>No Open</td>
                   <td>Nik Kasir</td>
                   <td>Tgl Jam</td>
                   <td>Saldo</td>
                   <td>No Close</td>
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
   
   <form id='web_form_insert'>
     <div class='row'>
       <div class='col-md-12'>
         <div class='card'>
           <div class='card-body'>
             <button type='submit' class='btn btn-success float-right ml-1'><i class='fa fa-plus-circle'></i> Save</button>
              <a class='btn btn-secondary float-right btn-form-exit web_form_back'><i class='fa fa-rotate-left'></i> Back</a>
           </div>
         </div>
         <div class='card box-warning'>
           <div class='card-body pad'>
              <div class='form-group row'>
                <label class='control-label col-3'>NIK</label>
                <div class="col-9">
                <input type='text' class='form-control'  name='nik' maxlength='200' value='<?php echo $nik; ?>'  readonly>
                </div>
             </div>
             <div class='form-group row'>
                <label class='control-label col-3'>Saldo Awal</label>
                <div class="col-9">
                <input type='text' id='inp-saldo_awal' name='saldo_awal' class='form-control currency '>
                </div>
             </div>
           </div>
         </div>
       </div>
     </div>
   </form>
   <!-- FORM EDIT -->
   <form id='web_form_edit'>
     <div class='row'>
       <div class='col-md-12'>
         <div class='card'>
           <div class='card-body'>
             <button type='submit' class='btn btn-success float-right'><i class='fa fa-plus-circle'></i> Save</button>
             <a class='btn btn-default float-right btn-form-exit web_form_back'><i class='fa fa-rotate-left'></i> Back</a>
           </div>
         </div>
         <div class='card box-warning'>
           <div class='card-body pad'>
              <div class='form-group row'>
                  <label class='control-label col-3'>No Open</label>
                  <div class="col-9">
                  <input type='text' name='no_open' class='form-control' maxlength='200' readonly id='web_form_edit_no_open'>
                  </div>
              </div>
              <div class='form-group row'>
                  <label class='control-label col-3'>Kasir</label>
                  <div class="col-9">
                  <input type='text' name='nik' class='form-control' maxlength='200' readonly id='web_form_edit_nik'>
                  </div>
              </div>
              <div class='form-group row'>
                  <label class='control-label col-3'>Saldo Awal</label>
                  <div class="col-9">
                  <input type='text' name='saldo_awal' class='form-control currency' id='web_form_edit_saldo_awal'>
                  </div>
              </div>
           </div>
         </div>
       </div>
     </div>
   </form>
</div>
</div>

<script src="<?=$folderroot_js?>/inputmask.js"></script>
<script>


function sepNum(x){
  var num = parseFloat(x).toFixed(0);
  var parts = num.toString().split(".");
  var len = num.toString().length;
  // parts[1] = parts[1]/(Math.pow(10, len));
  parts[0] = parts[0].replace(/(.)(?=(.{3})+$)/g,"$1.");
  return parts.join(",");
}

function toRp(num){
  if(num < 0){
    return "("+sepNum(num * -1)+")";
  }else{
    return sepNum(num);
  }
}

function toNilai(str_num){
  var parts = str_num.split('.');
  number = parts.join('');
  number = number.replace('Rp', '');
  // number = number.replace(',', '.');
  return +number;
}

$(document).ready(function(){

    var action_html = "<a href='#' title='Edit' class='sai-btn-circle web_datatable_edit'><i class='fa fa-pencil-alt fa-1'></i></a>";

    var kode_lokasi = '<?php echo $kode_lokasi ?>';
    var nik = '<?php echo $nik ?>';
    var dataTable = $('#table-konten').DataTable({
        'processing': true,
        'serverSide': true,
        'ajax': {
            'url': '<?=$root_ser?>/OpenKasir.php?fx=getOpenKasir',
            'data': {'kode_lokasi':kode_lokasi,'nik':nik},
            'type': 'POST',
            'dataSrc' : function(json) {
                return json.data;   
            }
        },
        'columnDefs': [
            {'targets': 5, data: null, 'defaultContent': action_html },
            {'targets': 3,
                'className': 'text-right',
                'render': $.fn.dataTable.render.number( '.', ',', 0, 'Rp. ' )
            }
        ]
    });

    $('#web_form_insert').hide();
    $('#web_form_edit').hide();

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
        var nik='<?php echo $nik; ?>' ;
        var kode_lokasi='<?php echo $kode_lokasi; ?>' ;

        $.ajax({
          type: 'POST',
          url: '<?=$root_ser?>/OpenKasir.php?fx=getEditOpenKasir',
          dataType: 'json',
          data: {'kode':kode,'nik':nik,'kode_lokasi':kode_lokasi},
          success:function(res){
              // alert('id ='+res.daftar[0].id);

              if(res.status){
                  $('#web_form_edit_no_open').val(res.daftar[0].no_open);
                  $('#web_form_edit_nik').val(res.daftar[0].nik);
                  $('#web_form_edit_saldo_awal').val(toRp(res.daftar[0].saldo_awal));               
                  $('#web_datatable').hide();
                  $('#web_form_edit').show();
              }
          },
          fail: function(xhr, textStatus, errorThrown){
              alert('request failed:');
          }
      });

    });

    $('#saiweb_container').on('submit', '#web_form_insert', function(e){
    e.preventDefault();

      var saldo = $('#inp-saldo_awal').val();

      if (saldo < 0){
          alert('Saldo awal tidak valid');
          return false;     
      }
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
          url: '<?=$root_ser?>/OpenKasir.php?fx=simpanOpenKasir',
          dataType: 'json',
          data: formData,
          contentType: false,
          cache: false,
          processData: false, 
          success:function(result){
              alert('Input data '+result.message);
              if(result.status){
                  // location.reload();
                  dataTable.ajax.reload();
                  $('#web_datatable').show();
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
          url: '<?=$root_ser?>/OpenKasir.php?fx=simpanOpenKasir',
          dataType: 'json',
          data: formData,
          contentType: false,
          cache: false,
          processData: false, 
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
              var kode_lokasi = '<?php echo $kode_lokasi; ?>';        
              
              $.ajax({
                  type: 'POST',
                  url: '<?=$root_ser?>/OpenKasir.php?fx=hapusOpenKasir',
                  dataType: 'json',
                  data: {'id':kode,'kode_lokasi':kode_lokasi},
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
        
      $(':input[type="number"], .currency').on('keydown', function (e){
            var value = String.fromCharCode(e.which) || e.key;

            if (    !/[0-9\.]/.test(value) //angka dan titik
                    && e.which != 190 // .
                    && e.which != 116 // F5
                    && e.which != 8   // backspace
                    && e.which != 9   // tab
                    && e.which != 13   // enter
                    && e.which != 46  // delete
                    && (e.which < 37 || e.which > 40) // arah 
                    && (e.keyCode < 96 || e.keyCode > 105) // dan angka dari numpad
                ){
                    e.preventDefault();
                    return false;
            }
        });

        $('.currency').inputmask("numeric", {
            radixPoint: ",",
            groupSeparator: ".",
            digits: 2,
            autoGroup: true,
            rightAlign: true,
            oncleared: function () { self.Value(''); }
        });

});
</script>