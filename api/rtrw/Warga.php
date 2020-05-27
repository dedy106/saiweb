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

function getBlok(){
    getKoneksi();
    $data = $_POST;
    if(arrayKeyCheck(array('api_key', 'username', 'kode_lokasi','periode'), $data)){
        if(authKey($data["api_key"], 'RTRW', $data['username'])){ 
  
            $kode_lokasi=$_POST['kode_lokasi'];
            $rt=$_POST['rt'];

            $response = array("message" => "", "rows" => 0, "status" => "" );

            $sql="select a.blok
            from rt_blok a where a.kode_pp='$rt' and a.kode_lokasi='$kode_lokasi'";
            
            $rs = execute($sql);					
            
            while ($row = $rs->FetchNextObject(false)){
                $response['daftar'][] = (array)$row;
            }
            $response['status']=TRUE;
            $response['sql']=$sql;
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

function getDataAset(){
    getKoneksi();
    $data = $_POST;
   
            // $kode_lokasi=$_POST['kode_lokasi'];
            $qrcode=$_POST['qrcode'];

            // $response = array("message" => "", "rows" => 0, "status" => "" );
            $sql="SELECT a.nama_inv AS nama, a.merk, a.warna, a.kode_lokasi FROM amu_asset_bergerak a
                WHERE a.no_bukti= '$qrcode' ";

            $rs = execute($sql);					
            
            while ($row = $rs->FetchNextObject(false)){
                $response['daftar'][] = (array)$row;
            }
            $response['status']=TRUE;
            $response['sql']=$sql;
    echo json_encode($response);
}


function getBlokRumah(){
    getKoneksi();
    $data = $_POST;
    if(arrayKeyCheck(array('api_key', 'username', 'kode_lokasi','periode'), $data)){
        if(authKey($data["api_key"], 'RTRW', $data['username'])){ 
  
            $kode_lokasi=$_POST['kode_lokasi'];
            $rt=$_POST['rt'];


            $response = array("message" => "", "rows" => 0, "status" => "" );

            $sql="select a.blok from rt_blok a where a.kode_pp='$rt' and a.kode_lokasi='$kode_lokasi'";
            
            $rs = execute($sql);					

            $hasil= array();
            $result["daftar2"]=array();            
            while ($row = $rs->FetchNextObject(false)){
                
                $response['daftar'][] = (array)$row;
                $sqlx = "SELECT a.kode_rumah, a.blok, a.status_rumah FROM rt_rumah a WHERE a.blok='$row->blok' ORDER BY a.kode_rumah";
                $rs1=execute($sqlx);

                while($row1 = $rs1->FetchNextObject($toupper = false)){
                    $hasil[]=(array)$row1;
                }
            }
            $response["daftar2"]=$hasil;
            $response['status']=TRUE;
            $response['sql']=$sql;
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

function getInformasi(){
    getKoneksi();
    $data = $_POST;
    if(arrayKeyCheck(array('api_key', 'username', 'kode_lokasi','periode'), $data)){
        if(authKey($data["api_key"], 'RTRW', $data['username'])){ 
   
            $kode_lokasi=$_POST['kode_lokasi'];
            $no_rumah=$_POST['no_rumah'];

            $response = array("message" => "", "rows" => 0, "status" => "" );

            $sql="SELECT a.judul_pesan, a.isi_pesan, a.tgl_pesan
            from rt_pesan a";
            // and a.sts_warga='Pemilik'
            $rs = execute($sql);					
            
            while ($row = $rs->FetchNextObject(false)){
                $response['daftar'][] = (array)$row;
            }
            $response['status']=TRUE;
            $response['sql']=$sql;
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

function getPemilik(){
    getKoneksi();
    $data = $_POST;
    if(arrayKeyCheck(array('api_key', 'username', 'kode_lokasi','periode'), $data)){
        if(authKey($data["api_key"], 'RTRW', $data['username'])){ 
   
            $kode_lokasi=$_POST['kode_lokasi'];
            $no_rumah=$_POST['no_rumah'];

            $response = array("message" => "", "rows" => 0, "status" => "" );

            $sql="SELECT a.nama, b.status_rumah
            from rt_warga_d a, rt_rumah b WHERE a.no_rumah='$no_rumah' AND b.kode_rumah='$no_rumah' AND a.sts_warga='Pemilik' ";
            // and a.sts_warga='Pemilik'
            $rs = execute($sql);					
            
            while ($row = $rs->FetchNextObject(false)){
                $response['daftar'][] = (array)$row;
            }
            $response['status']=TRUE;
            $response['sql']=$sql;
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

// Update 10

function getDataDiri(){
    getKoneksi();
    $data = $_POST;
    if(arrayKeyCheck(array('api_key', 'username', 'kode_lokasi','periode'), $data)){
        if(authKey($data["api_key"], 'RTRW', $data['username'])){ 
   
            $kode_lokasi=$_POST['kode_lokasi'];
            $nik=$_POST['nik'];

            $response = array("message" => "", "rows" => 0, "status" => "" );

            $sql="SELECT a.nik, a.nama, a.sex, a.agama, a.tempat_lahir, a.tgl_lahir, a.goldar, a.resus, a.didik, a.kerja, a.sts_nikah, a.sts_keluarga, a.sts_wni, a.sts_domisili, a.ayah,a.ibu,a.no_ktp,a.no_pass,a.no_kitas,a.no_hp,a.alamat,a.catatan,a.no_keluar,a.tgl_input,a.nik_user,a.kode_lokasi,a.rumah,a.sts_warga, b.foto
            from rt_warga a LEFT JOIN karyawan b ON a.nik=b.nik WHERE a.nik='$nik'";
            // and a.sts_warga='Pemilik'
            $rs = execute($sql);					
            
            while ($row = $rs->FetchNextObject(false)){
                $response['daftar'][] = (array)$row;
            }

            $sql2="SELECT *
            from rt_rumah b LEFT JOIN rt_warga a ON b.kode_pemilik=a.nik WHERE b.kode_rumah='$nik'";
            // and a.sts_warga='Pemilik'
            $rs2 = execute($sql2);					
            
            while ($row2 = $rs2->FetchNextObject(false)){
                $response['daftar2'][] = (array)$row2;
            }

            $response['status']=TRUE;
            $response['sql']=$sql;
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

function updateDataPribadi(){
    getKoneksi();
    $data = $_POST;
    if(arrayKeyCheck(array('api_key', 'username', 'kode_lokasi','periode'), $data)){
        if(authKey($data["api_key"], 'RTRW', $data['username'])){ 
   
            $nik=$_POST['nik'];
            $no_ktp=$_POST['no_ktp'];
            $no_kk=$_POST['no_kk'];
            $no_idlain=$_POST['no_idlain'];
            $nama=$_POST['nama'];
            // $no_telpon=$_POST['no_telpon'];
            $jenis_kelamin=strtolower($_POST['jenis_kelamin']);
            if($jenis_kelamin=="laki-laki"||$jenis_kelamin=="laki laki"){
                $jk="L";
            }else{
                $jk="P";
            }
            $ttl=$_POST['ttl'];
            $goldar=$_POST['goldar'];
            $arr=explode(",", $ttl);
            $tempat=$arr[0];
            $tanggal=$arr[1];

            $response = array("message" => "", "rows" => 0, "status" => "" );

            $sql="UPDATE rt_warga SET nama='$nama', sex='$jk', tempat_lahir='$tempat', tgl_lahir='$tanggal', goldar='$goldar',no_ktp='$no_ktp',no_pass='$no_kk',no_kitas='$no_idlain' WHERE nik='$nik'";
            // and a.sts_warga='Pemilik'
            $rs = execute($sql);					
            if($rs->RecordCount() >= 0){
                $response['status']=TRUE;
                $response['sql']=$sql;
            }else{
                $response['status']=FALSE;
                $response['sql']=$sql;
            }
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

function getTeguran(){
    getKoneksi();
    $data = $_POST;
    if(arrayKeyCheck(array('api_key', 'username', 'kode_lokasi','periode'), $data)){
        if(authKey($data["api_key"], 'RTRW', $data['username'])){ 
   
            $kode_lokasi=$_POST['kode_lokasi'];
            $periode=$_POST['periode'];
            $no_rumah=$_POST['no_rumah'];
            $isi_teguran=$_POST['isi_teguran'];
            $tanggal=$_POST['tanggal'];
            $pengirim=$_POST['pengirim'];

            $response = array("message" => "", "rows" => 0, "status" => "" );
            // $id = generateKode("rt_teguran", "id", $kode_lokasi."-NMA".$periode.".", "001");
            $sql="SELECT isi_teguran,tanggal,no_rumah,pengirim FROM rt_teguran";
            // and a.sts_warga='Pemilik'
            $rs = execute($sql);					
            
            while ($row = $rs->FetchNextObject(false)){
                $response['daftar'][] = (array)$row;
            }
            
            // if($rs->RecordCount() == 0){
            //     $response['status']=FALSE;
            //     $response['sql']=$sql;
            // }else{
                $response['status']=TRUE;
                $response['sql']=$sql;
            // }
            
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

function sendTeguran(){
    getKoneksi();
    $data = $_POST;
    if(arrayKeyCheck(array('api_key', 'username', 'kode_lokasi','periode'), $data)){
        if(authKey($data["api_key"], 'RTRW', $data['username'])){ 
   
            $kode_lokasi=$_POST['kode_lokasi'];
            $periode=$_POST['periode'];
            $no_rumah=$_POST['no_rumah'];
            $isi_teguran=$_POST['isi_teguran'];
            $tanggal=$_POST['tanggal'];
            $pengirim=$_POST['pengirim'];

            $response = array("message" => "", "rows" => 0, "status" => "" );
            // $id = generateKode("rt_teguran", "id", $kode_lokasi."-NMA".$periode.".", "001");
            $sql="INSERT INTO rt_teguran (isi_teguran,tanggal,no_rumah,pengirim) VALUES ('$isi_teguran','$tanggal','$no_rumah','$pengirim')";
            // and a.sts_warga='Pemilik'
            $rs = execute($sql);					
            
            // if($rs->RecordCount() == 0){
            //     $response['status']=FALSE;
            //     $response['sql']=$sql;
            // }else{
                $response['status']=TRUE;
                $response['sql']=$sql;
            // }
            
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

function getPenghuni(){
    getKoneksi();
    $data = $_POST;
    if(arrayKeyCheck(array('api_key', 'username', 'kode_lokasi','periode'), $data)){
        if(authKey($data["api_key"], 'RTRW', $data['username'])){ 
   
            $kode_lokasi=$_POST['kode_lokasi'];
            $no_rumah=$_POST['no_rumah'];

            $response = array("message" => "", "rows" => 0, "status" => "" );

            $sql="SELECT a.nama
            from rt_warga_d a WHERE a.no_rumah='$no_rumah' AND a.sts_warga='Penghuni' ";
            // and a.sts_warga='Pemilik'
            $rs = execute($sql);					
            
            while ($row = $rs->FetchNextObject(false)){
                $response['daftar'][] = (array)$row;
            }
            $response['status']=TRUE;
            $response['sql']=$sql;
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

function getRumah(){
    getKoneksi();
    $data = $_POST;
    if(arrayKeyCheck(array('api_key', 'username', 'kode_lokasi','periode'), $data)){
        if(authKey($data["api_key"], 'RTRW', $data['username'])){ 
   
            $kode_lokasi=$_POST['kode_lokasi'];
            $blok=$_POST['blok'];

            $response = array("message" => "", "rows" => 0, "status" => "" );

            $sql="select a.kode_rumah
            from rt_rumah a where a.blok='$blok' and a.kode_lokasi='$kode_lokasi'";
            
            $rs = execute($sql);					
            
            while ($row = $rs->FetchNextObject(false)){
                $response['daftar'][] = (array)$row;
            }
            $response['status']=TRUE;
            $response['sql']=$sql;
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

function getEditWarga(){
    getKoneksi();
    $data = $_POST;
    if(arrayKeyCheck(array('api_key', 'username', 'kode_lokasi','periode'), $data)){
        if(authKey($data["api_key"], 'RTRW', $data['username'])){
            $id=$_POST['kode'];
            $kode_lokasi=$_POST['lokasi'];    

            $response = array("message" => "", "rows" => 0, "status" => "" );

            $sql="select distinct no_bukti,tgl_masuk,sts_masuk,kode_blok as blok,no_rumah,kode_pp as rt from rt_warga_d where kode_lokasi='$kode_lokasi' and no_bukti='$id' ";
            
            $rs = execute($sql);					
            
            while ($row = $rs->FetchNextObject(false)){
                $response['daftar'][] = (array)$row;
            }

            $sql2="select * from rt_warga_d where kode_lokasi='$kode_lokasi' and no_bukti='$id' ";
            $rs2 = execute($sql2);					
            
            while ($row2 = $rs2->FetchNextObject(false)){
                $response['daftar2'][] = (array)$row2;
            }

            $response['status'] = TRUE;
            $response['sql'] = $sql;
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

function simpanWarga(){
    getKoneksi();
    $data = $_POST;
    if(arrayKeyCheck(array('api_key', 'username', 'kode_lokasi','periode'), $data)){
        if(authKey($data["api_key"], 'RTRW', $data['username'])){

            $exec = array();
            if($_POST['no_bukti'] == ""){
                $str_format="0000";
                $periode=date('Y').date('m');
                $per=date('y').date('m');
                // $prefix=$_POST['kode_rt']."-WR".$per.".";
                $prefix="WR".$per;
                $sql2="select right(isnull(max(no_bukti),'0000'),".strlen($str_format).")+1 as id from rt_warga_d where no_bukti like '$prefix%' and kode_lokasi='".$_POST['kode_lokasi']."' ";
                
                $query = execute($sql2);

                $id = $prefix.str_pad($query->fields[0], strlen($str_format), $str_format, STR_PAD_LEFT);
            }else{
                $id = $_POST['no_bukti'];
                $sqldel= "delete from rt_warga_d where kode_lokasi='".$_POST['kode_lokasi']."' and no_bukti='".$id."' ";
                array_push($exec,$sqldel);

            }

            $i=1;
            for($a=0; $a<count($_POST['nik']);$a++){
            
            $sql[$a]= "insert into rt_warga_d (kode_blok,no_rumah,no_urut,nama,alias,nik,kode_jk,tempat_lahir,tgl_lahir,kode_agama,kode_goldar,kode_didik,kode_kerja,kode_sts_nikah,kode_kb,kode_sts_hub,kode_sts_wni,no_hp,no_telp_emergency,ket_emergency,tgl_masuk,no_bukti,sts_masuk,kode_lokasi,kode_pp) values ".
                                    "('".$_POST['kode_blok']."','".$_POST['no_rumah']."','".$i."','".$_POST['nama'][$a]."','".$_POST['alias'][$a]."','".$_POST['nik'][$a]."','".$_POST['kode_jk'][$a]."','".$_POST['tempat_lahir'][$a]."','".$_POST['tgl_lahir'][$a]."','".$_POST['kode_agama'][$a]."','".$_POST['kode_goldar'][$a]."','".$_POST['kode_didik'][$a]."','".$_POST['kode_kerja'][$a]."','".$_POST['kode_sts_nikah'][$a]."','".$_POST['kb'][$a]."','".$_POST['kode_sts_hub'][$a]."','".$_POST['kode_sts_wni'][$a]."','".$_POST['no_hp'][$a]."','".$_POST['no_emergency'][$a]."','".$_POST['ket_emergency'][$a]."','".$_POST['tgl_masuk']."','".$id."','".$_POST['sts_masuk']."','".$_POST['kode_lokasi']."','".$_POST['kode_pp']."')";
                array_push($exec,$sql[$a]);	
                $i++;
            
            }

            
            $tmp = array();
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

function hapusWarga(){
    getKoneksi();
    $data = $_POST;
    if(arrayKeyCheck(array('api_key', 'username', 'kode_lokasi','periode'), $data)){
        if(authKey($data["api_key"], 'RTRW', $data['username'])){
            $exec = array();
            $sql="delete from rt_warga_d where no_bukti='".$_POST['id']."' and kode_lokasi='".$_POST['kode_lokasi']."'";
            array_push($exec,$sql);
            $res=executeArray($exec);

            $tmp=array();
            $kode = array();
            if ($res)
            {	
                $tmp="sukses";
                $sts=true;
            }else{
                $tmp="gagal";
                $sts=false;
            }		
            $response["message"] =$tmp;
            $response["status"] = $sts;
            // $response["sql"] = $sql;
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
