<?php
    $request_method=$_SERVER["REQUEST_METHOD"];

    switch($request_method) {
        case 'GET':
            if(isset($_GET["fx"]) AND function_exists($_GET['fx'])){
                $_GET['fx']();
            }
        break;
        case 'POST':
            if(isset($_GET["fx"]) AND function_exists($_GET['fx'])){
                $_GET['fx']();
            }
        break;
    }

    function getKoneksi(){
        $root_lib=$_SERVER["DOCUMENT_ROOT"];
        include_once($root_lib."lib/koneksi5.php");
        include_once($root_lib."lib/helpers.php");
        require_once($root_lib.'lib/jwt.php');
    }


    function authKey($token){
        getKoneksi();
        $token = $token;
        $date = date('Y-m-d H:i:s');

        $schema = db_Connect();
        $serverKey = "bccf9112d48a8aa444dd73e762cf263c";

        try {
            $payload = JWT::decode($token, $serverKey, array('HS256'));
            if(isset($payload->userId)  || $payload->userId != ''){
                
                if (isset($payload->exp)) {
                    $returnArray['exp'] = date(DateTime::ISO8601, $payload->exp);;
                }
                $returnArray['status'] = true;
            }
            
        }
        catch(Exception $e) {
            $returnArray = array('message' => $e->getMessage(),'status'=>false);
        }
        
        return $returnArray;
    }

    function getRaport(){
		
        getKoneksi();
        $data=$_GET;
        $header = getallheaders();
		$token = $header["Authorization"];
		// list($token) = sscanf($bearer, 'Bearer %s');
        $res = authKey($token);
		// $res = authKey($data["token"]);
        if($res["status"]){ 
			$nik = $data['nik'];
			$kode_pp = $data['kode_pp'];
			$kode_lokasi = $data['kode_lokasi'];
            $sql = "select b.kode_matpel,c.nama, isnull(b.nilai,0) as nilai,isnull(c.kkm,0) as kkm from sis_raport_m a
            inner join sis_raport_d b on a.no_bukti=b.no_bukti and a.kode_lokasi=b.kode_lokasi and a.kode_pp=b.kode_pp
            inner join sis_matpel c on b.kode_matpel=c.kode_matpel and b.kode_lokasi=c.kode_lokasi and b.kode_pp=c.kode_pp
            where a.kode_lokasi='$kode_lokasi' and a.kode_pp='$kode_pp' and a.nis='$nik'
           ";
            $response['daftar'] = dbResultArray($sql);
			$response['status'] = true;
        }else{
			$response["status"] = false;
			$response["message"] = "Unauthorized Access. ".$res["message"];
		}
		header('Content-Type: application/json');
		echo json_encode($response);
    }
    
    

?>