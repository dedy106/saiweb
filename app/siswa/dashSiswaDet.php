<?php
    
    $kode_lokasi=$_SESSION['lokasi'];
    $periode=$_SESSION['periode'];
    $kode_pp=$_SESSION['kodePP'];
    $nik=$_SESSION['userLog'];
    $fmain=$_SESSION['fMain'];
	
    $nik2=str_replace("a","",$nik);

    $root=$_SERVER["DOCUMENT_ROOT"];
    $root2="http://".$_SERVER['SERVER_NAME'];
	$folder_css=$root2."/web/css";
	$folder_js=$root2."/web/js";

	/*
    $sql="select kode_pp from karyawan where kode_lokasi = '$kode_lokasi' and nik ='$nik2' ";
    $rs = execute($sql);
    $kode_pp=$rs->fields[0];

	*/
	
    $tmp=explode("/",$_GET['param']);
    $jenis=$tmp[0];
    $kunci=$tmp[1];

    if($_GET['param']==""){
        $jenis="all";
        $kunci="keu";
    }

    $path = "http://".$_SERVER["SERVER_NAME"]."/";				
    $path2 = $path . "image/keubg.png";
    $foto = $path . "image/wallpaper/Forest.jpg";

    if (preg_match('/(chrome|firefox|avantgo|blackberry|android|blazer|elaine|hiptop|iphone|ipod|kindle|midp|mmp|mobile|o2|opera mini|palm|palm os|pda|plucker|pocket|psp|smartphone|symbian|treo|up.browser|up.link|vodafone|wap|windows ce; iemobile|windows ce; ppc;|windows ce; smartphone;|xiino)/i', $_SERVER['HTTP_USER_AGENT'], $version)) 

    // echo "Browser:".$version[1];

    switch($kunci){
        case "bp" :
        $judul = "Buku Penghubung";
        break;
        case "keu" :
        $judul = "Keuangan";
        break;
        case "kld" :
        $judul = "Jadwal Pelajaran";
        break;
        case "ka" :
        $judul = "Kalender Akademik";
        break;
        case "abs" :
        $judul = "Absensi";
        // $hreficback="$fmain?hal=app/ypt/dashSiswaDet2.php&param=$jenis/$kunci";
        // $imgicback= $path."image/icon_history.png";
        break;
        case "prestasi" :
        $judul = "Prestasi";
        break;
        case "raport" :
        $judul = "Raport";
        break;
        case "eskul" :
        $judul = "Ekstrakulikuler";
        break;
        case "nil" :
        $judul = "Nilai";
        // $hreficback="$fmain?hal=app/ypt/dashSiswaDet2.php&param=$jenis/$kunci";
        // $imgicback= $path."image/icon_history.png";
        break;
        case "news" :
        $judul = "Informasi";
        break;
    }

    if ($version[1] == "iPhone" || $version[1] == "Android" || $version[1] == "Blackberry" || $version[1] == "Blazer" ||$version[1] == "Elaine" || $version[1] == "Hiptop" || $version[1] == "iPod" || $version[1] == "Kindle" ||$version[1] == "Midp" || $version[1] == "Mobile" || $version[1] == "O2" || $version[1] == "Opera Mini" ||$version[1] == "Mobile" || $version[1] == "Smartphone"){
        $back1="";
        $backto="$fmain?hal=app/ypt/dashSiswa.php";
        $mobile=true;
        include('back.php');
        $padding="padding-top:50px;border:0px solid grey;box-shadow: 0 0 0 white;";
    }else{
        $back1="<div class='panel-heading'><a href='$fmain?hal=app/ypt/dashSiswa.php' class='small-box-footer' > Back <i class='fa fa-arrow-circle-left'></i></a></div>";
        $padding="";
        $mobile=false;
    }

    echo "<div class='panel' style='$padding'>
			<div class='panel-body'>
                
                $back1
                ";
                switch($kunci){
                    case "bp" :
                    echo "
                    <div class='row'>
                        <div class='col-md-12'>
                        <!-- The time line -->
                        <ul class='timeline'>
                            <!-- timeline time label -->
                            <style>
                                div.timeline-item:hover {
                                border: 2px solid #e3e3e3;
                                cursor:pointer;
                            }
                            </style>
                        ";

                        $sql="select distinct convert(varchar,tanggal,103) as tgl from sis_bp where kode_lokasi='$kode_lokasi' and kode_pp ='$kode_pp' order by convert(varchar,tanggal,103) desc  ";

                        // echo $sql;
                        $rs = execute($sql);  
                        while ($row = $rs->FetchNextObject($toupper=false)){

                        echo"    
                            <li class='time-label'>
                                <span class='bg-red'>
                                    $row->tgl
                                </span>
                            </li>
                            <!-- /.timeline-label -->
                            ";

                            $sql2="select no_bukti, tanggal, keterangan, convert(varchar(10), tgl_input, 108) as jam,file_gambar,jenis,file_dok,judul from sis_bp 
                            where convert(varchar,tanggal,103) = '$row->tgl' 
                            order by convert(varchar(10), tgl_input, 108)  desc ";

                            $rs2 = execute($sql2);  
                            while ($row2 = $rs2->FetchNextObject($toupper=false)){

                            $gambar= $path."server/media/".$row2->file_gambar;
                            $pathdok= $path."server/media/".$row2->file_dok;
                            

                            $keterangan= urldecode($row2->keterangan);
                            // $limited_string = limit_words($keterangan, 30);
                            $num_char = 100;
                            
                            // echo"
                            // <a class='btn btn-primary btn-xs pull-right list-bp' style='cursor:pointer;' href='$fmain?hal=app/ypt/dashSiswaNews.php&param=$row2->no_bukti/bp' >
                            // ";
                                
                        echo"
                            <!-- timeline item -->
                            
                            <li>
                            <i class='fa fa-envelope bg-red'></i>
                            
                            <div class='timeline-item'>
                                
                                <span class='time' style='padding-top:4px'>  <i class='fa fa-clock-o'></i> $row2->jam</span>
                                <h3 class='timeline-header' style='color:#dd4b39'><b>$row2->judul</b></h3>
                                
                                <div class='timeline-body'>
                                <p hidden class='list-no'>$row2->no_bukti</p>
                                <img src='$gambar' alt='' class='margin' width='150px'>
                                ".substr($keterangan, 0, 100) . '...'."
                                </div>
                                
                            </div>
                            </li>
                            </a>
                            <!-- END timeline item -->
                        ";
                            }

                        }
                        echo"
                            <li>
                            <i class='fa fa-clock-o bg-red'></i>
                            </li>
                        </ul>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->

                    ";
                    echo "
                    <script>
                    $('.timeline-body').click(function(){
                        var param = $(this).closest('div').find('.list-no').text();
                        // alert(param);
                        window.location.href='$fmain?hal=app/ypt/dashSiswaNews.php&param='+param+'/bp'
                    });
                    $('.timeline-item').on('click','.list-del',function(){
                        var param = $(this).closest('div').find('.list-no').text();
                        alert(param);
                        // window.location.href='$fmain?hal=app/ypt/dashSiswaNews.php&param='+param+'/bp'
                    });
                    </script>
                    ";
                        break;
                        case "keu" :
                    
                        $sql="select a.nis,a.nama,a.kode_lokasi,a.kode_pp,isnull(b.total,0)-isnull(d.total,0)+isnull(c.total,0)-isnull(e.total,0) as sak_total,a.kode_kelas,isnull(e.total,0) as bayar
                        from sis_siswa a 
                        inner join sis_kelas f on a.kode_kelas=f.kode_kelas and a.kode_lokasi=f.kode_lokasi and a.kode_pp=f.kode_pp
                        left join (select y.nis,y.kode_lokasi,  
                                    sum(case when x.dc='D' then x.nilai else -x.nilai end) as total		
                                    from sis_bill_d x 			
                                    inner join sis_siswa y on x.nis=y.nis and x.kode_lokasi=y.kode_lokasi and x.kode_pp=y.kode_pp
                                    where(x.kode_lokasi = '$kode_lokasi')and(x.periode < '$periode') and x.kode_pp='$kode_pp'			
                                    group by y.nis,y.kode_lokasi 			
                                    )b on a.nis=b.nis and a.kode_lokasi=b.kode_lokasi
                        left join (select y.nis,y.kode_lokasi,  
                                    sum(case when x.dc='D' then x.nilai else -x.nilai end) as total		
                                    from sis_bill_d x 			
                                    inner join sis_siswa y on x.nis=y.nis and x.kode_lokasi=y.kode_lokasi and x.kode_pp=y.kode_pp
                                    where(x.kode_lokasi = '$kode_lokasi')and(x.periode = '$periode') and x.kode_pp='$kode_pp'			
                                    group by y.nis,y.kode_lokasi 			
                                    )c on a.nis=c.nis and a.kode_lokasi=c.kode_lokasi
                        left join (select y.nis,y.kode_lokasi,  
                                    sum(case when x.dc='D' then x.nilai else -x.nilai end) as total				
                                    from sis_rekon_d x 	
                                    inner join sis_siswa y on x.nis=y.nis and x.kode_lokasi=y.kode_lokasi and x.kode_pp=y.kode_pp
                                    where(x.kode_lokasi = '$kode_lokasi')and(x.periode <'$periode')	and x.kode_pp='$kode_pp'		
                                    group by y.nis,y.kode_lokasi 			
                                    )d on a.nis=d.nis and a.kode_lokasi=d.kode_lokasi
                        left join (select y.nis,y.kode_lokasi, 
                                    sum(case when x.dc='D' then x.nilai else -x.nilai end) as total			
                                    from sis_rekon_d x 			
                                    inner join sis_siswa y on x.nis=y.nis and x.kode_lokasi=y.kode_lokasi and x.kode_pp=y.kode_pp
                                    where(x.kode_lokasi = '$kode_lokasi')and(x.periode ='$periode') and x.kode_pp='$kode_pp'			
                                    group by y.nis,y.kode_lokasi 			
                                    )e on a.nis=e.nis and a.kode_lokasi=e.kode_lokasi
                        where(a.kode_lokasi = '$kode_lokasi') and a.kode_pp='$kode_pp'	and a.nis='$nik2'
                        order by a.kode_kelas,a.nis";

                        
                         
    
                        $rsTung = execute($sql);  
                        $row = $rsTung->FetchNextObject($toupper=false);
    
                        $tunggakan = $row->sak_total;
    
                        $sql="select a.nis,a.kode_lokasi,a.nama,a.kode_kelas,
                        isnull(c.nilai,0)+isnull(d.nilai,0)-isnull(e.nilai,0) as so_akhir
                        from sis_siswa a 
                        inner join sis_kelas b on a.kode_kelas=b.kode_kelas and a.kode_lokasi=b.kode_lokasi and a.kode_pp=b.kode_pp
                        inner join (select a.nis,a.kode_pp,a.kode_lokasi
                                    from sis_cd_d a
                                    where a.kode_lokasi='$kode_lokasi' and a.kode_pp='$kode_pp'
                                    group by a.nis,a.kode_pp,a.kode_lokasi
                                    )g on a.nis=g.nis and a.kode_lokasi=g.kode_lokasi and a.kode_pp=g.kode_pp
                        left join (select a.nis,a.kode_lokasi,a.kode_pp,sum(case when a.dc='D' then nilai else -nilai end) as nilai
                                from sis_cd_d a			
                                inner join sis_siswa b on a.nis=b.nis and a.kode_lokasi=b.kode_lokasi and a.kode_pp=b.kode_pp
                                where a.kode_lokasi='$kode_lokasi' and a.periode<'$periode' and a.kode_pp='$kode_pp'
                                group by a.nis,a.kode_lokasi,a.kode_pp
                                )c on a.nis=c.nis and a.kode_lokasi=c.kode_lokasi and a.kode_pp=c.kode_pp
                        left join (select a.nis,a.kode_lokasi,a.kode_pp,sum(a.nilai) as nilai
                                from sis_cd_d a			
                                inner join sis_siswa b on a.nis=b.nis and a.kode_lokasi=b.kode_lokasi and a.kode_pp=b.kode_pp
                                where a.kode_lokasi='$kode_lokasi' and a.dc='D' and a.periode='$periode' and a.kode_pp='$kode_pp'
                                group by a.nis,a.kode_lokasi,a.kode_pp
                                )d on a.nis=d.nis and a.kode_lokasi=d.kode_lokasi and a.kode_pp=d.kode_pp
                        left join (select a.nis,a.kode_lokasi,a.kode_pp,sum(a.nilai) as nilai
                                from sis_cd_d a			
                                inner join sis_siswa b on a.nis=b.nis and a.kode_lokasi=b.kode_lokasi and a.kode_pp=b.kode_pp
                                where a.kode_lokasi='$kode_lokasi' and a.periode='$periode' and a.dc='C' and a.kode_pp='$kode_pp'
                                group by a.nis,a.kode_lokasi,a.kode_pp
                                )e on a.nis=e.nis and a.kode_lokasi=e.kode_lokasi and a.kode_pp=e.kode_pp
                        where a.kode_lokasi='$kode_lokasi' and a.kode_pp='$kode_pp' and a.nis='$nik2'
                        order by a.kode_kelas,a.nis";

                        
                         
    
                        $rsDep = execute($sql);  
                        $row = $rsDep->FetchNextObject($toupper=false);
    
                        $deposit =$row->so_akhir;
    
    
                    echo"
                    <style>.saldo::after {
                        content: '';
                        background-image: url('http://ypt.simkug.com/image/euro.png')   		    !important;  
                       background-size: 45%;
                         background-repeat: no-repeat;
                        opacity: 0.6;
                        top: 0;
                        left: 0;
                        bottom: 0;
                        right: 0;
                        position: absolute;
                      }
                      
                      .saldo{
                          display: block;
                      position:relative;
                      }
                    </style>
                    <div class='box box-widget' style='box-shadow: 0 0 0 white'>
                            <div class='box-body' style='padding: 10px 0px'>
                                <a href='$fmain?hal=app/ypt/dashSiswaDet2.php&param=all/$kunci/tagihan///$tunggakan' style='cursor:pointer;color: #dd4b39 !important;vertical-align: middle;margin-top: 20px;'><div class='col-md-6'>
                                    <div class='alert alert-danger alert-dismissible saldo' style='text-align:center;margin-bottom:5px;padding-right: 15px;min-height: 90px;padding:10px;vertical-align: middle;background-color: white !important;color: #dd4b39 !important;box-shadow: 1px 2px 2px #cd352257; '>
                                        <div class='col-xs-10' style='padding: 0px;min-height: 0px;padding-right:10px;border-right: 1px solid;'>          
                                            <span style='text-align: left;'>
                                                <p>Saldo Tagihan</p>
                                            </span>
                                            <span style='text-align: right;'>
                                                <h4 style='font-size:30px'>Rp. ".number_format($tunggakan,0,",",".")."</h4>
                                            </span>
                                        </div>
                                        <div class='col-xs-2' style='padding: 0px;'>
                                            <span>    
                                            <i class='fa fa-angle-right' style='font-size:30px;vertical-align: middle;margin-top: 15px;'></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                </a>
                                <a href='$fmain?hal=app/ypt/dashSiswaDet2.php&param=all/$kunci/deposit///$deposit' style='cursor:pointer;color: #dd4b39 !important;vertical-align: middle;margin-top: 20px;'><div class='col-md-6'>
                                    <div class='alert alert-danger alert-dismissible saldo' style='text-align:center;margin-bottom:5px;padding-right: 15px;min-height: 90px;padding:10px;vertical-align: middle;color: #dd4b39 !important;box-shadow: 1px 2px 2px #cd352257;
                                    background-color: white !important;
                                     '>
                                        <div class='col-xs-10' style='padding: 0px;min-height: 0px;padding-right:10px;border-right: 1px solid;'>
                                            <span style='text-align: left;'>
                                                <p>Saldo Deposit</p>
                                            </span>
                                            <span style='text-align: right;'>
                                                <h4 style='font-size:30px'>Rp. ".number_format($deposit,0,",",".")."</h4>
                                            </span>
                                        </div>
                                        <div class='col-xs-2' style='padding: 0px;'> 
                                            <span>    
                                            <i class='fa fa-angle-right' style='font-size:30px;vertical-align: middle;margin-top: 15px;'></i>
                                           
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                </a>
                                <div class='col-md-12'><h4 style='padding-top:10px'><b>Riwayat Transaksi</b></h4>
                                </div>
                            </div>";
    
                            $sql="select  top 10 a.* from (
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
                                                <div class='col-xs-8' style='padding-left: 0px;padding-right: 0px;font-weight: 500;color: black;'>
                                                $row2->keterangan <br> 
                                                <span style='font-size: 12px;'>$row2->tgl | $row2->no_bukti | $row2->kode_param</span>
                                                </div>               
                                                <div class='col-xs-4' style='padding-left: 0px;padding-right: 0px;text-align:right'>
                                                    <span style='color: #a9a7a7;font-size: 11px;'> $kdparam</span><br> 
                                                    <span style='$color;font-weight: bold;font-size: 1;'>".number_format($total,0,",",".")."</span>
                                                </div></a>
                                            </div>
                                        </div>
                                    </div>
                                ";
    
                            }
                            echo "<a href='$fmain?hal=app/ypt/dashSiswaDet2.php&param=$jenis/$kunci/detKeu' style='cursor:pointer;' >
                            <div class='box-footer box-comments' style='background:white'>
                                <div class='box-comment'>
                                    
                                    <div class='comment-text'>
                                        <span class='username' style='text-align:center;color:#dd4b39'>
                                            View More &nbsp;
                                            <i class='fa fa-angle-right'></i>
                                        </span><!-- /.username -->
                                    </div>
                                </div>
                            </div>
                            </a>";
                        echo"
                        </div>                   
                        ";

                        break;
                        
                        case "ka" :
    
                    echo"
                    <div class='box box-widget' style='box-shadow: 0 0 0 white;'>
                            <div class='box-body'>
                                <div class='alert alert-danger alert-dismissible' style='margin-bottom:0px;padding-left: 10px;padding-top: 0px;    background-color: white !important;
                                color: #dd4b39 !important;border:1px solid white;'>
                                    <h4 style='font-size:25px'>Kalender Akademik</h4>
                                    <p> Tahun Ajaran </p>                 
                                </div>
                            </div>";
    
                            $sql="select a.*, convert(varchar,a.tanggal,103) as tgl from sis_kalender_akad a where a.kode_lokasi='$kode_lokasi' and a.kode_pp='$kode_pp' ";

                            
                        // echo $sql;
    
                            $rs2 = execute($sql);  
                            while ($row2 = $rs2->FetchNextObject($toupper=false)){
    
                                // if ($row2->modul == "bill"){
                                //     $color="color:#01f400";
                                //     $total=$row2->tagihan;
                                //     $gmbr=$path."image/green2.png";
                                // }else{
                                //     $color="color:#1cbbff";
                                //     $total=$row2->bayar;
                                //     $gmbr=$path."image/blue.png";
                                // }
                                echo"
                                <div class='box-footer box-comments' style='background:white'>
                                    <div class='box-comment'>
                                        <div class='comment-text' style='margin-left: 10px;'>
                                            <span class='username'>
                                                $row2->agenda
                                                <span class='text-muted pull-right' style='font-size:14px'><b></b></span>
                                            </span><!-- /.username -->
                                                Tanggal $row2->tgl
                                        </div>
                                    </div>
                                </div>";
    
                            }
                    echo"
                    </div>                   
                    ";
                        break;
                        case "abs" :
                    $imgH=$path ."image/Masuk.png";
                    $imgA=$path ."image/Alpha.png";
                    $imgS=$path ."image/sick.png";
                    $imgI=$path ."image/Ijin.png";

                    if($tmp[2] != ""){
                        $kode_per=$tmp[2];
                    }else{
                        $kode_per=$periode;
                    }
                    
                    $sql ="select a.nis, a.nama , isnull(b.hadir,0) as hadir,isnull(b.alpa,0) as alpha,isnull(b.izin,0) as izin,isnull(b.sakit,0) as sakit 
                    from sis_siswa a 
                    left join (select a.nis,a.kode_lokasi,count(case when a.status ='hadir' then status end) hadir,
                               count(case when a.status ='alpa' then status end) alpa,
                               count(case when a.status ='izin' then status end) izin,
                               count(case when a.status ='sakit' then status end) sakit  
                                from sis_presensi a
                                inner join sis_ta b on a.kode_ta=b.kode_ta and a.kode_pp=b.kode_pp
                                inner join sis_kelas c on a.kode_kelas=c.kode_kelas and a.kode_pp=c.kode_pp
                                inner join sis_siswa d on a.nis=d.nis and a.kode_lokasi=d.kode_lokasi and a.kode_pp=d.kode_pp
                                where a.kode_lokasi='$kode_lokasi' and a.kode_pp='$kode_pp' and a.nis='$nik2' and substring(convert(varchar,a.tanggal,112),1,6)='$kode_per'
                    group by a.nis,a.kode_lokasi) b on a.nis=b.nis and a.kode_lokasi=b.kode_lokasi
                    where a.kode_lokasi='$kode_lokasi' and a.kode_pp='$kode_pp' and a.nis='$nik2' ";

                    $rsPres = execute($sql);  
                    $rowP = $rsPres->FetchNextObject($toupper=false);

                echo"
                    <div class='row'>
                        <div class='col-md-12'>
                            <div class='box box-solid ' style='box-shadow: 0 0 0 white;'>
                            <div class='box-header'>
                                <h3 class='box-title' style='font-size:25px;color:#dd4b39'>Absensi</h3>
                            </div>
                            <div class='box-body no-padding' style='border:none;box-shadow:none'>
                                <div class='row' style='margin-bottom:10px;margin-top;10px'>
                                    <div class='col-xs-12'>";
                                    if($mobile ==true){
                                        echo" <input type='text' value='".ubah_periode($kode_per)."' class='form-control' id='inp-per' placeholder='Pilih Periode' style='border:0;border-bottom:1px solid  #8080806b'>";
                                    }else{
                                        echo"
                                        <select class='form-control input-sm selectize' id='dash_ta' style='margin-bottom:5px;'>
                                        <option value=''>Pilih Periode</option> ";

                                        echo " <option value=".$kode_per." selected>".ubah_periode($kode_per)."</option>";

                                        $res = execute("select distinct substring(convert(varchar,tanggal,112),1,6) as periode from sis_presensi where kode_lokasi='$kode_lokasi' order by periode desc");

                                        while ($row = $res->FetchNextObject(false)){
                                            echo " <option value=".$row->periode." >".ubah_periode($row->periode)."</option>";
                                        }
                                
                                        echo" </select>";
                                    }
                                        echo"
                                    </div>
                                </div>
                                <div class='row'>
                                    <a href='$fmain?hal=app/ypt/dashSiswaDet2.php&param=$jenis/$kunci/detPresensi/0/hadir/$kode_per' style='cursor:pointer;' >
                                        <div class='col-lg-3 col-xs-6' style='padding-right:7px'>
                                            <div class='small-box bg-green' style='border-radius: 15px;'>
                                            <table class='table no-border' width='100%'>
                                                <tr>
                                                <td>
                                                <div class='img'>
                                                    <img src='$imgH' style='max-width:70px;'>   
                                                </div>
                                                </td>
                                                <td>
                                                <div class='inner' style='text-align:center'>
                                                    <p style='margin-bottom:0px'>Hadir</p>
                                                    <h3 style='font-size: 50px;margin-bottom: 0px;    font-weight: normal;'>$rowP->hadir</h3>
                                                </div>
                                                </td>
                                                </tr>
                                            </table>
                                            </div>
                                        </div>
                                    </a>
                                    <a href='$fmain?hal=app/ypt/dashSiswaDet2.php&param=$jenis/$kunci/detPresensi/0/alpa/$kode_per' style='cursor:pointer;' >
                                        <div class='col-lg-3 col-xs-6' style='padding-left:7px;'>
                                            <div class='small-box bg-red' style='border-radius: 15px;'>
                                            <table class='table no-border' width='100%'>
                                                <tr>
                                                <td>
                                                <div class='img'>
                                                    <img src='$imgA' style='max-width:70px;'>   
                                                </div>
                                                </td>
                                                <td>
                                                <div class='inner'  style='text-align:center'>
                                                    <p style='margin-bottom:0px'>Alpha</p>
                                                    <h3 style='font-size: 50px;margin-bottom: 0px;    font-weight: normal;'>$rowP->alpha</h3>
                                                </div>
                                                </td>
                                                </tr>
                                            </table>
                                            </div>
                                        </div>
                                    </a>
                                    <a href='$fmain?hal=app/ypt/dashSiswaDet2.php&param=$jenis/$kunci/detPresensi/0/sakit/$kode_per' style='cursor:pointer;' >
                                        <div class='col-lg-3 col-xs-6' style='padding-right:7px'>
                                            <div class='small-box bg-yellow' style='border-radius: 15px;'>
                                            <table class='table no-border' width='100%'>
                                                <tr>
                                                <td>
                                                <div class='img'>
                                                    <img src='$imgS' style='max-width:70px;'>   
                                                </div>
                                                </td>
                                                <td>
                                                <div class='inner'  style='text-align:center'>
                                                    <p style='margin-bottom:0px'>Sakit</p>
                                                    <h3 style='font-size: 50px;margin-bottom: 0px;    font-weight: normal;'>$rowP->sakit</h3>
                                                </div>
                                                </td>
                                                </tr>
                                            </table>
                                            </div>
                                        </div>
                                    </a>
                                    <a href='$fmain?hal=app/ypt/dashSiswaDet2.php&param=$jenis/$kunci/detPresensi/0/izin/$kode_per' style='cursor:pointer;' >
                                        <div class='col-lg-3 col-xs-6' style='padding-left:7px;'>
                                            <div class='small-box bg-blue' style='border-radius: 15px;'>
                                            <table class='table no-border' width='100%'>
                                                <tr>
                                                <td>
                                                <div class='img'>
                                                    <img src='$imgI' style='max-width:70px;'>   
                                                </div>
                                                </td>
                                                <td>
                                                <div class='inner'  style='text-align:center'>
                                                    <p style='margin-bottom:0px'>Izin</p>
                                                    <h3 style='font-size: 50px;margin-bottom: 0px;    font-weight: normal;'>$rowP->izin</h3>
                                                </div>
                                                </td>
                                                </tr>
                                            </table>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <!-- /.box-body -->
                            <div class='box-footer text-black' style='border-top:0;padding-top:0px'>
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
                                        $sql="select convert(varchar,tanggal,103) as tgl,status from sis_presensi where kode_lokasi='$kode_lokasi' and kode_pp='$kode_pp' and nis='$nik2' and substring(convert(varchar,tanggal,112),1,6)='$kode_per' ";

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
                                <!-- /.row -->
                            </div>
                            </div>
                            <!-- /.box -->
                        </div>
                    </div>
                ";
                // echo"<style>
                // .vertical-alignment-helper {
                //     display:table;
                //     height: 100%;
                //     width: 100%;
                //     pointer-events:none; /* This makes sure that we can still click outside of the modal to close it */
                // }
                // .vertical-align-center {
                //     /* To center vertically */
                //     display: table-cell;
                //     vertical-align: middle;
                //     pointer-events:none;
                // }
                // .modal-content {
                //     /* Bootstrap sets the size of the modal in the modal-dialog class, we need to inherit it */
                //     width:inherit;
                //     max-width:inherit; /* For Bootstrap 4 - to avoid the modal window stretching full width */
                //     height:inherit;
                //     /* To center horizontally */
                //     margin: 0 auto;
                //     pointer-events: all;
                // }
                // </style>";

                echo"<div class='modal' id='modal-periode' tabindex='-1' role='dialog'>
                    
                <div class=''>
                    <div class='modal-dialog modal-sm ' role='document'>
                        <div class='modal-content' style='border-radius:10px'>
                           
                                <div class='modal-header' style='border-bottom:0'>
                                    <a type='button' data-dismiss='modal' id='close-list' style='color:black;cursor:pointer'><h5 class='modal-title'> <i class='fa fa-angle-left fa-lg'></i> &nbsp;Pilih Periode</h5></a>
                                    
                                </div>
                                <div class='modal-body' style='padding-left: 1px;
                                padding-right: 1px;'>
                                    <ul class='list-group'>";
                                        $res = execute("select distinct substring(convert(varchar,tanggal,112),1,6) as periode from sis_presensi where kode_lokasi='$kode_lokasi' order by periode desc");
                                        
                                        while ($row = $res->FetchNextObject(false)){
                                        echo"
                                        <li class='list-group-item' style='border:0;border-top:0;    border-bottom: 1px solid #80808047;'>
                                            <div hidden class='isi'>".ubah_periode($row->periode)."</div>
                                            <span>".ubah_periode($row->periode)."</span>
                                            <span class='pull-right'><i class='fa fa-angle-right fa-lg'></i></span>
                                        </li>";
                                        }
                                    
                                    
                                echo"
                                    </ul>   
                                </div>
                        </div>
                    </div>
                </div>
                </diV>";
                echo"<script>

                function ubah_periode3(periode)
                {
                  var tmpx=periode.split(' ');
                  var bulan=tmpx[0];
                  var tahun=tmpx[1];
                  switch (bulan) 
                  {
                    case 'Januari':
                      tmp='01';
                      break;
                    case 'Februari':
                      tmp='02';
                      break;
                    case 'Maret':
                      tmp='03';
                      break;
                    case 'April':
                      tmp='04';
                      break;
                    case 'Mei':
                      tmp='05';
                      break;
                    case 'Juni':
                      tmp='06';
                      break;
                    case 'Juli':
                      tmp='07';
                      break;
                    case 'Agustus':
                      tmp='08';
                      break;  
                    case 'September':
                      tmp='09';
                      break;  
                    case 'Oktober':
                      tmp='10';
                      break;  
                    case 'November':
                      tmp='11';
                      break;  
                    case 'Desember':
                      tmp='12';
                      break;  
                    
                  }
                  return tahun+tmp;
                }

                $('#inp-per').focus(function(){
                    $('#modal-periode').modal('show');
                });

                $('.list-group li').on('click', function(){
                    $('.list-group li div.isi').removeClass('selected');
                    $(this).find('div.isi').addClass('selected');
                    var isi=$('.selected').text();
                    $('#inp-per').val(isi);
                    $('#modal-periode').modal('hide');
                    var per = ubah_periode3(isi);
                    window.location.href='$fmain?hal=app/ypt/dashSiswaDet.php&param=$jenis/$kunci/'+per;
                });
                
                </script>";
                        break;
                        case "nil" :
                        echo "
                        <div class='row'>
                            <div class='col-md-12'>
                                <!-- Custom Tabs -->
                                <div class='alert alert-danger alert-dismissible' style='margin-bottom:0px;padding-left: 10px;padding-top: 0px;    background-color: white !important;
                                color: #dd4b39 !important;border:1px solid white;'>
                                    <h3 style='font-size:25px'>Penilaian Siswa</h3>
                                    Tahun Ajaran 2018/2019
                                </div>

                                <style>
                                .nav-tabs-custom > .nav-tabs > li {
                                    
                                    width: 25%;
                                    max-width:90px;
                                    text-align: center;
                                    border-bottom:3px solid white;

                                }

                                .nav-tabs-custom > .nav-tabs > li.active {
                                    border-top:3px solid white;
                                }
                                .nav-tabs-custom > .nav-tabs > li.active > a {
                                    border:0px solid white;
                                    border-bottom:3px solid #dd4b39;
                                }
                                .nav-tabs-custom > .nav-tabs > li.active > a:hover {
                                    border:0px solid white;
                                    border-bottom:3px solid #dd4b39;
                                
                                }
                                </style>
                                <div class='nav-tabs-custom' style='box-shadow: 0 0 0 white;'>
                                    <ul class='nav nav-tabs'>
                                    ";
                                    // $sqltab="select distinct kode_jenis from sis_jenisnilai where kode_lokasi='$kode_lokasi' and kode_pp='$kode_pp' and kode_jenis not in ('PRM','TGS') ";

                                    $sqltab="select 'UHR' as kode_jenis,'1' as kode
                                             UNION
                                             select 'UTS' as kode_jenis,'2' as kode
                                             UNION 
                                             select 'UAS' as kode_jenis,'3' as kode 
                                             order by kode asc";

                                    
                                    // echo $sqltab;

                                    $rstab = execute($sqltab); 
                                    $i=0; 
                                    while ($rowt = $rstab->FetchNextObject($toupper=false)){
                                    
                                        if($i == 0){
                                            echo"
                                            <li class='active'><a href='#tab_$rowt->kode_jenis' data-toggle='tab'>$rowt->kode_jenis</a></li>";
                                        }else{
                                            echo"
                                            <li><a href='#tab_$rowt->kode_jenis' data-toggle='tab'>$rowt->kode_jenis</a></li>";
                                        }
                                   
                                        $i++;
                                    }
                                    echo"
                                    </ul>
                                    <div class='tab-content'>";

                                    $sqltabpane="select 'UHR' as kode_jenis,'1' as kode
                                    UNION
                                    select 'UTS' as kode_jenis,'2' as kode
                                    UNION 
                                    select 'UAS' as kode_jenis,'3' as kode 
                                    order by kode asc";

                                    
                                    // echo $sqltabpane;

                                    $rstabpane = execute($sqltabpane); 
                                    $i=0; 
                                    while ($rowtp = $rstabpane->FetchNextObject($toupper=false)){
                                    
                                        if($i == 0){
                                             $class="active";
                                        }else{
                                             $class=" ";
                                        }

                                        echo"
                                        <div class='tab-pane $class' id='tab_$rowtp->kode_jenis'>";

                                        $sql="select a.kode_ta,a.kode_kelas,a.kode_jenis,a.kode_matpel, b.nilai,c.nama as nama_matpel 
                                        from sis_nilai_m a
                                        inner join sis_nilai b on a.no_bukti=b.no_bukti and a.kode_lokasi=b.kode_lokasi and a.kode_pp=b.kode_pp
                                        inner join sis_matpel c on a.kode_matpel=c.kode_matpel and a.kode_lokasi=c.kode_lokasi and a.kode_pp=c.kode_pp
                                        where a.kode_lokasi='$kode_lokasi' and a.kode_pp='$kode_pp' and b.nis='$nik2' and a.kode_jenis='$rowtp->kode_jenis'";

                                        
                                        // echo $sql;

                                        $rs = execute($sql);  
                                        while ($row = $rs->FetchNextObject($toupper=false)){

                                            if ($row->kode_matpel == "BIN"){
                                                $color = "#1aa1d9";
                                            } else if($row->kode_matpel == "ING") {
                                                $color = "#0add5e";
                                            } else {
                                                $color = "#9c9a97";
                                            }
                                        echo"
                                            <div class='box-footer box-comments' style='box-shadow: 1px 1px 2px 1px rgba(0,0,0,0.1);height: 50px;margin-bottom: 10px;border-left: 7px solid $color;border-radius: 10px;'>
                                                <div class='box-comment'>
                                                    <div class='comment-text' style='margin-left: 10px'>
                                                        <span class='username' style='color:$color;font-size:20px'>
                                                        $row->nama_matpel
                                                        <span class='text-muted pull-right' style='font-size:20px'>$row->nilai</span>
                                                        </span><!-- /.username -->
                                                    </div>
                                                </div>
                                            </div>
                                            ";
                                        }
                                        echo"
                                        </div>
                                        <!-- /.tab-pane -->";

                                        $i++;
                                    }
                                    
                                  echo"
                                    </div>
                                    <!-- /.tab-content -->
                                </div>
                                <!-- nav-tabs-custom -->
                            </div>
                            <!-- /.col -->
                        </div>
                        ";
                        break;
                        case "kld" :
                        echo "
                        <div class='row'>
                            <div class='col-md-12'>
                                <!-- Custom Tabs -->
                                <div class='alert alert-danger alert-dismissible' style='margin-bottom:0px;padding-left: 10px;padding-top: 0px;    background-color: white !important;
                                color: #dd4b39 !important;border:1px solid white;'>
                                    <h3 style='font-size:25px'>Jadwal Pelajaran</h3>
                                    Tahun Ajaran 2018/2019
                                </div>";
                       
                                $root=$_SERVER["DOCUMENT_ROOT"];
                                $root_app="http://".$_SERVER['SERVER_NAME']."/web/app/belajar";
                                $root_ser="http://".$_SERVER['SERVER_NAME']."/web/server/belajar";
                                $folder_css=$root2."/web/css";
                                $folder_js=$root2."/web/js";
                                $folder_img=$root2."/web/img";
                        ?>
                            <link rel="stylesheet" href="<?=$folder_css?>/jquery.scrolling-tabs.css">
                            <style>
                                .scrtabs-tabs-fixed-container{
                                    height: 40px;
                                    width:100% !important;
                                }
                                .scrtabs-allow-scrollbar .scrtabs-tabs-fixed-container{
                                    -webkit-overflow-scrolling: touch;
                                }
                                .scrtabs-tab-scroll-arrow-left{
                                    visibility: hidden;
                                    display: none !important;
                                }
                                .scrtabs-tab-scroll-arrow-right{
                                    visibility: hidden;
                                    display: none !important;
                                }

                                .scrtabs-tabs-fixed-container ul.nav-tabs > li {
                                    margin-bottom:10px;
                                }
                                .nav-tabs > li {
                                    float:none !important;
                                    display:inline-block !important;
                                    zoom:1;
                                    background: white;
                                    border-radius: 15px;
                                    border: 1px solid #8080802e;
                                }
                                .nav-tabs > .nav-tabs > li {
                                    width:30% !important;
                                    max-width:95px !important;
                                    text-align:center !important;
                                    border:1px solid #cccbcbe3 !important;
                                    border-radius:20px !important;
                                    margin:3px !important;
                                }

                                .nav-tabs > li.active {
                                    border-top:0px !important;
                                    border:2px solid #dd4b39 !important;
                                    background: white;
                                    border-radius: 15px;
                                    
                                }
                                .nav-tabs > li> a {
                                    padding:6px !important; 
                                    color: black; 
                                    padding: 3px 20px !important; 
                                    
                                }
                                .nav-tabs > li.active > a {
                                    border:0px solid #dd4b39 !important;
                                    border-radius:20px !important;
                                    background : #dd4b390d !important;
                                    color:black !important;   
                                    padding: 3px 20px !important;                      
                                }
                                .nav-tabs > li.active > a:hover {
                                    border:0px solid #dd4b39 !important;
                                    border-radius:20px !important;
                                    background : #dd4b390d !important;
                                    color:black !important;
                                    
                                }
                                </style>

                        <?php
                                echo"
                                <div>
                                    <ul class='nav nav-tabs' role='tablist'>
                                        <li role='presentation' class='active'><a href='#tab_1' role='tab' data-toggle='tab'>Senin</a></li>
                                        <li role='presentation'><a href='#tab_2' role='tab' data-toggle='tab'>Selasa</a></li>
                                        <li role='presentation'><a href='#tab_3' role='tab' data-toggle='tab'>Rabu</a></li>
                                        <li role='presentation'><a href='#tab_4' role='tab' data-toggle='tab'>Kamis</a></li>
                                        <li role='presentation'><a href='#tab_5' role='tab' data-toggle='tab'>Jumat</a></li>
                                        <li role='presentation'><a href='#tab_6' role='tab' data-toggle='tab'>Sabtu</a></li>
                                        <li role='presentation' style='border:none'><a href='#tab_7' role='tab' style='color:white' data-toggle='tab'>Tab Number 7</a></li>
                                    </ul> 
                                    <div class='tab-content'  style='margin-top:20px'>
                                        <div class='tab-pane active' id='tab_1'>";

                                        $sql="select a.kode_slot, c.nama as nama_slot,a.kode_kelas, a.kode_hari, a.kode_matpel,d.nama as nama_matpel, b.nis,a.nik,e.nama as nama_guru from sis_jadwal a
                                        inner join sis_siswa b on a.kode_kelas=b.kode_kelas and a.kode_pp=b.kode_pp and a.kode_lokasi=b.kode_lokasi
                                        inner join sis_slot c on a.kode_slot=c.kode_slot and a.kode_pp=c.kode_pp and a.kode_lokasi=c.kode_lokasi
                                        inner join sis_matpel d on a.kode_matpel=d.kode_matpel and a.kode_pp=d.kode_pp and a.kode_lokasi=d.kode_lokasi
                                        inner join karyawan e on a.nik=e.nik and a.kode_pp=e.kode_pp and a.kode_lokasi=e.kode_lokasi
                                        where b.nis='$nik2' and kode_hari='1' order by kode_slot,kode_hari";

                                        
                                        // echo $sql;

                                        $rs = execute($sql);  
                                        while ($row = $rs->FetchNextObject($toupper=false)){

                                        if ($row->kode_matpel == "BIN"){
                                            $color = "#1aa1d9";
                                        } else if($row->kode_matpel == "ING") {
                                            $color = "#0add5e";
                                        } else {
                                            $color = "#9c9a97";
                                        }
                                        $x=explode(" ",$row->nama_slot);
                                        $txt=explode("[",$x[2]);
                                        $txt2=explode("]",$x[4]);
                                        $jam1=$txt[1];
                                        $jam2=$txt2[0];
                                        echo"
                                            <div class='box-footer box-comments' style='box-shadow: 1px 1px 2px 1px rgba(0,0,0,0.1);height: 80px;margin-bottom: 10px;border-left: 7px solid $color;border-radius: 10px;'>
                                                <div class='box-comment'>
                                                    <div class='comment-text' style='margin-left: 10px'>
                                                        
                                                        <div class='col-xs-8' style='padding-left: 0px;padding-right: 0px;font-weight: 500;color: black;'>
                                                        <span style='font-size:18px;'>$row->nama_matpel</span> <br> 
                                                        <span style='font-size: 12px;color:grey'>$row->nama_guru</span>
                                                        </div>               
                                                        <div class='col-xs-4' style='padding-left: 0px;padding-right: 0px;text-align:right'>
                                                            <span style='font-size: 12px;color:grey'>".$x[0]." ".$x[1]."</span><br>
                                                            <span style='font-size:18px'>".$jam1.$x[3].$jam2."</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            ";
                                        }
                                        echo"
                                        </div>
                                        <!-- /.tab-pane -->";
                                    echo"
                                         <div class='tab-pane' role='tabpanel' id='tab_2'>";

                                        $sql2="select a.kode_slot, c.nama as nama_slot,a.kode_kelas, a.kode_hari, a.kode_matpel,d.nama as nama_matpel, b.nis,a.nik,e.nama as nama_guru from sis_jadwal a
                                        inner join sis_siswa b on a.kode_kelas=b.kode_kelas and a.kode_pp=b.kode_pp and a.kode_lokasi=b.kode_lokasi
                                        inner join sis_slot c on a.kode_slot=c.kode_slot and a.kode_pp=c.kode_pp and a.kode_lokasi=c.kode_lokasi
                                        inner join sis_matpel d on a.kode_matpel=d.kode_matpel and a.kode_pp=d.kode_pp and a.kode_lokasi=d.kode_lokasi
                                        inner join karyawan e on a.nik=e.nik and a.kode_pp=e.kode_pp and a.kode_lokasi=e.kode_lokasi
                                        where b.nis='$nik2' and kode_hari='2' order by kode_slot,kode_hari";

                                        $rs2 = execute($sql2);  
                                        while ($row2 = $rs2->FetchNextObject($toupper=false)){
                                        
                                            if ($row2->kode_matpel == "BIN"){
                                                $color = "#1aa1d9";
                                            } else if($row2->kode_matpel == "ING") {
                                                $color = "#0add5e";
                                            } else {
                                                $color = "#9c9a97";
                                            }
                                        
                                        $x=explode(" ",$row2->nama_slot);
                                        $txt=explode("[",$x[2]);
                                        $txt2=explode("]",$x[4]);
                                        $jam1=$txt[1];
                                        $jam2=$txt2[0];

                                        echo"
                                            <div class='box-footer box-comments' style='box-shadow: 1px 1px 2px 1px rgba(0,0,0,0.1);height: 80px;margin-bottom: 10px;border-left: 7px solid $color;border-radius: 10px;'>
                                                <div class='box-comment'>
                                                    <div class='comment-text' style='margin-left: 10px'>
                                                        <div class='col-xs-8' style='padding-left: 0px;padding-right: 0px;font-weight: 500;color: black;'>
                                                        <span style='font-size:18px;'>$row2->nama_matpel</span> <br> 
                                                        <span style='font-size: 12px;color:grey'>$row2->nama_guru</span>
                                                        </div>               
                                                        <div class='col-xs-4' style='padding-left: 0px;padding-right: 0px;text-align:right'>
                                                            <span style='font-size: 12px;color:grey'>".$x[0]." ".$x[1]."</span><br>
                                                            <span style='font-size:18px'>".$jam1.$x[3].$jam2."</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            ";
                                        }

                                        echo"
                                        </div>
                                        <!-- /.tab-pane -->
                                        <div class='tab-pane' role='tabpanel' id='tab_3'>";
                                        $sql3="select a.kode_slot, c.nama as nama_slot,a.kode_kelas, a.kode_hari, a.kode_matpel,d.nama as nama_matpel, b.nis,a.nik,e.nama as nama_guru from sis_jadwal a
                                        inner join sis_siswa b on a.kode_kelas=b.kode_kelas and a.kode_pp=b.kode_pp and a.kode_lokasi=b.kode_lokasi
                                        inner join sis_slot c on a.kode_slot=c.kode_slot and a.kode_pp=c.kode_pp and a.kode_lokasi=c.kode_lokasi
                                        inner join sis_matpel d on a.kode_matpel=d.kode_matpel and a.kode_pp=d.kode_pp and a.kode_lokasi=d.kode_lokasi
                                        inner join karyawan e on a.nik=e.nik and a.kode_pp=e.kode_pp and a.kode_lokasi=e.kode_lokasi
                                        where b.nis='$nik2' and kode_hari='3' order by kode_slot,kode_hari";

                                        $rs3 = execute($sql3);  
                                        while ($row3 = $rs3->FetchNextObject($toupper=false)){
                                            if ($row3->kode_matpel == "BIN"){
                                                $color = "#1aa1d9";
                                            } else if($row3->kode_matpel == "ING") {
                                                $color = "#0add5e";
                                            } else {
                                                $color = "#9c9a97";
                                            }
                                            
                                            
                                            $x=explode(" ",$row3->nama_slot);
                                            $txt=explode("[",$x[2]);
                                            $txt2=explode("]",$x[4]);
                                            $jam1=$txt[1];
                                            $jam2=$txt2[0];

                                        echo"
                                            <div class='box-footer box-comments' style='box-shadow: 1px 1px 2px 1px rgba(0,0,0,0.1);height: 80px;margin-bottom: 10px;border-left: 7px solid $color;border-radius: 10px;'>
                                                <div class='box-comment'>
                                                    <div class='comment-text' style='margin-left: 10px'>
                                                        <div class='col-xs-8' style='padding-left: 0px;padding-right: 0px;font-weight: 500;color: black;'>
                                                        <span style='font-size:18px;'>$row3->nama_matpel</span> <br> 
                                                        <span style='font-size: 12px;color:grey'>$row3->nama_guru</span>
                                                        </div>               
                                                        <div class='col-xs-4' style='padding-left: 0px;padding-right: 0px;text-align:right'>
                                                            <span style='font-size: 12px;color:grey'>".$x[0]." ".$x[1]."</span><br>
                                                            <span style='font-size:18px'>".$jam1.$x[3].$jam2."</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            ";
                                        }
                                        echo"
                                        </div>
                                        <!-- /.tab-pane -->
                                        <div class='tab-pane' role='tabpanel' id='tab_4'>";

                                        $sql4="select a.kode_slot, c.nama as nama_slot,a.kode_kelas, a.kode_hari, a.kode_matpel,d.nama as nama_matpel, b.nis,a.nik,e.nama as nama_guru from sis_jadwal a
                                        inner join sis_siswa b on a.kode_kelas=b.kode_kelas and a.kode_pp=b.kode_pp and a.kode_lokasi=b.kode_lokasi
                                        inner join sis_slot c on a.kode_slot=c.kode_slot and a.kode_pp=c.kode_pp and a.kode_lokasi=c.kode_lokasi
                                        inner join sis_matpel d on a.kode_matpel=d.kode_matpel and a.kode_pp=d.kode_pp and a.kode_lokasi=d.kode_lokasi
                                        inner join karyawan e on a.nik=e.nik and a.kode_pp=e.kode_pp and a.kode_lokasi=e.kode_lokasi
                                        where b.nis='$nik2' and kode_hari='4' order by kode_slot,kode_hari";

                                        $rs4 = execute($sql4);  
                                        while ($row4 = $rs4->FetchNextObject($toupper=false)){
                                            if ($row4->kode_matpel == "BIN"){
                                                $color = "#1aa1d9";
                                            } else if($row4->kode_matpel == "ING") {
                                                $color = "#0add5e";
                                            } else {
                                                $color = "#9c9a97";
                                            }
                                        
                                            $x=explode(" ",$row4->nama_slot);
                                            $txt=explode("[",$x[2]);
                                            $txt2=explode("]",$x[4]);
                                            $jam1=$txt[1];
                                            $jam2=$txt2[0];
                                        echo"
                                            <div class='box-footer box-comments' style='box-shadow: 1px 1px 2px 1px rgba(0,0,0,0.1);height: 80px;margin-bottom: 10px;border-left: 7px solid $color;border-radius: 10px;'>
                                                <div class='box-comment'>
                                                    <div class='comment-text' style='margin-left: 10px'>
                                                        <div class='col-xs-8' style='padding-left: 0px;padding-right: 0px;font-weight: 500;color: black;'>
                                                        <span style='font-size:18px;'>$row4->nama_matpel</span> <br> 
                                                        <span style='font-size: 12px;color:grey'>$row4->nama_guru</span>
                                                        </div>               
                                                        <div class='col-xs-4' style='padding-left: 0px;padding-right: 0px;text-align:right'>
                                                            <span style='font-size: 12px;color:grey'>".$x[0]." ".$x[1]."</span><br>
                                                            <span style='font-size:18px'>".$jam1.$x[3].$jam2."</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            ";
                                        }
                                        echo"
                                        </div>
                                        <!-- /.tab-pane -->
                                        <div class='tab-pane' role='tabpanel' id='tab_5'>";
                                        $sql5="select a.kode_slot, c.nama as nama_slot,a.kode_kelas, a.kode_hari, a.kode_matpel,d.nama as nama_matpel, b.nis,a.nik,e.nama as nama_guru from sis_jadwal a
                                        inner join sis_siswa b on a.kode_kelas=b.kode_kelas and a.kode_pp=b.kode_pp and a.kode_lokasi=b.kode_lokasi
                                        inner join sis_slot c on a.kode_slot=c.kode_slot and a.kode_pp=c.kode_pp and a.kode_lokasi=c.kode_lokasi
                                        inner join sis_matpel d on a.kode_matpel=d.kode_matpel and a.kode_pp=d.kode_pp and a.kode_lokasi=d.kode_lokasi
                                        inner join karyawan e on a.nik=e.nik and a.kode_pp=e.kode_pp and a.kode_lokasi=e.kode_lokasi
                                        where b.nis='$nik2' and kode_hari='5' order by kode_slot,kode_hari";

                                        $rs5 = execute($sql5);  
                                        while ($row5 = $rs5->FetchNextObject($toupper=false)){
                                            if ($row5->kode_matpel == "BIN"){
                                                $color = "#1aa1d9";
                                            } else if($row5->kode_matpel == "ING") {
                                                $color = "#0add5e";
                                            } else {
                                                $color = "#9c9a97";
                                            }
                                            
                                        $x=explode(" ",$row5->nama_slot);
                                        $txt=explode("[",$x[2]);
                                        $txt2=explode("]",$x[4]);
                                        $jam1=$txt[1];
                                        $jam2=$txt2[0];
                                        echo"
                                            <div class='box-footer box-comments' style='box-shadow: 1px 1px 2px 1px rgba(0,0,0,0.1);height: 80px;margin-bottom: 10px;border-left: 7px solid $color;border-radius: 10px;'>
                                                <div class='box-comment'>
                                                    <div class='comment-text' style='margin-left: 10px'>
                                                        <div class='col-xs-8' style='padding-left: 0px;padding-right: 0px;font-weight: 500;color: black;'>
                                                        <span style='font-size:18px;'>$row5->nama_matpel</span> <br> 
                                                        <span style='font-size: 12px;color:grey'>$row5->nama_guru</span>
                                                        </div>               
                                                        <div class='col-xs-4' style='padding-left: 0px;padding-right: 0px;text-align:right'>
                                                            <span style='font-size: 12px;color:grey'>".$x[0]." ".$x[1]."</span><br>
                                                            <span style='font-size:18px'>".$jam1.$x[3].$jam2."</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            ";
                                        }
                                        echo"
                                        </div>
                                        <!-- /.tab-pane -->
                                        <div class='tab-pane' role='tabpanel' id='tab_6'>";
                                        $sql6="select a.kode_slot, c.nama as nama_slot,a.kode_kelas, a.kode_hari, a.kode_matpel,d.nama as nama_matpel, b.nis,a.nik,e.nama as nama_guru from sis_jadwal a
                                        inner join sis_siswa b on a.kode_kelas=b.kode_kelas and a.kode_pp=b.kode_pp and a.kode_lokasi=b.kode_lokasi
                                        inner join sis_slot c on a.kode_slot=c.kode_slot and a.kode_pp=c.kode_pp and a.kode_lokasi=c.kode_lokasi
                                        inner join sis_matpel d on a.kode_matpel=d.kode_matpel and a.kode_pp=d.kode_pp and a.kode_lokasi=d.kode_lokasi
                                        inner join karyawan e on a.nik=e.nik and a.kode_pp=e.kode_pp and a.kode_lokasi=e.kode_lokasi
                                        where b.nis='$nik2' and kode_hari='6' order by kode_slot,kode_hari";

                                        $rs6 = execute($sql6);  
                                        while ($row6 = $rs6->FetchNextObject($toupper=false)){

                                            if ($row6->kode_matpel == "BIN"){
                                                $color = "#1aa1d9";
                                            } else if($row6->kode_matpel == "ING") {
                                                $color = "#0add5e";
                                            } else {
                                                $color = "#9c9a97";
                                            }
                                        $x=explode(" ",$row6->nama_slot);
                                        $txt=explode("[",$x[2]);
                                        $txt2=explode("]",$x[4]);
                                        $jam1=$txt[1];
                                        $jam2=$txt2[0];
                                        echo"
                                            <div class='box-footer box-comments' style='box-shadow: 1px 1px 2px 1px rgba(0,0,0,0.1);height: 80px;margin-bottom: 10px;border-left: 7px solid $color;border-radius: 10px;'>
                                                <div class='box-comment'>
                                                    <div class='comment-text' style='margin-left: 10px'>
                                                        <div class='col-xs-8' style='padding-left: 0px;padding-right: 0px;font-weight: 500;color: black;'>
                                                        <span style='font-size:18px;'>$row6->nama_matpel</span> <br> 
                                                        <span style='font-size: 12px;color:grey'>$row6->nama_guru</span>
                                                        </div>               
                                                        <div class='col-xs-4' style='padding-left: 0px;padding-right: 0px;text-align:right'>
                                                            <span style='font-size: 12px;color:grey'>".$x[0]." ".$x[1]."</span><br>
                                                            <span style='font-size:18px'>".$jam1.$x[3].$jam2."</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            ";
                                        }
                                        echo"                                        
                                        </div>
                                        <!-- /.tab-pane -->
                                    </div>
                                    <!-- /.tab-content -->
                                </div>
                                <!-- nav-tabs-custom -->
                            </div>
                            <!-- /.col -->
                        </div>
                        ";
                        ?>
                        <script src="<?=$folder_js?>/jquery.scrolling-tabs.js"></script>

                        <script>
                        ;(function() {
                            'use strict';


                            $(activate);


                            function activate() {

                                $('.nav-tabs').scrollingTabs({
                                    enableSwiping: true,
                                    reverseScroll: true  
                                })
                                .on('ready.scrtabs', function() {
                                    $('.tab-content').show();
                                });

                            }

                            $('.scrtabs-tabs-fixed-container').addClass('col-md-12');

                        }());

                        </script>
                        <?php

                        break;
                        case "news" :

                        $sql="select top 10 no_konten,convert(varchar,tanggal,105) as tgl,judul,file_dok,tanggal from sis_konten where kode_lokasi = '$kode_lokasi' and kode_pp ='$kode_pp'  order by tanggal desc ";

                        $rs = execute($sql);  
                        while ($row = $rs->FetchNextObject($toupper=false)){
                            $foto2 = $path . "server/media/".$row->file_dok;
                     echo "
                     <a style='cursor:pointer;color:black' href='$fmain?hal=app/ypt/dashSiswaNews.php&param=$row->no_konten/newsdet'>
                        <div class='col-md-12 col-md-12'>
                            <!-- Widget: user widget style 1 -->
                            <div class='box box-widget widget-user'>
                                <!-- Add the bg color to the header using any of the bg-* classes -->
                                <div class='widget-user-header bg-black' style='background: url($foto2) center center;'>
                                    <h3 class='widget-user-username'></h3>
                                    <h5 class='widget-user-desc'></h5>
                                </div>
                                
                                <div class='box-footer'>
                                    <h5 class='description-header'>$row->judul</h5>
                                    <span class='description-text'>$row->tgl</span>  
                                </div>
                                <!-- /.widget-user -->
                            </div>
                        </div></a> ";
            
                        }
                        break;
                        case "prestasi" :
                        echo "
                        <div class='row'>
                            <div class='col-md-12'>
                                <!-- Custom Tabs -->
                                <div class='alert alert-danger alert-dismissible' style='margin-bottom:0px;padding-left: 10px;padding-top: 0px;    background-color: white !important;
                                color: #dd4b39 !important;border:1px solid white;'>
                                    <h3 style='font-size:25px'>Prestasi Siswa</h3>
                                    Tahun Ajaran 2018/2019
                                </div>

                                <style>.nav-tabs-custom > .nav-tabs > li {
                                    
                                    width: 25%;
                                    max-width:90px;
                                    text-align: center;

                                }

                                .nav-tabs-custom > .nav-tabs > li.active {
                                    border-top:3px solid white;
                                   
                                }
                                .nav-tabs-custom > .nav-tabs > li.active > a {
                                    border:0px solid white;
                                    border-bottom:3px solid #dd4b39;
                                }
                                .nav-tabs-custom > .nav-tabs > li.active > a:hover {
                                    border:0px solid white;
                                    border-bottom:3px solid #dd4b39;
                                
                                }
                                </style>
                                <div class='nav-tabs-custom' style='box-shadow: 0 0 0 white;'>
                                    <ul class='nav nav-tabs'>
                                    ";
                                    $sqltab="
                                    select kode_kategori,nama 
                                    from sis_prestasi_kategori
                                    where kode_lokasi='$kode_lokasi' and kode_pp='$kode_pp' ";

                                    $rstab = execute($sqltab); 
                                    $i=0; 
                                    while ($rowt = $rstab->FetchNextObject($toupper=false)){
                                    
                                        if($i == 0){
                                            echo"
                                            <li class='active'><a href='#tab_$rowt->kode_kategori' data-toggle='tab'>$rowt->nama</a></li>";
                                        }else{
                                            echo"
                                            <li><a href='#tab_$rowt->kode_kategori' data-toggle='tab'>$rowt->nama</a></li>";
                                        }
                                   
                                        $i++;
                                    }
                                    echo"
                                    </ul>
                                    <div class='tab-content'>";

                                    $sqltabpane="
                                    select kode_kategori,nama 
                                    from sis_prestasi_kategori
                                    where kode_lokasi='$kode_lokasi' and kode_pp='$kode_pp' ";

                                    $rstabpane = execute($sqltabpane); 
                                    $i=0; 
                                    while ($rowtp = $rstabpane->FetchNextObject($toupper=false)){
                                    
                                        if($i == 0){
                                             $class="active";
                                        }else{
                                             $class=" ";
                                        }

                                        echo"
                                        <div class='tab-pane $class' id='tab_$rowtp->kode_kategori'>";

                                        $sql="
                                        select a.no_bukti, convert(varchar,a.tanggal,103) as tgl,a.keterangan,a.tempat,a.jenis from sis_prestasi a
                                        where a.kode_lokasi='$kode_lokasi' and a.kode_pp='$kode_pp' and a.nis='$nik2' and a.kode_kategori='$rowtp->kode_kategori' ";

                                        $rs = execute($sql);  
                                        while ($row = $rs->FetchNextObject($toupper=false)){

                                            $color = "#dd4b39;";
                                            
                                        echo"
                                            <div class='box-footer box-comments' style='box-shadow: 1px 1px 2px 1px rgba(0,0,0,0.1);height: 70px;margin-bottom: 10px;border-left: 7px solid $color;border-radius: 10px;'>
                                                <div class='box-comment'>
                                                    <div class='comment-text' style='margin-left: 10px'>
                                                        <span class='username' style='color:black;font-size:16px'>
                                                        PRESTASI $row->jenis ( $row->tempat )
                                                        <span class='text-muted pull-right' style='font-size:11px'>$row->tgl</span>
                                                        </span><!-- /.username -->
                                                        $row->keterangan
                                                    </div>
                                                </div>
                                            </div>
                                            ";
                                        }
                                        echo"
                                        </div>
                                        <!-- /.tab-pane -->";

                                        $i++;
                                    }
                                    
                                  echo"
                                    </div>
                                    <!-- /.tab-content -->
                                </div>
                                <!-- nav-tabs-custom -->
                            </div>
                            <!-- /.col -->
                        </div>
                        ";
                        break;
                        case "eskul" :
                        echo "
                        <div class='row'>
                            <div class='col-md-12'>
                                <!-- Custom Tabs -->
                                <div class='alert alert-danger alert-dismissible' style='margin-bottom:0px;padding-left: 10px;padding-top: 0px;    background-color: white !important;
                                color: #dd4b39 !important;border:1px solid white;'>
                                    <h3 style='font-size:25px'>Ekstrakulikuler Siswa</h3>
                                    Tahun Ajaran 2018/2019
                                </div>

                                <style>
                                .nav-tabs-custom > .nav-tabs > li {
                                    
                                    width: 25%;
                                    max-width:90px;
                                    text-align: center;

                                }

                                .nav-tabs-custom > .nav-tabs > li.active {
                                    border-top:3px solid white;
                                }
                                .nav-tabs-custom > .nav-tabs > li.active > a {
                                    border:0px solid white;
                                    border-bottom:3px solid #dd4b39;
                                }
                                .nav-tabs-custom > .nav-tabs > li.active > a:hover {
                                    border:0px solid white;
                                    border-bottom:3px solid #dd4b39;
                                
                                }
                                </style>
                                <div class='nav-tabs-custom' style='box-shadow: 0 0 0 white;'>
                                    <ul class='nav nav-tabs'>
                                    ";
                                    $sqltab="
                                    select kode_jenis, nama from sis_ekskul_jenis
                                    where kode_lokasi='$kode_lokasi' and kode_pp='$kode_pp' ";

                                    $rstab = execute($sqltab); 
                                    $i=0; 
                                    while ($rowt = $rstab->FetchNextObject($toupper=false)){
                                    
                                        if($i == 0){
                                            echo"
                                            <li class='active'><a href='#tab_$rowt->kode_jenis' data-toggle='tab'>$rowt->nama</a></li>";
                                        }else{
                                            echo"
                                            <li><a href='#tab_$rowt->kode_jenis' data-toggle='tab'>$rowt->nama</a></li>";
                                        }
                                   
                                        $i++;
                                    }
                                    echo"
                                    </ul>
                                    <div class='tab-content'>";

                                    $sqltabpane="
                                    select kode_jenis,nama 
                                    from sis_ekskul_jenis
                                    where kode_lokasi='$kode_lokasi' and kode_pp='$kode_pp' ";

                                    $rstabpane = execute($sqltabpane); 
                                    $i=0; 
                                    while ($rowtp = $rstabpane->FetchNextObject($toupper=false)){
                                    
                                        if($i == 0){
                                             $class="active";
                                        }else{
                                             $class=" ";
                                        }

                                        echo"
                                        <div class='tab-pane $class' id='tab_$rowtp->kode_jenis'>";

                                        $sql=" select convert(varchar,a.tgl_mulai,103) as tgl_mulai,convert(varchar,a.tgl_selesai,103) as tgl_selesai,a.keterangan,a.predikat from sis_ekskul a
                                        where a.kode_lokasi='$kode_lokasi' and a.kode_pp='$kode_pp' and a.nis='$nik2' and a.kode_jenis='$rowtp->kode_jenis' ";

                                        $rs = execute($sql);  
                                        while ($row = $rs->FetchNextObject($toupper=false)){

                                            $color = "#1aa1d9";
                                           
                                        echo"
                                            <div class='box-footer box-comments' style='box-shadow: 1px 1px 2px 1px rgba(0,0,0,0.1);height: 70px;margin-bottom: 10px;border-left: 7px solid $color;border-radius: 10px;'>
                                                <div class='box-comment'>
                                                    <div class='comment-text' style='margin-left: 10px'>
                                                        <span class='username' style='color:$color;font-size:16px'>
                                                        $row->keterangan
                                                        <span class='text-muted pull-right' style='font-size:16px'>Predikat : $row->predikat</span>
                                                        </span><!-- /.username -->
                                                        $row->tgl_mulai s.d $row->tgl_selesai
                                                    </div>
                                                </div>
                                            </div>
                                            ";
                                        }
                                        echo"
                                        </div>
                                        <!-- /.tab-pane -->";

                                        $i++;
                                    }
                                    
                                  echo"
                                    </div>
                                    <!-- /.tab-content -->
                                </div>
                                <!-- nav-tabs-custom -->
                            </div>
                            <!-- /.col -->
                        </div>
                        ";
                        break;
                        case "raport" :
                        echo "
                        <div class='row'>
                            <div class='col-md-12'>
                                <!-- Custom Tabs -->
                                <div class='alert alert-danger alert-dismissible' style='margin-bottom:0px;padding-left: 10px;padding-top: 0px;    background-color: white !important;
                                color: #dd4b39 !important;border:1px solid white;'>
                                    <h3 style='font-size:25px'>Raport Siswa</h3>
                                    
                                </div>

                                <div class='row' style='padding: 0px 13px;'>
                                    <div class='col-xs-6'>
                                        <label>Tahun Akademik </label>
                                    </div>
                                    <div class='col-xs-6'>";
                                    if($tmp[2] != ""){
                                        $kode_ta=$tmp[2]."/".$tmp[3];
                                    }else{
                                        $resTAa = execute("select kode_ta from sis_ta where kode_lokasi='$kode_lokasi' and kode_pp='$kode_pp' and flag_aktif='1' order by kode_ta ");
                                        $kode_ta=$resTAa->fields[0];
                                    }

                                    if($mobile ==true){
                                        echo" <input type='text' value='".$kode_ta."' class='form-control' id='inp-ta' placeholder='Pilih Tahun Akademik' style='border:0;border-bottom:1px solid  #8080806b'>";
                                    }else{
                                    echo"
                                        <select class='form-control input-sm selectize' id='dash_ta' style='margin-bottom:5px;'>
                                        <option value=''>Pilih Tahun Akademik</option>";
                                    
                                        echo " <option value=".$kode_ta." selected>".$kode_ta."</option>";
                                        
                                        $resTA = execute("select kode_ta from sis_ta where kode_lokasi='$kode_lokasi' and kode_pp='$kode_pp' order by kode_ta ");

                                        while ($row = $resTA->FetchNextObject(false)){
                                            echo " <option value=".$row->kode_ta.">".$row->kode_ta."</option>";
                                        }
                                
                                        echo"  
                                    </select>";
                                    }
                                    echo"
                                    </div>
                                </div>

                                <style>.nav-tabs-custom > .nav-tabs > li {
                                    
                                    width: 25%;
                                    max-width:90px;
                                    text-align: center;
                                    border-top:0px;
                                    border-top:3px solid white;

                                }

                                .nav-tabs-custom > .nav-tabs > li.active {
                                    border-top:3px solid white;
                                   
                                }
                                .nav-tabs-custom > .nav-tabs > li.active > a {
                                    border:0px solid white;
                                    border-bottom:3px solid #dd4b39;
                                }
                                .nav-tabs-custom > .nav-tabs > li.active > a:hover {
                                    border:0px solid white;
                                    border-bottom:3px solid #dd4b39;
                                
                                }
                                </style>
                                <div class='nav-tabs-custom' style='box-shadow: 0 0 0 white;'>
                                    <ul class='nav nav-tabs'>
                                        <li class='active'><a href='#tab_GANJIL' data-toggle='tab'>GANJIL</a></li>
                                        <li><a href='#tab_GENAP' data-toggle='tab'>GENAP</a></li>
                                    </ul>
                                    <div class='tab-content'>";
                                        echo"
                                         <div class='tab-pane active' id='tab_GANJIL'>
                                            <div>
                                                <table class='table no-border'>
                                                    <thead>
                                                        <th>Mata Pelajaran</th>
                                                        <th>KKM</th>
                                                        <th>Nilai</th>
                                                    </thead>
                                                    <tbody>";


                                        $sql="select b.kode_matpel,c.nama, isnull(b.nilai,0) as nilai,isnull(c.kkm,0) as kkm from sis_raport_m a
                                        inner join sis_raport_d b on a.no_bukti=b.no_bukti and a.kode_lokasi=b.kode_lokasi and a.kode_pp=b.kode_pp
                                        inner join sis_matpel c on b.kode_matpel=c.kode_matpel and b.kode_lokasi=c.kode_lokasi and b.kode_pp=c.kode_pp
                                        where a.kode_lokasi='$kode_lokasi' and a.kode_pp='$kode_pp' and a.nis='$nik2' and a.kode_sem='GANJIL' and a.kode_ta='$kode_ta' ";

                                        $rs = execute($sql);  
                                        while ($row = $rs->FetchNextObject($toupper=false)){

                                            $color = "#1aa1d9";
                                           
                                        echo"
                                            <tr>
                                                <td>$row->nama</td>
                                                <td>$row->kkm</td>
                                                <td>$row->nilai</td>
                                            </tr>
                                            ";
                                        }
                                       
                                        echo"
                                                </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <!-- /.tab-pane -->";
                                        echo"
                                        <div class='tab-pane' role='tabpanel' id='tab_GENAP'>
                                           <div>
                                               <table class='table no-border'>
                                                   <thead>
                                                       <th>Mata Pelajaran</th>
                                                       <th>KKM</th>
                                                       <th>Nilai</th>
                                                   </thead>
                                                   <tbody>";


                                       $sql="select b.kode_matpel,c.nama, isnull(b.nilai,0) as nilai,isnull(c.kkm,0) as kkm from sis_raport_m a
                                       inner join sis_raport_d b on a.no_bukti=b.no_bukti and a.kode_lokasi=b.kode_lokasi and a.kode_pp=b.kode_pp
                                       inner join sis_matpel c on b.kode_matpel=c.kode_matpel and b.kode_lokasi=c.kode_lokasi and b.kode_pp=c.kode_pp
                                       where a.kode_lokasi='$kode_lokasi' and a.kode_pp='$kode_pp' and a.nis='$nik2' and a.kode_sem='GENAP' and a.kode_ta='$kode_ta' ";

                                       $rs = execute($sql);  
                                       while ($row = $rs->FetchNextObject($toupper=false)){

                                           $color = "#1aa1d9";
                                          
                                       echo"
                                           <tr>
                                               <td>$row->nama</td>
                                               <td>$row->kkm</td>
                                               <td>$row->nilai</td>
                                           </tr>
                                           ";
                                       }
                                      
                                       echo"
                                               </tbody>
                                               </table>
                                           </div>
                                       </div>
                                       <!-- /.tab-pane -->";
                                  echo"
                                    </div>
                                    <!-- /.tab-content -->
                                </div>
                                <!-- nav-tabs-custom -->
                            </div>
                            <!-- /.col -->
                        </div>
                        ";

                        // echo"<style>
                        // .vertical-alignment-helper {
                        //     display:table;
                        //     height: 100%;
                        //     width: 100%;
                        //     pointer-events:none; /* This makes sure that we can still click outside of the modal to close it */
                        // }
                        // .vertical-align-center {
                        //     /* To center vertically */
                        //     display: table-cell;
                        //     vertical-align: middle;
                        //     pointer-events:none;
                        // }
                        // .modal-content {
                        //     /* Bootstrap sets the size of the modal in the modal-dialog class, we need to inherit it */
                        //     width:inherit;
                        //     max-width:inherit; /* For Bootstrap 4 - to avoid the modal window stretching full width */
                        //     height:inherit;
                        //     /* To center horizontally */
                        //     margin: 0 auto;
                        //     pointer-events: all;
                        // }
                        // </style>";

                        echo"<div class='modal' id='modal-ta' tabindex='-1' role='dialog'>
                            
                        <div class=''>
                            <div class='modal-dialog modal-sm ' role='document'>
                                <div class='modal-content' style='border-radius:10px'>
                                
                                        <div class='modal-header' style='border-bottom:0'>
                                            <a type='button' data-dismiss='modal' id='close-list' style='color:black;cursor:pointer'><h5 class='modal-title'> <i class='fa fa-angle-left fa-lg'></i> &nbsp;Pilih Tahun Akademik</h5></a>
                                            
                                        </div>
                                        <div class='modal-body' style='padding-left: 1px;
                                        padding-right: 1px;'>
                                            <ul class='list-group'>";
                                                $res = execute("select kode_ta from sis_ta where kode_lokasi='$kode_lokasi' and kode_pp='$kode_pp' order by kode_ta ");
                                                
                                                while ($row = $res->FetchNextObject(false)){
                                                echo"
                                                <li class='list-group-item' style='border:0;border-top:0;    border-bottom: 1px solid #80808047;'>
                                                    <div class='isi' hidden>".$row->kode_ta."</div>
                                                    <span>".$row->kode_ta."</span>
                                                    <span class='pull-right'><i class='fa fa-angle-right fa-lg'></i></span>
                                                </li>";
                                                }
                                            
                                            
                                        echo"
                                            </ul>   
                                        </div>
                                </div>
                            </div>
                        </div>
                        </diV>";
                        echo"<script>

                        $('#inp-ta').focus(function(){
                            $('#modal-ta').modal('show');
                        });

                        $('.list-group li').on('click', function(){
                            $('.list-group li div.isi').removeClass('selected');
                            $(this).find('div.isi').addClass('selected');
                            var x=$('.selected').text();
                            $('#inp-ta').val(x);
                            $('#modal-ta').modal('hide');
                            var ta = x;
                            window.location.href='$fmain?hal=app/ypt/dashSiswaDet.php&param=$jenis/$kunci/'+x;
                        });
                        
                        </script>";
                        break;
                    }
               
            
            echo"   
                </div>
            </div>";
    
        echo "<script type='text/javascript'>
                          
                $('.daterange').daterangepicker({
                    ranges   : {
                    'Today'       : [moment(), moment()],
                    'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month'  : [moment().startOf('month'), moment().endOf('month')],
                    'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                    },
                    startDate: moment().subtract(29, 'days'),
                    endDate  : moment()
                }, function (start, end) {
                    window.alert('You chose: ' + start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                });

                // The Calender
                $('#calendar').datepicker();
                $('.datepicker-inline').width('100%');
                $('.table-condensed').width('100%');

                $('#calendar').datepicker('setDate', new Date());

                $('#dash_ta').change(function(e) { 
                    e.preventDefault();
                    var ta = this.value;
                    window.location.href='$fmain?hal=app/ypt/dashSiswaDet.php&param=$jenis/$kunci/'+ta;
                });

                $('#iconback').click(function(e) {
                    window.location.href='#';
                });
            
                
			 </script>";
?>
