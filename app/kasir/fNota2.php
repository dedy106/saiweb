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
        <link rel="stylesheet" href="<?=$folderroot_css?>/style2.css">
        <title>Receipt example</title>
    </head>
    <body>
        <div class="ticket">
            <p class="centered">Toko Asrama Putra TJ 
                <br><span style='font-size:10px !important'>Jl.Telekomunikasi No. 1 Trs.Buahbatu</span>
                <br><span style='font-size:10px !important'>Bandung</span></p>
            <table class="no-border" id="table-det" style='padding-top:10px;width:100%'>
                  <style>
                  th {
                    padding: 2px !important;
                  }
                  td {
                    padding: 2px !important;
                  }
                  </style>
                    <tbody>      
                    </tbody>
            </table>
            <p class="centered"></p>
            <table class='no-border' style='width:100%'>
                <tbody>
                    <tr>
                        <td style="widtd:70%" class='' >Total Trans </td>
                        <td style='text-align:right' id="totrans"></td>
                    </tr>
                    <tr>
                        <td class=''>Total Diskon </td>
                        <td style='text-align:right' id="todisk"></td>
                    </tr>
                    <tr>
                        <td class=''>Total Set. Disc. </td>
                        <td style='text-align:right' id="tostlh"></td>
                    </tr>
                </tbody>
            </table>
            <p class="centered"></p>
            <table class='no-border' style='width:100%'>
                <tbody>
                    <tr>
                        <td class=''>Total Bayar </td>
                        <td style='text-align:right' id="tobyr"></td>
                    </tr>
                    <tr>
                        <td class=''>Kembalian </td>
                        <td style='text-align:right' id="kembali"></td>
                    </tr>
                </tbody>
            </table>
            <p class="centered"></p>
            <table class='no-border' style='width:100%'>
                <tbody>
                    <tr>
                        <td id='no_bukti' colspan='2'></td>
                    </tr>
                    <tr>
                        <td id="tgl" colspan='2'></td>
                    </tr>
                    <tr>
                        <td><b>Kasir :</b><span id="nik"></span></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
            <p style='text-align:center'>TERIMA KASIH</p>
        </div>
        <!-- <button id="btnPrint" class="hidden-print">Print</button> -->
        <!-- <script src="<?=$folderroot_js?>/script2.js"></script> -->
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
    window.open(root+'/fNota2/?param='+no_bukti+'&print='+stsPrint,'_blank'); 
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
                    html += `<tr>
                              <td colspan='4' style='text-align: justify;'>`+result.daftar[i].kode_barang+` - `+result.daftar[i].nama+`</td>
                              </tr>
                              <tr>
                              <td  class='text-right'>`+sepNum(result.daftar[i].jumlah)+''+result.daftar[i].sat_kecil+` @</td>
                              <td  class='text-right'>`+sepNum(result.daftar[i].harga)+`</td>
                              <td  class='text-right'>`+sepNum(result.daftar[i].diskon)+`</td>
                              <td  class='text-right'>`+sepNum(sub)+`</td>
                        </tr>
                    `;
                }
                
                $('#table-det tbody').html(html);
                if(printValue){
                    executePrint();
                }
            }
        }
    });
}

loadNota();
</script>