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
        include_once($root_lib."lib/koneksi.php");
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
                $serverKey="b1b23c96bb8ecbf68bfba702a8e232b5";
                //SET EXPIRED :
                // $unixTime = time();
                // $nbf  = $unixTime;             
                // $exp     = $nbf + (60 * 60);  // 1 jam
                // create a token
                $payloadArray = array();
                $payloadArray['userId'] = $userId;
                $payloadArray['kode_lokasi'] = $row->kode_lokasi;
                if (isset($nbf)) {$payloadArray['nbf'] = $nbf;}
                if (isset($exp)) {$payloadArray['exp'] = $exp;}
                $token = JWT::encode($payloadArray, $serverKey);
                // return to caller
                $response = array('token' => $token,'status'=>true,'message'=>'Login Success');

            } 
            else {
                $response = array('message' => 'Invalid user ID or password.','status'=>false);
               
            }

        }else{
            $response = array('message' => "Kode Lokasi, Modul, and NIK required",'status'=>false);
        }
        
        echo json_encode($response);

    }

    function authKey($token){
        getKoneksi();
        $token = $token;
        $date = date('Y-m-d H:i:s');
        $serverKey="b1b23c96bb8ecbf68bfba702a8e232b5";
        try {
            $payload = JWT::decode($token, $serverKey, array('HS256'));
            if(isset($payload->userId)  || $payload->userId != ''){
                if (isset($payload->exp)) {
                    $returnArray['exp'] = date(DateTime::ISO8601, $payload->exp);

                    if (isset($payload->kode_lokasi)) {
                        $returnArray['kode_lokasi'] = $payload->kode_lokasi;
                    }

                    if ($payload->exp < time()) {
                        $returnArray['status'] = false;
                        $returnArray['message'] = "Your token was expired";
                    } else {
                        $returnArray['status'] = true;
                    }
                }else{

                    if (isset($payload->kode_lokasi)) {
                        $returnArray['kode_lokasi'] = $payload->kode_lokasi;
                    }
                    $returnArray['status'] = true;
                }
            }
            
        }
        catch(Exception $e) {
            $returnArray = array('message' => $e->getMessage(),'status'=>false);
        }
       
        return $returnArray;
    }

    
    function generateKode($tabel, $kolom_acuan, $prefix, $str_format){
        $query = execute("select right(max($kolom_acuan), ".strlen($str_format).")+1 as id from $tabel where $kolom_acuan like '$prefix%'");
        $kode = $query->fields[0];
        $id = $prefix.str_pad($kode, strlen($str_format), $str_format, STR_PAD_LEFT);
        return $id;
    }

    function getPeserta(){
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
            
            if (isset($data['no_peserta']) || $data['no_peserta'] != "") {
                $no_peserta = $data['no_peserta'];
                $filterno_peserta = " and a.no_peserta='$no_peserta' ";
            
            }else{
                $filterno_peserta = "";
            }

            $response = array("message" => "", "rows" => 0, "status" => "" );
           
            $sql="select *
            from dgw_peserta a
            where a.kode_lokasi='$kode_lokasi' $filterno_peserta";

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

    function addPeserta() {
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

            $row = dbResultArray("select substring(cast(year(getdate()) as varchar),3,2) as tahun");
			if (count($row) > 0){
				$line = $row[0];							
				$tahun = $line["tahun"];
            }
            
            $no_peserta = generateKode("dgw_peserta", "no_peserta", $tahun, "00001");

            if(ISSET($_FILES["foto"]["name"]) AND !empty($_FILES["foto"]["name"])){

                $path_s = $_SERVER['DOCUMENT_ROOT'];
                $target_dir = $path_s."upload/";
                $target_file = $target_dir . basename($_FILES["foto"]["name"]);
                $uploadOk = 1;
                $message="";
                $error_upload="";
                $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

                // generate nama gambar baru
                $namaFileBaru = uniqid();
                $namaFileBaru .= '.';
                $namaFileBaru .= $imageFileType;

                $target_file = $target_dir . $namaFileBaru;

                // Check if image file is a actual image or fake image
                if(isset($_POST["submit"])) {
                    $check = getimagesize($_FILES["foto"]["tmp_name"]);
                    if($check !== false) {
                        $message= "File is an image - " . $check["mime"] . ".";
                        $uploadOk = 1;
                    } else {
                        $error_upload= "File is not an image.";
                        $uploadOk = 0;
                    }
                }
                // Check if file already exists
                if (file_exists($target_file)) {
                    $error_upload= "Sorry, file already exists.";
                    $uploadOk = 0;
                }
                // Check file size
                if ($_FILES["foto"]["size"] > 2000000) {
                    $error_upload= "Sorry, your file is too large.";
                    $uploadOk = 0;
                }
                // Allow certain file formats
                if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                && $imageFileType != "gif" ) {
                    $error_upload= "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                    $uploadOk = 0;
                }
                // Check if $uploadOk is set to 0 by an error
                if ($uploadOk == 0) {
                    $error_upload= "Sorry, your file was not uploaded.";
                // if everything is ok, try to upload file
                } else {
                    if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
                        $message = "The file ". $namaFileBaru. " has been uploaded.";
                    } else {
                        $error_upload= "Sorry, there was an error uploading your file.";
                        // echo $target_file;
                        // echo $_FILES["foto"]["error"];
                        if (is_dir($target_dir) && is_writable($target_dir)) {
                            // do upload logic here
                        } else if (!is_dir($target_dir)){
                            $error_upload.= 'Upload directory does not exist.'.$target_dir;
                        } else if (!is_writable($target_dir)){
                            $error_upload.= 'Upload directory is not writable'.$target_dir;
                        }
    
                    }
                }
    
                $filepath=$namaFileBaru;
            }else{
                $filepath="-";
            }

            $exec=array();
            $query="insert into dgw_peserta(no_peserta,kode_lokasi,id_peserta,nama,jk,status,alamat,kode_pos,telp,hp,email,pekerjaan,bank,norek,nopass,kantor_mig,sp,ec_telp,ec_hp,issued,ex_pass,tempat,tgl_lahir,th_haji,th_umroh,ibu,foto) values 
            ('$no_peserta','".$kode_lokasi."','".$data['id_peserta']."','".$data['nama']."','".$data['jk']."','".$data['status']."','".$data['alamat']."','".$data['kode_pos']."','".$data['telp']."','".$data['hp']."','".$data['email']."','".$data['pekerjaan']."','".$data['bank']."','".$data['norek']."','".$data['nopass']."','".$data['kantor_mig']."','".$data['sp']."','".$data['ec_telp']."','".$data['ec_hp']."','".$data['issued']."','".$data['ex_pass']."','".$data['tempat']."','".$data['tgl_lahir']."','".$data['th_haji']."','".$data['th_umroh']."','".$data['ibu']."','".$filepath."')";

            array_push($exec,$query);

            $rs=executeArray($exec);
            if($rs) {
                $response=array(
                    'status' => true,
                    'message' =>'Peserta Jamaah Added Successfully.',
                    'no_peserta' => $no_peserta
                );
            }
            else {
                $response=array(
                    'status' => false,
                    'message' =>'Peserta Jamaah Addition Failed.'
                );
                
            }
          
        }else{
            $response['status'] = false;
            $response['message'] = "Unauthorized Access ".$res["message"];
            
        }
        echo json_encode($response);
    }

     function updatePeserta() {
        getKoneksi();
        // parse_str(file_get_contents('php://input'), $_PUT);
        $data = $_POST;
        $header = getallheaders();
		$bearer = $header["Authorization"];
		list($token) = sscanf($bearer, 'Bearer %s');
        $res = authKey($token);
        if($res["status"]){
            
            $exec = array();
            if(isset($data['kode_lokasi']) && $data['kode_lokasi'] != ""){

                $kode_lokasi=$data['kode_lokasi'];
            }else{
                $kode_lokasi=$res["kode_lokasi"];
            }

            if(ISSET($_FILES["foto"]["name"]) AND !empty($_FILES["foto"]["name"])){

                $path_s = $_SERVER['DOCUMENT_ROOT'];
                $target_dir = $path_s."upload/";
                $target_file = $target_dir . basename($_FILES["foto"]["name"]);
                $uploadOk = 1;
                $message="";
                $error_upload="";
                $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

                // generate nama gambar baru
                $namaFileBaru = uniqid();
                $namaFileBaru .= '.';
                $namaFileBaru .= $imageFileType;

                $target_file = $target_dir . $namaFileBaru;

                // Check if image file is a actual image or fake image
                if(isset($_POST["submit"])) {
                    $check = getimagesize($_FILES["foto"]["tmp_name"]);
                    if($check !== false) {
                        $message= "File is an image - " . $check["mime"] . ".";
                        $uploadOk = 1;
                    } else {
                        $error_upload= "File is not an image.";
                        $uploadOk = 0;
                    }
                }
                // Check if file already exists
                if (file_exists($target_file)) {
                    // $error_upload= "Sorry, file already exists.";
                    // $uploadOk = 0;
                    unlink($namaFileBaru,$target_file);
                }
                // Check file size
                if ($_FILES["foto"]["size"] > 2000000) {
                    $error_upload= "Sorry, your file is too large.";
                    $uploadOk = 0;
                }
                // Allow certain file formats
                if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                && $imageFileType != "gif" ) {
                    $error_upload= "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                    $uploadOk = 0;
                }
                // Check if $uploadOk is set to 0 by an error
                if ($uploadOk == 0) {
                    $error_upload= "Sorry, your file was not uploaded.";
                // if everything is ok, try to upload file
                } else {
                    if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
                        $message = "The file ". $namaFileBaru. " has been uploaded.";
                    } else {
                        $error_upload= "Sorry, there was an error uploading your file.";
                        // echo $target_file;
                        // echo $_FILES["foto"]["error"];
                        if (is_dir($target_dir) && is_writable($target_dir)) {
                            // do upload logic here
                        } else if (!is_dir($target_dir)){
                            $error_upload.= 'Upload directory does not exist.'.$target_dir;
                        } else if (!is_writable($target_dir)){
                            $error_upload.= 'Upload directory is not writable'.$target_dir;
                        }
    
                    }
                }
    
                $filepath=$namaFileBaru;
            }else{
                $sql = "select foto from dgw_peserta where  no_peserta='".$data['no_peserta']."' and kode_lokasi='".$kode_lokasi."' ";
                $refoto = execute($sql);
                $filepath=$refoto->fields[0];
            }

            $del = "delete from dgw_peserta where no_peserta ='".$data['no_peserta']."' and kode_lokasi='".$kode_lokasi."'  ";
            array_push($exec,$del);

            $query="insert into dgw_peserta(no_peserta,kode_lokasi,id_peserta,nama,jk,status,alamat,kode_pos,telp,hp,email,pekerjaan,bank,norek,nopass,kantor_mig,sp,ec_telp,ec_hp,issued,ex_pass,tempat,tgl_lahir,th_haji,th_umroh,ibu,foto) values 
            ('".$data['no_peserta']."','".$kode_lokasi."','".$data['id_peserta']."','".$data['nama']."','".$data['jk']."','".$data['status']."','".$data['alamat']."','".$data['kode_pos']."','".$data['telp']."','".$data['hp']."','".$data['email']."','".$data['pekerjaan']."','".$data['bank']."','".$data['norek']."','".$data['nopass']."','".$data['kantor_mig']."','".$data['sp']."','".$data['ec_telp']."','".$data['ec_hp']."','".$data['issued']."','".$data['ex_pass']."','".$data['tempat']."','".$data['tgl_lahir']."','".$data['th_haji']."','".$data['th_umroh']."','".$data['ibu']."','".$filepath."')";

            array_push($exec,$query);
            
            $rs=executeArray($exec);
            if($rs) {
                $response=array(
                    'status' => true,
                    'message' =>'Peserta Jamaah Updated Successfully.'
                );
            }
            else {
                $response=array(
                    'status' => false,
                    'message' =>'Peserta Jamaah Updation Failed.'.$rs
                );
            }
            // $response['sql'] = $exec;
        }else{
            $response['status'] = false;
            $response['message'] = "Unauthorized Access ".$res["message"];
        }
        echo json_encode($response);
    }

    function deletePeserta() {
        getKoneksi();
        parse_str(file_get_contents('php://input'), $_DELETE);
        $data = $_DELETE;
        $header = getallheaders();
		$bearer = $header["Authorization"];
		list($token) = sscanf($bearer, 'Bearer %s');
        $res = authKey($token);
        if($res["status"]){
            $id = qstr($_DELETE["no_peserta"]);
            if(isset($data['kode_lokasi']) && $data['kode_lokasi'] != ""){
                $kd_lok = qstr($data["kode_lokasi"]);
            }else{
                $kd_lok = qstr($res["kode_lokasi"]);
            }
            
            $query="DELETE FROM dgw_peserta where no_peserta=$id and kode_lokasi = $kd_lok" ;
            
            $exec = array();
            array_push($exec,$query);
            
            $rs=executeArray($exec);
            if($rs) {
                $response=array(
                    'status' => true,
                    'message' =>'Peserta Jamaah Deleted Successfully.'
                );
            }
            else {
                $response=array(
                    'status' => false,
                    'message' =>'Peserta Jamaah Deletion Failed.'
                );
            }                
        }else{
            $response['error'] = "Unauthorized Access 1";
        }
        echo json_encode($response);
    }  


?>
