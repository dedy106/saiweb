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
        case 'DELETE':
            hapus();
        break;
        default:
            // Invalid Request Method
            header("HTTP/1.0 405 Method Not Allowed");
        break;
    }

    function getKoneksi(){
        $root_lib=$_SERVER["DOCUMENT_ROOT"];
		if (substr($root_lib,-1)!="/") {
			$root_lib=$root_lib."/";
		}
		include_once($root_lib.'app/dago/setting.php');
    }

    function getPekerjaan(){
        
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $kode_lokasi = $_GET['kode_lokasi'];
            
            $sql="select id_pekerjaan, nama from dgw_pekerjaan where kode_lokasi='$kode_lokasi'";
            
            $response["daftar"]=dbResultArray($sql);
            $response['status'] = TRUE;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }

    
    function cekAuth($user){
        getKoneksi();
        $user = qstr($user);

        $schema = db_Connect();
        $auth = $schema->SelectLimit("SELECT * FROM hakakses where nik=$user ", 1);
        if($auth->RecordCount() > 0){
            return true;
        }else{
            return false;
        }
    }

    function generateKode($tabel, $kolom_acuan, $prefix, $str_format){
        $query = execute("select right(max($kolom_acuan), ".strlen($str_format).")+1 as id from $tabel where $kolom_acuan like '$prefix%'");
        $kode = $query->fields[0];
        $id = $prefix.str_pad($kode, strlen($str_format), $str_format, STR_PAD_LEFT);
        return $id;
    }

    function isUnik($isi){
        getKoneksi();

        $schema = db_Connect();
        if($isi != "-"){

            $auth = $schema->SelectLimit("SELECT id_peserta FROM dgw_peserta where id_peserta='$isi' ", 1);
            if($auth->RecordCount() > 0){
                return false;
            }else{
                return true;
            }
        }else{
            return true;
        }
    }

    function getJamaah(){
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $query = '';
            $output = array();
        
            $kode_lokasi = $_GET['kode_lokasi'];
            $query .= "select no_peserta,id_peserta,nama,jk,status from dgw_peserta where kode_lokasi='".$kode_lokasi."'  ";

            $column_array = array('no_peserta','id_peserta','nama','jk','status');
            $order_column = 'ORDER BY no_peserta '.$_GET['order'][0]['dir'];
            $column_string = join(',', $column_array);

            $res = execute($query);
            $jml_baris = $res->RecordCount();
            if(!empty($_GET['search']['value']))
            {
                $search = $_GET['search']['value'];
                $filter_string = " and (";
        
                for($i=0; $i<count($column_array); $i++){
        
                    if($i == (count($column_array) - 1)){
                        $filter_string .= $column_array[$i]." like '".$search."%' )";
                    }else{
                        $filter_string .= $column_array[$i]." like '".$search."%' or ";
                    }
                }
        
        
                $query.=" $filter_string ";
            }
        
            if(isset($_GET["order"]))
            {
                $query .= ' ORDER BY '.$column_array[$_GET['order'][0]['column']].' '.$_GET['order'][0]['dir'];
            }
            else
            {
                $query .= ' ORDER BY no_peserta ';
            }
            if($_GET["length"] != -1)
            {
                $query .= ' OFFSET ' . $_GET['start'] . ' ROWS FETCH NEXT ' . $_GET['length'] . ' ROWS ONLY ';
            }
            $statement = execute($query);
            $data = array();
            $filtered_rows = $statement->RecordCount();
            while($row = $statement->FetchNextObject($toupper=false))
            {
                $sub_array = array();
                $sub_array[] = $row->no_peserta;
                $sub_array[] = $row->id_peserta;
                $sub_array[] = $row->nama;
                $sub_array[] = $row->jk;
                $sub_array[] = $row->status; 
                $data[] = $sub_array;
            }
            $response = array(
                "draw"				=>	intval($_GET["draw"]),
                "recordsTotal"		=> 	$filtered_rows,
                "recordsFiltered"	=>	$jml_baris,
                "data"				=>	$data,
            );
            
            $response["status"] = true;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }
    


    function getEdit(){
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $id=$_GET['no_peserta'];    
        
            $response = array("message" => "", "rows" => 0, "status" => "" );
        
            $sql="select no_peserta,id_peserta,nama,tempat,tgl_lahir,jk,status,ibu,ayah,alamat,kode_pos,telp,hp,email,pekerjaan,bank,norek,cabang,namarek,nopass,issued,ex_pass,kantor_mig,ec_telp,ec_hp,sp,th_haji,th_umroh,foto,pendidikan from dgw_peserta where kode_lokasi='".$_GET['kode_lokasi']."' and no_peserta='$id' ";
            
            $response['daftar'] = dbResultArray($sql);
            $response['status'] = TRUE;
            $response['sql']=$sql;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    
    }

    function getEditByKTP(){
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $id=$_GET['id_peserta'];    
        
            $response = array("message" => "", "rows" => 0, "status" => "" );
        
            $sql="select no_peserta,id_peserta,nama,tempat,tgl_lahir,jk,status,ibu,ayah,alamat,kode_pos,telp,hp,email,pekerjaan,bank,norek,cabang,namarek,nopass,issued,ex_pass,kantor_mig,ec_telp,ec_hp,sp,th_haji,th_umroh,foto,pendidikan from dgw_peserta where kode_lokasi='".$_GET['kode_lokasi']."' and id_peserta='$id' ";
            
            $response['daftar'] = dbResultArray($sql);
            $response['status'] = TRUE;
            $response['sql']=$sql;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    
    }

    function simpan(){
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            if(isUnik($_POST["id_peserta"])){
                $data=$_POST;
                $kode_lokasi=$data['kode_lokasi'];
                $exec = array();
                $tahun = date('y');
                $no_peserta = generateKode("dgw_peserta", "no_peserta", $tahun, "00001");
                
                
                if(ISSET($_FILES["file_gambar"]["name"]) AND !empty($_FILES["file_gambar"]["name"])){
                    
                    $path_s = $_SERVER['DOCUMENT_ROOT'];
                    $target_dir = $path_s."upload/";
                    $target_file = $target_dir . basename($_FILES["file_gambar"]["name"]);
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
                            $message = "The file ". $namaFileBaru. " has been uploaded.";
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
                    
                    $filepath=$namaFileBaru;
                }else{
                    $filepath="-";
                }
                
                $sql1= "insert into dgw_peserta(no_peserta,kode_lokasi,id_peserta,nama,tempat,tgl_lahir,jk,status,ibu,alamat,kode_pos,telp,hp,email,pekerjaan,bank,norek,cabang,namarek,nopass,issued,ex_pass,kantor_mig,ec_telp,ec_hp,sp,th_haji,th_umroh,foto,ayah,pendidikan) values ('$no_peserta','$kode_lokasi','".$data['id_peserta']."','".$data['nama']."','".$data['tempat']."','".$data['tgl_lahir']."','".$data['jk']."','".$data['status']."','".$data['ibu']."','".$data['alamat']."','".$data['kode_pos']."','".$data['telp']."','".$data['hp']."','".$data['email']."','".$data['pekerjaan']."','".$data['bank']."','".$data['norek']."','".$data['cabang']."','".$data['namarek']."','".$data['nopass']."','".$data['issued']."','".$data['ex_pass']."','".$data['kantor_mig']."','".$data['ec_telp']."','".$data['ec_hp']."','".$data['sp']."','".$data['th_haji']."','".$data['th_umroh']."','".$filepath."','".$data['ayah']."','".$data['pendidikan']."') ";
                
                array_push($exec,$sql1);
                
                $rs=executeArray($exec,$err);
                
                if ($err == null)
                {	
                    $tmp="sukses";
                    $sts=true;
                }else{
                    $tmp=$err;
                    $sts=false;
                }	         
            }else{
                $tmp=" error:Duplicate Entry. No Ktp sudah terdaftar di database !";
                $sts=false;
            }

            $response["message"] =$tmp;
            $response["status"] = $sts;
            $response["sql"] = $exec;
            $response["error"] = $error_upload;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }
    

    function ubah(){
        session_start();
        getKoneksi();
        $data = $_POST;
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $exec = array();
            
            if(ISSET($_FILES["file_gambar"]["name"]) AND !empty($_FILES["file_gambar"]["name"])){

                $path_s = $_SERVER['DOCUMENT_ROOT'];
                $target_dir = $path_s."upload/";
                $target_file = $target_dir . basename($_FILES["file_gambar"]["name"]);
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
                    // $error_upload= "Sorry, file already exists.";
                    // $uploadOk = 0;
                    unlink($namaFileBaru,$target_file);
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
                        $message = "The file ". $namaFileBaru. " has been uploaded.";
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
    
                $filepath=$namaFileBaru;
            }else{
                $sql = "select foto from dgw_peserta where  no_peserta='".$data['no_peserta']."' and kode_lokasi='".$data['kode_lokasi']."' ";
                $refoto = execute($sql);
                $filepath=$refoto->fields[0];
            }

            $del = "delete from dgw_peserta where no_peserta='".$data['no_peserta']."' and kode_lokasi='".$data['kode_lokasi']."' ";
            array_push($exec,$del);

            $sql1= "insert into dgw_peserta(no_peserta,kode_lokasi,id_peserta,nama,tempat,tgl_lahir,jk,status,ibu,alamat,kode_pos,telp,hp,email,pekerjaan,bank,norek,cabang,namarek,nopass,issued,ex_pass,kantor_mig,ec_telp,ec_hp,sp,th_haji,th_umroh,foto,ayah,pendidikan) values ('".$data['no_peserta']."','".$data['kode_lokasi']."','".$data['id_peserta']."','".$data['nama']."','".$data['tempat']."','".$data['tgl_lahir']."','".$data['jk']."','".$data['status']."','".$data['ibu']."','".$data['alamat']."','".$data['kode_pos']."','".$data['telp']."','".$data['hp']."','".$data['email']."','".$data['pekerjaan']."','".$data['bank']."','".$data['norek']."','".$data['cabang']."','".$data['namarek']."','".$data['nopass']."','".$data['issued']."','".$data['ex_pass']."','".$data['kantor_mig']."','".$data['ec_telp']."','".$data['ec_hp']."','".$data['sp']."','".$data['th_haji']."','".$data['th_umroh']."','".$filepath."','".$data['ayah']."','".$data['pendidikan']."') ";

            array_push($exec,$sql1);

            $rs=executeArray($exec,$err);
            
            if ($err == null)
            {	
                $tmp="sukses";
                $sts=true;
            }else{
                $tmp=$err;
                $sts=false;
            }	 	
            $response["message"] =$tmp;
            $response["status"] = $sts;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }
    

    function hapus(){
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            
            $exec = array();
            parse_str(file_get_contents('php://input'), $_DELETE);
            $data = $_DELETE;
            $del = "delete from dgw_peserta where no_peserta='".$data['no_peserta']."' and kode_lokasi='".$data['kode_lokasi']."' ";
            array_push($exec,$del);
            
            $rs=executeArray($exec,$err);
            
            if ($err == null)
            {	
                $tmp="sukses";
                $sts=true;
            }else{
                $tmp=$err;
                $sts=false;
            }	 	
            $response["message"] =$tmp;
            $response["status"] = $sts;
        }else{
                
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }