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

<div id='canvasPreview'>
</div>

<script src="<?=$folderroot_js?>/sai.js"></script>
<script type="text/javascript">
    
    function drawLap(formData){
        saiPost('<?=$root_ser?>/LapKartuPbyr.php?fx=getLap', null, formData, null, function(res){
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
               
               var mon_html = "<div id='sai-rpt-table-export-tbl-daftar-pnj'>"+back;
                   var arr_tl = [0,0,0,0,0,0,0,0,0];
                   var x=1;
                   for (var i=0;i<data.length;i++)
                   { 
                       var line = data[i];
                       mon_html+=`
                            <table class='table' cellspacing='0' cellpadding='1' class='kotak'>
                                <style>
                                    td,th{
                                        padding:5px !important;
                                    }
                                    .style16{
                                        border:none !important;
                                    }
                                </style>
                                <tr>
                                    <td class="style16" align="center"><h4>KARTU PEMBAYARAN</h4></td>
                                </tr>
                                <tr>
                                    <td style='border:none !important;'>&nbsp;</td>
                                </tr>
                                <tr >
                                    <td height='23' colspan='7' style='border:none' class='header_laporan'>
                                        <table  class='table no-border' width='100%'>
                                            <tr>
                                                <td width='30%' class='header_laporan'>No Registrasi</td>
                                                <td width='70%' class='header_laporan'>: <a class='reg' href='#' style='cursor:pointer;color:blue' data-no_reg='`+line.no_reg+`'>`+line.no_reg+`</a></td>
                                            </tr>
                                            <tr>
                                                <td width='99' class='header_laporan'>Peserta</td>
                                                <td width='360' class='header_laporan'>: `+line.no_peserta+` - `+line.nama+`</td>
                                            </tr>
                                            <tr>
                                                <td width='99' class='header_laporan'>Tanggal</td>
                                                <td width='360' class='header_laporan'>: `+line.tgl+`</td>
                                            </tr>
                                            <tr>
                                                <td class='header_laporan'>Paket </td>
                                                <td class='header_laporan'>: `+line.no_paket+` - `+line.nama_paket+`</td>
                                            </tr>
                                            <tr>
                                                <td class='header_laporan'>Agen </td>
                                                <td class='header_laporan'>: `+line.no_agen+` - `+line.nama_agen+`</td>
                                            </tr>
                                            <tr>
                                                <td class='header_laporan'>Nilai Paket </td>
                                                <td class='header_laporan'>: `+sepNumPas(line.paket)+`</td>
                                            </tr>
                                            <tr>
                                                <td class='header_laporan'>Nilai Tambahan </td>
                                                <td class='header_laporan'>: `+sepNumPas(line.tambahan)+`</td>
                                            </tr>
                                            <tr>
                                                <td class='header_laporan'>Nilai Dokumen </td>
                                                <td class='header_laporan'>: `+sepNumPas(line.dokumen)+`</td>
                                            </tr>`;
                                            var total_paket = parseFloat(line.paket)+parseFloat(line.tambahan)+parseFloat(line.dokumen);
                                            mon_html+=`
                                            <tr>
                                                <td class='header_laporan'>Total</td>
                                                <td class='header_laporan'>: `+sepNumPas(total_paket)+`</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td style='border:none'>
                                        <table class='table table-bordered table-striped'>
                                        <thead style='vertical-align:middle !important;text-align:center'>
                                            <tr >
                                                <th rowspan='2' style='vertical-align: middle;' width='50'>Tanggal</th>
                                                <th rowspan='2' style='vertical-align: middle;' width='100'>No Bukti</th>
                                                <th rowspan='2' style='vertical-align: middle;' width='250'>Keterangan</th>
                                                <th colspan='3' style='vertical-align: middle;' >Pembayaran</th>
                                            </tr>
                                            <tr >
                                                <th width='80'>Paket</th>
                                                <th width='80'>Tambahan</th>
                                                <th width='80'>Dokumen</th>
                                            </tr>
                                        </thead>
                                        <tbody>`;
                                            var saldo_paket=line.paket;
                                            var saldo_tambahan=line.tambahan;
                                            var nilai_p=0;
                                            var nilai_t=0;                                           
                                            var nilai_m=0;
                                            var det =``;
                                            for(var j=0;j<res.result2.length;j++){

                                                var line2 = res.result2[j];
                                                if(line.no_reg == line2.no_reg){

                                                    nilai_p+=parseFloat(line2.nilai_p);
                                                    nilai_t+=parseFloat(line2.nilai_t);
                                                    nilai_m+=parseFloat(line2.nilai_m);
                                                    det+=`<tr>
                                                        <td>`+line2.tgl+`</td>
                                                        <td><a class='byr' href='#' style='cursor:pointer;color:blue' data-no_bayar='`+line2.no_kwitansi+`'>`+line2.no_kwitansi+`</a></td>
                                                        <td>`+line2.keterangan+`</td>
                                                        <td align='right'>`+sepNumPas(line2.nilai_p,0,',','.')+`</td>
                                                        <td align='right'>`+sepNumPas(line2.nilai_t,0,',','.')+`</td>
                                                        <td align='right'>`+sepNumPas(line2.nilai_m,0,',','.')+`</td>
                                                    </tr>`;
                                                }
                                                        
                                            }
                                            mon_html+=det+`
                                            <tr>
                                                <td  colspan='3' valign='top' class='header_laporan' align='right'>Total Pembayaran&nbsp;</td>
                                                <td valign='top' class='header_laporan' align='right'>`+sepNumPas(nilai_p,0,',','.')+`</td>
                                                <td valign='top' class='header_laporan' align='right'>`+sepNumPas(nilai_t,0,',','.')+`</td>
                                                <td valign='top' class='header_laporan' align='right'>`+sepNumPas(nilai_m,0,',','.')+`</td>
                                            </tr>
                                            <tr>
                                                <td  colspan='3' valign='top' class='header_laporan' align='right'>Saldo&nbsp;</td>
                                                <td valign='top' class='header_laporan' align='right'>`+sepNumPas(line.paket-nilai_p,0,',','.')+`</td>
                                                <td valign='top' class='header_laporan' align='right'>`+sepNumPas(line.tambahan-nilai_t,0,',','.')+`</td>
                                                <td valign='top' class='header_laporan' align='right'>`+sepNumPas(line.dokumen-nilai_m,0,',','.')+`</td>
                                            </tr>`;
                                            mon_html+=`
                                        </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <br>
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
   