<?php
$kode_lokasi=$_SESSION['lokasi'];
$periode=$_SESSION['periode'];
$kode_pp=$_SESSION['kodePP'];
$nik=$_SESSION['userLog'];
$fmain=$_SESSION['fMain'];

if($_SESSION['userStatus'] == 'A'){
    $path = "http://".$_SERVER["SERVER_NAME"]."/";	
    
    $logo = $path . "image/yspt2.png";
    
    $rs = execute("select nik, pass, nama
    from sis_hakakses a 
    left join sis_siswa b on a.nik=b.nis where a.kode_pp='$kode_pp'");
    
    while($row = $rs->FetchNextObject($toupper=false)){
        $daftar[]=(array)$row;
    }
    
    
    generateDaftar2(array("Username", "Password", "Nama"), array("nik", "pass", "nama"), $daftar, array('nik'));
}


?>
