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

    function getBTambah(){
        
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $kode_lokasi = $_GET['kode_lokasi'];
            
            $sql="select kode_biaya, nama, nilai from dgw_biaya where jenis = 'TAMBAHAN' and kode_lokasi='$kode_lokasi'";
            
            $response["daftar"]=dbResultArray($sql);
            $response['status'] = TRUE;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }

    function getBDok(){
        
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $kode_lokasi = $_GET['kode_lokasi'];
            
            $sql="select kode_biaya, nama, nilai from dgw_biaya where jenis = 'DOKUMEN' and kode_lokasi='$kode_lokasi'";
            
            $response["daftar"]=dbResultArray($sql);
            $response['status'] = TRUE;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }

    function getDokumen(){
        
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $kode_lokasi = $_GET['kode_lokasi'];
            
            $sql="select no_dokumen, deskripsi, jenis from dgw_dok where kode_lokasi='$kode_lokasi'";
            
            $response["daftar"]=dbResultArray($sql);
            $response['status'] = TRUE;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }

    function getAgen(){
        
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $kode_lokasi = $_GET['kode_lokasi'];
            
            $sql="select no_agen, nama_agen from dgw_agent where kode_lokasi='$kode_lokasi'";
            
            $response["daftar"]=dbResultArray($sql);
            $response['status'] = TRUE;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }

    function getMarketing(){
        
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $kode_lokasi = $_GET['kode_lokasi'];
            
            $sql="select no_marketing, nama_marketing from dgw_marketing where kode_lokasi='$kode_lokasi'";
            
            $response["daftar"]=dbResultArray($sql);
            $response['status'] = TRUE;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }

    function getPeserta(){
        
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $kode_lokasi = $_GET['kode_lokasi'];
            
            $sql="select no_peserta, nama from dgw_peserta where kode_lokasi='$kode_lokasi'";
            
            $response["daftar"]=dbResultArray($sql);
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
            
            $sql="select kode_pp, nama from pp where flag_aktif='1' and kode_lokasi = '$kode_lokasi'";
            
            $response["daftar"]=dbResultArray($sql);

            $sql="select kode_pp from karyawan_pp where nik='".$_SESSION['userLog']."' and kode_lokasi = '$kode_lokasi'";
            $res = dbRowArray($sql);
            $response["kodePP"]= $res['kode_pp'];

            $response['status'] = TRUE;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }

    function getRoom(){
        
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $kode_lokasi = $_GET['kode_lokasi'];
            
            $sql="select no_type,nama from dgw_typeroom where kode_lokasi = '$kode_lokasi'";
            
            $response["daftar"]=dbResultArray($sql);
            $response['status'] = TRUE;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }

    function getHarga(){
        
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $kode_lokasi = $_GET['kode_lokasi'];
            $data = $_GET;
            
            $strSQL="select harga,harga_se,harga_e from dgw_harga where no_paket ='".$data['no_paket']."' and kode_harga ='".$data['jpaket']."' and kode_lokasi='$kode_lokasi'";				
            $rs = execute($strSQL);
            if($rs->RecordCount()>0){
                if ($data['jenis'] == "STANDAR") $harga = $rs->fields[0];
                if ($data['jenis'] == "SEMI") $harga = $rs->fields[1];
                if ($data['jenis'] == "EKSEKUTIF") $harga = $rs->fields[2];						

            }
			$response['harga']= $harga;
            $response['status'] = TRUE;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }

    function getQuota(){
        
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $kode_lokasi = $_GET['kode_lokasi'];
            $data = $_GET;
            
            $strSQL="select a.tgl_berangkat,a.lama_hari,b.kode_curr 
                    from dgw_jadwal a 
                    inner join dgw_paket b on a.no_paket=b.no_paket and a.kode_lokasi=b.kode_lokasi 
                    where a.no_paket='".$data['no_paket']."' and a.no_jadwal='".$data['jadwal']."' and a.kode_lokasi='$kode_lokasi'";
            $rs = execute($strSQL);
            if($rs->RecordCount()>0){
                $tgl_berangkat = $rs->fields[0];
                $kode_curr = $rs->fields[2];
                $lama_hari = $rs->fields[1];
            }
            
            
            if ($data['jenis'] == "STANDAR") $strSQL="select quota as quota1 from dgw_jadwal where no_paket= '".$data['no_paket']."' and no_jadwal = '".$data['jadwal']."' and kode_lokasi='".$kode_lokasi."'";				
            if ($data['jenis'] == "SEMI") $strSQL="select quota_se as quota1 from dgw_jadwal where no_paket= '".$data['no_paket']."' and no_jadwal = '".$data['jadwal']."' and kode_lokasi='".$kode_lokasi."'";				
            if ($data['jenis'] == "EKSEKUTIF") $strSQL="select quota_e as quota1 from dgw_jadwal where no_paket= '".$data['no_paket']."' and no_jadwal = '".$data['jadwal']."' and kode_lokasi='".$kode_lokasi."'";	
            if($data['jenis'] == "") {
                $strSQL = "select quota+quota_se+quota_e as quota1 from dgw_jadwal where no_paket= '".$data['no_paket']."' and no_jadwal = '".$data['jadwal']."' and kode_lokasi='".$kode_lokasi."'";
                $filter_jenis = "";
            }else{
                $filter_jenis = " and jenis='".$data['jenis']."' ";
            }
            
            $rs2 = execute($strSQL);
            if($rs2->RecordCount()>0){
                $quota1 = $rs2->fields[0];
            }

            $strSQL="select COUNT(*) as jumlah from dgw_reg where no_paket= '".$data['no_paket']."' and no_jadwal= '".$data['jadwal']."' and kode_lokasi='".$kode_lokasi."' $filter_jenis  ";				
            $rs3=execute($strSQL);
            if($rs3->RecordCount()>0){
                $jumlah = $rs3->fields[0];
            }

            $quota = $quota1-$jumlah;
            
			$response['tgl_berangkat']= $tgl_berangkat;
			$response['kode_curr']= $kode_curr;
			$response['lama_hari']= $lama_hari;
			$response['quota']= $quota;
            $response['status'] = TRUE;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }

    function getHargaRoom(){
        
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $kode_lokasi = $_GET['kode_lokasi'];
            $data = $_GET;
            
            $strSQL="select harga  
            from dgw_typeroom 
            where kode_curr ='".$data['kode_curr']."' and no_type='".$data['type_room']."' and kode_lokasi='".$kode_lokasi."'";		
            $rs = execute($strSQL);
            if($rs->RecordCount()>0){
                $harga = $rs->fields[0];				
            }
			$response['harga_room']= $harga;
            $response['status'] = TRUE;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }

    function getPaket(){
        
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $kode_lokasi = $_GET['kode_lokasi'];
            
            $sql="select no_paket, nama from dgw_paket where kode_lokasi = '$kode_lokasi'";
            
            $response["daftar"]=dbResultArray($sql);
            $response['status'] = TRUE;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }

    function getJadwal(){
        
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $kode_lokasi = $_GET['kode_lokasi'];
            $data = $_GET;
            
            $sql="select convert(varchar,tgl_berangkat,103) as tgl_berangkat,no_jadwal from dgw_jadwal where no_closing='-' and no_paket='".$data['no_paket']."' and kode_lokasi = '$kode_lokasi'";
            
            $response["daftar"]=dbResultArray($sql);
            $response['status'] = TRUE;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }

    function getJPaket(){
        
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $kode_lokasi = $_GET['kode_lokasi'];
            
            $sql="select kode_harga, nama from dgw_jenis_harga where kode_lokasi = '$kode_lokasi'";
            
            $response["daftar"]=dbResultArray($sql);
            $response['status'] = TRUE;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }

    function getNoMarket(){
        
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $kode_lokasi = $_GET['kode_lokasi'];
            
            $sql="select kode_marketing from dgw_agent where no_agen= '".$_GET['no_agen']."' and kode_lokasi = '$kode_lokasi'";
            $res = execute($sql);
            if($res->RecordCount()>0){

                $response["marketing"]= $res->fields[0];
            }else{

                $response["marketing"]= '';
            }

            $response['status'] = TRUE;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
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

    function getReg(){
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $query = '';
            $output = array();
        
            $kode_lokasi = $_GET['kode_lokasi'];
            $query .= "select a.no_reg,a.no_peserta,b.nama,a.tgl_input,e.nama as nama_paket,c.tgl_berangkat,a.flag_group
            from dgw_reg a
            inner join dgw_peserta b on a.no_peserta=b.no_peserta and a.kode_lokasi=b.kode_lokasi 
            inner join dgw_jadwal c on a.no_paket=c.no_paket and a.no_jadwal=c.no_jadwal and a.kode_lokasi=c.kode_lokasi
            inner join dgw_paket e on a.no_paket=e.no_paket and a.kode_lokasi=e.kode_lokasi 
            where a.kode_lokasi='".$kode_lokasi."'  ";

            $column_array = array('a.no_reg','a.no_peserta','b.nama','a.tgl_input','e.nama','c.tgl_berangkat');
            $order_column = 'ORDER BY a.no_reg '.$_GET['order'][0]['dir'];
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
                $query .= ' ORDER BY a.tgl_input desc';
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
                $sub_array[] = $row->no_reg;
                $sub_array[] = $row->no_peserta;
                $sub_array[] = $row->nama;
                $sub_array[] = $row->tgl_input;
                $sub_array[] = $row->nama_paket; 
                $sub_array[] = $row->tgl_berangkat; 
                if($row->flag_group == "1"){
                    if($_SESSION['userStatus'] == "U"){
                        $sub_array[] = "<a href='#' title='Preview' class='badge badge-info' id='btn-print'><i class='fas fa-print'></i></a>&nbsp;<a href='#' title='Grouping Anggota' class='badge badge-primary' id='btn-group'><i class='fas fa-user-plus' style='color: white;'></i></a>";
                    }else{
                        $sub_array[] = "<a href='#' title='Edit' class='badge badge-info' id='btn-edit'><i class='fas fa-pencil-alt'></i></a> &nbsp; <a href='#' title='Hapus' class='badge badge-danger' id='btn-delete'><i class='fa fa-trash'></i></a>&nbsp; <a href='#' title='Preview' class='badge badge-info' id='btn-print'><i class='fas fa-print'></i></a>&nbsp;<a href='#' title='Grouping Anggota' class='badge badge-primary' id='btn-group'><i class='fas fa-user-plus' style='color: white;'></i></a>";
                    }
                    
                    // if($row->sts_dok == "-"){
                    //     $sub_array[] = "<a href='#' title='Edit' class='badge badge-info' id='btn-edit'><i class='fas fa-pencil-alt'></i></a> &nbsp; <a href='#' title='Hapus' class='badge badge-danger' id='btn-delete'><i class='fa fa-trash'></i></a>&nbsp; <a href='#' title='Preview' class='badge badge-info' id='btn-print'><i class='fas fa-print'></i></a>&nbsp;<a href='#' title='Grouping Anggota' class='badge badge-primary' id='btn-group'><i class='fas fa-user-plus' style='color: white;'></i></a>";
                    // }else{
                        // $sub_array[] = "<a href='#' title='Edit' class='badge badge-info' id='btn-edit'><i class='fas fa-pencil-alt'></i></a> &nbsp; <a href='#' title='Hapus' class='badge badge-danger' id='btn-delete'><i class='fa fa-trash'></i></a>&nbsp; <a href='#' title='Preview' class='badge badge-info' id='btn-print'><i class='fas fa-print'></i></a>&nbsp;<a href='#' title='Grouping Anggota' class='badge badge-primary' id='btn-group'><i class='fas fa-user-plus' style='color: white;'></i></a>";
                    // }
                }else{
                    if($_SESSION['userStatus'] == "U"){
                        $sub_array[] = "<a href='#' title='Preview' class='badge badge-info' id='btn-print'><i class='fas fa-print'></i></a>";
                    }else{
                        $sub_array[] = "<a href='#' title='Edit' class='badge badge-info' id='btn-edit'><i class='fas fa-pencil-alt'></i></a> &nbsp; <a href='#' title='Hapus' class='badge badge-danger' id='btn-delete'><i class='fa fa-trash'></i></a>&nbsp; <a href='#' title='Preview' class='badge badge-info' id='btn-print'><i class='fas fa-print'></i></a>";
                    }
                    // if($row->sts_dok == "-"){
                    //     $sub_array[] = "<a href='#' title='Edit' class='badge badge-info' id='btn-edit'><i class='fas fa-pencil-alt'></i></a> &nbsp; <a href='#' title='Hapus' class='badge badge-danger' id='btn-delete'><i class='fa fa-trash'></i></a>&nbsp; <a href='#' title='Preview' class='badge badge-info' id='btn-print'><i class='fas fa-print'></i></a>";
                    // }else{
                        // $sub_array[] = "<a href='#' title='Edit' class='badge badge-info' id='btn-edit'><i class='fas fa-pencil-alt'></i></a> &nbsp; <a href='#' title='Hapus' class='badge badge-danger' id='btn-delete'><i class='fa fa-trash'></i></a>&nbsp; <a href='#' title='Preview' class='badge badge-info' id='btn-print'><i class='fas fa-print'></i></a>";
                    // }
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
            $id=$_GET['no_reg'];    
            $kode_lokasi=$_GET['kode_lokasi'];
        
            $response = array("message" => "", "rows" => 0, "status" => "" );
        
            $sql ="select a.no_reg,a.kode_harga,a.harga, a.harga_room,a.no_paket, a.no_jadwal, a.tgl_input, a.no_type, b.tgl_berangkat, b.lama_hari, a.uk_pakaian, a.no_peserta, a.no_agen,a.no_jadwal,a.no_paket, a.no_marketing, a.info, a.jenis, a.no_peserta_ref, a.kode_pp, a.diskon,c.kode_curr,a.no_quota,a.flag_group,a.brkt_dgn,a.hubungan,a.referal,a.ket_diskon
            from dgw_reg a 
            left join dgw_jadwal b on a.no_paket=b.no_paket and a.kode_lokasi=b.kode_lokasi and a.no_jadwal=b.no_jadwal 
            left join dgw_typeroom c on a.no_type=c.no_type and a.kode_lokasi=c.kode_lokasi 
            where a.no_reg='$id'  and a.kode_lokasi='$kode_lokasi'";

            $response['daftar'] = dbResultArray($sql);
			
			//bisa nambah saat koreksi
            $sql = "select b.kode_biaya,isnull(a.tarif,0) as tarif,isnull(a.jml,0) as jml,isnull(a.nilai,0) as nilai,b.nama 
            from  dgw_biaya b left join dgw_reg_biaya a on a.kode_biaya=b.kode_biaya and a.kode_lokasi=b.kode_lokasi and a.no_reg = '$id' 
            where b.jenis='TAMBAHAN' and b.kode_lokasi='$kode_lokasi' order by b.kode_biaya";					
            
            $response['daftar2'] = dbResultArray($sql);

            $sql = "select a.kode_biaya,a.tarif,a.jml,a.nilai,b.nama 
            from dgw_reg_biaya a 
            inner join dgw_biaya b on a.kode_biaya=b.kode_biaya 
            where b.jenis='DOKUMEN' and a.no_reg = '$id' and a.kode_lokasi='$kode_lokasi' order by a.kode_biaya";					
			$response['daftar3'] = dbResultArray($sql);
			
            $sql= "select a.no_dok,b.deskripsi as ket,b.jenis 
            from dgw_reg_dok a 
            inner join dgw_dok b on a.no_dok=b.no_dokumen 
            where a.no_reg = '$id' and a.kode_lokasi='$kode_lokasi' order by a.no_dok";	
            $response['daftar4'] = dbResultArray($sql);
            $response["status"] = true;
						
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
            $kode_lokasi=$data['kode_lokasi'];
            $exec = array();
            $tahun = date('y');
            

            if ($data['id'] == 'edit') {
                $no_reg = $data['no_reg'];
                $del1 = "delete from dgw_reg where no_reg='".$no_reg."' and kode_lokasi ='".$kode_lokasi."'";
                array_push($exec,$del1);
                $del2 = "delete from dgw_reg_dok where no_reg='".$no_reg."' and kode_lokasi ='".$kode_lokasi."'";
                array_push($exec,$del2);					
                $del3 = "delete from dgw_reg_biaya where no_reg='".$no_reg."' and kode_lokasi ='".$kode_lokasi."'";
                array_push($exec,$del3);	
                if($data['paket_lama'] != $data['paket'] && $data['jadwal_lama'] != $data['jadwal']){						
                    $sql = "insert into dgw_history_jadwal(no_reg,no_paket,no_jadwal,no_paket_lama,no_jadwal_lama,kode_lokasi) values 
                        ('".$no_reg."','".$data['paket']."','".$data['jadwal']."','".$data['paket_lama']."','".$data['jadwal_lama']."','".$kode_lokasi."')";
                    array_push($exec,$sql);
                }										
               
            }else{
                
                $no_reg = generateKode("dgw_reg", "no_reg", "REG/".substr($data['periode2'],2,4)."/", "0001");
                $sql = "insert into dgw_history_jadwal(no_reg,no_paket,no_jadwal,no_paket_lama,no_jadwal_lama,kode_lokasi) values
                    ('".$no_reg."','".$data['paket']."','".$data['jadwal']."','-','-','".$kode_lokasi."')";
                    array_push($exec,$sql);
            }

            //NON-agen tidak ada fee
            if ($data['no_agen'] == "NON") {
                $noFee = "X";
            }else{
                $noFee = "-";
            }

            $sql1= "insert into dgw_reg(no_reg,tgl_input,no_peserta,no_paket,no_jadwal,no_agen,no_type,harga_room,info,kode_lokasi,no_quota,harga,uk_pakaian, no_marketing,kode_harga,periode, jenis,no_fee, no_peserta_ref, kode_pp, diskon,flag_group,brkt_dgn,hubungan,referal,ket_diskon) values 

            ('".$no_reg."','".$data['tgl_input']."','".$data['no_peserta']."','".$data['paket']."','".$data['jadwal']."','".$data['agen']."','".$data['type_room']."',".joinNum($data['harga_room']).",'".$data['sumber']."','".$kode_lokasi."',".joinNum($data['quota']).",".joinNum($data['harga_paket']).",'".$data['ukuran_pakaian']."', '".$data['marketing']."','".$data['jenis_promo']."','".$data['periode2']."','".$data['jenis_paket']."','".$noFee."','".$data['no_peserta']."','".$data['kode_pp2']."',".joinNum($data['diskon']).",'".$data['flag_group']."','".$data['brkt_dgn']."','".$data['hubungan']."','".$data['referal']."','".$data['ket_diskon']."')";

            array_push($exec,$sql1);

            if (count($data['dok_no_dokumen']) > 0){
                for ($i=0;$i <count($data['dok_no_dokumen']);$i++){
                   
                    $sql2 = "insert into dgw_reg_dok(no_reg,no_dok,ket,kode_lokasi,tgl_terima) values 
                    ('".$no_reg."','".$data['dok_no_dokumen'][$i]."','".$data['dok_deskripsi'][$i]."','".$kode_lokasi."','2099-12-31')";
                   array_push($exec,$sql2);
                    
                }						
            }
            if (count($data['btambah_kode_biaya']) > 0){
                for ($i=0;$i <count($data['btambah_kode_biaya']);$i++){
                  
                   $sql3 = "insert into dgw_reg_biaya(no_reg,kode_biaya,tarif,jml,nilai,kode_lokasi) values ('".$no_reg."','".$data['btambah_kode_biaya'][$i]."',".joinNum($data['btambah_nilai'][$i]).",'".joinNum($data['btambah_jumlah'][$i])."',".joinNum($data['btambah_total'][$i]).",'".$kode_lokasi."')";
                   array_push($exec,$sql3);
                
                }						
            }	
            
            if (count($data['bdok_kode_biaya']) > 0){
                for ($i=0;$i <count($data['bdok_kode_biaya']);$i++){
                    
                    $sql4 = "insert into dgw_reg_biaya(no_reg,kode_biaya,tarif,jml,nilai,kode_lokasi) values ('".$no_reg."','".$data['bdok_kode_biaya'][$i]."',".joinNum($data['bdok_nilai'][$i]).",".joinNum($data['bdok_jumlah'][$i]).",".joinNum($data['bdok_total'][$i]).",'".$kode_lokasi."')";
                    array_push($exec,$sql4);
                    
                }						
            }	

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
            $response["no_reg"] = $no_reg;
            $response["error"] = $error_upload;
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
            
            $no_reg = $data['no_reg'];
            $kode_lokasi = $data['kode_lokasi'];
            $del1 = "delete from dgw_reg where no_reg='".$no_reg."' and kode_lokasi ='".$kode_lokasi."'";
            array_push($exec,$del1);
            $del2 = "delete from dgw_reg_dok where no_reg='".$no_reg."' and kode_lokasi ='".$kode_lokasi."'";
            array_push($exec,$del2);					
            $del3 = "delete from dgw_reg_biaya where no_reg='".$no_reg."' and kode_lokasi ='".$kode_lokasi."'";
            array_push($exec,$del3);
            $del4 = "delete from dgw_history_jadwal where no_reg='".$no_reg."' and kode_lokasi ='".$kode_lokasi."'";
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

    function getPrint(){
        
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $kode_lokasi = $_GET['kode_lokasi'];
            $no_bukti = $_GET['no_reg'];

            $sql="select a.no_reg,b.alamat, a.no_quota, a.uk_pakaian, b.hp, a.no_peserta, b.nopass, b.norek, b.nama as peserta, b.status, a.no_paket, c.nama as namapaket, a.no_jadwal, d.tgl_berangkat, a.no_agen, e.nama_agen, a.no_type, f.nama as type, a.harga, h.nama_marketing, a.kode_lokasi,b.id_peserta,b.jk,b.tgl_lahir,b.tempat,b.th_umroh,b.th_haji,b.pekerjaan,b.kantor_mig,b.hp,b.telp,b.email,b.ec_telp,a.info,a.uk_pakaian,a.diskon,a.no_peserta_ref,isnull(a.brkt_dgn,'-') as brkt_dgn,isnull(a.hubungan,'-') as hubungan,isnull(a.referal,'-') as referal,g.nama as nama_pekerjaan,c.jenis as jenis_paket,a.harga_room
            from dgw_reg a
            inner join dgw_peserta b on a.no_peserta=b.no_peserta and a.kode_lokasi=b.kode_lokasi
            left join dgw_agent e on a.no_agen=e.no_agen and a.kode_lokasi=e.kode_lokasi 
            inner join dgw_typeroom f on a.no_type=f.no_type and a.kode_lokasi=f.kode_lokasi 
            left join dgw_marketing h on a.no_marketing=h.no_marketing and a.kode_lokasi=h.kode_lokasi
            inner join dgw_paket c on a.no_paket=c.no_paket and a.kode_lokasi=c.kode_lokasi 
            inner join dgw_jadwal d on  a.no_paket=d.no_paket and a.no_jadwal=d.no_jadwal and a.kode_lokasi=d.kode_lokasi
            inner join dgw_pekerjaan g on b.pekerjaan=g.id_pekerjaan and b.kode_lokasi=g.kode_lokasi
            where a.kode_lokasi='$kode_lokasi' and a.no_reg='$no_bukti' ";
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


    function getUpload(){
        
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $kode_lokasi = $_GET['kode_lokasi'];
            $no_reg = $_GET['no_reg'];

            $sql = "select a.no_reg,a.no_peserta,c.nama as nama_peserta,c.alamat,a.no_paket,b.nama as nama_paket,a.no_jadwal,d.tgl_berangkat
            from dgw_reg a
            inner join dgw_paket b on a.no_paket=b.no_paket and a.kode_lokasi=b.kode_lokasi
            inner join dgw_peserta c on a.no_peserta=c.no_peserta and a.kode_lokasi=c.kode_lokasi
            inner join dgw_jadwal d on a.no_paket=d.no_paket and a.no_jadwal=d.no_jadwal and a.kode_lokasi=d.kode_lokasi
            where a.kode_lokasi='$kode_lokasi' and a.no_reg='$no_reg'
            ";
            $response['daftar'] = dbResultArray($sql);

            $sql="select a.no_dokumen,a.deskripsi,a.jenis,isnull(convert(varchar,b.tgl_terima,111),'-') as tgl_terima,isnull(c.no_gambar,'-') as fileaddres,isnull(c.nik,'-') as nik 
            from dgw_dok a 
            left join dgw_reg_dok b on a.no_dokumen=b.no_dok and b.no_reg='$no_reg'
            left join dgw_scan c on a.no_dokumen=c.modul and c.no_bukti ='$no_reg' 
            where a.kode_lokasi='$kode_lokasi' order by a.no_dokumen";
            $response['daftar2'] = dbResultArray($sql);

            $response['status'] = TRUE;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }

    function simpanUpload(){
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            
            $data=$_POST;
            $kode_lokasi=$data['kode_lokasi'];
            $exec = array();

            $c = array();
            $type = array();
            $tmp_name = array();
            $error = array();
            $size = array();
            $dok_no = array();
            $user = array();
            for($i=0;$i<count($_FILES['file_dok']['name']);$i++){
                if($_FILES['file_dok']['name'][$i] != ""){

                    array_push($c,$_FILES['file_dok']['name'][$i]);
                    array_push($type,$_FILES['file_dok']['type'][$i]);
                    array_push($tmp_name,$_FILES['file_dok']['tmp_name'][$i]);
                    array_push($error,$_FILES['file_dok']['error'][$i]);
                    array_push($size,$_FILES['file_dok']['size'][$i]);
                    array_push($dok_no,$data['upload_no_dokumen'][$i]);
                    array_push($user,$data['upload_nik'][$i]);
                }
            }

            if(count($tmp_name)>0){
                $arr_nama = array();
                for($i=0; $i<count($tmp_name); $i++){
                    $_FILES['userfile']['name']     = $c[$i];
                    $_FILES['userfile']['type']     = $type[$i];
                    $_FILES['userfile']['tmp_name'] = $tmp_name[$i];
                    $_FILES['userfile']['error']    = $error[$i];
                    $_FILES['userfile']['size']     = $size[$i];
                    
                    if(!empty($_FILES['userfile']['name'])){
                        
                        $status_upload = TRUE;
                        $path_img = realpath($_SERVER['DOCUMENT_ROOT'])."/";
                        $target_dir = $path_img."upload/";
                        $uploadOk = 1;
                        $message="";
                        $error_upload="";
                        $target_file = $target_dir . basename($_FILES["userfile"]["name"]);
                        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
                        // generate nama gambar baru
                        $namaFileBaru = uniqid();
                        $namaFileBaru .= '.';
                        $namaFileBaru .= $imageFileType;
                        
                        $target_file = $target_dir . $namaFileBaru;
                        $arr_nama[$i] =  $namaFileBaru;
                        
                        // Check if file already exists
                        if (file_exists($target_file)) {
                            $error_upload .= "Sorry, file already exists.";
                            $uploadOk = 0;
                        }
                        // Check file size
                        // if ($_FILES["userfile"]["size"] > 3000000) {
                        //     $error_upload .= "Sorry, your file is too large.";
                        //     $uploadOk = 0;
                        // }
                        if ($uploadOk == 0) {
                            $error_upload .= "Sorry, your file was not uploaded.";
                            // if everything is ok, try to upload file
                        } else {
                            if (move_uploaded_file($_FILES["userfile"]["tmp_name"], $target_file)) {
                                $message = "The file $namaFileBaru has been uploaded.";
                            } else {
                                $error_upload .= "Sorry, there was an error uploading your file.";
                                if (is_dir($target_dir) && is_writable($target_dir)) {
                                    // do upload logic here
                                } else if (!is_dir($target_dir)){
                                    $error_upload.= 'Upload directory does not exist.'.$target_dir;
                                } else if (!is_writable($target_dir)){
                                    $error_upload.= 'Upload directory is not writable'.$target_dir;
                                }
                                
                            }
                        }
                    }
                }

                for($i=0; $i<count($arr_nama);$i++){

                    $del = "delete from dgw_scan where modul='".$dok_no[$i]."' and no_bukti = '".$data['upload_no_reg']."' and kode_lokasi='".$kode_lokasi."'";				
                    array_push($exec,$del);

                    $sql1 = "insert into dgw_scan(no_bukti,modul,no_gambar,kode_lokasi,nik) values ('".$data['upload_no_reg']."','".$dok_no[$i]."','".$arr_nama[$i]."','".$kode_lokasi."','".$user[$i]."')";	
                    array_push($exec,$sql1);

                    $upd = "update dgw_reg_dok set tgl_terima='".$data['upload_tgl_terima']."' where no_reg='".$data['upload_no_reg']."' and no_dok='".$dok_no[$i]."' "; 				
                    array_push($exec,$upd);
                    
                }
                
                $rs=executeArray($exec,$err);
                if ($err == null)
                {	
                    $tmp="sukses";
                    $sts=true;
                }else{
                    $tmp=$err;
                    $sts=false;
                }	 
                
            }else{
                $tmp="gagal. Dokumen required";
                $sts=true;
            }

            $response["message"] =$tmp.$error_upload;;
            $response["status"] = $sts;
            $response["exec"] = $exec;
            $response['jumlah_upload']= count($c);
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
        
    }


    function getGroup(){
        
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $kode_lokasi = $_GET['kode_lokasi'];
            $no_reg = $_GET['no_reg'];

            $sql = "select a.no_reg,a.no_peserta,c.nama as nama_peserta,c.alamat,a.no_paket,b.nama as nama_paket,a.no_jadwal,d.tgl_berangkat
            from dgw_reg a
            inner join dgw_paket b on a.no_paket=b.no_paket and a.kode_lokasi=b.kode_lokasi
            inner join dgw_peserta c on a.no_peserta=c.no_peserta and a.kode_lokasi=c.kode_lokasi
            inner join dgw_jadwal d on a.no_paket=d.no_paket and a.no_jadwal=d.no_jadwal and a.kode_lokasi=d.kode_lokasi
            where a.kode_lokasi='$kode_lokasi' and a.no_reg='$no_reg'
            ";
            $response['daftar'] = dbResultArray($sql);

            $sql = "select a.no_reg,a.no_peserta,no_reg_ref from dgw_group_d a
            where a.kode_lokasi='$kode_lokasi' and a.no_reg='$no_reg'
            ";
            $response['daftar2'] = dbResultArray($sql);

            $response['status'] = TRUE;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }


    function simpanGroup(){
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            
            $data=$_POST;
            $kode_lokasi=$data['kode_lokasi'];
            $exec = array();
            if(count($data['group_sts_reg']) > 0){

                // $del = "delete from dgw_group_d where no_reg = '".$data['group_no_reg']."' and kode_lokasi='".$kode_lokasi."'";				
                // array_push($exec,$del);
                $sql = "select * from dgw_group_d where no_reg='".$data['group_no_reg']."' and kode_lokasi='$kode_lokasi' ";
                $cekEdit = execute($sql);
                if($cekEdit->RecordCount()>0){
                    // while($row = $cekEdit->FetchNextObject($toupper=false)){
                    //     $del = "delete from dgw_reg where no_reg ='$row->no_reg_ref' and kode_lokasi='$kode_lokasi' ";
                    //     array_push($exec,$del);
                    //     $del2 = "delete from dgw_reg_dok where no_reg ='$row->no_reg_ref' and kode_lokasi='$kode_lokasi' ";
                    //     array_push($exec,$del2);
                    //     $del3 = "delete from dgw_reg_biaya where no_reg ='$row->no_reg_ref' and kode_lokasi='$kode_lokasi' ";
                    //     array_push($exec,$del3);
                    //     $del4 = "delete from dgw_history_jadwal where no_reg ='$row->no_reg_ref' and kode_lokasi='$kode_lokasi' ";
                    //     array_push($exec,$del4);

                    // }
                    // $del5="delete from dgw_group_d where no_reg='".$data['group_no_reg']."' ";
                    // array_push($exec,$del5);
                    for($i=0; $i<count($data['group_no_anggota']);$i++){
                        if($data['group_sts_reg'][$i] == "D"){
                            $del = "delete from dgw_reg where no_reg ='".$data['no_reg_ref'][$i]."' and kode_lokasi='$kode_lokasi' ";
                            array_push($exec,$del);
                            $del2 = "delete from dgw_reg_dok where no_reg ='".$data['no_reg_ref'][$i]."' and kode_lokasi='$kode_lokasi' ";
                            array_push($exec,$del2);
                            $del3 = "delete from dgw_reg_biaya where no_reg ='".$data['no_reg_ref'][$i]."' and kode_lokasi='$kode_lokasi' ";
                            array_push($exec,$del3);
                            $del4 = "delete from dgw_history_jadwal where no_reg ='".$data['no_reg_ref'][$i]."' and kode_lokasi='$kode_lokasi' ";
                            array_push($exec,$del4);
                            $del5="delete from dgw_group_d where no_reg_ref='".$data['no_reg_ref'][$i]."' ";
                            array_push($exec,$del5);
                        }
                    }
                }
                
                $tmp = generateKode("dgw_reg", "no_reg", "REG/".substr(date('Ym'),2,4)."/", "0001");
                $temp = explode("/",$tmp);
                $id = intval($temp[1]);

                for($i=0; $i<count($data['group_no_anggota']);$i++){
                    if($data['group_sts_reg'][$i] == "0"){

                        $no_reg = $temp[0]."/".sprintf("%04s",$id);
        
                        $sql1 = "insert into dgw_group_d(no_reg,no_peserta,no_reg_ref,kode_lokasi) values ('".$data['group_no_reg']."','".$data['group_no_anggota'][$i]."','".$no_reg."','".$kode_lokasi."')";	
                        array_push($exec,$sql1);
                        
                    
                        $ins = "insert into dgw_reg (no_reg,tgl_input,no_peserta,no_paket,no_jadwal,no_agen,no_type,harga_room,info,kode_lokasi,no_quota,harga,uk_pakaian,no_marketing,kode_harga,periode,jenis,no_fee,no_peserta_ref,kode_pp,diskon,flag_group,brkt_dgn,hubungan,referal,ket_diskon) select '$no_reg' as no_reg,getdate(),'".$data['group_no_anggota'][$i]."' as no_peserta,no_paket,no_jadwal,no_agen,no_type,harga_room,info,kode_lokasi,no_quota,harga,uk_pakaian,no_marketing,kode_harga,periode,jenis,no_fee,no_peserta_ref,kode_pp,diskon,'0' as flag_group,brkt_dgn,hubungan,referal,ket_diskon from dgw_reg where no_reg = '".$data['group_no_reg']."' and kode_lokasi='".$kode_lokasi."' ";	
        
                        array_push($exec,$ins);
        
        
                        $ins2 = "insert into dgw_reg_dok (no_dok,no_reg,ket,kode_lokasi,tgl_terima) 
                        select a.no_dok,'$no_reg' as no_reg,a.ket,a.kode_lokasi,'2099-12-31' from dgw_reg_dok a where a.no_reg='".$data['group_no_reg']."' and a.kode_lokasi='$kode_lokasi'";	
        
                        array_push($exec,$ins2);
        
                        $ins3 = "insert into dgw_reg_biaya (kode_biaya,no_reg,tarif,jml,nilai,kode_lokasi) 
                        select kode_biaya,'$no_reg' as no_reg,tarif,jml,nilai,kode_lokasi from dgw_reg_biaya where no_reg = '".$data['group_no_reg']."' and kode_lokasi='".$kode_lokasi."' ";	
        
                        array_push($exec,$ins3);
        
                        
                        $ins4 = "insert into dgw_history_jadwal(no_reg,no_paket,no_jadwal,no_paket_lama,no_jadwal_lama,kode_lokasi)
                        select '$no_reg' as no_reg,no_paket,no_jadwal,no_paket_lama,no_jadwal_lama,kode_lokasi from dgw_history_jadwal where no_reg = '".$data['group_no_reg']."' and kode_lokasi='".$kode_lokasi."' ";	
        
                        array_push($exec,$ins4);
                        $id++;
                    }                   
    
                    
                }
                
                $rs=executeArray($exec,$err);
                if ($err == null)
                {	
                    $tmp="sukses";
                    $sts=true;
                }else{
                    $tmp=$err;
                    $sts=false;
                }	 	
            }else{
                $tmp="gagal. Error No Peserta harus diisi ";
                $sts=true;
            }
            
           
            $response["message"] =$tmp.$error_upload;;
            $response["status"] = $sts;
            $response["exec"] = $exec;
            $response["data"] = $i.'-'.count($data['group_no_anggota']);
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
        
    }