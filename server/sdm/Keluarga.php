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
            hapusKeluarga();
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


function getKeluarga(){
    session_start();
    getKoneksi();
    if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
    
        $nik = $_POST['nik_user'];
        $query = '';
        $response = array();
        
        $kode_lokasi = $_REQUEST['kode_lokasi'];
        $query .= "select nama,case when jk = 'P' then 'Perempuan' else 'Laki-laki' end as jk,case when status_kes = 'Y' then 'Ya' else 'Tidak' end as status_kes,case when jenis = 'I' then 'Istri' when jenis= 'A' then 'Anak' else 'Suami' end as jenis,tempat,convert(varchar,tgl_lahir,103) as tgl from hr_keluarga 
        where kode_lokasi='$kode_lokasi' and nik='$nik' ";
        
        $column_array = array('nama','jk','status_kes','jenis','tempat','convert(varchar,tgl_lahir,103)');
        $order_column = 'ORDER BY nama '.$_POST['order'][0]['dir'];
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
            $query .= ' ORDER BY nama ';
        }
        if($_POST["length"] != -1)
        {
            $query .= ' OFFSET ' . $_POST['start'] . ' ROWS FETCH NEXT ' . $_POST['length'] . ' ROWS ONLY ';
        }
        $statement = execute($query);
        $data = array();
        $no=1;
        $filtered_rows = $statement->RecordCount();
        while($row = $statement->FetchNextObject($toupper=false))
        {
            $sub_array = array();
            
            $sub_array[] = $no;
            $sub_array[] = $row->nama;
            $sub_array[] = $row->jk;
            $sub_array[] = $row->status_kes;
            $sub_array[] = $row->jenis;
            $sub_array[] = $row->tempat;
            $sub_array[] = $row->tgl;
            $data[] = $sub_array;
            $no++;
        }
        $response = array(
            "draw"				=>	intval($_POST["draw"]),
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

function simpanKeluarga(){
    session_start();
    getKoneksi();
    if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
        $nik=$_POST['nik'];
        $kode_lokasi=$_POST['kode_lokasi'];    

        $response = array("message" => "", "rows" => 0, "status" => "" );

        if($_POST['id_edit'] == "1"){
    
            $sqldel1="delete from hr_keluarga where nama='".$_POST['nama']."' and nik='".$nik."' and kode_lokasi='".$kode_lokasi."' ";
        }

        $sqlnu= "select max(nu) as nu from hr_keluarga where nik='$nik' and kode_lokasi='$kode_lokasi'  ";
        $rsnu=execute($sqlnu);

        if($rsnu->RecordCount() > 0){
            $nu = $rsnu->fields[0] + 1;
        }else{
            $nu = 0;
        }

        $sql1 = "insert into hr_keluarga(nik,kode_lokasi,jenis,nama,jk,tempat,tgl_lahir,status_kes,foto) values ('".$nik."','".$kode_lokasi."','".$_POST['jenis']."','".$_POST['nama']."','".$_POST['jk']."','".$_POST['tempat']."','".$_POST['tgl_lahir']."','".$_POST['tgg']."','-')";

        if($_POST['id_edit']){
            $sql=[$sqldel1,$sql1];
        }else{
            $sql=[$sql1];
        }
        
        $rs=executeArray($sql);

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
        $response["sql"]=$sql;
        $response["error"]=$error_upload;

    }else{
        $response["status"] = false;
        $response["message"] = "Unauthorized Access, Login Required";
    }
    // header('Content-Type: application/json');
    echo json_encode($response);
}

function getEditKeluarga(){
    session_start();
    getKoneksi();
    if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
        $id=$_POST['kode'];
        $kode_lokasi=$_POST['kode_lokasi'];    
        $nik=$_POST['nik'];

        $response = array("message" => "", "rows" => 0, "status" => "" );

        $sql="select *,convert(varchar,tgl_lahir,23) as tgl from hr_keluarga 
        where kode_lokasi='$kode_lokasi' and nik='$nik' and nama = '$id' ";
        
        $rs = execute($sql);

        $response['daftar'] = array();

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

function hapusKeluarga(){
    session_start();
    getKoneksi();
    if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){

        $sql1="delete from hr_keluarga where nama='".$_POST['id']."' and kode_lokasi='".$_POST['kode_lokasi']."' and nik='".$_POST['nik']."' ";

        $sql=[$sql1];

        $rs=executeArray($sql);

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
    // header('Content-Type: application/json');
    echo json_encode($response);
   
}


?>
