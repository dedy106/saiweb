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
        // case 'PUT':
        //     updatePeserta();
        // break;
        case 'DELETE':
            deletePeserta();
        break;
        default:
            // Invalid Request Method
            header("HTTP/1.0 405 Method Not Allowed");
        break;
    }

    function getKoneksi(){
        $root_lib=$_SERVER["DOCUMENT_ROOT"];
        
        include_once($root_lib."lib/koneksi5.php");
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
                $sql1="select max(periode) as periode from periode where kode_lokasi='11' ";
                $p = execute($sql1);

                // $sql2 = "select server_key from api_server_key where kode_lokasi='$row->kode_lokasi' and  modul='SPPD' ";
                // $rs2=execute($sql2);
                // if($rs2->RecordCount()>0){
                //     $serverKey = $rs2->fields[0];
                    // create a token
                    $serverKey= "bccf9112d48a8aa444dd73e762cf263c";
                    
                    $payloadArray = array();
                    $payloadArray['userId'] = $userId;
                    $payloadArray['kode_lokasi'] = $row->kode_lokasi;
                    $payloadArray['periode'] = $p->fields[0];
                    if (isset($nbf)) {$payloadArray['nbf'] = $nbf;}
                    if (isset($exp)) {$payloadArray['exp'] = $exp;}
                    $token = JWT::encode($payloadArray, $serverKey);
                    // return to caller
                    $response = array('token' => $token,'status'=>true,'message'=>'Login Success');


                // }else{
                //     $response = array('status'=>false,'message'=>'Server key does not exist','sql'=>$sql2);
                // }
    
            } 
            else {
                $response = array('message' => 'Invalid user ID or password.','status'=>false);
               
            }

        }else{
            $response = array('message' => "Kode Lokasi, Modul, and NIK required",'status'=>false);
        }
        
        echo json_encode($response);

    }

    function authKey($token, $modul=null,$kode_lokasi=null){
        getKoneksi();
        $token = $token;
        // $modul = qstr($modul);
        // $kode_lokasi = qstr($kode_lokasi);
        $date = date('Y-m-d H:i:s');

        $schema = db_Connect();
        // $sql = "SELECT server_key FROM api_server_key where modul=$modul and kode_lokasi=$kode_lokasi ";
        // $auth = execute($sql);
        // if($auth->RecordCount() > 0){
           
        //     $serverKey = $auth->fields[0];
            $serverKey = "bccf9112d48a8aa444dd73e762cf263c";

            try {
                $payload = JWT::decode($token, $serverKey, array('HS256'));
                if(isset($payload->userId)  || $payload->userId != ''){

                    if (isset($payload->exp)) {
                        $returnArray['exp'] = date(DateTime::ISO8601, $payload->exp);;
                    }
                    $returnArray['status'] = true;
                    $returnArray['kode_lokasi'] = $payload->kode_lokasi;
                    $returnArray['nik_user'] = $payload->userId;
                    $returnArray['periode'] = $payload->periode;
                }
                

            }
            catch(Exception $e) {
                $returnArray = array('message' => $e->getMessage().'serverKey: '.$serverKey.'. token: '.$token,'status'=>false);
            }
            
        // }else{
        //     $returnArray = array('message' => 'serverKey does not exist','status'=>false);
        // }
        return $returnArray;
    }

    
    function generateKode($tabel, $kolom_acuan, $prefix, $str_format){
        $query = execute("select right(max($kolom_acuan), ".strlen($str_format).")+1 as id from $tabel where $kolom_acuan like '$prefix%'");
        $kode = $query->fields[0];
        $id = $prefix.str_pad($kode, strlen($str_format), $str_format, STR_PAD_LEFT);
        return $id;
    }

	function getPeriodeAktif(){
        getKoneksi();
        $data = $_GET;
        $header = getallheaders();
        $bearer = $header["Authorization"];
		list($token) = sscanf($bearer, 'Bearer %s');
		$res = authKey($token); 
        // $res = authKey($data["token"],"SPPD",$data["kode_lokasi"]);
        if($res["status"]){ 
    
            if(isset($data['kode_lokasi']) && $data['kode_lokasi'] != ""){

                $kode_lokasi=$data['kode_lokasi'];
            }else{
                $kode_lokasi=$res["kode_lokasi"];
            }
            
         

            $response = array("message" => "", "rows" => 0, "status" => "" );
           
            $sql="select max(periode) as periode from periode where kode_lokasi='$kode_lokasi' ";

            $response['daftar'] = dbResultArray($sql);
            $response['status'] = true;
            $response['rows']=count($response['daftar']);
        }else{
            $response['status'] = false;
            $response['message'] = "Unauthorized Access ".$res["message"];
            
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }
	
    function getAkun(){
        getKoneksi();
        $data = $_GET;
        $header = getallheaders();
        $bearer = $header["Authorization"];
		list($token) = sscanf($bearer, 'Bearer %s');
		$res = authKey($token); 
        // $res = authKey($data["token"],"SPPD",$data["kode_lokasi"]);
        if($res["status"]){ 
    
            if(isset($data['kode_lokasi']) && $data['kode_lokasi'] != ""){
                $kode_lokasi=$data['kode_lokasi'];
            }else{
                $kode_lokasi=$res["kode_lokasi"];
            }
            
            if (isset($data['kode_akun']) || $data['kode_akun'] != "") {
                $kode_akun = $data['kode_akun'];
                $filterkode_akun = " and a.kode_akun='$kode_akun' ";
            
            }else{
                $filterkode_akun = "";
            }
			if (isset($data['kode_pp']) || $data['kode_pp'] != "") {
                $kode_pp = $data['kode_pp'];
                $filterkode_pp = " and a.kode_pp='$kode_pp' ";
            
            }else{
                $filterkode_pp = "";
            }
			
            $response = array("message" => "", "rows" => 0, "status" => "" );
           
            $sql="select a.kode_akun,a.nama
				from masakun a
				inner join (select a.kode_akun,a.kode_lokasi
							from akun_sppd a
							where a.kode_lokasi='$kode_lokasi' $filterkode_pp
							group by a.kode_akun,a.kode_lokasi
							) b on a.kode_akun=b.kode_akun and a.kode_lokasi=b.kode_lokasi
				where a.kode_lokasi='$kode_lokasi'  $filterkode_akun order by a.kode_akun";

            $response['daftar'] = dbResultArray($sql);
            $response['status'] = true;
            $response['rows']=count($response['daftar']);
        }else{
            $response['status'] = false;
            $response['message'] = "Unauthorized Access ".$res["message"];
            
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    function getPp(){
        getKoneksi();
        $data = $_GET;
        $header = getallheaders();
        $bearer = $header["Authorization"];
		list($token) = sscanf($bearer, 'Bearer %s');
		$res = authKey($token); 
        if($res["status"]){ 
    
            if(isset($data['kode_lokasi']) && $data['kode_lokasi'] != ""){

                $kode_lokasi=$data['kode_lokasi'];
            }else{
                $kode_lokasi=$res["kode_lokasi"];
            }
            
            if (isset($data['kode_pp']) || $data['kode_pp'] != "") {
                $kode_pp = $data['kode_pp'];
                $filterkode_pp = " and a.kode_pp='$kode_pp' ";
            
            }else{
                $filterkode_pp = "";
            }

            $response = array("message" => "", "rows" => 0, "status" => "" );
           
            $sql="select a.kode_pp,a.nama,c.bank,c.cabang,c.no_rek,c.nama_rek
                from pp a
                inner join pp_rek c on a.kode_pp=c.kode_pp and a.kode_lokasi=c.kode_lokasi
				inner join (select a.kode_pp,a.kode_lokasi
							from akun_sppd a
							where a.kode_lokasi='$kode_lokasi'
							group by a.kode_pp,a.kode_lokasi
							) b on a.kode_pp=b.kode_pp and a.kode_lokasi=b.kode_lokasi
				where a.kode_lokasi='$kode_lokasi' $filterkode_pp and a.flag_aktif='1' order by a.kode_pp";

            $response['daftar'] = dbResultArray($sql);
            $response['status'] = true;
            $response['rows']=count($response['daftar']);
        }else{
            $response['status'] = false;
            $response['message'] = "Unauthorized Access ".$res["message"];
            
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    function getDrk(){
        getKoneksi();
        $data = $_GET;
        $header = getallheaders();
        $bearer = $header["Authorization"];
		list($token) = sscanf($bearer, 'Bearer %s');
		$res = authKey($token); 
        // $res = authKey($data["token"],"SPPD",$data["kode_lokasi"]);
        if($res["status"]){ 
    
            if(isset($data['kode_lokasi']) && $data['kode_lokasi'] != ""){

                $kode_lokasi=$data['kode_lokasi'];
            }else{
                $kode_lokasi=$res["kode_lokasi"];
            }
            $filter = "";
            if(isset($data['kode_pp']) && $data['kode_pp'] != ""){

                $filter.=" and a.kode_pp = '".$data['kode_pp']."'  ";
            }else{
                $filter.="";
            }

            if(isset($data['kode_akun']) && $data['kode_akun'] != ""){

                $filter.=" and a.kode_akun = '".$data['kode_akun']."'  ";
            }else{
                $filter.="";
            }

            if(isset($data['kode_drk']) && $data['kode_drk'] != ""){

                $filter.=" and a.kode_drk = '".$data['kode_drk']."'  ";
            }else{
                $filter.="";
            }

            $tahun=$data['tahun'];

            $response = array("message" => "", "rows" => 0, "status" => "" );
           
            $sql="select a.kode_drk,a.nama
				from drk a
				inner join (select a.kode_drk,a.kode_lokasi
							from akun_sppd a
							where a.kode_lokasi='$kode_lokasi' and a.tahun='$tahun' $filter
							group by a.kode_drk,a.kode_lokasi
							) b on a.kode_drk=b.kode_drk and a.kode_lokasi=b.kode_lokasi
				where a.kode_lokasi='$kode_lokasi' and a.tahun='$tahun'  order by a.kode_drk,a.nama  ";

            $response['daftar'] = dbResultArray($sql);
            $response['status'] = true;
            $response['rows']=count($response['daftar']);
            //$response['test']=$sql;
        }else{
            $response['status'] = false;
            $response['message'] = "Unauthorized Access ".$res["message"];
            
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    function cekBudget(){
        getKoneksi();
        $data = $_GET;
        // $res = authKey($data["token"],"SPPD",$data["kode_lokasi"]);
        $header = getallheaders();
        $bearer = $header["Authorization"];
		list($token) = sscanf($bearer, 'Bearer %s');
		$res = authKey($token); 
        if($res["status"]){ 
    
            if(isset($data['kode_lokasi']) && $data['kode_lokasi'] != ""){

                $kode_lokasi=$data['kode_lokasi'];
            }else{
                $kode_lokasi=$res["kode_lokasi"];
            }

            $kode_pp=$data['kode_pp'];
            $kode_akun=$data['kode_akun'];
            $kode_drk=$data['kode_drk'];
            $periode=$data['periode'];

            $response = array("message" => "", "rows" => 0, "status" => "" );
           
            if(isset($data['no_agenda']) && $data['no_agenda'] != ""){
                $sql="select dbo.fn_cekagg3('$kode_pp','$kode_lokasi','$kode_akun','$kode_drk','$periode','$no_agenda') as gar ";
            }else{
                $sql="select dbo.fn_cekagg2('$kode_pp','$kode_lokasi','$kode_akun','$kode_drk','$periode') as gar ";
            }

            
            $dft = dbResultArray($sql);

            $bug = explode(";",$dft[0]['gar']);

            $response['saldo_budget'] = $bug[0]-$bug[1];
            $response['status'] = true;
            $response['rows']=count($dft);
            // $response['test']=$sql;
        }else{
            $response['status'] = false;
            $response['message'] = "Unauthorized Access ".$res["message"];
            
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    function keepBudget(){
        getKoneksi();
        if(empty($_POST)){
            $data = json_decode(file_get_contents('php://input'), true);
        }else{
            $data = $_POST;
        }
        // $res = authKey($data["token"],"SPPD",$data["kode_lokasi"]);
        $header = getallheaders();
        $bearer = $header["Authorization"];
		list($token) = sscanf($bearer, 'Bearer %s');
		$res = authKey($token); 
        if($res["status"]){ 
    
            if(isset($data['kode_lokasi']) && $data['kode_lokasi'] != ""){

                $kode_lokasi=$data['kode_lokasi'];
            }else{
                $kode_lokasi=$res["kode_lokasi"];
            }
            
            $datam= $data["PBYR"];
            $no_agenda = generateKode("it_aju_m", "no_aju", $kode_lokasi."-".substr($datam[0]['periode'],2,2).".", "00001");
            $no_bukti = generateKode("tu_pdapp_m", "no_app", $kode_lokasi."-PDA".substr($datam[0]['periode'],2,4).".", "0001");
            
            $r = execute("select status_gar from masakun where kode_akun='".$datam[0]['kode_akun']."' and kode_lokasi='$kode_lokasi' ");
            if($r->RecordCount() > 0){
                $stsGar = $r->fields[0];
                if ($datam[0]['kode_drk'] == "-") {
                    $msg = "Transaksi tidak valid. Akun Anggaran Harus diisi DRK.";
                    $sts = false;						
                }
                else {
                    if ($datam[0]['total'] > $datam[0]['saldo_budget']) {
                        $msg = "Transaksi tidak valid. Nilai transaksi melebihi saldo.";
                        $sts =false;						
                    }elseif($datam[0]['total'] <= 0) {
                        $msg = "Transaksi tidak valid. Total tidak boleh nol atau kurang.";
                        $sts =false;
                    }else{
                        if ($res['periode'] > $datam[0]['periode']){
                            $msg ="Periode transaksi tidak valid. Periode transaksi tidak boleh kurang dari periode aktif sistem.[".$res['periode']."]";
                            $sts= false;
                        } else if ($res['periode'] < $datam[0]['periode']){
                            
                            $msg = "Periode transaksi tidak valid. Periode transaksi tidak boleh melebihi periode aktif sistem.[".$res['periode']."]";
                            $sts= false;
                        }else{
                            $exec = array();
                            //if ($stsGar == "1") {
                                $nilaiGar = $datam[0]['total'];
                                $sql1 ="insert into angg_r(no_bukti,modul,kode_lokasi,kode_akun,kode_pp,kode_drk,periode1,periode2,dc,saldo,nilai) values 
                                ('".$no_agenda."','ITKBAJUDRK','".$kode_lokasi."','".$datam[0]['kode_akun']."','".$datam[0]['kode_pp']."','".$datam[0]['kode_drk']."','".$datam[0]['periode']."','".$datam[0]['periode']."','D',".$datam[0]['saldo_budget'].",".$nilaiGar.")";
                                array_push($exec,$sql1);
                            //}	
                            
                            $sql2 = "insert into tu_pdapp_m(no_app,kode_lokasi,nik_user,tgl_input,periode,tanggal,keterangan,jenis,lama,kota,sarana,catatan,nik_buat,nik_app,no_aju) values ('".$no_bukti."','".$kode_lokasi."','".$res['nik_user']."',getdate(),'".$datam[0]['periode']."','".$datam[0]['tanggal']."','".$datam[0]['keterangan']."','".$datam[0]['jenis']."','".$datam[0]['lama']."','".$datam[0]['kota']."','".$datam[0]['sarana']."','".$datam[0]['catatan']."','".$datam[0]['nik_buat']."','".$datam[0]['nik_app']."','".$no_agenda."')";
                            array_push($exec,$sql2);
                            
                            $datad=$data["AJU"];
                            $datarek=$data["REK"][0];
                            $nu=1;
                            for ($i=0;$i < count($datad);$i++){
                                
                                // $upd = "update tu_pdaju_m set progress='1',no_app='".$no_bukti."' where kode_lokasi='".$kode_lokasi."' and no_spj='".$datad[$i]['no_spj']."'";																								
                                // array_push($exec,$upd);
                                $insAju = "insert into tu_pdaju_m (no_spj,tanggal,kode_lokasi,kode_pp,kode_akun,kode_drk,keterangan,nik_buat,nik_spj,periode,tgl_input,progress,no_app,nilai,jenis_pd,sts_bmhd,kode_proyek) values ('".$no_agenda."-".$nu."',getdate(),'".$kode_lokasi."','".$datad[$i]['pp_code']."','".$datam[0]['kode_akun']."','".$datam[0]['kode_drk']."','".$datad[$i]['nama_perjalanan']."','".$datam[0]['nik_buat']."','".$datad[$i]['nip']."','".$datam[0]['periode']."',getdate(),'1','".$no_bukti."',".$datad[$i]['total_biaya'].",'-','-','-') ";
                                
                                array_push($exec,$insAju);

                                $insAjud1 = "insert into tu_pdaju_d (no_spj,kode_lokasi,kode_param,jumlah,nilai,total) values ('".$no_agenda."-".$nu."','$kode_lokasi','91',1,".$datad[$i]['transport'].",".$datad[$i]['transport'].") ";
                                
                                array_push($exec,$insAjud1);

                                $insAjud2 = "insert into tu_pdaju_d (no_spj,kode_lokasi,kode_param,jumlah,nilai,total) values ('".$no_agenda."-".$nu."','$kode_lokasi','92',1,".$datad[$i]['harian'].",".$datad[$i]['harian'].") ";
                                
                                array_push($exec,$insAjud2);

                                $insAjud3 = "insert into tu_pdaju_d (no_spj,kode_lokasi,kode_param,jumlah,nilai,total) values ('".$no_agenda."-".$nu."','$kode_lokasi','93',1,".$datad[$i]['lain_lain'].",".$datad[$i]['lain_lain'].") ";
                                
                                array_push($exec,$insAjud3);
                                
                                $sql3 = "insert into it_aju_rek(no_aju,kode_lokasi,bank,no_rek,nama_rek,bank_trans,nilai,keterangan,pajak,berita) values ('".$no_agenda."','".$kode_lokasi."','".$datarek['bank']."','".$datarek['no_rekening']."','".$datarek['nama']."','-',".$datad[$i]['total_biaya'].",'".$datad[$i]['nip']."',0,'".$no_agenda."-".$nu."')";
                                array_push($exec,$sql3);
                                $nu++;
                            }	
                            
                            // $sql4 = "update a set a.bank=b.bank,a.no_rek=b.no_rek,a.nama_rek=b.nama_rek,a.bank_trans=b.cabang 
                            // from it_aju_rek a inner join karyawan b on a.keterangan=b.nik and a.kode_lokasi=b.kode_lokasi 
                            // where a.no_aju='".$no_agenda."' and a.kode_lokasi='".$kode_lokasi."'";
                            
                            // array_push($exec,$sql4);
                            
                            $sql5 = "insert into it_aju_m(no_aju,kode_lokasi,periode,tanggal,modul,kode_akun,kode_pp,kode_drk,keterangan,nilai,tgl_input,nik_user,no_kpa,no_app,no_ver,no_fiat,no_kas,progress,nik_panjar,no_ptg,user_input,form,sts_pajak,npajak,nik_app) values 
                            ('".$no_agenda."','".$kode_lokasi."','".$datam[0]['periode']."','".$datam[0]['tanggal']."','".$datam[0]['jenis_trans']."','".$datam[0]['kode_akun']."','".$datam[0]['kode_pp']."','".$datam[0]['kode_drk']."','".$datam[0]['keterangan']."',".$datam[0]['total'].",getdate(),'".$res['nik_user']."','-','-','-','-','-','A','-','-','".$datam[0]['nik_buat']."','SPPD','NON',0,'".$datam[0]['nik_app']."')
                            ";					
                            
                            array_push($exec,$sql5);
                            $rs = executeArray($exec,$err);
                            // $rs=true;
                            if($err == null){
                                $response['no_agenda']=$no_agenda;
                                $response['no_bukti']=$no_bukti;
                                $msg = "Input data sukses";
                                $sts = true;
                            }else{
                                $msg = "Input data gagal".$err;
                                $sts = false;
                            }
                            
                        } 
                    }
                }
            }
            
            $response['status'] = $sts;
            $response['message'] = $msg;
            // $response['exec'] = $exec;
        }else{
            $response['status'] = false;
            $response['message'] = "Unauthorized Access ".$res["message"];
            
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    function releaseBudget(){
        getKoneksi();
        $data = $_POST;
        // $res = authKey($data["token"],"SPPD",$data["kode_lokasi"]);
        $header = getallheaders();
        $bearer = $header["Authorization"];
		list($token) = sscanf($bearer, 'Bearer %s');
		$res = authKey($token); 
        if($res["status"]){ 
    
            if(isset($data['kode_lokasi']) && $data['kode_lokasi'] != ""){

                $kode_lokasi=$data['kode_lokasi'];
            }else{
                $kode_lokasi=$res["kode_lokasi"];
            }
            $exec = array();
            $sql1 = "delete from it_aju_m where kode_lokasi='$kode_lokasi' and no_aju='".$data['no_agenda']."' ";
            $sql2 = "delete from it_aju_d where kode_lokasi='$kode_lokasi' and no_aju='".$data['no_agenda']."' ";
            $sql3 = "delete from it_aju_rek where kode_lokasi='$kode_lokasi' and no_aju='".$data['no_agenda']."' ";
            $sql4 = "delete from angg_r where kode_lokasi='$kode_lokasi' and no_bukti='".$data['no_agenda']."' ";
            $sql5 = "delete from tu_pdaju_m where kode_lokasi='$kode_lokasi' and no_spj LIKE '".$data['no_agenda']."-%' ";
            $sql6 = "delete from tu_pdaju_d where kode_lokasi='$kode_lokasi' and no_spj LIKE '".$data['no_agenda']."-%' ";
            $sql7 = "delete from tu_pdapp_m where kode_lokasi='$kode_lokasi' and no_aju ='".$data['no_agenda']."' ";

            array_push($exec,$sql1);
            array_push($exec,$sql2);
            array_push($exec,$sql3);
            array_push($exec,$sql4);
            array_push($exec,$sql5);
            array_push($exec,$sql6);
            array_push($exec,$sql7);
            $rs = executeArray($exec,$err);
          
            if($err == null){
                    $response['no_agenda']=$data['no_agenda'];
                    $msg = "Release Budget Sukses";
                    $sts = true;
            }else{
                    $msg = "Release Budget Gagal".$err;
                    $sts = false;
            }

            $response['status'] = $sts;
            $response['message'] = $msg;
        }else{
            $response['status'] = false;
            $response['message'] = "Unauthorized Access ".$res["message"];
            
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }
   

    function kirimNoAgenda(){
        getKoneksi();
        $data = $_POST;
        // $res = authKey($data["token"],"SPPD",$data["kode_lokasi"]);
        $header = getallheaders();
        $bearer = $header["Authorization"];
		list($token) = sscanf($bearer, 'Bearer %s');
		$res = authKey($token); 
        if($res["status"]){ 
    
            if(isset($data['kode_lokasi']) && $data['kode_lokasi'] != ""){

                $kode_lokasi=$data['kode_lokasi'];
            }else{
                $kode_lokasi=$res["kode_lokasi"];
            }
            $exec = array();
            $sqlcek = "select a.nilai,a.kode_pp,a.keterangan,a.tanggal,a.kode_pp+' - '+b.nama as pp,a.kode_akun+' - '+c.nama as akun,a.kode_drk+' - '+isnull(d.nama,'-') as drk 
            from it_aju_m a 
                           inner join pp b on a.kode_pp=b.kode_pp and a.kode_lokasi=b.kode_lokasi 
                           inner join masakun c on a.kode_akun=c.kode_akun and a.kode_lokasi=c.kode_lokasi 
                           left join drk d on a.kode_drk=d.kode_drk and a.kode_lokasi=d.kode_lokasi 
            where a.no_aju = '".$data["no_agenda"]."' and a.nilai=".$data['nilai']." and a.kode_lokasi='".$kode_lokasi."' and a.progress='A' ";

            $cek = execute($sqlcek);
            if($cek->RecordCount() > 0){
                $no_app = generateKode("it_ajuapp_m", "no_app", $kode_lokasi."-APP".substr($res['periode'],2,2).".", "00001");

                $sql ="insert into it_ajuapp_m(no_app,no_aju,kode_lokasi,periode,tgl_input,user_input,tgl_aju,nik_terima) values 
                        ('".$no_app."','".$data["no_agenda"]."','".$kode_lokasi."','".$res['periode']."',getdate(),'".$data['user_input']."',getdate(),'".$data['nik_terima']."')";	
                array_push($exec,$sql);	
                            
                $sql2 ="update it_aju_m set progress='0', tanggal=getdate(), no_app='".$no_app."' where no_aju='".$data['no_agenda']."' and kode_lokasi='".$kode_lokasi."'";
                array_push($exec,$sql2);
                
                // $rs = executeArray($exec);
                $rs = true;
                if($rs){
                    $sts = true;
                    $msg = "No agenda sukses terkirim";
                }else{
                    $sts = false;
                    $msg = "No agenda gagal terkirim";
                }
            }else{
                $sts = false;
                $msg = "Error : No agenda tidak ditemukan";
            }

            $response['status'] = $sts;
            $response['message'] = $msg;
            $response['test'] = $exec;
        }else{
            $response['status'] = false;
            $response['message'] = "Unauthorized Access ".$res["message"];
            
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    

    function getAgendaDok(){
        getKoneksi();
        $data = $_GET;
        // $res = authKey($data["token"],"SPPD",$data["kode_lokasi"]);
        $header = getallheaders();
        $bearer = $header["Authorization"];
		list($token) = sscanf($bearer, 'Bearer %s');
		$res = authKey($token); 
        if($res["status"]){ 
    
            if(isset($data['kode_lokasi']) && $data['kode_lokasi'] != ""){

                $kode_lokasi=$data['kode_lokasi'];
            }else{
                $kode_lokasi=$res["kode_lokasi"];
            }
            $exec = array();
            $sql = "select a.no_aju,a.no_app,convert(varchar,b.tgl_input,103) as tgl_dok
				from it_aju_m a
				inner join it_ajuapp_m b on a.no_aju=b.no_aju and a.kode_lokasi=b.kode_lokasi and a.no_app=b.no_app
				where a.kode_lokasi='$kode_lokasi' and a.no_aju='".$data['no_agenda']."' ";
            $cek = dbResultArray($sql);

			$response['status'] = true;
            $response['data'] = $cek;
            //$response['test']=$sql;
        }else{
            $response['status'] = false;
            $response['message'] = "Unauthorized Access ".$res["message"];
            
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    function getAgendaBayar(){
        getKoneksi();
        $data = $_GET;
        // $res = authKey($data["token"],"SPPD",$data["kode_lokasi"]);
        $header = getallheaders();
        $bearer = $header["Authorization"];
		list($token) = sscanf($bearer, 'Bearer %s');
		$res = authKey($token); 
        if($res["status"]){ 
    
            if(isset($data['kode_lokasi']) && $data['kode_lokasi'] != ""){

                $kode_lokasi=$data['kode_lokasi'];
            }else{
                $kode_lokasi=$res["kode_lokasi"];
            }
            $exec = array();
            $sql = "select a.no_aju,a.no_spb,a.no_kas,convert(varchar,a.tanggal,103) as tgl_bayar
				from it_aju_m a
				inner join kas_m b on a.no_kas=b.no_kas and a.kode_lokasi=b.kode_lokasi
				where a.kode_lokasi='$kode_lokasi' and a.no_aju='".$data['no_agenda']."' ";

            $cek = dbResultArray($sql);

            $response['status'] = true;
            $response['data'] = $cek;
        }else{
            $response['status'] = false;
            $response['message'] = "Unauthorized Access ".$res["message"];
            
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }


    // function keepBudget2(){
    //     getKoneksi();
    //     if(empty($_POST)){
    //         $data = json_decode(file_get_contents('php://input'), true);
    //     }else{
    //         $data = $_POST;
    //     }
    //     // $res = authKey($data["token"],"SPPD",$data["kode_lokasi"]);
    //     $header = getallheaders();
    //     $bearer = $header["Authorization"];
	// 	list($token) = sscanf($bearer, 'Bearer %s');
	// 	$res = authKey($token); 
    //     if($res["status"]){ 
    
    //         if(isset($data['kode_lokasi']) && $data['kode_lokasi'] != ""){

    //             $kode_lokasi=$data['kode_lokasi'];
    //         }else{
    //             $kode_lokasi=$res["kode_lokasi"];
    //         }
            
    //         $datam= $data["PBYR"];
    //         $no_agenda = generateKode("it_aju_m", "no_aju", $kode_lokasi."-".substr($datam[0]['periode'],2,2).".", "00001");
    //         $no_bukti = generateKode("tu_pdapp_m", "no_app", $kode_lokasi."-PDA".substr($datam[0]['periode'],2,4).".", "0001");
            
    //         $r = execute("select status_gar from masakun where kode_akun='".$datam[0]['kode_akun']."' and kode_lokasi='$kode_lokasi' ");
    //         if($r->RecordCount() > 0){
    //             $stsGar = $r->fields[0];
    //             if ($datam[0]['kode_drk'] == "-") {
    //                 $msg = "Transaksi tidak valid. Akun Anggaran Harus diisi DRK.";
    //                 $sts = false;						
    //             }
    //             else {
    //                 if ($datam[0]['total'] > $datam[0]['saldo_budget']) {
    //                     $msg = "Transaksi tidak valid. Nilai transaksi melebihi saldo.";
    //                     $sts =false;						
    //                 }elseif($datam[0]['total'] <= 0) {
    //                     $msg = "Transaksi tidak valid. Total tidak boleh nol atau kurang.";
    //                     $sts =false;
    //                 }else{
    //                     if ($res['periode'] > $datam[0]['periode']){
    //                         $msg ="Periode transaksi tidak valid. Periode transaksi tidak boleh kurang dari periode aktif sistem.[".$res['periode']."]";
    //                         $sts= false;
    //                     } else if ($res['periode'] < $datam[0]['periode']){
                            
    //                         $msg = "Periode transaksi tidak valid. Periode transaksi tidak boleh melebihi periode aktif sistem.[".$res['periode']."]";
    //                         $sts= false;
    //                     }else{
    //                         $exec = array();
    //                         //if ($stsGar == "1") {
    //                             $nilaiGar = $datam[0]['total'];
    //                             $sql1 ="insert into angg_r(no_bukti,modul,kode_lokasi,kode_akun,kode_pp,kode_drk,periode1,periode2,dc,saldo,nilai) values 
    //                             ('".$no_agenda."','ITKBAJUDRK','".$kode_lokasi."','".$datam[0]['kode_akun']."','".$datam[0]['kode_pp']."','".$datam[0]['kode_drk']."','".$datam[0]['periode']."','".$datam[0]['periode']."','D',".$datam[0]['saldo_budget'].",".$nilaiGar.")";
    //                             array_push($exec,$sql1);
    //                         //}	
                            
    //                         $sql2 = "insert into tu_pdapp_m(no_app,kode_lokasi,nik_user,tgl_input,periode,tanggal,keterangan,jenis,lama,kota,sarana,catatan,nik_buat,nik_app,no_aju) values ('".$no_bukti."','".$kode_lokasi."','".$res['nik_user']."',getdate(),'".$datam[0]['periode']."','".$datam[0]['tanggal']."','".$datam[0]['keterangan']."','".$datam[0]['jenis']."','".$datam[0]['lama']."','".$datam[0]['kota']."','".$datam[0]['sarana']."','".$datam[0]['catatan']."','".$datam[0]['nik_buat']."','".$datam[0]['nik_app']."','".$no_agenda."')";
    //                         array_push($exec,$sql2);
                            
    //                         $datad=$data["AJU"];
    //                         $datarek=$data["REK"][0];
    //                         $nu=1;
    //                         for ($i=0;$i < count($datad);$i++){
                                
    //                             // $upd = "update tu_pdaju_m set progress='1',no_app='".$no_bukti."' where kode_lokasi='".$kode_lokasi."' and no_spj='".$datad[$i]['no_spj']."'";																								
    //                             // array_push($exec,$upd);
    //                             $insAju = "insert into tu_pdaju_m (no_spj,tanggal,kode_lokasi,kode_pp,kode_akun,kode_drk,keterangan,nik_buat,nik_spj,periode,tgl_input,progress,no_app,nilai,jenis_pd,sts_bmhd,kode_proyek) values ('".$no_agenda."-".$nu."','".$datad[$i]['tanggal']."','".$kode_lokasi."','".$datad[$i]['kode_pp']."','".$datad[$i]['kode_akun']."','".$datad[$i]['kode_drk']."','-','".$datad[$i]['nik_buat']."','".$datad[$i]['nik']."','".$datad[$i]['periode']."','".$datad[$i]['tanggal']."','1','".$no_bukti."',".$datad[$i]['nilai'].",'".$datad[$i]['jenis_pd']."','-','-') ";
                                
    //                             array_push($exec,$insAju);

    //                             $sql3 = "insert into it_aju_rek(no_aju,kode_lokasi,bank,no_rek,nama_rek,bank_trans,nilai,keterangan,pajak,berita) values ('".$no_agenda."','".$kode_lokasi."','".$datarek['bank']."','".$datarek['no_rekening']."','".$datarek['nama']."','-',".$datad[$i]['nilai'].",'".$datad[$i]['nik']."',0,'".$datad[$i]['no_spj']."')";
    //                             array_push($exec,$sql3);

    //                             $detAju = $datad[$i]["DETAIL"];
    //                             if(count($detAju) > 0){

    //                                 for ($j=0;$j < count($detAju);$j++){
    //                                     $insDet = "insert into tu_pdaju_d (no_spj,kode_lokasi,kode_param,jumlah,nilai,total) values ('".$no_agenda."-".$nu."','$kode_lokasi','".$detAju[$j]['kode_param']."',".$detAju[$j]['jumlah'].",".$detAju[$j]['nilai'].",".$detAju[$j]['total'].") ";
    //                                     array_push($exec,$insDet);
    //                                 }
    //                             }

                                
    //                             $nu++;
    //                         }	
                            
    //                         // $sql4 = "update a set a.bank=b.bank,a.no_rek=b.no_rek,a.nama_rek=b.nama_rek,a.bank_trans=b.cabang 
    //                         // from it_aju_rek a inner join karyawan b on a.keterangan=b.nik and a.kode_lokasi=b.kode_lokasi 
    //                         // where a.no_aju='".$no_agenda."' and a.kode_lokasi='".$kode_lokasi."'";
                            
    //                         // array_push($exec,$sql4);
                            
    //                         $sql5 = "insert into it_aju_m(no_aju,kode_lokasi,periode,tanggal,modul,kode_akun,kode_pp,kode_drk,keterangan,nilai,tgl_input,nik_user,no_kpa,no_app,no_ver,no_fiat,no_kas,progress,nik_panjar,no_ptg,user_input,form,sts_pajak,npajak,nik_app) values 
    //                         ('".$no_agenda."','".$kode_lokasi."','".$datam[0]['periode']."','".$datam[0]['tanggal']."','".$datam[0]['jenis_trans']."','".$datam[0]['kode_akun']."','".$datam[0]['kode_pp']."','".$datam[0]['kode_drk']."','".$datam[0]['keterangan']."',".$datam[0]['total'].",getdate(),'".$res['nik_user']."','-','-','-','-','-','A','-','-','".$datam[0]['nik_buat']."','SPPD','NON',0,'".$datam[0]['nik_app']."')
    //                         ";					
                            
    //                         array_push($exec,$sql5);
    //                         $rs = executeArray($exec,$err);
    //                         // $rs=true;
    //                         if($err == null){
    //                             $response['no_agenda']=$no_agenda;
    //                             $response['no_bukti']=$no_bukti;
    //                             $msg = "Input data sukses";
    //                             $sts = true;
    //                         }else{
    //                             $msg = "Input data gagal".$err;
    //                             $sts = false;
    //                         }
                            
    //                     } 
    //                 }
    //             }
    //         }
            
    //         $response['status'] = $sts;
    //         $response['message'] = $msg;
    //         // $response['exec'] = $exec;
    //     }else{
    //         $response['status'] = false;
    //         $response['message'] = "Unauthorized Access ".$res["message"];
            
    //     }
    //     header('Content-Type: application/json');
    //     echo json_encode($response);
    // }



?>
