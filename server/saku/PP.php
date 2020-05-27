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
        include_once($root_lib."lib/koneksi.php");
        include_once($root_lib."lib/helpers.php");
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
        $kode_lokasi=$_SESSION['lokasi'];
        $auth = $schema->SelectLimit("SELECT kode_pp FROM pp where kode_pp='$isi' and kode_lokasi='$kode_lokasi' ", 1);
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
        
            $kode_lokasi = $_GET['kode_lokasi'];
            $query .= "select kode_pp,nama,flag_aktif from pp where kode_lokasi='$kode_lokasi' ";

            $column_array = array('kode_pp','nama','flag_aktif');
            $order_column = 'ORDER BY kode_pp '.$_GET['order'][0]['dir'];
            $column_string = join(',', $column_array);

            $res = execute($query);
            $jml_baris = $res->RecordCount();
            if(!empty($_GET['search']['value']))
            {
                $search = $_GET['search']['value'];
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
        
            if(isset($_GET["order"]))
            {
                $query .= ' ORDER BY '.$column_array[$_GET['order'][0]['column']].' '.$_GET['order'][0]['dir'];
            }
            else
            {
                $query .= ' ORDER BY kode_pp ';
            }
            if($_GET["length"] != -1)
            {
                $query .= ' OFFSET ' . $_GET['start'] . ' ROWS FETCH NEXT ' . $_GET['length'] . ' ROWS ONLY ';
            }
            $statement = execute($query);
            $data = array();
            $filtered_rows = $statement->RecordCount();
            while($row = $statement->FetchNextObject($toupper=false))
            {
                $sub_array = array();
                $sub_array[] = $row->kode_pp;
                $sub_array[] = $row->nama;
                $sub_array[] = $row->flag_aktif;
                $data[] = $sub_array;
            }
            $response = array(
                "draw"				=>	intval($_GET["draw"]),
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
            $id=$_GET['kode_pp'];    
            $kode_lokasi=$_GET['kode_lokasi'];  

            $response = array("message" => "", "rows" => 0, "status" => "" );
        
            $sql="select kode_pp,nama,initial,kode_bidang,kode_ba,kode_pc,kota,flag_aktif from pp where kode_pp='$id' and kode_lokasi='$kode_lokasi'";
            
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

    function getPP(){
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $kode_pp=$_GET['kode_pp'];    
        
            $response = array("message" => "", "rows" => 0, "status" => "" );
        
            $sql="select kode_pp,nama from pp where kode_pp='$kode_pp' ";
            $response['daftar']=dbResultArray($sql);
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
            if(isUnik($_POST["kode_pp"])){
                $data=$_POST;
                $exec = array();

                $sql1= "insert into pp(kode_pp,nama,initial,kode_bidang,kode_ba,kode_pc,kota,flag_aktif,kode_lokasi) values ('".$data['kode_pp']."','".$data['nama']."','".$data['initial']."','".$data['kode_bidang']."','".$data['kode_ba']."','".$data['kode_pc']."','".$data['kota']."','".$data['flag_aktif']."','".$data['kode_lokasi']."') ";

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
                    $tmp="gagal. ".$err;
                    $sts=false;
                }	
            }else{
                $tmp=" error:Duplicate Entry. Kode pp sudah ada didatabase !";
                $sts=false;
            }

            $response["message"] =$tmp;
            $response["status"] = $sts;
            $response["error"] = $error_upload;
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
            $kode_lokasi=$data['kode_lokasi'];           

            $del = "delete from pp where kode_pp='".$data['kode_pp']."' and kode_lokasi='$kode_lokasi' ";
            array_push($exec,$del);

            $sql1= "insert into pp(kode_pp,nama,initial,kode_bidang,kode_ba,kode_pc,kota,flag_aktif,kode_lokasi) values ('".$data['kode_pp']."','".$data['nama']."','".$data['initial']."','".$data['kode_bidang']."','".$data['kode_ba']."','".$data['kode_pc']."','".$data['kota']."','".$data['flag_aktif']."','".$data['kode_lokasi']."') ";
              
            array_push($exec,$sql1);

            $rs=executeArray($exec,$err);

            $tmp=array();
            $kode = array();
            if ($err == null)
            {	
                $tmp="sukses";
                $sts=true;
            }else{
                $tmp="gagal. ".$err;
                $sts=false;
            }		
            $response["message"] =$tmp;
            $response["status"] = $sts;
            $response["exec"] = $exec;
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
            $del = "delete from pp where kode_pp='".$data['kode_pp']."' and kode_lokasi='".$data['kode_lokasi']."' ";
            array_push($exec,$del);
            
            $rs=executeArray($exec,$err);
            $tmp=array();
            $kode = array();
            if ($err == null)
            {	
                $tmp="sukses";
                $sts=true;
            }else{
                $tmp="gagal. ".$err;
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