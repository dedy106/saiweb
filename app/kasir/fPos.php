<?php
     session_start();
     $root_lib=$_SERVER["DOCUMENT_ROOT"];
     if (substr($root_lib,-1)!="/") {
         $root_lib=$root_lib."/";
     }
     include_once($root_lib.'app/kasir/setting.php');
 
 
    $kode_lokasi=$_COOKIE['lokasi'];
    $nik=$_COOKIE['userLog'];
    $kode_pp=$_COOKIE['kodePP'];

    // $str_format="0000";
    // $periode=date('Y').date('m');
    // $per=date('y').date('m');
    // $prefix=$kode_lokasi."-PNJ".$per.".";
    // $sql2="select right(isnull(max(no_jual),'0000'),".strlen($str_format).")+1 as id from brg_jualpiu_dloc where no_jual like '$prefix%' and kode_lokasi='".$kode_lokasi."' ";
    
    // $query = execute($sql2);
    
    // $id = $prefix.str_pad($query->fields[0], strlen($str_format), $str_format, STR_PAD_LEFT);
    
    // $no_bukti=$id;

    // $sqlg="select top 1 kode_gudang from brg_gudang where kode_pp='$kode_pp' and kode_lokasi='".$kode_lokasi."' ";

    // // echo $sqlg;
    
    // $query2 = execute($sqlg);
    // $kode_gudang=$query2->fields[0];

    // $sqlo="select no_open from kasir_open where nik='$nik' and kode_lokasi='".$kode_lokasi."' and no_close='-' ";
    // $rso=execute($sqlo);
    // $no_open = $rso->fields[0];
?>
<style>
#edit-qty{
    cursor:pointer;
}

