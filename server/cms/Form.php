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

    function joinNum2($num){
        // menggabungkan angka yang di-separate(10.000,75) menjadi 10000.00
        $num = str_replace(".", "", $num);
        $num = str_replace(",", ".", $num);
        return $num;
    }

    
    function cekAuth($user){
        getKoneksi();
        $user = qstr($user);

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
        $auth = $schema->SelectLimit("SELECT kode_form FROM m_form where kode_form='$isi' ", 1);
        if($auth->RecordCount() > 0){
            return false;
        }else{
            return true;
        }
    }

    function getView(){
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $query = '';
            $output = array();
        
            $kode_lokasi = $_REQUEST['kode_lokasi'];
            $query .= "select kode_form,nama_form,form from m_form ";

            $column_array = array('kode_form','nama_form','form');
            $order_column = 'ORDER BY kode_form '.$_POST['order'][0]['dir'];
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
                $query .= ' ORDER BY kode_form ';
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
                $sub_array[] = $row->kode_form;
                $sub_array[] = $row->nama_form;
                $sub_array[] = $row->form;
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
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $id=$_GET['kode'];    
        
            $response = array("message" => "", "rows" => 0, "status" => "" );
        
            $sql="select kode_form,nama_form,form from m_form where kode_form='$id' ";
            
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
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            if(isUnik($_POST["kode_form"])){
                $data=$_POST;
                $exec = array();
    
                $sql1= "insert into m_form(kode_form,nama_form,form) values ('".$data['kode_form']."','".$data['nama_form']."','".$data['form']."') ";
    
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
                $tmp=" error:Duplicate Entry. Kode Form sudah ada didatabase !";
                $sts=false;
            }

            $response["message"] =$tmp;
            $response["status"] = $sts;
            $response["error"] = $error_upload;
            $response["sql"]=$exec;
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
        $data = $_POST;
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $exec = array();
            $del = "delete from m_form where kode_form='".$data['kode_form']."' ";
            array_push($exec,$del);

            $sql1= "insert into m_form(kode_form,nama_form,form) values ('".$data['kode_form']."','".$data['nama_form']."','".$data['form']."') ";
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
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            
            $exec = array();
            parse_str(file_get_contents('php://input'), $_DELETE);
            $data = $_DELETE;
            $del = "delete from m_form where kode_form='".$data['kode']."'";
            array_push($exec,$del);
            
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
            $response["message"] =$tmp;
            $response["status"] = $sts;
        }else{
                
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }