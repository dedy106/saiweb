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
		if (substr($root_lib,-1)!="/") {
			$root_lib=$root_lib."/";
		}
		include_once($root_lib."lib/koneksi.php");
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

    function getHasil(){
        session_start();
        $response["message"] = "tanpa sql";
        echo json_encode($response);
    }

    function getPeriode2(){
        session_start();
        getKoneksi();
        $data=$_GET;
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'],$_SESSION['userPwd'])){             
            $kode_lokasi = $_SESSION['lokasi'];
            $perusahan = dbResultArray("select distinct periode from periode where kode_lokasi='$kode_lokasi' ");
            $response["daftar"] = $perusahan;
        } else{
            $response["message"] = "Unauthorized Access, Login Required";
        }     
        $response["status"]=true; 
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    function getTopSelling(){
        session_start();
        getKoneksi();
        $data=$_GET;
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'],$_SESSION['userPwd'])){             
            $kode_lokasi = $_SESSION['lokasi'];
			$sql="select top 5 a.kode_barang,a.nama,isnull(b.jumlah,0) as jumlah
            from brg_barang a
            left join (select a.kode_barang,a.kode_lokasi,count(a.kode_barang) as jumlah
            from brg_trans_dloc a
            where a.kode_lokasi='$kode_lokasi' and a.modul='BRGJUAL'
            group by a.kode_barang,a.kode_lokasi
                    )b on a.kode_barang=b.kode_barang and a.kode_lokasi=b.kode_lokasi
            where a.kode_lokasi='$kode_lokasi'
            order by jumlah desc";
            $sell = dbResultArray($sql);
			$response["daftar"] = $sell;
            //$response["daftar"] = $sell;
        } else{
            $response["message"] = "Unauthorized Access, Login Required";
        }     
        $response["status"]=true; 
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    function getSellingCtg(){
        session_start();
        getKoneksi();
        $data=$_GET;
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'],$_SESSION['userPwd'])){             
            $kode_lokasi = $_SESSION['lokasi'];

            $col = dbResultArray("select distinct LTRIM(RTRIM(b.kode_klp)) as kode_klp
            from brg_trans_dloc a
            inner join brg_barang b on a.kode_barang=b.kode_barang and a.kode_lokasi=b.kode_lokasi
            where a.kode_lokasi='$kode_lokasi' and a.modul='BRGJUAL' ");
            $grouping = array();
            $series = array();
            foreach($col as $row){
                // $ctg[] = $row2["kode_klp"];
                $kode_klp= trim($row["kode_klp"],'\r\n');
                $kode_klp= trim($kode_klp," ");
                
                // $sql[$kode_klp] = "select a.kode_klp,a.nama,isnull(b.jumlah,0) as jumlah,b.tgl_ed
                // from brg_barangklp a
                // left join (select b.kode_klp,a.kode_lokasi,a.tgl_ed,count(a.kode_barang) as jumlah
                // from brg_trans_dloc a
                // inner join brg_barang b on a.kode_barang=b.kode_barang and a.kode_lokasi=b.kode_lokasi
                // where a.kode_lokasi='$kode_lokasi' and a.modul='BRGJUAL' and b.kode_klp ='".$kode_klp."'
                // group by b.kode_klp,a.kode_lokasi,a.tgl_ed
                //         )b on a.kode_klp=b.kode_klp and a.kode_lokasi=b.kode_lokasi
                // where a.kode_lokasi='$kode_lokasi' and isnull(b.jumlah,0)>0
                // order by a.kode_klp,b.tgl_ed ";
                $sql[$kode_klp] = "select distinct a.tgl_ed,isnull(b.jumlah,0) as jumlah from brg_trans_dloc a
				left join (select b.kode_klp,a.kode_lokasi,a.tgl_ed,count(a.kode_barang) as jumlah
                from brg_trans_dloc a
                inner join brg_barang b on a.kode_barang=b.kode_barang and a.kode_lokasi=b.kode_lokasi
                where a.kode_lokasi='$kode_lokasi' and a.modul='BRGJUAL' and b.kode_klp='$kode_klp' 
                group by b.kode_klp,a.kode_lokasi,a.tgl_ed
                        )b on a.tgl_ed=b.tgl_ed and a.kode_lokasi=b.kode_lokasi
				where a.kode_lokasi='$kode_lokasi' and modul='BRGJUAL' 
				order by a.tgl_ed ";
                $sell = dbResultArray($sql[$kode_klp]);
                foreach($sell as $row1){
                    $sellctg[$kode_klp][] =floatval($row1["jumlah"]);
                    
                }
                $series[] = array("name"=>$kode_klp,"data"=>$sellctg[$kode_klp]);
            }

            $sql1 = "select distinct tgl_ed from brg_trans_dloc where kode_lokasi='$kode_lokasi' and modul='BRGJUAL' order by tgl_ed
            ";
            $rs = dbResultArray($sql1);
           
            foreach($rs as $row2){
                $ctg[] = $row2["tgl_ed"];
            }

           
            $response["daftar"] = $series;
            $response["ctg"] = $ctg;
            $response["sql"]=$sql;
        } else{
            $response["message"] = "Unauthorized Access, Login Required";
        }     
        $response["status"]=true; 
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    function getTopVendor(){
        session_start();
        getKoneksi();
        $data=$_GET;
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'],$_SESSION['userPwd'])){             
            $kode_lokasi = $_SESSION['lokasi'];
            $sell = dbResultArray("select top 5 a.kode_vendor,a.nama,isnull(b.total,0) as total
            from vendor a
            left join (select b.param2,a.kode_lokasi,sum(a.total) as total
            from brg_trans_d a
            inner join trans_m b on a.no_bukti=b.no_bukti and a.kode_lokasi=b.kode_lokasi
            where a.kode_lokasi='$kode_lokasi' and a.modul='BRGBELI'
            group by b.param2,a.kode_lokasi
                    )b on a.kode_vendor=b.param2 and a.kode_lokasi=b.kode_lokasi
            where a.kode_lokasi='$kode_lokasi' and isnull(b.total,0)>0
            order by total desc");

            $response["daftar"] = $sell;
        } else{
            $response["message"] = "Unauthorized Access, Login Required";
        }     
        $response["status"]=true; 
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    function getDataBox(){
        session_start();
        getKoneksi();
        $data=$_GET;
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'],$_SESSION['userPwd'])){             
            $kode_lokasi = $_SESSION['lokasi'];
            $periode = $data["periode"];
			$sql="select a.kode_grafik,a.nama,case format when 'Satuan' then isnull(b.nilai,0) when 'Ribuan' then isnull(b.nilai/1000,0) when 'Jutaan' then isnull(b.nilai/1000000,0) end as nilai 
                    from db_grafik_m a
                    left join (select a.kode_grafik,a.kode_lokasi,sum(case when b.jenis_akun='Pendapatan' then -b.n4 else b.n4 end) as nilai
                               from db_grafik_d a
                               inner join exs_neraca b on a.kode_lokasi=b.kode_lokasi and a.kode_neraca=b.kode_neraca and a.kode_fs=b.kode_fs
                               where a.kode_lokasi='$kode_lokasi' and b.periode='$periode' and a.kode_fs='FS1'
                               group by a.kode_grafik,a.kode_lokasi
                              )b on a.kode_grafik=b.kode_grafik and a.kode_lokasi=b.kode_lokasi
                    where a.kode_grafik in ('DB11') and a.kode_lokasi='$kode_lokasi' ";
            $sell = dbRowArray($sql);
            $response["cash"] = $sell["nilai"];
			//$response["cash"] = $sql;
            $sell2 = dbRowArray("
            select a.kode_grafik,a.nama,case format when 'Satuan' then isnull(b.nilai,0) when 'Ribuan' then isnull(b.nilai/1000,0) when 'Jutaan' then isnull(b.nilai/1000000,0) end as nilai 
                    from db_grafik_m a
                    left join (select a.kode_grafik,a.kode_lokasi,sum(case when b.jenis_akun='Pendapatan' then -b.n4 else b.n4 end) as nilai
                               from db_grafik_d a
                               inner join exs_neraca b on a.kode_lokasi=b.kode_lokasi and a.kode_neraca=b.kode_neraca and a.kode_fs=b.kode_fs
                               where a.kode_lokasi='$kode_lokasi' and b.periode='$periode' and a.kode_fs='FS1'
                               group by a.kode_grafik,a.kode_lokasi
                              )b on a.kode_grafik=b.kode_grafik and a.kode_lokasi=b.kode_lokasi
                    where a.kode_grafik in ('DB13') and a.kode_lokasi='$kode_lokasi' ");

            $response["pend"] = $sell2["nilai"];
        } else{
            $response["message"] = "Unauthorized Access, Login Required";
        }     
        $response["status"]=true; 
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    function getAkun() {
        session_start();
        getKoneksi();
        $data=$_GET;
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'],$_SESSION['userPwd'])){             
            $kode_lokasi = $_SESSION['lokasi'];  
            $periode = $_GET['periode'];
            $response = array("message" => "", "rows" => 0, "status" => "" ); 				
            $res = execute("
            select 'All' as kode_akun, 'All' as nama 
            union all
            select distinct c.kode_akun,d.nama
            from db_grafik_d a
            inner join relakun b on a.kode_neraca=b.kode_neraca and a.kode_lokasi=b.kode_lokasi and a.kode_fs=b.kode_fs 
            inner join exs_glma c on b.kode_akun=c.kode_akun and b.kode_lokasi=c.kode_lokasi
            inner join masakun d on c.kode_akun=d.kode_akun and c.kode_lokasi=d.kode_lokasi
            inner join db_grafik_m e on a.kode_grafik=e.kode_grafik and a.kode_lokasi=e.kode_lokasi
            where c.kode_lokasi='$kode_lokasi' and b.kode_fs='FS1' and c.periode='$periode' and a.kode_grafik in ('DB11')  and c.so_akhir<>0 
            ");
            
            while($row = $res->FetchNextObject($toupper))
            {
                $response['daftar'][] = (array)$row;
            }
            
            $response['status'] = true;
        }else{
            $response["message"] = "Unauthorized Access, Login Required";
        }
        
        header('Content-Type: application/json');
        echo json_encode($response);

    }

    function getBukuBesar() {
        session_start();
        getKoneksi();
        $data=$_GET;
        
         if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'],$_SESSION['userPwd'])){             
                $kode_lokasi = $_SESSION['lokasi'];
                $periode = $data['periode'];
                $response= array();
                
                $tmp = explode("|",$data["param"]);
                $data["kode_akun"] = $tmp[0];
                $data["tgl1"]=$tmp[1];
                $data["tgl2"]=$tmp[2];
                if($data["kode_akun"] == "All" OR $data["kode_akun"] == ""){
                    $kode_akun="";
                    $filterakun="";
                }else{
                    $kode_akun=$data["kode_akun"];
                    $filterakun=" and c.kode_akun='$kode_akun' ";
                }

                $sql="select c.kode_lokasi,c.kode_akun,d.nama,c.so_awal,c.periode,case when c.so_awal>=0 then c.so_awal else 0 end as so_debet,case when c.so_awal<0 then c.so_awal else 0 end as so_kredit
                from db_grafik_d a
                inner join relakun b on a.kode_neraca=b.kode_neraca and a.kode_lokasi=b.kode_lokasi and a.kode_fs=b.kode_fs 
                inner join exs_glma c on b.kode_akun=c.kode_akun and b.kode_lokasi=c.kode_lokasi
                inner join masakun d on c.kode_akun=d.kode_akun and c.kode_lokasi=d.kode_lokasi
                inner join db_grafik_m e on a.kode_grafik=e.kode_grafik and a.kode_lokasi=e.kode_lokasi
                where c.kode_lokasi='$kode_lokasi' and b.kode_fs='FS1' and c.periode='$periode' and a.kode_grafik in ('DB11') and c.so_akhir<>0 $filterakun 
                order by c.kode_akun";
                
                $rs=execute($sql);
        
                $response["daftar"] = array();

                $tahun = substr($periode,0,4);
                $bulan = substr($periode,5,2);
                
                $sql2 = "SELECT DATEADD(s,-1,DATEADD(mm, DATEDIFF(m,0,'$tahun-$bulan-01')+1,0))";
                
                $rs2=execute($sql2);
                $temp = explode(" ",$rs2->fields['0']);
                $tgl_akhir=$temp[0];

                if($data["tgl1"] == "" AND $data["tgl2"] == ""){
                    $filtertgl="";
                }else if ($data["tgl1"] != ""  AND $data["tgl2"] == ""){
                    $filtertgl=" and a.tanggal between '".$data["tgl1"]."' AND '".$tgl_akhir."' ";
                }else if ($data["tgl1"] == "" AND $data["tgl2"] != ""){
                    $filtertgl=" and a.tanggal between '$tahun-$bulan-01' AND '".$data["tgl2"]."' ";
                }else{
                    $filtertgl=" and a.tanggal between '".$data["tgl1"]."' AND '".$data["tgl2"]."' ";
                }

                $hasil = array();
                $result["daftar2"] = array();
                while($row = $rs->FetchNextObject($toupper = false)){
                    $response["daftar"][] = (array)$row;
                    $sqlx = "select a.no_bukti,convert(varchar,a.tanggal,103) as tgl,a.kode_akun,a.keterangan,a.kode_pp,
                    case when a.dc='D' then a.nilai else 0 end as debet,case when a.dc='C' then a.nilai else 0 end as kredit,a.kode_drk,a.no_dokumen
                    from gldt a
                    where a.kode_lokasi='$kode_lokasi' and a.periode='$periode' $filtertgl and a.kode_akun = '$row->kode_akun'
                    order by a.tanggal,a.no_bukti,a.dc ";
                    $rs1= execute($sqlx);

                    while($row = $rs1->FetchNextObject($toupper = false)){
                        $hasil[] = (array)$row;
                    }
                }


                // $sql2="select a.no_bukti,convert(varchar,a.tanggal,103) as tgl,a.kode_akun,a.keterangan,a.kode_pp,
                // case when a.dc='D' then a.nilai else 0 end as debet,case when a.dc='C' then a.nilai else 0 end as kredit,a.kode_drk,a.no_dokumen
                // from gldt a
                // where a.kode_lokasi='$kode_lokasi' and a.periode='$periode' $filtertgl 
                // order by a.tanggal,a.no_bukti,a.dc";
                // $rs1 = execute($sql2);
                // $response["daftar2"] = array();
                // while($row = $rs1->FetchNextObject($toupper = false)){
                //     $response["daftar2"][] = (array)$row;
                // }

                $response["daftar2"]=$hasil;

                $response['status'] = true;
                $response['sql'] = $sql;
            } else{
                $response["message"] = "Unauthorized Access, Login Required"; 
            }
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    function getIncomeChart() {
        session_start();
        getKoneksi();
        $data=$_GET;
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'],$_SESSION['userPwd'])){             
            $kode_lokasi = $_SESSION['lokasi'];  
            $periode = $_GET['periode'];
            $response = array("message" => "", "rows" => 0, "status" => "" ); 				
           
            $sql = "select a.kode_lokasi,
            sum(case when substring(a.periode,5,2)='01' then (case when a.jenis_akun='Pendapatan' then -a.n4 else a.n4 end) else 0 end) n1,
            sum(case when substring(a.periode,5,2)='02' then (case when a.jenis_akun='Pendapatan' then -a.n4 else a.n4 end) else 0 end) n2,   
            sum(case when substring(a.periode,5,2)='03' then (case when a.jenis_akun='Pendapatan' then -a.n4 else a.n4 end) else 0 end) n3,
            sum(case when substring(a.periode,5,2)='04' then (case when a.jenis_akun='Pendapatan' then -a.n4 else a.n4 end) else 0 end) n4,
            sum(case when substring(a.periode,5,2)='05' then (case when a.jenis_akun='Pendapatan' then -a.n4 else a.n4 end) else 0 end) n5,
            sum(case when substring(a.periode,5,2)='06' then (case when a.jenis_akun='Pendapatan' then -a.n4 else a.n4 end) else 0 end) n6,
            sum(case when substring(a.periode,5,2)='07' then (case when a.jenis_akun='Pendapatan' then -a.n4 else a.n4 end) else 0 end) n7,
            sum(case when substring(a.periode,5,2)='08' then (case when a.jenis_akun='Pendapatan' then -a.n4 else a.n4 end) else 0 end) n8,
            sum(case when substring(a.periode,5,2)='09' then (case when a.jenis_akun='Pendapatan' then -a.n4 else a.n4 end) else 0 end) n9,
            sum(case when substring(a.periode,5,2)='10' then (case when a.jenis_akun='Pendapatan' then -a.n4 else a.n4 end) else 0 end) n10,
            sum(case when substring(a.periode,5,2)='11' then (case when a.jenis_akun='Pendapatan' then -a.n4 else a.n4 end) else 0 end) n11,  
            sum(case when substring(a.periode,5,2) in ('12','13','14','15') then (case when a.jenis_akun='Pendapatan' then -a.n4 else a.n4 end) else 0 end) n12
            from exs_neraca a
            inner join db_grafik_d b on a.kode_neraca=b.kode_neraca and a.kode_lokasi=b.kode_lokasi
            where a.kode_lokasi='$kode_lokasi' and substring(a.periode,1,4)='".substr($periode,0,4)."' and b.kode_grafik='DB13'
            group by a.kode_lokasi";

            $res = execute($sql);
            $response["series"] = array();
            while ($row = $res->FetchNextObject(false)){
                $response["series"][] = array("name" =>"Income", "data" => array(
                                                round(floatval($row->n1)), round(floatval($row->n2)), round(floatval($row->n3)), 
                                                round(floatval($row->n4)), round(floatval($row->n5)), round(floatval($row->n6)),
                                                round(floatval($row->n7)), round(floatval($row->n8)), round(floatval($row->n9)), 
                                                round(floatval($row->n10)), round(floatval($row->n11)), round(floatval($row->n12))
                                            ));
            }	

            $sqlbox1 = "select 
                sum(case a.jenis_akun when 'Pendapatan' then -a.n2 else a.n2 end) as n2, 
                sum(case a.jenis_akun when  'Pendapatan' then -a.n4 else a.n4 end) as n4, (sum(case a.jenis_akun when  'Pendapatan' then -a.n4 else a.n4 end) - sum(case a.jenis_akun when 'Pendapatan' then -a.n2 else a.n2 end))/sum(case a.jenis_akun when  'Pendapatan' then -a.n4 else a.n4 end) as persen
                from exs_neraca a
                inner join db_grafik_d b on a.kode_neraca=b.kode_neraca and a.kode_lokasi=b.kode_lokasi
                where a.kode_lokasi='$kode_lokasi' and a.periode='".$periode."' and b.kode_grafik in ('DB13')
            ";
            $rsAcvp = execute($sqlbox1);
            $rowAcvp = $rsAcvp->FetchNextObject($toupper);
            $response["budpend"] = $rowAcvp->n2;
            $response["actpend"] = $rowAcvp->n4;
            $response["persen"] = round($rowAcvp->persen*100,2);
            $response['sql'] = $sql;
            $response['status'] = true;
        }else{
            $response["message"] = "Unauthorized Access, Login Required";
        }
        
        header('Content-Type: application/json');
        echo json_encode($response);

    }

    function getExpensesChart() {
        session_start();
        getKoneksi();
        $data=$_GET;
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'],$_SESSION['userPwd'])){             
            $kode_lokasi = $_SESSION['lokasi'];  
            $periode = $_GET['periode'];
            $response = array("message" => "", "rows" => 0, "status" => "" ); 				
           
            $sql = "select a.kode_lokasi,
            sum(case when substring(a.periode,5,2)='01' then (case when a.jenis_akun='Pendapatan' then -a.n4 else a.n4 end) else 0 end) n1,
            sum(case when substring(a.periode,5,2)='02' then (case when a.jenis_akun='Pendapatan' then -a.n4 else a.n4 end) else 0 end) n2,   
            sum(case when substring(a.periode,5,2)='03' then (case when a.jenis_akun='Pendapatan' then -a.n4 else a.n4 end) else 0 end) n3,
            sum(case when substring(a.periode,5,2)='04' then (case when a.jenis_akun='Pendapatan' then -a.n4 else a.n4 end) else 0 end) n4,
            sum(case when substring(a.periode,5,2)='05' then (case when a.jenis_akun='Pendapatan' then -a.n4 else a.n4 end) else 0 end) n5,
            sum(case when substring(a.periode,5,2)='06' then (case when a.jenis_akun='Pendapatan' then -a.n4 else a.n4 end) else 0 end) n6,
            sum(case when substring(a.periode,5,2)='07' then (case when a.jenis_akun='Pendapatan' then -a.n4 else a.n4 end) else 0 end) n7,
            sum(case when substring(a.periode,5,2)='08' then (case when a.jenis_akun='Pendapatan' then -a.n4 else a.n4 end) else 0 end) n8,
            sum(case when substring(a.periode,5,2)='09' then (case when a.jenis_akun='Pendapatan' then -a.n4 else a.n4 end) else 0 end) n9,
            sum(case when substring(a.periode,5,2)='10' then (case when a.jenis_akun='Pendapatan' then -a.n4 else a.n4 end) else 0 end) n10,
            sum(case when substring(a.periode,5,2)='11' then (case when a.jenis_akun='Pendapatan' then -a.n4 else a.n4 end) else 0 end) n11,  
            sum(case when substring(a.periode,5,2) in ('12','13','14','15') then (case when a.jenis_akun='Pendapatan' then -a.n4 else a.n4 end) else 0 end) n12
            from exs_neraca a
            inner join db_grafik_d b on a.kode_neraca=b.kode_neraca and a.kode_lokasi=b.kode_lokasi
            where a.kode_lokasi='$kode_lokasi' and substring(a.periode,1,4)='".substr($periode,0,4)."' and b.kode_grafik='DB14'
            group by a.kode_lokasi";

            $res = execute($sql);
            $response["series"] = array();
            while ($row = $res->FetchNextObject(false)){
                $response["series"][] = array("name" =>"Income", "data" => array(
                                                round(floatval($row->n1)), round(floatval($row->n2)), round(floatval($row->n3)), 
                                                round(floatval($row->n4)), round(floatval($row->n5)), round(floatval($row->n6)),
                                                round(floatval($row->n7)), round(floatval($row->n8)), round(floatval($row->n9)), 
                                                round(floatval($row->n10)), round(floatval($row->n11)), round(floatval($row->n12))
                                            ));
            }	

            $sqlbox1 = "select 
                sum(case a.jenis_akun when 'Pendapatan' then -a.n2 else a.n2 end) as n2, 
                sum(case a.jenis_akun when  'Pendapatan' then -a.n4 else a.n4 end) as n4, (sum(case a.jenis_akun when  'Pendapatan' then -a.n4 else a.n4 end) - sum(case a.jenis_akun when 'Pendapatan' then -a.n2 else a.n2 end))/sum(case a.jenis_akun when  'Pendapatan' then -a.n4 else a.n4 end) as persen
                from exs_neraca a
                inner join db_grafik_d b on a.kode_neraca=b.kode_neraca and a.kode_lokasi=b.kode_lokasi
                where a.kode_lokasi='$kode_lokasi' and a.periode='".$periode."' and b.kode_grafik in ('DB14')
            ";
            $rsAcvp = execute($sqlbox1);
            $rowAcvp = $rsAcvp->FetchNextObject($toupper);
            $response["budbeb"] = $rowAcvp->n2;
            $response["actbeb"] = $rowAcvp->n4;
            $response["persen"] = round($rowAcvp->persen*100,2);
            $response['sql'] = $sql;
            $response['status'] = true;
        }else{
            $response["message"] = "Unauthorized Access, Login Required";
        }
        
        header('Content-Type: application/json');
        echo json_encode($response);

    }

    function getNetProfitChart() {
        session_start();
        getKoneksi();
        $data=$_GET;
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'],$_SESSION['userPwd'])){             
            $kode_lokasi = $_SESSION['lokasi'];  
            $periode = $_GET['periode'];
            $response = array("message" => "", "rows" => 0, "status" => "" ); 				
           
            $sql = "select a.kode_lokasi,
            sum(case when substring(a.periode,5,2)='01' then (case when a.jenis_akun='Pendapatan' then -a.n4 else a.n4 end) else 0 end) n1,
            sum(case when substring(a.periode,5,2)='02' then (case when a.jenis_akun='Pendapatan' then -a.n4 else a.n4 end) else 0 end) n2,   
            sum(case when substring(a.periode,5,2)='03' then (case when a.jenis_akun='Pendapatan' then -a.n4 else a.n4 end) else 0 end) n3,
            sum(case when substring(a.periode,5,2)='04' then (case when a.jenis_akun='Pendapatan' then -a.n4 else a.n4 end) else 0 end) n4,
            sum(case when substring(a.periode,5,2)='05' then (case when a.jenis_akun='Pendapatan' then -a.n4 else a.n4 end) else 0 end) n5,
            sum(case when substring(a.periode,5,2)='06' then (case when a.jenis_akun='Pendapatan' then -a.n4 else a.n4 end) else 0 end) n6,
            sum(case when substring(a.periode,5,2)='07' then (case when a.jenis_akun='Pendapatan' then -a.n4 else a.n4 end) else 0 end) n7,
            sum(case when substring(a.periode,5,2)='08' then (case when a.jenis_akun='Pendapatan' then -a.n4 else a.n4 end) else 0 end) n8,
            sum(case when substring(a.periode,5,2)='09' then (case when a.jenis_akun='Pendapatan' then -a.n4 else a.n4 end) else 0 end) n9,
            sum(case when substring(a.periode,5,2)='10' then (case when a.jenis_akun='Pendapatan' then -a.n4 else a.n4 end) else 0 end) n10,
            sum(case when substring(a.periode,5,2)='11' then (case when a.jenis_akun='Pendapatan' then -a.n4 else a.n4 end) else 0 end) n11,  
            sum(case when substring(a.periode,5,2) in ('12','13','14','15') then (case when a.jenis_akun='Pendapatan' then -a.n4 else a.n4 end) else 0 end) n12
            from exs_neraca a
            inner join db_grafik_d b on a.kode_neraca=b.kode_neraca and a.kode_lokasi=b.kode_lokasi
            where a.kode_lokasi='$kode_lokasi' and substring(a.periode,1,4)='".substr($periode,0,4)."' and b.kode_grafik='DB12'
            group by a.kode_lokasi";

            $res = execute($sql);
            $response["series"] = array();
            while ($row = $res->FetchNextObject(false)){
                $response["series"][] = array("name" =>"Income", "data" => array(
                                                round(floatval($row->n1)), round(floatval($row->n2)), round(floatval($row->n3)), 
                                                round(floatval($row->n4)), round(floatval($row->n5)), round(floatval($row->n6)),
                                                round(floatval($row->n7)), round(floatval($row->n8)), round(floatval($row->n9)), 
                                                round(floatval($row->n10)), round(floatval($row->n11)), round(floatval($row->n12))
                                            ));
            }	

            $sqlbox1 = "select 
                sum(case a.jenis_akun when 'Pendapatan' then -a.n2 else a.n2 end) as n2, 
                sum(case a.jenis_akun when  'Pendapatan' then -a.n4 else a.n4 end) as n4, (sum(case a.jenis_akun when  'Pendapatan' then -a.n4 else a.n4 end) - sum(case a.jenis_akun when 'Pendapatan' then -a.n2 else a.n2 end))/sum(case a.jenis_akun when  'Pendapatan' then -a.n4 else a.n4 end) as persen
                from exs_neraca a
                inner join db_grafik_d b on a.kode_neraca=b.kode_neraca and a.kode_lokasi=b.kode_lokasi
                where a.kode_lokasi='$kode_lokasi' and a.periode='".$periode."' and b.kode_grafik in ('DB12')
            ";
            $rsAcvp = execute($sqlbox1);
            $rowAcvp = $rsAcvp->FetchNextObject($toupper);
            $response["budnet"] = $rowAcvp->n2;
            $response["actnet"] = $rowAcvp->n4;
            $response["persen"] = round($rowAcvp->persen*100,2);
            $response['sql'] = $sql;
            $response['status'] = true;
        }else{
            $response["message"] = "Unauthorized Access, Login Required";
        }
        
        header('Content-Type: application/json');
        echo json_encode($response);

    }

    function getCOGSChart() {
        session_start();
        getKoneksi();
        $data=$_GET;
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'],$_SESSION['userPwd'])){             
            $kode_lokasi = $_SESSION['lokasi'];  
            $periode = $_GET['periode'];
            $response = array("message" => "", "rows" => 0, "status" => "" ); 				
           
            $sql = "select a.kode_lokasi,
            sum(case when substring(a.periode,5,2)='01' then (case when a.jenis_akun='Pendapatan' then -a.n4 else a.n4 end) else 0 end) n1,
            sum(case when substring(a.periode,5,2)='02' then (case when a.jenis_akun='Pendapatan' then -a.n4 else a.n4 end) else 0 end) n2,   
            sum(case when substring(a.periode,5,2)='03' then (case when a.jenis_akun='Pendapatan' then -a.n4 else a.n4 end) else 0 end) n3,
            sum(case when substring(a.periode,5,2)='04' then (case when a.jenis_akun='Pendapatan' then -a.n4 else a.n4 end) else 0 end) n4,
            sum(case when substring(a.periode,5,2)='05' then (case when a.jenis_akun='Pendapatan' then -a.n4 else a.n4 end) else 0 end) n5,
            sum(case when substring(a.periode,5,2)='06' then (case when a.jenis_akun='Pendapatan' then -a.n4 else a.n4 end) else 0 end) n6,
            sum(case when substring(a.periode,5,2)='07' then (case when a.jenis_akun='Pendapatan' then -a.n4 else a.n4 end) else 0 end) n7,
            sum(case when substring(a.periode,5,2)='08' then (case when a.jenis_akun='Pendapatan' then -a.n4 else a.n4 end) else 0 end) n8,
            sum(case when substring(a.periode,5,2)='09' then (case when a.jenis_akun='Pendapatan' then -a.n4 else a.n4 end) else 0 end) n9,
            sum(case when substring(a.periode,5,2)='10' then (case when a.jenis_akun='Pendapatan' then -a.n4 else a.n4 end) else 0 end) n10,
            sum(case when substring(a.periode,5,2)='11' then (case when a.jenis_akun='Pendapatan' then -a.n4 else a.n4 end) else 0 end) n11,  
            sum(case when substring(a.periode,5,2) in ('12','13','14','15') then (case when a.jenis_akun='Pendapatan' then -a.n4 else a.n4 end) else 0 end) n12
            from exs_neraca a
            inner join db_grafik_d b on a.kode_neraca=b.kode_neraca and a.kode_lokasi=b.kode_lokasi
            where a.kode_lokasi='$kode_lokasi' and substring(a.periode,1,4)='".substr($periode,0,4)."' and b.kode_grafik='DB15'
            group by a.kode_lokasi";

            $res = execute($sql);
            $response["series"] = array();
            while ($row = $res->FetchNextObject(false)){
                $response["series"][] = array("name" =>"COGS", "data" => array(
                                                round(floatval($row->n1)), round(floatval($row->n2)), round(floatval($row->n3)), 
                                                round(floatval($row->n4)), round(floatval($row->n5)), round(floatval($row->n6)),
                                                round(floatval($row->n7)), round(floatval($row->n8)), round(floatval($row->n9)), 
                                                round(floatval($row->n10)), round(floatval($row->n11)), round(floatval($row->n12))
                                            ));
            }	

            $sqlbox1 = "select 
                sum(case a.jenis_akun when 'Pendapatan' then -a.n2 else a.n2 end) as n2, 
                sum(case a.jenis_akun when  'Pendapatan' then -a.n4 else a.n4 end) as n4, (sum(case a.jenis_akun when  'Pendapatan' then -a.n4 else a.n4 end) - sum(case a.jenis_akun when 'Pendapatan' then -a.n2 else a.n2 end))/sum(case a.jenis_akun when  'Pendapatan' then -a.n4 else a.n4 end) as persen
                from exs_neraca a
                inner join db_grafik_d b on a.kode_neraca=b.kode_neraca and a.kode_lokasi=b.kode_lokasi
                where a.kode_lokasi='$kode_lokasi' and a.periode='".$periode."' and b.kode_grafik in ('DB15')
            ";
            $rsAcvp = execute($sqlbox1);
            $rowAcvp = $rsAcvp->FetchNextObject($toupper);
            $response["budcogs"] = $rowAcvp->n2;
            $response["actcogs"] = $rowAcvp->n4;
            $response["persen"] = round($rowAcvp->persen*100,2);
            $response['sql'] = $sql;
            $response['status'] = true;
        }else{
            $response["message"] = "Unauthorized Access, Login Required";
        }
        
        header('Content-Type: application/json');
        echo json_encode($response);

    }

    function getLapPnj() {
        session_start();
        getKoneksi();
        $data=$_GET;
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'],$_SESSION['userPwd'])){             
            $kode_lokasi = $_SESSION['lokasi'];  
            $periode = $_GET['periode'];
            $response = array("message" => "", "rows" => 0, "status" => "" ); 

            $tmp = explode("|",$data["param"]);
            $per1 = $tmp[0];
            $per2=$tmp[1];
            $kode_klp=$tmp[2];
            $order=$tmp[3];
            $filterper = "";
            if($per1 == ""){
                $filterper.="";
            }else{
                $filterper.=" and a.periode >= '$per1' ";
            }

            if($per2 == ""){
                $filterper.="";
            }else{
                $filterper.=" and a.periode <= '$per2' ";
            }

            if($kode_klp == ""){
                $filter2.="";
            }else{
                $filter2.=" and a.kode_klp = '$kode_klp' ";
            }

            if($order == ""){
                $filter2.="";
            }else{
                $filter2.=" order by $order ";
            }

            $sql = "select top 20 a.kode_barang,a.nama,isnull(b.jumlah,0) as jumlah,0 as stok,0 as persen
            from brg_barang a
            left join (select a.kode_barang,a.kode_lokasi,sum(a.jumlah) as jumlah
            from brg_trans_dloc a
            where a.kode_lokasi='$kode_lokasi' and a.modul='BRGJUAL' $filterper
            group by a.kode_barang,a.kode_lokasi
                    )b on a.kode_barang=b.kode_barang and a.kode_lokasi=b.kode_lokasi
            where a.kode_lokasi='$kode_lokasi' 
            $filter2
            ";
            $pnj = dbResultArray($sql);

            $response["daftar"] = $pnj;
            $response["sql"] = $sql;
            $response['status'] = true;
        }else{
            $response["message"] = "Unauthorized Access, Login Required";
        }
        
        header('Content-Type: application/json');
        echo json_encode($response);

    }

    function getLapVendor() {
        session_start();
        getKoneksi();
        $data=$_GET;
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'],$_SESSION['userPwd'])){             
            $kode_lokasi = $_SESSION['lokasi'];  
            $periode = $_GET['periode'];
            $response = array("message" => "", "rows" => 0, "status" => "" ); 

            $tmp = explode("|",$data["param"]);
            $vendor = $tmp[1];
            $order = $tmp[0];
            $filterper = "";
            if($vendor == ""){
                $filterper.="";
            }else{
                $filterper.=" and a.kode_vendor like '%$vendor%' or a.nama like '%$vendor%' ";
            }

            if($order == ""){
                $filterper.="";
            }else{
                $filterper.=" order by $order ";
            }

            $vendor = dbResultArray("select a.kode_vendor,a.nama,isnull(b.total,0) as total
            from vendor a
            left join (select b.param2,a.kode_lokasi,sum(a.total) as total
            from brg_trans_d a
            inner join trans_m b on a.no_bukti=b.no_bukti and a.kode_lokasi=b.kode_lokasi
            where a.kode_lokasi='$kode_lokasi' and a.modul='BRGBELI'
            group by b.param2,a.kode_lokasi
                    )b on a.kode_vendor=b.param2 and a.kode_lokasi=b.kode_lokasi
            where a.kode_lokasi='$kode_lokasi' and isnull(b.total,0)>0
            $filterper ");

            $response["daftar"] = $vendor;
            
            $response['status'] = true;
        }else{
            $response["message"] = "Unauthorized Access, Login Required";
        }
        
        header('Content-Type: application/json');
        echo json_encode($response);

    }

    function getJurnal() {
        session_start();
        getKoneksi();
        $data=$_GET;
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'],$_SESSION['userPwd'])){             
            $kode_lokasi = $_SESSION['lokasi'];  
            $periode = $_GET['periode'];
            $param = explode("|",$data['param']);
            $no_bukti = $param[0];
            $tgl = $param[1];
            $response = array("message" => "", "rows" => 0, "status" => "" );
            $sql="select a.no_bukti,convert(varchar,a.tanggal,103) as tgl,a.kode_akun,a.keterangan,a.kode_pp,b.nama as nama_akun,a.kode_drk,
            case when a.dc='D' then a.nilai else 0 end as debet,case when a.dc='C' then a.nilai else 0 end as kredit,a.dc
            from gldt a
            inner join masakun b on a.kode_akun=b.kode_akun and a.kode_lokasi=b.kode_lokasi
            where a.kode_lokasi='$kode_lokasi' and a.no_bukti='$no_bukti' 
            union all
            select a.no_bukti,convert(varchar,a.tanggal,103) as tgl,a.kode_akun,a.keterangan,a.kode_pp,b.nama as nama_akun,a.kode_drk,
            case when a.dc='D' then a.nilai else 0 end as debet,case when a.dc='C' then a.nilai else 0 end as kredit,a.dc
            from gldt_h a
            inner join masakun b on a.kode_akun=b.kode_akun and a.kode_lokasi=b.kode_lokasi
            where a.kode_lokasi='$kode_lokasi' and a.no_bukti='$no_bukti'
            order by a.dc desc";

            $res = dbResultArray($sql);
            $response['daftar'] = $res;
            $response['no_bukti'] = $no_bukti;
            $response['tgl'] = $tgl;
            $response['status'] = true;
        }else{
            $response["message"] = "Unauthorized Access, Login Required";
        }
        
        header('Content-Type: application/json');
        echo json_encode($response);

    }

    
?>