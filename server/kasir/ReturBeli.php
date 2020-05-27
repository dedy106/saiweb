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

    function joinNum2($num){
        // menggabungkan angka yang di-separate(10.000,75) menjadi 10000.00
        $num = str_replace(".", "", $num);
        $num = str_replace(",", ".", $num);
        return $num;
    }

    function getNew(){
        session_start();
        getKoneksi();
        if($_COOKIE['isLogedIn'] AND cekAuth($_COOKIE['userLog'])){         
            $data = $_POST;
            
            $query = '';
            $output = array();
            
            $kode_lokasi = $_COOKIE['lokasi'];
            $nik = $_COOKIE['userLog'];
            $query .= "SELECT a.no_bukti,a.param2+' - '+b.nama as vendor,a.tanggal,a.nilai1
            FROM trans_m a
            left join vendor b on a.param2=b.kode_vendor and a.kode_lokasi=b.kode_lokasi
            where a.form='BRGBELI' and a.kode_lokasi='$kode_lokasi'  ";
            
            $column_array = array('a.no_bukti',"a.param2+' - '+b.nama",'a.tanggal','a.nilai1');
            $order_column = 'order by a.no_bukti '.$data['order'][0]['dir'];
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
                $query .= ' order by '.$column_array[$data['order'][0]['column']].' '.$data['order'][0]['dir'];
            }
            else
            {
                $query .= ' order by a.no_bukti ';
            }
            if($data["length"] != -1)
            {
                $query .= ' offset ' . $data['start'] . ' rows fetch next ' . $data['length'] . ' rows only ';
            }
            $statement = execute($query);
            $data = array();
            $filtered_rows = $statement->RecordCount();
            while($row = $statement->FetchNextObject($toupper=false))
            {
                $sub_array = array();
                $sub_array[] = $row->no_bukti;
                $sub_array[] = $row->vendor;
                $sub_array[] = $row->tanggal;
                $sub_array[] = $row->nilai1;
                $data[] = $sub_array;
            }
            $response = array(
                "draw"				=>	intval($data["draw"]),
                "recordsTotal"		=> 	$filtered_rows,
                "recordsFiltered"	=>	$jml_baris,
                "data"				=>	$data,
            );
            $response['status']=true;
        }else{
            $response['status']=false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    function getFinish(){
        session_start();
        getKoneksi();
        if($_COOKIE['isLogedIn'] AND cekAuth($_COOKIE['userLog'])){         
            $data = $_POST;
            
            $query = '';
            $output = array();
            
            $kode_lokasi = $_COOKIE['lokasi'];
            $nik = $_COOKIE['userLog'];
            
            $query.="SELECT no_bukti,tanggal,no_dokumen,keterangan,nilai1 FROM trans_m where form='BRGRETBELI' and kode_lokasi='".$kode_lokasi."' ";
            
            $column_array = array('no_bukti','tanggal','no_dokumen','keterangan','nilai1');
            $order_column = 'order by no_bukti '.$data['order'][0]['dir'];
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
                $query .= ' order by '.$column_array[$data['order'][0]['column']].' '.$data['order'][0]['dir'];
            }
            else
            {
                $query .= ' order by no_bukti ';
            }
            if($data["length"] != -1)
            {
                $query .= ' offset ' . $data['start'] . ' rows fetch next ' . $data['length'] . ' rows only ';
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
                "query" => $query
            );
            $response['status']=true;
        }else{
            $response['status']=false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    function getEdit(){
        session_start();
        getKoneksi();
        if($_COOKIE['isLogedIn'] AND cekAuth($_COOKIE['userLog'])){

            $id=$_GET['kode'];  
            $kode_lokasi=$_GET['kode_lokasi'];    

            $response = array("message" => "", "rows" => 0, "status" => "" );

            $sql="select a.no_bukti,a.param2+'-'+b.nama as vendor,a.tanggal,a.nilai1-isnull(c.retur,0) as saldo,a.param3 as akun_hutang
            from trans_m a
            left join vendor b on a.param2=b.kode_vendor and a.kode_lokasi=b.kode_lokasi
            left join ( select no_ref1,kode_lokasi,sum(nilai1) as retur 
                        from trans_m where form='BRGRETBELI' and kode_lokasi='$kode_lokasi'
                        group by no_ref1,kode_lokasi ) c on a.no_bukti=c.no_ref1 and a.kode_lokasi=c.kode_lokasi
            where a.form='BRGBELI' and a.kode_lokasi='$kode_lokasi' and a.no_bukti='".$id."' ";
            
            $response['daftar'] = dbResultArray($sql);					
            
            $response['status'] = TRUE;
            $response['sql'] = $sql;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }

    function getBarang(){
        session_start();
        getKoneksi();
        if($_COOKIE['isLogedIn'] AND cekAuth($_COOKIE['userLog'])){

            $id=$_GET['no_bukti'];  
            $kode_lokasi=$_GET['kode_lokasi'];    

            $response = array("message" => "", "rows" => 0, "status" => "" );

            $sql2 = "select a.kode_barang,b.nama,a.jumlah,a.harga,isnull(c.jum_ret,0) as jum_ret, a.jumlah- isnull(c.jum_ret,0) as saldo,d.akun_pers
            from brg_trans_d a
            inner join brg_barang b on a.kode_barang=b.kode_barang and a.kode_lokasi=b.kode_lokasi
            inner join brg_barangklp d on b.kode_klp=d.kode_klp and b.kode_lokasi=d.kode_lokasi
            left join (select b.no_ref1,a.kode_barang, a.kode_lokasi, sum(a.jumlah) as jum_ret 
                       from brg_trans_d a
                       inner join trans_m b on a.no_bukti=b.no_bukti and a.kode_lokasi=b.kode_lokasi
                       where a.form='BRGRETBELI' and a.kode_lokasi='$kode_lokasi'
                       group by b.no_ref1,a.kode_barang,a.kode_lokasi ) c on a.kode_barang=c.kode_barang and a.no_bukti=c.no_ref1 and a.kode_lokasi=c.kode_lokasi 
            where a.no_bukti='$id' and a.kode_lokasi='$kode_lokasi' and a.form='BRGBELI' and a.jumlah- isnull(c.jum_ret,0) > 0 " ;
            $response["daftar"]=dbResultArray($sql2);
            $response["status"]=true;          
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

            if(joinNum($_POST['total_return']) <= joinNum($_POST['saldo'])){

                $kode_lokasi=$_POST['kode_lokasi'];    
                $nik=$_POST['nik_user'];
                $kode_pp=$_POST['kode_pp'];

                $sqlg="select top 1 a.kode_gudang from brg_gudang a where a.kode_lokasi='$kode_lokasi' ";
                $rsg=execute($sqlg);
                if($rsg->RecordCount() > 0){
                    $rowg = $rsg->FetchNextObject(false);
                    $kodeGudang=$rowg->kode_gudang;
                }else{
                    $kodeGudang="-";
                }
    
                $str_format="0001";
                // $periode=date('Y').date('m');
                // $per=date('y').date('m');
                $periode = $_POST["periode"];
                $per = substr($periode,2,2).substr($periode,4,2);
                $prefix=$kode_lokasi."-RTR".$per.".";
                // $sql2="select right(isnull(max(no_bukti),'$str_format'),".strlen($str_format).")+1 as id from trans_m where no_bukti like '$prefix%' and kode_lokasi='".$_POST['kode_lokasi']."' and modul='BRGRETBELI' ";

                $query = execute("select right(max(no_bukti), ".strlen($str_format).")+1 as id from trans_m where no_bukti like '$prefix%'");
                $kode = $query->fields[0];
                $id = $prefix.str_pad($kode, strlen($str_format), $str_format, STR_PAD_LEFT);
                
                $query = execute($sql2);
                $exec = array();
                
                $temp = explode("-",$_POST['kode_vendor']);
                $vendor = $temp[0];
                $sql = "insert into trans_m (no_bukti,kode_lokasi,tgl_input,nik_user,periode,modul,form,posted,prog_seb,progress,kode_pp,tanggal,no_dokumen,keterangan,kode_curr,kurs,nilai1,nilai2,nilai3,nik1,nik2,nik3,no_ref1,no_ref2,no_ref3,param1,param2,param3) values 
                        ('".$id."','".$kode_lokasi."',getdate(),'".$nik."','".$_POST['periode']."','IV','BRGRETBELI','F','-','-','".$kode_pp."','".$_POST['tanggal']."','-','Retur Pembelian No: ".$id."','IDR',1,".joinNum($_POST['total_return']).",0,0,'-','-','-','".$_POST['no_beli']."','-','-','-','".$vendor."','-')";
                array_push($exec,$sql);
                $sql2 = "insert into trans_j (no_bukti,kode_lokasi,tgl_input,nik_user,periode,no_dokumen,tanggal,nu,kode_akun,dc,nilai,nilai_curr,keterangan,modul,jenis,kode_curr,kurs,kode_pp,kode_drk,kode_cust,kode_vendor,no_fa,no_selesai,no_ref1,no_ref2,no_ref3) values
                        ('".$id."','".$kode_lokasi."',getdate(),'".$nik."','".$_POST['periode']."','-','".$_POST['tanggal']."',0,'".$_POST['akun_hutang']."','D',".joinNum($_POST['total_return']).",".joinNum($_POST['total_return']).",'Retur Pembelian No:".$_POST['no_beli']."','BRGBELI','BRGRETBELI','IDR',1,'$kode_pp','-','-','".$vendor."','-','-','-','-','-')";
                array_push($exec,$sql2);
    
                $nu=1;
                for ($i=0;$i < count($_POST['kode_barang']);$i++){						
                    
                    $sql1 = "insert into brg_belibayar_d(no_bukti,kode_lokasi,no_beli,kode_vendor,periode,dc,modul,nilai,nik_user,tgl_input) 
                    values ('".$id."','".$kode_lokasi."','".$_POST['no_beli']."','-', '".$_POST['periode']."','D','KBBELICCL',".joinNum($_POST['total_return']).",'".$nik."',getdate())";
                    array_push($exec,$sql1);
                
                    $sql2 = "insert into trans_j (no_bukti,kode_lokasi,tgl_input,nik_user,periode,no_dokumen,tanggal,nu,kode_akun,dc,nilai,nilai_curr,keterangan,modul,jenis,kode_curr,kurs,kode_pp,kode_drk,kode_cust,kode_vendor,no_fa,no_selesai,no_ref1,no_ref2,no_ref3) values
                            ('".$id."','".$kode_lokasi."',getdate(),'".$nik."','".$_POST['periode']."','-','".$_POST['tanggal']."',".$nu.",'".$_POST['kode_akun'][$i]."','C',".joinNum($_POST['subtotal'][$i]).",".joinNum($_POST['subtotal'][$i]).",'Retur Pembelian No:".$_POST['no_beli']."','BRGBELI','BRGRETBELI','IDR',1,'$kode_pp','-','-','".$vendor."','-','-','-','-','-')";
                    array_push($exec,$sql2);
                   
                    $sql3="insert into brg_trans_d (no_bukti,kode_lokasi,periode,modul,form,nu,kode_gudang,kode_barang,no_batch,tgl_ed,satuan,dc,stok,jumlah,bonus,harga,hpp,p_disk,diskon,tot_diskon,total) values 
                            ('".$id."','".$kode_lokasi."','".$_POST['periode']."','BRGRETBELI','BRGRETBELI',".$nu.",'$kodeGudang','".$_POST['kode_barang'][$i]."','-',getdate(),'".$_POST['satuan'][$i]."','C',".joinNum($_POST['qty_beli'][$i]).",".joinNum($_POST['qty_retur'][$i]).",0,".joinNum($_POST['harga'][$i]).",0,0,0,0,".joinNum($_POST['subtotal'][$i]).")";
                    array_push($exec,$sql3);	
                    $nu++;	
                    
                }
    
                
                $rs=executeArray($exec,$err);
                
                $tmp=array();
                $kode = array();
                $sts=false;
                // $err = null;
                if ($err == null)
                {	
                    $tmp="sukses disimpan";
                    $sts=true;
                }else{
                    $tmp=$err;
                    $sts=false;
                }		
                $response["message"] =$tmp;
                $response["status"] = $sts;
                $response["exec"] = $exec;
            }else{
                $response["message"] = "error. Total Retur tidak boleh melebihi saldo pembelian";
                $response["status"] = false;
            }
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }

?>
