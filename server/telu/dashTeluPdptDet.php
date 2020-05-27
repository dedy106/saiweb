<?php

    $request_method=$_SERVER["REQUEST_METHOD"];

    switch($request_method) {
        case 'GET':
            if(isset($_GET["fx"]) AND function_exists($_GET['fx'])){
                $_GET['fx']();
            }
        break;
        default:
            // Invalid Request Method
            header("HTTP/1.0 405 Method Not Allowed");
        break;
    }

    function getKoneksi(){
        $root_lib=$_SERVER["DOCUMENT_ROOT"];
		if (substr($root_lib,-1)!="/") {
			$root_lib=$root_lib."/";
		}
		include_once($root_lib."lib/koneksi5.php");
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

    function getPertumbuhan(){
        
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $tmp = explode("|",$_GET['param']);
            $kode_lokasi = $tmp[0];
            $kode_neraca = $tmp[1];
            $periode = $_GET['periode'];
            $bulan = substr($periode,4,2);

            // $sql = "select distinct substring(periode,1,4) as tahun from exs_neraca_pp where kode_lokasi='$kode_lokasi' and kode_fs='FS4' and kode_neraca='$kode_neraca' order by substring(periode,1,4) asc ";
            $sql = "SELECT
            tahun
            FROM
            (
                SELECT TOP 6 * from (
                select distinct substring(periode,1,4) as tahun
                FROM exs_neraca_pp 
                WHERE kode_lokasi='$kode_lokasi' and kode_fs='FS4' and kode_neraca='$kode_neraca'
                ) a
                ORDER BY tahun DESC
            ) SQ
            ORDER BY tahun ASC ";
            $rs = execute($sql);
            $sumcase = "";
            $kolom ="";
            $ctg = array();
            if($rs->RecordCount()> 0){
                $i=1;
                while($row = $rs->FetchNextObject($toupper=false)){
                    $sumcase .= " , sum(case when a.periode='".$row->tahun."".$bulan."' then (case when a.jenis_akun='Pendapatan' then -a.n4 else a.n4 end) else 0 end) as thn$i ";
                    $kolom .=",isnull(b.thn$i,0) as thn$i";

                    array_push($ctg,substr($row->tahun,2,2));
                    $i++;
                }
            }
            $response['ctg']=$ctg;
            
            $sql = "select a.kode_bidang,a.nama $kolom
            from bidang a 
            left join (select c.kode_bidang,a.kode_lokasi $sumcase
                        from exs_neraca_pp a
                        inner join db_grafik_d b on a.kode_neraca=b.kode_neraca and a.kode_lokasi=b.kode_lokasi and a.kode_fs=b.kode_fs
                        inner join pp c on a.kode_pp=c.kode_pp and a.kode_lokasi=c.kode_lokasi
                        where a.kode_lokasi='$kode_lokasi' and a.kode_fs='FS4' and b.kode_grafik='D04' and b.kode_neraca='$kode_neraca'
                        group by c.kode_bidang,a.kode_lokasi
                        )b on a.kode_bidang=b.kode_bidang and a.kode_lokasi=b.kode_lokasi
            where a.kode_lokasi='$kode_lokasi' and isnull(b.thn1,0)<>0
            order by a.kode_bidang";
            $response['sql']=$sql;
            $row = dbResultArray($sql);
            for($i=0;$i<count($row);$i++){
                $dt[$i] = array();
                $c=0;
                for($x=1;$x<=count($ctg);$x++){

                    // array_push($dt[$i],floatval($row[$i]["thn$x"]));  
                    $dt[$i][]=array("y"=>floatval($row[$i]["thn$x"]),"key"=>$row[$i]["kode_bidang"]."|".$ctg[$c]);
                    $c++;          
                }
            }

            // $color = array('#E5FE42','#007AFF','#4CD964','#FF9500');
            for($i=0;$i<count($row);$i++){

                $response["series"][$i]= array(
                    "name"=> $row[$i]['nama'], "data"=>$dt[$i]
                );
            }

          
            $response['status'] = TRUE;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }

    function getTablePend(){
        
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $tmp = explode("|",$_GET['param']);
            $kode_lokasi = $tmp[0];
            $periode = $_GET['periode'];
            $tahun= substr($periode,0,4);
            $kode_neraca=$tmp[1];
            $daftar =dbResultArray("
            select a.kode_bidang,a.nama,
                   isnull(b.n2,0) as n2,isnull(b.n4,0) as n4,isnull(b.n5,0) as n5,
                   case when isnull(b.n2,0)<>0 then (isnull(b.n4,4)/isnull(b.n2,0))*100 else 0 end as capai
            from bidang a 
            left join (select c.kode_bidang,a.kode_lokasi,
                              sum(case when a.jenis_akun='Pendapatan' then -a.n2 else a.n2 end) as n2,
                              sum(case when a.jenis_akun='Pendapatan' then -a.n4 else a.n4 end) as n4,
                              sum(case when a.jenis_akun='Pendapatan' then -a.n5 else a.n5 end) as n5
                        from exs_neraca_pp a
                        inner join db_grafik_d b on a.kode_neraca=b.kode_neraca and a.kode_lokasi=b.kode_lokasi and a.kode_fs=b.kode_fs
                        inner join pp c on a.kode_pp=c.kode_pp and a.kode_lokasi=c.kode_lokasi
                        where a.kode_lokasi='$kode_lokasi' and a.kode_fs='FS4' and b.kode_grafik='D04' and b.kode_neraca='$kode_neraca' and a.periode like '$tahun%'
                        group by c.kode_bidang,a.kode_lokasi
                       )b on a.kode_bidang=b.kode_bidang and a.kode_lokasi=b.kode_lokasi
            where a.kode_lokasi='$kode_lokasi' and isnull(b.n2,0)<>0
            order by a.kode_bidang");
            $response["daftar"] = $daftar;
            $response['status'] = TRUE;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }

    
?>