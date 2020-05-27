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
<style>
#input-grid_wrapper{
  padding:0;
}
</style>
<div class="container-fluid mt-3">
  <div id='saiweb_container'>
    <div id='web_datatable'>
      <div class='row'>
        <div class='col-md-12'>
          <div class='card' style='border-top:none' >
              <div class='card-body'>
                <div class='row'>
                  <div class="col-md-6">
                    <h4 class="card-title mb-4"><i class='fas fa-cube'></i> Close Kasir 
                    </h4>
                  <hr>
                  </div>
                  <div class='col-md-6'>
                    <ul class="nav nav-tabs customtab float-right" role="tablist">
                        <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#sai-tab-finish" role="tab" aria-selected="true"><span class="hidden-xs-down">Finish</span></a> </li>
                        <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#sai-tab-new" role="tab" aria-selected="false"><span class="hidden-xs-down">New</span></a> </li>
                    </ul>
                  </div>
                  <div class='col-md-12'>
                    <div class="tab-content">
                      <div class="tab-pane active" id="sai-tab-new" style="position: relative;">
                          <div class='sai-container-overflow-x'>
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
                            <table class='table table-bordered table-striped DataTable' style='width: 100%;' id='table-new'>
                              <thead>
                                <tr>
                                  <td>No Open</td>
                                  <td>Nik Kasir</td>
                                  <td>Tgl Jam</td>
                                  <td>Saldo</td>
                                  <td>Action</td>
                                </tr>
                              </thead>
                              <tbody>
                              </tbody>
                            </table>
                          </div>
                      </div>
                      <div class="tab-pane" id="sai-tab-finish" style="position: relative;">
                          <div class='sai-container-overflow-x'>
                              <table class='table table-bordered table-striped DataTable' style='width: 100%;' id='table-finish'>
                                  <thead>
                                    <tr>
                                      <td>No Close</td>
                                      <td>Nik Kasir</td>
                                      <td>Tgl Jam</td>
                                      <td>Saldo Awal</td>
                                      <td>Total Penjualan</td>
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
              </div>
          </div>
        </div>
      </div>
    </div>
    <!-- FORM EDIT -->
    <form id='web_form_edit' >
      <div class='row'>
        <div class='col-md-12'>
          <div class='card'>
            <div class='card-body'>
              <!-- <div class='row'>
                <div class='col-md-1'>
                    <div class='logo text-center'><img src="<?=$folder_assets?>/images/sai_icon/logo.png" width="40px" alt="homepage" class="light-logo" /><br/>
                        <img src="<?=$folder_assets?>/images/sai_icon/logo-text.png" class="light-logo" alt="homepage" width="40px"/>
                    </div>
                </div>
                <div class='col-md-4'>
                  <div class='label-header'>
                      <h6><?=date('Y-m-d H:i:s')?></h6>
                      <h6 style="color:#007AFF"><i class='fa fa-user'></i> <span id='nik_kasir'></span></h6>
                  </div>
                </div>
                <div class='col-md-7'> -->
                  <button type='submit' class='btn btn-success float-right ml-1'><i class='fa fa-plus-circle'></i> Save</button>
                  <a class='btn btn-secondary float-right btn-form-exit web_form_back'><i class='fa fa-rotate-left'></i> Back</a>
                <!-- </div>
              </div> -->
            </div>
          </div>
          <div class='card box-warning'>
            <div class='card-body pad'>
              <div class='form-group row'>
                <label class='control-label col-2'>Tanggal Open</label>
                <div class="col-2">
                <input type='date' name='tgl' class='form-control' maxlength='200' readonly id='web_form_edit_tgl'> 
                </div>
              </div>
              <div class='form-group row'>
                <label class='control-label col-2'>No Open</label>
                <div class="col-2">
                <input type='text' name='no_open' class='form-control' maxlength='200' readonly id='web_form_edit_no_open'> 
                </div>
                <label class='control-label col-2'>Saldo Awal</label>
                <div class="col-2">
                <input type='text' name='saldo_awal' class='form-control currency' id='web_form_edit_saldo_awal' readonly>
                </div>
                <label class='control-label col-2'>Total Penjualan</label>
                <div class="col-2">
                <input type='text' name='total_pnj' class='form-control currency' id='web_form_edit_totpnj' readonly>       
                </div>
              </div>
              <div class='form-group row'>
                <label class='control-label col-2'>Total Diskon</label>
                <div class="col-2">
                <input type='text' name='total_disk' class='form-control currency' id='web_form_edit_totdisk' readonly>       
                </div>
                <label class='control-label col-2'>Total</label>
                <div class="col-2">
                <input type='text' name='total_all' class='form-control currency' id='web_form_edit_total_all' readonly>       
                </div>
                <label class='control-label col-2'>NIK Kasir</label>
                <div class="col-2">
                <input type='text' name='nik_user' class='form-control' id='web_form_edit_nik' readonly>       
                </div>
              </div>
              <div class='col-md-12' style='padding:0'>
                <h6>Detail Penjualan</h6>
              </div>
              <div class='col-md-12' style='padding:0'>
                <table class="table table-striped table-bordered table-condensed" style='width: 100%;' id="input-grid">
                  <thead>
                    <tr>
                      <th>No Jual</th>
                      <th>Tanggal</th>
                      <th>Keterangan</th>
                      <th>Periode</th>
                      <th>Total</th>
                      <th>Diskon</th>
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
    var action_html = "<a href='#' title='Edit' class='badge badge-info web_datatable_edit' ><i class='fas fa-pencil-alt'></i></a> &nbsp; <a href='#' title='Hapus' class='badge badge-danger web_datatable_del' ><i class='fa fa-trash'></i></a>";
    var kode_lokasi = '<?php echo $kode_lokasi ?>';
    var dataTable = $('#table-new').DataTable({
        'processing': true,
        'serverSide': true,
        'ajax': {
            'url': '<?=$root_ser?>/CloseKasir.php?fx=getNew',
            'data': {'kode_lokasi':kode_lokasi},
            'async':false,
            'type': 'POST',
            'dataSrc' : function(json) {
                return json.data;   
            }
        },
        'columnDefs': [
            {'targets': 4, data: null, 'defaultContent': action_html },
            {'targets': 3,
                'className': 'text-right',
                'render': $.fn.dataTable.render.number( '.', ',', 0, '' )
            }
        ]
    });

    var dataTable2 = $('#table-finish').DataTable({
        'processing': true,
        'serverSide': true,
        'ajax': {
            'url': '<?=$root_ser?>/CloseKasir.php?fx=getFinish',
            'data': {'kode_lokasi':kode_lokasi},
            'async':false,
            'type': 'POST',
            'dataSrc' : function(json) {
                return json.data;   
            }
        },
        'columnDefs': [
            {'targets': [3,4],
                'className': 'text-right',
                'render': $.fn.dataTable.render.number( '.', ',', 0, '' )
            }
        ]
    });

    var table = $('#input-grid').DataTable({
        data: [],
        columns: [
            { data: 'no_jual' },
            { data: 'tanggal' },
            { data: 'keterangan' },
            { data: 'periode' },
            { data: 'nilai' },
            { data: 'diskon' },
        ],
        'columnDefs': [
            {'targets': [4,5],
                'className': 'text-right',
                'render': $.fn.dataTable.render.number( '.', ',', 0, '' )
            },
            {'targets': [0],
                'className': 'text-right',
                'render': function (data, type, row) {
                    return data+"<input type='hidden' name='no_jual[]' value='" + data + "'>";
                }
            }
        ]

    });

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
        var nik=$(this).closest('tr').find('td:eq(1)').text(); ;
        var kode_lokasi='<?php echo $kode_lokasi; ?>' ;

        $.ajax({
          type: 'GET',
          url: '<?=$root_ser?>/CloseKasir.php?fx=getEditCloseKasir',
          dataType: 'json',
          data: {'kode':kode,'nik':nik,'kode_lokasi':kode_lokasi},
          success:function(res){
              if(res.status){
                  $('#web_form_edit_no_open').val(res.daftar[0].no_open);
                  $('#web_form_edit_tgl').val(res.daftar[0].tgl);
                  $('#web_form_edit_nik').val(res.daftar[0].nik);
                  $('#web_form_edit_saldo_awal').val(toRp(res.daftar[0].saldo_awal));  
                  $('#web_form_edit_totpnj').val(toRp(res.daftar[0].total_pnj));       
                  $('#web_form_edit_totdisk').val(toRp(res.daftar[0].total_disk));
                  var total = parseFloat(res.daftar[0].total_pnj)+parseFloat(res.daftar[0].saldo_awal);      
                  $('#web_form_edit_total_all').val(toRp(total));              
                  $('#web_datatable').hide();
                  $('#web_form_edit').show();
                  table.clear().draw();
                  table.rows.add(res.data).draw(false);
              }
          },
          fail: function(xhr, textStatus, errorThrown){
              alert('request failed:');
          }
      });

    });
    
    $('#saiweb_container').on('submit', '#web_form_edit', function(e){
      e.preventDefault();
      var formData = new FormData(this);
      
      for(var pair of formData.entries()) {
          console.log(pair[0]+ ', '+ pair[1]); 
      }
      
      var kode_lokasi='<?php echo $kode_lokasi; ?>' ;
      formData.append('kode_lokasi', kode_lokasi);
      
      $.ajax({
          type: 'POST',
          url: '<?=$root_ser?>/CloseKasir.php?fx=simpanCloseKasir',
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
                  dataTable2.ajax.reload();
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
                  url: '<?=$root_ser?>/CloseKasir.php?fx=hapusOpenKasir',
                  dataType: 'json',
                  data: {'id':kode,'kode_lokasi':kode_lokasi},
                  success:function(result){
                      alert('Penghapusan data '+result.message);
                      if(result.status){
                          // location.reload();
                          dataTable.ajax.reload();
                          dataTable2.ajax.reload();
                          
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

       
</script>