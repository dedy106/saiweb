<?php
    $request_method=$_SERVER["REQUEST_METHOD"];

    switch($request_method) {
        case 'GET':
            if(isset($_GET["fx"]) AND function_exists($_GET['fx'])){
                $_GET['fx']();
            }
        break;
        case 'POST':
            // if(isset($_GET["fx"]) AND function_exists($_GET['fx'])){
            //     $_GET['fx']();
            // }
            generate();
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


    function generate(){
        getKoneksi();
        $data = $_POST;
        if(arrayKeyCheck(array('kode_lokasi','modul'), $data)){

            $kode_lokasi=$data['kode_lokasi'];
            $modul=$data['modul'];
            
            $response = array("message" => "", "status" => "" );
            $exec=array();
            $serverKey = hash("md5", "sai".$modul.$kode_lokasi);
            $sql = "select * from api_server_key where modul='$modul' and kode_lokasi='$kode_lokasi'  ";
            $res = execute($sql);
            if($res->RecordCount()>0){
                $del = "delete from api_server_key where modul='$modul' and kode_lokasi='$kode_lokasi' ";
                array_push($exec,$del);
            }
            $ins = "insert into api_server_key (kode_lokasi,modul,server_key,tgl_input) values ('$kode_lokasi','$modul','$serverKey',getdate()) ";

            array_push($exec,$ins);
            $rs = executeArray($exec);

            if($rs){
                $msg = "berhasil";
                $sts = true;
            }else{
                $msg = "gagal";
                $sts = false;
            }

        }else{
            $sts = false;
            $msg = "Kode Lokasi, Modul required";
        }
        $response["message"] = $msg;
        $response["status"] = $sts;
        echo json_encode($response);

    }

?>
