
    
<div class='row'>
    <div class='col-xs-12'>
        <div class='box'>
        <?php
        $request = $_SERVER['REQUEST_URI'];
        $request2 = explode('/',$request);
        if($request2[6] == 1){
            echo"";
        } else{
            echo "
            <div class='box-header'>
                <a onclick='printForm()' id='printButton' target='_blank' class='btn btn-primary pull-right'>
                <i class='fa fa-print'></i> Print
                </a>";
            echo"
            </div>";
        }
        ?>
            <div class="box-body" id='formPros'>
                
            </div>
        </div>
    </div>
</div>
<script>
var no_bukti = '<?=$request2[4]?>';
var stsPrint = "<?php echo $request2[3]?>";
var root = "<?php echo $root_app; ?>";
if(stsPrint == '0') { 
    var printValue = false;
}else{
    var printValue = true ;
}

function printForm(){
    window.open(root+'/printPreview/formProsfecting/1/'+no_bukti,'_blank'); 
}

function executePrint(){
    window.print();
}

function loadForm(){
    $.ajax({
        type: 'GET',
        url: '<?=$root_ser?>/Posisi.php?fx=getFormProspecting',
        dataType: 'json',
        data: {'no_bukti':no_bukti},
        success:function(result){    
            if(result.status){
                var html = '';
                html = `
                <div class='row'>
                    <div class='col-xs-12'>
                        <center>
                            <img src='<?=$root_img?>/web/upload/sai_logo2.jpg' style='height:160px; width:auto;'>
                            <h2>FORM PROSPECTING</h2>
                        </center>
                    </div>
                </div>
                <div style='margin-left:40px;'>
                    <div class='row'>
                        <div class='col-xs-4'>
                        No Bukti
                        </div>
                        <div class='col-xs-1'>
                            :
                        </div>
                        <div class='col-xs-7'>
                            `+result.detail[0].no_bukti+`
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-xs-4'>
                            Tanggal
                        </div>
                        <div class='col-xs-1'>
                            :
                        </div>
                        <div class='col-xs-7'>
                        `+result.detail[0].tanggal+`
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-xs-4'>Keterangan
                        </div>
                        <div class='col-xs-1'>
                            :
                        </div>
                        <div class='col-xs-7'>
                        `+result.detail[0].keterangan+`
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-xs-4'>
                            Nama Customer
                        </div>
                        <div class='col-xs-1'>
                            :
                        </div>
                        <div class='col-xs-7'>
                        `+result.detail[0].nama_cust+`
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-xs-4'>
                            Produk
                        </div>
                        <div class='col-xs-1'>
                            :
                        </div>
                        <div class='col-xs-7'>
                        `+result.detail[0].nama_prod+`
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-xs-4'>
                            Karyawan
                        </div>
                        <div class='col-xs-1'>
                            :
                        </div>
                        <div class='col-xs-7'>
                        `+result.detail[0].nama_kar+`
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-xs-4'>
                            Nilai 
                        </div>
                        <div class='col-xs-1'>
                            :
                        </div>
                        <div class='col-xs-7'>
                            <b>`+result.detail[0].nilai+`</b>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-xs-4'>
                            Asli Permohonan ini disampaikan
                        </div>
                        <div class='col-xs-1'>
                            :
                        </div>
                        <div class='col-xs-7'>
                        </div>
                    </div>


                    <div class='row'>
                        <div class='col-xs-8'>
                        </div>
                        <div class='col-xs-4'>
                            <center>
                                Kontraktor / Principal
                                <br><br><br>
                                KASNATA/Direktur Utama
                            </center>
                        </div>
                    </div>
                </div>`;
                $('#formPros').html(html);
                if(printValue){
                    executePrint();
                }
            }
        }
    });
}

loadForm();
</script>
