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
            $message = Swift_Message::newInstance('Laporan Penjualan')
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

  
    function getPeriode2(){
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){

            $data = $_GET;
            $kode_lokasi=$_SESSION['lokasi'];
            
            $sql="select distinct periode from brg_jualpiu_dloc where kode_lokasi='$kode_lokasi' ";
            $response["daftar"] = dbResultArray($sql);
            $response["sql"]= $sql;
            $response["status"] = true;        

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

            $data = $_GET;
            $kode_lokasi=$_SESSION['lokasi'];
            if($data['periode'] == ""){
                $filter = "";
            }else{
                $filter = " and a.periode='".$data['periode']."' ";
            }
            
            $sql="select distinct a.nik_user,b.nama 
                from brg_jualpiu_dloc a 
                inner join karyawan b on a.nik_user=b.nik and a.kode_lokasi = b.kode_lokasi 
                where a.kode_lokasi='$kode_lokasi' $filter";
            $response["daftar"] = dbResultArray($sql);
            $response["status"] = true;        

        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }

    function getTanggal(){
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){

            $data = $_GET;
            $kode_lokasi=$_SESSION['lokasi'];
            if($data['periode'] == ""){
                $filter = "";
            }else{
                $filter = " and a.periode='".$data['periode']."' ";
            }

            if($data['nik_kasir'] == ""){
                $filter .= "";
            }else{
                $filter .= " and a.nik_user='".$data['nik_kasir']."' ";
            }
            
            $sql="select distinct a.tanggal 
            from brg_jualpiu_dloc a 
            where a.kode_lokasi='$kode_lokasi' $filter";
            $response["daftar"] = dbResultArray($sql);
            $response["status"] = true;        

        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }

    function getLap(){
        session_start();
        getKoneksi();
        if($_COOKIE['isLogedIn'] AND cekAuth($_COOKIE['userLog'])){

            $data = $_POST;
            
            $col_array = array('periode','nik_kasir','tanggal');
            $db_col_name = array('a.periode','a.nik_user','a.tanggal');
            $where = "";
            $kode_lokasi=$_POST['kode_lokasi'];
            
            for($i = 0; $i<count($col_array); $i++){
                if(ISSET($_POST[$col_array[$i]]) && $_POST[$col_array[$i]] !=""){
                    $where .= " and ".$db_col_name[$i]." = '".$_POST[$col_array[$i]]."' ";
                }
            }
            
            $sql="select a.tanggal,b.kode_gudang,a.periode,a.nik_user,a.kode_pp,c.nama as nama_pp,d.nama as nama_user,e.nama as nama_gudang,a.nik_user as nik_kasir,sum(a.nilai) as nilai
            from brg_jualpiu_dloc a 
            left join ( select no_bukti,kode_gudang,kode_lokasi,sum(case when dc='D' then -total else total end) as nilai
                        from brg_trans_dloc 
                        where kode_lokasi='$kode_lokasi' and form='BRGJUAL'
                        group by no_bukti,kode_gudang,kode_lokasi
                        ) b on a.no_jual=b.no_bukti and a.kode_lokasi=b.kode_lokasi
            left join pp c on a.kode_pp=c.kode_pp 
            left join karyawan d on a.nik_user=d.nik and a.kode_lokasi=d.kode_lokasi
            left join brg_gudang e on b.kode_gudang=e.kode_gudang and b.kode_lokasi=e.kode_lokasi
            where a.kode_lokasi='$kode_lokasi' $where
            group by a.tanggal,b.kode_gudang,a.periode,a.nik_user,a.kode_pp,c.nama,d.nama,e.nama,a.nik_user";

            $rs = execute($sql);
            $resdata = array();
            $tgl = "";
            $i=0;
            while($row = $rs->FetchNextObject($toupper=false)){

                $resdata[]=(array)$row;
                if($i == 0){
                    $tgl .= "'$row->tanggal'";
                }else{

                    $tgl .= ","."'$row->tanggal'";
                }
                $i++;
            }


            $sql2="select c.tanggal,a.kode_barang,b.nama as nama_brg,b.sat_kecil as satuan,sum(a.jumlah) as jumlah,sum(a.bonus) as bonus,a.harga,sum(a.diskon) as diskon,sum((a.harga*a.jumlah)-a.diskon) as total
            from brg_trans_dloc a
            inner join brg_barang b on a.kode_barang=b.kode_barang and a.kode_lokasi=b.kode_lokasi
			inner join brg_jualpiu_dloc c on a.no_bukti=c.no_jual and a.kode_lokasi=c.kode_lokasi
            where a.kode_lokasi='$kode_lokasi' and c.tanggal in ($tgl)
			group by c.tanggal,a.kode_barang,b.nama,b.sat_kecil,a.harga
            order by c.tanggal ";

            $rs2 = execute($sql2);
            $resdata2 = array();
            while($row2 = $rs2->FetchNextObject($toupper=false)){

                $resdata2[]=(array)$row2;
            }

            $response["result2"] = $resdata2;
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

    