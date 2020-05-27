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

    function getDataList(){
        session_start();
        getKoneksi();
        if($_COOKIE['isLogedIn'] AND cekAuth($_COOKIE['userLog'])){
            $requestData= $_REQUEST;

            switch($requestData["parameter"]){
                case 'no_bukti': 
                    $column_array = array('a.no_jual','a.keterangan');
                    $order_column = "ORDER BY a.no_jual ".$requestData['order'][0]['dir'];
                    $column_string = join(',', $column_array);
                    
                    if($requestData['order'][0]['column'] != 0){
                        $order_column = "ORDER BY ".$column_array[$requestData['order'][0]['column']]." ".$requestData['order'][0]['dir'];
                    }
                    
                    $sql = "select a.no_jual, a.keterangan from brg_jualpiu_dloc a 
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

                    case 'no_bukti': 
                        $nestedData[] = $row->no_jual;
                        $nestedData[] = $row->keterangan;
                    
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

    function getLapPNJ(){
        session_start();
        getKoneksi();
        if($_COOKIE['isLogedIn'] AND cekAuth($_COOKIE['userLog'])){

            $data = $_POST;
            
            $col_array = array('kasir','periode','no_bukti');
            $db_col_name = array('a.nik_user','a.periode','a.no_jual');
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
            
            $sql="select a.no_jual,convert(varchar,a.tanggal,103) as tanggal,a.keterangan,a.nilai,b.nilai as nilai2,b.kode_gudang,a.periode,a.nik_user,a.kode_pp,c.nama as nama_pp,d.nama as nama_user,a.diskon,a.tobyr
            from brg_jualpiu_dloc a 
            left join ( select no_bukti,kode_gudang,kode_lokasi,sum(case when dc='D' then -total else total end) as nilai
                        from brg_trans_dloc 
                        where kode_lokasi='".$data['kode_lokasi']."' and form='BRGJUAL'
                        group by no_bukti,kode_gudang,kode_lokasi
                        ) b on a.no_jual=b.no_bukti and a.kode_lokasi=b.kode_lokasi
            left join pp c on a.kode_pp=c.kode_pp 
            left join karyawan d on a.nik_user=d.nik and a.kode_lokasi=d.kode_lokasi
            where a.kode_lokasi='".$data['kode_lokasi']."' $where
            order by a.no_jual";

            $rs = execute($sql);
            
            $response['sql']=$sql;
            $resdata = array();
            while($row = $rs->FetchNextObject($toupper=false)){

                $resdata[]=(array)$row;
            }


            $sql2="select distinct a.no_bukti,a.kode_barang,b.nama as nama_brg,b.sat_kecil as satuan,a.jumlah,a.bonus,a.harga,a.diskon,(a.harga)*a.jumlah-a.diskon as total
            from brg_trans_dloc a
            inner join brg_barang b on a.kode_barang=b.kode_barang and a.kode_lokasi=b.kode_lokasi
            where a.kode_lokasi='".$data['kode_lokasi']."' 
            order by a.no_bukti";

            $rs2 = execute($sql2);
            $response['sql2']=$sql2;
            $resdata2 = array();
            while($row2 = $rs2->FetchNextObject($toupper=false)){

                $resdata2[]=(array)$row2;
            }

            $response["result2"] = $resdata2;
            $response["result"] = $resdata;
            $response['where'] = $where;
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

    function getNoBukti(){
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
            
            $sql="select a.no_jual,a.keterangan 
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

    function getLapPnj2(){
        session_start();
        getKoneksi();
        if($_COOKIE['isLogedIn'] AND cekAuth($_COOKIE['userLog'])){

            $data = $_POST;
            
            $col_array = array('periode','nik_kasir','no_bukti');
            $db_col_name = array('a.periode','a.nik_user','a.no_jual');
            $where = "";
            
            for($i = 0; $i<count($col_array); $i++){
                if(ISSET($_POST[$col_array[$i]]) && $_POST[$col_array[$i]] !=""){
                    $where .= " and ".$db_col_name[$i]." = '".$_POST[$col_array[$i]]."' ";
                }
            }
            
            $sql="select a.no_jual,convert(varchar,a.tanggal,103) as tanggal,a.keterangan,a.nilai,b.nilai as nilai2,b.kode_gudang,a.periode,a.nik_user,a.kode_pp,c.nama as nama_pp,d.nama as nama_user,e.nama as nama_gudang,a.nik_user as nik_kasir,a.tobyr,a.diskon
            from brg_jualpiu_dloc a 
            left join ( select no_bukti,kode_gudang,kode_lokasi,sum(case when dc='D' then -total else total end) as nilai
                        from brg_trans_dloc 
                        where kode_lokasi='".$data['kode_lokasi']."' and form='BRGJUAL'
                        group by no_bukti,kode_gudang,kode_lokasi
                        ) b on a.no_jual=b.no_bukti and a.kode_lokasi=b.kode_lokasi
            left join pp c on a.kode_pp=c.kode_pp 
            left join karyawan d on a.nik_user=d.nik and a.kode_lokasi=d.kode_lokasi
            left join brg_gudang e on b.kode_gudang=e.kode_gudang and b.kode_lokasi=e.kode_lokasi
            where a.kode_lokasi='".$data['kode_lokasi']."' $where
            order by a.no_jual";

            $rs = execute($sql);
            $resdata = array();
            while($row = $rs->FetchNextObject($toupper=false)){

                $resdata[]=(array)$row;
            }


            $sql2="select distinct a.no_bukti,a.kode_barang,b.nama as nama_brg,b.sat_kecil as satuan,a.jumlah,a.bonus,a.harga,a.diskon,(a.harga)*a.jumlah-a.diskon as total
            from brg_trans_dloc a
            inner join brg_barang b on a.kode_barang=b.kode_barang and a.kode_lokasi=b.kode_lokasi
            where a.kode_lokasi='".$data['kode_lokasi']."' 
            order by a.no_bukti";

            $rs2 = execute($sql2);
            $response['sql2']=$sql2;
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

    