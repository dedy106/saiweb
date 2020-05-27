<?php
    $request_method=$_SERVER["REQUEST_METHOD"];

    switch($request_method) {
        case 'GET':
            if(isset($_GET["fx"]) AND function_exists($_GET['fx'])){
                $_GET['fx']();
            }
        break;
        case 'POST':
            // Insert 
            if(isset($_GET["fx"]) AND function_exists($_GET['fx'])){
                $_GET['fx']();
            }
        break;
        default:
            // Invalid Request Method
            header("HTTP/1.0 405 Method Not Allowed");
        break;
    }

    function getKoneksi(){
        $root_lib=$_SERVER["DOCUMENT_ROOT"];
        include_once($root_lib."lib/koneksiTest.php");
        include_once($root_lib."lib/helpers.php");
    }

    function cekExecute(){
        getKoneksi();
        $exec =array();
        $ins = "insert into dev_siswa values ('SIS006','99','Execute Test','JUR001') ";
        $ins2 = "insert into dev_siswa values (SIS007,'99','Execute Test','JUR001') ";
        array_push($exec,$ins);
        array_push($exec,$ins2);
        $rs = executeArray($exec);
        if($rs["status"]){
            $response['message']= "berhasil";
            $response['status']= true;
        }else{
            
            $response['status']= false;
            $response['message']= "gagal. ".$rs["error"];
        }
        echo json_encode($response);
    }
    
?>
