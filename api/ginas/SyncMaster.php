<?php
	ini_set('max_execution_time', '1000');
    $request_method=$_SERVER["REQUEST_METHOD"];

    switch($request_method) {
        // case 'GET':
        //     if(isset($_GET["fx"]) AND function_exists($_GET['fx'])){
        //         $_GET['fx']();
        //     }
        // break;
        case 'POST':
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
        include_once($root_lib."lib/koneksi3.php");
        include_once($root_lib."lib/helpers.php");
        require_once($root_lib.'lib/jwt.php');
    }

    function generateToken(){
        getKoneksi();
        $data = $_POST;
        if(arrayKeyCheck(array('nik','pass'), $data)){

            $nik=$data['nik'];
            $pass=$data['pass'];
            
            $response = array("message" => "", "status" => "" );
            $exec=array();
            
            $nik = '';
            $pass = '';
        
            if (isset($_POST['nik'])) {$nik = $_POST['nik'];}
            if (isset($_POST['pass'])) {$pass = $_POST['pass'];}

            $sql="select a.kode_klp_menu, a.nik, a.nama, a.pass, a.status_admin, a.klp_akses, a.kode_lokasi,b.nama as nmlok, c.kode_pp,d.nama as nama_pp,
			b.kode_lokkonsol,d.kode_bidang, c.foto,isnull(e.form,'-') as path_view,b.logo
            from hakakses a 
            inner join lokasi b on b.kode_lokasi = a.kode_lokasi 
            left join karyawan c on a.nik=c.nik and a.kode_lokasi=c.kode_lokasi 
            left join pp d on c.kode_pp=d.kode_pp and c.kode_lokasi=d.kode_lokasi 
            left join m_form e on a.path_view=e.kode_form 
            where a.nik= '$nik' and a.pass='$pass' ";
            $rs=execute($sql,$error);
        
            $row = $rs->FetchNextObject(false);
            if($rs->RecordCount() > 0){
                
                $userId = $nik;
                $serverKey="b1b23c96bb8ecbf68bfba702a8e232b5";
                //SET EXPIRED :
                // $unixTime = time();
                // $nbf  = $unixTime;             
                // $exp     = $nbf + (60 * 60);  // 1 jam
                // create a token
                $payloadArray = array();
                $payloadArray['userId'] = $userId;
                $payloadArray['kode_lokasi'] = $row->kode_lokasi;
                if (isset($nbf)) {$payloadArray['nbf'] = $nbf;}
                if (isset($exp)) {$payloadArray['exp'] = $exp;}
                $token = JWT::encode($payloadArray, $serverKey);
                // return to caller
                $response = array('token' => $token,'status'=>true,'message'=>'Login Success');

            } 
            else {
                $response = array('message' => 'Invalid user ID or password.','status'=>false);
               
            }

        }else{
            $response = array('message' => "Kode Lokasi, Modul, and NIK required",'status'=>false);
        }
        
        echo json_encode($response);

    }

    function authKey($token){
        getKoneksi();
        $token = $token;
        $date = date('Y-m-d H:i:s');
        $serverKey="b1b23c96bb8ecbf68bfba702a8e232b5";
        try {
            $payload = JWT::decode($token, $serverKey, array('HS256'));
            if(isset($payload->userId)  || $payload->userId != ''){
                if (isset($payload->exp)) {
                    $returnArray['exp'] = date(DateTime::ISO8601, $payload->exp);

                    if (isset($payload->kode_lokasi)) {
                        $returnArray['kode_lokasi'] = $payload->kode_lokasi;
                    }

                    if ($payload->exp < time()) {
                        $returnArray['status'] = false;
                        $returnArray['message'] = "Your token was expired";
                    } else {
                        $returnArray['status'] = true;
                    }
                }else{

                    if (isset($payload->kode_lokasi)) {
                        $returnArray['kode_lokasi'] = $payload->kode_lokasi;
                    }
                    $returnArray['status'] = true;
                }
            }
            
        }
        catch(Exception $e) {
            $returnArray = array('message' => $e->getMessage(),'status'=>false);
        }
       
        return $returnArray;
    }

    
    function generateKode($tabel, $kolom_acuan, $prefix, $str_format){
        $query = execute("select right(max($kolom_acuan), ".strlen($str_format).")+1 as id from $tabel where $kolom_acuan like '$prefix%'");
        $kode = $query->fields[0];
        $id = $prefix.str_pad($kode, strlen($str_format), $str_format, STR_PAD_LEFT);
        return $id;
    }

    function syncMaster(){
        getKoneksi();
        if(empty($_POST)){
            $data = json_decode(file_get_contents('php://input'), true);
        }else{
            $data = $_POST;
        }
         
        $header = getallheaders();
        $bearer = $header["Authorization"];
        list($token) = sscanf($bearer,'Bearer %s');
        $res = authKey($token);
        if($res["status"]){

            if(isset($data['kode_lokasi']) && $data['kode_lokasi'] != ""){

                $kode_lokasi=$data['kode_lokasi'];
            }else{
                $kode_lokasi=$res["kode_lokasi"];
            }
            $exec=array();

            //CUSTOMER
            // $cust = $data["CUST"];
            // if(count($cust) > 0){
            //     // $del = "delete from cust where kode_lokasi='$kode_lokasi' and kode_cust='".$cust[$i]["kode_cust"]."' ";
            //     $del = "delete from cust where kode_lokasi='$kode_lokasi'";
            //     array_push($exec,$del);
            //     for($i=0;$i<count($cust);$i++){
            //         $ins = "insert into cust (kode_cust,nama,alamat,no_tel,email,npwp,pic,alamat2,jenis,sts_gar,no_fax,akun_piutang,kode_lokasi) values ('".$cust[$i]['kode_cust']."','".$cust[$i]['nama']."','".$cust[$i]['alamat']."','".$cust[$i]['no_telp']."','".$cust[$i]['email']."','".$cust[$i]['npwp']."','".$cust[$i]['pic']."','".$cust[$i]['alamat2']."','".$cust[$i]['jenis']."','".$cust[$i]['sts_gar']."','".$cust[$i]['no_fax']."','".$cust[$i]['akun_pitang']."','$kode_lokasi')";
            //         array_push($exec,$ins);
            //     }
            // }

            //VENDOR
            $vendor = $data["VENDOR"];
            if(count($vendor) > 0){
                // $del2 = "delete from vendor where kode_lokasi='$kode_lokasi' and kode_vendor='".$vendor[$i]['kode_vendor']."' ";
                $del2 = "delete from vendor where kode_lokasi='$kode_lokasi' ";
                array_push($exec,$del2);
                for($i=0;$i<count($vendor);$i++){
                    $ins2= "insert into vendor(kode_vendor,kode_lokasi,nama,alamat,no_tel,email,npwp,pic,alamat2,bank,cabang,no_rek,nama_rek,no_fax,no_pictel,spek,kode_klpvendor,penilaian,bank_trans,akun_hutang) values ('".$vendor[$i]['kode_vendor']."','".$kode_lokasi."','".$vendor[$i]['nama']."','".$vendor[$i]['alamat']."','".$vendor[$i]['no_telp']."','".$vendor[$i]['email']."','".$vendor[$i]['npwp']."','".$vendor[$i]['pic']."','".$vendor[$i]['alamat_npwp']."','".$vendor[$i]['bank']."','".$vendor[$i]['cabang']."','".$vendor[$i]['no_rek']."','".$vendor[$i]['nama_rek']."','".$vendor[$i]['no_fax']."','".$vendor[$i]['no_tel2']."','-','-','-','-','".$vendor[$i]['akun_hutang']."') ";
                    array_push($exec,$ins2);
                }
            }

            //BARANG
            $barang = $data["BARANG"];
            if(count($barang) > 0){
                // $del3 = "delete from brg_barang where kode_lokasi='$kode_lokasi' and kode_barang='".$barang[$i]['kode_barang']."' ";
                $del3 = "delete from brg_barang where kode_lokasi='$kode_lokasi' ";
                array_push($exec,$del3);
                for($i=0;$i<count($barang);$i++){
                    $hna=(joinNum($barang[$i]['harga_jual']) != "" ? joinNum($barang[$i]['harga_jual']) : 0);
                    $ss=(joinNum($barang[$i]['ss']) != "" ? joinNum($barang[$i]['ss']) : 0);
                    $sm1=(joinNum($barang[$i]['sm1']) != "" ? joinNum($barang[$i]['sm1']) : 0);
                    $sm2=(joinNum($barang[$i]['sm2']) != "" ? joinNum($barang[$i]['sm2']) : 0);
                    $mm1=(joinNum($barang[$i]['mm1']) != "" ? joinNum($barang[$i]['mm1']) : 0);
                    $mm2=(joinNum($barang[$i]['mm2']) != "" ? joinNum($barang[$i]['mm2']) : 0);
                    $fm1=(joinNum($barang[$i]['fm1']) != "" ? joinNum($barang[$i]['fm1']) : 0);
                    $fm2=(joinNum($barang[$i]['fm2']) != "" ? joinNum($barang[$i]['fm2']) : 0);
                    
                    $hrg_satuan=(joinNum($barang[$i]['hrg_satuan']) != "" ? joinNum($barang[$i]['hrg_satuan']) : 0);
                    $ppn=(joinNum($barang[$i]['ppn']) != "" ? joinNum($barang[$i]['ppn']) : 0);
                    $profit=(joinNum($barang[$i]['profit']) != "" ? joinNum($barang[$i]['profit']) : 0);
        
                    $ins3= "insert into brg_barang(kode_barang,nama,kode_lokasi,sat_kecil,sat_besar,jml_sat,hna,pabrik,flag_gen,flag_aktif,ss,sm1,sm2,mm1,mm2,fm1,fm2,kode_klp,file_gambar,barcode,hrg_satuan,ppn,profit) values ('".$barang[$i]['kode_barang']."','".$barang[$i]['nama']."','".$kode_lokasi."','".$barang[$i]['kode_satuan']."','-',1,".$hna.",'".$barang[$i]['keterangan']."','-','1',".$ss.",".$sm1.",".$sm2.",".$mm1.",".$mm2.",".$fm1.",".$fm2.",'".$barang[$i]['kode_klp']."','".$filepath."','".$barang[$i]['barcode']."',$hrg_satuan,$ppn,$profit) ";
        
                    array_push($exec,$ins3);
                }
            }

            //GUDANG
            $gudang = $data["GUDANG"];
            if(count($gudang) > 0){
                // $del4 = "delete from brg_gudang where kode_lokasi='$kode_lokasi' and kode_gudang='".$gudang[$i]['kode_gudang']."' ";
                
                $del4 = "delete from brg_gudang where kode_lokasi='$kode_lokasi' ";
                array_push($exec,$del4);
                for($i=0;$i<count($gudang);$i++){
        
                    $ins4= "insert into brg_gudang(kode_gudang,kode_lokasi,nama,pic,telp,alamat,kode_pp) values ('".$gudang[$i]['kode_gudang']."','".$kode_lokasi."','".$gudang[$i]['nama']."','".$gudang[$i]['pic']."','".$gudang[$i]['telp']."','".$gudang[$i]['alamat']."','".$gudang[$i]['kode_pp']."') ";
        
                    array_push($exec,$ins4);
                }
            }

            //BARANG KLP
            $klp = $data["BARANGKLP"];
            if(count($klp) > 0){
                // $del5 = "delete from brg_barangklp where kode_lokasi='$kode_lokasi' and kode_klp= '".$klp[$i]['kode_klp']."' ";
                $del5 = "delete from brg_barangklp where kode_lokasi='$kode_lokasi' ";
                array_push($exec,$del5);
                for($i=0;$i<count($klp);$i++){
        
                    $ins5= "insert into brg_barangklp(kode_klp,kode_lokasi,nama,akun_pers,akun_pdpt,akun_hpp) values ('".$klp[$i]['kode_klp']."','".$kode_lokasi."','".$klp[$i]['nama']."','".$klp[$i]['akun_pers']."','".$klp[$i]['akun_pdpt']."','".$klp[$i]['akun_hpp']."') ";
                    array_push($exec,$ins5);
                }
            }

            //SATUAN
            $satuan = $data["SATUAN"];
            if(count($satuan) > 0){
                // $del6 = "delete from brg_satuan where kode_lokasi='$kode_lokasi' and kode_satuan='".$satuan[$i]['kode_satuan']."' ";
                $del6 = "delete from brg_satuan where kode_lokasi='$kode_lokasi' ";
                array_push($exec,$del6);
                for($i=0;$i<count($satuan);$i++){
        
                    $ins6= "insert into brg_satuan(kode_satuan,kode_lokasi,nama) values ('".$satuan[$i]['kode_satuan']."','".$kode_lokasi."','".$satuan[$i]['nama']."') ";

                    array_push($exec,$ins6);
                }
            }
            //BONUS
            $bonus = $data["BONUS"];
            if(count($bonus) > 0){
                // $del7 = "delete from brg_bonus where kode_lokasi='$kode_lokasi' and kode_barang ='".$bonus[$i]['kode_barang']."'";
                $del7 = "delete from brg_bonus where kode_lokasi='$kode_lokasi' ";
                array_push($exec,$del7);
                for($i=0;$i<count($bonus);$i++){

                    $ref_qty=(joinNum($barang[$i]['ref_qty']) != "" ? joinNum($barang[$i]['ref_qty']) : 0);
                    $bonus_qty=(joinNum($barang[$i]['bonus_qty']) != "" ? joinNum($barang[$i]['bonus_qty']) : 0);
        
                    $ins7= "insert into brg_bonus(kode_barang,keterangan,kode_lokasi,ref_qty,bonus_qty,tgl_mulai,tgl_selesai) values ('".$bonus[$i]['kode_barang']."','".$bonus[$i]['keterangan']."','".$kode_lokasi."',".$ref_qty.",".$bonus_qty.",'".$bonus[$i]['tgl_mulai']."','".$bonus[$i]['tgl_selesai']."') ";


                    array_push($exec,$ins7);
                }
            }

            $rs=executeArray($exec,$err);
            // $rs = true;
            if($err == null) {
                $response=array(
                    'status' => true,
                    'message' =>'Synchronize Data Successfully.'
                );
            }
            else {
                $response=array(
                    'status' => false,
                    'message' =>'Synchronize Data Failed.'.$err
                );
                
            }
          
        }else{
            $response['status'] = false;
            $response['message'] = "Unauthorized Access ".$res["message"]."token:".$token;
            
        }
        echo json_encode($response);

    }


    function syncPnj(){
        getKoneksi();
        if(empty($_POST)){
            $data = json_decode(file_get_contents('php://input'), true);
        }else{
            $data = $_POST;
        }
        $response['cek']=$data["TRANSM"];
         
        $header = getallheaders();
        $bearer = $header["Authorization"];
        list($token) = sscanf($bearer,'Bearer %s');
        $res = authKey($token);
        if($res["status"]){

            if(isset($data['kode_lokasi']) && $data['kode_lokasi'] != ""){

                $kode_lokasi=$data['kode_lokasi'];
            }else{
                $kode_lokasi=$res["kode_lokasi"];
            }
            $exec=array();

            //TRANSM
            $transm = $data["TRANSM"];
            if(count($transm) > 0){
                for($i=0;$i<count($transm);$i++){

                    $kurs=($transm[$i]['kurs'] != "" ? $transm[$i]['kurs'] : 0);
                    $nilai1=($transm[$i]['nilai1'] != "" ? $transm[$i]['nilai1'] : 0);
                    $nilai2=($transm[$i]['nilai2'] != "" ? $transm[$i]['nilai2'] : 0);
                    $nilai3=($transm[$i]['nilai3'] != "" ? $transm[$i]['nilai3'] : 0);
                    $ins = "insert into trans_m (no_bukti,kode_lokasi,tgl_input,nik_user,periode,modul,form,posted,prog_seb,progress,kode_pp,tanggal,no_dokumen,keterangan,kode_curr,kurs,nilai1,nilai2,nilai3,nik1,nik2,nik3,no_ref1,no_ref2,no_ref3,param1,param2,param3) values 
                    ('".$transm[$i]["no_bukti"]."','".$transm[$i]["kode_lokasi"]."','".$transm[$i]["tgl_input"]."','".$transm[$i]["nik_user"]."','".$transm[$i]["periode"]."','".$transm[$i]["modul"]."','".$transm[$i]["form"]."','".$transm[$i]["posted"]."','".$transm[$i]["prog_seb"]."','".$transm[$i]["progress"]."','".$transm[$i]["kode_pp"]."','".$transm[$i]["tanggal"]."','".$transm[$i]["no_dokumen"]."','".$transm[$i]["keterangan"]."','".$transm[$i]["kode_curr"]."',".$kurs.",".$nilai1.",".$nilai2.",".$nilai3.",'".$transm[$i]["nik1"]."','".$transm[$i]["nik2"]."','".$transm[$i]["nik3"]."','".$transm[$i]["no_ref1"]."','".$transm[$i]["no_ref2"]."','".$transm[$i]["no_ref3"]."','".$transm[$i]["param1"]."','".$transm[$i]["param2"]."','".$transm[$i]["param3"]."')";
                    array_push($exec,$ins);
                }
            }

            //TRANSJ
            $transj = $data["TRANSJ"];
            if(count($transj) > 0){
                
                for($i=0;$i<count($transj);$i++){
                    
                    $kurs=($transj[$i]['kurs'] != "" ? $transj[$i]['kurs'] : 0);
                    $nu=($transm[$i]['nu'] != "" ? $transm[$i]['nu'] : 0);
                    $nilai=($transj[$i]['nilai'] != "" ? $transj[$i]['nilai'] : 0);
                    $nilai_curr=($transj[$i]['nilai_curr'] != "" ? $transj[$i]['nilai_curr'] : 0);
                    $ins2="insert into trans_j (no_bukti,kode_lokasi,tgl_input,nik_user,periode,no_dokumen,tanggal,nu,kode_akun,dc,nilai,nilai_curr,keterangan,modul,jenis,kode_curr,kurs,kode_pp,kode_drk,kode_cust,kode_vendor,no_fa,no_selesai,no_ref1,no_ref2,no_ref3) values 
                    ('".$transj[$i]["no_bukti"]."','".$transj[$i]["kode_lokasi"]."','".$transj[$i]["tgl_input"]."','".$transj[$i]["nik_user"]."','".$transj[$i]["periode"]."','".$transj[$i]["no_dokumen"]."','".$transj[$i]["tanggal"]."',".$nu.",'".$transj[$i]["kode_akun"]."','".$transj[$i]["dc"]."',".$nilai.",".$nilai_curr.",'".$transj[$i]["keterangan"]."','".$transj[$i]["keterangan"]."','".$transj[$i]["modul"]."','".$transj[$i]["jenis"]."',".$kurs.",'".$transj[$i]["kode_pp"]."','".$transj[$i]["kode_drk"]."','".$transj[$i]["kode_cust"]."','".$transj[$i]["kode_vendor"]."','".$transj[$i]["no_fa"]."','".$transj[$i]["no_selesai"]."','".$transj[$i]["no_ref1"]."','".$transj[$i]["no_ref2"]."','".$transj[$i]["no_ref3"]."')";
                    array_push($exec,$ins2);
                }
            }

            //BRGJUAL
            $brgJual = $data["BRGJUAL"];
            if(count($brgJual) > 0){
                for($i=0;$i<count($brgJual);$i++){
                    $kurs=($brgJual[$i]['kurs'] != "" ? $brgJual[$i]['kurs'] : 0);
                    $nilai=($brgJual[$i]['nilai'] != "" ? $brgJual[$i]['nilai'] : 0);
                    $nilai_ppn=($brgJual[$i]['nilai_ppn'] != "" ? $brgJual[$i]['nilai_ppn'] : 0);
                    $nilai_pph=($brgJual[$i]['nilai_pph'] != "" ? $brgJual[$i]['nilai_pph'] : 0);
                    $diskon=($brgJual[$i]['diskon'] != "" ? $brgJual[$i]['diskon'] : 0);
                    $tobyr=($brgJual[$i]['tobyr'] != "" ? $brgJual[$i]['tobyr'] : 0);

                    $ins3 = "insert into brg_jualpiu_d (no_jual,kode_lokasi,tanggal,keterangan,kode_cust,kode_curr,kurs,kode_pp,nilai,periode,nik_user,tgl_input,akun_piutang,nilai_ppn,nilai_pph,no_fp,diskon,kode_gudang,no_ba,tobyr,no_open,no_close) values ('".$brgJual[$i]["no_jual"]."','".$brgJual[$i]["kode_lokasi"]."','".$brgJual[$i]["tanggal"]."','".$brgJual[$i]["keterangan"]."','".$brgJual[$i]["kode_cust"]."','".$brgJual[$i]["kode_curr"]."',$kurs,'".$brgJual[$i]["kode_pp"]."',".$nilai.",'".$brgJual[$i]["periode"]."','".$brgJual[$i]["nik_user"]."','".$brgJual[$i]["tgl_input"]."','".$brgJual[$i]["akun_piutang"]."',".$nilai_ppn.",".$nilai_pph.",'".$brgJual[$i]["no_fp"]."',".$diskon.",'".$brgJual[$i]["kode_gudang"]."','".$brgJual[$i]["no_ba"]."',".$tobyr.",'".$brgJual[$i]["no_open"]."','".$brgJual[$i]["no_close"]."') ";
                        
                    array_push($exec,$ins3);
                }
            }

            //BRGTRANS
            $brgTrans = $data["BRGTRANS"];
            if(count($brgTrans) > 0){
                for($i=0;$i<count($brgTrans);$i++){
                    $stok=($brgTrans[$i]['stok'] != "" ? $brgTrans[$i]['stok'] : 0);  
                    $jumlah=($brgTrans[$i]['jumlah'] != "" ? $brgTrans[$i]['jumlah'] : 0);
                    $bonus=($brgTrans[$i]['bonus'] != "" ? $brgTrans[$i]['bonus'] : 0);
                    $harga=($brgTrans[$i]['harga'] != "" ? $brgTrans[$i]['harga'] : 0);
                    $hpp_p=($brgTrans[$i]['hpp_p'] != "" ? $brgTrans[$i]['hpp_p'] : 0);  
                    $p_disk=($brgTrans[$i]['p_disk'] != "" ? $brgTrans[$i]['p_disk'] : 0);
                    $diskon=($brgTrans[$i]['diskon'] != "" ? $brgTrans[$i]['diskon'] : 0);
                    $tot_diskon=($brgTrans[$i]['tot_diskon'] != "" ? $brgTrans[$i]['tot_diskon'] : 0);
                    $total=($brgTrans[$i]['total'] != "" ? $brgTrans[$i]['total'] : 0);
                    $ins4= "insert into brg_trans_d (no_bukti,kode_lokasi,periode,modul,form,nu,kode_gudang,kode_barang,no_batch,tgl_ed,satuan,dc,stok,jumlah,bonus,harga,hpp,p_disk,diskon,tot_diskon,total) values 
                        ('".$brgTrans[$i]["no_bukti"]."','".$brgTrans[$i]["kode_lokasi"]."','".$brgTrans[$i]["periode"]."','".$brgTrans[$i]["modul"]."','".$brgTrans[$i]["form"]."',".$brgTrans[$i]["nu"].",'".$brgTrans[$i]["kode_gudang"]."','".$brgTrans[$i]["kode_barang"]."','".$brgTrans[$i]["no_batch"]."','".$brgTrans[$i]["tgl_ed"]."','".$brgTrans[$i]["satuan"]."','".$brgTrans[$i]["dc"]."',".$stok.",".$jumlah.",".$bonus.",".$harga.",".$hpp_p.",".$p_disk.",".$diskon.",".$tot_diskon.",".$total.") ";
                        
        
                    array_push($exec,$ins4);
                }
            }

            $rs=executeArray($exec,$err);
            // $rs = true;
            if($err == null) {
                $response=array(
                    'status' => true,
                    'message' =>'Synchronize Data Successfully.',
                );
            }
            else {
                $response=array(
                    'status' => false,
                    'message' =>'Synchronize Data Failed.'.$err
                );
                
            }
          
        }else{
            $response['status'] = false;
            $response['message'] = "Unauthorized Access ".$res["message"]."token:".$token;
            
        }
        echo json_encode($response);

    }

    

    function syncPmb(){
        getKoneksi();
        if(empty($_POST)){
            $data = json_decode(file_get_contents('php://input'), true);
        }else{
            $data = $_POST;
        }
         
        $header = getallheaders();
        $bearer = $header["Authorization"];
        list($token) = sscanf($bearer,'Bearer %s');
        $res = authKey($token);
        if($res["status"]){

            if(isset($data['kode_lokasi']) && $data['kode_lokasi'] != ""){

                $kode_lokasi=$data['kode_lokasi'];
            }else{
                $kode_lokasi=$res["kode_lokasi"];
            }
            $exec=array();

            //TRANSM
            $transm = $data["TRANSM"];
            if(count($transm) > 0){
                for($i=0;$i<count($transm);$i++){

                    $kurs=($transm[$i]['kurs'] != "" ? $transm[$i]['kurs'] : 0);
                    $nilai1=($transm[$i]['nilai1'] != "" ? $transm[$i]['nilai1'] : 0);
                    $nilai2=($transm[$i]['nilai2'] != "" ? $transm[$i]['nilai2'] : 0);
                    $nilai3=($transm[$i]['nilai3'] != "" ? $transm[$i]['nilai3'] : 0);
                    $ins = "insert into trans_m (no_bukti,kode_lokasi,tgl_input,nik_user,periode,modul,form,posted,prog_seb,progress,kode_pp,tanggal,no_dokumen,keterangan,kode_curr,kurs,nilai1,nilai2,nilai3,nik1,nik2,nik3,no_ref1,no_ref2,no_ref3,param1,param2,param3) values 
                    ('".$transm[$i]["no_bukti"]."','".$transm[$i]["kode_lokasi"]."','".$transm[$i]["tgl_input"]."','".$transm[$i]["nik_user"]."','".$transm[$i]["periode"]."','".$transm[$i]["modul"]."','".$transm[$i]["form"]."','".$transm[$i]["posted"]."','".$transm[$i]["prog_seb"]."','".$transm[$i]["progress"]."','".$transm[$i]["kode_pp"]."','".$transm[$i]["tanggal"]."','".$transm[$i]["no_dokumen"]."','".$transm[$i]["keterangan"]."','".$transm[$i]["kode_curr"]."',".$kurs.",".$nilai1.",".$nilai2.",".$nilai3.",'".$transm[$i]["nik1"]."','".$transm[$i]["nik2"]."','".$transm[$i]["nik3"]."','".$transm[$i]["no_ref1"]."','".$transm[$i]["no_ref2"]."','".$transm[$i]["no_ref3"]."','".$transm[$i]["param1"]."','".$transm[$i]["param2"]."','".$transm[$i]["param3"]."')";
                    array_push($exec,$ins);
                }
            }

            //TRANSJ
            $transj = $data["TRANSJ"];
            if(count($transj) > 0){
                
                for($i=0;$i<count($transj);$i++){
                    
                    $kurs=($transj[$i]['kurs'] != "" ? $transj[$i]['kurs'] : 0);
                    $nu=($transm[$i]['nu'] != "" ? $transm[$i]['nu'] : 0);
                    $nilai=($transj[$i]['nilai'] != "" ? $transj[$i]['nilai'] : 0);
                    $nilai_curr=($transj[$i]['nilai_curr'] != "" ? $transj[$i]['nilai_curr'] : 0);
                    $ins2="insert into trans_j (no_bukti,kode_lokasi,tgl_input,nik_user,periode,no_dokumen,tanggal,nu,kode_akun,dc,nilai,nilai_curr,keterangan,modul,jenis,kode_curr,kurs,kode_pp,kode_drk,kode_cust,kode_vendor,no_fa,no_selesai,no_ref1,no_ref2,no_ref3) values 
                    ('".$transj[$i]["no_bukti"]."','".$transj[$i]["kode_lokasi"]."','".$transj[$i]["tgl_input"]."','".$transj[$i]["nik_user"]."','".$transj[$i]["periode"]."','".$transj[$i]["no_dokumen"]."','".$transj[$i]["tanggal"]."',".$nu.",'".$transj[$i]["kode_akun"]."','".$transj[$i]["dc"]."',".$nilai.",".$nilai_curr.",'".$transj[$i]["keterangan"]."','".$transj[$i]["keterangan"]."','".$transj[$i]["modul"]."','".$transj[$i]["jenis"]."',".$kurs.",'".$transj[$i]["kode_pp"]."','".$transj[$i]["kode_drk"]."','".$transj[$i]["kode_cust"]."','".$transj[$i]["kode_vendor"]."','".$transj[$i]["no_fa"]."','".$transj[$i]["no_selesai"]."','".$transj[$i]["no_ref1"]."','".$transj[$i]["no_ref2"]."','".$transj[$i]["no_ref3"]."')";
                    array_push($exec,$ins2);
                }
            }

            //BRGBELI HUT
            $brgBeli = $data["BRGHUT"];
            if(count($brgBeli) > 0){
                for($i=0;$i<count($brgBeli);$i++){
                    $kurs=($brgBeli[$i]['kurs'] != "" ? $brgBeli[$i]['kurs'] : 0);
                    $nilai=($brgBeli[$i]['nilai'] != "" ? $brgBeli[$i]['nilai'] : 0);
                    $nilai_ppn=($brgBeli[$i]['nilai_ppn'] != "" ? $brgBeli[$i]['nilai_ppn'] : 0);
                    $nilai_pph=($brgBeli[$i]['nilai_pph'] != "" ? $brgBeli[$i]['nilai_pph'] : 0);
                    $diskon=($brgBeli[$i]['diskon'] != "" ? $brgBeli[$i]['diskon'] : 0);

                    $ins3 = "insert into brg_belihut_d (no_beli, kode_lokasi, tanggal, keterangan, kode_vendor, kode_curr, kurs, kode_pp, nilai, periode, nik_user, tgl_input, akun_hutang, nilai_ppn, no_fp, due_date, nilai_pph, diskon, modul, kode_gudang
                    ) values ('".$brgBeli[$i]["no_beli"]."','".$brgBeli[$i]["kode_lokasi"]."','".$brgBeli[$i]["tanggal"]."','".$brgBeli[$i]["keterangan"]."','".$brgBeli[$i]["kode_vendor"]."','".$brgBeli[$i]["kode_curr"]."',$kurs,'".$brgBeli[$i]["kode_pp"]."',".$nilai.",'".$brgBeli[$i]["periode"]."','".$brgBeli[$i]["nik_user"]."','".$brgBeli[$i]["tgl_input"]."','".$brgBeli[$i]["akun_hutang"]."',".$nilai_ppn.",'".$brgBeli[$i]["no_fp"]."','".$brgBeli[$i]["due_date"]."',".$nilai_pph.",$diskon,'".$brgBeli[$i]["modul"]."','".$brgBeli[$i]["kode_gudang"]."') ";
                        
                    array_push($exec,$ins3);
                }
            }

            //BRGTRANS
            $brgTrans = $data["BRGTRANS"];
            if(count($brgTrans) > 0){
                for($i=0;$i<count($brgTrans);$i++){
                    $stok=($brgTrans[$i]['stok'] != "" ? $brgTrans[$i]['stok'] : 0);  
                    $jumlah=($brgTrans[$i]['jumlah'] != "" ? $brgTrans[$i]['jumlah'] : 0);
                    $bonus=($brgTrans[$i]['bonus'] != "" ? $brgTrans[$i]['bonus'] : 0);
                    $harga=($brgTrans[$i]['harga'] != "" ? $brgTrans[$i]['harga'] : 0);
                    $hpp_p=($brgTrans[$i]['hpp_p'] != "" ? $brgTrans[$i]['hpp_p'] : 0);  
                    $p_disk=($brgTrans[$i]['p_disk'] != "" ? $brgTrans[$i]['p_disk'] : 0);
                    $diskon=($brgTrans[$i]['diskon'] != "" ? $brgTrans[$i]['diskon'] : 0);
                    $tot_diskon=($brgTrans[$i]['tot_diskon'] != "" ? $brgTrans[$i]['tot_diskon'] : 0);
                    $total=($brgTrans[$i]['total'] != "" ? $brgTrans[$i]['total'] : 0);
                    $ins4= "insert into brg_trans_d (no_bukti,kode_lokasi,periode,modul,form,nu,kode_gudang,kode_barang,no_batch,tgl_ed,satuan,dc,stok,jumlah,bonus,harga,hpp,p_disk,diskon,tot_diskon,total) values 
                        ('".$brgTrans[$i]["no_bukti"]."','".$brgTrans[$i]["kode_lokasi"]."','".$brgTrans[$i]["periode"]."','".$brgTrans[$i]["modul"]."','".$brgTrans[$i]["form"]."',".$brgTrans[$i]["nu"].",'".$brgTrans[$i]["kode_gudang"]."','".$brgTrans[$i]["kode_barang"]."','".$brgTrans[$i]["no_batch"]."','".$brgTrans[$i]["tgl_ed"]."','".$brgTrans[$i]["satuan"]."','".$brgTrans[$i]["dc"]."',".$stok.",".$jumlah.",".$bonus.",".$harga.",".$hpp_p.",".$p_disk.",".$diskon.",".$tot_diskon.",".$total.") ";
                        
        
                    array_push($exec,$ins4);
                }
            }

            $rs=executeArray($exec,$err);
            // $rs = true;
            if($err == null) {
                $response=array(
                    'status' => true,
                    'message' =>'Synchronize Data Successfully.',
                );
            }
            else {
                $response=array(
                    'status' => false,
                    'message' =>'Synchronize Data Failed.'.$err
                );
                
            }
          
        }else{
            $response['status'] = false;
            $response['message'] = "Unauthorized Access ".$res["message"]."token:".$token;
            
        }
        echo json_encode($response);

    }

    function syncReturBeli(){
        getKoneksi();
        if(empty($_POST)){
            $data = json_decode(file_get_contents('php://input'), true);
        }else{
            $data = $_POST;
        }
         
        $header = getallheaders();
        $bearer = $header["Authorization"];
        list($token) = sscanf($bearer,'Bearer %s');
        $res = authKey($token);
        if($res["status"]){

            if(isset($data['kode_lokasi']) && $data['kode_lokasi'] != ""){

                $kode_lokasi=$data['kode_lokasi'];
            }else{
                $kode_lokasi=$res["kode_lokasi"];
            }
            $exec=array();

            //TRANSM
            $transm = $data["TRANSM"];
            if(count($transm) > 0){
                for($i=0;$i<count($transm);$i++){

                    $kurs=($transm[$i]['kurs'] != "" ? $transm[$i]['kurs'] : 0);
                    $nilai1=($transm[$i]['nilai1'] != "" ? $transm[$i]['nilai1'] : 0);
                    $nilai2=($transm[$i]['nilai2'] != "" ? $transm[$i]['nilai2'] : 0);
                    $nilai3=($transm[$i]['nilai3'] != "" ? $transm[$i]['nilai3'] : 0);
                    $ins = "insert into trans_m (no_bukti,kode_lokasi,tgl_input,nik_user,periode,modul,form,posted,prog_seb,progress,kode_pp,tanggal,no_dokumen,keterangan,kode_curr,kurs,nilai1,nilai2,nilai3,nik1,nik2,nik3,no_ref1,no_ref2,no_ref3,param1,param2,param3) values 
                    ('".$transm[$i]["no_bukti"]."','".$transm[$i]["kode_lokasi"]."','".$transm[$i]["tgl_input"]."','".$transm[$i]["nik_user"]."','".$transm[$i]["periode"]."','".$transm[$i]["modul"]."','".$transm[$i]["form"]."','".$transm[$i]["posted"]."','".$transm[$i]["prog_seb"]."','".$transm[$i]["progress"]."','".$transm[$i]["kode_pp"]."','".$transm[$i]["tanggal"]."','".$transm[$i]["no_dokumen"]."','".$transm[$i]["keterangan"]."','".$transm[$i]["kode_curr"]."',".$kurs.",".$nilai1.",".$nilai2.",".$nilai3.",'".$transm[$i]["nik1"]."','".$transm[$i]["nik2"]."','".$transm[$i]["nik3"]."','".$transm[$i]["no_ref1"]."','".$transm[$i]["no_ref2"]."','".$transm[$i]["no_ref3"]."','".$transm[$i]["param1"]."','".$transm[$i]["param2"]."','".$transm[$i]["param3"]."')";
                    array_push($exec,$ins);
                }
            }

            //TRANSJ
            $transj = $data["TRANSJ"];
            if(count($transj) > 0){
                
                for($i=0;$i<count($transj);$i++){
                    
                    $kurs=($transj[$i]['kurs'] != "" ? $transj[$i]['kurs'] : 0);
                    $nu=($transm[$i]['nu'] != "" ? $transm[$i]['nu'] : 0);
                    $nilai=($transj[$i]['nilai'] != "" ? $transj[$i]['nilai'] : 0);
                    $nilai_curr=($transj[$i]['nilai_curr'] != "" ? $transj[$i]['nilai_curr'] : 0);
                    $ins2="insert into trans_j (no_bukti,kode_lokasi,tgl_input,nik_user,periode,no_dokumen,tanggal,nu,kode_akun,dc,nilai,nilai_curr,keterangan,modul,jenis,kode_curr,kurs,kode_pp,kode_drk,kode_cust,kode_vendor,no_fa,no_selesai,no_ref1,no_ref2,no_ref3) values 
                    ('".$transj[$i]["no_bukti"]."','".$transj[$i]["kode_lokasi"]."','".$transj[$i]["tgl_input"]."','".$transj[$i]["nik_user"]."','".$transj[$i]["periode"]."','".$transj[$i]["no_dokumen"]."','".$transj[$i]["tanggal"]."',".$nu.",'".$transj[$i]["kode_akun"]."','".$transj[$i]["dc"]."',".$nilai.",".$nilai_curr.",'".$transj[$i]["keterangan"]."','".$transj[$i]["keterangan"]."','".$transj[$i]["modul"]."','".$transj[$i]["jenis"]."',".$kurs.",'".$transj[$i]["kode_pp"]."','".$transj[$i]["kode_drk"]."','".$transj[$i]["kode_cust"]."','".$transj[$i]["kode_vendor"]."','".$transj[$i]["no_fa"]."','".$transj[$i]["no_selesai"]."','".$transj[$i]["no_ref1"]."','".$transj[$i]["no_ref2"]."','".$transj[$i]["no_ref3"]."')";
                    array_push($exec,$ins2);
                }
            }

            //BRGBELI BAYAR
            $brgBeli = $data["BRGBAYAR"];
            if(count($brgBeli) > 0){
                for($i=0;$i<count($brgBeli);$i++){
                    $nilai=($brgBeli[$i]['nilai'] != "" ? $brgBeli[$i]['nilai'] : 0);

                    $ins3 = "insert into brg_belibayar_d(no_bukti,kode_lokasi,no_beli,kode_vendor,periode,dc,modul,nilai,nik_user,tgl_input) 
                    values ('".$brgBeli[$i]["no_bukti"]."','".$brgBeli[$i]["kode_lokasi"]."','".$brgBeli[$i]['no_beli']."','".$brgBeli[$i]['kode_vendor']."', '".$brgBeli[$i]['periode']."','".$brgBeli[$i]["dc"]."','".$brgBeli[$i]["modul"]."',".$nilai.",'".$brgBeli[$i]['nik_user']."','".$brgBeli[$i]['tgl_input']."')";
                   
                        
                    array_push($exec,$ins3);
                }
            }

            //BRGTRANS
            $brgTrans = $data["BRGTRANS"];
            if(count($brgTrans) > 0){
                for($i=0;$i<count($brgTrans);$i++){
                    $stok=($brgTrans[$i]['stok'] != "" ? $brgTrans[$i]['stok'] : 0);  
                    $jumlah=($brgTrans[$i]['jumlah'] != "" ? $brgTrans[$i]['jumlah'] : 0);
                    $bonus=($brgTrans[$i]['bonus'] != "" ? $brgTrans[$i]['bonus'] : 0);
                    $harga=($brgTrans[$i]['harga'] != "" ? $brgTrans[$i]['harga'] : 0);
                    $hpp_p=($brgTrans[$i]['hpp_p'] != "" ? $brgTrans[$i]['hpp_p'] : 0);  
                    $p_disk=($brgTrans[$i]['p_disk'] != "" ? $brgTrans[$i]['p_disk'] : 0);
                    $diskon=($brgTrans[$i]['diskon'] != "" ? $brgTrans[$i]['diskon'] : 0);
                    $tot_diskon=($brgTrans[$i]['tot_diskon'] != "" ? $brgTrans[$i]['tot_diskon'] : 0);
                    $total=($brgTrans[$i]['total'] != "" ? $brgTrans[$i]['total'] : 0);
                    $ins4= "insert into brg_trans_d (no_bukti,kode_lokasi,periode,modul,form,nu,kode_gudang,kode_barang,no_batch,tgl_ed,satuan,dc,stok,jumlah,bonus,harga,hpp,p_disk,diskon,tot_diskon,total) values 
                        ('".$brgTrans[$i]["no_bukti"]."','".$brgTrans[$i]["kode_lokasi"]."','".$brgTrans[$i]["periode"]."','".$brgTrans[$i]["modul"]."','".$brgTrans[$i]["form"]."',".$brgTrans[$i]["nu"].",'".$brgTrans[$i]["kode_gudang"]."','".$brgTrans[$i]["kode_barang"]."','".$brgTrans[$i]["no_batch"]."','".$brgTrans[$i]["tgl_ed"]."','".$brgTrans[$i]["satuan"]."','".$brgTrans[$i]["dc"]."',".$stok.",".$jumlah.",".$bonus.",".$harga.",".$hpp_p.",".$p_disk.",".$diskon.",".$tot_diskon.",".$total.") ";
                        
        
                    array_push($exec,$ins4);
                }
            }

            $rs=executeArray($exec,$err);
            // $rs = true;
            if($err == null) {
                $response=array(
                    'status' => true,
                    'message' =>'Synchronize Data Successfully.',
                );
            }
            else {
                $response=array(
                    'status' => false,
                    'message' =>'Synchronize Data Failed.'.$err
                );
                
            }
          
        }else{
            $response['status'] = false;
            $response['message'] = "Unauthorized Access ".$res["message"]."token:".$token;
            
        }
        echo json_encode($response);

    }

 

?>
