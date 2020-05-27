<?php
    if(function_exists($_GET['fx'])) {
        $_GET['fx']();
    }

    function getKoneksi(){
        $root=realpath($_SERVER["DOCUMENT_ROOT"])."/";
        include_once($root."lib/koneksi5.php");
        include_once($root."lib/helpers.php");
        require_once($root.'lib/jwt.php');
    }
    
    function login(){
        
        getKoneksi();
        $root_app=$_SERVER['REQUEST_SCHEME']."://".$_SERVER['SERVER_NAME'];
        $root_ser=$_SERVER['REQUEST_SCHEME']."://".$_SERVER['SERVER_NAME']."/server";

        $post=$_POST;
        $nik=$post['nik'];
        $pass=$post['pass'];
        $kode_pp=$post['kode_pp'];

        $cek = dbRowArray("select kode_menu from sis_hakakses where nik = '$nik' and kode_pp='$kode_pp'");

        if($cek['kode_menu'] == "SISWAWEB" OR $cek['kode_menu'] == "SISWA_APV"){
            $sql="select a.nik, a.kode_menu, a.kode_lokasi, a.kode_pp, b.nis, b.nama, a.foto, a.status_login, b.kode_kelas, isnull(e.form,'-') as path_view,x.nama as nama_pp,b.email,b.hp_siswa as no_hp
            from sis_hakakses a 
            left join sis_siswa b on a.nik=b.nis and a.kode_pp=b.kode_pp and a.kode_lokasi=b.kode_lokasi
            left join m_form e on a.path_view=e.kode_form  
            left join pp x on a.kode_pp=x.kode_pp and a.kode_lokasi=x.kode_lokasi
            where a.nik='$nik' and a.pass='$pass' and a.kode_pp='$kode_pp' ";
        }else{
            $sql="select a.nik, a.kode_menu, a.kode_lokasi, a.kode_pp, b.no_reg as nis, b.nama, a.foto, a.status_login, 'REG' as kode_kelas, isnull(e.form,'-') as path_view,x.nama as nama_pp,b.email
            from sis_hakakses a 
            left join sis_siswareg b on a.nik=b.no_reg and a.kode_pp=b.kode_pp and a.kode_lokasi=b.kode_lokasi
            left join pp x on a.kode_pp=x.kode_pp and a.kode_lokasi=x.kode_lokasi
            left join m_form e on a.path_view=e.kode_form     
            where a.nik='$nik' and a.pass='$pass' and a.kode_pp='$kode_pp' ";
        }
        $rs=execute($sql,$error);
        
        $row = $rs->FetchNextObject(false);

       
        if($rs->RecordCount() > 0){
            try{
                session_start();
            
                $new_key = random_string('alnum', 20);
                $date = date('Y-m-d H:i:s');
                $date = new DateTime($date);
                $date->add(new DateInterval('PT1H'));
                $WorkingArray = json_decode(json_encode($date),true);
                $expired = explode(".",$WorkingArray["date"]);

                $db_key["nik"] = $nik;
                $db_key["api_key"] = $new_key;
                // $db_key["expired"] = $date;
                $db_key["expired"] = $expired[0];
                $db_key["modul"] = "SISWA";
                $exec_sql = array();
                $sqlcek = "select api_key from api_key_auth where nik ='".$db_key["nik"]."' and modul='".$db_key["modul"]."' ";

                $rscek = execute($sqlcek);
                if($rscek->RecordCount() == 0){
                    $query = "insert into api_key_auth (nik,api_key,expired,modul) values ('".$db_key["nik"]."','".$db_key["api_key"]."','".$db_key["expired"]."','".$db_key["modul"]."') ";
                    array_push($exec_sql,$query);
                }else{
                    $new_key = $rscek->fields[0];
                }

                $sqllog = "insert into api_key_log (nik,api_key,kode_lokasi,tgl_login,modul,flag_aktif) values ('".$db_key["nik"]."','".$db_key["api_key"]."','$row->kode_lokasi',getdate(),'".$db_key["modul"]."','1') ";
                array_push($exec_sql,$sqllog);

                $ip = $_SERVER['REMOTE_ADDR'];
                $agen = getenv('HTTP_USER_AGENT');
                $details = json_decode(file_get_contents("http://ipinfo.io/{$ip}"));
                $kota = $details->city;
                $loc = $details->loc;
                $region = $details->region;
                $negara = $details->country;
                    
                $pp=$row->pp;
                $lokasi=$row->kode_lokasi;
                $sql2="insert into lab_log( nik, kode_lokasi, kode_pp,tanggal, ip, agen, kota,loc,region,negara) values('$nik','$lokasi','$pp',getdate(),'$ip','$agen','$kota','$loc','$region','$negara')";
                
                array_push($exec_sql,$sql2);

                $rs2 = executeArray($exec_sql);

                if($rs2){
                    $sql1="select max(periode) as periode from periode where kode_lokasi='$row->kode_lokasi' ";
                    $rs1=execute($sql1,$error);
                    $row1 = $rs1->FetchNextObject(false);
                    
                    $response['isLogedIn'] = true;				
                    $response['userLog'] = $row->nik;
                    $response['lokasi'] = $row->kode_lokasi;
                    $response['kodeMenu'] = $row->kode_menu;
                    $response['namalokasi'] = $row->nmlokasi;				
                    $response['userStatus'] = $row->status_login;
                    $response['namaUser'] = $row->nama;
                    $response['kodePP'] = $row->kode_pp;
                    $response['namaPP'] = $row->nama_pp;
                    $response['kodeLokasiKonsol']=$row->kodelokkonsol;
                    $response['kodeBidang'] = $row->kode_bidang;
                    $response['foto'] = $row->foto;
                    $response['dash'] = $row->path_view;
                    $response['logo'] = $row->logo;	
                    $response['loginTime'] = date('d-m-Y');
                    $response['nikUser']= $row->nik."_".date('d-m-Y');				
                    $response['periode'] = $row1->periode;						
                    $response['userPwd']=$post['pass'];
                    $response['exit_url']=$root_ser."/siswa/cLogin.php?fx=logout";
                    $response['form_login']="fLogin.php";
                    $response['hakakses']="hakakses";
                    $response['api_key']=$new_key;
                    $response['email'] = $row->email;
                    $response['no_hp'] = $row->no_hp;

                    $serverKey= "bccf9112d48a8aa444dd73e762cf263c";
                    $payloadArray = array();
                    $payloadArray['userId'] = $nik;
                    if (isset($nbf)) {$payloadArray['nbf'] = $nbf;}
                    if (isset($exp)) {$payloadArray['exp'] = $exp;}
                    $token = JWT::encode($payloadArray, $serverKey);
                    $response['token']=$token;
                    // return to caller
    
                    $sql3="select kode_fs from fs where kode_lokasi='$row->kode_lokasi' ";
                    $rs3=execute($sql3,$error);
                    $row3 = $rs3->FetchNextObject(false);
                    $response['kode_fs']=$row3->kode_fs;
                
                }else{
                    $response['isLogedIn'] = false;	
                    $response['message'] = 'Error Login 1';	
                }

            } catch (exception $e) { 
                error_log($e->getMessage());		
                return " error " .  $e->getMessage();
            } 	
            
        }else{
            $response['isLogedIn'] = false;	
            $response['message'] = 'Error Login 2';	
        }
        echo json_encode($response);
    }

    function getDaftarPP(){	
        getKoneksi();
		$nik = $_POST['nis'];
		$sql = "SELECT a.kode_pp,a.nama from sis_sekolah a left join sis_hakakses b on a.kode_pp = b.kode_pp where a.kode_lokasi = '12' and b.nik='$nik' order by a.kode_pp";
        $rs = execute($sql);
        if($rs->RecordCount() > 0){
            while ($row = $rs->FetchNextObject(false)){
                $result['pp'][] = (array)$row;
            }
        }else{
            $result['pp'] = array();
        }
        
        echo json_encode($result);
	}
?>
