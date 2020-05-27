<?php
    if(function_exists($_GET['fx'])) {
        $_GET['fx']();
    }

    function searchPhone(){
        $root=$_SERVER["DOCUMENT_ROOT"];
        include_once($root."lib/koneksi.php");
        include_once($root."lib/helpers.php");

        $root_app="http://".$_SERVER['SERVER_NAME'];
        $root_ser="http://".$_SERVER['SERVER_NAME']."/server";

        $post=$_POST;
        $no_telp=$post['no_telp'];
        // $response['no_telp']=$no_telp;

        $sql="SELECT * from karyawan b LEFT JOIN hakakses a ON a.nik=b.nik WHERE b.no_hp='$no_telp' AND a.kode_klp_menu='WEBURT'";
        // $sql="SELECT b.no_hp
        //     from karyawan b LEFT JOIN hak_akses a ON b.nik=a.nik WHERE b.no_hp='$no_telp'";
            // and a.sts_warga='Pemilik'
            $rs = execute($sql);					
            
            while ($row = $rs->FetchNextObject(false)){
                $response['daftar'][] = (array)$row;
            }
            $response['status']=TRUE;
            $response['sql']=$sql;
        echo json_encode($response);
    }
    
    function login(){
        
        $root=$_SERVER["DOCUMENT_ROOT"];
        include_once($root."lib/koneksi.php");
        include_once($root."lib/helpers.php");

        $root_app="http://".$_SERVER['SERVER_NAME'];
        $root_ser="http://".$_SERVER['SERVER_NAME']."/server";

        $post=$_POST;
        $nik=$post['nik'];
        $no_telp=$post['no_telp'];
        $pass=$post['pass'];
        $token=$post['token'];
        $exec_sql = array();

        $sql="select a.kode_menu_lab as kode_klp_menu, a.nik, a.nama, a.pass, a.status_admin, a.klp_akses, a.kode_lokasi,b.nama as nmlok, c.kode_pp,d.nama as nama_pp,
			b.kode_lokkonsol,d.kode_bidang, c.foto,isnull(e.form,'-') as path_view,b.logo,c.no_hp
        from hakakses a 
        inner join lokasi b on b.kode_lokasi = a.kode_lokasi 
        left join karyawan c on a.nik=c.nik and a.kode_lokasi=c.kode_lokasi 
        left join pp d on c.kode_pp=d.kode_pp and c.kode_lokasi=d.kode_lokasi 
        left join m_form e on a.menu_mobile=e.kode_form 
        where c.no_hp='$no_telp' and a.pass='$pass' ";
        $rs=execute($sql,$error);
        
        $row = $rs->FetchNextObject(false);

        $mysql="SELECT * FROM rt_token WHERE token='$token'";
        $res=execute($mysql,$error);
        if($res->RecordCount()>0){
            $response['message'] = 'Token Exists';	
        }else{
            $mysql="INSERT INTO rt_token (token,nik,kode_lokasi) VALUES ('$token','$nik','32')";
            // $res=execute($mysql,$error);  
            array_push($exec_sql,$mysql);  
        }
       
        if($rs->RecordCount() > 0){
             try{
                // session_start();
            
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
                $db_key["modul"] = "RTRW";
               
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
                    $response['kodeMenu'] = $row->kode_klp_menu;
                    $response['namalokasi'] = $row->nmlokasi;				
                    $response['userStatus'] = $row->status_admin;
                    $response['namaUser'] = $row->nama;
                    $response['kodePP'] = $row->kode_pp;
                    $response['namaPP'] = $row->nama_pp;
                    $response['kodeLokasiKonsol']=$row->kodelokkonsol;
                    $response['kodeBidang'] = $row->kode_bidang;
                    $response['foto'] = $row->foto;
                    $response['dash'] = $row->path_view;
                    $response['logo'] = $row->logo;	
                    $response['no_telp'] = $row->no_hp;
                    $response['loginTime'] = date('d-m-Y');
                    $response['nikUser']= $row->nik."_".date('d-m-Y');				
                    $response['periode'] = $row1->periode;
                    $response['api_key']=$new_key;

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
            $response['notelp']=$nik;
        }
        echo json_encode($response); 

    }

    function login2(){
        
        $root=$_SERVER["DOCUMENT_ROOT"];
        include_once($root."lib/koneksi.php");
        include_once($root."lib/helpers.php");

        $root_app="http://".$_SERVER['SERVER_NAME'];
        $root_ser="http://".$_SERVER['SERVER_NAME']."/server";

        $post=$_POST;
        $no_telp=$post['no_telp'];

        $sql="select a.kode_menu_lab as kode_klp_menu, a.nik, a.nama, a.pass, a.status_admin, a.klp_akses, a.kode_lokasi,b.nama as nmlok, c.kode_pp,d.nama as nama_pp,
			b.kode_lokkonsol,d.kode_bidang, c.foto,isnull(e.form,'-') as path_view,b.logo,c.no_telp
        from hakakses a 
        inner join lokasi b on b.kode_lokasi = a.kode_lokasi 
        left join karyawan c on a.nik=c.nik and a.kode_lokasi=c.kode_lokasi 
        left join pp d on c.kode_pp=d.kode_pp and c.kode_lokasi=d.kode_lokasi 
        left join m_form e on a.menu_mobile=e.kode_form 
        where c.no_telp='$no_telp' ";
        $rs=execute($sql,$error);
        
        $row = $rs->FetchNextObject(false);

       
        if($rs->RecordCount() > 0){
             try{
                // session_start();
            
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
                $db_key["modul"] = "RTRW";
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
                    $response['kodeMenu'] = $row->kode_klp_menu;
                    $response['namalokasi'] = $row->nmlokasi;				
                    $response['userStatus'] = $row->status_admin;
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
                    $response['api_key']=$new_key;

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

    function view(){
        
        $root=$_SERVER["DOCUMENT_ROOT"];
        include_once($root."lib/koneksi.php");
        include_once($root."lib/helpers.php");

        $post=$_POST;
        $nik=$post['nik'];
        $pass=$post['pass'];

        $sql="select * from hakakses where nik='rt' and pass='saisai'";
        $rs=dbRowArray($sql);
        echo json_encode($rs["nik"]);
    }

    function mobileLogin(){
        
        $root=$_SERVER["DOCUMENT_ROOT"];
        include_once($root."lib/koneksi.php");
        include_once($root."lib/helpers.php");

        $root_app="http://".$_SERVER['SERVER_NAME'];
        $root_ser="http://".$_SERVER['SERVER_NAME']."/server";

        $post=$_POST;
        $nik=$post['nik'];
        $pass=$post['pass'];
        $token=$post['token'];

        $exec_sql = array();

        $sql="select a.kode_menu_lab as kode_klp_menu, a.nik, a.nama, a.pass, a.status_admin, a.klp_akses, a.kode_lokasi,b.nama as nmlok, c.kode_pp,d.nama as nama_pp,
			b.kode_lokkonsol,d.kode_bidang, c.foto,isnull(e.form,'-') as path_view,b.logo,c.no_telp
        from rt_akun a 
        inner join lokasi b on b.kode_lokasi = a.kode_lokasi 
        left join karyawan c on a.nik=c.nik and a.kode_lokasi=c.kode_lokasi 
        left join pp d on c.kode_pp=d.kode_pp and c.kode_lokasi=d.kode_lokasi 
        left join m_form e on a.menu_mobile=e.kode_form 
        where a.nik='$nik' or b.email ='$nik' and a.pass='$pass' ";
        $rs=execute($sql,$error);
        
        $row = $rs->FetchNextObject(false);

        $mysql="SELECT * FROM rt_token WHERE token='$token'";
        $res=execute($mysql,$error);
        if($res->RecordCount()>0){
            $response['message'] = 'Token Exists';	
        }else{
            $mysql="INSERT INTO rt_token (token,nik,kode_lokasi) VALUES ('$token','$nik','32')";
            // $res=execute($mysql,$error);  
            array_push($exec_sql,$mysql);  
        }
       
        if($rs->RecordCount() > 0){
             try{
                // session_start();
            
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
                $db_key["modul"] = "RTRW";
               
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
                    $response['kodeMenu'] = $row->kode_klp_menu;
                    $response['namalokasi'] = $row->nmlok;				
                    $response['userStatus'] = $row->status_admin;
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
                    $response['api_key']=$new_key;

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

?>
