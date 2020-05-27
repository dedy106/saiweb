<?php

    $request_method=$_SERVER["REQUEST_METHOD"];

    switch($request_method) {
        case 'GET':
            if(isset($_GET["fx"]) AND function_exists($_GET['fx'])){
                $_GET['fx']();
            }
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
		include_once($root_lib.'app/ajax/setting.php');
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

    function getDaftarJob(){
        
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $tmp = explode("|",$_GET['param']);
            $kode_lokasi = $tmp[0];
            
            $sql="select a.nik,a.nama,isnull(a.foto,'-') as foto, isnull(b.job_total,0) as job_total,isnull(c.job_finish,0) as job_finish, isnull(b.job_total,0)-isnull(c.job_finish,0) as job_inprog
            from sai_karyawan a
            left join (select a.nik,a.kode_lokasi,count(*) as job_total
                       from sai_job_d a
                       group by a.nik,a.kode_lokasi
                       ) b on a.nik=b.nik and a.kode_lokasi=b.kode_lokasi
            left join (select a.nik,a.kode_lokasi,count(*) as job_finish
                       from sai_job_d a 
                       where a.status = '1'
                       group by a.nik,a.kode_lokasi
                       ) c on a.nik=c.nik and a.kode_lokasi=c.kode_lokasi and b.nik=c.nik
            where a.kode_lokasi='$kode_lokasi' ";
            $response['daftar']=dbResultArray($sql);

            $response['status'] = TRUE;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }

?>