#pbyr{
    cursor:pointer;
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
                                    <div class='logo text-center'><img src="<?=$folder_assets?>/images/sai_icon/logo.png" width="40px" alt="homepage" class="light-logo" /><br/>
                                    <img src="<?=$folder_assets?>/images/sai_icon/logo-text.png" class="light-logo" alt="homepage" width="40px"/>
                                    </div>
                                </div>
                                <div class='col-md-8'>
                                    <div class='label-header'>
                                        <h6><?=date('Y-m-d H:i:s')?></h6>
                                        <h6 style="color:#007AFF"><i class='fa fa-user'></i> <?=$nik?> / <span id='no_open'></span></h6>
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
                                    <div class="text-left" id='pbyr' style="width: 120px;height:42px;padding: 5px;border: 1px solid #d0cfcf;background: white;border-radius: 5px;vertical-align: middle;"><img style='width:30px;height:30px;position:absolute' src='<?=$folderroot_img?>/debit-card.png'>
                                    <h6 style="font-size: 10px;padding-left: 35px;margin-bottom: 0;margin-top: 5px;text-align:center">Pembayaran</h6>
                                    <h6 style="font-size: 9px;padding-left: 35px;text-align:center">F8</h6></div>
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
                                            <?php
                                            // $sql="select kode_barang,nama,hna as harga,barcode from brg_barang where flag_aktif='1' and kode_lokasi='$kode_lokasi'";
                                            // $rs=execute($sql);

                                            // $jsArray = "var dtBrg = new Array();\n";  
                                            // $jsArray2 = "var dtBrg2 = new Array();\n";       

                                            // while($row = $rs->FetchNextObject($toupper=false)){
                                            //     echo "<option value='".$row->kode_barang."'>".$row->kode_barang."-".$row->nama."</option>";

                                            //     $jsArray .= "dtBrg['" . $row->kode_barang . "'] = {harga:" . addslashes($row->harga) . "};\n";  
                                            //     $jsArray2 .= "dtBrg2['" . $row->barcode . "'] = {harga:" . addslashes($row->harga) . ",nama:'".$row->nama."',kd_barang:'".$row->kode_barang."'};\n"; 
                                            // }
                                            ?>
                                        </select>
                                    </td>
                                    <th style='padding: 3px;width:5%'>Disc.</th>
                                    <th style='padding: 3px;width:20%'><input type='text' placeholder='Total Disc.' value="0" name="total_disk" class='form-control currency' id='todisk' required ></th>
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
                                        <th>Qty</th>
                                        <th>Subtotal</th>
                                        <th>Disc</th>
                                    </tr>
                                </table>
                            </div>
                            <div class='col-md-6 mt-2 float-right'>
                                <div class='form-group row'>
                                    
                                    <label for="judul" class="col-4 col-form-label" style="font-size:16px">Pembayaran</label>
                                    <div class="col-6">
                                    <input type="text" name="total_bayar" min="1" class='form-control currency' id='tobyr' required value="0">
                                    </div>
                                    <input type='hidden' style='' name="kembalian" min="1" class='form-control currency' id='kembalian' required readonly>
                                    <div class='col-2'>
                                        <button class='btn btn-info' type="submit" id='btnBayar'>Bayar</button>
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
    <div id='area_print'></div>
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
                </div>
            </div>
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
                        <h5 class="">Kembalian</h5>
                        <h5 id="modal-no_jual" hidden></h5>
                        <h1 class="text-info" id="modal-kembalian">12.500</h1>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div class="row mb-2" style="">
                    <div class="col-6" style="">
                    Total 
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
                    Pembulatan 
                    </div>
                    <div class="col-6 text-right">
                    14
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-6">
                    Total Bayar
                    </div>
                    <div class="col-6 text-right" id="modal-tostlhdisk">
                    300.000
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-6">
                    Pembayaran
                    </div>
                    <div class="col-6 text-right" id="modal-tobyr">
                    312.500
                    </div>
                </div>
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
    var $submitBtn = false;
    var $dtBrg = new Array();
    var $dtBrg2 = new Array();
    var $no_open = "";
    // $('#kd-barang').selectize({
    //     selectOnTab: true,    
    //     onChange: function (val){
    //         // $('#kd-barang').val(value);
    //         var id = val
    //         if (id != "" && id != null && id != undefined){
    //             addBarang();
    //         }
    //     }
    // });

    // $('#kd-barang').selectize({
    //     // create: false,
    //     // delimiter: ',',
    //     // valueField: 'kode_barang',
    //     // labelField: 'nama',
    //     // searchField: ['kode_barang'],
    //     // sortField: 'nama',
    //     // persist: false,
    //     selectOnTab: true,    
    //     onChange: function (val){
    //         // $('#kd-barang').val(value);
    //         var id = val
    //         if (id != "" && id != null && id != undefined){
    //             addBarang();
    //         }
    //     }
    // });

    $('#kd-barang').selectize({
        // theme: 'links',
        selectOnTab:true,
        maxItems: 1,
        valueField: 'kd_barang',
        labelField: 'nama',
        searchField: ['kd_barang','nama','barcode'],
        options: [
            {kd_barang: 123456, nama: 'test', barcode: '200'},
        ],
        render: {
            option: function(data, escape) {
                return '<div class="option">' +
                '<span class="nama">' + escape(data.nama) + '</span>' +
                '</div>';
            },
            item: function(data, escape) {
                return '<div class="item"><a href="#">' + escape(data.nama) + '</a></div>';
            }
        },
        // create: function(input) {
        //     return {
        //         id: 0,
        //         title: input,
        //         url: '#'
        //     };
        // }
        create:false,
        onChange: function (val){
            // $('#kd-barang').val(value);
            var id = val
            if (id != "" && id != null && id != undefined){
                addBarang(id);
                // alert(id);
            }
        }
    });

    function getBarang(){
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/POS.php?fx=getBarang',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?=$kode_lokasi?>','kode_pp':'<?=$kode_pp?>'},
            success:function(result){    
                if(result.status){
                    if(typeof result.daftar !== 'undefined' && result.daftar.length>0){
                        var select = $('#modal-edit-kode').selectize();
                        select = select[0];
                        var control = select.selectize;

                        
                        var select2 = $('#kd-barang').selectize();
                        select2 = select2[0];
                        var control2 = select2.selectize;
                        control2.clearOptions();
                        
                        for(i=0;i<result.daftar.length;i++){
                            control.addOption([{text:result.daftar[i].kode_barang + ' - ' + result.daftar[i].nama, value:result.daftar[i].kode_barang}]);
                            // $('#kd-barang')[0].selectize.addOption([{text:result.daftar[i].kode_barang + ' - ' + result.daftar[i].nama, value:result.daftar[i].kode_barang}]);
                            control2.addOption([{kd_barang:result.daftar[i].kode_barang, nama:result.daftar[i].nama,barcode:result.daftar[i].barcode}]);

                            $dtBrg[result.daftar[i].kode_barang] = {harga:result.daftar[i].harga};  
                            $dtBrg2[result.daftar[i].barcode] = {harga:result.daftar[i].harga,nama:result.daftar[i].nama,kd_barang:result.daftar[i].kode_barang};
                        }
                        
                    }
                }
            }
        });
    }

    function getNoOpen(){
        $.ajax({
            type: 'GET',
            url: '<?=$root_ser?>/POS.php?fx=getNoOpen',
            dataType: 'json',
            async:false,
            data: {'kode_lokasi':'<?=$kode_lokasi?>','nik':'<?=$nik?>'},
            success:function(result){    
                if(result.status){
                    $no_open = result.no_open;
                    $('#no_open').text($no_open);
                }
            }
        });
    }

    getNoOpen();
    getBarang();
    
    $('#area_print').hide();
    $('#kd-barang2').focus();
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

    function hitungDisc(){
        
        var total_trans = toNilai($('#totrans').val());
        var total_disk= toNilai($('#todisk').val());
        var total_stlh = +total_trans - +total_disk;
        
        $('#tostlh').val(toRp(total_stlh));
        var total_bayar = toNilai($('#tobyr').val());
        if(total_bayar > 0 ){
            kembalian = +total_bayar - +total_stlh;  
            if(kembalian < 0) kembalian = 0; 
            $("#kembalian").val(toRp(kembalian));
        }
        
    }

    function hitungTotal(){
        
        // hitung total barang
        if($('#todisk').val() == ""){
            
            $('#todisk').val(0);
        }
        var total_brg = 0;
        var diskon =  0;
        $('.row-barang').each(function(){
            var qtyb = $(this).closest('tr').find('.inp-qtyb').val();
            var hrgb = $(this).closest('tr').find('.inp-hrgb').val();
            var disc = $(this).closest('tr').find('.inp-disc').val();
            //var todis= (toNilai(hrgb) * toNilai(disc)) / 100
            // var subb = (+qtyb * toNilai(hrgb)) - disc;
            diskon += +disc;
            var subb = (+qtyb * toNilai(hrgb));
            $(this).closest('tr').find('.inp-subb').val(toRp(subb));
            total_brg += +subb;
        });
        $('#totrans').val(toRp(total_brg));
        $('#todisk').val(toRp(diskon));

        var total_disk= toNilai($('#todisk').val());
        var total_stlh = +total_brg - +total_disk;
        
        $('#tostlh').val(toRp(total_stlh));
        var total_bayar = toNilai($('#tobyr').val());
        // alert(total_bayar);
        if(total_bayar > 0 ){
            if(kembalian < 0) kembalian = 0;
            kembalian = +total_bayar - +total_stlh;
            // alert(total_trans);
        
            $("#kembalian").val(toRp(kembalian));
        }
        
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
        var post_url = "include_lib.php?hal=server/kasir/POS.php&fx=getDataBarang";
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
        $('.gridexample').formNavigation();

    }

    $('#edit-submit').click(function(e){
        e.preventDefault();
        
        var hrg = toNilai($('#modal-edit-harga').val());
        var qty = toNilai($('#modal-edit-qty').val());
        var disc = toNilai($('#modal-edit-disc').val());
        var kd = $('#modal-edit-kode option:selected').val();
        var nama = $('#modal-edit-kode option:selected').text();
        //var todis= (hrg * disc) / 100
        var sub = (+qty * +hrg);
        var tgl = '<?=date('Y-m-d')?>';
        
        
        $.ajax({
            url: "<?=$root_ser?>/POS.php?fx=cekBonus",
            data: { 'kode_barang' : kd,'kode_lokasi' :'<?=$kode_lokasi?>','harga':hrg,'jumlah':qty,'tanggal':tgl},
            type: "post",
            dataType: "json",
            cache: false,
            success: function (data) {
                
                qty=data.jumlah;
                disc=data.diskon;
                sub = (hrg*qty);
                var input="";
                input = "<tr class='row-barang'>";
                input += "<td width='30%'>"+nama+"<input type='hidden' name='kode_barang[]' class='change-validation inp-kdb form-control' value='"+kd+"' readonly required></td>";
                input += "<td width='20%' style='text-align:right'><input type='text' name='harga_barang[]' class='change-validation inp-hrgb form-control'  value='"+toRp(hrg)+"' readonly required></td>";
                input += "<td width='15%' style='text-align:right'><input type='text' name='qty_barang[]' class='change-validation inp-qtyb form-control'  value='"+qty+"' readonly required></td>";
                input += "<td width='15%' style='text-align:right'><input type='text' name='sub_barang[]' class='change-validation inp-subb form-control'  value='"+toRp(sub)+"' readonly required></td>";
                input += "<td width='10%' style='text-align:right'><input type='text' name='disc_barang[]' class='change-validation inp-disc form-control'  value='"+disc+"' readonly required></td>";
                input += "<td width='10%'></a><a class='btn btn-primary btn-sm ubah-barang' style='font-size:8px'><i class='fas fa-pencil-alt fa-1'></i></a>&nbsp;<a class='btn btn-danger btn-sm hapus-item' style='font-size:8px'><i class='fa fa-times fa-1'></i></td>";
                input += "</tr>";
                
                $('.set-selected').closest('tr').remove();
                // $('.set-selected').closest('tr').append(input);
                
                $("#input-grid2").append(input);
                hitungTotal();
                        
                $('.gridexample').formNavigation();
                $('#modal-edit').modal('hide');
            }
        });
        
    });

    function addBarang(){

        // var kd1 = $('#kd-barang').val();
        var kd1 = $('#kd-barang')[0].selectize.getValue();
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
            var sub = (+qty * +hrg);
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
                    sub=(hrg*qty);
                    $(this).closest('tr').remove();
                }
            });

            
            var tgl = '<?=date('Y-m-d')?>';
            $.ajax({
                url: "<?=$root_ser?>/POS.php?fx=cekBonus",
                data: { 'kode_barang' : kd,'kode_lokasi' :'<?=$kode_lokasi?>','harga':hrg,'jumlah':qty,'tanggal':tgl},
                type: "post",
                dataType: "json",
                cache: false,
                success: function (data) {
                   
                    qty=data.jumlah;
                    disc=data.diskon;
                    sub = (hrg*qty);
                    input = "<tr class='row-barang'>";
                    input += "<td width='30%'>"+nama+"<input type='hidden' name='kode_barang[]' class='change-validation inp-kdb form-control' value='"+kd+"' readonly required></td>";
                    input += "<td width='20%' style='text-align:right'><input type='text' name='harga_barang[]' class='change-validation inp-hrgb form-control'  value='"+toRp(hrg)+"' readonly required></td>";
                    input += "<td width='15%' style='text-align:right'><input type='text' name='qty_barang[]' class='change-validation inp-qtyb form-control'  value='"+qty+"' readonly required></td>";
                    input += "<td width='15%' style='text-align:right'><input type='text' name='sub_barang[]' class='change-validation inp-subb form-control'  value='"+toRp(sub)+"' readonly required></td>";
                    input += "<td width='10%' style='text-align:right'><input type='text' name='disc_barang[]' class='change-validation inp-disc form-control'  value='"+disc+"' readonly required></td>";
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
            });
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
            input += "<td width='20%' style='text-align:right'><input type='text' name='harga_barang[]' class='change-validation inp-hrgb form-control'  value='"+toRp(hrg)+"' readonly required></td>";
            input += "<td width='15%' style='text-align:right'><input type='text' name='qty_barang[]' class='change-validation inp-qtyb form-control'  value='"+qty+"' readonly required></td>";
            input += "<td width='15%' style='text-align:right'><input type='text' name='sub_barang[]' class='change-validation inp-subb form-control'  value='"+toRp(sub)+"' readonly required></td>";
            input += "<td width='10%' style='text-align:right'><input type='text' name='disc_barang[]' class='change-validation inp-disc form-control'  value='"+disc+"' readonly required></td>";
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

    $('#tobyr').keydown(function(e){
        var value = String.fromCharCode(e.which) || e.key;
        
        if (e.key == 'ArrowUp') {
            e.preventDefault();
            $('#tostlh').focus();
        }else if(e.key == 'ArrowDown'){
            e.preventDefault();
            $('#kembalian').focus();
        }else if(e.which == 13){
            $('#web-form-pos').submit();
        }
    });
    $('#tobyr').change(function(){
        hitungKembali();
    });

    $('#kembalian').keydown(function(e){
        var value = String.fromCharCode(e.which) || e.key;
        
        if (e.key == 'ArrowUp') {
            e.preventDefault();
            $('#tobyr').focus();
        }
    });

    $('#btn-byr').click(function(){
        $('#modal-bayar').modal('show');
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
            var tobyr=toNilai($('#tobyr').val());
            var kembalian=tobyr-tostlh;
            if(totrans <= 0){
                alert('Total transaksi tidak valid');
            }
            else if(tobyr <= 0){
                alert('Total bayar tidak valid');
            }
            else if(kembalian < 0){
                alert('Total Bayar kurang dari Total Transaksi');
            }else if($no_open == ""){
                alert('Anda belum melakukan open kasir!');
            }else{
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
                formData.append('no_open', $no_open);
                $.ajax({
                    type: 'POST',
                    url: '<?=$root_ser?>/POS.php?fx=simpanPnj',
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
                            $('#modal-tostlhdisk').text(sepNum(tostlh));
                            $('#modal-tobyr').text(sepNum(tobyr));
                            $('#modal-kembalian').text(sepNum(kembalian));
                            $('#modal-no_jual').text(result.no_jual);
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
        if (e.ctrlKey && e.which == 67) {
            $('#kd-barang-selectized').focus();
        }
        if (e.which == 118) {
            // $('.inp-qtyb').prop('readonly');
            $('.inp-qtyb').prop('readonly', false);
            $('.inp-qtyb').first().focus();
            $('.inp-qtyb').first().select();
        }
        if (e.which == 112) {
            $('#kd-barang2').focus();
        }
        if (e.which == 119) {
            $('#tobyr').focus();
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
        var no_jual = $('#modal-no_jual').text();
        // window.location.href='<?=$root_app?>/fNota/?param='+no_jual+'&pnj=1';
        window.open('<?=$root_print?>/fNota3/?param='+no_jual+'&print=1'); 

        // $('#area_print').load('<?=$root_print?>/fNota3/?param='+no_jual+'&print=1');
        // setTimeout(function(){ 
        // var w=window.open();
        //     w.document.write($('#area_print').html());
        //     w.print();
        //     w.close();
        // }, 1000);      
    });

    $('#input-grid2').on('keydown', '.inp-qtyb', function(e){
        if (e.which == 9 || e.which == 40 || e.which == 38) {
            hitungTotal();
            
        }else if(e.which == 13){
            hitungTotal();
            $('.inp-qtyb').prop('readonly', true);
        }
    });

    $('#web-form-pos').on('click', '#edit-qty', function(e){
       
       $('.inp-qtyb').prop('readonly', false);
       $('.inp-qtyb').first().focus();
       $('.inp-qtyb').first().select();
       
   });
   $('#web-form-pos').on('click', '#pbyr', function(e){
       
       $('#tobyr').focus();
       
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

    $(document).on("keypress", '#modal-bayar2', function (e) {
        var code = e.keyCode || e.which;
        // console.log(code);
        if (code == 13) {
            // console.log('Inside');
            e.preventDefault();
            // console.log(this);
            $('#cetakBtn').click();
        }
    });
</script>