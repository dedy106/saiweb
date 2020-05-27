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
       saiPost('<?=$root_ser?>/Laporan.php?fx=getLapReg2', null, formData, null, function(res){
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
                var back= `<button type="button" class="btn btn-secondary ml-2" id="btn-back" style="float:right;">
                <i class="fa fa-undo"></i> Back</button>`;
            }else{
                var back= ``;
            }

            var mon_html = `<div align='center' style='padding:10px' id='sai-rpt-table-export-tbl-daftar-reg'>
                    `+back;
                    var arr_tl = [0,0,0,0,0,0,0,0,0];
                    var x=1;
                    for(var i=0;i<data.length;i++){
                        var line = data[i];
                        mon_html +=`
                                <table width='100%' class='table no-border' cellspacing='1' cellpadding='2'>
                                <style>
                                    td,th{
                                        padding:5px !important;
                                    }
                                </style>
                                <tr>
                                <td colspan='2' align='center' style='font-weight:bold;'>FORMULIR PENDAFTARAN UMROH </td>
                                </tr>
                                <tr>
                                <td colspan='2'>&nbsp;</td>
                                </tr>
                                <tr>
                                <td colspan='2' style='font-weight:bold;'>DATA PRIBADI </td>
                                </tr>
                                <tr>
                                <td width='30%' style='font-weight:bold;'>NO REGISTRASI </td>
                                <td width='70%'>:&nbsp;`+line.no_reg+`</td>
                                </tr>
                                <tr>
                                <td width='30%' style='font-weight:bold;'>NO PESERTA </td>
                                <td width='70%'>:&nbsp;`+line.no_peserta+`</td>
                                </tr>
                                <tr>
                                <td width='30%' style='font-weight:bold;'>NAMA LENGKAP </td>
                                <td width='70%'>:&nbsp;`+line.peserta+`</td>
                                </tr>
                                <tr>
                                <td style='font-weight:bold;'>NO ID CARD </td>
                                <td>:&nbsp;`+line.id_peserta+`</td>
                                </tr>
                                <tr>
                                <td style='font-weight:bold;'>STATUS</td>
                                <td>:&nbsp;`+line.status+`</td>
                                </tr>
                                <tr>
                                <td style='font-weight:bold;'>JENIS KELAMIN </td>
                                <td>:&nbsp;`+line.jk+`</td>
                                </tr>
                                <tr>
                                <td style='font-weight:bold;'>TEMPAT &amp; TGL LAHIR </td>
                                <td>:&nbsp;`+line.tempat+` `+line.tgl_lahir+`</td>
                                </tr>
                                <tr>
                                <td style='font-weight:bold;'>BERANGKAT DENGAN </td>
                                <td>:&nbsp;`+line.brkt_dgn+`<br> Hubungan : `+line.hubungan+`</td>
                                </tr>
                                <tr>
                                <td style='font-weight:bold;'>PERNAH UMROH / HAJI </td>
                                <td>:&nbsp;`+line.th_umroh+`/`+line.th_haji+`</td>
                                </tr>
                                <tr>
                                <td style='font-weight:bold;'>PEKERJAAN</td>
                                <td>:&nbsp;`+line.nama_pekerjaan+`</td>
                                </tr>
                                <tr>
                                <td style='font-weight:bold;'>NO PASSPORT </td>
                                <td>:&nbsp;`+line.nopass+`</td>
                                </tr>
                                <tr>
                                <td style='font-weight:bold;'>KANTOR IMIGRASI </td>
                                <td>:&nbsp;`+line.kantor_mig+`</td>
                                </tr>
                                <tr>
                                <td style='font-weight:bold'>HP</td>
                                <td>:&nbsp;`+line.hp+`</td>
                                </tr>
                                <tr>
                                <td style='font-weight:bold;'>TELEPON</td>
                                <td>:&nbsp;`+line.telp+`</td>
                                </tr>
                                <tr>
                                <td style='font-weight:bold;'>EMAIL</td>
                                <td>:&nbsp;`+line.email+`</td>
                                </tr>
                                <tr>
                                <td style='font-weight:bold;'>ALAMAT</td>
                                <td>:&nbsp;`+line.alamat+`</td>
                                </tr>
                                <tr>
                                <td style='font-weight:bold;'>NO EMERGENCY </td>
                                <td>:&nbsp;`+line.ec_telp+`</td>
                                </tr>
                                <tr>
                                <td style='font-weight:bold;'>MARKETING</td>
                                <td>:&nbsp;`+line.nama_marketing+`</td>
                                </tr>
                                <tr>
                                <td style='font-weight:bold;'>AGEN</td>
                                <td>:&nbsp;`+line.nama_agen+`</td>
                                </tr>
                                <tr>
                                <td style='font-weight:bold;'>REFERAL</td>
                                <td>:&nbsp;`+line.referal+`</td>
                                </tr>
                                <tr>
                                <td colspan='2'>&nbsp;</td>
                                </tr>
                                <tr>
                                <td colspan='2' style='font-weight:bold;'>DATA KELANGKAPAN PERJALANAN </td>
                                </tr>
                                <tr>
                                <td style='font-weight:bold;'>PAKET</td>
                                <td>:&nbsp;`+line.namapaket+`</td>
                                </tr>
                                <tr>
                                <td style='font-weight:bold;'>PROGRAM UMROH / HAJI </td>
                                <td>:&nbsp;`+line.jenis_paket+`</td>
                                </tr>
                                <tr>
                                <td style='font-weight:bold;'>TYPE ROOM </td>
                                <td>:&nbsp;`+line.type+`</td>
                                </tr>
                                <tr>
                                <td style='font-weight:bold;'>BIAYA PAKET </td>
                                <td>:&nbsp;`+sepNum(line.harga)+`</td>
                                </tr>
                                <tr>
                                <td style='font-weight:bold;'>BIAYA ROOM </td>
                                <td>:&nbsp;`+sepNum(line.harga_room)+`</td>
                                </tr>
                                <tr>
                                <td style='font-weight:bold;'>DISKON</td>
                                <td>:&nbsp;`+sepNum(line.diskon)+`</td>
                                </tr>
                                <tr>
                                <td style='font-weight:bold;'>TGL KEBERANGKATAN </td>
                                <td>:&nbsp;`+line.tgl_berangkat+`</td>
                                </tr>
                                <tr>
                                <td style='font-weight:bold;'>UKURAN PAKAIAN </td>
                                <td>:&nbsp;`+line.uk_pakaian+`</td>
                                </tr>
                                <tr>
                                <td style='font-weight:bold;'>SUMBER INFORMASI </td>
                                <td>:&nbsp;`+line.info+`</td>
                                </tr>
                                <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                </tr>
                                <tr>
                                <td align='center'>Calon Jamaah</td>
                                <td align='center'>MO</td>
                                </tr>
                                <tr>
                                <td height='60'>&nbsp;</td>
                                <td>&nbsp;</td>
                                </tr>
                                <tr>
                                <td style='text-align:center'>(..............................................)</td>
                                <td style='text-align:center'>(..............................................)</td>
                                </tr>
                                </table>
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
   