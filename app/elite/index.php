<?php
$request = $_SERVER['REQUEST_URI'];
//echo $request;
$request2 = explode('/',$request);
$root=$_SERVER["DOCUMENT_ROOT"];

switch ($request2[4]) {
    case '' :
        require __DIR__ . '/login.php';
    break;
    case 'login' :
        require __DIR__ . '/login.php';
        break;
    case '/' :
        include_once($root.'elite_main/fDashboard.php');
    break;
    case 'fKonten' :
        include_once($root.'elite_main/fKonten.php');
    break;
    case 'main' :
        if(isset($request2[5]) AND $request2[5] != ""){
           $_GET['hal']=$request2[5].'.php';
        }
        include_once(__DIR__ . '/fMain.php');
    break;
    default:
        require __DIR__ . '/404.php';   
    break;
}

?>