<?php

    $kode_lokasi=$_SESSION['lokasi'];
    $periode=$_SESSION['periode'];
    $kode_pp=$_SESSION['kodePP'];
    $nik=$_SESSION['userLog'];
    $kode_fs=$_SESSION['kode_fs'];
    $kode_fs="FS1";
    $path = "http://".$_SERVER["SERVER_NAME"]."/";	
    
    $notifikasi= $path . "image/dok.png";
    $logomain = $path.'/web/img/yspt2.png';
    $mainname = $_SESSION['namaPP'];

    $sql = "select b.nis,b.nama,b.kode_kelas,c.nama as nama_kelas,c.kode_tingkat,e.nama as nama_tingkat,c.kode_jur,f.nama as nama_jur,
    b.kode_akt,d.nama as nama_akt,b.tgl_lulus
    from sis_siswa b
    left join sis_kelas c on b.kode_kelas=c.kode_kelas and b.kode_lokasi=c.kode_lokasi
    left join sis_angkat d on b.kode_akt=d.kode_akt and b.kode_lokasi=d.kode_lokasi
    left join sis_tingkat e on c.kode_tingkat=e.kode_tingkat and c.kode_lokasi=e.kode_lokasi
    left join sis_jur f on c.kode_jur=f.kode_jur and c.kode_lokasi=f.kode_lokasi
    where b.nis='$nik' ";
    $rs = execute($sql);
    $row = $rs->FetchNextObject($toupper=false);

    if($row->foto == "-" OR $row->foto == ""){
        $user2 = $path ."image/user.png";
    }else{
        $user2 = $path ."image/".$row->foto;
    }
    $user2 = $path ."image/user3.png";

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
 
    echo "
        $header
		<div class='panel' style='margin:0px;$padding'>
            <div class='panel-heading' style='font-size:25px;padding:10px 0px 0px 20px;color:#dd4b39'>Profile
            </div>
            <div class='panel-body' style='padding:0px'> ";
            echo"
                <div class='row' style='margin:0px'>
                
                    <div class='col-md-12' style='padding:0px'>
                        <div class='box-body box-profile' style='padding-top:0px'>
                            <div>
                                <img class='profile-user-img img-responsive img-circle' src='$user2' alt='User profile picture' style='width: 80px;margin: auto;border: none;'>
                                <p class='text-muted' style='margin:auto;text-align:center'>$row->nis</p>
                                <h3 class='profile-username' style='margin-left: auto;text-align:center;padding-bottom:10px;font-size:18px'>&nbsp;</h3>
                                
                            </div>
                            <style>
                                .head-profile {
                                    color:grey;
                                }
                                .isi-profile{
                                    color:black;
                                }
                            </style>
                            <table class='table no-border' style='margin-bottom:20px'>
                                <tr>
                                    <td>Nama</td>
                                    <td>$row->nama</td>
                                </tr>
                                <tr>
                                    <td>Angkatan</td>
                                    <td>$row->kode_akt</td>
                                </tr>
                                <tr>
                                    <td>Kelas</td>
                                    <td>$row->kode_kelas</td>
                                </tr>
                                <tr>
                                    <td>Jurusan</td>
                                    <td>$row->nama_jur</td>
                                </tr>
                                <tr>
                                    <td>Tingkat</td>
                                    <td>$row->nama_tingkat</td>
                                </tr>
                                <tr>
                                    <td>Status</td>
                                    <td>Aktif dalam kegiatan belajar mengajar</td>
                                </tr>
                            </table>
                            <!-- <a href='#' style='margin-right:5px' class='btn btn-default  disabled' >Edit Profile</a> -->
                            <a href='".$_SESSION['exit_url']."' style='margin-right:5px;border-radius:10px' class='btn btn-danger   '>Logout</a>
                        </div>
                    </div>
                </div>";
            echo"               
            </div>
       </div>";    
       
       
                		
		echo "
        <script type='text/javascript'>
        </script>";

?>
