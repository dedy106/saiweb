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
    
    function cekAuth($user,$pass){
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
        $auth = $schema->SelectLimit("SELECT kode_role FROM apv_role where kode_role='$isi' ", 1);
        if($auth->RecordCount() > 0){
            return false;
        }else{
            return true;
        }
    }

    function getPP(){
        
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $kode_lokasi = $_GET['kode_lokasi'];
            
            $sql="select kode_pp, nama from apv_pp where kode_lokasi='$kode_lokasi' ";
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

    function getJabatan(){
        
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $kode_lokasi = $_GET['kode_lokasi'];
            
            $sql="select kode_jab, nama from apv_jab where kode_lokasi='$kode_lokasi' ";
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
   
    function getRole(){
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $query = '';
            $output = array();
        
            $kode_lokasi = $_GET['kode_lokasi'];
            $query .= "
            select a.kode_role,a.nama,a.kode_pp,a.bawah,a.atas,case a.modul when 'JK' then 'Justifikasi Kebutuhan' else 'Justifikasi Pengadaan' end as modul
            from apv_role a
            where a.kode_lokasi='".$kode_lokasi."'  ";

            $column_array = array('a.kode_role','a.kode_pp','a.nama','a.bawah','a.atas','a.modul');
            $order_column = 'ORDER BY a.kode_role '.$_GET['order'][0]['dir'];
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
                $query .= ' ORDER BY  a.kode_role ';
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
                $sub_array[] = $row->kode_role;
                $sub_array[] = $row->kode_pp;
                $sub_array[] = $row->nama;                
                $sub_array[] = $row->bawah;             
                $sub_array[] = $row->atas;            
                $sub_array[] = $row->modul;
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
            $id=$_GET['kode_role'];    
        
            $response = array("message" => "", "rows" => 0, "status" => "" );
        
            $sql="select kode_role,kode_pp,nama,bawah,atas,modul from apv_role where kode_lokasi='".$_GET['kode_lokasi']."' and kode_role='$id' ";
            
            $rs = execute($sql);					
            
            while ($row = $rs->FetchNextObject(false)){
                $response['daftar'][] = (array)$row;
            }

            $sql="select kode_role,kode_jab,no_urut from apv_role_jab where kode_lokasi='".$_GET['kode_lokasi']."' and kode_role='$id'  order by no_urut";
            
            $rs = execute($sql);					
            
            while ($row = $rs->FetchNextObject(false)){
                $response['daftar2'][] = (array)$row;
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
                $kode_lokasi=$data['kode_lokasi'];
                $nik=$data['nik_user'];
                $exec = array();
                $kode_role=$data['kode_role'];
                if($data['id'] == 'edit'){
                    $del1 = "delete from apv_role where kode_role ='$kode_role' and kode_lokasi='$kode_lokasi' ";
                    array_push($exec,$del1);
                    $del2 = "delete from apv_role_jab where kode_role ='$kode_role' and kode_lokasi='$kode_lokasi' ";
                    array_push($exec,$del2);
                    $sts = true;
                }else{
                    if(!isUnik($_POST["kode_role"])){
                        $tmp=" error:Duplicate Entry. Kode Role sudah ada didatabase !";
                        $sts=false;
                    }else{
                        $sts= true;
                    }

                }
                
                if($sts){
                    
                    $sql1= "insert into apv_role (kode_role,kode_pp,nama,bawah,atas,kode_lokasi,modul) values ('".$kode_role."','".$data['kode_pp']."','".$data['nama']."',".joinNum($data['bawah']).",".joinNum($data['atas']).",'".$data['kode_lokasi']."','".$data['modul']."') ";
                    
                    array_push($exec,$sql1);
                    
                    if(count($data['kode_jab']) > 0){
                        for($i=0; $i<count($data['kode_jab']);$i++){
                            $insdet = "insert into apv_role_jab (kode_lokasi,kode_role,kode_jab,no_urut) values ('$kode_lokasi','$kode_role','".$data['kode_jab'][$i]."',$i)"; 
                            
                            array_push($exec,$insdet);
                        }
                    }
                    $rs=executeArray($exec);  
                    // $rs=true;    
                    if ($rs)
                    {	
                        $tmp="sukses";
                        $sts=true;
                    }else{
                        $tmp="gagal";
                        $sts=false;
                    }	

                }
                $response["message"] =$tmp;
                $response["status"] = $sts;
                $response["error"] = $error_upload;
                $response["sql"] = $exec;
                $response["sql2"] = $sql;
            
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
            $del = "delete from apv_role where kode_role='".$data['kode_role']."' and kode_lokasi='".$data['kode_lokasi']."' ";
            array_push($exec,$del);

            $del2 = "delete from apv_role_jab where kode_role='".$data['kode_role']."' and kode_lokasi='".$data['kode_lokasi']."' ";
            array_push($exec,$del2);
            
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