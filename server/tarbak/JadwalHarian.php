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
		if (substr($root_lib,-1)!="/") {
			$root_lib=$root_lib."/";
		}
		include_once($root_lib.'app/tarbak/setting.php');
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

    function getView(){
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $query = '';
            $output = array();
        
            $kode_lokasi = $_GET['kode_lokasi'];
            $kode_pp = $_GET['kode_pp'];
            $query .= "select a.kode_ta,a.kode_matpel,a.nik,a.kode_kelas,a.kode_pp+'-'+b.nama as pp
            from sis_jadwal a 
            inner join pp b on a.kode_pp=b.kode_pp and a.kode_lokasi=b.kode_lokasi
            where a.kode_lokasi='$kode_lokasi'
            group by a.kode_ta,a.kode_matpel,a.nik,a.kode_kelas,a.kode_pp+'-'+b.nama 
             ";

            $column_array = array('a.kode_ta','a.kode_matpel','a.nik','a.kode_kelas',"a.kode_pp+'-'+b.nama");
            $order_column = 'ORDER BY a.kode_ta '.$_GET['order'][0]['dir'];
            $column_string = join(',', $column_array);

            if(!empty($_GET['search']['value']))
            {
                $search = $_GET['search']['value'];
                $filter_string = " having (";
        
                for($i=0; $i<count($column_array); $i++){
        
                    if($i == (count($column_array) - 1)){
                        $filter_string .= $column_array[$i]." like '%".$search."%' )";
                    }else{
                        $filter_string .= $column_array[$i]." like '%".$search."%' or ";
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
                $query .= ' ORDER BY a.kode_ta ';
            }

            
            $res = execute($query);
            $jml_baris = $res->RecordCount();
            
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
                $sub_array[] = $row->kode_ta;
                $sub_array[] = $row->kode_matpel;
                $sub_array[] = $row->nik;
                $sub_array[] = $row->kode_kelas;  
                $sub_array[] = $row->pp;  
                $data[] = $sub_array;
            }
            $response = array(
                "draw"				=>	intval($_GET["draw"]),
                "recordsTotal"		=> 	$filtered_rows,
                "recordsFiltered"	=>	$jml_baris,
                "data"				=>	$data,
                "query"             =>  $query
            );
            
            $response["status"] = true;
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
            $id=$_GET['kode_kelas'];   
            $kode_lokasi=$_GET['kode_lokasi'];
            $kode_pp=$_GET['kode_pp']; 
        
            $response = array("message" => "", "rows" => 0, "status" => "" );
        
            $sql="select nama, kode_pp from pp where kode_lokasi='".$kode_lokasi."' ";
            $response['daftar']=dbResultArray($sql);
            $response['status'] = TRUE;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    
    }

    function getTA(){
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){ 
            $kode_lokasi=$_GET['kode_lokasi'];
            $kode_pp=$_GET['kode_pp']; 
        
            $response = array("message" => "", "rows" => 0, "status" => "" );
            if($kode_pp != ""){
                $filter = " and kode_pp='$kode_pp' ";
            }else{
                $filter = "";
            }
        
            $sql="select kode_ta, nama from sis_ta where kode_lokasi = '".$kode_lokasi."' and flag_aktif='1' $filter";
            $response['daftar']=dbResultArray($sql);
            $response['status'] = TRUE;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    
    }

    function getNIKGuru(){
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){ 
            $kode_lokasi=$_GET['kode_lokasi'];
            $kode_pp=$_GET['kode_pp']; 
        
            $response = array("message" => "", "rows" => 0, "status" => "" );
            if($kode_pp != ""){
                $filter = " and kode_pp='$kode_pp' ";
            }else{
                $filter = "";
            }
        
            $sql="select nik, nama from karyawan where status='GURU' and flag_aktif='1' and kode_lokasi = '".$kode_lokasi."' $filter";
            $response['daftar']=dbResultArray($sql);
            $response['status'] = TRUE;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    
    }

    function getMatpel(){
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){ 
            $kode_lokasi=$_GET['kode_lokasi'];
            $kode_pp=$_GET['kode_pp']; 
            $nik=$_GET['nik_guru']; 
        
            $response = array("message" => "", "rows" => 0, "status" => "" );
            if($kode_pp != ""){
                $filter = " and a.kode_pp='$kode_pp' ";
            }else{
                $filter = "";
            }

            if($nik != ""){
                $filter .= " and a.nik='$nik' ";
            }else{
                $filter .= "";
            }
        
            $sql="select a.kode_matpel, b.nama from sis_guru_matpel a inner join sis_matpel b on a.kode_matpel=b.kode_matpel and a.kode_lokasi=b.kode_lokasi where a.kode_lokasi = '".$kode_lokasi."' $filter";
            $response['daftar']=dbResultArray($sql);
            $response['status'] = TRUE;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    
    }

    function getKelas(){
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){ 
            $kode_lokasi=$_GET['kode_lokasi'];
            $kode_pp=$_GET['kode_pp'];
        
            $response = array("message" => "", "rows" => 0, "status" => "" );
            if($kode_pp != ""){
                $filter = " and kode_pp='$kode_pp' ";
            }else{
                $filter = "";
            }
            $sql="select kode_kelas, nama from sis_kelas where kode_lokasi = '".$kode_lokasi."' $filter";
            $response['daftar']=dbResultArray($sql);
            $response['status'] = TRUE;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    
    }

    function getLoadJadwal(){
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){ 
            
            $kode_lokasi=$_GET['kode_lokasi'];
            $kode_pp=$_GET['kode_pp'];
            $data = $_GET;
            
            $exec = array();
            $jadwal = array();
            $strSQL = "select a.kode_slot,a.nama
            from  sis_slot a 
            where a.kode_pp='".$kode_pp."' and a.kode_lokasi='".$kode_lokasi."' order by a.kode_slot ";
            // array_push($exec,$strSQL);	
            $result = dbResultArray($strSQL);
            
		    if (count($result) > 0){
                $no=1;			
                foreach ($result as $row){
                    $senin=$selasa=$rabu=$kamis=$jumat=$sabtu=$minggu="KOSONG";
                    $strSQL2 = "select kode_hari,kode_matpel,nik from sis_jadwal where kode_slot='".$row["kode_slot"]."' and kode_ta='".$data["kode_ta"]."' and kode_kelas='".$data["kode_kelas"]."' and kode_pp='".$data["kode_pp"]."' ";
                    // array_push($exec,$strSQL2);
                    $result2 = dbResultArray($strSQL2); 			
                    if (count($result2 > 0)){		
                        foreach ($result2 as $row2){
                            if ($row2['kode_hari'] == "HR_01") {
                                if ($row2['kode_matpel'] == $data['kode_matpel'] && $row2['nik'] == $data['nik_guru']) $senin  = "ISI";
                                else $senin  = "TERPAKAI";
                            }
                            if ($row2['kode_hari'] == "HR_02") {
                                if ($row2['kode_matpel'] == $data['kode_matpel'] && $row2['nik'] == $data['nik_guru']) $selasa  = "ISI";
                                else $selasa  = "TERPAKAI";
                            }
                            if ($row2['kode_hari'] == "HR_03") {
                                if ($row2['kode_matpel'] == $data['kode_matpel'] && $row2['nik'] == $data['nik_guru']) $rabu  = "ISI";
                                else $rabu  = "TERPAKAI";
                            }
                            if ($row2['kode_hari'] == "HR_04") {
                                if ($row2['kode_matpel'] == $data['kode_matpel'] && $row2['nik'] == $data['nik_guru']) $kamis  = "ISI";
                                else $kamis  = "TERPAKAI";
                            }
                            if ($row2['kode_hari'] == "HR_05") {
                                if ($row2['kode_matpel'] == $data['kode_matpel'] && $row2['nik'] == $data['nik_guru']) $jumat  = "ISI";
                                else $jumat  = "TERPAKAI";
                            }
                            if ($row2['kode_hari'] == "HR_06") {
                                if ($row2['kode_matpel'] == $data['kode_matpel'] && $row2['nik'] == $data['nik_guru']) $sabtu  = "ISI";
                                else $sabtu  = "TERPAKAI";
                            }
                            if ($row2['kode_hari'] == "HR_07") {
                                if ($row2['kode_matpel'] == $data['kode_matpel'] && $row2['nik'] == $data['nik_guru']) $minggu  = "ISI";
                                else $minggu  = "TERPAKAI";
                            }
                        }
                    }				
                     
                    $jadwal[] = array("no"=>$no,"kode_slot"=>$row['kode_slot'],"nama"=>$row['nama'],"senin"=>$senin,"selasa"=>$selasa,"rabu"=>$rabu,"kamis"=>$kamis,"jumat"=>$jumat,"sabtu"=>$sabtu,"minggu"=>$minggu); 
                    $no++;
                }
            } 
            $response["status"] = true;
            $response["jadwal"] = $jadwal;
            // $response["exec"] = $exec;
        }else{
                
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);

    }

    // function getEdit(){
    //     session_start();
    //     getKoneksi();
    //     if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
    //         $kode_sem=$_GET['kode_sem'];  
    //         $kode_ta=$_GET['kode_ta'];   
    //         $kode_lokasi=$_GET['kode_lokasi'];
    //         $kode_pp=$_GET['kode_pp']; 
        
    //         $response = array("message" => "", "rows" => 0, "status" => "" );

    //         $sql = "select tanggal,agenda from sis_kalender_akad 
    //         where kode_ta='$kode_ta' and kode_sem='$kode_sem' and kode_lokasi='$kode_lokasi' and kode_pp='$kode_pp' ";	
    //         $response['daftar2']=dbResultArray($sql);

    //         $response['status'] = TRUE;
    //     }else{
            
    //         $response["status"] = false;
    //         $response["message"] = "Unauthorized Access, Login Required";
    //     }
    //     // header('Content-Type: application/json');
    //     echo json_encode($response);
    
    // }

    function simpan(){
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $data=$_POST;
            $exec = array();

            $del = "delete from sis_jadwal where kode_ta = '".$data['kode_ta']."' and nik = '".$data['nik_guru']."' and kode_matpel = '".$data['kode_matpel']."' and kode_kelas='".$data['kode_kelas']."' and kode_pp='".$data['kode_pp']."' and kode_lokasi='".$data['kode_lokasi']."'";	
            array_push($exec,$del);		

            $hari = array('senin','selasa','rabu','kamis','jumat','sabtu','minggu');
            $kodeHari = array('HR_01','HR_02','HR_03','HR_04','HR_05','HR_06','HR_07');
            if (count($data['kode_slot']) > 0){
                for ($i=0;$i < count($data['kode_slot']);$i++){
                    for ($j = 0; $j < count($hari);$j++){
                        if($data[$hari[$j]][$i] == "ISI"){
                            // $kodeHari = "HR_".$j+1;
                            $sql = "insert into sis_jadwal(kode_slot,kode_lokasi,kode_pp,kode_kelas,kode_hari,kode_ta,nik,kode_matpel) values 
                            ('".$data['kode_slot'][$i]."','".$data['kode_lokasi']."','".$data['kode_pp']."','".$data['kode_kelas']."','".$kodeHari[$j]."','".$data['kode_ta']."','".$data['nik_guru']."','".$data['kode_matpel']."')";
                            array_push($exec,$sql);
                            
                        }
                    }
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
           
            $del = "delete from sis_jadwal where kode_ta = '".$data['kode_ta']."' and nik = '".$data['nik_guru']."' and kode_matpel = '".$data['kode_matpel']."' and kode_kelas='".$data['kode_kelas']."' and kode_pp='".$data['kode_pp']."' and kode_lokasi='".$data['kode_lokasi']."'";	
            array_push($exec,$del);	
            
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
        }else{
                
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }