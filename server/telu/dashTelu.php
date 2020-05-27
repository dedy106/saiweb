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

    function getPencapaian(){
        
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $tmp = explode("|",$_GET['param']);
            $kode_lokasi = $tmp[0];
            $periode = $_GET['periode'];
            $daftar =dbResultArray("select a.kode_neraca,
            case when a.jenis_akun='Pendapatan' then -a.n1 else a.n1 end as n1,
            case when a.jenis_akun='Pendapatan' then -a.n4 else a.n4 end as n2,
            case when a.jenis_akun='Pendapatan' then -a.n5 else a.n5 end as n3,
            case when a.n1<>0 then (a.n4/a.n1)*100 else 0 end as capai
            from exs_neraca a
            inner join db_grafik_d b on a.kode_neraca=b.kode_neraca and a.kode_lokasi=b.kode_lokasi and a.kode_fs=b.kode_fs
            where a.kode_lokasi='$kode_lokasi' and a.kode_fs='FS4' and a.periode='$periode' and b.kode_grafik='D01'
            order by b.nu");
            $response["daftar"] = $daftar;
            $response['status'] = TRUE;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }

    function getRKAVSReal(){
        
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $tmp = explode("|",$_GET['param']);
            $kode_lokasi = $tmp[0];
            $periode = $_GET['periode'];
            $sql = "select a.kode_neraca,case when a.jenis_akun='Pendapatan' then -a.n2 else a.n2 end as n1,
            case when a.jenis_akun='Pendapatan' then -a.n4 else a.n4 end as n2,
            case when a.jenis_akun='Pendapatan' then -a.n5 else a.n5 end as n3,
            case when a.n2<>0 then (a.n4/a.n2)*100 else 0 end as capai
            from exs_neraca a
            inner join db_grafik_d b on a.kode_neraca=b.kode_neraca and a.kode_lokasi=b.kode_lokasi and a.kode_fs=b.kode_fs
            where a.kode_lokasi='$kode_lokasi' and a.kode_fs='FS4' and a.periode='$periode' and b.kode_grafik='D02'
            order by b.nu ";
            $res = execute($sql);
            $dt[0] = array();
            $dt[1] = array();
            $category = array();
            $i=0;
            while($row = $res->FetchNextObject($toupper=false)){
                
                array_push($dt[0],floatval($row->n1));
                array_push($dt[1],floatval($row->n2));               
                $i++;
            }
            
            $response["series"][0]= array(
                "name"=> 'RKA', "type"=>'column',"color"=>'#ad1d3e',"data"=>$dt[0]
            );

            $response["series"][1] = array(
                "name"=> 'Realisasi', "type"=>'column',"color"=>'#4c4c4c',"data"=>$dt[1]
            );
            $response['status'] = TRUE;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }

    function getGrowthRKA(){
        
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $tmp = explode("|",$_GET['param']);
            $kode_lokasi = $tmp[0];
            $periode = $_GET['periode'];
            $bulan = substr($periode,4,2);

            // $sql = "select distinct substring(periode,1,4) as tahun from exs_neraca where kode_lokasi='$kode_lokasi' and kode_fs='FS4' order by substring(periode,1,4) asc ";
            $sql = "SELECT
            tahun
            FROM
            (
                SELECT TOP 6 * from (
                select distinct substring(periode,1,4) as tahun
                FROM exs_neraca 
                WHERE kode_lokasi='$kode_lokasi' and kode_fs='FS4'
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
                    $sumcase .= " , sum(case when a.periode='".$row->tahun."".$bulan."' then (case when a.jenis_akun='Pendapatan' then -a.n2 else a.n2 end) else 0 end) as thn$i ";
                    $kolom .=",isnull(c.thn$i,0) as thn$i";

                    array_push($ctg,substr($row->tahun,2,2));
                    $i++;
                }
            }
            $response['ctg']=$ctg;
            
            $sql = "select a.kode_neraca,b.nama $kolom
            from db_grafik_d a
            inner join neraca b on a.kode_neraca=b.kode_neraca and a.kode_lokasi=b.kode_lokasi and a.kode_fs=b.kode_fs
            left join (select a.kode_neraca,a.kode_lokasi,a.kode_fs $sumcase                        
            from exs_neraca a
            inner join db_grafik_d b on a.kode_neraca=b.kode_neraca and a.kode_lokasi=b.kode_lokasi and a.kode_fs=b.kode_fs
            where a.kode_lokasi='$kode_lokasi' and a.kode_fs='FS4'  and b.kode_grafik='D02'
            group by a.kode_neraca,a.kode_lokasi,a.kode_fs
            )c on a.kode_neraca=c.kode_neraca and a.kode_lokasi=c.kode_lokasi and a.kode_fs=c.kode_fs
            where a.kode_grafik='D02' and a.kode_lokasi='$kode_lokasi' and a.kode_fs='FS4'";
            $response['sql']=$sql;
            $row = dbResultArray($sql);
            for($i=0;$i<count($row);$i++){
                $dt[$i] = array();
                for($x=1;$x<=count($ctg);$x++){

                    array_push($dt[$i],floatval($row[$i]["thn$x"]));             
                }
            }

            $color = array('#E5FE42','#007AFF','#4CD964','#FF9500');
            for($i=0;$i<count($row);$i++){

                if($row[$i]['kode_neraca'] == '47'){
                    $response["series"][$i]= array(
                        "name"=> $row[$i]['nama'], "color"=>$color[$i],"data"=>$dt[$i],"type"=>"spline", "marker"=>array("enabled"=>false)
                        
                    );
                }else{
                    
                    $response["series"][$i]= array(
                        "name"=> $row[$i]['nama'], "color"=>$color[$i],"data"=>$dt[$i],"type"=>"column", "dataLabels"=>array("enabled"=>true)
                        
                    );
                }
               
            }

          
            $response['status'] = TRUE;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }

    function getGrowthReal(){
        
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $tmp = explode("|",$_GET['param']);
            $kode_lokasi = $tmp[0];
            $periode = $_GET['periode'];
            $bulan = substr($periode,4,2);

            // $sql = "select distinct substring(periode,1,4) as tahun from exs_neraca where kode_lokasi='$kode_lokasi' and kode_fs='FS4' order by substring(periode,1,4) asc ";
            $sql = "SELECT
            tahun
            FROM
            (
                SELECT TOP 6 * from (
                select distinct substring(periode,1,4) as tahun
                FROM exs_neraca 
                WHERE kode_lokasi='$kode_lokasi' and kode_fs='FS4'
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
                    $kolom .=",isnull(c.thn$i,0) as thn$i";

                    array_push($ctg,substr($row->tahun,2,2));
                    $i++;
                }
            }
            $response['sql']=$sql;
            $response['ctg']=$ctg;

            $sql = "select a.kode_neraca,b.nama $kolom
            from db_grafik_d a
            inner join neraca b on a.kode_neraca=b.kode_neraca and a.kode_lokasi=b.kode_lokasi and a.kode_fs=b.kode_fs
            left join (select a.kode_neraca,a.kode_lokasi,a.kode_fs $sumcase                        
                        from exs_neraca a
                        inner join db_grafik_d b on a.kode_neraca=b.kode_neraca and a.kode_lokasi=b.kode_lokasi and a.kode_fs=b.kode_fs
                        where a.kode_lokasi='$kode_lokasi' and a.kode_fs='FS4'  and b.kode_grafik='D02'
                        group by a.kode_neraca,a.kode_lokasi,a.kode_fs
                        )c on a.kode_neraca=c.kode_neraca and a.kode_lokasi=c.kode_lokasi and a.kode_fs=c.kode_fs
            where a.kode_grafik='D02' and a.kode_lokasi='$kode_lokasi' and a.kode_fs='FS4'";
            $row = dbResultArray($sql);
            for($i=0;$i<count($row);$i++){
                $dt[$i] = array();
                for($x=1;$x<=count($ctg);$x++){

                    array_push($dt[$i],floatval($row[$i]["thn$x"]));             
                }
            }

            $color = array('#E5FE42','#007AFF','#4CD964','#FF9500');
            for($i=0;$i<count($row);$i++){

                if($i == 0){
                    $response["series"][$i]= array(
                        "name"=> $row[$i]['nama'], "color"=>$color[$i],"data"=>$dt[$i]
                    );
                }else{
                    
                    $response["series"][$i]= array(
                        "name"=> $row[$i]['nama'], "color"=>$color[$i],"data"=>$dt[$i],"visible"=> false
                    );
                }
               
            }

          
            $response['status'] = TRUE;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);

    }

    function getGrowthRKA2(){
        
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $tmp = explode("|",$_GET['param']);
            $kode_lokasi = $tmp[0];
            $periode = $_GET['periode'];
            $bulan = substr($periode,4,2);

            // $sql = "select distinct substring(periode,1,4) as tahun from exs_neraca where kode_lokasi='$kode_lokasi' and kode_fs='FS4' order by substring(periode,1,4) asc ";
            $sql = "SELECT
            tahun
            FROM
            (
                SELECT TOP 6 * from (
                select distinct substring(periode,1,4) as tahun
                FROM exs_neraca 
                WHERE kode_lokasi='$kode_lokasi' and kode_fs='FS4'
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
                    $sumcase .= " , sum(case when a.periode='".$row->tahun."".$bulan."' then (case when a.jenis_akun='Pendapatan' then -a.n2 else a.n2 end) else 0 end) as thn$i ";
                    $kolom .=",isnull(c.thn$i,0) as thn$i";

                    array_push($ctg,substr($row->tahun,2,2));
                    $i++;
                }
            }
            // $response['ctg']=$ctg;

            
            
            $sql = "select a.kode_neraca,b.nama $kolom
            from db_grafik_d a
            inner join neraca b on a.kode_neraca=b.kode_neraca and a.kode_lokasi=b.kode_lokasi and a.kode_fs=b.kode_fs
            left join (select a.kode_neraca,a.kode_lokasi,a.kode_fs $sumcase                        
            from exs_neraca a
            inner join db_grafik_d b on a.kode_neraca=b.kode_neraca and a.kode_lokasi=b.kode_lokasi and a.kode_fs=b.kode_fs
            where a.kode_lokasi='$kode_lokasi' and a.kode_fs='FS4'  and b.kode_grafik='D02'
            group by a.kode_neraca,a.kode_lokasi,a.kode_fs
            )c on a.kode_neraca=c.kode_neraca and a.kode_lokasi=c.kode_lokasi and a.kode_fs=c.kode_fs
            where a.kode_grafik='D02' and a.kode_lokasi='$kode_lokasi' and a.kode_fs='FS4'";
            // $response['sql']=$sql;
            $row = dbResultArray($sql);
            $c=1;
            for($x=0;$x<count($ctg);$x++){
                $dt[$x]["c"][] = array("v"=>$ctg[$x],"f"=>null);
                for($i=0;$i<count($row);$i++){
                    $dt[$x]["c"][] = array("v"=>floatval($row[$i]["thn$c"])/1000000000,"f"=>null);
                }
                $c++;
            }

            $color = array('#E5FE42','#007AFF','#4CD964','#FF9500');
            $response['cols'][0] = array("id"=>"","label"=>"Year","pattern"=>"","type"=>"string");
            $c = 0;
            for($i=1;$i<=count($row);$i++){

                // $response["series"][$i]= array(
                //     "name"=> $row[$i]['nama'], "type"=>'line',"color"=>$color[$i],"data"=>$dt[$i]
                // );
                $response['cols'][$i] = array("id"=>"","label"=>$row[$c]['nama'],"pattern"=>"","type"=>"number");
            
                $c++;
            }
            $response['rows']=$dt;

            
          
            // $response['status'] = TRUE;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }

?>