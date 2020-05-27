<?php
    $request_method=$_SERVER["REQUEST_METHOD"];

    switch($request_method) {
        case 'GET':
            getMenu();
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
                $returnArray = array('message' => $e->getMessage().'. token: '.$token,'status'=>false);
            }
            
        // }else{
        //     $returnArray = array('message' => 'serverKey does not exist','status'=>false);
        // }
        return $returnArray;
    }

	function getMenu(){
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
            $kode_menu = $data['kode_klp'];
            
            $sql = "select a.*,b.form from menu a left join m_form b on a.kode_form=b.kode_form where a.kode_klp = '$kode_menu' and (isnull(a.jenis_menu,'-') = '-' OR a.jenis_menu = '') order by kode_klp, rowindex ";
            $response = array("message" => "", "rows" => 0, "status" => "" );
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



?>
