<?php
$root=$_SERVER["DOCUMENT_ROOT"];
// echo substr($root, -1); 


//echo $_SERVER['SERVER_NAME'];

// echo $base_url;
$request = $_SERVER['REQUEST_URI'];
$request2 = explode('/',$request);
//echo $request2[1];
switch ($request2[1]) {
    
	case 'belajar' :
        require __DIR__ . '/app/belajar/fLogin.php';
    break;
	case 'belajar_main' :
		if(isset($request2[2]) AND $request2[2] != ""){
           $_GET['hal']=$request2[2].'.php';
        }
        require __DIR__ . '/app/belajar/fMain.php';
    break;
	
    case 'crm' :
        require __DIR__ . '/app/crm/fLogin.php';
    break;
	case 'crm_main' :
		if(isset($request2[2]) AND $request2[2] != ""){
           $_GET['hal']=$request2[2].'.php';
        }
        require __DIR__ . '/app/crm/fMain.php';
    break;
	case 'elite' :
        require __DIR__ . '/app/elite/fLogin.php';
    break;
	case 'elite_main' :
		if(isset($request2[2]) AND $request2[2] != ""){
           $_GET['hal']=$request2[2].'.php';
        }
        require __DIR__ . '/app/elite/fMain.php';
    break;
	case 'sakuaja' :
        require __DIR__ . '/app/sakuaja/index.php';
    break;
	case '' :
        require __DIR__ . '/app/kasir/fLogin.php';
    break;
    case 'kasir' :
        require __DIR__ . '/app/kasir/fLogin.php';
    break;
	case 'kasir_main' :
		if(isset($request2[2]) AND $request2[2] != ""){
           $_GET['hal']=$request2[2].'.php';
        }
        require __DIR__ . '/app/kasir/fMain.php';
    break;
    case 'kasir_preview' :
		if(isset($request2[2]) AND $request2[2] != ""){
           $_GET['hal']=$request2[2].'.php';
        }
        require __DIR__ . '/app/kasir/previewPos.php';
    break;
    case 'sdm' :
        require __DIR__ . '/app/sdm/fLogin.php';
    break;
	case 'sdm_main' :
		if(isset($request2[2]) AND $request2[2] != ""){
           $_GET['hal']=$request2[2].'.php';
        }
        require __DIR__ . '/app/sdm/fMain.php';
    break;
    case 'sdm_preview' :
		if(isset($request2[2]) AND $request2[2] != ""){
           $_GET['hal']=$request2[2].'.php';
        }
        require __DIR__ . '/app/sdm/preview.php';
    break;
    case 'apv' :
        require __DIR__ . '/app/apv/fLogin.php';
    break;
	case 'apv_main' :
		if(isset($request2[2]) AND $request2[2] != ""){
           $_GET['hal']=$request2[2].'.php';
        }
        require __DIR__ . '/app/apv/fMain.php';
    break;
    case 'apv_preview' :
		if(isset($request2[2]) AND $request2[2] != ""){
           $_GET['hal']=$request2[2].'.php';
        }
        require __DIR__ . '/app/apv/preview.php';
    break;
    case 'apv_mobile' :
        require __DIR__ . '/app/apv/fLoginMobile.php';
    break;
	case 'apv_mainmobile' :
		if(isset($request2[2]) AND $request2[2] != ""){
           $_GET['hal']=$request2[2].'.php';
        }
        require __DIR__ . '/app/apv/fMainMobile.php';
    break;
	
	case 'jwt' :
        require __DIR__ . '/app/jwt/index.html';
    break;
    case 'siswa' :
        require __DIR__ . '/app/siswa/fLogin.php';
    break;
	case 'siswa_main' :
		if(isset($request2[2]) AND $request2[2] != ""){
           $_GET['hal']=$request2[2].'.php';
        }
        require __DIR__ . '/app/siswa/fMain.php';
    break;
    case 'siswa_preview' :
		if(isset($request2[2]) AND $request2[2] != ""){
           $_GET['hal']=$request2[2].'.php';
        }
        require __DIR__ . '/app/siswa/preview.php';
    break;
    case 'siswa_mobile' :
        require __DIR__ . '/app/siswa/fLoginMobile.php';
    break;
	case 'siswa_mainmobile' :
		if(isset($request2[2]) AND $request2[2] != ""){
           $_GET['hal']=$request2[2].'.php';
        }
        require __DIR__ . '/app/siswa/fMainMobile.php';
    break;
    case 'esaku' :
		if(isset($request2[2]) AND $request2[2] != ""){
           $hal=$request2[2];
        }else{
            $hal = "home";
        }
        require __DIR__ . '/app/esaku/'.$hal.'.php';
    break;
    case 'esaku-admin' :
        require __DIR__ . '/app/esaku/fLogin.php';
    break;
	case 'esaku-admin_main' :
		if(isset($request2[2]) AND $request2[2] != ""){
           $_GET['hal']=$request2[2].'.php';
        }
        require __DIR__ . '/app/esaku/fMain.php';
    break;
    case 'esaku_preview' :
		if(isset($request2[2]) AND $request2[2] != ""){
           $_GET['hal']=$request2[2].'.php';
        }
        require __DIR__ . '/app/esaku/preview.php';
    break;
    case 'sai' :
        require __DIR__ . '/app/sai/fLogin.php';
    break;
	case 'sai_main' :
		if(isset($request2[2]) AND $request2[2] != ""){
           $_GET['hal']=$request2[2].'.php';
        }
        require __DIR__ . '/app/sai/fMain.php';

    break;
    case 'sai_preview' :
		if(isset($request2[2]) AND $request2[2] != ""){
           $_GET['hal']=$request2[2].'.php';
        }
        require __DIR__ . '/app/sai/preview.php';
    break;
    case 'test_main' :
		if(isset($request2[2]) AND $request2[2] != ""){
           $_GET['hal']=$request2[2].'.php';
        }
        require __DIR__ . '/app/test/fMain.php';
    break;
    case 'dago' :
        require __DIR__ . '/app/dago/fLogin.php';
    break;
	case 'dago_main' :
		if(isset($request2[2]) AND $request2[2] != ""){
           $_GET['hal']=$request2[2].'.php';
        }
        require __DIR__ . '/app/dago/fMain.php';
    break;
    case 'dago_preview' :
		if(isset($request2[2]) AND $request2[2] != ""){
           $_GET['hal']=$request2[2].'.php';
        }
        require __DIR__ . '/app/dago/preview.php';
    break;
	case 'dash' :
        require __DIR__ . '/app/dash/fLogin.php';
    break;
	case 'dash_main' :
		if(isset($request2[2]) AND $request2[2] != ""){
           $_GET['hal']=$request2[2].'.php';
        }
        require __DIR__ . '/app/dash/fMain.php';
    break;
    case 'dash_preview' :
		if(isset($request2[2]) AND $request2[2] != ""){
           $_GET['hal']=$request2[2].'.php';
        }
        require __DIR__ . '/app/dash/preview.php';
    break;
    case 'ajax' :
        require __DIR__ . '/app/ajax/fLogin.php';
    break;
	case 'ajax_main' :
		if(isset($request2[2]) AND $request2[2] != ""){
           $_GET['hal']=$request2[2].'.php';
        }
        require __DIR__ . '/app/ajax/fMain.php';
    break;
    case 'ajax_preview' :
		if(isset($request2[2]) AND $request2[2] != ""){
           $_GET['hal']=$request2[2].'.php';
        }
        require __DIR__ . '/app/ajax/preview.php';
    break;
    case 'sekolah' :
        require __DIR__ . '/app/sekolah/fLogin.php';
    break;
	case 'sekolah_main' :
		if(isset($request2[2]) AND $request2[2] != ""){
           $_GET['hal']=$request2[2].'.php';
        }
        require __DIR__ . '/app/sekolah/fMain.php';
    break;
    case 'sekolah_preview' :
		if(isset($request2[2]) AND $request2[2] != ""){
           $_GET['hal']=$request2[2].'.php';
        }
        require __DIR__ . '/app/sekolah/preview.php';
    break;
    case 'saku' :
        require __DIR__ . '/app/saku/fLogin.php';
    break;
    case 'saku_main' :
        if(isset($request2[2]) AND $request2[2] != ""){
            $_GET['hal']=$request2[2].'.php';
        }
        require __DIR__ . '/app/saku/fMain.php';
    break;
    case 'cms' :
        require __DIR__ . '/app/cms/fLogin.php';
    break;
    case 'cms_main' :
        if(isset($request2[2]) AND $request2[2] != ""){
            $_GET['hal']=$request2[2].'.php';
        }
        require __DIR__ . '/app/cms/fMain.php';
    break;
    case 'trengginas' :
        require __DIR__ . '/app/trengginas/fMain.php';
    break;
    case 'tarbak' :
        require __DIR__ . '/app/tarbak/fLogin.php';
    break;
	case 'tarbak_main' :
		if(isset($request2[2]) AND $request2[2] != ""){
           $_GET['hal']=$request2[2].'.php';
        }
        require __DIR__ . '/app/tarbak/fMain.php';
    break;
    case 'tarbak_preview' :
		if(isset($request2[2]) AND $request2[2] != ""){
           $_GET['hal']=$request2[2].'.php';
        }
        require __DIR__ . '/app/tarbak/preview.php';
    break;
    case 'sakube' :
        require __DIR__ . '/app/sakube/fLogin.php';
    break;
    case 'sakube_main' :
		if(isset($request2[2]) AND $request2[2] != ""){
           $_GET['hal']=$request2[2].'.php';
        }
        require __DIR__ . '/app/sakube/fMain.php';
    break;
    case 'telu' :
        require __DIR__ . '/app/telu/fLogin.php';
    break;
	case 'telu_main' :
		if(isset($request2[2]) AND $request2[2] != ""){
           $_GET['hal']=$request2[2].'.php';
        }
        require __DIR__ . '/app/telu/fMain.php';
    break;
    case 'telu_preview' :
		if(isset($request2[2]) AND $request2[2] != ""){
           $_GET['hal']=$request2[2].'.php';
        }
        require __DIR__ . '/app/telu/preview.php';
    break;
    case 'apisaku' :
        require __DIR__ . '/app/apisaku/fLogin.php';
    break;
	case 'apisaku_main' :
		if(isset($request2[2]) AND $request2[2] != ""){
           $_GET['hal']=$request2[2].'.php';
        }
        require __DIR__ . '/app/apisaku/fMain.php';
    break;
    case 'apisaku_preview' :
		if(isset($request2[2]) AND $request2[2] != ""){
           $_GET['hal']=$request2[2].'.php';
        }
        require __DIR__ . '/app/apisaku/preview.php';
    break;
}

?>