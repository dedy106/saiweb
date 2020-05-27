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
	
    $sql3="select top 6 no_konten,convert(varchar,tanggal,105) as tgl,judul,file_dok,tanggal from sis_konten where kode_lokasi = '$kode_lokasi' and kode_pp ='$kode_pp' order by tanggal desc ";
    $rs3 = execute($sql3);
	
    $path = "http://".$_SERVER["SERVER_NAME"]."/";				
    $bp = $path . "image/Buku Penghubung.png";
    $keu = $path . "image/Keuangan.png";
    $kldr = $path . "image/Kalender Akademik.png";
    $kldr2 = $path . "image/Jadwal Pelajaran.png";
    $absen = $path . "image/Absensi.png";
    $nil = $path . "image/Nilais.png";
    $notifikasi= $path . "image/notif.png";
    $dok = $path . "image/dok.png";
    $user = $path . "image/user2.png";
    $pres = $path . "image/Prestasis.png";
    $raport = $path . "image/Report.png";

    $eskul = $path . "image/Ekstrakulikuler.png";

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
        $padding="padding-top:50px;border:0px solid grey;box-shadow: 0 0 0 white;";
        $style = "box-shadow: 0 0 0 white;";
    }else{
        $width="25%";
        $header="";
        $padding="";
        $style = "box-shadow: 0 0 0 white;";
    }

    
 
    echo "
        $header
		<div class='panel' style='$padding'>
            <div class='panel-heading' style='font-size:18px;padding:10px 0px 1px 20px'> 
            </div>
            <div class='panel-body'>
                <div class='row'>
                    <div class='col-md-12'>
                        <div class='box' style='border-top:white;$style'>
                            <div class='box-body no-padding'>
                                <ul class='users-list clearfix'>
                                    <li style='width:$width'>
                                        <a class='users-list-name' style='cursor:pointer;font-weight:100;overflow:unset' href='$fmain?hal=app/ypt/dashSiswaDet.php&param=all/bp' ><img src='$bp' width='80px' alt='User Image'><br>
                                        Buku Penghubung</a>
                                    </li>
                                    <li style='width:$width'>
                                        <a class='users-list-name' style='cursor:pointer;font-weight:100;overflow:unset'  href='$fmain?hal=app/ypt/dashSiswaDet.php&param=all/keu'><img src='$keu' width='80px'  alt='User Image'><br>
                                        Keuangan</a>
                                    </li>
                                    <li style='width:$width'>
                                        <a class='users-list-name' style='cursor:pointer;font-weight:100;overflow:unset' href='$fmain?hal=app/ypt/dashSiswaDet.php&param=all/ka'><img src='$kldr' width='80px'  alt='User Image'><br>
                                        Kalender Akademik</a>
                                    </li>
                                    <li style='width:$width'>
                                        <a class='users-list-name' style='cursor:pointer;font-weight:100;overflow:unset' href='$fmain?hal=app/ypt/dashSiswaDet.php&param=all/abs' ><img src='$absen' width='80px' alt='User Image'><br>
                                        Absensi</a>
                                    </li>
                                    <li style='width:$width'>
                                        <a class='users-list-name' style='cursor:pointer;font-weight:100;overflow:unset' href='$fmain?hal=app/ypt/dashSiswaDet.php&param=all/nil'><img src='$nil' width='80px' alt='User Image'><br>
                                        Nilai</a>
                                    </li> 
                                    <li style='width:$width'>
                                        <a class='users-list-name' style='cursor:pointer;font-weight:100;overflow:unset' href='$fmain?hal=app/ypt/dashSiswaDet.php&param=all/kld'><img src='$kldr2' width='80px' alt='User Image'><br>
                                        Jadwal Pelajaran</a>
                                    </li>
                                    <li style='width:$width'>
                                        <a class='users-list-name' style='cursor:pointer;font-weight:100;overflow:unset' href='$fmain?hal=app/ypt/dashSiswaDet.php&param=all/prestasi'><img src='$pres' width='80px' alt='User Image'><br>
                                        Prestasi</a>
                                    </li>
                                    <li style='width:$width'>
                                        <a class='users-list-name' style='cursor:pointer;font-weight:100;overflow:unset' href='$fmain?hal=app/ypt/dashSiswaDet.php&param=all/raport'><img src='$raport' width='80px' alt='User Image'><br>
                                        Raport</a>
                                    </li>
                                    <li style='width:$width'>
                                        <a class='users-list-name' style='cursor:pointer;font-weight:100;overflow:unset' href='$fmain?hal=app/ypt/dashSiswaDet.php&param=all/eskul'><img src='$eskul' width='80px' alt='User Image'><br>
                                        Ekstrakulikuler</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class='row'>
                    <div class='col-md-12'>
                        <div class='box-header with-border'>
                            <h3 class='box-title'><b>Informasi</b></h3>                      
                            
                        </div>
                        <div class='box-body'>";

                        while ($row = $rs3->FetchNextObject($toupper=false)){
                            $foto= $path."server/media/".$row->file_dok;
                        echo "
                            <a style='cursor:pointer;color:black' href='$fmain?hal=app/ypt/dashSiswaNews.php&param=$row->no_konten/news'>
                            <div class='col-md-12 col-md-2'>
                                <div class='box box-widget widget-user'>
                                    <div class='widget-user-header bg-black' style='background: url($foto) center center;'>
                                        <h3 class='widget-user-username'></h3>
                                        <h5 class='widget-user-desc'></h5>
                                    </div>
                                    <div class='box-footer'>
                                        <h5 class='description-header'>$row->judul</h5>
                                        <span class='description-text'>$row->tgl</span>
                                    </div>
                                </div>
                            </div> 
                            </a>
                            ";
                        }
                       
                        echo"
                        </div>
                        <div class='box-footer'>
                        <a style='cursor:pointer;color:black' href='$fmain?hal=app/ypt/dashSiswaDet.php&param=all/news' class='pull-right'>See More &nbsp;<i class='fa fa-angle-right'></i><i class='fa fa-angle-right'></i><i class='fa fa-angle-right'></i></a>
                        </div>";            
        echo"       </div> 
                </div>                
            </div>
       </div>";     
                		
		echo "
        <script type='text/javascript'>
        </script>";

   
?>
