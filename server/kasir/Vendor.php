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
        $auth = $schema->SelectLimit("SELECT kode_vendor FROM vendor where kode_vendor='$isi' ", 1);
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
            
            $sql="select a.kode_akun, a.nama from masakun a inner join flag_relasi b on a.kode_akun=b.kode_akun and a.kode_lokasi=b.kode_lokasi and b.kode_flag = '024' where a.kode_lokasi='$kode_lokasi'";
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

    
    function getVendor(){
        session_start();
        getKoneksi();
        if($_COOKIE['isLogedIn'] AND cekAuth($_COOKIE['userLog'])){
            $query = '';
            $output = array();
        
            $kode_lokasi = $_REQUEST['kode_lokasi'];
            $query .= "SELECT kode_vendor, nama, alamat FROM vendor as status where kode_lokasi= '".$kode_lokasi."'  ";

            $column_array = array('kode_vendor','nama','alamat');
            $order_column = 'ORDER BY kode_vendor '.$_POST['order'][0]['dir'];
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
                $query .= ' ORDER BY kode_vendor ';
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
                $sub_array[] = $row->kode_vendor;
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
        getKoneksi();
        if($_COOKIE['isLogedIn'] AND cekAuth($_COOKIE['userLog'])){
            $id=$_GET['kode_vendor'];    
        
            $response = array("message" => "", "rows" => 0, "status" => "" );
        
            $sql="select kode_vendor,nama,alamat,no_tel,no_fax,email,npwp,alamat2,pic,akun_hutang,bank,cabang,no_rek,nama_rek,no_pictel from vendor where kode_lokasi='".$_GET['kode_lokasi']."' and kode_vendor='$id' ";
            
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
            if(isUnik($_POST["kode_vendor"])){
                $data=$_POST;

                $exec = array();

                $sql1= "insert into vendor(kode_vendor,kode_lokasi,nama,alamat,no_tel,email,npwp,pic,alamat2,bank,cabang,no_rek,nama_rek,no_fax,no_pictel,spek,kode_klpvendor,penilaian,bank_trans,akun_hutang) values ('".$data['kode_vendor']."','".$data['kode_lokasi']."','".$data['nama']."','".$data['alamat']."','".$data['no_telp']."','".$data['email']."','".$data['npwp']."','".$data['pic']."','".$data['alamat_npwp']."','".$data['bank']."','".$data['cabang']."','".$data['no_rek']."','".$data['nama_rek']."','".$data['no_fax']."','".$data['no_tel2']."','-','-','-','-','".$data['akun_hutang']."') ";

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
                $tmp=" error:Duplicate Entry. Kode Vendor sudah ada didatabase !";
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
            $del = "delete from vendor where kode_vendor='".$data['kode_vendor']."' and kode_lokasi='".$data['kode_lokasi']."' ";
            array_push($exec,$del);

            $sql1= "insert into vendor(kode_vendor,kode_lokasi,nama,alamat,no_tel,email,npwp,pic,alamat2,bank,cabang,no_rek,nama_rek,no_fax,no_pictel,spek,kode_klpvendor,penilaian,bank_trans,akun_hutang) values ('".$data['kode_vendor']."','".$data['kode_lokasi']."','".$data['nama']."','".$data['alamat']."','".$data['no_telp']."','".$data['email']."','".$data['npwp']."','".$data['pic']."','".$data['alamat_npwp']."','".$data['bank']."','".$data['cabang']."','".$data['no_rek']."','".$data['nama_rek']."','".$data['no_fax']."','".$data['no_tel2']."','-','-','-','-','".$data['akun_hutang']."') ";
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
            $del = "delete from vendor where kode_vendor='".$data['kode_vendor']."' and kode_lokasi='".$data['kode_lokasi']."' ";
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