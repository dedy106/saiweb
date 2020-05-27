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
		if (substr($root_lib,-1)!="/") {
			$root_lib=$root_lib."/";
		}
		include_once($root_lib."lib/koneksi5.php");
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

    function getKomposisi(){
        
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $tmp = explode("|",$_GET['param']);
            $kode_lokasi = $tmp[0];
            $periode=$_GET['periode'];
            $sql = "select a.kode_neraca,a.nama,
                case when a.jenis_akun='Pendapatan' then -a.n1 else a.n1 end as n1,
                case when a.jenis_akun='Pendapatan' then -a.n4 else a.n4 end as n4,
                case when a.jenis_akun='Pendapatan' then -a.n5 else a.n5 end as n5,
                case when a.n1<>0 then (a.n4/a.n1)*100 else 0 end as capai
            from exs_neraca a
            inner join db_grafik_d b on a.kode_neraca=b.kode_neraca and a.kode_lokasi=b.kode_lokasi and a.kode_fs=b.kode_fs
            where a.kode_lokasi='$kode_lokasi' and a.kode_fs='FS4' and a.periode='$periode' and b.kode_grafik='D04' and (a.n1<>0 or a.n4<>0 or a.n5<>0)
            order by b.nu";
            $res = execute($sql);
            $daftar = array();
            while($row= $res->FetchNextObject($toupper=false)){
                $daftar[] = array("y"=>floatval($row->n1),"name"=>$row->nama,"key"=>$row->kode_neraca); 
                
            }
            $response['data'] = $daftar;
            $response['category'] = $category;
            $response['status'] = TRUE;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }

    function perRKAvsReal(){
        
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $tmp = explode("|",$_GET['param']);
            $kode_lokasi = $tmp[0];
            $periode=$_GET['periode'];
            $sql = "select a.kode_neraca,a.nama,
            case when a.jenis_akun='Pendapatan' then -a.n1 else a.n1 end as n1,
            case when a.jenis_akun='Pendapatan' then -a.n4 else a.n4 end as n4,
            case when a.jenis_akun='Pendapatan' then -a.n5 else a.n5 end as n5,
            case when a.n1<>0 then (a.n4/a.n1)*100 else 0 end as capai
            from exs_neraca a
            inner join db_grafik_d b on a.kode_neraca=b.kode_neraca and a.kode_lokasi=b.kode_lokasi and a.kode_fs=b.kode_fs
            where a.kode_lokasi='$kode_lokasi' and a.kode_fs='FS4' and a.periode='$periode' and b.kode_grafik='D04' and (a.n1<>0 or a.n4<>0 or a.n5<>0)
            order by b.nu";
            $res = execute($sql);
            $daftar = array();
            $category = array();
            while($row= $res->FetchNextObject($toupper=false)){
                $daftar[] = array("y"=>floatval($row->capai),"name"=>$row->nama,"key"=>$row->kode_neraca); 
                $category[] = $row->nama;
            }
            $response['data'] = $daftar;
            $response['category'] = $category;
            $response['status'] = TRUE;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }

    function getTotalOprNon(){
        
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $tmp = explode("|",$_GET['param']);
            $kode_lokasi = $tmp[0];
            $periode = $_GET['periode'];
            $sql = "select a.kode_neraca,a.n5,a.n1,a.n4,case when a.n1<>0 then (a.n4/a.n1)*100 else 0 end as capai
            from exs_neraca a
            inner join db_grafik_d b on a.kode_neraca=b.kode_neraca and a.kode_lokasi=b.kode_lokasi and a.kode_fs=b.kode_fs
            where a.kode_lokasi='$kode_lokasi' and a.kode_fs='FS4' and a.periode='$periode' and b.kode_grafik='D03'
            order by b.nu";
            $rs = dbResultArray($sql);
            $response['opr'] = $rs[0]["capai"];
            $response['nonopr'] = $rs[1]["capai"]; 
            
            $response['status'] = TRUE;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }

    function getData(){
        $string = file_get_contents("sampleData2.json");
        echo $string;

    }

?>