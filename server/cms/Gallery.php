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
		include_once($root_lib."lib/koneksi.php");
        include_once($root_lib."lib/helpers.php");
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

    function isUnik($isi){
        getKoneksi();

        $schema = db_Connect();
        $auth = $schema->SelectLimit("SELECT nik FROM karyawan where nik='$isi' ", 1);
        if($auth->RecordCount() > 0){
            return false;
        }else{
            return true;
        }
    }

    function getKtgGaleri(){
        
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $kode_lokasi = $_GET['kode_lokasi'];
            
            $sql="select kode_ktg, nama from lab_konten_ktg where kode_lokasi='$kode_lokasi' ";
            $rs=execute($sql);
            $response["daftar"]=array();
            while ($row = $rs->FetchNextObject(false)){
                $response['daftar'][] = (array)$row;
            }
            $response['status'] = TRUE;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }
    

    function getView(){
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $query = '';
            $output = array();
        
            $kode_lokasi = $_POST['kode_lokasi'];
            $query .= "select id,nama,jenis from lab_konten_galeri where kode_lokasi='".$kode_lokasi."'  ";

            $column_array = array('id','nama','jenis');
            $order_column = 'ORDER BY id '.$_POST['order'][0]['dir'];
            $column_string = join(',', $column_array);

            $res = execute($query);
            $jml_baris = $res->RecordCount();
            if(!empty($_POST['search']['value']))
            {
                $search = $_POST['search']['value'];
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
        
            if(isset($_POST["order"]))
            {
                $query .= ' ORDER BY '.$column_array[$_POST['order'][0]['column']].' '.$_POST['order'][0]['dir'];
            }
            else
            {
                $query .= ' ORDER BY id ';
            }
            if($_POST["length"] != -1)
            {
                $query .= ' OFFSET ' . $_POST['start'] . ' ROWS FETCH NEXT ' . $_POST['length'] . ' ROWS ONLY ';
            }
            $statement = execute($query);
            $data = array();
            $filtered_rows = $statement->RecordCount();
            while($row = $statement->FetchNextObject($toupper=false))
            {
                $sub_array = array();
                $sub_array[] = $row->id;
                $sub_array[] = $row->nama;
                $sub_array[] = $row->jenis; 
                $data[] = $sub_array;
            }
            $response = array(
                "draw"				=>	intval($_POST["draw"]),
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
            $id=$_GET['kode'];    
        
            $response = array("message" => "", "rows" => 0, "status" => "" );
        
            $sql="select id,kode_lokasi,nama,keterangan,jenis,file_gambar,kode_ktg from lab_konten_galeri where kode_lokasi='".$_GET['kode_lokasi']."' and id='$id' ";
            
            $rs = execute($sql);					
            
            while ($row = $rs->FetchNextObject(false)){
                $response['daftar'][] = (array)$row;
            }
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
          
            $data=$_POST;
            $kode_lokasi=$data['kode_lokasi'];
            $exec = array();
            
            $sql = "select max(id) from lab_konten_galeri ";
            $cek = execute($sql);
            if($cek->RecordCount() > 0){
                $id = intval($cek->fields[0])+1;
            }else{
                $id = 1;
            }
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
                $filetype = $imageFileType;
                $sql1= "insert into lab_konten_galeri(id,kode_lokasi,nama,keterangan,file_gambar,flag_aktif,jenis,nik_user,tgl_input,kode_ktg,file_type) values ('$id','".$data['kode_lokasi']."','".$data['nama']."','".$data['keterangan']."','".$filepath."','1','".$data['jenis']."','".$data['nik_user']."',getdate(),'".$data['kode_ktg']."','".$filetype."') ";
                
                array_push($exec,$sql1);
                
                $rs=executeArray($exec,$err);
                
                $tmp=array();
                $kode = array();
                $sts=false;
                if ($err == NULL)
                {	
                    $tmp="sukses";
                    $sts=true;
                }else{
                    $tmp="gagal".$err;
                    $sts=false;
                }	
            }else{
                $filepath="-";
                $filetype="-";
                $tmp="Foto harus di isi! ";
                $sts=false;
            }
            
            
            $response["message"] =$tmp;
            $response["status"] = $sts;
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
                $filetype=$imageFileType;
            }else{
                $sql = "select file_gambar,file_type from lab_konten_galeri where  id='".$data['id']."' and kode_lokasi='".$data['kode_lokasi']."' ";
                $refoto = execute($sql);
                $filepath=$refoto->fields[0];
                $filetype=$refoto->fields[1];
            }
        
            // $del = "delete from lab_konten_galeri where id='".$data['id']."' and kode_lokasi='".$data['kode_lokasi']."' ";

            // array_push($exec,$del);

            // $sql1= "insert into lab_konten_galeri(kode_lokasi,id,nama,keterangan,file_gambar,flag_aktif,jenis,nik_user,tgl_input,kode_ktg,file_type) values ('".$data['id']."','".$data['kode_lokasi']."','".$data['nama']."','".$data['keterangan']."','".$filepath."','1','".$data['jenis']."','".$data['nik_user']."',getdate(),'".$data['kode_ktg']."','".$filetype."') ";
            $sql = "update lab_konten_galeri set nama='".$data['nama']."' ,keterangan='".$data['keterangan']."',file_gambar='$filepath',flag_aktif=1,jenis='".$data['jenis']."',nik_user='".$data['nik_user']."',tgl_input=getdate(),kode_ktg='".$data['kode_ktg']."',file_type='$filetype' where kode_lokasi='".$data['kode_lokasi']."' and id='".$data['id']."' ";
            
            array_push($exec,$sql);

            $rs=executeArray($exec,$err);

            $tmp=array();
            $kode = array();
            if ($err == NULL)
            {	
                $tmp="sukses";
                $sts=true;
            }else{
                $tmp="gagal".$err;
                $sts=false;
            }		
            $response["message"] =$tmp;
            $response["status"] = $sts;
            $response["exec"] = $exec;
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
            $del = "delete from lab_konten_galeri where id='".$data['kode']."' and kode_lokasi='".$data['kode_lokasi']."' ";
            array_push($exec,$del);
            
            $rs=executeArray($exec,$err);
            $tmp=array();
            $kode = array();
            if ($err == NULL)
            {	
                $tmp="sukses";
                $sts=true;
            }else{
                $tmp="gagal";
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