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
        case 'PUT':
            updateSiswa();
        break;
        case 'DELETE':
            deleteSiswa();
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

                $sql2 = "select server_key from api_server_key where kode_lokasi='$row->kode_lokasi' and  modul='SISWA' ";
                $rs2=execute($sql2);
                if($rs2->RecordCount()>0){
                    $serverKey = $rs2->fields[0];
                    // create a token
                    $payloadArray = array();
                    $payloadArray['userId'] = $userId;
                    if (isset($nbf)) {$payloadArray['nbf'] = $nbf;}
                    if (isset($exp)) {$payloadArray['exp'] = $exp;}
                    $token = JWT::encode($payloadArray, $serverKey);
                    // return to caller
                    $response = array('token' => $token,'status'=>true,'message'=>'Login Success');


                }else{
                    $response = array('status'=>false,'message'=>'Server key does not exist','sql'=>$sql2);
                }
    
            } 
            else {
                $response = array('message' => 'Invalid user ID or password.','status'=>false);
               
            }

        }else{
            $response = array('message' => "Kode Lokasi, Modul, and NIK required",'status'=>false);
        }
        
        echo json_encode($response);

    }

    function authKey($token, $modul,$kode_lokasi){
        getKoneksi();
        $token = $token;
        $modul = qstr($modul);
        $kode_lokasi = qstr($kode_lokasi);
        $date = date('Y-m-d H:i:s');

        $schema = db_Connect();
        $sql = "SELECT server_key FROM api_server_key where modul=$modul and kode_lokasi=$kode_lokasi ";
        $auth = execute($sql);
        if($auth->RecordCount() > 0){
           
            $serverKey = $auth->fields[0];

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
        }else{
            $returnArray = array('message' => 'serverKey does not exist','status'=>false);
        }
        return $returnArray;
    }

    function getSiswa(){
        getKoneksi();
        $data = $_GET;
        $res = authKey($data["token"],"SISWA",$data["kode_lokasi"]);
        if($res["status"]){ 
    
            $kode_lokasi=$data['kode_lokasi'];
            
            if (isset($data['no_peserta']) || $data['no_peserta'] != "") {
                $no_peserta = $data['no_peserta'];
                $filterno_peserta = " and a.no_peserta='$no_peserta' ";
            
            }else{
                $filterno_peserta = "";
            }

            $response = array("message" => "", "rows" => 0, "status" => "" );
           
            $sql="select *
            from dgw_peserta a
            where a.kode_lokasi='$kode_lokasi' $filterno_peserta";

            $response['daftar'] = dbResultArray($sql);
            $response['status'] = true;
            $response['rows']=count($response['daftar']);
        }else{
            $response['status'] = false;
            $response['message'] = "Unauthorized Access ".$res["message"];
            
        }
        header('Content-Type: application/json');
        echo json_encode($response);

    }

    function addSiswa() {
        getKoneksi();
         
        $data = $_POST;
        $res = authKey($data["token"],"SISWA",$data["kode_lokasi"]);
        if($res["status"]){
            $no_peserta=qstr($data["no_peserta"]);
            $kode_lokasi=qstr($data["kode_lokasi"]);
            $nama=qstr($data["nama"]);
            $kode_jur=qstr($data["kode_jur"]);
            
            if($no_peserta != "" AND $kode_lokasi != ""){
                $query=" insert into dgw_peserta (no_peserta,kode_lokasi,nama,kode_jur)
                values ($no_peserta,$kode_lokasi,$nama,$kode_jur) ";
                
                $sql = array();
                array_push($sql,$query);
                
                $rs=executeArray($sql);
                if($rs) {
                    $response=array(
                        'status' => true,
                        'message' =>'Siswa Added Successfully.'
                    );
                }
                else {
                    $response=array(
                        'status' => false,
                        'message' =>'Siswa Addition Failed.'
                    );
                    
                }
            }else{
                $response['error'] = "no_peserta and Kode Lokasi Required";
            }
        }else{
            $response['status'] = false;
            $response['message'] = "Unauthorized Access ".$res["message"];
            
        }
        echo json_encode($response);
    }

     function updateSiswa() {
        getKoneksi();
        parse_str(file_get_contents('php://input'), $_PUT);
        $data = $_PUT;
        $res = authKey($data["token"],"SISWA",$data["kode_lokasi"]);
        if($res["status"]){
            $no_peserta=qstr($data["no_peserta"]);
            $kode_lokasi=qstr($data["kode_lokasi"]);
            $nama=qstr($data["nama"]);
            $kode_jur=qstr($data["kode_jur"]);
            $query="UPDATE dgw_peserta SET nama = $nama,
            kode_jur = $kode_jur
            WHERE no_peserta = $no_peserta and kode_lokasi = $kode_lokasi ";
            $sql = array();
            array_push($sql,$query);
            
            $rs=executeArray($sql);
            if($rs) {
                $response=array(
                    'status' => true,
                    'message' =>'siswa Updated Successfully.'
                );
            }
            else {
                $response=array(
                    'status' => false,
                    'message' =>'siswa Updation Failed.'.$rs
                );
            }
        }else{
            $response['status'] = false;
            $response['message'] = "Unauthorized Access ".$res["message"];
        }
        echo json_encode($response);
    }

    function deleteSiswa() {
        getKoneksi();
        parse_str(file_get_contents('php://input'), $_DELETE);
        $data = $_DELETE;
        $res = authKey($data["token"],"SISWA",$data["kode_lokasi"]);
        if($res["status"]){
            $id = qstr($_DELETE["no_peserta"]);
            $kd_lok = qstr($_DELETE["kode_lokasi"]);
            
            $query="DELETE FROM dgw_peserta where no_peserta=$id and kode_lokasi = $kd_lok" ;
            
            $sql = array();
            array_push($sql,$query);
            
            $rs=executeArray($sql);
            if($rs) {
                $response=array(
                    'status' => true,
                    'message' =>'Siswa Deleted Successfully.'
                );
            }
            else {
                $response=array(
                    'status' => false,
                    'message' =>'Siswa Deletion Failed.'
                );
            }                
        }else{
            $response['error'] = "Unauthorized Access 1";
        }
        echo json_encode($response);
    }
  


?>
