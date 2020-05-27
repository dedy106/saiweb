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
            hapusPendidikan();
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

function getPendidikan(){
    session_start();
    getKoneksi();
    if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
        $nik = $_GET['nik_user'];
        $query = '';
        $response = array();
        
        $kode_lokasi = $_REQUEST['kode_lokasi'];
        $query .= "select a.nama, a.tahun, a.kode_jurusan,a.kode_strata, b.nama as nama_jur,c.nama as nama_str, isnull(a.setifikat,'-') as serti 
        from hr_pendidikan a 
        inner join hr_jur b on a.kode_jurusan =b.kode_jur and a.kode_lokasi=b.kode_lokasi 
        inner join hr_strata c on a.kode_strata =c.kode_strata and a.kode_lokasi=c.kode_lokasi 
        where a.kode_lokasi='$kode_lokasi' and a.nik='$nik' ";
        
        $column_array = array('a.nama','a.tahun','a.kode_jurusan','a.kode_strata','b.nama','c.nama','a.sertifikat');
        $order_column = 'ORDER BY a.nama '.$_GET['order'][0]['dir'];
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
            $query .= ' ORDER BY a.nama ';
        }
        if($_GET["length"] != -1)
        {
            $query .= ' OFFSET ' . $_GET['start'] . ' ROWS FETCH NEXT ' . $_GET['length'] . ' ROWS ONLY ';
        }
        $statement = execute($query);
        $data = array();
        $no=1;
        $filtered_rows = $statement->RecordCount();
        while($row = $statement->FetchNextObject($toupper=false))
        {
            $sub_array = array();
            
            $sub_array[] = $no;
            $sub_array[] = $row->nama;
            $sub_array[] = $row->tahun;
            $sub_array[] = $row->nama_jur;
            $sub_array[] = $row->nama_str;
            $sub_array[] = $row->serti;
            $data[] = $sub_array;
            $no++;
        }
        $response = array(
            "draw"				=>	intval($_GET["draw"]),
            "recordsTotal"		=> 	$filtered_rows,
            "recordsFiltered"	=>	$jml_baris,
            "data"				=>	$data,
        );
    }else{
        $response["status"] = false;
        $response["message"] = "Unauthorized Access, Login Required";
    }
    echo json_encode($response);
       

}

function simpanPendidikan(){
    session_start();
    getKoneksi();
    if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){

        $nik=$_POST['nik'];
        $kode_lokasi=$_POST['kode_lokasi'];    

        $response = array("message" => "", "rows" => 0, "status" => "" );

        if($_POST['id_edit'] == "1"){
    
            $sqldel1="delete from hr_pendidikan where nama='".$_POST['nama']."' and nik='".$nik."' and kode_lokasi='".$kode_lokasi."' ";

        }

        if(ISSET($_FILES["file_gambar"]["name"]) AND !empty($_FILES["file_gambar"]["name"])){
            $path_s = $_SERVER['DOCUMENT_ROOT'];
            $target_dir = $path_s."server/media/";
            $target_file = $target_dir . basename($_FILES["file_gambar"]["name"]);
            $uploadOk = 1;
            $message="";
            $error_upload="";
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        
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
            && $imageFileType != "pdf" && $imageFileType != "doc" && $imageFileType != "docx"  ) {
                $error_upload= "Sorry, only JPG, JPEG, PNG, PDF, DOC, DOCX files are allowed.";
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

        }else{
            $filepath ="-";
            $filetype ="";
            
        }

        $sqlnu= "select max(nu) as nu from hr_pendidikan where nik='$nik' and kode_lokasi='$kode_lokasi'  ";
        $rsnu=execute($sqlnu);

        if($rsnu->RecordCount() > 0){
            $nu = $rsnu->fields[0] + 1;
        }else{
            $nu = 0;
        }

        $sql1 = "insert into hr_pendidikan(nik,kode_lokasi,nu,nama,tahun,setifikat,kode_jurusan,kode_strata) values ('".$nik."','".$kode_lokasi."',".$nu.",'".$_POST['nama']."','".$_POST['tahun']."','".$filepath."','".$_POST['kode_jur']."','".$_POST['kode_strata']."')";

        if($_POST['id_edit'] == "1"){
            $sql=[$sqldel1,$sql1];
        }else{
            $sql=[$sql1];
        }

        $rs=executeArray($sql);

        $tmp=array();
        $kode = array();

        if ($rs)
        {	
            $tmp="Sukses disimpan";
            $sts=true;
        }else{

            $tmp="Gagal disimpan";
            $sts=false;
        }		
        $response["message"] =$tmp;
        $response["status"] = $sts;
        $response["sql"]=$sql;
        $response["error"]=$error_upload;

    }else{
        $response["status"] = false;
        $response["message"] = "Unauthorized Access, Login Required";
    }
    echo json_encode($response);

}

function getEditPendidikan(){
    session_start();
    getKoneksi();
    if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
        
        $id=$_POST['kode'];
        $kode_lokasi=$_POST['kode_lokasi'];    
        $nik=$_POST['nik'];
        
        $response = array("message" => "", "rows" => 0, "status" => "" );
        
        $sql="select a.*,b.nama as nama_jur,c.nama as nama_str 
        from hr_pendidikan a 
        inner join hr_jur b on a.kode_jurusan =b.kode_jur and a.kode_lokasi=b.kode_lokasi 
        inner join hr_strata c on a.kode_strata =c.kode_strata and a.kode_lokasi=c.kode_lokasi 
        where a.kode_lokasi='$kode_lokasi' and a.nik='$nik' and a.nama = '$id' ";
        
        $rs = execute($sql);
        
        $response['daftar'] = array();
        
        while ($row = $rs->FetchNextObject(false)){
            $response['daftar'][] = (array)$row;
        }
        
        $response['status'] = TRUE;
        $response['sql'] = $sql;
    }else{
        $response["status"] = false;
        $response["message"] = "Unauthorized Access, Login Required";
    }
    echo json_encode($response);
    
}

function hapusPendidikan(){
    session_start();
    getKoneksi();
    if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            
        $sql1="delete from hr_pendidikan where nama='".$_POST['id']."' and kode_lokasi='".$_POST['kode_lokasi']."' and nik='".$_POST['nik']."' ";

        $sql=[$sql1];

        $rs=executeArray($sql);

        $tmp=array();
        $kode = array();
        if ($rs)
        {	
            $tmp="sukses";
            $sts=true;
        }else{
            $tmp="gagal";
            $sts=false;
        }		
        $response["message"] =$tmp;
        $response["status"] = $sts;
        $response["sql"] = $sql;

    }else{
        $response["status"] = false;
        $response["message"] = "Unauthorized Access, Login Required";
    }
    echo json_encode($response);
}


function getStrata(){
    session_start();
    getKoneksi();
    if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){   
    $kode_lokasi=$_POST['kode_lokasi'];
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
    echo json_encode($response);

}

function getJur(){
    session_start();
    getKoneksi();
    if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){   
        $kode_lokasi=$_POST['kode_lokasi'];
        $response = array("message" => "", "rows" => 0, "status" => "" );

        $sql1 = "select kode_jur,nama from hr_jur where kode_lokasi = '".$kode_lokasi."' ";
        
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
    echo json_encode($response);

}


?>
