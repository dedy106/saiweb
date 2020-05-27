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
                // echo "<script>alert('ini')</script>";
                // insertDok();
                insertDok();
            }
        break;
        case 'PUT':
        // Update Product
           
            updateDok();
        break;
        case 'DELETE':
        // Delete Product
            deleteDok();
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

    function generateKode($tabel, $kolom_acuan, $prefix, $str_format){
        $query = execute("select right(max($kolom_acuan), ".strlen($str_format).")+1 as id from $tabel where $kolom_acuan like '$prefix%'");
        $kode = $query->fields[0];
        $id = $prefix.str_pad($kode, strlen($str_format), $str_format, STR_PAD_LEFT);
        return $id;
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

    function getProses(){
        session_start();
        getKoneksi();
        $data = $_GET;
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'],$_SESSION['userPwd'])){
            if ($_SESSION['token']==$data['token'] AND $data['token'] !=""){ 
                $response = array("message" => "", "rows" => 0, "status" => "" ); 				
                $sql = "select kode_proses,nama from sai_proses where kode_lokasi='11' ";
                $rs = execute($sql);
                while($row = $rs->FetchNextObject($toupper))
                {
                    $response['daftar'][] = (array)$row;
                }
                
                $response['status'] = true;
            }else{
                $response['status']=false;
                $response["message"] = "Unauthorized Access, Token Invalid";
            }
        }else{
            $response['status']=false;
            $response["message"] = "Unauthorized Access, Login Required";
        }

        header('Content-Type: application/json');
        echo json_encode($response);
    }

    function getDatatableDok(){
        session_start();
        getKoneksi();        
        $data = $_POST;
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'],$_SESSION['userPwd'])){
            if ($_SESSION['token']==$data['token'] AND $data['token'] !=""){ 
                $query = '';
                $output = array();
                $kode_lokasi = $_SESSION['lokasi'];
                $query .= "select kode_dok, nama,kode_proses from sai_dok where kode_lokasi = '$kode_lokasi'";
                
                $column_array = array('kode_dok','nama','kode_proses');
                $order_column = 'order by kode_dok '.$data['order'][0]['dir'];
                $column_string = join(',', $column_array);
                
                $res = execute($query);
                $jml_baris = $res->RecordCount();
                if(!empty($data['search']['value']))
                {
                    $search = $data['search']['value'];
                    $filter_string = " and (";
                    
                    for($i=0; $i<count($column_array); $i++){
                        
                        if($i == (count($column_array) - 1)){
                            $filter_string .= $column_array[$i]." like '".$search."%' )";
                        }else{
                            $filter_string .= $column_array[$i]." like '".$search."%' or ";
                        }
                    }
                    
                    
                    $query.=" $filter_string ";
                }
                
                if(isset($data["order"]))
                {
                    $query .= ' order by '.$column_array[$data['order'][0]['column']].' '.$data['order'][0]['dir'];
                }
                else
                {
                    $query .= ' order by kode_dok ';
                }
                if($data["length"] != -1)
                {
                    $query .= ' offset ' . $data['start'] . ' rows fetch next ' . $data['length'] . ' rows only ';
                }
                $statement = execute($query);
                $data = array();
                $filtered_rows = $statement->RecordCount();
                while($row = $statement->FetchNextObject($toupper=false))
                {
                    $sub_array = array();
                    $sub_array[] = $row->kode_dok;
                    $sub_array[] = $row->nama;
                    $sub_array[] = $row->kode_proses;
                    $data[] = $sub_array;
                }
                $response = array(
                    "draw"				=>	intval($data["draw"]),
                    "recordsTotal"		=> 	$filtered_rows,
                    "recordsFiltered"	=>	$jml_baris,
                    "data"				=>	$data,
                );
            }else{
                $response['status']=false;
                $response["message"] = "Unauthorized Access, Token Invalid";
            }
        }else{
            $response['status']=false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    function insertDok(){
        session_start();
        getKoneksi();
        $data = $_POST;
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'],$_SESSION['userPwd'])){
            if ($_SESSION['token']==$data['token'] AND $data['token'] !=""){ 
                
                $kode_dok = qstr(generateKode("sai_dok", "kode_dok", "DK", "01"));
                // $kode_dok = qstr($data["kode_dok"]);
                $kode_lokasi=qstr($_SESSION["lokasi"]);
                $kode_proses=qstr($data["kode_proses"]);
                $nama=qstr($data["nama"]);
                
                $query2=" insert into sai_dok (kode_dok,kode_lokasi,nama,kode_proses)
                values ($kode_dok,$kode_lokasi,$nama,$kode_proses) ";
                
                $sql = array();
                array_push($sql,$query2);
                
                $rs=executeArray($sql);
                if($rs) {
                    $response=array(
                        'status' => 1,
                        'message' =>'dokumen sukses',
                        'query' => $sql
                    );
                }
                else {
                    $response=array(
                        'status' => 0,
                        'message' =>'dokumen gagal'.$rs,
                        'query' => $sql
                    );
                    
                }
            }else{
                $response['status']=false;
                $response["message"] = "Unauthorized Access, Token Invalid";
            }
        }else{
            $response['status']=false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }

    function getEditDok(){
        session_start();
        getKoneksi();
        $data = $_GET;
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'],$_SESSION['userPwd'])){
            if ($_SESSION['token']==$data['token'] AND $data['token'] !=""){ 
                $sql="select kode_dok,nama,kode_proses
                from sai_dok
                where kode_lokasi= '".$_SESSION['lokasi']."' and kode_dok='".$_GET['kode_dok']."' ";
                
                $rs=execute($sql);
                
                $response["daftar"] = array();
                while($row = $rs->FetchNextObject($toupper = false)){
                    $response["daftar"][] = (array)$row;
                }
                $response['status']=true;
            }else{
                $response['status']=false;
                $response["message"] = "Unauthorized Access, Token Invalid";
            }
        }else{
            $response['status']=false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    function updateDok() {
        session_start();
        getKoneksi();
        parse_str(file_get_contents('php://input'), $_PUT);
        $data = $_PUT;
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'],$_SESSION['userPwd'])){
            if ($_SESSION['token']==$data['token'] AND $data['token'] !=""){

                $kode_dok=qstr($data["kode_dok"]);
                $kode_lokasi=qstr($_SESSION["lokasi"]);
                $nama=qstr($data["nama"]);
                $kode_proses=qstr($data["kode_proses"]);
                $query="UPDATE sai_dok SET nama = $nama, kode_proses = $kode_proses
                WHERE kode_dok = $kode_dok and kode_lokasi = $kode_lokasi ";
                $sql = array();
                array_push($sql,$query);
                
                $rs=executeArray($sql);
                if($rs) {
                    $response=array(
                        'status' => 1,
                        'message' =>'dokumen berhasil ',
                        'query'=>$query,
                        'request'=>$_PUT
                    );
                }
                else {
                    $response=array(
                        'status' => 0,
                        'message' =>'dokumen gagal '.$rs,
                        'query'=>$query,
                        'request'=>$_PUT
                    );
                }
            }else{
                $response['status']=false;
                $response["message"] = "Unauthorized Access, Token Invalid";
            }
        }else{
            $response['status']=false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    function deleteDok() {
        session_start();
        getKoneksi();
        parse_str(file_get_contents('php://input'), $_DELETE);
        $data = $_DELETE;
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'],$_SESSION['userPwd'])){
            if ($_SESSION['token']==$data['token'] AND $data['token'] !=""){
                
                $id = qstr($data["kode_dok"]);
                $kd_lok = qstr($_SESSION["lokasi"]);
                
                $query="DELETE FROM sai_dok where kode_dok=$id and kode_lokasi = $kd_lok" ;
                
                $sql = array();
                array_push($sql,$query);
                
                $rs=executeArray($sql);
                if($rs) {
                    $response=array(
                        'status' => 1,
                        'message' =>'dokumen berhasil',
                        'sql'=>$sql
                    );
                }
                else {
                    $response=array(
                        'status' => 0,
                        'message' =>'dokumen gagal'.$rs,
                        'sql'=>$sql
                    );
                }
            }else{
                $response['status']=false;
                $response["message"] = "Unauthorized Access, Token Invalid";
            }
        }else{
            $response['status']=false;
            $response['error'] = "Unauthorized Access 2";
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }
?>