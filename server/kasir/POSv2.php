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
            else{
                insertProduk();
            }
        break;
        case 'PUT':
        // Update Product
        
            updateProduk();
        break;
        case 'DELETE':
        // Delete Product
            deleteProduk();
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

    function view(){
        // menggabungkan angka yang di-separate(10.000,75) menjadi 10000.00
        echo "test";
    }

    function joinNum2($num){
        // menggabungkan angka yang di-separate(10.000,75) menjadi 10000.00
        $num = str_replace(".", "", $num);
        $num = str_replace(",", ".", $num);
        return $num;
    }
    function simpanPnj(){
        session_start();
        getKoneksi();
        if($_COOKIE['isLogedIn'] AND cekAuth($_COOKIE['userLog'])){
   
            $kode_lokasi=$_POST['kode_lokasi'];    
            $nik=$_POST['nik_user'];
            $kode_pp=$_POST['kode_pp'];

            $str_format="0000";
            $periode=date('Y').date('m');
            $per=date('y').date('m');
            $prefix=$kode_lokasi."-PNJ".$per.".";
            $sql2="select right(isnull(max(no_bukti),'0000'),".strlen($str_format).")+1 as id from trans_m where no_bukti like '$prefix%' and kode_lokasi='".$_POST['kode_lokasi']."' ";
            
            $query = execute($sql2);
            
            $id = $prefix.str_pad($query->fields[0], strlen($str_format), $str_format, STR_PAD_LEFT);

            $sql="select kode_spro,flag from spro where kode_spro in ('JUALDIS','HUTPPN','JUALKAS') and kode_lokasi = '".$kode_lokasi."'";

            $rs=execute($sql);
            while ($row = $rs->FetchNextObject(false)){
                if ($row->kode_spro == "HUTPPN") $akunPPN=$row->flag;
                if ($row->kode_spro == "JUALDIS") $akunDiskon=$row->flag;
                if ($row->kode_spro == "JUALKAS") $akunKas=$row->flag;
            }

            $sqlp="select distinct b.akun_pdpt as kode_akun from brg_barang a inner join brg_barangklp b on a.kode_klp=b.kode_klp and a.kode_lokasi=b.kode_lokasi where a.kode_lokasi='$kode_lokasi' ";

            $rsp=execute($sqlp);
            $rowp = $rsp->FetchNextObject(false);
            $akunPDPT=$rowp->kode_akun;
            $exec = array();

            $sql1= "insert into trans_m (no_bukti,kode_lokasi,tgl_input,nik_user,periode,modul,form,posted,prog_seb,progress,kode_pp,tanggal,no_dokumen,keterangan,kode_curr,kurs,nilai1,nilai2,nilai3,nik1,nik2,nik3,no_ref1,no_ref2,no_ref3,param1,param2,param3) values 
                            ('".$id."','".$kode_lokasi."',getdate(),'".$nik."','".$periode."','IV','BRGJUAL','F','-','-','".$kode_pp."',getdate(),'-','Penjualan No: ".$id."','IDR',1,".joinNum2($_POST['total_stlh']).",".joinNum2($_POST['total_bayar']).",".joinNum2($_POST['total_disk']).",'-','-','-','-','-','TUNAI','-','CASH','".$akunPiutang."')";
            array_push($exec,$sql1);

            $sql2="insert into brg_jualpiu_d(no_jual,kode_lokasi,tanggal,keterangan,kode_cust,kode_curr,kurs,kode_pp,nilai,periode,nik_user,tgl_input,akun_piutang,nilai_ppn,nilai_pph,no_fp,diskon,kode_gudang) values 
                            ('".$id."','".$kode_lokasi."',getdate(),'Penjualan No: ".$id."','CASH','IDR',1,'".$kode_pp."',".joinNum2($_POST['total_stlh']).",'".$periode."','".$nik."',getdate(),'".$akunPiutang."',0,0,'-',".joinNum2($_POST['total_disk']).",'-')";		
            array_push($exec,$sql2);
            //KAS
            $sql3a="insert into trans_j (no_bukti,kode_lokasi,tgl_input,nik_user,periode,no_dokumen,tanggal,nu,kode_akun,dc,nilai,nilai_curr,keterangan,modul,jenis,kode_curr,kurs,kode_pp,kode_drk,kode_cust,kode_vendor,no_fa,no_selesai,no_ref1,no_ref2,no_ref3) values ('".$id."','".$kode_lokasi."',getdate(),'".$nik."','".$periode."','-',getdate(),0,'".$akunKas."','D',".joinNum2($_POST['total_stlh']).",".joinNum2($_POST['total_stlh']).",'Kas Penjualan','BRGJUAL','KAS','IDR',1,'$kode_pp','-','CASH','-','-','-','-','-','-')";
            array_push($exec,$sql3a);
            
            if($_POST['total_disk'] > 0 ){
                $sql3b="insert into trans_j (no_bukti,kode_lokasi,tgl_input,nik_user,periode,no_dokumen,tanggal,nu,kode_akun,dc,nilai,nilai_curr,keterangan,modul,jenis,kode_curr,kurs,kode_pp,kode_drk,kode_cust,kode_vendor,no_fa,no_selesai,no_ref1,no_ref2,no_ref3) values ('".$id."','".$kode_lokasi."',getdate(),'".$nik."','".$periode."','-',getdate(),1,'".$akunDiskon."','D',".joinNum2($_POST['total_disk']).",".joinNum2($_POST['total_disk']).",'Diskon Penjualan','BRGJUAL','JUALDISC','IDR',1,'$kode_pp','-','CASH','-','-','-','-','-','-')";
                array_push($exec,$sql3b);
            }

            //PNJ
            $sql3c="insert into trans_j (no_bukti,kode_lokasi,tgl_input,nik_user,periode,no_dokumen,tanggal,nu,kode_akun,dc,nilai,nilai_curr,keterangan,modul,jenis,kode_curr,kurs,kode_pp,kode_drk,kode_cust,kode_vendor,no_fa,no_selesai,no_ref1,no_ref2,no_ref3) values ('$id','".$kode_lokasi."',getdate(),'".$nik."','".$periode."','-',getdate(),2,'".$akunPDPT."','C',".joinNum2($_POST['total_trans']).",".joinNum2($_POST['total_trans']).",'Pendapatan Penjualan','BRGJUAL','JUALBRG','IDR',1,'$kode_pp','-','CASH','-','-','-','-','-','-')";
            array_push($exec,$sql3c);
            
            for($a=0; $a<count($_POST['kode_barang']);$a++){

                //$diskon[$a]= (joinNum2($_POST['harga_barang'][$a]) / $_POST['disc_barang'][$a])*100; 
                
                $sql4[$a]="insert into brg_trans_d (no_bukti,kode_lokasi,periode,modul,form,nu,kode_gudang,kode_barang,no_batch,tgl_ed,satuan,dc,stok,jumlah,bonus,harga,hpp,p_disk,diskon,tot_diskon,total) values 
                    ('".$id."','".$kode_lokasi."','".$periode."','BRGJUAL','BRGJUAL',".$a.",'".$_POST['kode_gudang']."','".$_POST['kode_barang'][$a]."','-',getdate(),'-','C',0,".joinNum2($_POST['qty_barang'][$a]).",0,".joinNum2($_POST['harga_barang'][$a]).",0,0,".$_POST['disc_barang'][$a].",0,".joinNum2($_POST['sub_barang'][$a]).")";
                array_push($exec,$sql4[$a]);
            }	

            $rs=executeArray($exec);
            $tmp=array();
            $kode = array();

            if ($rs)
            {	
                // CommitTrans();
                $tmp="Sukses disimpan";
                $sts=true;
            }else{
                // RollbackTrans();
                $tmp="Gagal disimpan";
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

    function getDataList(){
        session_start();
        getKoneksi();
        if($_COOKIE['isLogedIn'] AND cekAuth($_COOKIE['userLog'])){
            $requestData= $_REQUEST;
            
            $column_array = array('a.no_bukti','a.keterangan');
            $order_column = "ORDER BY a.no_bukti ".$requestData['order'][0]['dir'];
            $column_string = join(',', $column_array);
            
            if($requestData['order'][0]['column'] != 0){
                $order_column = "ORDER BY ".$column_array[$requestData['order'][0]['column']]." ".$requestData['order'][0]['dir'];
            }
            
            // $additional_filter_string = ($additional_filter !== null ? "where $additional_filter" : null);
            
            $sql = "select a.no_bukti, a.keterangan from trans_m a
            where a.kode_lokasi = '".$requestData['kode_lokasi']."' and a.periode='".$requestData['periode']."' and a.form='BRGJUAL' ";
            
            // $data["sql"]=$sql;
            
            $rs=execute($sql);
            
            $jml_baris = $rs->RecordCount();
            // $jml_baris_filtered = $jml_baris;
            
            if(empty($requestData['search']['value'])){
                // $data_filter = $dbLib->execute("$sql OFFSET ".$requestData['start']." ROWS FETCH NEXT $jml_baris ROWS ONLY ");
                $sql.="$order_column OFFSET ".$requestData['start']." ROWS FETCH NEXT $jml_baris ROWS ONLY ";
                // echo "$sql1 UNION $sql2 $order_column OFFSET ".$_POST['start']." ROWS FETCH NEXT $jml_baris ROWS ONLY ";
                $jml_baris_filtered = $jml_baris;
            }else{
                $search = $requestData['search']['value'];
                $filter_string = " and (";
                
                for($i=0; $i<count($column_array); $i++){
                    // $search_string = $dbLib->qstr("%$search%");
                    if($i == (count($column_array) - 1)){
                        $filter_string .= $column_array[$i]." like '".$search."%' )";
                    }else{
                        $filter_string .= $column_array[$i]." like '".$search."%' or ";
                    }
                }
                $sql.=" $filter_string $order_column  OFFSET ".$requestData['start']." ROWS FETCH NEXT $jml_baris ROWS ONLY ";

                $sql2= " select a.no_bukti, a.keterangan from trans_m a
                where a.kode_lokasi = '".$requestData['kode_lokasi']."' and a.periode='".$requestData['periode']."' and a.form='BRGJUAL' $filter_string $order_column ";

                $rs2= execute($sql2);
                $jml_baris_filtered = $rs2->RecordCount();
            }
            
            if($jml_baris > 0){
                $arr1 = execute($sql);
                // $arr1=$dbLib->LimitQuery($sql,25,$requestData['start']);	
            }else{
                $arr1 = array();
            }
            
            
            while ($row = $arr1->FetchNextObject($toupper=false))
            {
                $nestedData=array(); 
                
                $nestedData[] = $row->no_bukti;
                $nestedData[] = $row->keterangan;
                
                $data[] = $nestedData;
            }
            
            $response = array(
                "draw" => $requestData['draw'],
                "recordsTotal" => $jml_baris,
                "recordsFiltered" => $jml_baris_filtered,
                "data_list" => $data,
            );
            
            $response["status"] = true;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }

    function getLapPNJ(){
        session_start();
        getKoneksi();
        if($_COOKIE['isLogedIn'] AND cekAuth($_COOKIE['userLog'])){

            $data = $_POST;
            
            $col_array = array('gudang','kasir','periode','no_bukti');
            $db_col_name = array('b.kode_gudang','a.nik_user','a.periode','a.no_bukti');
            $where = "";
            
            for($i = 0; $i<count($col_array); $i++){
                if(ISSET($_POST[$col_array[$i]][0])){
                    if($_POST[$col_array[$i]][0] == "range" AND ISSET($_POST[$col_array[$i]][1]) AND ISSET($_POST[$col_array[$i]][2])){
                        $where .= " and (".$db_col_name[$i]." between '".$_POST[$col_array[$i]][1]."' AND '".$_POST[$col_array[$i]][2]."') ";
                    }else if($_POST[$col_array[$i]][0] == "exact" AND ISSET($_POST[$col_array[$i]][1])){
                        $where .= " and ".$db_col_name[$i]." = '".$_POST[$col_array[$i]][1]."' ";
                    }
                }
            }
            
            $sql="select a.no_bukti,convert(varchar,a.tanggal,103) as tanggal,a.keterangan,a.nilai1,b.nilai,b.kode_gudang,a.periode,a.nik_user
            from trans_m a 
            left join ( select no_bukti,kode_gudang,kode_lokasi,sum(case when dc='D' then -total else total end) as nilai
                        from brg_trans_d 
                        where kode_lokasi='".$data['kode_lokasi']."' and form='BRGJUAL'
                        group by no_bukti,kode_gudang,kode_lokasi
                        ) b on a.no_bukti=b.no_bukti and a.kode_lokasi=b.kode_lokasi
            where a.kode_lokasi='".$data['kode_lokasi']."'
            and a.form='BRGJUAL' $where
            order by a.no_bukti";

            $rs = execute($sql);

            while($row = $rs->FetchNextObject($toupper=false)){

                $resdata[]=(array)$row;
            }

            $response["result"] = $resdata;
            $response['sql'] = $sql;
            $response['where'] = $where;
            if(isset($data['periode'][1])){
                $response['periode'] = "Periode ".$data['periode'][1];
            }else{
                $response['periode'] = "Semua Periode";
            }
            
            $response["auth_status"] = 1;        

        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }

    function cekBonus(){
        getKoneksi();
        try{

            $kode_brg=$_POST['kode_barang'];
            $jumlah_brg=$_POST['jumlah'];
            $harga=$_POST['harga'];
            $kode_lokasi=$_POST['kode_lokasi'];
            $sql=" select beli,bonus from brg_bonus where kode_barang='$kode_brg' and kode_lokasi='$kode_lokasi' ";
            $res=dbResultArray($sql);
            $bonus=0;
            $diskon=0;
            if(count($res)>0){
                for($i=0;$i<count($res);$i++){
                    $bonus += (int) floor(abs($jumlah_brg/$res[$i]["beli"]));
                    $jumlah_brg+=($bonus*$res[$i]["bonus"]);
                    $diskon+= $bonus*$harga;
                }
            }
    
            $response["bonus"] = $bonus;
            $response["jumlah"] = $jumlah_brg;
            $response["diskon"] = $diskon;
            $response["status"] = true; 
        }catch (exception $e) { 
            error_log($e->getMessage());		
            $error ="error " .  $e->getMessage();
            $response['error'] = $error;
            $response["status"] = false;
        } 	
        echo json_encode($response);
    }
    
?>
