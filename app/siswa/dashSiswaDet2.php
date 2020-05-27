<?php
    
    $kode_lokasi=$_SESSION['lokasi'];
    $periode=$_SESSION['periode'];
    $kode_pp=$_SESSION['kodePP'];
    $nik=$_SESSION['userLog'];
    $fmain=$_SESSION['fMain'];

    $nik2=str_replace("a","",$nik);
	/*
    $sql="select kode_pp from karyawan where kode_lokasi = '$kode_lokasi' and nik ='$nik2' ";
    $rs = execute($sql);
    $kode_pp=$rs->fields[0];

	*/
    $tmp=explode("/",$_GET['param']);
    $jenis=$tmp[0];
    $kunci=$tmp[1];
    $param=$tmp[2];

    $path = "http://".$_SERVER["SERVER_NAME"]."/";					
    $path2 = $path . "image/yspt2.png";

    if (preg_match('/(chrome|firefox|avantgo|blackberry|android|blazer|elaine|hiptop|iphone|ipod|kindle|midp|mmp|mobile|o2|opera mini|palm|palm os|pda|plucker|pocket|psp|smartphone|symbian|treo|up.browser|up.link|vodafone|wap|windows ce; iemobile|windows ce; ppc;|windows ce; smartphone;|xiino)/i', $_SERVER['HTTP_USER_AGENT'], $version)) 

    // echo "Browser:".$version[1];

    if ($version[1] == "iPhone" || $version[1] == "Android" || $version[1] == "Blackberry" || $version[1] == "Blazer" ||$version[1] == "Elaine" || $version[1] == "Hiptop" || $version[1] == "iPod" || $version[1] == "Kindle" ||$version[1] == "Midp" || $version[1] == "Mobile" || $version[1] == "O2" || $version[1] == "Opera Mini" ||$version[1] == "Mobile" || $version[1] == "Smartphone"){

        $back1="";
        switch($param){
            case "tagihan" :
            case "detTagih":
                $judul = "Detail Tagihan";
            break;
            case "deposit" :
                $judul = "Deposit";
            break;
            case "detKeu" :
                $judul = "Riwayat Transaksi";
            break;
            case "detPresensi" :
                $judul = "Detail Absensi";
            break;
        }
        

        if($tmp[3] == "" OR $tmp[3]==0){
            $backto="$fmain?hal=app/ypt/dashSiswaDet.php&param=$jenis/$kunci";
        }else{
            $prev=intval($tmp[3])-1;
            $backto="$fmain?hal=app/ypt/dashSiswaDet2.php&param=$jenis/$kunci/$param/$prev";
        }

        include('back.php');
        $padding="padding-top:50px;border:0px solid grey;box-shadow: 0 0 0 white;";
        $style = "box-shadow: 0 0 0 white;";
    }else{

        if($tmp[3] == "" OR $tmp[3]==0){
            $back1="<div class='panel-heading'>
            <a href='$fmain?hal=app/ypt/dashSiswaDet.php&param=$jenis/$kunci' class='small-box-footer' > Back <i class='fa fa-arrow-circle-left'></i></a>
            </div>";
        }else{
            $prev=intval($tmp[3])-1;
            // if($next == 1) $next = 0;
            $back1="<div class='panel-heading'>
            <a href='$fmain?hal=app/ypt/dashSiswaDet2.php&param=$jenis/$kunci/$param/$prev' class='small-box-footer' > Back <i class='fa fa-arrow-circle-left'></i></a>
            </div>";
        }

        $padding="";
        $style = "box-shadow: 0 0 0 white;";
    }

    echo "<div class='panel' style='$padding'>
			<div class='panel-body'>
                $back1 ";
                switch($param){
                    case "receipt" :
                echo"
                    <div class='row'>
                        <div class='col-md-12'>
                            <div class='box box-success box-solid'>
                                <div class='box-header with-border'>
                                    <h3 class='box-title'>Pembayaran</h3>
                            
                                    <div class='box-tools pull-right'>
                                        <img width='20px' src='$path'><img>
                                    </div>
                                    <!-- /.box-tools -->
                                </div>
                                <!-- /.box-header -->
                                <div class='box-body' style='padding:2px'>
                                    <table class='table no-border' style='padding: 5px;'>
                                        <tbody>
                                            <tr>
                                                <td style='padding-bottom: 0px;' colspan='2'>Nama</td>
                                                <td style='text-align:right;padding-bottom: 0px;'>Nilai Bayar</td>
                                            </tr>
                                            <tr>
                                                <th style='padding-top: 0px;font-size: 20px;' colspan='2'>RANGGA AZHAR FAUZAN</th>
                                                <th style='text-align:right;padding-top: 0px;font-size: 20px;'>650.000</th>
                                            </tr>
                                            <tr>
                                                <td colspan='3' style='border-bottom: 2px dashed #a4f5a4;padding-top: 0px;'>No Virtual Account </td>
                                            </tr>
                                            <tr>
                                                <td style='text-align:center;padding: 2px;' colspan='3'>Rincian Alokasi Pembayaran</td>
                                            </tr>
                                            <tr>
                                                <td style='text-align: right;border-bottom: 2px solid #2791f9;padding-top: 0px;padding-bottom: 0px;' width='40%'><i>Deposit</i></td>
                                                <td style='border-bottom: 2px solid #2791f9;padding-bottom: 0px;' width='25%'>&nbsp;</td>
                                                <td style='border-bottom: 2px solid #2791f9;padding-top: 0px;padding-bottom: 0px;' width='35%'>0</td>
                                            </tr>
                                            <tr>
                                                <th style='padding-top: 2px;padding-bottom: 2px;' width='40%'>Periode</th>
                                                <th style='padding-top: 2px;padding-bottom: 2px;' width='25%'>Parameter</th>
                                                <th style='padding-top: 2px;padding-bottom: 2px;' width='35%'>Nilai Bayar</th>
                                            </tr>
                                            <tr>
                                                <td style='padding-top: 2px;padding-bottom: 2px;' width='40%'>Februari , 2019</td>
                                                <td style='padding-top: 2px;padding-bottom: 2px;' width='25%'>SPP</td>
                                                <td style='padding-top: 2px;padding-bottom: 2px;' width='35%'>650.000</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.box-body -->
                            </div>
                            <!-- /.box -->
                        </div>
                    </div>
                ";
                        break;
                        case "invoice" :
                echo"
                    <div class='row'>
                        <div class='col-md-12'>
                            <div class='box box-primary box-solid'>
                                <div class='box-header with-border'>
                                    <h3 class='box-title'>Tagihan Sekolah</h3>
                            
                                    <div class='box-tools pull-right'>
                                        <img width='20px' src='$path'><img>
                                    </div>
                                    <!-- /.box-tools -->
                                </div>
                                <!-- /.box-header -->
                                <div class='box-body' style='padding:2px'>
                                    <table class='table no-border' style='padding: 5px;'>
                                        <tbody>
                                            <tr>
                                                <td style='padding-bottom: 0px;' colspan='2'>Nama</td>
                                                <td style='text-align:right;padding-bottom: 0px;'>Nilai Tagihan</td>
                                            </tr>
                                            <tr>
                                                <th style='padding-top: 0px;font-size: 20px;' colspan='2'>RANGGA AZHAR FAUZAN</th>
                                                <th style='text-align:right;padding-top: 0px;font-size: 20px;'>650.000</th>
                                            </tr>
                                            <tr>
                                                <td colspan='3' style='border-bottom: 2px dashed #3c8dbc;padding-top: 0px;'>No Virtual Account </td>
                                            </tr>
                                            <tr>
                                                <td style='text-align:center;padding: 2px;' colspan='3'>Rincian Tagihan</td>
                                            </tr>
                                            <tr>
                                                <td style='text-align: right;border-bottom: 2px solid #2791f9;padding-top: 0px;padding-bottom: 0px;' width='40%'><i>Saldo Tunggakan Sebelumnya</i></td>
                                                <td style='border-bottom: 2px solid #2791f9;padding-bottom: 0px;' width='25%'>&nbsp;</td>
                                                <td style='border-bottom: 2px solid #2791f9;padding-top: 0px;padding-bottom: 0px;' width='35%'>0</td>
                                            </tr>
                                            <tr>
                                                <th style='padding-top: 2px;padding-bottom: 2px;' width='40%'>No Tagihan</th>
                                                <th style='padding-top: 2px;padding-bottom: 2px;' width='25%'>Parameter</th>
                                                <th style='padding-top: 2px;padding-bottom: 2px;' width='35%'>Nilai</th>
                                            </tr>
                                            <tr>
                                                <td style='padding-top: 2px;padding-bottom: 2px;' width='40%'>12-BILL1707.0007</td>
                                                <td style='padding-top: 2px;padding-bottom: 2px;' width='25%'>KOP</td>
                                                <td style='padding-top: 2px;padding-bottom: 2px;' width='35%'>50.000</td>
                                            </tr>
                                            <tr>
                                                <td style='padding-top: 2px;padding-bottom: 2px;' width='40%'>12-BILL1707.0007</td>
                                                <td style='padding-top: 2px;padding-bottom: 2px;' width='25%'>LAIN</td>
                                                <td style='padding-top: 2px;padding-bottom: 2px;' width='35%'>600.000</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.box-body -->
                            </div>
                            <!-- /.box -->
                        </div>
                    </div>
                ";
                
                        break;
                        case "tagihan" :

                        $color="color:#dd4b39";

                        if($tmp[4] != ""){
                            $sem = $tmp[4];
                        }else{
                            $sem = "GENAP";
                        }
                        
                        $sql=" select distinct periode from sis_bill_d where kode_lokasi='$kode_lokasi' and kode_pp='$kode_pp' and nis='$nik2' and periode like '".substr($periode,0,4)."%'  and kode_sem='$sem' order by periode desc";
                        $rs = execute($sql);

                        echo "<div class='box box-widget' style='border: none;box-shadow: none;text-align: right;padding: 0px;margin-bottom: 0px;'>
                        <div class='box-body' style='padding-bottom: 0px;'>
                            <div class='row'>
                                <div class='col-md-12'>
                                    <span style='font-size:14px'> Total Tagihan : ".number_format($tmp[5],0,",",".")."</span>
                                </div>
                            </div>
                        </div>
                        </div>";

                        while ($row = $rs->FetchNextObject($toupper=false)){

                    echo"   
                            <div class='box box-widget' style='border: 1px solid #f2efefa1;'>
                                <div class='box-body'>
                                    <div class='row' style='margin-bottom:10px'>
                                        <div class='col-md-1'>
                                            <span class='label label-danger' style='font-size:14px'> ".ubah_periode($row->periode)."</span>
                                        </div>
                                    </div>
                                    <div class='row'>
                                        <div class='col-md-12'>";

                                        $sql1="select kode_ta, kode_sem, periode from sis_bill_d where kode_pp='$kode_pp' and kode_lokasi='$kode_lokasi' and nis='$nik2' and periode = '$row->periode' ";

                                        $rs1 = execute($sql1);
                                        $row1 = $rs1->FetchNextObject($toupper=false);
                                        echo"
                                            <table style='width: 100%;'>
                                                <tbody>
                                                <tr>
                                                    <th width='15%' >Tahun Ajaran</th>
                                                    <td width='15%' colspan='3'>$row1->kode_ta</td>
                                                </tr>
                                                <tr>
                                                    <th  width='15%'>Semester</th>
                                                    <td  width='15%' colspan='3' >$row1->kode_sem</td>
                                                </tr>
                                                <tr>
                                                    <th >Saldo</th>
                                                    <td colspan='3'>Rp.0</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </diV>
                                </div>";        
                                   
                                 $colors = array('#39CCCC','#001F3F','#39CCCC','#001F3F','#39CCCC','#001F3F','#39CCCC','#001F3F','#39CCCC','#001F3F','#39CCCC','#001F3F');

                                    echo"
                                    <table class='table no-margin'>
                                    <thead>
                                        <tr>
                                            <th width='25%' colspan='2' style='border-bottom: 1px solid white;text-align:center'>Parameter</th>
                                            <th width='25%' style='border-bottom: 1px solid white;text-align:center'>Nilai</th>
                                            <th width='25%' style='border-bottom: 1px solid white;text-align:center'>Terbayar</th>
                                            <th width='25%' style='border-bottom: 1px solid white;text-align:center'>Saldo</th>
                                        </tr>
                                    </thead>
                                    <tbody>";   
                                   
                                    $sql="
                                    select a.nis, a.nama, b.kode_param,isnull(b.total,0) as bill, isnull(c.total,0) as bayar , isnull(b.total,0)-isnull(c.total,0) as saldo
                                    from sis_siswa a 
                                    left join (
                                    select x.kode_param,x.nis,x.kode_lokasi,sum(case when x.dc='D' then x.nilai else -x.nilai end) as total
                                    from sis_bill_d x
                                    inner join sis_siswa y on x.nis=y.nis and x.kode_lokasi=y.kode_lokasi and x.kode_pp=y.kode_pp
                                    where(x.kode_lokasi = '$kode_lokasi')and(x.periode = '$row1->periode') and x.kode_pp='$kode_pp' 	
                                    group by x.kode_param,x.kode_lokasi,x.nis ) b on a.kode_lokasi=b.kode_lokasi and a.nis=b.nis
                                    left join (
                                    select x.kode_param,x.nis,x.kode_lokasi, sum(case when x.dc='D' then x.nilai else -x.nilai end) as total
                                    from sis_rekon_d x
                                    inner join sis_siswa y on x.nis=y.nis and x.kode_lokasi=y.kode_lokasi and x.kode_pp=y.kode_pp
                                    where(x.kode_lokasi = '$kode_lokasi')and(x.periode = '$row1->periode') and x.kode_pp='$kode_pp' 
                                    group by x.kode_param,x.nis,x.kode_lokasi
                                    ) c on a.kode_lokasi=c.kode_lokasi and a.nis=c.nis
                                    where(a.kode_lokasi = '$kode_lokasi')and a.kode_pp='$kode_pp' and a.nis='$nik2'";
                
                                    $rs2 = execute($sql);  
                                    $x=0;
                                    while ($row2 = $rs2->FetchNextObject($toupper=false)){

                                        if($x % 2 == 1){
                                            $clr=$colors[1];
                                        }else{
                                            $clr=$colors[2];
                                        }
                                 
                                        echo"
                                        <tr>
                                            <td width='25%' colspan='2' style='text-align:center'>$row2->kode_param</td>
                                            <td style='text-align:center' > ".number_format($row2->bill,0,",",".")."</td>
                                            <td style='text-align:center'> ".number_format($row2->bayar,0,",",".")."</td>
                                            <td style='text-align:center'> ".number_format($row2->saldo,0,",",".")."</td>
                                        </tr>";
                                        $x++;
                                    }
                                    echo"
                                    </tbody>
                                    </table>
                                     ";
                                  
                        echo"
                        </div>
                        ";  
                        
                        }

                        if($tmp[3] == "" OR $tmp[3] == "0"){
                            echo "<div class='row'>
                            <a class='btn btn-block' style='margin-left:15px;$color' href='$fmain?hal=app/ypt/dashSiswaDet2.php&param=$jenis/$kunci/tagihan/1/GANJIL' style='cursor:pointer;' > Next Page &nbsp;<i class='fa fa-angle-right'></i>
                            </div>";
                        }else{
                            echo "<div class='row'>
                            <a class='btn btn-block' style='margin-left:15px;$color' href='$fmain?hal=app/ypt/dashSiswaDet2.php&param=$jenis/$kunci/tagihan/0/GENAP' style='cursor:pointer;' > <i class='fa fa-angle-left'></i> &nbsp; Prev Page
                            </div>";
                        }
                        
                         
                        break;
                        case "detTagih" :
                
                        $kode_param=$tmp[3];
                        $per=$tmp[4];
                        $modul=$tmp[5];

                        if($modul == "bill" OR $modul == "BILL"){

                    echo"
                            <div class='box box-widget' style='border: 1px solid #f2efefa1;'>
                                <div class='box-body'>
                                    <div class='row' style='margin-bottom:10px'>
                                        <div class='col-md-1'>
                                            <span class='label label-primary' style='font-size:14px'>Bulan ".substr($per,4,2)."</span>
                                        </div>
                                    </div>
                                    <div class='row'>
                                        <div class='col-md-12'>";

                                        $sql1="select kode_ta, kode_sem, periode from sis_bill_d where kode_pp='$kode_pp' and kode_lokasi='$kode_lokasi' and nis='$nik2' and periode = '$per' ";

                                        $rs1 = execute($sql1);
                                        $row1 = $rs1->FetchNextObject($toupper=false);
                                        echo"
                                            <table style='width: 100%;'>
                                                <tbody>
                                                <tr>
                                                    <td width='15%' >Tahun Ajaran</td>
                                                    <th width='15%' colspan='3'>$row1->kode_ta</th>
                                                </tr>
                                                <tr>
                                                    <td  width='15%'>Semester</td>
                                                    <th  width='15%' colspan='3' >$row1->kode_sem</th>
                                                </tr>
                                                <tr>
                                                    <td  width='15%'>Periode</td>
                                                    <th  width='15%' colspan='3' >$row1->periode</th>
                                                </tr>
                                                <tr>
                                                    <td  >Saldo</td>
                                                    <th style='text-align:right' colspan='3'>Rp.0</th>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </diV>
                                </div>";        
                                   
                                 $colors = array('#39CCCC','#001F3F','#39CCCC','#001F3F','#39CCCC','#001F3F','#39CCCC','#001F3F','#39CCCC','#001F3F','#39CCCC','#001F3F');

                                    echo"
                                    <table class='table no-margin'>
                                    <thead>
                                        <tr>
                                            <th width='25%' colspan='2' style='border-bottom: 1px solid white;'>Parameter Biaya</th>
                                            <th width='25%' style='text-align:right;border-bottom: 1px solid white;'>Nilai</th>
                                            <th width='25%' style='text-align:right;border-bottom: 1px solid white;'>Terbayar</th>
                                            <th width='25%' style='text-align:right;border-bottom: 1px solid white;'>Saldo</th>
                                        </tr>
                                    </thead>
                                    <tbody>";   
                                   
                                    $sql="
                                    select a.nis, a.nama, b.kode_param,isnull(b.total,0) as bill, isnull(c.total,0) as bayar , isnull(b.total,0)-isnull(c.total,0) as saldo
                                    from sis_siswa a 
                                    left join (
                                    select x.kode_param,x.nis,x.kode_lokasi,sum(case when x.dc='D' then x.nilai else -x.nilai end) as total
                                    from sis_bill_d x
                                    inner join sis_siswa y on x.nis=y.nis and x.kode_lokasi=y.kode_lokasi and x.kode_pp=y.kode_pp
                                    where(x.kode_lokasi = '$kode_lokasi')and(x.periode = '$row1->periode') and x.kode_pp='$kode_pp' 	
                                    group by x.kode_param,x.kode_lokasi,x.nis ) b on a.kode_lokasi=b.kode_lokasi and a.nis=b.nis
                                    left join (
                                    select x.kode_param,x.nis,x.kode_lokasi, sum(case when x.dc='D' then x.nilai else -x.nilai end) as total
                                    from sis_rekon_d x
                                    inner join sis_siswa y on x.nis=y.nis and x.kode_lokasi=y.kode_lokasi and x.kode_pp=y.kode_pp
                                    where(x.kode_lokasi = '$kode_lokasi')and(x.periode = '$row1->periode') and x.kode_pp='$kode_pp' 
                                    group by x.kode_param,x.nis,x.kode_lokasi
                                    ) c on a.kode_lokasi=c.kode_lokasi and a.nis=c.nis
                                    where(a.kode_lokasi = '$kode_lokasi')and a.kode_pp='$kode_pp' and a.nis='$nik2' and b.kode_param='$kode_param' ";
                
                                    $rs2 = execute($sql);  
                                    $x=0;
                                    while ($row2 = $rs2->FetchNextObject($toupper=false)){

                                        if($x % 2 == 1){
                                            $clr=$colors[1];
                                        }else{
                                            $clr=$colors[2];
                                        }
                                 
                                        echo"
                                        <tr>
                                            <td width='2%'><div style='width:30px;height:30px;color:".$clr.";border:2px solid ".$clr.";border-radius:50%;background:".$clr."'>OR9</div></td>
                                            <td width='23%'>$row2->kode_param</td>
                                            <td style='text-align:right;color:".$clr."'>Rp. ".number_format($row2->bill,0,",",".")."</td>
                                            <td style='text-align:right;color:".$clr."'>Rp. ".number_format($row2->bayar,0,",",".")."</td>
                                            <td style='text-align:right;color:".$clr."'>Rp. ".number_format($row2->saldo,0,",",".")."</td>
                                        </tr>";
                                        $x++;
                                    }
                                    echo"
                                    </tbody>
                                    </table>
                                     ";
                                    
                                    
                                // }
                        echo"
                        </div>
                        ";  
                        }else{
                        
                    
                    echo"
                    <div class='box box-widget' style='border: 1px solid #f2efefa1;'>
                        <div class='box-body'>
                            <div class='row' style='margin-bottom:10px'>
                                <div class='col-md-1'>
                                    <span class='label label-primary' style='font-size:14px'>Bulan ".substr($per,4,2)."</span>
                                </div>
                            </div>
                            <div class='row'>
                                <div class='col-md-12'>
                                    <table style='width: 100%;'>
                                        <tbody>
                                        <tr>
                                            <th width='75%' colspan='2'>Deposit</th>
                                            <th width='25%' style='text-align:right'>&nbsp;</th>
                                        </tr>";
                                    
                                        $colors = array('#39CCCC','#001F3F','#39CCCC','#001F3F','#39CCCC','#001F3F','#39CCCC','#001F3F','#39CCCC','#001F3F','#39CCCC','#001F3F');

                                        $sql="
                                        select a.nis, a.nama, b.kode_param,isnull(b.total,0) as dep
                                        from sis_siswa a 
                                        left join (
                                        select x.kode_param,x.nis,x.kode_lokasi,sum(case when x.dc='D' then x.nilai else -x.nilai end) as total
                                        from sis_cd_d x
                                        inner join sis_siswa y on x.nis=y.nis and x.kode_lokasi=y.kode_lokasi and x.kode_pp=y.kode_pp
                                        where(x.kode_lokasi = '$kode_lokasi')and(x.periode = '$per') and x.kode_pp='$kode_pp' 	
                                        group by x.kode_param,x.kode_lokasi,x.nis ) b on a.kode_lokasi=b.kode_lokasi and a.nis=b.nis
                                        where(a.kode_lokasi = '$kode_lokasi')and a.kode_pp='$kode_pp' and a.nis='$nik2' and b.kode_param='$kode_param' ";
                    
                                        $rs2 = execute($sql);  
                                        $x=0;
                                        while ($row2 = $rs2->FetchNextObject($toupper=false)){

                                            if($x % 2 == 1){
                                                $clr=$colors[1];
                                            }else{
                                                $clr=$colors[2];
                                            }
                                    
                                            echo"
                                            <tr>
                                                <td width='2%'><div style='width:30px;height:30px;color:".$clr.";border:2px solid ".$clr.";border-radius:50%;background:".$clr."'>OR9</div></td>
                                                <td width='73%' style='padding-left: 10px;'>$row2->kode_param</td>
                                                <td style='text-align:right;color:".$clr."'>Rp. ".number_format($row2->dep,0,",",".")."</td>
                                            </tr>";
                                            $x++;
                                        }
                                        echo"
                                        </tbody>
                                        </table>
                                        ";


                                echo"
                                </div>
                            </diV>
                        </div>";        
                           
                    
                    echo"
                    </div>
                    ";       
                    
                    }
                        break;
                        case "deposit" :
                
                        echo"
                        <div class='box box-widget' style='box-shadow:0 0 0 white'>
                                <div class='box-body' style='padding-bottom: 2px;'>
                                    <h4 style='margin-bottom:0px'><b> Deposit </b></h4>
                                    <h5 class='pull-right' style='margin-bottom:0px;margin-top:0px'>Total Deposit : <b>".number_format($tmp[5],0,",",".")."</b></h5>
                                </div>";
        
                                $sql="
                                select a.* from (
                                    select a.no_bukti, a.kode_lokasi,a.nis, a.periode, a.nilai, b.keterangan, 
                                    convert(varchar,b.tanggal,103) as tgl, a.dc,a.kode_pp,a.kode_param,a.modul, b.tanggal
                                    from sis_cd_d a 
                                    inner join sis_rekon_m b on a.no_bukti=b.no_rekon and a.kode_lokasi=b.kode_lokasi
                                    where a.nis='$nik2' and a.kode_pp='$kode_pp' and a.kode_lokasi='$kode_lokasi' 
                                    union 
                                    select  a.no_bukti, a.kode_lokasi,a.nis, a.periode, a.nilai, b.keterangan, 
                                    convert(varchar,b.tanggal,103) as tgl, a.dc,a.kode_pp,a.kode_param,a.modul,b.tanggal from sis_cd_d a 
                                    inner join kas_m b on a.no_bukti=b.no_kas and a.kode_lokasi=b.kode_lokasi
                                    where a.nis='$nik2' and a.kode_pp='$kode_pp' and a.kode_lokasi='$kode_lokasi' 
                                )  a
                                inner join (
                                select a.periode,a.kode_lokasi,a.nis,sum(case when a.dc = 'D' then a.nilai else -a.nilai end) as tot 
                                from sis_cd_d a
                                where a.nis='$nik2' and a.kode_pp='$kode_pp' and a.kode_lokasi='$kode_lokasi' 
                                group by a.periode,a.kode_lokasi,a.nis
                                having sum(case when a.dc = 'D' then a.nilai else -a.nilai end) <> 0
                                ) b on a.kode_lokasi=b.kode_lokasi and a.periode=b.periode and a.nis=b.nis 
                                order by tanggal desc ";

                                // echo $sql;
        
                                $rs2 = execute($sql);  
                                while ($row2 = $rs2->FetchNextObject($toupper=false)){
        
                                    
                                    $color="color:#dd4b39";
                                    if ($row2->dc == "D"){
                                        $color="color:#14b313";
                                        // $total=$row2->tagihan;
                                        $gmbr=$path."image/green2.png";
                                        $tanda="<span style='font-size:18px;$color'>+</span>";
                                    }else{
                                        //$color="color:#1cbbff";
                                        // $total=$row2->bayar;
                                        $gmbr=$path."image/blue.png";
                                        $tanda="<span style='font-size:18px;'>-</span>";
                                    }
                                    echo"
                                    <div class='box-footer box-comments' style='background:white'>
                                        <div class='box-comment'>
                                           
                                            <div class='comment-text' style='margin-left:0px'>
                                                
                                                <div class='col-xs-8' style='padding-left: 0px;padding-right: 0px;font-weight: 400;color: black;'>
                                                $row2->keterangan <br>                                     <span style='font-size:12px'> $row2->no_bukti | $row2->tgl </span>
                                                </div>               
                                                <div class='col-xs-4' style='padding-left: 0px;padding-right: 0px;text-align:right'>
                                                    <br>
                                                    <span style='$color;font-size: 14px;'>$tanda &nbsp; <b>Rp. ".number_format($row2->nilai,0,",",".")."</b></span>
                                                </div>
                                                    
                                            </div>
                                        </div>
                                    </div>";
                                   
        
                                }
                        echo"
                        </div>                   
                        ";
        
                        break;
                        case "detKeu" :
                       
                    echo"
                    <div class='box box-widget' style='box-shadow:0 0 0 white'>
                            <div class='box-body' style='padding: 10px 0px'>
                                <h4 style='padding-left:15px'><b>Riwayat Transaksi</b> </h4>
                            </div>";
    
                            $sql2="select  a.* from (
                                select a.no_bill as no_bukti,a.kode_lokasi,b.tanggal,convert(varchar(10),b.tanggal,103) as tgl,b.periode,
                                b.keterangan,'BILL' as modul, isnull(a.tagihan,0) as tagihan,isnull(a.bayar,0) as bayar,a.kode_param
                                from (select x.kode_lokasi,x.no_bill,x.kode_param,sum(x.nilai) as tagihan,
                                        0 as bayar from sis_bill_d x 
                                        inner join sis_siswa y on x.nis=y.nis and x.kode_lokasi=y.kode_lokasi and x.kode_pp=y.kode_pp
                                        where x.kode_lokasi = '$kode_lokasi' and x.nis='$nik2' and x.kode_pp='$kode_pp' and x.nilai<>0 
                                        group by x.kode_lokasi,x.no_bill,x.nis,x.kode_param )a 
                                inner join sis_bill_m b on a.no_bill=b.no_bill and a.kode_lokasi=b.kode_lokasi 
                                union all 
                                select a.no_rekon as no_bukti,a.kode_lokasi,b.tanggal,
                                convert(varchar(10),b.tanggal,103) as tgl,b.periode,b.keterangan,'PDD' as modul, isnull(a.tagihan,0) as tagihan,isnull(a.bayar,0) as bayar,a.kode_param
                                from (select x.kode_lokasi,x.no_rekon,x.kode_param,
                                    case when x.modul in ('BTLREKON') then x.nilai else 0 end as tagihan,case when x.modul <>'BTLREKON' then x.nilai else 0 end as bayar
                                    from sis_rekon_d x inner join sis_siswa y on x.nis=y.nis and x.kode_lokasi=y.kode_lokasi and x.kode_pp=y.kode_pp 
                                    where x.kode_lokasi = '$kode_lokasi' and x.nis='$nik2' and x.kode_pp='$kode_pp' and x.nilai<>0
                                    )a 
                                inner join sis_rekon_m b on a.no_rekon=b.no_rekon and a.kode_lokasi=b.kode_lokasi 
                                union all 
                                select a.no_rekon as no_bukti,a.kode_lokasi,b.tanggal,
                                convert(varchar(10),b.tanggal,103) as tgl,b.periode,b.keterangan,'KB' as modul, isnull(a.tagihan,0) as tagihan,isnull(a.bayar,0) as bayar,a.kode_param 
                                from (select x.kode_lokasi,x.no_rekon,x.kode_param,
                                    case when x.modul in ('BTLREKON') then x.nilai else 0 end as tagihan,case when x.modul <>'BTLREKON' then x.nilai else 0 end as bayar
                                    from sis_rekon_d x inner join sis_siswa y on x.nis=y.nis and x.kode_lokasi=y.kode_lokasi and x.kode_pp=y.kode_pp 
                                    where x.kode_lokasi = '$kode_lokasi' and x.nis='$nik2' and x.kode_pp='$kode_pp' and x.nilai<>0 
                                )a
                                inner join kas_m b on a.no_rekon=b.no_kas and a.kode_lokasi=b.kode_lokasi 
                            ) a
                            order by a.tanggal desc ";

                            $rs=execute($sql2);
                            $torecord =  $rs->RecordCount();
                            if($tmp[3] == ""){
                                $page = 0;
                                $nextpage = 0;
                            }else{
                                $page = $tmp[3];
                                $nextpage = ($page * 10) + 1;
                            }
                            $jumpage = ceil($torecord/10);
                            
                            $sql = $sql2." 
                            OFFSET ".$nextpage." ROWS FETCH NEXT 10 ROWS ONLY";
                            $rs2 = execute($sql);

                            while ($row2 = $rs2->FetchNextObject($toupper=false)){
    
                                if ($row2->modul == "BILL"){
                                    $color="color:#0fce0e";
                                    $total=$row2->tagihan;
                                    $gmbr=$path."image/green2.png";
                                    $kdparam="Tagihan";
                                }else{
                                    $color="color:#1cbbff";
                                    $total=$row2->bayar;
                                    $gmbr=$path."image/blue.png";
                                    $kdparam="Pembayaran";
                                }
                                
                                $color="color:#dd4b39";
                                echo"
                                    <div class='box-footer box-comments' style='background:white'>
                                        <div class='box-comment' style=''>
                                            <div class='comment-text' style='margin-left: 5px;'>
                                                <div class='col-xs-8' style='padding-left: 0px;padding-right: 0px;font-weight: 400;color: black;'>
                                                $row2->keterangan <br> 
                                                <span style='font-size: 12px;'>$row2->tgl | $row2->no_bukti | $row2->kode_param</span>
                                                </div>               
                                                <div class='col-xs-4' style='padding-left: 0px;padding-right: 0px;text-align:right'>
                                                    <span style='color: #a9a7a7;font-size: 11px;'> $kdparam</span>
                                                    <br> 
                                                    <span style='$color;font-weight: bold;font-size: 1;'>".number_format($total,0,",",".")."</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                ";
    
                            }
                            if($tmp[3] == "" OR $tmp[3]==0){
                                if($jumpage > 1 AND $page < ($jumpage-1) ){
                                    $page++;
                                    echo "<div class='col-xs-6 pull-right'><a style='cursor:pointer;$color'  class='btn btn-block' onclick=\"window.location.href='$fmain?hal=app/ypt/dashSiswaDet2.php&param=$jenis/$kunci/$param/$page';\">Next Page
                                    &nbsp;<i class='fa fa-angle-right'></i></a></div>";
                                }

                            }else{
                                $prev=intval($tmp[3])-1;
                                // if($next == 1) $next = 0;

                                echo "<div class='col-xs-6' style='border-right:1px solid #bfbebe'><a style='cursor:pointer;$color'  class='btn  btn-block'  onclick=\"window.location.href='$fmain?hal=app/ypt/dashSiswaDet2.php&param=$jenis/$kunci/$param/$prev';\"><i class='fa fa-angle-left'></i> &nbsp; Prev Page </a></div>";
                                if($jumpage > 1 AND $page < ($jumpage-1) ){
                                    $page++;
                                    echo "<div class='col-xs-6'><a style='cursor:pointer;$color'  class='btn  btn-block' onclick=\"window.location.href='$fmain?hal=app/ypt/dashSiswaDet2.php&param=$jenis/$kunci/$param/$page';\">Next Page
                                    &nbsp;<i class='fa fa-angle-right'></i></a></div>";
                                }
                            }
                            
                            
                        echo"
                        </div>                   
                        ";

                        break;
                        case "detPresensi" :

                    echo"
                        <div class='box box-widget' style='border: 0px solid #f2efefa1;'>        
                            <div class='row' style='color:#dd4b39;padding-left:15px'>
                            <h3 style='margin-top:0px'>Detail Absensi</h3>
                            </div>
                            <div class='row'>
                                <div class='col-sm-12 col-xs-12'>
                                <table class='table no-border'>
                                    <thead>
                                        <tr>
                                            <td>Tanggal</td>
                                            <td>Keterangan</td>
                                        </tr>
                                    </thead>
                                    <tbody>";
                                    $sql="select convert(varchar,tanggal,103) as tgl,status from sis_presensi where kode_lokasi='$kode_lokasi' and kode_pp='$kode_pp' and nis='$nik2' and substring(convert(varchar,tanggal,112),1,6)='".$tmp[5]."' and status='".$tmp[4]."' ";

                                    $rs=execute($sql);
                                    while($rows=$rs->FetchNextObject($toupper=false)){
                                        echo"<tr>
                                            <td>$rows->tgl</td>
                                            <td>$rows->status</td>
                                        </tr>";
                                    }

                                    echo"
                                    </tbody>
                                </table>
                                </div>
                            </div>
                        </div>
                        "; 
                         
                        break;

                    }
           
        
        echo"   
            </div>
        </div>";

    echo "<script type='text/javascript'>
          $(document).ready(function(){
                

            })
         </script>";

?>
