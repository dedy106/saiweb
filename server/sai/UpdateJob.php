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
        include_once($root_lib."lib/koneksi.php");
        include_once($root_lib."lib/helpers.php");
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
                'key'=> '15a5475dddf14b76254be32384b350ed2c416554ca8eb67c'
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

    function getProyek(){
        
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $kode_lokasi = $_GET['kode_lokasi'];
            
            $sql="select no_proyek, nama from sai_proyek where kode_lokasi='$kode_lokasi' ";
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
            
            $sql="select nik, nama from sai_karyawan where kode_lokasi='$kode_lokasi' ";
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
   
    function getJob(){
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $query = '';
            $output = array();
        
            $kode_lokasi = $_GET['kode_lokasi'];
            $query .= "select a.kode_job,a.nama,a.no_proyek, a.nik,a.tgl_mulai,a.tgl_selesai, a.progress 
            from sai_job_m a
            where a.kode_lokasi='$kode_lokasi' and a.progress in ('0','1') ";

            $column_array = array('a.kode_job','a.nama','a.no_proyek','a.nik','a.tgl_mulai','a.tgl_selesai');
            $order_column = 'ORDER BY a.kode_job '.$_GET['order'][0]['dir'];
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
                $query .= ' ORDER BY  b.kode_job ';
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
                $sub_array[] = $row->kode_job;
                $sub_array[] = $row->nama;
                $sub_array[] = $row->no_proyek;
                $sub_array[] = $row->nik;                
                $sub_array[] = $row->tgl_mulai;             
                $sub_array[] = $row->tgl_selesai; 
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

    function getApproval(){
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $query = '';
            $output = array();
        
            $kode_lokasi = $_GET['kode_lokasi'];
            $query .= "select a.kode_job,a.nama,a.no_proyek, a.nik,a.tgl_mulai,a.tgl_selesai, a.progress 
            from sai_job_m a
            where a.kode_lokasi='$kode_lokasi' and a.status='F' ";

            $column_array = array('a.kode_job','a.nama','a.no_proyek','a.nik','a.tgl_mulai','a.tgl_selesai');
            $order_column = 'ORDER BY a.kode_job '.$_GET['order'][0]['dir'];
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
                $query .= ' ORDER BY  a.kode_jab ';
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
                $sub_array[] = $row->kode_job;
                $sub_array[] = $row->nama;
                $sub_array[] = $row->no_proyek;
                $sub_array[] = $row->nik;                
                $sub_array[] = $row->tgl_mulai;             
                $sub_array[] = $row->tgl_selesai; 
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
        
            $sql="select a.kode_job,a.nama,a.no_proyek, a.nik,a.tgl_mulai,a.tgl_selesai, a.progress 
            from sai_job_m a
            where a.kode_lokasi='$kode_lokasi' and a.kode_job='$id' and a.progress in ('0','1') ";
            $response["daftar"] = dbResultArray($sql);

            $sql="select kode_job,nu,nama,keterangan,status from sai_job_d where kode_lokasi='".$kode_lokasi."' and kode_job='$id'  and status='0' order by nu";
            $response["daftar2"] = dbResultArray($sql);

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
            $exec = array();
            $no_bukti = generateKode("sai_pesan", "id", "PSN-", "0001");
            $no=0;
            for($i=0;$i<count($data['nu']);$i++){
                if($data['status'][$i] == "1"){

                    $sql1 = "insert into sai_pesan (no_bukti,kode_lokasi,keterangan,tanggal,no_urut,status,modul) values ('".$data['kode_job']."','".$kode_lokasi."','".$data['ket_det'][$i]."','".$data['tanggal']."',".$data['nu'][$i].",'".$data['status'][$i]."','-') ";
                    array_push($exec,$sql1);
                    $upd = "update sai_job_d set status ='".$data['status'][$i]."' where kode_job='".$data['kode_job']."' and nu=".$data['nu'][$i]." and kode_lokasi='$kode_lokasi' ";
                    array_push($exec,$upd);
                    $updx = "update sai_job_m set progress ='1' where kode_job='".$data['kode_job']."' and kode_lokasi='$kode_lokasi' ";
                    array_push($exec,$updx);
                    $no++;
                }

            }
            
            $sql = "select kode_job,kode_lokasi,count(*) as semua,count(case when status='1' then kode_job end) as finish 
            from sai_job_d  
            where kode_lokasi='$kode_lokasi' and kode_job='".$data['kode_job']."' 
            group by kode_job,kode_lokasi 
            having count(*) - count(case when status='1' then kode_job end) -$no <> '0' ";
            $cek = dbResultArray($sql);
            if(count($cek) == 0){
                $upd2 = "update sai_job_m set progress='F'
                where kode_lokasi='$kode_lokasi' and kode_job='".$data['kode_job']."' ";
                array_push($exec,$upd2);
            }

            $rs=executeArray($exec);  
            // $rs=true;    
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
            $response["sql"] = $exec;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }

?>