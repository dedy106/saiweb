<?php
    session_start();
    $root_lib=$_SERVER["DOCUMENT_ROOT"];
    if (substr($root_lib,-1)!="/") {
        $root_lib=$root_lib."/";
    }
    include_once($root_lib.'app/kasir/setting.php');
    $kode_lokasi=$_COOKIE['lokasi'];
    $periode=$_COOKIE['periode'];
    $kode_pp=$_COOKIE['kodePP'];
    $nik=$_COOKIE['userLog'];

?>

<div id='canvasPreview'>
</div>

<script src="<?=$folderroot_js?>/sai.js"></script>
<script type="text/javascript">

    function drawLap(formData){
       saiPost('<?=$root_ser?>/LapClosing.php?fx=getLapClosing2', null, formData, null, function(data){
           if(data.result.length > 0){
               
            var mon_html = "<div align='center' id='sai-rpt-table-export-tbl-daftar-pnj'>";
               var arr_tl = [0,0,0,0,0,0,0,0,0];
               var x=1;
                for (var i=0;i<data.result.length;i++)
                { 

                    var line = data.result[i];
                    mon_html+=`
                    <table width="750px" cellspacing="2" cellpadding="1" border="0">
                        <tbody>
                            <tr>
                                <td class="style16" align="center"><b>Toko Asrama Putra TJ<br>Jl.Telekomunikasi No. 1 Trs.Buahbatu <br>Bandung</b></td>
                            </tr>
                            <tr>
                                <td class="style16" align="center"><b>LAPORAN CLOSING</b></td></tr><tr><td class="style16" align="center">`+data.periode+` </td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td align="center">
                                    <table width="100%" cellspacing="2" cellpadding="1" border="0">
                                        <style>
                                            td,th{
                                                padding:4px;
                                            }
                                        </style>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <table class="kotak" width="100%" cellspacing="0" cellpadding="0" border="1">
                                                        <tbody>
                                                            <tr>
                                                                <td colspan="20" style="padding:5px">
                                                                    <table width="100%" cellspacing="2" cellpadding="1" border="0">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td class='header_laporan' width='114'>No Bukti </td>
                                                                                <td class='header_laporan'>:&nbsp;`+line.no_bukti+`</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td class='header_laporan'>Tanggal   </td>
                                                                                <td class='header_laporan'>:&nbsp;`+line.tanggal+`</td>
                                                                            </tr>
                                                                                <tr>
                                                                                <td class='header_laporan'>Saldo Awal </td>
                                                                                <td class='header_laporan'>:&nbsp;`+line.saldo_awal+`</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td class='header_laporan'>Kasir  </td>
                                                                                <td class='header_laporan'>:&nbsp;`+line.nik_user+`</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td class='header_laporan'>Total Penjualan  </td>
                                                                                <td class='header_laporan'>:&nbsp;`+sepNum(line.total_pnj)+`</td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                            <tr bgcolor="#CCCCCC">
                                                                <td class="header_laporan" width="20" align="center">No</td>
                                                                <td class="header_laporan" width="70" align="center">No Penjualan</td>
                                                                <td class="header_laporan" width="100" align="center">Tgl Jual</td>
                                                                <td class="header_laporan" width="100" align="center">Periode</td>
                                                                <td class="header_laporan" width="200" align="center">Keterangan</td>
                                                                <td class="header_laporan" width="60" align="center">Total</td>
                                                                <td class="header_laporan" width="60" align="center">Diskon</td>
                                                            </tr>`;
                                                            var diskon=0; 
                                                            var total=0; 
                                                            var no=1;
                                                            var det='';
                                                            for (var x=0;x<data.result2.length;x++)
                                                                {
                                                                    var line2 = data.result2[x];
                                                                    if(line.no_bukti == line2.no_bukti){
                                                                        diskon+=+line2.diskon;
                                                                        total+=+line2.nilai;
                                                                    det+=  `<tr>
                                                                        <td align='center' class='isi_laporan'>`+no+`</td>
                                                                        <td  class='isi_laporan'>`+line2.no_jual+`</td>
                                                                        <td class='isi_laporan'>`+line2.tanggal+`</td>
                                                                        <td class='isi_laporan'>`+line2.periode+`</td>
                                                                        <td class='isi_laporan'>`+line2.keterangan+`</td>
                                                                        <td align='right' class='isi_laporan'>`+sepNum(line2.nilai)+`</td>
                                                                        <td align='right' class='isi_laporan'>`+sepNum(line2.diskon)+`</td>
                                                                        </tr>`;		
                                                                        no++;
                                                                    }
                                                                }
                                                            mon_html+=det+`
                                                                <tr>
                                                                    <td colspan='5' align='right'  class='header_laporan'>Total</td>
                                                                    <td align='right' class='header_laporan'>`+sepNum(total)+`</td>
                                                                    <td align='right' class='header_laporan'>`+sepNum(diskon)+`</td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan='7' class='header_laporan'> Terbilang : `+terbilang(total)+`</td>
                                                                </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <br>
                    <div style="page-break-after:always"></div>`;
                }               
               mon_html+="</div>"; 
               drawRptPage(mon_html, 'sai-rpt-table-export-tbl-daftar-pnj'); 
 
           }
       });
   }

   drawLap($formData);

   
function drawRptPage(html, table_id){
    $('#canvasPreview').html(html);
}
</script>
   