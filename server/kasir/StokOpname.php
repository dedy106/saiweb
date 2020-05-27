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
        $auth = $schema->SelectLimit("select kode_akun from masakun where kode_akun='$isi' and kode_lokasi='".$_COOKIE['lokasi']."' ", 1);
        if($auth->RecordCount() > 0){
            return false;
        }else{
            return true;
        }
    }

    function doCekPeriode2($modul,$status,$periode) {
        //dimatikan dulu protect modul nya
        // try{
            
        //     $perValid = false;
            
        //     if ($status == "A") {
    
        //         $strSQL = "select modul from periode_aktif where kode_lokasi ='".$_COOKIE['lokasi']."'  and modul ='".$modul."' and '".$periode."' between per_awal2 and per_akhir2";
        //     }else{
    
        //         $strSQL = "select modul from periode_aktif where kode_lokasi ='".$_COOKIE['lokasi']."'  and modul ='".$modul."' and '".$periode."' between per_awal1 and per_akhir1";
        //     }
        //     $schema = db_Connect();
        //     $auth = $schema->SelectLimit($strSQL, 1);
        //     if($auth->RecordCount() > 0){
        //         $perValid = true;
        //     }
        //     $msg = "ok";
        // }catch (exception $e) { 
        //     error_log($e->getMessage());		
        //     $msg= " error " .  $e->getMessage();
        //     $perValid = false;
        // } 	
        $perValid = true;
        $msg = 'ok';
        $result['status'] = $perValid;
        $result['message'] = $msg;
        return $result;		
	}

    
    function getGudang(){
        
        session_start();
        getKoneksi();
        if($_COOKIE['isLogedIn'] AND cekAuth($_COOKIE['userLog'])){
            $kode_lokasi = $_GET['kode_lokasi'];
            $kode_pp = $_GET['kode_pp'];

            $sql="select kode_gudang,nama from brg_gudang where kode_lokasi='$kode_lokasi' --and kode_pp='$kode_pp' ";
            $response['daftar']=dbResultArray($sql);
            $response['kode_gudang'] =  $response['daftar'][0]['kode_gudang']; 
            $response['status'] = TRUE;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }

    function execSP(){
        
        session_start();
        getKoneksi();
        if($_COOKIE['isLogedIn'] AND cekAuth($_COOKIE['userLog'])){
            $exec = array();
            $kode_lokasi = $_GET['kode_lokasi'];
            $nik = $_GET['nik'];
            $periode = date('Ym');
            $sql = "exec sp_brg_stok2 '".$periode."','".$kode_lokasi."','".$nik."' ";
            array_push($exec,$sql);
            $rs = executeArray($exec,$err);
            if($err == null){
                $msg = 'ok';
                $sts = true;
            }else{
                $sts = false;
                $msg = $err;
            }
            $response['message'] = $msg;
            $response['status'] = $sts;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }

    function getLoad(){
        
        session_start();
        getKoneksi();
        if($_COOKIE['isLogedIn'] AND cekAuth($_COOKIE['userLog'])){
            $kode_lokasi = $_GET['kode_lokasi'];
            $nik = $_GET['nik_user'];
            $kode_gudang = $_GET['kode_gudang'];
            $exec = array();

            $del = "delete from brg_stok_tmp where kode_lokasi='".$kode_lokasi."' and nik_user='".$nik."'  ";
            array_push($exec,$del);

            $sql=" insert into brg_stok_tmp (kode_barang,nama_barang,satuan,stok,jumlah,selisih,barcode,kode_lokasi,nik_user,nu)
            select a.kode_barang,a.nama,a.sat_kecil as satuan,isnull(b.stok,0) as stok, 0 as jumlah, 0 as selisih, a.barcode, '$kode_lokasi' as kode_lokasi, '$nik' as nik_user,row_number() over (order by (select NULL)) 
            from brg_barang a 
            inner join brg_stok b on a.kode_barang=b.kode_barang and a.kode_lokasi=b.kode_lokasi and b.kode_gudang='".$kode_gudang."' and b.nik_user='".$nik."' 
            where a.kode_lokasi='".$kode_lokasi."' order by a.kode_barang ";

            array_push($exec,$sql);

            $rs = executeArray($exec,$err);
            if($err == NULL){
                $msg = "sukses";
                $sts = true;
                $response['daftar']=dbResultArray("select 0 as no,kode_barang,nama_barang as nama,satuan,stok,jumlah,selisih,barcode from brg_stok_tmp where kode_lokasi='".$kode_lokasi."' and nik_user='$nik' ");
            }else{
                $msg = "gagal. ".$err;
                $sts = false;
            }

            $response['status'] = $sts;
            $response['message'] = $msg;
            $response['sql']=$sql;
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
        if($_COOKIE['isLogedIn'] AND cekAuth($_COOKIE['userLog'])){
            $query = '';
            $output = array();
        
            $kode_lokasi = $_GET['kode_lokasi'];
            $query .= " select a.no_bukti,convert(varchar,a.tanggal,103) as tgl,a.keterangan 
            from trans_m a inner join brg_gudang b on a.param1=b.kode_gudang and a.kode_lokasi=b.kode_lokasi 
            inner join karyawan_pp c on b.kode_pp=c.kode_pp and c.kode_lokasi=b.kode_lokasi and c.nik='".$_COOKIE['userLog']."' 
            where a.kode_lokasi='".$kode_lokasi."' and a.modul='IV' and a.form='BRGSOP'";

            $column_array = array('a.no_bukti','convert(varchar,a.tanggal,103)','a.keterangan');
            $order_column = 'ORDER BY a.no_bukti '.$_GET['order'][0]['dir'];
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
                $query .= ' ORDER BY  a.no_bukti ';
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
                $sub_array[] = $row->no_bukti;
                $sub_array[] = $row->tgl;                
                $sub_array[] = $row->keterangan;
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

    function cekKode($kode){
        getKoneksi();     
        $sql = "select a.kode_barang from brg_barang a
        where a.kode_barang ='$kode'";
        $rs = execute($sql);
        if($rs->RecordCount()>0){
            return true;
        }else{
            return false;
        }
    }

    function simpanFisikTmp(){
        session_start();
        getKoneksi();
        $data=$_POST;

        if($_COOKIE['isLogedIn'] AND cekAuth($_COOKIE['userLog'])){             
        
            include_once("excel_reader2.php");
            $exec = array();
            $kode_lokasi = $data['kode_lokasi'];
            $nik = $data['nik'];
         
            $path_s = realpath($_SERVER["DOCUMENT_ROOT"])."/";
            $target_dir = $path_s."upload/";
            $target_file = $target_dir . basename($_FILES["file_dok"]["name"]);
            $uploadOk = 1;
            $message="";
            $error_upload="";
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

            // Allow certain file formats
            if($imageFileType != "xlsx" && $imageFileType != "xls") {
                $error_upload= "Sorry, only Excel files are allowed.";
                $uploadOk = 0;
            }

            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
                $error_upload= "Sorry, your file was not uploaded.";
                // if everything is ok, try to upload file
            } else {
                if (move_uploaded_file($_FILES["file_dok"]["tmp_name"], $target_file)) {
                    $message = "The file ". basename( $_FILES["file_dok"]["name"]). " has been uploaded.";
                } else {
                    $error_upload= "Sorry, there was an error uploading your file.";
                    // echo $target_file;
                    // echo $_FILES["file_dok"]["error"];
                    if (is_dir($target_dir) && is_writable($target_dir)) {
                        // do upload logic here
                    } else if (!is_dir($target_dir)){
                        $error_upload.= 'Upload directory does not exist.'.$target_dir;
                    } else if (!is_writable($target_dir)){
                        $error_upload.= 'Upload directory is not writable'.$target_dir;
                    }
                    
                }
            }

                $filepath=$target_file;
                $filetype=$imageFileType;
            // }else{
            //     $filepath="-";
            //     $filetype="-";
            // }

            $err_ins = array();
            if($uploadOk){
                chmod($_FILES['file_dok']['name'],0777);
                // mengambil isi file xls
                $data = new Spreadsheet_Excel_Reader($target_dir.$_FILES['file_dok']['name'],false);
                // menghitung jumlah baris data yang ada
                $jumlah_baris = $data->rowcount($sheet_index=0);
                
                // jumlah default data yang berhasil di import
                $berhasil = 0;
                $e = 0;
                $response["jumlah"] = $jumlah_baris;

                $del = "delete from brg_fisik_tmp where kode_lokasi='$kode_lokasi' ";
                
                array_push($exec,$del);
                
                for ($i=2; $i<=$jumlah_baris; $i++){
                
                    // menangkap data dan memasukkan ke variabel sesuai dengan kolumnya masing-masing
                    $kode_barang     = $data->val($i, 1);
                    $jumlah   = floatval($data->val($i, 2));
                    
                    if($kode_barang == ""){
                        $err_ins[] = array("no"=>0,"kode_barang" =>"Invalid kode","err_msg"=>"Error Kode Kosong");
                        
                    }else{
                        if(cekKode($kode_barang)){
                            $sql[$i] = "INSERT into  brg_fisik_tmp  (kode_lokasi,nu,kode_barang,jumlah,nik_user) values ('$kode_lokasi',$i,'$kode_barang',$jumlah,'$nik')";
                            // $rs[$i]=execute($sql[$i]);
        
                            array_push($exec,$sql[$i]);
                        }else{
                            $err_ins[] = array("no"=>0,"kode_barang" => $kode_barang,"err_msg"=>"kode tidak terdaftar");
                            
                        }
                    }

                }
                
                // hapus kembali file .xls yang di upload tadi
                unlink($target_dir.$_FILES['file_dok']['name']);
                $insert = true;
            }else{
                $insert = false;
            }

            $response["data"] = array();
            $response["error_list"] = array();
            if(count($err_ins)>0){
                $tmp = "error data tidak valid";
                $response["error_list"] = $err_ins;
                $sts = false;
            }else{
                $insert = executeArray($exec,$err);
                $tmp=array();
                $kode = array();
                if ($err == null)
                {	
                    $tmp="sukses";
                    $sts=true;
                    $response["data"] = dbResultArray("select nu as no,kode_barang,jumlah from brg_fisik_tmp where kode_lokasi='$kode_lokasi' and nik_user='$nik' order by nu ");
                }else{
                    $tmp="gagal".$err;
                    $sts=false;
                }	
            }
            
            $response["message"] =$tmp;
            $response["status"] = $sts;
            $response["exec"]= $exec;
        } else{
            $response["message"] = "Unauthorized Access, Login Required";
            $response["status"]=true; 
        }     
        echo json_encode($response);
        
    }

    function simpanRekon(){
        session_start();
        getKoneksi();
        $data=$_POST;

        if($_COOKIE['isLogedIn'] AND cekAuth($_COOKIE['userLog'])){             
        
            $exec = array();
            $kode_lokasi = $data['kode_lokasi'];
            $nik = $data['nik'];
            if(count($data['kode_barang']) > 0){

                for($i=0;$i < count($data['kode_barang']);$i++){
                    $upd = "update brg_stok_tmp set jumlah=".intval($data['jumlah'][$i]).", selisih=stok-".intval($data['jumlah'][$i])." where kode_barang='".$data['kode_barang'][$i]."' and kode_lokasi='$kode_lokasi' and nik_user='$nik' ";
                    array_push($exec,$upd);
                }
                // $del = "delete from brg_fisik_tmp where kode_lokasi='$kode_lokasi' and nik_user='$nik' ";
                // array_push($exec,$del);
            }
            $rs = executeArray($exec,$err);
            if($err ==null){
                $msg = "sukses";
                $sts = true;
                $response["daftar"] = dbResultArray("select 0 as no,kode_barang,nama_barang as nama,satuan,stok,jumlah,selisih,barcode from brg_stok_tmp where kode_lokasi='".$kode_lokasi."' and nik_user='$nik' ");
            }else{
                $msg = "gagal. ".$err;
                $sts = false;
                $response["daftar"] = array();
            }
            $response["message"] = $msg;
            $response["status"] =$sts; 
            $response["exec"] =$exec; 
        }else{
            $response["message"] = "Unauthorized Access, Login Required";
            $response["status"]=true; 
        }
        
        echo json_encode($response);
    }

    

    function getEdit(){
        session_start();
        getKoneksi();
        if($_COOKIE['isLogedIn'] AND cekAuth($_COOKIE['userLog'])){
            $data= $_GET;  
        
            $response = array("message" => "", "rows" => 0, "status" => "" );
            $kode_lokasi = $_GET['kode_lokasi'];
            $nik = $_GET['nik'];
            $periode = date('Ym');
            $no_bukti= $data['no_bukti'];
            
            $exec = array();
            $sql = "exec sp_brg_stok2 '".$periode."','".$kode_lokasi."','".$nik."' ";
            array_push($exec,$sql);
            $rs = executeArray($exec,$err);
            if($err == null){
                $sql = " select no_bukti,tanggal,keterangan,param1,periode from trans_m where no_bukti='$no_bukti' and kode_lokasi='$kode_lokasi' ";
                $response['daftar']=dbResultArray($sql);
    
                $sql2 = "select 0 as no,a.kode_barang,b.barcode, b.nama,a.satuan,c.stok, case dc when 'D' then a.stok+a.jumlah else a.stok-a.jumlah end as jumlah, case dc when 'D' then -a.jumlah else a.jumlah end as selisih 
                from brg_trans_d a inner join brg_barang b on a.kode_barang=b.kode_barang and a.kode_lokasi=b.kode_lokasi 
                inner join brg_stok c on a.kode_barang=c.kode_barang and a.kode_gudang=c.kode_gudang and a.kode_lokasi=c.kode_lokasi and c.nik_user='".$nik."' 
                where a.no_bukti='".$no_bukti."' and a.kode_lokasi='".$kode_lokasi."' order by a.kode_barang";						
                $response['daftar2']=dbResultArray($sql2);
                $sts = true;
            }else{
                $sts = false;
                $msg = $err;
            }
            $response['message'] = $msg;
            $response['status'] = $sts;
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
           
            $data=$_POST;
            $kode_lokasi=$data['kode_lokasi'];
            $nik=$data['nik'];
            $kode_pp=$data['kode_pp'];
            $exec = array();
            $periode = substr($data['tanggal'],0,4).substr($data['tanggal'],5,2);
            if($data['id'] == 'edit'){
                $no_bukti = $data['no_bukti'];
                $del ="delete from trans_m where no_bukti = '".$no_bukti."' and kode_lokasi='".$kode_lokasi."'";
                array_push($exec,$del);
                $del2 ="delete from brg_trans_d where no_bukti = '".$no_bukti."' and kode_lokasi='".$kode_lokasi."'";
                array_push($exec,$del2);									
                $sts = true;
            }else{
                $no_bukti = generateKode("trans_m", "no_bukti", "OP/".substr($periode,2,4)."/", "0001");
                $cek = doCekPeriode2("IV",$_COOKIE['userStatus'],$periode);
                if($cek['status']){
                    $sts = true;
                }else{
                    $sts = false;
                }
                
            }
            
            if($sts){

                $ins = "insert into trans_m (no_bukti,kode_lokasi,tgl_input,nik_user,periode,modul,form,posted,prog_seb,progress,kode_pp,tanggal,no_dokumen,keterangan,kode_curr,kurs,nilai1,nilai2,nilai3,nik1,nik2,nik3,no_ref1,no_ref2,no_ref3,param1,param2,param3) values ('".$no_bukti."','".$kode_lokasi."',getdate(),'".$nik."','".$periode."','IV','BRGSOP','X','0','0','".$kode_pp."','".$data['tanggal']."','-','".$data['deskripsi']."','IDR',1,0,0,0,'-','-','-','-','-','-','".$data['kode_gudang']."','-','-')";
                array_push($exec,$ins);

                $det = "insert into brg_trans_d (no_bukti,kode_lokasi,periode,modul,form,nu,kode_gudang,kode_barang,no_batch,tgl_ed,satuan,dc,stok,jumlah,bonus,harga,hpp,p_disk,diskon,tot_diskon,total) 

                select '".$no_bukti."','".$kode_lokasi."','".$periode."','BRGSOP','BRGSOP',nu,'".$data['kode_gudang']."',kode_barang,'-',getdate(),satuan,case when selisih > 0 then 'C' else 'D' end as dc,stok,selisih,0,0,0,0,0,0,0 
                from brg_stok_tmp 
                where kode_lokasi ='$kode_lokasi' and nik_user='$nik' order by nu";

                array_push($exec,$det);

                $del3 = "delete from brg_stok_tmp where kode_lokasi ='$kode_lokasi' and nik_user='$nik' ";
                array_push($exec,$del3);

                $del4 = "delete from brg_fisik_tmp where kode_lokasi ='$kode_lokasi' and nik_user='$nik' ";
                array_push($exec,$del4);

               
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
                $sts = false;
                $tmp = "Periode transaksi modul tidak valid (IV - LOCKED). Hubungi Administrator Sistem";
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
        if($_COOKIE['isLogedIn'] AND cekAuth($_COOKIE['userLog'])){
            
            $exec = array();
            parse_str(file_get_contents('php://input'), $_DELETE);
            $data = $_DELETE;
            
            $no_bukti= $data['no_bukti'];
            $kode_lokasi= $_COOKIE['lokasi'];
            $periode = $_COOKIE['periode'];
           
            $cek = doCekPeriode2("IV",$_COOKIE['userStatus'],$periode);
            if($cek['status']){

                $del ="delete from trans_m where no_bukti = '".$no_bukti."' and kode_lokasi='".$kode_lokasi."'";
                array_push($exec,$del);

                $del2 ="delete from brg_trans_d where no_bukti = '".$no_bukti."' and kode_lokasi='".$kode_lokasi."'";
                array_push($exec,$del2);

                $rs=executeArray($exec,$err);
                if ($err == null)
                {	
                    $tmp="sukses";
                    $sts=true;
                }else{
                    $tmp="gagal";
                    $sts=false;
                }		
            }else{
                $sts = false;
                $tmp = "Periode transaksi modul tidak valid (IV - LOCKED). Hubungi Administrator Sistem";
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