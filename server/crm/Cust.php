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
            else{
                insertCust();
            }
        break;
        case 'PUT':
        // Update Product
           
            updateCust();
        break;
        case 'DELETE':
        // Delete Product
            deleteCust();
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
    }

    function generateKode($tabel, $kolom_acuan, $prefix, $str_format){
        $query = execute("select right(max($kolom_acuan), ".strlen($str_format).")+1 as id from $tabel where $kolom_acuan like '$prefix%'");
        $data = $query->fields[0];
        $id = $prefix.str_pad($data, strlen($str_format), $str_format, STR_PAD_LEFT);
        return $id;
    }

    function cekAuth($user,$pass){
        getKoneksi();
        $user = qstr($user);
        $pass = qstr($pass);

        $schema = db_Connect();
        $auth = $schema->SelectLimit("SELECT * FROM hakakses where nik=$user and pass=$pass", 1);
        if($auth->RecordCount() > 0){
            return true;
        }else{
            return false;
        }
    }

    function getDatatableCust(){
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'],$_SESSION['userPwd'])){
            if ($_SESSION['token']==$_POST['token'] AND $_POST['token'] !=""){        
                $data = $_POST;
                $query = '';
                $output = array();
                
                $kode_lokasi = $_SESSION['lokasi'];
                $query .= "select kode_cust, nama,alamat,pic,no_telp,email from sai_cust where kode_lokasi = '$kode_lokasi'";
                
                $column_array = array('kode_cust','nama','alamat','pic','no_telp','email');
                $order_column = 'order by kode_cust '.$data['order'][0]['dir'];
                $column_string = join(',', $column_array);
                
                $res = execute($query);
                $jml_baris = $res->RecordCount();
                if(!empty($data['search']['value']))
                {
                    $search = $data['search']['value'];
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
                
                if(isset($data["order"]))
                {
                    $query .= ' order by '.$column_array[$data['order'][0]['column']].' '.$data['order'][0]['dir'];
                }
                else
                {
                    $query .= ' order by kode_cust ';
                }
                if($data["length"] != -1)
                {
                    $query .= ' offset ' . $data['start'] . ' rows fetch next ' . $data['length'] . ' rows only ';
                }
                $statement = execute($query);
                $data = array();
                $filtered_rows = $statement->RecordCount();
                while($row = $statement->FetchNextObject($toupper=false))
                {
                    $sub_array = array();
                    $sub_array[] = $row->kode_cust;
                    $sub_array[] = $row->nama;
                    $sub_array[] = $row->alamat;
                    $sub_array[] = $row->pic;
                    $sub_array[] = $row->no_telp;
                    $sub_array[] = $row->email;
                    $data[] = $sub_array;
                }
                $response = array(
                    "draw"				=>	intval($data["draw"]),
                    "recordsTotal"		=> 	$filtered_rows,
                    "recordsFiltered"	=>	$jml_baris,
                    "data"				=>	$data,
                );
                $response['status']=true;
            }else{
                $response['status']=false;
                $response['message'] = "Unauthorized Access, Token Invalid";
            }
        }else{
            $response['status']=false;
            $response['message'] = "Unauthorized Access, Login Required";
            
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    function insertCust() {
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'],$_SESSION['userPwd'])){
            if ($_SESSION['token']==$_POST['token'] AND $_POST['token'] !=""){ 
                $data = $_POST;
                $kode_cust = qstr(generateKode("sai_cust", "kode_cust", "CS", "001"));
                $kode_lokasi = qstr($_SESSION["lokasi"]);
                
                $nama=qstr($data["nama"]);
                $alamat=qstr($data["alamat"]);
                $pic=qstr($data["pic"]);
                $no_telp=qstr($data["no_telp"]);
                $email=qstr($data["email"]);

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
        
                    $filepath="/upload/".$namaFileBaru;
                    $filetype=$imageFileType;
                }else{
                    $filepath="-";
                    $filetype="-";
                }
                
                $query=" insert into sai_cust (kode_cust,kode_lokasi,nama,alamat,pic,no_telp,email,gambar)
                values ($kode_cust,$kode_lokasi,$nama,$alamat,$pic,$no_telp,$email,'$filepath') ";
                
                $sql = array();
                array_push($sql,$query);
                
                $rs=executeArray($sql);
                if($rs) {
                    $response=array(
                        'status' => 1,
                        'message' =>'customer sukses',
                        'query' => $query
                    );
                }
                else {
                    $response=array(
                        'status' => 0,
                        'message' =>'customer gagal'.$rs,
                        'query' => $query
                    );
                    
                }
            }else{
                $response['status']=false;
                $response['message'] = "Unauthorized Access, Token Invalid";
            }
        }else{
            $response['status']=false;
            $response['message'] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json'); dicomment soalnya bkin input jadi double
        echo json_encode($response);
    }

    function getEditCust(){
        session_start();
        getKoneksi();
        $data=$_GET;
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'],$_SESSION['userPwd'])){
            if ($_SESSION['token']==$data['token'] AND $data['token'] !=""){ 

                $sql="select kode_cust,nama,alamat,pic,no_telp,email,gambar
                from sai_cust
                where kode_lokasi= '".$_SESSION['lokasi']."' and kode_cust='".$_GET['kode_cust']."' ";
                
                $rs=execute($sql);
                
                $response["daftar"] = array();
                while($row = $rs->FetchNextObject($toupper = false)){
                    $response["daftar"][] = (array)$row;
                }
                $response['status']=true;
                $response['sql'] = $sql;
            }else{
                $response['status']=false;
                $response['message'] = "Unauthorized Access, Token Invalid";
            }
        }else{
            $response['status']=false;
            $response['message'] = "Unauthorized Access, Login Required";
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    function updateCust() {
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'],$_SESSION['userPwd'])){
            if ($_SESSION['token']==$_POST['token'] AND $_POST['token'] !=""){ 
                // parse_str(file_get_contents('php://input'), $_PUT);
                // $data = $_PUT;
                $data = $_POST;

                // if (!($data = fopen("php://input", "r")))
                //     throw new \Exception("Can't get PUT data.");

                
                $kode_cust=qstr($data["kode_cust"]);
                $kode_lokasi=qstr($_SESSION["lokasi"]);
                $nama=qstr($data["nama"]);
                $alamat=qstr($data["alamat"]);
                $pic=qstr($data["pic"]);
                $no_telp=qstr($data["no_telp"]);
                $email=qstr($data["email"]);

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
        
                    $filepath="/upload/".$namaFileBaru;
                    $filetype=$imageFileType;
                    $query="UPDATE sai_cust SET nama = $nama, alamat =$alamat, pic = $pic, no_telp=$no_telp,email=$email,gambar='$filepath'
                    WHERE kode_cust = $kode_cust and kode_lokasi = $kode_lokasi ";
                }else{
                    $query="UPDATE sai_cust SET nama = $nama, alamat =$alamat, pic = $pic, no_telp=$no_telp,email = $email
                    WHERE kode_cust = $kode_cust and kode_lokasi = $kode_lokasi ";
                }
                
                $sql = array();
                array_push($sql,$query);
                
                $rs=executeArray($sql);
                if($rs) {
                    $response=array(
                        'status' => 1,
                        'message' =>'customer berhasil ',
                        'query'=>$query,
                        'data' =>$data
                    );
                }
                else {
                    $response=array(
                        'status' => 0,
                        'message' =>'customer gagal '.$rs,
                        'query'=>$query
                    );
                }
            }else{
                $response['status']=false;
                $response['message'] = "Unauthorized Access, Token Invalid";
            }
        }else{
            $response['status']=false;
            $response['message'] = "Unauthorized Access, Login Required";
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    function deleteCust() {
        session_start();
        getKoneksi();
        parse_str(file_get_contents('php://input'), $_DELETE);
        $data = $_DELETE;
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'],$_SESSION['userPwd'])){
            if ($_SESSION['token']==$data['token'] AND $data['token'] !=""){ 
                $id = qstr($data["kode_cust"]);
                $kd_lok = qstr($_SESSION["lokasi"]);
                
                $query="DELETE FROM sai_cust where kode_cust=$id and kode_lokasi = $kd_lok" ;
                
                $sql = array();
                array_push($sql,$query);
                
                $rs=executeArray($sql);
                if($rs) {
                    $response=array(
                        'status' => 1,
                        'message' =>'customer berhasil',
                        'sql'=>$sql
                    );
                }
                else {
                    $response=array(
                        'status' => 0,
                        'message' =>'customer gagal'.$rs,
                        'sql'=>$sql
                    );
                }
            }else{
                $response['status']=false;
                $response['message'] = "Unauthorized Access, Token Invalid";
            }            
        }else{
            $response['status']=false;
            $response['message'] = "Unauthorized Access, Login Required";
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }
?>