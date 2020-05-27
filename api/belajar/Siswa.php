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
                $serverKey="b1b23c96bb8ecbf68bfba702a8e232b5";
                // create a token
                $unixTime = time();
                $nbf  = $unixTime;             
                $exp     = $nbf + (2 * 60);  // 2 menit
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

    function getSiswa(){
        getKoneksi();
        $data = $_GET;

        $header = getallheaders();
		$bearer = $header["Authorization"];
		list($token) = sscanf($bearer, 'Bearer %s');
        // error_log("Token ". print_r($token, true));
        // $token = base64_decode($token);    

        $res = authKey($token);
        if($res["status"]){ 
    
            if(isset($data['kode_lokasi']) && $data['kode_lokasi'] != ""){
                $kode_lokasi=$data['kode_lokasi'];
            }else{
                $kode_lokasi=$res["kode_lokasi"];
            }
            
            if (isset($data['nim']) || $data['nim'] != "") {
                $nim = $data['nim'];
                $filternim = " and a.nim='$nim' ";
            
            }else{
                $filternim = "";
            }

            $response = array("message" => "", "rows" => 0, "status" => "" );
           
            $sql="select *
            from dev_siswa a
            where a.kode_lokasi='$kode_lokasi' $filternim";

            $response['daftar'] = dbResultArray($sql);
            $response['status'] = true;
            $response['rows']=count($response['daftar']);
            $response['message'] = $res["message"]."exp:".$res["exp"];
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
        $header = getallheaders();
		$bearer = $header["Authorization"];
		list($token) = sscanf($bearer, 'Bearer %s');
        // error_log("Token ". print_r($token, true));
        // $token = base64_decode($token);    

        $res = authKey($token);
        if($res["status"]){
            $nim=qstr($data["nim"]);
            if(isset($data['kode_lokasi']) && $data['kode_lokasi'] != ""){
                $kode_lokasi=qstr($data['kode_lokasi']);
            }else{
                $kode_lokasi=qstr($res["kode_lokasi"]);
            }

            $nama=qstr($data["nama"]);
            $kode_jur=qstr($data["kode_jur"]);
            
            if($nim != "" AND $kode_lokasi != ""){
                $query=" insert into dev_siswa (nim,kode_lokasi,nama,kode_jur)
                values ($nim,$kode_lokasi,$nama,$kode_jur) ";
                
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
                $response['error'] = "NIM and Kode Lokasi Required";
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
        $data = $_POST;
        $header = getallheaders();
		$bearer = $header["Authorization"];
		list($token) = sscanf($bearer, 'Bearer %s');
        // error_log("Token ". print_r($token, true));
        // $token = base64_decode($token);    

        $res = authKey($token);
        if($res["status"]){
            $nim=qstr($data["nim"]);
            if(isset($data['kode_lokasi']) && $data['kode_lokasi'] != ""){
                $kode_lokasi=qstr($data['kode_lokasi']);
            }else{
                $kode_lokasi=qstr($res["kode_lokasi"]);
            }
            $nama=qstr($data["nama"]);
            $kode_jur=qstr($data["kode_jur"]);
            $query="UPDATE dev_siswa SET nama = $nama,
            kode_jur = $kode_jur
            WHERE nim = $nim and kode_lokasi = $kode_lokasi ";
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
        getKoneksi();
        parse_str(file_get_contents('php://input'), $_PUT);
        $data = $_PUT;
        $data = $_POST;
        $header = getallheaders();
		$bearer = $header["Authorization"];
		list($token) = sscanf($bearer, 'Bearer %s');
        // error_log("Token ". print_r($token, true));
        // $token = base64_decode($token);    
        $res = authKey($token);
        if($res["status"]){
            $id = qstr($_DELETE["nim"]);
            if(isset($data['kode_lokasi']) && $data['kode_lokasi'] != ""){
                $kode_lokasi=qstr($data['kode_lokasi']);
            }else{
                $kode_lokasi=qstr($res["kode_lokasi"]);
            }
            
            $query="DELETE FROM dev_siswa where nim=$id and kode_lokasi = $kode_lokasi" ;
            
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

    function tesCurl(){
        var_dump($_POST);
    }

    function tesJSON(){
        getKoneksi();
        $sql="select a.*,b.form  from menu a 
        left join m_form b on a.kode_form=b.kode_form 
        where a.kode_klp = 'SDM' and isnull(a.jenis_menu,'-') = '-' order by kode_klp, rowindex";
        $rs=execute($sql);
        //$daftar_menu = $rs->GetRowAssoc(); 
        $i=1;
        $daftar = array();
        while ($row = $rs->FetchNextObject($toupper=false))
        {
            // $daftar_menu[] = (array)$row;
            if($row->level_menu == 0){

                $sub_data["id"] = $i;
            }else{
            
                $sub_data["id"] = $row->kode_menu;
            }
            $sub_data["name"] = $row->nama;
            $sub_data["text"] = $row->nama;
            $sub_data["level_menu"] = $row->level_menu;
            $daftar[] = $sub_data;
            $i++;
        }
        
        $pre_prt = 0;
        $parent_array = array();
        for($i=0; $i<count($daftar); $i++){
            // $forms = str_replace("_","/", $daftar[$i]["form"]);
            // $this_lv = $daftar[$i]['level_menu']; // t1 lv=0
            // // $this_link = "fMain.php?hal=".$forms.".php";
            // $forms = explode("/",$forms);
            // $this_link = "$root_app/".$forms[2];
            
            
            if(!ISSET($daftar[$i-1]['level_menu'])){
                $prev_lv = 0; // t1 pv=0
            }else{
                $prev_lv = $daftar[$i-1]['level_menu'];
            }
            
            if(!ISSET($daftar[$i+1]['level_menu'])){
                $next_lv = $daftar[$i]['level_menu'];
            }else{
                $next_lv = $daftar[$i+1]['level_menu']; //t1 nv=1
            }


            if($daftar[$i]['level_menu'] == 0){
                $daftar[$i]['parent_id'] = 0;
                $prev_prt = $daftar[$i]['id'];
            }else if($daftar[$i]['level_menu'] == 1){
                $daftar[$i]['parent_id'] = $prev_prt;
                $prev_prt2 =  $daftar[$i]['id'];
            }else if($daftar[$i]['level_menu'] == 2){
                $daftar[$i]['parent_id'] = $prev_prt2;
                $prev_prt3 =  $daftar[$i]['id'];
            }
            
        }

        
        foreach($daftar as $key => &$value)
        {
            $output[$value["id"]] = &$value;
        }

        
        
        foreach($daftar as $key => &$value)
        {
            if($value["parent_id"] && isset($output[$value["parent_id"]]))
            {
                $output[$value["parent_id"]]["nodes"][] = &$value;
            }
        }
        
        foreach($daftar as $key => &$value)
        {
            if($value["parent_id"] && isset($output[$value["parent_id"]]))
            {
                unset($daftar[$key]);
            }
        }

        foreach($daftar as $key => &$value)
        {
            $data [] = &$value;
        }

        echo json_encode($data);

    }


?>
