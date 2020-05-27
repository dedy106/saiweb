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
       saiPost('<?=$root_ser?>/LapMkuOpr.php?fx=getLap', null, formData, null, function(res){
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
                            }
                        </style>
                        <thead>
                            <tr>
                                <td class="style16" align="center"><h4>LAPORAN MKU OPERASIONAL</h4></td>
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
                                                <th class="header_laporan" align="center" colspan='2'>KEBERANGKATAN</th>
                                                <th class="header_laporan" align="center" rowspan='2'>PAKET UMROH</th>
                                                <th class="header_laporan" align="center" rowspan='2'>NAMA LENGKAP</th>
                                                <th class="header_laporan" align="center" rowspan='2'>SEX</th>
                                                <th class="header_laporan" align="center" rowspan='2'>TEMPAT LAHIR</th>
                                                <th class="header_laporan" align="center" rowspan='2'>TANGGAL LAHIR</th>
                                                <th class="header_laporan" align="center" rowspan='2'>USIA</th>
                                                <th class="header_laporan" align="center" rowspan='2'>NO KTP</th>
                                                <th class="header_laporan" align="center" rowspan='2'>ALAMAT LENGKAP</th>
                                                <th class="header_laporan" align="center" rowspan='2'>NO HP</th>
                                                <th class="header_laporan" align="center" rowspan='2'>MARITAL STATUS</th>
                                                <th class="header_laporan" align="center" rowspan='2'>WOG</th>
                                                <th class="header_laporan" align="center" rowspan='2'>MAHROM</th>	
                                                <th class="header_laporan" align="center" rowspan='2'>ROOM</th>	
                                                <th class="header_laporan" align="center" rowspan='2'>NO PASSPOR</th>	
                                                <th class="header_laporan" align="center" rowspan='2'>ISSUE</th>	
                                                <th class="header_laporan" align="center" rowspan='2'>EXPIRED</th>	
                                                <th class="header_laporan" align="center" rowspan='2'>PLACE OF ISSUED</th>	
                                                <th class="header_laporan" align="center" rowspan='2'>MARKETING</th>	
                                                <th class="header_laporan" align="center" rowspan='2'>NAMA AYAH</th>	
                                                <th class="header_laporan" align="center" rowspan='2'>NAMA IBU</th>	
                                                <th class="header_laporan" align="center" rowspan='2'>NAMA KAKEK</th>	
                                                <th class="header_laporan" align="center" rowspan='2'>PENDIDIKAN</th>	
                                                <th class="header_laporan" align="center" rowspan='2'>PEKERJAAN</th>	
                                                <th class="header_laporan" align="center" rowspan='2'>STATUS</th>	
                                                <th class="header_laporan" align="center" rowspan='2'>Agen</th>	
                                                <th class="header_laporan" align="center" rowspan='2'>PJ BERKAS</th>	
                                                <th class="header_laporan" align="center" rowspan='2'>PASSPOR</th>	
                                                <th class="header_laporan" align="center" rowspan='2'>KTP</th>	
                                                <th class="header_laporan" align="center" rowspan='2'>KK</th>	
                                                <th class="header_laporan" align="center" rowspan='2'>SURAT NIKAH</th>	
                                                <th class="header_laporan" align="center" rowspan='2'>AKTE</th>	
                                                <th class="header_laporan" align="center" rowspan='2'>VAKSIN</th>	
                                                <th class="header_laporan" align="center" rowspan='2'>FOTO 4x6</th>	
                                                <th class="header_laporan" align="center" rowspan='2'>BIOMETRIK</th>
                                            </tr>
                                            <tr>
                                                <th class="header_laporan" align="center">PLAN</th>
                                                <th class="header_laporan" align="center">AKTUAL</th>
                                            </tr>
                                        </thead>
                                        <tbody>`;
                                            var no=1;var det='';
                                            for (var x=0;x<data.length;x++)
                                            {
                                                var line2 = data[x];
                                              
                                                det+=`<tr>
                                                <td align='center' class='isi_laporan'>`+no+`</td>
                                                <td  class='isi_laporan'>`+line2.tgl_berangkat+`</td>
                                                <td class='isi_laporan'>`+line2.tgl_aktual+`</td>
                                                <td class='isi_laporan'>`+line2.nama_paket+`</td>
                                                <td class='isi_laporan'>`+line2.nama+`</td>
                                                <td  class='isi_laporan'>`+line2.jk+`</td>
                                                <td  class='isi_laporan'>`+line2.tempat+`</td>
                                                <td  class='isi_laporan'>`+line2.tgl_lahir+`</td>
                                                <td class='isi_laporan'>`+line2.usia+`</td>
                                                <td class='isi_laporan'>`+line2.id_peserta+`</td>
                                                <td class='isi_laporan'>`+line2.alamat+`</td>
                                                <td  class='isi_laporan'>`+line2.hp+`</td>
                                                <td  class='isi_laporan'>`+line2.brkt_dgn+`</td>
                                                <td  class='isi_laporan'>-</td>
                                                <td class='isi_laporan'>-</td>
                                                <td class='isi_laporan'>`+line2.nama_room+`</td>
                                                <td class='isi_laporan'>`+line2.nopass+`</td>
                                                <td  class='isi_laporan'>`+line2.issued+`</td>
                                                <td  class='isi_laporan'>`+line2.ex_pass+`</td>
                                                <td class='isi_laporan'>`+line2.kantor_mig+`</td>
                                                <td class='isi_laporan'>`+line2.nama_marketing+`</td>
                                                <td class='isi_laporan'>`+line2.ayah+`</td>
                                                <td  class='isi_laporan'>`+line2.ibu+`</td>
                                                <td  class='isi_laporan'>`+line2.kakek+`</td>
                                                <td class='isi_laporan'>`+line2.pendidikan+`</td>
                                                <td  class='isi_laporan'>`+line2.pekerjaan+`</td>
                                                <td  class='isi_laporan'>`+line2.status+`</td>
                                                <td  class='isi_laporan'>`+line2.agen+`</td>
                                                <td  class='isi_laporan'>&nbsp;</td>
                                                <td  class='isi_laporan'>&nbsp;</td>
                                                <td  class='isi_laporan'>&nbsp;</td>
                                                <td  class='isi_laporan'>&nbsp;</td>
                                                <td  class='isi_laporan'>&nbsp;</td>
                                                <td  class='isi_laporan'>&nbsp;</td>
                                                <td  class='isi_laporan'>&nbsp;</td>
                                                <td  class='isi_laporan'>&nbsp;</td>
                                                <td  class='isi_laporan'>&nbsp;</td>
                                                </tr>`;	
                                                no++;
                                            
                                                
                                            }
                                            mon_html+=det+`
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
   