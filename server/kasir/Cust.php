<?php
	//include_once("config/setting.php");
    
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
        case 'PUT':
            ubah();
        break;
        case 'DELETE':
            hapus();
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
		include_once($root_lib."lib/koneksi.php");
        include_once($root_lib."lib/helpers.php");
    }

    
    function cekAuth($user){
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
        $auth = $schema->SelectLimit("SELECT kode_cust FROM cust where kode_cust='$isi' ", 1);
        if($auth->RecordCount() > 0){
            return false;
        }else{
            return true;
        }
    }


    function getAkun(){
        
        session_start();
        getKoneksi();
        if($_COOKIE['isLogedIn'] AND cekAuth($_COOKIE['userLog'])){
            $kode_lokasi = $_GET['kode_lokasi'];
            $sql="select a.kode_akun, a.nama from masakun a inner join flag_relasi b on a.kode_akun=b.kode_akun and a.kode_lokasi=b.kode_lokasi where b.kode_flag = '003' and a.kode_lokasi='$kode_lokasi'";
            $rs=execute($sql);
            $response["daftar"]=array();
            while ($row = $rs->FetchNextObject(false)){
                $response['daftar'][] = (array)$row;
            }
            $response['status'] = TRUE;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }

    function getCust(){
        session_start();
        getKoneksi();
        if($_COOKIE['isLogedIn'] AND cekAuth($_COOKIE['userLog'])){
            $query = '';
            $output = array();
        
            $kode_lokasi = $_REQUEST['kode_lokasi'];
            $query .= "SELECT kode_cust, nama, alamat FROM cust as status where kode_lokasi= '".$kode_lokasi."'  ";

            $column_array = array('kode_cust','nama','alamat');
            $order_column = 'ORDER BY kode_cust '.$_POST['order'][0]['dir'];
            $column_string = join(',', $column_array);

            $res = execute($query);
            $jml_baris = $res->RecordCount();
            if(!empty($_POST['search']['value']))
            {
                $search = $_POST['search']['value'];
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
        
            if(isset($_POST["order"]))
            {
                $query .= ' ORDER BY '.$column_array[$_POST['order'][0]['column']].' '.$_POST['order'][0]['dir'];
            }
            else
            {
                $query .= ' ORDER BY kode_cust ';
            }
            if($_POST["length"] != -1)
            {
                $query .= ' OFFSET ' . $_POST['start'] . ' ROWS FETCH NEXT ' . $_POST['length'] . ' ROWS ONLY ';
            }
            $statement = execute($query);
            $data = array();
            $filtered_rows = $statement->RecordCount();
            while($row = $statement->FetchNextObject($toupper=false))
            {
                $sub_array = array();
                $sub_array[] = $row->kode_cust;
                $sub_array[] = $row->nama;
                $sub_array[] = $row->alamat;
                $data[] = $sub_array;
            }
            $response = array(
                "draw"				=>	intval($_POST["draw"]),
                "recordsTotal"		=> 	$filtered_rows,
                "recordsFiltered"	=>	$jml_baris,
                "data"				=>	$data,
            );
            
            $response["status"] = true;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }
    


    function getEdit(){
        session_start();
        //getKoneksi();
        if($_COOKIE['isLogedIn'] AND cekAuth($_COOKIE['userLog'])){
            $id=$_GET['kode_cust'];    
        
            $response = array("message" => "", "rows" => 0, "status" => "" );
        
            $sql="select kode_cust, nama,alamat,no_tel,no_fax,email,npwp,alamat2,pic,akun_piutang from cust where kode_lokasi='".$_GET['kode_lokasi']."' and kode_cust='$id' ";
            
            $rs = execute($sql);					
            
            while ($row = $rs->FetchNextObject(false)){
                $response['daftar'][] = (array)$row;
            }
            $response['status'] = TRUE;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    
    }

    function simpan(){
        session_start();
        getKoneksi();
        if($_COOKIE['isLogedIn'] AND cekAuth($_COOKIE['userLog'])){
            if(isUnik($_POST["kode_cust"])){

                $data=$_POST;
                $exec = array();

                $sql1= "insert into cust(kode_cust,kode_lokasi,nama,alamat,no_tel,email,npwp,pic,alamat2,no_fax,akun_piutang) values ('".$data['kode_cust']."','".$data['kode_lokasi']."','".$data['nama']."','".$data['alamat']."','".$data['no_telp']."','".$data['email']."','".$data['npwp']."','".$data['pic']."','".$data['alamat_npwp']."','".$data['no_fax']."','".$data['akun_piutang']."') ";

                array_push($exec,$sql1);
                
                $rs=executeArray($exec,$err);

                $tmp=array();
                $kode = array();
                $sts=false;
                if ($err == null)
                {	
                    $tmp="sukses disimpan";
                    $sts=true;
                }else{
                    $tmp=$err;
                    $sts=false;
                }	
            }else{
                $tmp=" error:Duplicate Entry. Kode Customer sudah ada didatabase !";
                $sts=false;
            }

            $response["message"] =$tmp;
            $response["status"] = $sts;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }
    

    function ubah(){
        session_start();
        getKoneksi();
        parse_str(file_get_contents('php://input'), $_PUT);
        $data = $_PUT;
        if($_COOKIE['isLogedIn'] AND cekAuth($_COOKIE['userLog'])){
            $exec = array();
            $del = "delete from cust where kode_cust='".$data['kode_cust']."' and kode_lokasi='".$data['kode_lokasi']."' ";
            array_push($exec,$del);
            
            $sql1= "insert into cust(kode_cust,kode_lokasi,nama,alamat,no_tel,email,npwp,pic,alamat2,no_fax,akun_piutang) values ('".$data['kode_cust']."','".$data['kode_lokasi']."','".$data['nama']."','".$data['alamat']."','".$data['no_telp']."','".$data['email']."','".$data['npwp']."','".$data['pic']."','".$data['alamat_npwp']."','".$data['no_fax']."','".$data['akun_piutang']."') ";

            array_push($exec,$sql1);
            
            $rs=executeArray($exec,$err);
            
            $tmp=array();
            $kode = array();
            $sts=false;
            if ($err == null)
            {	
                $tmp="sukses";
                $sts=true;
            }else{
                $tmp=$err;
                $sts=false;
            }		
            $response["message"] =$tmp;
            $response["status"] = $sts;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }
    

    function hapus(){
        session_start();
        getKoneksi();
        if($_COOKIE['isLogedIn'] AND cekAuth($_COOKIE['userLog'])){
            
            $exec = array();
            parse_str(file_get_contents('php://input'), $_DELETE);
            $data = $_DELETE;
            $del = "delete from cust where kode_cust='".$data['kode_cust']."' and kode_lokasi='".$data['kode_lokasi']."' ";
            array_push($exec,$del);
            
            $rs=executeArray($exec,$err);
            
            $tmp=array();
            $kode = array();
            $sts=false;
            if ($err == null)
            {	
                $tmp="sukses";
                $sts=true;
            }else{
                $tmp=$err;
                $sts=false;
            }		
            $response["message"] =$tmp;
            $response["status"] = $sts;
        }else{
                
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }

    
    function syncData(){
        session_start();
        getKoneksi();
        // if($_COOKIE['isLogedIn'] AND cekAuth($_COOKIE['userLog'])){
            try{

                $fields = array(
                    "nik" => "kasir",
                    "pass" => "saisai"
                );

                //getToken
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "http://saiweb.simkug.com/api/ginas/SyncCust.php?fx=generateToken");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($ch, CURLOPT_HEADER, FALSE);
                curl_setopt($ch, CURLOPT_POST, FALSE);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
                
                $response["sync"]= json_decode(curl_exec($ch));
                curl_close($ch);

                $token = $response["sync"]->token;
                // $fields2 = http_build_query($_POST);
                $_POST['kode_lokasi']=$response["sync"]->kode_lokasi;
                $res = array (
                    'CUST' =>  array (
                        0 => 
                        array (
                            'kode_cust' => '124CUST',
                            'nama' => 'Customer 122',
                        ),
                        1 => 
                        array (
                            'kode_cust' => '124CUST2',
                            'nama' => 'Customer 1223',
                        ),
                    ),
                );
                $fields2 = json_encode($res);
                //syncData
                $ch2 = curl_init();
                curl_setopt($ch2, CURLOPT_URL, "http://saiweb.simkug.com/api/ginas/SyncCust.php?fx=addCustomer2");
                curl_setopt($ch2, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json; charset=utf-8',
                    'Authorization: Bearer '.$token //REST API KEY GINAS
                ));
                curl_setopt($ch2, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($ch2, CURLOPT_HEADER, FALSE);
                curl_setopt($ch2, CURLOPT_POST, TRUE);
                curl_setopt($ch2, CURLOPT_POSTFIELDS, $fields2);
                curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt($ch2, CURLOPT_SSL_VERIFYHOST, FALSE);
                $response["sync2"]= json_decode(curl_exec($ch2));
                curl_close($ch2);

            } catch (exception $e) { 
                error_log($e->getMessage());		
                $error ="error " .  $e->getMessage();
                
                $response['status'] = false;
                $response['error'] = $error;
            } 	
            $response['status'] = TRUE;
            $response['fields'] = $fields2;
        // }else{
            
        //     $response["status"] = false;
        //     $response["message"] = "Unauthorized Access, Login Required";
        // }
        // header('Content-Type: application/json');
        echo json_encode($response);
    
    }