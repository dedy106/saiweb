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
            else{
                insertProduk();
            }
        break;
        case 'PUT':
        // Update Product
        
            updateProduk();
        break;
        case 'DELETE':
        // Delete Product
            deleteProduk();
        break;
        default:
            // Invalid Request Method
            header("HTTP/1.0 405 Method Not Allowed");
        break;
    }

    
    function generateKode($tabel, $kolom_acuan, $prefix, $str_format){
        $query = execute("select right(max($kolom_acuan), ".strlen($str_format).")+1 as id from $tabel where $kolom_acuan like '$prefix%'");
        $kode = $query->fields[0];
        $id = $prefix.str_pad($kode, strlen($str_format), $str_format, STR_PAD_LEFT);
        return $id;
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

    function getDatatableProduk(){
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'],$_SESSION['userPwd'])){
            if ($_SESSION['token']==$_POST['token'] AND $_POST['token'] !=""){             
                $data = $_POST;
          
                $query = '';
                $output = array();
                
                $kode_lokasi = $_SESSION['lokasi'];
                $query .= "select kode_produk, nama from sai_produk where kode_lokasi = '$kode_lokasi'";
                
                $column_array = array('kode_produk','nama');
                $order_column = 'order by kode_produk '.$data['order'][0]['dir'];
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
                    $query .= ' order by kode_produk ';
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
                    $sub_array[] = $row->kode_produk;
                    $sub_array[] = $row->nama;
                    $data[] = $sub_array;
                }
                $response = array(
                    "draw"				=>	intval($data["draw"]),
                    "recordsTotal"		=> 	$filtered_rows,
                    "recordsFiltered"	=>	$jml_baris,
                    "data"				=>	$data,
                );
                $response['status']=true;
            }else{
                $response['status']=false;
                $response["message"] = "Unauthorized Access, Invalid Token";
                
            }
        }else{
            $response['status']=false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }
    
    function insertProduk() {
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'],$_SESSION['userPwd'])){
            if ($_SESSION['token']==$_POST['token'] AND $_POST['token'] !=""){  
                $data = $_POST;
                $kode_produk = qstr(generateKode("sai_produk", "kode_produk", "PR", "001"));
                // $kode_produk = qstr($data["kode_produk"]);
                $kode_lokasi=qstr($_SESSION["lokasi"]);
                
                $nama=qstr($data["nama"]);
                
                $query2=" insert into sai_produk (kode_produk,kode_lokasi,nama)
                values ($kode_produk,$kode_lokasi,$nama) ";
                
                $sql = array();
                array_push($sql,$query2);
                
                $rs=executeArray($sql);
                if($rs) {
                    $response=array(
                        'status' => true,
                        'message' =>'produk sukses',
                        'query' => $sql,
                        'session' => $_SESSION['lokasi']
                    );
                }
                else {
                    $response=array(
                        'status' => false,
                        'message' =>'produk gagal'.$rs,
                        'query' => $sql,
                        'session' => $_SESSION['lokasi']
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
        
        echo json_encode($response);
    }

    function getEditProduk(){
        session_start();
        getKoneksi();
        $data = $_GET;
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'],$_SESSION['userPwd'])){
            if ($_SESSION['token']==$data['token'] AND $data['token'] !=""){  
                $sql="select kode_produk,nama
                from sai_produk
                where kode_lokasi= '".$_SESSION['lokasi']."' and kode_produk='".$_GET['kode_produk']."' ";
            
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

    function updateProduk() {
        session_start();
        getKoneksi();
        parse_str(file_get_contents('php://input'), $_PUT);
        $data = $_PUT;
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'],$_SESSION['userPwd'])){
            if ($_SESSION['token']==$data['token'] AND $data['token'] !=""){  
                $kode_produk=qstr($data["kode_produk"]);
                $kode_lokasi=qstr($_SESSION["lokasi"]);
                $nama=qstr($data["nama"]);
                $query="UPDATE sai_produk SET nama = $nama
                WHERE kode_produk = $kode_produk and kode_lokasi = $kode_lokasi ";
                $sql = array();
                array_push($sql,$query);

                $rs=executeArray($sql);
                if($rs) {
                    $response=array(
                        'status' => true,
                        'message' =>'produk berhasil ',
                        'query'=>$query
                    );
                }
                else {
                    $response=array(
                        'status' => false,
                        'message' =>'produk gagal '.$rs,
                        'query'=>$query
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

    function deleteProduk() {
        session_start();
        getKoneksi();
        parse_str(file_get_contents('php://input'), $_DELETE);
        $data = $_DELETE;
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'],$_SESSION['userPwd'])){
            if ($_SESSION['token']==$data['token'] AND $data['token'] !=""){
                $id = qstr($data["kode_produk"]);
                $kd_lok = qstr($_SESSION["lokasi"]);
                
                $query="DELETE FROM sai_produk where kode_produk=$id and kode_lokasi = $kd_lok" ;
                
                $sql = array();
                array_push($sql,$query);
                
                $rs=executeArray($sql);
                if($rs) {
                    $response=array(
                        'status' => 1,
                        'message' =>'produk berhasil',
                        'sql'=>$sql
                    );
                }
                else {
                    $response=array(
                        'status' => 0,
                        'message' =>'produk gagal'.$rs,
                        'sql'=>$sql
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

?>