<?php
	$root_lib=$_SERVER["DOCUMENT_ROOT"];
	if (substr($root_lib,-1)!="/") {
		$root_lib=$root_lib."/";
	}
	include_once($root_lib."lib/koneksi5.php");
	include_once($root_lib."lib/helpers.php");
	
	$root=$_SERVER['REQUEST_SCHEME']."://".$_SERVER['SERVER_NAME'];
	$folder_css=$root."/vendor/asset_elite/dist/css";
	$folder_js=$root."/vendor/asset_elite/dist/js";
	$folder_assets=$root."/vendor/asset_elite";
	$folder_upload=$root."/vendor/asset_elite/upload";
	$folderoot_assets=$root."/assets";
	$folderroot_css=$root."/assets/css";
	$folderroot_js=$root."/assets/js";
	$folderroot_img=$root."/assets/img";
	$root_upload=$root."/upload";
	
	$root_ser=$root."/server/sekolah";
	$root_app=$root."/sekolah_main";
	$root_log=$root."/sekolah";
	$root_print=$root."/sekolah_preview";
	
	session_start();
	if(!$_SESSION['isLogedIn']){
       
            echo "<script>
            alert('Harap login terlebih dahulu !'); 
            window.location='$root_log';
            </script>";
    }

?>