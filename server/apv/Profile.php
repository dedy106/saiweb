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
		if (substr($root_lib,-1)!="/") {
			$root_lib=$root_lib."/";
		}
		include_once($root_lib.'app/apv/setting.php');
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

    function getProfile(){
        
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $tmp = explode("|",$_GET['param']);
            $kode_lokasi = $tmp[0];
            $nik = $tmp[1];
            $sql="select a.nik,a.nama,a.email,a.no_telp,a.foto,b.nama as nama_jab,a.kode_pp,c.nama as nama_pp from apv_karyawan a left join apv_jab b on a.kode_jab=b.kode_jab and a.kode_lokasi=b.kode_lokasi 
            left join apv_pp c on a.kode_pp=c.kode_pp and a.kode_lokasi=c.kode_lokasi where a.kode_lokasi='$kode_lokasi' and a.nik='$nik' ";
            $data =dbRowArray($sql);
            $response["data"] = $data;
            $response['sql'] = $sql;            
            $response['status'] = TRUE;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }

?>