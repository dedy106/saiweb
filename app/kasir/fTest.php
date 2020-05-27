<?php
    $kode_lokasi=$_COOKIE['lokasi'];
    $nik=$_COOKIE['userLog'];

    $str_format="0000";
    $periode=date('Y').date('m');
    $per=date('y').date('m');
    $prefix=$kode_lokasi."-PNJ".$per.".";
    $sql2="select right(isnull(max(no_bukti),'0000'),".strlen($str_format).")+1 as id from trans_m where no_bukti like '$prefix%' and kode_lokasi='".$kode_lokasi."' ";

    // echo $sql2;
    
    $query = execute($sql2);
    
    $id = $prefix.str_pad($query->fields[0], strlen($str_format), $str_format, STR_PAD_LEFT);
    
    $no_bukti=$id;
?>
<form id="web-form-pos" method='POST'>
    <div class='row'>
        <div class='col-xs-12'>
            <center>
                <h4 style='margin-top:0px;'>
                    <b><?php echo $no_nota; ?></b>
                </h4>
            </center>
        </div>
    </div>
    <div class='row'>
        <div class='col-md-12'>
            <div class='box box-primary' style='margin-bottom:5px;'>
                <div class='box-header with-border'>
                    <h3 class="box-title">Penjualan</h3>
                    <button type='submit' class='btn btn-primary pull-right' id='tbl-submit'><i class='fa fa-floppy-o'></i> Simpan</button>
                </div>
                <div class='box-body'>
                    <!--<div class="well well-sm no-shadow" style="margin-top: 0px; margin-bottom:5px;">-->
                        <div class='col-sm-4'>
                            <table class='table' style='margin-bottom: 5px'>
                                <tr>
                                    <td style='width:40%; padding: 3px;'>Tanggal</td>
                                    <td style='width:5%; padding: 3px;'>:</td>
                                    <td style='width:55%; padding: 3px;' colspan='3'><input name="tgl" class='form-control' readonly value='<?php echo date('Y-m-d H:i:s'); ?>'></td>
                                </tr>
                                <tr>
                                    <td style='width:40%; padding: 3px;'>User</td>
                                    <td style='width:5%; padding: 3px;'>:</td>
                                    <td style='width:55%; padding: 3px;' colspan='3'><input name="nik_user" class='form-control' readonly  value='<?php echo $nik; ?>' ></td>
                                </tr>
                                <tr>
                                    <td style='width:40%; padding: 3px;'>No Bukti</td>
                                    <td style='width:5%; padding: 3px;'>:</td>
                                    <td style='width:55%; padding: 3px;' colspan='3'><input name="no_bukti" class='form-control' readonly value='<?php echo $no_bukti; ?>'></td>
                                </tr>
                            </table>
                            
                        </div>
                        <div class='col-sm-4 pull-right'>
                            <table class='table'>
                                <tr>
                                    <td>Total Transaksi</td>
                                    <td>:</td>
                                    <td><input type='text' name="total_trans" min="1" class='form-control currency' id='totrans' required readonly></td>
                                </tr>
                                <tr>
                                    <td>Total Diskon</td>
                                    <td>:</td>
                                    <td><input type='text' name="total_disk" class='form-control currency' id='todisk' required ></td>
                                </tr>
                                <tr>
                                    <td>Total Setelah Diskon</td>
                                    <td>:</td>
                                    <td><input type='text' name="total_stlh" min="1" class='form-control currency' id='tostlh' required readonly></td>
                                </tr>
                                <tr>
                                    <td>Total Bayar &nbsp;&nbsp; </td>
                                    <td>:</td>
                                    <td><div class="input-group">
                                        <input type='text' name="total_bayar" min="1" class='form-control currency' id='tobyr' required>
                                        <span class="input-group-addon"><a id='btn-byr' role='button'><i class="fa fa-plus-circle"></i></a></span>
                                    </div></td>
                                </tr>
                                <tr>
                                    <td>Kembalian</td>
                                    <td>:</td>
                                    <td><input type='text' name="kembalian" min="1" class='form-control currency' id='kembalian' required readonly></td>
                                </tr>                                
                            </table>
                        </div>
                    <!--</div>-->
                        <div class='col-md-12'>
                            <table class='table' style='margin-bottom: 5px'>
                                <tr>
                                    <td style='width:30%; padding: 3px;' colspan='2'>
                                        <select class='form-control' id="kd-barang">
                                            <option value=''>--- Pilih Barang ---</option>
                                            <?php
                                            $sql="select kode_barang,nama from brg_barang where flag_aktif='1' and kode_lokasi='$kode_lokasi'";
                                            $rs=execute($sql);
                                            
                                            while($row = $rs->FetchNextObject($toupper=false)){
                                                echo "<option value='".$row->kode_barang."'>".$row->kode_barang."-".$row->nama."</option>";
                                            }
                                            ?>
                                        </select>
                                    </td>
                                    <td style='width:20%; padding: 3px;'><input class='form-control currency' id="hrg-barang" readonly></td>
                                    <td style='width:15%; padding: 3px;'><input type='text' min='1' step='1' class='form-control currency' id="qty-barang"></td>
                                    <td style='width:15%; padding: 3px;'><input type='text' min='1' step='1' class='form-control currency' placeholder='disc%' id="disc-barang"></td>
                                    <td style='width:10%; padding: 3px;'><a class='btn btn-warning pull-right' id='tambah-barang'><i class='fa fa-plus'></i> Tambah</a></td>
                                </tr>
                            </table>
                            <div class='col-xs-12' style='overflow-y: scroll; max-height:120px; margin:0px; padding:0px;'>
                                <table class="table table-striped table-bordered" id="input-grid2">
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
                </div>
            </div>
        </div>
    </div>
