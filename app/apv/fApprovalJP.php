<?php
     session_start();
     $root_lib=$_SERVER["DOCUMENT_ROOT"];
     if (substr($root_lib,-1)!="/") {
         $root_lib=$root_lib."/";
     }
     include_once($root_lib.'app/apv/setting.php');
   $kode_lokasi=$_SESSION['lokasi'];
   $nik=$_SESSION['userLog'];
?>
<style>
.form-group{
    margin-bottom:15px !important;
}
</style>
    <div class="container-fluid mt-3">
        <div class="row" id="saku-data-app">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class='row'>   
                            <div class="col-md-6">
                                <h4 class="card-title mb-4"><i class='fas fa-cube'></i> Approval Pengadaan
                                </h4>
                                <hr>
                            </div>
                            <div class='col-md-6'>
                                <ul class="nav nav-tabs customtab float-right" role="tablist">
                                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#sai-tab-finish" role="tab" aria-selected="true"><span class="hidden-xs-down">Finish</span></a> </li>
                                <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#sai-tab-new" role="tab" aria-selected="false"><span class="hidden-xs-down">New</span></a> </li>
                                </ul>
                            </div>
                        </div>
                        <div class='row'>   
                            <div class='col-md-12'>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="sai-tab-new" style="position: relative;">
                                        <div class="table-responsive ">
                                            <table id="table-aju" class="table table-bordered table-striped" style='width:100%'>
                                                <thead>
                                                    <tr>
                                                        <th>No Bukti</th>
                                                        <th>No Juskeb</th>
                                                        <th>No Dokumen</th>
                                                        <th>PP</th>
                                                        <th>Waktu</th>
                                                        <th>Kegiatan</th>
                                                        <th>Posisi</th>
                                                        <th>Nilai</th> 
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="sai-tab-finish" style="position: relative;">
                                        <div class="table-responsive">
                                            <table id="table-app" class="table table-bordered table-striped" style='width:100%'>
                                                <thead>
                                                    <tr>
                                                        <th>No Aju</th>
                                                        <th>No Urut</th>
                                                        <th>Id Approval</th>
                                                        <th>Keterangan</th>
                                                        <th>Tanggal</th>
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
        <div class="row" id="form-tambah-app" style="display:none;">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <form class="form" id="form-tambah">
                            <h4 class="card-title" style='margin-bottom: 15px;'>Form Approval
                            <button type="submit" class="btn btn-success ml-2"  style="float:right;" id="btn-save"><i class="fa fa-save"></i> Simpan</button>
                            <button type="button" class="btn btn-secondary ml-2" id="btn-app-kembali" style="float:right;"><i class="fa fa-undo"></i> Kembali</button>
                            </h4>
                            <!-- <div class="form-group row">
                                <div class="col-3">
                                    <input class="form-control" type="text" placeholder="No Bukti" id="no_bukti" name="no_bukti" hidden>
                                </div>
                            </div> -->
                            <div class="form-group row mt-2">
                                <label for="nama" class="col-3 col-form-label">Tanggal</label>
                                <div class="col-3">
                                    <input class="form-control" type="date" placeholder="tanggal" id="tanggal" name="tanggal" value="<?=date('Y-m-d')?>" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">Status</label>
                                <div class="col-3">
                                    <select class='form-control' id="status" name="status" required>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">Keterangan</label>
                                <div class="col-9">
                                    <input class="form-control" type="text" placeholder="Keterangan" id="keterangan" name="keterangan" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">No Aju</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" placeholder="No Pengajuan" id="no_aju" name="no_aju" readonly>
                                </div>
                                <label for="nama" class="col-3 col-form-label">No Urut</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" placeholder="No Urut" id="nu" name="nu" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="no_dokumen" class="col-3 col-form-label">No Dokumen</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" placeholder="No Dokumen" id="no_dokumen" name="no_dokumen" readonly>
                                </div>
                                <label for="nama" class="col-3 col-form-label">Kode PP</label>
                                <div class="col-3">
                                    <select class='form-control' id="kode_pp" name="kode_pp" readonly>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">Waktu</label>
                                <div class="col-3">
                                    <input class="form-control" type="date" placeholder="Waktu" id="waktu" name="waktu" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">Kegiatan</label>
                                <div class="col-9">
                                    <input class="form-control" type="text" placeholder="Kegiatan" id="kegiatan" name="kegiatan" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">Dasar</label>
                                <div class="col-9">
                                    <input class="form-control" type="text" placeholder="Dasar" id="dasar" name="dasar" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">Total Barang</label>
                                <div class="col-3">
                                    <input class="form-control text-right" type="text"  id="total" name="total" readonly>
                                </div>
                            </div>
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#det" role="tab" aria-selected="true"><span class="hidden-xs-down">Barang</span></a> </li>
                                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#dok" role="tab" aria-selected="false"><span class="hidden-xs-down">Dokumen</span></a> </li>
                            </ul>
                            <div class="tab-content tabcontent-border">
                                <div class="tab-pane active" id="det" role="tabpanel">
                                    <div class='col-xs-12 mt-2' style='overflow-y: scroll; height:300px; margin:0px; padding:0px;'>
                                        <style>
                                            th,td{
                                                padding:8px !important;
                                                vertical-align:middle !important;
                                            }
                                        </style>
                                        <table class="table table-striped table-bordered table-condensed" id="input-grid2">
                                        <thead>
                                            <tr>
                                                <th width="5%">No</th>
                                                <th width="45%">Barang</th>
                                                <th width="20%">Harga</th>
                                                <th width="10%">Qty</th>
                                                <th width="20%">Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane" id="dok" role="tabpanel">
                                    <div class='col-xs-12 mt-2' style='overflow-y: scroll; height:300px; margin:0px; padding:0px;'>
                                        <style>
                                            th,td{
                                                padding:8px !important;
                                                vertical-align:middle !important;
                                            }
                                        </style>
                                        <table class="table table-striped table-bordered table-condensed" id="input-dok">
                                        <thead>
                                            <tr>
                                                <th width="5%">No</th>
                                                <th width="60%">Nama Dokumen</th>
                                                <th width="30%">File</th>
                                                <th width="5%">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
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

    function getStatus(){
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/ApprovalJP.php?fx=getStatus',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?=$kode_lokasi?>'},
            success:function(result){    
                if(result.status){
                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                        var select = $('#status').selectize();
                        select = select[0];
                        var control = select.selectize;
                        for(i=0;i<result.daftar.length;i++){
                            control.addOption([{text:result.daftar[i].status + ' - ' + result.daftar[i].nama, value:result.daftar[i].status}]);
                        }
                        control.setValue('2');
                    }
                }
            }
        });
    }

    function getPP(){
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Pengajuan.php?fx=getPP',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?=$kode_lokasi?>'},
            success:function(result){    
                if(result.status){
                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                        var select = $('#kode_pp').selectize();
                        select = select[0];
                        var control = select.selectize;
                        for(i=0;i<result.daftar.length;i++){
                            control.addOption([{text:result.daftar[i].kode_pp + ' - ' + result.daftar[i].nama, value:result.daftar[i].kode_pp}]);
                        }
                    }
                }
            }
        });
    }

    getStatus();
    getPP();

    
    var $iconLoad = $('.preloader');

    var action_html = "<a href='#' title='Edit' class='badge badge-info' id='btn-edit'><i class='fas fa-pencil-alt'></i></a>";
    var kode_lokasi = '<?php echo $kode_lokasi ?>';
    var dataTable = $('#table-aju').DataTable({
        'processing': true,
        'serverSide': true,
        'ajax': {
            'url': '<?=$root_ser?>/ApprovalJP.php?fx=getPengajuan',
            'data': {'kode_lokasi':kode_lokasi},
            'async':false,
            'type': 'GET',
            'dataSrc' : function(json) {
                return json.data;   
            }
        },
        'columnDefs': [
            {'targets': 8, data: null, 'defaultContent': action_html },
            {   'targets': 7, 
                'className': 'text-right',
                'render': $.fn.dataTable.render.number( '.', ',', 0, '' ) 
            }
        ]
    });

    var dataTable2 = $('#table-app').DataTable({
        'processing': true,
        'serverSide': true,
        'ajax': {
            'url': '<?=$root_ser?>/ApprovalJP.php?fx=getApproval',
            'data': {'kode_lokasi':kode_lokasi},
            'async':false,
            'type': 'GET',
            'dataSrc' : function(json) {
                return json.data;   
            }
        }
    });

    $('#saku-data-app').on('click', '#btn-edit', function(){
        var id= $(this).closest('tr').find('td').eq(0).html();

        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/ApprovalJP.php?fx=getData',
            dataType: 'json',
            async:false,
            data: {'no_aju':id,'kode_lokasi':kode_lokasi},
            success:function(result){
                if(result.status){
                    // $('#no_bukti').val(result.no_app);
                    $('#no_aju').val(result.daftar[0].no_bukti);
                    $('#nu').val(result.daftar[0].no_urut);
                    $('#no_dokumen').val(result.daftar[0].no_dokumen);
                    $('#kode_pp')[0].selectize.setValue(result.daftar[0].kode_pp);
                    $('#kode_pp')[0].selectize.disable();
                    $('#waktu').val(result.daftar[0].waktu);
                    $('#kegiatan').val(result.daftar[0].kegiatan);
                    $('#dasar').val(result.daftar[0].dasar);
                    $('#total').val(toRp(result.daftar[0].nilai));
                    var input="";
                    var no=1;
                    if(result.daftar2.length > 0){

                        for(var x=0;x<result.daftar2.length;x++){
                            var line = result.daftar2[x];
                            input += "<tr class='row-barang'>";
                            input += "<td width='5%' class='no-barang'>"+no+"</td>";
                            input += "<td width='50%'>"+line.barang+"</td>";
                            input += "<td width='15%' style='text-align:right'>"+toRp(line.harga)+"</td>";
                            input += "<td width='10%' style='text-align:right'>"+line.jumlah+"</td>";
                            input += "<td width='20%' style='text-align:right'>"+toRp(line.nilai)+"</td>";
                            input += "</tr>";
                            no++;
                        }
                    }

                    var input2 = "";
                    var no=1;
                    if(result.daftar3.length > 0){

                        for(var i=0;i< result.daftar3.length;i++){
                            var line2 = result.daftar3[i];
                            input2 += "<tr class='row-dok'>";
                            input2 += "<td width='5%'  class='no-dok'>"+no+"</td>";
                            input2 += "<td width='60%'>"+line2.nama+"</td>";
                            input2 += "<td width='30%'>"+line2.file_dok+"</td>";
                            input2 += "<td width='5%'><a class='btn btn-danger btn-sm down-dok' style='font-size:8px' href='<?=$root_upload?>/"+line2.file_dok+"' target='_blank'><i class='fa fa-download fa-1'></i></td>";
                            input2 += "</tr>";
                            no++;
                        }
                    }

                    $('#input-grid2 tbody').html(input);
                    $('#input-dok tbody').html(input2);
                    $('.currency').inputmask("numeric", {
                        radixPoint: ",",
                        groupSeparator: ".",
                        digits: 2,
                        autoGroup: true,
                        rightAlign: true,
                        oncleared: function () { self.Value(''); }
                    });
                    $('#saku-data-app').hide();
                    $('#form-tambah-app').show();
                }
            }
        });
    });

    $('#form-tambah-app').on('click', '#btn-app-kembali', function(){
        $('#saku-data-app').show();
        $('#form-tambah-app').hide();
    });

    $('#form-tambah-app').on('submit', '#form-tambah', function(e){
    e.preventDefault();
        var formData = new FormData(this);
        for(var pair of formData.entries()) {
            console.log(pair[0]+ ', '+ pair[1]); 
        }
        
        var nik='<?php echo $nik; ?>' ;
        var kode_lokasi='<?php echo $kode_lokasi; ?>' ;
        
        formData.append('nik_user', nik);
        formData.append('kode_lokasi', kode_lokasi);
        $iconLoad.show();
        $.ajax({
            type: 'POST',
            url: '<?=$root_ser?>/ApprovalJP.php?fx=simpan',
            dataType: 'json',
            data: formData,
            async:false,
            contentType: false,
            cache: false,
            processData: false, 
            success:function(result){
                // alert('Input data '+result.message);
                if(result.status){
                    dataTable.ajax.reload();
                    dataTable2.ajax.reload();
                    Swal.fire(
                        'Saved!',
                        'Your data has been saved.'+result.message,
                        'success'
                    )
                    $iconLoad.hide();
                    $('#form-tambah-app').hide();
                    $('#saku-data-app').show();
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong!',
                        footer: '<a href>'+result.message+'</a>'
                    })
                }
            },
            fail: function(xhr, textStatus, errorThrown){
                alert('request failed:'+textStatus);
            }
        });     
    });
    

    $('.inp-hrg').inputmask("numeric", {
        radixPoint: ",",
        groupSeparator: ".",
        digits: 2,
        autoGroup: true,
        rightAlign: true,
        oncleared: function () { self.Value(''); }
    });

    </script>