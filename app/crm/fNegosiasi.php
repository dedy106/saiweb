<div id='saiweb_container'>
    <div id='web_datatable'>
        <div class="row">
            <div class="col-md-12">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs pull-right">
                        <li class="active"><a href="#sai-tab-new" data-toggle='tab'><i class="fa fa-inbox"></i> New</a></li>
                        <li class=""><a href="#sai-tab-keep" data-toggle='tab'><i class="fa fa-inbox"></i> Keep</a></li>
                        <li class=""><a href="#sai-tab-finish" data-toggle='tab'><i class="fa fa-check-circle"></i> Finish</a></li>
                        <li class="pull-left header"><i class="fa fa-inbox"></i> Data Negosiasi</li>        
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="sai-tab-new" style="position: relative;">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="box" style="border-top:none;">
                                        <div class="box-header">
                                            
                                        </div>
                                        <div class="box-body sai-container-overflow-x">
                                            <table class='table table-bordered' id='table-Negosiasi' style='width:100%'>
                                                <thead>
                                                    <th>No Bukti</th>
                                                    <th>Tanggal</th>
                                                    <th>Keterangan</th>
                                                    <th>Customer</th>
                                                    <th>Produk</th>
                                                    <th>Karyawan</th>
                                                    <th>Investasi</th>
                                                    <th>Margin</th>
                                                    <th>Nilai</th>
                                                    <th>Action</th>
                                                </thead>
                                                <tbody>
                                                <tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>                
                        </div>
                        <div class="tab-pane" id="sai-tab-keep" style="position: relative;">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="box" style="border-top:none;">
                                        <div class="box-header">
                                            
                                        </div>
                                        <div class="box-body sai-container-overflow-x">
                                            <table class='table table-bordered' id='table-Negosiasi-keep' style='width:100%'>
                                                <thead>
                                                    <th>No Bukti</th>
                                                    <th>No Ref</th>
                                                    <th>Tanggal</th>
                                                    <th>Keterangan</th>
                                                    <th>Customer</th>
                                                    <th>Produk</th>
                                                    <th>Karyawan</th>
                                                    <th>Investasi</th>
                                                    <th>Margin</th>
                                                    <th>Nilai</th>
                                                    <th>Action</th>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>                
                        </div>
                        <div class="tab-pane" id="sai-tab-finish" style="position: relative;">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="box" style="border-top:none;">
                                        <div class="box-header">
                                            
                                        </div>
                                        <div class="box-body sai-container-overflow-x">
                                            <table class='table table-bordered' id='table-Negosiasi-finish' style='width:100%'>
                                                <thead>
                                                    <th>No Bukti</th>
                                                    <th>No Ref</th>
                                                    <th>Tanggal</th>
                                                    <th>Keterangan</th>
                                                    <th>Customer</th>
                                                    <th>Produk</th>
                                                    <th>Karyawan</th>
                                                    <th>Investasi</th>
                                                    <th>Margin</th>
                                                    <th>Nilai</th>
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
    <!-- FORM INSERT -->
    <form id='web_form_insert' hidden enctype="multipart/form-data">
      <div class='row'>
        <div class='col-xs-12'>
          <div class='box'>
            <div class='box-header'>
              <h3 class="box-title" ><i class="fa fa-inbox"></i> Input Negosiasi</h3> 
              <button type='submit' class='btn btn-success pull-right'><i class='fa fa-plus-circle'></i> Save</button>
               <a class='btn btn-default pull-right btn-form-exit web_form_back'><i class='fa fa-rotate-left'></i> Back</a>
            </div>
          </div>
          <div class='box box-warning'>
            <div class='box-body pad'> 
              <div class='row'>
                <div class='form-group' >
                    <div class='col-sm-9' style='margin-bottom:5px;'>
                      <input type='hidden' name='no_bukti' id='no_bukti' class='form-control input-form' >
                     </div>
                </div>
              </div>
              <div class='row'>
                <div class='form-group' style='margin-bottom:5px;'>
                    <label class='control-label col-sm-3'>Tanggal</label>
                    <div class='input-group date col-sm-9' style='padding-right:15px; padding-left:15px;'>
                    <div class='input-group-addon'>
                    <i class='fa fa-calendar'></i>
                    </div>
                    <input name='tanggal' class='form-control datepicker-dmy' id='tanggal' value='<?php echo date('d-m-Y') ?>' required>
                    </div>
                </div>
              </div>
              <div class='row'>
                <div class='form-group' style='margin-bottom:5px;'>
                  <label class='control-label col-sm-3'>Karyawan</label>
                    <div class='col-sm-9' >
                      <select id='nik' name='nik' class='form-control input-form selectize' required>
                      <option value='' disabled>Pilih Karyawan</option>
                      </select>
                     </div>
                </div>
              </div>
              <div class='row'>
                <div class='form-group'>
                  <label class='control-label col-sm-3'>No Ref</label>
                    <div class='col-sm-9' style='margin-bottom:5px;'>
                      <input type='text' name='no_ref' id='no_ref' readonly class='form-control input-form' required>
                     </div>
                </div>
              </div>
              <div class='row'>
                <div class='form-group'>
                  <label class='control-label col-sm-3'>Investasi/HPP</label>
                    <div class='col-sm-4' style='margin-bottom:5px;'>
                      <input type='text' name='inves' id='inves' placeholder='Masukkan Nilai Investasi' class='form-control currency input-form' required>
                     </div>
                     <label class='control-label col-sm-2'>Margin</label>
                    <div class='col-sm-3' style='margin-bottom:5px;'>
                      <input type='text' name='margin' id='margin' placeholder='Masukkan Margin' class='form-control currency input-form' required>
                     </div>
                </div>
              </div>
              <div class='row'>
                <div class='form-group'>
                  <label class='control-label col-sm-3'>Nilai</label>
                    <div class='col-sm-9' style='margin-bottom:5px;'>
                      <input type='text' name='nilai' id='nilai' placeholder='Masukkan Nilai' class='form-control currency input-form' required readonly>
                     </div>
                </div>
              </div>
              <div class='row'>
                <div class='form-group' >
                  <label class='control-label col-sm-3'>Keterangan</label>
                    <div class='col-sm-9' style='margin-bottom:5px;'>
                      <input type='text' name='keterangan' id='keterangan' placeholder='Masukkan Keterangan' class='form-control input-form' required>
                     </div>
                </div>
              </div>
              <div class='row'>
                <div class='form-group'>
                  <label class='control-label col-sm-3'>Status</label>
                    <div class='col-sm-9' >
                      <select id='status' name='status' class='form-control input-form selectize' required>
                      <option value=''>Pilih Status</option>
                      <option value='0' >0 - Keep</option>
                      <option value='1' >1 - Back</option>
                      <option value='2' >2 - Next</option>
                      </select>
                     </div>
                </div>
              </div>
              <div class='row'>
                <div class='col-md-12 sai-container-overflow'>
                    <table class='table table-striped table-bordered' id='sai-grid-table'>
                        <thead>
                            <tr>
                                <th>Jenis</th>
                                <th>File</th>
                                <th>
                                    <a href='#' class='sai-btn-circle pull-right' id='sai-grid-add'><i class='fa fa-plus'></i>
                                </th> 
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
    </form>
    <!-- Modal FORM -->
    <div class="modal in" id="sai-grid-table-modal" tabindex="-1" role="dialog" >
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="sai-grid-table-form">
                    <div class="modal-header">
                        <h5 class="modal-title">Input Dokumen</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group">
                                <label class="control-label col-sm-3">Jenis</label>
                                <div class="col-sm-9" style="margin-bottom:5px;">
                                    <select name="kode_dok[]" class="form-control selectize" id="web-modal-jenis" required>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function sepNum(x){
    var num = parseFloat(x).toFixed(2);
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
function getKaryawan(){
    $.ajax({
        type: 'GET',
        url: '<?=$root_ser?>/Prospecting.php?fx=getKaryawan',
        dataType: 'json',
        data: {},
        success:function(result){    
            if(result.status){
                if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                    for(i=0;i<result.daftar.length;i++){
                        $('#nik')[0].selectize.addOption([{text:result.daftar[i].nik + ' - ' + result.daftar[i].nama, value:result.daftar[i].nik}]);
                    }
                }
            }
        }
    });
}

