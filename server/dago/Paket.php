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
            $query .= "select a.no_paket,a.nama,a.kode_curr,a.jenis,b.nama as nama_produk 
            from dgw_paket a
            inner join dgw_jenis_produk b on a.kode_produk=b.kode_produk and a.kode_lokasi=b.kode_lokasi where a.kode_lokasi='".$kode_lokasi."'  ";

            // $query .= "select a.no_paket,a.nama,a.kode_curr,a.jenis,b.nama as nama_produk 
            // from dgw_paket a
            // inner join dgw_jenis_produk b on a.kode_produk=b.kode_produk and a.kode_lokasi=b.kode_lokasi where a.kode_lokasi='".$kode_lokasi."'  ";

            $column_array = array('a.no_paket','a.nama','a.kode_curr','b.nama');
            // $column_array = array('a.no_paket','a.nama','a.kode_curr','a.jenis','b.nama');
            $order_column = 'ORDER BY a.no_paket '.$_GET['order'][0]['dir'];
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
                $query .= ' ORDER BY a.no_paket ';
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
                $sub_array[] = $row->no_paket;
                $sub_array[] = $row->nama;  
                $sub_array[] = $row->kode_curr;
                // $sub_array[] = $row->jenis;  
                $sub_array[] = $row->nama_produk;              
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
    


    function getEdit(){
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $id=$_GET['kode_paket'];    
            $kode_lokasi=$_GET['kode_lokasi'];    
            $response = array("message" => "", "rows" => 0, "status" => "" );
        
            $sql="select a.no_paket,a.nama,a.kode_curr,a.jenis,b.nama as nama_produk, a.kode_produk, a.tarif_agen 
            from dgw_paket a
            inner join dgw_jenis_produk b on a.kode_produk=b.kode_produk and a.kode_lokasi=b.kode_lokasi where a.kode_lokasi='".$kode_lokasi."' and no_paket='".$id."'";
            
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

    function getJenisProduk(){
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $kode_lokasi=$_GET['kode_lokasi'];    
        
            $response = array("message" => "", "rows" => 0, "status" => "" );
            $sql="select kode_produk, nama from dgw_jenis_produk where kode_lokasi = '".$kode_lokasi."'";
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
            
            $data=$_POST;
            $exec = array();
            //$kode = generateKode("dev_jenis", "kode_jenis", "J", "001");
            
            // $sql1= "insert into dgw_paket(no_paket,nama,kode_curr,jenis,kode_produk,tarif_agen,kode_lokasi) values ('".$data['kode_paket']."','".$data['nama']."','".$data['kode_curr']."','".$data['jenis']."','".$data['jenis_produk']."','".$data['tarif_agen']."','".$data['kode_lokasi']."') ";
            $sql1= "insert into dgw_paket(no_paket,nama,kode_curr,kode_produk,tarif_agen,kode_lokasi) values ('".$data['kode_paket']."','".$data['nama']."','".$data['kode_curr']."','".$data['jenis_produk']."','".joinNum($data['tarif_agen'])."','".$data['kode_lokasi']."') ";
            array_push($exec,$sql1);
            $$rs=executeArray($exec,$err);
            
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

            $del = "delete from dgw_paket where no_paket='".$data['kode_paket']."' and kode_lokasi='".$data['kode_lokasi']."' ";
            array_push($exec,$del);

            // $sql1= "insert into dgw_paket(no_paket,nama,kode_curr,jenis,kode_produk,tarif_agen,kode_lokasi) values ('".$data['kode_paket']."','".$data['nama']."','".$data['kode_curr']."','".$data['jenis']."','".$data['jenis_produk']."','".$data['tarif_agen']."','".$data['kode_lokasi']."') ";
            $sql1= "insert into dgw_paket(no_paket,nama,kode_curr,kode_produk,tarif_agen,kode_lokasi) values ('".$data['kode_paket']."','".$data['nama']."','".$data['kode_curr']."','".$data['jenis_produk']."','".joinNum($data['tarif_agen'])."','".$data['kode_lokasi']."') ";
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
            $del = "delete from dgw_paket where no_paket='".$data['kode_paket']."' and kode_lokasi='".$data['kode_lokasi']."' ";
            array_push($exec,$del);
            
            $rs=executeArray($exec,$err);
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