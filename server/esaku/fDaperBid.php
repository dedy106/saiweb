<?php
    $request_method=$_SERVER["REQUEST_METHOD"];

    switch($request_method) {
        case 'GET':
            if(isset($_GET["fx"]) AND function_exists($_GET['fx'])){
                $_GET['fx']();
            }
        break;
    }

    function getKoneksi(){
        $root_lib=$_SERVER["DOCUMENT_ROOT"];
        include_once($root_lib."web/lib/koneksi.php");
        include_once($root_lib."web/lib/helpers.php");
    }

    function getBid(){
        getKoneksi();
        $kode_lokasi = $_GET['kode_lokasi'];  
        $result = array("message" => "", "rows" => 0, "status" => "" );                 
        $sql = "select kode_bidang,nama from sai_bidang where kode_lokasi ='$kode_lokasi' ";
        $rs = execute($sql);
        while($row = $rs->FetchNextObject($toupper))
        {
        
            $result['daftar'][] = (array)$row;
        
        }
                            
        $result['status'] = true;
        $result['sql'] = $sql;
        echo json_encode($result);
    }

    function getKod(){
        getKoneksi();
        $kode_lokasi = $_GET['kode_lokasi'];  
        $result = array("message" => "", "rows" => 0, "status" => "" );                 
        $sql = "select kode_akun, nama, jenis from sai_masakun";
        $rs = execute($sql);
        while($row = $rs->FetchNextObject($toupper))
        {
        
            $result['daftar'][] = (array)$row;
        
        }
                            
        $result['status'] = true;
        $result['sql'] = $sql;
        echo json_encode($result);
    }
?>