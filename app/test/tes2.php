<?php
$root=$_SERVER["DOCUMENT_ROOT"];
include_once($root."lib/koneksi.php");
$sql="select kode_lokasi,nama from lokasi ";
$rs = execute($sql,$error);
while ($row = $rs->FetchNextObject($toupper=false))
{
	
	echo "Nama :".$row->kode_lokasi."-".$row->nama."<br>";
	
}
echo "Proses Berhasi";

?>