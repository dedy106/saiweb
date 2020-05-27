<?php    

    $request_method=$_SERVER["REQUEST_METHOD"];

    switch($request_method) {
        case 'GET':
            if(isset($_GET["fx"]) AND function_exists($_GET['fx'])){
                $_GET['fx']();
            }
        break;
        case 'POST':
            // Insert Product
            if(isset($_GET["fx"]) AND function_exists($_GET['fx'])){
                $_GET['fx']();
            }else{
                insertProspecting();
            }
        break;
        // case 'PUT':
        // // Update Product
           
        //     updateProduk();
        // break;
        // case 'DELETE':
        // // Delete Product
        //     deleteProduk();
        // break;
        default:
            // Invalid Request Method
            header("HTTP/1.0 405 Method Not Allowed");
        break;
    }

    function getKoneksi(){
        $root_lib=$_SERVER["DOCUMENT_ROOT"];
        include_once($root_lib."web/lib/koneksi.php");
        include_once($root_lib."web/lib/helpers.php");
    }

    function generateKode($tabel, $kolom_acuan, $prefix, $str_format){
        $query = execute("select right(max($kolom_acuan), ".strlen($str_format).")+1 as id from $tabel where $kolom_acuan like '$prefix%'");
        $kode = $query->fields[0];
        $id = $prefix.str_pad($kode, strlen($str_format), $str_format, STR_PAD_LEFT);
        return $id;
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
        $auth = $schema->SelectLimit("SELECT * FROM api_key_auth where api_key=$key and modul=$modul $user_str", 1);
        if($auth->RecordCount() > 0){
            return true;
        }else{
            return false;
        }
    }

    function getDatatable(){
        getKoneksi();
        $data = $_POST;
        if(arrayKeyCheck(array('api_key', 'username', 'kode_lokasi'), $data)){
            if(authKey($data["api_key"], 'crm', $data['username'])){
                
                $query = '';
                $output = array();
                $jenis = $data['jenis'];
                $kode_lokasi = $_REQUEST['kode_lokasi'];
                if($jenis == null OR $jenis == 'new'){
                    $query = "select a.no_bukti,a.tanggal,a.keterangan,a.nilai, b.nama as nama_cust,c.nama as nama_produk,k.nama as nama_karyawan from sai_proses01 a left join sai_cust b on a.kode_cust=b.kode_cust left join sai_produk c on a.kode_produk=c.kode_produk
                    left join sai_karyawan k on a.nik=k.nik where a.status='0' and a.flag_keep !='0' and a.progress='SA01' and a.kode_lokasi = '".$kode_lokasi."'";
                    $jenis = 'new';
                }else if($jenis == 'finish'){
                    $query = "select a.no_bukti,a.tanggal,a.keterangan,a.nilai, b.nama as nama_cust,c.nama as nama_produk,k.nama as nama_karyawan from sai_proses01 a left join sai_cust b on a.kode_cust=b.kode_cust left join sai_produk c on a.kode_produk=c.kode_produk left join sai_karyawan k on a.nik=k.nik where a.status='2' and a.progress='SA01' and a.kode_lokasi = '".$kode_lokasi."'";
                }

                $column_array = array('a.no_bukti','a.tanggal','a.keterangan','a.nilai', 'b.nama','c.nama','k.nama');
                $order_column = 'order by a.no_bukti '.$data['order'][0]['dir'];
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
                    $query .= ' order by a.no_bukti ';
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
                    $sub_array[] = $row->no_bukti;
                    $sub_array[] = $row->tanggal;
                    $sub_array[] = $row->keterangan;
                    $sub_array[] = $row->nama_cust;
                    $sub_array[] = $row->nama_produk;
                    $sub_array[] = $row->nama_karyawan;
                    $sub_array[] = $row->nilai;
                    $data[] = $sub_array;
                }
                $response = array(
                    "draw"				=>	intval($data["draw"]),
                    "recordsTotal"		=> 	$filtered_rows,
                    "recordsFiltered"	=>	$jml_baris,
                    "data"				=>	$data,
                );
            }else{
                $response['error'] = "Unauthorized Access 2";
                
            }
        }else{
            $response['error'] = "Unauthorized Access 1";
            
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    function getCust(){
        getKoneksi();

        if(arrayKeyCheck(array('api_key', 'username', 'kode_lokasi'), $_GET)){
            if(authKey($_GET["api_key"], 'crm', $_GET['username'])){
                $kode_lokasi = $_GET['kode_lokasi'];  
                $response = array("message" => "", "rows" => 0, "status" => "" ); 				
                $sql = "select kode_cust,nama from sai_cust where kode_lokasi='$kode_lokasi' ";
                $rs = execute($sql);
                while($row = $rs->FetchNextObject($toupper))
                {
                    $response['daftar'][] = (array)$row;
                }
                                    
                $response['status'] = true;
            }else{
                $response['error'] = "Unauthorized Access 2";
                
            }
        }else{
            $response['error'] = "Unauthorized Access 1";
            
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    function getProduk(){
        getKoneksi();

        if(arrayKeyCheck(array('api_key', 'username', 'kode_lokasi'), $_GET)){
            if(authKey($_GET["api_key"], 'crm', $_GET['username'])){
                $kode_lokasi = $_GET['kode_lokasi'];  
                $response = array("message" => "", "rows" => 0, "status" => "" ); 				
                $sql = "select kode_produk,nama from sai_produk where kode_lokasi='$kode_lokasi' ";
                $rs = execute($sql);
                while($row = $rs->FetchNextObject($toupper))
                {
                    $response['daftar'][] = (array)$row;
                }
                                    
                $response['status'] = true;
            }else{
                $response['error'] = "Unauthorized Access 2";
                
            }
        }else{
            $response['error'] = "Unauthorized Access 1";
            
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    function getJenisDok(){
        getKoneksi();

        if(arrayKeyCheck(array('api_key', 'username', 'kode_lokasi'), $_GET)){
            if(authKey($_GET["api_key"], 'crm', $_GET['username'])){
                $kode_lokasi = $_GET['kode_lokasi'];  
                $response = array("message" => "", "rows" => 0, "status" => "" ); 				
                $sql = "select kode_dok,nama from sai_dok where kode_proses='SA01' and kode_lokasi='$kode_lokasi' ";
                $rs = execute($sql);
                while($row = $rs->FetchNextObject($toupper))
                {
                    $response['daftar'][] = (array)$row;
                }
                                    
                $response['status'] = true;
            }else{
                $response['error'] = "Unauthorized Access 2";
                
            }
        }else{
            $response['error'] = "Unauthorized Access 1";
            
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    function getKaryawan(){
        getKoneksi();

        if(arrayKeyCheck(array('api_key', 'username', 'kode_lokasi'), $_GET)){
            if(authKey($_GET["api_key"], 'crm', $_GET['username'])){
                $kode_lokasi = $_GET['kode_lokasi'];  
                $response = array("message" => "", "rows" => 0, "status" => "" ); 				
                $sql = "select nik,nama from sai_karyawan where kode_lokasi='$kode_lokasi' ";
                $rs = execute($sql);
                while($row = $rs->FetchNextObject($toupper))
                {
                    $response['daftar'][] = (array)$row;
                }
                                    
                $response['status'] = true;
            }else{
                $response['error'] = "Unauthorized Access 2";
                
            }
        }else{
            $response['error'] = "Unauthorized Access 1";
            
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }


    function insertProspecting() {
        getKoneksi();
        if(arrayKeyCheck(array('api_key', 'username', 'kode_lokasi'), $_POST)){
            if(authKey($_POST["api_key"], 'crm', $_POST['username'])){
                $data = $_POST;
                $kode_lokasi = qstr($data["kode_lokasi"]);
                $exec_array=array();
                if($data["no_bukti"] == ""){
                    $no_bukti = qstr(generateKode("sai_proses01", "no_bukti", "SA-", "0001"));
                    $edit = false;
                    if($data['status']=='0'){
						$flag_keep='1';
					}else{
						$flag_keep='';
					}
                }else{
                    $no_bukti = qstr($data["no_bukti"]);
                    $edit = true;
                    if($data['status']=='0'){
                        $flag_keep="1";
                        $upd1 = "0";
                        $no_bukti = qstr(generateKode("sai_proses01", "no_bukti", "SA-", "0001"));
                        $update1 = "update sai_proses01 set flag_keep ='$upd1' where no_bukti='".$data['no_bukti']."' and kode_lokasi=$kode_lokasi ";

                        array_push($exec_array,$update1);
					}else{
						$flag_keep='';
					}
                }
                
                $error_upload = "";
                if(arrayKeyCheck(array('kode_dok'), $data) AND arrayKeyCheck(array('file'), $_FILES) AND !empty($_FILES['file']['tmp_name'])){
                    $data['kode_dok'] = array_values(array_filter($data['kode_dok'], function($value) { return $value !== ''; }));
                    $_FILES['file']['name'] = array_values(array_filter($_FILES['file']['name'], function($value) { return $value !== ''; }));
                    $_FILES['file']['type'] = array_values(array_filter($_FILES['file']['type'], function($value) { return $value !== ''; }));
                    $_FILES['file']['tmp_name'] = array_values(array_filter($_FILES['file']['tmp_name'], function($value) { return $value !== ''; }));
                    $_FILES['file']['error'] = array_values(array_filter($_FILES['file']['error'], function($value) { return $value === 0; }));
                    $_FILES['file']['size'] = array_values(array_filter($_FILES['file']['size'], function($value) { return $value > 0; }));
                    $arr_nama = array();
                    for($i=0; $i<count($data['kode_dok']); $i++){
                        $_FILES['userfile']['name']     = $_FILES['file']['name'][$i];
                        $_FILES['userfile']['type']     = $_FILES['file']['type'][$i];
                        $_FILES['userfile']['tmp_name'] = $_FILES['file']['tmp_name'][$i];
                        $_FILES['userfile']['error']    = $_FILES['file']['error'][$i];
                        $_FILES['userfile']['size']     = $_FILES['file']['size'][$i];

                        if(empty($_FILES['file']['name'])){
                            
                            $status_upload=FALSE;

                        }else{
                            $status_upload = TRUE;
                            $path_img = $_SERVER['DOCUMENT_ROOT'];
                            $target_dir = $path_img."web/upload/";
                            $uploadOk = 1;
                            $message="";
                            $error_upload="";
                            $target_file = $target_dir . basename($_FILES["userfile"]["name"]);
                            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
                            // generate nama gambar baru
                            $namaFileBaru = uniqid();
                            $namaFileBaru .= '.';
                            $namaFileBaru .= $imageFileType;

                            $target_file = $target_dir . $namaFileBaru;
                            $arr_nama[$i] =  $namaFileBaru;
                            
                            // Check if file already exists
                            if (file_exists($target_file)) {
                                $error_upload .= "Sorry, file already exists.";
                                $uploadOk = 0;
                            }
                            // Check file size
                            if ($_FILES["userfile"]["size"] > 2000000) {
                                $error_upload .= "Sorry, your file is too large.";
                                $uploadOk = 0;
                            }
                            // // Allow certain file formats
                            // if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                            // && $imageFileType != "gif" ) {
                            //     $error_upload= "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                            //     $uploadOk = 0;
                            // }
                            // Check if $uploadOk is set to 0 by an error
                            if ($uploadOk == 0) {
                                $error_upload .= "Sorry, your file was not uploaded.";
                            // if everything is ok, try to upload file
                            } else {
                                if (move_uploaded_file($_FILES["userfile"]["tmp_name"], $target_file)) {
                                    $message = "The file $namaFileBaru has been uploaded.";
                                } else {
                                    $error_upload .= "Sorry, there was an error uploading your file.";
                                    if (is_dir($target_dir) && is_writable($target_dir)) {
                                        // do upload logic here
                                    } else if (!is_dir($target_dir)){
                                        $error_upload.= 'Upload directory does not exist.'.$target_dir;
                                    } else if (!is_writable($target_dir)){
                                        $error_upload.= 'Upload directory is not writable'.$target_dir;
                                    }

                                }
                            }
                        }
                    }
                }

                $status = $data["status"];
                $update1 = TRUE;
                $update2 = TRUE;
                $exs = TRUE;
                
                if($edit){
                    $del1 = "DELETE FROM sai_proses01 where no_bukti=$no_bukti and kode_lokasi=$kode_lokasi "; 
                    array_push($exec_array,$del1);
                }

                $master = "insert into sai_proses01 (no_bukti,kode_lokasi,status,tanggal,keterangan,progress,kode_cust,kode_produk,nik,nilai,flag_keep) values ($no_bukti,$kode_lokasi,'$status','".$data["tanggal"]."','".$data["keterangan"]."','SA01','".$data["kode_cust"]."','".$data["kode_produk"]."','".$data["nik"]."','".joinNum($data["nilai"])."','$flag_keep') ";
                array_push($exec_array,$master);
                    
                if(arrayKeyCheck(array('kode_dok'), $data) AND arrayKeyCheck(array('file'), $_FILES) AND !empty($_FILES['file']['tmp_name'])){
                    $del2 = "DELETE FROM sai_proses_dok where no_bukti=$no_bukti and kode_proses = 'SA01' and kode_lokasi=$kode_lokasi ";
                    array_push($exec_array,$del2);

                    for($i=0; $i<count($data['kode_dok']);$i++){
                        $insdet[$i] = "insert into sai_proses_dok (kode_lokasi,no_bukti,kode_proses,kode_dok,path_file) values ($kode_lokasi,$no_bukti,'SA01','".$data['kode_dok'][$i]."','".$arr_nama[$i]."')"; 
                        
                        array_push($exec_array,$insdet[$i]);
                    }
                    
                }
                
                
                $rs=executeArray($exec_array);
                if($rs) {
                    $response=array(
                        'status' => 1,
                        'message' =>'produk sukses',
                        'query' => $exec_array,
                        'error_upload' => $error_upload,
                        'message_upl' =>$message,
                        'file' =>$_FILES['file']
                    );
                }
                else {
                    $response=array(
                        'status' => 0,
                        'message' =>'produk gagal'.$rs,
                        'query' => $exec_array,
                        'error_upload' => $error_upload,
                        'message_upl' =>$message,
                        'file' =>$_FILES
                    );
                    
                }
            }else{
                $response['error'] = "Unauthorized Access 2";
            }
        }else{
            $response['error'] = "Unauthorized Access 1";
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    function getEditProduk(){
        getKoneksi();
        if(arrayKeyCheck(array('api_key', 'username', 'kode_lokasi','kode_produk'), $_GET)){
            if(authKey($_GET["api_key"], 'crm', $_GET['username'])){
                $sql="select kode_produk,nama
                from sai_produk
                where kode_lokasi= '".$_GET['kode_lokasi']."' and kode_produk='".$_GET['kode_produk']."' ";
                
                $rs=execute($sql);
                
                $response["daftar"] = array();
                while($row = $rs->FetchNextObject($toupper = false)){
                    $response["daftar"][] = (array)$row;
                }
                $response['status']=true;
            }else{
                $response['error'] = "Unauthorized Access 2";
            }
        }else{
            $response['error'] = "Unauthorized Access 1";
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    function updateProduk() {
        getKoneksi();
        parse_str(file_get_contents('php://input'), $_PUT);
        $data = $_PUT;
        if(arrayKeyCheck(array('api_key', 'username', 'kode_lokasi'), $data)){
            if(authKey($data["api_key"], 'crm', $data['username'])){

                $kode_produk=qstr($data["kode_produk"]);
                $kode_lokasi=qstr($data["kode_lokasi"]);
                $nama=qstr($data["nama"]);
                $query="UPDATE sai_produk SET nama = $nama
                        WHERE kode_produk = $kode_produk and kode_lokasi = $kode_lokasi ";
                $sql = array();
                array_push($sql,$query);

                $rs=executeArray($sql);
                if($rs) {
                    $response=array(
                    'status' => 1,
                    'message' =>'produk berhasil ',
                    'query'=>$query,
                    'request'=>$_PUT
                    );
                }
                else {
                    $response=array(
                    'status' => 0,
                    'message' =>'produk gagal '.$rs,
                    'query'=>$query,
                    'request'=>$_PUT
                    );
                }
            }else{
                $response['error'] = "Unauthorized Access 2";
            }
        }else{
            $response['error'] = "Unauthorized Access 1";
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    function deleteProduk() {
        getKoneksi();
        parse_str(file_get_contents('php://input'), $_DELETE);
        $data = $_DELETE;
        if(arrayKeyCheck(array('api_key', 'username', 'kode_lokasi'), $data)){
            if(authKey($data["api_key"], 'crm', $data['username'])){
                $id = qstr($_DELETE["kode_produk"]);
                $kd_lok = qstr($_DELETE["kode_lokasi"]);

                $query="DELETE FROM sai_produk where kode_produk=$id and kode_lokasi = $kd_lok" ;

                $sql = array();
                array_push($sql,$query);

                $rs=executeArray($sql);
                if($rs) {
                    $response=array(
                    'status' => 1,
                    'message' =>'produk berhasil',
                    'sql'=>$sql
                    );
                }
                else {
                    $response=array(
                    'status' => 0,
                    'message' =>'produk gagal'.$rs,
                    'sql'=>$sql
                    );
                }
                
            }else{
                $response['error'] = "Unauthorized Access 2";
            }
        }else{
            $response['error'] = "Unauthorized Access 1";
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }
?>