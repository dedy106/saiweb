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


    function authKey($token, $modul=null,$kode_lokasi=null){
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

    // get Kartu Piutang
    function getPiutang(){
		
        getKoneksi();
        $data=$_GET;
        $header = getallheaders();
        $token = $header["Authorization"];
		$res = authKey($token);
        if($res["status"]){ 
			$nik = $data['nik'];
			$kode_pp = $data['kode_pp'];
			$kode_lokasi = $data['kode_lokasi'];
            $kode_lokasi = $_GET['kode_lokasi'];
			$sql = "select a.nis,a.kode_lokasi,a.kode_pp,a.nama,a.kode_kelas,b.nama as nama_kelas,a.kode_lokasi,b.kode_jur,c.nama as nama_jur,a.kode_akt,a.id_bank,x.nama as nama_pp
			from sis_siswa a
			inner join sis_kelas b on a.kode_kelas=b.kode_kelas and a.kode_lokasi=b.kode_lokasi and a.kode_pp=b.kode_pp
			inner join sis_jur c on b.kode_jur=c.kode_jur and 
			b.kode_lokasi=c.kode_lokasi and b.kode_pp=c.kode_pp
			inner join pp x on a.kode_pp=x.kode_pp and a.kode_lokasi=x.kode_lokasi
			left join (select a.nis,a.kode_pp,a.kode_lokasi
						from sis_cd_d a
						where a.kode_lokasi='$kode_lokasi' and a.kode_pp='$kode_pp'
						group by a.nis,a.kode_pp,a.kode_lokasi
						)g on a.nis=g.nis and a.kode_lokasi=g.kode_lokasi and a.kode_pp=g.kode_pp
			where a.kode_lokasi='$kode_lokasi' and a.kode_pp='$kode_pp' and a.nis='$nik'
            order by a.kode_kelas,a.nis";
			$response["daftar"]=dbResultArray($sql);
			
			$sql="select case when sum(debet-kredit) < 0 then 0 else sum(debet-kredit) end as so_akhir
			from (select a.no_bukti,a.nilai,convert(varchar(20),b.tanggal,103) as tgl,b.keterangan,b.modul,b.tanggal,
			a.nilai as debet,0 as kredit,a.dc
			from sis_cd_d a
			inner join kas_m b on a.no_bukti=b.no_kas and a.kode_lokasi=b.kode_lokasi
			where a.nis='$nik' and a.kode_lokasi='$kode_lokasi' and a.kode_pp='$kode_pp' and a.dc='D'
			union all
			select a.no_bukti,a.nilai,convert(varchar(20),b.tanggal,103) as tgl,b.keterangan,b.modul,b.tanggal,
			case when a.dc='D' then a.nilai else 0 end as debet,case when a.dc='C' then a.nilai else 0 end as kredit,a.dc
			from sis_cd_d a
			inner join sis_rekon_m b on a.no_bukti=b.no_rekon and a.kode_lokasi=b.kode_lokasi
			where a.nis='$nik' and a.kode_lokasi='$kode_lokasi' and a.kode_pp='$kode_pp' 
			union all
			select a.no_bukti,a.nilai,convert(varchar(20),b.tanggal,103) as tgl,b.keterangan,b.modul,b.tanggal,
			0 as debet,case when a.dc='C' then a.nilai else 0 end as kredit,a.dc
			from sis_cd_d a
			inner join kas_m b on a.no_bukti=b.no_kas and a.kode_lokasi=b.kode_lokasi
			where a.nis='$nik' and a.kode_lokasi='$kode_lokasi' and a.kode_pp='$kode_pp' and a.dc='C'
			
			)a";
			
            // echo $sql;
            
			$saldo= dbResultArray($sql);
			$response['so_akhir']=$saldo[0]["so_akhir"];
			
			
            $sql2="select a.no_bill as no_bukti,a.kode_lokasi,b.tanggal,convert(varchar(10),b.tanggal,103) as tgl,b.keterangan,'BILL' as modul, isnull(a.tagihan,0) as tagihan,isnull(a.bayar,0) as bayar,a.kode_param from (select x.kode_lokasi,x.no_bill,x.kode_param,sum(x.nilai) as tagihan,0 as bayar from sis_bill_d x inner join sis_siswa y on x.nis=y.nis and x.kode_lokasi=y.kode_lokasi and x.kode_pp=y.kode_pp where y.flag_aktif=1 and x.kode_lokasi = '$kode_lokasi' and x.nis='$nik' and x.kode_pp='$kode_pp' and x.nilai<>0 group by x.kode_lokasi,x.no_bill,x.nis,x.kode_param )a inner join sis_bill_m b on a.no_bill=b.no_bill and a.kode_lokasi=b.kode_lokasi union all select a.no_rekon as no_bukti,a.kode_lokasi,b.tanggal,convert(varchar(10),b.tanggal,103) as tgl,b.keterangan,'PDD' as modul, isnull(a.tagihan,0) as tagihan,isnull(a.bayar,0) as bayar,a.kode_param from (select x.kode_lokasi,x.no_rekon,x.kode_param,0 as tagihan,sum(nilai) as bayar from sis_rekon_d x inner join sis_siswa y on x.nis=y.nis and x.kode_lokasi=y.kode_lokasi and x.kode_pp=y.kode_pp where y.flag_aktif=1 and x.kode_lokasi = '$kode_lokasi' and x.nis='$nik' and x.kode_pp='$kode_pp' and x.nilai<>0 group by x.kode_lokasi,x.no_rekon,x.nis,x.kode_param )a inner join sis_rekon_m b on a.no_rekon=b.no_rekon and a.kode_lokasi=b.kode_lokasi union all select a.no_rekon as no_bukti,a.kode_lokasi,b.tanggal,convert(varchar(10),b.tanggal,103) as tgl,b.keterangan,'KB' as modul, isnull(a.tagihan,0) as tagihan,isnull(a.bayar,0) as bayar,a.kode_param from (select x.kode_lokasi,x.no_rekon,x.kode_param,0 as tagihan,sum(nilai) as bayar from sis_rekon_d x inner join sis_siswa y on x.nis=y.nis and x.kode_lokasi=y.kode_lokasi and x.kode_pp=y.kode_pp where y.flag_aktif=1 and x.kode_lokasi = '$kode_lokasi' and x.nis='$nik' and x.kode_pp='$kode_pp' and x.nilai<>0 group by x.kode_lokasi,x.no_rekon,x.nis,x.kode_param )a inner join kas_m b on a.no_rekon=b.no_kas and a.kode_lokasi=b.kode_lokasi order by tanggal,modul";
            $response["daftar2"]=dbResultArray($sql2);
            
			$response['status'] = true;
        }else{
			$response["status"] = false;
			$response["message"] = "Unauthorized Access. ".$res["message"];
		}
		header('Content-Type: application/json');
		echo json_encode($response);
    }
    
    // get Kartu PDD/Deposit
    function getPDD(){
		
        getKoneksi();
        $data=$_GET;
        $header = getallheaders();
        $token = $header["Authorization"];
		$res = authKey($token);
        if($res["status"]){ 
			$nik = $data['nik'];
			$kode_pp = $data['kode_pp'];
			$kode_lokasi = $data['kode_lokasi'];
            $sql = "select a.nis,a.kode_lokasi,a.kode_pp,a.nama,a.kode_kelas,b.nama as nama_kelas,a.kode_lokasi,b.kode_jur,c.nama as nama_jur,a.kode_akt,a.id_bank,x.nama as nama_pp
			from sis_siswa a
			inner join sis_kelas b on a.kode_kelas=b.kode_kelas and a.kode_lokasi=b.kode_lokasi and a.kode_pp=b.kode_pp
			inner join sis_jur c on b.kode_jur=c.kode_jur and 
			b.kode_lokasi=c.kode_lokasi and b.kode_pp=c.kode_pp
			inner join pp x on a.kode_pp=x.kode_pp and a.kode_lokasi=x.kode_lokasi
			inner join (select a.nis,a.kode_pp,a.kode_lokasi
			from sis_cd_d a
			where a.kode_lokasi='$kode_lokasi' and a.kode_pp='$kode_pp'
			group by a.nis,a.kode_pp,a.kode_lokasi
			)g on a.nis=g.nis and a.kode_lokasi=g.kode_lokasi and a.kode_pp=g.kode_pp
			where a.kode_lokasi='$kode_lokasi' and a.kode_pp='$kode_pp' and a.nis='$nik'
			order by a.kode_kelas,a.nis";
			$response["daftar"]=dbResultArray($sql);
			
			$sql="select case when sum(debet-kredit) < 0 then 0 else sum(debet-kredit) end as so_akhir
			from (select a.no_bukti,a.nilai,convert(varchar(20),b.tanggal,103) as tgl,b.keterangan,b.modul,b.tanggal,
			a.nilai as debet,0 as kredit,a.dc
			from sis_cd_d a
			inner join kas_m b on a.no_bukti=b.no_kas and a.kode_lokasi=b.kode_lokasi
			where a.nis='$nik' and a.kode_lokasi='$kode_lokasi' and a.kode_pp='$kode_pp' and a.dc='D'
			union all
			select a.no_bukti,a.nilai,convert(varchar(20),b.tanggal,103) as tgl,b.keterangan,b.modul,b.tanggal,
			case when a.dc='D' then a.nilai else 0 end as debet,case when a.dc='C' then a.nilai else 0 end as kredit,a.dc
			from sis_cd_d a
			inner join sis_rekon_m b on a.no_bukti=b.no_rekon and a.kode_lokasi=b.kode_lokasi
			where a.nis='$nik' and a.kode_lokasi='$kode_lokasi' and a.kode_pp='$kode_pp' 
			union all
			select a.no_bukti,a.nilai,convert(varchar(20),b.tanggal,103) as tgl,b.keterangan,b.modul,b.tanggal,
			0 as debet,case when a.dc='C' then a.nilai else 0 end as kredit,a.dc
			from sis_cd_d a
			inner join kas_m b on a.no_bukti=b.no_kas and a.kode_lokasi=b.kode_lokasi
			where a.nis='$nik' and a.kode_lokasi='$kode_lokasi' and a.kode_pp='$kode_pp' and a.dc='C'
			
			)a";
			
			// echo $sql;
			$saldo= dbResultArray($sql);
			$response["so_akhir"]=$saldo[0]["so_akhir"];

			
			$sql2="select a.no_bukti,a.tgl,a.keterangan,a.modul,a.debet,a.kredit,a.dc
			from (select a.no_bukti,a.nilai,convert(varchar(20),b.tanggal,103) as tgl,b.keterangan,b.modul,b.tanggal,
					   a.nilai as debet,0 as kredit,a.dc
				from sis_cd_d a
				inner join kas_m b on a.no_bukti=b.no_kas and a.kode_lokasi=b.kode_lokasi
				where a.nis='$nik' and a.kode_lokasi='$kode_lokasi' and a.kode_pp='$kode_pp' and a.dc='D'
				union all
				select a.no_bukti,a.nilai,convert(varchar(20),b.tanggal,103) as tgl,b.keterangan,b.modul,b.tanggal,
					   case when a.dc='D' then a.nilai else 0 end as debet,case when a.dc='C' then a.nilai else 0 end as kredit,a.dc
				from sis_cd_d a
				inner join sis_rekon_m b on a.no_bukti=b.no_rekon and a.kode_lokasi=b.kode_lokasi
				where a.nis='$nik' and a.kode_lokasi='$kode_lokasi' and a.kode_pp='$kode_pp' 
				union all
				select a.no_bukti,a.nilai,convert(varchar(20),b.tanggal,103) as tgl,b.keterangan,b.modul,b.tanggal,
					   0 as debet,case when a.dc='C' then a.nilai else 0 end as kredit,a.dc
				from sis_cd_d a
				inner join kas_m b on a.no_bukti=b.no_kas and a.kode_lokasi=b.kode_lokasi
				where a.nis='$nik' and a.kode_lokasi='$kode_lokasi' and a.kode_pp='$kode_pp' and a.dc='C'
							
				)a
			order by a.tanggal,a.no_bukti ";
            $response["daftar2"]=dbResultArray($sql2);

			$response['status'] = true;
        }else{
			$response["status"] = false;
            $response["message"] = "Unauthorized Access. ".$res["message"];
            $response["token"] = $token;
		}
		header('Content-Type: application/json');
		echo json_encode($response);
    }
    
     // get Saldo Piutang
     function getSaldoPiutang(){
		
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
            $periode = $data['periode'];
            $sql="select a.nis,a.nama,a.kode_lokasi,a.kode_pp,isnull(b.total,0)-isnull(d.total,0)+isnull(c.total,0)-isnull(e.total,0) as sak_total,a.kode_kelas,isnull(e.total,0) as bayar
            from sis_siswa a 
            inner join sis_kelas f on a.kode_kelas=f.kode_kelas and a.kode_lokasi=f.kode_lokasi and a.kode_pp=f.kode_pp
            left join (select y.nis,y.kode_lokasi,  
                        sum(case when x.dc='D' then x.nilai else -x.nilai end) as total		
                        from sis_bill_d x 			
                        inner join sis_siswa y on x.nis=y.nis and x.kode_lokasi=y.kode_lokasi and x.kode_pp=y.kode_pp
                        where(x.kode_lokasi = '$kode_lokasi')and(x.periode < '$periode') and x.kode_pp='$kode_pp'			
                        group by y.nis,y.kode_lokasi 			
                        )b on a.nis=b.nis and a.kode_lokasi=b.kode_lokasi
            left join (select y.nis,y.kode_lokasi,  
                        sum(case when x.dc='D' then x.nilai else -x.nilai end) as total		
                        from sis_bill_d x 			
                        inner join sis_siswa y on x.nis=y.nis and x.kode_lokasi=y.kode_lokasi and x.kode_pp=y.kode_pp
                        where(x.kode_lokasi = '$kode_lokasi')and(x.periode = '$periode') and x.kode_pp='$kode_pp'			
                        group by y.nis,y.kode_lokasi 			
                        )c on a.nis=c.nis and a.kode_lokasi=c.kode_lokasi
            left join (select y.nis,y.kode_lokasi,  
                        sum(case when x.dc='D' then x.nilai else -x.nilai end) as total				
                        from sis_rekon_d x 	
                        inner join sis_siswa y on x.nis=y.nis and x.kode_lokasi=y.kode_lokasi and x.kode_pp=y.kode_pp
                        where(x.kode_lokasi = '$kode_lokasi')and(x.periode <'$periode')	and x.kode_pp='$kode_pp'		
                        group by y.nis,y.kode_lokasi 			
                        )d on a.nis=d.nis and a.kode_lokasi=d.kode_lokasi
            left join (select y.nis,y.kode_lokasi, 
                        sum(case when x.dc='D' then x.nilai else -x.nilai end) as total			
                        from sis_rekon_d x 			
                        inner join sis_siswa y on x.nis=y.nis and x.kode_lokasi=y.kode_lokasi and x.kode_pp=y.kode_pp
                        where(x.kode_lokasi = '$kode_lokasi')and(x.periode ='$periode') and x.kode_pp='$kode_pp'			
                        group by y.nis,y.kode_lokasi 			
                        )e on a.nis=e.nis and a.kode_lokasi=e.kode_lokasi
            where(a.kode_lokasi = '$kode_lokasi') and a.kode_pp='$kode_pp'	and a.nis='$nik'
            order by a.kode_kelas,a.nis";
            $rs = dbRowArray($sql);

            $response["saldo"]=$rs["sak_total"];
			

			$response['status'] = true;
        }else{
			$response["status"] = false;
			$response["message"] = "Unauthorized Access. ".$res["message"];
		}
		header('Content-Type: application/json');
		echo json_encode($response);
    }
    
     // get Saldo PDD
     function getSaldoPDD(){
		
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
            $sql="select case when sum(debet-kredit) < 0 then 0 else sum(debet-kredit) end as so_akhir
			from (select a.no_bukti,a.nilai,convert(varchar(20),b.tanggal,103) as tgl,b.keterangan,b.modul,b.tanggal,
			a.nilai as debet,0 as kredit,a.dc
			from sis_cd_d a
			inner join kas_m b on a.no_bukti=b.no_kas and a.kode_lokasi=b.kode_lokasi
			where a.nis='$nik' and a.kode_lokasi='$kode_lokasi' and a.kode_pp='$kode_pp' and a.dc='D'
			union all
			select a.no_bukti,a.nilai,convert(varchar(20),b.tanggal,103) as tgl,b.keterangan,b.modul,b.tanggal,
			case when a.dc='D' then a.nilai else 0 end as debet,case when a.dc='C' then a.nilai else 0 end as kredit,a.dc
			from sis_cd_d a
			inner join sis_rekon_m b on a.no_bukti=b.no_rekon and a.kode_lokasi=b.kode_lokasi
			where a.nis='$nik' and a.kode_lokasi='$kode_lokasi' and a.kode_pp='$kode_pp' 
			union all
			select a.no_bukti,a.nilai,convert(varchar(20),b.tanggal,103) as tgl,b.keterangan,b.modul,b.tanggal,
			0 as debet,case when a.dc='C' then a.nilai else 0 end as kredit,a.dc
			from sis_cd_d a
			inner join kas_m b on a.no_bukti=b.no_kas and a.kode_lokasi=b.kode_lokasi
			where a.nis='$nik' and a.kode_lokasi='$kode_lokasi' and a.kode_pp='$kode_pp' and a.dc='C'
			
			)a";
            $rs = dbRowArray($sql);
            $response["saldo"]=$rs["so_akhir"];
			

			$response['status'] = true;
        }else{
			$response["status"] = false;
			$response["message"] = "Unauthorized Access. ".$res["message"];
		}
		header('Content-Type: application/json');
		echo json_encode($response);
    }
    
    // get Riwayat
    function getRiwayat(){
		
        getKoneksi();
        $data=$_GET;
		$header = getallheaders();
        $token = $header["Authorization"];
		$res = authKey($token);
        if($res["status"]){ 
			$nik = $data['nik'];
			$kode_pp = $data['kode_pp'];
			$kode_lokasi = $data['kode_lokasi'];
            $sql="select  top 10 a.* from (
                select a.no_bill as no_bukti,a.kode_lokasi,b.tanggal,convert(varchar(10),b.tanggal,103) as tgl,b.periode,
                b.keterangan,'BILL' as modul, isnull(a.tagihan,0) as tagihan,isnull(a.bayar,0) as bayar,a.kode_param
                from (select x.kode_lokasi,x.no_bill,x.kode_param,sum(x.nilai) as tagihan,
                        0 as bayar from sis_bill_d x 
                        inner join sis_siswa y on x.nis=y.nis and x.kode_lokasi=y.kode_lokasi and x.kode_pp=y.kode_pp
                        where x.kode_lokasi = '$kode_lokasi' and x.nis='$nik' and x.kode_pp='$kode_pp' and x.nilai<>0 
                        group by x.kode_lokasi,x.no_bill,x.nis,x.kode_param )a 
                inner join sis_bill_m b on a.no_bill=b.no_bill and a.kode_lokasi=b.kode_lokasi 
                union all 
                select a.no_rekon as no_bukti,a.kode_lokasi,b.tanggal,
                convert(varchar(10),b.tanggal,103) as tgl,b.periode,b.keterangan,'PDD' as modul, isnull(a.tagihan,0) as tagihan,isnull(a.bayar,0) as bayar,a.kode_param
                from (select x.kode_lokasi,x.no_rekon,x.kode_param,
                    case when x.modul in ('BTLREKON') then x.nilai else 0 end as tagihan,case when x.modul <>'BTLREKON' then x.nilai else 0 end as bayar
                    from sis_rekon_d x inner join sis_siswa y on x.nis=y.nis and x.kode_lokasi=y.kode_lokasi and x.kode_pp=y.kode_pp 
                    where x.kode_lokasi = '$kode_lokasi' and x.nis='$nik' and x.kode_pp='$kode_pp' and x.nilai<>0
                    )a 
                inner join sis_rekon_m b on a.no_rekon=b.no_rekon and a.kode_lokasi=b.kode_lokasi 
                union all 
                select a.no_rekon as no_bukti,a.kode_lokasi,b.tanggal,
                convert(varchar(10),b.tanggal,103) as tgl,b.periode,b.keterangan,'KB' as modul, isnull(a.tagihan,0) as tagihan,isnull(a.bayar,0) as bayar,a.kode_param 
                from (select x.kode_lokasi,x.no_rekon,x.kode_param,
                    case when x.modul in ('BTLREKON') then x.nilai else 0 end as tagihan,case when x.modul <>'BTLREKON' then x.nilai else 0 end as bayar
                    from sis_rekon_d x inner join sis_siswa y on x.nis=y.nis and x.kode_lokasi=y.kode_lokasi and x.kode_pp=y.kode_pp 
                    where x.kode_lokasi = '$kode_lokasi' and x.nis='$nik' and x.kode_pp='$kode_pp' and x.nilai<>0 
                )a
                inner join kas_m b on a.no_rekon=b.no_kas and a.kode_lokasi=b.kode_lokasi 
            ) a
            order by a.tanggal desc";

            $response["daftar"]=dbResultArray($sql);
			

			$response['status'] = true;
        }else{
			$response["status"] = false;
			$response["message"] = "Unauthorized Access. ".$res["message"];
		}
		header('Content-Type: application/json');
		echo json_encode($response);
    }
    
    // get Rincian Tagihan
    function getDetailPiu(){
		
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
            $tahun = $data['tahun'];
            
            $response["daftar"] = dbResultArray("
            select distinct a.periode from sis_bill_d a where(a.kode_lokasi = '$kode_lokasi')and a.kode_pp='$kode_pp' and a.nis='$nik' and a.periode  LIKE '$tahun%' ");

            $response["daftar2"] = dbResultArray("
            select a.no_bill,a.periode,a.tanggal,isnull(b.jum,0) as jum_param
            from sis_bill_m a 
             left join (select a.no_bill,a.kode_lokasi,a.kode_pp,a.periode,a.nis,count(a.kode_param) as jum
                       from sis_bill_d a
                        where(a.kode_lokasi = '$kode_lokasi')and a.kode_pp='$kode_pp' and a.periode  LIKE '$tahun%' 
                        group by a.no_bill,a.kode_lokasi,a.kode_pp,a.periode,a.nis
             ) b on a.no_bill=b.no_bill and a.kode_lokasi=b.kode_lokasi and a.kode_pp=b.kode_pp
             where(a.kode_lokasi = '$kode_lokasi')and a.kode_pp='$kode_pp' and a.periode  LIKE '$tahun%'  and b.nis ='$nik'
            ");
            
            $sql="select a.nis, a.nama, b.kode_param,isnull(b.total,0) as bill, isnull(c.total,0) as bayar , isnull(b.total,0)-isnull(c.total,0) as saldo,b.periode,b.tanggal,b.no_bill   
            from sis_siswa a             
            left join (select x.no_bill,x.periode,z.tanggal,x.kode_param,x.nis,x.kode_lokasi,sum(case when x.dc='D' then x.nilai else -x.nilai end) as total                         
                       from sis_bill_d x    
					   inner join sis_bill_m z on x.no_bill=z.no_bill and x.kode_lokasi=z.kode_lokasi and x.kode_pp=z.kode_pp                   
                       inner join sis_siswa y on x.nis=y.nis and x.kode_lokasi=y.kode_lokasi and x.kode_pp=y.kode_pp               
                       where(x.kode_lokasi = '$kode_lokasi')and x.kode_pp='$kode_pp'              
                       group by x.no_bill,x.periode,z.tanggal,x.kode_param,x.kode_lokasi,x.nis ) b on a.kode_lokasi=b.kode_lokasi and a.nis=b.nis            
            left join (select x.no_rekon,x.periode_bill,x.kode_param,x.nis,x.kode_lokasi, 
                        sum(case when x.dc='D' then x.nilai else -x.nilai end) as total                       
                        from sis_rekon_d x                       
                        inner join sis_siswa y on x.nis=y.nis and x.kode_lokasi=y.kode_lokasi and x.kode_pp=y.kode_pp                       
                        where(x.kode_lokasi = '$kode_lokasi')and x.kode_pp='$kode_pp'                        
                        group by x.no_rekon,x.periode_bill,x.kode_param,x.nis,x.kode_lokasi ) c on a.kode_lokasi=c.kode_lokasi and a.nis=c.nis 
                        and b.periode=c.periode_bill and b.kode_param=c.kode_param            
            where(a.kode_lokasi = '$kode_lokasi')and a.kode_pp='$kode_pp' and a.nis='$nik' and b.periode  LIKE '$tahun%'            
            order by periode desc";

            $response["daftar3"]=dbResultArray($sql);
			$response['status'] = true;
        }else{
			$response["status"] = false;
			$response["message"] = "Unauthorized Access. ".$res["message"];
		}
		header('Content-Type: application/json');
		echo json_encode($response);
	}

?>