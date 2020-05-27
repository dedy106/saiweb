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
        // case 'PUT':
        //     updatePeserta();
        // break;
        case 'DELETE':
            deletePeserta();
        break;
        default:
            // Invalid Request Method
            header("HTTP/1.0 405 Method Not Allowed");
        break;
    }

    function getKoneksi(){
        $root_lib=$_SERVER["DOCUMENT_ROOT"];
        
        include_once($root_lib."lib/koneksi7.php");
        include_once($root_lib."lib/helpers.php");
        require_once($root_lib.'lib/jwt.php');
    }

    function generateToken(){
        getKoneksi();
        $data = $_POST;
        if(arrayKeyCheck(array('nik','pass'), $data)){

            $nik=$data['nik'];
            $pass=$data['pass'];
            
            $response = array("message" => "", "status" => "" );
            $exec=array();
            
            $nik = '';
            $pass = '';
        
            if (isset($_POST['nik'])) {$nik = $_POST['nik'];}
            if (isset($_POST['pass'])) {$pass = $_POST['pass'];}

            $sql="select a.kode_klp_menu, a.nik, a.nama, a.pass, a.status_admin, a.klp_akses, a.kode_lokasi,b.nama as nmlok, c.kode_pp,d.nama as nama_pp,
			b.kode_lokkonsol,d.kode_bidang, c.foto,isnull(e.form,'-') as path_view,b.logo
            from hakakses a 
            inner join lokasi b on b.kode_lokasi = a.kode_lokasi 
            left join karyawan c on a.nik=c.nik and a.kode_lokasi=c.kode_lokasi 
            left join pp d on c.kode_pp=d.kode_pp and c.kode_lokasi=d.kode_lokasi 
            left join m_form e on a.path_view=e.kode_form 
            where a.nik= '$nik' and a.pass='$pass' ";
            $rs=execute($sql,$error);
        
            $row = $rs->FetchNextObject(false);
            if($rs->RecordCount() > 0){
                
                $userId = $nik;
                $sql1="select max(periode) as periode from periode where kode_lokasi='34' ";
                $p = execute($sql1);

                // $sql2 = "select server_key from api_server_key where kode_lokasi='$row->kode_lokasi' and  modul='SPPD' ";
                // $rs2=execute($sql2);
                // if($rs2->RecordCount()>0){
                //     $serverKey = $rs2->fields[0];
                    // create a token
                    $serverKey= "bccf9112d48a8aa444dd73e762cf263c";
                    
                    $payloadArray = array();
                    $payloadArray['userId'] = $userId;
                    $payloadArray['kode_lokasi'] = $row->kode_lokasi;
                    $payloadArray['periode'] = $p->fields[0];
                    if (isset($nbf)) {$payloadArray['nbf'] = $nbf;}
                    if (isset($exp)) {$payloadArray['exp'] = $exp;}
                    $token = JWT::encode($payloadArray, $serverKey);
                    // return to caller
                    $response = array('token' => $token,'status'=>true,'message'=>'Login Success');


                // }else{
                //     $response = array('status'=>false,'message'=>'Server key does not exist','sql'=>$sql2);
                // }
    
            } 
            else {
                $response = array('message' => 'Invalid user ID or password.','status'=>false);
               
            }

        }else{
            $response = array('message' => "Kode Lokasi, Modul, and NIK required",'status'=>false);
        }
        
        echo json_encode($response);

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

    
    function generateKode($tabel, $kolom_acuan, $prefix, $str_format){
        $query = execute("select right(max($kolom_acuan), ".strlen($str_format).")+1 as id from $tabel where $kolom_acuan like '$prefix%'");
        $kode = $query->fields[0];
        $id = $prefix.str_pad($kode, strlen($str_format), $str_format, STR_PAD_LEFT);
        return $id;
    }

	function getPeriodeAktif(){
        getKoneksi();
        $data = $_GET;
        $header = getallheaders();
        $bearer = $header["Authorization"];
		list($token) = sscanf($bearer, 'Bearer %s');
		$res = authKey($token); 
        // $res = authKey($data["token"],"SPPD",$data["kode_lokasi"]);
        if($res["status"]){ 
    
            if(isset($data['kode_lokasi']) && $data['kode_lokasi'] != ""){

                $kode_lokasi=$data['kode_lokasi'];
            }else{
                $kode_lokasi=$res["kode_lokasi"];
            }
            
         

            $response = array("message" => "", "rows" => 0, "status" => "" );
           
            $sql="select max(periode) as periode from periode where kode_lokasi='$kode_lokasi' ";

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
	
	function getAju(){
        getKoneksi();
        $data = $_GET;
        $header = getallheaders();
        $bearer = $header["Authorization"];
		list($token) = sscanf($bearer, 'Bearer %s');
		$res = authKey($token); 
        // $res = authKey($data["token"],"SPPD",$data["kode_lokasi"]);
        if($res["status"]){ 
    
            if(isset($data['kode_lokasi']) && $data['kode_lokasi'] != ""){

                $kode_lokasi=$data['kode_lokasi'];
            }else{
                $kode_lokasi=$res["kode_lokasi"];
            }
            
         

            $response = array("message" => "", "rows" => 0, "status" => "" );
           
            $sql="select a.due_date,a.no_pb as no_bukti,'INPROG' as status,convert(varchar,a.tanggal,103) as tgl,convert(varchar,a.due_date,103) as tgl2,a.modul,b.kode_pp+' - '+b.nama as pp,'-' as no_dokumen,a.keterangan,a.nilai,c.nik+' - '+c.nama as pembuat,a.no_app2,a.kode_lokasi,convert(varchar,a.tgl_input,120) as tglinput,b.kode_pp 
			from yk_pb_m a 
			inner join pp b on a.kode_pp=b.kode_pp and a.kode_lokasi=b.kode_lokasi 
			inner join karyawan c on a.nik_user=c.nik and a.kode_lokasi=c.kode_lokasi 
			where a.progress='1' and a.kode_lokasi='34' and a.modul in ('PBBAU','PBPR','PBINV') 					 
			union 			
			select a.due_date,a.no_panjar as no_bukti,'INPROG' as status,convert(varchar,a.tanggal,103) as tgl,convert(varchar,a.due_date,103) as tgl2,a.modul,b.kode_pp+' - '+b.nama as pp,'-' as no_dokumen,a.keterangan,a.nilai,c.nik+' - '+c.nama as pembuat,a.no_app2,a.kode_lokasi,convert(varchar,a.tgl_input,120) as tglinput,b.kode_pp 
			from panjar2_m a 
			inner join pp b on a.kode_pp=b.kode_pp and a.kode_lokasi=b.kode_lokasi 
			inner join karyawan c on a.nik_buat=c.nik and a.kode_lokasi=c.kode_lokasi 
			where a.progress='1' and a.kode_lokasi='34' and a.modul in ('PJAJU','PJPR')  
			order by a.due_date
			";
			
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
	
    function getAjuDetail(){
        getKoneksi();
        $data = $_GET;
        $header = getallheaders();
        $bearer = $header["Authorization"];
		list($token) = sscanf($bearer, 'Bearer %s');
		$res = authKey($token); 
        // $res = authKey($data["token"],"SPPD",$data["kode_lokasi"]);
        if($res["status"]){ 
            $response = array("message" => "", "rows" => 0, "status" => "" );
			$modul=$data['modul'];
			$no_aju=$data['no_aju'];
			
			if ($modul=='PBBAU' || $modul=='PBPR' || $modul=='PBINV') {	
				$sql="select a.due_date,a.no_pb as no_bukti,'INPROG' as status,convert(varchar,a.tanggal,103) as tgl,convert(varchar,a.due_date,103) as tgl2,a.modul,b.kode_pp+' - '+b.nama as pp,'-' as no_dokumen,a.keterangan,a.nilai,c.nik+' - '+c.nama as pembuat,a.no_app2,a.kode_lokasi,convert(varchar,a.tgl_input,120) as tglinput,b.kode_pp 
					from yk_pb_m a 
					inner join pp b on a.kode_pp=b.kode_pp and a.kode_lokasi=b.kode_lokasi 
					inner join karyawan c on a.nik_user=c.nik and a.kode_lokasi=c.kode_lokasi 
					where a.progress='1' and a.kode_lokasi='34' and a.modul in ('PBBAU','PBPR','PBINV') and a.no_pb='$no_aju' ";
			}
			
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

	function getAjuRek(){
        getKoneksi();
        $data = $_GET;
        $header = getallheaders();
        $bearer = $header["Authorization"];
		list($token) = sscanf($bearer, 'Bearer %s');
		$res = authKey($token); 
        // $res = authKey($data["token"],"SPPD",$data["kode_lokasi"]);
        if($res["status"]){ 
            $response = array("message" => "", "rows" => 0, "status" => "" );
			$no_aju=$data['no_aju'];
			$sql="select a.bank,a.cabang,a.no_rek,a.nama_rek,a.bruto,a.pajak
				from spm_rek a
				where a.no_bukti ='$no_aju' and a.kode_lokasi='34' ";
	
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
	
	function getAjuJurnal(){
        getKoneksi();
        $data = $_GET;
        $header = getallheaders();
        $bearer = $header["Authorization"];
		list($token) = sscanf($bearer, 'Bearer %s');
		$res = authKey($token); 
        // $res = authKey($data["token"],"SPPD",$data["kode_lokasi"]);
        if($res["status"]){ 
            $response = array("message" => "", "rows" => 0, "status" => "" );
			$modul=$data['modul'];
			$no_aju=$data['no_aju'];
			$modul=$data['modul'];
			if ($modul=='PBBAU' || $modul=='PBPR' || $modul=='PBINV') {	
				$sql="select b.kode_akun,b.nama as nama_akun,a.dc,a.keterangan,a.nilai,a.kode_pp,c.nama as nama_pp,d.kode_proyek,
						isnull(e.nama,'-') as nama_proyek 
				from yk_pb_j a 
				inner join masakun b on a.kode_akun=b.kode_akun and a.kode_lokasi=b.kode_lokasi 
				inner join pp c on a.kode_pp=c.kode_pp and a.kode_lokasi=c.kode_lokasi 							
				inner join yk_pb_m d on a.no_pb=d.no_pb and a.kode_lokasi=d.kode_lokasi 	
				left join spm_proyek e on d.kode_proyek=e.kode_proyek and d.kode_lokasi=e.kode_lokasi 
				where a.no_pb = '$no_aju' and a.kode_lokasi='34'";
			}
			
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
