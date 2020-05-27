<?php
    session_start();
    $root_lib=$_SERVER["DOCUMENT_ROOT"];
    if (substr($root_lib,-1)!="/") {
        $root_lib=$root_lib."/";
    }
    include_once($root_lib.'app/dago/setting.php');
    $kode_lokasi=$_SESSION['lokasi'];
    $periode=$_SESSION['periode'];
    $kode_pp=$_SESSION['kodePP'];
    $nik=$_SESSION['userLog'];

?>
<style>
@media print {
   body { font-size: 12pt !important }
 }
</style>
<div id='canvasPreview' style='font-size:12pt !important;'>
</div>
<script type="text/javascript">


    function drawLap(formData){
       saiPost('<?=$root_ser?>/LapPbyr.php?fx=getLap', null, formData, null, function(res){
           if(res.result.length > 0){
                $('#pagination').html('');
                var show = $('#show')[0].selectize.getValue();
                generatePagination('pagination',show,res);
          
              
           }
       });
   }

   drawLap($formData);

   
   function drawRptPage(data,res,from,to){
        var data = data;
        console.log(data.length);
        if(data.length > 0){
            if(res.back){
                var back= `<div class="row mb-2">
                    <div class="col-md-12 pull-right">
                    <button type="button" class="btn btn-secondary ml-2" id="btn-back" style="float:right;"><i class="fa fa-undo"></i> Back</button></div></div>`;
            }else{
                var back= ``;
            }

            var mon_html = `<div align='center' style='padding:10px' id='sai-rpt-table-export-tbl-daftar-reg'>
                    `+back;
                    var foto = "<?=$folderroot_img?>/dago.png";
                    var arr_tl = [0,0,0,0,0,0,0,0,0];
                    var x=1;
                    for(var i=0;i<data.length;i++){
                        var line = data[i];
                        mon_html +=`
                        <style>
                            td,th{
                                padding:4px !important;
                            }
                        </style>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card card-body printableArea">
                                    <h3 class='text-left'><b>KUITANSI</b> <span class="pull-right">#`+line.no_kwitansi+`</span></h3>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="pull-left">
                                                <address>
                                                    <div class="row">
                                                        <div class="col-6 text-left"><img src="`+foto+`" width="150" height="90"></div>
                                                        <div class="col-6 text-right">
                                                        Jl. Puter No. 7 Bandung<br>
                                                        Tlp. 022-2500307, 022-2531259,<br>02517062
                                                        </div>
                                                    </div>
                                                </address>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="table-responsive m-t-40" style="clear: both;">
                                                <table class="table no-border">
                                                    <tbody>
                                                        <tr>
                                                            <td width="154">TANGGAL BAYAR</td>
                                                            <td width="244">: `+line.tgl_bayar+`</td>
                                                        </tr>
                                                        <tr>
                                                            <td>DITERIMA DARI</td>
                                                            <td>: `+line.peserta+`</td>
                                                        </tr>

                                                        <tr>
                                                            <td>PAKET / ROOM</td>
                                                            <td>: `+line.paket+` / `+line.room+`</td>
                                                        </tr>
                                                        <tr>
                                                            <td>HARGA PAKET </td>
                                                            <td>: `+line.kode_curr+` `+sepNum(line.harga_paket)+`</td>
                                                        </tr>
                                                        <tr>
                                                            <td>KEBERANGKATAN </td>
                                                            <td>: `+line.jadwal+`</td>
                                                        </tr>
                                                        <tr>
                                                            <td>MARKETING</td>
                                                            <td>: `+line.nama_marketing+`</td>
                                                        </tr>
                                                        <tr>
                                                            <td>AGEN / REFERAL</td>
                                                            <td>:  `+line.nama_agen+` / `+line.referal+`</td>
                                                        </tr>
                                                        <tr><td>&nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2" align="center"><b>DATA PEMBAYARAN</b></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="border-top:1px dotted black;border-bottom:1px dotted black" width="154">BIAYA PAKET (RP) </td>
                                                            <td style="border-top:1px dotted black;border-bottom:1px dotted black" width="244">: `+sepNum(line.biaya_paket)+` - KURS : `+sepNum(line.kurs)+`</td>
                                                        </tr>
                                                        <tr>
                                                            <td>SISTEM PEMBAYARAN</td>
                                                            <td>: Cicilan Ke-`+line.cicil_ke+`</td>
                                                        </tr>
                                                        <tr>
                                                            <td>SALDO </td>
                                                            <td>: `+sepNum(line.saldo)+`</td>
                                                        </tr>
                                                        <tr>
                                                            <td>TOTAL BAYAR </td>
                                                            <td>: `+sepNum(line.bayar)+`</td>
                                                        </tr>
                                                        <tr>
                                                            <td>TERBILANG </td>
                                                            <td width="300">: `+terbilang(line.bayar)+`</td>
                                                        </tr>
                                                        <tr>
                                                            <td>SISA </td>
                                                            <td>: `+sepNum(line.sisa)+`</td>
                                                        </tr>
                                                        <tr>
                                                            <td>DIINPUT OLEH </td>
                                                            <td>: `+line.nik_user+` </td>
                                                        </tr>
                                                        <tr><td>&nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                            <td>&nbsp;</td>
                                                            <td>&nbsp;</td>
                                                        </tr>
                                                    <tr>
                                                        <td align="left"></td>
                                                        <td align="center">Customer,</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="">&nbsp;</td>
                                                        <td style="">&nbsp;</td>
                                                    </tr>
                                                    <tr>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                    </tr>
                                                        <tr>
                                                            <td valign="top" align="left"></td>
                                                            <td align="center">(`+line.peserta+`)</td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br><DIV style='page-break-after:always'></DIV>`;
                                
                    }
               mon_html+=`</div>`;
            }
        $('#canvasPreview').html(mon_html);
        $('li.first a ').html("<i class='icon-control-start'></i>");
        $('li.last a ').html("<i class='icon-control-end'></i>");
        $('li.prev a ').html("<i class='icon-arrow-left'></i>");
        $('li.next a ').html("<i class='icon-arrow-right'></i>");
        $('#pagination').append(`<li class="page-item all"><a href="#" class="page-link"><i class="far fa-list-alt"></i></a></li>`);
    }
</script>
   