<?php

    // class CrudNw{
    if(function_exists($_GET['fx'])) {
        $_GET['fx']();
    }

    function reverseDate2($ymd_or_dmy_date, $org_sep='-', $new_sep='-'){
        $arr = explode($org_sep, $ymd_or_dmy_date);
        return $arr[2].$new_sep.$arr[1].$new_sep.$arr[0];
    }

    function getLogo(){

   
        $kode_lokasi=$_POST['kode_lokasi'];
        $result = array("message" => "", "rows" => 0, "status" => "" );
    
        $sql1 = "SELECT logo FROM lokasi where kode_lokasi='".$kode_lokasi."' ";
        $rs = execute($sql1);					
        
        $result['daftar'] = array();
        while ($row = $rs->FetchNextObject(false)){
            $result['daftar'][] = (array)$row;
        }
        $result['status']=TRUE;
        $result['sql']=$sql1;
        echo json_encode($result);
    
    
    }

    function editLogo(){
        $kode_lokasi=$_POST['kode_lokasi'];

        if(ISSET($_FILES["file_gambar"]["name"]) AND !empty($_FILES["file_gambar"]["name"])){

            $path_s = $_SERVER['DOCUMENT_ROOT'];
            $target_dir = $path_s."image/";
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

            $filepath = basename($_FILES["file_gambar"]["name"]);
            $sql1= "update lokasi set logo='".$filepath."' where kode_lokasi='$kode_lokasi' ";
            $sql=[$sql1];
            $rs=executeArray($sql);

        }else{
            $rs=false;
        }
        
        $tmp=array();
        $kode = array();
        if ($rs)
        {	
            $tmp="sukses";
            $sts=true;
        }else{
            $tmp="gagal";
            // unlink($target_file);
            $sts=false;
        }	

        $result["message"] =$tmp."-".$message;
        $result["error"] =$error_upload;
        $result["status"] = $sts;
        $result["sql"] = $sql;
        echo json_encode($result);
    }

    
?>
