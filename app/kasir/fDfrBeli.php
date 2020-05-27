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
           <div class='card-header'>
           <h3 class="card-title" style="position:absolute">Daftar Pembelian</h3> 
           </div>
           <div class='card-body sai-container-overflow-x'>
             <table class='table table-bordered table-striped DataTable' id='table-konten'>
               <thead>
                 <tr>
                   <td>No Pembelian</td>
                   <td>Nik Kasir</td>
                   <td>Tanggal</td>
                   <td>Kode Vendor</td>
                   <td>Total</td>
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
   <form id='web_form_edit' >
      <div class='row'>
        <div class='col-md-12'>
          <div class='card'>
            <div class='card-body'>
              <div class='row'>
                <div class='col-md-1'>
                    <div class='logo text-center'><img src="<?=$folder_assets?>/images/sai_icon/logo.png" width="40px" alt="homepage" class="light-logo" /><br/>
                        <img src="<?=$folder_assets?>/images/sai_icon/logo-text.png" class="light-logo" alt="homepage" width="40px"/>
                    </div>
                </div>
                <div class='col-md-4'>
                  <div class='label-header'>
                      <h6><?=date('Y-m-d H:i:s')?></h6>
                      <h6 style="color:#007AFF"><i class='fa fa-user'></i> <?=$nik?></h6>
                  </div>
                </div>
                <div class='col-md-7'>
                  <button type='button' id='btn-delete' class='btn btn-success float-right ml-1'><i class='fa fa-trash'></i> Delete</button>
                  <a class='btn btn-secondary float-right btn-form-exit web_form_back'><i class='fa fa-rotate-left'></i> Back</a>
                </div>
              </div>
            </div>
          </div>
          <div class='card box-warning'>
            <div class='card-body pad'>
              <div class='form-group row'>
                <label class='control-label col-2'>No Pembelian</label>
                <div class="col-2">
                <input type='text' name='no_beli' class='form-control' maxlength='200' readonly id='web_form_edit_no_beli'> 
                </div>
                <label class='control-label col-2'>NIK Kasir</label>
                <div class="col-2">
                <input type='text' name='nik_kasir' class='form-control' id='web_form_edit_nik' readonly>
                </div>
                <label class='control-label col-2'>Vendor</label>
                <div class="col-2">
                <input type='text' name='kode_vendor' class='form-control' id='web_form_edit_vendor' readonly>       
                </div>
              </div>
              <div class='form-group row'>
                
                <label class='control-label col-2'>Total</label>
                <div class="col-2">
                <input type='text' name='total_all' class='form-control currency' id='web_form_edit_total_all' readonly>       
                </div>
                <label class='control-label col-2'>Total Diskon</label>
                <div class="col-2">
                    <input type='text' name='total_disk' class='form-control currency' id='web_form_edit_totdisk' readonly>       
                </div>
                <label class='control-label col-2'>Total PPN</label>
                <div class="col-2">
                    <input type='text' name='total_ppn' class='form-control currency' id='web_form_edit_totppn' readonly>       
                </div>
              </div>
              <div class='col-md-12' style='padding:0'>
                <h6>Detail Pembelian</h6>
              </div>
              <div class='col-md-12' style='padding:0'>
                <table class="table table-striped table-bordered table-condensed" style='width: 100%;' id="input-grid">
                <style>
                    td,th{
                        padding:8px !important;
                        vertical-align:middle !important;
                    }
                </style>
                  <thead>
                    <tr>
                      <th>Barang</th>
                      <th>Harga Beli</th>
                      <th>Satuan</th>
                      <th>Qty</th>
                      <th>Diskon</th>
                      <th>Sub Total</th>
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

    var action_html = "<a href='#' title='Edit' class='badge badge-warning web_datatable_edit'><i class='fas fa-pencil-alt'></i></a> &nbsp; <a href='#' title='Hapus' class='badge badge-danger web_datatable_del'><i class='fa fa-trash'></i></a>&nbsp;";

    var kode_lokasi = '<?php echo $kode_lokasi ?>';
    var nik = '<?php echo $nik ?>';
    var dataTable = $('#table-konten').DataTable({
        'processing': true,
        'serverSide': true,
        'ajax': {
            'url': '<?=$root_ser?>/Pembelian.php?fx=getDaftar',
            'data': {'kode_lokasi':kode_lokasi,'nik':nik},
            'type': 'POST',
            'dataSrc' : function(json) {
                return json.data;   
            }
        },
        'columnDefs': [
            {'targets': 5, data: null, 'defaultContent': action_html },
            {'targets': 4,
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
          type: 'GET',
          url: '<?=$root_ser?>/Pembelian.php?fx=getEdit',
          dataType: 'json',
          data: {'no_bukti':kode,'nik':nik,'kode_lokasi':kode_lokasi},
          success:function(res){
              // alert('id ='+res.daftar[0].id);

              if(res.status){
                  $('#web_form_edit_no_beli').val(res.daftar[0].no_bukti);
                  $('#web_form_edit_nik').val(res.daftar[0].nik_user);
                  $('#web_form_edit_total_all').val(toRp(res.daftar[0].total));    
                  $('#web_form_edit_totdisk').val(toRp(res.daftar[0].diskon));    
                  $('#web_form_edit_totppn').val(toRp(res.daftar[0].ppn));    
                  $('#web_form_edit_vendor').val(res.daftar[0].kode_vendor);

                    var input="";
                    var no=1;
                    var jum =0; var disk=0; var sub=0;
                    if(res.daftar2.length>0){
                        for(var x=0;x<res.daftar2.length;x++){
                            var line = res.daftar2[x];
                            jum+=+line.jumlah;
                            disk+=+line.diskon;
                            sub+=+line.subtotal;
                            input += "<tr class='row-barang'>";
                            input += "<td width='30%'>"+line.nama+"</td>";
                            input += "<td width='15%' style='text-align:right'>"+toRp(line.harga)+"</td>";
                            input += "<td width='5%' style='text-align:right'>"+line.satuan+"</td>";
                            input += "<td width='15%' style='text-align:right'>"+toRp(line.jumlah)+"</td>";
                            input += "<td width='10%' style='text-align:right'>"+toRp(line.diskon)+"</td>";
                            input += "<td width='15%' style='text-align:right'>"+toRp(line.subtotal)+"</td>";
                            input += "</tr>";
                            
                            no++;
                        }
                    }
                    input += "<tr class='row-barang' style='font-weight:bold'>";
                    input += "<td width='30%'>Total</td>";
                    input += "<td width='15%' style='text-align:right'>&nbsp;</td>";
                    input += "<td width='5%' style='text-align:right'>&nbsp;</td>";
                    input += "<td width='15%' style='text-align:right'>"+toRp(jum)+"</td>";
                    input += "<td width='10%' style='text-align:right'>"+toRp(disk)+"</td>";
                    input += "<td width='15%' style='text-align:right'>"+toRp(sub)+"</td>";
                    input += "</tr>";

                 $('#input-grid tbody').html(input);

                  $('#web_datatable').hide();
                  $('#web_form_edit').show();
              }
          },
          fail: function(xhr, textStatus, errorThrown){
              alert('request failed:');
          }
      });

    });


      $('#saiweb_container').on('click', '.web_datatable_del', function(){
          if(confirm('Apakah anda ingin menghapus data ini?')){
              var kode = $(this).closest('tr').find('td:eq(0)').text(); 
              var kode_lokasi = '<?php echo $kode_lokasi; ?>'; 
              var nik = '<?php echo $nik; ?>';         
              
              $.ajax({
                  type: 'POST',
                  url: '<?=$root_ser?>/Pembelian.php?fx=hapusBeli',
                  dataType: 'json',
                  data: {'id':kode,'kode_lokasi':kode_lokasi,'nik':nik},
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

      $('#web_form_edit').on('click', '#btn-delete', function(){
          if(confirm('Apakah anda ingin menghapus data ini?')){
              var kode = $('#web_form_edit_no_beli').val(); 
              var kode_lokasi = '<?php echo $kode_lokasi; ?>';    
              var nik = '<?php echo $nik; ?>';        
              
              $.ajax({
                  type: 'POST',
                  url: '<?=$root_ser?>/Pembelian.php?fx=hapusBeli',
                  dataType: 'json',
                  data: {'id':kode,'kode_lokasi':kode_lokasi,'nik':nik},
                  success:function(result){
                      alert('Penghapusan data '+result.message);
                      if(result.status){
                          dataTable.ajax.reload();
                          $('#web_datatable').show();
                          $('#web_form_edit').hide();
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