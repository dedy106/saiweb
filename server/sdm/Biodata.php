<?php

if(function_exists($_GET['fx'])) {
    $_GET['fx']();
}

function ubahBiodata(){
        $nik=$_POST['nik_user'];
        $kode_lokasi=$_POST['kode_lokasi'];
        
        $dbconn = db_Connect();

        $result = array("message" => "", "rows" => 0, "status" => "" );
        $error_upload = "not found";

        if(ISSET($_FILES["file_gambar"]["name"]) AND !empty($_FILES["file_gambar"]["name"])){
            $path_s = $_SERVER['DOCUMENT_ROOT'];
            $target_dir = $path_s."server/media/";
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

        $result['status'] = $sts;
        $result['message'] = $msg;
        $result['error'] = $error_upload;
        $result['Update'] = $upd;
        echo json_encode($result);

}

?>
