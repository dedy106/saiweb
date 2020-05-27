<?php
$request_method=$_SERVER["REQUEST_METHOD"];

switch($request_method) {
    case 'GET':
        if(isset($_GET["fx"]) AND function_exists($_GET['fx'])){
            $_GET['fx']();
        }
    break;
}

function getKoneksi(){
    $root_lib=$_SERVER["DOCUMENT_ROOT"];
    include_once($root_lib."lib/koneksi.php");
    include_once($root_lib."lib/helpers.php");
}
function authKey2($key, $modul, $user=null){
    getKoneksi();
    $key = qstr($key);
    $modul = qstr($modul);
    $date = date('Y-m-d H:i:s');
    $user_str = "";
    if($user != null){
        $user = qstr($user);
        $user_str .= "AND nik = $user";
    }

    $schema = db_Connect();
    $auth = $schema->SelectLimit("SELECT * FROM api_key_auth where api_key=$key and expired > '$date' and modul=$modul $user_str", 1);
    if($auth->RecordCount() > 0){
        
        $date = new DateTime($date);
        $date->add(new DateInterval('PT1H'));
        $WorkingArray = json_decode(json_encode($date),true);
        $expired = explode(".",$WorkingArray["date"]);

        $db_key["expired"] = $expired[0];
        $key_sql = $schema->AutoExecute('api_key_auth', $db_key, 'UPDATE', "api_key=$key and modul=$modul");
        return true;
    }else{
        return false;
    }
}


function authKey($key, $modul, $user=null){
    getKoneksi();
    $key = qstr($key);
    $modul = qstr($modul);
    $date = date('Y-m-d H:i:s');
    $user_str = "";
    if($user != null){
        $user = qstr($user);
        $user_str .= "AND nik = $user";
    }

    $schema = db_Connect();
    $auth = $schema->SelectLimit("SELECT * FROM api_key_auth where api_key=$key and modul=$modul $user_str ", 1);
    if($auth->RecordCount() > 0){
        return true;
    }else{
        return false;
    }
}

function getTagihan(){
    getKoneksi();
    $data = $_GET;
    if(arrayKeyCheck(array('api_key', 'username', 'kode_lokasi'), $data)){
        if(authKey($data["api_key"], 'proyek', $data['username'])){
            $id=$data['no_bill'];
            $kode_lokasi=$data['kode_lokasi'];    
            
            $response = array("message" => "", "rows" => 0, "status" => "" );
            
            $sql="select a.*, c.jenis_pph42, x.no_rab,d.no_bill,convert(varchar,d.tanggal,103) as tgl_bill,d.keterangan as ket_bil,
            d.nilai as nilai_bil,d.diskon,d.nilai_ppn as ppn_bil,d.no_valid 
            from prb_proyek a 
            inner join prb_prbill_m d on a.kode_proyek=d.kode_proyek and a.kode_lokasi=d.kode_lokasi 
            inner join prb_proyek_jenis c on a.kode_jenis=c.kode_jenis and a.kode_lokasi=c.kode_lokasi 
            inner join prb_rabapp_m x on a.kode_proyek=x.kode_proyek and a.kode_lokasi=x.kode_lokasi
            where a.kode_lokasi='$kode_lokasi' and d.no_bill='$id' ";
            
            $response['daftar'] = dbResultArray($sql);
            $response['rows']= count($response['daftar']);
            $response['status'] = TRUE;
            // $response['sql'] = $sql;
        }else{
            $response['status']=false;
            $response['message'] = "Unauthorized Access 2";
        }
    }else{
        $response['status']=false;
        $response['message'] = "Unauthorized Access 1";
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
    
}

function getTagihanDetail(){
    getKoneksi();
    $data = $_GET;
    if(arrayKeyCheck(array('api_key', 'username', 'kode_lokasi'), $data)){
        if(authKey($data["api_key"], 'proyek', $data['username'])){
            $id=$data['no_bill'];
            $kode_lokasi=$data['kode_lokasi'];    
            
            $response = array("message" => "", "rows" => 0, "status" => "" );
            
            $sql="select a.keterangan,a.jumlah,a.harga,a.total 
            from prb_prbill_d a 
            where a.kode_lokasi='$kode_lokasi' and a.no_bill='$id'
            order by a.nu
            ";
            
            $response['daftar'] = dbResultArray($sql);
            $response['rows']= count($response['daftar']);
            $response['status'] = TRUE;
            // $response['sql'] = $sql;
        }else{
            $response['status']=false;
            $response['message'] = "Unauthorized Access 2";
        }
    }else{
        $response['status']=false;
        $response['message'] = "Unauthorized Access 1";
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
    
}

function getTagihanDok(){
    getKoneksi();
    $data = $_GET;
    if(arrayKeyCheck(array('api_key', 'username', 'kode_lokasi'), $data)){
        if(authKey($data["api_key"], 'proyek', $data['username'])){
            $no_ref=$data['no_ref'];
            $kode_lokasi=$data['kode_lokasi'];    
            
            $response = array("message" => "", "rows" => 0, "status" => "" );
            
            $sql="select b.kode_jenis,b.nama,a.no_gambar 
            from prb_rab_dok a 
            inner join prb_dok_jenis b on a.kode_jenis=b.kode_jenis and a.kode_lokasi=b.kode_lokasi 
            where a.kode_lokasi='$kode_lokasi' and a.nu<>888  and a.no_ref='$no_ref'
            order by a.nu
            ";
            
            $response['daftar'] = dbResultArray($sql);
            $response['status'] = TRUE;
            $response['rows']= count($response['daftar']);
            // $response['sql'] = $sql;
        }else{
            $response['status']=false;
            $response['message'] = "Unauthorized Access 2";
        }
    }else{
        $response['status']=false;
        $response['message'] = "Unauthorized Access 1";
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
    
}


?>
