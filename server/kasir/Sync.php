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

     
    function generateKode($tabel, $kolom_acuan, $prefix, $str_format){
        $query = execute("select right(max($kolom_acuan), ".strlen($str_format).")+1 as id from $tabel where $kolom_acuan like '$prefix%'");
        $kode = $query->fields[0];
        $id = $prefix.str_pad($kode, strlen($str_format), $str_format, STR_PAD_LEFT);
        return $id;
    }

    function getHistory(){
        session_start();
        getKoneksi();
        if($_COOKIE['isLogedIn'] AND cekAuth($_COOKIE['userLog'])){
            $query = '';
            $output = array();
        
            $kode_lokasi = $_REQUEST['kode_lokasi'];
            $query .= "select id,tgl_sync,total_rows,jenis_master,nik_user from sync_master where kode_lokasi='".$kode_lokasi."'  ";

            $column_array = array('id','tgl_sync','total_rows','jenis_master','nik_user');
            $order_column = 'ORDER BY id '.$_POST['order'][0]['dir'];
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
                $query .= ' ORDER BY id desc';
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
                $sub_array[] = $row->id;
                $sub_array[] = $row->tgl_sync;
                $sub_array[] = $row->jenis_master;
                $sub_array[] = $row->total_rows;
                $sub_array[] = $row->nik_user;
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
    

    function syncMaster(){
        session_start();
        getKoneksi();
        if($_COOKIE['isLogedIn'] AND cekAuth($_COOKIE['userLog'])){
            try{

                $fields = array(
                    "nik" => "kasir",
                    "pass" => "saisai"
                );

                $root="http://".$_SERVER['SERVER_NAME']."/api/ginas";
                $root="http://saiweb.simkug.com/api/ginas";
                //getToken
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "$root/SyncMaster.php?fx=generateToken");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($ch, CURLOPT_HEADER, FALSE);
                curl_setopt($ch, CURLOPT_POST, FALSE);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
                
                $response["sync"]= json_decode(curl_exec($ch));
                curl_close($ch);

                $token = $response["sync"]->token;
                $kode_lokasi = $_COOKIE['lokasi'];
                $nik = $_COOKIE['userLog'];
                // $res["CUST"] = dbResultArray("select * from cust where kode_lokasi='$kode_lokasi' ");
                $res["BARANG"] = dbResultArray("select * from brg_barang where kode_lokasi='$kode_lokasi' ");
                // $res["BARANG"] = array();
                $res["GUDANG"] = dbResultArray("select * from brg_gudang where kode_lokasi='$kode_lokasi' ");
                $res["BARANGKLP"] = dbResultArray("select * from brg_barangklp where kode_lokasi='$kode_lokasi' ");
                $res["VENDOR"] = dbResultArray("select * from vendor where kode_lokasi='$kode_lokasi' ");
                $res["SATUAN"] = dbResultArray("select * from brg_satuan where kode_lokasi='$kode_lokasi' ");
                $res["BONUS"] = dbResultArray("select * from brg_bonus where kode_lokasi='$kode_lokasi' ");

                // $tCust = ((count($res["CUST"]) > 0 ) ? count($res["CUST"]) : 0 );
                $tBrg = ((count($res["BARANG"]) > 0 ) ? count($res["BARANG"]) : 0 );
                $tGudang = ((count($res["GUDANG"]) > 0 ) ? count($res["GUDANG"]) : 0 );
                $tKlp = ((count($res["BARANGKLP"]) > 0 ) ? count($res["BARANGKLP"]) : 0 );
                $tVendor = ((count($res["VENDOR"]) > 0 ) ? count($res["VENDOR"]) : 0 );
                $tSat = ((count($res["SATUAN"]) > 0 ) ? count($res["SATUAN"]) : 0 );
                $tBonus = ((count($res["BONUS"]) > 0 ) ? count($res["BONUS"]) : 0 );

                $exec = array();
                $fields2 = json_encode($res);
                //syncData
                $ch2 = curl_init();
                curl_setopt($ch2, CURLOPT_URL, "$root/SyncMaster.php?fx=syncMaster");
                curl_setopt($ch2, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json; charset=utf-8',
                    'Authorization: Bearer '.$token //REST API KEY GINAS
                ));
                curl_setopt($ch2, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($ch2, CURLOPT_HEADER, FALSE);
                curl_setopt($ch2, CURLOPT_POST, TRUE);
                curl_setopt($ch2, CURLOPT_POSTFIELDS, $fields2);
                curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt($ch2, CURLOPT_SSL_VERIFYHOST, FALSE);
                $response["sync2"]= json_decode(curl_exec($ch2));
                curl_close($ch2);

                
                // $ins = "insert into sync_master (kode_lokasi,jenis_master,tgl_sync,nik_user,total_rows) 
                // values ('$kode_lokasi','CUST',getdate(),'$nik',$tCust) ";
                // array_push($exec,$ins);
                $ins2 = "insert into sync_master (kode_lokasi,jenis_master,tgl_sync,nik_user,total_rows) 
                values ('$kode_lokasi','BARANG',getdate(),'$nik',$tBrg) ";
                array_push($exec,$ins2);
                $ins3 = "insert into sync_master (kode_lokasi,jenis_master,tgl_sync,nik_user,total_rows) 
                values ('$kode_lokasi','GUDANG',getdate(),'$nik',$tGudang) ";
                array_push($exec,$ins3);
                $ins4 = "insert into sync_master (kode_lokasi,jenis_master,tgl_sync,nik_user,total_rows) 
                values ('$kode_lokasi','BARANGKLP',getdate(),'$nik',$tKlp) ";
                array_push($exec,$ins4);
                $ins5 = "insert into sync_master (kode_lokasi,jenis_master,tgl_sync,nik_user,total_rows) 
                values ('$kode_lokasi','VENDOR',getdate(),'$nik',$tVendor) ";
                array_push($exec,$ins5);
                $ins6 = "insert into sync_master (kode_lokasi,jenis_master,tgl_sync,nik_user,total_rows) 
                values ('$kode_lokasi','SATUAN',getdate(),'$nik',$tSat) ";
                array_push($exec,$ins6);
                $ins7 = "insert into sync_master (kode_lokasi,jenis_master,tgl_sync,nik_user,total_rows) 
                values ('$kode_lokasi','BONUS',getdate(),'$nik',$tBonus) ";
                array_push($exec,$ins7);
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
               
            } catch (exception $e) { 
                error_log($e->getMessage());		
                $error ="error " .  $e->getMessage();
                
                $sts = false;
                $tmp = $error;
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

    function getHistoryPnj(){
        session_start();
        getKoneksi();
        if($_COOKIE['isLogedIn'] AND cekAuth($_COOKIE['userLog'])){
            $query = '';
            $output = array();
        
            $kode_lokasi = $_REQUEST['kode_lokasi'];
            $query .= "select id,tgl_sync,keterangan,total_rows,nik_user from sync_pnj where kode_lokasi='".$kode_lokasi."'  ";

            $column_array = array('id','tgl_sync','keterangan','total_rows','nik_user');
            $order_column = 'ORDER BY id '.$_POST['order'][0]['dir'];
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
                $query .= ' ORDER BY id desc';
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
                $sub_array[] = $row->id;
                $sub_array[] = $row->tgl_sync;
                $sub_array[] = $row->keterangan;
                $sub_array[] = $row->total_rows;
                $sub_array[] = $row->nik_user;
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


    function syncPnj(){
        session_start();
        getKoneksi();
        if($_COOKIE['isLogedIn'] AND cekAuth($_COOKIE['userLog'])){
            try{

                $fields = array(
                    "nik" => "kasir",
                    "pass" => "saisai"
                );

                $root="http://".$_SERVER['SERVER_NAME']."/api/ginas";
                $root="http://saiweb.simkug.com/api/ginas";

                //getToken
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "$root/SyncMaster.php?fx=generateToken");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($ch, CURLOPT_HEADER, FALSE);
                curl_setopt($ch, CURLOPT_POST, FALSE);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
                
                $response["sync"]= json_decode(curl_exec($ch));
                curl_close($ch);

                $token = $response["sync"]->token;
                $kode_lokasi = $_COOKIE['lokasi'];
                $nik = $_COOKIE['userLog'];
                $res["TRANSM"] = dbResultArray("select * from trans_m where kode_lokasi='$kode_lokasi' and isnull(id_sync,'-')='-' and form='CLOSING' ");
                $res["TRANSJ"] = dbResultArray("select * from trans_j where kode_lokasi='$kode_lokasi' and isnull(id_sync,'-')='-' and modul='BRGJUAL' ");
                // $res["BARANG"] = array();
                $res["BRGJUAL"] = dbResultArray("select * from brg_jualpiu_dloc where kode_lokasi='$kode_lokasi' and isnull(id_sync,'-')='-' and isnull(no_close,'-') <> '-' ");
                $res["BRGTRANS"] = dbResultArray("select * from brg_trans_dloc where kode_lokasi='$kode_lokasi' and isnull(id_sync,'-')='-' and isnull(no_close,'-') <> '-' ");

                $tM = ((count($res["TRANSM"]) > 0 ) ? count($res["TRANSM"]) : 0 );
                $tJ = ((count($res["TRANSJ"]) > 0 ) ? count($res["TRANSJ"]) : 0 );
                $tBRGJ = ((count($res["BRGJUAL"]) > 0 ) ? count($res["BRGJUAL"]) : 0 );
                $tBRGT = ((count($res["BRGTRANS"]) > 0 ) ? count($res["BRGTRANS"]) : 0 );

                $total = $tM+$tJ+$tBRGJ+$tBRGT;
                $response['t']=$total;

                $exec = array();
                $fields2 = json_encode($res);
                //syncData
                $ch2 = curl_init();
                curl_setopt($ch2, CURLOPT_URL, "$root/SyncMaster.php?fx=syncPnj");
                curl_setopt($ch2, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json; charset=utf-8',
                    'Authorization: Bearer '.$token //REST API KEY GINAS
                ));
                curl_setopt($ch2, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($ch2, CURLOPT_HEADER, FALSE);
                curl_setopt($ch2, CURLOPT_POST, TRUE);
                curl_setopt($ch2, CURLOPT_POSTFIELDS, $fields2);
                curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt($ch2, CURLOPT_SSL_VERIFYHOST, FALSE);
                $response["sync2"]= json_decode(curl_exec($ch2));
                if (curl_errno($ch2)) { 
                    $response['error_sync2'] = curl_error($ch2); 
                } 
                curl_close($ch2);

                if($response["sync2"]->status){

                    $id =  generateKode("sync_pnj", "id", $kode_lokasi.'SC'.date('Y'), "00001");
                    
                    $ins = "insert into sync_pnj (id,kode_lokasi,keterangan,tgl_sync,nik_user,total_rows) 
                    values ('$id','$kode_lokasi','DATA PENJUALAN DAN JURNAL',getdate(),'$nik',$total) ";
                    array_push($exec,$ins);
    
                    $ins2 = "insert into sync_pnj_d (kode_lokasi,keterangan,total_rows,id) 
                    values ('$kode_lokasi','TRANS M',$tM,'$id') ";
                    array_push($exec,$ins2);
                    
                    $ins3 = "insert into sync_pnj_d (kode_lokasi,keterangan,total_rows,id) 
                    values ('$kode_lokasi','TRANS J',$tJ,'$id') ";
                    array_push($exec,$ins3);
    
                    
                    $ins4 = "insert into sync_pnj_d (kode_lokasi,keterangan,total_rows,id) 
                    values ('$kode_lokasi','BRG JUALPIU',$tBRGJ,'$id') ";
                    array_push($exec,$ins4);
                    
                    
                    $ins5 = "insert into sync_pnj_d (kode_lokasi,keterangan,total_rows,id) 
                    values ('$kode_lokasi','BRG TRANSD',$tBRGT,'$id') ";
                    array_push($exec,$ins5);
    
                    $upd1 = "update trans_m set id_sync='$id' where isnull(id_sync,'-') = '-'  and kode_lokasi='$kode_lokasi'  and form='CLOSING'  "; 
                    array_push($exec,$upd1);
                    $upd2 = "update trans_j set id_sync='$id' where isnull(id_sync,'-') = '-'  and kode_lokasi='$kode_lokasi'  and modul='BRGJUAL'  "; 
                    array_push($exec,$upd2);
                    $upd3 = "update brg_jualpiu_dloc set id_sync='$id' where isnull(id_sync,'-') = '-' and isnull(no_close,'-') <> '-'  and kode_lokasi='$kode_lokasi' ";
                    array_push($exec,$upd3);
                    $upd4 = "update brg_trans_dloc set id_sync='$id' where isnull(id_sync,'-') = '-' and isnull(no_close,'-') <> '-'  and kode_lokasi='$kode_lokasi' ";
                    array_push($exec,$upd4);
    
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
                    $tmp=$response["sync2"]->message;
                    $sts=false;
                
                }

               
            } catch (exception $e) { 
                error_log($e->getMessage());		
                $error ="error " .  $e->getMessage();
                
                $sts = false;
                $tmp = $error;
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
	
		function syncPnjPerNo(){
        session_start();
        getKoneksi();
        if($_COOKIE['isLogedIn'] AND cekAuth($_COOKIE['userLog'])){
            try{

                $fields = array(
                    "nik" => "kasir",
                    "pass" => "saisai"
                );

                $root="http://".$_SERVER['SERVER_NAME']."/api/ginas";
                $root="http://saiweb.simkug.com/api/ginas";

                //getToken
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "$root/SyncMaster.php?fx=generateToken");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($ch, CURLOPT_HEADER, FALSE);
                curl_setopt($ch, CURLOPT_POST, FALSE);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
                
                $response["sync"]= json_decode(curl_exec($ch));
                curl_close($ch);

                $token = $response["sync"]->token;
                $kode_lokasi = $_COOKIE['lokasi'];
                $nik = $_COOKIE['userLog'];
				$no_close = $_POST['no_close'];
                $res["TRANSM"] = dbResultArray("select * from trans_m where kode_lokasi='$kode_lokasi' and isnull(id_sync,'-')='-' and form='CLOSING' and no_bukti='$no_close' ");
                $res["TRANSJ"] = dbResultArray("select * from trans_j where kode_lokasi='$kode_lokasi' and isnull(id_sync,'-')='-' and modul='BRGJUAL' and no_bukti='$no_close' ");
                // $res["BARANG"] = array();
                $res["BRGJUAL"] = dbResultArray("select * from brg_jualpiu_dloc where kode_lokasi='$kode_lokasi' and isnull(id_sync,'-')='-' and isnull(no_close,'-') <> '-' and no_close='$no_close' ");
                $res["BRGTRANS"] = dbResultArray("select * from brg_trans_dloc where kode_lokasi='$kode_lokasi' and isnull(id_sync,'-')='-' and isnull(no_close,'-') <> '-' and no_close='$no_close' ");

                $tM = ((count($res["TRANSM"]) > 0 ) ? count($res["TRANSM"]) : 0 );
                $tJ = ((count($res["TRANSJ"]) > 0 ) ? count($res["TRANSJ"]) : 0 );
                $tBRGJ = ((count($res["BRGJUAL"]) > 0 ) ? count($res["BRGJUAL"]) : 0 );
                $tBRGT = ((count($res["BRGTRANS"]) > 0 ) ? count($res["BRGTRANS"]) : 0 );

                $total = $tM+$tJ+$tBRGJ+$tBRGT;
                $response['t']=$total;

                $exec = array();
                $fields2 = json_encode($res);
                //syncData
                $ch2 = curl_init();
                curl_setopt($ch2, CURLOPT_URL, "$root/SyncMaster.php?fx=syncPnj");
                curl_setopt($ch2, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json; charset=utf-8',
                    'Authorization: Bearer '.$token //REST API KEY GINAS
                ));
                curl_setopt($ch2, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($ch2, CURLOPT_HEADER, FALSE);
                curl_setopt($ch2, CURLOPT_POST, TRUE);
                curl_setopt($ch2, CURLOPT_POSTFIELDS, $fields2);
                curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt($ch2, CURLOPT_SSL_VERIFYHOST, FALSE);
                // curl_setopt($ch2, CURLOPT_TIMEOUT, 2000);
                $response["sync2"]= json_decode(curl_exec($ch2));
                if (curl_errno($ch2)) { 
                    $response['error_sync2'] = curl_error($ch2); 
                } 
                curl_close($ch2);

                if($response["sync2"]->status){

                    $id =  generateKode("sync_pnj", "id", $kode_lokasi.'SC'.date('ym'), "00001");
                    
                    $ins = "insert into sync_pnj (id,kode_lokasi,keterangan,tgl_sync,nik_user,total_rows) 
                    values ('$id','$kode_lokasi','DATA PENJUALAN DAN JURNAL $no_close',getdate(),'$nik',$total) ";
                    array_push($exec,$ins);
    
                    $ins2 = "insert into sync_pnj_d (kode_lokasi,keterangan,total_rows,id) 
                    values ('$kode_lokasi','TRANS M $no_close',$tM,'$id') ";
                    array_push($exec,$ins2);
                    
                    $ins3 = "insert into sync_pnj_d (kode_lokasi,keterangan,total_rows,id) 
                    values ('$kode_lokasi','TRANS J $no_close',$tJ,'$id') ";
                    array_push($exec,$ins3);
    
                    
                    $ins4 = "insert into sync_pnj_d (kode_lokasi,keterangan,total_rows,id) 
                    values ('$kode_lokasi','BRG JUALPIU $no_close',$tBRGJ,'$id') ";
                    array_push($exec,$ins4);
                    
                    
                    $ins5 = "insert into sync_pnj_d (kode_lokasi,keterangan,total_rows,id) 
                    values ('$kode_lokasi','BRG TRANSD $no_close',$tBRGT,'$id') ";
                    array_push($exec,$ins5);
    
                    $upd1 = "update trans_m set id_sync='$id' where isnull(id_sync,'-') = '-'  and kode_lokasi='$kode_lokasi'  and form='CLOSING' and no_bukti='$no_close' "; 
                    array_push($exec,$upd1);
                    $upd2 = "update trans_j set id_sync='$id' where isnull(id_sync,'-') = '-'  and kode_lokasi='$kode_lokasi'  and modul='BRGJUAL' and no_bukti='$no_close' "; 
                    array_push($exec,$upd2);
                    $upd3 = "update brg_jualpiu_dloc set id_sync='$id' where isnull(id_sync,'-') = '-' and isnull(no_close,'-') <> '-'  and kode_lokasi='$kode_lokasi' and no_close='$no_close' ";
                    array_push($exec,$upd3);
                    $upd4 = "update brg_trans_dloc set id_sync='$id' where isnull(id_sync,'-') = '-' and isnull(no_close,'-') <> '-'  and kode_lokasi='$kode_lokasi' and no_close='$no_close' ";
                    array_push($exec,$upd4);
    
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
                    $tmp=$response["sync2"]->message;
                    $sts=false;
                
                }

               
            } catch (exception $e) { 
                error_log($e->getMessage());		
                $error ="error " .  $e->getMessage();
                
                $sts = false;
                $tmp = $error;
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


	function getClosing(){
        session_start();
        getKoneksi();
        if($_COOKIE['isLogedIn'] AND cekAuth($_COOKIE['userLog'])){
            $query = '';
            $output = array();
        
            $kode_lokasi = $_REQUEST['kode_lokasi'];
            $query .= "select no_bukti,tgl_input,nik_user,periode,nilai1,isnull(id_sync,'-') as id_sync from trans_m where form='CLOSING' and kode_lokasi='".$kode_lokasi."' and isnull(id_sync,'-') = '-' ";

            $column_array = array('no_bukti','tgl_input','nik_user','periode','nilai1');
            $order_column = 'ORDER BY no_bukti '.$_POST['order'][0]['dir'];
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
                $query .= ' ORDER BY no_bukti desc';
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
                $sub_array[] = $row->no_bukti;
                $sub_array[] = $row->tgl_input;
                $sub_array[] = $row->nik_user;
                $sub_array[] = $row->periode;
                $sub_array[] = $row->nilai1;
				if($row->id_sync == "-"){
					$sub_array[] = "<a href='#' class='badge badge-info btn-sync'><i class='fa fa-sync'></i></a>";
				}else{
					$sub_array[] = "<a href='#' title='Sudah Sync' class='badge badge-success' ><i class='fas fa-check'></i></a>";
				}
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

    function getPmb(){
        session_start();
        getKoneksi();
        if($_COOKIE['isLogedIn'] AND cekAuth($_COOKIE['userLog'])){
            $query = '';
            $output = array();

            $nik = $_REQUEST['nik'];
            $kode_lokasi = $_REQUEST['kode_lokasi'];
            $query .= "select no_bukti,nik_user,tanggal,param2 as kode_vendor,nilai1 as total from trans_m where kode_lokasi='".$kode_lokasi."' and form = 'BRGBELI' and isnull(id_sync,'-') = '-' ";

            $column_array = array('no_bukti','nik_user','tanggal','param2','nilai1');
            $order_column = 'ORDER BY no_bukti '.$_POST['order'][0]['dir'];
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
                $query .= ' ORDER BY no_bukti ';
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
                $sub_array[] = $row->no_bukti;
                $sub_array[] = $row->nik_user;
                $sub_array[] = $row->tanggal;
                $sub_array[] = $row->kode_vendor;
                $sub_array[] = $row->total;
                $sub_array[] = "<a href='#' class='badge badge-info btn-sync'><i class='fa fa-sync'></i></a>";
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


    function syncPmb(){
        session_start();
        getKoneksi();
        if($_COOKIE['isLogedIn'] AND cekAuth($_COOKIE['userLog'])){
            try{

                $fields = array(
                    "nik" => "kasir",
                    "pass" => "saisai"
                );

                $root="http://".$_SERVER['SERVER_NAME']."/api/ginas";
                $root="http://saiweb.simkug.com/api/ginas";

                //getToken
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "$root/SyncMaster.php?fx=generateToken");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($ch, CURLOPT_HEADER, FALSE);
                curl_setopt($ch, CURLOPT_POST, FALSE);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
                
                $response["sync"]= json_decode(curl_exec($ch));
                curl_close($ch);

                $token = $response["sync"]->token;
                $kode_lokasi = $_COOKIE['lokasi'];
                $nik = $_COOKIE['userLog'];
                $res["TRANSM"] = dbResultArray("select * from trans_m where kode_lokasi='$kode_lokasi' and isnull(id_sync,'-')='-' and form='BRGBELI' ");
                $res["TRANSJ"] = dbResultArray("select * from trans_j where kode_lokasi='$kode_lokasi' and isnull(id_sync,'-')='-' and modul='BRGBELI'  and jenis not in ('BRGRETBELI')  ");
                // $res["BARANG"] = array();
                $res["BRGHUT"] = dbResultArray("select * from brg_belihut_d where kode_lokasi='$kode_lokasi' and isnull(id_sync,'-')='-'  and modul='BELI' ");
                $res["BRGTRANS"] = dbResultArray("select * from brg_trans_d where kode_lokasi='$kode_lokasi' and isnull(id_sync,'-')='-'  and modul='BRGBELI' ");

                $tM = ((count($res["TRANSM"]) > 0 ) ? count($res["TRANSM"]) : 0 );
                $tJ = ((count($res["TRANSJ"]) > 0 ) ? count($res["TRANSJ"]) : 0 );
                $tBRGJ = ((count($res["BRGHUT"]) > 0 ) ? count($res["BRGHUT"]) : 0 );
                $tBRGT = ((count($res["BRGTRANS"]) > 0 ) ? count($res["BRGTRANS"]) : 0 );

                $total = $tM+$tJ+$tBRGJ+$tBRGT;

                $exec = array();
                $fields2 = json_encode($res);
                //syncData
                $ch2 = curl_init();
                curl_setopt($ch2, CURLOPT_URL, "$root/SyncMaster.php?fx=syncPmb");
                curl_setopt($ch2, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json; charset=utf-8',
                    'Authorization: Bearer '.$token //REST API KEY GINAS
                ));
                curl_setopt($ch2, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($ch2, CURLOPT_HEADER, FALSE);
                curl_setopt($ch2, CURLOPT_POST, TRUE);
                curl_setopt($ch2, CURLOPT_POSTFIELDS, $fields2);
                curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt($ch2, CURLOPT_SSL_VERIFYHOST, FALSE);
                $response["sync2"]= json_decode(curl_exec($ch2));
                curl_close($ch2);

                $id =  generateKode("sync_pmb", "id", $kode_lokasi.'SC'.date('Y'), "00001");
                
                $ins = "insert into sync_pmb (id,kode_lokasi,keterangan,tgl_sync,nik_user,total_rows) 
                values ('$id','$kode_lokasi','DATA PENJUALAN DAN JURNAL',getdate(),'$nik',$total) ";
                array_push($exec,$ins);

                $ins2 = "insert into sync_pmb_d (kode_lokasi,keterangan,total_rows,id) 
                values ('$kode_lokasi','TRANS M',$tM,'$id') ";
                array_push($exec,$ins2);
                
                $ins3 = "insert into sync_pmb_d (kode_lokasi,keterangan,total_rows,id) 
                values ('$kode_lokasi','TRANS J',$tJ,'$id') ";
                array_push($exec,$ins3);

                
                $ins4 = "insert into sync_pmb_d (kode_lokasi,keterangan,total_rows,id) 
                values ('$kode_lokasi','BRG HUT',$tBRGJ,'$id') ";
                array_push($exec,$ins4);
                
                
                $ins5 = "insert into sync_pmb_d (kode_lokasi,keterangan,total_rows,id) 
                values ('$kode_lokasi','BRG TRANSD',$tBRGT,'$id') ";
                array_push($exec,$ins5);

                $upd1 = "update trans_m set id_sync='$id' where isnull(id_sync,'-') = '-'  and kode_lokasi='$kode_lokasi' and form='BRGBELI'  "; 
                array_push($exec,$upd1);
                $upd2 = "update trans_j set id_sync='$id' where isnull(id_sync,'-') = '-'  and kode_lokasi='$kode_lokasi' and modul='BRGBELI' and jenis not in ('BRGRETBELI') "; 
                array_push($exec,$upd2);
                $upd3 = "update brg_belihut_d set id_sync='$id' where isnull(id_sync,'-') = '-' and kode_lokasi='$kode_lokasi' and modul='BELI' ";
                array_push($exec,$upd3);
                $upd4 = "update brg_trans_d set id_sync='$id' where isnull(id_sync,'-') = '-' and kode_lokasi='$kode_lokasi' and modul='BRGBELI' ";
                array_push($exec,$upd4);
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
               
            } catch (exception $e) { 
                error_log($e->getMessage());		
                $error ="error " .  $e->getMessage();
                
                $sts = false;
                $tmp = $error;
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

    function syncPmbPerNo(){
        session_start();
        getKoneksi();
        if($_COOKIE['isLogedIn'] AND cekAuth($_COOKIE['userLog'])){
            try{

                $fields = array(
                    "nik" => "kasir",
                    "pass" => "saisai"
                );

                $root="http://".$_SERVER['SERVER_NAME']."/api/ginas";
                $root="http://saiweb.simkug.com/api/ginas";

                //getToken
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "$root/SyncMaster.php?fx=generateToken");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($ch, CURLOPT_HEADER, FALSE);
                curl_setopt($ch, CURLOPT_POST, FALSE);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
                
                $response["sync"]= json_decode(curl_exec($ch));
                curl_close($ch);

                $token = $response["sync"]->token;
                $kode_lokasi = $_COOKIE['lokasi'];
                $nik = $_COOKIE['userLog'];
                $no_beli = $_POST['no_bukti'];
                $res["TRANSM"] = dbResultArray("select * from trans_m where kode_lokasi='$kode_lokasi' and isnull(id_sync,'-')='-' and form='BRGBELI' and no_bukti='$no_beli' ");
                $res["TRANSJ"] = dbResultArray("select * from trans_j where kode_lokasi='$kode_lokasi' and isnull(id_sync,'-')='-' and modul='BRGBELI'  and jenis not in ('BRGRETBELI') and no_bukti='$no_beli' ");
                // $res["BARANG"] = array();
                $res["BRGHUT"] = dbResultArray("select * from brg_belihut_d where kode_lokasi='$kode_lokasi' and isnull(id_sync,'-')='-'  and modul='BELI' and no_beli='$no_beli'");
                $res["BRGTRANS"] = dbResultArray("select * from brg_trans_d where kode_lokasi='$kode_lokasi' and isnull(id_sync,'-')='-'  and modul='BRGBELI' and no_bukti='$no_beli'");

                $tM = ((count($res["TRANSM"]) > 0 ) ? count($res["TRANSM"]) : 0 );
                $tJ = ((count($res["TRANSJ"]) > 0 ) ? count($res["TRANSJ"]) : 0 );
                $tBRGJ = ((count($res["BRGHUT"]) > 0 ) ? count($res["BRGHUT"]) : 0 );
                $tBRGT = ((count($res["BRGTRANS"]) > 0 ) ? count($res["BRGTRANS"]) : 0 );

                $total = $tM+$tJ+$tBRGJ+$tBRGT;

                $exec = array();
                $fields2 = json_encode($res);
                //syncData
                $ch2 = curl_init();
                curl_setopt($ch2, CURLOPT_URL, "$root/SyncMaster.php?fx=syncPmb");
                curl_setopt($ch2, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json; charset=utf-8',
                    'Authorization: Bearer '.$token //REST API KEY GINAS
                ));
                curl_setopt($ch2, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($ch2, CURLOPT_HEADER, FALSE);
                curl_setopt($ch2, CURLOPT_POST, TRUE);
                curl_setopt($ch2, CURLOPT_POSTFIELDS, $fields2);
                curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt($ch2, CURLOPT_SSL_VERIFYHOST, FALSE);
                $response["sync2"]= json_decode(curl_exec($ch2));
                curl_close($ch2);

                if($response["sync2"]->status){

                    $id =  generateKode("sync_pmb", "id", $kode_lokasi.'SC'.date('Y'), "00001");
                    
                    $ins = "insert into sync_pmb (id,kode_lokasi,keterangan,tgl_sync,nik_user,total_rows) 
                    values ('$id','$kode_lokasi','DATA PEMBELIAN DAN JURNAL $no_beli',getdate(),'$nik',$total) ";
                    array_push($exec,$ins);

                    $ins2 = "insert into sync_pmb_d (kode_lokasi,keterangan,total_rows,id) 
                    values ('$kode_lokasi','TRANS M $no_beli',$tM,'$id') ";
                    array_push($exec,$ins2);
                    
                    $ins3 = "insert into sync_pmb_d (kode_lokasi,keterangan,total_rows,id) 
                    values ('$kode_lokasi','TRANS J $no_beli',$tJ,'$id') ";
                    array_push($exec,$ins3);

                    
                    $ins4 = "insert into sync_pmb_d (kode_lokasi,keterangan,total_rows,id) 
                    values ('$kode_lokasi','BRG HUT $no_beli',$tBRGJ,'$id') ";
                    array_push($exec,$ins4);
                    
                    
                    $ins5 = "insert into sync_pmb_d (kode_lokasi,keterangan,total_rows,id) 
                    values ('$kode_lokasi','BRG TRANSD $no_beli',$tBRGT,'$id') ";
                    array_push($exec,$ins5);

                    $upd1 = "update trans_m set id_sync='$id' where isnull(id_sync,'-') = '-'  and kode_lokasi='$kode_lokasi' and form='BRGBELI'  and no_bukti='$no_beli' "; 
                    array_push($exec,$upd1);
                    $upd2 = "update trans_j set id_sync='$id' where isnull(id_sync,'-') = '-'  and kode_lokasi='$kode_lokasi' and modul='BRGBELI' and jenis not in ('BRGRETBELI') and no_bukti='$no_beli' "; 
                    array_push($exec,$upd2);
                    $upd3 = "update brg_belihut_d set id_sync='$id' where isnull(id_sync,'-') = '-' and kode_lokasi='$kode_lokasi' and modul='BELI' and no_beli='$no_beli'";
                    array_push($exec,$upd3);
                    $upd4 = "update brg_trans_d set id_sync='$id' where isnull(id_sync,'-') = '-' and kode_lokasi='$kode_lokasi' and modul='BRGBELI' and no_bukti='$no_beli'";
                    array_push($exec,$upd4);
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
                    $tmp=$response["sync2"]->message;
                    $sts=false;
                
                }
				// $tmp="cek";
				// $sts = true;
               
            } catch (exception $e) { 
                error_log($e->getMessage());		
                $error ="error " .  $e->getMessage();
                
                $sts = false;
                $tmp = $error;
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

    function syncRetBeli(){
        session_start();
        getKoneksi();
        if($_COOKIE['isLogedIn'] AND cekAuth($_COOKIE['userLog'])){
            try{

                $fields = array(
                    "nik" => "kasir",
                    "pass" => "saisai"
                );

                $root="http://".$_SERVER['SERVER_NAME']."/api/ginas";
                $root="http://saiweb.simkug.com/api/ginas";

                //getToken
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "$root/SyncMaster.php?fx=generateToken");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($ch, CURLOPT_HEADER, FALSE);
                curl_setopt($ch, CURLOPT_POST, FALSE);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
                
                $response["sync"]= json_decode(curl_exec($ch));
                curl_close($ch);

                $token = $response["sync"]->token;
                $kode_lokasi = $_COOKIE['lokasi'];
                $nik = $_COOKIE['userLog'];
                $res["TRANSM"] = dbResultArray("select * from trans_m where kode_lokasi='$kode_lokasi' and isnull(id_sync,'-')='-' and form='BRGRETBELI' ");
                $res["TRANSJ"] = dbResultArray("select * from trans_j where kode_lokasi='$kode_lokasi' and isnull(id_sync,'-')='-' and modul='BRGBELI' and jenis ='BRGRETBELI' ");
                // $res["BARANG"] = array();
                $res["BRGBAYAR"] = dbResultArray("select * from brg_belibayar_d where kode_lokasi='$kode_lokasi' and isnull(id_sync,'-')='-'  and modul='KBBELICCL' ");
                $res["BRGTRANS"] = dbResultArray("select * from brg_trans_d where kode_lokasi='$kode_lokasi' and isnull(id_sync,'-')='-'  and modul='BRGRETBELI' ");

                $tM = ((count($res["TRANSM"]) > 0 ) ? count($res["TRANSM"]) : 0 );
                $tJ = ((count($res["TRANSJ"]) > 0 ) ? count($res["TRANSJ"]) : 0 );
                $tBRGJ = ((count($res["BRGBAYAR"]) > 0 ) ? count($res["BRGBAYAR"]) : 0 );
                $tBRGT = ((count($res["BRGTRANS"]) > 0 ) ? count($res["BRGTRANS"]) : 0 );

                $total = $tM+$tJ+$tBRGJ+$tBRGT;

                $exec = array();
                $fields2 = json_encode($res);
                //syncData
                $ch2 = curl_init();
                curl_setopt($ch2, CURLOPT_URL, "$root/SyncMaster.php?fx=syncReturBeli");
                curl_setopt($ch2, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json; charset=utf-8',
                    'Authorization: Bearer '.$token //REST API KEY GINAS
                ));
                curl_setopt($ch2, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($ch2, CURLOPT_HEADER, FALSE);
                curl_setopt($ch2, CURLOPT_POST, TRUE);
                curl_setopt($ch2, CURLOPT_POSTFIELDS, $fields2);
                curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt($ch2, CURLOPT_SSL_VERIFYHOST, FALSE);
                $response["sync2"]= json_decode(curl_exec($ch2));
                curl_close($ch2);

                $id =  generateKode("sync_retbeli", "id", $kode_lokasi.'SC'.date('Y'), "00001");
                
                $ins = "insert into sync_retbeli (id,kode_lokasi,keterangan,tgl_sync,nik_user,total_rows) 
                values ('$id','$kode_lokasi','DATA PENJUALAN DAN JURNAL',getdate(),'$nik',$total) ";
                array_push($exec,$ins);

                $ins2 = "insert into sync_retbeli_d (kode_lokasi,keterangan,total_rows,id) 
                values ('$kode_lokasi','TRANS M',$tM,'$id') ";
                array_push($exec,$ins2);
                
                $ins3 = "insert into sync_retbeli_d (kode_lokasi,keterangan,total_rows,id) 
                values ('$kode_lokasi','TRANS J',$tJ,'$id') ";
                array_push($exec,$ins3);

                
                $ins4 = "insert into sync_retbeli_d (kode_lokasi,keterangan,total_rows,id) 
                values ('$kode_lokasi','BRG BAYAR',$tBRGJ,'$id') ";
                array_push($exec,$ins4);
                
                
                $ins5 = "insert into sync_retbeli_d (kode_lokasi,keterangan,total_rows,id) 
                values ('$kode_lokasi','BRG TRANSD',$tBRGT,'$id') ";
                array_push($exec,$ins5);

                $upd1 = "update trans_m set id_sync='$id' where isnull(id_sync,'-') = '-'  and kode_lokasi='$kode_lokasi' and form='BRGRETBELI'  "; 
                array_push($exec,$upd1);
                $upd2 = "update trans_j set id_sync='$id' where isnull(id_sync,'-') = '-'  and kode_lokasi='$kode_lokasi' and modul='BRGBELI' and jenis='BRGRETBELI'  "; 
                array_push($exec,$upd2);
                $upd3 = "update brg_belibayar_d set id_sync='$id' where isnull(id_sync,'-') = '-' and kode_lokasi='$kode_lokasi' and modul='KBBELICCL' ";
                array_push($exec,$upd3);
                $upd4 = "update brg_trans_d set id_sync='$id' where isnull(id_sync,'-') = '-' and kode_lokasi='$kode_lokasi' and modul='BRGRETBELI' ";
                array_push($exec,$upd4);
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
               
            } catch (exception $e) { 
                error_log($e->getMessage());		
                $error ="error " .  $e->getMessage();
                
                $sts = false;
                $tmp = $error;
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

    function getHistoryPmb(){
        session_start();
        getKoneksi();
        if($_COOKIE['isLogedIn'] AND cekAuth($_COOKIE['userLog'])){
            $query = '';
            $output = array();
        
            $kode_lokasi = $_REQUEST['kode_lokasi'];
            $query .= "select id,tgl_sync,keterangan,total_rows,nik_user from sync_pmb where kode_lokasi='".$kode_lokasi."'  ";

            $column_array = array('id','tgl_sync','keterangan','total_rows','nik_user');
            $order_column = 'ORDER BY id '.$_POST['order'][0]['dir'];
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
                $query .= ' ORDER BY id desc';
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
                $sub_array[] = $row->id;
                $sub_array[] = $row->tgl_sync;
                $sub_array[] = $row->keterangan;
                $sub_array[] = $row->total_rows;
                $sub_array[] = $row->nik_user;
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

    function getHistoryRetBeli(){
        session_start();
        getKoneksi();
        if($_COOKIE['isLogedIn'] AND cekAuth($_COOKIE['userLog'])){
            $query = '';
            $output = array();
        
            $kode_lokasi = $_REQUEST['kode_lokasi'];
            $query .= "select id,tgl_sync,keterangan,total_rows,nik_user from sync_retbeli where kode_lokasi='".$kode_lokasi."'  ";

            $column_array = array('id','tgl_sync','keterangan','total_rows','nik_user');
            $order_column = 'ORDER BY id '.$_POST['order'][0]['dir'];
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
                $query .= ' ORDER BY id desc';
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
                $sub_array[] = $row->id;
                $sub_array[] = $row->tgl_sync;
                $sub_array[] = $row->keterangan;
                $sub_array[] = $row->total_rows;
                $sub_array[] = $row->nik_user;
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
    
