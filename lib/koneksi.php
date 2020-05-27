<?php

function db_Connect() { 
	//$root=$_SERVER["DOCUMENT_ROOT"]."/";
	/*
	$root_lib=$_SERVER["DOCUMENT_ROOT"];
	if (substr($root_lib,-1)!="/") {
		$root_lib=$root_lib."/";
	}
	*/
	$root_lib=$root=$_SERVER["DOCUMENT_ROOT"]."/";
	//include_once($root_lib."vendor/adodb7/adodb-exceptions.inc.php");
	include_once($root_lib."vendor/adodb7/adodb.inc.php");
	//include_once($root_lib."vendor/adodb7/adodb-xmlschema.inc.php" ); # or adodb-xmlschema03.inc.php
	include_once($root_lib."config/dbSetting.php");
	global $host;
	global $user;
	global $pass;
	global $database;
	global $driver;
	define("CONN_DB",$database);
	define("CONN_DBDRIVER",$driver);
	define("CONN_HOST",$host);
	
	//$adoc = ADONewConnection($driver);
	//$connected = $adoc->PConnect($host, $user, $pass, $database); 
	$adoc = adoNewConnection($driver);
	$connected = $adoc->connect($host, $user, $pass, $database); 		
	if (!$connected){
		error_log($adoc->ErrorMsg());
	}
	return $adoc;
}

function execute($sql) { 	
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


function dbResultArray($sql){
	$schema = db_Connect();
    
    $query = $schema->execute($sql);

    if($query){
        while($row = $query->FetchNextObject($toupper = false)){
            $option[] = (array)$row;
        }

        if(ISSET($option)){
            return $option;
        }else{
            return array();
        }
    }else{
        exit($schema->ErrorMsg());
    }

}

function dbRowArray($sql){
	$schema = db_Connect();

    $query = $schema->SelectLimit($sql,1);
    if($query){
        $option = $query->GetRowAssoc(false);

        if(ISSET($option)){
            return $option;
        }else{
            return null;
        }
    }else{
        exit($schema->ErrorMsg());
    }
}

function qstr($string){
    return db_Connect()->qstr($string);
}


function executeArray($sql, &$err){
	try { 	
		$conn = db_Connect();
		$conn->beginTrans();
		foreach($sql as $x => $value) {
			$ok = $conn->execute($value);										
			if (!$ok) 
			{	
				error_log($value);
				throw new Exception($conn->ErrorMsg() . "\r\n" .$value);
			}
		}
		$conn->commitTrans();
		return true;
	} catch (exception $e) { 
		error_log($e->getMessage());
		$err=$e->getMessage();
		$conn->rollbackTrans();				
		return " error " .  $e->getMessage();
	} 		

}


?>