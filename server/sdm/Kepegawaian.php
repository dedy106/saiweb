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
    $root=realpath($_SERVER["DOCUMENT_ROOT"])."/";
    include_once($root."lib/koneksi.php");
    include_once($root."lib/helpers.php");
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
function getKepegawaian(){
    session_start();
    getKoneksi();
    if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){

        $kode_lokasi=$_GET['kode_lokasi'];
        $nik=$_GET['nik_user'];
        $response = array("message" => "", "rows" => 0, "status" => "" );

        $sql="select kode_pp, kode_sdm,kode_gol,kode_jab,kode_loker,kode_unit,tahun_masuk, no_sk,convert(varchar,tgl_sk,23) as tgl_sk,gelar_depan,gelar_belakang,convert(varchar,tgl_masuk,23) as tgl_masuk,ijht,bpjs,jp,mk_gol,mk_ytb,convert(varchar,tgl_kontrak,23) as tgl_kontrak,no_kontrak 
        from hr_karyawan
        where kode_lokasi ='$kode_lokasi' and nik='$nik' ";

        $rs=execute($sql);			
        
        $response['daftar'] = array();
        while ($row = $rs->FetchNextObject(false)){
            $response['daftar'][] = (array)$row;
        }
        $response['status']=TRUE;
        $response['sql']=$sql1;
    }else{
        $response["status"] = false;
        $response["message"] = "Unauthorized Access, Login Required";
    }
    header('Content-Type: application/json');
    echo json_encode($response);
}

function ubahKepegawaian(){
    session_start();
    getKoneksi();
    if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
        $nik=$_POST['nik_user'];
        $kode_lokasi=$_POST['kode_lokasi'];
        
        $dbconn = db_Connect();

        $response = array("message" => "", "rows" => 0, "status" => "" );
        $error_upload = "not found";

        $upd= array(
            'gelar_depan'=>$_POST['gelar_depan'],
            'gelar_belakang'=>$_POST['gelar_belakang'],
            'kode_sdm'=>$_POST['kode_sdm'],
            'kode_gol'=>$_POST['kode_gol'],
            'kode_jab'=>$_POST['kode_jab'],
            'kode_unit'=>$_POST['kode_unit'],
            'kode_pp'=>$_POST['kode_pp'],
            'kode_loker'=>$_POST['kode_loker'],
            'ijht'=>$_POST['status_ijht'],
            'bpjs'=>$_POST['status_bpjs'],
            'jp'=>$_POST['status_jp'],
            'mk_gol'=>$_POST['mk_gol'],
            'tgl_masuk'=>$_POST['tgl_masuk'],
            'no_sk'=>$_POST['no_sk'],
            'mk_ytb'=>$_POST['mk_ytb'],
            'tgl_sk'=>$_POST['tgl_sk'],
            'no_kontrak'=>$_POST['no_kontrak'],
           
        );
        
        $update = $dbconn->AutoExecute("hr_karyawan", $upd, 'UPDATE', " nik='".$nik."' and kode_lokasi='".$kode_lokasi."' ");

        if($update){
            $sts=TRUE;
            $msg="berhasil";
        }else{
            $sts=FALSE;
            $msg="gagal";
        }

        $response['status'] = $sts;
        $response['message'] = $msg;
        $response['error'] = $error_upload;
        $response['Update'] = $upd;
    }else{
        $response["status"] = false;
        $response["message"] = "Unauthorized Access, Login Required";
    }
    // header('Content-Type: application/json');
    echo json_encode($response);

}

function getSDM(){
    session_start();
    getKoneksi();
    if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
        $kode_lokasi=$_GET['kode_lokasi'];
        $response = array("message" => "", "rows" => 0, "status" => "" );

        $sql = "SELECT kode_sdm,nama from hr_sdm where kode_lokasi = '".$kode_lokasi."' ";

        $rs=execute($sql);			
        
        $response['daftar'] = array();
        while ($row = $rs->FetchNextObject(false)){
            $response['daftar'][] = (array)$row;
        }
        $response['status']=TRUE;
        $response['sql']=$sql1;
    }else{
        $response["status"] = false;
        $response["message"] = "Unauthorized Access, Login Required";
    }
    header('Content-Type: application/json');
    echo json_encode($response);

}

