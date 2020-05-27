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
        case 'PUT':
            ubah();
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

    function joinNum2($num){
        // menggabungkan angka yang di-separate(10.000,75) menjadi 10000.00
        $num = str_replace(".", "", $num);
        $num = str_replace(",", ".", $num);
        return $num;
    }

    
    function cekAuth($user){
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

    function isUnik($isi){
        getKoneksi();

        $schema = db_Connect();
        $auth = $schema->SelectLimit("SELECT kode_barang FROM brg_barang where kode_barang='$isi' ", 1);
        if($auth->RecordCount() > 0){
            return false;
        }else{
            return true;
        }
    }

    function getKlp(){
        
        session_start();
        getKoneksi();
        if($_COOKIE['isLogedIn'] AND cekAuth($_COOKIE['userLog'])){
            $kode_lokasi = $_GET['kode_lokasi'];
            
            $sql="select kode_klp, nama from brg_barangklp where kode_lokasi='$kode_lokasi'";
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
    function getSatuan(){
        
        session_start();
        getKoneksi();
        if($_COOKIE['isLogedIn'] AND cekAuth($_COOKIE['userLog'])){
            $kode_lokasi = $_GET['kode_lokasi'];
            
            $sql="select kode_satuan,nama from brg_satuan where kode_lokasi='$kode_lokasi'";
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

    function getBarang(){
        session_start();
        getKoneksi();
        if($_COOKIE['isLogedIn'] AND cekAuth($_COOKIE['userLog'])){
            $query = '';
            $output = array();
        
            $kode_lokasi = $_REQUEST['kode_lokasi'];
            $query .= "select kode_barang,nama,sat_kecil,pabrik,hna from brg_barang where kode_lokasi='".$kode_lokasi."'  ";

            $column_array = array('kode_barang','nama','sat_kecil','pabrik','hna');
            $order_column = 'ORDER BY kode_barang '.$_POST['order'][0]['dir'];
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
                $query .= ' ORDER BY kode_barang ';
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
                $sub_array[] = $row->kode_barang;
                $sub_array[] = $row->nama;
                $sub_array[] = $row->sat_kecil;
                $sub_array[] = $row->pabrik;                
                $sub_array[] = $row->hna;
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
        if($_COOKIE['isLogedIn'] AND cekAuth($_COOKIE['userLog'])){
            $id=$_GET['kode_barang'];    
        
            $response = array("message" => "", "rows" => 0, "status" => "" );
        
            $sql="select kode_barang,nama,sat_kecil as satuan,hna,pabrik as keterangan,flag_aktif,ss,sm1,sm2,mm1,mm2,fm1,fm2,kode_klp,file_gambar,barcode,hrg_satuan,ppn,profit from brg_barang where kode_lokasi='".$_GET['kode_lokasi']."' and kode_barang='$id' ";
            
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
        if($_COOKIE['isLogedIn'] AND cekAuth($_COOKIE['userLog'])){
            if(isUnik($_POST["kode_barang"])){
                $data=$_POST;
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

                $hna=joinNum2($_POST['harga_jual']);
                $ss=joinNum2($_POST['ss']);
                $sm1=joinNum2($_POST['sm1']);
                $sm2=joinNum2($_POST['sm2']);
                $mm1=joinNum2($_POST['mm1']);
                $mm2=joinNum2($_POST['mm2']);
                $fm1=joinNum2($_POST['fm1']);
                $fm2=joinNum2($_POST['fm2']);
                $hrg_satuan=joinNum2($_POST['hrg_satuan']);
                $ppn=joinNum2($_POST['ppn']);
                $profit=joinNum2($_POST['profit']);
    
                $sql1= "insert into brg_barang(kode_barang,nama,kode_lokasi,sat_kecil,sat_besar,jml_sat,hna,pabrik,flag_gen,flag_aktif,ss,sm1,sm2,mm1,mm2,fm1,fm2,kode_klp,file_gambar,barcode,hrg_satuan,ppn,profit) values ('".$data['kode_barang']."','".$data['nama']."','".$data['kode_lokasi']."','".$data['kode_satuan']."','-',1,".$hna.",'".$data['keterangan']."','-','1',".$ss.",".$sm1.",".$sm2.",".$mm1.",".$mm2.",".$fm1.",".$fm2.",'".$data['kode_klp']."','".$filepath."','".$data['barcode']."',$hrg_satuan,$ppn,$profit) ";
    
                array_push($exec,$sql1);
                
                $rs=executeArray($exec,$err);

                $tmp=array();
                $kode = array();
                $sts=false;
                if ($err == null)
                {	
                    $tmp="sukses disimpan";
                    $sts=true;
                }else{
                    $tmp=$err;
                    $sts=false;
                }	
            }else{
                $tmp=" error:Duplicate Entry. Kode Barang sudah ada didatabase !";
                $sts=false;
            }

            $response["message"] =$tmp;
            $response["status"] = $sts;
            $response["error"] = $error_upload;
            $response["sql"]=$exec;
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
        if($_COOKIE['isLogedIn'] AND cekAuth($_COOKIE['userLog'])){
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
                $sql = "select file_gambar from brg_barang where  kode_barang='".$data['kode_barang']."' and kode_lokasi='".$data['kode_lokasi']."' ";
                $refoto = execute($sql);
                $filepath=$refoto->fields[0];
            }

            $del = "delete from brg_barang where kode_barang='".$data['kode_barang']."' and kode_lokasi='".$data['kode_lokasi']."' ";
            array_push($exec,$del);

            $hna=joinNum2($_POST['harga_jual']);
            $ss=joinNum2($_POST['ss']);
            $sm1=joinNum2($_POST['sm1']);
            $sm2=joinNum2($_POST['sm2']);
            $mm1=joinNum2($_POST['mm1']);
            $mm2=joinNum2($_POST['mm2']);
            $fm1=joinNum2($_POST['fm1']);
            $fm2=joinNum2($_POST['fm2']);
            
            $hrg_satuan=joinNum2($_POST['hrg_satuan']);
            $ppn=joinNum2($_POST['ppn']);
            $profit=joinNum2($_POST['profit']);

            $sql1= "insert into brg_barang(kode_barang,nama,kode_lokasi,sat_kecil,sat_besar,jml_sat,hna,pabrik,flag_gen,flag_aktif,ss,sm1,sm2,mm1,mm2,fm1,fm2,kode_klp,file_gambar,barcode,hrg_satuan,ppn,profit) values ('".$data['kode_barang']."','".$data['nama']."','".$data['kode_lokasi']."','".$data['kode_satuan']."','-',1,".$hna.",'".$data['keterangan']."','-','1',".$ss.",".$sm1.",".$sm2.",".$mm1.",".$mm2.",".$fm1.",".$fm2.",'".$data['kode_klp']."','".$filepath."','".$data['barcode']."',$hrg_satuan,$ppn,$profit) ";

            array_push($exec,$sql1);

            $rs=executeArray($exec,$err);
            
            $tmp=array();
            $kode = array();
            $sts=false;
            if ($err == null)
            {	
                $tmp="sukses disimpan";
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
        if($_COOKIE['isLogedIn'] AND cekAuth($_COOKIE['userLog'])){
            
            $exec = array();
            parse_str(file_get_contents('php://input'), $_DELETE);
            $data = $_DELETE;
            $del = "delete from brg_barang where kode_barang='".$data['kode_barang']."' and kode_lokasi='".$data['kode_lokasi']."' ";
            array_push($exec,$del);
            
            $rs=executeArray($exec,$err);
            
            $tmp=array();
            $kode = array();
            $sts=false;
            if ($err == null)
            {	
                $tmp="sukses disimpan";
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