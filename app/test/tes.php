<?php

function db_Connect() { 
	$root=$_SERVER["DOCUMENT_ROOT"];
	include_once($root."vendor/adodb5/adodb-exceptions.inc.php");
	include_once($root."vendor/adodb5/adodb.inc.php");
	include_once($root."vendor/adodb5/adodb-xmlschema.inc.php" ); # or adodb-xmlschema03.inc.php
	include_once($root."config/dbSetting.php");
	global $host;
	global $user;
	global $pass;
	global $database;
	global $driver;
	define("CONN_DB",$database);
	define("CONN_DBDRIVER",$driver);
	define("CONN_HOST",$host);
	
	$adoc = ADONewConnection($driver);
	$connected = $adoc->PConnect($host, $user, $pass, $database); 				  		
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
echo $_SERVER["DOCUMENT_ROOT"];

$sql="select nama from lokasi ";
$rs = execute($sql,$error);
while ($row = $rs->FetchNextObject($toupper=false))
{
	
	echo "Nama :".$row->nama."<br>";
	
}
echo "Proses Berhasi";

?>