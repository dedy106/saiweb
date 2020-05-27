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
</style> 
<div class="container-fluid mt-3" style="color:black !important">
    <div class="row"  style="color:black !important">
        <div class="col-sm-4">
          <div class="card">
            <div class="card-body" style="color:black !important">
              <div class="row" style='display:block;border-bottom:1px dashed black'>
                <div class="col-md-12">
                    <h5 class="page-header text-center">
                      XXX, Inc
                    </h5>
                </div>
              </div>
              <!-- Table row -->
              <div class="row mt-2"  style='border-bottom:1px dashed black'>
                <div class="col-md-12">
                  <table class="table no-border" id="table-det">
                  <style>
                  th {
                    padding: 8px !important;
                  }
                  td {
                    padding: 8px !important;
                  }
                  </style>
                    <tbody>        
                    </tbody>
                  </table>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->

              <div class="row mt-2" style="display:block;border-bottom:1px dashed black">
                <div class="col-md-12">
                  <div class="">
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
                        <td style="width:70%"  >Total Pembelian </td>
                        <td class='text-right'><span id="totpem"></span></td>
                      </tr>
                      <tr>
                        <td >Total Diskon </td>
                        <td class='text-right'><span id="totdisk"></span></td>
                      </tr>
                      <tr>
                        <td >Total PPN</td>
                        <td class='text-right'><span id="totppn"></span></td>
                      </tr>
                      <tr>
                        <td >Total Transaksi </td>
                        <td class='text-right'><span id="tottrans"></span></td>
                      </tr>
                    </tbody></table>
                  </div>
                </div>
                <!-- /.col -->
              </div>
              <!-- info row -->
              <div class="row mt-2" style="display:block"> 
                <div class="col-md-12">
                  <span id="no_bukti"></span><br>
                  <span id="tgl"></span><br>
                  <b>Kasir :</b> <span id="nik"></span><br>
                </div>
                <!-- /.col -->
              </div>
              <div class="row" style="display:block"> 
                <div class="col-md-12  text-center">              
                    <h6 class='mt-2'>TERIMA KASIH</h6>
                </div>
              </div>
              <!-- /.row -->

    <?php
       
            echo "
                <div class='row ml-1 area-noprint' style='display:none'>
                    <div class='col-xs-12'>
                        <div class='box' style='padding:0'>
                            <div class='box-body'>
                                <a href='#' class='btn btn-primary pull-right mr-1' id='btnPrint'>
                                    <i class='fa fa-print'></i> Print
                                </a><a href='$root_app/fDfrBeli2' class='btn btn-secondary' id='btnBack'><i class='fa fa-arrow-left'></i> Close </a>
                            </div>
                        </div>
                    </div>
                </div>";
    ?>
          </div>
        </div>
    </div>
  </div>
</div>
<script>
var no_bukti = param;
// var stsPrint = stsPrint;
// var pmb = pmb;
var root = "<?php echo $root_print; ?>";
var $pr=false;
if(stsPrint == 0) { 
    var printValue = false;
    $('.area-noprint').show();
}else{
    var printValue = true ;
    $('.area-noprint').hide();
}

if(pmb == 1) { 
    $('#btnPrint').show();
}else{
    $('#btnPrint').hide();
}



function printNota(){
    window.open(root+'/fNotaBeli2/?param='+no_bukti+'&print=1','_blank'); 
}

$('.area-noprint').on('click','#btnPrint',function(){
    printNota();
});

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
        url: '<?=$root_ser?>/Pembelian.php?fx=getNota',
        dataType: 'json',
        async:false,
        data: {'no_bukti':no_bukti,'kode_lokasi':'<?=$kode_lokasi?>','nik_user':'<?=$nik?>','kode_pp':'<?=$kode_pp?>'},
        success:function(result){    
            if(result.status){
                var html = '';
                $('#no_bukti').text(result.no_bukti);
                $('#nik').text(result.nik);
                $('#tgl').text(result.tgl);
                $('#tottrans').text(sepNum(result.tottrans));
                $('#totpem').text(sepNum(result.totpemb));
                $('#totdisk').text(sepNum(result.totdisk));
                $('#totppn').text(sepNum(result.totppn));
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