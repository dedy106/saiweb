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
		if (substr($root_lib,-1)!="/") {
			$root_lib=$root_lib."/";
		}
		include_once($root_lib.'app/tarbak/setting.php');
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

    function isUnik($isi,$kode_lokasi,$kode_pp){
        getKoneksi();

        $schema = db_Connect();
        $auth = $schema->SelectLimit("select kode_ta from sis_ta where kode_ta ='".$isi."' and kode_lokasi='".$kode_lokasi."'  and kode_pp='".$kode_pp."'", 1);
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
            $kode_pp = $_GET['kode_pp'];
            $query .= "select a.kode_sem,a.kode_ta,a.kode_pp+'-'+b.nama as pp 
            from sis_kalender_akad a 
            inner join pp b on a.kode_pp=b.kode_pp and a.kode_lokasi=b.kode_lokasi
            where a.kode_lokasi='$kode_lokasi' 
            group by a.kode_sem,a.kode_ta,a.kode_pp+'-'+b.nama";

            $column_array = array('a.kode_sem','a.kode_ta'."a.kode_pp+'-'+b.nama ");
            $order_column = 'ORDER BY a.kode_sem '.$_GET['order'][0]['dir'];
            $column_string = join(',', $column_array);

            if(!empty($_GET['search']['value']))
            {
                $search = $_GET['search']['value'];
                $filter_string = " having (";
        
                for($i=0; $i<count($column_array); $i++){
        
                    if($i == (count($column_array) - 1)){
                        $filter_string .= $column_array[$i]." like '%".$search."%' )";
                    }else{
                        $filter_string .= $column_array[$i]." like '%".$search."%' or ";
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
                $query .= ' ORDER BY a.kode_sem ';
            }
            
            $res = execute($query);
            $jml_baris = $res->RecordCount();

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
                $sub_array[] = $row->kode_sem;
                $sub_array[] = $row->kode_ta;
                $sub_array[] = $row->pp;  
                $data[] = $sub_array;
            }
            $response = array(
                "draw"				=>	intval($_GET["draw"]),
                "recordsTotal"		=> 	$filtered_rows,
                "recordsFiltered"	=>	$jml_baris,
                "data"				=>	$data,
                "query"             =>  $query
            );
            
            $response["status"] = true;
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
            $id=$_GET['kode_kelas'];   
            $kode_lokasi=$_GET['kode_lokasi'];
            $kode_pp=$_GET['kode_pp']; 
        
            $response = array("message" => "", "rows" => 0, "status" => "" );
        
            $sql="select nama, kode_pp from pp where kode_lokasi='".$kode_lokasi."' ";
            $response['daftar']=dbResultArray($sql);
            $response['status'] = TRUE;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    
    }

    function getTA(){
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){ 
            $kode_lokasi=$_GET['kode_lokasi'];
            $kode_pp=$_GET['kode_pp']; 
        
            $response = array("message" => "", "rows" => 0, "status" => "" );
            if($kode_pp != ""){
                $filter = " and kode_pp='$kode_pp' ";
            }else{
                $filter = "";
            }
        
            $sql="select kode_ta, nama from sis_ta where kode_lokasi = '".$kode_lokasi."' and flag_aktif='1' $filter";
            $response['daftar']=dbResultArray($sql);
            $response['status'] = TRUE;
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
            $kode_sem=$_GET['kode_sem'];  
            $kode_ta=$_GET['kode_ta'];   
            $kode_lokasi=$_GET['kode_lokasi'];
            $kode_pp=$_GET['kode_pp']; 
        
            $response = array("message" => "", "rows" => 0, "status" => "" );

            $sql = "select tanggal,agenda from sis_kalender_akad 
            where kode_ta='$kode_ta' and kode_sem='$kode_sem' and kode_lokasi='$kode_lokasi' and kode_pp='$kode_pp' ";	
            $response['daftar2']=dbResultArray($sql);

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
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $data=$_POST;
            
            $exec = array();
            
            if(count($data['tanggal']) > 0){

                for($i=0;$i<count($data['tanggal']);$i++){
    
                    $sql1 = "insert into sis_kalender_akad(kode_pp,kode_lokasi,kode_ta,kode_sem,tanggal,agenda) values ('".$data['kode_pp']."','".$data['kode_lokasi']."','".$data['kode_ta']."','".$data['kode_sem']."','".$data['tanggal'][$i]."','".$data['agenda'][$i]."')";
                    
                    array_push($exec,$sql1);
                }
                
                $rs=executeArray($exec,$err);
            }
            
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
            $response["sql"] = $exec;
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

            if(count($data['tanggal']) > 0){

                $del = "delete from sis_kalender_akad where kode_sem='".$data['kode_sem']."' and kode_ta='".$data['kode_ta']."' and kode_lokasi='".$data['kode_lokasi']."' and kode_pp='".$data['kode_pp']."' ";
                array_push($exec,$del);

                for($i=0;$i<count($data['tanggal']);$i++){
    
                    $sql1 = "insert into sis_kalender_akad(kode_pp,kode_lokasi,kode_ta,kode_sem,tanggal,agenda) values ('".$data['kode_pp']."','".$data['kode_lokasi']."','".$data['kode_ta']."','".$data['kode_sem']."','".$data['tanggal'][$i]."','".$data['agenda'][$i]."')";
                    
                    array_push($exec,$sql1);
                }
                
                $rs=executeArray($exec,$err);
            }
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
            $response["sql"] = $exec;
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
           
            $del = "delete from sis_kalender_akad where kode_sem='".$data['kode_sem']."' and kode_ta='".$data['kode_ta']."' and kode_lokasi='".$data['kode_lokasi']."' and kode_pp='".$data['kode_pp']."' ";
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