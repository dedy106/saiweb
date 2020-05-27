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
		include_once($root_lib.'app/apv/setting.php');
    }

    
    function generateKode($tabel, $kolom_acuan, $prefix, $str_format){
        $query = execute("select right(max($kolom_acuan), ".strlen($str_format).")+1 as id from $tabel where $kolom_acuan like '$prefix%'");
        $kode = $query->fields[0];
        $id = $prefix.str_pad($kode, strlen($str_format), $str_format, STR_PAD_LEFT);
        return $id;
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

    function kirim_email($email,$user,$body_text){
        try{

            $root_vendor=realpath($_SERVER["DOCUMENT_ROOT"])."/vendor";
        
            require_once $root_vendor."/mail/swift_required.php";
            require_once $root_vendor."/mail/pop3.php";
            require_once $root_vendor."/mail/mime_parser.php";
            require_once $root_vendor."/mail/rfc822_addresses.php";
        
            $response = array();
            // Create the Transport
            $transport = Swift_SmtpTransport::newInstance('smtp.googlemail.com', 465, 'tls')
            ->setUsername('devptsai@gmail.com')
            ->setPassword('Saisai2019')
            ;
        
            // Create the Mailer using your created Transport
            $mailer = Swift_Mailer::newInstance($transport);
            // Create a message
            $message = Swift_Message::newInstance('Pengajuan Justifikasi Kebutuhan')
            ->setFrom(array('devptsai@gmail.com' => 'Pt. Samudera Aplikasi Indonesia'))
            ->setTo(array($email=> $user))
            ->setBody($body_text)
            ;
            // Send the message
            $result = $mailer->send($message);
            if($result){
                return true;
            }else{
                return false;
            }
        }catch (exception $e) { 
            error_log($e->getMessage());		
            $error ="error " .  $e->getMessage();
            return false;
        } 	
        
    }

    function sendNotif($title,$content,$token_player){
        try{

            $title = $title;
            $content      = array(
                "en" => $content
            );
            
            $fields = array(
                'app_id' => "17d5726f-3bc0-4e97-8567-8ad802ccb9ff", //appid saiweb
                
                // // per token id
                'include_player_ids' => $token_player,
                'data' => array(
                    "foo" => "bar"
                ),
                'contents' => $content,
                'headings' => array(
                    'en' => $title
                    )
            );
            
            $fields = json_encode($fields);
            $response['fields']=$fields;
            // print("\nJSON sent:\n");
            // print($fields);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json; charset=utf-8',
                'Authorization: Basic NDg5NDdkZTAtZjEyMi00NTFiLWEwMDktOWU4YTVjMTRmMjkw' //REST API KEY ONESIGNAL
            ));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            curl_setopt($ch, CURLOPT_POST, FALSE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            
            $response["notif"] = curl_exec($ch);
            curl_close($ch);
    
            if(!isset($response["notif"]["errors"])){
    
                return true;
            }else{
                return false;
            }
        } catch (exception $e) { 
            error_log($e->getMessage());		
            $error ="error " .  $e->getMessage();
            return false;
        } 	

    }

    function sendWA($no_telp,$pesan){
        try{

            $fields = array(
                'phone_no' => $no_telp,
                'message' => $pesan." [send from saiweb app]",
                // 'key'=> '15a5475dddf14b76254be32384b350ed2c416554ca8eb67c'
                'key'=>'cd89a8c837380f06ed5f8bbf3a3759b3becd577cc5979586'
            );
            
            $fields = json_encode($fields);
             
            // Prepare new cURL resource
            $ch2 = curl_init('http://send.woonotif.com/api/send_message');
            curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch2, CURLINFO_HEADER_OUT, true);
            curl_setopt($ch2, CURLOPT_POST, true);
            curl_setopt($ch2, CURLOPT_POSTFIELDS, $fields);
             
            // Set HTTP Header for POST request 
            curl_setopt($ch2, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($fields))
            );
             
            // Submit the POST request
            $result= curl_exec($ch2);
             
            // Close cURL session handle
            curl_close($ch2);
            return $result;
        }catch (exception $e) { 
            error_log($e->getMessage());		
            $error ="error " .  $e->getMessage();
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

    function getPP(){
        
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $kode_lokasi = $_GET['kode_lokasi'];
            
            $sql="select kode_pp, nama from apv_pp where kode_lokasi='$kode_lokasi' ";
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
    function getNIK(){
        
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $kode_lokasi = $_GET['kode_lokasi'];
            
            $sql="select nik, nama from apv_karyawan where kode_lokasi='$kode_lokasi' ";
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

    function getHistory(){
        
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $kode_lokasi = $_GET['kode_lokasi'];
            $no_bukti = $_GET['no_bukti'];

            $sql="select a.no_bukti,b.keterangan,b.tanggal,c.nama
            from apv_flow a
            inner join apv_pesan b on a.no_bukti=b.no_bukti and a.kode_lokasi=b.kode_lokasi and a.no_urut=b.no_urut
            left join apv_jab c on a.kode_jab=c.kode_jab and a.kode_lokasi=c.kode_lokasi
            where a.kode_lokasi='$kode_lokasi' and a.no_bukti='$no_bukti' ";
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

    function getPrint(){
        
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $kode_lokasi = $_GET['kode_lokasi'];
            $no_bukti = $_GET['no_bukti'];

            $sql="select a.no_bukti,a.no_dokumen, convert(varchar(10),a.tanggal,121) as tanggal,a.kegiatan,a.waktu,a.dasar,a.nilai,a.kode_pp,b.nama as nama_pp 
            from apv_juskeb_m a
            left join apv_pp b on a.kode_pp=b.kode_pp and a.kode_lokasi=b.kode_lokasi
            where a.kode_lokasi='$kode_lokasi' and a.no_bukti='$no_bukti' ";
            $rs=execute($sql);
            $response["daftar"]=array();
            while ($row = $rs->FetchNextObject(false)){
                $response['daftar'][] = (array)$row;
            }

            $sql="select a.no_bukti,a.no_urut,a.barang,a.jumlah,a.harga,a.nilai 
            from apv_juskeb_d a            
            where a.kode_lokasi='$kode_lokasi' and a.no_bukti='$no_bukti' ";
            $rs=execute($sql);
            $response["daftar2"]=array();
            while ($row = $rs->FetchNextObject(false)){
                $response['daftar2'][] = (array)$row;
            }

            $sql="select a.kode_role,a.kode_jab,a.no_urut,b.nama as nama_jab,c.nik,c.nama as nama_kar,isnull(convert(varchar,a.tgl_app,103),'-') as tanggal
            from apv_flow a
            inner join apv_jab b on a.kode_jab=b.kode_jab and a.kode_lokasi=b.kode_lokasi
            inner join apv_karyawan c on a.kode_jab=c.kode_jab and a.kode_lokasi=c.kode_lokasi
            where a.kode_lokasi='$kode_lokasi' and a.no_bukti='$no_bukti' ";
            $rs=execute($sql);
            $response["daftar3"]=array();
            while ($row = $rs->FetchNextObject(false)){
                $response['daftar3'][] = (array)$row;
            }
            $response['status'] = TRUE;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }
   
    function getPengajuan(){
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $query = '';
            $output = array();
        
            $kode_lokasi = $_GET['kode_lokasi'];
            $query .= "
            select a.no_bukti,a.no_dokumen,a.kode_pp,convert(varchar,a.waktu,103) as waktu,a.kegiatan,case a.progress when 'S' then 'FINISH' when 'F' then 'Return Verifikasi' when 'R' then 'Return Approval' else isnull(b.nama_jab,'-') end as posisi,a.nilai,a.progress
            from apv_juskeb_m a
            left join (select a.no_bukti,b.nama as nama_jab
                    from apv_flow a
                    inner join apv_jab b on a.kode_jab=b.kode_jab and a.kode_lokasi=b.kode_lokasi
                    where a.kode_lokasi='$kode_lokasi' and a.status='1'
                    )b on a.no_bukti=b.no_bukti
            where a.kode_lokasi='".$kode_lokasi."'  and a.nik_buat='".$_SESSION['userLog']."' ";

            $column_array = array('a.no_bukti','a.no_juskeb','a.no_dokumen','a.kode_pp','a.waktu','b.nama_jab','a.nilai');
            $order_column = 'ORDER BY a.no_bukti '.$_GET['order'][0]['dir'];
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
                $query .= ' ORDER BY  a.no_bukti ';
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
                $sub_array[] = $row->no_bukti;
                $sub_array[] = $row->no_dokumen;
                $sub_array[] = $row->kode_pp;
                $sub_array[] = $row->waktu;                
                $sub_array[] = $row->kegiatan;             
                $sub_array[] = $row->posisi;             
                $sub_array[] = $row->nilai;
                if($row->progress == "A" || $row->progress == "3" || $row->progress == "F"){
                    $sub_array[] = "<a href='#' title='Edit' class='badge badge-warning' id='btn-edit'><i class='fas fa-pencil-alt'></i></a> &nbsp; <a href='#' title='Hapus' class='badge badge-danger' id='btn-delete'><i class='fa fa-trash'></i></a>&nbsp; <a href='#' title='History' class='badge badge-success' id='btn-history'><i class='fas fa-history'></i></a>&nbsp; <a href='#' title='Preview' class='badge badge-info' id='btn-print'><i class='fas fa-print'></i></a>";
                }else{
                    $sub_array[] = "<a href='#' title='History' class='badge badge-success' id='btn-history'><i class='fas fa-history'></i></a>&nbsp; <a href='#' title='Preview' class='badge badge-info' id='btn-print'><i class='fas fa-print'></i></a>";
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
    

    function getEdit(){
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $id=$_GET['no_bukti'];    
        
            $response = array("message" => "", "rows" => 0, "status" => "" );
        
            $sql="select no_bukti,no_dokumen,kode_pp,waktu,kegiatan,dasar,nilai,convert(varchar(10),tanggal,121) as tanggal from apv_juskeb_m where kode_lokasi='".$_GET['kode_lokasi']."' and no_bukti='$id' ";
            $response['daftar'] = dbResultArray($sql);

            $sql="select no_bukti,barang,harga,jumlah,nilai from apv_juskeb_d where kode_lokasi='".$_GET['kode_lokasi']."' and no_bukti='$id'  order by no_urut";					
            $response['daftar2'] = dbResultArray($sql);

            $sql="select no_bukti,nama,file_dok from apv_juskeb_dok where kode_lokasi='".$_GET['kode_lokasi']."' and no_bukti='$id'  order by no_urut";
            $response['daftar3'] = dbResultArray($sql);

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
            $nik=$data['nik_user'];
            $exec = array();
            if($data["no_bukti"] == ""){
                $no_bukti = generateKode("apv_juskeb_m", "no_bukti", "APV-", "0001");
                $edit = false;
            }else{
                $no_bukti = $data["no_bukti"];
                $edit = true;
            }
            
            if(arrayKeyCheck(array('nama_dok'), $data) AND arrayKeyCheck(array('file_dok'), $_FILES) AND !empty($_FILES['file_dok']['tmp_name'])){
                $data['nama_dok'] = array_values(array_filter($data['nama_dok'], function($value) { return $value !== ''; }));
                $_FILES['file_dok']['name'] = array_values(array_filter($_FILES['file_dok']['name'], function($value) { return $value !== ''; }));
                $_FILES['file_dok']['type'] = array_values(array_filter($_FILES['file_dok']['type'], function($value) { return $value !== ''; }));
                $_FILES['file_dok']['tmp_name'] = array_values(array_filter($_FILES['file_dok']['tmp_name'], function($value) { return $value !== ''; }));
                $_FILES['file_dok']['error'] = array_values(array_filter($_FILES['file_dok']['error'], function($value) { return $value === 0; }));
                $_FILES['file_dok']['size'] = array_values(array_filter($_FILES['file_dok']['size'], function($value) { return $value > 0; }));
                $arr_nama = array();
                $error_upload="";
                for($i=0; $i<count($data['nama_dok']); $i++){
                    $_FILES['userfile']['name']     = $_FILES['file_dok']['name'][$i];
                    $_FILES['userfile']['type']     = $_FILES['file_dok']['type'][$i];
                    $_FILES['userfile']['tmp_name'] = $_FILES['file_dok']['tmp_name'][$i];
                    $_FILES['userfile']['error']    = $_FILES['file_dok']['error'][$i];
                    $_FILES['userfile']['size']     = $_FILES['file_dok']['size'][$i];
                    
                    if(empty($_FILES['userfile']['name'])){
                        
                        $status_upload=FALSE;
                        
                    }else{
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
                        if ($_FILES["userfile"]["size"] > 3000000) {
                            $error_upload .= "Sorry, your file is too large.";
                            $uploadOk = 0;
                        }
                        if ($uploadOk == 0) {
                            $error_upload .= "Sorry, your file was not uploaded.";
                            // if everything is ok, try to upload file
                        } else {
                            if (move_uploaded_file($_FILES["userfile"]["tmp_name"], $target_file)) {
                                $message = "The file $namaFileBaru has been uploaded.";
                            } else {
                                $error_upload .= "Sorry, there was an error uploading your file.".$_FILES['userfile']['tmp_name'];
                                if (is_dir($target_dir) && is_writable($target_dir)) {
                                    // do upload logic here
                                    $error_upload.=$target_dir;
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
            
            
            if($edit){
                $del1 = "delete from apv_juskeb_m where no_bukti ='$no_bukti' and kode_lokasi='$kode_lokasi' ";
                array_push($exec,$del1);
                $del2 = "delete from apv_juskeb_d where no_bukti ='$no_bukti' and kode_lokasi='$kode_lokasi' ";
                array_push($exec,$del2);
                $del3 = "delete from apv_flow where no_bukti ='$no_bukti' and kode_lokasi='$kode_lokasi' ";
                array_push($exec,$del3);
                $del4 = "delete from apv_juskeb_dok where no_bukti ='$no_bukti' and kode_lokasi='$kode_lokasi' ";
                array_push($exec,$del4);

                if(arrayKeyCheck(array('nama_dok'), $data)){
                    $del4 = "DELETE FROM apv_juskeb_dok where no_bukti='$no_bukti' and kode_lokasi='$kode_lokasi' ";
                    array_push($exec,$del4);
                    
                    $x=0;
                    for($i=0; $i<count($data['nama_dok']);$i++){
                        if($data['nama_file'][$i] == "-"){
                            $insdet2 = "insert into apv_juskeb_dok (kode_lokasi,no_bukti,nama,no_urut,file_dok) values ('$kode_lokasi','$no_bukti','".$data['nama_dok'][$i]."',$i,'".$arr_nama[$x]."')"; 
                            $x++;
                        }else{
                            $insdet2 = "insert into apv_juskeb_dok (kode_lokasi,no_bukti,nama,no_urut,file_dok) values ('$kode_lokasi','$no_bukti','".$data['nama_dok'][$i]."',$i,'".$data['nama_file'][$i]."')"; 
                        }
                        array_push($exec,$insdet2);
                    }
                    $response['nama_file']= $data['nama_file']; 
                    
                }
            }else{
                if(arrayKeyCheck(array('nama_dok'), $data) AND arrayKeyCheck(array('file_dok'), $_FILES) AND !empty($_FILES['file_dok']['tmp_name'])){
                    $del4 = "DELETE FROM apv_juskeb_dok where no_bukti='$no_bukti' and kode_lokasi='$kode_lokasi' ";
                    array_push($exec,$del4);
                    
                    for($i=0; $i<count($data['nama_dok']);$i++){
                        $insdet2 = "insert into apv_juskeb_dok (kode_lokasi,no_bukti,nama,no_urut,file_dok) values ('$kode_lokasi','$no_bukti','".$data['nama_dok'][$i]."',$i,'".$arr_nama[$i]."')"; 
                        
                        array_push($exec,$insdet2);
                    }
                    
                }
            }
            
            $sql1= "insert into apv_juskeb_m (no_bukti,no_dokumen,kode_pp,waktu,kegiatan,dasar,nik_buat,kode_lokasi,nilai,tanggal,progress) values ('".$no_bukti."','".$data['no_dokumen']."','".$data['kode_pp']."','".$data['waktu']."','".$data['kegiatan']."','".$data['dasar']."','$nik','".$kode_lokasi."',".joinNum($data['total']).",'".$data['tanggal']."','A') ";
            
            array_push($exec,$sql1);
            
            if(count($data['barang']) > 0){
                for($i=0; $i<count($data['barang']);$i++){
                    $sub = joinNum($data['harga'][$i])*joinNum($data['qty'][$i]);
                    $insdet = "insert into apv_juskeb_d (kode_lokasi,no_bukti,barang,harga,jumlah,no_urut,nilai) values ('$kode_lokasi','$no_bukti','".$data['barang'][$i]."',".joinNum($data['harga'][$i]).",".joinNum($data['qty'][$i]).",$i,".$sub.")"; 
                    
                    array_push($exec,$insdet);
                }
            }
            
          

            $sql = "select a.kode_role,b.kode_jab,b.no_urut,c.nik,c.no_telp
            from apv_role a
            inner join apv_role_jab b on a.kode_role=b.kode_role and a.kode_lokasi=b.kode_lokasi
            inner join apv_karyawan c on b.kode_jab=c.kode_jab and b.kode_lokasi=c.kode_lokasi
            where a.kode_lokasi='$kode_lokasi' and ".joinNum($data['total'])." between a.bawah and a.atas and a.modul='JK'
            order by b.no_urut";

            $role = dbResultArray($sql);
            $token_player = array();
            
            for($i=0;$i<count($role);$i++){
                
                if($i == 0){
                    $prog = 1;
                    $rst = dbResultArray("select token from api_token_auth where nik='".$role[$i]["nik"]."' ");
                    for($t=0;$t<count($rst);$t++){
                        array_push($token_player,$rst[$t]["token"]);
                    }
                    $no_telp = $role[$i]["no_telp"];
                    $app_nik=$role[$i]["nik"];
                }else{
                    $prog = 0;
                }
                $ins = "insert into apv_flow (no_bukti,kode_lokasi,kode_role,kode_jab,no_urut,status,sts_ver) values ('$no_bukti','$kode_lokasi','".$role[$i]['kode_role']."','".$role[$i]['kode_jab']."',$i,'$prog',0) ";

                array_push($exec,$ins);
            }
            
            $rs=executeArray($exec,$err);  
            // $rs=true;    
            
            if ($err == null)
            {	
                $tmp="sukses";
                $sts=true;
                //KIRIM NOTIF & EMAIL
                $email = $_SESSION['email'];
                $user = $_SESSION['namaUser'];
                $body_text = "Pengajuan Justifikasi kebutuhan $no_bukti berhasil dikirim, menunggu verifikasi ";
                $rs2 = kirim_email($email,$user,$body_text);
                if($rs2){
                    $tmp.=". Email terkirim";
                }else{
                    $tmp.=". Email gagal terkirim";
                }

                // $title = "Pengajuan Justifikasi";
                // $content = "Pengajuan Justifikasi kebutuhan $no_bukti dari $nik, menunggu approval anda ";
                // $rs3 = sendNotif($title,$content,$token_player);
                // if($rs3){
                //     $tmp.=". Notif terkirim";
                //     $exec_notif = array();
                //     for($t=0;$t<count($token_player);$t++){

                //         $insert = " insert into apv_notif_m (kode_lokasi,nik,token,title,isi,tgl_input,kode_pp) values ('$kode_lokasi','$app_nik','".$token_player[$t]."','$title','$content',getdate(),'-') ";
                //         array_push($exec_notif,$insert);

                //     }
                //     $resno = executeArray($exec_notif,$err);
                //     $tmp.=".".$err;
                // }else{
                //     $tmp.=". Notif gagal terkirim";
                // }
                // //SEND WA
                // $rs4 = sendWA($no_telp,$content);
                // if($rs4 == "Success"){
                //     $tmp.=". Pesan whatsapp terkirim ke ".$no_telp;
                // }else{
                //     $tmp.=". Pesan whatsapp gagal terkirim";
                // }
                
            }else{
                $tmp=$err;
                $sts=false;
            }	
            $response["message"] =$tmp." ".$error_upload;
            $response["status"] = $sts;
            $response["error"] = $error_upload;
            $response["no_aju"] = $no_bukti;
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
            $del = "delete from apv_juskeb_m where no_bukti='".$data['no_bukti']."' and kode_lokasi='".$data['kode_lokasi']."' ";
            array_push($exec,$del);

            $del2 = "delete from apv_juskeb_d where no_bukti='".$data['no_bukti']."' and kode_lokasi='".$data['kode_lokasi']."' ";
            array_push($exec,$del2);

            $del3 = "delete from apv_juskeb_dok where no_bukti='".$data['no_bukti']."' and kode_lokasi='".$data['kode_lokasi']."' ";
            array_push($exec,$del3);

            $del4 = "delete from apv_flow where no_bukti='".$data['no_bukti']."' and kode_lokasi='".$data['kode_lokasi']."' ";
            array_push($exec,$del4);
            
            $rs=executeArray($exec,$err);
            $tmp=array();
            $kode = array();
            if ($err==null)
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