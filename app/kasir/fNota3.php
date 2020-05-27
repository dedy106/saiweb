<?php
     session_start();
     $root_lib=$_SERVER["DOCUMENT_ROOT"];
     if (substr($root_lib,-1)!="/") {
         $root_lib=$root_lib."/";
     }
     include_once($root_lib.'app/kasir/setting.php');
 
 
     $kode_lokasi=$_COOKIE['lokasi'];
     $nik=$_COOKIE['userLog'];
     $tmp=explode("|",$_GET['param']);
     $no_bukti=$tmp[0];
     $stsPrint=$_GET['print'];

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Nota Penjualan</title>
        <style>
            .size_judul{
              font-size:12px;
            }
            
            .size_isi{
              font-size:10px;
            }

        </style>
        <style media="print">
            
            #nonPrint{
               display:none;
            }
        </style>
    </head>
    <body>
    <table width='100%' border='0' cellspacing='0' cellpadding='0'>
        <tr>
          <td colspan='2' align='center' class='size_judul'>Toko Asrama Putra TJ</td>
        </tr>
        <tr>
          <td colspan='2' align='center' class='size_judul'>Jl.Telekomunikasi No. 1 Trs.Buahbatu </td>
        </tr>
        <tr>
          <td colspan='2' align='center' class='size_judul'>Bandung</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td align='right'>&nbsp;</td>
        </tr>
    </table>
    <table width='100%' border='0' cellspacing='0' cellpadding='0' id='table-det' style='padding-bottom:20px'>

      </table>
      <table width='100%' border='0' cellspacing='0' cellpadding='0'>
        <tr>
          <td class='size_isi'>Total Transaksi</td>
          <td align='right' class='size_isi' id='totrans'>0</td>
        </tr>
        <tr>
          <td class='size_isi'>Total Diskon</td>
          <td align='right' class='size_isi' id='todisk'>0</td>
        </tr>
        <tr>
          <td class='size_isi'>Total Set. Disc</td>
          <td align='right' class='size_isi' id='tostlh'>0</td>
        </tr>
        <tr>
          <td class='size_isi'>Total Bayar</td>
          <td align='right' class='size_isi' id='tobyr'>0</td>
        </tr>
        <tr>
          <td class='size_isi'>Kembalian</td>
          <td align='right' class='size_isi' id='kembali'>0</td>
        </tr>
        <tr>
          <td class='size_isi'>&nbsp;</td>
          <td align='right' class='size_isi'>&nbsp;</td>
        </tr>
        <tr>
        <tr>
          <td class='size_isi' colspan='2' id='no_bukti'></td>
        </tr>
        <tr>
          <td class='size_isi' colspan='2' id='tgl'></td>
        </tr>
        <tr>
          <td class='size_isi'>Kasir:<span id='nik'></span></td>
          <td class='size_isi'></td>
        </tr>
        <tr>
          <td class='size_isi'>&nbsp;</td>
          <td align='right' class='size_isi'>&nbsp;</td>
        </tr>
        <tr>
          <td colspan='2' align='center' class='size_judul'>Terima Kasih </td>
        </tr>
      </table>
      <a href='<?=$root_app?>/fPos' id='nonPrint' class='btn btn-secondary' style='margin-top:20px'><i class='fa fa-arrow-left'></i> Back </a>
    </body>
</html>
<script src="<?=$folderroot_js?>/jquery-3.4.1.js" ></script>
<script>
var no_bukti = '<?=$no_bukti?>';
var stsPrint = "<?=$stsPrint?>";
var root = "<?php echo $root_print; ?>";
if(stsPrint == 0) { 
    var printValue = false;
}else{
    var printValue = true ;
}

function printNota(){
    window.open(root+'/fNota3/?param='+no_bukti+'&print='+stsPrint,'_blank'); 
}

function executePrint(){
  setTimeout(function() { 
      window.print();
    }, 1500);
}

function sepNum(x){
    var num = parseFloat(x).toFixed(0);
    var parts = num.toString().split(".");
    var len = num.toString().length;
    // parts[1] = parts[1]/(Math.pow(10, len));
    parts[0] = parts[0].replace(/(.)(?=(.{3})+$)/g,"$1.");
    return parts.join(",");
}

function loadNota(){
    $.ajax({
        type: 'GET',
        url: '<?=$root_ser?>/POS.php?fx=getNota',
        dataType: 'json',
        data: {'no_bukti':no_bukti,'kode_lokasi':'<?=$kode_lokasi?>','nik_user':'<?=$nik?>','kode_pp':'<?=$kode_pp?>'},
        success:function(result){    
            if(result.status){
                var html = '';
                $('#no_bukti').text(result.no_jual);
                $('#nik').text(result.nik);
                $('#tgl').text(result.tgl);
                $('#totrans').text(sepNum(result.total_trans));
                $('#todisk').text(sepNum(result.total_disk));
                $('#tostlh').text(sepNum(result.total_stlh));
                $('#tobyr').text(sepNum(result.total_byr));
                $('#kembali').text(sepNum(result.kembalian));
                for(var i =0; i<result.daftar.length;i++){
                    var sub=(result.daftar[i].harga*result.daftar[i].jumlah)-result.daftar[i].diskon;
                    html += `
                        <tr>
                          <td width='85%' class='size_isi'>`+result.daftar[i].nama+`  X `+sepNum(result.daftar[i].jumlah)+`</td>
                          <td width='15%' align='right' class='size_isi'>`+sepNum(result.daftar[i].total)+`</td>
                        </tr>
                    `;
                }
                
                $('#table-det').html(html);
                if(printValue){
                    executePrint();
                }
            }
        }
    });
}

loadNota();
</script>