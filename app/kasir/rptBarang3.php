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
<script type="text/javascript">

    function drawLap(formData){
       saiPost('<?=$root_ser?>/LapBarang.php?fx=getLapBarang2', null, formData, null, function(res){
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
            
            
            var mon_html = "<div align='center' id='sai-rpt-table-export-tbl-daftar-pnj'>";
            var arr_tl = [0,0,0,0,0,0,0,0,0];
            var x=1;
            
            
            mon_html+=`
            <table class='table no-border'>
                <tbody>
                    <tr>
                        <td class="style16" align="center">
                            <b>Toko Asrama Putra TJ</b>
                        </td>
                    </tr>
                    <tr>
                        <td class="style16" align="center"><b>LAPORAN BARANG</b></td>
                    </tr>
                    <tr>
                        <td class="style16" align="center">Periode `+res.periode+`</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td align="center">
                            <style>
                            td,th{
                                padding:4px !important;
                                vertical-align:middle !important;
                            }
                            /*.header_laporan,.isi_laporan {
                                border:1px solid #e9ecef !important;
                            }*/
                            th{
                                text-align:center;
                            }
                            </style>
                            <div class="table-responsive">
                            <table class='table table-striped color-table info-table'>
                                <thead>
                                    <tr>
                                        <th class="header_laporan" width="20" align="center">No</th>
                                        <th class="header_laporan" width="70" align="center">Kode Barang</th>
                                        <th class="header_laporan" width="250" align="center">Nama Barang</th>
                                        <th class="header_laporan" width="60" align="center">Satuan</th>
                                        <th class="header_laporan" width="90" align="center">Harga</th>
                                        <th class="header_laporan" width="90" align="center">Kelompok Barang</th>
                                        <th class="header_laporan" width="90" align="center">Barcode</th>
                                    </tr>
                                </thead>
                                <tbody>`;
                                
                                var total=0; 
                                var det = '';
                                if(isNaN(from)){
                                    var no=1;
                                }else{
                                    var no=from+1;
                                }

                                for (var x=0;x<data.length;x++)
                                {
                                    var line2 = data[x];
                                    
                                    det+=`<tr>
                                        <td align='center' class='isi_laporan'>`+no+`</td>
                                        <td  class='isi_laporan'>`+line2.kode_barang+`</td>
                                        <td class='isi_laporan'>`+line2.nama+`</td>
                                        <td class='isi_laporan'>`+line2.satuan+`</td>
                                        <td align='right' class='isi_laporan'>`+sepNum(line2.harga)+`</td>
                                        <td  class='isi_laporan'>`+line2.kode_klp+`</td>
                                        <td  class='isi_laporan'>`+line2.barcode+`</td>
                                    </tr>`;	
                                    no++;
                                    
                                    
                                }
                                mon_html+=det+`
                                </tbody>
                            </table>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table><br>
            <div style="page-break-after:always"></div>
            `;
            
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
   