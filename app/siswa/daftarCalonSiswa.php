<?php
$kode_lokasi=$_SESSION['lokasi'];
$periode=$_SESSION['periode'];
$kode_pp=$_SESSION['kodePP'];
$nik=$_SESSION['userLog'];

if($_SESSION['userStatus'] == 'A'){
    $path = "http://".$_SERVER["SERVER_NAME"]."/";	

    $logo = $path . "image/yspt2.png";

    $rs = execute("select a.nik, pass, b.nama
    from sis_siswareg b inner join sis_hakakses a on a.nik=b.no_reg and a.kode_lokasi=b.kode_lokasi  where a.kode_pp='$kode_pp'");

    while($row = $rs->FetchNextObject($toupper=false)){
        $daftar[]=(array)$row;
    }


    generateDaftar2(array("Username", "Password", "Nama"), array("nik", "pass", "nama"), $daftar, array('nik'));
}
?>