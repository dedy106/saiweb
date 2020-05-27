<?php
    $kode_lokasi=$_COOKIE['lokasi'];
    $nik=$_COOKIE['userLog'];
    $kode_pp=$_COOKIE['kodePP'];
?>
<style>
   #edit-qty{
       cursor:pointer
   }
</style>
<div class="container-fluid mt-3">
    <div class="row" >
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <form class="form" id="web-form-pos" method='POST'>
                        <div class='row'>
                            <div class='col-md-4'>
                                <div class='row'>
                                    <div class='col-md-4'>
                                        <div class='logo text-center'>
                                            <img src="<?=$folder_assets?>/images/sai_icon/logo.png" width="40px" alt="homepage" class="light-logo" /><br/>
                                            <img src="<?=$folder_assets?>/images/sai_icon/logo-text.png" class="light-logo" alt="homepage" width="40px"/>
                                        </div>
                                    </div>
                                    <div class='col-md-8'>
                                        <div class='label-header'>
                                            <h6><?=date('Y-m-d H:i:s')?></h6>
                                            <h6 style="color:#007AFF"><i class='fa fa-user'></i> <?php echo $nik;?></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class='col-md-3 text-right'>
                                <h5>Nilai Transaksi </h5>
                                
                                <div class='row  float-right'>
                                    <div class="text-left" id='edit-qty' style="width: 90px;height:42px;padding: 5px;border: 1px solid #d0cfcf;background: white;border-radius: 5px;vertical-align: middle;margin-right:5px">
                                    <img style='width:30px;height:30px;position:absolute' src='<?=$folderroot_img?>/edit.png'>
                                    <h6 style="font-size: 10px;padding-left: 35px;margin-bottom: 0;margin-top: 5px;text-align:center">Edit Qty</h6>
                                    <h6 style="font-size: 9px;padding-left: 35px;text-align:center">F7</h6></div>
                                </div>
                            </div>
                            <div class='col-md-5'>
                                <h3><input type='text' style='font-size: 40px;'  name="total_stlh" min="1" class='form-control currency' id='tostlh' required readonly></h3>
                            </div>
                            <div class='col-md-12'>
                                <table class='table' style='margin-bottom: 5px'>
                                    <tr>
                                        <td style='padding: 3px;width:25%' colspan='2'>
                                        <input type='text' class='form-control' placeholder="Barcode [F1]" id="kd-barang2" >
                                        </td>
                                        <td style='padding: 3px;width:25%' colspan='2'>
                                            <select class='form-control' id="kd-barang">
                                                <option value=''>--- Pilih Barang [CTRL+C] ---</option>
                                            </select>
                                        </td>
                                        <th style='padding: 3px;width:25%'>
                                        <select class='form-control' id="kode_vendor" name='kode_vendor' required >
                                                <option value=''>--- Pilih Vendor ---</option>
                                        </select></th>
                                        <th style='padding: 3px;width:5%'>Total</th>
                                        <th style='padding: 3px;width:20%'><input type='text' name="total_trans" min="1" class='form-control currency' id='totrans' required readonly></th>
                                        <td style='padding: 0px;'><input type='hidden' class='form-control currency' id="hrg-barang" readonly></td>
                                        <td style='padding: 0px;'><input type='hidden' min='1' step='1' class='form-control currency' id="qty-barang"></td>
                                        <td style='padding: 0px;'><input type='hidden' min='1' step='1' class='form-control currency' placeholder='disc%' id="disc-barang"></td>
                                        <td style='padding: 0px;'><a class='btn btn-warning pull-right' id='tambah-barang'><i class='fa fa-plus'></i> Tambah</a></td>
                                    </tr>
                                </table>
                                <div class='col-xs-12' style='overflow-y: scroll; height:300px; margin:0px; padding:0px;'>
                                    <table class="table table-striped table-bordered table-condensed gridexample" id="input-grid2">
                                        <tr>
                                            <th>Barang</th>
                                            <th>Harga</th>
                                            <th>Satuan</th>
                                            <th>Qty</th>
                                            <th>Disc</th>
                                            <th>Subtotal</th>
                                        </tr>
                                        <!-- <tr>
                                            <th>1</th>
                                            <td><input type="text" class='form-control' /></td>
                                            <td><input type="text" class='form-control' /></td>
                                            <td><input type="text" class='form-control' /></td>
                                            <td><input type="text" class='form-control' /></td>
                                            <td><input type="text" class='form-control' /></td>
                                        </tr>
                                        <tr>
                                            <th>2</th>
                                            <td><input type="text" class='form-control' /></td>
                                            <td><input type="text" class='form-control' /></td>
                                            <td><input type="text" class='form-control' /></td>
                                            <td><input type="text" class='form-control' /></td>
                                            <td><input type="text" class='form-control' /></td>
                                        </tr> -->
                                    </table>
                                </div>
                                <div class='row'>
                                    <div class='col-md-7 mt-2'>
                                        <div class='form-group row'>
                                            
                                            <label for="judul" class="col-2 col-form-label" style="font-size:16px">Disc.</label>
                                            <div class="col-4">
                                            <input type="text" name="total_disk" min="1" class='form-control currency' id='todisk' required value="0">
                                            </div>
                                            <label for="judul" class="col-1 col-form-label" style="font-size:16px">PPN</label>
                                            <div class="col-5">
                                                <div class="input-group mb-3">
                                                    <input type="text" name="total_ppn" min="1" class='form-control currency' id='toppn' required value="0">
                                                    <div class="input-group-append">
                                                        <button class="btn btn-info" id="getPPN" type="button"><i class="mdi mdi-sync"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class='col-md-5 mt-2'>
                                        <div class='form-group row float-right'>
                                            
                                            <div class='col-12'>
                                                <button class='btn btn-info' type="submit" id='btnBayar'><i class="fa fa-save"></i> Simpan</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php
    include $root_app.'dist/php/right-sidebar.php';
