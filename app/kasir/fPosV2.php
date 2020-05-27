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

    $str_format="0000";
    $periode=date('Y').date('m');
    $per=date('y').date('m');
    $prefix=$kode_lokasi."-PNJ".$per.".";
    $sql2="select right(isnull(max(no_bukti),'0000'),".strlen($str_format).")+1 as id from trans_m where no_bukti like '$prefix%' and kode_lokasi='".$kode_lokasi."' ";
    
    $query = execute($sql2);
    
    $id = $prefix.str_pad($query->fields[0], strlen($str_format), $str_format, STR_PAD_LEFT);
    
    $no_bukti=$id;

    $sqlg="select top 1 kode_gudang from brg_gudang where kode_pp='$kode_pp' and kode_lokasi='".$kode_lokasi."' ";

    // echo $sqlg;
    
    $query2 = execute($sqlg);
    $kode_gudang=$query2->fields[0];
?>
<div class="container-fluid mt-3">
    <div class="row" >
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <form class="form" id="web-form-pos" method='POST'>
                        <h4 class="card-title">Penjualan
                        <button type='submit' class='btn btn-success float-right' id='tbl-submit'><i class="fa fa-save"></i> Simpan</button>
                        </h4>
                        <br/>
                        <div class='row'>
                        <div class='col-md-9'>
                            <table class='table' style='margin-bottom: 5px'>
                                <tr>
                                    <td style='width:30%; padding: 3px;' colspan='2'>
                                        <select class='form-control' id="kd-barang">
                                            <option value=''>--- Pilih Barang ---</option>
                                            <?php
                                            $sql="select kode_barang,nama,hna as harga from brg_barang where flag_aktif='1' and kode_lokasi='$kode_lokasi'";
                                            $rs=execute($sql);

                                            $jsArray = "var dtBrg = new Array();\n";       

                                            while($row = $rs->FetchNextObject($toupper=false)){
                                                echo "<option value='".$row->kode_barang."'>".$row->kode_barang."-".$row->nama."</option>";

                                                $jsArray .= "dtBrg['" . $row->kode_barang . "'] = {harga:" . addslashes($row->harga) . "};\n";  
                                            }
                                            ?>
                                        </select>
                                    </td>
                                    <td style='width:20%; padding: 3px;'><select class='form-control' name='kode_gudang' id="kd-gudang">
                                            <option value=''>--- Pilih Gudang ---</option>
                                            <?php
                                            $sql="select kode_gudang,nama from brg_gudang where  kode_lokasi='$kode_lokasi' and kode_pp='$kode_pp' ";
                                            $rs1=execute($sql);

                                            while($row1 = $rs1->FetchNextObject($toupper=false)){
                                                if($row1->kode_gudang == $kode_gudang){
                                                    $selected="selected";
                                                }else{
                                                    $selected="";
                                                }
                                                echo "<option value='".$row1->kode_gudang."' $selected>".$row1->kode_gudang."-".$row1->nama."</option>";  
                                            }
                                            ?>
                                        </select>
                                    </td>
                                    <td style='width:20%; padding: 3px;'><input type='hidden' class='form-control currency' id="hrg-barang" readonly></td>
                                    <td style='width:15%; padding: 3px;'><input type='hidden' min='1' step='1' class='form-control currency' id="qty-barang"></td>
                                    <td style='width:15%; padding: 3px;'><input type='hidden' min='1' step='1' class='form-control currency' placeholder='disc%' id="disc-barang"></td>
                                    <td style='width:10%; padding: 3px;'><a class='btn btn-warning pull-right' id='tambah-barang'><i class='fa fa-plus'></i> Tambah</a></td>
                                </tr>
                            </table>
                            <div class='col-xs-12' style='overflow-y: scroll; max-height:430px; margin:0px; padding:0px;'>
                                <table class="table table-striped table-bordered table-condensed" id="input-grid2">
                                    <tr>
                                        <th>Barang</th>
                                        <th>Harga</th>
                                        <th>Qty</th>
                                        <th>Disc</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    <!--<div class="well well-sm no-shadow" style="margin-top: 0px; margin-bottom:5px;">-->
                        <div class='col-sm-3 pull-right' style='padding-left:0px'>
                            <table class='table table-condensed' id='table-label'>
                                <style>
                                th {
                                    padding: 5px !important;
                                }
                                td {
                                    padding: 5px !important;
                                }
                                </style>
                                <tr>
                                    <td >Tanggal</td>
                                    <td><?php echo date('Y-m-d H:i:s'); ?></td>
                                </tr>
                                <tr>
                                    <td >User</td>
                                    <td><?php echo $nik; ?></td>
                                </tr>
                                <tr>
                                    <td >No Bukti</td>
                                    <td><?php echo $no_bukti; ?></td>
                                </tr>
                                <tr>
                                    <th style='' colspan='2'>Total Transaksi</th>
                               </tr>
                                <tr>
                                    <th colspan='2'><input type='text' style='' name="total_trans" min="1" class='form-control currency' id='totrans' required readonly></th>
                                </tr>
                                <tr>
                                    <th colspan='2'style=''>Total Diskon</th>
                               </tr>
                                <tr>
                                    <th colspan='2'><input type='text' style='' name="total_disk" class='form-control currency' id='todisk' required ></th>
                                </tr>
                                <tr>
                                    <th colspan='2' style=''>Total Setelah Diskon</th>
                               </tr>
                                <tr>
                                    <th colspan='2'><input type='text' style='' name="total_stlh" min="1" class='form-control currency' id='tostlh' required readonly></th>
                                </tr>
                                <tr>
                                    <th  colspan='2'style=''>Total Bayar &nbsp;&nbsp; </th>
                                </tr>
                                <tr>
                                    <th colspan='2'>
                                    <div class="input-group">
                                        <input type="text" name="total_bayar" min="1" class='form-control currency' id='tobyr' required>
                                        <div class="input-group-append">
                                        <a id='btn-byr' class='btn btn-secondary' role='button'><i class="fa fa-plus-circle"></i></a>
                                        </div>
                                    </div>
                                    </th>
                                </tr>
                                <tr>
                                    <th colspan='2' style=''>Kembalian</th>
                                </tr>
                                <tr>
                                    <th colspan='2'><input type='text' style='' name="kembalian" min="1" class='form-control currency' id='kembalian' required readonly></th>
                                </tr>        
                            </table>
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
                            <label for="judul" class="col-3 col-form-label">ubahBarang</label>
                            <div class="col-9">
                                <select class='form-control' id='modal-edit-kode' readonly>
                                    <option value=''>--- Pilih Barang ---</option>
                                    <?php
                                    $sql="select kode_barang,nama,hna from brg_barang where flag_aktif='1' and kode_lokasi='$kode_lokasi'";
                                    $rs=execute($sql);
                                    
                                    while($row = $rs->FetchNextObject($toupper=false)){
                                        echo "<option value='".$row->kode_barang."'>".$row->kode_barang."-".$row->nama."</option>";
                                    }
                                    ?>
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
                                <input type='text' class='form-control currency' maxlength='100' id='modal-edit-disc'>
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

    function setHarga2(id){
        <?php echo $jsArray; ?>  
        if (id != ""){
            return dtBrg[id].harga;  
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
        $('.row-barang').each(function(){
            var qtyb = $(this).closest('tr').find('.inp-qtyb').val();
            var hrgb = $(this).closest('tr').find('.inp-hrgb').val();
            var disc = $(this).closest('tr').find('.inp-disc').val();
            //var todis= (toNilai(hrgb) * toNilai(disc)) / 100
            var subb = (+qtyb * toNilai(hrgb)) - disc;
            total_brg += +subb;
        });
        $('#totrans').val(toRp(total_brg));

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

    $('#kd-gudang').selectize({
        selectOnTab: true
    });

    // $('#kd-barang').unbind();
    // $('#kd-barang').change(function(e){
    //     e.preventDefault();
    //     var id = $('#kd-barang option:selected').val();
    //     if (id != "" && id != null && id != undefined){
    //             addBarang();
    //     }
        
    // });

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

    // function ubahBarang(rowindex){
    //     var kd = $("#input-grid2 tr:eq("+rowindex+")").find('.inp-kdb').val();
    //     var qty = $("#input-grid2 tr:eq("+rowindex+")").find('.inp-qtyb').val();
    //     var harga = $("#input-grid2 tr:eq("+rowindex+")").find('.inp-hrgb').val();    
    //     var disc = $("#input-grid2 tr:eq("+rowindex+")").find('.inp-disc').val();
    //     $('#kd-barang')[0].selectize.setValue(kd);
    //     $('#kd-barang').val(kd);
    //     $('#qty-barang').val(qty);
    //     $('#disc-barang').val(disc);
    //     setHarga(kd);
    //     $("#input-grid2 tr:eq("+rowindex+")").remove();
    // }

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

        $.ajax({
                url: "<?=$root_ser?>/POSv2.php?fx=cekBonus",
                data: { 'kode_barang' : kd,'kode_lokasi' :'<?=$kode_lokasi?>','harga':hrg,'jumlah':qty},
                type: "post",
                dataType: "json",
                cache: false,
                success: function (data) {
                   
                    qty=data.jumlah;
                    disc+=data.diskon;
                    sub = (hrg*qty)-disc;
                    var input="";
                    input = "<tr class='row-barang'>";
                    input += "<td width='30%'>"+nama+"<input type='hidden' name='kode_barang[]' class='change-validation inp-kdb' value='"+kd+"' readonly required></td>";
                    input += "<td width='20%' style='text-align:right'>"+toRp(hrg)+"<input type='hidden' name='harga_barang[]' class='change-validation inp-hrgb'  value='"+toRp(hrg)+"' readonly required></td>";
                    input += "<td width='15%' style='text-align:right'>"+qty+"<input type='hidden' name='qty_barang[]' class='change-validation inp-qtyb'  value='"+qty+"' readonly required></td>";
                    input += "<td width='10%' style='text-align:right'>"+disc+"<input type='hidden' name='disc_barang[]' class='change-validation inp-disc'  value='"+disc+"' readonly required></td>";
                    input += "<td width='15%' style='text-align:right'>"+toRp(sub)+"<input type='hidden' name='sub_barang[]' class='change-validation inp-subb'  value='"+toRp(sub)+"' readonly required></td>";
                    input += "<td width='10%'></a><a class='btn btn-primary btn-sm ubah-barang' style='font-size:8px'><i class='fas fa-pencil-alt fa-1'></i></a>&nbsp;<a class='btn btn-danger btn-sm hapus-item' style='font-size:8px'><i class='fa fa-times fa-1'></i></td>";
                    input += "</tr>";
                    
                    $('.set-selected').closest('tr').remove();
                    // $('.set-selected').closest('tr').append(input);

                    $("#input-grid2").append(input);
                    hitungTotal();
                    $('#modal-edit').modal('hide');
                    
                }
            });
        
       
        
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

             $.ajax({
                url: "<?=$root_ser?>/POSv2.php?fx=cekBonus",
                data: { 'kode_barang' : kd,'kode_lokasi' :'<?=$kode_lokasi?>','harga':hrg,'jumlah':qty},
                type: "post",
                dataType: "json",
                cache: false,
                success: function (data) {
                   
                    qty=data.jumlah;
                    disc+=data.diskon;
                    sub = (hrg*qty)-disc;
                    input = "<tr class='row-barang'>";
                    input += "<td width='30%'>"+nama+"<input type='hidden' name='kode_barang[]' class='change-validation inp-kdb' value='"+kd+"' readonly required></td>";
                    input += "<td width='20%' style='text-align:right'>"+toRp(hrg)+"<input type='hidden' name='harga_barang[]' class='change-validation inp-hrgb'  value='"+toRp(hrg)+"' readonly required></td>";
                    input += "<td width='15%' style='text-align:right'>"+qty+"<input type='hidden' name='qty_barang[]' class='change-validation inp-qtyb'  value='"+qty+"' readonly required></td>";
                    input += "<td width='10%' style='text-align:right'>"+disc+"<input type='hidden' name='disc_barang[]' class='change-validation inp-disc'  value='"+disc+"' readonly required></td>";
                    input += "<td width='15%' style='text-align:right'>"+toRp(sub)+"<input type='hidden' name='sub_barang[]' class='change-validation inp-subb'  value='"+toRp(sub)+"' readonly required></td>";
                    input += "<td width='10%'></a><a class='btn btn-primary btn-sm ubah-barang' style='font-size:8px'><i class='fas fa-pencil-alt fa-1'></i></a>&nbsp;<a class='btn btn-danger btn-sm hapus-item' style='font-size:8px'><i class='fa fa-times fa-1'></i></td>";
                    input += "</tr>";
                    
                    $("#input-grid2").append(input);
                    
                    hitungTotal();
                    
                    $('#kd-barang').val('');
                    $('#kd-barang')[0].selectize.setValue('');
                    // $('#qty-barang').val('');
                    // $('#disc-barang').val('');
                    // $('#hrg-barang').val('');
                    $("#input-grid2 tr:last").focus();
                    $('#kd-barang-selectized').focus();
                    
                }
            });

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

    // $('#kd-barang').change(function(e){
    //     e.preventDefault();
    //     var id = $('#kd-barang option:selected').val();
        
    //         if (id != ""){
    //             addBarang();
    //         }
        
    // });

    $('#kd-barang-selectized').keydown(function(e){
        var value = String.fromCharCode(e.which) || e.key;
        var id = $('#kd-barang option:selected').val();
        
        if (e.which == 13 || e.which == 9) {
            e.preventDefault();
            if (id != ""){
                addBarang();
                $('#kd-barang')[0].selectize.setValue('');
            }
        }
    });

    $('#qty-barang').keydown(function(e){
        var value = String.fromCharCode(e.which) || e.key;
        
        if (e.which == 13) {
            e.preventDefault();
            
        }
        
    });

    $('#disc-barang').keydown(function(e){
        var value = String.fromCharCode(e.which) || e.key;
        
        if (e.which == 13) {
            e.preventDefault();
            addBarang();
        }
    });

    $('#tambah-barang').hide();
    $('#tambah-barang').click(function(){
        addBarang();
    });

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

    // $('#btn-plus').click(function(){
    //     $('#param').val('+');
    // });
    $('#btn-clear').click(function(){
        $('#inp-byr').val(0);
        $('#param').val('');
    });

    $('#nom0').click(function(){
        var tot= toNilai($('#tostlh').val());
        $('#inp-byr').val(tot);
        // $('#modal-bayar').modal('hide');
        // hitungTotal();
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
        // $('#modal-bayar').modal('hide');
        // hitungTotal();
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
            var totrans=$('#totrans').val();
            var todisk=$('#todisk').val();
            var tostlh=$('#tostlh').val();
            var tobyr=$('#tobyr').val();
            var kembalian=tobyr-tostlh;
            if(totrans <= 0){
                alert('Total transaksi tidak valid');
            }
            else if(tobyr <= 0){
                alert('Total bayar tidak valid');
            }
            else if(kembalian < 0){
                alert('Total Bayar kurang dari Total Transaksi');
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
                $.ajax({
                    type: 'POST',
                    url: '<?=$root_ser?>/POS.php?fx=simpanPnj',
                    dataType: 'json',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false, 
                    success:function(result){
                        alert('Input data '+result.message);
                        if(result.status){
                            // location.reload();
                            window.location.href='<?=$root_app?>/fNota?param='+result.id+'&pnj=1';
                        }
                    },
                    fail: function(xhr, textStatus, errorThrown){
                        alert('request failed:'+textStatus);
                    }
                });
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