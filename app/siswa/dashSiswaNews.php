<?php
    
    $kode_lokasi=$_SESSION['lokasi'];
    $periode=$_SESSION['periode'];
    $kode_pp=$_SESSION['kodePP'];
    $nik=$_SESSION['userLog'];
    $fmain=$_SESSION['fMain'];

    $tmp=explode("/",$_GET['param']);
    $no_konten=$tmp[0];
    $jenis=$tmp[1];

    $path = "http://".$_SERVER["SERVER_NAME"]."/";				
    $path2 = $path . "image/keubg.png";
    
	switch($jenis){
        case "news" :
        case "newsdet" :

		$sql="select no_konten,judul,keterangan,file_dok as file_img,'-' as file_dok,convert(varchar,tanggal,105) as tanggal from sis_konten where kode_lokasi='$kode_lokasi' and flag_aktif='1' and no_konten='$no_konten' ";
            // $back="server_report_saku3_dash_rptDashSis";

		break;
		case "bp" :
		$sql="select no_bukti as konten,judul,keterangan,file_gambar as file_img,file_dok,convert(varchar,tanggal,105) as tanggal from sis_bp where kode_lokasi='$kode_lokasi' and no_bukti='$no_konten' ";

		break;
    }
    
        
        $rs = execute($sql);  
        $row = $rs->FetchNextObject($toupper=false);

        $judul = $row->judul;
        $keterangan =$row->keterangan;
        $file_img= $path. "server/media/".$row->file_img;
        $file_dok= $path. "server/media/".$row->file_dok;
        $tanggal=$row->tanggal;

        if (preg_match('/(chrome|firefox|avantgo|blackberry|android|blazer|elaine|hiptop|iphone|ipod|kindle|midp|mmp|mobile|o2|opera mini|palm|palm os|pda|plucker|pocket|psp|smartphone|symbian|treo|up.browser|up.link|vodafone|wap|windows ce; iemobile|windows ce; ppc;|windows ce; smartphone;|xiino)/i', $_SERVER['HTTP_USER_AGENT'], $version)) 

    // echo "Browser:".$version[1];

        if ($version[1] == "iPhone" || $version[1] == "Android" || $version[1] == "Blackberry" || $version[1] == "Blazer" ||$version[1] == "Elaine" || $version[1] == "Hiptop" || $version[1] == "iPod" || $version[1] == "Kindle" ||$version[1] == "Midp" || $version[1] == "Mobile" || $version[1] == "O2" || $version[1] == "Opera Mini" ||$version[1] == "Mobile" || $version[1] == "Smartphone"){
            $back1="";
            $widthimg="250px";
            $padding="padding-top:40px";
            switch($jenis){
				case "news" :
				$backto = "$fmain?hal=app/ypt/dashSiswa.php";
				break;
				case "bp" :
				$backto = "$fmain?hal=app/ypt/dashSiswaDet.php&param=$jenis/bp";
                break;
                case "newsdet" :
                $backto= "$fmain?hal=app/ypt/dashSiswaDet.php&param=$jenis/news";
                break;
                }
            include('back.php');
        }else{

            $padding="";
            $widthimg="500px";
            switch($jenis){
				case "news" :
				$back1="<div class='panel-heading'><a class='small-box-footer' href='$fmain?hal=app/ypt/dashSiswa.php' > Back <i class='fa fa-arrow-circle-left'></i></a></div>";
				break;
				case "bp" :
				$back1="<div class='panel-heading'><a href='$fmain?hal=app/ypt/dashSiswaDet.php&param=$jenis/bp' class='small-box-footer' > Back <i class='fa fa-arrow-circle-left'></i></a></div>";
                break;
                case "newsdet" :
				$back1="<div class='panel-heading'><a href='$fmain?hal=app/ypt/dashSiswaDet.php&param=$jenis/news' class='small-box-footer' > Back <i class='fa fa-arrow-circle-left'></i></a></div>";
				break;
				}
        }
        
		echo "<div class='panel' style='$padding'>
                <div class='panel-body'>
                $back1
					";
            echo"<h3><span style='color:#dd4b39'>$judul</span><br> <span class='' style='
            font-size: 11px;'><i class='fa fa-clock-o'></i> $tanggal</span></h3><br>";
            echo"<img src='$file_img' width='$widthimg' style='display: block;margin-left: auto;margin-right: auto;'></img>";
            echo"<p>".urldecode($keterangan)." </p>";
            if($row->file_dok =="-" OR $row->file_dok == ""){
                echo"";
            }else{
                echo"   
                <hr>
                <span style='color:#dd4b39'><i class='fa fa-file'></i> &nbsp;&nbsp;
                <b>$row->file_dok</b></span>
                <a class='btn btn-danger btn-xs pull-right' href='$filedok' target='_blank'><i class='fa fa-download'></i></a>";
            }
           
                echo"
                </div>
                
            </div>";
    
        echo "<script type='text/javascript'>
            
              </script>";

?>
