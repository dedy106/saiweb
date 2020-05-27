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
        case 'DELETE':
            hapus();
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
    
    function cekAuth($user){
        getKoneksi();
        $user = qstr($user);

        $schema = db_Connect();
        $auth = $schema->SelectLimit("SELECT * FROM hakakses where nik=$user ", 1);
        if($auth->RecordCount() > 0){
            return true;
        }else{
            return false;
        }
    }

    function generateKode($tabel, $kolom_acuan, $prefix, $str_format){
        $query = execute("select right(max($kolom_acuan), ".strlen($str_format).")+1 as id from $tabel where $kolom_acuan like '$prefix%'");
        $kode = $query->fields[0];
        $id = $prefix.str_pad($kode, strlen($str_format), $str_format, STR_PAD_LEFT);
        return $id;
    }

    function cekPassLama($isi,$nik){
        getKoneksi();

        $schema = db_Connect();
        $kode_lokasi= $_SESSION['lokasi'];
        $auth = $schema->SelectLimit("SELECT pass FROM hakakses where pass='$isi' and nik='$nik' and kode_lokasi='$kode_lokasi' ", 1);
        if($auth->RecordCount() > 0){
            return true;
        }else{
            return false;
        }
    }

    function cekPassKonfirm($pass_baru,$pass_confirm){
       
        if($pass_baru == $pass_confirm){
            return true;
        }else{
            return false;
        }
    }

    function simpan(){
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            
            $data=$_POST;
            $exec = array();
            if(cekPassLama($data['password_lama'],$data['nik'])){
                if(cekPassKonfirm($data['password_baru'],$data['password_confirm'])){
                    $kode_lokasi=$data['kode_lokasi'];
                    $sql = "update hakakses set pass='".$data['password_baru']."' where nik ='".$data['nik']."' and kode_lokasi='$kode_lokasi'  ";
                    array_push($exec,$sql);
                    $rs = executeArray($exec,$err);
                    if($err == null){

                        $sts = true;
                        $msg = "";
                    }else{
                        $sts = false;
                        $msg = "Error : ".$err;
                    }
                    $response["sql"] =$sql;
                }else{
                    $sts = false;
                    $msg = "Error : Password Konfirm tidak sama dengan Password baru ";    
                }
            }else{
                $sts = false;
                $msg = "Error : Password Lama tidak valid ";
            }
           

            $response["message"] =$msg;
            $response["status"] = $sts;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        echo json_encode($response);
    }
    