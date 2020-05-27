<?php

    $request_method=$_SERVER["REQUEST_METHOD"];

    switch($request_method) {
        case 'GET':
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
        include_once($root_lib."lib/koneksi6.php");
        include_once($root_lib."lib/helpers.php");

    }

    function getTest(){
        try{

            getKoneksi();
            // $res['daftar'] = dbResultArray("select * from hakakses ");
            
            $res['daftar'] = db_Connect();
            echo json_encode($res);
        }
        catch (exception $e) { 
            error_log($e->getMessage());
            $res['error'] = $e->getMessage();	
            echo json_encode($res);
        } 	
    }
