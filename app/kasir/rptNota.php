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
        saiPost('<?=$root_ser?>/Laporan.php?fx=getLapPnj2', null, formData, null, function(res){
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
               
               var mon_html = "<div id='sai-rpt-table-export-tbl-daftar-pnj'>";
                   var arr_tl = [0,0,0,0,0,0,0,0,0];
                   var x=1;
                   for (var i=0;i<data.length;i++)
                   { 
                       var line = data[i];
                       mon_html+=`
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
                        <table width='100%' border='0' cellspacing='0' cellpadding='0' id='table-det' style='padding-bottom:20px'>`;
                        var harga=0; 
                        var diskon=0; 
                        var jumlah=0; 
                        var bonus=0; 
                        var total=0;  
                        var subTot=0; 
                        var det = '';
                        var no=1;
                        var sub = 0;       
                        for(var x =0; x<res.result2.length;x++){
                            var line2 = res.result2[x];
                            if(line.no_jual == line2.no_bukti){

                                var sub=(line2.harga*line2.jumlah)-line2.diskon;
                                det += `
                                <tr>
                                    <td width='85%' class='size_isi'>`+line2.nama_brg+`  X `+sepNum(line2.jumlah)+`</td>
                                    <td width='15%' align='right' class='size_isi'>`+sepNum(line2.total)+`</td>
                                </tr>
                                `;
                            }
                        }
                        var total_trans=parseFloat(line.nilai)+parseFloat(line.diskon);
                        var total_disk=parseFloat(line.diskon);
                        var total_stlh=parseFloat(line.nilai);
                        var total_byr=parseFloat(line.tobyr);
                        var kembalian=parseFloat(line.tobyr)-parseFloat(line.nilai);
                        mon_html+=det+
                        `</table>
                        <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                            <tr>
                            <td class='size_isi'>Total Transaksi</td>
                            <td align='right' class='size_isi' id='totrans'>`+sepNum(total_trans)+`</td>
                            </tr>
                            <tr>
                            <td class='size_isi'>Total Diskon</td>
                            <td align='right' class='size_isi' id='todisk'>`+sepNum(total_disk)+`</td>
                            </tr>
                            <tr>
                            <td class='size_isi'>Total Set. Disc</td>
                            <td align='right' class='size_isi' id='tostlh'>`+sepNum(total_stlh)+`</td>
                            </tr>
                            <tr>
                            <td class='size_isi'>Total Bayar</td>
                            <td align='right' class='size_isi' id='tobyr'>`+sepNum(total_byr)+`</td>
                            </tr>
                            <tr>
                            <td class='size_isi'>Kembalian</td>
                            <td align='right' class='size_isi' id='kembali'>`+sepNum(kembalian)+`</td>
                            </tr>
                            <tr>
                            <td class='size_isi'>&nbsp;</td>
                            <td align='right' class='size_isi'>&nbsp;</td>
                            </tr>
                            <tr>
                            <tr>
                            <td class='size_isi' colspan='2' id='no_bukti'>`+line.no_jual+`</td>
                            </tr>
                            <tr>
                            <td class='size_isi' colspan='2' id='tgl'>`+line.tanggal+`</td>
                            </tr>
                            <tr>
                            <td class='size_isi'>Kasir:<span id='nik'>`+line.nik_user+`</span></td>
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
                       
                       <div style="page-break-after:always"></div>`;
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
   