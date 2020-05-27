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

    function getAbsen(){
		
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
            $sql = "select a.nis, a.nama , isnull(b.hadir,0) as hadir,isnull(b.alpa,0) as alpha,isnull(b.izin,0) as izin,isnull(b.sakit,0) as sakit 
            from sis_siswa a 
            left join (select a.nis,a.kode_lokasi,count(case when a.status ='hadir' then status end) hadir,
                       count(case when a.status ='alpa' then status end) alpa,
                       count(case when a.status ='izin' then status end) izin,
                       count(case when a.status ='sakit' then status end) sakit  
                        from sis_presensi a
                        inner join sis_ta b on a.kode_ta=b.kode_ta and a.kode_pp=b.kode_pp
                        inner join sis_kelas c on a.kode_kelas=c.kode_kelas and a.kode_pp=c.kode_pp
                        inner join sis_siswa d on a.nis=d.nis and a.kode_lokasi=d.kode_lokasi and a.kode_pp=d.kode_pp
                        where a.kode_lokasi='$kode_lokasi' and a.kode_pp='$kode_pp' and a.nis='$nik' 
            group by a.nis,a.kode_lokasi) b on a.nis=b.nis and a.kode_lokasi=b.kode_lokasi
            where a.kode_lokasi='$kode_lokasi' and a.kode_pp='$kode_pp' and a.nis='$nik'
           ";
            $response['daftar'] = dbResultArray($sql);

			$sql = " select tanggal,convert(varchar(5),tgl_input,108) as jam, status from sis_presensi  where kode_lokasi='$kode_lokasi' and kode_pp='$kode_pp' and nis='$nik'
            ";
            $response["daftar2"]=dbResultArray($sql);
            
			$response['status'] = true;
        }else{
			$response["status"] = false;
			$response["message"] = "Unauthorized Access. ".$res["message"];
		}
		header('Content-Type: application/json');
		echo json_encode($response);
    }
    
    

?>