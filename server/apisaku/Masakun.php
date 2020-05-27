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

    function getLib(){
		$root_lib=$_SERVER["DOCUMENT_ROOT"];
		if (substr($root_lib,-1)!="/") {
			$root_lib=$root_lib."/";
		}
        include_once($root_lib."lib/helpers.php");
        include_once($root_lib."lib/libcurl.php");
    }

    function getCurr(){
        
        session_start();
        getLib();
        if($_SESSION['isLogedIn']){
            try{

                $url = "http://api.simkug.com/api/gl/currency";
                $post=$_POST;
    
                $token = $_SESSION['token'];
                $rs = curl_view($url,$token);
                $hasil='';
                $data = array();
                $daftar = $rs->success->data;
                if(count($daftar) > 0){

                    foreach($daftar as $row)
                    {
                        $data[] = (array) $row;
                    }
                }
                $response["daftar"]=$data;
                
                $response["status"] = true;
            }  catch (exception $e) { 
                error_log($e->getMessage());		
                $response["error"]= $e->getMessage();
            } 	
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }

        // header('Content-Type: application/json');
        echo json_encode($response);
    }

    function getModul(){
        
        session_start();
        getLib();
        if($_SESSION['isLogedIn']){
            try{

                $url = "http://api.simkug.com/api/gl/modul";
                $post=$_POST;
    
                $token = $_SESSION['token'];
                
                $rs = curl_view($url,$token);
                $hasil='';
                $data = array();
                $daftar = $rs->success->data;
                if(count($daftar) > 0){

                    foreach($daftar as $row)
                    {
                        $data[] = (array) $row;
                    }
                }
                $response["daftar"]=$data;
                
                $response["status"] = true;
            }  catch (exception $e) { 
                error_log($e->getMessage());		
                $response["error"]= $e->getMessage();
            } 	
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }

        // header('Content-Type: application/json');
        echo json_encode($response);
    }

    function getFlag(){
        
        // $sql="select kode_flag, nama from flag_akun ";
        session_start();
        getLib();
        if($_SESSION['isLogedIn']){
            try{

                $root = "http://api.simkug.com/api/gl/flag_akun";
                $post=$_POST;
    
                $token = $_SESSION['token'];
                $rs = curl_view($url,$token);
                $hasil='';
                $data = array();
                $daftar = $rs->success->data;
                if(count($daftar) > 0){

                    foreach($daftar as $row)
                    {
                        $data[] = (array) $row;
                    }
                }
                $response["daftar"]=$data;
                
                $response["status"] = true;
            }  catch (exception $e) { 
                error_log($e->getMessage());		
                $response["error"]= $e->getMessage();
            } 	
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }

        echo json_encode($response);
    }

    function getFS(){
        
        session_start();
        getLib();
        if($_SESSION['isLogedIn']){
            try{

                $url = "http://api.simkug.com/api/gl/fs";
                $post=$_POST;
    
                $token = $_SESSION['token'];
                
                $rs = curl_view($url,$token);
                $hasil='';
                $data = array();
                $daftar = $rs->success->data;
                if(count($daftar) > 0){

                    foreach($daftar as $row)
                    {
                        $data[] = (array) $row;
                    }
                }
                $response["daftar"]=$data;
                
                $response["status"] = true;
            }  catch (exception $e) { 
                error_log($e->getMessage());		
                $response["error"]= $e->getMessage();
            } 	
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }

        echo json_encode($response);
    }

    function getNeraca(){
        session_start();
        getLib();
        if($_SESSION['isLogedIn']){
            try{

                $get=$_GET;
                $kode_fs=$get["kode_fs"];
                $url = "http://api.simkug.com/api/gl/neraca/$kode_fs";
                
                $token = $_SESSION['token'];
                $rs = curl_view($url,$token);
                $hasil='';
                $data = array();
                $daftar = $rs->success->data;
                if(count($daftar) > 0){

                    foreach($daftar as $row)
                    {
                        $data[] = (array) $row;
                    }
                }
                $response["daftar"]=$data;
                
                $response["status"] = true;
            }  catch (exception $e) { 
                error_log($e->getMessage());		
                $response["error"]= $e->getMessage();
            } 	
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }

        echo json_encode($response);
    }
   
    function getFSGar(){
        
        session_start();
        getLib();
        if($_SESSION['isLogedIn']){
            try{

                $url = "http://api.simkug.com/api/gl/fsgar";
                $token = $_SESSION['token'];
                
                $rs = curl_view($url,$token);
                $hasil='';
                $data = array();
                $daftar = $rs->success->data;
                if(count($daftar) > 0){

                    foreach($daftar as $row)
                    {
                        $data[] = (array) $row;
                    }
                }
                $response["daftar"]=$data;
                
                $response["status"] = true;
            }  catch (exception $e) { 
                error_log($e->getMessage());		
                $response["error"]= $e->getMessage();
            } 	
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }

        echo json_encode($response);
    }

    function getNeracaGar(){
        session_start();
        getLib();
        if($_SESSION['isLogedIn']){
            try{

                $get=$_GET;
                $kode_fs=$get["kode_fs"];
                $url = "http://api.simkug.com/api/gl/neracagar/$kode_fs";
    
                $token = $_SESSION['token'];
                $rs = curl_view($url,$token);
                $hasil='';
                $data = array();
                $daftar = $rs->success->data;
                if(count($daftar) > 0){

                    foreach($daftar as $row)
                    {
                        $data[] = (array) $row;
                    }
                }
                $response["daftar"]=$data;
                
                $response["status"] = true;
            }  catch (exception $e) { 
                error_log($e->getMessage());		
                $response["error"]= $e->getMessage();
            } 	
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }

        echo json_encode($response);
    }
   
    function getView(){
        session_start();
        getLib();
        if($_SESSION['isLogedIn']){
            try{

                $url = "http://api.simkug.com/api/gl/masakun";
                $token = $_SESSION['token'];
                
                $rs = curl_view($url,$token);
                $hasil='';
                $data = array();
                $daftar = $rs->success->data;
                if(count($daftar) > 0){

                    foreach($daftar as $row)
                    {
                        $sub_array = array();
                        $sub_array["kode_akun"] = $row->kode_akun;
                        $sub_array["nama"] = $row->nama;
                        $sub_array["kode_curr"] = $row->kode_curr;
                        $sub_array["modul"] = $row->modul;
                        $sub_array["jenis"] = $row->jenis;
                        $data[] = $sub_array;
                    }
                }
                $response["data"]=$data;
                
                $response["status"] = true;
            }  catch (exception $e) { 
                error_log($e->getMessage());		
                $response["error"]= $e->getMessage();
            } 	
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }

    function getEdit(){
        session_start();
        getLib();
        if($_SESSION['isLogedIn']){
            try{

                $kode_akun = $_GET['kode_akun'];
                $url = "http://api.simkug.com/api/gl/masakun/$kode_akun";
    
                $token = $_SESSION['token'];
                
                $rs = curl_view($url,$token);
                $hasil='';
                $data = array();
                $data2 = array();
                $data3 = array();
                $data4 = array();
                $row = $rs->success->data[0];
                if(count($row) > 0){
                    $sub_array["kode_akun"] = $row->kode_akun;
                    $sub_array["nama"] = $row->nama;
                    $sub_array["jenis"] = $row->jenis;
                    $sub_array["modul"] = $row->modul;
                    $sub_array["curr"] = $row->kode_curr;
                    $sub_array["block"] = $row->block;
                    $sub_array["status_gar"] = $row->status_gar;
                    $sub_array["normal"] = $row->normal;
                    $data[] = $sub_array;
                }
                $response["daftar"]=$data;

                $row2 = $rs->success->detail_relasi;
                if(count($row2) > 0){
                    foreach($row2 as $row)
                    {
                        $data2[] = (array) $row;
                    }
                }
                $response["daftar2"]=$data2;

                $row3 = $rs->success->detail_keuangan;
                if(count($row3) > 0){
                    foreach($row3 as $row)
                    {
                        $data3[] = (array) $row;
                    }
                }
                $response["daftar3"]=$data3;

                $row4 = $rs->success->detail_anggaran;
                if(count($row4) > 0){
                    foreach($row4 as $row)
                    {
                        $data4[] = (array) $row;
                    }
                }
                $response["daftar4"]=$data4;
                
                $response["status"] = true;
            }  catch (exception $e) { 
                error_log($e->getMessage());		
                $response["error"]= $e->getMessage();
            } 	
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
    //         $kode_lokasi=$data['kode_lokasi'];
    //         $nik=$data['nik_user'];
    //         $exec = array();
    //         $kode_akun=$data['kode_akun'];
    //         if($data['id'] == 'edit'){
    //             $del1 = "delete from masakun where kode_akun = '".$kode_akun."' and kode_lokasi='".$kode_lokasi."'";
    //             array_push($exec,$del1);                   
    //             $del2 = "delete from relakun where kode_akun = '".$kode_akun."' and kode_lokasi='".$kode_lokasi."'";
    //             array_push($exec,$del2); 
    //             $del3 = "delete from relakungar where kode_akun = '".$kode_akun."' and kode_lokasi='".$kode_lokasi."'";
    //             array_push($exec,$del3); 
    //             $del4 = "delete from flag_relasi where kode_akun = '".$kode_akun."' and kode_lokasi='".$kode_lokasi."'";
    //             array_push($exec,$del4); 
    //             $sts = true;
    //         }else{
    //             if(!isUnik($_POST["kode_akun"])){
    //                 $tmp=" error:Duplicate Entry. Kode Akun sudah ada didatabase !";
    //                 $sts=false;
    //             }else{
    //                 $sts= true;
    //             }
                
    //         }
            
    //         if($sts){
    //             $ins = "insert into masakun (kode_akun,kode_lokasi,nama,modul,jenis,kode_curr,block,status_gar,normal) values 
    //             ('".$data['kode_akun']."','".$kode_lokasi."','".$data['nama']."','".$data['modul']."','".$data['jenis']."','".$data['curr']."','0','".$data['gar']."','".$data['normal']."')";
    //             array_push($exec,$ins); 
    //             if (count($data['kode_flag']) > 0){
    //                 for ($i=0;$i<count($data['kode_flag']);$i++){
    //                     $ins2 = "insert into flag_relasi(kode_akun,kode_lokasi,kode_flag) values 
    //                     ('".$data['kode_akun']."','".$kode_lokasi."','".$data['kode_flag'][$i]."')";
    //                     array_push($exec,$ins2); 
    //                 }
    //             }
    //             if (count($data['kode_fs']) > 0){
    //                 for ($i=0;$i<count($data['kode_fs']);$i++){							
    //                     $ins3 = "insert into relakun (kode_neraca,kode_fs,kode_akun,kode_lokasi) values ('".$data['kode_neraca'][$i]."','".$data['kode_fs'][$i]."','".$data['kode_akun']."','".$kode_lokasi."')";
    //                     array_push($exec,$ins3); 
    //                 }
    //             }
    //             if (count($data['kode_fsgar']) > 0){
    //                 for ($i=0;$i<count($data['kode_fsgar']);$i++){								
    //                     $ins4 = "insert into relakungar (kode_neraca,kode_fs,kode_akun,kode_lokasi) values ('".$data['kode_neracagar'][$i]."','".$data['kode_fsgar'][$i]."','".$data['kode_akun']."','".$kode_lokasi."')";
    //                     array_push($exec,$ins4); 
                        
    //                 }
    //             }
    //             $rs=executeArray($exec,$err);  
    //             // $rs=true;    
    //             if ($err == null)
    //             {	
    //                 $tmp="sukses";
    //                 $sts=true;
    //             }else{
    //                 $tmp=$err;
    //                 $sts=false;
    //             }	
                
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
            
    //         $kode_akun= $data['kode_akun'];
    //         $kode_lokasi= $data['kode_lokasi'];
    //         $del1 = "delete from masakun where kode_akun = '".$kode_akun."' and kode_lokasi='".$kode_lokasi."'";
    //         array_push($exec,$del1);                   
    //         $del2 = "delete from relakun where kode_akun = '".$kode_akun."' and kode_lokasi='".$kode_lokasi."'";
    //         array_push($exec,$del2); 
    //         $del3 = "delete from relakungar where kode_akun = '".$kode_akun."' and kode_lokasi='".$kode_lokasi."'";
    //         array_push($exec,$del3); 
    //         $del4 = "delete from flag_relasi where kode_akun = '".$kode_akun."' and kode_lokasi='".$kode_lokasi."'";
    //         array_push($exec,$del4); 
            
    //         $rs=executeArray($exec,$err);
    //         $tmp=array();
    //         $kode = array();
    //         if ($err == null)
    //         {	
    //             $tmp="sukses";
    //             $sts=true;
    //         }else{
    //             $tmp="gagal";
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