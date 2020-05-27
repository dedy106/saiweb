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
        include_once($root_lib."lib/koneksi5.php");
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
        $auth = $schema->SelectLimit("SELECT * FROM sis_hakakses where nik=$user ", 1);
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
                'key' => 'cd89a8c837380f06ed5f8bbf3a3759b3becd577cc5979586'
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

    function getSisTtd(){
        
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $kode_lokasi = $_GET['kode_lokasi'];
            $kode_pp = $_GET['kode_pp'];

            $wali = dbRowArray("select nik,nama from sis_ttd where kode_lokasi='$kode_lokasi' and kode_pp='$kode_pp' and jabatan='Wali Kelas' ");

            $adm = dbRowArray("select nik,nama from sis_ttd where kode_lokasi='$kode_lokasi' and kode_pp='$kode_pp' and jabatan='Ka Admin' ");

            $kepsek = dbRowArray("select nik from sis_ttd where kode_lokasi='$kode_lokasi' and kode_pp='$kode_pp' and jabatan='Kepala Sekolah' ");

            $response['nik_wali']=$wali['nik'];
            $response['nik_app1']=$adm['nik'];
            $response['nik_app2']=$kepsek['nik'];
            
            $response['nama_wali']=$wali['nama'];
            $response['nama_app1']=$adm['nama'];
            $response['nama_app2']=$kepsek['nama'];

            $response['status'] = TRUE;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }
    
    function getTagihan(){
        
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $kode_lokasi = $_GET['kode_lokasi'];
            $kode_pp = $_GET['kode_pp'];
            $nis = $_GET['nik'];
            $periode = $_GET['periode'];
            $sql = "select a.nis,a.nama,a.kode_lokasi,a.kode_pp,a.kode_akt,
            a.kode_kelas,f.kode_jur,f.nama as nama_jur,b.kode_param, isnull(b.total,0)-isnull(d.total,0) as saw_total
            ,isnull(c.total,0) as debet_total
            ,isnull(e.total,0) as kredit_total
            ,isnull(b.total,0)-isnull(d.total,0)+isnull(c.total,0)-isnull(e.total,0) as sak_total
            from sis_siswa a 
            inner join sis_kelas f on a.kode_kelas=f.kode_kelas and a.kode_lokasi=f.kode_lokasi and a.kode_pp=f.kode_pp
            inner join sis_jur g on f.kode_jur=g.kode_jur and f.kode_lokasi=g.kode_lokasi and f.kode_pp=g.kode_pp
            left join (select y.nis,y.kode_lokasi,x.kode_param,sum(case when x.dc='D' then x.nilai else -x.nilai end) as total
                        from sis_bill_d x 			
                        inner join sis_siswa y on x.nis=y.nis and x.kode_lokasi=y.kode_lokasi and x.kode_pp=y.kode_pp
                        where(x.kode_lokasi = '$kode_lokasi')and(x.periode < '$periode') and x.kode_pp='$kode_pp' and x.kode_param in ('DSP','SPP')	
                        group by y.nis,y.kode_lokasi,x.kode_param ) b on a.nis=b.nis and a.kode_lokasi=b.kode_lokasi
            left join (select y.nis,y.kode_lokasi,x.kode_param,sum(case when x.dc='D' then x.nilai else -x.nilai end) as total
                        from sis_bill_d x 			
                        inner join sis_siswa y on x.nis=y.nis and x.kode_lokasi=y.kode_lokasi and x.kode_pp=y.kode_pp
                        where(x.kode_lokasi = '$kode_lokasi')and(x.periode = '$periode') and x.kode_pp='$kode_pp' and x.kode_param in ('DSP','SPP')		
                        group by y.nis,y.kode_lokasi,x.kode_param 		
                        )c on a.nis=c.nis and a.kode_lokasi=c.kode_lokasi and b.kode_param=c.kode_param
            left join (select y.nis,y.kode_lokasi,x.kode_param,sum(case when x.dc='D' then x.nilai else -x.nilai end) as total				
                        from sis_rekon_d x 	
                        inner join sis_siswa y on x.nis=y.nis and x.kode_lokasi=y.kode_lokasi and x.kode_pp=y.kode_pp
                        where(x.kode_lokasi = '$kode_lokasi')and(x.periode <'$periode')	and x.kode_pp='$kode_pp'	and x.kode_param in ('DSP','SPP')
                        group by y.nis,y.kode_lokasi,x.kode_param 		
                        )d on a.nis=d.nis and a.kode_lokasi=d.kode_lokasi and b.kode_param=d.kode_param
            left join (select y.nis,y.kode_lokasi,x.kode_param,sum(case when x.dc='D' then x.nilai else -x.nilai end) as total
                        from sis_rekon_d x 			
                        inner join sis_siswa y on x.nis=y.nis and x.kode_lokasi=y.kode_lokasi and x.kode_pp=y.kode_pp
                        where(x.kode_lokasi = '$kode_lokasi')and(x.periode ='$periode') and x.kode_pp='$kode_pp'	and x.kode_param in ('DSP','SPP')	
                        group by y.nis,y.kode_lokasi,x.kode_param 	
                        )e on a.nis=e.nis and a.kode_lokasi=e.kode_lokasi and b.kode_param=e.kode_param
            where a.kode_lokasi='$kode_lokasi' and a.kode_pp='$kode_pp' and a.nis='$nis' ";
            $response['daftar'] = dbResultArray($sql);
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
            $kode_pp = $_GET['kode_pp'];

            $sql="select a.no_bukti,b.keterangan,b.tanggal,c.nama
            from apv_flow a
            inner join apv_pesan b on a.no_bukti=b.no_bukti and a.kode_lokasi=b.kode_lokasi and a.no_urut=b.no_urut
            left join apv_jab c on a.kode_jab=c.kode_jab and a.kode_lokasi=c.kode_lokasi
            where a.kode_lokasi='$kode_lokasi' and a.no_bukti='$no_bukti' and a.kode_pp='$kode_pp'  ";
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
            $kode_pp = $_GET['kode_pp'];

            $sql="select a.no_aju,a.nama_ortu,a.alamat,a.no_telp,a.nis,a.nilai,a.nilai_byr,a.tgl_bayar,b.nama as nama_siswa,b.kode_kelas as nama_kelas,a.nik_wali,a.nik_app1,a.nik_app2,c.nama as nama_wali,d.nama as nama_app1,e.nama as nama_app2,a.tgl_input
            from sis_aju_m a
            left join sis_siswa b on a.nis=b.nis and a.kode_pp=b.kode_pp and a.kode_lokasi=b.kode_lokasi
            left join karyawan c on a.nik_wali=c.nik and a.kode_pp=c.kode_pp and a.kode_lokasi=c.kode_lokasi
            left join karyawan d on a.nik_app1=d.nik and a.kode_pp=d.kode_pp and a.kode_lokasi=d.kode_lokasi
            left join karyawan e on a.nik_app2=e.nik and a.kode_pp=e.kode_pp and a.kode_lokasi=e.kode_lokasi
            where a.kode_lokasi='$kode_lokasi' and a.no_aju='$no_bukti' and a.kode_pp='$kode_pp' ";
            $rs=execute($sql);
            $response["daftar"]=array();
            while ($row = $rs->FetchNextObject(false)){
                $response['daftar'][] = (array)$row;
            }

            $sql2="select a.no_aju,a.nu,a.kode_produk,a.nilai,a.nilai_byr
            from sis_aju_d a            
            where a.kode_lokasi='$kode_lokasi' and a.no_aju='$no_bukti' and a.kode_pp='$kode_pp' ";
            $rs2=execute($sql2);
            $response["daftar2"]=array();
            while ($row = $rs2->FetchNextObject(false)){
                $response['daftar2'][] = (array)$row;
            }

            $response['sql']=$sql;
            $response['sql2']=$sql2;
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
            $kode_pp = $_GET['kode_pp'];
            $nik = $_GET['nik'];
            $query .= "select a.no_aju,convert(varchar,a.tgl_input,103) as tgl,a.no_ktp,a.nama_ortu,a.kode_pp,a.nilai,case a.progress when '0' then 'INPROGRESS' when '2' then 'APPROVE' when '3' then 'RETURN' end as posisi,a.progress
            from sis_aju_m a 
            left join (select a.no_bukti,b.nama as nama_jab
            from apv_flow a
            inner join apv_jab b on a.kode_jab=b.kode_jab and a.kode_lokasi=b.kode_lokasi
            where a.kode_lokasi='$kode_lokasi' and a.status='1'
            )b on a.no_aju=b.no_bukti
            where a.kode_lokasi='$kode_lokasi' and a.nis='$nik' and a.kode_pp='$kode_pp' ";

            $column_array = array('a.no_aju','convert(varchar,a.tgl_input,103)','a.kode_pp','a.no_ktp','a.nama_ortu','a.progress','a.nilai');
            $order_column = 'ORDER BY a.no_aju '.$_GET['order'][0]['dir'];
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
                $query .= ' ORDER BY  a.no_aju ';
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
                $sub_array[] = $row->no_aju;
                $sub_array[] = $row->tgl;
                $sub_array[] = $row->kode_pp;
                $sub_array[] = $row->no_ktp;
                $sub_array[] = $row->nama_ortu;                
                $sub_array[] = $row->posisi;             
                $sub_array[] = $row->nilai; 
                if($row->progress == '2'){
                    $sub_array[]="<a href='#' title='History' class='badge badge-success' id='btn-history'><i class='fas fa-history'></i></a>&nbsp; <a href='#' title='Preview' class='badge badge-info' id='btn-print'><i class='fas fa-print'></i></a>";
                }else{
                    $sub_array[] = "<a href='#' title='Edit' class='badge badge-warning' id='btn-edit'><i class='fas fa-pencil-alt'></i></a> &nbsp; <a href='#' title='Hapus' class='badge badge-danger' id='btn-delete'><i class='fa fa-trash'></i></a>&nbsp; <a href='#' title='History' class='badge badge-success' id='btn-history'><i class='fas fa-history'></i></a>&nbsp; <a href='#' title='Preview' class='badge badge-info' id='btn-print'><i class='fas fa-print'></i></a>";
                }
                $data[] = $sub_array;
            }
            $response = array(
                "draw"				=>	intval($_GET["draw"]),
                "recordsTotal"		=> 	$filtered_rows,
                "recordsFiltered"	=>	$jml_baris,
                "data"				=>	$data,
                "query"				=>	$query
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
            $kode_lokasi=$_GET['kode_lokasi'];  
            $kode_pp=$_GET['kode_pp'];    

            $response = array("message" => "", "rows" => 0, "status" => "" );
        
            $sql="select a.no_aju,a.nama_ortu,a.alamat,a.no_telp,a.nis,a.nilai,a.nilai_byr,a.tgl_bayar,b.nama as nama_siswa,b.kode_kelas as nama_kelas,a.nik_wali,a.nik_app1,a.nik_app2,c.nama as nama_wali,d.nama as nama_app1,e.nama as nama_app2,a.tgl_input,a.no_ktp,a.file_dok
            from sis_aju_m a
            left join sis_siswa b on a.nis=b.nis and a.kode_pp=b.kode_pp and a.kode_lokasi=b.kode_lokasi
            left join karyawan c on a.nik_wali=c.nik and a.kode_pp=c.kode_pp and a.kode_lokasi=c.kode_lokasi
            left join karyawan d on a.nik_app1=d.nik and a.kode_pp=d.kode_pp and a.kode_lokasi=d.kode_lokasi
            left join karyawan e on a.nik_app2=e.nik and a.kode_pp=e.kode_pp and a.kode_lokasi=e.kode_lokasi
            where a.kode_lokasi='$kode_lokasi' and a.no_aju='$id' and a.kode_pp='$kode_pp'";
            $rs=execute($sql);
            $response["daftar"]=array();
            while ($row = $rs->FetchNextObject(false)){
                $response['daftar'][] = (array)$row;
            }

            $sql2="select a.no_aju,a.nu,a.kode_produk,a.nilai,a.nilai_byr
            from sis_aju_d a            
            where a.kode_lokasi='$kode_lokasi' and a.no_aju='$id' and a.kode_pp='$kode_pp' ";
            $rs2=execute($sql2);
            $response["daftar2"]=array();
            while ($row = $rs2->FetchNextObject(false)){
                $response['daftar2'][] = (array)$row;
            }

            $sql="select no_bukti,nama,file_dok from sis_aju_dok where kode_lokasi='".$_GET['kode_lokasi']."' and no_bukti='$id'  order by no_urut";
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
            $kode_pp=$data['kode_pp'];
            $exec = array();
            if($data["no_bukti"] == ""){
                $no_aju = generateKode("sis_aju_m", "no_aju", "AJU-S", "0001");
                $edit = false;
            }else{
                $no_aju = $data["no_bukti"];
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
            
            if($edit){
                $del1 = "delete from sis_aju_m where no_aju ='$no_aju' and kode_lokasi='$kode_lokasi' and kode_pp='$kode_pp'";
                array_push($exec,$del1);
                $del2 = "delete from sis_aju_d where no_aju ='$no_aju' and kode_lokasi='$kode_lokasi' and kode_pp='$kode_pp'";
                array_push($exec,$del2);
                $del3 = "delete from apv_flow where no_bukti ='$no_aju' and kode_lokasi='$kode_lokasi' and kode_pp='$kode_pp' ";
                array_push($exec,$del3);
                $del4 = "delete from sis_aju_dok where no_bukti ='$no_aju' and kode_lokasi='$kode_lokasi' ";
                array_push($exec,$del4);
            }

           

            $tmp1 = explode("/",$data['nik_wali']);
            $nik_wali= $tmp1[0];
            $nama_wali= $tmp1[1];

            $tmp2 = explode("/",$data['nik_app1']);
            $nik_app1= $tmp2[0];
            $nama_app1= $tmp2[1];

            $tmp3 = explode("/",$data['nik_app2']);
            $nik_app2= $tmp3[0];
            $nama_app2= $tmp3[1];
            
            $sql1= "insert into sis_aju_m (no_aju, kode_lokasi, periode, tgl_bayar, nis, nama_ortu, alamat, no_telp, no_ktp, tgl_input, nik_user, kode_pp, nik_wali, nik_app1, nik_app2, progress, nilai, nilai_byr,file_dok) values ('".$no_aju."','".$data['kode_lokasi']."','".$data['periode']."','".$data['tgl_bayar']."','".$data['nis']."','".$data['nama_wali']."','".$data['alamat']."','".$data['no_telp']."','".$data['no_ktp']."',getdate(),'".$data['nik_user']."','".$data['kode_pp']."','".$nik_wali."','".$nik_app1."','".$nik_app2."','0',".joinNum($data['total']).",".joinNum($data['total_byr']).",'-') ";
            
            array_push($exec,$sql1);
            
            $det="";
            $detwa="";
            $nom=array('a','b','c','d');
            if(count($data['kode_produk']) > 0){
                for($i=0; $i<count($data['kode_produk']);$i++){
                    $insdet = "insert into sis_aju_d (no_aju, kode_lokasi, periode, kode_pp, kode_produk, nilai, nilai_byr,nu) values ('$no_aju','".$data['kode_lokasi']."','".$data['periode']."','".$data['kode_pp']."','".$data['kode_produk'][$i]."',".joinNum($data['nilai'][$i]).",".joinNum($data['nilai_byr'][$i]).",$i)"; 
                    
                    array_push($exec,$insdet);
                    $det.=$nom[$i].". ".$data['kode_produk'][$i].":".$data['nilai'][$i];
                    $detwa.=$nom[$i].". ".$data['kode_produk'][$i].":".$data['nilai'][$i]."<ENTER>";
                }
            }
            $det.="Jumlah Kesanggupan Membayar:".$data['total'];
            $detwa.="Jumlah Kesanggupan Membayar:".$data['total']."<ENTER>";

            if(arrayKeyCheck(array('nama_dok'), $data) AND arrayKeyCheck(array('file_dok'), $_FILES) AND !empty($_FILES['file_dok']['tmp_name'])){
                $del4 = "DELETE FROM sis_aju_dok where no_bukti='$no_aju' and kode_lokasi='$kode_lokasi' ";
                array_push($exec,$del4);
                
                for($i=0; $i<count($data['nama_dok']);$i++){
                    $insdet2 = "insert into sis_aju_dok (kode_lokasi,no_bukti,nama,no_urut,file_dok) values ('$kode_lokasi','$no_aju','".$data['nama_dok'][$i]."',$i,'".$arr_nama[$i]."')"; 
                    
                    array_push($exec,$insdet2);
                }
                
            }

            $sql = "select a.kode_role,b.kode_jab,b.no_urut,c.nik,c.no_telp,c.email
            from apv_role a
            inner join apv_role_jab b on a.kode_role=b.kode_role and a.kode_lokasi=b.kode_lokasi
            inner join apv_karyawan c on b.kode_jab=c.kode_jab and b.kode_lokasi=c.kode_lokasi and a.kode_pp=c.kode_pp
            where a.kode_lokasi='$kode_lokasi' and a.kode_pp='$kode_pp' and ".joinNum($data['total'])." between a.bawah and a.atas
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
                    $email_app=$role[$i]["email"];
                }else{
                    $prog = 0;
                }
                $ins = "insert into apv_flow (no_bukti,kode_lokasi,kode_role,kode_jab,no_urut,status,kode_pp) values ('$no_aju','$kode_lokasi','".$role[$i]['kode_role']."','".$role[$i]['kode_jab']."',$i,'$prog','$kode_pp') ";

                array_push($exec,$ins);
            }
            
            $rs=executeArray($exec);  
            $rs=true;    
            
            if ($rs)
            {	


                $tmp="sukses";
                $sts=true;
                // KIRIM EMAIL SISWA
                try{

                    $email = $_SESSION['email'];
                    $user = $_SESSION['namaUser'];
                    $no_hp = $data['no_telp'];
                    // $body_text = "Pengajuan Keringanan Membayar anda ($no_aju) berhasil dikirim, menunggu approval $app_nik ";
                    $body_text = "NOTIFIKASI KESANGGUPAN MEMBAYAR SISWA
                    Nama Siswa	:	$user <br>
                    Kelas	    :	".$data['kelas']." <br>
                    $det ";
                    if($email != "" OR $email != "-" OR !empty($email)){
                        $rs2 = kirim_email($email,$user,$body_text);
                        if($rs2){
                            $tmp.=". Email ke siswa terkirim";
                        }else{
                            $tmp.=". Email ke siswa gagal terkirim";
                        }
                    }else{
                        $tmp.=".Email siswa belum terdaftar didatabase";
                    }
                    
                    //SEND WA SISWA
                    $body_text = "NOTIFIKASI KESANGGUPAN MEMBAYAR SISWA <ENTER>Nama Siswa	:	$user <ENTER>Kelas	    :	".$data['kelas']." <ENTER> $detwa ";
                    if($no_hp != ""  OR $no_hp != "-"){
    
                        $rs4 = sendWA($no_hp,$body_text);
                        if($rs4 == "Success"){
                            $tmp.=". Pesan whatsapp ke siswa terkirim ke ".$no_hp;
                        }else{
                            $tmp.=". Pesan whatsapp ke siswa gagal terkirim";
                        }
                    }else{
                        $tmp.=".No Hp siswa belum terdaftar didatabase";
                    }
    
                    // KIRIM EMAIL APP
                    // $body_text = "Pengajuan Keringanan Membayar ($no_aju) dari siswa dengan nim $nik ($user) menunggu approval $app_nik ";

                    $body_text = "<center> NOTIFIKASI KESANGGUPAN MEMBAYAR SISWA </center>
                    Nama Siswa	:	$user <br>
                    Kelas	    :	".$data['kelas']." <br>
                    $det ";
                    
                    if($email_app != "" OR $email_app != "-" OR !empty($email_app)){
                        $rs2 = kirim_email($email_app,$app_nik,$body_text);
                        if($rs2){
                            $tmp.=". Email ke approver terkirim ";
                        }else{
                            $tmp.=". Email ke approver gagal terkirim";
                        }
                    }else{
                        $tmp.=".Email aprrover belum terdaftar didatabase";
                    }
    
                    //SEND WA APP
                    $body_text = "NOTIFIKASI KESANGGUPAN MEMBAYAR SISWA<ENTER>Nama Siswa	:	$user <ENTER>Kelas	    :	".$data['kelas']." <ENTER>
                    $detwa ";
                    if($no_telp != ""  OR $no_telp != "-"){
                        $rs4 = sendWA($no_telp,$body_text);
                        if($rs4 == "Success"){
                            $tmp.=". Pesan whatsapp approver terkirim ke ".$no_telp;
                        }else{
                            $tmp.=". Pesan whatsapp approver gagal terkirim".$no_telp;
                        }
                    }else{
                        $tmp.=".No Telp Approver belum terdaftar didatabase";
                    }
    
                    $title = "Pengajuan Keringanan Membayar";
                    // $content = "Pengajuan Keringanan Membayar $no_aju dari siswa dengan nim $nik ($user), menunggu approval anda ";
                    $content = "NOTIFIKASI KESANGGUPAN MEMBAYAR SISWA
                    Nama Siswa	:	$user 
                    Kelas	    :	".$data['kelas']." 
                    $det ";

                    $rs3 = sendNotif($title,$content,$token_player);
                    if($rs3){
                        $tmp.=". Notif app terkirim";
                        $exec_notif = array();
                        for($t=0;$t<count($token_player);$t++){
    
                            $insert = " insert into apv_notif_m (kode_lokasi,nik,token,title,isi,tgl_input,kode_pp) values ('$kode_lokasi','$app_nik','".$token_player[$t]."','$title','$content',getdate(),'-') ";
                            array_push($exec_notif,$insert);
    
                        }
                        // $resno = executeArray($exec_notif);
                    }else{
                        $tmp.=". Notif app gagal terkirim";
                    }
                } catch (exception $e) { 
                    error_log($e->getMessage());		
                    $error ="error " .  $e->getMessage();
                } 	

                
            }else{
                $tmp="gagal";
                $sts=false;
            }	
            $response["message"] =$tmp.$error;
            $response["status"] = $sts;
            $response["error"] = $error_upload;
            $response["no_aju"] = $no_aju;
            
            $response["bodytext"] = $body_text;
            
            // $response["exec"] = $exec;
            // $response["exec_n"] = $exec_notif;
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
            $del = "delete from sis_aju_m where no_aju='".$data['no_bukti']."' and kode_lokasi='".$data['kode_lokasi']."' ";
            array_push($exec,$del);

            $del2 = "delete from sis_aju_d where no_aju='".$data['no_bukti']."' and kode_lokasi='".$data['kode_lokasi']."' ";
            array_push($exec,$del2);

            $del3 = "delete from apv_flow where no_bukti='".$data['no_bukti']."' and kode_lokasi='".$data['kode_lokasi']."' ";
            array_push($exec,$del3);
            
            $rs=executeArray($exec);
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
        }else{
                
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }