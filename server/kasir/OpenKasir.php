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

function getOpenKasir(){
    session_start();
    getKoneksi();
    if($_COOKIE['isLogedIn'] AND cekAuth($_COOKIE['userLog'])){
        $query = '';
        $output = array();

        $nik = $_REQUEST['nik'];
        $kode_lokasi = $_REQUEST['kode_lokasi'];
        $query .= "select no_open,nik,tgl_input,saldo_awal,no_close from kasir_open where kode_lokasi='".$kode_lokasi."' and nik='".$nik."' and no_close = '-' ";

        $column_array = array('no_open','nik','tgl_input','saldo_awal','no_close');
        $order_column = 'ORDER BY no_open '.$_POST['order'][0]['dir'];
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
            $query .= ' ORDER BY no_open ';
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
            $sub_array[] = $row->no_open;
            $sub_array[] = $row->nik;
            $sub_array[] = $row->tgl_input;
            $sub_array[] = $row->saldo_awal;
            $sub_array[] = $row->no_close;
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

function simpanOpenKasir(){
    session_start();
    getKoneksi();
    if($_COOKIE['isLogedIn'] AND cekAuth($_COOKIE['userLog'])){
        $kode_lokasi=$_POST['kode_lokasi'];    
        $nik=$_POST['nik_user'];
        $kode_pp=$_POST['kode_pp'];
        $exec = array();
        if(ISSET($_POST['no_open'])){
            $id=$_POST['no_open'];
            $sql2="delete from kasir_open where no_open='".$_POST['no_open']."' and kode_lokasi='".$kode_lokasi."' ";
            // $rs2=execute($sql2);
            array_push($exec,$sql2);

        }else{
            $str_format="0000";
            $periode=date('Y').date('m');
            $per=date('y').date('m');
            $prefix=$kode_lokasi."-OPN".$per.".";
            $sql2="select right(isnull(max(no_open),'0000'),".strlen($str_format).")+1 as id from kasir_open where no_open like '$prefix%' and kode_lokasi='".$_POST['kode_lokasi']."' ";
            
            $query = execute($sql2);
            
            $id = $prefix.str_pad($query->fields[0], strlen($str_format), $str_format, STR_PAD_LEFT);

        }


        $cek = dbResultArray("select*from kasir_open where nik='$nik' and no_close ='-'");
        if(count($cek) > 0){
            $tmp = "Gagal disimpan. Masih ada data open kasir yg belum closing";
        }else{
            
            $sql1= "insert into kasir_open (no_open,kode_lokasi,tgl_input,nik_user,nik,saldo_awal,no_close) values 
            ('".$id."','".$kode_lokasi."',getdate(),'".$nik."','".$_POST['nik']."',".joinNum2($_POST['saldo_awal']).",'-')";
            // $rs1=execute($sql1);
            array_push($exec,$sql1);
            
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

function getEditOpenKasir(){
    session_start();
    getKoneksi();
    if($_COOKIE['isLogedIn'] AND cekAuth($_COOKIE['userLog'])){
        $id=$_POST['kode'];    

        $response = array("message" => "", "rows" => 0, "status" => "" );

        $sql="select * from kasir_open where kode_lokasi='".$_POST['kode_lokasi']."' and no_open='$id' ";
        
        $rs = execute($sql);					
        
        while ($row = $rs->FetchNextObject(false)){
            $response['daftar'][] = (array)$row;
        }
        $response['status'] = TRUE;
        $response['sql'] = $sql;
    }else{
                
        $response["status"] = false;
        $response["message"] = "Unauthorized Access, Login Required";
    }
    // header('Content-Type: application/json');
    echo json_encode($response);

}

?>
