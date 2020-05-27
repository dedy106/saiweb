<?php
    // if(function_exists($_GET['fx'])) {
    //     $_GET['fx']();
    // }

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
    }

    function getKoneksi(){
        $root_lib=$_SERVER["DOCUMENT_ROOT"];
        include_once($root_lib."lib/koneksi.php");
        include_once($root_lib."lib/helpers.php");
        require_once($root_lib.'lib/jwt.php');
    }

    function generateToken(){
        getKoneksi();
        $data = $_POST;
        if(arrayKeyCheck(array('nik','pass'), $data)){

            $nik=$data['nik'];
            $pass=$data['pass'];
            
            $response = array("message" => "", "status" => "" );
            $exec=array();
            
            $nik = '';
            $pass = '';
        
            if (isset($_POST['nik'])) {$nik = $_POST['nik'];}
            if (isset($_POST['pass'])) {$pass = $_POST['pass'];}

            $sql="select a.kode_klp_menu, a.nik, a.nama, a.pass, a.status_admin, a.klp_akses, a.kode_lokasi,b.nama as nmlok, c.kode_pp,d.nama as nama_pp,
			b.kode_lokkonsol,d.kode_bidang, c.foto,isnull(e.form,'-') as path_view,b.logo
            from hakakses a 
            inner join lokasi b on b.kode_lokasi = a.kode_lokasi 
            left join karyawan c on a.nik=c.nik and a.kode_lokasi=c.kode_lokasi 
            left join pp d on c.kode_pp=d.kode_pp and c.kode_lokasi=d.kode_lokasi 
            left join m_form e on a.path_view=e.kode_form 
            where a.nik= '$nik' and a.pass='$pass' ";
            $rs=execute($sql,$error);
        
            $row = $rs->FetchNextObject(false);
            if($rs->RecordCount() > 0){
                
                $userId = $nik;

                // $sql2 = "select server_key from api_server_key where kode_lokasi='$row->kode_lokasi' and  modul='SISWA' ";
                // $rs2=execute($sql2);
                // if($rs2->RecordCount()>0){
                //     $serverKey = $rs2->fields[0];
                    // create a token
                    $serverKey= "bccf9112d48a8aa444dd73e762cf263c";
                    $payloadArray = array();
                    $payloadArray['userId'] = $userId;
                    if (isset($nbf)) {$payloadArray['nbf'] = $nbf;}
                    if (isset($exp)) {$payloadArray['exp'] = $exp;}
                    $token = JWT::encode($payloadArray, $serverKey);
                    // return to caller
                    $response = array('token' => $token,'status'=>true,'message'=>'Login Success');


                // }else{
                //     $response = array('status'=>false,'message'=>'Server key does not exist','sql'=>$sql2);
                // }
    
            } 
            else {
                $response = array('message' => 'Invalid user ID or password.','status'=>false);
               
            }

        }else{
            $response = array('message' => "Kode Lokasi, Modul, and NIK required",'status'=>false);
        }
        
        echo json_encode($response);

    }

    function authKey($token, $modul=null,$kode_lokasi=null){
        getKoneksi();
        $token = $token;
        // $modul = qstr($modul);
        // $kode_lokasi = qstr($kode_lokasi);
        $date = date('Y-m-d H:i:s');

        $schema = db_Connect();
        // $sql = "SELECT server_key FROM api_server_key where modul=$modul and kode_lokasi=$kode_lokasi ";
        // $auth = execute($sql);
        // if($auth->RecordCount() > 0){

           
            // $serverKey = $auth->fields[0];
            $serverKey = "bccf9112d48a8aa444dd73e762cf263c";

            try {
                $payload = JWT::decode($token, $serverKey, array('HS256'));
                if(isset($payload->userId)  || $payload->userId != ''){

                    if (isset($payload->exp)) {
                        $returnArray['exp'] = date(DateTime::ISO8601, $payload->exp);;
                    }
                    $returnArray['status'] = true;
                }

            }
            catch(Exception $e) {
                $returnArray = array('message' => $e->getMessage(),'status'=>false);
            }
        // }else{
        //     $returnArray = array('message' => 'serverKey does not exist','status'=>false);
        // }
        return $returnArray;
    }

    function getGedung(){
        getKoneksi();
        $data = $_POST;
        $kode_lokasi=$_POST['kode_lokasi'];
        $header = getallheaders();
        $token = $header["Authorization"];
		$res = authKey($token); 
        $res = authKey($data["token"]); // HAPUS JIKA INGIN PAKAI HEADER
        if($res["status"]){ 
        
            $response = array("message" => "", "rows" => 0, "status" => "" );
            $sql="SELECT a.id_gedung,a.kode_lokasi,a.nama_gedung,isnull(b.jumlah,0) as jumlah,isnull(b.nilai_perolehan,0) as nilai_perolehan
            from amu_gedung a
            left join (select b.id_gedung,a.kode_lokasi,a.kode_pp,count(a.no_bukti) as jumlah,sum(nilai_perolehan) as nilai_perolehan
                    from amu_asset_bergerak a
                    inner join amu_ruangan b on a.no_ruang=b.no_ruangan and a.kode_lokasi=b.kode_lokasi and a.kode_pp=b.kode_pp
                    where a.kode_lokasi='99' and a.kode_pp='yspte05'
                    group by b.id_gedung,a.kode_lokasi,a.kode_pp
                    )b on a.id_gedung=b.id_gedung and a.kode_lokasi=b.kode_lokasi and a.kode_pp=b.kode_pp
            where a.kode_lokasi='99' and a.kode_pp='yspte05' and isnull(b.jumlah,0)>0 
            order by a.id_gedung";
            
            $rs = execute($sql);					
                        
            $response['daftar']=array();
            while ($row = $rs->FetchNextObject(false)){
                $response['daftar'][] = (array)$row;
            }
            $response['status']=TRUE;
            $response['sql']=$sql;
        }else{
            $response['status'] = false;
            $response['message'] = "Unauthorized Access ".$res["message"];
            
        }
        echo json_encode($response);
    }

    function getRuangan(){
        getKoneksi();
        $data = $_POST;
        $kode_lokasi=$_POST['kode_lokasi'];
        $id_gedung=$data['id_gedung'];
        $header = getallheaders();
        $token = $header["Authorization"];
		$res = authKey($token); 
        $res = authKey($data["token"]); // HAPUS JIKA INGIN PAKAI HEADER
        if($res["status"]){ 
        
            $response = array("message" => "", "rows" => 0, "status" => "" );
            $sql="SELECT a.no_ruangan,a.kode_lokasi,a.nama_ruangan,isnull(b.jumlah,0) as jumlah,isnull(b.nilai_perolehan,0) as nilai_perolehan
            from amu_ruangan a
            left join (select a.no_ruang,a.kode_lokasi,count(a.no_bukti) as jumlah,sum(nilai_perolehan) as nilai_perolehan
                    from amu_asset_bergerak a
                    where a.kode_lokasi='99' and a.kode_pp='yspte05'
                    group by a.no_ruang,a.kode_lokasi
                    )b on a.no_ruangan=b.no_ruang and a.kode_lokasi=b.kode_lokasi
            where a.kode_lokasi='99' and a.kode_pp='yspte05' and isnull(b.jumlah,0)>0 AND a.id_gedung='$id_gedung'
            order by a.no_ruangan";
            
            $rs = execute($sql);					
                        
            $response['daftar']=array();
            while ($row = $rs->FetchNextObject(false)){
                $response['daftar'][] = (array)$row;
            }
            $response['status']=TRUE;
            $response['sql']=$sql;
        }else{
            $response['status'] = false;
            $response['message'] = "Unauthorized Access ".$res["message"];
            
        }
        echo json_encode($response);
    }

    function getBarang(){
        getKoneksi();
        $data = $_POST;
        $kode_lokasi=$_POST['kode_lokasi'];
        $id_ruangan=$_POST['id_ruangan'];
        $header = getallheaders();
        $token = $header["Authorization"];
		$res = authKey($token); 
        $res = authKey($data["token"]); // HAPUS JIKA INGIN PAKAI HEADER
        if($res["status"]){ 
            
            $response = array("message" => "", "rows" => 0, "status" => "" );
            $sql="SELECT a.kode_klp,a.kode_lokasi,a.nama_klp,isnull(b.jumlah,0) as jumlah,isnull(b.nilai_perolehan,0) as nilai_perolehan
            from amu_klp_brg a
            left join (select a.kode_klp,a.kode_lokasi,count(a.no_bukti) as jumlah,sum(nilai_perolehan) as nilai_perolehan
                    from amu_asset_bergerak a
                    where a.kode_lokasi='99' and a.kode_pp='yspte05' AND no_ruang='$id_ruangan'
                    group by a.kode_klp,a.kode_lokasi
                    )b on a.kode_klp=b.kode_klp and a.kode_lokasi=b.kode_lokasi
            where a.kode_lokasi='99'  and isnull(b.jumlah,0)>0
            order by a.kode_klp ";
            
            $rs = execute($sql);					
                        
            $response['daftar']=array();
            while ($row = $rs->FetchNextObject(false)){
                $response['daftar'][] = (array)$row;
            }
            $response['status']=TRUE;
            $response['sql']=$sql;
        }else{
            $response['status'] = false;
            $response['message'] = "Unauthorized Access ".$res["message"];
            
        }
        echo json_encode($response);
    }
    
    function getDaftarPengajuan(){
        getKoneksi();
        $data = $_POST;
        $kode_lokasi=$_POST['kode_lokasi'];
        $header = getallheaders();
        $token = $header["Authorization"];
		$res = authKey($token); 
        $res = authKey($data["token"]); // HAPUS JIKA INGIN PAKAI HEADER
        if($res["status"]){ 
        
            $response = array("message" => "", "rows" => 0, "status" => "" );
            $sql="SELECT a.gambar, a.kondisi, a.kode, a.nama, a.status FROM namatable WHERE kode_lokasi='$kode_lokasi'";
            $sql="SELECT no_bukti
            ,barcode
            ,no_seri
            ,merk
            ,tipe
            ,warna
            ,satuan
            ,spesifikasi
            ,id_gedung
            ,no_ruang
            ,kode_klp
            ,tanggal_perolehan
            ,kode_lokasi
            ,kode_pp
            ,nilai_perolehan
            ,kd_asset
            ,sumber_dana
            ,nama_inv as nama
            ,foto FROM amu_asset_bergerak a WHERE a.id_gedung='$id_gedung' AND a.id_ruangan='$id_ruangan' AND a.kode_klp='$kode_klp'";
            $rs = execute($sql);					
                        
            $response['daftar']=array();
            while ($row = $rs->FetchNextObject(false)){
                $response['daftar'][] = (array)$row;
            }
            $response['status']=TRUE;
            $response['sql']=$sql;
        }else{
            $response['status'] = false;
            $response['message'] = "Unauthorized Access ".$res["message"];
            
        }
        echo json_encode($response);
    }

    function getDetailBarang(){
        getKoneksi();
        $data = $_GET;
        $header = getallheaders();
        $token = $header["Authorization"];
		$res = authKey($token); 
        $res = authKey($data["token"]); // HAPUS JIKA INGIN PAKAI HEADER
        if($res["status"]){ 
            $id_gedung=$data['id_gedung'];
            $id_ruangan=$data['id_ruangan'];
            $id_nama=$data['id_nama'];
            $kode_klp=$data['id_barang'];
            $kode_lokasi=$_GET['kode_lokasi'];
            $qrcode=$_GET['qrcode'];
            
            $response = array("message" => "", "rows" => 0, "status" => "" );
            // $sql="SELECT a.nama_inv AS nama,a.kd_asset, a.merk, a.warna, a.no_ruang FROM amu_asset_bergerak a WHERE a.no_bukti='$qrcode'";
            $sql="SELECT no_bukti
            ,barcode
            ,no_seri
            ,merk
            ,tipe
            ,warna
            ,satuan
            ,spesifikasi
            ,id_gedung
            ,no_ruang
            ,kode_klp
            ,tanggal_perolehan
            ,kode_lokasi
            ,kode_pp
            ,nilai_perolehan
            ,kd_asset
            ,sumber_dana
            ,nama_inv as nama
            ,foto FROM amu_asset_bergerak a WHERE a.id_gedung='$id_gedung' AND a.no_ruang='$id_ruangan' AND a.kode_klp='$kode_klp' AND a.no_bukti='$id_nama'";
            
            // no_bukti
            $rs = execute($sql);					
            $response['daftar']=array();
            while ($row = $rs->FetchNextObject(false)){
                $response['daftar'][] = (array)$row;
            }
            $response['status']=TRUE;
            $response['sql']=$sql;
        }else{
            $response['status'] = false;
            $response['message'] = "Unauthorized Access ".$res["message"];
            
        }
        echo json_encode($response);
    }

    function getDaftarBarang(){
        getKoneksi();
        $data = $_GET;
        $header = getallheaders();
        $token = $header["Authorization"];
		$res = authKey($token); 
        $res = authKey($data["token"]); // HAPUS JIKA INGIN PAKAI HEADER
        if($res["status"]){ 
            $id_gedung=$data['id_gedung'];
            $id_ruangan=$data['id_ruangan'];
            $kode_klp=$data['id_barang'];
            // $kode_lokasi=$_GET['kode_lokasi'];
            // $qrcode=$_GET['qrcode'];
            
            $response = array("message" => "", "rows" => 0, "status" => "" );
            // $sql="SELECT a.nama_inv AS nama,a.kd_asset, a.merk, a.warna, a.no_ruang FROM amu_asset_bergerak a WHERE a.no_bukti='$qrcode'";
            $sql="SELECT no_bukti
            ,barcode
            ,no_seri
            ,merk
            ,tipe
            ,warna
            ,satuan
            ,spesifikasi
            ,id_gedung
            ,no_ruang
            ,kode_klp
            ,tanggal_perolehan
            ,kode_lokasi
            ,kode_pp
            ,nilai_perolehan
            ,kd_asset
            ,sumber_dana
            ,nama_inv as nama
            ,foto FROM amu_asset_bergerak a WHERE a.id_gedung='$id_gedung' AND a.no_ruang='$id_ruangan' AND a.kode_klp='$kode_klp'";
            
            // no_bukti
            $rs = execute($sql);					
            $response['daftar']=array();
            while ($row = $rs->FetchNextObject(false)){
                $response['daftar'][] = (array)$row;
            }
            $response['status']=TRUE;
            $response['sql']=$sql;
        }else{
            $response['status'] = false;
            $response['message'] = "Unauthorized Access ".$res["message"];
            
        }
        echo json_encode($response);
    }

    function getDataAset(){
        getKoneksi();
        $data = $_GET;
        $header = getallheaders();
        $token = $header["Authorization"];
		$res = authKey($token); 
        $res = authKey($data["token"]); // HAPUS JIKA INGIN PAKAI HEADER
        if($res["status"]){ 
            $kode_lokasi=$_GET['kode_lokasi'];
            $qrcode=$_GET['qrcode'];
            
            $response = array("message" => "", "rows" => 0, "status" => "" );
            // $sql="SELECT a.nama_inv AS nama,a.kd_asset, a.merk, a.warna, a.no_ruang FROM amu_asset_bergerak a WHERE a.no_bukti='$qrcode'";
            $sql="SELECT no_bukti
            ,barcode
            ,no_seri
            ,merk
            ,tipe
            ,warna
            ,satuan
            ,spesifikasi
            ,id_gedung
            ,no_ruang
            ,kode_klp
            ,tanggal_perolehan
            ,kode_lokasi
            ,kode_pp
            ,nilai_perolehan
            ,kd_asset
            ,sumber_dana
            ,nama_inv as nama
            ,foto FROM amu_asset_bergerak a WHERE a.no_bukti='$qrcode'";
            
            // no_bukti
            $rs = execute($sql);					
            $response['daftar']=array();
            while ($row = $rs->FetchNextObject(false)){
                $response['daftar'][] = (array)$row;
            }
            $response['status']=TRUE;
            $response['sql']=$sql;
        }else{
            $response['status'] = false;
            $response['message'] = "Unauthorized Access ".$res["message"];
            
        }
        echo json_encode($response);
    }

    function getPerbaikan(){
        getKoneksi();
        $data = $_GET;
        $header = getallheaders();
        $token = $header["Authorization"];
		$res = authKey($token); 
        $res = authKey($data["token"]); // HAPUS JIKA INGIN PAKAI HEADER
        if($res["status"]){ 
            $kode_lokasi=$data['kode_lokasi'];
            
            $response = array("message" => "", "rows" => 0, "status" => "" );
            $sql="select a.mon_id,a.kd_asset,a.id_gedung,a.no_ruangan, case a.status when 'Berfungsi' then 'Baik' else 'Rusak' end as kondisi,a.tgl_input from amu_mon_asset_bergerak a where a.status='Tidak Berfungsi' and a.kode_lokasi='$kode_lokasi' ";				
            $response['daftar']=dbResultArray($sql);
            $response['status']=TRUE;
        }else{
            $response['status'] = false;
            $response['message'] = "Unauthorized Access ".$res["message"];
            
        }
        echo json_encode($response);
    }

    function getDetailPerbaikan(){
        getKoneksi();
        $data = $_GET;
        $header = getallheaders();
        $token = $header["Authorization"];
		$res = authKey($token); 
        $res = authKey($data["token"]); // HAPUS JIKA INGIN PAKAI HEADER
        if($res["status"]){ 
            $kode_lokasi=$data['kode_lokasi'];
            $id=$data['mon_id'];
            
            $response = array("message" => "", "rows" => 0, "status" => "" );
            $sql="select b.mon_id,a.nama_inv as nama,a.kd_asset as kode, a.merk, a.warna, a.no_ruang, case status when 'Berfungsi' then 'Baik' when 'Tidak Berfungsi' then 'Rusak' end as kondisi,a.foto 
            from amu_asset_bergerak a
            left join amu_mon_asset_bergerak b on a.no_bukti=b.kd_asset and a.kode_lokasi=b.kode_lokasi where b.mon_id= '$id' and a.kode_lokasi='$kode_lokasi' ";
            
            $response['daftar']=dbResultArray($sql);
            $response['status']=TRUE;
            $response['sql']=$sql;
        }else{
            $response['status'] = false;
            $response['message'] = "Unauthorized Access ".$res["message"];
            
        }
        echo json_encode($response);
    }

    function getInventarisBerjalan(){
        getKoneksi();
        $data = $_GET;
        $header = getallheaders();
        $token = $header["Authorization"];
		$res = authKey($token); 
        $res = authKey($data["token"]); // HAPUS JIKA INGIN PAKAI HEADER
        if($res["status"]){ 
            $kode_lokasi=$data['kode_lokasi'];
            
            $response = array("message" => "", "rows" => 0, "status" => "" );
            $sql="select a.no_ruangan, isnull(b.jum_asset,0) as jum_asset, isnull(c.jum_mon,0) as jum_mon,CAST((isnull(c.jum_mon,0) * 1.0 / isnull(b.jum_asset,0)) AS DECIMAL(6,2))*100 as persen, isnull(b.jum_asset,0) - isnull(c.jum_mon,0) as jum_belum,getdate() as tgl
            from amu_ruangan a
            left join ( select a.no_ruang,a.kode_lokasi,count(a.no_bukti) as jum_asset 
                        from amu_asset_bergerak a
                        group by a.no_ruang,a.kode_lokasi
                        ) b on a.no_ruangan=b.no_ruang and a.kode_lokasi=b.kode_lokasi
            left join ( select a.no_ruangan,a.kode_lokasi,count(a.kd_asset) as jum_mon 
                        from amu_mon_asset_bergerak a
                        inner join amu_asset_bergerak b on a.kd_asset=b.no_bukti
                        group by a.no_ruangan,a.kode_lokasi
                    ) c on a.no_ruangan=c.no_ruangan and a.kode_lokasi=c.kode_lokasi
            where a.kode_lokasi='$kode_lokasi' and isnull(b.jum_asset,0) <> 0 and CAST((isnull(c.jum_mon,0) * 1.0 / isnull(b.jum_asset,0)) AS DECIMAL(6,2))*100 <> 100 ";
            
            $response['daftar']=dbResultArray($sql);
            $response['status']=TRUE;
        }else{
            $response['status'] = false;
            $response['message'] = "Unauthorized Access ".$res["message"];
            
        }
        echo json_encode($response);
    }

    function getInventarisLengkap(){
        getKoneksi();
        $data = $_GET;
        $header = getallheaders();
        $token = $header["Authorization"];
		$res = authKey($token); 
        $res = authKey($data["token"]); // HAPUS JIKA INGIN PAKAI HEADER
        if($res["status"]){ 
            $kode_lokasi=$data['kode_lokasi'];
            
            $response = array("message" => "", "rows" => 0, "status" => "" );
            $sql="select a.no_ruangan, isnull(b.jum_asset,0) as jum_asset, isnull(d.jum_rusak,0) as jum_rusak,isnull(c.jum_baik,0) as jum_baik,CAST(((isnull(d.jum_rusak,0)+isnull(c.jum_baik,0)) * 1.0 / isnull(b.jum_asset,0)) AS DECIMAL(6,2))*100 as persen, isnull(b.jum_asset,0) - (isnull(d.jum_rusak,0)+isnull(c.jum_baik,0)) as jum_belum,getdate() as tgl from amu_ruangan a left join ( select a.no_ruang,a.kode_lokasi,count(a.no_bukti) as jum_asset from amu_asset_bergerak a group by a.no_ruang,a.kode_lokasi ) b on a.no_ruangan=b.no_ruang and a.kode_lokasi=b.kode_lokasi left join ( select a.no_ruangan,a.kode_lokasi,count(a.kd_asset) as jum_rusak  from amu_mon_asset_bergerak a inner join amu_asset_bergerak b on a.kd_asset=b.no_bukti where a.status='Tidak Berfungsi' group by a.no_ruangan,a.kode_lokasi) d on a.no_ruangan=d.no_ruangan and a.kode_lokasi=d.kode_lokasi left join ( select a.no_ruangan,a.kode_lokasi,count(a.kd_asset) as jum_baik from amu_mon_asset_bergerak a inner join amu_asset_bergerak b on a.kd_asset=b.no_bukti where a.status='Berfungsi' group by a.no_ruangan,a.kode_lokasi ) c on a.no_ruangan=c.no_ruangan and a.kode_lokasi=c.kode_lokasi where a.kode_lokasi='$kode_lokasi' and isnull(b.jum_asset,0) <> 0 and CAST(((isnull(d.jum_rusak,0)+isnull(c.jum_baik,0)) * 1.0 / isnull(b.jum_asset,0)) AS DECIMAL(6,2))*100 = 100
            ";
            
            $response['daftar']=dbResultArray($sql);
            $response['status']=TRUE;
        }else{
            $response['status'] = false;
            $response['message'] = "Unauthorized Access ".$res["message"];
            
        }
        echo json_encode($response);
    }

    function getLokasi(){
        getKoneksi();
        $data=$_GET;
        $header = getallheaders();
        $token = $header["Authorization"];
		$res = authKey($token); 
        $res = authKey($data["token"]); // HAPUS JIKA INGIN PAKAI HEADER
        if($res["status"]){ 
            $kode_pp=$_GET['kodePP'];
            $response = array("message" => "", "rows" => 0, "status" => "" );
            $sql="SELECT a.no_ruangan, a.nama_ruangan, a.kode_pp FROM amu_ruangan a WHERE kode_pp='$kode_pp'";
            $response['daftar']=dbResultArray($sql);
            $response['status']=TRUE;
        }else{
            $response['status'] = false;
            $response['message'] = "Unauthorized Access ".$res["message"];
            
        }
        echo json_encode($response);
    }

    function getDaftarAsset(){
        getKoneksi();
        $data = $_GET;
        $header = getallheaders();
        $token = $header["Authorization"];
		$res = authKey($token); 
        $res = authKey($data["token"]); // HAPUS JIKA INGIN PAKAI HEADER
        if($res["status"]){ 
            $kode_lokasi=$data['kode_lokasi'];
            $no_ruangan=$data['no_ruangan'];
            $kondisi = $data['kondisi'];
            if($kondisi == "All"){
                $filter = "";
            }else if($kondisi == "Selesai"){
                $filter = " and b.status in ('Tidak Berfungsi','Berfungsi') ";
            }else if($kondisi == "Belum"){
                $filter = " and isnull(b.status,'-') = '-' ";
            }
            
            $response = array("message" => "", "rows" => 0, "status" => "" );
            $sql="select a.foto,a.no_bukti,a.no_ruang,a.nama_inv as nama,a.kd_asset,case b.status when 'Tidak Berfungsi' then 'Rusak' when 'Berfungsi' then 'Baik' else 'Belum diketahui' end as kondisi
            from amu_asset_bergerak a
            left join amu_mon_asset_bergerak b on a.no_bukti=b.kd_asset and a.kode_lokasi=b.kode_lokasi
            where a.no_ruang='$no_ruangan' $filter
            ";
            
            $response['daftar']=dbResultArray($sql);
            $response['status']=TRUE;
        }else{
            $response['status'] = false;
            $response['message'] = "Unauthorized Access ".$res["message"];   
        }
        echo json_encode($response);
    }

    function generateKode($tabel, $kolom_acuan, $prefix, $str_format){
        $query = execute("select right(max($kolom_acuan), ".strlen($str_format).")+1 as id from $tabel where $kolom_acuan like '$prefix%'");
        $kode = $query->fields[0];
        $id = $prefix.str_pad($kode, strlen($str_format), $str_format, STR_PAD_LEFT);
        return $id;
    }

    function simpanInventaris(){
        getKoneksi();
        $data = $_POST;
        $response = array();
        $header = getallheaders();
        $token = $header["Authorization"];
		$res = authKey($token); 
        $res = authKey($data["token"]); // HAPUS JIKA INGIN PAKAI HEADER
        if($res["status"]){ 
        
            $kode_lokasi=$data['kode_lokasi'];
            $no_ruangan=$data['no_ruangan'];
            $kode_aset=$data['kode_aset'];
            $kondisi = $data['kondisi'];
            $periode = date('Y').date('m');
            if($kondisi == "Baik"){
                $status = "Berfungsi";
            }else if($kondisi == "Rusak"){
                $status = "Tidak Berfungsi";
            }
            
            $id = generateKode("amu_mon_asset_bergerak", "mon_id", $kode_lokasi."-NMA".$periode.".", "001");

            $sql = "select id_gedung from amu_ruangan where no_ruangan ='$no_ruangan' and kode_lokasi='$kode_lokasi' ";

            $ged = dbResultArray($sql);
            if(count($ged)>0){
                $id_gedung = $ged[0]["id_gedung"];
            }else{
                $id_gedung = "-";
            }

            if(ISSET($_FILES["file_gambar"]["name"]) AND !empty($_FILES["file_gambar"]["name"])){

                $path_s = $_SERVER['DOCUMENT_ROOT'];
                $target_dir = $path_s."upload/";
                $target_file = $target_dir . basename($_FILES["file_gambar"]["name"]);
                $uploadOk = 1;
                $message="";
                $error_upload="";
                $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

                // generate nama gambar baru
                $namaFileBaru = uniqid();
                $namaFileBaru .= '.';
                $namaFileBaru .= $imageFileType;

                $target_file = $target_dir . $namaFileBaru;

                // Check if image file is a actual image or fake image
                if(isset($_POST["submit"])) {
                    $check = getimagesize($_FILES["file_gambar"]["tmp_name"]);
                    if($check !== false) {
                        $message= "File is an image - " . $check["mime"] . ".";
                        $uploadOk = 1;
                    } else {
                        $error_upload= "File is not an image.";
                        $uploadOk = 0;
                    }
                }
                // Check if file already exists
                if (file_exists($target_file)) {
                    $error_upload= "Sorry, file already exists.";
                    $uploadOk = 0;
                }
                // Check file size
                if ($_FILES["file_gambar"]["size"] > 3000000) {
                    $error_upload= "Sorry, your file is too large.";
                    $uploadOk = 0;
                }
                // Allow certain file formats
                if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                && $imageFileType != "gif" ) {
                    $error_upload= "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                    $uploadOk = 0;
                }
                // Check if $uploadOk is set to 0 by an error
                if ($uploadOk == 0) {
                    $error_upload= "Sorry, your file was not uploaded.";
                // if everything is ok, try to upload file
                } else {
                    if (move_uploaded_file($_FILES["file_gambar"]["tmp_name"], $target_file)) {
                        $message = "The file ". $namaFileBaru. " has been uploaded.";
                    } else {
                        $error_upload= "Sorry, there was an error uploading your file.";
                        // echo $target_file;
                        // echo $_FILES["file_gambar"]["error"];
                        if (is_dir($target_dir) && is_writable($target_dir)) {
                            // do upload logic here
                        } else if (!is_dir($target_dir)){
                            $error_upload.= 'Upload directory does not exist.'.$target_dir;
                        } else if (!is_writable($target_dir)){
                            $error_upload.= 'Upload directory is not writable'.$target_dir;
                        }

                    }
                }

                $filepath=$namaFileBaru;
            }else{
                $filepath="-";
            }

            $exec = array();

            $ins = "insert into amu_mon_asset_bergerak (mon_id,kd_asset,id_gedung,no_ruangan,status,periode,kode_lokasi,tgl_input,foto) values('$id','$kode_aset','$id_gedung','$no_ruangan','$status','$periode','$kode_lokasi',getdate(),'$filepath') ";
            
            array_push($exec,$ins);

            $rs = executeArray($exec);
            if($rs){
                $response["message"] = "berhasil";
                $response["status"]= true;
            }else{
                
                $response["message"] = "gagal";
                $response["status"]=false;
            }

        // $response["sql"]=$exec;
        }else{
            $response['status'] = false;
            $response['message'] = "Unauthorized Access ".$res["message"];   
        }
        echo json_encode($response);

    }

    function ubahGambarAset(){
        getKoneksi();
        $data = $_POST;
        $response = array();
        $header = getallheaders();
        $token = $header["Authorization"];
		$res = authKey($token); 
        $res = authKey($data["token"]); // HAPUS JIKA INGIN PAKAI HEADER
        if($res["status"]){ 
            $kode_lokasi=$data['kode_lokasi'];
            $no_bukti=$data['no_bukti'];

            if(ISSET($_FILES["file_gambar"]["name"]) AND !empty($_FILES["file_gambar"]["name"])){

                $path_s = $_SERVER['DOCUMENT_ROOT'];
                $target_dir = $path_s."upload/";
                $target_file = $target_dir . basename($_FILES["file_gambar"]["name"]);
                $uploadOk = 1;
                $message="";
                $error_upload="";
                $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

                // generate nama gambar baru
                $namaFileBaru = uniqid();
                $namaFileBaru .= '.';
                $namaFileBaru .= $imageFileType;

                $target_file = $target_dir . $namaFileBaru;

                // Check if image file is a actual image or fake image
                if(isset($_POST["submit"])) {
                    $check = getimagesize($_FILES["file_gambar"]["tmp_name"]);
                    if($check !== false) {
                        $message= "File is an image - " . $check["mime"] . ".";
                        $uploadOk = 1;
                    } else {
                        $error_upload= "File is not an image.";
                        $uploadOk = 0;
                    }
                }
                // Check if file already exists
                if (file_exists($target_file)) {
                    $error_upload= "Sorry, file already exists.";
                    $uploadOk = 0;
                }
                // Check file size
                if ($_FILES["file_gambar"]["size"] > 3000000) {
                    $error_upload= "Sorry, your file is too large.";
                    $uploadOk = 0;
                }
                // Allow certain file formats
                if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                && $imageFileType != "gif" ) {
                    $error_upload= "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                    $uploadOk = 0;
                }
                // Check if $uploadOk is set to 0 by an error
                if ($uploadOk == 0) {
                    $error_upload= "Sorry, your file was not uploaded.";
                // if everything is ok, try to upload file
                } else {
                    if (move_uploaded_file($_FILES["file_gambar"]["tmp_name"], $target_file)) {
                        $message = "The file ". $namaFileBaru. " has been uploaded.";
                    } else {
                        $error_upload= "Sorry, there was an error uploading your file.";
                        // echo $target_file;
                        // echo $_FILES["file_gambar"]["error"];
                        if (is_dir($target_dir) && is_writable($target_dir)) {
                            // do upload logic here
                        } else if (!is_dir($target_dir)){
                            $error_upload.= 'Upload directory does not exist.'.$target_dir;
                        } else if (!is_writable($target_dir)){
                            $error_upload.= 'Upload directory is not writable'.$target_dir;
                        }

                    }
                }

                $filepath=$namaFileBaru;
            }else{
                $filepath="-";
            }

            $exec = array();

            $ins = "UPDATE amu_asset_bergerak SET foto='$filepath' WHERE no_bukti='$no_bukti' AND kode_lokasi='$kode_lokasi'";
            
            array_push($exec,$ins);

            $rs = executeArray($exec);
            if($rs){
                $response["message"] = "berhasil";
                $response["status"]= true;
            }else{
                
                $response["message"] = "gagal";
                $response["status"]=false;
            }

        }else{
            $response['status'] = false;
            $response['message'] = "Unauthorized Access ".$res["message"];   
        }
        echo json_encode($response);

    }


?>