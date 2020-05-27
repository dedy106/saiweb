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

    
    function register(){
        getKoneksi();
        $dbconn = db_Connect();

        $db_token["nik"] = $_POST["nik"];
        $db_token["api_key"] = random_string('alnum', 20);
        $db_token["token"] = $_POST["token"];
        $db_token["kode_lokasi"] = $_POST["kode_lokasi"];

        $db_token["os"] = 'BROWSER';
        $db_token["ver"] = '';
        $db_token["model"] = '';
        $db_token["uuid"] = '';

        $db_token["tgl_login"] = date('Y-m-d H:i:s');

        $res = dbResultArray("select nik,kode_lokasi from api_token_auth where nik='".$_POST['nik']."' and kode_lokasi='".$_POST['kode_lokasi']."' and token='".$_POST['token']."' ");
        $exec=array();

        if(count($res)>0){
            $response['message'] = 'Already registered';
        }else{
            $token_sql = $dbconn->AutoExecute('api_token_auth', $db_token, 'INSERT');
            if($token_sql){
                $response['message'] = "ID registered";
            }else{
                $response['message'] = "Failed to register";
            }
        }

        echo json_encode($response);
    }

    
    function kirim_email(){
        
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
        $body_text="Testing send aju";  
        // Create a message
        $message = Swift_Message::newInstance('Pengajuan Justifikasi Kebutuhan')
        ->setFrom(array('devptsai@gmail.com' => 'Pt. Samudera Aplikasi Indonesia'))
        ->setTo(array($_POST['email'] => $_POST['nama1']))
        ->setBody($body_text)
        ;
        // Send the message
        $result = $mailer->send($message);
        if($result){
            $response["message"] = "Email berhasil dikirim";
            $response["status"] = true;
        }else{
            $response["message"] = "Email gagal dikirim";
            $response["status"] = false;
        }
        
        echo json_encode($response);
    }

    function sendNotif(){

        getKoneksi();
        $res = dbRowArray("select token from api_token_auth where nik='102' and kode_lokasi='99' ");

        $title = "Pengajuan Justifikasi Kebutuhan";
        $content      = array(
            "en" => "Pengajuan Justifikasi kebutuhan APV-0006 berhasil dikirim, menunggu approval "
        );
        
        $fields = array(
            'app_id' => "17d5726f-3bc0-4e97-8567-8ad802ccb9ff", //appid saiweb
            
            // // per token id
            'include_player_ids' => array($res['token']),
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
            $response["status"]= true;
        }else{
            $response["status"]= false;
        }
        echo json_encode($response);

    }

    function sendWA(){

        $fields = array(
            'phone_no' => $_POST['no'],
            'message' => $_POST['pesan'],
            'key'=> 'cd89a8c837380f06ed5f8bbf3a3759b3becd577cc5979586'
        );
        
        $fields = json_encode($fields);
         
        // Prepare new cURL resource
        $ch = curl_init('http://send.woonotif.com/api/send_message');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
         
        // Set HTTP Header for POST request 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($fields))
        );
         
        // Submit the POST request
        $response["sendWA"] = curl_exec($ch);
         
        // Close cURL session handle
        curl_close($ch);
        echo json_encode($response);
    }

    function getNotif(){

        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $sqlnotif = "select distinct title,isi,nik,kode_lokasi,convert(varchar,tgl_input,103) as tanggal from apv_notif_m  where kode_lokasi='".$_SESSION['lokasi']."' and nik='".$_SESSION['userLog']."' ";
            $response["data"] = dbResultArray($sqlnotif);
            $response["status"] = true;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        echo json_encode($response);

    }


?>