?>
</div>

<!-- FORM MODAL BAYAR -->
<div class='modal' id='modal-bayar' tabindex='-1' role='dialog'>
    <div class='modal-dialog modal-sm' role='document'>
        <div class='modal-content'>
            <div class='modal-header'>
                <h5 class='modal-title'>Pilih Nominal</h5>
                <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                </button>
            </div>
            <div class='modal-body'>
                <div class='row mb-2' style="text-align: center;">
                <a class="btn btn-lg btn-secondary" id="nom0" style="width: 126px;">Uang Pas</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class="btn btn-lg btn-secondary" id='nom1' style="width: 126px;">1.000</a></div>
                <div class='row mb-2'><a class="btn btn-lg btn-secondary" id='nom2' style="width: 126px;">2.000</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class="btn btn-lg btn-secondary" id='nom3' style="width: 126px;">5.000</a></div>
                <div class='row mb-2'><a class="btn btn-lg btn-secondary" id='nom4' style="width: 126px;">10.000</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class="btn btn-lg btn-secondary" id='nom5' style="width: 126px;">20.000</a></div>
                <div class='row mb-2'><a class="btn btn-lg btn-secondary" id='nom6' style="width: 126px;">50.000</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class="btn btn-lg btn-secondary" id='nom7' style="width: 126px;">100.000</a></div>
                <div class='form-group row'>
                    <label for="judul" class="col-3 col-form-label">Nominal Bayar</label>
                    <div class="col-9">
                    <input type='text' class='form-control currency' maxlength='100' id='inp-byr' readonly>
                    </div>
                </div>
                <div class='form-group row'>
                    <div class="col-9">
                    <input type='hidden' class='form-control' id='param' readonly>
                    </div>
                </div>
            </div>
            <div class='modal-footer'>
            <button type='button' id='btn-ok' class='btn btn-success'>OK</button>
            <button type='button' id='btn-clear' class='btn btn-default'>C</button>
            </div>
        </div>
    </div>
</div>
<!-- FORM EDIT MODAL -->
<div class='modal' id='modal-edit' tabindex='-1' role='dialog'>
    <div class='modal-dialog' role='document'>
            <div class='modal-content'>
                <form id='form-edit-barang'>
                    <div class='modal-header'>
                        <h5 class='modal-title'>Edit Barang</h5>
                        <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                        </button>
                    </div>
                    <div class='modal-body'>
                        <div class="form-group row mt-40">
                            <label for="judul" class="col-3 col-form-label">Barang</label>
                            <div class="col-9">
                                <select class='form-control' id='modal-edit-kode' readonly>
                                    <option value=''>--- Pilih Barang ---</option>
                                </select>
                            </div>
                        </div>
                        <div class='form-group row'>
                            <label for="judul" class="col-3 col-form-label">Harga</label>
                            <div class="col-9">
                                <input type='text' class='form-control currency' readonly maxlength='100' id='modal-edit-harga'>
                            </div>
                        </div>
                        <div class='form-group row'>
                            <label for="judul" class="col-3 col-form-label">Disc</label>
                            <div class="col-9">
                                <input type='text' class='form-control currency' maxlength='100' id='modal-edit-disc' >
                            </div>
                        </div>
                        <div class='form-group row'>
                            <label for="judul" class="col-3 col-form-label">Qty</label>
                            <div class="col-9">
                                <input type='text' class='form-control currency ' maxlength='100' id='modal-edit-qty'>
                            </div>
                        </div>
                    </div>
                    <div class='modal-footer'>
                        <button type='button' id='edit-submit' class='btn btn-primary'>Simpan</button> 
                        <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
                    </div>
                </form>
            </div>
    </div>
</div>
<!-- FORM MODAL BAYAR 2-->
<div class="modal" id="modal-bayar2" tabindex="-1" role="dialog" aria-modal="true">
    <div role="document" style="" class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content" style="border-radius: 15px !important;">
            <div class="modal-header " style="display:block">
                <div class="row text-center" style="">
                    <div class="col-md-12">
                        <h5 class="">Total Transaksi</h5>
                        <h1 class="text-info" id="modal-toakhir">12.500</h1>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div class="row mb-2" style="">
                    <div class="col-6" style="">
                    Total Pembelian
                    </div>
                    <div class="col-6 text-right" id="modal-totrans">
                    300.800,26
                    </div>
                </div>
                <div class="row mb-2">
                <div class="col-6">
                    Diskon 
                    </div>
                    <div class="col-6 text-right" id="modal-diskon">
                    800,26
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-6">
                    PPN 
                    </div>
                    <div class="col-6 text-right" id="modal-ppn">
                    30000
                    </div>
                </div>
                <div id="modal-nobukti" hidden></div>
            </div>
            <div class="modal-footer" style="padding: 0;">
            <button id="cetakBtn" type="button" class="btn btn-info btn-block" style="border-bottom-left-radius: 15px;border-bottom-right-radius: 15px;">Cetak</button>
            </div>
        </div>
    </div>
</div>

<script src="<?=$folderroot_js?>/inputmask.js"></script>
<script src="<?=$folderroot_js?>/jquery.scannerdetection.js"></script>
<script src="<?=$folderroot_js?>/jquery.formnavigation.js"></script>
<script>
    $('.gridexample').formNavigation();
    var $submitBtn = false;
    var $dtBrg = new Array();
    var $dtBrg2 = new Array();
    $('#kd-barang').selectize({
        selectOnTab: true,    
        onChange: function (val){
            // $('#kd-barang').val(value);
            var id = val
            if (id != "" && id != null && id != undefined){
                addBarang();
            }
        }
    });
    function getBarang(){
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Pembelian.php?fx=getBarang',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?=$kode_lokasi?>'},
            success:function(result){    
                if(result.status){
                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                        var select = $('#modal-edit-kode').selectize();
                        select = select[0];
                        var control = select.selectize;
                        
                        for(i=0;i<result.daftar.length;i++){
                            control.addOption([{text:result.daftar[i].kode_barang + ' - ' + result.daftar[i].nama, value:result.daftar[i].kode_barang}]);
                            $('#kd-barang')[0].selectize.addOption([{text:result.daftar[i].kode_barang + ' - ' + result.daftar[i].nama, value:result.daftar[i].kode_barang}]);
                            $dtBrg[result.daftar[i].kode_barang] = {harga:result.daftar[i].harga,satuan:result.daftar[i].satuan,kode_akun:result.daftar[i].kode_akun};  
                            $dtBrg2[result.daftar[i].barcode] = {harga:result.daftar[i].harga,nama:result.daftar[i].nama,kd_barang:result.daftar[i].kode_barang,satuan:result.daftar[i].satuan,kode_akun:result.daftar[i].kode_akun};
                        }
                        
                    }
                }
            }
        });
    }

    function getVendor(){
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/Pembelian.php?fx=getVendor',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?=$kode_lokasi?>'},
            success:function(result){    
                if(result.status){
                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                        var select = $('#kode_vendor').selectize();
                        select = select[0];
                        var control = select.selectize;
                        for(i=0;i<result.daftar.length;i++){
                            control.addOption([{text:result.daftar[i].kode_vendor + ' - ' + result.daftar[i].nama, value:result.daftar[i].kode_vendor}]);
                        }
                        
                    }
                }
            }
        });
    }
    
    getVendor();
    getBarang();

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

    function setHarga2(id){
        if (id != ""){
            return $dtBrg[id].harga;  
        }else{
            return "";
        }
    
    };  
    function setHarga3(id){
        if (id != ""){
            return $dtBrg2[id].harga;  
        }else{
            return "";
        }
    
    };  
    function setNama(id){
        if (id != ""){
            return $dtBrg2[id].nama;  
        }else{
            return "";
        }
    
    };  
    function setSatuan(id){  
        if (id != ""){
            return $dtBrg[id].satuan;  
        }else{
            return "";
        }
    
    }; 
    function setAkun(id){  
        if (id != ""){
            return $dtBrg[id].kode_akun;  
        }else{
            return "";
        }
    
    };   
    function getKode(id){ 
        if (id != ""){
            return $dtBrg2[id].kd_barang;  
        }else{
            return "";
        }
    
    };  

    function hitungKembali(){
        
        var total_stlh = toNilai($('#tostlh').val());
        var total_bayar = toNilai($('#tobyr').val());

        if(total_bayar > 0 ){
            kembalian = +total_bayar - +total_stlh; 
            if(kembalian < 0) kembalian = 0;  
            $("#kembalian").val(toRp(kembalian));
        }
        
    }

    function getPPN(){
        
        var todisk = toNilai($('#todisk').val());
        var totrans = toNilai($('#totrans').val());
        var total = totrans - todisk;
        var ppn = (total * 10)/100;
        $("#toppn").val(toRp(ppn));
        var total2 = total+ppn;
        $("#tostlh").val(toRp(total2));
        
    }

    function hitungDisc(){
        
        var total_trans = toNilai($('#totrans').val());
        var total_disk= toNilai($('#todisk').val());
        var total_stlh = +total_trans - +total_disk;
        
        $('#tostlh').val(toRp(total_stlh));
        // var total_bayar = toNilai($('#tobyr').val());
        // if(total_bayar > 0 ){
        //     kembalian = +total_bayar - +total_stlh;  
        //     if(kembalian < 0) kembalian = 0; 
        //     $("#kembalian").val(toRp(kembalian));
        // }
        
    }

    function hitungTotal(){
        
        // hitung total barang
        if($('#todisk').val() == ""){
            
            $('#todisk').val(0);
        }
        var total_brg = 0;
        $('.row-barang').each(function(){
            var qtyb = $(this).closest('tr').find('.inp-qtyb').val();
            var hrgb = $(this).closest('tr').find('.inp-hrgb').val();
            var disc = $(this).closest('tr').find('.inp-disc').val();
            //var todis= (toNilai(hrgb) * toNilai(disc)) / 100
            var subb = (+qtyb * toNilai(hrgb)) - disc;
            $(this).closest('tr').find('.inp-subb').val(toRp(subb));
            total_brg += +subb;
        });
        $('#totrans').val(toRp(total_brg));

        var total_disk= toNilai($('#todisk').val());
        var total_stlh = +total_brg - +total_disk;
        
        $('#tostlh').val(toRp(total_stlh));
        // var total_bayar = toNilai($('#tobyr').val());
        // // alert(total_bayar);
        // if(total_bayar > 0 ){
        //     if(kembalian < 0) kembalian = 0;
        //     kembalian = +total_bayar - +total_stlh;
        //     // alert(total_trans);
        
        //     $("#kembalian").val(toRp(kembalian));
        // }
        
    }
    var count= 0;

    $('#modal-edit-kode').selectize({
        selectOnTab: true,
        onChange: function (){
        var id = $('#modal-edit-kode').val();
            setHarga(id);
        }
    });

    function setHarga(id){
        var post_url = "include_lib.php?hal=server/kasir/Pembelian.php&fx=getDataBarang";
        if(id == '' || id == null){
            $('#qty-barang').val('');
            $('#hrg-barang').val('');
        }else{

            var kode_lokasi= '<?php echo $kode_lokasi; ?>';
            $.ajax({
                url: post_url,
                data: { 'kode_barang' : id,'kode_lokasi' :kode_lokasi},
                type: "post",
                dataType: "json",
                cache: false,
                success: function (data) {
                    harga = data.harga_barang;
                    $('#modal-edit-harga').val(harga);
                    
                }
            });
            $('#modal-edit-qty').focus();
            // addBarang();
        }
    }

    function hapusBarang(rowindex){
        $("#input-grid2 tr:eq("+rowindex+")").remove();
        hitungTotal();
    }

    function ubahBarang(rowindex){
        $('.row-barang').removeClass('set-selected');
        $("#input-grid2 tr:eq("+rowindex+")").addClass('set-selected');

        var kd = $("#input-grid2 tr:eq("+rowindex+")").find('.inp-kdb').val();
        var qty = $("#input-grid2 tr:eq("+rowindex+")").find('.inp-qtyb').val();
        var harga = toNilai($("#input-grid2 tr:eq("+rowindex+")").find('.inp-hrgb').val());    
        var disc = $("#input-grid2 tr:eq("+rowindex+")").find('.inp-disc').val();

        $('#modal-edit-kode')[0].selectize.setValue(kd);
        $('#modal-edit-kode').val(kd);
        $('#modal-edit-qty').val(qty);
        $('#modal-edit-harga').val(harga);
        $('#modal-edit-disc').val(disc);
        
        $('#modal-edit').modal('show');
        var selectKode = $('#modal-edit-kode').data('selectize');
        selectKode.disable();

    }

    $('#edit-submit').click(function(e){
        e.preventDefault();
        
        var hrg = toNilai($('#modal-edit-harga').val());
        var qty = toNilai($('#modal-edit-qty').val());
        var disc = toNilai($('#modal-edit-disc').val());
        var kd = $('#modal-edit-kode option:selected').val();
        var nama = $('#modal-edit-kode option:selected').text();
        //var todis= (hrg * disc) / 100
        var sub = (+qty * +hrg) - disc;
        
        var input="";
        input = "<tr class='row-barang'>";
        input += "<td width='30%'>"+nama+"<input type='hidden' name='kode_barang[]' class='change-validation inp-kdb form-control' value='"+kd+"' readonly required></td>";
        input += "<td width='15%' style='text-align:right'><input type='text' name='harga_barang[]' class='change-validation inp-hrgb form-control'  value='"+toRp(hrg)+"' readonly required></td>";
        input += "<td width='5%' style='text-align:right'><input type='text' name='satuan_barang[]' class='change-validation inp-satuanb form-control'  value='"+setSatuan(kd)+"' readonly required><input type='hidden' name='kode_akun[]' class='change-validation inp-satuanb'  value='"+setAkun(kd)+"' readonly></td>";
        input += "<td width='15%' style='text-align:right'><input type='text' name='qty_barang[]' class='change-validation inp-qtyb form-control'  value='"+qty+"' required></td>";
        input += "<td width='10%' style='text-align:right'><input type='text' name='disc_barang[]' class='change-validation inp-disc form-control'  value='"+disc+"' readonly required></td>";
        input += "<td width='15%' style='text-align:right'><input type='text' name='sub_barang[]' class='change-validation inp-subb form-control'  value='"+toRp(sub)+"'  required></td>";
        input += "<td width='10%'></a><a class='btn btn-primary btn-sm ubah-barang' style='font-size:8px'><i class='fas fa-pencil-alt fa-1'></i></a>&nbsp;<a class='btn btn-danger btn-sm hapus-item' style='font-size:8px'><i class='fa fa-times fa-1'></i></td>";
        input += "</tr>";
        
        $('.set-selected').closest('tr').remove();
        // $('.set-selected').closest('tr').append(input);

        $("#input-grid2").append(input);
        hitungTotal();
        $('.gridexample').formNavigation();
        $('#modal-edit').modal('hide');
        
        
    });

    function addBarang(){

        var kd1 = $('#kd-barang').val();
        var qty1 = 1;
        var disc1 = 0;
        var hrg1 = setHarga2(kd1);
        // || +qty1 <= 0 || +hrg1 <= 0
        if(kd1 == '' || +hrg1 <= 0){
            alert('Masukkan data barang yang valid');
        }else{
            var kd = $('#kd-barang option:selected').val();
            var nama = $('#kd-barang option:selected').text();
            var qty = qty1;
            var hrg = hrg1;
            var disc = disc1;
            // var todis= (hrg * disc) / 100
            var sub = (+qty * +hrg) - disc;
            // var sub = +qty * +hrg;
            
            // cek barang sama
            $('.row-barang').each(function(){
                var kd_temp = $(this).closest('tr').find('.inp-kdb').val();
                var qty_temp = $(this).closest('tr').find('.inp-qtyb').val();
                var hrg_temp = $(this).closest('tr').find('.inp-hrgb').val();
                var disc_temp = $(this).closest('tr').find('.inp-disc').val();
                if(kd_temp == kd){
                    qty+=+(toNilai(qty_temp));
                    // hrg+=+(toNilai(hrg_temp));
                    disc+=+(toNilai(disc_temp));
                    //todis+=+(hrg*toNilai(disc_temp))/100;
                    sub=(hrg*qty)-disc;
                    $(this).closest('tr').remove();
                }
            });
            
            input = "<tr class='row-barang'>";
            input += "<td width='30%'>"+nama+"<input type='hidden' name='kode_barang[]' class='change-validation inp-kdb form-control' value='"+kd+"' readonly required></td>";
            input += "<td width='15%' style='text-align:right'><input type='text' name='harga_barang[]' class='change-validation inp-hrgb form-control'  value='"+toRp(hrg)+"' readonly required></td>";
            input += "<td width='5%' style='text-align:right'><input type='text' name='satuan_barang[]' class='change-validation inp-satuanb form-control'  value='"+setSatuan(kd)+"' readonly required><input type='hidden' name='kode_akun[]' class='change-validation inp-satuanb'  value='"+setAkun(kd)+"' readonly></td>";
            input += "<td width='15%' style='text-align:right'><input type='text' name='qty_barang[]' class='change-validation inp-qtyb form-control'  value='"+qty+"' required></td>";
            input += "<td width='10%' style='text-align:right'><input type='text' name='disc_barang[]' class='change-validation inp-disc form-control'  value='"+disc+"' readonly required></td>";
            input += "<td width='15%' style='text-align:right'><input type='text' name='sub_barang[]' class='change-validation inp-subb form-control'  value='"+toRp(sub)+"' required></td>";
            input += "<td width='10%'></a><a class='btn btn-primary btn-sm ubah-barang' style='font-size:8px'><i class='fas fa-pencil-alt fa-1'></i></a>&nbsp;<a class='btn btn-danger btn-sm hapus-item' style='font-size:8px'><i class='fa fa-times fa-1'></i></td>";
            input += "</tr>";
            
            $("#input-grid2").append(input);
            
            hitungTotal();
            
            // $('#kd-barang').val('');
            $('#kd-barang')[0].selectize.setValue('');
            // $('#qty-barang').val('');
            // $('#disc-barang').val('');
            // $('#hrg-barang').val('');
            $("#input-grid2 tr:last").focus();
            $('.gridexample').formNavigation();
            // $('#kd-barang-selectized').focus();
        }
    }
    function addBarang2(){

        var kd1 = $('#kd-barang2').val();
        var qty1 = 1;
        var disc1 = 0;
        var hrg1 = setHarga3(kd1);
        var kd=getKode(kd1);
        var nama = kd+"-"+setNama(kd1);
        // || +qty1 <= 0 || +hrg1 <= 0
        if(kd1 == '' || +hrg1 <= 0){
            alert('Masukkan data barang yang valid');
        }else{
            // var kd = $('#kd-barang2').val();
            
            // var nama = $('#kd-barang option:selected').text();
            var qty = qty1;
            var hrg = hrg1;
            var disc = disc1;
            // var todis= (hrg * disc) / 100
            var sub = (+qty * +hrg) - disc;
            // var sub = +qty * +hrg;
            
            // cek barang sama
            $('.row-barang').each(function(){
                var kd_temp = $(this).closest('tr').find('.inp-kdb').val();
                var qty_temp = $(this).closest('tr').find('.inp-qtyb').val();
                var hrg_temp = $(this).closest('tr').find('.inp-hrgb').val();
                var disc_temp = $(this).closest('tr').find('.inp-disc').val();
                if(kd_temp == kd){
                    qty+=+(toNilai(qty_temp));
                    // hrg+=+(toNilai(hrg_temp));
                    disc+=+(toNilai(disc_temp));
                    //todis+=+(hrg*toNilai(disc_temp))/100;
                    sub=(hrg*qty)-disc;
                    $(this).closest('tr').remove();
                }
            });
            
            input = "<tr class='row-barang'>";
            input += "<td width='30%'>"+nama+"<input type='hidden' name='kode_barang[]' class='change-validation inp-kdb form-control' value='"+kd+"' readonly required></td>";
            input += "<td width='15%' style='text-align:right'><input type='text' name='harga_barang[]' class='change-validation inp-hrgb form-control'  value='"+toRp(hrg)+"' readonly required></td>";
            input += "<td width='5%' style='text-align:right'><input type='text' name='satuan_barang[]' class='change-validation inp-satuanb form-control'  value='"+setSatuan(kd)+"' readonly required><input type='hidden' name='kode_akun[]' class='change-validation inp-satuanb'  value='"+setAkun(kd)+"' readonly></td>";
            input += "<td width='15%' style='text-align:right'><input type='text' name='qty_barang[]' class='change-validation inp-qtyb form-control'  value='"+qty+"' required></td>";
            input += "<td width='10%' style='text-align:right'><input type='text' name='disc_barang[]' class='change-validation inp-disc form-control'  value='"+disc+"' readonly required></td>";
            input += "<td width='15%' style='text-align:right'><input type='text' name='sub_barang[]' class='change-validation inp-subb form-control'  value='"+toRp(sub)+"' required></td>";
            input += "<td width='10%'></a><a class='btn btn-primary btn-sm ubah-barang' style='font-size:8px'><i class='fas fa-pencil-alt fa-1'></i></a>&nbsp;<a class='btn btn-danger btn-sm hapus-item' style='font-size:8px'><i class='fa fa-times fa-1'></i></td>";
            input += "</tr>";
            
            $("#input-grid2").append(input);
            
            hitungTotal();
            
            $('#kd-barang2').val('');
            $("#input-grid2 tr:last").focus();
            $('#kd-barang2').focus();
            $('.gridexample').formNavigation();
        }
    }

    $("#input-grid2").on("dblclick", '.row-barang',function(){
        // get clicked index
        var index = $(this).closest('tr').index();
        ubahBarang(index);
    });

    $("#input-grid2").on("click", '.ubah-barang', function(){
        // get clicked index
        var index = $(this).closest('tr').index();
        ubahBarang(index);
    });

    $("#input-grid2").on("click", '.hapus-item', function(){
        // get clicked index
        var index = $(this).closest('tr').index();
        hapusBarang(index);
    });

    $('#qty-barang').keydown(function(e){
        var value = String.fromCharCode(e.which) || e.key;
        
        if (e.which == 13) {
            e.preventDefault();
            
        }
        
    });

    $('#tambah-barang').hide();
    $('#totrans').keydown(function(e){
        var value = String.fromCharCode(e.which) || e.key;
        
        if(e.key == 'ArrowDown'){
            e.preventDefault();
            $('#todisk').focus();
        }
    });

    $('#todisk').change(function(){
        hitungDisc();
    });

    $('#todisk').keydown(function(e){
        var value = String.fromCharCode(e.which) || e.key;
        
        if (e.key == 'ArrowUp') {
            e.preventDefault();
            $('#totrans').focus();
        }else if(e.key == 'ArrowDown'){
            e.preventDefault();
            $('#tostlh').focus();
        }
    });

    $('#tostlh').keydown(function(e){
        var value = String.fromCharCode(e.which) || e.key;
        
        if (e.key == 'ArrowUp') {
            e.preventDefault();
            $('#todisk').focus();
        }else if(e.key == 'ArrowDown'){
            e.preventDefault();
            $('#tobyr').focus();
        }
    });

    // $('#tobyr').keydown(function(e){
    //     var value = String.fromCharCode(e.which) || e.key;
        
    //     if (e.key == 'ArrowUp') {
    //         e.preventDefault();
    //         $('#tostlh').focus();
    //     }else if(e.key == 'ArrowDown'){
    //         e.preventDefault();
    //         $('#kembalian').focus();
    //     }
    // });
    // $('#tobyr').change(function(){
    //     hitungKembali();
    // });

    // $('#kembalian').keydown(function(e){
    //     var value = String.fromCharCode(e.which) || e.key;
        
    //     if (e.key == 'ArrowUp') {
    //         e.preventDefault();
    //         $('#tobyr').focus();
    //     }
    // });

    $('#btn-byr').click(function(){
        $('#modal-bayar').modal('show');
    });

    $('#getPPN').click(function(){
        getPPN();
    });

    $('#btn-ok').click(function(){
        var tot = toNilai($('#inp-byr').val());
        $('#tobyr').val(toRp(tot));
        hitungTotal();
        $('#modal-bayar').modal('hide');
        $('#inp-byr').val(0);
        $('#param').val('');
    });

    $('#btn-clear').click(function(){
        $('#inp-byr').val(0);
        $('#param').val('');
    });

    $('#nom0').click(function(){
        var tot= toNilai($('#tostlh').val());
        $('#inp-byr').val(tot);
    });
    $('#nom1').click(function(){
        var tot= toNilai($('#inp-byr').val());
        var tanda= $('#param').val();

        // if(tanda != ''){
            tot+=+1000;
        // }else{
        //     tot=1000;
        // }
        $('#inp-byr').val(tot);
    });
    $('#nom2').click(function(){
        var tot= toNilai($('#inp-byr').val());
        var tanda= $('#param').val();

        // if(tanda != ''){
            tot+=+2000;
        // }else{
        //     tot=2000;
        // }
        $('#inp-byr').val(tot);
    });
    $('#nom3').click(function(){
        var tot= toNilai($('#inp-byr').val());
        var tanda= $('#param').val();

        // if(tanda != ''){
            tot+=+5000;
        // }else{
        //     tot=5000;
        // }
        $('#inp-byr').val(tot);
    });
    $('#nom4').click(function(){
        var tot= toNilai($('#inp-byr').val());
        var tanda= $('#param').val();

        // if(tanda != ''){
            tot+=+10000;
        // }else{
        //     tot=10000;
        // }
        $('#inp-byr').val(tot);
    });
    $('#nom5').click(function(){
        var tot= toNilai($('#inp-byr').val());
        var tanda= $('#param').val();

        // if(tanda != ''){
            tot+=+20000;
        // }else{
        //     tot=20000;
        // }
        $('#inp-byr').val(tot);
    });
    $('#nom6').click(function(){
        var tot= toNilai($('#inp-byr').val());
        var tanda= $('#param').val();

        // if(tanda != ''){
            tot+=+50000;
        // }else{
        //     tot=50000;
        // }
        $('#inp-byr').val(tot);
    });
    $('#nom7').click(function(){
        var tot= toNilai($('#inp-byr').val());
        var tanda= $('#param').val();

        // if(tanda != ''){
            tot+=+100000;
        // }else{
        //     tot=100000;
        // }
        $('#inp-byr').val(tot);
    });

    // Simpan Penjualan
    $('#web-form-pos').submit(function(e){
    e.preventDefault();
    // if($submitBtn){
            var totrans=toNilai($('#totrans').val());
            var todisk=toNilai($('#todisk').val());
            var tostlh=toNilai($('#tostlh').val());
            var toppn=toNilai($('#toppn').val());
            // var tobyr=toNilai($('#tobyr').val());
            // var kembalian=tobyr-tostlh;
            if(totrans <= 0){
                alert('Total transaksi tidak valid');
            }else{
                var formData = new FormData(this);
                
                var nik='<?php echo $nik; ?>' ;
                var kode_lokasi='<?php echo $kode_lokasi; ?>' ;
                var kode_pp='<?php echo $kode_pp; ?>' ;

                formData.append('nik_user', nik);
                formData.append('kode_lokasi', kode_lokasi);
                formData.append('kode_pp', kode_pp);
                for(var pair of formData.entries()) {
                    console.log(pair[0]+ ', '+ pair[1]); 
                    }

                $.ajax({
                    type: 'POST',
                    url: '<?=$root_ser?>/Pembelian.php?fx=simpan',
                    dataType: 'json',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false, 
                    async:false,
                    success:function(result){
                        
                        alert('Input data '+result.message);
                        if(result.status){
                            $('#modal-totrans').text(sepNum(totrans));
                            $('#modal-diskon').text(sepNum(todisk));
                            $('#modal-ppn').text(sepNum(toppn)); 
                            $('#modal-toakhir').text(sepNum(tostlh));
                            $('#modal-nobukti').text(result.no_bukti);
                            // $('#modal-kembalian').text(sepNum(kembalian));
                            $('#modal-bayar2').modal('show');
                            // location.reload();
                            
                           
                        }
                    },
                    fail: function(xhr, textStatus, errorThrown){
                        alert('request failed:'+textStatus);
                    }
                });
            }
    // }else{
    //     return false;
    // }
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

    document.onkeyup = function(e) {
        if (e.ctrlKey && e.which == 66) {
            $('#kd-barang2').focus();
        }
        if (e.which == 112) {
            $('#kd-barang2').focus();
        }
        if (e.ctrlKey && e.which == 67) {
            $('#kd-barang-selectized').focus();
        }
        if (e.which == 118) {
            // $('.inp-qtyb').prop('readonly');
            $('.inp-qtyb').prop('readonly', false);
            $('.inp-qtyb').first().focus();
            $('.inp-qtyb').first().select();
        }
    };


    $('#kd-barang2').scannerDetection({
        
        //https://github.com/kabachello/jQuery-Scanner-Detection

        timeBeforeScanTest: 200, // wait for the next character for upto 200ms
        avgTimeByChar: 40, // it's not a barcode if a character takes longer than 100ms
        preventDefault: true,

        endChar: [13],
        onComplete: function(barcode, qty){
        validScan = true;
            $('#kd-barang2').val (barcode);
            addBarang2();
        
        } // main callback function	,
        ,
        onError: function(string, qty) {
            console.log('barcode-error');
        }	
    });

    $('#cetakBtn').click(function(){
        var no_bukti= $('#modal-nobukti').text();
        window.location.href='<?=$root_app?>/fNotaBeli/?param='+no_bukti+'&pmb=1';
    });
  
    // $('#input-grid').on('keydown','.inp-qtyb',function(e){
    //     e.preventDefault();
    //     if (e.which == 13) {
    //         // // $('.inp-qtyb').prop('readonly');
    //         // $('.inp-qtyb').prop('readonly', true);
    //         // hitungTotal();
    //         // hitungKembali();
    //         // hitungDisc();
    //     }
    //     alert(e.which);
    // })
    $('#input-grid2').on('keydown', '.inp-qtyb', function(e){
        if (e.which == 9 || e.which == 40 || e.which == 38) {
            hitungTotal();
            
        }else if(e.which == 13){
            hitungTotal();
            // $('.inp-qtyb').prop('readonly', true);
        }
    });


    $('#web-form-pos').on('click', '#edit-qty', function(e){
       
       $('.inp-qtyb').prop('readonly', false);
       $('.inp-qtyb').first().focus();
       $('.inp-qtyb').first().select();
        
    });

    $('#input-grid2').on('change', '.inp-qtyb', function(e){
        
        hitungTotal();
        
    });

    $(document).on("keypress", 'form', function (e) {
        var code = e.keyCode || e.which;
        // console.log(code);
        if (code == 13) {
            // console.log('Inside');
            e.preventDefault();
            // console.log(this);
            return false;
        } 
    });



</script>