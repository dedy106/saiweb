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

     
    function generateKode($tabel, $kolom_acuan, $prefix, $str_format){
        $query = execute("select right(max($kolom_acuan), ".strlen($str_format).")+1 as id from $tabel where $kolom_acuan like '$prefix%'");
        $kode = $query->fields[0];
        $id = $prefix.str_pad($kode, strlen($str_format), $str_format, STR_PAD_LEFT);
        return $id;
    }


    function joinNum2($num){
        // menggabungkan angka yang di-separate(10.000,75) menjadi 10000.00
        $num = str_replace(".", "", $num);
        $num = str_replace(",", ".", $num);
        return $num;
    }

    function getRekBank(){
        
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $kode_lokasi = $_GET['kode_lokasi'];
            
            $sql="select a.kode_akun, a.nama
            from masakun a inner join flag_relasi b on a.kode_akun=b.kode_akun and a.kode_lokasi=b.kode_lokasi
            where a.kode_lokasi='$kode_lokasi' and b.kode_flag in ('057') ";
            
            $response["daftar"]=dbResultArray($sql);
            $response['status'] = TRUE;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }

    function getNew(){
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){         
            $data = $_POST;
            
            $query = '';
            $output = array();
            
            $kode_lokasi = $_SESSION['lokasi'];
            $nik = $_SESSION['userLog'];
            $query .= "select a.no_reg,a.no_peserta,b.nama,a.tgl_input,f.nama as nama_paket,e.tgl_berangkat,case when ( ((a.harga+harga_room) > isnull(c.bayar_paket,0)+a.diskon) or (isnull(d.tot_tambahan,0) > isnull(c.bayar_tambahan,0) ) ) then '-' else 'Lunas' end as status
            from dgw_reg a 
            inner join dgw_peserta b on a.no_peserta=b.no_peserta and a.kode_lokasi=b.kode_lokasi 
            
            inner join dgw_paket f on a.no_paket=f.no_paket and a.kode_lokasi=f.kode_lokasi 
            left join (select no_reg,sum(nilai) as tot_tambahan from dgw_reg_biaya 
                        where nilai <> 0 and kode_lokasi='$kode_lokasi' group by no_reg
                        ) d on a.no_reg=d.no_reg 
            left join (select no_reg,sum(nilai_p) as bayar_paket,sum(nilai_t+nilai_m) as bayar_tambahan
                        from dgw_pembayaran 
                        where kode_lokasi='$kode_lokasi' group by no_reg 
                        ) c on a.no_reg=c.no_reg 
            inner join dgw_jadwal e on a.no_paket=e.no_paket and a.no_jadwal=e.no_jadwal and a.kode_lokasi=e.kode_lokasi
            where a.kode_lokasi='$kode_lokasi'";
            
            $column_array = array('a.no_reg','a.no_peserta','b.nama','a.tgl_input','f.nama','e.tgl_berangkat');
            $order_column = 'order by a.no_reg '.$data['order'][0]['dir'];
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
                $query .= ' order by a.no_reg ';
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
                $sub_array[] = $row->no_reg;
                $sub_array[] = $row->no_peserta;
                $sub_array[] = $row->nama;
                $sub_array[] = $row->tgl_input;
                $sub_array[] = $row->nama_paket;
                $sub_array[] = $row->tgl_berangkat;
                $sub_array[] = "<a href='#' title='Edit' class='badge badge-info web_datatable_bayar' ><i class='fas fa-pencil-alt'></i>&nbsp; Bayar</a>";
                if($row->status == "-"){
                    $sub_array[] = "";
                }else{
                    $sub_array[] = "<a href='#' title='Sudah Lunas' class='badge badge-success' ><i class='fas fa-check'></i> Lunas</a>";
                }
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
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){         
            $data = $_POST;
            
            $query = '';
            $output = array();
            
            $kode_lokasi = $_SESSION['lokasi'];
            $nik = $_SESSION['userLog'];
            
            $query.="select a.no_kwitansi, a.tgl_bayar, a.no_reg, a.paket, a.jadwal, round(a.nilai_p,4) as nilai_p, a.nilai_t, (a.nilai_p * a.kurs) + a.nilai_t as total_idr 
            from dgw_pembayaran a inner join trans_m b on a.no_kwitansi=b.no_bukti and a.kode_lokasi=b.kode_lokasi
            where b.kode_lokasi='".$kode_lokasi."' and b.posted='F' and b.form='KBREG' ";
            
            $column_array = array('a.no_kwitansi', 'a.tgl_bayar', 'a.no_reg', 'a.paket', 'a.jadwal', 'round(a.nilai_p,4)' , 'a.nilai_t', '(a.nilai_p * a.kurs) + a.nilai_t');
            $order_column = 'order by a.no_kwitansi '.$data['order'][0]['dir'];
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
                $query .= ' order by a.no_kwitansi ';
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
                $sub_array[] = $row->no_kwitansi;
                $sub_array[] = $row->tgl_bayar;
                $sub_array[] = $row->no_reg;
                $sub_array[] = $row->paket;
                $sub_array[] = $row->jadwal;
                $sub_array[] = $row->nilai_p;
                $sub_array[] = $row->nilai_t;
                $sub_array[] = $row->total_idr;
                if($_SESSION['userStatus'] == "U"){
                    $sub_array[] = "";
                }else{
                    $sub_array[] = "<a href='#' title='Edit' class='badge badge-info' id='btn-edit'><i class='fas fa-pencil-alt'></i></a> &nbsp; <a href='#' title='Hapus' class='badge badge-danger' id='btn-delete'><i class='fa fa-trash'></i></a>";
                }
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

    function getBayar(){
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){

            $id=$_GET['kode'];    
            $kode_lokasi=$_GET['kode_lokasi'];  
            $no_bukti=$_GET['no_bukti'];
            $response = array("message" => "", "rows" => 0, "status" => "" );
            if($no_bukti == ""){
                $no_bukti = generateKode("trans_m", "no_bukti", $kode_lokasi.'-BM'.date('ym'), "0001");
            }  else {
                $no_bukti = $no_bukti;
                $sql = "select no_bukti,keterangan,param1 as kode_akun 
                from trans_m 
                where no_bukti='$no_bukti' and kode_lokasi='$kode_lokasi' and no_ref1='".$id."'";
                $response['daftar4'] = dbResultArray($sql);
            }


            $sql = "select b.no_reg,a.nama,d.tgl_berangkat,e.nama as paket,e.kode_curr,b.harga + b.harga_room as harga_tot,  case when d.no_closing ='-' then f.kode_akun else f.akun_piutang end as kode_akun,d.kurs_closing, d.no_closing, f.akun_piutang, b.diskon 
            from dgw_peserta a 
                inner join dgw_reg b on a.no_peserta=b.no_peserta and a.kode_lokasi=b.kode_lokasi 
                inner join dgw_jadwal d on b.no_paket=d.no_paket and b.no_jadwal=d.no_jadwal and b.kode_lokasi=d.kode_lokasi 
                inner join dgw_paket e on b.no_paket=e.no_paket and b.kode_lokasi=e.kode_lokasi 
                inner join dgw_jenis_produk f on e.kode_produk=f.kode_produk and e.kode_lokasi=f.kode_lokasi
            where b.no_reg='".$id."' and b.kode_lokasi='".$kode_lokasi."'";
          
            $response['daftar'] = dbResultArray($sql);

            $sql2= "select isnull(sum(nilai_p),0) as paket, isnull(sum(nilai_t),0) as tambahan, isnull(sum(nilai_m),0) as dokumen
            from dgw_pembayaran 
            where no_reg='".$id."' and kode_lokasi='".$kode_lokasi."' and no_kwitansi <>'".$no_bukti."'";
            
            $response['daftar2'] = dbResultArray($sql2);

            $sql3 = "select a.kode_biaya, a.tarif, a.nilai, isnull(c.byr,0) as byr,a.nilai-isnull(c.byr,0) as saldo,a.jml, b.nama, 'IDR' as curr, b.jenis,b.akun_pdpt 
            from dgw_reg_biaya a 
            inner join dgw_biaya b on a.kode_biaya=b.kode_biaya and a.kode_lokasi=b.kode_lokasi 
            left join ( select a.no_reg,a.kode_biaya,a.kode_lokasi,sum(nilai) as byr 
                        from dgw_pembayaran_d a 
                        where a.no_kwitansi <>'".$no_bukti."'
                        group by a.no_reg,a.kode_biaya,a.kode_lokasi ) c on a.kode_biaya=c.kode_biaya and a.kode_lokasi=c.kode_lokasi 
                        and a.no_reg=c.no_reg 
            where a.nilai <> 0 and a.no_reg='$id' and a.kode_lokasi='$kode_lokasi' 
            union all 
            select 'ROOM' as kode_biaya, a.harga_room as tarif, a.harga_room as nilai,isnull(c.byr,0) as byr,a.harga_room-isnull(c.byr,0) as saldo, 
                    1 as jml, 'ROOM' as nama, 'USD' as curr, '-' as jenis,'-' as akun_pdpt 
            from dgw_reg a 
            left join ( select a.no_reg,a.kode_lokasi,sum(nilai) as byr 
                        from dgw_pembayaran_d a 
                        where a.kode_biaya ='ROOM' and a.no_kwitansi <>'".$no_bukti."'
                        group by a.no_reg,a.kode_lokasi ) c on a.kode_lokasi=c.kode_lokasi 
                        and a.no_reg=c.no_reg 
            where a.harga_room <> 0 and a.no_reg='$id' and a.kode_lokasi='$kode_lokasi' 
            union all 
            select 'PAKET' as kode_biaya, a.harga-isnull(a.diskon,0) as tarif, a.harga-isnull(a.diskon,0) as nilai,isnull(c.byr,0) as byr,a.harga-isnull(a.diskon,0)-isnull(c.byr,0) as saldo, 1 as jml, 'PAKET' as nama, 'USD' as curr, '-' as jenis,'-' as akun_pdpt 
            from dgw_reg a 
            left join ( select a.no_reg,a.kode_lokasi,sum(nilai) as byr 
                        from dgw_pembayaran_d a 
                        where a.kode_biaya = 'PAKET' and a.no_kwitansi <>'".$no_bukti."'
                        group by a.no_reg,a.kode_lokasi ) c on a.kode_lokasi=c.kode_lokasi 
                        and a.no_reg=c.no_reg 
            where a.harga <> 0 and a.no_reg='$id' and a.kode_lokasi='$kode_lokasi' 
           
            order by curr desc";
            $daftar = dbResultArray($sql3);
            $response['daftar3']=$daftar;
            // $response['sql3']=$sql3;
            $totTambah = $totDok = 0;

            if (count($daftar) > 0){
                for($i=0;$i<count($daftar);$i++){
                    if ($daftar[$i]['jenis'] == "TAMBAHAN") $totTambah += floatval($daftar[$i]['nilai']);
                    if ($daftar[$i]['jenis'] == "DOKUMEN") $totDok += floatval($daftar[$i]['nilai']);	
                }
            } 
            $response['totTambah']=$totTambah;
            $response['totDok']=$totDok;

            $sql4 = " select a.no_kwitansi, a.tgl_bayar, a.no_reg, a.paket, a.jadwal, round(a.nilai_p,4) as nilai_p, a.nilai_t,nilai_m, (a.nilai_p * a.kurs) + a.nilai_t+a.nilai_m as total_idr 
            from dgw_pembayaran a 
            inner join trans_m b on a.no_kwitansi=b.no_bukti and a.kode_lokasi=b.kode_lokasi
            where b.kode_lokasi='".$kode_lokasi."' and a.no_reg='$id' and b.posted='F' and b.form='KBREG' ";

            $response['daftar4'] = dbResultArray($sql4);
            $response['status'] = TRUE;
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

            $id=$_GET['kode'];    
            $kode_lokasi=$_GET['kode_lokasi'];  
            $no_bukti=$_GET['no_bukti'];
            $response = array("message" => "", "rows" => 0, "status" => "" );
            if($no_bukti == ""){
                $no_bukti = generateKode("trans_m", "no_bukti", $kode_lokasi.'-BM'.date('ym'), "0001");
            }  else {
                $no_bukti = $no_bukti;
                $sql = "select no_bukti,keterangan,param1 as kode_akun 
                from trans_m 
                where no_bukti='$no_bukti' and kode_lokasi='$kode_lokasi' and no_ref1='".$id."'";
                $response['daftar4'] = dbResultArray($sql);
            }


            $sql = "select b.no_reg,a.nama,d.tgl_berangkat,e.nama as paket,e.kode_curr,b.harga + b.harga_room as harga_tot,  case when d.no_closing ='-' then f.kode_akun else f.akun_piutang end as kode_akun,d.kurs_closing, d.no_closing, f.akun_piutang, b.diskon 
            from dgw_peserta a 
                inner join dgw_reg b on a.no_peserta=b.no_peserta and a.kode_lokasi=b.kode_lokasi 
                inner join dgw_jadwal d on b.no_paket=d.no_paket and b.no_jadwal=d.no_jadwal and b.kode_lokasi=d.kode_lokasi 
                inner join dgw_paket e on b.no_paket=e.no_paket and b.kode_lokasi=e.kode_lokasi 
                inner join dgw_jenis_produk f on e.kode_produk=f.kode_produk and e.kode_lokasi=f.kode_lokasi
            where b.no_reg='".$id."' and b.kode_lokasi='".$kode_lokasi."'";
          
            $response['daftar'] = dbResultArray($sql);

            $sql2= "select isnull(sum(nilai_p),0) as paket, isnull(sum(nilai_t),0) as tambahan, isnull(sum(nilai_m),0) as dokumen
            from dgw_pembayaran 
            where no_reg='".$id."' and kode_lokasi='".$kode_lokasi."' and no_kwitansi ='".$no_bukti."'";
            
            $response['daftar2'] = dbResultArray($sql2);

            $sql3 = "select a.kode_biaya, a.tarif, a.nilai, isnull(c.byr,0) as byr_e,isnull(d.byr,0) as byr,a.nilai-isnull(c.byr,0)-isnull(d.byr,0) as saldo,a.jml, b.nama, 'IDR' as curr, b.jenis,b.akun_pdpt 
            from dgw_reg_biaya a 
            inner join dgw_biaya b on a.kode_biaya=b.kode_biaya and a.kode_lokasi=b.kode_lokasi 
            left join ( select a.no_reg,a.kode_biaya,a.kode_lokasi,sum(nilai) as byr 
                        from dgw_pembayaran_d a 
                        where a.no_kwitansi = '$no_bukti'
                        group by a.no_reg,a.kode_biaya,a.kode_lokasi ) c on a.kode_biaya=c.kode_biaya and a.kode_lokasi=c.kode_lokasi 
                        and a.no_reg=c.no_reg 
			 left join ( select a.no_reg,a.kode_biaya,a.kode_lokasi,sum(nilai) as byr 
                        from dgw_pembayaran_d a 
                        where a.no_kwitansi <> '$no_bukti'
                        group by a.no_reg,a.kode_biaya,a.kode_lokasi ) d on a.kode_biaya=d.kode_biaya and a.kode_lokasi=d.kode_lokasi 
                        and a.no_reg=d.no_reg 
            where a.nilai <> 0 and a.no_reg='$id' and a.kode_lokasi='$kode_lokasi' 
            union all 
            select 'ROOM' as kode_biaya, a.harga_room as tarif, a.harga_room as nilai,isnull(c.byr,0) as byr_e,isnull(d.byr,0) as byr,a.harga_room-isnull(c.byr,0)-isnull(d.byr,0) as saldo, 
                    1 as jml, 'ROOM' as nama, 'USD' as curr, '-' as jenis,'-' as akun_pdpt 
            from dgw_reg a 
            left join ( select a.no_reg,a.kode_lokasi,sum(nilai) as byr 
                        from dgw_pembayaran_d a 
                        where a.kode_biaya ='ROOM' and a.no_kwitansi = '$no_bukti'
                        group by a.no_reg,a.kode_lokasi ) c on a.kode_lokasi=c.kode_lokasi 
                        and a.no_reg=c.no_reg 
			left join ( select a.no_reg,a.kode_lokasi,sum(nilai) as byr 
                        from dgw_pembayaran_d a 
                        where a.kode_biaya ='ROOM' and a.no_kwitansi <> '$no_bukti'
                        group by a.no_reg,a.kode_lokasi ) d on a.kode_lokasi=d.kode_lokasi 
                        and a.no_reg=d.no_reg 
            where a.harga_room <> 0 and a.no_reg='$id' and a.kode_lokasi='$kode_lokasi' 
            union all 
            select 'PAKET' as kode_biaya, a.harga-a.diskon as tarif, a.harga-a.diskon as nilai,isnull(c.byr,0) as byr_e,isnull(d.byr,0) as byr,a.harga-isnull(c.byr,0)-isnull(d.byr,0)-a.diskon as saldo, 1 as jml, 
                    'PAKET' as nama, 'USD' as curr, '-' as jenis,'-' as akun_pdpt 
            from dgw_reg a 
            left join ( select a.no_reg,a.kode_lokasi,sum(nilai) as byr 
                        from dgw_pembayaran_d a 
                        where a.kode_biaya = 'PAKET' and a.no_kwitansi = '$no_bukti'
                        group by a.no_reg,a.kode_lokasi ) c on a.kode_lokasi=c.kode_lokasi 
                        and a.no_reg=c.no_reg 
			left join ( select a.no_reg,a.kode_lokasi,sum(nilai) as byr 
                        from dgw_pembayaran_d a 
                        where a.kode_biaya = 'PAKET' and a.no_kwitansi <> '$no_bukti'
                        group by a.no_reg,a.kode_lokasi ) d on a.kode_lokasi=d.kode_lokasi 
                        and a.no_reg=d.no_reg 
            where a.harga <> 0 and a.no_reg='$id' and a.kode_lokasi='$kode_lokasi' 
            
            order by curr desc";
            $daftar = dbResultArray($sql3);
            $response['daftar3']=$daftar;
            // $response['sql3']=$sql3;
            $totTambah = $totDok = 0;

            if (count($daftar) > 0){
                for($i=0;$i<count($daftar);$i++){
                    if ($daftar[$i]['jenis'] == "TAMBAHAN") $totTambah += floatval($daftar[$i]['nilai']);
                    if ($daftar[$i]['jenis'] == "DOKUMEN") $totDok += floatval($daftar[$i]['nilai']);	
                }
            } 
            $response['totTambah']=$totTambah;
            $response['totDok']=$totDok;
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
            $data = $_POST;
            $exec=array();
            $periode = substr($data['tanggal'],0,4).substr($data['tanggal'],5,2);
            $d = dbResultArray("select kode_spro,flag from spro where kode_spro in ('LKURS','RKURS','AKUNT','AKUND','AKUNOI','AKUNOE') and kode_lokasi = '".$data['kode_lokasi']."'");			
			if (count($d)>0){
				
				for ($i;$i<count($d);$i++){
					$line = $d[$i];	
					// if ($line['kode_spro'] == "LKURS") $lKurs = $line['flag'];
					// if ($line['kode_spro'] == "RKURS") $rKurs = $line['flag'];
					// if ($line['kode_spro'] == "AKUNT") $akunTambah = $line['flag'];
					// if ($line['kode_spro'] == "AKUND") $akunDokumen = $line['flag'];
					if ($line['kode_spro'] == "AKUNOI") $akunOI = $line['flag'];
					if ($line['kode_spro'] == "AKUNOE") $akunOE = $line['flag'];
				}
            }	
            $no_bukti = $data['no_bukti'];
            
            if ( $no_bukti != "") {
                $del = "delete from trans_m where no_bukti='".$no_bukti."' and kode_lokasi ='".$data['kode_lokasi']."' ";
                $del2 = "delete from trans_j where no_bukti='".$no_bukti."' and kode_lokasi ='".$data['kode_lokasi']."' ";
                $del3 = "delete from dgw_pembayaran where no_kwitansi='".$no_bukti."' and kode_lokasi ='".$data['kode_lokasi']."' ";
                $del4 = "delete from dgw_pembayaran_d where no_kwitansi='".$no_bukti."' and kode_lokasi ='".$data['kode_lokasi']."' ";
                array_push($exec,$del);
                array_push($exec,$del2);
                array_push($exec,$del3);
                array_push($exec,$del4);
            }else{
                $no_bukti = generateKode("trans_m", "no_bukti", $data['kode_lokasi'].'-BM'.substr($periode,2,4).".", "0001");
            }
            $bayarPaketIDR = joinNum2($data['bayar_paket']);

            $ins = "insert into trans_m (no_bukti,kode_lokasi,tgl_input,nik_user,periode,modul,form,posted,prog_seb,progress,kode_pp,tanggal,no_dokumen,keterangan,kode_curr,kurs,nilai1,nilai2,nilai3,nik1,nik2,nik3,no_ref1,no_ref2,no_ref3,param1,param2,param3) values
                ('".$no_bukti."','".$data['kode_lokasi']."',getdate(),'".$data['nik_user']."','".$periode."','KB','KBREG','F','-','-','".$data['kode_pp']."','".$data['tanggal']."','".$data['no_reg']."','".$data['deskripsi']."','IDR',1,".joinNum($data['total_bayar']).",0,0,'-','-','-','".$data['no_reg']."','-','-','".$data['kode_akun']."','-','BM')";
            array_push($exec,$ins);
            

            $ins2 = "insert into trans_j (no_bukti,kode_lokasi,tgl_input,nik_user,periode,no_dokumen,tanggal,nu,kode_akun,dc,nilai,nilai_curr,keterangan,modul,jenis,kode_curr,kurs,kode_pp,kode_drk,kode_cust,kode_vendor,no_fa,no_selesai,no_ref1,no_ref2,no_ref3) values ('".$no_bukti."','".$data['kode_lokasi']."',getdate(),'".$data['nik_user']."','".$periode."','".$data['no_reg']."','".$data['tanggal']."',0,'".$data['kode_akun']."','D',".joinNum($data['total_bayar']).",".joinNum($data['total_bayar']).",'".$data['deskripsi']."','KB','KB','IDR',1,'".$data['kode_pp']."','-','-','-','-','-','-','-','-')";
            array_push($exec,$ins2);
                    
            $ins3 = "insert into trans_j (no_bukti,kode_lokasi,tgl_input,nik_user,periode,no_dokumen,tanggal,nu,kode_akun,dc,nilai,nilai_curr,keterangan,modul,jenis,kode_curr,kurs,kode_pp,kode_drk,kode_cust,kode_vendor,no_fa,no_selesai,no_ref1,no_ref2,no_ref3) values 
            ('".$no_bukti."','".$data['kode_lokasi']."',getdate(),'".$data['nik_user']."','".$periode."','".$data['no_reg']."','".$data['tanggal']."',1,'".$data['akunTitip']."','C',".$bayarPaketIDR.",".joinNum($data['bayar_paket']).",'".$data['deskripsi']."','KB','TTPPAKET','IDR',1,'".$data['kode_pp']."','-','-','-','-','-','-','-','-')";	
            array_push($exec,$ins3);									

            if (joinNum($data['bayar_tambahan']) != 0 || joinNum($data['bayar_dok']) != 0 || joinNum($data['bayar_paket']) != 0) {
               
                $nilai_t=0;$nilai_d=0;$total_t=0;$total_d=0;$ser_t=array();$ser2_t=array();$ser_d=array();$ser2_d=array();$tes=array();
                for($i=0; $i<count($_POST['kode_biaya']);$i++){
                
                    if($data['nbiaya_bayar'][$i] != 0){

                        if($data['jenis_biaya'][$i] == 'TAMBAHAN'){
                            $nilai_t = joinNum($data['nbiaya_bayar'][$i]);
                            array_push($tes,$nilai_t);
                            $isAda_t = false;
                            $idx_t = 0;
                            
                            $akun_t = $data['kode_akunbiaya'][$i];						
                            for ($c=0;$c <= $i;$c++){
                                if ($akun_t == $data['kode_akunbiaya'][$c-1]) {
                                    $isAda_t = true;
                                    $idx_t = $c;
                                    break;
                                }
                            }
                            if (!$isAda_t) {							
                                array_push($ser_t,$data['kode_akunbiaya'][$i]);
                                
                                $ser2_t[$data['kode_akunbiaya'][$i]]=$nilai_t;
                            } 
                            else { 
                                $total_t = $ser2_t[$data['kode_akunbiaya'][$i]];
                                $total_t = $total_t + $nilai_t;
                                $ser2_t[$data['kode_akunbiaya'][$i]]=$total_t;
                            }		
                        }else if($data['jenis_biaya'][$i] == 'DOKUMEN'){
                            $nilai_d = joinNum($data['nbiaya_bayar'][$i]);
                            $isAda_d = false;
                            $idx_d = 0;
                            
                            $akun_d = $data['kode_akunbiaya'][$i];						
                            for ($c=0;$c <= $i;$c++){
                                if ($akun_d == $data['kode_akunbiaya'][$c-1]) {
                                    $isAda_d = true;
                                    $idx_d = $c;
                                    break;
                                }
                            }
                            if (!$isAda_d) {							
                                array_push($ser_d,$data['kode_akunbiaya'][$i]);
                                
                                $ser2_d[$data['kode_akunbiaya'][$i]]=$nilai_d;
                            } 
                            else { 
                                $total_d = $ser2_d[$data['kode_akunbiaya'][$i]];
                                $total_d = $total_d + $nilai_d;
                                $ser2_d[$data['kode_akunbiaya'][$i]]=$total_d;
                            }
                        }
    
                        $insdet = "insert into dgw_pembayaran_d (no_kwitansi,kode_lokasi,no_reg,kode_biaya,jenis,nilai) values ('$no_bukti','".$data['kode_lokasi']."','".$data['no_reg']."','".$data['kode_biaya'][$i]."','".$data['jenis_biaya'][$i]."','".joinNum($data['nbiaya_bayar'][$i])."') ";
                        array_push($exec,$insdet);
                    } 
                }	
                $nu =2;
                for($x=0; $x<count($ser_t);$x++){
                        
                    $ins4 = "insert into trans_j (no_bukti,kode_lokasi,tgl_input,nik_user,periode,no_dokumen,tanggal,nu,kode_akun,dc,nilai,nilai_curr,keterangan,modul,jenis,kode_curr,kurs,kode_pp,kode_drk,kode_cust,kode_vendor,no_fa,no_selesai,no_ref1,no_ref2,no_ref3) values ('".$no_bukti."','".$data['kode_lokasi']."',getdate(),'".$data['nik_user']."','".$periode."','".$data['no_reg']."','".$data['tanggal']."',$nu,'".$ser_t[$x]."','C',".joinNum($ser2_t[$ser_t[$x]]).",".joinNum($ser2_t[$ser_t[$x]]).",'".$data['deskripsi']."','KB','PDTAMBAH','IDR',1,'".$data['kode_pp']."','-','-','-','-','-','-','-','-')";	
                    array_push($exec,$ins4);
                    $nu++;
                        
                }

                $nu =3;
                for($x=0; $x<count($ser_d);$x++){
                        
                    $ins5 ="insert into trans_j (no_bukti,kode_lokasi,tgl_input,nik_user,periode,no_dokumen,tanggal,nu,kode_akun,dc,nilai,nilai_curr,keterangan,modul,jenis,kode_curr,kurs,kode_pp,kode_drk,kode_cust,kode_vendor,no_fa,no_selesai,no_ref1,no_ref2,no_ref3) values ('".$no_bukti."','".$data['kode_lokasi']."',getdate(),'".$data['nik_user']."','".$periode."','".$data['no_reg']."','".$data['tanggal']."',$nu,'".$ser_d[$x]."','C',".joinNum($ser2_d[$ser_d[$x]]).",".joinNum($ser2_d[$ser_d[$x]]).",'".$data['deskripsi']."','KB','PDDOKUMEN','IDR',1,'".$data['kode_pp']."','-','-','-','-','-','-','-','-')";
                    array_push($exec,$ins5);										
                    $nu++;
                        
                }
            }		
            
            $insp = "insert into dgw_pembayaran (no_kwitansi,no_reg,jadwal,tgl_bayar,paket,sistem_bayar,kode_lokasi,periode,nilai_t,nilai_p,kode_curr,kurs,nilai_m) values  
            ('".$no_bukti."','".$data['no_reg']."','".$data['tgl_berangkat']."','".$data['tanggal']."','".$data['paket']."','".$data['status_bayar']."','".$data['kode_lokasi']."','".$periode."',".joinNum($data['bayar_tambahan']).",".joinNum($data['bayar_paket']).",'IDR',1,".joinNum($data['bayar_dok']).")";
            array_push($exec,$insp);
            
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
            $response["no_kwitansi"]=$no_bukti;
            $response["nik"]=$nik;
            $response["sql1"]=$exec;
            $response["ser_t"]=$ser_t;
            $response["ser2_t"]=$ser2_t;
            $response["tes"]=$tes;

        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }

    function getPrint(){
        
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $kode_lokasi = $_GET['kode_lokasi'];
            $no_bukti = $_GET['no_kwitansi'];

            // $sql="select count(a.no_kwitansi) as cicil,i.nilai1,a.kode_lokasi,a.no_kwitansi,a.kurs, c.nama as peserta,c.telp,CONVERT(varchar, a.tgl_bayar, 105) as tgl_bayar,a.jadwal,a.paket,a.sistem_bayar,a.nilai_t, 
            // d.nama as paket, b.no_agen,i.nik_user,e.nama_agen as agen,a.no_reg,d.kode_curr,a.nilai_p+a.nilai_t+a.nilai_m as biaya_paket, (a.nilai_p+a.nilai_t+a.nilai_m) - i.nilai1 as sisap,h.nama_marketing,b.harga+b.harga_room as harga_paket,j.nama as room,b.referal
            //         from dgw_pembayaran a 
            //         inner join dgw_reg b on a.no_reg=b.no_reg and a.kode_lokasi=b.kode_lokasi 				
            //         inner join dgw_peserta c on c.no_peserta=b.no_peserta and c.kode_lokasi=b.kode_lokasi 								
            //         inner join dgw_paket d on d.no_paket=b.no_paket and d.kode_lokasi=b.kode_lokasi 				
            //         inner join dgw_agent e on e.no_agen=b.no_agen and b.kode_lokasi=e.kode_lokasi 
            //         inner join dgw_marketing h on h.no_marketing=e.kode_marketing and h.kode_lokasi=e.kode_lokasi
            //         inner join dgw_typeroom j on b.no_type=j.no_type and b.kode_lokasi=j.kode_lokasi
            // inner join trans_m i on i.no_bukti=a.no_kwitansi and i.kode_lokasi=a.kode_lokasi
            // where a.kode_lokasi='$kode_lokasi' and a.no_kwitansi='$no_bukti' 
            // group by b.no_agen,i.nilai1,a.kode_lokasi,a.no_kwitansi,a.kurs, c.nama,c.telp,a.tgl_bayar,a.jadwal,a.paket,a.bayar_paket,a.sistem_bayar,a.nilai_t, 
            //     d.nama, e.nama_agen,i.nik_user,a.no_reg,d.kode_curr,a.nilai_p,a.nilai_t,a.saldo_t,nilai_t,saldo_p ,a.bayar_tambahan, h.nama_marketing,b.harga,b.harga_room,j.nama,b.referal,a.nilai_m
            //     order by a.no_kwitansi
            // ";
            $sql="select a.no_kwitansi, a.kurs,a.paket,b.no_type,c.nama as room,b.harga+b.harga_room-b.diskon as harga_paket,a.jadwal,h.nama_marketing,e.nama_agen,isnull(b.referal,'-') as referal,a.no_reg,i.biaya_tambah,j.paket+j.tambahan+j.dokumen as bayar_lain,n.cicil_ke as cicil_ke, (b.harga+b.harga_room-b.diskon)+i.biaya_tambah as biaya_paket,((b.harga+b.harga_room-b.diskon)+i.biaya_tambah)-(j.paket+j.tambahan+j.dokumen)+a.nilai_p+a.nilai_t+a.nilai_m as saldo, a.nilai_p+a.nilai_t+a.nilai_m as bayar,((b.harga+b.harga_room-b.diskon)+i.biaya_tambah)-(j.paket+j.tambahan+j.dokumen) as sisa,CONVERT(varchar, a.tgl_bayar, 105) as tgl_bayar,k.nama as peserta,l.kode_curr,m.nik_user
            from dgw_pembayaran a
            inner join dgw_reg b on a.no_reg=b.no_reg and a.kode_lokasi=b.kode_lokasi
            inner join dgw_typeroom c on b.no_type=c.no_type and b.kode_lokasi=c.kode_lokasi
            inner join dgw_agent e on b.no_agen=e.no_agen and b.kode_lokasi=e.kode_lokasi 
            inner join dgw_marketing h on b.no_marketing=h.no_marketing and b.kode_lokasi=h.kode_lokasi
            inner join dgw_peserta k on b.no_peserta=k.no_peserta and b.kode_lokasi=k.kode_lokasi 
            inner join dgw_paket l on b.no_paket=l.no_paket and b.kode_lokasi=l.kode_lokasi 
            inner join trans_m m on a.no_kwitansi=m.no_bukti and a.kode_lokasi=m.kode_lokasi				
            left join ( select no_reg,kode_lokasi,sum(jml*nilai) as biaya_tambah 
                        from dgw_reg_biaya 
                        group by no_reg,kode_lokasi ) i on b.no_reg=i.no_reg and b.kode_lokasi=i.kode_lokasi
            left join (select no_reg,kode_lokasi,isnull(sum(nilai_p),0) as paket, 
                        isnull(sum(nilai_t),0) as tambahan, isnull(sum(nilai_m),0) as dokumen
                        from dgw_pembayaran 
                        group by no_reg,kode_lokasi ) j on b.no_reg=j.no_reg and b.kode_lokasi=j.kode_lokasi
			left join (select no_reg,kode_lokasi,count(no_kwitansi) as cicil_ke
                        from dgw_pembayaran 
                        group by no_reg,kode_lokasi ) n on b.no_reg=n.no_reg and b.kode_lokasi=n.kode_lokasi
			where a.no_kwitansi='$no_bukti' and a.kode_lokasi='$kode_lokasi'
 ";
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

    
    function hapus(){
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            
            $exec = array();
            parse_str(file_get_contents('php://input'), $_DELETE);
            $data = $_DELETE;
            
            $no_reg = $data['no_kwitansi'];
            $kode_lokasi = $data['kode_lokasi'];
            $del = "delete from trans_m where no_bukti='".$no_bukti."' and kode_lokasi ='".$data['kode_lokasi']."' ";
            $del2 = "delete from trans_j where no_bukti='".$no_bukti."' and kode_lokasi ='".$data['kode_lokasi']."' ";
            $del3 = "delete from dgw_pembayaran where no_kwitansi='".$no_bukti."' and kode_lokasi ='".$data['kode_lokasi']."' ";
            $del4 = "delete from dgw_pembayaran_d where no_kwitansi='".$no_bukti."' and kode_lokasi ='".$data['kode_lokasi']."' ";
            array_push($exec,$del);
            array_push($exec,$del2);
            array_push($exec,$del3);
            array_push($exec,$del4);
            
            $rs=executeArray($exec,$err);
            if ($err == null)
            {	
                $tmp="sukses";
                $sts=true;
            }else{
                $tmp=$err;
                $sts=false;
            }	 

            $response["message"] =$tmp;
            $response["status"] = $sts;
            $response["sql"] = $exec;
        }else{
                
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }

?>
