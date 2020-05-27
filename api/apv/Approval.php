<?php

    $request_method=$_SERVER["REQUEST_METHOD"];

    switch($request_method) {
        case 'GET':
            if(isset($_GET["fx"]) AND function_exists($_GET['fx'])){
                $_GET['fx']();
            }
        break;
        case 'POST':
            // Insert 
            if(isset($_GET["fx"]) AND function_exists($_GET['fx'])){
                $_GET['fx']();
            }
        break;
        case 'PUT':
            ubah();
        break;
        case 'DELETE':
            hapus();
        break;
        default:
            // Invalid Request Method
            header("HTTP/1.0 405 Method Not Allowed");
        break;
    }

    function getKoneksi(){
        $root_lib=$_SERVER["DOCUMENT_ROOT"];
        include_once($root_lib."lib/koneksi.php");
        include_once($root_lib."lib/helpers.php");
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


    function getPengajuan(){
        getKoneksi();
    if(authKey($_GET["api_key"], 'APV', $_GET['username'])){ 
        $query = '';
        $output = array();
        
        $kode_lokasi = $_GET['kode_lokasi'];
        $kode_jab = $_GET['kode_jab'];
        $query .= "	SELECT a.no_urut,b.no_bukti,b.no_dokumen,b.kode_pp,b.waktu,b.kegiatan,b.dasar,b.nilai,a.status
            from apv_flow a
            inner join apv_juskeb_m b on a.no_bukti=b.no_bukti and a.kode_lokasi=b.kode_lokasi
            where a.kode_lokasi='$kode_lokasi' and a.status='1' AND a.kode_jab='$kode_jab'";
        $rs = execute($query);					
        while ($row = $rs->FetchNextObject(false)){
            $response['daftar'][] = (array)$row;
        }
        $response['sql']=$query;
        $response["status"] = true;
    }else{
    
    $response["status"] = false;
    $response["message"] = "Unauthorized Access, Login Required";
    }
    // header('Content-Type: application/json');
    echo json_encode($response);
}

function simpan(){
            getKoneksi();
            $data=$_POST;
        if(authKey($data["api_key"], 'APV', $data['username'])){ 
            $kode_lokasi=$data['kode_lokasi'];
            $nik=$data['nik_user'];
            $exec = array();
            // $no_bukti = generateKode("app_pesan", "no_pesan", "PSN-", "0001");
            
            $sql1= "INSERT into apv_pesan (no_bukti,kode_lokasi,keterangan,tanggal,no_urut,status) values ('".$data['no_bukti']."','".$data['kode_lokasi']."','".$data['keterangan']."','".$data['tanggal']."',".$data['no_urut'].",'".$data['status']."') ";
            
            array_push($exec,$sql1);
            
            $upd = "UPDATE apv_flow set status ='".$data['status']."' where no_bukti='".$data['no_bukti']."' and no_urut='".$data['no_urut']."' ";
            array_push($exec,$upd);

            if($data['status'] == 2){
                $no_urut=$data['no_urut']+1;
                $upd2 = "UPDATE apv_flow set status='1' ,tgl_app='".$data['tanggal']."' where no_bukti='".$data['no_bukti']."' AND no_urut='".$no_urut."' ";
            }else{
                $no_urut=$data['no_urut']-1;
                $upd2 = "UPDATE apv_flow SET status='1' ,tgl_app='-' WHERE no_bukti='".$data['no_bukti']."' AND no_urut='".$no_urut."' ";
            }

            
            array_push($exec,$upd2);
            $rs=executeArray($exec);  
            // $rs=true;    
            if ($rs)
            {	
                $tmp="sukses";
                $sts=true;
            }else{
                $tmp="gagal";
                $sts=false;
            }	
            $response["message"] =$tmp;
            $response["status"] = $sts;
            $response["sql"] = $exec;
        }else{
    
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
            }
            echo json_encode($response);
        }

        // 
// 
function getDetail(){
    getKoneksi();
    if(authKey($_GET["api_key"], 'APV', $_GET['username'])){ 
    $id=$_GET['no_bukti'];
    $kode_lokasi=$_GET['kode_lokasi'];    
    
        $response = array("message" => "", "rows" => 0, "status" => "" );
    
        $sql="select a.no_bukti,b.no_dokumen,b.kode_pp,b.waktu,b.kegiatan,b.dasar,b.nilai,a.no_urut
        from apv_flow a
        inner join apv_juskeb_m b on a.no_bukti=b.no_bukti and a.kode_lokasi=b.kode_lokasi
        
        where a.kode_lokasi='$kode_lokasi' and a.no_bukti='$id' and a.status='1' ";
        $response['sql']=$sql;
        $rs = execute($sql);					
        
        while ($row = $rs->FetchNextObject(false)){
            $response['daftar'][] = (array)$row;
        }

        $sql="select no_bukti,barang,harga,jumlah,nilai from apv_juskeb_d where kode_lokasi='".$kode_lokasi."' and no_bukti='$id'  order by no_urut";
        
        $rs2 = execute($sql);					
        
        while ($row = $rs2->FetchNextObject(false)){
            $response['daftar2'][] = (array)$row;
        }

        $sql="select no_bukti,nama,file_dok from apv_juskeb_dok where kode_lokasi='".$kode_lokasi."' and no_bukti='$id'  order by no_urut";
        
        $rs3 = execute($sql);					
        
        while ($row = $rs3->FetchNextObject(false)){
            $response['daftar3'][] = (array)$row;
        }
        $response['status'] = TRUE;
    }else{
        $response["status"] = false;
        $response["message"] = "Unauthorized Access, Login Required";
        }
        // $response['sql']=$sql;
    // }else{
        
    //     $response["status"] = false;
    //     $response["message"] = "Unauthorized Access, Login Required";
    // }
    // header('Content-Type: application/json');
    echo json_encode($response);
}

function getDetailRiwayat(){
    getKoneksi();
    if(authKey($_GET["api_key"], 'APV', $_GET['username'])){ 
    $no_bukti=$_GET['no_bukti'];
    $kode_lokasi=$_GET['kode_lokasi'];    
    $kode_jab=$_GET['kode_jab'];
    $id=$_GET['id'];

        $response = array("message" => "", "rows" => 0, "status" => "" );
    
        $sql="SELECT c.keterangan,a.no_bukti,b.no_dokumen,b.kode_pp,b.waktu,b.kegiatan,b.dasar,b.nilai,a.no_urut
        from apv_flow a
        inner join apv_juskeb_m b on a.no_bukti=b.no_bukti and a.kode_lokasi=b.kode_lokasi
        left join apv_pesan c on a.no_bukti=c.no_bukti and a.kode_lokasi=c.kode_lokasi AND a.no_urut=c.no_urut
        where a.kode_lokasi='$kode_lokasi' and a.no_bukti='$no_bukti' and a.status IN ('2','3') AND kode_jab='$kode_jab' AND id='$id'";
        $response['sql1']=$sql;
        $rs = execute($sql);					
        
        while ($row = $rs->FetchNextObject(false)){
            $response['daftar'][] = (array)$row;
        }

        $sql="select no_bukti,barang,harga,jumlah,nilai from apv_juskeb_d where kode_lokasi='".$kode_lokasi."' and no_bukti='$no_bukti'  order by no_urut";
        
        $rs2 = execute($sql);					
        
        while ($row = $rs2->FetchNextObject(false)){
            $response['daftar2'][] = (array)$row;
        }

        $sql="select no_bukti,nama,file_dok from apv_juskeb_dok where kode_lokasi='".$kode_lokasi."' and no_bukti='$no_bukti'  order by no_urut";
        
        $rs3 = execute($sql);					
        
        while ($row = $rs3->FetchNextObject(false)){
            $response['daftar3'][] = (array)$row;
        }
        $response['status'] = TRUE;
        $response['sql']=$sql;
    }else{
        $response["status"] = false;
        $response["message"] = "Unauthorized Access, Login Required";
    }
    // header('Content-Type: application/json');
    echo json_encode($response);
}

function getRiwayat(){
    getKoneksi();
    if(authKey($_GET["api_key"], 'APV', $_GET['username'])){ 
    $query = '';
    $output = array();
    
    $kode_lokasi = $_GET['kode_lokasi'];
    $kode_jab = $_GET['kode_jab'];
    $query .= "SELECT c.kegiatan, c.dasar,c.nilai, a.no_bukti,a.status,a.no_urut,a.id,a.keterangan,a.tanggal
            from apv_pesan a
            left join apv_flow b on a.no_bukti=b.no_bukti and a.kode_lokasi=b.kode_lokasi and a.kode_lokasi=b.kode_lokasi and a.no_urut=b.no_urut
            left join apv_juskeb_m c ON a.no_bukti=c.no_bukti AND a.kode_lokasi=c.kode_lokasi 
            where a.kode_lokasi='$kode_lokasi' and b.status IN ('2','3') and b.kode_jab='$kode_jab' ";

            $rs = execute($query);					
            
            while ($row = $rs->FetchNextObject(false)){
                $response['daftar'][] = (array)$row;
            }
            $response['sql']=$query;
        
            $response["status"] = true;
        }else{
    
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
            }
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
        $pass=$post['pass'];

        $sql="select a.kode_menu_lab as kode_klp_menu, a.nik, a.nama, a.pass, a.status_admin, a.klp_akses, a.kode_lokasi,b.nama as nmlok, c.kode_pp,d.nama as nama_pp,
			b.kode_lokkonsol,d.kode_bidang, f.foto,isnull(e.form,'-') as path_view,b.logo,f.kode_jab,f.email
        from hakakses a 
        inner join lokasi b on b.kode_lokasi = a.kode_lokasi 
        left join karyawan c on a.nik=c.nik and a.kode_lokasi=c.kode_lokasi 
        left join pp d on c.kode_pp=d.kode_pp and c.kode_lokasi=d.kode_lokasi 
        left join m_form e on a.menu_mobile=e.kode_form 
        left join apv_karyawan f on a.nik=f.nik and a.kode_lokasi=f.kode_lokasi 
        where a.nik= '$nik' and a.pass='$pass' ";
        $rs=execute($sql,$error);
        
        $row = $rs->FetchNextObject(false);

       
        if($rs->RecordCount() > 0){
            try{
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
                    $response['kode_jab'] = $row->kode_jab;
                    $response['email'] = $row->email;

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