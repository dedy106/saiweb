<?php
    $request_method=$_SERVER["REQUEST_METHOD"];

    switch($request_method) {
        case 'GET':
            if(isset($_GET["fx"]) AND function_exists($_GET['fx'])){
                $_GET['fx']();
            }
        break;
    }

    function getKoneksi(){
        $root_lib=$_SERVER["DOCUMENT_ROOT"];
        include_once($root_lib."web/lib/koneksi.php");
        include_once($root_lib."web/lib/helpers.php");
    }

    function cekAuth($user,$pass){
        getKoneksi();
        $user = qstr($user);
        $pass = qstr($pass);

        $schema = db_Connect();
        $auth = $schema->SelectLimit("SELECT * FROM hakakses where nik=$user", 1);
        if($auth->RecordCount() > 0){
            return true;
        }else{
            return false;
        }
    }

    function getPeriode2(){
        session_start();
        // getKoneksi();
        // $data=$_GET;
        // if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){             
            $kode_lokasi = $_SESSION['lokasi'];
            // $perusahan = dbResultArray("select distinct periode from periode where kode_lokasi='$kode_lokasi' ");
            $perusahan ="select distinct periode from periode where kode_lokasi='$kode_lokasi'";
            $response["daftar"] = $perusahan;
        // } else{
        //     $response["message"] = "Unauthorized Access, Login Required";
        // }     
        $response["status"]=true; 
        header('Content-Type: application/json');
        echo json_encode($response);
    }
?>