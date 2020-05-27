<?php

    $request_method=$_SERVER["REQUEST_METHOD"];

    switch($request_method) {
        case 'GET':
            if(isset($_GET["fx"]) AND function_exists($_GET['fx'])){
                $_GET['fx']();
            }
        break;
        case 'POST':
            // Insert Product
            if(isset($_GET["fx"]) AND function_exists($_GET['fx'])){
                $_GET['fx']();
            }
        break;
    }

    function getKoneksi(){
        $root_lib=$_SERVER["DOCUMENT_ROOT"];
        include_once($root_lib."lib/koneksi.php");
        include_once($root_lib."lib/helpers.php");
    }

    function cekAuth($user,$pass){
        getKoneksi();
        $user = qstr($user);
        $pass = qstr($pass);

        $schema = db_Connect();
        $auth = $schema->SelectLimit("SELECT * FROM hakakses where nik=$user and pass=$pass", 1);
        if($auth->RecordCount() > 0){
            return true;
        }else{
            return false;
        }
    }

    function getDataList(){
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'],$_SESSION['userPwd'])){

            $data = $_POST;
            $query = '';
            $output = array();
            $param = $data['parameter'];
            $kode_lokasi = $_SESSION['lokasi'];
            switch($param) {
                case "kode_cust" :
                    $query .= "select kode_cust,nama from sai_cust where kode_lokasi ='".$kode_lokasi."'";
                    $column_array = array('kode_cust','nama');
                    $order_column = 'order by kode_cust '.$data['order'][0]['dir'];
                    $default_order = 'order by kode_cust ';
                break;
                case "kode_produk" :
                    $query .= "select kode_produk,nama from sai_produk where kode_lokasi ='".$kode_lokasi."'";
                    $column_array = array('kode_produk','nama');
                    $order_column = 'order by kode_produk '.$data['order'][0]['dir'];
                    $default_order = 'order by kode_produk ';
                break;
                case "nik" :
                    $query .= "select nik,nama from sai_karyawan where kode_lokasi = '".$kode_lokasi."'";
                    $column_array = array('nik','nama');
                    $order_column = 'order by nik '.$data['order'][0]['dir'];
                    $default_order = 'order by nik ';
                break;
            }
               
            
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
                $query .= $default_order;
            }
            if($data["length"] != -1)
            {
                $query .= ' offset ' . $data['start'] . ' rows fetch next ' . $data['length'] . ' rows only ';
            }
            $statement = execute($query);
            $data = array();
            $filtered_rows = $statement->RecordCount();
            switch($param) {
                case "kode_cust" :
                    while($row = $statement->FetchNextObject($toupper=false))
                    {
                        $sub_array = array();
                        $sub_array[] = $row->kode_cust;
                        $sub_array[] = $row->nama;
                        $data[] = $sub_array;
                    }
                break;
                case "kode_produk" :
                    while($row = $statement->FetchNextObject($toupper=false))
                    {
                        $sub_array = array();
                        $sub_array[] = $row->kode_produk;
                        $sub_array[] = $row->nama;
                        $data[] = $sub_array;
                    }
                break;
                case "nik" :
                    while($row = $statement->FetchNextObject($toupper=false))
                    {
                        $sub_array = array();
                        $sub_array[] = $row->nik;
                        $sub_array[] = $row->nama;
                        $data[] = $sub_array;
                    }
                break;
            }
            $response = array(
                "draw"				=>	intval($data["draw"]),
                "recordsTotal"		=> 	$filtered_rows,
                "recordsFiltered"	=>	$jml_baris,
                "data_list"				=>	$data,
            );
        }else{
            $response["message"] = "Unauthorized Access, Login Required";
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    function getLapPosisi(){
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'],$_SESSION['userPwd'])){

            $data = $_POST;
            $kode_lokasi = $_SESSION['lokasi'];

            $col_array = array('kode_lokasi', 'kode_cust', 'kode_produk','nik');
			$db_col_name = array('a.kode_lokasi', 'a.kode_cust', 'a.kode_produk','a.nik');
			$where = '';
            $response = array();
			for($i = 0; $i<count($col_array); $i++){
				if(ISSET($_POST[$col_array[$i]][0])){
                    if($_POST[$col_array[$i]][0] == "range" AND ISSET($_POST[$col_array[$i]][1]) AND ISSET($_POST[$col_array[$i]][2])){
                        $where .= " and (".$db_col_name[$i]." between '".$_POST[$col_array[$i]][1]."' AND '".$_POST[$col_array[$i]][2]."') ";
                    }else if($_POST[$col_array[$i]][0] == "exact" AND ISSET($_POST[$col_array[$i]][1])){
                        $where .= " and ".$db_col_name[$i]." = '".$_POST[$col_array[$i]][1]."' ";
                    }
				}
			}

            $sql="select no_bukti,convert(varchar, a.tanggal, 103) as tanggal, keterangan,c.nama as nama_cust, p.nama as nama_prod,k.nama as nama_karyawan,d.nama as status, a.nilai
			from sai_proses01 a
			inner join sai_proses d on a.progress=d.kode_proses 
			inner join sai_cust c on a.kode_cust=c.kode_cust
			inner join sai_produk p on a.kode_produk=p.kode_produk
            inner join sai_karyawan k on a.nik=k.nik
            where a.kode_lokasi ='$kode_lokasi'
			$where";

            $rs = execute($sql);
            $resdata = array();
            while($row = $rs->FetchNextObject($toupper=false)){

                $resdata[]=(array)$row;
            }

            $response["daftar_ls"] = $resdata;
            $response['sql'] = $sql;
            $response['where'] = $where;
            $response["auth_status"] = 1;        
        }else{
            $response["auth_status"] = 0; 
            $response["message"] = "Unauthorized Access, Login Required";
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }


    function getFormProspecting(){
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'],$_SESSION['userPwd'])){

            $data = $_GET;
            $kode_lokasi = $_SESSION['lokasi'];

            $sql="select a.*,convert(varchar, a.tanggal, 103) as tanggal, c.nama as nama_cust, d.nama as nama_prod, e.nama as nama_kar
            from sai_proses01 a 
            inner join sai_cust c on a.kode_cust=c.kode_cust 
            inner join sai_produk d on a.kode_produk=d.kode_produk
            inner join sai_karyawan e on a.nik= e.nik  
            where a.kode_lokasi ='$kode_lokasi' and a.no_bukti ='".$data['no_bukti']."'
			";

            $rs = execute($sql);
            $response["detail"] = array();
            while($row = $rs->FetchNextObject($toupper=false)){

                $response["detail"][]=(array)$row;
            }
            $response["sql"] = $sql;
            $response["status"] = 1;        
        }else{
            $response["status"] = 0; 
            $response["message"] = "Unauthorized Access, Login Required";
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }

?>