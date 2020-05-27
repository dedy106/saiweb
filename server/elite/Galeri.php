<?php

    // class CrudNw{
    if(function_exists($_GET['fx'])) {
        $_GET['fx']();
    }

    function getKoneksi(){
        $root_lib=$_SERVER["DOCUMENT_ROOT"];
        include_once($root_lib."lib/koneksi.php");
        include_once($root_lib."lib/helpers.php");
    }

    function reverseDate2($ymd_or_dmy_date, $org_sep='-', $new_sep='-'){
        $arr = explode($org_sep, $ymd_or_dmy_date);
        return $arr[2].$new_sep.$arr[1].$new_sep.$arr[0];
    }

    //FORM GALERI

    function getGaleri(){
        session_start();
        getKoneksi();

        $query = '';
        $output = array();
    
        $kode_lokasi = $_REQUEST['kode_lokasi'];
        $query .= "select id, nama, file_gambar from sai_konten_galeri where kode_lokasi= '".$kode_lokasi."'  ";

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

            // $path = $_SERVER["SCRIPT_NAME"];				
            // $path = substr($path,0,strpos($path,"server/serverApp.php"));
            $sub_array[1] = "<img id='gambarnya' src='http://".$_SERVER["SERVER_NAME"].$row->file_gambar."' width='50px'>";
            // http://".$_SERVER["SERVER_NAME"].

            $sub_array[2] = $row->nama;
            // $no++;
            // $sub_array[] = '<button type="button" name="update" id="'.$row["id"].'" class="btn btn-warning btn-xs update">Update</button>';
            // $sub_array[] = '<button type="button" name="delete" id="'.$row["id"].'" class="btn btn-danger btn-xs delete">Delete</button>';
            $data[] = $sub_array;
        }
        $output = array(
            "draw"				=>	intval($_POST["draw"]),
            "recordsTotal"		=> 	$filtered_rows,
            "recordsFiltered"	=>	$jml_baris,
            "data"				=>	$data,
        );
        echo json_encode($output);
    }

    function getKtg(){
        session_start();
        getKoneksi();
   
        $kode_lokasi=$_POST['kode_lokasi'];
        $result = array("message" => "", "rows" => 0, "status" => "" );
    
        $sql1 = "select kode_ktg, nama from sai_konten_ktg where kode_lokasi='".$kode_lokasi."' ";
        $rs = execute($sql1);					
        
        $result['daftar'] = array();
        while ($row = $rs->FetchNextObject(false)){
            $result['daftar'][] = (array)$row;
        }
        $result['status']=TRUE;
        $result['sql']=$sql1;
        echo json_encode($result);
    
    }

    function getEditGaleri(){
        session_start();
        getKoneksi();

        $id=$_POST['kode'];
        $kode_lokasi=$_POST['lokasi'];    
    
        $result = array("message" => "", "rows" => 0, "status" => "" );
    
        $sql="select id, nama, keterangan as isi,kode_ktg,file_gambar,file_type,jenis from sai_konten_galeri where kode_lokasi='$kode_lokasi' and id='$id' ";
        
        $rs = execute($sql);					
        
        while ($row = $rs->FetchNextObject(false)){
            $result['daftar'][] = (array)$row;
        }
        $result['status'] = TRUE;
        $result['sql'] = $sql;
        echo json_encode($result);
    
    }

    function simpanGaleri(){

        session_start();
        getKoneksi();

        $data=$_POST;

        $sql="select isnull(max(id),0)+1 as id from sai_konten_galeri where kode_lokasi='".$data['kode_lokasi']."' ";
        $rs1=execute($sql);

        $id=$rs1->fields[0];
        $kode_lokasi=$data['kode_lokasi'];
        $nik=$data['nik_user'];

        // if(ISSET($_FILES["filename"]["name"])){

            $path_s = $_SERVER['DOCUMENT_ROOT'];
            $target_dir = $path_s."upload/";
            $target_file = $target_dir . basename($_FILES["filename"]["name"]);
            $uploadOk = 1;
            $message="";
            $error_upload="";
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            // Check if image file is a actual image or fake image
            if(isset($_POST["submit"])) {
                $check = getimagesize($_FILES["filename"]["tmp_name"]);
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
            if ($_FILES["filename"]["size"] > 2000000) {
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
                if (move_uploaded_file($_FILES["filename"]["tmp_name"], $target_file)) {
                    $message = "The file ". basename( $_FILES["filename"]["name"]). " has been uploaded.";
                } else {
                    $error_upload= "Sorry, there was an error uploading your file.";
                    // echo $target_file;
                    // echo $_FILES["filename"]["error"];
                    if (is_dir($target_dir) && is_writable($target_dir)) {
                        // do upload logic here
                    } else if (!is_dir($target_dir)){
                        $error_upload.= 'Upload directory does not exist.'.$target_dir;
                    } else if (!is_writable($target_dir)){
                        $error_upload.= 'Upload directory is not writable'.$target_dir;
                    }

                }
            }

            $filepath="/upload/".basename($_FILES["filename"]["name"]);
            $filetype=$imageFileType;
        // }else{
        //     $filepath="-";
        //     $filetype="-";
        // }

        $sql1= "insert into sai_konten_galeri 
        (id,kode_lokasi,nik_user,tgl_input,flag_aktif,nama,jenis,file_gambar,file_type,keterangan,kode_ktg) values 
        ('$id','$kode_lokasi','$nik',getdate(),'1','".$data['nama']."','".$data['jenis']."','".$filepath."','".$filetype."','".$data['isi']."','".$data['kategori']."') ";
        
        $sql=[$sql1];
        $rs = executeArray($sql);

        $tmp=array();
        $kode = array();
        $sts=false;
        if ($rs)
        {	
            $tmp="sukses";
            $sts=true;
        }else{
            $tmp="gagal";
            unlink($target_file);
            $sts=false;
        }	

        $result["message"] =$tmp."-".$message;
        $result["error"] =$error_upload;
        $result["status"] = $sts;
        $result["sql"] = $sql;
        $result["id"] = $id;
        echo json_encode($result);
    }
    

    function ubahGaleri(){
        session_start();
        getKoneksi();
        if(ISSET($_FILES["filename"]["name"]) AND !empty($_FILES["filename"]["name"])){
            $path_s = $_SERVER['DOCUMENT_ROOT'];
            $target_dir = $path_s."upload/";
            $target_file = $target_dir . basename($_FILES["filename"]["name"]);
            $uploadOk = 1;
            $message="";
            $error_upload="";
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            // Check if image file is a actual image or fake image
            if(isset($_POST["submit"])) {
                $check = getimagesize($_FILES["filename"]["tmp_name"]);
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
            if ($_FILES["filename"]["size"] > 2000000) {
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
                if (move_uploaded_file($_FILES["filename"]["tmp_name"], $target_file)) {
                    $message = "The file ". basename( $_FILES["filename"]["name"]). " has been uploaded.";
                } else {
                    $error_upload= "Sorry, there was an error uploading your file.";
                    // echo $target_file;
                    // echo $_FILES["filename"]["error"];
                    if (is_dir($target_dir) && is_writable($target_dir)) {
                        // do upload logic here
                    } else if (!is_dir($target_dir)){
                        $error_upload.= 'Upload directory does not exist.'.$target_dir;
                    } else if (!is_writable($target_dir)){
                        $error_upload.= 'Upload directory is not writable'.$target_dir;
                    }

                }
            }

            $filepath="/upload/".basename($_FILES["filename"]["name"]);
            // $filepath="/upload/".basename($_FILES["filename"]["name"]);
            $filetype=$imageFileType;

            $sql1="update sai_konten_galeri set nama='".$_POST['nama']."',jenis ='".$_POST['jenis']."',file_gambar ='".$filepath."',file_type='".$filetype."',keterangan ='".$_POST['keterangan']."',kode_ktg='".$_POST['kategori']."' where id = '".$_POST['id']."' and kode_lokasi='".$_POST['kode_lokasi']."' ";

        }else{
    
            $sql1="update sai_konten_galeri set nama='".$_POST['nama']."',jenis ='".$_POST['jenis']."',keterangan ='".$_POST['isi']."',kode_ktg='".$_POST['kategori']."' where id = '".$_POST['id']."' and kode_lokasi='".$_POST['kode_lokasi']."' ";
        }
        
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
        $result["message"] =$tmp;
        $result["status"] = $sts;
        echo json_encode($result);
    }
    

    function hapusGaleri(){
        session_start();
        getKoneksi();

        $pathini = $_SERVER['DOCUMENT_ROOT'];
        $sql2="select file_gambar from sai_konten_galeri where id='".$_POST['id']."' and kode_lokasi='".$_POST['kode_lokasi']."' ";
        $rs2=execute($sql2);

        $tmp=explode("/",$rs2->fields[0]);
        $fullpath=$pathini.$tmp[0].$tmp[1]."/".$tmp[2]."/".$tmp[3];

        $sql1="delete from sai_konten_galeri where id='".$_POST['id']."' and kode_lokasi='".$_POST['kode_lokasi']."'";

        $sql=[$sql1];
        $rs=executeArray($sql);

        $tmp=array();
        $kode = array();
        if ($rs)
        {	
            $tmp="sukses";
            unlink($fullpath);
            if (!unlink($fullpath))
            {
                $error_del= "Error deleting $fullpath";
            }
            else
            {
                $error_del= "Deleting $fullpath";
            }
            $sts=true;
        }else{
            $tmp="gagal";
            $sts=false;
        }		
        $result["message"] =$tmp;
        $result["status"] = $sts;
        $result["sql"] = $sql2;
        $result["path"] = $fullpath;
        $result["error_del"] = $error_del;
        echo json_encode($result);
    }

    
?>
