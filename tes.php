<?php

function db_Connect() { 
	$root=$_SERVER["DOCUMENT_ROOT"]."/";
	include_once($root."vendor/adodb7/adodb.inc.php");
	include_once($root."config/dbSetting.php");
	global $host;
	global $user;
	global $pass;
	global $database;
	global $driver;
	//define("CONN_DB",$database);
	//define("CONN_DBDRIVER",$driver);
	//define("CONN_HOST",$host);
	
	//$adoc = ADONewConnection($driver);
	//$connected = $adoc->PConnect($host, $user, $pass, $database); 		
	$adoc = adoNewConnection($driver);
	$connected = $adoc->connect($host, $user, $pass, $database); 		
	if (!$connected){
		error_log($adoc->ErrorMsg());
	}
	return $adoc;
}

function execute($sql, &$error) { 	
	$schema = db_Connect();
	$resultSet = $schema->execute($sql);
	$error = false;
	if (!$resultSet){
		error_log($schema->ErrorMsg());
		error_log($sql);
		echo "error::" . $schema->ErrorMsg();
		$error = true;
		return null;
	}else return $resultSet;
}

function executeArray($sql,&$err){
	try { 	
		$conn = db_Connect();
		//$conn->BeginTrans();
		$conn->beginTrans();
		foreach($sql as $x => $value) {
			$ok = $conn->execute($value);										
			if (!$ok) 
			{	
				error_log($value);
				throw new Exception($conn->ErrorMsg() . "\r\n" .$value);
			}
		}
		//$conn->CommitTrans();
		$conn->commitTrans();
		return true;
	} catch (exception $e) { 
		error_log($e->getMessage());
		$err=$e->getMessage();
		$conn->rollbackTrans();				
		return " error " .  $e->getMessage();
	} 		

}
/*
$exec_sql = array();

$sql="insert into fs( kode_fs, kode_lokasi) values('FS1','04')";
array_push($exec_sql,$sql);
var_dump($exec_sql);
$rs = executeArray($exec_sql,$err);
echo $err;				
*/
//echo $_SERVER["DOCUMENT_ROOT"]."/";
$root_lib=$_SERVER["DOCUMENT_ROOT"];
	if (substr($root_lib,-1)!="/") {
		$root_lib=$root_lib."/";
	}
echo $root_lib;

$sql="select kode_fs from fs ";
$rs = execute($sql,$error);
while ($row = $rs->FetchNextObject($toupper=false))
{
	
	echo "Nama :".$row->kode_fs."<br>";
	
}
echo "Proses Berhasi";

?>