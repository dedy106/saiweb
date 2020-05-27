<?php
   $kode_lokasi=$_SESSION['lokasi'];
   $nik=$_SESSION['userLog'];
   $kode_pp=$_SESSION['kodePP'];
   $periode=$_SESSION['periode'];
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
                        
                                <h4 class="card-title">Data Pengajuan 
                                </h4>
                                <!-- <h6 class="card-subtitle">Tabel Pengajuan</h6> -->
                                <hr>
                                <div class="table-responsive ">
                                    <table id="table-aju" class="table table-bordered table-striped" style='width:100%'>
                                        <thead>
                                            <tr>
                                                <th>No Bukti</th>
                                                <th>PP</th>
                                                <th>Tanggal</th>
                                                <th>No KTP</th>
                                                <th>Nama wali</th>
                                                <th>NIS</th>
                                                <th>Nilai</th>
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
                            <div class="form-group row mt-2">
                                <label for="nama" class="col-3 col-form-label">Tanggal</label>
                                <div class="col-3">
                                    <input class="form-control" type="date" placeholder="tanggal" id="tanggal" name="tanggal" value="<?=date('Y-m-d')?>" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">Status</label>
                                <div class="col-3">
                                    <select class='form-control selectize' id="status" name="status" required>
                                    <option value="2">APPROVE</option>
                                    <option value="3">RETURN</option>
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
                                <label for="nu" class="col-3 col-form-label">No Urut</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" placeholder="No Urut" id="nu" name="nu" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="no_ktp" class="col-3 col-form-label">No KTP</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" placeholder="" id="no_ktp" name="no_ktp" readonly>
                                </div>
                                <label for="nama_wali" class="col-3 col-form-label">Nama Wali</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" placeholder="" id="nama_wali" name="nama_wali" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nis" class="col-3 col-form-label">NIS</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" placeholder="" id="nis" name="nis" readonly>
                                </div>
                                <label for="nama_siswa" class="col-3 col-form-label">Nama Siswa</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" placeholder="" id="nama_siswa" name="nama_siswa" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="tgl_bayar" class="col-3 col-form-label">Tgl Bayar</label>
                                <div class="col-3">
                                    <input class="form-control" type="date" placeholder="" id="tgl_bayar" name="tgl_bayar" readonly>
                                </div>
                            </div>
                            <!-- <div class="form-group row">
                                <label for="tgl_bayar" class="col-3 col-form-label">File Dokumen</label>
                                <div class="col-6">
                                    <input class="form-control" type="text" placeholder="" id="file_dok"  readonly>
                                </div>
                                <label class="col-3 col-form-label" id="dFile"></label>
                            </div> -->
                            <div class="form-group row">
                                <label for="total" class="col-3 col-form-label">Total Tagihan</label>
                                <div class="col-3">
                                    <input class="form-control text-right" type="text"  id="total" name="total" readonly>
                                </div>
                                <label for="total_byr" class="col-3 col-form-label">Total Bayar</label>
                                <div class="col-3">
                                    <input class="form-control text-right" type="text"  id="total_byr" name="total_byr" readonly>
                                </div>
                            </div>
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#det" role="tab" aria-selected="true"><span class="hidden-xs-down">Detail Tagihan</span></a> </li>
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
                                                <th width="35%">Jenis Tagihan</th>
                                                <th width="30%">Nilai</th>
                                                <th width="30%">Nilai Bayar</th>
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
    
    var $iconLoad = $('.preloader');

    var action_html = "<a href='#' title='Edit' class='badge badge-info' id='btn-edit'><i class='fas fa-pencil-alt'></i></a>";
    var kode_lokasi = '<?php echo $kode_lokasi ?>';
    var dataTable = $('#table-aju').DataTable({
        'processing': true,
        'serverSide': true,
        'ajax': {
            'url': '<?=$root_ser?>/Approval.php?fx=getPengajuan',
            'data': {'kode_lokasi':kode_lokasi,'nik':'<?=$nik?>','kode_pp':'<?=$kode_pp?>'},
            'async':false,
            'type': 'GET',
            'dataSrc' : function(json) {
                return json.data;   
            }
        },
        'columnDefs': [
            {'targets': 7, data: null, 'defaultContent': action_html },
            {   'targets': 6, 
                'className': 'text-right',
                'render': $.fn.dataTable.render.number( '.', ',', 0, '' ) 
            }
        ]
    });

    $('#saku-data-app').on('click', '#btn-edit', function(){
        var id= $(this).closest('tr').find('td').eq(0).html();

        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Approval.php?fx=getData',
            dataType: 'json',
            async:false,
            data: {'no_aju':id,'kode_lokasi':kode_lokasi,'nik':'<?=$nik?>','kode_pp':'<?=$kode_pp?>'},
            success:function(result){
                if(result.status){
                    // $('#no_bukti').val(result.no_app);
                    $('#no_aju').val(result.daftar[0].no_aju);
                    $('#nu').val(result.daftar[0].nu);
                    $('#no_ktp').val(result.daftar[0].no_ktp);
                    $('#nama_wali').val(result.daftar[0].nama_ortu);
                    $('#nis').val(result.daftar[0].nis);
                    $('#nama_siswa').val(result.daftar[0].nama_siswa);
                    $('#tgl_bayar').val(result.daftar[0].tgl_bayar);
                    $('#total').val(toRp(result.daftar[0].nilai));
                    $('#total_byr').val(toRp(result.daftar[0].nilai_byr));
                    // $('#file_dok').val(result.daftar[0].file_dok);
                    // $('#dFile').html("<a type='button'  href='<?=$path?>/upload/"+result.daftar[0].file_dok+"' target='_blank' class='btn btn-info btn-sm'><i class='fa fa-download'></i> Download</a>");
                    var input="";
                    var no=1;
                    if(result.daftar2.length > 0){

                        for(var x=0;x<result.daftar2.length;x++){
                            var line = result.daftar2[x];
                            input += "<tr class='row-tgh'>";
                            input += "<td width='5%' class='no-tgh'>"+no+"</td>";
                            input += "<td width='35%'>"+line.kode_produk+"</td>";
                            input += "<td width='30%' style='text-align:right'>"+toRp(line.nilai)+"</td>";
                            input += "<td width='30%' style='text-align:right'>"+toRp(line.nilai_byr)+"</td>";
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
                            input2 += "<td width='50%'>"+line2.file_dok+"</td>";
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
        var kode_pp='<?php echo $kode_pp; ?>' ;
        
        formData.append('nik_user', nik);
        formData.append('kode_lokasi', kode_lokasi);
        formData.append('kode_pp', kode_pp);
        $iconLoad.show();
        $.ajax({
            type: 'POST',
            url: '<?=$root_ser?>/Approval.php?fx=simpan',
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