function getJenisDok(){
    $.ajax({
        type: 'GET',
        url: '<?=$root_ser?>/Negosiasi.php?fx=getJenisDok',
        dataType: 'json',
        data: {},
        success:function(result){    
            if(result.status){
                if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                    for(i=0;i<result.daftar.length;i++){
                        $('#web-modal-jenis')[0].selectize.addOption([{text:result.daftar[i].kode_dok + ' - ' + result.daftar[i].nama, value:result.daftar[i].kode_dok}]);
                    }
                }
            }
        }
    });
}

function getNilai(){
    var inves = $('#inves').val();
    var margin = $('#margin').val();
    if((inves != null || inves != "") && (margin !=null || margin != "")){
        var nilai = toNilai(inves) + toNilai(margin);
        $('#nilai').val(nilai);
    }
}
$(document).ready(function(){


  var action_html = "<a href='#' title='Edit' data-jenis ='new' class='sai-btn-circle web_datatable_edit'><i class='fa fa-pencil'></i></a>";

  var action_html2 = "<a href='#' title='Edit' data-jenis ='keep' class='sai-btn-circle web_datatable_edit'><i class='fa fa-pencil'></i></a>";

  var dataTable = $('#table-Negosiasi').DataTable({
      'processing': true,
      'serverSide': true,
      'ajax': {
          'url': '<?=$root_ser?>/Negosiasi.php?fx=getDatatable',
          'data': {'jenis':'new'},
          'type': 'POST',
          'dataSrc' : function(json) {
              return json.data;   
          }
      },
      'columnDefs': [
          {'targets': 9, 'className': 'text-center',data: null, 'defaultContent': action_html },
          {
                'targets': 6,
                'className': 'text-right',
                'render': $.fn.dataTable.render.number( '.', ',', 0, 'Rp. ' )
          },
          {
                'targets': 7,
                'className': 'text-right',
                'render': $.fn.dataTable.render.number( '.', ',', 0, 'Rp. ' )
          },
          {
                'targets': 8,
                'className': 'text-right',
                'render': $.fn.dataTable.render.number( '.', ',', 0, 'Rp. ' )
          }
      ]
  });

  var dataTable2 = $('#table-Negosiasi-keep').DataTable({
      'processing': true,
      'serverSide': true,
      'ajax': {
          'url': '<?=$root_ser?>/Negosiasi.php?fx=getDatatable',
          'data': {'jenis':'keep'},
          'type': 'POST',
          'dataSrc' : function(json) {
              return json.data;   
          }
      },
      'columnDefs': [
          {'targets': 10, 'className': 'text-center', 'defaultContent': action_html2 },
          {
            'targets': 7,
            'className': 'text-right',
            'render': $.fn.dataTable.render.number( '.', ',', 0, 'Rp. ' )
          },
          {
                'targets': 8,
                'className': 'text-right',
                'render': $.fn.dataTable.render.number( '.', ',', 0, 'Rp. ' )
          },
          {
                'targets': 9,
                'className': 'text-right',
                'render': $.fn.dataTable.render.number( '.', ',', 0, 'Rp. ' )
          }
      ]
  });

  var dataTable3 = $('#table-Negosiasi-finish').DataTable({
      'processing': true,
      'serverSide': true,
      'ajax': {
          'url': '<?=$root_ser?>/Negosiasi.php?fx=getDatatable',
          'data': {'jenis':'finish'},
          'type': 'POST',
          'dataSrc' : function(json) {
              return json.data;   
          }
      },
      'columnDefs': [
        {
                'targets': 7,
                'className': 'text-right',
                'render': $.fn.dataTable.render.number( '.', ',', 0, 'Rp. ' )
        },
        {
                'targets': 8,
                'className': 'text-right',
                'render': $.fn.dataTable.render.number( '.', ',', 0, 'Rp. ' )
        },
        {
                'targets': 9,
                'className': 'text-right',
                'render': $.fn.dataTable.render.number( '.', ',', 0, 'Rp. ' )
        }

      ]
  });

  getKaryawan();
  getJenisDok();

  $('#saiweb_container').on('click', '#sai-grid-add', function(){
    $('#sai-grid-table-modal').modal('show');
    // alert('test');
  });

  
  $('#saiweb_container').on('keyup', '#inves', function(e){
      e.preventDefault();
        getNilai();
  });

  $('#saiweb_container').on('keyup', '#margin', function(e){
     e.preventDefault();
        getNilai();
  });

  $('#saiweb_container').on('submit', '#sai-grid-table-form', function(e){
    e.preventDefault();
    // var file = $('#web-modal-file').val();
    var kode_dok = $('#web-modal-jenis')[0].selectize.getValue();
    
    //var nama=getNamaAkun();
    var row = 
    "<tr class='sai-grid-input-row'>"+
        "<td width='40%'>"+
        kode_dok+
        "<input type='hidden' name='kode_dok[]' value='"+kode_dok+"' required readonly class='form-control'>"+
        "</td>"+
        "<td width='50%'>"+
        "<input type='file' name='file[]' required readonly >"+
        "</td>"+
        "<td width='10%'><a href='#' class='sai-btn-circle pull-right sai-grid-del'><i class='fa fa-times'></i></td>"+
    "</tr>";
    $('#sai-grid-table tbody').append(row);
    $('#sai-grid-table-modal').modal('hide');

  });
  
  $('#saiweb_container').on('click', '.sai-grid-del', function(){
        $(this).closest('tr').remove();
        $("html, body").animate({ scrollTop: $(document).height() }, 1000);
  });

  $('#saiweb_container').on('click', '.web_form_back', function(){
     var id = $(this).closest('form').attr('id');
     $('#'+id).hide();
     $('#web_datatable').show();
  });

  $('#saiweb_container').on('click', '.web_datatable_edit', function(){
                    // getset value
     var kode = $(this).closest('tr').find('td:eq(0)').text();
     var jenis = $(this).data('jenis');
     if(jenis == 'new'){
        $('#no_ref').val(kode);
        $('#web_datatable').hide();
        $('#web_form_insert').show();
     }else{
        var ref = $(this).closest('tr').find('td:eq(1)').text();
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Negosiasi.php?fx=getEditNegosiasi',
            dataType: 'json',
            data: {'no_bukti':kode,'no_ref':ref},
            success:function(res){
                if(res.status){
                    var line = res.daftar[0];
                    $('#no_bukti').val(line.no_bukti);
                    $('#no_ref').val(line.no_ref);
                    $('#keterangan').val(line.keterangan);
                    $('#tanggal').val(line.tanggal);
                    $('#nik')[0].selectize.setValue(line.nik);
                    $('#nilai').val(line.nilai);
                    $('#inves').val(line.inves);
                    $('#margin').val(line.margin);
                    $('#status')[0].selectize.setValue(line.status);
                    $('#sai-grid-table tbody').html('');
                    var row = "";
                    var line2 = "";
                    for (var i =0; i<res.daftar2.length; i++){
                        line2 = res.daftar2[i];
                        row+=
                        "<tr class='sai-grid-input-row'>"+
                        "<td width='40%'>"+
                        line2.kode_dok+
                        "<input type='hidden' name='kode_dok[]' value='"+line2.kode_dok+"' required readonly class='form-control'>"+
                        "</td>"+
                        "<td width='50%'>"+ line2.path_file +"<br>"+
                        "<input type='file' name='file[]' required readonly >"+
                        "</td>"+
                        "<td width='10%'><a href='#' class='sai-btn-circle pull-right sai-grid-del'><i class='fa fa-times'></i></td>"+
                        "</tr>";
                    }
                    
                    
                    $('#sai-grid-table tbody').append(row);

                    $('#web_datatable').hide();
                    $('#web_form_insert').show();
                }
            },
            fail: function(xhr, textStatus, errorThrown){
                alert('request failed:'+textStatus);
            }
        });
     }
    
     
  });

  $('#saiweb_container').on('submit', '#web_form_insert', function(e){
  e.preventDefault();
    var formData = new FormData(this);
    for(var pair of formData.entries()) {
         console.log(pair[0]+ ', '+ pair[1]); 
        }
    $.ajax({
        type: 'POST',
        url: '<?=$root_ser?>/Negosiasi.php',
        dataType: 'json',
        data: formData,
        contentType: false,
        cache: false,
        processData: false, 
        success:function(result){
            alert('Input data '+result.message);
            if(result.status){
                if(result.jenis == "2"){
                    dataTable3.ajax.reload();
                    dataTable.ajax.reload();
                    dataTable2.ajax.reload();
                }else if(result.jenis == "0"){
                    dataTable.ajax.reload();
                    dataTable2.ajax.reload();
                }else{
                    dataTable.ajax.reload();
                }

                $('#web_datatable').show();
                $('.input-form').val('');
                $('#web_form_insert').hide();
            }
        },
        fail: function(xhr, textStatus, errorThrown){
            alert('request failed:'+textStatus);
        }
    });
  });

   $('#saiweb_container').on('click', '.web_datatable_del', function(){
        if(confirm('Apakah anda ingin menghapus data ini?')){
            var kode = $(this).closest('tr').find('td:eq(0)').text();
            
            $.ajax({
                type: 'DELETE',
                url: '<?=$root_ser?>/Negosiasi.php',
                dataType: 'json',
                data: {'no_bukti':kode},
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

   $('.datepicker').datepicker({
    autoclose: true,
    format: 'yyyy-mm-dd'
    });

    $('.datepicker-dmy').datepicker({
        autoclose: true,
        format: 'dd-mm-yyyy'
    });

    $('.datepicker, .daterangepicker').on('keydown keyup keypress', function(e){
        e.preventDefault();
        return false;
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