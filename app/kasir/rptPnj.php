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
       saiPost('<?=$root_ser?>/Laporan.php?fx=getLapPnj2', null, formData, null, function(data){
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
                                <td class="style16" align="center">
                                    <b>Toko Asrama Putra TJ<br>Jl.Telekomunikasi No. 1 Trs.Buahbatu <br>Bandung</b>
                                </td>
                            </tr>
                            <tr>
                                <td class="style16" align="center"><b>LAPORAN PENJUALAN</b></td>
                            </tr>
                            <tr>
                                <td class="style16" align="center">`+data.periode+`</td>
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
                                                                <td colspan="18" style="padding:5px">
                                                                    <table width="100%" cellspacing="2" cellpadding="1" border="0">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td class='header_laporan' width='114'>No Bukti </td>
                                                                                <td class='header_laporan'>:&nbsp;`+line.no_jual+`</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td class='header_laporan'>Tanggal   </td>
                                                                                <td class='header_laporan'>:&nbsp;`+line.tanggal+`</td>
                                                                            </tr>
                                                                            
                                                                            <tr>
                                                                                <td class='header_laporan'>Gudang  </td>
                                                                                <td class='header_laporan'>:&nbsp;`+line.kode_gudang+` -&nbsp; `+line.nama_pp+`</td>
                                                                            </tr>

                                                                            <tr>
                                                                                <td class='header_laporan'>Keterangan   </td>
                                                                                <td class='header_laporan'>:&nbsp;`+line.keterangan+`</td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                            <tr bgcolor="#CCCCCC">
                                                                <td class="header_laporan" width="20" align="center">No</td>
                                                                <td class="header_laporan" width="70" align="center">Kode Barang</td>
                                                                <td class="header_laporan" width="250" align="center">Nama Barang</td>
                                                                <td class="header_laporan" width="60" align="center">Satuan</td>
                                                                <td class="header_laporan" width="90" align="center">Harga</td>
                                                                <td class="header_laporan" width="90" align="center">Diskon</td>
                                                                <td class="header_laporan" width="50" align="center">Jumlah</td>
                                                                <td class="header_laporan" width="50" align="center">Bonus</td>
                                                                <td class="header_laporan" width="90" align="center">Sub Total</td>
                                                            </tr>`;
                                                            
                                                            var harga=0; 
                                                            var diskon=0; 
                                                            var jumlah=0; 
                                                            var bonus=0; 
                                                            var total=0; 
                                                            var det = '';
                                                            var no=1;
                                                            for (var x=0;x<data.result2.length;x++)
                                                            {
                                                                var line2 = data.result2[x];
                                                                if(line.no_jual == line2.no_bukti){
                                                                    harga+=+line2.harga;
                                                                    diskon+=+line2.diskon;
                                                                    jumlah+=+line2.jumlah;
                                                                    bonus+=+line2.bonus;
                                                                    total+=+line2.total;
                                                                    det+=`<tr>
                                                                        <td align='center' class='isi_laporan'>`+no+`</td>
                                                                        <td  class='isi_laporan'>`+line2.kode_barang+`</td>
                                                                        <td class='isi_laporan'>`+line2.nama_brg+`</td>
                                                                        <td class='isi_laporan'>`+line2.satuan+`</td>
                                                                        <td align='right' class='isi_laporan'>`+sepNum(line2.harga)+`</td>
                                                                        <td align='right' class='isi_laporan'>`+sepNum(line2.diskon)+`</td>
                                                                        <td align='right' class='isi_laporan'>`+sepNum(line2.jumlah)+`</td>
                                                                        <td align='right' class='isi_laporan'>`+sepNum(line2.bonus)+`</td>
                                                                        <td align='right' class='isi_laporan'>`+sepNum(line2.total)+`</td>
                                                                    </tr>`;	
                                                                    no++;
                                                                }

                                                            }
                                                            mon_html+=det+`
                                                            <tr>
                                                                <td colspan='4' align='center'  class='header_laporan'>Total</td>
                                                                <td align='right' class='header_laporan'>&nbsp;</td>
                                                                <td align='right' class='header_laporan'>`+sepNum(diskon)+`</td>
                                                                <td align='right' class='header_laporan'>`+sepNum(jumlah)+`</td>
                                                                <td align='right' class='header_laporan'>`+sepNum(bonus)+`</td>
                                                                <td align='right' class='header_laporan'>`+sepNum(total)+`</td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan='8' align='right'  class='header_laporan'>PPN</td>
                                                                <td align='right' class='header_laporan'>`+sepNum(line.ppn)+`</td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan='8' align='right'  class='header_laporan'>Total</td>
                                                                <td align='right' class='header_laporan'>`+sepNum(line.nilai)+`</td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan='11' class='header_laporan'> Terbilang : `+terbilang(line.nilai)+`</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="right">
                                                    <table class="kotak" width="600" cellspacing="0" cellpadding="0" border="1">
                                                        <tbody>
                                                            <tr>
                                                                <td width="300" valign="top">
                                                                    <table width="100%" cellspacing="2" cellpadding="1" border="0">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td class="header_laporan" align="center">Barang telah diterima dengan baik,</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td height="50" align="center">&nbsp;</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td align="center">&nbsp;</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td class="header_laporan" align="center">NIK.</td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                                <td width="200" valign="top">
                                                                    <table width="100%" cellspacing="2" cellpadding="1" border="0">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td class="header_laporan" align="center">Controler,</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td height="50" align="center">&nbsp;</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td align="center">&nbsp;</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td class="header_laporan" align="center">NIK.</td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                                <td width="200" valign="top">
                                                                    <table width="100%" cellspacing="2" cellpadding="1" border="0">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td class="header_laporan" align="center">Hormat Kami,</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td height="50" align="center">&nbsp;</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td align='center' class='header_laporan'><u>`+line.nama_user+`</u></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td align='center' class='header_laporan'>NIK.`+line.nik_user+`</td>
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
                                </td>
                            </tr>
                        </tbody>
                    </table><br>
                    <div style="page-break-after:always"></div>`;
                }               
               mon_html+=`</div>`;
               drawRptPage(mon_html, 'sai-rpt-table-export-tbl-daftar-pnj');  
           }
       });
   }

   drawLap($formData);

   
function drawRptPage(html, table_id){
    $('#canvasPreview').html(html);
}
</script>
   