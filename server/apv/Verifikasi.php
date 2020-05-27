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
                'key'=> 'cd89a8c837380f06ed5f8bbf3a3759b3becd577cc5979586'
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
    function getStatus(){
        
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $kode_lokasi = $_GET['kode_lokasi'];
            
            $sql="select status, nama from apv_status where kode_lokasi='$kode_lokasi' and status in ('V','F') ";
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
   
    function getPengajuan(){
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $query = '';
            $output = array();
        
            $kode_lokasi = $_GET['kode_lokasi'];
            $query .= "select b.no_bukti,b.no_dokumen,b.kode_pp,b.waktu,b.kegiatan,b.dasar,b.nilai
            from apv_juskeb_m b 
            where b.kode_lokasi='$kode_lokasi' and b.progress in ('A','R') --and a.kode_jab='".$_SESSION['kode_jab']."' ";

            $column_array = array('b.no_bukti','b.no_dokumen','b.kode_pp','b.waktu','b.kegiatan','b.dasar','b.nilai');
            $order_column = 'ORDER BY b.no_bukti '.$_GET['order'][0]['dir'];
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
                $query .= ' ORDER BY  b.no_bukti ';
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
                $sub_array[] = $row->dasar;             
                $sub_array[] = $row->nilai;
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

    function getData(){
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $id=$_GET['no_aju'];
            $kode_lokasi=$_GET['kode_lokasi'];    
        
            $response = array("message" => "", "rows" => 0, "status" => "" );
        
            $sql="select b.no_bukti,b.no_dokumen,b.kode_pp,b.waktu,b.kegiatan,b.dasar,b.nilai
            from apv_juskeb_m b 
            where b.kode_lokasi='$kode_lokasi' and b.no_bukti='$id' and b.progress in ('A','R') ";
            $response["daftar"] = dbResultArray($sql);

            $sql="select no_bukti,barang,harga,jumlah,nilai from apv_juskeb_d where kode_lokasi='".$kode_lokasi."' and no_bukti='$id'  order by no_urut";
            $response["daftar2"] = dbResultArray($sql);

            $sql="select no_bukti,nama,file_dok from apv_juskeb_dok where kode_lokasi='".$kode_lokasi."' and no_bukti='$id'  order by no_urut";
            $response["daftar3"] = dbResultArray($sql);

            $response['status'] = TRUE;
            // $response['sql']=$sql;
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
            $periode = date('Ym');
            $exec = array();
            $tmp="";
            $no_bukti = generateKode("apv_ver_m", "no_bukti", $kode_lokasi."-VER".substr($periode,2,4).".", "0001");
            
            $sql1= "insert into apv_ver_m (no_bukti,kode_lokasi,no_juskeb,status,keterangan,tanggal) values ('".$no_bukti."','".$kode_lokasi."','".$data['no_aju']."','".$data['status']."','".$data['keterangan']."',getdate()) ";
            
            array_push($exec,$sql1);
            
            $upd = "update apv_juskeb_m set progress ='".$data['status']."',nilai=".joinNum($data['total'])." where no_bukti='".$data['no_aju']."' and kode_lokasi='".$kode_lokasi."' ";
            array_push($exec,$upd);

            if($data['status'] == "V"){
                
                if(arrayKeyCheck(array('nama_dok'), $data) AND arrayKeyCheck(array('file_dok'), $_FILES) AND !empty($_FILES['file_dok']['tmp_name'])){
                    $data['nama_dok'] = array_values(array_filter($data['nama_dok'], function($value) { return $value !== ''; }));
                    $_FILES['file_dok']['name'] = array_values(array_filter($_FILES['file_dok']['name'], function($value) { return $value !== ''; }));
                    $_FILES['file_dok']['type'] = array_values(array_filter($_FILES['file_dok']['type'], function($value) { return $value !== ''; }));
                    $_FILES['file_dok']['tmp_name'] = array_values(array_filter($_FILES['file_dok']['tmp_name'], function($value) { return $value !== ''; }));
                    $_FILES['file_dok']['error'] = array_values(array_filter($_FILES['file_dok']['error'], function($value) { return $value === 0; }));
                    $_FILES['file_dok']['size'] = array_values(array_filter($_FILES['file_dok']['size'], function($value) { return $value > 0; }));
                    $arr_nama = array();
                    for($i=0; $i<count($data['nama_dok']); $i++){
                        $_FILES['userfile']['name']     = $_FILES['file_dok']['name'][$i];
                        $_FILES['userfile']['type']     = $_FILES['file_dok']['type'][$i];
                        $_FILES['userfile']['tmp_name'] = $_FILES['file_dok']['tmp_name'][$i];
                        $_FILES['userfile']['error']    = $_FILES['file_dok']['error'][$i];
                        $_FILES['userfile']['size']     = $_FILES['file_dok']['size'][$i];
                        
                        if(empty($_FILES['file_dok']['name'])){
                            
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

               
                // $del1 = "delete from apv_juskeb_m where no_bukti ='".$data['no_aju']."' and kode_lokasi='$kode_lokasi' ";
                // array_push($exec,$del1);
                
                // $sql1= "insert into apv_juskeb_m (no_bukti,no_dokumen,kode_pp,waktu,kegiatan,dasar,nik_buat,kode_lokasi,nilai,tanggal,progress) values ('".$data['no_aju']."','".$data['no_dokumen']."','".$data['kode_pp']."','".$data['waktu']."','".$data['kegiatan']."','".$data['dasar']."','$nik','".$kode_lokasi."',".joinNum($data['total']).",'".$data['tanggal']."','A') ";
                
                // array_push($exec,$sql1);
                
                if(count($data['barang']) > 0){
                    $del2 = "delete from apv_juskeb_d where no_bukti ='".$data['no_aju']."' and kode_lokasi='$kode_lokasi' ";
                    array_push($exec,$del2);

                    for($i=0; $i<count($data['barang']);$i++){
                        $sub = joinNum($data['harga'][$i])*joinNum($data['qty'][$i]);
                        $insdet = "insert into apv_juskeb_d (kode_lokasi,no_bukti,barang,harga,jumlah,no_urut,nilai) values ('$kode_lokasi','".$data['no_aju']."','".$data['barang'][$i]."',".joinNum($data['harga'][$i]).",".joinNum($data['qty'][$i]).",$i,".$sub.")"; 
                        
                        array_push($exec,$insdet);
                    }
                }
                
                if(arrayKeyCheck(array('nama_dok'), $data)){
                    $del4 = "delete from apv_juskeb_dok where no_bukti ='".$data['no_aju']."' and kode_lokasi='$kode_lokasi' ";
                    array_push($exec,$del4);
                    $x=0;
                    for($i=0; $i<count($data['nama_dok']);$i++){
                        if($data['nama_file'][$i] == "-"){
                            $insdet2 = "insert into apv_juskeb_dok (kode_lokasi,no_bukti,nama,no_urut,file_dok) values ('$kode_lokasi','".$data['no_aju']."','".$data['nama_dok'][$i]."',$i,'".$arr_nama[$x]."')"; 
                            $x++;
                        }else{

                            $insdet2 = "insert into apv_juskeb_dok (kode_lokasi,no_bukti,nama,no_urut,file_dok) values ('$kode_lokasi','".$data['no_aju']."','".$data['nama_dok'][$i]."',$i,'".$data['nama_file'][$i]."')"; 
                        }
                        
                        array_push($exec,$insdet2);
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
                
                $del5 = "delete from apv_flow where no_bukti ='".$data['no_aju']."' and kode_lokasi='$kode_lokasi' ";
                array_push($exec,$del5);

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
                    $ins = "insert into apv_flow (no_bukti,kode_lokasi,kode_role,kode_jab,no_urut,status,sts_ver) values ('".$data['no_aju']."','$kode_lokasi','".$role[$i]['kode_role']."','".$role[$i]['kode_jab']."',$i,'$prog',1) ";
    
                    array_push($exec,$ins);
                }

                //send to nik buat
                $sqlbuat = "
                select distinct isnull(c.no_telp,'-') as no_telp,d.token,b.nik_buat
                from apv_juskeb_m b 
                inner join apv_karyawan c on b.nik_buat=c.nik 
                inner join api_token_auth d on c.nik=d.nik and c.kode_lokasi=d.kode_lokasi
                where b.no_bukti='".$data['no_aju']."' ";
                $rs2 = dbResultArray($sqlbuat);
                if(count($rs2)>0){
                    $token_player = array();
                    for($i=0;$i<count($rs2);$i++){

                        $no_telp2 = $rs2[0]["no_telp"];
                        $nik_buat = $rs2[0]['nik_buat'];
                        array_push($token_player,$rs2[$i]['token']);
                        
                    }
                    $title = "Verifikasi Pengajuan Justifikasi Kebutuhan";
                    $content = "[Verifikasi] Pengajuan Justifikasi Kebutuhan ".$data["no_aju"]." anda telah di approve oleh $nik. ".$psn;
                    $notif2 = sendNotif($title,$content,$token_player);
                    // $wa2 = sendWA($no_telp2,$content);
                    $exec_notif2 = array();
                    for($t=0;$t<count($token_player);$t++){

                        $insert2 = " insert into apv_notif_m (kode_lokasi,nik,token,title,isi,tgl_input,kode_pp) values ('$kode_lokasi','$nik_buat','".$token_player[$t]."','$title','$content',getdate(),'-') ";
                        array_push($exec_notif2,$insert2);

                    }
                    $resno2 = executeArray($exec_notif2,$err);
                    $tmp.=".".$err;
                }

            }else{
                //send to nik buat

                $sqlbuat="
                select distinct isnull(c.no_telp,'-') as no_telp,d.token,b.nik_buat
                from apv_juskeb_m b 
                inner join apv_karyawan c on b.nik_buat=c.nik 
                inner join api_token_auth d on c.nik=d.nik and c.kode_lokasi=d.kode_lokasi
                where b.no_bukti='".$data['no_aju']."' ";
                $rs2 = dbResultArray($sqlbuat);
                if(count($rs2)>0){
                    $token_player = array();
                    for($i=0;$i<count($rs2);$i++){

                        $no_telp2 = $rs2[0]["no_telp"];
                        $nik_buat = $rs2[0]["nik_buat"];
                        array_push($token_player,$rs2[$i]['token']);
                        
                    }
                    $title = "Verifikasi Pengajuan Justifikasi Kebutuhan";
                    $content = "[Return] Pengajuan Justifikasi Kebutuhan ".$data["no_aju"]." anda telah di direturn oleh $nik. ";
                    $notif2 = sendNotif($title,$content,$token_player);
                    // $wa2 = sendWA($no_telp2,$content);
                    $exec_notif2 = array();
                    for($t=0;$t<count($token_player);$t++){

                        $insert2 = " insert into apv_notif_m (kode_lokasi,nik,token,title,isi,tgl_input,kode_pp) values ('$kode_lokasi','$nik_buat','".$token_player[$t]."','$title','$content',getdate(),'-') ";
                        array_push($exec_notif2,$insert2);

                    }
                    $resno2 = executeArray($exec_notif2,$err);
                    $tmp.=".".$err;
                }
            }
            array_push($exec,$upd2);

            $rs=executeArray($exec,$err);  
            // $rs=true;    
            if ($err == null)
            {	
                $tmp.="sukses";
                $sts=true;
            }else{
                $tmp.=$err;
                $sts=false;
            }	
            $stswanotif = "Notif 1 = ".$notif1." Notif 2 = ".$notif2; 
            $response["message"] =$tmp." ".$stswanotif;
            $response["status"] = $sts;
            $response["sql"] = $exec;
            $response["stsWANotif"] = $stswanotif;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }

    

   
?>