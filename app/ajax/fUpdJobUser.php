<?php
	session_start();
	$root_lib=$_SERVER["DOCUMENT_ROOT"];
	if (substr($root_lib,-1)!="/") {
		$root_lib=$root_lib."/";
	}
	include_once($root_lib.'app/ajax/setting.php');
	
	$nik=$_SESSION['userLog'];
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
                        <h4 class="card-title">Data BackLog 
                        </h4>
                        <!-- <h6 class="card-subtitle">Tabel Pengajuan</h6> -->
                        <hr>
                        <div class="table-responsive ">
                            <table id="table-aju" class="table table-bordered table-striped" style='width:100%'>
                                <thead>
                                <tr>
                                <th>Kode Job</th>
                                <th>Nama Job</th>
                                <th>Kode Proyek</th>
                                <th>NIK</th>
                                <th>Tgl Mulai</th>
                                <th>Tgl Selesai</th>
                                <th>Action</th>
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
        <div class="row" id="form-tambah-app" style="display:none;">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <form class="form" id="form-tambah">
                            <h4 class="card-title" style='margin-bottom: 15px;'>Form Update Backlog
                            <button type="submit" class="btn btn-success ml-2"  style="float:right;" id="btn-save"><i class="fa fa-save"></i> Simpan</button>
                            <button type="button" class="btn btn-secondary ml-2" id="btn-app-kembali" style="float:right;"><i class="fa fa-undo"></i> Kembali</button>
                            </h4>
                            <div class="form-group row mt-2">
                                <label for="nama" class="col-3 col-form-label">Tanggal</label>
                                <div class="col-3">
                                    <input class="form-control" type="date" placeholder="tanggal" id="tanggal" name="tanggal" value="<?=date('Y-m-d')?>" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">Kode Job</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" placeholder="Kode Job" id="kode_job" name="kode_job" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">Nama Job</label>
                                <div class="col-9">
                                    <input class="form-control" type="text" placeholder="Nama Job" id="nama" name="nama" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">Proyek</label>
                                <div class="col-6">
                                    <select class='form-control' id="no_proyek" name="no_proyek" required>
                                    <option value=''>--- Pilih Proyek ---</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">NIK</label>
                                <div class="col-6">
                                    <select class='form-control' id="nik" name="nik" required>
                                    <option value=''>--- Pilih NIK ---</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="tgl_mulai" class="col-3 col-form-label">Tgl Mulai</label>
                                <div class="col-3">
                                    <input class="form-control" type="date" id="tgl_mulai" name="tgl_mulai" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="tgl_selesai" class="col-3 col-form-label">Tgl Selesai</label>
                                <div class="col-3">
                                    <input class="form-control" type="date" id="tgl_selesai" name="tgl_selesai" readonly>
                                </div>
                            </div>
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#det" role="tab" aria-selected="true"><span class="hidden-xs-down">Detail Job</span></a> </li>
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
                                                <th width="30%">Action</th>
                                                <th width="30%">Nama Detail</th>
                                                <th width="35%">Keterangan</th>
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

    
    function getProyek(){
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/UpdateJobUser.php?fx=getProyek',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?=$kode_lokasi?>'},
            success:function(result){    
                if(result.status){
                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                        var select = $('#no_proyek').selectize();
                        select = select[0];
                        var control = select.selectize;
                        for(i=0;i<result.daftar.length;i++){
                            control.addOption([{text:result.daftar[i].no_proyek + ' - ' + result.daftar[i].nama, value:result.daftar[i].no_proyek}]);
                        }
                    }
                }
            }
        });
    }

    function getNIK(){
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/UpdateJobUser.php?fx=getNIK',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?=$kode_lokasi?>','nik':'<?=$nik?>'},
            success:function(result){    
                if(result.status){
                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                        var select = $('#nik').selectize();
                        select = select[0];
                        var control = select.selectize;
                        for(i=0;i<result.daftar.length;i++){
                            control.addOption([{text:result.daftar[i].nik + ' - ' + result.daftar[i].nama, value:result.daftar[i].nik}]);
                        }
                        control.setValue('<?=$nik?>');
                    }
                }
            }
        });
    }
    getProyek();
    getNIK();
    
    var $iconLoad = $('.preloader');

    var action_html = "<a href='#' title='Edit' class='badge badge-info' id='btn-edit'><i class='fas fa-pencil-alt'></i></a>";
    var kode_lokasi = '<?php echo $kode_lokasi ?>';
    var dataTable = $('#table-aju').DataTable({
        'processing': true,
        'serverSide': true,
        'ajax': {
            'url': '<?=$root_ser?>/UpdateJobUser.php?fx=getJob',
            'data': {'kode_lokasi':kode_lokasi,'nik':'<?=$nik?>'},
            'async':false,
            'type': 'GET',
            'dataSrc' : function(json) {
                return json.data;   
            }
        },
        'columnDefs': [
            {'targets': 6, data: null, 'defaultContent': action_html },
            // {   'targets': 6, 
            //     'className': 'text-right',
            //     'render': $.fn.dataTable.render.number( '.', ',', 0, '' ) 
            // }
        ]
    });

    $('#saku-data-app').on('click', '#btn-edit', function(){
        var id= $(this).closest('tr').find('td').eq(0).html();

        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/UpdateJobUser.php?fx=getData',
            dataType: 'json',
            async:false,
            data: {'no_aju':id,'kode_lokasi':kode_lokasi,'nik':'<?=$nik?>'},
            success:function(result){
                if(result.status){
                    // $('#no_bukti').val(result.no_app);
                    $('#kode_job').val(result.daftar[0].kode_job);
                    $('#nama').val(result.daftar[0].nama);
                    $('#no_proyek')[0].selectize.setValue(result.daftar[0].no_proyek);
                    $('#no_proyek')[0].selectize.disable();
                    $('#nik')[0].selectize.setValue(result.daftar[0].nik);
                    $('#nik')[0].selectize.disable();
                    $('#tgl_mulai').val(result.daftar[0].tgl_mulai);
                    $('#tgl_selesai').val(result.daftar[0].tgl_selesai);
                    var input="";
                    var no=1;
                    if(result.daftar2.length > 0){

                        for(var x=0;x<result.daftar2.length;x++){
                            var line = result.daftar2[x];
                            input += "<tr class='row-job'>";
                            input += "<td width='5%' class='no-job'>"+no+"<input type='hidden' name='no[]' class='form-control inp-no' value='"+no+"' required></td>";
                            if(result.daftar2[x].status == "1"){

                                input += "<td width='30%'><select name='status[]' class='form-control inp-sts selectize'><option value='0'>INPROGRESS</option><option value='1' selected>FINISH</option></select><input type='hidden' name='nu[]' class='form-control inp-nu' value='"+result.daftar2[x].nu+"' required></td>";
                            }else{
                                input += "<td width='30%'><select name='status[]' class='form-control inp-sts selectize'><option value='0' selected>INPROGRESS</option><option value='1'>FINISH</option></select><input type='hidden' name='nu[]' class='form-control inp-nu' value='"+result.daftar2[x].nu+"' required></td>";
                            }
                            input += "<td width='30%'><input type='text' name='nama_det[]' class='form-control inp-nama' value='"+result.daftar2[x].nama+"' required readonly></td>";
                            input += "<td width='35%'><input type='text' name='ket_det[]' class='form-control inp-ket' value='"+result.daftar2[x].keterangan+"' required></td>";
                            input += "</tr>";
                            no++;
                        }
                    }
                   
                    $('#input-grid2 tbody').html(input);
                    $('.selectize').selectize();
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
            url: '<?=$root_ser?>/UpdateJobUser.php?fx=simpan',
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
                    // dataTable2.ajax.reload();
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