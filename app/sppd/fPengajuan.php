<?php
   $kode_lokasi=$_SESSION['lokasi'];
   $nik=$_SESSION['userLog'];
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
                        <!-- <h6 class="card-subtitle">Tabel Pengajuan</h6> -->
                        <hr>
                        <div class="table-responsive ">
                            <table id="table-aju" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No Bukti</th>
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
                                    <input class="form-control" type="text" placeholder="No Bukti" id="no_bukti" name="no_bukti" hidden>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">Tanggal</label>
                                <div class="col-3">
                                    <input class="form-control" type="date" placeholder="tanggal" id="tanggal" name="tanggal" value="<?=date('Y-m-d')?>" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="no_dokumen" class="col-3 col-form-label">No Dokumen</label>
                                <div class="col-3">
                                    <input class="form-control" type="text" placeholder="No Dokumen" id="no_dokumen" name="no_dokumen" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">Kode PP</label>
                                <div class="col-3">
                                    <select class='form-control' id="kode_pp" name="kode_pp" required>
                                    <option value=''>--- Pilih PP ---</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">Waktu</label>
                                <div class="col-3">
                                    <input class="form-control" type="date" placeholder="Waktu" id="waktu" name="waktu" value="<?=date('Y-m-d')?>" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">Kegiatan</label>
                                <div class="col-9">
                                    <input class="form-control" type="text" placeholder="Kegiatan" id="kegiatan" name="kegiatan" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">Dasar</label>
                                <div class="col-9">
                                    <input class="form-control" type="text" placeholder="Dasar" id="dasar" name="dasar" required>
                                </div>
                            </div>
                            <!-- <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">NIK Buat</label>
                                <div class="col-3">
                                    <select class='form-control' id="nik_buat" name="nik_buat" required>
                                    <option value=''>--- Pilih NIK Buat ---</option>
                                    </select>
                                </div>
                            </div> -->
                            <div class="form-group row">
                                <label for="nama" class="col-3 col-form-label">Total Barang</label>
                                <div class="col-3">
                                    <input class="form-control text-right" type="text"  id="total" name="total" required readonly>
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
                                                <th width="5%"><button type="button" href="#" id="add-row" class="btn btn-default"><i class="fa fa-plus-circle"></i></button></th>
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

    function hitungBrg(){
        $('#total').val(0);
        total= 0;
        $('.row-barang').each(function(){
            var sub = toNilai($(this).closest('tr').find('.inp-sub').val());
            var this_val = sub;
            total += +this_val;
            
            $('#total').val(sepNum(total));
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

    function printAju(id){
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Pengajuan.php?fx=getPrint',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?=$kode_lokasi?>','no_bukti':id},
            success:function(result){    
                if(result.status){
                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                        var det='';
                        var no=1;var total=0;
                        for(var i=0;i<result.daftar2.length;i++){
                            total+=+result.daftar2[i].nilai;
                            det +=`<tr>
                                <td>`+no+`</td>
                                <td>`+result.daftar2[i].barang+`</td>
                                <td class='text-right'>`+toRp(result.daftar2[i].harga)+`</td>
                                <td class='text-right'>`+toRp(result.daftar2[i].jumlah)+`</td>
                                <td class='text-right'>`+toRp(result.daftar2[i].nilai)+`</td>
                            </tr>`;
                            no++;
                        }
                        det +=`<tr>
                                <td colspan='4'>Total</td>
                                <td class='text-right'>`+toRp(total)+`</td>
                            </tr>`;

                        var no=1;var det2='';
                        for(var i=0;i<result.daftar3.length;i++){
                            det2 +=`<tr>
                                <td>Dibuat Oleh</td>
                                <td>`+result.daftar3[i].nama_kar+`/`+result.daftar3[i].nik+`</td>
                                <td>`+result.daftar3[i].nama_jab+`</td>
                                <td>`+result.daftar3[i].tanggal+`</td>
                                <td>&nbsp;</td>
                            </tr>`;
                            no++;
                        }
                        var html=`<div class="row">
                                <div class="col-12" style='border-bottom:3px solid black;text-align:center'>
                                    <h3>JUSTIFIKASI PENGADAAN</h3>
                                    <h3 id='print-kegiatan'>`+result.daftar[0].kegiatan+`</h3>
                                </div>
                                <div class="col-12 my-2" style='text-align:center'>
                                    <h6>Tanggal : <span id='print-tgl'>`+result.daftar[0].tanggal.substr(8,2)+' '+getNamaBulan(result.daftar[0].tanggal.substr(5,2))+' '+result.daftar[0].tanggal.substr(0,4)+`</span></h6>
                                    <h6>Nomor : <span id='print-no_dokumen'>`+result.daftar[0].no_dokumen+`</span></h6>
                                </div>
                                <div class="col-12">
                                    <table class="table table-condensed table-bordered" width="100%" id='table-m'>
                                        <tbody>
                                            <tr>
                                                <td width="5%">1</td>
                                                <td width="25">UNIT KERJA</td>
                                                <td width="70%" id='print-unit'>`+result.daftar[0].nama_pp+`</td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>NAMA KEGIATAN</td>
                                                <td id='print-kegiatan2'>`+result.daftar[0].kegiatan+`</td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>SAAT PENGGUNAAN</td>
                                                <td id='print-waktu'>`+result.daftar[0].waktu.substr(8,2)+' '+getNamaBulan(result.daftar[0].waktu.substr(5,2))+' '+result.daftar[0].waktu.substr(0,4)+`</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-12">
                                    <h6 style='font-weight:bold'># <u>KEBUTUHAN</u></h6>
                                </div>
                                <div class="col-12">
                                    <table class="table table-condensed table-bordered" width="100%" id="table-d">
                                        <thead>
                                            <tr>
                                                <td width="5%">No</td>
                                                <td width="25">Deskripsi</td>
                                                <td width="15%">Harga</td>
                                                <td width="25%">Qty</td>
                                                <td width="30%">Jumlah Harga</td>
                                            </tr>
                                        </thead>
                                        <tbody>`+det+`
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-12">
                                    <h6 style='font-weight:bold'># <u>ESTIMASI BIAYA</u></h6>
                                    <p>Estimasi Biaya yang dibutuhkan untuk pengadaan tersebut adalah sebesar <span id='estimasi-biaya' style='text-transform: capitalize;'>`+'Rp. '+toRp(result.daftar[0].nilai)+' ( '+terbilang(result.daftar[0].nilai)+' Rupiah)'+`</span> </p>
                                </div>
                                <div class="col-12">
                                    <h6 style='font-weight:bold'># <u>PENUTUP</u></h6>
                                </div>
                                <div class="col-12">
                                    <table class="table table-condensed table-bordered" width="100%" id="table-penutup">
                                        <thead class="text-center">
                                            <tr>
                                                <td width="20%"></td>
                                                <td width="25">NAMA/NIK</td>
                                                <td width="25%">JABATAN</td>
                                                <td width="15%">TANGGAL</td>
                                                <td width="15%">TTD</td>
                                            </tr>
                                        </thead>
                                        <tbody>`+det2+`
                                        </tbody>
                                    </table>
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

    getPP();

    var $iconLoad = $('.preloader');
    var action_html = "<a href='#' title='Edit' class='badge badge-warning' id='btn-edit'><i class='fas fa-pencil-alt'></i></a> &nbsp; <a href='#' title='Hapus' class='badge badge-danger' id='btn-delete'><i class='fa fa-trash'></i></a>&nbsp; <a href='#' title='History' class='badge badge-success' id='btn-history'><i class='fas fa-history'></i></a>&nbsp; <a href='#' title='Preview' class='badge badge-info' id='btn-print'><i class='fas fa-print'></i></a>";
    var kode_lokasi = '<?php echo $kode_lokasi ?>';
    var dataTable = $('#table-aju').DataTable({
        'processing': true,
        'serverSide': true,
        'ajax': {
            'url': '<?=$root_ser?>/Pengajuan.php?fx=getPengajuan',
            'data': {'kode_lokasi':kode_lokasi},
            'async':false,
            'type': 'GET',
            'dataSrc' : function(json) {
                return json.data;   
            }
        },
        'columnDefs': [
            // {'targets': 7, data: null, 'defaultContent': action_html },
            {   'targets': 6, 
                'className': 'text-right',
                'render': $.fn.dataTable.render.number( '.', ',', 0, '' ) 
            }
        ]
    });

    $('#saku-data-aju').on('click', '#btn-aju-tambah', function(){
        $('#row-id').hide();
        $('#id').val('');
        $('#saku-data-aju').hide();
        $('#form-tambah-aju').show();
        $('#form-tambah')[0].reset();
    });

    $('#form-tambah-aju').on('click', '#add-row', function(){

        var no=$('#input-grid2 .row-barang:last').index();
        no=no+2;
        var input = "";
        input += "<tr class='row-barang'>";
        input += "<td width='5%' class='no-barang'>"+no+"</td>";
        input += "<td width='45%'><input type='text' name='barang[]' class='form-control inp-brg' value='' required></td>";
        input += "<td width='15%' style='text-align:right'><input type='text' name='harga[]' class='form-control currency inp-hrg'  value='0' required></td>";
        input += "<td width='10%' style='text-align:right'><input type='text' name='qty[]' class='form-control currency inp-qty'  value='0' required></td>";
        input += "<td width='20%' style='text-align:right'><input type='text' name='nilai[]' class='form-control currency inp-sub' readonly value='0' required></td>";
        input += "<td width='5%'><a class='btn btn-danger btn-sm hapus-item' style='font-size:8px'><i class='fa fa-times fa-1'></i></td>";
        input += "</tr>";
        $('#input-grid2 tbody').append(input);
        $('.currency').inputmask("numeric", {
            radixPoint: ",",
            groupSeparator: ".",
            digits: 2,
            autoGroup: true,
            rightAlign: true,
            oncleared: function () { self.Value(''); }
        });
        $('#input-grid2 tbody tr:last').find('.inp-brg').focus();
    });

    $('#input-grid2').on('keydown', '.inp-brg', function(e){
        if (e.which == 13 || e.which == 9) {
            e.preventDefault();
            $(this).closest('tr').find('.inp-hrg').focus();
        }
    });

    $('#input-grid2').on('keydown', '.inp-hrg', function(e){
        if (e.which == 13 || e.which == 9) {
            e.preventDefault();
            var hrg = $(this).closest('tr').find('.inp-hrg').val();
            var qty = $(this).closest('tr').find('.inp-qty').val();
            var sub = toNilai(hrg)*toNilai(qty);
            $(this).closest('tr').find('.inp-qty').focus();
            $(this).closest('tr').find('.inp-sub').val(sub);
            hitungBrg();
        }
    });

    $('#input-grid2').on('change', '.inp-hrg', function(e){
        // if (e.which == 13 || e.which == 9) {
            e.preventDefault();
            var hrg = $(this).closest('tr').find('.inp-hrg').val();
            var qty = $(this).closest('tr').find('.inp-qty').val();
            var sub = toNilai(hrg)*toNilai(qty);
            $(this).closest('tr').find('.inp-qty').focus();
            $(this).closest('tr').find('.inp-sub').val(sub);
            hitungBrg();
        // }
    });

    $('#input-grid2').on('keydown', '.inp-qty', function(e){
        if (e.which == 13 || e.which == 9) {
            e.preventDefault();
            var hrg = $(this).closest('tr').find('.inp-hrg').val();
            var qty = $(this).closest('tr').find('.inp-qty').val();
            var sub = toNilai(hrg)*toNilai(qty);
            $(this).closest('tr').find('.inp-sub').val(sub);
            hitungBrg();
            $('#add-row').click();
        }
    });

    $('#input-grid2').on('change', '.inp-qty', function(e){
        // if (e.which == 13 || e.which == 9) {
            e.preventDefault();
            var hrg = $(this).closest('tr').find('.inp-hrg').val();
            var qty = $(this).closest('tr').find('.inp-qty').val();
            var sub = toNilai(hrg)*toNilai(qty);
            $(this).closest('tr').find('.inp-sub').val(sub);
            hitungBrg();
        // }
    });

    $('#form-tambah-aju').on('click', '#add-row-dok', function(){
        // $('.modal-title').html('Tambah Dokumen');
        // $('#modal-id-dok').val(0);
        // $('#modal-dok-nama').val('');
        // $('#modal-dok').modal('show');
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

    $('#saku-data-aju').on('click', '#btn-edit', function(){
        var id= $(this).closest('tr').find('td').eq(0).html();
       
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Pengajuan.php?fx=getEdit',
            dataType: 'json',
            async:false,
            data: {'no_bukti':id,'kode_lokasi':kode_lokasi},
            success:function(result){
                if(result.status){
                    $('#id').val('edit');
                    $('#no_bukti').val(id);
                    // $('#kode_barang').attr('readonly', true);
                    $('#tanggal').val(result.daftar[0].tanggal);
                    $('#no_dokumen').val(result.daftar[0].no_dokumen);
                    $('#kode_pp')[0].selectize.setValue(result.daftar[0].kode_pp);
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
                            input += "<td width='45%'><input type='text' name='barang[]' class='form-control inp-brg' value='"+line.barang+"' required></td>";
                            input += "<td width='15%' style='text-align:right'><input type='text' name='harga[]' class='form-control inp-hrg currency'  value='"+toRp(line.harga)+"' required></td>";
                            input += "<td width='10%' style='text-align:right'><input type='text' name='qty[]' class='form-control inp-qty currency'  value='"+line.jumlah+"' required></td>";
                            input += "<td width='20%' style='text-align:right'><input type='text' name='nilai[]' class='form-control inp-sub currency' readonly value='"+toRp(line.nilai)+"' required></td>";
                            input += "<td width='5%'><a class='btn btn-danger btn-sm hapus-item' style='font-size:8px'><i class='fa fa-times fa-1'></i></td>";
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


    $('#form-tambah-aju').on('click', '#btn-aju-kembali', function(){
        $('#saku-data-aju').show();
        $('#form-tambah-aju').hide();
        $('#slide-history').hide();
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

    $('#saku-data-aju').on('click','#btn-history',function(e){
        var id = $(this).closest('tr').find('td').eq(0).html();
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Pengajuan.php?fx=getHistory',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?=$kode_lokasi?>','no_bukti':id},
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
                    data: {'no_bukti':kode,'kode_lokasi':kode_lokasi},
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

            formData.append('nik_user', nik);
            formData.append('kode_lokasi', kode_lokasi);

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
    

    $('.inp-hrg').inputmask("numeric", {
        radixPoint: ",",
        groupSeparator: ".",
        digits: 2,
        autoGroup: true,
        rightAlign: true,
        oncleared: function () { self.Value(''); }
    });
    
    $('#input-grid2').on('click', '.hapus-item', function(){
        $(this).closest('tr').remove();
        no=1;
        $('.row-barang').each(function(){
            var nom = $(this).closest('tr').find('.no-barang');
            nom.html(no);
            no++;
        });
        hitungBrg();
        $("html, body").animate({ scrollTop: $(document).height() }, 1000);
    });


    $('#input-dok').on('click', '.hapus-dok', function(){
        $(this).closest('tr').remove();
        no=1;
        $('.row-dok').each(function(){
            var nom = $(this).closest('tr').find('.no-dok');
            nom.html(no);
            no++;
        });
        $("html, body").animate({ scrollTop: $(document).height() }, 1000);
    });

    $('#tanggal,#no_dokumen,#kode_pp,#waktu,#kegiatan,#dasar').keydown(function(e){
        var code = (e.keyCode ? e.keyCode : e.which);
        var nxt = ['tanggal','no_dokumen','kode_pp','waktu','kegiatan','dasar'];
        if (code == 13 || code == 40) {
            e.preventDefault();
            var idx = nxt.indexOf(e.target.id);
            idx++;
            if(idx == 2){
                $('#'+nxt[idx])[0].selectize.focus();
            }else if(idx == 6){
                $('#add-row').click();
            }
            else{
                $('#'+nxt[idx]).focus();
            }
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


    </script>

    
