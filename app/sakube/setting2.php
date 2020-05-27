<?php
	$root_lib=$_SERVER["DOCUMENT_ROOT"];
	if (substr($root_lib,-1)!="/") {
		$root_lib=$root_lib."/";
	}
	include_once($root_lib."lib/koneksi.php");
    include_once($root_lib."lib/helpers.php");
    
    
	
	$root=$_SERVER['REQUEST_SCHEME']."://".$_SERVER['SERVER_NAME'];
    $folder_bootstrap=$root."/vendor/sakube/bootstrap";
    $folder_css=$root."/vendor/sakube/assets/css";
    $folder_js=$root."/vendor/sakube/assets/js";
    
	$folder_assets=$root."/vendor/sakube";
	$folder_upload=$root."/vendor/sakube/upload";
	$folderoot_assets=$root."/assets";
	$folderroot_css=$root."/assets/css";
	$folderroot_js=$root."/assets/js";
	$folderroot_img=$root."/assets/img";
	$root_upload=$root."/upload";
	
	$root_ser=$root."/server/sakube";
	$root_app=$root."/sakube_main";
	$root_log=$root."/sakube";
	$root_print=$root."/sakube_preview";

	// if(!$_SESSION['isLogedIn']){
      
    //         echo "<script>
    //         alert('Harap login terlebih dahulu !'); 
    //         window.location='$root_log';
    //         </script>";
    // }

?>