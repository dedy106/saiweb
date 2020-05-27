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
		default:
			// Invalid Request Method
			header("HTTP/1.0 405 Method Not Allowed");
		break;
	}


	function getKoneksi(){
		$root=realpath($_SERVER["DOCUMENT_ROOT"])."/";
		include_once($root."lib/koneksi5.php");
		include_once($root."lib/helpers.php");
	}
		
	function cekAuth($user){
		getKoneksi();
		$user = qstr($user);

		$schema = db_Connect();
		$auth = $schema->SelectLimit("SELECT * FROM sis_hakakses where nik=$user ", 1);
		if($auth->RecordCount() > 0){
			return true;
		}else{
			return false;
		}
	}

	function getPDD(){
		session_start();
		getKoneksi();
		if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
			$nik = $_GET['nik'];
			$kode_pp = $_GET['kode_pp'];
			$kode_lokasi = $_GET['kode_lokasi'];
            $sql = "select a.nis,a.kode_lokasi,a.kode_pp,a.nama,a.kode_kelas,b.nama as nama_kelas,a.kode_lokasi,b.kode_jur,c.nama as nama_jur,a.kode_akt,a.id_bank,x.nama as nama_pp,x.alamat,x.alamat2
			from sis_siswa a
			inner join sis_kelas b on a.kode_kelas=b.kode_kelas and a.kode_lokasi=b.kode_lokasi and a.kode_pp=b.kode_pp
			inner join sis_jur c on b.kode_jur=c.kode_jur and 
			b.kode_lokasi=c.kode_lokasi and b.kode_pp=c.kode_pp
			inner join sis_sekolah x on a.kode_pp=x.kode_pp and a.kode_lokasi=x.kode_lokasi
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
			$response["message"] = "Unauthorized Access, Login Required";
		}
		// header('Content-Type: application/json');
		echo json_encode($response);
	}


?>
