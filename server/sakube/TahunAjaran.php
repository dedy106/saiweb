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
        include_once($root_lib."lib/koneksi.php");
        // include_once($root_lib.'app/sakube/setting2.php');
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
            $query .= "select a.kode_ta,a.kode_pp+'-'+b.nama as pp,a.nama,convert (varchar, a.tgl_mulai,103) as tgl_mulai,convert (varchar, a.tgl_akhir,103) as tgl_akhir, case when a.flag_aktif='1' then 'AKTIF' else 'NONAKTIF' end as sts
            from sis_ta a 
            inner join pp b on a.kode_pp=b.kode_pp and a.kode_lokasi=b.kode_lokasi
            where a.kode_lokasi='$kode_lokasi'  ";
            $response['data']=dbResultArray($query);
            $response['query']=$query;
            
            $response["status"] = true;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }

    function getView2(){
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $query = '';
            $output = array();
        
            $kode_lokasi = $_GET['kode_lokasi'];
            $kode_pp = $_GET['kode_pp'];
            $query .= "select a.kode_ta,a.kode_pp+'-'+b.nama as pp,a.nama,convert (varchar, a.tgl_mulai,103) as tgl_mulai,convert (varchar, a.tgl_akhir,103) as tgl_akhir, case when a.flag_aktif='1' then 'AKTIF' else 'NONAKTIF' end as sts
            from sis_ta a 
            inner join pp b on a.kode_pp=b.kode_pp and a.kode_lokasi=b.kode_lokasi
            where a.kode_lokasi='$kode_lokasi'  ";

            $column_array = array('a.kode_ta',"a.kode_pp+'-'+b.nama",'a.nama','convert (varchar, a.tgl_mulai,103)','convert (varchar, a.tgl_akhir,103)','a.flag_aktif',"case when a.flag_aktif='1' then 'AKTIF' else 'NONAKTIF' end");
            $order_column = 'ORDER BY a.kode_ta '.$_GET['order'][0]['dir'];
            $column_string = join(',', $column_array);

            if(!empty($_GET['search']['value']))
            {
                $search = $_GET['search']['value'];
                $filter_string = " and (";
        
                for($i=0; $i<count($column_array); $i++){
        
                    if($i == (count($column_array) - 1)){
                        $filter_string .= $column_array[$i]." like '%".$search."%' )";
                    }else{
                        $filter_string .= $column_array[$i]." like '%".$search."%' or ";
                    }
                }
        
        
                $query.=" $filter_string ";
            }

            $columns = $_GET['columns'];
            if(count($columns) > 0){

                $filters = "";
                for ($i=0;$i<count($columns);$i++) {
                    $columnName = $column_array[$i];
                    $searchString = $columns[$i]['search']['value'];
                    if($searchString != ""){

                        $filters .= " and $columnName like '%".$searchString."%' ";
                    }
                }
            }

            if($filters != ""){
                $query.= $filters;
            }
        
            if(isset($_GET["order"]))
            {
                $query .= ' ORDER BY '.$column_array[$_GET['order'][0]['column']].' '.$_GET['order'][0]['dir'];
            }
            else
            {
                $query .= ' ORDER BY a.kode_ta ';
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
                $sub_array[] = $row->kode_ta;
                $sub_array[] = $row->pp;
                $sub_array[] = $row->nama;  
                $sub_array[] = $row->tgl_mulai;
                $sub_array[] = $row->tgl_akhir;  
                $sub_array[] = $row->sts;              
                $data[] = $sub_array;
            }
            $response = array(
                "draw"				=>	intval($_GET["draw"]),
                "recordsTotal"		=> 	$filtered_rows,
                "recordsFiltered"	=>	$jml_baris,
                "data"				=>	$data,
                "query"=>$query
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
            $id=$_GET['kode_ta'];   
            $kode_lokasi=$_GET['kode_lokasi'];
            $kode_pp=$_GET['kode_pp']; 
        
            $response = array("message" => "", "rows" => 0, "status" => "" );
        
            $sql="select kode_pp,kode_ta,nama, flag_aktif,tgl_mulai,tgl_akhir from sis_ta where kode_ta ='".$id."' and kode_lokasi='".$kode_lokasi."'  and kode_pp='".$kode_pp."' ";
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

    function getPP(){
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $id=$_GET['kode_ta'];   
            $kode_lokasi=$_GET['kode_lokasi'];
            $kode_pp=$_GET['kode_pp']; 
        
            $response = array("message" => "", "rows" => 0, "status" => "" );
        
            $sql="select kode_pp,nama from pp where kode_lokasi='".$kode_lokasi."' ";
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
            if(isUnik($data["kode_ta"],$data['kode_lokasi'],$data['kode_pp'])){
                $exec = array();
                //$kode = generateKode("dev_jenis", "kode_jenis", "J", "001");
                
                $sql1= "insert into sis_ta (kode_ta,nama,kode_lokasi,kode_pp,tgl_mulai,tgl_akhir,flag_aktif) values ('".$data['kode_ta']."','".$data['nama']."','".$data['kode_lokasi']."','".$data['kode_pp']."','".$data['tgl_mulai']."','".$data['tgl_akhir']."','".$data['flag_aktif']."') ";
                
                array_push($exec,$sql1);
                
                $rs=executeArray($exec,$err);
                
                if ($err == null)
                {	
                    $tmp="sukses";
                    $sts=true;
                }else{
                    $tmp=$err;
                    $sts=false;
                }	 
            }else{
                $tmp=" error:Duplicate Entry. Kode TA sudah terdaftar di database !";
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
        $data = $_POST;
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $exec = array();

            $del = "delete from sis_ta where kode_ta='".$data['kode_ta']."' and kode_lokasi='".$data['kode_lokasi']."' and kode_pp='".$data['kode_pp']."' ";
            array_push($exec,$del);

            $sql1= "insert into sis_ta (kode_ta,nama,kode_lokasi,kode_pp,tgl_mulai,tgl_akhir,flag_aktif) values ('".$data['kode_ta']."','".$data['nama']."','".$data['kode_lokasi']."','".$data['kode_pp']."','".$data['tgl_mulai']."','".$data['tgl_akhir']."','".$data['flag_aktif']."') ";
            array_push($exec,$sql1);

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
    

    function hapus(){
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            
            $exec = array();
            parse_str(file_get_contents('php://input'), $_DELETE);
            $data = $_DELETE;
            $del = "delete from sis_ta where kode_ta='".$data['kode_ta']."' and kode_lokasi='".$data['kode_lokasi']."' and kode_pp='".$data['kode_pp']."' ";
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