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
            $message = Swift_Message::newInstance('Laporan Form Registrasi')
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

    function getLap(){
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){

            $data = $_POST;
            $kode_lokasi=$_SESSION['lokasi'];

            if($data['back']){
                $back=true;
            }else{
                $back=false;
            }

            if(isset($data['no_bayar']) && $data['no_bayar'] != ""){
                $_POST['no_kwitansi']=$_POST['no_bayar'];
            }else{
                $_POST['no_kwitansi']=$_POST['no_kwitansi'];
            }
            
            $col_array = array('periode','no_kwitansi');
            $db_col_name = array('a.periode','a.no_kwitansi');
            $where = "";
            
            for($i = 0; $i<count($col_array); $i++){
                if(ISSET($_POST[$col_array[$i]]) && $_POST[$col_array[$i]] !=""){
                    $where .= " and ".$db_col_name[$i]." = '".$_POST[$col_array[$i]]."' ";
                }
            }
            
            // $sql="select count(a.no_kwitansi) as cicil,i.nilai1,a.kode_lokasi,a.no_kwitansi,a.kurs, c.nama as peserta,c.telp,CONVERT(varchar, a.tgl_bayar, 105) as tgl_bayar,a.jadwal,a.paket,a.sistem_bayar,a.nilai_t, 
            // d.nama as paket, b.no_agen,i.nik_user,e.nama_agen as agen,a.no_reg,d.kode_curr,(a.nilai_p*a.kurs)+a.nilai_t+a.nilai_m as biaya_paket, ((a.nilai_p*a.kurs)+a.nilai_t+a.nilai_m) - i.nilai1 as sisap,h.nama_marketing,b.harga+b.harga_room as harga_paket,j.nama as room,b.referal
            //         from dgw_pembayaran a 
            //         inner join dgw_reg b on a.no_reg=b.no_reg and a.kode_lokasi=b.kode_lokasi 				
            //         inner join dgw_peserta c on c.no_peserta=b.no_peserta and c.kode_lokasi=b.kode_lokasi 								
            //         inner join dgw_paket d on d.no_paket=b.no_paket and d.kode_lokasi=b.kode_lokasi 				
            //         inner join dgw_agent e on e.no_agen=b.no_agen and b.kode_lokasi=e.kode_lokasi 
            //         inner join dgw_marketing h on h.no_marketing=e.kode_marketing and h.kode_lokasi=e.kode_lokasi
            //         inner join dgw_typeroom j on b.no_type=j.no_type and b.kode_lokasi=j.kode_lokasi
            // inner join trans_m i on i.no_bukti=a.no_kwitansi and i.kode_lokasi=a.kode_lokasi
            // where a.kode_lokasi='$kode_lokasi' $where
            // group by b.no_agen,i.nilai1,a.kode_lokasi,a.no_kwitansi,a.kurs, c.nama,c.telp,a.tgl_bayar,a.jadwal,a.paket,a.bayar_paket,a.sistem_bayar,a.nilai_t, 
            //     d.nama, e.nama_agen,i.nik_user,a.no_reg,d.kode_curr,a.nilai_p,a.nilai_t,a.saldo_t,nilai_t,saldo_p ,a.bayar_tambahan, h.nama_marketing,b.harga,b.harga_room,j.nama,b.referal,a.nilai_m
            //     order by a.no_kwitansi
            // ";

            $sql ="select a.no_kwitansi, a.kurs,a.paket,b.no_type,c.nama as room,b.harga+b.harga_room-b.diskon as harga_paket,a.jadwal,h.nama_marketing,e.nama_agen,isnull(b.referal,'-') as referal,a.no_reg,i.biaya_tambah,j.paket+j.tambahan+j.dokumen as bayar_lain,n.cicil_ke as cicil_ke, (b.harga+b.harga_room-b.diskon)+i.biaya_tambah as biaya_paket,((b.harga+b.harga_room-b.diskon)+i.biaya_tambah)-(j.paket+j.tambahan+j.dokumen)+a.nilai_p+a.nilai_t+a.nilai_m as saldo, a.nilai_p+a.nilai_t+a.nilai_m as bayar,((b.harga+b.harga_room-b.diskon)+i.biaya_tambah)-(j.paket+j.tambahan+j.dokumen) as sisa,CONVERT(varchar, a.tgl_bayar, 105) as tgl_bayar,k.nama as peserta,l.kode_curr,m.nik_user,b.no_marketing
            from dgw_pembayaran a
            inner join dgw_reg b on a.no_reg=b.no_reg and a.kode_lokasi=b.kode_lokasi
            inner join dgw_typeroom c on b.no_type=c.no_type and b.kode_lokasi=c.kode_lokasi
            inner join dgw_agent e on b.no_agen=e.no_agen and b.kode_lokasi=e.kode_lokasi 
            inner join dgw_marketing h on b.no_marketing=h.no_marketing and b.kode_lokasi=h.kode_lokasi
            inner join dgw_peserta k on b.no_peserta=k.no_peserta and b.kode_lokasi=k.kode_lokasi 
            inner join dgw_paket l on b.no_paket=l.no_paket and b.kode_lokasi=l.kode_lokasi 
            inner join trans_m m on a.no_kwitansi=m.no_bukti and a.kode_lokasi=m.kode_lokasi				
            left join ( select no_reg,kode_lokasi,sum(jml*nilai) as biaya_tambah 
                        from dgw_reg_biaya 
                        group by no_reg,kode_lokasi ) i on b.no_reg=i.no_reg and b.kode_lokasi=i.kode_lokasi
            left join (select no_reg,kode_lokasi,isnull(sum(nilai_p),0) as paket, 
                        isnull(sum(nilai_t),0) as tambahan, isnull(sum(nilai_m),0) as dokumen
                        from dgw_pembayaran 
                        group by no_reg,kode_lokasi ) j on b.no_reg=j.no_reg and b.kode_lokasi=j.kode_lokasi
			left join (select no_reg,kode_lokasi,count(no_kwitansi) as cicil_ke
                        from dgw_pembayaran 
                        group by no_reg,kode_lokasi ) n on b.no_reg=n.no_reg and b.kode_lokasi=n.kode_lokasi
            where a.kode_lokasi='$kode_lokasi' $where ";

            $resdata = dbResultArray($sql);

            $response["result"] = $resdata;
            $response["sql"] = $sql;
            $response['where'] = $where;
            $response['post'] = $_POST;
            if(isset($data['periode'][1])){
                $response['periode'] = "Periode ".$data['periode'][1];
            }else{
                $response['periode'] = "Semua Periode";
            }
            
            $response["auth_status"] = 1;   
            $response['back']= $back;      

        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }


    function getPeriode2(){
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){

            $data = $_GET;
            $kode_lokasi=$_SESSION['lokasi'];
            
            $sql="select distinct periode from dgw_reg where kode_lokasi='$kode_lokasi' ";
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

    function getKwitansi(){
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
            
            $sql="select a.no_kwitansi,a.no_reg 
                from dgw_pembayaran a 
                where kode_lokasi='$kode_lokasi' $filter";
            $response["daftar"] = dbResultArray($sql);
            $response["status"] = true;        

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
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){

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
