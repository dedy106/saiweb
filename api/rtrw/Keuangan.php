<?php
$request_method=$_SERVER["REQUEST_METHOD"];

switch($request_method) {
    case 'GET':
        if(isset($_GET["fx"]) AND function_exists($_GET['fx'])){
            $_GET['fx']();
        }
    break;
    case 'POST':
        if(isset($_GET["fx"]) AND function_exists($_GET['fx'])){
            $_GET['fx']();
        }
    break;
}

function getKoneksi(){
    $root_lib=$_SERVER["DOCUMENT_ROOT"];
    include_once($root_lib."lib/koneksi.php");
    include_once($root_lib."lib/helpers.php");
}
function authKey2($key, $modul, $user=null){
    getKoneksi();
    $key = qstr($key);
    $modul = qstr($modul);
    $date = date('Y-m-d H:i:s');
    $user_str = "";
    if($user != null){
        $user = qstr($user);
        $user_str .= "AND nik = $user";
    }

    $schema = db_Connect();
    $auth = $schema->SelectLimit("SELECT * FROM api_key_auth where api_key=$key and expired > '$date' and modul=$modul $user_str", 1);
    if($auth->RecordCount() > 0){
        
        $date = new DateTime($date);
        $date->add(new DateInterval('PT1H'));
        $WorkingArray = json_decode(json_encode($date),true);
        $expired = explode(".",$WorkingArray["date"]);

        $db_key["expired"] = $expired[0];
        $key_sql = $schema->AutoExecute('api_key_auth', $db_key, 'UPDATE', "api_key=$key and modul=$modul");
        return true;
    }else{
        return false;
    }
}


function authKey($key, $modul, $user=null){
    getKoneksi();
    $key = qstr($key);
    $modul = qstr($modul);
    $date = date('Y-m-d H:i:s');
    $user_str = "";
    if($user != null){
        $user = qstr($user);
        $user_str .= "AND nik = $user";
    }

    $schema = db_Connect();
    $auth = $schema->SelectLimit("SELECT * FROM api_key_auth where api_key=$key and modul=$modul $user_str ", 1);
    if($auth->RecordCount() > 0){
        return true;
    }else{
        return false;
    }
}

