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
        saiPost('<?=$root_ser?>/LapClosing.php?fx=getLapClosing2', null, formData, null, function(res){
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
                       <div class="card card-body">
                            <h3><b>No Closing</b> <span class="pull-right">#`+line.no_bukti+`</span></h3>
                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="pull-left">
                                        <address>
                                            
                                            <p class="text-muted m-l-5">`+line.tanggal+`
                                                <br> Saldo Awal: &nbsp;`+sepNum(line.saldo_awal)+`
                                                <br>Kasir: `+line.nik_user+`
                                                <br> Total Penjualan: `+sepNum(line.total_pnj)+`</p>
                                        </address>
                                    </div>
                                    
                                </div>
                                <div class="col-md-12">
                                    <div class="table-responsive m-t-40" style="clear: both;">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">No</th>
                                                    <th>No Penjualan</th>
                                                    <th>Tgl Jual</th>
                                                    <th>Periode</th>
                                                    <th>Keterangan</th>
                                                    <th class="text-right">Total</th>
                                                    <th class="text-right">Diskon</th>
                                                </tr>
                                            </thead>
                                            <tbody>`;
                                            var diskon=0; 
                                            var total=0;
                                            var subTot = 0;
                                            var det = '';
                                            var no=1;
                                            for (var x=0;x<res.result2.length;x++)
                                            {
                                                var line2 = res.result2[x];
                                                if(line.no_bukti == line2.no_bukti){
                                                    diskon+=+line2.tot_diskon;
                                                    total+=+line2.nilai;
                                                    subTot+= +parseFloat(line2.nilai)+parseFloat(line2.diskon);
                                                    det+=`<tr>
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
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="pull-right m-t-30 text-right">
                                        <p>Sub - Total amount: `+sepNum(subTot)+`</p>
                                        <p>Discount :`+sepNum(diskon)+` </p>
                                        <hr>
                                        <h3><b>Total :</b> `+sepNum(total)+`</h3>
                                    </div>
                                    <div class="clearfix"></div>
                                    
                                </div>
                            </div>
                        </div>
                       
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
   