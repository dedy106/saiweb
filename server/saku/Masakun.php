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
        include_once($root_lib."lib/koneksi.php");
        include_once($root_lib."lib/helpers.php");
    }

    
    function generateKode($tabel, $kolom_acuan, $prefix, $str_format){
        $query = execute("select right(max($kolom_acuan), ".strlen($str_format).")+1 as id from $tabel where $kolom_acuan like '$prefix%'");
        $kode = $query->fields[0];
        $id = $prefix.str_pad($kode, strlen($str_format), $str_format, STR_PAD_LEFT);
        return $id;
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
        $auth = $schema->SelectLimit("select kode_akun from masakun where kode_akun='$isi' and kode_lokasi='".$_SESSION['lokasi']."' ", 1);
        if($auth->RecordCount() > 0){
            return false;
        }else{
            return true;
        }
    }

    function getCurr(){
        
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $kode_lokasi = $_GET['kode_lokasi'];
            
            $sql="select kode_curr from curr ";
            $response['daftar']=dbResultArray($sql);
            $response['status'] = TRUE;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }

    function getModul(){
        
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $kode_lokasi = $_GET['kode_lokasi'];
            
            $sql="select kode_tipe,nama_tipe from tipe_neraca where kode_lokasi='$kode_lokasi' ";
            $response['daftar']=dbResultArray($sql);
            $response['status'] = TRUE;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }

    function getFlag(){
        
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $kode_lokasi = $_GET['kode_lokasi'];
            
            $sql="select kode_flag, nama from flag_akun ";
            $response['daftar']=dbResultArray($sql);
            $response['status'] = TRUE;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }

    function getFS(){
        
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $kode_lokasi = $_GET['kode_lokasi'];
            
            $sql="select kode_fs, nama from fs where kode_lokasi='$kode_lokasi' ";
            $response['daftar']=dbResultArray($sql);
            $response['status'] = TRUE;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }

    function getNeraca(){
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $kode_lokasi = $_GET['kode_lokasi'];
            $kode_fs = $_GET['kode_fs'];
            
            $sql="select kode_neraca, nama from neraca where kode_fs='".$kode_fs."' and tipe = 'posting' and kode_lokasi='".$kode_lokasi."' ";
            $response['daftar']=dbResultArray($sql);
            $response['status'] = TRUE;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }
   
    function getFSGar(){
        
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $kode_lokasi = $_GET['kode_lokasi'];
            
            $sql="select kode_fs, nama from fsgar where kode_lokasi='$kode_lokasi' ";
            $response['daftar']=dbResultArray($sql);
            $response['status'] = TRUE;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }

    function getNeracaGar(){
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $kode_lokasi = $_GET['kode_lokasi'];
            $kode_fs = $_GET['kode_fs'];
            
            $sql="select kode_neraca, nama from neracagar where kode_fs='".$kode_fs."' and tipe = 'posting' and kode_lokasi='".$kode_lokasi."' ";
            $response['daftar']=dbResultArray($sql);
            $response['status'] = TRUE;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }
   

    function getView(){
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $query = '';
            $output = array();
        
            $kode_lokasi = $_GET['kode_lokasi'];
            $query .= "select a.kode_akun,a.nama,a.modul,a.jenis from masakun a
            where a.kode_lokasi='".$kode_lokasi."'  ";

            $column_array = array('a.kode_akun','a.nama','a.modul','a.jenis');
            $order_column = 'ORDER BY a.kode_akun '.$_GET['order'][0]['dir'];
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
                $query .= ' ORDER BY  a.kode_akun ';
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
                $sub_array[] = $row->kode_akun;
                $sub_array[] = $row->nama;
                $sub_array[] = $row->curr;
                $sub_array[] = $row->modul;                
                $sub_array[] = $row->jenis;
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
            $data= $_GET;  
        
            $response = array("message" => "", "rows" => 0, "status" => "" );
            $kode_lokasi= $data['kode_lokasi'];
            $kode_akun= $data['kode_akun'];
            $sql = "select nama,modul,jenis,block,status_gar,normal,kode_curr as curr from masakun 
                    where kode_akun = '".$kode_akun."' and kode_lokasi ='".$kode_lokasi."' ";
            $response['daftar'] = dbResultArray($sql);

            $sql = "select b.kode_flag,b.nama from flag_relasi a inner join flag_akun b on a.kode_flag=b.kode_flag where a.kode_akun = '".$kode_akun."' and a.kode_lokasi='".$kode_lokasi."'";
            $response['daftar2'] = dbResultArray($sql);					
							
            $sql = "select b.kode_fs,b.nama as nama_fs,c.kode_neraca,c.nama as nama_lap 
                    from relakun a 
                    inner join fs b on a.kode_fs=b.kode_fs and a.kode_lokasi=b.kode_lokasi 
                    inner join neraca c on a.kode_neraca=c.kode_neraca and a.kode_fs=c.kode_fs and a.kode_lokasi=c.kode_lokasi 
                    where a.kode_akun = '".$kode_akun."' and a.kode_lokasi='".$kode_lokasi."'";
            $response['daftar3'] = dbResultArray($sql);												
            
            $sql = "select b.kode_fs,b.nama as nama_fs,c.kode_neraca,c.nama as nama_lap 
					from relakungar a 
					inner join fsgar b on a.kode_fs=b.kode_fs and a.kode_lokasi=b.kode_lokasi 
					inner join neracagar c on a.kode_neraca=c.kode_neraca and a.kode_fs=c.kode_fs and a.kode_lokasi=c.kode_lokasi 
                    where a.kode_akun = '".$kode_akun."' and a.kode_lokasi='".$kode_lokasi."'";
            $response['daftar4'] = dbResultArray($sql);	
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
            $kode_lokasi=$data['kode_lokasi'];
            $nik=$data['nik_user'];
            $exec = array();
            $kode_akun=$data['kode_akun'];
            if($data['id'] == 'edit'){
                $del1 = "delete from masakun where kode_akun = '".$kode_akun."' and kode_lokasi='".$kode_lokasi."'";
                array_push($exec,$del1);                   
                $del2 = "delete from relakun where kode_akun = '".$kode_akun."' and kode_lokasi='".$kode_lokasi."'";
                array_push($exec,$del2); 
                $del3 = "delete from relakungar where kode_akun = '".$kode_akun."' and kode_lokasi='".$kode_lokasi."'";
                array_push($exec,$del3); 
                $del4 = "delete from flag_relasi where kode_akun = '".$kode_akun."' and kode_lokasi='".$kode_lokasi."'";
                array_push($exec,$del4); 
                $sts = true;
            }else{
                if(!isUnik($_POST["kode_akun"])){
                    $tmp=" error:Duplicate Entry. Kode Akun sudah ada didatabase !";
                    $sts=false;
                }else{
                    $sts= true;
                }
                
            }
            
            if($sts){
                $ins = "insert into masakun (kode_akun,kode_lokasi,nama,modul,jenis,kode_curr,block,status_gar,normal) values 
                ('".$data['kode_akun']."','".$kode_lokasi."','".$data['nama']."','".$data['modul']."','".$data['jenis']."','".$data['curr']."','0','".$data['gar']."','".$data['normal']."')";
                array_push($exec,$ins); 
                if (count($data['kode_flag']) > 0){
                    for ($i=0;$i<count($data['kode_flag']);$i++){
                        $ins2 = "insert into flag_relasi(kode_akun,kode_lokasi,kode_flag) values 
                        ('".$data['kode_akun']."','".$kode_lokasi."','".$data['kode_flag'][$i]."')";
                        array_push($exec,$ins2); 
                    }
                }
                if (count($data['kode_fs']) > 0){
                    for ($i=0;$i<count($data['kode_fs']);$i++){							
                        $ins3 = "insert into relakun (kode_neraca,kode_fs,kode_akun,kode_lokasi) values ('".$data['kode_neraca'][$i]."','".$data['kode_fs'][$i]."','".$data['kode_akun']."','".$kode_lokasi."')";
                        array_push($exec,$ins3); 
                    }
                }
                if (count($data['kode_fsgar']) > 0){
                    for ($i=0;$i<count($data['kode_fsgar']);$i++){								
                        $ins4 = "insert into relakungar (kode_neraca,kode_fs,kode_akun,kode_lokasi) values ('".$data['kode_neracagar'][$i]."','".$data['kode_fsgar'][$i]."','".$data['kode_akun']."','".$kode_lokasi."')";
                        array_push($exec,$ins4); 
                        
                    }
                }
                $rs=executeArray($exec,$err);  
                // $rs=true;    
                if ($err == null)
                {	
                    $tmp="sukses";
                    $sts=true;
                }else{
                    $tmp=$err;
                    $sts=false;
                }	
                
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
            
            $kode_akun= $data['kode_akun'];
            $kode_lokasi= $data['kode_lokasi'];
            $del1 = "delete from masakun where kode_akun = '".$kode_akun."' and kode_lokasi='".$kode_lokasi."'";
            array_push($exec,$del1);                   
            $del2 = "delete from relakun where kode_akun = '".$kode_akun."' and kode_lokasi='".$kode_lokasi."'";
            array_push($exec,$del2); 
            $del3 = "delete from relakungar where kode_akun = '".$kode_akun."' and kode_lokasi='".$kode_lokasi."'";
            array_push($exec,$del3); 
            $del4 = "delete from flag_relasi where kode_akun = '".$kode_akun."' and kode_lokasi='".$kode_lokasi."'";
            array_push($exec,$del4); 
            
            $rs=executeArray($exec,$err);
            $tmp=array();
            $kode = array();
            if ($err == null)
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