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

<style media="print">
    div{
      font-size:8px !important;
      font-weight:bold !important;
    }
    .card{
        width:50mm !important;
    }
    h5,h6{
      font-weight:bold !important;
      font-size:10px !important;
    }
    body
    {
      width:100% !important;
      margin:0 !important;
      padding:0 !important;
    }
    .areanonprint{
      display:none;
    }
</style> 
<div class="container-fluid mt-3" style="color:black !important">
    <div class="row"  style="color:black !important">
        <div class="col-sm-4">
          <div class="card">
            <div class="card-body" style="color:black !important">
              <div class="row" style='display:block;border-bottom:1px dashed black'>
                <div class="col-md-12">
                    <h5 class="page-header text-center">
                      Toko Asrama Putra TJ<br>
                      Jl.Telekomunikasi No. 1 Trs.Buahbatu <br>
                      Bandung
                    </h5>
                </div>
              </div>
              
              <!-- Table row -->
              <div class="row mt-2"  style='border-bottom:1px dashed black'>
                <div class="col-md-12">
                  <table class="table no-border" id="table-det">
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
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->
              <?php 
                      
                  ?>

              <div class="row mt-2" style="display:block;border-bottom:1px dashed black">
                <div class="col-md-12">
                    <style>
                      th {
                        padding: 2px !important;
                        border:none;
                      }
                      td {
                        padding: 2px !important;
                        border:none;
                      }
                      </style>
                    <table class="table no-border">
                        <tbody><tr>
                          <td style="widtd:70%" class='' id="totrans">Total Trans </td>
                          <td class='text-right'></td>
                        </tr>
                        <tr>
                          <td class=''>Total Diskon </td>
                          <td class='text-right' id="todisk"></td>
                        </tr>
                        <tr>
                          <td class=''>Total Set. Disc. </td>
                          <td class='text-right' id="tostlh"></td>
                        </tr>
                      </tbody>
                    </table>
                </div>
              </div>
              <div class="row mt-2" style="display:block;border-bottom:1px dashed black">
                <div class="col-md-12">
                    <style>
                      th {
                        padding: 2px !important;
                        border:none;
                      }
                      td {
                        padding: 2px !important;
                        border:none;
                      }
                      </style>
                    <table class="table no-border">
                        <tbody>
                        <tr>
                          <td class=''>Total Bayar </td>
                          <td class='text-right' id="tobyr"></td>
                        </tr>
                        <tr>
                          <td class=''>Kembalian </td>
                          <td class='text-right' id="kembali"></td>
                        </tr>
                      </tbody>
                    </table>
                </div>
              </div>
              <div class="row mt-2" style="display:block"> 
                <div class="col-md-12">
                  <span id="no_bukti"></span><br>
                  <span id="tgl"></span><br>
                  <b>Kasir :</b><span id="nik"></span><br>
                </div>
              </div>
              <div class="row" style="display:block"> 
                <div class="col-md-12  text-center">              
                    <h6 class='mt-2'>TERIMA KASIH</h6>
                </div>
              </div>

              <!-- /.row -->

    <?php
        if(ISSET($_GET['print']) OR ISSET($_GET['download'])){
          echo"";
        } else{
            echo "
                <div class='row ml-1' id='areanonprint'>
                    <div class='col-xs-12'>
                        <div class='box' style='padding:0'>
                            <div class='box-body'>
                                <a  style='display:block' href='$root_print/fNota3/?param=$no_bukti&print=1' target='_blank' class='btn btn-primary pull-right mr-1'>
                                    <i class='fa fa-print'></i> Print
                                </a>";
                                if(ISSET($_GET['pnj'])){
                                  echo"<a href='$root_app/fPos' class='btn btn-secondary'><i class='fa fa-arrow-left'></i> Back </a>";
                                }
                                echo"
                            </div>
                        </div>
                    </div>
                </div>";
        }
    ?>
          </div>
        </div>
    </div>
  </div>
</div>
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
                    html += `<tr>
                              <td colspan='4'>`+result.daftar[i].kode_barang+` - `+result.daftar[i].nama+`</td>
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