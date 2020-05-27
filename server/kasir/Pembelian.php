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

    function view(){
        session_start();
        getKoneksi();
        $sql ="select*from hakakses where nik='admin' ";
        $response['data1']=dbResultArray($sql);
        $sql ="select*from hakakses where nik='dev' ";
        $response['data2']=dbResultArray2($sql);
        echo json_encode($response);
    }
    function simpan(){
        session_start();
        getKoneksi();
        if($_COOKIE['isLogedIn'] AND cekAuth($_COOKIE['userLog'])){
            
            $kode_lokasi=$_POST['kode_lokasi'];    
            $nik=$_POST['nik_user'];
            $kode_pp=$_POST['kode_pp'];

            $str_format="0000";
            $periode=date('Y').date('m');
            $per=date('y').date('m');
            $prefix="PO/".substr($periode,2,4)."/";
            $sql2="select right(isnull(max(no_bukti),'0000'),".strlen($str_format).")+1 as id from trans_m where no_bukti like '$prefix%' and kode_lokasi='".$kode_lokasi."' ";
            
            $query = execute($sql2);
            
            $id = $prefix.str_pad($query->fields[0], strlen($str_format), $str_format, STR_PAD_LEFT);
            
            $sql="select kode_spro,flag from spro where kode_spro in ('PPNM','BELIDIS') and kode_lokasi = '".$kode_lokasi."'";

            $rs=execute($sql);
            while ($row = $rs->FetchNextObject(false)){
                if ($row->kode_spro == "PPNM") $akunPPN=$row->flag;
                if ($row->kode_spro == "BELIDIS") $akunDiskon=$row->flag;
            }

            $sql3 = "select akun_hutang from vendor where kode_vendor ='".$_POST["kode_vendor"]."' and kode_lokasi = '".$kode_lokasi."'";
            $res=execute($sql3);			
            if ($res->RecordCount() > 0){
                $akunHutang = $res->fields[0];									
            }	

            $sqlg="select top 1 a.kode_gudang from brg_gudang a where a.kode_lokasi='$kode_lokasi' ";
            $rsg=execute($sqlg);
            if($rsg->RecordCount() > 0){
                $rowg = $rsg->FetchNextObject(false);
                $kodeGudang=$rowg->kode_gudang;
            }else{
                $kodeGudang="-";
            }

            $exec = array();

            $sqlm = "insert into trans_m (no_bukti,kode_lokasi,tgl_input,nik_user,periode,modul,form,posted,prog_seb,progress,kode_pp,tanggal,no_dokumen,keterangan,kode_curr,kurs,nilai1,nilai2,nilai3,nik1,nik2,nik3,no_ref1,no_ref2,no_ref3,param1,param2,param3) values 
                    ('".$id."','".$kode_lokasi."',getdate(),'".$_POST['nik_user']."','".$periode."','IV','BRGBELI','F','-','-','".$kode_pp."',getdate(),'".$_POST['no_faktur']."','Pembelian Persediaan','IDR',1,".joinNum2($_POST['total_stlh']).",".joinNum2($_POST['total_ppn']).",".joinNum2($_POST['total_disk']).",'-','-','-','-','-','-','-','".$_POST['kode_vendor']."','".$akunHutang."')";
            array_push($exec,$sqlm);
					
            $sqlb = "insert into brg_belihut_d(no_beli,kode_lokasi,tanggal,keterangan,kode_vendor,kode_curr,kurs,kode_pp,nilai,periode,nik_user,tgl_input,akun_hutang,nilai_ppn,no_fp,due_date, nilai_pph, diskon, modul,kode_gudang) values  
                    ('".$id."','".$kode_lokasi."',getdate(), 'Pembelian Persediaan','".$_POST['kode_vendor']."','IDR',1,'".$kode_pp."',".joinNum2($_POST['total_stlh']).",'".$periode."','".$nik."',getdate(),'".$akunHutang."',".joinNum2($_POST['total_ppn']).",'-',getdate(),0,".joinNum2($_POST['total_disk']).", 'BELI','$kodeGudang')";
            
            array_push($exec,$sqlb);
                        
            $series = array();
            $series2 = array();
            $group = array();
            $nilai = 0;
            $diskItem = 0;
            $total=0;
            for($b=0; $b<count($_POST['kode_barang']);$b++){
				
                // $nilai = joinNum2($_POST['harga_barang'][$b])*joinNum2($_POST['qty_barang'][$b]);
                $nilai = joinNum2($_POST['sub_barang'][$b]);
                $isAda = false;
                $idx = 0;
                
                $akun = $_POST['kode_akun'][$b];						
                for ($c=0;$c <= $b;$c++){
                    if ($akun == $_POST['kode_akun'][$c-1]) {
                        $isAda = true;
                        $idx = $c;
                        break;
                    }
                }
                if (!$isAda) {							
                    array_push($series,$_POST['kode_akun'][$b]);
                    
                    $series2[$_POST['kode_akun'][$b]]=$nilai;
                } 
                else { 
                    $total = $series2[$_POST['kode_akun'][$b]];
                    $total = $total + $nilai;
                    $series2[$_POST['kode_akun'][$b]]=$total;
                }		
                    
                $diskItem+=$_POST['disc_barang'][$b];
			}
            for($x=0; $x<count($series);$x++){
                
                $sqlj="insert into trans_j (no_bukti,kode_lokasi,tgl_input,nik_user,periode,no_dokumen,tanggal,nu,kode_akun,dc,nilai,nilai_curr,keterangan,modul,jenis,kode_curr,kurs,kode_pp,kode_drk,kode_cust,kode_vendor,no_fa,no_selesai,no_ref1,no_ref2,no_ref3) values 
                    ('".$id."','".$kode_lokasi."',getdate(),'".$nik."','".$periode."','-',getdate(),".$x.",'".$_POST['kode_akun'][$x]."','D',".joinNum2($series2[$series[$x]]).",".joinNum2($series2[$series[$x]]).",'Persediaan Barang','BRGBELI','BRGBELI','IDR',1,'$kode_pp','-','-','-','-','-','-','-','-')";
                    
                array_push($exec,$sqlj);
                    
            }
            
            $totDiskon = joinNum2($_POST['total_disk']) +$diskItem;
            if (joinNum2($_POST['total_ppn']) > 0) {
                $x=$x+1;
                $sql6="insert into trans_j (no_bukti,kode_lokasi,tgl_input,nik_user,periode,no_dokumen,tanggal,nu,kode_akun,dc,nilai,nilai_curr,keterangan,modul,jenis,kode_curr,kurs,kode_pp,kode_drk,kode_cust,kode_vendor,no_fa,no_selesai,no_ref1,no_ref2,no_ref3) values 
                    ('".$id."','".$kode_lokasi."',getdate(),'".$nik."','".$periode."','-',getdate(),".$x.",'".$akunPPN."','D',".joinNum2($_POST['total_ppn']).",".joinNum2($_POST['total_ppn']).",'PPN Masukan','BRGBELI','PPNM','IDR',1,'$kode_pp','-','-','-','-','-','-','-','-')";
                    
                array_push($exec,$sql6);
            }

            if (joinNum2($_POST['total_stlh']) > 0) {
                $x=$x+1;
                $sql7="insert into trans_j (no_bukti,kode_lokasi,tgl_input,nik_user,periode,no_dokumen,tanggal,nu,kode_akun,dc,nilai,nilai_curr,keterangan,modul,jenis,kode_curr,kurs,kode_pp,kode_drk,kode_cust,kode_vendor,no_fa,no_selesai,no_ref1,no_ref2,no_ref3) values 
                    ('".$id."','".$kode_lokasi."',getdate(),'".$nik."','".$periode."','-',getdate(),".$x.",'".$akunHutang."','C',".joinNum2($_POST['total_stlh']).",".joinNum2($_POST['total_stlh']).",'Hutang Vendor Pembelian','BRGBELI','BELIDISC','IDR',1,'$kode_pp','-','-','-','-','-','-','-','-')";
                    
                array_push($exec,$sql7);
            }
            
            if (joinNum2($_POST['total_disk']) > 0) {
                $x=$x+1;
                $sql8="insert into trans_j (no_bukti,kode_lokasi,tgl_input,nik_user,periode,no_dokumen,tanggal,nu,kode_akun,dc,nilai,nilai_curr,keterangan,modul,jenis,kode_curr,kurs,kode_pp,kode_drk,kode_cust,kode_vendor,no_fa,no_selesai,no_ref1,no_ref2,no_ref3) values 
                    ('".$id."','".$kode_lokasi."',getdate(),'".$nik."','".$periode."','-',getdate(),".$x.",'".$akunDiskon."','C',".joinNum2($totDiskon).",".joinNum2($totDiskon).",'Diskon Pembelian','BRGBELI','BELIDISC','IDR',1,'$kode_pp','-','-','-','-','-','-','-','-')";
                    
                array_push($exec,$sql8);
            }
            
            for($a=0; $a<count($_POST['kode_barang']);$a++){

                $sql9="insert into brg_trans_d (no_bukti,kode_lokasi,periode,modul,form,nu,kode_gudang,kode_barang,no_batch,tgl_ed,satuan,dc,stok,jumlah,bonus,harga,hpp,p_disk,diskon,tot_diskon,total) values 
                        ('".$id."','".$kode_lokasi."','".$periode."','BRGBELI','BRGBELI',".$a.",'$kodeGudang','".$_POST['kode_barang'][$a]."','-',getdate(),'".$_POST['satuan_barang'][$a]."','D',0,".joinNum2($_POST['qty_barang'][$a]).",0,".joinNum2($_POST['harga_barang'][$a]).",0,0,".$diskItem.",".joinNum2($_POST['disc_barang'][$a]).",".joinNum2($_POST['sub_barang'][$a]).")";

                array_push($exec,$sql9);

                $sql10="update brg_barang set nilai_beli =".joinNum2($_POST['harga_barang'][$a])." where kode_barang ='".$_POST['kode_barang'][$a]."'  and kode_lokasi='$kode_lokasi' ";
                array_push($exec,$sql10);

                $sql11="update brg_barang set hna =".joinNum2($_POST['harga_jual'][$a])." where kode_barang ='".$_POST['kode_barang'][$a]."'  and kode_lokasi='$kode_lokasi' ";
                array_push($exec,$sql11);
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
            $response["message"] =$tmp;
            $response["status"] = $sts;
            $response["no_bukti"]= $id;
            $response["sql"]= $exec;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        echo json_encode($response);
    }

    function getNota(){
        session_start();
        getKoneksi();
        if($_COOKIE['isLogedIn'] AND cekAuth($_COOKIE['userLog'])){
            
            $kode_lokasi=$_GET['kode_lokasi'];    
            $response["nik"]=$_GET['nik_user'];
            $kode_pp=$_GET['kode_pp'];
            $no_bukti=$_GET['no_bukti'];
            $response["no_bukti"] = $no_bukti;

            $sql="select * from trans_m where no_bukti='$no_bukti' and kode_lokasi='$kode_lokasi' ";
            $rj=execute($sql);
            $row=$rj->FetchNextObject($toupper=false);

            $response["totpemb"]=$row->nilai1-$row->nilai2+$row->nilai3;
            $response["totdisk"]=$row->nilai2;
            $response["tottrans"]=$row->nilai1;
            $response["totppn"]=$row->nilai3;
            $response["tgl"] = $row->tanggal;
            
            $response["sql"] = $sql;

            $sql="select a.kode_barang,a.harga,a.jumlah,a.diskon,b.nama,b.sat_kecil from brg_trans_d a inner join brg_barang b on a.kode_barang=b.kode_barang and a.kode_lokasi=b.kode_lokasi where a.no_bukti='$no_bukti' and a.kode_lokasi='$kode_lokasi' ";
            $response["daftar"] = dbResultArray($sql); 
            
            $response["sql2"] = $sql;      
            $response["status"] = true;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        echo json_encode($response);
    }

    function getBarang(){
        session_start();
        getKoneksi();
        if($_COOKIE['isLogedIn'] AND cekAuth($_COOKIE['userLog'])){
            $kode_lokasi = $_GET['kode_lokasi'];
            $nik=$_GET['nik_user'];
            $periode = date('Ym');
            $tahun = date('Y');
            
            $sql="select a.kode_barang,a.nama,a.hna as harga,a.barcode,a.sat_kecil as satuan,x.akun_pers as kode_akun,isnull(a.nilai_beli,0) as harga_seb,isnull(d.sakhir,0) as saldo 
            from ( select a.kode_barang,a.nama,a.hna,a.barcode,a.sat_kecil,a.nilai_beli,b.kode_gudang,a.kode_klp,a.kode_lokasi
			from brg_barang a cross join brg_gudang b
			where a.kode_lokasi='$kode_lokasi' and b.kode_lokasi='$kode_lokasi' and b.kode_gudang='G01'
			) a
            inner join brg_barangklp x on a.kode_klp=x.kode_klp and a.kode_lokasi=x.kode_lokasi 
            left join (select kode_barang,kode_gudang,kode_lokasi,sum(jumlah) as sawal from brg_sawal 
                        where periode='$tahun-01' and kode_lokasi='$kode_lokasi' 
                        group by kode_lokasi,kode_barang,kode_gudang 
            ) b on a.kode_barang=b.kode_barang and a.kode_lokasi=b.kode_lokasi and a.kode_gudang=b.kode_gudang 
            
            left join (select kode_barang,kode_gudang,kode_lokasi,sum(jumlah+bonus) as beli 
                        from brg_trans_d 
                        where modul='BRGBELI' and periode like '$tahun%' and periode <= '$periode' and kode_lokasi='$kode_lokasi' 
                        group by kode_lokasi,kode_barang,kode_gudang 
            ) c on a.kode_barang=c.kode_barang and a.kode_lokasi=c.kode_lokasi and a.kode_gudang=c.kode_gudang    	   	 
            
            left join (select kode_barang,kode_gudang,kode_lokasi,sum(stok) as sakhir 
                        from brg_stok where kode_lokasi='$kode_lokasi' and nik_user ='$nik' 
                        group by kode_lokasi,kode_barang,kode_gudang 
            ) d on a.kode_barang=d.kode_barang and a.kode_lokasi=d.kode_lokasi and a.kode_gudang=d.kode_gudang 								 
            where a.kode_lokasi='$kode_lokasi' ";
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
        echo json_encode($response);
    }

    function getVendor(){
        session_start();
        getKoneksi();
        if($_COOKIE['isLogedIn'] AND cekAuth($_COOKIE['userLog'])){
            $kode_lokasi = $_GET['kode_lokasi'];
            $sql="select kode_vendor,nama from vendor where kode_lokasi='$kode_lokasi'";
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
        echo json_encode($response);
    }


    
    function getDaftar(){
        session_start();
        getKoneksi();
        if($_COOKIE['isLogedIn'] AND cekAuth($_COOKIE['userLog'])){
            $query = '';
            $output = array();

            $nik = $_REQUEST['nik'];
            $kode_lokasi = $_REQUEST['kode_lokasi'];
            $query .= "select no_bukti,nik_user,tanggal,param2 as kode_vendor,nilai1 as total from trans_m where kode_lokasi='".$kode_lokasi."' and nik_user='".$nik."' and form = 'BRGBELI' ";

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

    function getEdit(){
        session_start();
        getKoneksi();
        if($_COOKIE['isLogedIn'] AND cekAuth($_COOKIE['userLog'])){
            $kode_lokasi = $_GET['kode_lokasi'];
            $no_bukti = $_GET['no_bukti'];
            $nik = $_GET['nik'];

            $sql="select no_bukti,nik_user,nilai1 as total,nilai2 as ppn,nilai3 as diskon,param2 as kode_vendor,no_dokumen from trans_m where form='BRGBELI' and kode_lokasi='$kode_lokasi' and nik_user='$nik' and no_bukti='$no_bukti' ";
            $response["daftar"]=dbResultArray($sql);

            // $response['get']=$_GET; 
            // $response['sql']=$sql; 
            $sql="select a.nu, a.kode_barang,isnull(b.nilai_beli,0) as hrg_seb,a.satuan,a.jumlah,a.harga,a.diskon,a.total as subtotal,b.nama 
            from brg_trans_d  a 
            left join brg_barang b on a.kode_barang=b.kode_barang and a.kode_lokasi=b.kode_lokasi
            where  a.form='BRGBELI' and a.kode_lokasi='$kode_lokasi' and a.no_bukti='$no_bukti' ";
            
            // $response['sql2']=$sql;
            $response["daftar2"]=dbResultArray($sql);

            $response['status'] = TRUE;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        echo json_encode($response);
    }

    function hapusBeli(){
        session_start();
        getKoneksi();
        if($_COOKIE['isLogedIn'] AND cekAuth($_COOKIE['userLog'])){
            $kode_lokasi = $_POST['kode_lokasi'];
            $no_bukti = $_POST['id'];
            $nik = $_POST['nik'];

            $exec=array();
            $del=" delete from trans_m where no_bukti='$no_bukti' and kode_lokasi='$kode_lokasi' and nik_user='$nik' and form='BRGBELI' ";
            
            array_push($exec,$del);
            
            $del2=" delete from trans_j where no_bukti='$no_bukti' and kode_lokasi='$kode_lokasi' and nik_user='$nik' and modul='BRGBELI' ";
            
            array_push($exec,$del2);
            
            $del3=" delete from brg_belihut_d where no_beli='$no_bukti' and kode_lokasi='$kode_lokasi' and nik_user='$nik' and modul='BELI' ";
            
            array_push($exec,$del3);
            
            $del4=" delete from brg_trans_d where no_bukti='$no_bukti' and kode_lokasi='$kode_lokasi' and form='BRGBELI' ";

            array_push($exec,$del4);

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

            $response['status'] =$sts;
            $response['message'] =$msg;
            // $response['exec'] =$exec;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        echo json_encode($response);
    }

    function update(){
        session_start();
        getKoneksi();
        if($_COOKIE['isLogedIn'] AND cekAuth($_COOKIE['userLog'])){
            
            $kode_lokasi=$_POST['kode_lokasi'];    
            $nik=$_POST['nik_user'];
            $kode_pp=$_POST['kode_pp'];
            
            $exec = array();

            $id = $_POST['no_beli'];
            $ceksql="select * from trans_m where isnull(id_sync,'-') ='-' and no_bukti='$id' and kode_lokasi='$kode_lokasi' ";
            $cek = execute($ceksql);
            if($cek->RecordCount() > 0){

                $del = "delete from trans_m where form='BRGBELI' and kode_lokasi='$kode_lokasi' and no_bukti='$id' ";
                array_push($exec,$del);
    
                $del2 = "delete from trans_j where modul='BRGBELI' and kode_lokasi='$kode_lokasi' and no_bukti='$id' ";
                array_push($exec,$del2);
    
                $del3 = "delete from brg_belihut_d where modul='BELI' and kode_lokasi='$kode_lokasi' and no_beli='$id' ";
                array_push($exec,$del3);
    
                $del4 = "delete from brg_trans_d where modul='BRGBELI' and kode_lokasi='$kode_lokasi' and no_bukti='$id' ";
                array_push($exec,$del4);
    
    
                $sql="select kode_spro,flag from spro where kode_spro in ('PPNM','BELIDIS') and kode_lokasi = '".$kode_lokasi."'";
    
                $rs=execute($sql);
                while ($row = $rs->FetchNextObject(false)){
                    if ($row->kode_spro == "PPNM") $akunPPN=$row->flag;
                    if ($row->kode_spro == "BELIDIS") $akunDiskon=$row->flag;
                }
    
                $sql3 = "select akun_hutang from vendor where kode_vendor ='".$_POST["kode_vendor"]."' and kode_lokasi = '".$kode_lokasi."'";
                $res=execute($sql3);			
                if ($res->RecordCount() > 0){
                    $akunHutang = $res->fields[0];									
                }	
    
                $sqlg="select top 1 a.kode_gudang from brg_gudang a where a.kode_lokasi='$kode_lokasi' ";
                $rsg=execute($sqlg);
                if($rsg->RecordCount() > 0){
                    $rowg = $rsg->FetchNextObject(false);
                    $kodeGudang=$rowg->kode_gudang;
                }else{
                    $kodeGudang="-";
                }
    
                $sqlm = "insert into trans_m (no_bukti,kode_lokasi,tgl_input,nik_user,periode,modul,form,posted,prog_seb,progress,kode_pp,tanggal,no_dokumen,keterangan,kode_curr,kurs,nilai1,nilai2,nilai3,nik1,nik2,nik3,no_ref1,no_ref2,no_ref3,param1,param2,param3) values 
                        ('".$id."','".$kode_lokasi."',getdate(),'".$_POST['nik_user']."','".$periode."','IV','BRGBELI','F','-','-','".$kode_pp."',getdate(),'".$_POST['no_faktur']."','Pembelian Persediaan','IDR',1,".joinNum2($_POST['total_stlh']).",".joinNum2($_POST['total_ppn']).",".joinNum2($_POST['total_disk']).",'-','-','-','-','-','-','-','".$_POST['kode_vendor']."','".$akunHutang."')";
                array_push($exec,$sqlm);
                        
                $sqlb = "insert into brg_belihut_d(no_beli,kode_lokasi,tanggal,keterangan,kode_vendor,kode_curr,kurs,kode_pp,nilai,periode,nik_user,tgl_input,akun_hutang,nilai_ppn,no_fp,due_date, nilai_pph, diskon, modul,kode_gudang) values  
                        ('".$id."','".$kode_lokasi."',getdate(), 'Pembelian Persediaan','".$_POST['kode_vendor']."','IDR',1,'".$kode_pp."',".joinNum2($_POST['total_stlh']).",'".$periode."','".$nik."',getdate(),'".$akunHutang."',".joinNum2($_POST['total_ppn']).",'-',getdate(),0,".joinNum2($_POST['total_disk']).", 'BELI','$kodeGudang')";
                
                array_push($exec,$sqlb);
                            
                $series = array();
                $series2 = array();
                $group = array();
                $nilai = 0;
                $diskItem = 0;
                $total=0;
                for($b=0; $b<count($_POST['kode_barang']);$b++){
                    
                    // $nilai = joinNum2($_POST['harga_barang'][$b])*joinNum2($_POST['qty_barang'][$b]);
                    $nilai = joinNum2($_POST['sub_barang'][$b]);
                    $isAda = false;
                    $idx = 0;
                    
                    $akun = $_POST['kode_akun'][$b];						
                    for ($c=0;$c <= $b;$c++){
                        if ($akun == $_POST['kode_akun'][$c-1]) {
                            $isAda = true;
                            $idx = $c;
                            break;
                        }
                    }
                    if (!$isAda) {							
                        array_push($series,$_POST['kode_akun'][$b]);
                        
                        $series2[$_POST['kode_akun'][$b]]=$nilai;
                    } 
                    else { 
                        $total = $series2[$_POST['kode_akun'][$b]];
                        $total = $total + $nilai;
                        $series2[$_POST['kode_akun'][$b]]=$total;
                    }		
                        
                    $diskItem+=$_POST['disc_barang'][$b];
                }
                for($x=0; $x<count($series);$x++){
                    
                    $sql5[$x]="insert into trans_j (no_bukti,kode_lokasi,tgl_input,nik_user,periode,no_dokumen,tanggal,nu,kode_akun,dc,nilai,nilai_curr,keterangan,modul,jenis,kode_curr,kurs,kode_pp,kode_drk,kode_cust,kode_vendor,no_fa,no_selesai,no_ref1,no_ref2,no_ref3) values 
                        ('".$id."','".$kode_lokasi."',getdate(),'".$nik."','".$periode."','-',getdate(),".$x.",'".$_POST['kode_akun'][$x]."','D',".joinNum2($series2[$series[$x]]).",".joinNum2($series2[$series[$x]]).",'Persediaan Barang','BRGBELI','BRGBELI','IDR',1,'$kode_pp','-','-','-','-','-','-','-','-')";
                        
                    array_push($exec,$sql5[$x]);
                        
                }
                
                $totDiskon = joinNum2($_POST['total_disk']) +$diskItem;
                if (joinNum2($_POST['total_ppn']) > 0) {
                    $x=$x+1;
                    $sql6="insert into trans_j (no_bukti,kode_lokasi,tgl_input,nik_user,periode,no_dokumen,tanggal,nu,kode_akun,dc,nilai,nilai_curr,keterangan,modul,jenis,kode_curr,kurs,kode_pp,kode_drk,kode_cust,kode_vendor,no_fa,no_selesai,no_ref1,no_ref2,no_ref3) values 
                        ('".$id."','".$kode_lokasi."',getdate(),'".$nik."','".$periode."','-',getdate(),".$x.",'".$akunPPN."','D',".joinNum2($_POST['total_ppn']).",".joinNum2($_POST['total_ppn']).",'PPN Masukan','BRGBELI','PPNM','IDR',1,'$kode_pp','-','-','-','-','-','-','-','-')";
                        
                    array_push($exec,$sql6);
                }
    
                if (joinNum2($_POST['total_stlh']) > 0) {
                    $x=$x+1;
                    $sql7="insert into trans_j (no_bukti,kode_lokasi,tgl_input,nik_user,periode,no_dokumen,tanggal,nu,kode_akun,dc,nilai,nilai_curr,keterangan,modul,jenis,kode_curr,kurs,kode_pp,kode_drk,kode_cust,kode_vendor,no_fa,no_selesai,no_ref1,no_ref2,no_ref3) values 
                        ('".$id."','".$kode_lokasi."',getdate(),'".$nik."','".$periode."','-',getdate(),".$x.",'".$akunHutang."','C',".joinNum2($_POST['total_stlh']).",".joinNum2($_POST['total_stlh']).",'Hutang Vendor Pembelian','BRGBELI','BELIDISC','IDR',1,'$kode_pp','-','-','-','-','-','-','-','-')";
                        
                    array_push($exec,$sql7);
                }
                
                if (joinNum2($_POST['total_disk']) > 0) {
                    $x=$x+1;
                    $sql7="insert into trans_j (no_bukti,kode_lokasi,tgl_input,nik_user,periode,no_dokumen,tanggal,nu,kode_akun,dc,nilai,nilai_curr,keterangan,modul,jenis,kode_curr,kurs,kode_pp,kode_drk,kode_cust,kode_vendor,no_fa,no_selesai,no_ref1,no_ref2,no_ref3) values 
                        ('".$id."','".$kode_lokasi."',getdate(),'".$nik."','".$periode."','-',getdate(),".$x.",'".$akunDiskon."','C',".joinNum2($totDiskon).",".joinNum2($totDiskon).",'Diskon Pembelian','BRGBELI','BELIDISC','IDR',1,'$kode_pp','-','-','-','-','-','-','-','-')";
                        
                    array_push($exec,$sql7);
                }
                
                for($a=0; $a<count($_POST['kode_barang']);$a++){
    
                    $sql4[$a]="insert into brg_trans_d (no_bukti,kode_lokasi,periode,modul,form,nu,kode_gudang,kode_barang,no_batch,tgl_ed,satuan,dc,stok,jumlah,bonus,harga,hpp,p_disk,diskon,tot_diskon,total) values 
                            ('".$id."','".$kode_lokasi."','".$periode."','BRGBELI','BRGBELI',".$a.",'$kodeGudang','".$_POST['kode_barang'][$a]."','-',getdate(),'".$_POST['satuan_barang'][$a]."','D',0,".joinNum2($_POST['qty_barang'][$a]).",0,".joinNum2($_POST['harga_barang'][$a]).",0,0,".$diskItem.",".joinNum2($_POST['disc_barang'][$a]).",".joinNum2($_POST['sub_barang'][$a]).")";
    
                    array_push($exec,$sql4[$a]);
    
                    $sql5[$a]="update brg_barang set nilai_beli =".joinNum2($_POST['harga_barang'][$a])." where kode_barang ='".$_POST['kode_barang'][$a]."'  and kode_lokasi='$kode_lokasi' ";
                    array_push($exec,$sql5[$a]);
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
                $response["message"] =$tmp;
                $response["status"] = $sts;
                $response["no_bukti"]= $id;
            }else{
                $response["message"] ="No Pembelian = ".$id." tidak dapat diedit karena sudah disyncronize ";
                $response["status"] = false;
                $response["no_bukti"] = $id;
            }
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        echo json_encode($response);
    }

    
?>
