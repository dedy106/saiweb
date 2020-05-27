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
        $auth = $schema->SelectLimit("SELECT kode_gudang FROM brg_gudang where kode_gudang='$isi' ", 1);
        if($auth->RecordCount() > 0){
            return false;
        }else{
            return true;
        }
    }

    function getNIK(){
        
        session_start();
        getKoneksi();
        if($_COOKIE['isLogedIn'] AND cekAuth($_COOKIE['userLog'])){
            $kode_lokasi = $_GET['kode_lokasi'];
            
            $sql="select nik, nama from karyawan where kode_lokasi='$kode_lokasi'";
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
    function getPP(){
        
        session_start();
        getKoneksi();
        if($_COOKIE['isLogedIn'] AND cekAuth($_COOKIE['userLog'])){
            $kode_lokasi = $_GET['kode_lokasi'];
            
            $sql="select kode_pp, nama from pp where kode_lokasi='$kode_lokasi'";
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

    function getGudang(){
        session_start();
        getKoneksi();
        if($_COOKIE['isLogedIn'] AND cekAuth($_COOKIE['userLog'])){
            $query = '';
            $output = array();
        
            $kode_lokasi = $_REQUEST['kode_lokasi'];
            $query .= "select kode_gudang,nama,alamat,telp,pic from brg_gudang where kode_lokasi='".$kode_lokasi."'  ";

            $column_array = array('kode_gudang','nama','alamat','telp','pic');
            $order_column = 'ORDER BY kode_gudang '.$_POST['order'][0]['dir'];
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
                $query .= ' ORDER BY kode_gudang ';
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
                $sub_array[] = $row->kode_gudang;
                $sub_array[] = $row->nama;
                $sub_array[] = $row->alamat;
                $sub_array[] = $row->telp;                
                $sub_array[] = $row->pic;
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
        getKoneksi();
        if($_COOKIE['isLogedIn'] AND cekAuth($_COOKIE['userLog'])){
            $id=$_GET['kode_gudang'];    
        
            $response = array("message" => "", "rows" => 0, "status" => "" );
        
            $sql="select kode_gudang,nama,pic,telp,alamat,kode_pp from brg_gudang where kode_lokasi='".$_GET['kode_lokasi']."' and kode_gudang='$id' ";
            
            $rs = execute($sql);					
            
            while ($row = $rs->FetchNextObject(false)){
                $response['daftar'][] = (array)$row;
            }
            $response['status'] = TRUE;
            $response['sql']=$sql;
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
            if(isUnik($_POST["kode_gudang"])){
                $data=$_POST;
                $exec = array();

                $sql1= "insert into brg_gudang(kode_gudang,kode_lokasi,nama,pic,telp,alamat,kode_pp) values ('".$data['kode_gudang']."','".$data['kode_lokasi']."','".$data['nama']."','".$data['nik']."','".$data['telp']."','".$data['alamat']."','".$data['kode_pp']."') ";

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
                $tmp=" error:Duplicate Entry. Kode Gudang sudah ada didatabase !";
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
            $del = "delete from brg_gudang where kode_gudang='".$data['kode_gudang']."' and kode_lokasi='".$data['kode_lokasi']."' ";
            array_push($exec,$del);

            $sql1= "insert into brg_gudang(kode_gudang,kode_lokasi,nama,pic,telp,alamat,kode_pp) values ('".$data['kode_gudang']."','".$data['kode_lokasi']."','".$data['nama']."','".$data['nik']."','".$data['telp']."','".$data['alamat']."','".$data['kode_pp']."') ";

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
            $del = "delete from brg_gudang where kode_gudang='".$data['kode_gudang']."' and kode_lokasi='".$data['kode_lokasi']."' ";
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