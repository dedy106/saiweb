<?php
    if(function_exists($_GET['fx'])) {
        $_GET['fx']();
    }

    function getKoneksi(){
        $root=realpath($_SERVER["DOCUMENT_ROOT"])."/";
        include_once($root."lib/koneksi.php");
        include_once($root."lib/helpers.php");
    }
    
    function login(){
        
        getKoneksi();
        $root_app=$_SERVER['REQUEST_SCHEME']."://".$_SERVER['SERVER_NAME'];
        $root_ser=$_SERVER['REQUEST_SCHEME']."://".$_SERVER['SERVER_NAME']."/server";

        $post=$_POST;
        $nik=$post['nik'];
        $pass=$post['pass'];

        $sql="select a.kode_klp_menu, a.nik, a.nama, a.pass, a.status_admin, a.klp_akses, a.kode_lokasi,b.nama as nmlok, c.kode_pp,d.nama as nama_pp,
			b.kode_lokkonsol,d.kode_bidang, f.foto,isnull(e.form,'-') as path_view,b.logo,f.kode_jab,f.email
        from hakakses a 
        inner join lokasi b on b.kode_lokasi = a.kode_lokasi 
        left join karyawan c on a.nik=c.nik and a.kode_lokasi=c.kode_lokasi 
        left join pp d on c.kode_pp=d.kode_pp and c.kode_lokasi=d.kode_lokasi 
        left join m_form e on a.path_view=e.kode_form 
        left join apv_karyawan f on a.nik=f.nik and a.kode_lokasi=f.kode_lokasi 
        where a.nik= '$nik' and a.pass='$pass' ";
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
                $db_key["modul"] = "apv";
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
                    $_SESSION['userLog'] = $row->nik;
                    $_SESSION['lokasi'] = $row->kode_lokasi;
                    $_SESSION['kodeMenu'] = $row->kode_klp_menu;
                    $_SESSION['namalokasi'] = $row->nmlokasi;				
                    $_SESSION['userStatus'] = $row->status_admin;
                    $_SESSION['namaUser'] = $row->nama;
                    $_SESSION['kodePP'] = $row->kode_pp;
                    $_SESSION['namaPP'] = $row->nama_pp;
                    $_SESSION['kodeLokasiKonsol']=$row->kodelokkonsol;
                    $_SESSION['kodeBidang'] = $row->kode_bidang;
                    $_SESSION['foto'] = $row->foto;
                    $_SESSION['dash'] = $row->path_view;
                    $_SESSION['logo'] = $row->logo;	
                    $_SESSION['loginTime'] = date('d-m-Y');
                    $_SESSION['nikUser']= $row->nik."_".date('d-m-Y');				
                    $_SESSION['periode'] = $row1->periode;						
                    $_SESSION['userPwd']=$post['pass'];
                    $_SESSION['exit_url']=$root_ser."/apv/cLogin.php?fx=logout";
                    $_SESSION['form_login']="fLogin.php";
                    $_SESSION['hakakses']="hakakses";
                    $_SESSION['api_key']=$new_key;
                    $_SESSION['kode_jab'] = $row->kode_jab;
                    $_SESSION['email'] = $row->email;

                    $cookie_name = "user";
                    $cookie_value = $row->nik;
                    setcookie($cookie_name, $cookie_value, time() + (86400 * 0.5), "/");
                    setcookie("LoggedIn", true, time() + (1200), "/");
    
                    $sql3="select kode_fs from fs where kode_lokasi='$row->kode_lokasi' ";
                    $rs3=execute($sql3,$error);
                    $row3 = $rs3->FetchNextObject(false);
                    $_SESSION['kode_fs']=$row3->kode_fs;
                
                    if($row->path_view != null || $row->path_view != "-" ){
                        $dash=str_replace("_","/", $row->path_view);
                        $dash= explode("/",$dash);
                        $dash=$dash[2];
                    }else{
                        $dash="";
                        // header("Location: $root_app/apv/dashboard.php".$dash, true, 301);
                        
                    }
                   
                    
                    header("Location: $root_app/apv_main/".$dash, true, 301);
                    // echo $root2."/fMain.php?hal=".$dash;
                    exit();
                }else{
                    echo "<script>alert('Error Login : ".$rs2."'); window.location='$root_app/apv';</script>";   
                    
                    $db_key["api_key"] = random_string('alnum', 20);
                }

            } catch (exception $e) { 
                error_log($e->getMessage());		
                return " error " .  $e->getMessage();
            } 	
            
        }else{
            echo "<script>alert('Username, password salah !'); window.location='$root_app/apv';</script>";
        }

    }

    function autoLogin(){
        getKoneksi();

        $root_app=$_SERVER['REQUEST_SCHEME']."://".$_SERVER['SERVER_NAME'];
        $root_ser=$_SERVER['REQUEST_SCHEME']."://".$_SERVER['SERVER_NAME']."/server";

        $post=$_GET;
        $nik=$post['nik'];

        $sql="select a.kode_klp_menu, a.nik, a.nama, a.pass, a.status_admin, a.klp_akses, a.kode_lokasi,b.nama as nmlok, c.kode_pp,d.nama as nama_pp,
        b.kode_lokkonsol,d.kode_bidang, f.foto,isnull(e.form,'-') as path_view,b.logo,f.kode_jab,f.email
        from hakakses a 
        inner join lokasi b on b.kode_lokasi = a.kode_lokasi 
        left join karyawan c on a.nik=c.nik and a.kode_lokasi=c.kode_lokasi 
        left join pp d on c.kode_pp=d.kode_pp and c.kode_lokasi=d.kode_lokasi 
        left join m_form e on a.path_view=e.kode_form 
        left join apv_karyawan f on a.nik=f.nik and a.kode_lokasi=f.kode_lokasi 
        where a.nik= '$nik' ";
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
                $db_key["modul"] = "apv";
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
                    $_SESSION['userLog'] = $row->nik;
                    $_SESSION['lokasi'] = $row->kode_lokasi;
                    $_SESSION['kodeMenu'] = $row->kode_klp_menu;
                    $_SESSION['namalokasi'] = $row->nmlokasi;				
                    $_SESSION['userStatus'] = $row->status_admin;
                    $_SESSION['namaUser'] = $row->nama;
                    $_SESSION['kodePP'] = $row->kode_pp;
                    $_SESSION['namaPP'] = $row->nama_pp;
                    $_SESSION['kodeLokasiKonsol']=$row->kodelokkonsol;
                    $_SESSION['kodeBidang'] = $row->kode_bidang;
                    $_SESSION['foto'] = $row->foto;
                    $_SESSION['dash'] = $row->path_view;
                    $_SESSION['logo'] = $row->logo;	
                    $_SESSION['loginTime'] = date('d-m-Y');
                    $_SESSION['nikUser']= $row->nik."_".date('d-m-Y');				
                    $_SESSION['periode'] = $row1->periode;						
                    $_SESSION['userPwd']=$post['pass'];
                    $_SESSION['exit_url']=$root_ser."/apv/cLogin.php?fx=logout";
                    $_SESSION['form_login']="fLogin.php";
                    $_SESSION['hakakses']="hakakses";
                    $_SESSION['api_key']=$new_key;
                    $_SESSION['kode_jab']=$row->kode_jab;
                    $_SESSION['email'] = $row->email;

                    $cookie_name = "user";
                    $cookie_value = $row->nik;
                    setcookie($cookie_name, $cookie_value, time() + (86400 * 0.5), "/"); //12 jam
                    setcookie("LoggedIn", true, time() + (1200), "/");
    
                    $sql3="select kode_fs from fs where kode_lokasi='$row->kode_lokasi' ";
                    $rs3=execute($sql3,$error);
                    $row3 = $rs3->FetchNextObject(false);
                    $_SESSION['kode_fs']=$row3->kode_fs;
                
                    if($row->path_view != null || $row->path_view != "-" ){
                        $dash=str_replace("_","/", $row->path_view);
                        $dash= explode("/",$dash);
                        $dash=$dash[2];
                    }else{
                        $dash="";
                        // header("Location: $root_app/apv/dashboard.php".$dash, true, 301);
                        
                    }
                   
                    
                    header("Location: $root_app/apv_main/".$dash, true, 301);
                    // echo $root2."/fMain.php?hal=".$dash;
                    exit();
                }else{
                    echo "<script>alert('Error Login : ".$rs2."'); window.location='$root_app/apv';</script>";   
                    
                    $db_key["api_key"] = random_string('alnum', 20);
                }

            } catch (exception $e) { 
                error_log($e->getMessage());		
                return " error " .  $e->getMessage();
            } 	
            
        }else{
            echo "<script>alert('Username, password salah !'); window.location='$root_app/apv';</script>";
        }

    }

    function logout(){

        $root_app=$_SERVER['REQUEST_SCHEME']."://".$_SERVER['SERVER_NAME'];
        $header="Location: $root_app/apv";
                
        session_start();
        $_SESSION = [];
        unset($_SESSION);
        session_unset();
        session_destroy();
        setcookie("user", "", time() - (86400 * 0.5), "/"); //12 jam
        setcookie("LoggedIn", true, time() -(1200), "/");
        
        header($header, true, 301);
        exit();   
    }

    function testKoneksi(){
        getKoneksi();

        echo "test...";
        $sql = "select*from menu where kode_klp='apvWEB' ";
        $res = dbResultArray($sql);
        echo json_encode($res);
    }

?>