// VIEW MOBILE
function getKartu(){
    getKoneksi();
    $data = $_GET;
    if(arrayKeyCheck(array('api_key', 'username', 'kode_lokasi','periode'), $data)){
        if(authKey($data["api_key"], 'RTRW', $data['username'])){
            $kode_lokasi=$data["kode_lokasi"];
            $no_rumah=$data["kode_rumah"];
            $periode=$data["periode"];
            $tahun=$data["tahun"];
            $sql="select  case when substring(a.periode,5,2) = '01' then 'JANUARI'
            when substring(a.periode,5,2) = '02' then 'FEBRUARI'
            when substring(a.periode,5,2) = '03' then 'MARET'
            when substring(a.periode,5,2) = '04' then 'APRIL'
            when substring(a.periode,5,2) = '05' then 'MEI'
            when substring(a.periode,5,2) = '06' then 'JUNI'
            when substring(a.periode,5,2) = '07' then 'JULI'
            when substring(a.periode,5,2) = '08' then 'AGUSTUS'
            when substring(a.periode,5,2) = '09' then 'SEPTEMBER'
            when substring(a.periode,5,2) = '10' then 'OKTOBER'
            when substring(a.periode,5,2) = '11' then 'NOVEMBER'
            when substring(a.periode,5,2) = '12' then 'DESEMBER'
            end as periode,(a.nilai_rt+a.nilai_rw) as bill,isnull(b.bayar,0) as bayar
            from rt_bill_d a 
            left join (
                select periode_bill,kode_lokasi,kode_rumah,sum(nilai_rt+nilai_rw) as bayar
                from rt_angs_d where kode_lokasi ='$kode_lokasi' and kode_rumah ='$no_rumah' and periode_bill like '$tahun%' and kode_jenis='IWAJIB' group by periode_bill,kode_lokasi,kode_rumah
                ) b on a.periode=periode_bill and a.kode_lokasi=b.kode_lokasi and a.kode_rumah=b.kode_rumah 
            where a.kode_lokasi ='$kode_lokasi' and a.kode_rumah ='$no_rumah' and a.periode like '$tahun%' and a.kode_jenis='IWAJIB'
            order by a.periode";
            $response["daftar"] = dbResultArray($sql);
            // $response["sql"] = $sql;
            $response['status']=true;
        }else{
            $response['status']=false;
            $response['message'] = "Unauthorized Access 2";
        }
    }else{
        $response['status']=false;
        $response['message'] = "Unauthorized Access 1";
        
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
        
}

function getLaporanRT(){
    getKoneksi();
    $data = $_GET;
    if(arrayKeyCheck(array('api_key', 'username', 'kode_lokasi','periode'), $data)){
        if(authKey($data["api_key"], 'RTRW', $data['username'])){
            $kode_lokasi=$data["kode_lokasi"];
            $kode_pp=$data["kode_pp"];
            $periode=$data["periode"];
            $tahun=$data["tahun"];
            $sql="select a.kode_drk,b.nama,b.jenis,b.idx,sum(case a.dc when 'D' then nilai else -nilai end) as total
            from gldt a inner join trans_ref b on a.kode_drk=b.kode_ref and a.kode_lokasi=b.kode_lokasi
            where a.kode_pp = '$kode_pp' and a.kode_lokasi ='$kode_lokasi' and a.periode like '$tahun%' and a.kode_akun not in ('11101') and b.jenis ='PENERIMAAN'
            group by a.kode_drk,b.nama,b.jenis,b.idx
            order by b.jenis,b.idx";
            $res= execute($sql);
            $response["penerimaan"] = array();
            $tot=0;
            while($row=$res->FetchNextObject($toupper=false)){
                $response["penerimaan"][] = (array)$row;
                $tot+=$row->total;
            }

            $sql2="select a.kode_drk,b.nama,b.jenis,b.idx,sum(case a.dc when 'D' then nilai else -nilai end) as total
            from gldt a inner join trans_ref b on a.kode_drk=b.kode_ref and a.kode_lokasi=b.kode_lokasi
            where a.kode_pp = '$kode_pp' and a.kode_lokasi ='$kode_lokasi' and a.periode like '$tahun%' and a.kode_akun not in ('11101') and b.jenis ='PENGELUARAN'
            group by a.kode_drk,b.nama,b.jenis,b.idx
            order by b.jenis,b.idx";
            $res2= execute($sql2);
            $response["pengeluaran"] = array();
            $tot2=0;
            while($row=$res2->FetchNextObject($toupper=false)){
                $response["pengeluaran"][] = (array)$row;
                $tot2+=$row->total;
            }
            $response["total_penerimaan"]=$tot;
            $response["total_pengeluaran"]=$tot2; 
            $response["saldo"] = $tot+$tot2;
            $response['status']=true;
        }else{
            $response['status']=false;
            $response['message'] = "Unauthorized Access 2";
        }
    }else{
        $response['status']=false;
        $response['message'] = "Unauthorized Access 1";
        
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
        
}
function getTransaksiWarga(){
    getKoneksi();
    $data = $_GET;
    if(arrayKeyCheck(array('api_key', 'username', 'kode_lokasi','periode'), $data)){
        if(authKey($data["api_key"], 'RTRW', $data['username'])){
            $kode_lokasi=$data["kode_lokasi"];
            $kode_pp=$data["kode_pp"];
            $periode=$data["periode"];
            $tahun=$data["tahun"];
            $sqlakun= execute("select akun_kas from pp where kode_pp='$kode_pp' ");
            $kode_akun= $sqlakun->fields[0];
            $kode_rumah=$data["kode_rumah"];

            $sql="select top 10 substring(convert(varchar,a.tanggal,105),1,2) as tgl2,convert(varchar,a.tanggal,105) as tgl,a.keterangan,a.dc,a.nilai as nilai1,a.jenis,a.tgl_input                          
            from gldt a 
            inner join trans_m b on a.no_bukti=b.no_bukti and a.kode_lokasi=b.kode_lokasi 
            where a.kode_akun ='$kode_akun' and a.kode_pp ='$kode_pp' and a.kode_lokasi='$kode_lokasi' and b.param1='$kode_rumah' order by  convert(varchar,a.tanggal,103) desc";
            $response["daftar"]=dbResultArray($sql);
            $response['status']=true;
        }else{
            $response['status']=false;
            $response['message'] = "Unauthorized Access 2";
        }
    }else{
        $response['status']=false;
        $response['message'] = "Unauthorized Access 1";
        
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
        
}
function getTransaksiRTRW(){
    getKoneksi();
    $data = $_GET;
    if(arrayKeyCheck(array('api_key', 'username', 'kode_lokasi','periode'), $data)){
        if(authKey($data["api_key"], 'RTRW', $data['username'])){
            $kode_lokasi=$data["kode_lokasi"];
            $kode_pp=$data["kode_pp"];
            $periode=$data["periode"];
            $tahun=$data["tahun"];
            $sqlakun= execute("select akun_kas from pp where kode_pp='$kode_pp' ");
            $kode_akun= $sqlakun->fields[0];
            $sql="select top 10 substring(convert(varchar,tanggal,105),1,2) as tgl2,convert(varchar,tanggal,105) as tgl,keterangan,dc,nilai as nilai1,jenis,tgl_input
                          from gldt where kode_akun ='$kode_akun' and kode_pp ='$kode_pp' and kode_lokasi='$kode_lokasi'
                          order by tgl_input desc ";
            $response["daftar"]=dbResultArray($sql);
            $response['status']=true;
        }else{
            $response['status']=false;
            $response['message'] = "Unauthorized Access 2";
        }
    }else{
        $response['status']=false;
        $response['message'] = "Unauthorized Access 1";
        
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
        
}

function getSaldoKasBank(){
    getKoneksi();
    $data = $_GET;
    if(arrayKeyCheck(array('api_key', 'username', 'kode_lokasi','periode'), $data)){
        if(authKey($data["api_key"], 'RTRW', $data['username'])){
            $kode_lokasi=$data["kode_lokasi"];
            $kode_pp=$data["kode_pp"];
            $periode=$data["periode"];
            $tahun=$data["tahun"];
            $sqlakun= execute("select akun_kas from pp where kode_pp='$kode_pp' ");
            $kode_akun= $sqlakun->fields[0];
            $sql="select a.kode_akun,a.nama,c.no_rek,c.nama_bank, isnull(sum(b.nilai),0) as saldo
            from masakun a
            left join(
                                select kode_akun,kode_lokasi,sum(so_akhir) as nilai 
                                from glma_pp
                                where kode_pp='$kode_pp' and kode_lokasi='$kode_lokasi' 
                                group by kode_akun,kode_lokasi
                                union all
                                select kode_akun,kode_lokasi,sum(case dc when 'D' then nilai else -nilai end) as nilai 
                                from gldt 
                                where kode_lokasi='$kode_lokasi' 
                                group by kode_akun,kode_lokasi
                    ) b on a.kode_akun=b.kode_akun and a.kode_lokasi=b.kode_lokasi
            left join rt_rekbank c on a.kode_akun=c.kode_akun
            where a.kode_akun in ('$kode_akun','11201','11202')
            group by a.kode_akun,a.nama,c.no_rek,c.nama_bank
            having sum(b.nilai) <> 0  ";

            $response["daftar"]=dbResultArray($sql);
            $response['status']=true;
        }else{
            $response['status']=false;
            $response['message'] = "Unauthorized Access 2";
        }
    }else{
        $response['status']=false;
        $response['message'] = "Unauthorized Access 1";
        
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
        
}

?>
