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
            $query .= "SELECT no_open,nik,tgl_input,saldo_awal FROM kasir_open where kode_lokasi='".$kode_lokasi."' and no_close='-' ";
            
            $column_array = array('no_open','nik','tgl_input','saldo_awal');
            $order_column = 'order by no_open '.$data['order'][0]['dir'];
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
                $query .= ' order by no_open ';
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
                $sub_array[] = $row->no_open;
                $sub_array[] = $row->nik;
                $sub_array[] = $row->tgl_input;
                $sub_array[] = $row->saldo_awal;
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
            
            $query.="SELECT no_close,nik,tgl_input,saldo_awal,total_pnj FROM kasir_close where kode_lokasi='".$kode_lokasi."' ";
            
            $column_array = array('no_close','nik','tgl_input','saldo_awal','total_pnj');
            $order_column = 'order by no_close '.$data['order'][0]['dir'];
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
                $query .= ' order by no_close ';
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
                $sub_array[] = $row->no_close;
                $sub_array[] = $row->nik;
                $sub_array[] = $row->tgl_input;
                $sub_array[] = $row->saldo_awal;
                $sub_array[] = $row->total_pnj;
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

    function getEditCloseKasir(){
        session_start();
        getKoneksi();
        if($_COOKIE['isLogedIn'] AND cekAuth($_COOKIE['userLog'])){

            $id=$_GET['kode'];    

            $response = array("message" => "", "rows" => 0, "status" => "" );

            $sql="select a.no_open,a.nik,a.tgl_input,a.saldo_awal,isnull(b.total,0) as total_pnj,isnull(b.diskon,0) as total_disk,convert(varchar(10),a.tgl_input,121) as tgl 
            from kasir_open a 
            left join 
            ( select a.no_open,sum(a.nilai) as total,sum(a.diskon) as diskon
              from brg_jualpiu_dloc a
              where a.no_close='-' 
              group by a.no_open
              ) b on a.no_open=b.no_open 
            where a.kode_lokasi='".$_GET['kode_lokasi']."' and a.no_open='".$id."' and a.nik='".$_GET['nik']."' ";
            
            $rs = execute($sql);					
            
            while ($row = $rs->FetchNextObject(false)){
                $response['daftar'][] = (array)$row;
            }

            $query = "select no_jual,tanggal,keterangan,periode,nilai,diskon from brg_jualpiu_dloc
            where kode_lokasi = '".$_GET['kode_lokasi']."' and no_open='$id' " ;
          
            $response['data'] = dbResultArray($query);
            $response['status'] = TRUE;
            $response['sql'] = $sql;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }

    function simpanCloseKasir(){
        session_start();
        getKoneksi();
        if($_COOKIE['isLogedIn'] AND cekAuth($_COOKIE['userLog'])){
            
            $kode_lokasi=$_POST['kode_lokasi'];    
            $nik=$_POST['nik_user'];
            $kode_pp=$_POST['kode_pp'];
            $sqlcek = "select no_open from kasir_open where no_open='".$_POST['no_open']."' and isnull(no_close,'-') = '-' and kode_lokasi='$kode_lokasi' ";
            $cek = execute($sqlcek);
            if($cek->RecordCount() > 0){

    
                $str_format="0000";
                $periode=date('Y').date('m');
                $per=date('y').date('m');
                $prefix=$kode_lokasi."-CLS".$per.".";
                $sql2="select right(isnull(max(no_close),'0000'),".strlen($str_format).")+1 as id from kasir_close where no_close like '$prefix%' and kode_lokasi='".$_POST['kode_lokasi']."' ";
                
                $query = execute($sql2);
                
                $id = $prefix.str_pad($query->fields[0], strlen($str_format), $str_format, STR_PAD_LEFT);
    
                $spro = dbResultArray("select a.akun_pdpt, sum (case when c.dc='C' then c.total else -c.total end) as nilai_jual from brg_barangklp a
                inner join brg_barang b on a.kode_klp=b.kode_klp and a.kode_lokasi=b.kode_lokasi
                inner join brg_trans_dloc c on b.kode_barang=c.kode_barang and c.kode_lokasi=b.kode_lokasi
                inner join brg_jualpiu_dloc d on c.no_bukti=d.no_jual and c.kode_lokasi=d.kode_lokasi
                 where  a.kode_lokasi='$kode_lokasi' and d.no_close='-' and d.no_open='".$_POST['no_open']."' and d.nik_user='$nik' group by a.akun_pdpt
                ");
                if(count($spro)>0){
                    $akunpdpt=$spro[0]["akun_pdpt"];
                }else{
                    $akunpdpt = "-";
                }
    
                $spro2 =dbResultArray("select kode_spro,flag  from spro where kode_lokasi='$kode_lokasi' and kode_spro='JUALDIS'");
    
                if(count($spro2)>0){
                    $akunDiskon=$spro2[0]["flag"];
                }else{
                    $akunDiskon = "-";
                }
    
                $spro3 = dbResultArray(" select kode_spro,flag  from spro where kode_lokasi='$kode_lokasi' and kode_spro='CUSTINV'");
                if(count($spro3)>0){
                    $akunpiu=$spro3[0]["flag"];
                }else{
                    $akunpiu = "-";
                }
    
                $exec = array();
                if(ISSET($_POST['no_close'])){
    
                    $sql2="delete from kasir_close where no_close='".$_POST['no_close']."' and kode_lokasi='".$kode_lokasi."' ";
                    // $rs2=execute($sql2);
                    array_push($exec,$sql2);
    
                    $sqlx="delete from trans_m where no_bukti='".$_POST['no_close']."' and kode_lokasi='".$kode_lokasi."' ";
                    // $rs2=execute($sql2);
                    array_push($exec,$sqlx);
    
                    $sqly="delete from trans_j where no_bukti='".$_POST['no_close']."' and kode_lokasi='".$kode_lokasi."' ";
                    // $rs2=execute($sql2);
                    array_push($exec,$sqly);
    
                    
                    $sql3="update kasir_open set no_close='-' where no_open='".$_POST['no_open']."' and kode_lokasi='".$kode_lokasi."' ";
                    // $rs3=execute($sql3);
                    array_push($exec,$sql3);
    
                    if(count($_POST['no_jual']) > 0){
    
                        for($i=0;$i<count($_POST['no_jual']);$i++){
                            $upd[$i] = "update brg_jualpiu_dloc set no_close ='-' where no_jual ='".$_POST['no_jual'][$i]."' ";
                            array_push($exec,$upd[$i]);
                        }   
                    }
                    
                }
    
    
                $sql1= "insert into kasir_close (no_close,kode_lokasi,tgl_input,nik_user,nik,saldo_awal,total_pnj) values 
                        ('".$id."','".$kode_lokasi."',getdate(),'".$nik."','".$nik."',".joinNum2($_POST['saldo_awal']).",".joinNum2($_POST['total_pnj']).")";
                array_push($exec,$sql1);
    
                $sql4="update kasir_open set no_close='$id' where no_open='".$_POST['no_open']."' and kode_lokasi='".$kode_lokasi."' ";
                array_push($exec,$sql4);
    
    
                if(count($_POST['no_jual']) > 0){
    
                    ///------------------------------------JURNAL--------------------------------//
                    $sqlm = "insert into trans_m (no_bukti,kode_lokasi,tgl_input,nik_user,periode,modul,form,posted,prog_seb,progress,kode_pp,tanggal,no_dokumen,keterangan,kode_curr,kurs,nilai1,nilai2,nilai3,nik1,nik2,nik3,no_ref1,no_ref2,no_ref3,param1,param2,param3) values 
                    ('".$id."','".$kode_lokasi."',getdate(),'".$_POST['nik_user']."','".$periode."','IV','CLOSING','F','-','-','".$kode_pp."','".$_POST['tgl']."','-','Penjualan Persediaan','IDR',1,".joinNum2($_POST['total_pnj']).",0,".joinNum2($_POST['total_disk']).",'-','-','-','-','-','-','-','-','-')";
                    array_push($exec,$sqlm);
    
                    $total_pnj=joinNum2($_POST['total_pnj'])+joinNum2($_POST['total_disk']);
                    
                    $sqlJ2="insert into trans_j (no_bukti,kode_lokasi,tgl_input,nik_user,periode,no_dokumen,tanggal,nu,kode_akun,dc,nilai,nilai_curr,keterangan,modul,jenis,kode_curr,kurs,kode_pp,kode_drk,kode_cust,kode_vendor,no_fa,no_selesai,no_ref1,no_ref2,no_ref3) values 
                    ('".$id."','".$kode_lokasi."',getdate(),'".$nik."','".$periode."','-','".$_POST['tgl']."',0,'".$akunpiu."','D',".joinNum2($_POST['total_pnj']).",".joinNum2($_POST['total_pnj']).",'Piutang','BRGJUAL','PIUTANG','IDR',1,'$kode_pp','-','-','-','-','-','-','-','-')";
    
                    array_push($exec,$sqlJ2);
                    
                    if (joinNum2($_POST['total_disk']) > 0) {
    
                        $sqld="insert into trans_j (no_bukti,kode_lokasi,tgl_input,nik_user,periode,no_dokumen,tanggal,nu,kode_akun,dc,nilai,nilai_curr,keterangan,modul,jenis,kode_curr,kurs,kode_pp,kode_drk,kode_cust,kode_vendor,no_fa,no_selesai,no_ref1,no_ref2,no_ref3) values 
                            ('".$id."','".$kode_lokasi."',getdate(),'".$nik."','".$periode."','-','".$_POST['tgl']."',1,'".$akunDiskon."','D',".joinNum2($_POST['total_disk']).",".joinNum2($_POST['total_disk']).",'Diskon Penjualan','BRGJUAL','JUALDISC','IDR',1,'$kode_pp','-','-','-','-','-','-','-','-')";
                            
                        array_push($exec,$sqld);
                    }
    
                    $sqlJ="insert into trans_j (no_bukti,kode_lokasi,tgl_input,nik_user,periode,no_dokumen,tanggal,nu,kode_akun,dc,nilai,nilai_curr,keterangan,modul,jenis,kode_curr,kurs,kode_pp,kode_drk,kode_cust,kode_vendor,no_fa,no_selesai,no_ref1,no_ref2,no_ref3) values 
                    ('".$id."','".$kode_lokasi."',getdate(),'".$nik."','".$periode."','-','".$_POST['tgl']."',2,'".$akunpdpt."','C',".$total_pnj.",".$total_pnj.",'Penjualan','BRGJUAL','PDPT','IDR',1,'$kode_pp','-','-','-','-','-','-','-','-')";
                    array_push($exec,$sqlJ);
    
                    ///------------------------------------END JURNAL--------------------------------//
    
                    $sql = "select no_jual from brg_jualpiu_dloc where no_open='".$_POST['no_open']."' and kode_lokasi='$kode_lokasi' ";
                    $return = dbResultArray($sql);
    
                    for($i=0;$i<count($return);$i++){
    
                        $upd[$i] = "update brg_jualpiu_dloc set no_close ='".$id."' where no_jual ='".$return[$i]['no_jual']."' ";
                        array_push($exec,$upd[$i]);
    
                        $upd2[$i] = "update brg_trans_dloc set no_close ='".$id."' where no_bukti ='".$return[$i]['no_jual']."' ";
                        array_push($exec,$upd2[$i]);
    
                        
                    }   
                }
    
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
                $tmp="Error! No open ".$_POST['no_open']." sudah diclose.";
                $sts=false;
            }
            $response["message"] =$tmp;
            $response["status"] = $sts;
            $response["nik"]=$nik;
            $response["sql1"]=$exec;

        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }

?>
