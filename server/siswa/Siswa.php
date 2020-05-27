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

    function ubahPassword(){
		session_start();
		getKoneksi();
		if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){

			$post = $_POST;
			$response["auth_status"] = 1;
			$error_input = array();
		
			$nik = $post['nik'];
			$pass = $post['password_lama'];
			
			$kode_lokasi = $post['kode_lokasi'];
			$kode_pp = $post['kode_pp'];
			
			$sql="select nik, pass from sis_hakakses where nik='$nik' and kode_lokasi='$kode_lokasi' and kode_pp='$kode_pp' and pass='$pass'";
			$cek = execute($sql);

			if($cek->RecordCount() > 0){
				$up_data = $post["password_baru"];
				$konfir_data = $post["password_repeat"];
				if ($up_data == $konfir_data){

					$sql2= "update sis_hakakses set pass='$up_data' where nik='$nik' and kode_lokasi = '$kode_lokasi' and kode_pp='$kode_pp' and pass='$pass' ";
					$rs = execute($sql2);

					if($rs){
						$response['status'] = 1;
						$response['alert'] = 'Data berhasil disimpan';
						$response['edit'] = TRUE;
						$response['sql']=$sql;
					}else{
						$response['status'] = 2;
						$response['alert'] = "Data gagal disimpan ke database";
						$response['sql']=$sql;
					}
				}else{
					$response['status'] = 3;
					$response['alert'] = "error input";
					$response['error_input'][0] = "Password baru dan konfirmasi password tidak sama ! ";
					$response['sql']=$sql;
				}			
			}else{
				$response['status'] = 3;
				$response['alert'] = "error input";
				$response['error_input'][0] = "Password lama salah ! Harap input password yang benar. ";
				$response['sql']=$sql;
			}
			
		}else{
			$response["status"] = false;
			$response["message"] = "Unauthorized Access, Login Required";
		}
		// header('Content-Type: application/json');
		echo json_encode($response);
	}


	function ubahFoto(){
		session_start();
		getKoneksi();
		if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){

			$kode_pp=$_POST['kode_pp'];
			$nik=$_POST['nik'];
			$kode_lokasi=$_POST['kode_lokasi'];

			$ekstensi_diperbolehkan	= array('png','jpg','jpeg');
			$nama = $_FILES['file']['name'];
			$x = explode('.', $nama);
			$ekstensi = strtolower(end($x));
			$ukuran	= $_FILES['file']['size'];
			$file_tmp = $_FILES['file']['tmp_name'];	
			$acak           = rand(1,99);
			$nama_file_unik = $acak.$nama;
			
			$path = "http://".$_SERVER["SERVER_NAME"]."/";
			$path_foto2=$path."upload/";


			$path_s = $_SERVER['DOCUMENT_ROOT'];
			$path_foto=$path_s."upload/";

			if(in_array($ekstensi, $ekstensi_diperbolehkan) === true)
			{
				if($ukuran < 1044070)
				{			
					move_uploaded_file($file_tmp, $path_foto.$nama_file_unik);
					$query = "update sis_hakakses set
							foto = '$nama_file_unik'
							where nik='$nik' and kode_lokasi='$kode_lokasi' and kode_pp='$kode_pp'";

					$rs=execute($query);
					
					if($rs){								
						$response['status'] = 1;
						$response['alert'] = 'Data berhasil disimpan';
						$response['edit'] = TRUE;
						$img = $path_foto2.$nama_file_unik;
						$_SESSION['foto']= $nama_file_unik;
						$response['new_img'] = $img;
						$response['query']=$query;
					}
					else
					{								
						$response['status'] = 2;
						$response['alert'] = "Data gagal disimpan ke database";
						$img = $path_foto2.$nama_file_unik;
						unlink($img);
					}
				}
				else
				{		
					$response['alert'] = 'Ukuran file terlalu besar';
					
				}
			}
			else
			{		
					$response['alert'] = 'Ekstensi file tidak di perbolehkan '.$ekstensi;
					
			}

		}else{
			$response["status"] = false;
			$response["message"] = "Unauthorized Access, Login Required";
		}
		// header('Content-Type: application/json');
		echo json_encode($response);
	}

	function getEditTelp(){
		session_start();
		getKoneksi();
		if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
			
			$response = array("message" => "", "rows" => 0, "status" => "" );
		
			$sql="select hp_ayah,hp_ibu from sis_siswa where kode_lokasi='".$_GET['kode_lokasi']."' and nis='".$_GET['nik']."' and kode_pp='".$_GET['kode_pp']."' ";
			
			$rs = execute($sql);					
			
			while ($row = $rs->FetchNextObject(false)){
				$response['daftar'][] = (array)$row;
			}
			$response['status'] = TRUE;
			$response['sql'] = $sql;

        }else{
			$response["status"] = false;
			$response["message"] = "Unauthorized Access, Login Required";
		}
		// header('Content-Type: application/json');
		echo json_encode($response);
	}
	
	function getSiswa(){
		session_start();
		getKoneksi();
		if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
			$nik = $_GET['nis'];
			$kode_pp = $_GET['kode_pp'];
			$kode_pp = $_GET['kode_lokasi'];
			$sql = "SELECT a.nis,a.nama from sis_siswa a 
			where a.kode_lokasi = '$kode_lokasi' and a.kode_pp = '$kode_pp' and a.nis='$nik' ";
			$rs = execute($sql);

			if($rs->RecordCount() > 0){
				$response['status'] = 1;
			}else{
				$response['status'] = 3;
			}
        }else{
			$response["status"] = false;
			$response["message"] = "Unauthorized Access, Login Required";
		}
		// header('Content-Type: application/json');
		echo json_encode($response);
	}

	function ubahNoTelp(){
		session_start();
		getKoneksi();
		if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){

			$post = $_POST;
			$response["auth_status"] = 1;
			$error_input = array();
		
			$nik = $post['nik'];
			$kode_lokasi = $post['kode_lokasi'];
			$kode_pp = $post['kode_pp'];
			
			$sql2= "update sis_siswa set hp_ayah='".$post['hp_ayah']."',hp_ibu='".$post['hp_ibu']."' where nis='$nik' and kode_lokasi = '$kode_lokasi' and kode_pp='$kode_pp' ";
			$rs = execute($sql2);
			
			if($rs){
				$response['status'] = 1;
				$response['alert'] = 'Data berhasil disimpan';
				$response['edit'] = TRUE;
				$response['sql']=$sql;
			}else{
				$response['status'] = 2;
				$response['alert'] = "Data gagal disimpan ke database";
				$response['sql']=$sql;
			}
		}else{
			$response["status"] = false;
			$response["message"] = "Unauthorized Access, Login Required";
		}
		// header('Content-Type: application/json');
		echo json_encode($response);
	}

	function getProfile(){
		session_start();
		getKoneksi();
		if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
			$nik = $_GET['nik'];
			$kode_pp = $_GET['kode_pp'];
			$kode_lokasi = $_GET['kode_lokasi'];

			if($_SESSION['userStatus'] == "A"){
								   
				$sql = "select a.nik, a.kode_lokasi, a.kode_pp, a.foto, b.nama, b.kode_tingkat, b.kode_jur, c.nama as nama_jur from sis_hakakses a left join sis_kelas b on a.nik=b.kode_kelas and a.kode_lokasi=b.kode_lokasi and a.kode_pp=b.kode_pp left join sis_jur c on b.kode_jur=c.kode_jur and b.kode_lokasi=c.kode_lokasi and b.kode_pp=c.kode_pp where a.nik='$nik' AND a.kode_lokasi ='$kode_lokasi' AND a.kode_pp = '$kode_pp'";
				$rs = execute($sql);

				$row_name=array("NIK", "Nama", "Jurusan", "Tingkat");

				$row = $rs->FetchNextObject($toupper=false);
			
			$html= "<tr><td>".$row_name[0]."</td><td>".$row->nik."</td></tr>
				  <tr><td>".$row_name[1]."</td><td>".$row->nama."</td></tr>
				  <tr><td>".$row_name[2]."</td><td>".$row->nama_jur."</td></tr>
				  <tr><td>".$row_name[3]."</td><td>".$row->kode_tingkat."</td></tr>"; 
			}else{

				$row_name = array("NIS", "ID Bank", "Nama", "Angkatan", "Kelas", "Jurusan", "Tingkat", "Status", "Tanggal Lulus","No Hp Ayah","No Hp Ibu");
				
				if($_SESSION['kodeMenu'] == 'SISWAWEB'){

					$sql2 = "select a.nis, a.id_bank, a.nama, b.nama as nama_akt, c.nama as nama_kls, d.nama as nama_jur, e.nama as nama_tingkat, a.flag_aktif, f.foto,a.hp_ayah,a.hp_ibu,a.tgl_lulus,a.kode_pp,g.nama as nama_pp from sis_siswa a left join sis_angkat b on a.kode_akt=b.kode_akt  and a.kode_pp=b.kode_pp  left join sis_kelas c on a.kode_kelas=c.kode_kelas and a.kode_pp=c.kode_pp left join sis_jur d on c.kode_jur = d.kode_jur and c.kode_lokasi=d.kode_lokasi  and c.kode_pp=d.kode_pp left join sis_tingkat e on c.kode_tingkat=e.kode_tingkat left join sis_hakakses f on a.nis=f.nik and a.kode_lokasi=f.kode_lokasi and a.kode_pp=f.kode_pp left join sis_sekolah g on a.kode_pp=g.kode_pp and a.kode_lokasi=g.kode_lokasi where a.nis='$nik' AND a.kode_lokasi ='$kode_lokasi' AND a.kode_pp = '$kode_pp' ";
					$rs = execute($sql2);

					$row = $rs->FetchNextObject($toupper=false);
				
					$html= "<tr><td>".$row_name[0]."</td><td>".$row->nis."</td></tr>
						<tr><td>".$row_name[1]."</td><td>".$row->id_bank."</td></tr>
						<tr><td>".$row_name[2]."</td><td>".$row->nama."</td></tr>
						<tr><td>".$row_name[3]."</td><td>".$row->nama_akt."</td></tr>
						<tr><td>".$row_name[4]."</td><td>".$row->nama_kls."</td></tr>
						<tr><td>".$row_name[5]."</td><td>".$row->nama_jur."</td></tr>
						<tr><td>".$row_name[6]."</td><td>".$row->nama_tingkat."</td></tr>
						<tr><td>".$row_name[7]."</td><td>".$row->flag_aktif."</td></tr>
						<tr><td>".$row_name[8]."</td><td>".$row->tgl_lulus."</td></tr>
						<tr><td>".$row_name[9]."</td><td>".$row->hp_ayah."</td></tr>
						<tr><td>".$row_name[10]."</td><td>".$row->hp_ibu."</td></tr>";  

				}else if($_SESSION['kodeMenu'] == 'SISWAWREG'){
					$sql3 = "select a.no_reg as nis, a.id_bank, a.nama, a.kode_ta as nama_akt, 'REG' as nama_kls, '-' as nama_jur, '-' as nama_tingkat, 'Registrasi' as flag_aktif, f.foto from sis_siswareg a left join sis_hakakses f on a.no_reg=f.nik and a.kode_lokasi=f.kode_lokasi and a.kode_pp=f.kode_pp where a.no_reg='$nik' AND a.kode_lokasi ='$kode_lokasi' AND a.kode_pp = '$kode_pp'";
					$rs = execute($sql3);

					$row = $rs->FetchNextObject($toupper=false);
				
					$html= "<tr><td>".$row_name[0]."</td><td>".$row->nis."</td></tr>
						<tr><td>".$row_name[1]."</td><td>".$row->id_bank."</td></tr>
						<tr><td>".$row_name[2]."</td><td>".$row->nama."</td></tr>
						<tr><td>".$row_name[3]."</td><td>".$row->nama_akt."</td></tr> ";

				} 
			}

			$path = "http://".$_SERVER["SERVER_NAME"]."/";	
			if($row->foto == null){
				$src = $path. 'upload/user2.png';
			}else{
				$src = $path. 'upload/'.$row->foto;
			}
			$img="<img src='$src' style='width:25%; height:25%; min-width:200px; min-height:200px; display:block; margin-left: auto; margin-right: auto;'>
			<br><br>";

			$response['status'] = true;
			$response['html'] = $html;
			$response['img'] = $img;
			$response['sql'] = dbResultArray($sql2);
        }else{
			$response["status"] = false;
			$response["message"] = "Unauthorized Access, Login Required";
		}
		// header('Content-Type: application/json');
		echo json_encode($response);
	}

	function getProfile2(){
		session_start();
		getKoneksi();
		if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
			$tmp = explode("|",$_GET['param']);
			$nik = $tmp[1];
			$kode_pp = $tmp[2];
			$kode_lokasi = $tmp[0];

			if($_SESSION['userStatus'] == "A"){
								   
				$sql = "select a.nik, a.kode_lokasi, a.kode_pp, a.foto, b.nama, b.kode_tingkat, b.kode_jur, c.nama as nama_jur from sis_hakakses a left join sis_kelas b on a.nik=b.kode_kelas and a.kode_lokasi=b.kode_lokasi and a.kode_pp=b.kode_pp left join sis_jur c on b.kode_jur=c.kode_jur and b.kode_lokasi=c.kode_lokasi and b.kode_pp=c.kode_pp where a.nik='$nik' AND a.kode_lokasi ='$kode_lokasi' AND a.kode_pp = '$kode_pp'";
				$response['daftar']= dbResultArray($sql); 
			}else{

				$row_name = array("NIS", "ID Bank", "Nama", "Angkatan", "Kelas", "Jurusan", "Tingkat", "Status", "Tanggal Lulus","No Hp Ayah","No Hp Ibu");
				
				if($_SESSION['kodeMenu'] == 'SISWAWEB'){

					$sql2 = "select a.nis, a.id_bank, a.nama, b.nama as nama_akt, c.nama as nama_kls, d.nama as nama_jur, e.nama as nama_tingkat, a.flag_aktif as status, f.foto,a.hp_ayah,a.hp_ibu,a.tgl_lulus,a.kode_pp,g.nama as nama_pp,a.email from sis_siswa a left join sis_angkat b on a.kode_akt=b.kode_akt  and a.kode_pp=b.kode_pp  left join sis_kelas c on a.kode_kelas=c.kode_kelas and a.kode_pp=c.kode_pp left join sis_jur d on c.kode_jur = d.kode_jur and c.kode_lokasi=d.kode_lokasi  and c.kode_pp=d.kode_pp left join sis_tingkat e on c.kode_tingkat=e.kode_tingkat left join sis_hakakses f on a.nis=f.nik and a.kode_lokasi=f.kode_lokasi and a.kode_pp=f.kode_pp left join sis_sekolah g on a.kode_pp=g.kode_pp and a.kode_lokasi=g.kode_lokasi where a.nis='$nik' AND a.kode_lokasi ='$kode_lokasi' AND a.kode_pp = '$kode_pp' ";
					
					$response['daftar']=dbResultArray($sql2);
					$response['row_name']=$row_name;

				}else if($_SESSION['kodeMenu'] == 'SISWAWREG'){
					$sql3 = "select a.no_reg as nis, a.id_bank, a.nama, a.kode_ta as nama_akt, 'REG' as nama_kls, '-' as nama_jur, '-' as nama_tingkat, 'Registrasi' as flag_aktif, f.foto from sis_siswareg a left join sis_hakakses f on a.no_reg=f.nik and a.kode_lokasi=f.kode_lokasi and a.kode_pp=f.kode_pp where a.no_reg='$nik' AND a.kode_lokasi ='$kode_lokasi' AND a.kode_pp = '$kode_pp'";
					$response['daftar']=dbResultArray($sql3);
					$response['row_name']=$row_name;
				} 
			}

			$path = "http://".$_SERVER["SERVER_NAME"]."/";	
			if($row->foto == null){
				$src = $path. 'upload/user2.png';
			}else{
				$src = $path. 'upload/'.$row->foto;
			}
			// $img="<img src='$src' style='width:25%; height:25%; min-width:200px; min-height:200px; display:block; margin-left: auto; margin-right: auto;'>
			// <br><br>";

			$response['status'] = true;
			$response['html'] = $html;
			$response['img'] = $row->foto;
			$response['sql'] = dbResultArray($sql2);
        }else{
			$response["status"] = false;
			$response["message"] = "Unauthorized Access, Login Required";
		}
		// header('Content-Type: application/json');
		echo json_encode($response);
	}


?>
