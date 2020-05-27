
    
<div class='row'>
    <div class='col-xs-12'>
        <div class='box'>
        <?php
        if(ISSET($_GET['print']) OR ISSET($_GET['download'])){
            echo"";
        } else{
            echo "
            <div class='box-header'>
                <a href='printPreview.php?hal=app/crm/rptPosisi.php&param=$no_bukti&print=1' target='_blank' class='btn btn-primary pull-right'>
                <i class='fa fa-print'></i> Print
                </a>";
            echo"
            </div>";
        }
        ?>
            <div class="box-body">
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
                            <?php echo $detail["no_bukti"]; ?>
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
                            <?php echo $detail["tanggal"]; ?>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-xs-4'>Keterangan
                        </div>
                        <div class='col-xs-1'>
                            :
                        </div>
                        <div class='col-xs-7'>
                            <?php echo $detail["keterangan"]; ?>
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
                            <?php echo $detail["nama_cust"]; ?>
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
                            <?php echo $detail["nama_prod"]; ?>
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
                            <?php echo $detail["nama_kar"]; ?>
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
                            <b><?php echo toRp($detail["nilai"]); ?></b>
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
                </div>
            </div>
        </div>
    </div>
</div>
