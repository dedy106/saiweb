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

    function generateKode($tabel, $kolom_acuan, $prefix, $str_format){
        $query = execute("select right(max($kolom_acuan), ".strlen($str_format).")+1 as id from $tabel where $kolom_acuan like '$prefix%'");
        $kode = $query->fields[0];
        $id = $prefix.str_pad($kode, strlen($str_format), $str_format, STR_PAD_LEFT);
        return $id;
    }

    function isUnik($isi){
        getKoneksi();

        $schema = db_Connect();
        $auth = $schema->SelectLimit("SELECT kode_jenis FROM dev_jenis where kode_jenis='$isi' ", 1);
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
            $query .= "select no_agen,nama_agen,tgl_lahir,alamat,no_hp,email,bank,norek,kode_marketing from dgw_agent where kode_lokasi='".$kode_lokasi."'  ";

            $column_array = array('no_agen','nama_agen','tgl_lahir','alamat','no_hp','email','bank','norek','kode_marketing');
            $order_column = 'ORDER BY no_agen '.$_GET['order'][0]['dir'];
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
                $query .= ' ORDER BY no_agen ';
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
                $sub_array[] = $row->no_agen;
                $sub_array[] = $row->nama_agen;  
                $sub_array[] = $row->tgl_lahir;
                $sub_array[] = $row->alamat;  
                $sub_array[] = $row->no_hp;  
                $sub_array[] = $row->email;  
                $sub_array[] = $row->bank;  
                $sub_array[] = $row->norek; 
                $sub_array[] = $row->kode_marketing;              
                $data[] = $sub_array;
            }
            $response = array(
                "draw"				=>	intval($_GET["draw"]),
                "recordsTotal"		=> 	$filtered_rows,
                "recordsFiltered"	=>	$jml_baris,
                "data"				=>	$data
            );
            
            $response["status"] = true;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }
    
    function getMarketing(){
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $kode_lokasi=$_GET['kode_lokasi'];    
        
            $response = array("message" => "", "rows" => 0, "status" => "" );
            $sql="select no_marketing, nama_marketing from dgw_marketing where kode_lokasi = '".$kode_lokasi."'";
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

    function getEdit(){
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $id=$_GET['kode_agen'];    
        
            $response = array("message" => "", "rows" => 0, "status" => "" );
        
            $sql="select no_agen,nama_agen, alamat, flag_aktif, tempat_lahir, tgl_lahir, no_hp,email,bank,cabang,norek,namarek,kode_marketing from dgw_agent where kode_lokasi='".$_GET['kode_lokasi']."' and no_agen='$id' ";
            
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
            
                $data=$_POST;
                $exec = array();
                //$kode = generateKode("dev_jenis", "kode_jenis", "J", "001");

                $sql1= "insert into dgw_agent(no_agen,nama_agen,alamat,tempat_lahir,tgl_lahir,no_hp,email,bank,cabang,norek,namarek,kode_marketing,flag_aktif,kode_lokasi) values ('".$data['kode_agen']."','".$data['nama']."','".$data['alamat']."','".$data['tempat_lahir']."','".$data['tgl_lahir']."','".$data['no_hp']."','".$data['email']."','".$data['bank']."','".$data['cabang']."','".$data['norek']."','".$data['namarek']."','".$data['kode_marketing']."','".$data['sts_aktif']."','".$data['kode_lokasi']."') ";

                array_push($exec,$sql1);
                
                $rs=executeArray($exec,$error);

                $tmp=array();
                $kode = array();
                $sts=false;
                if ($error==null)
                {	
                    $tmp="sukses";
                    $sts=true;
                }else{
                    $tmp=$error;
                    $sts=false;
                }	
            

            $response["message"] =$tmp;
            $response["status"] = $sts;
            //$response["sql"] = $sql1;
			//$response["err"] = $error;
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

            $del = "delete from dgw_agent where no_agen='".$data['kode_agen']."' and kode_lokasi='".$data['kode_lokasi']."' ";
            array_push($exec,$del);

            $sql1= "insert into dgw_agent(no_agen,nama_agen,alamat,tempat_lahir,tgl_lahir,no_hp,email,bank,cabang,norek,namarek,kode_marketing,flag_aktif,kode_lokasi) values ('".$data['kode_agen']."','".$data['nama']."','".$data['alamat']."','".$data['tempat_lahir']."','".$data['tgl_lahir']."','".$data['no_hp']."','".$data['email']."','".$data['bank']."','".$data['cabang']."','".$data['norek']."','".$data['namarek']."','".$data['kode_marketing']."','".$data['sts_aktif']."','".$data['kode_lokasi']."') ";

            array_push($exec,$sql1);

            $rs=executeArray($exec,$error);

            $tmp=array();
            $kode = array();
            if ($error==null)
            {	
                $tmp="sukses";
                $sts=true;
            }else{
                $tmp=$error;
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
            $del = "delete from dgw_agent where no_agen='".$data['kode_agen']."' and kode_lokasi='".$data['kode_lokasi']."' ";
            array_push($exec,$del);
            
            $rs=executeArray($exec);
            $tmp=array();
            $kode = array();
            if ($rs)
            {	
                $tmp="sukses";
                $sts=true;
            }else{
                $tmp="gagal";
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