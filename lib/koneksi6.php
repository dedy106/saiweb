<?php

function db_Connect() { 
		$root=$_SERVER["DOCUMENT_ROOT"];
		include_once($root."vendor/adodb5/adodb-exceptions.inc.php");
		include_once($root."vendor/adodb5/adodb.inc.php");
		include_once($root."vendor/adodb5/adodb-xmlschema.inc.php" ); # or adodb-xmlschema03.inc.php
		include_once($root."config/dbSetting6.php");
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
		// $res = array('driver'=>$driver,'host'=>$host,'database'=>$database,'user'=>$user,'pass'=>$pass);
		// return $res;
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


function dbResultArray($sql){
	$schema = db_Connect();
    
    $query = $schema->Execute($sql);

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
		$conn->BeginTrans();
		foreach($sql as $x => $value) {
			$ok = $conn->Execute($value);										
			if (!$ok) 
			{	
				error_log($value);
				throw new Exception($conn->ErrorMsg() . "\r\n" .$value);
			}
		}
		$conn->CommitTrans();
		return true;
	} catch (exception $e) { 
		error_log($e->getMessage());
		$err=$e->getMessage();
		$conn->RollbackTrans();				
		return " error " .  $e->getMessage();
	} 		

}


?>