</form>
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
                        <p><a class="btn btn-lg btn-default" id='nom1' style="width: 126px;">10.000</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class="btn btn-lg btn-default" id='nom2' style="width: 126px;">20.000</a></p>
                        <p><a class="btn btn-lg btn-default" id='nom3' style="width: 126px;">50.000</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class="btn btn-lg btn-default" id='nom4' style="width: 126px;">100.000</a></p>
                    </div>
            </div>
        </div>
    </div>
</div>
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

function hitungKembali(){
    
    var total_stlh = toNilai($('#tostlh').val());
    var total_bayar = toNilai($('#tobyr').val());
    kembalian = +total_bayar - +total_stlh; 
    if(kembalian < 0) kembalian = 0;  
    $("#kembalian").val(toRp(kembalian));
    
}

function hitungDisc(){
    
    var total_trans = toNilai($('#totrans').val());
    var total_disk= toNilai($('#todisk').val());
    var total_stlh = +total_trans - +total_disk;
    
    $('#tostlh').val(toRp(total_stlh));
    var total_bayar = toNilai($('#tobyr').val());
    kembalian = +total_bayar - +total_stlh;  
    if(kembalian < 0) kembalian = 0; 
    $("#kembalian").val(toRp(kembalian));
    
}

function hitungTotal(){
    
    // hitung total barang
    $('#todisk').val(0);
    var total_brg = 0;
    $('.row-barang').each(function(){
        var qtyb = $(this).closest('tr').find('.inp-qtyb').val();
        var hrgb = $(this).closest('tr').find('.inp-hrgb').val();
        var disc = $(this).closest('tr').find('.inp-disc').val();
        var todis= (toNilai(hrgb) * toNilai(disc)) / 100
        var subb = (+qtyb * toNilai(hrgb)) - todis;
        total_brg += +subb;
    });
    $('#totrans').val(toRp(total_brg));

    var total_disk= toNilai($('#todisk').val());
    var total_stlh = +total_brg - +total_disk;
    
    $('#tostlh').val(toRp(total_stlh));
    var total_bayar = toNilai($('#tobyr').val());
    // alert(total_bayar);
    if(kembalian < 0) kembalian = 0;
    kembalian = +total_bayar - +total_stlh;
    // alert(total_trans);
   
    $("#kembalian").val(toRp(kembalian));
    
}

