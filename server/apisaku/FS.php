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

    function getLib(){
		$root_lib=$_SERVER["DOCUMENT_ROOT"];
		if (substr($root_lib,-1)!="/") {
			$root_lib=$root_lib."/";
		}
        include_once($root_lib."lib/helpers.php");
        include_once($root_lib."lib/libcurl.php");
    }

    function joinNum2($num){
        // menggabungkan angka yang di-separate(10.000,75) menjadi 10000.00
        $num = str_replace(".", "", $num);
        $num = str_replace(",", ".", $num);
        return $num;
    }

    function getView(){
        session_start();
        getLib();
        if($_SESSION['isLogedIn']){
            try{

                $post = $_POST;
                $url = "http://api.simkug.com/api/gl/fs";
                $token = $_SESSION['token'];
                $response['fs'] = curl_view($url,$token);
                $hasil='';
                $data = array();
                $daftar = $response['fs']->success->data;
                if(count($daftar) > 0){

                    foreach($daftar as $row)
                    {
                        $sub_array = array();
                        $sub_array["kode_fs"] = $row->kode_fs;
                        $sub_array["nama"] = $row->nama;
                        $sub_array["flag_status"] = $row->flag_status;
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

                $kode_fs = $_GET['kode_fs'];
                $token = $_SESSION['token'];
                $url = "http://api.simkug.com/api/gl/fs/$kode_fs";
                $rs = curl_view($url,$token);
                $hasil='';
                $data = array();
                $row = $rs->success->data[0];
                if(count($row) > 0){

                    $sub_array["kode_fs"] = $row->kode_fs;
                    $sub_array["nama"] = $row->nama;
                    $sub_array["tgl_awal"] = substr($row->tgl_awal,0,10);
                    $sub_array["tgl_akhir"] = substr($row->tgl_akhir,0,10);
                    $sub_array["flag_status"] = $row->flag_status;
                    $data[] = $sub_array;
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


    function simpan(){
        session_start();
        getLib();
        if($_SESSION['isLogedIn']){
            try{

                $url = "http://api.simkug.com/api/gl/fs";
                $post=$_POST;
    
                $token = $_SESSION['token'];
                
                $fields = array(
                    "kode_fs" => $post['kode_fs'],
                    "nama" => $post['nama'],
                    "tgl_awal" => $post['tgl_awal'],
                    "tgl_akhir" => $post['tgl_akhir'],
                    "flag_status" => $post['flag_status'],
                );
                
                $response['rs'] = curl_simpan($url,$token,$fields);
                $response["status"] =  $response['rs']->success->status;
                $response["message"] =  $response['rs']->success->message;
               
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

    function ubah(){
        session_start();
        getLib();
        if($_SESSION['isLogedIn']){
            try{

                $data = $_POST;
                $kode_fs = $data['kode_fs'];
                $url = "http://api.simkug.com/api/gl/fs/$kode_fs";
    
                $token = $_SESSION['token'];
                
                $fields = array(
                    "kode_fs" => $data['kode_fs'],
                    "nama" => $data['nama'],
                    "tgl_awal" => $data['tgl_awal'],
                    "tgl_akhir" => $data['tgl_akhir'],
                    "flag_status" => $data['flag_status'],
                );

              
                $response['rs'] = curl_edit($url,$token,$fields);
                $response["status"] = $response['rs']->success->status;
                $response["message"] = $response['rs']->success->message;
               
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

    function hapus(){
        session_start();
        getLib();
        if($_SESSION['isLogedIn']){
            try{

                parse_str(file_get_contents('php://input'), $_DELETE);
                $data = $_DELETE;
                $token = $_SESSION['token'];
                $kode_fs = $data['kode_fs'];
                $url = "http://api.simkug.com/api/gl/fs/$kode_fs";
               
                $rs = curl_del($url,$token);
                $response["status"] = $rs->success->status;
                $response["message"] = $rs->success->message;
               
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
    