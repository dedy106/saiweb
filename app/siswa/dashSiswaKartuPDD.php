<?php

    $kode_lokasi=$_SESSION['lokasi'];
    $periode=$_SESSION['periode'];
    $kode_pp=$_SESSION['kodePP'];
    $nik=$_SESSION['userLog'];
    $kode_fs=$_SESSION['kode_fs'];
    $kode_fs="FS1";
    $path = "http://".$_SERVER["SERVER_NAME"]."/";	
    
    $notifikasi= $path . "image/notif.png";
    $logomain = $path.'/web/img/yspt2.png';
    $mainname = $_SESSION['namaPP'];

    if (preg_match('/(chrome|firefox|avantgo|blackberry|android|blazer|elaine|hiptop|iphone|ipod|kindle|midp|mmp|mobile|o2|opera mini|palm|palm os|pda|plucker|pocket|psp|smartphone|symbian|treo|up.browser|up.link|vodafone|wap|windows ce; iemobile|windows ce; ppc;|windows ce; smartphone;|xiino)/i', $_SERVER['HTTP_USER_AGENT'], $version)) 

    // echo "Browser:".$version[1];

    if ($version[1] == "iPhone" || $version[1] == "Android" || $version[1] == "Blackberry" || $version[1] == "Blazer" ||$version[1] == "Elaine" || $version[1] == "Hiptop" || $version[1] == "iPod" || $version[1] == "Kindle" ||$version[1] == "Midp" || $version[1] == "Mobile" || $version[1] == "O2" || $version[1] == "Opera Mini" ||$version[1] == "Mobile" || $version[1] == "Smartphone"){
        $width="33%";
        $header= "<header class='main-header' id='header'>
        <a href='#' class='logo btn btn-block btn-default' style='width:100%;background-color: white;color: black;border: 0px solid black;vertical-align: middle;font-size:16px;text-align: left;border-bottom: 1px solid #e6e2e2;'>
           <span class='logo-lg'><img src='$logomain' style='max-width:30px;max-height:37px'>&nbsp;&nbsp; <b>$mainname</b></span>
           </a>
        </header>";
        $padding="padding-top:50px";
    }else{
        $width="25%";
        $header="";
        $padding="";
    }
 
    $sql="select a.nis,a.kode_lokasi,a.kode_pp,a.nama,a.kode_kelas,b.nama as nama_kelas,a.kode_lokasi,b.kode_jur,f.nama as nama_jur,a.id_bank,a.kode_akt, g.nama as nama_pp,g.alamat,g.alamat2,g.pic,g.bank,g.rek, isnull(c.nilai,0)+isnull(d.nilai,0)-isnull(e.nilai,0) as so_akhir from sis_siswa a inner join sis_kelas b on a.kode_kelas=b.kode_kelas and a.kode_lokasi=b.kode_lokasi and a.kode_pp=b.kode_pp inner join sis_jur f on b.kode_jur=f.kode_jur and b.kode_lokasi=f.kode_lokasi and b.kode_pp=f.kode_pp inner join sis_sekolah g on a.kode_pp=g.kode_pp and a.kode_lokasi=g.kode_lokasi left join (select a.nis,a.kode_lokasi,a.kode_pp,sum(case when a.dc='D' then nilai else -nilai end) as nilai from sis_cd_d a inner join sis_siswa b on a.nis=b.nis and a.kode_lokasi=b.kode_lokasi and a.kode_pp=b.kode_pp where a.kode_lokasi='$kode_lokasi' and a.kode_pp='$kode_pp' and a.periode<'$periode' and a.nis='$nik' group by a.nis,a.kode_lokasi,a.kode_pp )c on a.nis=c.nis and a.kode_lokasi=c.kode_lokasi and a.kode_pp=c.kode_pp left join (select a.nis,a.kode_lokasi,a.kode_pp,sum(a.nilai) as nilai from sis_cd_d a inner join sis_siswa b on a.nis=b.nis and a.kode_lokasi=b.kode_lokasi and a.kode_pp=b.kode_pp where a.kode_lokasi='$kode_lokasi' and a.dc='D' and a.kode_pp='$kode_pp' and a.periode='$periode' and a.nis='$nik' group by a.nis,a.kode_lokasi,a.kode_pp )d on a.nis=d.nis and a.kode_lokasi=d.kode_lokasi and a.kode_pp=d.kode_pp left join (select a.nis,a.kode_lokasi,a.kode_pp,sum(a.nilai) as nilai from sis_cd_d a inner join sis_siswa b on a.nis=b.nis and a.kode_lokasi=b.kode_lokasi and a.kode_pp=b.kode_pp where a.kode_lokasi='$kode_lokasi' and a.dc='C' and a.kode_pp='$kode_pp' and a.periode='$periode' and a.nis='$nik' group by a.nis,a.kode_lokasi,a.kode_pp )e on a.nis=e.nis and a.kode_lokasi=e.kode_lokasi and a.kode_pp=e.kode_pp where a.nis='$nik' order by a.nis ";

    $rs=execute($sql);
    $row = $rs->FetchNextObject($toupper=false);	

    echo "
        $header
		<div class='panel' style='margin:0px;$padding'>
            <div class='panel-heading' style='font-size:25px;padding:10px 0px 10px 20px;color:#dd4b39'>Laporan Kartu PDD
            </div>
            
            <div class='panel-body' style='padding:0px'>
            <div class='row' style='padding-left:10px'>
                <div class='col-xs-12 table-responsive' style='border:none'>";
            echo"<table class='table no-border'>";
            echo"
                <tr>
                    <td style='padding-bottom:0px;padding-top:0px'>Nama</td>
                    <td style='padding-bottom:0px;padding-top:0px'>$row->nama</td>
                </tr>
                <tr>
                    <td style='padding-bottom:0px;padding-top:0px'>NIS</td>
                    <td style='padding-bottom:0px;padding-top:0px'>$row->nis</td>
                </tr>
                <tr>
                    <td style='padding-bottom:0px;padding-top:0px'>Id Bank</td>
                    <td style='padding-bottom:0px;padding-top:0px'>$row->id_bank</td>
                </tr>
                <tr>
                    <td style='padding-bottom:0px;padding-top:0px'>Saldo PDD</td>
                    <td style='padding-bottom:0px;padding-top:0px'>".number_format($row->so_akhir,0,",",".")."</td>
                </tr>
                </table>
                </div>
            </div>
            <div class='row' style='margin-right: -10px;margin-left: -10px;'>
            <div class='col-xs-12 table-responsive' style='border:none'>
                <table class='table no-border' style='font-size:12px;background: #dd4b39;border:1px solid #dd4b39;color: white;border-radius: 5px;'>
                <tr style=''>
                    <th width='50%'>Keterangan</th>
                    <th width='22%'>Transaksi</th>
                    <th width='28%' style=''>Saldo</th>
                </tr>
                </table>
                <table class='table no-border' style='font-size:12px;'>
                ";
                $tgh_tabel = "";
                $total_d= 0;
                $total_k = 0;
                $total_saldo = 0;
                
                $sql2 = "select a.no_bukti,a.tgl,a.keterangan,a.modul,a.kode_param,a.debet,a.kredit 
                from (select a.no_bukti,a.nilai,CONVERT(VARCHAR(8), b.tanggal, 3) as tgl,a.kode_param,b.keterangan,b.modul,b.tanggal, a.nilai as debet,0 as kredit 
                    from sis_cd_d a 
                    inner join kas_m b on a.no_bukti=b.no_kas and a.kode_lokasi=b.kode_lokasi where a.nis='$nik' and a.kode_lokasi='$kode_lokasi' and a.kode_pp='$kode_pp' and a.dc='D' union all 
                    select a.no_bukti,a.nilai,CONVERT(VARCHAR(8), b.tanggal, 3) as tgl,a.kode_param,b.keterangan,b.modul,b.tanggal, 0 as debet,case when a.dc='C' then a.nilai else 0 end as kredit from sis_cd_d a inner join sis_rekon_m b on a.no_bukti=b.no_rekon and a.kode_lokasi=b.kode_lokasi where a.nis='$nik' and a.kode_lokasi='$kode_lokasi' and a.kode_pp='$kode_pp' and a.dc='C' 
                    union all 
                    select a.no_bukti,a.nilai,CONVERT(VARCHAR(8), b.tanggal, 3) as tgl,a.kode_param,b.keterangan,b.modul,b.tanggal, 0 as debet,case when a.dc='C' then a.nilai else 0 end as kredit from sis_cd_d a inner join kas_m b on a.no_bukti=b.no_kas and a.kode_lokasi=b.kode_lokasi where a.nis='$nik' and a.kode_lokasi='$kode_lokasi' and a.kode_pp='$kode_pp' and a.dc='C')a order by a.tanggal";
                
            
                $rs1=execute($sql2);
                $i=1;
                while($row1 = $rs1->FetchNextObject($toupper=false)){
                    $total_d += +$row1->debet;
                    $total_k += +$row1->kredit;
                    $total_saldo += +$total_d - $total_k;
                    
                    echo "<tr>";
                    if($row1->debet > 0){
                        echo"<td width='50%' style=''>$row1->tgl | $row1->no_bukti <br> Deposit $row1->kode_param  </td>
                        <td style='color:green' width='22%'>".number_format($row1->debet,0,",",".")."</td>";
                    }else{
                        echo"<td width='50%'>$row1->tgl | $row1->no_bukti <br> Pembayaran $row1->kode_param  </td>
                        <td style='color:#dd4b39' width='22%'>".number_format($row1->kredit,0,",",".")."</td>";
                    }
                    echo"
                    <td style='' width='28%'>".number_format($total_saldo,0,",",".")."</td>
                    </tr>";
                    $i++;
                }
                $total=$total_d-$total_k;
                // echo "<tr>
                // <td style='text-align:right;'><strong>Total</strong></td>
                // <td>".number_format($total,0,",",".")."</td>
                // <td></td>
                // </tr>";
                
                if($total_saldo > 0){
                    echo "<tr>
                    <td style='text-align:right;' colspan='2'><strong>Saldo</strong></td>
                    <td>".number_format($total_saldo)."</td>
                    </tr>";
                }else{
                    echo "<tr>
                    <td style='text-align:right;' colspan='2'><strong>Saldo</strong></td>
                    <td></td>
                    </tr>";
                }
                echo"
                </table>
            </div>
            </div>
            ";
            echo"               
            </div>
       </div>";    
       
       
                		
		echo "
        <script type='text/javascript'>
        </script>";

   
?>
