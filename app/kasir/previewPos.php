<?php
     session_start();
     $root_lib=$_SERVER["DOCUMENT_ROOT"];
     if (substr($root_lib,-1)!="/") {
         $root_lib=$root_lib."/";
     }
     include_once($root_lib.'app/kasir/setting.php');
 
 
    if(!$_COOKIE['isLogedIn']){
        echo "<script>alert('Harap login terlebih dahulu !'); window.location='$root_log';</script>";
    }
    include_once($root2.'lib/helpers.php');
    include_once($root2.'lib/koneksi.php');
    // include_once($root2.'/web/setting.php');
    echo"
    <script src='".$folderroot_js."/jquery-3.4.1.js' ></script>
    <script>
    var param = '".$_GET['param']."';
    var pmb = '".$_GET['pmb']."';
    var stsPrint = '".$_GET['print']."';

    </script>";

    if(ISSET($_GET['hal'])){
        
        include_once($_GET['hal']); 
        
    }
?>

                    
                   