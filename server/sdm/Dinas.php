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
            hapusDinas();
        break;
    default:
        // Invalid Request Method
        header("HTTP/1.0 405 Method Not Allowed");
    break;
}


function getKoneksi(){
    $root=realpath($_SERVER["DOCUMENT_ROOT"])."/";
    include_once($root."lib/koneksi.php");
    include_once($root."lib/helpers.php");
}
    
function cekAuth($user,$pass){
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

function getDinas(){
    session_start();
    getKoneksi();
    if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
        $nik = $_GET['nik_user'];
        $query = '';
        $response = array();
        
        $kode_lokasi = $_REQUEST['kode_lokasi'];
        $query .= "select no_sk,convert(varchar,tgl_sk,103) as tgl,nama from hr_sk where kode_lokasi='$kode_lokasi' and nik='$nik'   ";
        
        $column_array = array('no_sk','convert(varchar,tgl_sk,103) as tgl','nama');
        $order_column = 'ORDER BY no_sk '.$_GET['order'][0]['dir'];
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
            $query .= ' ORDER BY no_sk ';
        }
        if($_GET["length"] != -1)
        {
            $query .= ' OFFSET ' . $_GET['start'] . ' ROWS FETCH NEXT ' . $_GET['length'] . ' ROWS ONLY ';
        }
        $statement = execute($query);
        $data = array();
        $no=1;
        $filtered_rows = $statement->RecordCount();
        while($row = $statement->FetchNextObject($toupper=false))
        {
            $sub_array = array();
            
            $sub_array[] = $no;
            $sub_array[] = $row->no_sk;
            $sub_array[] = $row->tgl;
            $sub_array[] = $row->nama;
            $data[] = $sub_array;
            $no++;
        }
        $response = array(
            "draw"				=>	intval($_GET["draw"]),
            "recordsTotal"		=> 	$filtered_rows,
            "recordsFiltered"	=>	$jml_baris,
            "data"				=>	$data,
        );
    }else{
        $response["status"] = false;
        $response["message"] = "Unauthorized Access, Login Required";
    }
    // header('Content-Type: application/json');
    echo json_encode($response);
       

}

function simpanDinas(){
    session_start();
    getKoneksi();
    if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
        
        $nik=$_POST['nik'];
        $kode_lokasi=$_POST['kode_lokasi'];    
        $exec = array();

        $response = array("message" => "", "rows" => 0, "status" => "" );

        if($_POST['id_edit'] == "1"){
    
            $sqldel1="delete from hr_sk where no_sk='".$_POST['no_sk']."' and nik='".$nik."' and kode_lokasi='".$kode_lokasi."' ";
            array_push($exec,$sqldel1);
        }

        $sqlnu= "select max(nu) as nu from hr_sk where nik='$nik' and kode_lokasi='$kode_lokasi'  ";
        $rsnu=execute($sqlnu);

        if($rsnu->RecordCount() > 0){
            $nu = $rsnu->fields[0] + 1;
        }else{
            $nu = 0;
        }

        $sql1 = "insert into hr_sk(nik,kode_lokasi,nu,no_sk,nama,tgl_sk) values ('".$nik."','".$kode_lokasi."',".$nu.",'".$_POST['no_sk']."','".$_POST['nama']."','".$_POST['tgl_sk']."')";

        array_push($exec,$sql1);

        $rs=executeArray($exec);

        $tmp=array();
        $kode = array();

        if ($rs)
        {	
            $tmp="Sukses disimpan";
            $sts=true;
        }else{

            $tmp="Gagal disimpan";
            $sts=false;
        }		
        $response["message"] =$tmp;
        $response["status"] = $sts;
        $response["sql"]=$exec;
    }else{
        $response["status"] = false;
        $response["message"] = "Unauthorized Access, Login Required";
    }
    // header('Content-Type: application/json');
    echo json_encode($response);

}

function hapusDinas(){
    session_start();
    getKoneksi();
    parse_str(file_get_contents('php://input'), $_DELETE);
    $data = $_DELETE;
    if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
        
        $exec = array();
        $sql1="delete from hr_sk where no_sk='".$data['id']."' and kode_lokasi='".$data['kode_lokasi']."' and nik='".$data['nik']."' ";
        array_push($exec,$sql1);
        $rs=executeArray($exec);

        $tmp=array();
        $kode = array();
        if ($rs)
        {	
            $tmp="sukses";
            $sts=true;
        }else{
            $tmp="gagal";
            $sts=false;
        }		
        $response["message"] =$tmp;
        $response["status"] = $sts;
        $response["sql"] = $sql;
    }else{
        $response["status"] = false;
        $response["message"] = "Unauthorized Access, Login Required";
    }
    header('Content-Type: application/json');
    echo json_encode($response);

}



?>
