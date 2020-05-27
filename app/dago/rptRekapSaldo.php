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
<script type="text/javascript">

    function drawLap(formData){
       saiPost('<?=$root_ser?>/LapRekapSaldo.php?fx=getLap', null, formData, null, function(res){
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

    var mon_html = `<div align='center' style='padding:10px' id='sai-rpt-table-export-tbl-daftar-reg'>
               `;
               var x=1;
               mon_html+=`
                    <table width="800px" class="table no-border">
                        <style>
                            td,th{
                                padding:4px !important;
                                vertical-align:middle !important;
                            }
                            thead td{
                                border:none !important;
                                text-align:center !important;
                            }
                            th{
                                text-align:center !important;
                            }
                        </style>
                        <thead>
                            <tr>
                                <td class="style16" align="center"><h4>LAPORAN REKAP SALDO</h4></td>
                            </tr>
                            <tr>
                                <td class="style16" align="center">`+res.periode+`</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td align="center">
                                    <table width="100%" class="table table-striped color-table info-table">
                                        <thead>
                                            <tr>
                                                <th class="header_laporan" align="center" rowspan='2'>NO</th>
                                                <th class="header_laporan" align="center" rowspan='2'>NO REGISTRASI</th>
                                                <th class="header_laporan" align="center" rowspan='2'>NO KTP</th>
                                                <th class="header_laporan" align="center" rowspan='2'>NO PESERTA</th>
                                                <th class="header_laporan" align="center" rowspan='2'>NAMA PESERTA</th>
                                                <th class="header_laporan" align="center" rowspan='2'>PAKET</th>
                                                <th class="header_laporan" align="center" rowspan='2'>ROOM</th>
                                                <th class="header_laporan" align="center" rowspan='2'>JADWAL KEBERANGKATAN</th>
                                                <th class="header_laporan" align="center" colspan='4'>TAGIHAN</th>
                                                <th class="header_laporan" align="center" colspan='4'>PEMBAYARAN</th>
                                                <th class="header_laporan" align="center" colspan='4'>SALDO</th>
                                            </tr>
                                            <tr>
                                                <th class="header_laporan" align="center">HARGA PAKET+ROOM</th>
                                                <th class="header_laporan" align="center">BIAYA TAMBAHAN</th>
                                                <th class="header_laporan" align="center">BIAYA DOK</th>
                                                <th class="header_laporan" align="center">TOTAL</th>
                                                <th class="header_laporan" align="center">HARGA PAKET+ROOM</th>
                                                <th class="header_laporan" align="center">BIAYA TAMBAHAN</th>
                                                <th class="header_laporan" align="center">BIAYA DOK</th>
                                                <th class="header_laporan" align="center">TOTAL</th>
                                                <th class="header_laporan" align="center">HARGA PAKET+ROOM</th>
                                                <th class="header_laporan" align="center">BIAYA TAMBAHAN</th>
                                                <th class="header_laporan" align="center">BIAYA DOK</th>
                                                <th class="header_laporan" align="center">TOTAL</th>
                                            </tr>
                                        </thead>
                                        <tbody>`;
                                            var no=1;var det='';
                                            var biaya_paket=0;
                                            var biaya_tambahan=0
                                            var biaya_dok=0;
                                            var bayar_paket=0;
                                            var bayar_tambahan=0;
                                            var bayar_dok=0;
                                            var saldo_paket=0;
                                            var saldo_tambahan=0;
                                            var saldo_dok=0;
                                            var to_tagihan=0;
                                            var to_bayar=0;
                                            var to_saldo=0;
                                            for (var x=0;x<data.length;x++)
                                            {
                                                var line2 = data[x];
                                                biaya_paket+= +parseFloat(line2.biaya_paket);
                                                biaya_tambahan+= +parseFloat(line2.biaya_tambahan);
                                                biaya_dok+= +parseFloat(line2.biaya_dok);
                                                bayar_paket+= +parseFloat(line2.bayar_paket);
                                                bayar_tambahan+= +parseFloat(line2.bayar_tambahan);
                                                bayar_dok+= +parseFloat(line2.bayar_dok);
                                                saldo_paket+= +parseFloat(line2.saldo_paket);
                                                saldo_tambahan+= +parseFloat(line2.saldo_tambahan);
                                                saldo_dok+= +parseFloat(line2.saldo_dok);
                                                to_tagihan+= +parseFloat(line2.tagihan);
                                                to_bayar+= +parseFloat(line2.bayar);
                                                to_saldo+= +parseFloat(line2.saldo); 
                                              
                                                det+=`<tr>
                                                <td align='center' class='isi_laporan'>`+no+`</td>
                                                <td  class='isi_laporan'>`+line2.no_reg+`</td>
                                                <td class='isi_laporan'>`+line2.id_peserta+`</td>
                                                <td class='isi_laporan'>`+line2.no_peserta+`</td>
                                                <td class='isi_laporan'>`+line2.nama_peserta+`</td>
                                                <td  class='isi_laporan'>`+line2.nama_paket+`</td>
                                                <td  class='isi_laporan'>`+line2.nama_room+`</td>
                                                <td  class='isi_laporan'>`+line2.tgl_berangkat+`</td>
                                                <td class='isi_laporan text-right'>`+sepNumPas(line2.biaya_paket)+`</td>
                                                <td class='isi_laporan text-right'>`+sepNumPas(line2.biaya_tambahan)+`</td>
                                                <td class='isi_laporan text-right'>`+sepNumPas(line2.biaya_dok)+`</td>
                                                <td class='isi_laporan text-right'><a class='tagihan' href='#' style='cursor:pointer;color:blue' data-no_reg='`+line2.no_reg+`'>`+sepNumPas(line2.tagihan)+`</a></td>
                                                <td  class='isi_laporan text-right'>`+sepNumPas(line2.bayar_paket)+`</td>
                                                <td  class='isi_laporan text-right'>`+sepNumPas(line2.bayar_tambahan)+`</td>
                                                <td  class='isi_laporan text-right'>`+sepNumPas(line2.bayar_dok)+`</td>
                                                <td  class='isi_laporan text-right'><a class='bayar' href='#' style='cursor:pointer;color:blue' data-no_reg='`+line2.no_reg+`'>`+sepNumPas(line2.bayar)+`</a></td>
                                                <td class='isi_laporan text-right'>`+sepNumPas(line2.saldo_paket)+`</td>
                                                <td class='isi_laporan text-right'>`+sepNumPas(line2.saldo_tambahan)+`</td>
                                                <td class='isi_laporan text-right'>`+sepNumPas(line2.saldo_dok)+`</td>
                                                <td  class='isi_laporan text-right'><a class='saldo' href='#' style='cursor:pointer;color:blue' data-no_reg='`+line2.no_reg+`'>`+sepNumPas(line2.saldo)+`</a></td>
                                                </tr>`;	
                                                no++;
                                            
                                                
                                            }
                                            // console.log(bayar_paket);
                                            mon_html+=det+`
                                            <tr>
                                                <td class='isi_laporan' colspan='8' >TOTAL</td>
                                                <td class='isi_laporan text-right'>`+sepNumPas(biaya_paket)+`</td>
                                                <td class='isi_laporan text-right'>`+sepNumPas(biaya_tambahan)+`</td>
                                                <td class='isi_laporan text-right'>`+sepNumPas(biaya_dok)+`</td>
                                                <td class='isi_laporan text-right'>`+sepNumPas(to_tagihan)+`</td>
                                                <td  class='isi_laporan text-right'>`+sepNumPas(bayar_paket)+`</td>
                                                <td  class='isi_laporan text-right'>`+sepNumPas(bayar_tambahan)+`</td>
                                                <td  class='isi_laporan text-right'>`+sepNumPas(bayar_dok)+`</td>
                                                <td class='isi_laporan text-right'>`+sepNumPas(to_bayar)+`</td>
                                                <td class='isi_laporan text-right'>`+sepNumPas(saldo_paket)+`</td>
                                                <td class='isi_laporan text-right'>`+sepNumPas(saldo_tambahan)+`</td>
                                                <td class='isi_laporan text-right'>`+sepNumPas(saldo_dok)+`</td>
                                                <td class='isi_laporan text-right'>`+sepNumPas(to_saldo)+`</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table><br>
                    <div style="page-break-after:always"></div>`;
                        
               mon_html+="</div>"; 
            }
        $('#canvasPreview').html(mon_html);
        $('li.first a ').html("<i class='icon-control-start'></i>");
        $('li.last a ').html("<i class='icon-control-end'></i>");
        $('li.prev a ').html("<i class='icon-arrow-left'></i>");
        $('li.next a ').html("<i class='icon-arrow-right'></i>");
        $('#pagination').append(`<li class="page-item all"><a href="#" class="page-link"><i class="far fa-list-alt"></i></a></li>`);
    }
</script>
   