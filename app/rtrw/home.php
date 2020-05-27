<?php

function getKoneksi(){
    $root_lib=$_SERVER["DOCUMENT_ROOT"];
    include_once($root_lib."lib/koneksi.php");
    include_once($root_lib."lib/helpers.php");
}

$nik = $_GET['nik'];
// $kode_lokasi=null;
$kode_lokasi = $_GET['kode_lokasi'];

// getKoneksi();
// $data = $_POST;
// if(arrayKeyCheck(array('api_key', 'username', 'kode_lokasi','periode'), $data)){
//     if(authKey($data["api_key"], 'RTRW', $data['username'])){ 

//         $kode_lokasi=$_POST['kode_lokasi'];
//         $response = array("message" => "", "rows" => 0, "status" => "" );

//         if($_POST['jenis'] == 'keluar'){
//             $jenis="PENGELUARAN";
//         }else{
//             $jenis="PENERIMAAN";
//         }
//         $sql="select a.kode_ref, a.nama 
//         from trans_ref a inner join karyawan_pp b on a.kode_pp=b.kode_pp and a.kode_lokasi=b.kode_lokasi and b.nik='".$_nik."' 
//         where a.jenis='$jenis' and a.kode_lokasi='$kode_lokasi'";
        
//         $rs = execute($sql);					
        
//         while ($row = $rs->FetchNextObject(false)){
//             $response['daftar'][] = (array)$row;
//         }
//         $response['status']=true;
//     }else{
//         $response['status']=false;
//         $response['message'] = "Unauthorized Access 2";
//     }
// }else{
//     $response['status']=false;
//     $response['message'] = "Unauthorized Access 1";
    
// }
// header('Content-Type: application/json');
// echo json_encode($response);

// echo $percobaan;
getKoneksi();
// $kode_lokasi=$_POST['kode_lokasi'];
$response = array("message" => "", "rows" => 0, "status" => "" );

// if($_POST['jenis'] == 'keluar'){
    // $jenis="PENGELUARAN";
// }else{
    $jenis="PENERIMAAN";
// }
$sql="select a.kode_ref, a.nama 
from trans_ref a inner join karyawan_pp b on a.kode_pp=b.kode_pp and a.kode_lokasi=b.kode_lokasi and b.nik='".$nik."' 
where a.jenis='$jenis' and a.kode_lokasi='$kode_lokasi'";

// $rs = execute($sql);					
$rs=execute($sql);
    $row = $rs->FetchNextObject(false);
    echo $row->kode_ref;
    // $akunDebet=$row->akun_debet;
    // $akunKredit=$row->akun_kredit;
// while ($row = $rs->FetchNextObject(false)){
//     $response['daftar'][] = (array)$row;
// }
// $response['status']=true;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<?php 
echo $nik.'<br>';
echo $kode_lokasi;?>
    Percobaan
</body>
</html>