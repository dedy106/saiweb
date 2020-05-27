<?php
    $request_method=$_SERVER["REQUEST_METHOD"];

    switch($request_method) {
        case 'GET':
            getView();
        break;
        case 'POST':
            simpan();
        break;
        case 'PUT':
            ubah();
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
        require_once($root_lib.'lib/jwt.php');
    }

    function authKey($token, $modul=null,$kode_lokasi=null){
        getKoneksi();
        $token = $token;
        // $modul = qstr($modul);
        // $kode_lokasi = qstr($kode_lokasi);
        $date = date('Y-m-d H:i:s');

        $schema = db_Connect();
        // $sql = "SELECT server_key FROM api_server_key where modul=$modul and kode_lokasi=$kode_lokasi ";
        // $auth = execute($sql);
        // if($auth->RecordCount() > 0){
           
        //     $serverKey = $auth->fields[0];
            $serverKey = "bccf9112d48a8aa444dd73e762cf263c";

            try {
                $payload = JWT::decode($token, $serverKey, array('HS256'));
                if(isset($payload->userId)  || $payload->userId != ''){

                    if (isset($payload->exp)) {
                        $returnArray['exp'] = date(DateTime::ISO8601, $payload->exp);;
                    }
                    $returnArray['status'] = true;
                    $returnArray['kode_lokasi'] = $payload->kode_lokasi;
                    $returnArray['nik_user'] = $payload->userId;
                    $returnArray['periode'] = $payload->periode;
                }
                

            }
            catch(Exception $e) {
                $returnArray = array('message' => $e->getMessage().'serverKey: '.$serverKey.'. token: '.$token,'status'=>false);
            }
            
        // }else{
        //     $returnArray = array('message' => 'serverKey does not exist','status'=>false);
        // }
        return $returnArray;
    }

	function getView(){
        getKoneksi();
        $data = $_GET;
        $header = getallheaders();
        $bearer = $header["Authorization"];
		list($token) = sscanf($bearer, 'Bearer %s');
		$res = authKey($token); 
        if($res["status"]){ 
    
            if(isset($data['kode_lokasi']) && $data['kode_lokasi'] != ""){
                $kode_lokasi=$data['kode_lokasi'];
            }else{
                $kode_lokasi=$res["kode_lokasi"];
            }

            if(isset($data['kode_fs']) && $data['kode_fs'] != ""){
                $filter = " and kode_fs='".$data['kode_fs']."' ";
            }else{
                $filter = "";
            }
            
            $sql = "select kode_fs,kode_lokasi,nama,tgl_awal,tgl_akhir,flag_status,tgl_input,nik_user  from fs where kode_lokasi='$kode_lokasi' $filter";
            // $response['sql']=$sql;
            $response['daftar'] = dbResultArray($sql);
            $response['status'] = true;
            $response['rows']=count($response['daftar']);
        }else{
            $response['status'] = false;
            $response['message'] = "Unauthorized Access ".$res["message"];
            
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    function simpan(){
        getKoneksi();
        $data = $_POST;
        $header = getallheaders();
        $bearer = $header["Authorization"];
		list($token) = sscanf($bearer, 'Bearer %s');
		$res = authKey($token); 
        if($res["status"]){ 
    
            if(isset($data['kode_lokasi']) && $data['kode_lokasi'] != ""){
                $kode_lokasi=$data['kode_lokasi'];
            }else{
                $kode_lokasi=$res["kode_lokasi"];
            }
            $exec = array();
            
            $sql = "insert into fs (kode_fs,kode_lokasi,nama,tgl_awal,tgl_akhir,flag_status,tgl_input,nik_user) values ('".$data['kode_fs']."','$kode_lokasi','".$data['nama']."','".$data['tgl_awal']."','".$data['tgl_akhir']."','".$data['flag_status']."',getdate(),'".$data['nik_user']."') ";
            array_push($exec,$sql);
            $rs = executeArray($exec,$err);
            if($err == null){
                $tmp= "Data FS berhasil disimpan";
                $sts=true;
            }else{
                
                $tmp= "Data FS gagal disimpan. ".$err;
                $sts=false;
            }

            $response['message'] = $tmp;
            $response['status'] = $sts;
        }else{
            $response['status'] = false;
            $response['message'] = "Unauthorized Access ".$res["message"];
            
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    function ubah(){
        getKoneksi();
        parse_str(file_get_contents('php://input'), $_PUT);
        $data = $_PUT;
        $header = getallheaders();
        $bearer = $header["Authorization"];
		list($token) = sscanf($bearer, 'Bearer %s');
		$res = authKey($token); 
        if($res["status"]){ 
    
            if(isset($data['kode_lokasi']) && $data['kode_lokasi'] != ""){
                $kode_lokasi=$data['kode_lokasi'];
            }else{
                $kode_lokasi=$res["kode_lokasi"];
            }
            $exec = array();
            $del = "delete from fs where kode_lokasi='$kode_lokasi' and kode_fs = '".$data['kode_fs']."'";
            array_push($exec,$del);
            
            $sql = "insert into fs (kode_fs,kode_lokasi,nama,tgl_awal,tgl_akhir,flag_status,tgl_input,nik_user) values ('".$data['kode_fs']."','$kode_lokasi','".$data['nama']."','".$data['tgl_awal']."','".$data['tgl_akhir']."','".$data['flag_status']."',getdate(),'".$data['nik_user']."') ";
            array_push($exec,$sql);
            $rs = executeArray($exec,$err);
            if($err == null){
                $tmp= "Data FS berhasil diubah";
                $sts=true;
            }else{
                
                $tmp= "Data FS gagal diubah. ".$err;
                $sts=false;
            }

            $response['message'] = $tmp;
            $response['status'] = $sts;
        }else{
            $response['status'] = false;
            $response['message'] = "Unauthorized Access ".$res["message"];
            
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    function hapus(){
        getKoneksi();
        parse_str(file_get_contents('php://input'), $_DELETE);
        $data = $_DELETE;
        $header = getallheaders();
        $bearer = $header["Authorization"];
		list($token) = sscanf($bearer, 'Bearer %s');
		$res = authKey($token); 
        if($res["status"]){ 
    
            if(isset($data['kode_lokasi']) && $data['kode_lokasi'] != ""){
                $kode_lokasi=$data['kode_lokasi'];
            }else{
                $kode_lokasi=$res["kode_lokasi"];
            }
            $exec = array();
            $del = "delete from fs where kode_lokasi='$kode_lokasi' and kode_fs = '".$data['kode_fs']."'";
            array_push($exec,$del);
           
            $rs = executeArray($exec,$err);
            if($err == null){
                $tmp= "Data FS berhasil dihapus";
                $sts=true;
            }else{
                
                $tmp= "Data FS gagal dihapus. ".$err;
                $sts=false;
            }

            $response['message'] = $tmp;
            $response['status'] = $sts;
        }else{
            $response['status'] = false;
            $response['message'] = "Unauthorized Access ".$res["message"];
            
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }



?>
