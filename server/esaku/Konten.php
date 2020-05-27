<?php

    $request_method=$_SERVER["REQUEST_METHOD"];

    switch($request_method) {
        case 'GET':
            if(isset($_GET["fx"]) AND function_exists($_GET['fx'])){
                $_GET['fx']();
            }
        break;
        case 'POST':
            if(isset($_GET["fx"]) AND function_exists($_GET['fx'])){
                $_GET['fx']();
            }
            else{
                insertKonten();
            }
        break;
        case 'PUT': 
            updateKonten();
        break;
        case 'DELETE':
            deleteKonten();
        break;
        default:
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
        include_once($root_lib."web/lib/koneksi.php");
        include_once($root_lib."web/lib/helpers.php");
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

    //FORM KONTEN
    function getKonten(){

        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'],$_SESSION['userPwd'])){

            $data = $_POST;
            $query = '';
            $output = array();
        
            $kode_lokasi = $data['kode_lokasi'];
            $query .= "select no_konten, convert(varchar, tanggal, 105) as tanggal, judul from sai_konten 
            where kode_lokasi= '".$kode_lokasi."'  ";

            $column_array = array('no_konten','convert(varchar, tanggal, 105)','judul');
            $order_column = 'ORDER BY no_konten '.$data['order'][0]['dir'];
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
                $query .= ' ORDER BY '.$column_array[$data['order'][0]['column']].' '.$data['order'][0]['dir'];
            }
            else
            {
                $query .= ' ORDER BY no_konten ';
            }
            if($data["length"] != -1)
            {
                $query .= ' OFFSET ' . $data['start'] . ' ROWS FETCH NEXT ' . $data['length'] . ' ROWS ONLY ';
            }
            $statement = execute($query);
            $data = array();
            $filtered_rows = $statement->RecordCount();
            while($row = $statement->FetchNextObject($toupper=false))
            {
                $sub_array = array();
                $sub_array[] = $row->no_konten;
                $sub_array[] = $row->tanggal;
                $sub_array[] = $row->judul;
                $data[] = $sub_array;
            }
            $response = array(
                "draw"				=>	intval($data["draw"]),
                "recordsTotal"		=> 	$filtered_rows,
                "recordsFiltered"	=>	$jml_baris,
                "data"				=>	$data,
                "query" =>$query
            );
        }else{
            $response["message"] = "Unauthorized Access, Login Required";
        }
        header('Content-Type: application/json');
        echo json_encode($response);
        
    }
       

    
    // function getKelompok(){
    //     getKoneksi();
    //     session_start();
    //     getKoneksi();
    //     if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'],$_SESSION['userPwd'])){
    //         $kode_lokasi=$_GET['kode_lokasi'];
    //         $response = array("message" => "", "rows" => 0, "status" => "" );

    //         $sql1="SELECT kode_klp, nama FROM sai_konten_klp where kode_lokasi='$kode_lokasi' ";

    //         $rs = execute($sql1);				
            
    //         $response['daftar'] = array();
    //         while ($row = $rs->FetchNextObject(false)){
    //             $response['daftar'][] = (array)$row;
    //         }
    //         $response['status']=TRUE;
        
    //     }else{
    //         $response["message"] = "Unauthorized Access, Login Required";
    //     }
    //     header('Content-Type: application/json');
    //     echo json_encode($response);
    
    // }

    

    function getGaleri(){
        session_start();
        getKoneksi();
   
        $kode_lokasi=$_POST['kode_lokasi'];
        $result = array("message" => "", "rows" => 0, "status" => "" );
    
        $sql1 = "select id as kode, nama from sai_konten_galeri where kode_lokasi='".$kode_lokasi."' ";
        $rs = execute($sql1);					
        
        $result['daftar'] = array();
        while ($row = $rs->FetchNextObject(false)){
            $result['daftar'][] = (array)$row;
        }
        $result['status']=TRUE;
        $result['sql']=$sql1;
        echo json_encode($result);
    
    }

    function getKtg(){
        session_start();
        getKoneksi();
   
        $kode_lokasi=$_POST['kode_lokasi'];
        $result = array("message" => "", "rows" => 0, "status" => "" );
    
        $sql1 = "select kode_ktg, nama from sai_konten_ktg where kode_lokasi='".$kode_lokasi."' ";
        $rs = execute($sql1);					
        
        $result['daftar'] = array();
        while ($row = $rs->FetchNextObject(false)){
            $result['daftar'][] = (array)$row;
        }
        $result['status']=TRUE;
        $result['sql']=$sql1;
        echo json_encode($result);
    
    }

    function getEditData(){
        getKoneksi();
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'],$_SESSION['userPwd'])){

            $id=$_POST['kode'];    
        
            $response = array("message" => "", "rows" => 0, "status" => "" );
        
            $sql="select no_konten, convert(varchar, tanggal, 105) as tanggal, judul, isi, file_gambar,kode_kategori,tag from sai_konten where kode_lokasi='".$_POST['lokasi']."' and no_konten='$id' ";
            
            $rs = execute($sql);					
            
            while ($row = $rs->FetchNextObject(false)){
                $response['daftar'][] = (array)$row;
            }
            $response['status'] = TRUE;
            $response['sql'] = $sql;
        }else{
            $response["message"] = "Unauthorized Access, Login Required";
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    
    }

    function insertKonten(){

        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'],$_SESSION['userPwd'])){
            $data = $_POST;

            $sql = " select isnull(max(no_konten),0)+1 as id from sai_konten where kode_lokasi='99' ";
            $rs1 = execute($sql);

            $id = $rs1->fields[0];

            $sql1= "insert into sai_konten (no_konten,kode_lokasi,tanggal,judul,isi,nik_buat,tgl_input,flag_aktif,tag,kode_kategori,file_gambar,file_dok) values ('$id','".$data['kode_lokasi']."',getdate(),'".$data['judul']."','".$data['isi']."','".$data['nik_user']."','".reverseDate($data['tanggal'])."','0','".$data['tag']."','".$data['kode_kategori']."','".$data['gambar']."','') ";
            
            $sql= array();
            array_push($sql,$sql1);
            $rs= executeArray($sql);


            $rs = true;
            $tmp=array();
            $kode = array();
            $sts=false;
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
            $response["sql"] = $sql;
        }else{
            $response["message"] = "Unauthorized Access, Login Required";
        }
        echo json_encode($response);
    }
    

    function updateKonten(){
        getKoneksi();
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'],$_SESSION['userPwd'])){
            parse_str(file_get_contents('php://input'), $_PUT);
            $data = $_POST;

            $sql1="update sai_konten set judul='".$data['judul']."',tanggal='".reverseDate($data['tanggal'])."',isi='".$data['isi']."',file_gambar='".$data['gambar']."',kode_kategori='".$data['kategori']."',tag='".$data['tag']."' where no_konten = '".$data['id']."' and kode_lokasi='".$data['kode_lokasi']."' ";
            
            $sql=[$sql1];
            $rs=executeArray($sql);

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
            echo json_encode($response);
        }else{
            $response["message"] = "Unauthorized Access, Login Required";
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }
    

    function deleteKonten(){
        getKoneksi();
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'],$_SESSION['userPwd'])){
            parse_str(file_get_contents('php://input'), $_DELETE);
            $data = $_DELETE;
            $sql1="delete from sai_konten where no_konten='".$data['id']."' and kode_lokasi='".$data['kode_lokasi']."' ";
            $sql=array();
            array_push($sql,$sql1);
            $rs=executeArray($sql);

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
            $response["message"] = "Unauthorized Access, Login Required";
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    function view(){
        getKoneksi();
        $sql= dbRowArray("select kode_lokasi from lokasi where kode_lokasi='99'");
        echo "Lokasi=".$sql["kode_lokasi"];
    }

?>
