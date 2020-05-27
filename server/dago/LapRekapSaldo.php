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
            $message = Swift_Message::newInstance('Laporan MKU Operasional')
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
            
            $col_array = array('periode','no_paket','no_jadwal','no_reg','no_peserta');
            $db_col_name = array('a.periode','a.no_paket','a.no_jadwal','a.no_reg','a.no_peserta');
            $where = "";
            
            for($i = 0; $i<count($col_array); $i++){
                if(ISSET($_POST[$col_array[$i]]) && $_POST[$col_array[$i]] !=""){
                    $where .= " and ".$db_col_name[$i]." = '".$_POST[$col_array[$i]]."' ";
                }
            }

            $sql="select a.no_reg,b.id_peserta,a.no_peserta,b.nama as nama_peserta,a.no_paket,c.nama as nama_paket,d.nama as nama_room, convert(varchar,e.tgl_berangkat,103) tgl_berangkat,a.harga+a.harga_room-a.diskon as biaya_paket,isnull(f.nilai,0) as biaya_tambahan,isnull(g.nilai,0) as biaya_dok,isnull(h.bayar_paket,0) as bayar_paket,isnull(h.bayar_tambahan,0) as bayar_tambahan,isnull(h.bayar_dok,0) as bayar_dok, (a.harga+a.harga_room-a.diskon)-isnull(h.bayar_paket,0) as saldo_paket,isnull(f.nilai,0) - isnull(h.bayar_tambahan,0) as saldo_tambahan,isnull(g.nilai,0)-isnull(h.bayar_dok,0) as saldo_dok, (a.harga+a.harga_room-a.diskon)+isnull(f.nilai,0)+isnull(g.nilai,0) as tagihan,isnull(h.bayar_paket,0) + isnull(h.bayar_tambahan,0) + isnull(h.bayar_dok,0) as bayar,((a.harga+a.harga_room-a.diskon)-isnull(h.bayar_paket,0))+ (isnull(f.nilai,0) - isnull(h.bayar_tambahan,0)) + (isnull(g.nilai,0)-isnull(h.bayar_dok,0)) as saldo
            from dgw_reg a 
            inner join dgw_peserta b on a.no_peserta=b.no_peserta and a.kode_lokasi=b.kode_lokasi
            inner join dgw_paket c on a.no_paket=c.no_paket and a.kode_lokasi=c.kode_lokasi
            inner join dgw_typeroom d on a.no_type=d.no_type and a.kode_lokasi=d.kode_lokasi
            inner join dgw_jadwal e on a.no_jadwal=e.no_jadwal and a.kode_lokasi=e.kode_lokasi and a.no_paket=e.no_paket
            left join (select a.no_reg,a.kode_lokasi, sum(a.nilai) as nilai
                        from dgw_reg_biaya a
                        inner join dgw_biaya b on a.kode_biaya=b.kode_biaya and a.kode_lokasi=b.kode_lokasi
                        where b.jenis='TAMBAHAN'
                        group by a.no_reg,a.kode_lokasi
                        ) f on a.no_reg=f.no_reg and a.kode_lokasi=f.kode_lokasi
            left join (select a.no_reg,a.kode_lokasi, sum(a.nilai) as nilai
                        from dgw_reg_biaya a
                        inner join dgw_biaya b on a.kode_biaya=b.kode_biaya and a.kode_lokasi=b.kode_lokasi
                        where b.jenis='DOKUMEN'
                        group by a.no_reg,a.kode_lokasi
                        ) g on a.no_reg=g.no_reg and a.kode_lokasi=g.kode_lokasi
            left join (select a.no_reg,a.kode_lokasi, sum(a.nilai_p) as bayar_paket,sum(a.nilai_t) as bayar_tambahan,sum(a.nilai_m) as bayar_dok
                        from dgw_pembayaran a
                        group by a.no_reg,a.kode_lokasi
                        ) h on a.no_reg=h.no_reg and a.kode_lokasi=h.kode_lokasi
            where a.kode_lokasi='$kode_lokasi' $where 
            order by a.no_reg ";

            $resdata = dbResultArray($sql);

            $response["result"] = $resdata;
            $response["sql"] = $sql;
            if(isset($data['periode']) && $data['periode'] != ""){
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

    function getDetailTagihan(){
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){

            $_POST['no_reg']= $_POST['param'];
            $data = $_POST;
            $kode_lokasi=$_SESSION['lokasi'];
            
            $col_array = array('periode','no_paket','no_jadwal','no_reg','no_peserta');
            $db_col_name = array('c.periode','c.no_paket','c.no_jadwal','c.no_reg','c.no_peserta');
            $where = "";
            
            for($i = 0; $i<count($col_array); $i++){
                if(ISSET($_POST[$col_array[$i]]) && $_POST[$col_array[$i]] !=""){
                    $where .= " and ".$db_col_name[$i]." = '".$_POST[$col_array[$i]]."' ";
                }
            }

            $sql="select 'ROOM' as kode_biaya, c.harga_room as tarif, c.harga_room as nilai,1 as jml, 'ROOM' as nama,'-' as jenis
                from dgw_reg c 
                where c.kode_lokasi='$kode_lokasi' $where 
                union all
                select 'PAKET' as kode_biaya, c.harga-isnull(c.diskon,0) as tarif, c.harga-isnull(c.diskon,0) as nilai, 1 as jml, 'PAKET' as nama, '-' as jenis
                from dgw_reg c
                where c.kode_lokasi='$kode_lokasi' $where 
                union all
                select a.kode_biaya, a.tarif, a.nilai, a.jml, b.nama,b.jenis 
                from dgw_reg_biaya a 
                inner join dgw_biaya b on a.kode_biaya=b.kode_biaya and a.kode_lokasi=b.kode_lokasi
                inner join dgw_reg c on a.no_reg=c.no_reg and a.kode_lokasi=c.kode_lokasi 
                where a.kode_lokasi='$kode_lokasi' and b.jenis='TAMBAHAN' $where 
                union all
                select a.kode_biaya, a.tarif, a.nilai, a.jml, b.nama,b.jenis 
                from dgw_reg_biaya a 
                inner join dgw_biaya b on a.kode_biaya=b.kode_biaya and a.kode_lokasi=b.kode_lokasi 
                inner join dgw_reg c on a.no_reg=c.no_reg and a.kode_lokasi=c.kode_lokasi 
                where a.kode_lokasi='$kode_lokasi' and b.jenis='DOKUMEN' $where ";

            $resdata = dbResultArray($sql);

            $response["result"] = $resdata;
            $response["sql"] = $sql;
            if(isset($data['periode']) && $data['periode'] != ""){
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

    function getDetailPbyr(){
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){

            $_POST['no_reg']= $_POST['param'];
            $data = $_POST;
            $kode_lokasi=$_SESSION['lokasi'];
            
            $col_array = array('periode','no_paket','no_jadwal','no_reg','no_peserta');
            $db_col_name = array('c.periode','c.no_paket','c.no_jadwal','c.no_reg','c.no_peserta');
            $where = "";
            
            for($i = 0; $i<count($col_array); $i++){
                if(ISSET($_POST[$col_array[$i]]) && $_POST[$col_array[$i]] !=""){
                    $where .= " and ".$db_col_name[$i]." = '".$_POST[$col_array[$i]]."' ";
                }
            }

                    $sql="select 'ROOM' as kode_biaya, c.harga_room as tarif, c.harga_room as nilai,isnull(d.byr,0) as byr,
                    1 as jml, 'ROOM' as nama, '-' as jenis
                    from dgw_reg c
                    left join ( select a.no_reg,a.kode_lokasi,sum(nilai) as byr 
                                from dgw_pembayaran_d a 
                                where a.kode_biaya ='ROOM' and a.no_kwitansi <>'".$no_bukti."'
                                group by a.no_reg,a.kode_lokasi ) d on c.kode_lokasi=d.kode_lokasi 
                                and c.no_reg=d.no_reg 
                    where c.kode_lokasi='$kode_lokasi' $where 
                    union all
                    select 'PAKET' as kode_biaya, c.harga-isnull(c.diskon,0) as tarif, c.harga-isnull(c.diskon,0) as nilai,isnull(d.byr,0) as byr,1 as jml, 'PAKET' as nama, '-' as jenis
                    from dgw_reg c
                    left join ( select a.no_reg,a.kode_lokasi,sum(nilai) as byr 
                                from dgw_pembayaran_d a 
                                where a.kode_biaya = 'PAKET' and a.no_kwitansi <>'".$no_bukti."'
                                group by a.no_reg,a.kode_lokasi ) d on c.kode_lokasi=d.kode_lokasi 
                                and c.no_reg=d.no_reg 
                    where c.kode_lokasi='$kode_lokasi' $where
                    union all
                    select a.kode_biaya, a.tarif, a.nilai, isnull(d.byr,0) as byr,a.jml, b.nama, b.jenis
                    from dgw_reg_biaya a 
                    inner join dgw_biaya b on a.kode_biaya=b.kode_biaya and a.kode_lokasi=b.kode_lokasi 
                    inner join dgw_reg c on a.no_reg=c.no_reg and a.kode_lokasi=c.kode_lokasi 
                    left join ( select a.no_reg,a.kode_biaya,a.kode_lokasi,sum(nilai) as byr 
                                from dgw_pembayaran_d a 
                                group by a.no_reg,a.kode_biaya,a.kode_lokasi ) d on a.kode_biaya=d.kode_biaya and a.kode_lokasi=d.kode_lokasi 
                                and a.no_reg=d.no_reg 
                    where a.nilai <> 0 and a.kode_lokasi='$kode_lokasi' and b.jenis='TAMBAHAN' $where 
                    union all
                    select a.kode_biaya, a.tarif, a.nilai, isnull(d.byr,0) as byr,a.jml, b.nama, b.jenis
                    from dgw_reg_biaya a 
                    inner join dgw_biaya b on a.kode_biaya=b.kode_biaya and a.kode_lokasi=b.kode_lokasi 
                    inner join dgw_reg c on a.no_reg=c.no_reg and a.kode_lokasi=c.kode_lokasi 
                    left join ( select a.no_reg,a.kode_biaya,a.kode_lokasi,sum(nilai) as byr 
                                from dgw_pembayaran_d a 
                                group by a.no_reg,a.kode_biaya,a.kode_lokasi ) d on a.kode_biaya=d.kode_biaya and a.kode_lokasi=d.kode_lokasi 
                                and a.no_reg=d.no_reg 
                    where a.nilai <> 0 and a.kode_lokasi='$kode_lokasi' and b.jenis='DOKUMEN' $where ";

            $resdata = dbResultArray($sql);

            $response["result"] = $resdata;
            $response["sql"] = $sql;
            if(isset($data['periode']) && $data['periode'] != ""){
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

    function getDetailSaldo(){
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){

            $_POST['no_reg']= $_POST['param'];
            $data = $_POST;
            $kode_lokasi=$_SESSION['lokasi'];
            
            $col_array = array('periode','no_paket','no_jadwal','no_reg','no_peserta');
            $db_col_name = array('c.periode','c.no_paket','c.no_jadwal','c.no_reg','c.no_peserta');
            $where = "";
            
            for($i = 0; $i<count($col_array); $i++){
                if(ISSET($_POST[$col_array[$i]]) && $_POST[$col_array[$i]] !=""){
                    $where .= " and ".$db_col_name[$i]." = '".$_POST[$col_array[$i]]."' ";
                }
            }

                    $sql="select 'ROOM' as kode_biaya, c.harga_room as tarif, c.harga_room as nilai,isnull(d.byr,0) as byr,c.harga_room-isnull(d.byr,0) as saldo, 
                    1 as jml, 'ROOM' as nama, '-' as jenis
                    from dgw_reg c 
                    left join ( select a.no_reg,a.kode_lokasi,sum(nilai) as byr 
                                from dgw_pembayaran_d a 
                                where a.kode_biaya ='ROOM' and a.no_kwitansi <>'".$no_bukti."'
                                group by a.no_reg,a.kode_lokasi ) d on c.kode_lokasi=d.kode_lokasi 
                                and c.no_reg=d.no_reg 
                    where c.kode_lokasi='$kode_lokasi' $where 
                    union all
                    select 'PAKET' as kode_biaya, c.harga-isnull(c.diskon,0) as tarif, c.harga-isnull(c.diskon,0) as nilai,isnull(d.byr,0) as byr,c.harga-isnull(c.diskon,0)-isnull(d.byr,0) as saldo, 1 as jml, 'PAKET' as nama, '-' as jenis
                    from dgw_reg c
                    left join ( select a.no_reg,a.kode_lokasi,sum(nilai) as byr 
                                from dgw_pembayaran_d a 
                                where a.kode_biaya = 'PAKET' and a.no_kwitansi <>'".$no_bukti."'
                                group by a.no_reg,a.kode_lokasi ) d on c.kode_lokasi=d.kode_lokasi 
                                and c.no_reg=d.no_reg 
                    where c.kode_lokasi='$kode_lokasi' $where 
                    union all
                    select a.kode_biaya, a.tarif, a.nilai, isnull(d.byr,0) as byr,a.nilai-isnull(d.byr,0) as saldo,a.jml, b.nama, b.jenis 
                    from dgw_reg_biaya a 
                    inner join dgw_biaya b on a.kode_biaya=b.kode_biaya and a.kode_lokasi=b.kode_lokasi 
                    inner join dgw_reg c on a.no_reg=c.no_reg and a.kode_lokasi=c.kode_lokasi 
                    left join ( select a.no_reg,a.kode_biaya,a.kode_lokasi,sum(nilai) as byr 
                                from dgw_pembayaran_d a 
                                group by a.no_reg,a.kode_biaya,a.kode_lokasi ) d on a.kode_biaya=d.kode_biaya and a.kode_lokasi=d.kode_lokasi 
                                and a.no_reg=d.no_reg 
                    where a.nilai <> 0 and a.kode_lokasi='$kode_lokasi' and b.jenis='TAMBAHAN' $where 
                    union all
                    select a.kode_biaya, a.tarif, a.nilai, isnull(d.byr,0) as byr,a.nilai-isnull(d.byr,0) as saldo,a.jml, b.nama, b.jenis 
                    from dgw_reg_biaya a 
                    inner join dgw_biaya b on a.kode_biaya=b.kode_biaya and a.kode_lokasi=b.kode_lokasi 
                    inner join dgw_reg c on a.no_reg=c.no_reg and a.kode_lokasi=c.kode_lokasi 
                    left join ( select a.no_reg,a.kode_biaya,a.kode_lokasi,sum(nilai) as byr 
                                from dgw_pembayaran_d a 
                                group by a.no_reg,a.kode_biaya,a.kode_lokasi ) d on a.kode_biaya=d.kode_biaya and a.kode_lokasi=d.kode_lokasi 
                                and a.no_reg=d.no_reg 
                    where a.nilai <> 0 and a.kode_lokasi='$kode_lokasi' and b.jenis='DOKUMEN' $where ";

            $resdata = dbResultArray($sql);

            $response["result"] = $resdata;
            $response["sql"] = $sql;
            if(isset($data['periode']) && $data['periode'] != ""){
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

    function getPaket(){
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
            
            $sql="select a.no_paket,b.nama 
                from dgw_reg a 
                inner join dgw_paket b on a.no_paket=b.no_paket and a.kode_lokasi = b.kode_lokasi 
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

    function getJadwal(){
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

            if($data['paket'] == ""){
                $filter .= "";
            }else{
                $filter .= " and a.no_paket='".$data['paket']."' ";
            }
            
            $sql="select a.no_jadwal,b.tgl_berangkat 
            from dgw_reg a 
            inner join dgw_jadwal b on a.no_jadwal=b.no_jadwal and a.kode_lokasi=b.kode_lokasi and a.no_paket=b.no_paket 
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

    function getNoReg(){
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

            if($data['paket'] == ""){
                $filter .= "";
            }else{
                $filter .= " and a.no_paket='".$data['paket']."' ";
            }

            
            if($data['jadwal'] == ""){
                $filter .= "";
            }else{
                $filter .= " and a.no_jadwal='".$data['jadwal']."' ";
            }
            
            $sql="select a.no_reg from dgw_reg a where a.kode_lokasi='$kode_lokasi' $filter";
            $response["daftar"] = dbResultArray($sql);
            $response["status"] = true;        

        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }

    function getPeserta(){
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

            if($data['paket'] == ""){
                $filter .= "";
            }else{
                $filter .= " and a.no_paket='".$data['paket']."' ";
            }

            
            if($data['jadwal'] == ""){
                $filter .= "";
            }else{
                $filter .= " and a.no_jadwal='".$data['jadwal']."' ";
            }

            if($data['no_reg'] == ""){
                $filter .= "";
            }else{
                $filter .= " and a.no_reg='".$data['no_reg']."' ";
            }
            
            $sql="select a.no_peserta,b.nama 
            from dgw_reg a 
            inner join dgw_peserta b on a.no_peserta=b.no_peserta and a.kode_lokasi=b.kode_lokasi 
            where a.kode_lokasi='$kode_lokasi' $filter";
            $response["daftar"] = dbResultArray($sql);
            $response["status"] = true;      
            $response["sql"]=$sql;  

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