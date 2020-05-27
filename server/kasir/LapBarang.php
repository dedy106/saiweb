<?php
    $request_method=$_SERVER["REQUEST_METHOD"];

    switch($request_method) {
        case 'GET':
            if(isset($_GET["fx"]) AND function_exists($_GET['fx'])){
                $_GET['fx']();
            }
        break;
        case 'POST':
            if(isset($_GET["fx"]) AND function_exists($_GET['fx'])){
                $_GET['fx']();
            }
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
		include_once($root_lib."lib/koneksi.php");
        include_once($root_lib."lib/helpers.php");
    }

    function cekAuth($user){
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
            $message = Swift_Message::newInstance('Laporan Data Barang')
            ->setFrom(array('devptsai@gmail.com' => 'Pt. Samudera Aplikasi Indonesia'))
            ->setTo(array($email=> $user))
            ->setBody($body_text,"text/html")
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

    function getDataList(){
        session_start();
        getKoneksi();
        if($_COOKIE['isLogedIn'] AND cekAuth($_COOKIE['userLog'])){
            $requestData= $_REQUEST;

            switch($requestData["parameter"]){
                case 'kode_barang': 
                    $column_array = array('a.kode_barang','a.nama');
                    $order_column = "ORDER BY a.kode_barang ".$requestData['order'][0]['dir'];
                    $column_string = join(',', $column_array);
                    
                    if($requestData['order'][0]['column'] != 0){
                        $order_column = "ORDER BY ".$column_array[$requestData['order'][0]['column']]." ".$requestData['order'][0]['dir'];
                    }
                    
                    $sql = "select a.kode_barang, a.nama from brg_barang a 
                    where a.kode_lokasi = '".$requestData['kode_lokasi']."' and a.periode='".$requestData['periode']."' ";
                break;
                case 'kasir': 
                    $column_array = array('a.nik_user','b.nama');
                    $order_column = "ORDER BY a.nik_user ".$requestData['order'][0]['dir'];
                    $column_string = join(',', $column_array);
                    
                    if($requestData['order'][0]['column'] != 0){
                        $order_column = "ORDER BY ".$column_array[$requestData['order'][0]['column']]." ".$requestData['order'][0]['dir'];
                    }
                    
                    $sql = "select distinct a.nik_user, b.nama from brg_jualpiu_dloc a 
                    left join karyawan b on a.nik_user=b.nik and a.kode_lokasi=b.kode_lokasi
                    where a.kode_lokasi = '".$requestData['kode_lokasi']."' and a.periode='".$requestData['periode']."'";
                break;
            }
            
            $sql2 = $sql;
            
            $rs=execute($sql);
            
            $jml_baris = $rs->RecordCount();
            
            if(empty($requestData['search']['value'])){
              
                $sql.="$order_column OFFSET ".$requestData['start']." ROWS FETCH NEXT $jml_baris ROWS ONLY ";
                $jml_baris_filtered = $jml_baris;
            }else{
                $search = $requestData['search']['value'];
                $filter_string = " and (";
                
                for($i=0; $i<count($column_array); $i++){
                   
                    if($i == (count($column_array) - 1)){
                        $filter_string .= $column_array[$i]." like '".$search."%' )";
                    }else{
                        $filter_string .= $column_array[$i]." like '".$search."%' or ";
                    }
                }
                $sql.=" $filter_string $order_column  OFFSET ".$requestData['start']." ROWS FETCH NEXT $jml_baris ROWS ONLY ";

                $sql2 .= "$filter_string $order_column ";

                $rs2= execute($sql2);
                $jml_baris_filtered = $rs2->RecordCount();
            }
            
            if($jml_baris > 0){
                $arr1 = execute($sql);
                // $arr1=$dbLib->LimitQuery($sql,25,$requestData['start']);	
            }else{
                $arr1 = array();
            }
            
            
            while ($row = $arr1->FetchNextObject($toupper=false))
            {
                $nestedData=array(); 
                
                switch($requestData["parameter"]){

                    case 'kode_barang': 
                        $nestedData[] = $row->kode_barang;
                        $nestedData[] = $row->nama;
                    
                    break;
                    case 'kasir': 
                        $nestedData[] = $row->nik_user;
                        $nestedData[] = $row->nama;
                    break;
                }
                
                $data[] = $nestedData;
            }
            
            $response = array(
                "draw" => $requestData['draw'],
                "recordsTotal" => $jml_baris,
                "recordsFiltered" => $jml_baris_filtered,
                "data_list" => $data,
            );
            
            $response["status"] = true;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }

    function getLapBarang(){
        session_start();
        getKoneksi();
        if($_COOKIE['isLogedIn'] AND cekAuth($_COOKIE['userLog'])){

            $data = $_POST;
            
            $col_array = array('kode_barang');
            $db_col_name = array('a.kode_barang');
            $where = "";
            
            for($i = 0; $i<count($col_array); $i++){
                if(ISSET($_POST[$col_array[$i]][0])){
                    if($_POST[$col_array[$i]][0] == "range" AND ISSET($_POST[$col_array[$i]][1]) AND ISSET($_POST[$col_array[$i]][2])){
                        $where .= " and (".$db_col_name[$i]." between '".$_POST[$col_array[$i]][1]."' AND '".$_POST[$col_array[$i]][2]."') ";
                    }else if($_POST[$col_array[$i]][0] == "exact" AND ISSET($_POST[$col_array[$i]][1])){
                        $where .= " and ".$db_col_name[$i]." = '".$_POST[$col_array[$i]][1]."' ";
                    }
                }
            }
            
            $sql=" select a.kode_barang,a.nama,a.sat_kecil as satuan,a.hna as harga,a.hrg_satuan,a.ppn,a.profit,a.barcode,a.kode_klp
            from brg_barang a
            where a.kode_lokasi='".$data['kode_lokasi']."' $where ";

            $rs = execute($sql);
            $resdata = array();
            while($row = $rs->FetchNextObject($toupper=false)){

                $resdata[]=(array)$row;
            }

            $response["result"] = $resdata;
            $response['sql'] = $sql;
            if(isset($data['periode'][1])){
                $response['periode'] = "Periode ".$data['periode'][1];
            }else{
                $response['periode'] = "Semua Periode";
            }
            
            $response["auth_status"] = 1;        

        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }

    function getKodeBarang(){
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){

            $data = $_GET;
            $kode_lokasi=$_SESSION['lokasi'];

            $sql="select a.kode_barang,a.nama
            from brg_barang a 
            where a.kode_lokasi='$kode_lokasi'";
            $response["daftar"] = dbResultArray($sql);
            $response["status"] = true;        

        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }

    function getLapBarang2(){
        session_start();
        getKoneksi();
        if($_COOKIE['isLogedIn'] AND cekAuth($_COOKIE['userLog'])){

            $data = $_POST;
            
            $col_array = array('kode_barang');
            $db_col_name = array('a.kode_barang');
            $where = "";
            
            for($i = 0; $i<count($col_array); $i++){
                if(ISSET($_POST[$col_array[$i]]) && $_POST[$col_array[$i]] !=""){
                    $where .= " and ".$db_col_name[$i]." = '".$_POST[$col_array[$i]]."' ";
                }
            }
            
            $sql=" select a.kode_barang,a.nama,a.sat_kecil as satuan,a.hna as harga,a.hrg_satuan,a.ppn,a.profit,a.barcode,a.kode_klp
            from brg_barang a
            where a.kode_lokasi='".$data['kode_lokasi']."' $where ";

            $rs = execute($sql);
            $resdata = array();
            while($row = $rs->FetchNextObject($toupper=false)){

                $resdata[]=(array)$row;
            }

            $response["result"] = $resdata;
            $response['where'] = $where;
            if(isset($data['periode'][1])){
                $response['periode'] = "Periode ".$data['periode'];
            }else{
                $response['periode'] = "Semua Periode";
            }
            
            $response["auth_status"] = 1;        

        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }

    function sendEmail(){
        session_start();
        getKoneksi();
        if($_COOKIE['isLogedIn'] AND cekAuth($_COOKIE['userLog'])){

            $data = $_POST;
            $email = $_POST['email'];
            $nama = $_POST['nama'];
            
            $root=$_SERVER['REQUEST_SCHEME']."://".$_SERVER['SERVER_NAME'];
            $folder_css=$root."/vendor/asset_elite/dist/css";
            $html = $_POST['html'];
            $body_text = "
            <!DOCTYPE html>
            <html lang='en'>
            <head>
            <meta charset='utf-8'>
            <meta http-equiv='X-UA-Compatible' content='IE=edge'>
            <meta name='viewport' content='width=device-width, initial-scale=1'>
            <meta name='description' content=''>
            <meta name='author' content=''>
            <link rel='icon' type='image/png' sizes='16x16' href='$folder_assets/images/sai_icon/saku.png'>
            <title>SAKU | Sistem Akuntansi Keuangan Digital</title>
            <link href='$folder_css/style.min.css' rel='stylesheet'>
            <link href='$folder_css/pages/dashboard1.css' rel='stylesheet'>
            <link href='$folder_css/sai.css' rel='stylesheet'>'
            <body>
            $html
            </body>
            </html>";

            $rs=kirim_email($email,$nama,$body_text);
            if($rs){
                $tmp.=". Email ke terkirim";
            }else{
                $tmp.=". Email ke gagal terkirim";
            }
            $response["status"] = true;
            $response["message"] = $tmp;

        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }
