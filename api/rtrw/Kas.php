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

function view(){
    getKoneksi();
    
    $sql="select a.kode_ref, a.nama 
     from trans_ref a inner join karyawan_pp b on a.kode_pp=b.kode_pp and a.kode_lokasi=b.kode_lokasi and b.nik='B10-11' 
     where a.jenis='PENERIMAAN' and a.kode_lokasi='18' ";
    
    $rs=execute($sql);
    $html="";
    while($row=$rs->FetchNextObject($toupper = false)){
        $html.= $row->kode_ref;
    }
    echo $html;
    
}

function getsel(){
    getKoneksi();
    $data = $_POST;
    if(arrayKeyCheck(array('api_key', 'username', 'kode_lokasi','periode'), $data)){
        if(authKey($data["api_key"], 'RTRW', $data['username'])){ 
   
            $kode_lokasi=$_POST['kode_lokasi'];
            $response = array("message" => "", "rows" => 0, "status" => "" );

            if($_POST['jenis'] == 'keluar'){
                $jenis="PENGELUARAN";
            }else{
                $jenis="PENERIMAAN";
            }
            $sql="select a.kode_ref, a.nama 
            from trans_ref a inner join karyawan_pp b on a.kode_pp=b.kode_pp and a.kode_lokasi=b.kode_lokasi and b.nik='".$_POST['nik']."' 
            where a.jenis='$jenis' and a.kode_lokasi='$kode_lokasi'";
            
            $rs = execute($sql);					
            
            while ($row = $rs->FetchNextObject(false)){
                $response['daftar'][] = (array)$row;
            }
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

function joinNum2($num){
    // menggabungkan angka yang di-separate(10.000,75) menjadi 10000.00
    $num = str_replace(".", "", $num);
    $num = str_replace(",", ".", $num);
    return $num;
}

function simpan(){
    getKoneksi();
    $data = $_POST;
    if(arrayKeyCheck(array('api_key', 'username', 'kode_lokasi','periode'), $data)){
        if(authKey($data["api_key"], 'RTRW', $data['username'])){ 

            if($_POST['kode_jenis'] == 'keluar'){
                $jenis="BK";
            }else{
                $jenis="BM";
            }
            
            $str_format="0000";
            $periode=date('Y').date('m');
            $per=date('y').date('m');
            $prefix=$_POST['kode_lokasi']."-".$jenis.$per.".";
            $query = execute("select right(isnull(max(no_bukti),'0000'),".strlen($str_format).")+1 as id from trans_m where no_bukti like '$prefix%' ");
            $id = $prefix.str_pad($query->fields[0], strlen($str_format), $str_format, STR_PAD_LEFT);

            $sql="select a.nama,a.akun_debet,a.akun_kredit,a.kode_pp, b.kode_gar as gar_debet,c.kode_gar as gar_kredit 
            from trans_ref a 
            inner join masakun b on a.akun_debet=b.kode_akun and a.kode_lokasi=b.kode_lokasi 
            inner join masakun c on a.akun_kredit=c.kode_akun and a.kode_lokasi=c.kode_lokasi 
            where a.kode_ref='".$_POST['kode_ref']."' and a.kode_lokasi='".$_POST['kode_lokasi']."'";

            $rs=execute($sql);
            $row = $rs->FetchNextObject(false);
            $akunDebet=$row->akun_debet;
            $akunKredit=$row->akun_kredit;

            // BeginTrans();
            $exec = array();

            $sql1 ="insert into trans_m (no_bukti,kode_lokasi,tgl_input,nik_user,periode,modul,form,posted,prog_seb,progress,kode_pp,tanggal,no_dokumen,keterangan,kode_curr,kurs,nilai1,nilai2,nilai3,nik1,nik2,nik3,no_ref1,no_ref2,no_ref3,param1,param2,param3) values ('".$id."','".$_POST['kode_lokasi']."',getdate(),'".$_POST['nik']."','".$periode."','KB','KBDUAL','T','-','-','".$_POST['kode_pp']."',getdate(),'-','".$_POST['keterangan']."','IDR',1,".joinNum2($_POST['nilai']).",0,0,'".$_POST['nik']."','-','-','-','-','-','".$_POST['kode_ref']."','TUNAI','".$jenis."')";
            array_push($exec,$sql1);

            $sql2 = "insert into trans_j (no_bukti,kode_lokasi,tgl_input,nik_user,periode,no_dokumen,tanggal,nu,kode_akun,dc,nilai,nilai_curr,keterangan,modul,jenis,kode_curr,kurs,kode_pp,kode_drk,kode_cust,kode_vendor,no_fa,no_selesai,no_ref1,no_ref2,no_ref3) values ('".$id."','".$_POST['kode_lokasi']."',getdate(),'".$_POST['nik']."','".$periode."','-',getdate(),0,'".$akunDebet."','D',".joinNum2($_POST['nilai']).",".joinNum2($_POST['nilai']).",'".$_POST['keterangan']."','KB','".$jenis."','IDR',1,'".$_POST['kode_pp']."','".$_POST['kode_ref']."','-','-','-','-','-','-','-')";	
            array_push($exec,$sql2);

            $sql3="insert into trans_j (no_bukti,kode_lokasi,tgl_input,nik_user,periode,no_dokumen,tanggal,nu,kode_akun,dc,nilai,nilai_curr,keterangan,modul,jenis,kode_curr,kurs,kode_pp,kode_drk,kode_cust,kode_vendor,no_fa,no_selesai,no_ref1,no_ref2,no_ref3) values ('".$id."','".$_POST['kode_lokasi']."',getdate(),'".$_POST['nik']."','".$periode."','-',getdate(),0,'".$akunKredit."','C',".joinNum2($_POST['nilai']).",".joinNum2($_POST['nilai']).",'".$_POST['keterangan']."','KB','".$jenis."','IDR',1,'".$_POST['kode_pp']."','".$_POST['kode_ref']."','-','-','-','-','-','-','-')";		
            array_push($exec,$sql3);

            $sql4 = "insert into gldt (no_bukti,no_urut,kode_lokasi,modul,jenis,no_dokumen,tanggal,kode_akun,dc,nilai,keterangan,kode_pp,periode,kode_drk,kode_curr,kurs,nilai_curr,tgl_input,nik_user,kode_cust,kode_proyek,kode_task,kode_vendor,kode_lokarea,nik) select no_bukti,nu,kode_lokasi,modul,jenis,no_dokumen,tanggal,kode_akun,dc,nilai,keterangan,kode_pp,periode,kode_drk,kode_curr,1,nilai,tgl_input,nik_user,'-','-','-','-','-','-' from trans_j 
            where kode_lokasi='".$_POST['kode_lokasi']."' and no_bukti='".$id." '";
            array_push($exec,$sql4);

            $tmp=array();
            $kode = array();
            $res = executeArray($exec); 

            if ($res)
            {	
                // CommitTrans();
                $tmp="Sukses disimpan";
                $sts=true;
            }else{
                // RollbackTrans();
                $tmp="Gagal disimpan";
                $sts=false;
            }		
            $response["message"] =$tmp;
            $response["status"] = $sts;
            // $response["sql1"]=$sql1;

        }else{
            $response['status']=false;
            $response['message'] = "Unauthorized Access 2";
        }
    }else{
        $response['status']=false;
        $response['message'] = "Unauthorized Access 1";
        
    }
    echo json_encode($response);
}

?>
