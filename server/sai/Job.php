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
        include_once($root_lib."lib/koneksi.php");
        include_once($root_lib."lib/helpers.php");
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

    function generateKode($tabel, $kolom_acuan, $prefix, $str_format){
        $query = execute("select right(max($kolom_acuan), ".strlen($str_format).")+1 as id from $tabel where $kolom_acuan like '$prefix%'");
        $kode = $query->fields[0];
        $id = $prefix.str_pad($kode, strlen($str_format), $str_format, STR_PAD_LEFT);
        return $id;
    }

    function isUnik($isi){
        getKoneksi();

        $schema = db_Connect();
        $auth = $schema->SelectLimit("SELECT kode_job FROM sai_job_m where kode_job='$isi' ", 1);
        if($auth->RecordCount() > 0){
            return false;
        }else{
            return true;
        }
    }

    function getProyek(){
        
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $kode_lokasi = $_GET['kode_lokasi'];
            
            $sql="select no_proyek,nama from sai_proyek where kode_lokasi='$kode_lokasi'";
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

    function getKaryawan(){
        
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $kode_lokasi = $_GET['kode_lokasi'];
            
            $sql="select nik,nama from sai_karyawan where kode_lokasi='$kode_lokasi'";
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

    function getJob(){
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $query = '';
            $output = array();
        
            $kode_lokasi = $_GET['kode_lokasi'];
            $query .= "select kode_job,nama,no_proyek,nik,convert(varchar(10),tgl_mulai,103) as tgl_mulai,convert(varchar(10),tgl_selesai,103) as tgl_selesai,progress from sai_job_m where kode_lokasi='".$kode_lokasi."'  ";

            $column_array = array('kode_job','nama','no_proyek','nik','convert(varchar(10),tgl_mulai,103)','convert(varchar(10),tgl_selesai,103)');
            $order_column = 'ORDER BY kode_job '.$_GET['order'][0]['dir'];
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
                $query .= ' ORDER BY kode_job ';
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
                $sub_array[] = $row->kode_job;
                $sub_array[] = $row->nama;
                $sub_array[] = $row->no_proyek;
                $sub_array[] = $row->nik;
                $sub_array[] = $row->tgl_mulai; 
                $sub_array[] = $row->tgl_selesai; 
                if($row->progress == '0'){
                    $sub_array[]= "<a href='#' title='Edit' class='badge badge-info' id='btn-edit'><i class='fas fa-pencil-alt'></i></a> &nbsp; <a href='#' title='Hapus' class='badge badge-danger' id='btn-delete'><i class='fa fa-trash'></i></a>";
                }else{
                    $sub_array[] = "<a href='#' title='History' class='badge badge-success' id='btn-history'><i class='fas fa-history'></i></a>";
                }
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
            $id=$_GET['kode_job'];    
        
            $response = array("message" => "", "rows" => 0, "status" => "" );
        
            $sql="select kode_job,nama,no_proyek,nik,convert(varchar(10),tgl_mulai,121) as tgl_mulai,convert(varchar(10),tgl_selesai,121) as tgl_selesai from sai_job_m where kode_lokasi='".$_GET['kode_lokasi']."' and kode_job='$id' ";
            
            $response['daftar'] = dbResultArray($sql);

            $sql2="select nu, kode_job, kode_lokasi, nik, nama, keterangan, status, tgl_input
            from sai_job_d where kode_lokasi='".$_GET['kode_lokasi']."' and kode_job='$id' ";
            
            $response['daftar2'] = dbResultArray($sql2);
            
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
            if(isUnik($_POST["kode_job"])){
                $data=$_POST;
                $exec = array();
                $kode = generateKode("sai_job_m", "kode_job", "JB-", "001");

                $sql1= "insert into sai_job_m(kode_job,nama,no_proyek,nik,tgl_mulai,kode_lokasi,tgl_selesai,progress) values ('".$kode."','".$data['nama']."','".$data['no_proyek']."','".$data['nik']."','".$data['tgl_mulai']."','".$data['kode_lokasi']."','".$data['tgl_selesai']."','0') ";

                array_push($exec,$sql1);

                $no= $data['no'];
                if(count($no) > 0){
                    for($i=0; $i<count($no);$i++){
                        $insdet = "insert into sai_job_d (nu,kode_job, kode_lokasi, nik, nama, keterangan, status, tgl_input ) values ($i,'$kode','".$data['kode_lokasi']."','".$data['nik']."','".$data['nama_det'][$i]."','".$data['ket_det'][$i]."',0,getdate())"; 
                        
                        array_push($exec,$insdet);
                    }
                }
                
                $rs=executeArray($exec);
                

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
                $response['sql']=$exec;
            }else{
                $tmp=" error:Duplicate Entry. kode_job sudah ada didatabase !";
                $sts=false;
            }

            $response["message"] =$tmp;
            $response["status"] = $sts;
            $response["error"] = $error_upload;
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
            
            $del = "delete from sai_job_m where kode_job='".$data['kode_job']."' and kode_lokasi='".$data['kode_lokasi']."' ";
            array_push($exec,$del);

            $del2 = "delete from sai_job_d where kode_job='".$data['kode_job']."' and kode_lokasi='".$data['kode_lokasi']."' ";
            array_push($exec,$del2);

            $sql1= "insert into sai_job_m(kode_job,nama,no_proyek,nik,tgl_mulai,kode_lokasi,tgl_selesai,progress) values ('".$data['kode_job']."','".$data['nama']."','".$data['no_proyek']."','".$data['nik']."','".$data['tgl_mulai']."','".$data['kode_lokasi']."','".$data['tgl_selesai']."','0') ";

            array_push($exec,$sql1);

            $no= $data['no'];
            if(count($no) > 0){
                for($i=0; $i<count($no);$i++){
                    $insdet = "insert into sai_job_d (nu,kode_job, kode_lokasi, nik, nama, keterangan, status, tgl_input ) values ($i,'".$data['kode_job']."','".$data['kode_lokasi']."','".$data['nik']."','".$data['nama_det'][$i]."','".$data['ket_det'][$i]."',0,getdate())"; 
                    
                    array_push($exec,$insdet);
                }
            }

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
            $response["exec"] = $exec;
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
            $del = "delete from sai_job_m where kode_job='".$data['kode_job']."' and kode_lokasi='".$data['kode_lokasi']."' ";
            array_push($exec,$del);

            $del2 = "delete from sai_job_d where kode_job='".$data['kode_job']."' and kode_lokasi='".$data['kode_lokasi']."' ";
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

    function getHistory(){
        
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $kode_lokasi = $_GET['kode_lokasi'];
            $no_bukti = $_GET['kode_job'];

            $sql="select a.kode_job,a.nama,a.nik,a.status,b.tanggal,b.keterangan,a.nu 
            from sai_job_d a 
            inner join sai_pesan b on a.kode_job=b.no_bukti and a.kode_lokasi=b.kode_lokasi and a.nu=b.no_urut            
            where a.kode_lokasi='$kode_lokasi' and a.kode_job='$no_bukti' ";
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

?>