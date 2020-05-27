<?php

    $request_method=$_SERVER["REQUEST_METHOD"];

    switch($request_method) {
        case 'GET':
            if(isset($_GET["fx"]) AND function_exists($_GET['fx'])){
                $_GET['fx']();
            }
        break;
        case 'POST':
            // Insert Product
            if(isset($_GET["fx"]) AND function_exists($_GET['fx'])){
                $_GET['fx']();
            }else{
                insertPembayaran();
            }
        break;
        case 'DELETE':
        // Delete Product
            deletePembayaran();
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

    function cekAuth($user,$pass){
        getKoneksi();
        $user = qstr($user);
        $pass = qstr($pass);

        $schema = db_Connect();
        $auth = $schema->SelectLimit("SELECT * FROM hakakses where nik=$user and pass=$pass", 1);
        if($auth->RecordCount() > 0){
            return true;
        }else{
            return false;
        }
    }

    function getDataBox(){
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'],$_SESSION['userPwd'])){

            $periode = date('Y').date('m');
            $tahun = substr($periode, 0, 4);
            $tahunLalu = floatval($tahun) - 1;
            $periodeLalu = $tahunLalu . substr($periode, 4, 2);
            
            $tahun = qstr($tahun);
            $periode =qstr($periode);
            
            
            $response["CUST"] = dbRowArray("select isnull(count(kode_cust),0) as cust from sai_proses01 where flag_keep != '0' and status != '3' ");
            $response["PROD"] = dbRowArray("select isnull(count(kode_produk),0) as prod from sai_proses01 where flag_keep != '0' and status != '3'");
            $response["KONTRAK"] = dbRowArray("select isnull (sum(nilai),0) as kontrak
            from sai_proses03 where flag_keep != '0'");
            $response["PBYR"] = dbRowArray("select isnull (sum(nilai),0) as pbyr
            from sai_proses07 where flag_keep != '0'");
            $response["status"] = true;
            
        }else{
            $response["message"] = "Unauthorized Access, Login Required";
            $response["status"] = false;
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    function getDataListBox(){
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'],$_SESSION['userPwd'])){

            $periode = date('Y').date('m');
            $tahun = substr($periode, 0, 4);
            $tahunLalu = floatval($tahun) - 1;
            $periodeLalu = $tahunLalu . substr($periode, 4, 2);
            $kode_lokasi = qstr($_SESSION['lokasi']);
            $tahun = qstr($tahun);
            $periode =qstr($periode);
            $response = array("project"=>array(),"customer"=>array(),"kontrak"=>array(),"status"=>true);
            
            
            $response["project"] = dbResultArray("select a.kode_proses,a.nama,isnull (b.jumlah,0) as jumlah,isnull(b.nilai,0) as nilai
																from sai_proses a
																left join(select a.progress,count(a.no_bukti) as jumlah,sum(a.nilai) as nilai
																from sai_proses01 a 
																where a.flag_keep !='0' and a.status != '3'
																group by a.progress
																		) b on a.kode_proses=b.progress
																
																order by a.kode_proses");
            $response["customer"] = dbResultArray("select a.kode_cust,a.nama,isnull(b.jumlah,0) as jumlah,isnull(b.nilai,0) as nilai
																from sai_cust a
																inner join (select a.kode_lokasi,a.kode_cust,count(a.no_bukti) as jumlah,sum(a.nilai) as nilai
																			from sai_proses01 a
																			where a.flag_keep !='0' and a.status != '3'
																			group by a.kode_lokasi,a.kode_cust
																)b on a.kode_cust=b.kode_cust and a.kode_lokasi=b.kode_lokasi
																where a.kode_lokasi=$kode_lokasi
																
																");
			$response["produk"] = dbResultArray("select a.kode_produk,a.nama,isnull(b.jumlah,0) as jumlah,isnull(b.nilai,0) as nilai
																from sai_produk a
																inner join (select a.kode_lokasi,a.kode_produk,count(a.no_bukti) as jumlah,sum(a.nilai) as nilai
																			from sai_proses01 a
																			where a.flag_keep !='0' and a.status != '3'
																			group by a.kode_lokasi,a.kode_produk
																)b on a.kode_produk=b.kode_produk and a.kode_lokasi=b.kode_lokasi
																where a.kode_lokasi=$kode_lokasi
																
																");
            $response["status"] = true;
            
        }else{
            $response["message"] = "Unauthorized Access, Login Required";
            $response["status"] = false;
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    function getDataGrafik(){
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'],$_SESSION['userPwd'])){

            $periode = date('Y').date('m');
            $tahun = substr($periode, 0, 4);
            $tahunLalu = floatval($tahun) - 1;
            $periodeLalu = $tahunLalu . substr($periode, 4, 2);
            $kode_lokasi = qstr($_SESSION['lokasi']);
            $tahun = qstr($tahun);
            $periode =qstr($periode);
            $response = array("project"=>array(),"customer"=>array(),"kontrak"=>array(),"status"=>true);
            
            $res = execute("select a.kode_cust,b.nama as nama, sum(nilai) as nilai
                            from sai_proses01 a 
                            inner join sai_cust b on a.kode_cust=b.kode_cust 
                            where a.flag_keep !='0' and a.status != '3' and a.kode_lokasi=$kode_lokasi
                            group by a.kode_cust,b.nama");
            $response["prn"]["series"]["data"] = array();
            $response["prn"]["series"]["name"] = "Nilai";
            $response["prn"]["series"]["colorByPoint"] = TRUE;
            $response["prn"]["categories"] = array();
            while ($row = $res->FetchNextObject(false)){
                $response["prn"]["series"]["data"][] = array('name'=>$row->nama, 'y'=>floatval($row->nilai),'key'=>$row->kode_cust);
                $response["prn"]["categories"][] = $row->nama;
            }
            
            $res = execute("select a.kode_cust,b.nama as nama, count(a.no_bukti) as jumlah
                            from sai_proses01 a 
                            inner join sai_cust b on a.kode_cust=b.kode_cust 
                            where a.flag_keep !='0' and a.status != '3' and a.kode_lokasi=$kode_lokasi
                            group by a.kode_cust,b.nama");
            $response["prj"]["series"]["data"] = array();
            $response["prj"]["series"]["name"] = "Jumlah";
            $response["prj"]["series"]["colorByPoint"] = TRUE;
            $response["prj"]["categories"] = array();
            while ($row = $res->FetchNextObject(false)){
                $response["prj"]["series"]["data"][] = array('name'=>$row->nama, 'y'=>floatval($row->jumlah),'key'=>$row->kode_cust);
                $response["prj"]["categories"][] = $row->nama;
            }
            
            $res = execute("select a.kode_cust,b.nama as nama, sum(c.nilai) as nilai
                            from sai_proses01 a 
                            inner join sai_cust b on a.kode_cust=b.kode_cust 
                            inner join sai_proses07 c on a.no_bukti=c.no_ref
                            where c.flag_keep !='0' and a.status != '3' and a.kode_lokasi=$kode_lokasi
                            group by a.kode_cust,b.nama");
            $response["pen"]["series"]["data"] = array();
            $response["pen"]["series"]["name"] = "Nilai";
            $response["pen"]["series"]["colorByPoint"] = TRUE;
            $response["pen"]["categories"] = array();
            while ($row = $res->FetchNextObject(false)){
                $response["pen"]["series"]["data"][] = array('name'=>$row->nama, 'y'=>floatval($row->nilai),'key'=>$row->kode_cust);
                $response["pen"]["categories"][] = $row->nama;
            }
            
            $res = execute("select a.kode_cust,b.nama as nama, count(c.no_bukti) as jumlah 
                        from sai_proses01 a 
                        inner join sai_cust b on a.kode_cust=b.kode_cust 
                        inner join sai_proses07 c on a.no_bukti=c.no_ref
                        where c.flag_keep !='0' and a.status != '3' and a.kode_lokasi=$kode_lokasi
                        group by a.kode_cust,b.nama");
            $response["pej"]["series"]["data"] = array();
            $response["pej"]["series"]["name"] = "Jumlah";
            $response["pej"]["series"]["colorByPoint"] = TRUE;
            $response["pej"]["categories"] = array();
            while ($row = $res->FetchNextObject(false)){
                $response["pej"]["series"]["data"][] = array('name'=>$row->nama, 'y'=>floatval($row->jumlah),'key'=>$row->kode_cust);
                $response["pej"]["categories"][] = $row->nama;
            }
            $response["status"] = true;
            
        }else{
            $response["message"] = "Unauthorized Access, Login Required";
            $response["status"] = false;
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    function getMonitoringGrafik(){
        
		session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'],$_SESSION['userPwd'])){

            $data=$_GET;

            $kode_lokasi = qstr($_SESSION["lokasi"]);
            $id = qstr($data["param"]);

            $sql = "select a.no_bukti,a.nilai, convert(varchar, a.tanggal, 103) as tanggal,b.nama as nama_progress, 
            d.nama as nama_cust, e.nama as nama_prod,k.nama as nama_karyawan
            from sai_proses01 a 
            inner join sai_proses b on a.progress=b.kode_proses 
            inner join sai_cust d on a.kode_cust = d.kode_cust 
            inner join sai_produk e on a.kode_produk = e.kode_produk 
            inner join sai_karyawan k on a.nik = k.nik 
            and a.kode_lokasi=d.kode_lokasi 
            where a.kode_lokasi =$kode_lokasi and a.kode_cust=$id and a.flag_keep !='0' and a.status != '3' ";

            $response["sql"] = $sql;
            $response["daftar"] = dbResultArray($sql);
            $response["status"] = true;
		}else{
            $response["message"] = "Unauthorized Access, Login Required";
            $response["status"] = false;
        }
        header('Content-Type: application/json');
		echo json_encode($response);
	}


	function getMonitoringList(){
        
		session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'],$_SESSION['userPwd'])){
            
            $data=$_GET;
            $kode_lokasi = qstr($_SESSION["lokasi"]);
            $id = qstr($data["param"]);

            $sql = "select a.no_bukti,a.nilai, convert(varchar, a.tanggal, 103) as tanggal,b.nama as nama_progress, 
            d.nama as nama_cust, e.nama as nama_prod,k.nama as nama_karyawan
            from sai_proses01 a 
            inner join sai_proses b on a.progress=b.kode_proses 
            inner join sai_cust d on a.kode_cust = d.kode_cust 
            inner join sai_produk e on a.kode_produk = e.kode_produk 
            inner join sai_karyawan k on a.nik = k.nik 
            and a.kode_lokasi=d.kode_lokasi 
            where a.kode_lokasi = $kode_lokasi and a.progress=$id and a.flag_keep !='0' and a.status != '3'";

            $response["sql"] = $sql;
            $response["status"] = true;
            $response["daftar"] = dbResultArray($sql);
			
		}else{
            $response["message"] = "Unauthorized Access, Login Required";
            $response["status"] = false;
        }
        header('Content-Type: application/json');
		echo json_encode($response);
    }
    
    function getMonitoringListCust(){
        
		session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'],$_SESSION['userPwd'])){
            
            $data=$_GET;
            $kode_lokasi = qstr($_SESSION["lokasi"]);
            $id = qstr($data["param"]);

            $sql = "select a.no_bukti,a.nilai, convert(varchar, a.tanggal, 103) as tanggal,b.nama as nama_progress, 
					d.nama as nama_cust, e.nama as nama_prod,k.nama as nama_karyawan
					from sai_proses01 a 
					inner join sai_proses b on a.progress=b.kode_proses 
					inner join sai_cust d on a.kode_cust = d.kode_cust 
					inner join sai_produk e on a.kode_produk = e.kode_produk 
					inner join sai_karyawan k on a.nik = k.nik 
					and a.kode_lokasi=d.kode_lokasi where a.kode_lokasi = $kode_lokasi 
					and a.kode_cust=$id and a.flag_keep !='0' and a.status != '3'";

            $response["sql"] = $sql;
            $response["status"] = true;
            $response["daftar"] = dbResultArray($sql);
			
		}else{
            $response["message"] = "Unauthorized Access, Login Required";
            $response["status"] = false;
        }
        header('Content-Type: application/json');
		echo json_encode($response);
    }
    
    function getMonitoringListProd(){
        
		session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'],$_SESSION['userPwd'])){
            
            $data=$_GET;
            $kode_lokasi = qstr($_SESSION["lokasi"]);
            $id = qstr($data["param"]);

            $sql = "select a.no_bukti,a.nilai, convert(varchar, a.tanggal, 103) as tanggal,b.nama as nama_progress, 
					d.nama as nama_cust, e.nama as nama_prod,k.nama as nama_karyawan
					from sai_proses01 a 
					inner join sai_proses b on a.progress=b.kode_proses 
					inner join sai_cust d on a.kode_cust = d.kode_cust 
					inner join sai_produk e on a.kode_produk = e.kode_produk 
					inner join sai_karyawan k on a.nik = k.nik 
					and a.kode_lokasi=d.kode_lokasi where a.kode_lokasi = $kode_lokasi 
					and a.kode_produk=$id and a.flag_keep !='0' and a.status != '3'";

            $response["sql"] = $sql;
            $response["status"] = true;
            $response["daftar"] = dbResultArray($sql);
			
		}else{
            $response["message"] = "Unauthorized Access, Login Required";
            $response["status"] = false;
        }
        header('Content-Type: application/json');
		echo json_encode($response);
    }
    
    function getTraceProgress(){
        
		session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'],$_SESSION['userPwd'])){
            
            $data=$_GET;
            $kode_lokasi = qstr($_SESSION["lokasi"]);
            $id = qstr($data["param"]);

            $response["daftar"] = array();

            // SA01 Prospecting
            $response["daftar"][] = array(
                "kode" => "01", 
                "data" => dbRowArray("select a.no_bukti, a.nilai, b.nama as nama_progress,d.nama as nama_cust, a.tanggal 
                from sai_proses01 a 
                inner join sai_proses b on a.progress=b.kode_proses 
                inner join sai_cust d on a.kode_cust = d.kode_cust 
                where a.kode_lokasi = $kode_lokasi and a.no_bukti=$id")
            );

            // SA02 Negosiasi
            $response["daftar"][] = array(
                "kode" => "02", 
                "data" => dbRowArray("select a.no_bukti,convert(varchar, a.tanggal, 103) as tanggal,a.keterangan, (select nama from sai_proses where kode_proses = 'SA02') as nama_progress from sai_proses02 a where a.kode_lokasi=$kode_lokasi and a.no_ref=$id")
            );

            // SA03 Kontrak
            $response["daftar"][] = array(
                "kode" => "03", 
                "data" => dbRowArray("select a.no_bukti,convert(varchar, a.tanggal, 103) as tanggal,a.keterangan, (select nama from sai_proses where kode_proses = 'SA03') as nama_progress from sai_proses03 a where a.kode_lokasi=$kode_lokasi and a.no_ref=$id")
            );

            // SA04 Development
            $response["daftar"][] = array(
                "kode" => "04", 
                "data" => dbRowArray("select a.no_bukti,convert(varchar, a.tanggal, 103) as tanggal,a.keterangan, (select nama from sai_proses where kode_proses = 'SA04') as nama_progress from sai_proses04 a where a.kode_lokasi=$kode_lokasi and a.no_ref=$id")
            );

            // SA05 Berita Acara
            $response["daftar"][] = array(
                "kode" => "05", 
                "data" => dbRowArray("select a.no_bukti,convert(varchar, a.tanggal, 103) as tanggal,a.keterangan, (select nama from sai_proses where kode_proses = 'SA05') as nama_progress from sai_proses05 a where a.kode_lokasi=$kode_lokasi and a.no_ref=$id")
            );

            // SA06 Invoice
            $response["daftar"][] = array(
                "kode" => "06", 
                "data" => dbRowArray("select a.no_bukti,convert(varchar, a.tanggal, 103) as tanggal,a.keterangan, (select nama from sai_proses where kode_proses = 'SA06') as nama_progress from sai_proses06 a where a.kode_lokasi=$kode_lokasi and a.no_ref=$id")
            );

            // SA07 Pembayaran
            $response["daftar"][] = array(
                "kode" => "07", 
                "data" => dbRowArray("select a.no_bukti,convert(varchar, a.tanggal, 103) as tanggal,a.keterangan, (select nama from sai_proses where kode_proses = 'SA07') as nama_progress from sai_proses07 a where a.kode_lokasi=$kode_lokasi and a.no_ref=$id")
            );
            
            $response["status"] = true;
			
		}else{
            $response["message"] = "Unauthorized Access, Login Required";
            $response["status"] = false;
        }
        header('Content-Type: application/json');
		echo json_encode($response);
	}


?>