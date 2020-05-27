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
        $root_lib=realpath($_SERVER["DOCUMENT_ROOT"])."/";
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

    
    function register(){
        getKoneksi();
        $dbconn = db_Connect();

        $db_token["nik"] = $_POST["nik"];
        $db_token["api_key"] = random_string('alnum', 20);
        $db_token["token"] = $_POST["token"];
        $db_token["kode_lokasi"] = $_POST["kode_lokasi"];

        $db_token["os"] = 'BROWSER';
        $db_token["ver"] = '';
        $db_token["model"] = '';
        $db_token["uuid"] = '';

        $db_token["tgl_login"] = date('Y-m-d H:i:s');

        $res = dbResultArray("select nik,kode_lokasi from api_token_auth where nik='".$_POST['nik']."' and kode_lokasi='".$_POST['kode_lokasi']."' and token='".$_POST['token']."' ");
        $exec=array();

        if(count($res)>0){
            $response['message'] = 'Already registered';
        }else{
            $token_sql = $dbconn->AutoExecute('api_token_auth', $db_token, 'INSERT');
            if($token_sql){
                $response['message'] = "ID registered";
            }else{
                $response['message'] = "Failed to register";
            }
        }

        echo json_encode($response);
    }

    function getNotif(){

        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $sqlnotif = "select distinct title,isi,nik,kode_lokasi,convert(varchar,tgl_input,103) as tanggal from apv_notif_m  where kode_lokasi='".$_SESSION['lokasi']."' and nik='".$_SESSION['userLog']."' ";
            $response["data"] = dbResultArray($sqlnotif);
            $response["status"] = true;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        echo json_encode($response);

    }

?>