$('#kd-barang').selectize({
    selectOnTab: true,
    // onChange: function(){
    //     $('#qty-barang').select().focus();
    // }
});

function setHarga(id){
    var post_url = "include_lib.php?hal=server/kasir/POS.php&fx=getDataBarang";
    if(id == '' || id == null){
        $('#qty-barang').val('');
        $('#hrg-barang').val('');
    }else{
        var qty = $('#qty-barang').val();
        var kode_lokasi= '<?php echo $kode_lokasi; ?>';
        $.ajax({
            url: post_url,
            data: { 'kode_barang' : id,'kode_lokasi' :kode_lokasi},
            type: "post",
            dataType: "json",
            cache: false,
            success: function (data) {
                harga = data.harga_barang;
                $('#hrg-barang').val(harga);
                
            }
        });
        $('#qty-barang').val(1);
        $('#disc-barang').val(0);
        // addBarang();
    }
}

function hapusBarang(rowindex){
    $("#input-grid2 tr:eq("+rowindex+")").remove();
}

function ubahBarang(rowindex){
    var kd = $("#input-grid2 tr:eq("+rowindex+")").find('.inp-kdb').val();
    var qty = $("#input-grid2 tr:eq("+rowindex+")").find('.inp-qtyb').val();
    var harga = $("#input-grid2 tr:eq("+rowindex+")").find('.inp-hrgb').val();    
    var disc = $("#input-grid2 tr:eq("+rowindex+")").find('.inp-disc').val();
    $('#kd-barang')[0].selectize.setValue(kd);
    $('#kd-barang').val(kd);
    $('#qty-barang').val(qty);
    $('#disc-barang').val(disc);
    setHarga(kd);
    $("#input-grid2 tr:eq("+rowindex+")").remove();
}

