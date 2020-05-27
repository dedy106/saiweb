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
    include_once($root."lib/koneksi.php");
    include_once($root."lib/helpers.php");
}
    
function cekAuth($user,$pass){
    getKoneksi();
    $user = qstr($user);
    $pass = qstr($pass);

    $schema = db_Connect();
    $auth = $schema->SelectLimit("SELECT * FROM hakakses where nik=$user ", 1);
    if($auth->RecordCount() > 0){
        return true;
    }else{
        return false;
    }
}

function getDataPribadi(){
    session_start();
    getKoneksi();
    if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
        $kode_lokasi=$_GET['kode_lokasi'];
        $nik=$_GET['nik_user'];
        $response = array("message" => "", "rows" => 0, "status" => "" );

        $sql1 = "select nik, kode_lokasi, nama, alamat,  no_telp, email, kode_pp, npwp, bank, cabang, no_rek, nama_rek, grade, kota, kode_pos, no_hp, flag_aktif, foto,kode_sdm,kode_gol,kode_jab,kode_loker,kode_pajak,kode_unit,kode_profesi,kode_agama,jk,tahun_masuk, no_sk,tgl_sk,gelar_depan,gelar_belakang,status_nikah,tgl_nikah,gol_darah,no_kk,kelurahan,kecamatan,ibu_kandung,tempat,convert(varchar,tgl_lahir,23) as tgl_lahir,tgl_masuk,no_ktp,no_bpjs,kode_strata,ijht,bpjs,jp,mk_gol,mk_ytb,tgl_kontrak,no_kontrak 
        from hr_karyawan
        where kode_lokasi ='$kode_lokasi' and nik='$nik' ";
        
        $rs = execute($sql1);					
        
        $response['daftar'] = array();
        while ($row = $rs->FetchNextObject(false)){
            $response['daftar'][] = (array)$row;
        }
        $response['status']=true;
        $response['sql']=$sql1;
    }else{
            
        $response["status"] = false;
        $response["message"] = "Unauthorized Access, Login Required";
    }
    header('Content-Type: application/json');
    echo json_encode($response);
}

function ubahBiodata(){
    session_start();
    getKoneksi();
    if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
        $nik=$_POST['nik_user'];
        $kode_lokasi=$_POST['kode_lokasi'];
        
        $dbconn = db_Connect();

        $response = array("message" => "", "rows" => 0, "status" => "" );
        $error_upload = "not found";

        if(ISSET($_FILES["file_gambar"]["name"]) AND !empty($_FILES["file_gambar"]["name"])){
            $path_s = realpath($_SERVER['DOCUMENT_ROOT'])."/";
            $target_dir = $path_s."upload/";
            $target_file = $target_dir . basename($_FILES["file_gambar"]["name"]);
            $uploadOk = 1;
            $message="";
            $error_upload="";
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            // Check if image file is a actual image or fake image
            if(isset($_POST["submit"])) {
                $check = getimagesize($_FILES["file_gambar"]["tmp_name"]);
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
            if ($_FILES["file_gambar"]["size"] > 2000000) {
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
                if (move_uploaded_file($_FILES["file_gambar"]["tmp_name"], $target_file)) {
                    $message = "The file ". basename( $_FILES["file_gambar"]["name"]). " has been uploaded.";
                } else {
                    $error_upload= "Sorry, there was an error uploading your file.";
                    // echo $target_file;
                    // echo $_FILES["file_gambar"]["error"];
                    if (is_dir($target_dir) && is_writable($target_dir)) {
                        // do upload logic here
                    } else if (!is_dir($target_dir)){
                        $error_upload.= 'Upload directory does not exist.'.$target_dir;
                    } else if (!is_writable($target_dir)){
                        $error_upload.= 'Upload directory is not writable'.$target_dir;
                    }

                }
            }

            $filepath=basename($_FILES["file_gambar"]["name"]);
            $filetype=$imageFileType;

            $upd= array(
                'nama'=>$_POST['nama'],
                'jk'=>$_POST['jk'],
                'kode_agama'=>$_POST['kode_agama'],
                'no_telp'=>$_POST['no_telp'],
                'no_hp'=>$_POST['no_hp'],
                'email'=>$_POST['email'],
                'alamat'=>$_POST['alamat'],
                'kota'=>$_POST['kota'],
                'kode_pos'=>$_POST['kode_pos'],
                'kelurahan'=>$_POST['kelurahan'],
                'kecamatan'=>$_POST['kecamatan'],
                'npwp'=>$_POST['npwp'],
                'no_ktp'=>$_POST['no_ktp'],
                'no_bpjs'=>$_POST['no_bpjs'],
                'kode_profesi'=>$_POST['kode_profesi'],
                'kode_strata'=>$_POST['kode_strata'],
                'kode_pajak'=>$_POST['kode_pajak'],
                'tempat'=>$_POST['tempat'],
                'tgl_lahir'=>$_POST['tgl_lahir'],
                'no_kk'=>$_POST['no_kk'],
                'gol_darah'=>$_POST['gol_darah'],
                'status_nikah'=>$_POST['status_nikah'],
                'tgl_nikah'=>$_POST['tgl_nikah'],
                'ibu_kandung'=>$_POST['ibu_kandung'],
                'bank'=>$_POST['bank'],
                'cabang'=>$_POST['cabang'],
                'no_rek'=>$_POST['no_rek'],
                'nama_rek'=>$_POST['nama_rek'],
                'foto'=>$filepath
            );

        }else{

            $upd= array(
                'nama'=>$_POST['nama'],
                'jk'=>$_POST['jk'],
                'kode_agama'=>$_POST['kode_agama'],
                'no_telp'=>$_POST['no_telp'],
                'no_hp'=>$_POST['no_hp'],
                'email'=>$_POST['email'],
                'alamat'=>$_POST['alamat'],
                'kota'=>$_POST['kota'],
                'kode_pos'=>$_POST['kode_pos'],
                'kelurahan'=>$_POST['kelurahan'],
                'kecamatan'=>$_POST['kecamatan'],
                'npwp'=>$_POST['npwp'],
                'no_ktp'=>$_POST['no_ktp'],
                'no_bpjs'=>$_POST['no_bpjs'],
                'kode_profesi'=>$_POST['kode_profesi'],
                'kode_strata'=>$_POST['kode_strata'],
                'kode_pajak'=>$_POST['kode_pajak'],
                'tempat'=>$_POST['tempat'],
                'tgl_lahir'=>$_POST['tgl_lahir'],
                'no_kk'=>$_POST['no_kk'],
                'gol_darah'=>$_POST['gol_darah'],
                'status_nikah'=>$_POST['status_nikah'],
                'tgl_nikah'=>$_POST['tgl_nikah'],
                'ibu_kandung'=>$_POST['ibu_kandung'],
                'bank'=>$_POST['bank'],
                'cabang'=>$_POST['cabang'],
                'no_rek'=>$_POST['no_rek'],
                'nama_rek'=>$_POST['nama_rek']
            );
        }
        
        $update = $dbconn->AutoExecute("hr_karyawan", $upd, 'UPDATE', " nik='".$_POST['nik']."' and kode_lokasi='".$kode_lokasi."' ");

        if($update){
            $sts=TRUE;
            $msg="berhasil";
        }else{
            $sts=FALSE;
            $msg="gagal";
        }

        $response['status'] = $sts;
        $response['message'] = $msg;
        $response['error'] = $error_upload;
        $response['Update'] = $upd;
    }else{
        $response["status"] = false;
        $response["message"] = "Unauthorized Access, Login Required";
    }
    // header('Content-Type: application/json');
    echo json_encode($response);

}

