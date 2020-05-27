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

    function getKodeTA(){
		
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
            $kode_kelas = $data['kode_kelas'];
            
            $sql = "select kode_ta from sis_ta where flag_aktif='1' and kode_pp='$kode_pp' and kode_lokasi='$kode_lokasi'            
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

    function getJadwalSekarang(){
		
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
            $kode_hari = $data['kode_hari'];
            $kode_ta = $data['kode_ta'];
            
            $sql = "select a.kode_kelas,a.kode_matpel,b.jam1,b.jam2,c.nama as matpel,'-' as status 
            from sis_jadwal a 
            inner join sis_slot b on a.kode_slot=b.kode_slot and a.kode_lokasi=b.kode_lokasi and a.kode_pp=b.kode_pp
            inner join sis_matpel c on a.kode_matpel=c.kode_matpel and a.kode_pp=c.kode_pp and a.kode_lokasi=c.kode_lokasi
            where a.kode_ta='$kode_ta' and a.kode_pp='$kode_pp' and a.kode_lokasi='$kode_lokasi' and a.nik='$nik' and a.kode_hari='$kode_hari' 
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

    function getAbsenTotal(){
		
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
            $kode_hari = $data['kode_hari'];
            $kode_ta = $data['kode_ta'];
            $jam = $data['jam'];
            $tanggal = $data['tanggal'];
            
            $sql = "select a.kode_kelas,isnull(b.jum,0) as jum_sis,isnull(c.jum_hadir,0) as jum_hadir,isnull(c.jum_sakit,0) as jum_sakit,isnull(c.jum_izin,0) as jum_izin,isnull(c.jum_alpa,0) as jum_alpa
            from sis_jadwal a 
            inner join sis_slot x on a.kode_slot=x.kode_slot and a.kode_lokasi=x.kode_lokasi
            left join (select kode_kelas,kode_pp,kode_lokasi,count(nis) as jum
                        from sis_siswa 
                        where flag_aktif='1'
                        group by kode_kelas,kode_pp,kode_lokasi) b on a.kode_kelas=b.kode_kelas and a.kode_pp=b.kode_pp and a.kode_lokasi=b.kode_lokasi
            left join (select kode_kelas,kode_pp,kode_lokasi,kode_matpel,count(case when status='Hadir' then nis end) jum_hadir,
                        count(case when status='Sakit' then nis end) jum_sakit,
                        count(case when status='Alpa' then nis end) jum_alpa,
                        count(case when status='Izin' then nis end) jum_izin
                        from sis_presensi
                        where tanggal = '$tanggal'
                        group by kode_kelas,kode_pp,kode_lokasi,kode_matpel) c on a.kode_kelas=c.kode_kelas and a.kode_pp=c.kode_pp and a.kode_lokasi=c.kode_lokasi and a.kode_matpel=c.kode_matpel
            where a.kode_ta='$kode_ta' and a.kode_pp='$kode_pp' and a.kode_lokasi='$kode_lokasi' and a.nik='$nik' and a.kode_hari='$kode_hari' and '$jam' between x.jam1 and x.jam2
           ";
            $response['daftar'] = dbResultArray($sql);
            // $response['sql']=$sql;
			$response['status'] = true;
        }else{
			$response["status"] = false;
			$response["message"] = "Unauthorized Access. ".$res["message"];
		}
		header('Content-Type: application/json');
		echo json_encode($response);
    }

    function getDaftarSiswa(){
		
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
            $kode_kelas = $data['kode_kelas'];
            
            $sql = "select a.nis,a.nama,'HADIR' as sts
            from sis_siswa a
            where a.flag_aktif='1' and a.kode_kelas = '$kode_kelas' and a.kode_lokasi='$kode_lokasi' and a.kode_pp='$kode_pp'             
           ";
            $response['daftar'] = dbResultArray($sql);
            // $response['sql']=$sql;
			$response['status'] = true;
        }else{
			$response["status"] = false;
			$response["message"] = "Unauthorized Access. ".$res["message"];
		}
		header('Content-Type: application/json');
		echo json_encode($response);
    }

    function getEditAbsen(){
		
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
            $kode_kelas = $data['kode_kelas'];
			$matpel = $data['kode_matpel'];
            $tanggal = $data['tanggal'];
            
            $sql = "select a.nis,a.nama,isnull(b.status,'-') as sts
            from sis_siswa a
            left join (select a.nis,kode_pp,kode_lokasi,a.status,a.kode_kelas
                        from sis_presensi a
                        where a.tanggal = '$tanggal' and a.kode_matpel='$matpel'
                        ) b on a.nis=b.nis and a.kode_pp=b.kode_pp and a.kode_lokasi=b.kode_lokasi and a.kode_kelas=b.kode_kelas
            where a.flag_aktif='1' and a.kode_kelas = '$kode_kelas' and a.kode_lokasi='$kode_lokasi' and a.kode_pp='$kode_pp'           
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

    function insertAbsen(){
		
        getKoneksi();
        if(empty($_POST)){
            $data = json_decode(file_get_contents('php://input'), true);
        }else{
            $data = $_POST;
        }
        $header = getallheaders();
		$token = $header["Authorization"];
		// list($token) = sscanf($bearer, 'Bearer %s');
        $res = authKey($token);
		// $res = authKey($data["token"]);
        if($res["status"]){ 
			$nik = $data['nik'];
			$kode_pp = $data['kode_pp'];
			$kode_lokasi = $data['kode_lokasi'];
            $kode_kelas = $data['kode_kelas'];
            $kode_ta = $data['kode_ta'];
			$matpel = $data['kode_matpel'];
            $tanggal = $data['tanggal'];
            $stsSimpan = $data['status_simpan'];
            
            $exec = array();
            if ($stsSimpan == 0) {
               $del = "delete from sis_presensi where kode_kelas = '".$kode_kelas."' and tanggal = '".$tanggal."' and kode_ta = '".$kode_ta."' and kode_matpel='$matpel' and kode_lokasi='".$kode_lokasi."' and jenis_absen='HARIAN'";
               array_push($exec,$del);
            }		
            $data = $data["DETAIL"];	
            if(count($data) > 0){

                for ($i=0;$i < count($data);$i++){
                     
                     $sql = "insert into sis_presensi(nis,kode_kelas,kode_ta,tgl_input,status,kode_lokasi,kode_pp,keterangan,tanggal,jenis_absen,kode_matpel,nik) values 
                             ('".$data[$i]['nis']."','".$kode_kelas."','".$kode_ta."',getdate(),'".$data[$i]['status']."','".$kode_lokasi."','".$kode_pp."','-','".$tanggal."','HARIAN','$matpel','$nik')";
                    array_push($exec,$sql);
                }	
            }

            $rs = executeArray($exec,$err);
            if($err == null){
                $sts = true;
                $msg = "berhasil";
            }else{
                
                $sts = false;
                $msg = $err;
            }

			$response["status"] = $sts;
			$response["message"] = $msg;
        }else{
			$response["status"] = false;
			$response["message"] = "Unauthorized Access. ".$res["message"];
		}
		header('Content-Type: application/json');
		echo json_encode($response);
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
            $kode_ta = $data['kode_ta'];           

			$sql = "select a.kode_slot,a.kode_kelas, a.kode_hari, a.kode_matpel,d.nama as nama_matpel, a.nik,e.nama as nama_guru,c.jam1,c.jam2 
            from sis_jadwal a
                        inner join sis_slot c on a.kode_slot=c.kode_slot and a.kode_pp=c.kode_pp and a.kode_lokasi=c.kode_lokasi
                        inner join sis_matpel d on a.kode_matpel=d.kode_matpel and a.kode_pp=d.kode_pp and a.kode_lokasi=d.kode_lokasi
                        inner join karyawan e on a.nik=e.nik and a.kode_pp=e.kode_pp and a.kode_lokasi=e.kode_lokasi
                        where a.nik='$nik' and a.kode_lokasi='$kode_lokasi' and a.kode_pp='$kode_pp' and a.kode_ta='$kode_ta'
                        order by a.kode_slot,a.kode_hari  ";
            $response["daftar"] = dbResultArray($sql);
            // $rs = execute($sql);
            // $data=array();
            // while($row=$rs->FetchNextObject($toupper=false)){
            //     $sub_array = array();
            //     // $tmp = explode(" ",$row->nama_slot);
            //     // $jam1 = str_replace("[","",$tmp[2]);
            //     // $jam2 = str_replace("]","",$tmp[4]);
            //     $sub_array =array('kode_slot'=>$row->kode_slot,'nama_slot'=>$row->nama_slot,'kode_kelas'=>$row->kode_kelas,'kode_hari'=>$row->kode_hari,'kode_matpel'=>$row->kode_matpel,'nama_matpel'=>$row->nama_matpel,'nis'=>$row->nis,'nik'=>$row->nik,'nama_guru'=>$row->nama_guru,'jam1'=>$jam1,'jam2'=>$jam2,'absen'=>"-");
            //     $data[] = $sub_array;
            // }

            // $response["daftar2"]=$data;
            
			$response['status'] = true;
        }else{
			$response["status"] = false;
			$response["message"] = "Unauthorized Access. ".$res["message"];
		}
		header('Content-Type: application/json');
		echo json_encode($response);
    }

    
    
    

?>