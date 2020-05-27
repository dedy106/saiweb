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
    include_once($root_lib."lib/koneksi.php");
    include_once($root_lib."lib/helpers.php");
}
function authKey2($key, $modul, $user=null){
    getKoneksi();
    $key = qstr($key);
    $modul = qstr($modul);
    $date = date('Y-m-d H:i:s');
    $user_str = "";
    if($user != null){
        $user = qstr($user);
        $user_str .= "AND nik = $user";
    }

    $schema = db_Connect();
    $auth = $schema->SelectLimit("SELECT * FROM api_key_auth where api_key=$key and expired > '$date' and modul=$modul $user_str", 1);
    if($auth->RecordCount() > 0){
        
        $date = new DateTime($date);
        $date->add(new DateInterval('PT1H'));
        $WorkingArray = json_decode(json_encode($date),true);
        $expired = explode(".",$WorkingArray["date"]);

        $db_key["expired"] = $expired[0];
        $key_sql = $schema->AutoExecute('api_key_auth', $db_key, 'UPDATE', "api_key=$key and modul=$modul");
        return true;
    }else{
        return false;
    }
}


function authKey($key, $modul, $user=null){
    getKoneksi();
    $key = qstr($key);
    $modul = qstr($modul);
    $date = date('Y-m-d H:i:s');
    $user_str = "";
    if($user != null){
        $user = qstr($user);
        $user_str .= "AND nik = $user";
    }

    $schema = db_Connect();
    $auth = $schema->SelectLimit("SELECT * FROM api_key_auth where api_key=$key and modul=$modul $user_str ", 1);
    if($auth->RecordCount() > 0){
        return true;
    }else{
        return false;
    }
}

function getEditInfo(){
    getKoneksi();
    $data = $_POST;
    if(arrayKeyCheck(array('api_key', 'username', 'kode_lokasi','periode'), $data)){
        if(authKey($data["api_key"], 'RTRW', $data['username'])){
            $id=$_POST['kode'];
            $kode_lokasi=$_POST['lokasi'];    
            
            $response = array("message" => "", "rows" => 0, "status" => "" );
            
            $sql="select * from rt_buku_p where kode_lokasi='$kode_lokasi' and no_bukti='$id' ";
            
            $rs = execute($sql);					
            
            while ($row = $rs->FetchNextObject(false)){
                $result['daftar'][] = (array)$row;
            }
            $response['status'] = TRUE;
            // $response['sql'] = $sql;
        }else{
            $response['status']=false;
            $response['message'] = "Unauthorized Access 2";
        }
    }else{
        $response['status']=false;
        $response['message'] = "Unauthorized Access 1";
    }
    echo json_encode($response);
    
}