function getAgama(){
    session_start();
    getKoneksi();
    if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){

        $kode_lokasi=$_GET['kode_lokasi'];
        $response = array("message" => "", "rows" => 0, "status" => "" );

        $sql1 = " SELECT kode_agama,nama from hr_agama where kode_lokasi = '".$kode_lokasi."' ";
        
        $rs = execute($sql1);					
        
        $response['daftar'] = array();
        while ($row = $rs->FetchNextObject(false)){
            $response['daftar'][] = (array)$row;
        }
        $response['status']=TRUE;
        $response['sql']=$sql1;
    }else{
        $response["status"] = false;
        $response["message"] = "Unauthorized Access, Login Required";
    }
    header('Content-Type: application/json');
    echo json_encode($response);

}

function getProfesi(){
    session_start();
    getKoneksi();
    if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
        $kode_lokasi=$_GET['kode_lokasi'];
        $response = array("message" => "", "rows" => 0, "status" => "" );

        $sql1 = "SELECT kode_profesi,nama from hr_profesi where kode_lokasi = '".$kode_lokasi."' ";
        
        $rs = execute($sql1);					
        
        $response['daftar'] = array();
        while ($row = $rs->FetchNextObject(false)){
            $response['daftar'][] = (array)$row;
        }
        $response['status']=TRUE;
        $response['sql']=$sql1;
    }else{
        $response["status"] = false;
        $response["message"] = "Unauthorized Access, Login Required";
    }
    header('Content-Type: application/json');
    echo json_encode($response);

}

function getStrata(){
    session_start();
    getKoneksi();
    if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
        $kode_lokasi=$_GET['kode_lokasi'];
        $response = array("message" => "", "rows" => 0, "status" => "" );

        $sql1 = "select kode_strata,nama from hr_strata where kode_lokasi = '".$kode_lokasi."' ";
        
        $rs = execute($sql1);					
        
        $response['daftar'] = array();
        while ($row = $rs->FetchNextObject(false)){
            $response['daftar'][] = (array)$row;
        }
        $response['status']=TRUE;
        $response['sql']=$sql1;
    }else{
        $response["status"] = false;
        $response["message"] = "Unauthorized Access, Login Required";
    }
    header('Content-Type: application/json');
    echo json_encode($response);

}

function getStatusPajak(){
    session_start();
    getKoneksi();
    if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
        $kode_lokasi=$_GET['kode_lokasi'];
        $response = array("message" => "", "rows" => 0, "status" => "" );

        $sql1 = "SELECT kode_pajak,nama from hr_pajak where kode_lokasi = '".$kode_lokasi."' ";
        
        $rs = execute($sql1);					
        
        $response['daftar'] = array();
        while ($row = $rs->FetchNextObject(false)){
            $response['daftar'][] = (array)$row;
        }
        $response['status']=TRUE;
        $response['sql']=$sql1;
    }else{
        $response["status"] = false;
        $response["message"] = "Unauthorized Access, Login Required";
    }
    header('Content-Type: application/json');
    echo json_encode($response);

}


?>