function getGol(){
    session_start();
    getKoneksi();
    if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
        $kode_lokasi=$_GET['kode_lokasi'];
        $response = array("message" => "", "rows" => 0, "status" => "" );

        $sql = "SELECT kode_gol,nama from hr_gol where kode_lokasi = '".$kode_lokasi."' ";

        $rs=execute($sql);			
        
        $response['daftar'] = array();
        while ($row = $rs->FetchNextObject(false)){
            $response['daftar'][] = (array)$row;
        }
        $response['status']=TRUE;
        $response['sql']=$sql1;
    }else{
        $response["status"] = false;
        $response["message"] = "Unauthorized Access, Login Required";
    }
    header('Content-Type: application/json');
    echo json_encode($response);
}

function getJab(){
    session_start();
    getKoneksi();
    if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
        $kode_lokasi=$_GET['kode_lokasi'];
        $response = array("message" => "", "rows" => 0, "status" => "" );

        $sql = "SELECT kode_jab,nama from hr_jab where kode_lokasi = '".$kode_lokasi."' ";

        $rs=execute($sql);			
        
        $response['daftar'] = array();
        while ($row = $rs->FetchNextObject(false)){
            $response['daftar'][] = (array)$row;
        }
        $response['status']=TRUE;
        $response['sql']=$sql1;
    }else{
        $response["status"] = false;
        $response["message"] = "Unauthorized Access, Login Required";
    }
    header('Content-Type: application/json');
    echo json_encode($response);
}

function getUnit(){
    session_start();
    getKoneksi();
    if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
        $kode_lokasi=$_GET['kode_lokasi'];
        $response = array("message" => "", "rows" => 0, "status" => "" );

        $sql = "SELECT kode_unit,nama from hr_unit where kode_lokasi = '".$kode_lokasi."' ";

        $rs=execute($sql);			
        
        $response['daftar'] = array();
        while ($row = $rs->FetchNextObject(false)){
            $response['daftar'][] = (array)$row;
        }
        $response['status']=TRUE;
        $response['sql']=$sql1;
    }else{
        $response["status"] = false;
        $response["message"] = "Unauthorized Access, Login Required";
    }
    header('Content-Type: application/json');
    echo json_encode($response);
}

function getPP(){
    session_start();
    getKoneksi();
    if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
        $kode_lokasi=$_GET['kode_lokasi'];
        $response = array("message" => "", "rows" => 0, "status" => "" );

        $sql = "SELECT kode_pp,nama from pp where kode_lokasi = '".$kode_lokasi."' ";

        $rs=execute($sql);			
        
        $response['daftar'] = array();
        while ($row = $rs->FetchNextObject(false)){
            $response['daftar'][] = (array)$row;
        }
        $response['status']=TRUE;
        $response['sql']=$sql1;
    }else{
        $response["status"] = false;
        $response["message"] = "Unauthorized Access, Login Required";
    }
    header('Content-Type: application/json');
    echo json_encode($response);
}

function getLoker(){
    session_start();
    getKoneksi();
    if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
        $kode_lokasi=$_GET['kode_lokasi'];
        $response = array("message" => "", "rows" => 0, "status" => "" );

        $sql = "SELECT kode_loker,nama from hr_loker where kode_lokasi = '".$kode_lokasi."' ";

        $rs=execute($sql);			
        
        $response['daftar'] = array();
        while ($row = $rs->FetchNextObject(false)){
            $response['daftar'][] = (array)$row;
        }
        $response['status']=TRUE;
        $response['sql']=$sql1;
    }else{
        $response["status"] = false;
        $response["message"] = "Unauthorized Access, Login Required";
    }
    header('Content-Type: application/json');
    echo json_encode($response);

}




?>
