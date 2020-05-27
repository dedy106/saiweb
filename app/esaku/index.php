<?php
$request = $_SERVER['REQUEST_URI'];
$root=$_SERVER["DOCUMENT_ROOT"];
$request2 = explode('/',$request);
$path = "http://".$_SERVER['SERVER_NAME']."/esaku";
//echo $request2[4];

switch ($request2[4]) {
    case '' :
        include_once($root.$path.'/home.php');
        break;
    case 'produk' :
        include_once($root.$path.'/produk.php');
        break;
	case 'fitur' :
        include_once($root.$path.'/fitur.php');
        break;
	case 'tentang' :
        include_once($root.$path.'/tentang.php');
        break;
    case 'login' :
        include_once($root.$path.'/fLogin.php');
        break;
    case 'verify_email' :
        include_once($root.$path.'/verify.php');
        break;
    case 'registrasi' :
        include_once($root.$path.'/fRegis.php');
        break;
    case 'iblog' :
        include_once($root.$path.'/iBlog.php');
        break;
    case 'admin' :
        include_once($root.$path.'/admin/home.php');
        break;
    case 'artikel' :
        include_once($root.$path.'/iArtikel.php');
        break;
    case 'video' :
        include_once($root.$path.'/iVideo.php');
        break;
    case 'artikelU' :
        include_once($root.$path.'/iArtikelUtama.php');
        break;
    case 'videoU' :
        include_once($root.$path.'/iVideoUtama.php');
        break;
    case 'kat' :
        include_once($root.$path.'/iKategori.php');
        break;
    case 'main' :
        if(isset($request2[5]) AND $request2[5] != ""){
            $_GET['hal']=$request2[5].'.php';
        }
        include_once($root.$path.'/admin/fma.php');
        break;
    default:
        include_once($root.$path.'/home.php');
        break;
}

?>