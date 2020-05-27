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
        include_once($root_lib."lib/koneksi.php");
        include_once($root_lib."lib/helpers.php");
    }

    // function getReg(){
        
    //     session_start();
    //     getKoneksi();
    //     if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
    //         $kode_lokasi = $_GET['kode_lokasi'];
            
    //         $sql="select distinct a.no_reg,a.no_peserta,b.nama as nama_peserta,a.tgl_input,a.no_paket,c.tgl_berangkat,a.flag_group,isnull(d.no_bukti,'-') as sts_dok 
    //         from dgw_reg a
    //         inner join dgw_peserta b on a.no_peserta=b.no_peserta and a.kode_lokasi=b.kode_lokasi 
    //         inner join dgw_jadwal c on a.no_paket=c.no_paket and a.no_jadwal=c.no_jadwal and a.kode_lokasi=c.kode_lokasi
    //         left join dgw_scan d on a.no_reg=d.no_bukti and a.kode_lokasi=d.kode_lokasi
    //         where a.kode_lokasi='".$kode_lokasi."'  ";
            
    //         $response["daftar"]=dbResultArray($sql);
    //         $response['status'] = TRUE;
    //     }else{
            
    //         $response["status"] = false;
    //         $response["message"] = "Unauthorized Access, Login Required";
    //     }
    //     // header('Content-Type: application/json');
    //     echo json_encode($response);
    // }
    function getReg(){
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $query = '';
            $output = array();
        
            $kode_lokasi = $_GET['kode_lokasi'];
            $query .= "select a.no_reg,a.no_peserta,b.nama,a.tgl_input,e.nama as nama_paket,c.tgl_berangkat,a.flag_group,
            isnull(d.jum_upload,0) as jum_upload,isnull(f.jum_dok,0) as jum_dok,
            case when d.jum_upload = f.jum_dok then 'selesai' else '-' end as sts_dok 
            from dgw_reg a
            inner join dgw_peserta b on a.no_peserta=b.no_peserta and a.kode_lokasi=b.kode_lokasi 
            inner join dgw_jadwal c on a.no_paket=c.no_paket and a.no_jadwal=c.no_jadwal and a.kode_lokasi=c.kode_lokasi
            left join ( select no_bukti,kode_lokasi,count(*) as jum_upload
                        from dgw_scan
                        group by no_bukti,kode_lokasi) d on a.no_reg=d.no_bukti and a.kode_lokasi=d.kode_lokasi
            left join ( select no_reg,kode_lokasi,count(*) as jum_dok
                        from dgw_reg_dok
                        group by no_reg,kode_lokasi) f on a.no_reg=f.no_reg and a.kode_lokasi=f.kode_lokasi
            inner join dgw_paket e on a.no_paket=e.no_paket and a.kode_lokasi=e.kode_lokasi
            where a.kode_lokasi='".$kode_lokasi."'  ";

            $column_array = array('a.no_reg','a.no_peserta','b.nama','a.tgl_input','e.nama','c.tgl_berangkat');
            $order_column = 'ORDER BY a.no_reg '.$_GET['order'][0]['dir'];
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
                $query .= ' ORDER BY a.tgl_input desc';
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
                $sub_array[] = $row->no_reg;
                $sub_array[] = $row->no_peserta;
                $sub_array[] = $row->nama;
                $sub_array[] = $row->tgl_input;
                $sub_array[] = $row->nama_paket; 
                $sub_array[] = $row->tgl_berangkat;
                $sub_array[] = $row->jum_upload." dari ".$row->jum_dok; 
                
                if($row->sts_dok == "-"){
                    $sub_array[] = "<a href='#' title='Upload Dokumen' class='badge badge-success' id='btn-upload'><i class='ti-upload'></i></a>";
                }else{
                    $sub_array[] = "<a href='#' title='Upload Dokumen' class='badge badge-success' id='btn-upload'><i class='ti-upload'></i></a>&nbsp;<a href='#' title='Sudah Upload' class='badge badge-success' ><i class='fas fa-check'></i></a>";
                }
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

    function getUpload(){
        
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $kode_lokasi = $_GET['kode_lokasi'];
            $no_reg = $_GET['no_reg'];

            $sql = "select a.no_reg,a.no_peserta,c.nama as nama_peserta,c.alamat,a.no_paket,b.nama as nama_paket,a.no_jadwal,d.tgl_berangkat
            from dgw_reg a
            inner join dgw_paket b on a.no_paket=b.no_paket and a.kode_lokasi=b.kode_lokasi
            inner join dgw_peserta c on a.no_peserta=c.no_peserta and a.kode_lokasi=c.kode_lokasi
            inner join dgw_jadwal d on a.no_paket=d.no_paket and a.no_jadwal=d.no_jadwal and a.kode_lokasi=d.kode_lokasi
            where a.kode_lokasi='$kode_lokasi' and a.no_reg='$no_reg'
            ";
            $response['daftar'] = dbResultArray($sql);

            $sql="select a.no_dokumen,a.deskripsi,a.jenis,isnull(convert(varchar,b.tgl_terima,111),'-') as tgl_terima,isnull(c.no_gambar,'-') as fileaddres,isnull(c.nik,'-') as nik 
            from dgw_dok a 
            left join dgw_reg_dok b on a.no_dokumen=b.no_dok and b.no_reg='$no_reg'
            left join dgw_scan c on a.no_dokumen=c.modul and c.no_bukti ='$no_reg' 
            where a.kode_lokasi='$kode_lokasi' order by a.no_dokumen";
            $response['daftar2'] = dbResultArray($sql);

            $response['status'] = TRUE;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }

    function simpanUpload(){
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            
            $data=$_POST;
            $kode_lokasi=$data['kode_lokasi'];
            $exec = array();

            $c = array();
            $type = array();
            $tmp_name = array();
            $error = array();
            $size = array();
            $dok_no = array();
            $user = array();
            for($i=0;$i<count($_FILES['file_dok']['name']);$i++){
                if($_FILES['file_dok']['name'][$i] != ""){

                    array_push($c,$_FILES['file_dok']['name'][$i]);
                    array_push($type,$_FILES['file_dok']['type'][$i]);
                    array_push($tmp_name,$_FILES['file_dok']['tmp_name'][$i]);
                    array_push($error,$_FILES['file_dok']['error'][$i]);
                    array_push($size,$_FILES['file_dok']['size'][$i]);
                    array_push($dok_no,$data['upload_no_dokumen'][$i]);
                    array_push($user,$data['upload_nik'][$i]);
                }
            }

            if(count($tmp_name)>0){
                $arr_nama = array();
                for($i=0; $i<count($tmp_name); $i++){
                    $_FILES['userfile']['name']     = $c[$i];
                    $_FILES['userfile']['type']     = $type[$i];
                    $_FILES['userfile']['tmp_name'] = $tmp_name[$i];
                    $_FILES['userfile']['error']    = $error[$i];
                    $_FILES['userfile']['size']     = $size[$i];
                    
                    if(!empty($_FILES['userfile']['name'])){
                        
                        $status_upload = TRUE;
                        $path_img = realpath($_SERVER['DOCUMENT_ROOT'])."/";
                        $target_dir = $path_img."upload/";
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
                        // if ($_FILES["userfile"]["size"] > 3000000) {
                        //     $error_upload .= "Sorry, your file is too large.";
                        //     $uploadOk = 0;
                        // }
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

                for($i=0; $i<count($arr_nama);$i++){

                    $del = "delete from dgw_scan where modul='".$dok_no[$i]."' and no_bukti = '".$data['upload_no_reg']."' and kode_lokasi='".$kode_lokasi."'";				
                    array_push($exec,$del);

                    $sql1 = "insert into dgw_scan(no_bukti,modul,no_gambar,kode_lokasi,nik) values ('".$data['upload_no_reg']."','".$dok_no[$i]."','".$arr_nama[$i]."','".$kode_lokasi."','".$user[$i]."')";	
                    array_push($exec,$sql1);

                    $upd = "update dgw_reg_dok set tgl_terima='".$data['upload_tgl_terima']."' where no_reg='".$data['upload_no_reg']."' and no_dok='".$dok_no[$i]."' "; 				
                    array_push($exec,$upd);
                    
                }
                
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
                $tmp="gagal. Dokumen required";
                $sts=true;
            }

            $response["message"] =$tmp.$error_upload;;
            $response["status"] = $sts;
            $response["exec"] = $exec;
            $response['jumlah_upload']= count($c);
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
        
    }