function addBarang(){
    // setHarga();
    var qty1 = toNilai($('#qty-barang').val());
    var hrg1 = toNilai($('#hrg-barang').val());
    var kd1 = $('#kd-barang').val();
    // || +qty1 <= 0 || +hrg1 <= 0
    if(kd1 == '' || +qty1 <= 0 || +hrg1 <= 0){
        alert('Masukkan data barang yang valid');
    }else{
        var kd = $('#kd-barang option:selected').val();
        var nama = $('#kd-barang option:selected').text();
        var qty = toNilai($('#qty-barang').val());
        var hrg = toNilai($('#hrg-barang').val());
        var disc = toNilai($('#disc-barang').val());
        var todis= (hrg * disc) / 100
        var sub = (+qty * +hrg) - todis;
        // var sub = +qty * +hrg;
        
        // cek barang sama
        $('.row-barang').each(function(){
            var kd_temp = $(this).closest('tr').find('.inp-kdb').val();
            var qty_temp = $(this).closest('tr').find('.inp-qtyb').val();
            var hrg_temp = $(this).closest('tr').find('.inp-hrgb').val();
            var disc_temp = $(this).closest('tr').find('.inp-disc').val();
            if(kd_temp == kd){
                qty+=+(toNilai(qty_temp));
                hrg+=+(toNilai(hrg_temp));
                disc+=+(toNilai(disc_temp));
                todis+=+(toNilai(hrg_temp)*toNilai(disc_temp))/100;
                sub=(hrg*qty)-todis;
                $(this).closest('tr').remove();
            }
        });
        
        input = "<tr class='row-barang'>";
        input += "<td width='30%'>"+nama+"<input type='hidden' name='kode_barang[]' class='change-validation inp-kdb' value='"+kd+"' readonly required></td>";
        input += "<td width='20%'>"+toRp(hrg)+"<input type='hidden' name='harga_barang[]' class='change-validation inp-hrgb'  value='"+toRp(hrg)+"' readonly required></td>";
        input += "<td width='15%'>"+qty+"<input type='hidden' name='qty_barang[]' class='change-validation inp-qtyb'  value='"+qty+"' readonly required></td>";
        input += "<td width='10%'>"+disc+"<input type='hidden' name='disc_barang[]' class='change-validation inp-disc'  value='"+disc+"' readonly required></td>";
        input += "<td width='15%'>"+toRp(sub)+"<input type='hidden' name='sub_barang[]' class='change-validation inp-subb'  value='"+toRp(sub)+"' readonly required></td>";
        input += "<td width='10%'></a><a class='btn btn-primary btn-sm ubah-barang'><i class='fa fa-pencil fa-1'></i></a>&nbsp;<a class='btn btn-danger btn-sm hapus-item'><i class='fa fa-times fa-1'></i></td>";
        input += "</tr>";
        
        $("#input-grid2").append(input);
        
        hitungTotal();
        
        $('#kd-barang').val('');
        $('#kd-barang')[0].selectize.setValue('');
        $('#qty-barang').val('');
        $('#disc-barang').val('');
        $('#hrg-barang').val('');
        // $('#tunai').val('');
        // $('#tcash').val('');
        // $('#edcm').val('');
        // $('#kembalian').text('');
        $("#input-grid2 tr:last").focus();
        $('#kd-barang-selectized').focus();
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

$('#kd-barang').change(function(e){
    // alert('test');
    e.preventDefault();
    var id = $('#kd-barang option:selected').val();
    setHarga(id);
});

// $('#hrg-barang').change(function(e){
//     // alert('test');
//     e.preventDefault();
//     addBarang();
// });

$('#kd-barang-selectized').keydown(function(e){
    var value = String.fromCharCode(e.which) || e.key;
    var id = $('#kd-barang option:selected').val();
    
    if (e.which == 13 || e.which == 9) {
        e.preventDefault();
        setHarga(id);
        $('#qty-barang').select().focus();
        // addBarang();
    }
    // else if(e.which == 17){
    //     e.preventDefault();
    //     $('#tunai').select().focus();
    // }else if(e.which == 16){
    //     e.preventDefault();
    //     $('#dd-tenant-selectized').focus();
    // }
});

$('#qty-barang').keydown(function(e){
    var value = String.fromCharCode(e.which) || e.key;
    
    if (e.which == 13) {
        e.preventDefault();
        $('#disc-barang').focus();
        // addBarang();
    }
    // else if(e.which == 17){
    //     e.preventDefault();
    //     $('#tunai').select().focus();
    // }
    // else if(e.which == 16){
    //     e.preventDefault();
    //     $('#dd-tenant-selectized').focus();
    // }else if(e.which == 9){
    //     e.preventDefault();
    //     $('#dd-barang-selectized').focus();
    // }
});

$('#disc-barang').keydown(function(e){
    var value = String.fromCharCode(e.which) || e.key;
    
    if (e.which == 13) {
        e.preventDefault();
        addBarang();
    }
});

$('#tambah-barang').click(function(){
    addBarang();
});

$('#todisk').change(function(){
    hitungDisc();
});

$('#tobyr').change(function(){
    hitungKembali();
});

$('#btn-byr').click(function(){
    $('#modal-bayar').modal('show');
});
$('#nom1').click(function(){
    $('#tobyr').val(10000);
    $('#modal-bayar').modal('hide');
    hitungTotal();
});
$('#nom2').click(function(){
    $('#tobyr').val(20000);
    $('#modal-bayar').modal('hide');
    hitungTotal();
});
$('#nom3').click(function(){
    $('#tobyr').val(50000);
    $('#modal-bayar').modal('hide');
    hitungTotal();
});
$('#nom4').click(function(){
    $('#tobyr').val(100000);
    $('#modal-bayar').modal('hide');
    hitungTotal();
});

// Simpan Penjualan
$('#web-form-pos').submit(function(e){
  e.preventDefault();

        if(kembalian < 0){
            alert('Kembalian tidak boleh kurang dari 0');
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
                url: 'include_lib.php?hal=server/kasir/POS.php&fx=simpanPnj',
                dataType: 'json',
                data: formData,
                contentType: false,
                cache: false,
                processData: false, 
                success:function(result){
                    alert('Input data '+result.message);
                    if(result.status){
                        location.reload();
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