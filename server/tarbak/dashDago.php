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
		include_once($root_lib.'app/tarbak/setting.php');
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

    function getDataBox(){
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            
            $kode_lokasi=$_SESSION['lokasi'];
            $param = explode('|',$_GET['param']);
            $nik = $param[0];
            if($nik != ""){
                $filter = " and b.no_peserta='$nik' ";
            }else{
                $filter = "";
            }

            $rs = execute("select count(*) as jum from dgw_peserta b where b.kode_lokasi='$kode_lokasi' ");
            $response['jamaah'] = $rs->fields[0];
            $rs2 =execute("select count(*) as jum from dgw_reg b where b.kode_lokasi='$kode_lokasi' $filter");
            $response['registrasi'] = $rs2->fields[0];
            $sql = "select count(a.no_reg) as jum from dgw_pembayaran a 
            inner join dgw_reg b on a.no_reg=b.no_reg and a.kode_lokasi=b.kode_lokasi 
            where a.kode_lokasi='$kode_lokasi' $filter ";
            $rs3 = execute($sql);
            $response['pembayaran'] = $rs3->fields[0];
            $response["status"] = true;
            $response['sql']=$sql;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    function getTopAgen(){
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            
            $kode_lokasi=$_SESSION['lokasi'];
            $sql = "
            select top 5 a.no_agen,b.nama_agen,count(*) as jum
            from dgw_reg a 
            left join dgw_agent b on a.no_agen=b.no_agen and a.kode_lokasi=b.kode_lokasi
            where a.no_agen <> '-' and a.kode_lokasi='$kode_lokasi'
            group by a.no_agen,b.nama_agen
            order by count(*) desc ";
            $response['daftar'] = dbResultArray($sql);

            $response["status"] = true;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    function getRegHari(){
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            
            $kode_lokasi=$_SESSION['lokasi'];
            $periode = date('Ym');
            $sql = "select a.* from (select top 12 tgl_input, count(*) as n1 
            from dgw_reg
            where kode_lokasi='$kode_lokasi'
            group by tgl_input
            order by tgl_input desc 
            ) a order by a.tgl_input asc";
            
            $rs=execute($sql);

            if($rs->RecordCount() > 0){
                while ($row = $rs->FetchNextObject(false)){
                    $result['reg'][] = floatval($row->n1);
                    $ctg[] = $row->tgl_input;
                }
            }
            
            $series[] = array("name"=>"Total Registrasi","data"=>$result['reg']);
            
            $response['series']=$series;
            $response['ctg']=$ctg;
            $response["status"] = true;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    function getKartu(){
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            
            $kode_lokasi=$_SESSION['lokasi'];
            $param = explode('|',$_GET['param']);
            $nik = $param[0];
            if($nik != ""){
                $filter = " and a.no_peserta='$nik' ";
            }else{
                $filter = "";
            }
            $sql="select top 1 a.no_reg,a.no_peserta,a.no_paket,a.tgl_input,d.nama,e.nama_agen,convert(varchar(20),a.tgl_input,103) as tgl,
            a.harga+harga_room as paket,isnull(b.nilai,0) as tambahan,f.nama as nama_paket,a.no_agen
            from dgw_reg a
            inner join dgw_peserta d on a.no_peserta=d.no_peserta and a.kode_lokasi=d.kode_lokasi
            inner join dgw_agent e on a.no_agen=e.no_agen and a.kode_lokasi=e.kode_lokasi
            inner join dgw_paket f on a.no_paket=f.no_paket and a.kode_lokasi=f.kode_lokasi
            left join (select a.no_reg,a.kode_lokasi,sum(a.nilai) as nilai
            from dgw_reg_biaya a
            where a.kode_lokasi='$kode_lokasi'
            group by a.no_reg,a.kode_lokasi
            )b on a.no_reg=b.no_reg and a.kode_lokasi=b.kode_lokasi
            where a.kode_lokasi='$kode_lokasi' $filter
            order by a.no_reg desc ";

            $response['daftar'] = dbResultArray($sql);

            $sql="select a.no_reg,a.no_kwitansi,a.kode_lokasi,convert(varchar(20),b.tanggal,103) as tgl,b.keterangan,a.nilai_p,a.nilai_t
            from dgw_pembayaran a
			inner join dgw_reg c on a.no_reg=c.no_reg and a.kode_lokasi=c.kode_lokasi
            inner join trans_m b on a.no_kwitansi=b.no_bukti and a.kode_lokasi=b.kode_lokasi
            where a.kode_lokasi='$kode_lokasi' and c.no_peserta='$nik'
            order by b.tanggal ";
            
            $response['daftar2'] = dbResultArray($sql);

            $response["status"] = true;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    function getDokumen(){
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            
            $kode_lokasi=$_SESSION['lokasi'];
            $param = explode('|',$_GET['param']);
            $nik = $param[0];
            if($nik != ""){
                $filter = " and a.no_peserta='$nik' ";
            }else{
                $filter = "";
            }
            $sql="select top 1 a.no_reg,a.no_peserta,a.no_paket,a.tgl_input,d.nama,e.nama_agen,convert(varchar(20),a.tgl_input,103) as tgl,
            a.harga+harga_room as paket,isnull(b.nilai,0) as tambahan,f.nama as nama_paket,a.no_agen
            from dgw_reg a
            inner join dgw_peserta d on a.no_peserta=d.no_peserta and a.kode_lokasi=d.kode_lokasi
            inner join dgw_agent e on a.no_agen=e.no_agen and a.kode_lokasi=e.kode_lokasi
            inner join dgw_paket f on a.no_paket=f.no_paket and a.kode_lokasi=f.kode_lokasi
            left join (select a.no_reg,a.kode_lokasi,sum(a.nilai) as nilai
            from dgw_reg_biaya a
            where a.kode_lokasi='$kode_lokasi'
            group by a.no_reg,a.kode_lokasi
            )b on a.no_reg=b.no_reg and a.kode_lokasi=b.kode_lokasi
            where a.kode_lokasi='$kode_lokasi' $filter
            order by a.no_reg desc ";

            $rs = execute($sql);
            if($rs->RecordCount()>0){
                $no_reg = $rs->fields[0];
                $response['daftar'] = dbResultArray("select a.no_dokumen,a.deskripsi,a.jenis,isnull(convert(varchar,b.tgl_terima,111),'-') as tgl_terima,isnull(c.no_gambar,'-') as fileaddres 
                from dgw_dok a 
                left join dgw_reg_dok b on a.no_dokumen=b.no_dok and b.no_reg='$no_reg'
                left join dgw_scan c on a.no_dokumen=c.modul and c.no_bukti ='$no_reg' 
                where a.kode_lokasi='$kode_lokasi' order by a.no_dokumen");
            }else{
                $response['daftar'] = array();
            }           

            $response["status"] = true;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }


    function getKuota(){
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            
            $kode_lokasi=$_SESSION['lokasi'];
            $sql = "
            select a.no_paket,a.no_jadwal,a.tgl_berangkat,b.nama, a.quota+a.quota_se+a.quota_e as quota,isnull(c.jum,0) as jum,  (a.quota+a.quota_se+a.quota_e) - isnull(c.jum,0) as sisa 
            from dgw_jadwal a
            inner join dgw_paket b on a.no_paket=b.no_paket and a.kode_lokasi=b.kode_lokasi
            left join (
                    select a.no_paket,a.no_jadwal,a.kode_lokasi,count(*) as jum
                    from dgw_reg a
                    group by a.no_paket,a.no_jadwal,a.kode_lokasi
            ) c on a.no_paket=c.no_paket and a.no_jadwal=c.no_jadwal and a.kode_lokasi=c.kode_lokasi
            where a.kode_lokasi='$kode_lokasi'
            order by a.tgl_berangkat desc,a.no_paket ";
            $response['daftar'] = dbResultArray($sql);

            $response["status"] = true;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    