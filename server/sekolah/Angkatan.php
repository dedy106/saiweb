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
		include_once($root_lib.'app/sekolah/setting.php');
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
            $query .= "select kode_akt, nama, kode_tingkat,flag_aktif from sis_angkat where kode_lokasi='".$kode_lokasi."' and kode_pp='$kode_pp'";

            $column_array = array('kode_akt','nama','kode_tingkat','flag_aktif');
            $order_column = 'ORDER BY kode_akt '.$_GET['order'][0]['dir'];
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
                $query .= ' ORDER BY kode_akt ';
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
                $sub_array[] = $row->kode_akt;
                $sub_array[] = $row->nama;  
                $sub_array[] = $row->kode_tingkat;
                $sub_array[] = $row->flag_aktif;              
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
            $id=$_GET['kode_ta'];   
            $kode_lokasi=$_GET['kode_lokasi'];
            $kode_pp=$_GET['kode_pp']; 
        
            $response = array("message" => "", "rows" => 0, "status" => "" );
        
            $sql="select nama, flag_aktif,tgl_mulai,tgl_akhir from sis_ta where kode_ta ='".$id."' and kode_lokasi='".$kode_lokasi."'  and kode_pp='".$kode_pp."' ";
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

    // function simpan(){
    //     session_start();
    //     getKoneksi();
    //     if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
    //         $data=$_POST;
    //         if(isUnik($data["kode_ta"],$data['kode_lokasi'],$data['kode_pp'])){
    //             $exec = array();
    //             //$kode = generateKode("dev_jenis", "kode_jenis", "J", "001");
                
    //             $sql1= "insert into id_pekerjaan(id_pekerjaan,nama,kode_lokasi) values ('".$data['id_pekerjaan']."','".$data['nama']."','".$data['kode_lokasi']."') ";
                
    //             array_push($exec,$sql1);
                
    //             $rs=executeArray($exec,$err);
                
    //             if ($err == null)
    //             {	
    //                 $tmp="sukses";
    //                 $sts=true;
    //             }else{
    //                 $tmp=$err;
    //                 $sts=false;
    //             }	 
    //         }else{
    //             $tmp=" error:Duplicate Entry. Kode TA sudah terdaftar di database !";
    //             $sts=false;
    //         }
            
    //         $response["message"] =$tmp;
    //         $response["status"] = $sts;
    //     }else{
            
    //         $response["status"] = false;
    //         $response["message"] = "Unauthorized Access, Login Required";
    //     }
    //     // header('Content-Type: application/json');
    //     echo json_encode($response);
    // }
    

    // function ubah(){
    //     session_start();
    //     getKoneksi();
    //     $data = $_POST;
    //     if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
    //         $exec = array();

    //         $del = "delete from dgw_pekerjaan where id_pekerjaan='".$data['id_pekerjaan']."' and kode_lokasi='".$data['kode_lokasi']."' ";
    //         array_push($exec,$del);

    //         $sql1= "insert into dgw_pekerjaan(id_pekerjaan,nama,kode_lokasi) values ('".$data['id_pekerjaan']."','".$data['nama']."','".$data['kode_lokasi']."') ";

    //         array_push($exec,$sql1);

    //         $rs=executeArray($exec,$err);
            
    //         if ($err == null)
    //         {	
    //             $tmp="sukses";
    //             $sts=true;
    //         }else{
    //             $tmp=$err;
    //             $sts=false;
    //         }	 
    //         $response["message"] =$tmp;
    //         $response["status"] = $sts;
    //     }else{
            
    //         $response["status"] = false;
    //         $response["message"] = "Unauthorized Access, Login Required";
    //     }
    //     // header('Content-Type: application/json');
    //     echo json_encode($response);
    // }
    

    // function hapus(){
    //     session_start();
    //     getKoneksi();
    //     if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            
    //         $exec = array();
    //         parse_str(file_get_contents('php://input'), $_DELETE);
    //         $data = $_DELETE;
    //         $del = "delete from dgw_pekerjaan where id_pekerjaan='".$data['id_pekerjaan']."' and kode_lokasi='".$data['kode_lokasi']."' ";
    //         array_push($exec,$del);
            
    //         $rs=executeArray($exec,$err);
            
    //         if ($err == null)
    //         {	
    //             $tmp="sukses";
    //             $sts=true;
    //         }else{
    //             $tmp=$err;
    //             $sts=false;
    //         }	 	
    //         $response["message"] =$tmp;
    //         $response["status"] = $sts;
    //     }else{
                
    //         $response["status"] = false;
    //         $response["message"] = "Unauthorized Access, Login Required";
    //     }
    //     // header('Content-Type: application/json');
    //     echo json_encode($response);
    // }