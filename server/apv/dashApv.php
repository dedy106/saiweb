<?php

    $request_method=$_SERVER["REQUEST_METHOD"];

    switch($request_method) {
        case 'GET':
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
        include_once($root_lib."lib/koneksi.php");
        include_once($root_lib."lib/helpers.php");
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

    function isUnik($isi){
        getKoneksi();

        $schema = db_Connect();
        $auth = $schema->SelectLimit("SELECT nik FROM apv_karyawan where nik='$isi' ", 1);
        if($auth->RecordCount() > 0){
            return false;
        }else{
            return true;
        }
    }

    function getDataBox(){
        
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $tmp = explode("|",$_GET['param']);
            $kode_lokasi = $tmp[0];
            
            $aju =dbRowArray("select count(*) as jum from apv_juskeb_m where kode_lokasi='$kode_lokasi'");
            $response["juskeb"] = $aju["jum"];

            $ver =dbRowArray("select count(distinct no_juskeb) as jum from apv_ver_m  where kode_lokasi='$kode_lokasi'");
            $response["ver"] = $ver["jum"];

            $appkeb =dbRowArray("select count(*) as jum from apv_juskeb_m where kode_lokasi='$kode_lokasi' and progress in ('S')");
            $response["appjuskeb"] = $appkeb["jum"];

            
            $ajup =dbRowArray("select count(*) as jum from apv_juspo_m where kode_lokasi='$kode_lokasi'");
            $response["juspeng"] = $ajup["jum"];

            $appp =dbRowArray("select count(*) as jum from apv_juspo_m where kode_lokasi='$kode_lokasi' and progress in ('S')");
            $response["appjuspeng"] = $appp["jum"];

            
            $response['status'] = TRUE;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }

    function getPosisi(){
        
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $tmp = explode("|",$_GET['param']);
            $kode_lokasi = $tmp[0];
            $jenis = $tmp[1];
            if($jenis == ""){
                $filter = "";
            }else{

                switch($jenis){
                    case 'JK':
                        $sql ="select no_bukti from apv_juskeb_m where kode_lokasi='$kode_lokasi' ";
                    break;
                    case 'VR':
                        
                        $sql ="select distinct no_juskeb as no_bukti from apv_ver_m where kode_lokasi='$kode_lokasi' ";
                    break;
                    case 'AJK':
                        $sql ="select no_bukti from apv_juskeb_m where kode_lokasi='$kode_lokasi' and progress in ('S') ";
                    break;
                    case 'JP':
                        $sql ="select no_juskeb as no_bukti from apv_juspo_m where kode_lokasi='$kode_lokasi' ";
                    break;
                    case 'AJP':
                        $sql ="select no_juskeb as no_bukti from apv_juspo_m where kode_lokasi='$kode_lokasi'  and progress in ('S') ";
                    break;
                }
    
                $res = execute($sql);
                $array_no = "";
                $i=0;
                while($row = $res->FetchNextObject($toupper=false)){
                    if($i == 0){
                        $array_no .= "'$row->no_bukti'";
                    }else{
    
                        $array_no .= ","."'$row->no_bukti'";
                    }
                    $i++;
                }
                $filter = " and a.no_bukti in ($array_no) ";
            }
            
            $daftar =dbResultArray(" select a.no_bukti,a.no_dokumen,a.kode_pp,convert(varchar,a.waktu,103) as waktu,a.kegiatan,case a.progress when 'S' then 'FINISH' when 'F' then 'Return Verifikasi' when 'R' then 'Return Approval' else isnull(b.nama_jab,'-') end as posisi,a.nilai,a.progress,c.progress as progress2,case c.progress when 'S' then 'FINISH' when 'F' then 'Return Verifikasi' when 'R' then 'Return Approval' else isnull(d.nama_jab,'-') end as posisi2
            from apv_juskeb_m a
            left join (select a.no_bukti,b.nama as nama_jab
                    from apv_flow a
                    inner join apv_jab b on a.kode_jab=b.kode_jab and a.kode_lokasi=b.kode_lokasi
                    where a.kode_lokasi='$kode_lokasi' and a.status='1'
                    )b on a.no_bukti=b.no_bukti
			left join apv_juspo_m c on a.no_bukti=c.no_juskeb and a.kode_lokasi=c.kode_lokasi
			left join (select a.no_bukti,b.nama as nama_jab
                    from apv_flow a
                    inner join apv_jab b on a.kode_jab=b.kode_jab and a.kode_lokasi=b.kode_lokasi
                    where a.kode_lokasi='$kode_lokasi' and a.status='1'
                    )d on c.no_bukti=d.no_bukti
            where a.kode_lokasi='".$kode_lokasi."'  and a.nik_buat='".$_SESSION['userLog']."' $filter ");
            $response["daftar"] = $daftar;
            $response['status'] = TRUE;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }

?>