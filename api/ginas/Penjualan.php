<?php
	ini_set('max_execution_time', '500');
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
        // case 'PUT':
        //     updatePeserta();
        // break;
        case 'DELETE':
            deletePeserta();
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
                $serverKey="b1b23c96bb8ecbf68bfba702a8e232b5";
                //SET EXPIRED :
                // $unixTime = time();
                // $nbf  = $unixTime;             
                // $exp     = $nbf + (60 * 60);  // 1 jam
                // create a token
                $payloadArray = array();
                $payloadArray['userId'] = $userId;
                $payloadArray['kode_lokasi'] = $row->kode_lokasi;
                if (isset($nbf)) {$payloadArray['nbf'] = $nbf;}
                if (isset($exp)) {$payloadArray['exp'] = $exp;}
                $token = JWT::encode($payloadArray, $serverKey);
                // return to caller
                $response = array('token' => $token,'status'=>true,'message'=>'Login Success');

            } 
            else {
                $response = array('message' => 'Invalid user ID or password.','status'=>false);
               
            }

        }else{
            $response = array('message' => "Kode Lokasi, Modul, and NIK required",'status'=>false);
        }
        
        echo json_encode($response);

    }

    function authKey($token){
        getKoneksi();
        $token = $token;
        $date = date('Y-m-d H:i:s');
        $serverKey="b1b23c96bb8ecbf68bfba702a8e232b5";
        try {
            $payload = JWT::decode($token, $serverKey, array('HS256'));
            if(isset($payload->userId)  || $payload->userId != ''){
                if (isset($payload->exp)) {
                    $returnArray['exp'] = date(DateTime::ISO8601, $payload->exp);

                    if (isset($payload->kode_lokasi)) {
                        $returnArray['kode_lokasi'] = $payload->kode_lokasi;
                    }

                    if ($payload->exp < time()) {
                        $returnArray['status'] = false;
                        $returnArray['message'] = "Your token was expired";
                    } else {
                        $returnArray['status'] = true;
                    }
                }else{

                    if (isset($payload->kode_lokasi)) {
                        $returnArray['kode_lokasi'] = $payload->kode_lokasi;
                    }
                    $returnArray['status'] = true;
                }
            }
            
        }
        catch(Exception $e) {
            $returnArray = array('message' => $e->getMessage(),'status'=>false);
        }
       
        return $returnArray;
    }

    
    function generateKode($tabel, $kolom_acuan, $prefix, $str_format){
        $query = execute("select right(max($kolom_acuan), ".strlen($str_format).")+1 as id from $tabel where $kolom_acuan like '$prefix%'");
        $kode = $query->fields[0];
        $id = $prefix.str_pad($kode, strlen($str_format), $str_format, STR_PAD_LEFT);
        return $id;
    }

    function addPenjualan() {
        getKoneksi();
         
        $data = $_POST;
        $header = getallheaders();
		$bearer = $header["Authorization"];
		list($token) = sscanf($bearer, 'Bearer %s');
        $res = authKey($token);
        if($res["status"]){

            if(isset($data['kode_lokasi']) && $data['kode_lokasi'] != ""){

                $kode_lokasi=$data['kode_lokasi'];
            }else{
                $kode_lokasi=$res["kode_lokasi"];
            }

            array_push($exec,$query);

            $rs=executeArray($exec);
            if($rs) {
                $response=array(
                    'status' => true,
                    'message' =>'Synchronize Data Successfully.'
                );
            }
            else {
                $response=array(
                    'status' => false,
                    'message' =>'Synchronize Data Failed.'
                );
                
            }
          
        }else{
            $response['status'] = false;
            $response['message'] = "Unauthorized Access ".$res["message"];
            
        }
        echo json_encode($response);
    }

    


?>
