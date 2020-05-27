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

    function joinNum2($num){
        // menggabungkan angka yang di-separate(10.000,75) menjadi 10000.00
        $num = str_replace(".", "", $num);
        $num = str_replace(",", ".", $num);
        return $num;
    }

    function generateKode($tabel, $kolom_acuan, $prefix, $str_format){
        $query = execute("select right(max($kolom_acuan), ".strlen($str_format).")+1 as id from $tabel where $kolom_acuan like '$prefix%'");
        $kode = $query->fields[0];
        $id = $prefix.str_pad($kode, strlen($str_format), $str_format, STR_PAD_LEFT);
        return $id;
    }

    function isUnik($isi,$no_bukti){
        getKoneksi();

        $schema = db_Connect();
        $res = array();
        $auth = $schema->SelectLimit("select no_bukti from trans_m where no_dokumen = '".$isi."' and kode_lokasi='".$_SESSION['lokasi']."' and no_bukti <> '".$no_bukti."' ", 1);
        if($auth->RecordCount() > 0){
            // return false;
            $res['status'] = false;
            $res['no_bukti'] = $auth->fields[0];
        }else{
            $res['status'] = true;
        }
        return $res;
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

    function doCekPeriode2($modul,$status,$periode) {
        try{
            
            $perValid = false;
            
            if ($status == "A") {
    
                $strSQL = "select modul from periode_aktif where kode_lokasi ='".$_SESSION['lokasi']."'  and modul ='".$modul."' and '".$periode."' between per_awal2 and per_akhir2";
            }else{
    
                $strSQL = "select modul from periode_aktif where kode_lokasi ='".$_SESSION['lokasi']."'  and modul ='".$modul."' and '".$periode."' between per_awal1 and per_akhir1";
            }
            $schema = db_Connect();
            $auth = $schema->SelectLimit($strSQL, 1);
            if($auth->RecordCount() > 0){
                $perValid = true;
            }
            $msg = "ok";
        }catch (exception $e) { 
            error_log($e->getMessage());		
            $msg= " error " .  $e->getMessage();
            $perValid = false;
        } 	
        $result['status'] = $perValid;
        $result['message'] = $msg;
        return $result;		
	}

    function getAkun(){
        
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $kode_lokasi = $_GET['kode_lokasi'];
            
            $sql="select a.kode_akun,a.nama from masakun a inner join flag_relasi b on a.kode_akun=b.kode_akun and a.kode_lokasi=b.kode_lokasi and b.kode_flag = '034' where a.block= '0' and a.kode_lokasi='$kode_lokasi' ";
            $response['daftar']=dbResultArray($sql);
            $response['status'] = TRUE;
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
            $kode_lokasi = $_GET['kode_lokasi'];
            $kode_pp = $_SESSION['kodePP'];
            $nik = $_SESSION['userLog'];
            if ($_SESSION['userStatus'] == "U"){

				$sql = "select a.kode_pp,a.nama from pp a where a.kode_pp='".$kode_pp."'  and a.kode_lokasi = '".$kode_lokasi."' and a.flag_aktif='1' ";
            }else{

                $sql = "select a.kode_pp,a.nama from pp a inner join karyawan_pp b on a.kode_pp=b.kode_pp and a.kode_lokasi=b.kode_lokasi and b.nik='".$nik."' 
                where a.kode_lokasi = '".$kode_lokasi."' and a.flag_aktif='1' ";
            }

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
            $data= $_GET;
        
            $kode_lokasi = $_REQUEST['kode_lokasi'];
            $query .= "select no_bukti,tanggal,no_dokumen,keterangan,nilai1 from trans_m where modul='MI' and kode_lokasi='$kode_lokasi' ";

            $column_array = array('no_bukti','tanggal','no_dokumen','keterangan','nilai1');
            $order_column = 'ORDER BY no_bukti '.$data['order'][0]['dir'];
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
                $query .= ' ORDER BY no_bukti ';
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
                $sub_array[] = $row->no_bukti;
                $sub_array[] = $row->tanggal;
                $sub_array[] = $row->no_dokumen;
                $sub_array[] = $row->keterangan;
                $sub_array[] = $row->nilai1;
                $data[] = $sub_array;
            }
            $response = array(
                "draw"				=>	intval($data["draw"]),
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
            $id=$_GET['no_bukti'];    
            $kode_lokasi=$_SESSION['lokasi'];
        
            $response = array("message" => "", "rows" => 0, "status" => "" );
            $sql = "select tanggal,no_bukti,periode,keterangan as deskripsi,nilai1,no_dokumen,modul as jenis from trans_m where no_bukti = '".$id."' and kode_lokasi='".$kode_lokasi."'";						
            $response['daftar']= dbResultArray($sql);
            
            $sql2="select a.kode_akun,b.nama as nama_akun,a.dc,a.keterangan,a.nilai,a.kode_pp,c.nama as nama_pp 
                    from trans_j a 
                    inner join masakun b on a.kode_akun=b.kode_akun and a.kode_lokasi=b.kode_lokasi 
                    inner join pp c on a.kode_pp=c.kode_pp and a.kode_lokasi=c.kode_lokasi 
                    where a.no_bukti = '".$id."' and a.kode_lokasi='".$kode_lokasi."' order by a.nu";
            $response['daftar2']= dbResultArray($sql2);
            $response['sql1']=$sql;
            $response['sql2']=$sql2;
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
            $kode_lokasi=$_SESSION['lokasi'];
            $nik=$_SESSION['userLog'];
            $kode_pp=$_SESSION['kodePP'];

            $periode=substr($data['tanggal'],0,4).substr($data['tanggal'],5,2);
            $exec = array();
            
            if($data['id'] == 'edit'){
                $no_bukti = $data['no_bukti'];
                $del1 = "delete from trans_m where no_bukti = '".$no_bukti."' and kode_lokasi='".$kode_lokasi."' ";
                array_push($exec,$del1);                   
                $del2 = "delete from trans_j where no_bukti = '".$no_bukti."' and kode_lokasi='".$kode_lokasi."' ";
                array_push($exec,$del2); 
            }else{
                $no_bukti = generateKode("trans_m", "no_bukti", $kode_lokasi."-JU".substr($periode,2,4).".", "0001");
            }
            $cek = doCekPeriode2($data['jenis'],$_SESSION['userStatus'],$periode);
            if($cek['status']){

                $res = isUnik($data['no_dokumen'],$no_bukti);
                if($res['status']){
    
                    $sql = "insert into trans_m (no_bukti,kode_lokasi,tgl_input,nik_user,periode,modul,form,posted,prog_seb,progress,kode_pp,tanggal,no_dokumen,keterangan,kode_curr,kurs,nilai1,nilai2,nilai3,nik1,nik2,nik3,no_ref1,no_ref2,no_ref3,param1,param2,param3) values ('".$no_bukti."','".$kode_lokasi."',getdate(),'".$nik."','".$periode."','MI','MI','F','-','-','".$kode_pp."','".$data['tanggal']."','".$data['no_dokumen']."','".$data['deskripsi']."','IDR',1,".joinNum2($data['total_debet']).",0,0,'".$nik."','-','-','-','-','-','-','-','".$data['jenis']."')";
                    array_push($exec,$sql);
                    
                    if (count($data['kode_akun']) > 0){
                        for ($i=0;$i < count($data['kode_akun']);$i++){
                            if($data['kode_akun'][$i] != ""){
                                
                                $ins = "insert into trans_j (no_bukti,kode_lokasi,tgl_input,nik_user,periode,no_dokumen,tanggal,nu,kode_akun,dc,nilai,nilai_curr,keterangan,modul,jenis,kode_curr,kurs,kode_pp,kode_drk,kode_cust,kode_vendor,no_fa,no_selesai,no_ref1,no_ref2,no_ref3) values ('".$no_bukti."','".$kode_lokasi."',getdate(),'".$nik."','".$periode."','".$data['no_dokumen']."','".$data['tanggal']."',".$i.",'".$data['kode_akun'][$i]."','".$data['dc'][$i]."',".joinNum2($data['nilai'][$i]).",".joinNum2($data['nilai'][$i]).",'".$data['keterangan'][$i]."','MI','".$data['jenis']."','IDR',1,'".$data['kode_pp'][$i]."','-','-','-','-','-','-','-','-')";
                                array_push($exec,$ins);
                            }
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
                }else{
                    $tmp = "Transaksi tidak valid. No Dokumen '".$data['no_dokumen']."' sudah terpakai di No Bukti '".$res['no_bukti']."' .";
                    $sts = false;
                }
            }else{
                $tmp = "Periode transaksi modul tidak valid (MI - LOCKED). Hubungi Administrator Sistem .";
                $sts = false;
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
            $cek = doCekPeriode2($data['jenis'],$_SESSION['userStatus'],$periode);
            if($cek['status']){

                $no_bukti= $data['no_bukti'];
                $kode_lokasi= $_SESSION['lokasi'];
                $del1 = "delete from trans_m where no_bukti = '".$no_bukti."' and kode_lokasi='".$kode_lokasi."'";
                array_push($exec,$del1);                   
                $del2 = "delete from trans_j where no_bukti = '".$no_bukti."' and kode_lokasi='".$kode_lokasi."'";
                array_push($exec,$del2); 
                
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
            }else{
                $tmp = "Periode transaksi modul tidak valid (MI - LOCKED). Hubungi Administrator Sistem .";
                $sts = false;
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