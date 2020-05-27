<?php
    if(function_exists($_GET['fx'])) {
        $_GET['fx']();
    }
    
	
	function getKoneksi(){
		$root_lib=$_SERVER["DOCUMENT_ROOT"];
		if (substr($root_lib,-1)!="/") {
			$root_lib=$root_lib."/";
		}
		include_once($root_lib."lib/koneksi6.php");
        include_once($root_lib."lib/helpers.php");
    }
	
    function login(){
		getKoneksi();
        
        $root_app="http://".$_SERVER['SERVER_NAME'];
        $root_ser="http://".$_SERVER['SERVER_NAME']."/server";

        $post=$_POST;
        $nik=$post['nik'];
        $pass=$post['pass'];

        $sql="select a.kode_klp_menu, a.nik, a.nama, a.pass, a.status_admin, a.klp_akses, a.kode_lokasi,b.nama as nmlok, c.kode_pp,d.nama as nama_pp,
			b.kode_lokkonsol,d.kode_bidang, isnull(c.foto,'-') as foto,isnull(e.form,'-') as path_view,b.logo
        from hakakses a 
        inner join lokasi b on b.kode_lokasi = a.kode_lokasi 
        left join karyawan c on a.nik=c.nik and a.kode_lokasi=c.kode_lokasi 
        left join pp d on c.kode_pp=d.kode_pp and c.kode_lokasi=d.kode_lokasi 
        left join m_form e on a.menu_mobile=e.kode_form  
        where a.nik= '$nik' and a.pass='$pass' ";
		
        $rs=execute($sql,$error);
		$jum=$rs->RecordCount();
		if($jum > 0){
            try{
				$row = $rs->FetchNextObject(false);
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
                $db_key["modul"] = "tarbak";
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
                    
                    $_SESSION['isLogedIn'] = true;	
                    // setcookie('isLogedIn', true, time() + (86400 * 0.5), "/");			
                    $_SESSION['userLog'] = $row->nik;
                    // setcookie('userLog', $row->nik, time() + (86400 * 0.5), "/");	
                    $_SESSION['lokasi'] = $row->kode_lokasi;
                    // setcookie('lokasi', $row->kode_lokasi, time() + (86400 * 0.5), "/");	
                    $_SESSION['kodeMenu'] = $row->kode_klp_menu;
                    // setcookie('kodeMenu', $row->kode_klp_menu, time() + (86400 * 0.5), "/");	
                    $_SESSION['namalokasi'] = $row->nmlok;	
                    // setcookie('namalokasi', $row->nmlok, time() + (86400 * 0.5), "/");				
                    $_SESSION['userStatus'] = $row->status_admin;
                    // setcookie('userStatus', $row->status_admin, time() + (86400 * 0.5), "/");	
                    $_SESSION['namaUser'] = $row->nama;
                    // setcookie('namaUser', $row->nama, time() + (86400 * 0.5), "/");	
                    $_SESSION['kodePP'] = $row->kode_pp;
                    // setcookie('kodePP', $row->kode_pp, time() + (86400 * 0.5), "/");	
                    $_SESSION['namaPP'] = $row->nama_pp;
                    // setcookie('namaPP', $row->nama_pp, time() + (86400 * 0.5), "/");	
                    $_SESSION['kodeLokasiKonsol']=$row->kodelokkonsol;
                    // setcookie('kodeLokasiKonsol', $row->kodelokkonsol, time() + (86400 * 0.5), "/");	
                    $_SESSION['kodeBidang'] = $row->kode_bidang;
                    // setcookie('kodeBidang', $row->kode_bidang, time() + (86400 * 0.5), "/");	
                    $_SESSION['foto'] = $row->foto;
                    // setcookie('foto', $row->foto, time() + (86400 * 0.5), "/");	
                    $_SESSION['dash'] = $row->path_view;
                    // setcookie('dash', $row->path_view, time() + (86400 * 0.5), "/");	
                    $_SESSION['logo'] = $row->logo;	
                    // setcookie('logo', $row->logo, time() + (86400 * 0.5), "/");	
                    $_SESSION['loginTime'] = date('d-m-Y');
                    // setcookie('loginTime', date('d-m-Y'), time() + (86400 * 0.5), "/");	
                    $_SESSION['nikUser']= $row->nik."_".date('d-m-Y');	
                    // setcookie('nikUser', $row->nik."_".date('d-m-Y'), time() + (86400 * 0.5), "/");				
                    $_SESSION['periode'] = $row1->periode;
                    // setcookie('periode', $row1->periode, time() + (86400 * 0.5), "/");							
                    $_SESSION['userPwd']=$post['pass'];
                    // setcookie('userPwd', $post['pass'], time() + (86400 * 0.5), "/");	
                    $_SESSION['exit_url']=$root_ser."/tarbak/cLogin.php?fx=logout";
                    // setcookie('exit_url', $root_ser."/tarbak/cLogin.php?fx=logout", time() + (86400 * 0.5), "/");	
                    $_SESSION['form_login']="fLogin.php";
                    // setcookie('form_login', "fLogin.php", time() + (86400 * 0.5), "/");	
                    $_SESSION['hakakses']="hakakses";
                    // setcookie('hakakses', "hakakses", time() + (86400 * 0.5), "/");	
                    $_SESSION['api_key']=$new_key;
                    // setcookie('api_key', $new_key, time() + (86400 * 0.5), "/");	

                    $cookie_name = "user";
                    $cookie_value = $row->nik;
                    // setcookie($cookie_name, $cookie_value, time() + (86400 * 0.5), "/"); //12 jam
					
					
                    $sql3="select kode_fs from fs where kode_lokasi='$row->kode_lokasi' ";
                    $rs3=execute($sql3,$error);
                    $row3 = $rs3->FetchNextObject(false);
                    $_SESSION['kode_fs']=$row3->kode_fs;
                    // setcookie('kode_fs', $row3->kode_fs, time() + (86400 * 0.5), "/");	
					
					
                    if($row->path_view != null || $row->path_view != "-" ){
                        $dash=str_replace("_","/", $row->path_view);
                        $dash= explode("/",$dash);
                        $dash=$dash[2];
                    }else{
                        $dash="";
                        // header("Location: $root_app/tarbak/dashboard.php".$dash, true, 301);
                        
                    }
                   
                    //echo "$root_app/tarbak/dashboard.php".$dash;
                    header("Location: $root_app/tarbak_main/".$dash, true, 301);
                    // echo $root2."/fMain.php?hal=".$dash;
                    exit();
                }else{
                    echo "<script>alert('Error Login : ".$rs2."'); window.location='$root_app/tarbak';</script>";   
                    
                    $db_key["api_key"] = random_string('alnum', 20);
                }

            } catch (exception $e) { 
                error_log($e->getMessage());		
                return " error " .  $e->getMessage();
            } 	
            
        }else{
            echo "<script>alert('Username, password salah !'); window.location='$root_app/tarbak';</script>";
        }
		
		
    }

    function logout(){

        $root_app="http://".$_SERVER['SERVER_NAME'];
        $header="Location: $root_app/tarbak";
                
        session_start();
        $_SESSION = [];
        unset($_SESSION);
        session_unset();
        session_destroy();
        // setcookie("user", "", time() - (86400 * 0.5), "/"); //12 jam
        // setcookie("isLogedIn", true, time() -(86400 * 0.5), "/");
        
        header($header, true, 301);
        exit();   
    }

?>
