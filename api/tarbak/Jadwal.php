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
        include_once($root_lib."lib/koneksi6.php");
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

    function getJadwal(){
		
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
            $kode_lokasi = $_GET['kode_lokasi'];
            $sql = "select kode_hari,nama from sis_hari where kode_lokasi='$kode_lokasi' and kode_pp='$kode_pp' ";
            $response['daftar'] = dbResultArray($sql);

			$sql = "select a.kode_slot, c.nama as nama_slot,a.kode_kelas, a.kode_hari, a.kode_matpel,d.nama as nama_matpel, b.nis,a.nik,e.nama as nama_guru from sis_jadwal a
            inner join sis_siswa b on a.kode_kelas=b.kode_kelas and a.kode_pp=b.kode_pp and a.kode_lokasi=b.kode_lokasi
            inner join sis_slot c on a.kode_slot=c.kode_slot and a.kode_pp=c.kode_pp and a.kode_lokasi=c.kode_lokasi
            inner join sis_matpel d on a.kode_matpel=d.kode_matpel and a.kode_pp=d.kode_pp and a.kode_lokasi=d.kode_lokasi
            inner join karyawan e on a.nik=e.nik and a.kode_pp=e.kode_pp and a.kode_lokasi=e.kode_lokasi
            where b.nis='$nik' and b.kode_lokasi='$kode_lokasi' and b.kode_pp='$kode_pp'  order by kode_slot,kode_hari ";
            $rs = execute($sql);
            $data=array();
            while($row=$rs->FetchNextObject($toupper=false)){
                $sub_array = array();
                $tmp = explode(" ",$row->nama_slot);
                $jam1 = str_replace("[","",$tmp[2]);
                $jam2 = str_replace("]","",$tmp[4]);
                $sub_array =array('kode_slot'=>$row->kode_slot,'nama_slot'=>$row->nama_slot,'kode_kelas'=>$row->kode_kelas,'kode_hari'=>$row->kode_hari,'kode_matpel'=>$row->kode_matpel,'nama_matpel'=>$row->nama_matpel,'nis'=>$row->nis,'nik'=>$row->nik,'nama_guru'=>$row->nama_guru,'jam1'=>$jam1,'jam2'=>$jam2,'absen'=>"-");
                $data[] = $sub_array;
            }

            $response["daftar2"]=$data;
            
			$response['status'] = true;
        }else{
			$response["status"] = false;
			$response["message"] = "Unauthorized Access. ".$res["message"];
		}
		header('Content-Type: application/json');
		echo json_encode($response);
    }
    
    

?>