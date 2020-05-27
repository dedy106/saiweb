<?php

    $kode_lokasi=$_SESSION['lokasi'];
    $periode=$_SESSION['periode'];
    $kode_pp=$_SESSION['kodePP'];
    $nik=$_SESSION['userLog'];
    $kode_fs=$_SESSION['kode_fs'];
    $kode_fs="FS1";
    $path = "http://".$_SERVER["SERVER_NAME"]."/";
    
    $logomain = $path.'/web/img/yspt2.png';
    $mainname = $_SESSION['namaPP'];
    
    $notifikasi= $path . "image/dok.png";
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

    echo $_SESSION['sql'];
 
    echo "
        $header
		<div class='panel' style='margin:0px;$padding'>
            <div class='panel-heading' style='font-size:25px;padding:10px 0px 10px 20px;color:#dd4b39'>Dokumen
            </div>
            <div class='panel-body' style='padding:0px'>
                <div class='row' style='margin:0px'>
                    <div class='col-md-12' style='padding:0px'>
                        <div class='box-footer box-comments' style='background:white;box-shadow: 1px 1px 2px 1px rgba(0,0,0,0.1);height:70px'>
                            <div class='box-comment'>
                            <img class='img-circle img-sm' style='width: 50px !important;height: 50px !important;' src='$notifikasi' alt='User Image'>
                            <div class='comment-text' style='margin-left: 60px;'>
                                    <span class='username'>
                                    Judul Dokumen
                                    <span class='text-muted pull-right'>12-02-2019<i class='fa  fa-angle-right' style='font-size:30px;padding-left: 20px;'></i></span>
                                    </span><!-- /.username -->
                                    Ket (unread) ...
                            </div>
                            </div>
                        </div>
                        <div class='box-footer box-comments' style='background:white;box-shadow: 1px 1px 2px 1px rgba(0,0,0,0.1);height:70px'>
                            <div class='box-comment'>
                            <img class='img-circle img-sm' style='width: 50px !important;height: 50px !important;' src='$notifikasi' alt='User Image'>
                            <div class='comment-text' style='margin-left: 60px;'>
                                    <span class='username'>
                                    Judul Dokumen
                                    <span class='text-muted pull-right'>12-02-2019<i class='fa  fa-angle-right' style='font-size:30px;padding-left: 20px;'></i></span>
                                    </span><!-- /.username -->
                                    Ket (unread) ...
                            </div>
                            </div>
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
