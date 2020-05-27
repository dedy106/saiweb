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
        case 'PUT':
            ubah();
        break;
        case 'DELETE':
            hapus();
        break;
        default:
            // Invalid Request Method
            header("HTTP/1.0 405 Method Not Allowed");
        break;
    }

    function getKoneksi(){
        $root_lib=$_SERVER["DOCUMENT_ROOT"];
        include_once($root_lib."lib/koneksi2.php");
        include_once($root_lib."lib/helpers.php");
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

    function hitungROI(){
        
        session_start();
        getKoneksi();
        
        $tgl_awal = '2017-07-01';
        $tgl_akhir = '2017-07-31';
        $sql = "select tanggal,roi_hari from inv_roi_total where kode_plan='1' and tanggal between '2019-07-01' and '2019-07-31'
        order by tanggal";
        $row=dbResultArray($sql);
        $series = array();
        $exec = array();
        $a=0;
        for($a=0;$a<count($row);$a++){
            $sql2 = "select DateDiff ( Day, '2019-07-01', '".$row[$a]['tanggal']."') as jum ";
            $rs2=execute($sql2);
            $jumlah_hari = $rs2->fields[0];
            $total = 1;
            if($jumlah_hari > 0){
                for($i=1;$i<$jumlah_hari+1;$i++){
                    $total = $total * $row[$i]['roi_hari'];
                    
                    $nil = $total;
                }
            }else{
                $nil = $row[$a]['roi_hari'];
            }
            $roi = ($nil)-1;
            array_push($series,$roi);
            $upd = "update inv_roi_total set roi_bulan = $roi where tanggal='".$row[$a]['tanggal']."' ";
            array_push($exec,$upd);
        }

        $sql3 = "select tanggal,roi_hari from inv_roi_total where kode_plan='1' and tanggal between '2019-01-01' and '2019-07-31'
        order by tanggal";
        $row2=dbResultArray($sql3);
        $series2 = array();
        $a=0;
        for($a=0;$a<count($row2);$a++){
            $sql4 = "select DateDiff ( Day, '2019-01-01', '".$row2[$a]['tanggal']."') as jum ";
            $rs3=execute($sql4);
            $jumlah_hari = $rs3->fields[0];
            $total = 1;
            if($jumlah_hari > 0){
                for($i=1;$i<$jumlah_hari+1;$i++){
                    $total = $total * $row2[$i]['roi_hari'];
                    
                    $nil = $total;
                }
            }else{
                $nil = $row2[$a]['roi_hari'];
            }
            $roi2 = ($nil)-1;
            array_push($series2,$roi2);
            $upd2 = "update inv_roi_total set roi_ytd = $roi2 where tanggal='".$row2[$a]['tanggal']."' ";
            array_push($exec,$upd2);
        }
        // $response["rows"]=count($row);
        // $response["series"]=$series;  
        // $response["series2"]=$series2; 
        // $response["exec"]=$exec;  
        // $response["exec2"]=$exec2; 
        // $response["ni"]=$i;
        $hasil = executeArray($exec);
        if($hasil){
            $msg = "Generate data berhasil";
            $sts = true;
        }else{
            $msg = "Generate data gagal";
            $sts = false;
        }  
        $response["status"] = $sts;
        $response["message"] = $msg;

        // header('Content-Type: application/json');
        echo json_encode($response);
    }
?>