function simpanInfo(){
    getKoneksi();
    $data = $_POST;
    if(arrayKeyCheck(array('api_key', 'username', 'kode_lokasi','periode'), $data)){
        if(authKey($data["api_key"], 'RTRW', $data['username'])){

            $data=$_POST;
            $exec = array();
            $kode_lokasi=$data['kode_lokasi'];
            $kode_pp=$data['kode_pp'];
            $nik=$data['nik'];

            $str_format="0000";
            $prefix=$kode_lokasi."-BPRT.";
            $sql2="select right(isnull(max(no_bukti),'000'),".strlen($str_format).")+1 as id from rt_buku_p where no_bukti like '$prefix%' and kode_lokasi='".$data['kode_lokasi']."'";
            $query = execute($sql2);

            $id = $prefix.str_pad($query->fields[0], strlen($str_format), $str_format, STR_PAD_LEFT);


            if(!EMPTY($_FILES["foto"]["name"])){

                $path_s = $_SERVER['DOCUMENT_ROOT'];
                $target_dir = $path_s."web/upload/";
                $target_file = $target_dir . basename($_FILES["foto"]["name"]);
                $uploadOk = 1;
                $message="";
                $error_upload="";
                $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
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
                    $error_upload .= "Sorry, your file was not uploaded.";
                // if everything is ok, try to upload file
                } else {
                    if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
                        $message = "The file ". basename( $_FILES["foto"]["name"]). " has been uploaded.";
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

                $filepath="/upload/".basename($_FILES["foto"]["name"]);
                $filetype=$imageFileType;
            }else{
                $filepath="-";
                $filetype="-";
            }

            if(!EMPTY($_FILES["file_dok"]["name"])){

                $path_s = $_SERVER['DOCUMENT_ROOT'];
                $target_dir = $path_s."web/upload/";
                $target_file = $target_dir . basename($_FILES["file_dok"]["name"]);
                $uploadOk = 1;
                $message="";
                $error_upload="";
                $FileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
                // Check if image file is a actual image or fake image
                // if(isset($_POST["submit"])) {
                //     $check = getimagesize($_FILES["file_dok"]["tmp_name"]);
                //     if($check !== false) {
                //         $message= "File is an dok - " . $check["mime"] . ".";
                //         $uploadOk = 1;
                //     } else {
                //         $error_upload= "File is not an dok";
                //         $uploadOk = 0;
                //     }
                // }
                // Check if file already exists
                if (file_exists($target_file)) {
                    $error_upload= "Sorry, file already exists.";
                    $uploadOk = 0;
                }
                // Check file size
                if ($_FILES["file_dok"]["size"] > 2000000) {
                    $error_upload= "Sorry, your file is too large.";
                    $uploadOk = 0;
                }
                // Allow certain file formats
                if($FileType != "pdf" && $FileType != "txt" && $FileType != "doc"
                && $FileType != "docx" && $FileType != "xls" && $FileType != "xlsx" ) {
                    $error_upload= "Sorry, only PDF, TXT, DOC/DOCX & XLS/XLSX files are allowed.";
                    $uploadOk = 0;
                }
                // Check if $uploadOk is set to 0 by an error
                if ($uploadOk == 0) {
                    $error_upload .= "Sorry, your file was not uploaded.";
                // if everything is ok, try to upload file
                } else {
                    if (move_uploaded_file($_FILES["file_dok"]["tmp_name"], $target_file)) {
                        $message = "The file ". basename( $_FILES["file_dok"]["name"]). " has been uploaded.";
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

                $filedok="/upload/".basename($_FILES["file_dok"]["name"]);
            
            }else{
                $filedok="-";
            }

            $sql= "insert into rt_buku_p (no_bukti,kode_lokasi,jenis,rw,rt,no_rumah,keterangan,tanggal,file_gambar,file_dok,nik_user,tgl_input,kode_pp) values ('$id','$kode_lokasi','".$data['jenis']."','".$data['rw']."','".$data['rt']."','".$data['no_rumah']."','".$data['keterangan']."','".$data['tanggal']."','$filepath','$filedok','$nik',getdate(),'$kode_pp')";
            
            array_push($exec,$sql);
            $res=executeArray($exec);

            $tmp=array();
            $kode = array();
            $sts=false;
            if ($res)
            {	
                $tmp="sukses";
                $sts=true;
            }else{
                $tmp="gagal";
                unlink($target_file);
                $sts=false;
            }	

            $response["message"] =$tmp."-".$message;
            $response["error"] =$error_upload;
            $response["status"] = $sts;
            // $response["sql"] = $sql;
            // $response["id"] = $id;
        }else{
            $response['status']=false;
            $response['message'] = "Unauthorized Access 2";
        }
    }else{
        $response['status']=false;
        $response['message'] = "Unauthorized Access 1";
    }
    echo json_encode($response);
}
    
function ubahInfo(){
    getKoneksi();
    $data = $_POST;
    if(arrayKeyCheck(array('api_key', 'username', 'kode_lokasi','periode'), $data)){
        if(authKey($data["api_key"], 'RTRW', $data['username'])){
            $exec = array();
            if(!empty($_FILES["foto"]["name"])){
                $path_s = $_SERVER['DOCUMENT_ROOT'];
                $target_dir = $path_s."web/upload/";
                $target_file = $target_dir . basename($_FILES["foto"]["name"]);
                $uploadOk = 1;
                $message="";
                $error_upload="";
                $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
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
                        $message = "The file ". basename( $_FILES["foto"]["name"]). " has been uploaded.";
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

                $filepath="/upload/".basename($_FILES["foto"]["name"]);

            }else{
        
                $filepath="";
            }

            if(!empty($_FILES["file_dok"]["name"])){
                $path_s = $_SERVER['DOCUMENT_ROOT'];
                $target_dir = $path_s."web/upload/";
                $target_file = $target_dir . basename($_FILES["file_dok"]["name"]);
                $uploadOk = 1;
                $message="";
                $error_upload="";
                $FileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
                
                // Check if file already exists
                if (file_exists($target_file)) {
                    $error_upload= "Sorry, file already exists.";
                    $uploadOk = 0;
                }
                // Check file size
                if ($_FILES["file_dok"]["size"] > 2000000) {
                    $error_upload= "Sorry, your file is too large.";
                    $uploadOk = 0;
                }
                // Allow certain file formats
                if($FileType != "pdf" && $FileType != "txt" && $FileType != "doc"
                && $FileType != "docx" && $FileType != "xls" && $FileType != "xlsx" ) {
                    $error_upload= "Sorry, only PDF, TXT, DOC/DOCX & XLS/XLSX files are allowed.";
                    $uploadOk = 0;
                }
                // Check if $uploadOk is set to 0 by an error
                if ($uploadOk == 0) {
                    $error_upload= "Sorry, your file was not uploaded.";
                // if everything is ok, try to upload file
                } else {
                    if (move_uploaded_file($_FILES["file_dok"]["tmp_name"], $target_file)) {
                        $message = "The file ". basename( $_FILES["file_dok"]["name"]). " has been uploaded.";
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

                $filedok="/upload/".basename($_FILES["file_dok"]["name"]);

            }else{
        
                $filedok="";
            }

            if ($_POST['jenis'] == "RW"){
                $kolom_edit = ",rw='".$_POST['rw']."' ";
            }else if($_POST['jenis'] == "RT"){
                $kolom_edit = ",rt='".$_POST['rt']."' ";
            }else if($_POST['jenis'] == "No Rumah"){
                $kolom_edit = ",no_rumah='".$_POST['no_rumah']."' ";
            }else{
                $kolom_edit = "";
            }

            if($filepath == ""){
                $kolom_edit.="";
            }else{
                $kolom_edit.=",file_gambar='".$filepath."' ";
            }

            if($filedok == ""){
                $kolom_edit.="";
            }else{
                $kolom_edit.=",file_dok='".$filedok."' ";
            }

            $sql="update rt_buku_p set keterangan='".$_POST['keterangan']."',jenis ='".$_POST['jenis']."',tanggal='".$_POST['tanggal']."' $kolom_edit where no_bukti = '".$_POST['no_bukti']."' and kode_lokasi='".$_POST['kode_lokasi']."' ";
            array_push($exec,$sql);
            $res=executeArray($exec);

            $tmp=array();
            $kode = array();
            if ($res)
            {	
                $tmp="sukses";
                $sts=true;
            }else{
                $tmp="gagal";
                $sts=false;
            }		
            $result["message"] =$tmp;
            $result["status"] = $sts;

        }else{
            $response['status']=false;
            $response['message'] = "Unauthorized Access 2";
        }
    }else{
        $response['status']=false;
        $response['message'] = "Unauthorized Access 1";
    }
    echo json_encode($response);
}
    
function hapusInfo(){
    getKoneksi();
    $data = $_POST;
    if(arrayKeyCheck(array('api_key', 'username', 'kode_lokasi','periode'), $data)){
        if(authKey($data["api_key"], 'RTRW', $data['username'])){
            $exec = array();

            $pathini = $_SERVER['DOCUMENT_ROOT'];
            $sql2="select file_gambar,file_dok from rt_buku_p where no_bukti='".$_POST['id']."' and kode_lokasi='".$_POST['kode_lokasi']."' ";
            $rs2=execute($sql2);

            $fullpath=$pathini.$rs2->fields[0];
            $fullpath2=$pathini.$rs2->fields[1];

            $sql="delete from rt_buku_p where no_bukti='".$_POST['id']."' and kode_lokasi='".$_POST['kode_lokasi']."'";

            array_push($exec,$sql);
            $res=executeArray($exec);

            $tmp=array();
            $kode = array();
            if ($res)
            {	
                $tmp="sukses";
                unlink($fullpath);
                if (!unlink($fullpath))
                {
                    $error_del.= "Error deleting $fullpath";
                }
                else
                {
                    $error_del.= "Deleting $fullpath";
                }
                unlink($fullpath2);
                if (!unlink($fullpath2))
                {
                    $error_del.= "Error deleting $fullpath2";
                }
                else
                {
                    $error_del.= "Deleting $fullpath2";
                }
                $sts=true;
            }else{
                $tmp="gagal";
                $sts=false;
            }		
            $response["message"] =$tmp;
            $response["status"] = $sts;
            // $response["sql"] = $sql2;
            $response["path"] = $fullpath;
            $response["error_del"] = $error_del;
        }else{
            $response['status']=false;
            $response['message'] = "Unauthorized Access 2";
        }
    }else{
        $response['status']=false;
        $response['message'] = "Unauthorized Access 1";
    }
    echo json_encode($response);

}

?>
