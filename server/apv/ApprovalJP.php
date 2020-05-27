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
    function getStatus(){
        
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $kode_lokasi = $_GET['kode_lokasi'];
            
            $sql="select status, nama from apv_status where kode_lokasi='$kode_lokasi' and status in (2,3) ";
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
            $query .= "select b.no_bukti,b.no_juskeb,b.no_dokumen,b.kode_pp,b.waktu,b.kegiatan,b.dasar,b.nilai
            from apv_flow a
            inner join apv_juspo_m b on a.no_bukti=b.no_bukti and a.kode_lokasi=b.kode_lokasi
            where a.kode_lokasi='$kode_lokasi' and a.status='1' and a.kode_jab='".$_SESSION['kode_jab']."' ";

            $column_array = array('b.no_bukti','b.no_juskeb','b.no_dokumen','b.kode_pp','b.waktu','b.kegiatan','b.dasar','b.nilai');
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
                $sub_array[] = $row->no_juskeb;
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

    function getApproval(){
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $query = '';
            $output = array();
        
            $kode_lokasi = $_GET['kode_lokasi'];
            $query .= "select a.no_bukti,a.no_urut,a.id,a.keterangan,a.tanggal
            from apv_pesan a
            left join apv_flow b on a.no_bukti=b.no_bukti and a.kode_lokasi=b.kode_lokasi and a.kode_lokasi=b.kode_lokasi and a.no_urut=b.no_urut
            where a.kode_lokasi='$kode_lokasi' and b.status='2' and a.modul='JP' and b.kode_jab='".$_SESSION['kode_jab']."' ";

            $column_array = array('a.no_bukti','a.no_urut','a.id','a.keterangan','a.tanggal');
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
                $sub_array[] = $row->no_urut;     
                $sub_array[] = $row->id;              
                $sub_array[] = $row->keterangan;             
                $sub_array[] = $row->tanggal;  
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
        
            $sql="select a.no_bukti,b.no_juskeb,b.no_dokumen,b.kode_pp,b.waktu,b.kegiatan,b.dasar,b.nilai,a.no_urut,c.nama as nama_pp
            from apv_flow a
            inner join apv_juspo_m b on a.no_bukti=b.no_bukti and a.kode_lokasi=b.kode_lokasi
            left join apv_pp c on b.kode_pp=c.kode_pp and b.kode_lokasi=c.kode_lokasi
            where a.kode_lokasi='$kode_lokasi' and a.no_bukti='$id' and a.status='1' ";
            $response["daftar"] = dbResultArray($sql);

            $sql2="select no_bukti,barang,harga,jumlah,nilai from apv_juspo_d where kode_lokasi='".$kode_lokasi."' and no_bukti='$id'  order by no_urut";
            $response["daftar2"] = dbResultArray($sql2);

            $sql3="select a.no_bukti,a.nama,a.file_dok from apv_juskeb_dok a inner join apv_juspo_m b on a.no_bukti=b.no_juskeb and a.kode_lokasi=b.kode_lokasi where a.kode_lokasi='".$_GET['kode_lokasi']."' and b.no_bukti='$id'  order by a.no_urut";
            $response["daftar3"] = dbResultArray($sql3);

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
            $tmp="";
            $data=$_POST;
            $kode_lokasi=$data['kode_lokasi'];
            $nik=$data['nik_user'];
            $exec = array();
            // $no_bukti = generateKode("app_pesan", "no_pesan", "PSN-", "0001");
            
            $sql1= "insert into apv_pesan (no_bukti,kode_lokasi,keterangan,tanggal,no_urut,status,modul) values ('".$data['no_aju']."','".$kode_lokasi."','".$data['keterangan']."','".$data['tanggal']."',".$data['nu'].",'".$data['status']."','JP') ";
            
            array_push($exec,$sql1);
            
            $upd = "update apv_flow set status ='".$data['status']."' ,tgl_app='".$data['tanggal']."' where no_bukti='".$data['no_aju']."' and no_urut=".$data['nu']."  ";
            array_push($exec,$upd);

            $max = dbRowArray("select max(no_urut) as nu from apv_flow where no_bukti='".$data['no_aju']."' ");

            $min = dbRowArray("select min(no_urut) as nu from apv_flow where no_bukti='".$data['no_aju']."' ");

            if($data['status'] == 2){
                $nu=$data['nu']+1;
                $upd2 = "update apv_flow set status ='1' ,tgl_app='".$data['tanggal']."' where no_bukti='".$data['no_aju']."' and no_urut=".$nu." ";
                //send to App selanjutnya
                if($data['nu'] != $max['nu']){

                    $sqlapp="
                    select isnull(b.no_telp,'-') as no_telp,c.token,b.nik
                    from apv_flow a
                    left join apv_karyawan b on a.kode_jab=b.kode_jab 
                    left join api_token_auth c on b.nik=c.nik and b.kode_lokasi=c.kode_lokasi
                    where a.no_bukti='".$data['no_aju']."' and a.no_urut=$nu ";

                    $rs = dbResultArray($sqlapp);
                    if(count($rs)>0){
                        $token_player = array();
                        for($i=0;$i<count($rs);$i++){
    
                            $no_telp = $rs[0]["no_telp"];
                            $nik_app1 = $rs[0]["nik"];
                            array_push($token_player,$rs[$i]['token']);
                            
                        }
                        $title = "Approval Pengajuan Justifikasi Pengadaan";
                        $content = "[Approval] Pengajuan Justifikasi Pengadaan ".$data["no_aju"]." telah di approve oleh $nik , Menunggu approval anda.";
                        $notif1 = sendNotif($title,$content,$token_player);
                        // $wa1 = sendWA($no_telp,$content);
                        $psn = "Menunggu approval $nik_app1 ";
                        $exec_notif = array();
                        for($t=0;$t<count($token_player);$t++){

                            $insert = " insert into apv_notif_m (kode_lokasi,nik,token,title,isi,tgl_input,kode_pp) values ('$kode_lokasi','$nik_app1','".$token_player[$t]."','$title','$content',getdate(),'-') ";
                            array_push($exec_notif,$insert);

                        }
                        $resno = executeArray($exec_notif,$err);
                        $tmp.=".".$err;
                    }
                    $upd3 = "update apv_juspo_m set progress ='".$data['status']."' where no_bukti='".$data['no_aju']."' ";
                    array_push($exec,$upd3);
                }else{
                    $upd3 = "update apv_juspo_m set progress ='S' where no_bukti='".$data['no_aju']."' ";
                    array_push($exec,$upd3);
                    $psn = "Approver terakhir";
                }

                //send to nik buat
                $sqlbuat = "
                select distinct isnull(c.no_telp,'-') as no_telp,d.token,b.nik_buat
                from apv_flow a
                inner join apv_juspo_m b on a.no_bukti=b.no_bukti 
                inner join apv_karyawan c on b.nik_buat=c.nik 
                inner join api_token_auth d on c.nik=d.nik and c.kode_lokasi=d.kode_lokasi
                where a.no_bukti='".$data['no_aju']."' ";
                $rs2 = dbResultArray($sqlbuat);
                if(count($rs2)>0){
                    $token_player = array();
                    for($i=0;$i<count($rs2);$i++){

                        $no_telp2 = $rs2[0]["no_telp"];
                        $nik_buat = $rs2[0]['nik_buat'];
                        array_push($token_player,$rs2[$i]['token']);
                        
                    }
                    $title = "Approval Pengajuan Justifikasi Pengadaan";
                    $content = "[Approval] Pengajuan Justifikasi Pengadaan ".$data["no_aju"]." anda telah di approve oleh $nik. ".$psn;
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
                $nu=$data['nu']-1;
                $upd2 = "update apv_flow set status ='1' ,tgl_app='-' where no_bukti='".$data['no_aju']."' and no_urut=".$nu." ";

                $upd3 = "update apv_juspo_m set progress ='R' where no_bukti='".$data['no_aju']."' ";
                array_push($exec,$upd3);

                if($data['nu'] != $min['nu']){
                    //send to approver sebelumnya
                    $sqlapp="
                    select isnull(b.no_telp,'-') as no_telp,c.token,b.nik
                    from apv_flow a
                    left join apv_karyawan b on a.kode_jab=b.kode_jab 
                    left join api_token_auth c on b.nik=c.nik and b.kode_lokasi=c.kode_lokasi
                    where a.no_bukti='".$data['no_aju']."' and a.no_urut=$nu ";
                    $rs = dbResultArray($sqlapp);
                    if(count($rs)>0){
                        $token_player = array();
                        for($i=0;$i<count($rs);$i++){
                            
                            $no_telp = $rs[0]["no_telp"];
                            $nik_app1 = $rs[0]["nik"];
                            array_push($token_player,$rs[$i]['token']);
                            
                        }
                        $title = "Approval Pengajuan Justifikasi Pengadaan";
                        $content = "[Return] Pengajuan Justifikasi Pengadaan ".$data["no_aju"]." telah di return oleh $nik";
                        $notif1 = sendNotif($title,$content,$token_player);
                        // $wa1 = sendWA($no_telp,$content);
                        // $psn = "Menunggu approval $nik_app1 ";
                        $exec_notif = array();
                        for($t=0;$t<count($token_player);$t++){
                            
                            $insert = " insert into apv_notif_m (kode_lokasi,nik,token,title,isi,tgl_input,kode_pp) values ('$kode_lokasi','$nik_app1','".$token_player[$t]."','$title','$content',getdate(),'-') ";
                            array_push($exec_notif,$insert);
                            
                        }
                        $resno = executeArray($exec_notif,$err);
                        $tmp.=".".$err;
                    }
                }
                //send to nik buat

                $sqlbuat="
                select distinct isnull(c.no_telp,'-') as no_telp,d.token,b.nik_buat
                from apv_flow a
                inner join apv_juspo_m b on a.no_bukti=b.no_bukti 
                inner join apv_karyawan c on b.nik_buat=c.nik 
                inner join api_token_auth d on c.nik=d.nik and c.kode_lokasi=d.kode_lokasi
                where a.no_bukti='".$data['no_aju']."' ";
                $rs2 = dbResultArray($sqlbuat);
                if(count($rs2)>0){
                    $token_player = array();
                    for($i=0;$i<count($rs2);$i++){

                        $no_telp2 = $rs2[0]["no_telp"];
                        $nik_buat = $rs2[0]["nik_buat"];
                        array_push($token_player,$rs2[$i]['token']);
                        
                    }
                    $title = "Approval Pengajuan Justifikasi Pengadaan";
                    $content = "[Return] Pengajuan Justifikasi Pengadaan ".$data["no_aju"]." anda telah di direturn oleh $nik. ";
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
            if ($err==null)
            {	
                $tmp.="sukses";
                $sts=true;
            }else{
                $tmp.=$err;
                $sts=false;
            }	
            $stswanotif = "Notif 1 = ".$notif1." Notif 2 = ".$notif2;// $n=" WA 1= ".$wa1."$no_telp WA 2 =".$wa2." $no_telp2";
            $response["message"] =$tmp." ".$stswanotif;
            $response["status"] = $sts;
            $response["sql"] = $exec;
            $response["sqlapp"] = $sqlapp;
            $response["sqlbuat"] = $sqlbuat;
            $response["stsWANotif"] = $stswanotif;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }

    // MOBILE WEB VIEW 
    function getPengajuan2(){
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $query = '';
            $output = array();
            $tmp =explode("|",$_GET['param']);
        
            $kode_lokasi = $tmp[0];
            $query .= "select b.no_bukti,b.no_dokumen,b.kode_pp,b.waktu,b.kegiatan,b.dasar,b.nilai,convert(varchar,b.tanggal,103) as tanggal,datename(dw,b.tanggal) as nama_hari
            from apv_flow a
            inner join apv_juskeb_m b on a.no_bukti=b.no_bukti and a.kode_lokasi=b.kode_lokasi
            where a.kode_lokasi='$kode_lokasi' and a.status='1' and a.kode_jab='".$_SESSION['kode_jab']."' ";

            $response["data"] = dbResultArray($query);
            $response['sql']=$query;
            $response["status"] = true;
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
            $query = '';
            $output = array();
            $tmp =explode("|",$_GET['param']);
        
            $kode_lokasi = $tmp[0];
            // $query .= "select b.no_bukti,b.no_dokumen,b.kode_pp,b.waktu,b.kegiatan,b.dasar,b.nilai,convert(varchar,b.tanggal,103) as tanggal,datename(dw,b.tanggal) as nama_hari,a.status
            // from apv_flow a
            // inner join apv_juskeb_m b on a.no_bukti=b.no_bukti and a.kode_lokasi=b.kode_lokasi
            // where a.kode_lokasi='$kode_lokasi' and a.status in ('2','3') and a.kode_jab='".$_SESSION['kode_jab']."' ";
            $query .="select c.kegiatan, c.dasar,c.nilai, a.no_bukti,a.status,a.no_urut,a.id,a.keterangan,convert(varchar,a.tanggal,103) as tanggal,datename(dw,a.tanggal) as nama_hari
            from apv_pesan a
            left join apv_flow b on a.no_bukti=b.no_bukti and a.kode_lokasi=b.kode_lokasi and a.kode_lokasi=b.kode_lokasi and a.no_urut=b.no_urut
            left join apv_juspo_m c on a.no_bukti=c.no_bukti AND a.kode_lokasi=c.kode_lokasi 
            where a.kode_lokasi='$kode_lokasi' and a.modul='JP' and b.status IN ('2','3') and b.kode_jab='".$_SESSION['kode_jab']."' ";

            $response["data"] = dbResultArray($query);
            $response['sql']=$query;
            $response["status"] = true;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }

    
    function getData2(){
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $id=$_GET['no_aju'];
            $kode_lokasi=$_GET['kode_lokasi'];    
        
            $response = array("message" => "", "rows" => 0, "status" => "" );
        
            // $sql="select a.no_bukti,b.no_dokumen,b.kode_pp,b.waktu,b.kegiatan,b.dasar,b.nilai,a.no_urut,c.nama as nama_pp
            // from apv_flow a
            // inner join apv_juskeb_m b on a.no_bukti=b.no_bukti and a.kode_lokasi=b.kode_lokasi
            // left join apv_pp c on b.kode_pp=c.kode_pp and b.kode_lokasi=c.kode_lokasi
            // where a.kode_lokasi='$kode_lokasi' and a.no_bukti='$id' and a.status='1' ";

            $sql="select c.keterangan,a.no_bukti,b.no_dokumen,b.kode_pp,b.waktu,b.kegiatan,b.dasar,b.nilai,a.no_urut,d.nama as nama_pp
            from apv_flow a
            inner join apv_juskeb_m b on a.no_bukti=b.no_bukti and a.kode_lokasi=b.kode_lokasi
            left join apv_pesan c on a.no_bukti=c.no_bukti and a.kode_lokasi=c.kode_lokasi and a.no_urut=c.no_urut and c.modul='JP'
            left join apv_pp d on b.kode_pp=d.kode_pp and b.kode_lokasi=d.kode_lokasi
            where a.kode_lokasi='$kode_lokasi' and a.no_bukti='$id' and a.status IN ('2','3') ";
        
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

?>