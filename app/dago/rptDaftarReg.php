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
       saiPost('<?=$root_ser?>/LapDaftarReg.php?fx=getDaftarReg', null, formData, null, function(res){
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
                            }
                            thead td{
                                border:none !important;
                            }
                        </style>
                        <thead>
                            <tr>
                                <td class="style16" align="center"><h4>LAPORAN DAFTAR REGISTRASI</h4></td>
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
                                                <th class="header_laporan" width="20" align="center">No</th>
                                                <th class="header_laporan" width="70" align="center">No Registrasi</th>
                                                <th class="header_laporan" width="60" align="center">No Peserta</th>
                                                <th class="header_laporan" width="250" align="center">Nama Peserta</th>
                                                <th class="header_laporan" width="90" align="center">Tanggal</th>
                                                <th class="header_laporan" width="90" align="center">Paket</th>
                                                <th class="header_laporan" width="90" align="center">Jadwal</th>
                                            </tr>
                                        </thead>
                                        <tbody>`;
                                            
                                            var total=0; 
                                            var det = '';
                                            var no=1;
                                            for (var x=0;x<data.length;x++)
                                            {
                                                var line2 = data[x];
                                              
                                                det+=`<tr>
                                                <td align='center' class='isi_laporan'>`+no+`</td>
                                                <td  class='isi_laporan'>`+line2.no_reg+`</td>
                                                <td class='isi_laporan'>`+line2.no_peserta+`</td>
                                                <td class='isi_laporan'>`+line2.nama+`</td>
                                                <td class='isi_laporan'>`+line2.tgl_input+`</td>
                                                <td  class='isi_laporan'>`+line2.namapaket+`</td>
                                                <td  class='isi_laporan'>`+line2.tgl_berangkat+`</td>
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
   