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
        <div class="row" id="saku-data-aju">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Data Pengajuan 
                        <button type="button" id="btn-aju-tambah" class="btn btn-info ml-2" style="float:right;"><i class="fa fa-plus-circle"></i> Tambah</button>
                        </h4>
                        <hr>
                        <div class="table-responsive ">
                            <table id="table-aju" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No Bukti</th>
                                        <th>Tanggal</th>
                                        <th>PP</th>
                                        <th>No KTP</th>
                                        <th>Nama Wali</th>
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
                </div>
            </div>
        </div>
        <div class="row" id="form-tambah-aju" style="display:none;">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <form class="form" id="form-tambah">
                            <h4 class="card-title">Form Pengajuan
                            <button type="submit" class="btn btn-success ml-2"  style="float:right;" id="btn-save"><i class="fa fa-save"></i> Simpan</button>
                            <button type="button" class="btn btn-secondary ml-2" id="btn-aju-kembali" style="float:right;"><i class="fa fa-undo"></i> Kembali</button>
                            </h4>
                            <div class="form-group row" id="row-id">
                                <div class="col-9">
                                    <input class="form-control" type="text" id="id" name="id" readonly hidden>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-3">
                                    <input class="form-control" type="hidden" placeholder="No Bukti" id="no_bukti" name="no_bukti" >
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="no_ktp" class="col-3 col-form-label">No KTP</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" placeholder="Masukkan No KTP" id="no_ktp" name="no_ktp" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">Nama Wali</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" placeholder="Masukkan Nama Wali" id="nama_wali" name="nama_wali"  required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">Alamat</label>
                                <div class="col-9">
                                    <input class="form-control" type="text" placeholder="Masukkan Alamat Wali" id="alamat" name="alamat"  required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">No Telp</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" placeholder="Masukkan No Telp Wali" id="no_telp" name="no_telp" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">NIS</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" placeholder="Masukkan No Telp Wali" id="nis" name="nis" required readonly value='<?=$_SESSION['userLog']?>'>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">Nama Siswa</label>
                                <div class="col-9">
                                    <input class="form-control" type="text" placeholder="Masukkan No Telp Wali" id="nama_siswa" name="nama_siswa" value='<?=$_SESSION['namaUser']?>' readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">Tgl Bayar</label>
                                <div class="col-3">
                                    <input class="form-control" type="date" placeholder="tanggal" id="tgl_bayar" name="tgl_bayar" value="<?=date('Y-m-d')?>" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">Wali Kelas</label>
                                <div class="col-9">
                                    <input class="form-control" type="text" id="nik_wali" name="nik_wali" readonly value=''>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">Ka Admin</label>
                                <div class="col-9">
                                    <input class="form-control" type="text" id="nik_app1" name="nik_app1" readonly value=''>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">Kepala Sekolah</label>
                                <div class="col-9">
                                    <input class="form-control" type="text" id="nik_app2" name="nik_app2" readonly value=''>
                                </div>
                            </div>
                            <!-- <div class="form-group row">
                                <label class="col-3 col-form-label">File</label>
                                <div class="input-group col-6">
                                    <div class="custom-file">
                                        <input type="file" name="file_dok" class="custom-file-input" id="file_dok">
                                        <label class="custom-file-label" for="file_dok">Choose file</label>
                                    </div>
                                </div>
                                <label id='dFile' class='col-3 col-form-label'></label>
                            </div> -->
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">Total Tagihan</label>
                                <div class="col-3">
                                    <input class="form-control text-right" type="text"  id="total" name="total" required readonly>
                                </div>
                                <label for="nama" class="col-3 col-form-label">Total Bayar</label>
                                <div class="col-3">
                                    <input class="form-control text-right" type="text"  id="total_byr" name="total_byr" required readonly>
                                </div>
                            </div>
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#det" role="tab" aria-selected="true"><span class="hidden-xs-down">Detail Tagihan</span></a> </li>
                                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#dok" role="tab" aria-selected="true"><span class="hidden-xs-down">Dokumen</span></a> </li>
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
                                                <th width="5%"><button type="button" href="#" id="add-row-dok" class="btn btn-default"><i class="fa fa-plus-circle"></i></button></th>
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
        <div class="row" id="slide-history" style="display:none;">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="btn btn-secondary ml-2" id="btn-aju-kembali" style="float:right;"><i class="fa fa-undo"></i> Kembali</button>
                        <div class="profiletimeline mt-5">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" id="slide-print" style="display:none;">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="btn btn-secondary ml-2" id="btn-aju-kembali" style="float:right;"><i class="fa fa-undo"></i> Kembali</button>
                        <button type="button" class="btn btn-info ml-2" id="btn-aju-print" style="float:right;"><i class="fa fa-print"></i> Print</button>
                        <div id="print-area" class="mt-5" width='100%' style='border:none;min-height:480px'>
                        </div>
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

    function hitungTotal(){
        $('#total').val(0);
        total= 0;
        $('.row-tgh').each(function(){
            var sub = toNilai($(this).closest('tr').find('.inp-nil').val());
            var this_val = sub;
            total += +this_val;
            
            $('#total').val(sepNum(total));
        });
    }

    function hitungBayar(){
        $('#total_byr').val(0);
        total= 0;
        $('.row-tgh').each(function(){
            var sub = toNilai($(this).closest('tr').find('.inp-nil_byr').val());
            var this_val = sub;
            total += +this_val;
            
            $('#total_byr').val(sepNum(total));
        });
    }

    function terbilang(int){
        angka = ["", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas"];
        int = Math.floor(int);
        if (int < 12)
            return " " + angka[int];
        else if (int < 20)
            return terbilang(int - 10) + " belas ";
        else if (int < 100)
            return terbilang(int / 10) + " puluh " + terbilang(int % 10);
        else if (int < 200)
            return "seratus" + terbilang(int - 100);
        else if (int < 1000)
            return terbilang(int / 100) + " ratus " + terbilang(int % 100);
        else if (int < 2000)
            return "seribu" + terbilang(int - 1000);
        else if (int < 1000000)
            return terbilang(int / 1000) + " ribu " + terbilang(int % 1000);
        else if (int < 1000000000)
            return terbilang(int / 1000000) + " juta " + terbilang(int % 1000000);
        else if (int < 1000000000000)
            return terbilang(int / 1000000) + " milyar " + terbilang(int % 1000000000);
        else if (int >= 1000000000000)
            return terbilang(int / 1000000) + " trilyun " + terbilang(int % 1000000000000);
    }

    function getNamaBulan(no_bulan){
        switch (no_bulan){
            case 1 : case '1' : case '01': bulan = "Januari"; break;
            case 2 : case '2' : case '02': bulan = "Februari"; break;
            case 3 : case '3' : case '03': bulan = "Maret"; break;
            case 4 : case '4' : case '04': bulan = "April"; break;
            case 5 : case '5' : case '05': bulan = "Mei"; break;
            case 6 : case '6' : case '06': bulan = "Juni"; break;
            case 7 : case '7' : case '07': bulan = "Juli"; break;
            case 8 : case '8' : case '08': bulan = "Agustus"; break;
            case 9 : case '9' : case '09': bulan = "September"; break;
            case 10 : case '10' : case '10': bulan = "Oktober"; break;
            case 11 : case '11' : case '11': bulan = "November"; break;
            case 12 : case '12' : case '12': bulan = "Desember"; break;
            default: bulan = null;
        }

        return bulan;
    }

    function getTtd(){
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Pengajuan.php?fx=getSisTtd',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?=$kode_lokasi?>','nik':'<?=$nik?>','kode_pp':'<?=$kode_pp?>'},
            success:function(result){    
                if(result.status){
                     $('#nik_wali').val(result.nik_wali+'/'+result.nama_wali);
                     $('#nik_app1').val(result.nik_app1+'/'+result.nama_app1);
                     $('#nik_app2').val(result.nik_app2+'/'+result.nama_app2);
                }
            }
        });
    }

    function getTagihan(){
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Pengajuan.php?fx=getTagihan',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?=$kode_lokasi?>','nik':'<?=$nik?>','kode_pp':'<?=$kode_pp?>','periode':'<?=$periode?>'},
            success:function(result){    
                if(result.status){
                     if(result.daftar.length > 0){
                         var html ='';
                         var no=1;
                         for(var i=0;i<result.daftar.length;i++){
                             var line =result.daftar[i];
                             html+=`<tr class='row-tgh'>
                             <td width='5%' class='no-tgh'>`+no+`</td>
                             <td width='35%'><input type='text' name='kode_produk[]' class='form-control inp-prod' value='`+line.kode_param+`' readonly></td>
                             <td width='30%' style='text-align:right'><input type='text' name='nilai[]' class='form-control inp-nil currency'  value='`+line.sak_total+`' readonly required></td>
                             <td width='30%' style='text-align:right'><input type='text' name='nilai_byr[]' class='form-control inp-nil_byr currency'  value='0' required></td>
                             </tr>`;
                             no++;
                         }
                         $('#input-grid2 tbody').html(html);
                         $('.currency').inputmask("numeric", {
                            radixPoint: ",",
                            groupSeparator: ".",
                            digits: 2,
                            autoGroup: true,
                            rightAlign: true,
                            oncleared: function () { self.Value(''); }
                        });
                         hitungTotal();
                     }
                }
            }
        });
    }

    var $iconLoad = $('.preloader');
    var action_html = "<a href='#' title='Edit' class='badge badge-warning' id='btn-edit'><i class='fas fa-pencil-alt'></i></a> &nbsp; <a href='#' title='Hapus' class='badge badge-danger' id='btn-delete'><i class='fa fa-trash'></i></a>&nbsp; <a href='#' title='History' class='badge badge-success' id='btn-history'><i class='fas fa-history'></i></a>&nbsp; <a href='#' title='Preview' class='badge badge-info' id='btn-print'><i class='fas fa-print'></i></a>";
    var kode_lokasi = '<?php echo $kode_lokasi ?>';
    var dataTable = $('#table-aju').DataTable({
        'processing': true,
        'serverSide': true,
        'ajax': {
            'url': '<?=$root_ser?>/Pengajuan.php?fx=getPengajuan',
            'data': {'kode_lokasi':kode_lokasi,'nik':'<?=$nik?>','kode_pp':'<?=$kode_pp?>'},
            'async':false,
            'type': 'GET',
            'dataSrc' : function(json) {
                return json.data;   
            }
        },
        'columnDefs': [
            {   'targets': 6, 
                'className': 'text-right',
                'render': $.fn.dataTable.render.number( '.', ',', 0, '' ) 
            }
        ]
    });


    $('#saku-data-aju').on('click', '#btn-aju-tambah', function(){
        $('#row-id').hide();
        $('#form-tambah')[0].reset();
        $('#id').val('0');
        $('#dFile').hide();
        getTtd();
        getTagihan();
        $('#saku-data-aju').hide();
        $('#form-tambah-aju').show();
    });

    $('#form-tambah-aju').on('click', '#btn-aju-kembali', function(){
        $('#saku-data-aju').show();
        $('#form-tambah-aju').hide();
        $('#slide-history').hide();
    });

    $('#form-tambah-aju').on('submit', '#form-tambah', function(e){
    e.preventDefault();
        var parameter = $('#id').val();
        var total = $('#total').val();
        if(total == 0){
            alert('Total pengajuan tidak boleh 0');
        }else{
            // tambah
            $iconLoad.show();
            console.log('parameter:tambah');
            var formData = new FormData(this);
            for(var pair of formData.entries()) {
                    console.log(pair[0]+ ', '+ pair[1]); 
                }

            var nik='<?php echo $nik; ?>' ;
            var kode_lokasi='<?php echo $kode_lokasi; ?>' ;
            var kode_pp='<?php echo $kode_pp; ?>' ;
            var periode='<?php echo $periode; ?>' ;

            formData.append('nik_user', nik);
            formData.append('kode_lokasi', kode_lokasi);
            formData.append('kode_pp', kode_pp);
            formData.append('periode', periode);

            $.ajax({
                type: 'POST',
                url: '<?=$root_ser?>/Pengajuan.php?fx=simpan',
                dataType: 'json',
                data: formData,
                async:false,
                contentType: false,
                cache: false,
                processData: false, 
                success:function(result){
                    if(result.status){
                        dataTable.ajax.reload();
                        Swal.fire(
                            'Great Job!',
                            'Your data has been saved.'+result.message,
                            'success'
                            )
                        printAju(result.no_aju);
                        $iconLoad.hide();
                        
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
        }     
    });

    
    $('#form-tambah-aju').on('click', '#add-row-dok', function(){
        var no=$('#input-dok .row-dok:last').index();
        no=no+2;
        var input="";
        input = "<tr class='row-dok'>";
        input += "<td width='5%'  class='no-dok'>"+no+"</td>";
        input += "<td width='60%'><input type='text' name='nama_dok[]' class='form-control inp-dok' value='' required></td>";
        input += "<td width='50%'>"+
        "<input type='file' name='file_dok[]' required >"+
        "</td>";
        input += "<td width='5%'><a class='btn btn-danger btn-sm hapus-dok' style='font-size:8px'><i class='fa fa-times fa-1'></i></td>";
        input += "</tr>";
        $('#input-dok tbody').append(input);
    });

    $('#slide-history').on('click', '#btn-aju-kembali', function(){
        $('#saku-data-aju').show();
        $('#form-tambah-aju').hide();
        $('#slide-history').hide();
    });

    $('#slide-print').on('click', '#btn-aju-kembali', function(){
        $('#saku-data-aju').show();
        $('#form-tambah-aju').hide();
        $('#slide-print').hide();
    });


    $('#saku-data-aju').on('click', '#btn-edit', function(){
        var id= $(this).closest('tr').find('td').eq(0).html();
       
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Pengajuan.php?fx=getEdit',
            dataType: 'json',
            async:false,
            data: {'no_bukti':id,'kode_lokasi':kode_lokasi,'kode_pp':'<?=$kode_pp?>'},
            success:function(result){
                if(result.status){
                    $('#id').val('edit');
                    $('#no_bukti').val(id);
                    $('#tgl_bayar').val(result.daftar[0].tgl_bayar);
                    $('#no_ktp').val(result.daftar[0].no_ktp);
                    $('#nama_wali').val(result.daftar[0].nama_ortu);
                    $('#alamat').val(result.daftar[0].alamat);
                    $('#no_telp').val(result.daftar[0].no_telp);
                    $('#nis').val(result.daftar[0].nis);
                    $('#nama_siswa').val(result.daftar[0].nama_siswa);
                    $('#total').val(toRp(result.daftar[0].nilai));
                    $('#total_byr').val(toRp(result.daftar[0].nilai_byr));
                    // $('#dFile').html("<a type='button'  href='<?=$path?>/upload/"+result.daftar[0].file_dok+"' target='_blank' class='btn btn-info btn-sm'><i class='fa fa-download'></i> Download</a>");
                    // $('#dFile').show();
                    $('#nik_wali').val(result.daftar[0].nik_wali+'/'+result.daftar[0].nama_wali);
                    $('#nik_app1').val(result.daftar[0].nik_app1+'/'+result.daftar[0].nama_app1);
                    $('#nik_app2').val(result.daftar[0].nik_app2+'/'+result.daftar[0].nama_app2);
                    var input="";
                    var no=1;
                    if(result.daftar2.length>0){
                        for(var x=0;x<result.daftar2.length;x++){
                            var line = result.daftar2[x];
                            input+=`<tr class='row-tgh'>
                                <td width='5%' class='no-tgh'>`+no+`</td>
                                <td width='35%'><input type='text' name='kode_produk[]' class='form-control inp-prod' value='`+line.kode_produk+`' readonly></td>
                                <td width='30%' style='text-align:right'><input type='text' name='nilai[]' class='form-control inp-nil currency'  value='`+line.nilai+`' readonly required></td>
                                <td width='30%' style='text-align:right'><input type='text' name='nilai_byr[]' class='form-control inp-nil_byr currency'  value='`+line.nilai_byr+`' required></td>
                                </tr>`;
                            no++;
                        }
                    }

                    var no=1;
                    var input2='';
                    if(result.daftar3.length>0){
                        for(var i=0;i< result.daftar3.length;i++){
                            var line2 = result.daftar3[i];
                            input2 += "<tr class='row-dok'>";
                            input2 += "<td width='5%'  class='no-dok'>"+no+"</td>";
                            input2 += "<td width='60%'><input type='text' name='nama_dok[]' class='form-control inp-dok' value='"+line2.nama+"' required></td>";
                            input2 += "<td width='50%'>"+
                            "<input type='file' name='file_dok[]'>"+
                            "</td>";
                            input2 += "<td width='5%'><a class='btn btn-danger btn-sm hapus-dok' style='font-size:8px'><i class='fa fa-times fa-1'></i></td>";
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
                    $('#saku-data-aju').hide();
                    $('#form-tambah-aju').show();
                }
            }
        });
    });


    $('#saku-data-aju').on('click','#btn-history',function(e){
        var id = $(this).closest('tr').find('td').eq(0).html();
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Pengajuan.php?fx=getHistory',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?=$kode_lokasi?>','no_bukti':id,'kode_pp':'<?=$kode_pp?>'},
            success:function(result){    
                if(result.status){
                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                        var html='';
                        for(var i=0;i<result.daftar.length;i++){
                            html +=`<div class="sl-item"> <div class="sl-left" style="margin-left: -65px;"> <div style="padding: 10px;border: 1px solid #03a9f3;border-radius: 50%;background: #03a9f3;color: white;width: 50px;text-align: center;"><i style="font-size: 25px;" class="fas fa-clipboard-check"></i> </div> 
                                </div>
                                <div class="sl-right">
                                    <div><a href="javascript:void(0)" class="link">`+result.daftar[i].nama+`</a> <span class="sl-date">`+result.daftar[i].tanggal+`</span>
                                    <div class="row mt-3 mb-2">
                                        <div class="col-md-6">No Bukti : </div>
                                        <div class="col-md-6">`+result.daftar[i].no_bukti+`</div>
                                        <div class="col-md-6">Catatan : </div>
                                        <div class="col-md-6">`+result.daftar[i].keterangan+`</div>
                                    </div>
                            </div>
                            </div>
                            <hr>`;
                        }
                        
                        $('.profiletimeline').html(html);
                        
                        $('#slide-history').show();
                        $('#saku-data-aju').hide();
                        $('#form-tambah-aju').hide();
                    }
                }
            }
        });
    });

    $('#saku-data-aju').on('click','#btn-print',function(e){
        var id = $(this).closest('tr').find('td').eq(0).html();
        printAju(id);
    });

    
    $('#saku-data-aju').on('click','#btn-delete',function(e){
        Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.value) {
                var kode = $(this).closest('tr').find('td:eq(0)').html(); 
                var kode_lokasi = '<?php echo $kode_lokasi; ?>';        
                
                $.ajax({
                    type: 'DELETE',
                    url: '<?=$root_ser?>/Pengajuan.php',
                    dataType: 'json',
                    async:false,
                    data: {'no_bukti':kode,'kode_lokasi':kode_lokasi,'kode_pp':'<?=$kode_pp?>'},
                    success:function(result){
                        if(result.status){
                            dataTable.ajax.reload();
                            Swal.fire(
                                'Deleted!',
                                'Your file has been deleted.',
                                'success'
                            )
                        }else{
                            Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong!',
                            footer: '<a href>'+result.message+'</a>'
                            })
                        }
                    }
                });
                
            }else{
                return false;
            }
        })
    });

    
    

    $('.inp-nil').inputmask("numeric", {
        radixPoint: ",",
        groupSeparator: ".",
        digits: 2,
        autoGroup: true,
        rightAlign: true,
        oncleared: function () { self.Value(''); }
    });

    $('#input-grid2').on('keydown', '.inp-nil_byr', function(e){
        if (e.which == 13 || e.which == 9) {
            hitungBayar();
        }
    });

    $('#input-grid2').on('change', '.inp-nil_byr', function(e){
        // if (e.which == 13 || e.which == 9) {
            e.preventDefault();
            hitungBayar();
        // }
    });
   

    $('#no_ktp,#nama_wali,#alamat,#no_telp,#nis,#nama_siswa,#tgl_bayar').keydown(function(e){
        var code = (e.keyCode ? e.keyCode : e.which);
        var nxt = ['no_ktp','nama_wali','alamat','no_telp','nis','nama_siswa','tgl_bayar'];
        if (code == 13 || code == 40) {
            e.preventDefault();
            var idx = nxt.indexOf(e.target.id);
            idx++;
           
            $('#'+nxt[idx]).focus();
            
        }else if(code == 38){
            e.preventDefault();
            var idx = nxt.indexOf(e.target.id);
            idx--;
            if(idx != -1){ 
                $('#'+nxt[idx]).focus();
            }
        }
    });

    $('#slide-print').on('click','#btn-aju-print',function(e){
        e.preventDefault();
        var w=window.open();
        var html =`<html><head>
                <meta charset="utf-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1">
                <meta name="description" content="">
                <meta name="author" content="">
                <title>SAKU | Sistem Akuntansi Keuangan Digital</title>
                <link href="<?php echo $folder_css?>/style.min.css" rel="stylesheet">
                <!-- Dashboard 1 Page CSS -->
                <link href="<?php echo $folder_css?>/pages/dashboard1.css" rel="stylesheet">
                <link rel="stylesheet" type="text/css" href="<?php echo $folder_assets?>/node_modules/datatables.net-bs4/css/dataTables.bootstrap4.css">
                <!-- SAI CSS -->
                <link href="<?php echo $folder_css?>/sai.css" rel="stylesheet">
                
            </head>
            <!--
            <body class="skin-default fixed-layout" >-->
                <div id="main-wrapper" style='color:black'>
                    <div class="page-wrapper" style='min-height: 674px;margin: 0;padding: 10px;background: white;color: black !important;'>
                        <section class="content" id='ajax-content-section' style='color:black !important'>
                            <div class="container-fluid mt-3">
                                <div class="row" id="slide-print">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-body">`+$('#print-area').html()+`
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            <!--</body></html>-->
            `;
            w.document.write(html);
            setTimeout(function(){
                w.print();
                w.close();
            }, 1500);
    });


    function printAju(id){
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Pengajuan.php?fx=getPrint',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?=$kode_lokasi?>','no_bukti':id,'kode_pp':'<?=$kode_pp?>'},
            success:function(result){    
                if(result.status){
                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                        var det='';
                        var no= ['a','b','c','d','e'];
                        var total=0;var total_byr=0;
                        for(var i=0;i<result.daftar2.length;i++){
                            total+=+parseFloat(result.daftar2[i].nilai);
                            total_byr+=+parseFloat(result.daftar2[i].nilai_byr);
                            det +=`<tr>
                                <td width='20%'>`+no[i]+`. `+result.daftar2[i].kode_produk+`</td>
                                <td width='5%'>:&nbsp;</td>
                                <td width='20%' class='text-right'>`+toRp(result.daftar2[i].nilai)+`</td>
                                <td width='60%'>&nbsp;</td>
                            </tr>`;
                        }
                        det +=`<tr>
                                <td colspan='2'>Jumlah Kesanggupan Bayar</td>
                                <td class='text-right'>`+toRp(total_byr)+`</td>
                                <td width='60%'>&nbsp;</td>
                            </tr>`;

                        var html=`<div class="row">
                                <div class="col-12" style='text-align:center'>
                                    <h3 style='font-weight'><u>SURAT PERNYATAAN KESANGGUPAN MEMBAYAR</u></h3>
                                </div>
                                <div class="col-12 my-2" style=''>
                                    <h6>Yang bertanda tangan dibawah ini :</span></h6>
                                </div>
                                <div class="col-12">
                                <style>
                                    td{
                                        padding:4px !important;
                                        font-size: 13px;
                                    }
                                </style>
                                    <table class="table no-border" width="100%" id='table-m'>
                                        <tbody>
                                            <tr>
                                                <td width="25%">Nama Orang Tua</td>
                                                <td width="5">:</td>
                                                <td width="70%" id='print-unit'>`+result.daftar[0].nama_ortu+`</td>
                                            </tr>
                                            <tr>
                                                <td width="25%">Alamat</td>
                                                <td width="5">:</td>
                                                <td width="70%" id='print-unit'>`+result.daftar[0].alamat+`</td>
                                            </tr>
                                            <tr>
                                                <td width="25%">No.Telp/HP</td>
                                                <td width="5">:</td>
                                                <td width="70%" id='print-unit'>`+result.daftar[0].no_telp+`</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-12">
                                    <h6 style=''>Sebagai orang tua/wali dari :</h6>
                                </div>
                                <div class="col-12">
                                    <table class="table no-border" width="100%" id="table-d">
                                        <tbody>
                                            <tr>
                                                <td width="25%">Nama Siswa</td>
                                                <td width="5">:</td>
                                                <td width="70%" id='print-unit'>`+result.daftar[0].nama_siswa+`</td>
                                            </tr>
                                            <tr>
                                                <td width="25%">Kelas</td>
                                                <td width="5">:</td>
                                                <td width="70%" id='print-unit'>`+result.daftar[0].nama_kelas+`</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-12">
                                    <p style='text-align:justify'>Dengan ini menyatakan kesanggupan untuk menyelesaikan tunggakan administratif siswa dengan rincian sebagai berikut :</p>
                                </div>
                                <div class="col-12">
                                    <table class="table no-border" width="100%" id="table-penutup">
                                        <tbody>`+det+`
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-12">
                                    <p style='text-align:justify'>Tunggakan tersebut akan dilunasi selambat lambatnya pada tanggal `+result.daftar[0].tgl_bayar.substr(8,2)+` bulan `+getNamaBulan(result.daftar[0].tgl_bayar.substr(5,2))+` tahun `+result.daftar[0].tgl_bayar.substr(0,4)+` dan apabila tidak dapat memenuhi persyaratan tersebut maka kami bersedia menerima sanksi yang diberikan oleh pihak sekolah. Demikian surat pernyataan kesediaan ini kami buat dengan sebenarnya tanpa ada paksaan dari pihak manapun.</p>
                                </div>
                                <div class="col-12">
                                    <p>Nama Kota,  `+result.daftar[0].tgl_input.substr(8,2)+` `+getNamaBulan(result.daftar[0].tgl_input.substr(5,2))+` `+result.daftar[0].tgl_input.substr(0,4)+` </p>
                                    <h6>Yang membuat pernyataan</h6>
                                    <h6>Orang Tua/Wali Siswa</h6>
                                    <div style='height:80px'></div>
                                    <h6>`+result.daftar[0].nama_ortu+`</h6>
                                </div>
                                <div class="col-12">
                                    <p>&nbsp;</p>
                                </div>
                                <div class="col-4">
                                    <h6>Diperiksa Oleh</h6>
                                    <h6>Wali Kelas</h6>
                                    <div style='height:80px'></div>
                                    <h6>`+result.daftar[0].nama_wali+`</h6>
                                </div>
                                <div class="col-4">
                                    <h6>Disetujui Oleh</h6>
                                    <h6>Ka Admin</h6>
                                    <div style='height:80px'></div>
                                    <h6>`+result.daftar[0].nama_app1+`</h6>
                                </div>
                                <div class="col-4">
                                    <h6>Disetujui Oleh</h6>
                                    <h6>Ka Sekolah</h6>
                                    <div style='height:80px'></div>
                                    <h6>`+result.daftar[0].nama_app2+`</h6>
                                </div>
                            </div>`;
                            $('#print-area').html(html);
                            $('#slide-print').show();
                            $('#saku-data-aju').hide();
                            $('#form-tambah-aju').hide();
                    }
                }
            }
        });
    }

    </script>

    
