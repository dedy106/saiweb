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
        include_once($root_lib."lib/koneksi.php");
        include_once($root_lib."lib/helpers.php");
    }
    
    function cekAuth($user,$pass){
        getKoneksi();
        $user = qstr($user);
        $pass = qstr($pass);

        $schema = db_Connect();
        $auth = $schema->SelectLimit("SELECT * FROM hakakses where nik=$user ", 1);
        if($auth->RecordCount() > 0){
            return true;
        }else{
            return false;
        }
    }

    function isUnik($isi){
        getKoneksi();

        $schema = db_Connect();
        $auth = $schema->SelectLimit("SELECT nik FROM apv_karyawan where nik='$isi' ", 1);
        if($auth->RecordCount() > 0){
            return false;
        }else{
            return true;
        }
    }

    function getDataBox(){
        
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $tmp = explode("|",$_GET['param']);
            $kode_lokasi = $tmp[0];
            
            $aju =dbRowArray("select count(*) as jum from apv_juskeb_m where kode_lokasi='$kode_lokasi'");
            $response["pengajuan"] = $aju["jum"];

            $app =dbRowArray("select count(*) as jum from apv_pesan where kode_lokasi='$kode_lokasi'");
            $response["approval"] = $app["jum"];

            $appd =dbRowArray("select count(*) as jum from apv_pesan where kode_lokasi='$kode_lokasi' and status='2' ");
            $response["approved"] = $appd["jum"];

            
            $rtn =dbRowArray("select count(*) as jum from apv_pesan where kode_lokasi='$kode_lokasi' and status='3' ");
            $response["return"] = $rtn["jum"];
            $response["sql"]= "select count(*) as jum from apv_juskeb_m where kode_lokasi='$kode_lokasi'";
            $response['status'] = TRUE;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }

?>