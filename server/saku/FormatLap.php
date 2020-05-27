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
		if (substr($root_lib,-1)!="/") {
			$root_lib=$root_lib."/";
		}
		include_once($root_lib."lib/koneksi.php");
        include_once($root_lib."lib/helpers.php");
        
        $root=$_SERVER['REQUEST_SCHEME']."://".$_SERVER['SERVER_NAME'];
    }

    function joinNum2($num){
        // menggabungkan angka yang di-separate(10.000,75) menjadi 10000.00
        $num = str_replace(".", "", $num);
        $num = str_replace(",", ".", $num);
        return $num;
    }

    
    function cekAuth($user,$pass){
        getKoneksi();
        $user = qstr($user);
        $pass = qstr($pass);

        $schema = db_Connect();
        $auth = $schema->SelectLimit("SELECT * FROM hakakses where nik=$user ", 1);
        if($auth->RecordCount() > 0){
            return true;
        }else{
            return false;
        }
    }

    function getLap(){

        getKoneksi();
        $kode_lokasi=$_POST['kode_lokasi'];
        $kode_fs=$_POST['kode_fs'];
        $modul=$_POST['modul'];

        $sql="select kode_neraca,kode_fs,nama,level_spasi,level_lap,tipe,sum_header,jenis_akun,kode_induk,rowindex,modul,(rowindex*100)+rowindex as nu 
        from neraca 
        where kode_fs='$kode_fs' and kode_lokasi='$kode_lokasi' and modul='$modul'
        order by rowindex";
        $rs =  execute($sql);

        if($rs->RecordCount()){
            while ($row = $rs->FetchNextObject($toupper=false)){
                $daftar[] = (array)$row;
            }
        }else{
            $daftar[]=array();
        }
        
        $html = "";
        if($daftar != null){
            
            $pre_prt = 0;
            $parent_array = array();
            // node == i
            for($i=0; $i<count($daftar); $i++){
                if(!ISSET($daftar[$i-1]['level_spasi'])){
                    $prev_lv = 0;
                }else{
                    $prev_lv = $daftar[$i-1]['level_spasi'];
                }
                
                // if($daftar[$i]['level_spasi'] == 0){
                //     $parent_to_prt = "";
                //     $prev_prt = $i;
                //     $parent_array[$daftar[$i]['level_spasi']] = $i;
                // }else if($daftar[$i]['level_spasi'] > $prev_lv){
                //     $parent_to_prt = "treegrid-parent-".($i-1);
                //     $prev_prt = $i-1;
                //     $parent_array[$daftar[$i]['level_spasi']] = $i - 1;
                // }else if($daftar[$i]['level_spasi'] == $prev_lv){
                //     $parent_to_prt = "treegrid-parent-".($prev_prt);
                // }else if($daftar[$i]['level_spasi'] < $prev_lv){
                //     $parent_to_prt = "treegrid-parent-".$parent_array[$daftar[$i]['level_spasi']];
                // }
    
                if($daftar[$i]['kode_induk'] == '00'){
                    $parent_to_prt = "";
                }else{
                    $parent_to_prt = "treegrid-parent-".$daftar[$i]['kode_induk'];
                }
                $html .= "
                <tr class='treegrid-".$daftar[$i]['kode_neraca']." $parent_to_prt'>
                <td class='set_kode'>".$daftar[$i]['kode_neraca']."<input type='hidden' name='kode_neraca[]' value='".$daftar[$i]['kode_neraca']."'><input type='hidden' class='set_lvl' name='level_spasi[]' value='".$daftar[$i]['level_spasi']."'></td>
                <td class='set_nama'>".$daftar[$i]['nama']."<input type='hidden' name='nama[]' value='".$daftar[$i]['nama']."'><input type='hidden' class='set_sumheader' name='sum_header[]' value='".$daftar[$i]['sum_header']."'></td>
                <td class='set_lvlap'>".$daftar[$i]['level_lap']."<input type='hidden' name='level_lap[]' value='".$daftar[$i]['level_lap']."'><input type='hidden' class='set_kodeinduk'  name='kode_induk[]' value='".$daftar[$i]['kode_induk']."'><input type='hidden' name='jenis_akun[]' value='".$daftar[$i]['jenis_akun']."' class='set_jenis' ></td>
                <td>".$daftar[$i]['tipe']."<input type='hidden' name='tipe[]' class='set_tipe' value='".$daftar[$i]['tipe']."'></td>
                <td class='set_nu' style='display:none'>".$daftar[$i]['nu']."</td>
                <td class='set_index' style='display:none'>".$daftar[$i]['rowindex']."</td>
                </tr>
                ";
            }

        }else{
            $html = "";
        }
    
        $result['status'] = true;
        $result['html'] = $html;
        echo json_encode($result);
    
    }

    function getVersi(){
        
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $kode_lokasi = $_GET['kode_lokasi'];
            $kode_menu = $_GET['kode_menu'];

            $sql="select kode_fs,nama from fs where kode_lokasi='$kode_lokasi' ";

            $response["daftar"]= dbResultArray($sql);
            $response['status'] = TRUE;
            $response['sql']=$sql;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }

    function getTipe(){
        
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $kode_lokasi = $_GET['kode_lokasi'];
            $kode_menu = $_GET['kode_menu'];

            $sql="select kode_tipe,nama_tipe from tipe_neraca where kode_lokasi='$kode_lokasi' ";

            $response["daftar"]= dbResultArray($sql);
            $response['status'] = TRUE;
            $response['sql']=$sql;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }
    
    function simpanLap(){
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            
            $exec = array();
            $exec_tmp = array();
            $data=$_POST;
            $kode_lokasi=$data['kode_lokasi'];
            $nik_user=$data['nik_user'];
            $kode_fs=$data['kode_fs'];
            $modul=$data['modul'];

            $cek = execute("select kode_neraca from neraca where modul = '$modul' and kode_fs='".$data['kode_fs']."' and kode_lokasi='$kode_lokasi' and kode_neraca='".$data['kode_neraca']."' ");
            $edit = ($cek->RecordCount() > 0 ? TRUE : FALSE);

            if(!$edit){

                //delete neraca tmp
                $del_tmp = "delete from neraca_tmp where modul = '$modul' and kode_fs='".$data['kode_fs']."' and kode_lokasi='$kode_lokasi' and nik_user='$nik_user' ";
                array_push($exec_tmp,$del_tmp);
    
                //insert neraca tmp
                $nrc_tmp = "insert into neraca_tmp (kode_neraca,kode_fs,nama,level_spasi,level_lap,tipe,sum_header,jenis_akun,kode_induk,rowindex,modul,nik_user,tgl_input,kode_lokasi)
                select kode_neraca,kode_fs,nama,level_spasi,level_lap,tipe,sum_header,jenis_akun,kode_induk,(rowindex*100)+rowindex as rowindex,modul,'$nik_user' as nik_user,getdate(),kode_lokasi
                from neraca 
                where modul = '$modul' and kode_fs='".$data['kode_fs']."' and kode_lokasi='$kode_lokasi'
                order by rowindex";
                array_push($exec_tmp,$nrc_tmp);
    
                //insert 1 row to nrc tmp
                $sql = "insert into neraca_tmp (kode_neraca,kode_fs,nama,level_spasi,level_lap,tipe,sum_header,jenis_akun,kode_induk,rowindex,modul,nik_user,tgl_input,kode_lokasi) values ('".$data['kode_neraca']."','".$kode_fs."','".$data['nama']."','".$data['level_spasi']."','".$data['level_lap']."','".$data['tipe']."','".$data['sum_header']."','".$data['jenis_akun']."','".$data['kode_induk']."','".$data['nu']."','".$data['modul']."','".$data['nik_user']."',getdate(),'".$kode_lokasi."')";
                array_push($exec_tmp,$sql);
    
                $rs = executeArray($exec_tmp,$err2);
                $sts=false;
                if ($err2 == null)
                {	
                    try{
                        
                        //del nrc
                        $del = "delete from neraca where modul = '$modul' and kode_fs='".$data['kode_fs']."' and kode_lokasi='$kode_lokasi' ";
                        array_push($exec,$del);
            
                        //get nrc dari tmp
                        $getnrc = "select kode_neraca,kode_fs,nama,level_spasi,level_lap,tipe,sum_header,jenis_akun,kode_induk,(rowindex*100)+rowindex as rowindex,modul,'saku' as nik_user,getdate() as tgl_input,kode_lokasi
                        from neraca_tmp 
                        where modul = '$modul' and kode_fs='$kode_fs' and kode_lokasi='$kode_lokasi'
                        order by rowindex";
                        $nrc = execute($getnrc);
            
                        //insert nrc
                        $i=1;
                        while($data = $nrc->FetchNextObject($toupper=false)){
                            $ins = "insert into neraca (kode_neraca,kode_fs,nama,level_spasi,level_lap,tipe,sum_header,jenis_akun,kode_induk,rowindex,modul,kode_lokasi) values ('".$data->kode_neraca."','".$data->kode_fs."','".$data->nama."','".$data->level_spasi."','".$data->level_lap."','".$data->tipe."','".$data->sum_header."','".$data->jenis_akun."','".$data->kode_induk."',".$i.",'".$data->modul."','$data->kode_lokasi')";
                            array_push($exec,$ins);
                            $i++;
                        }
            
                        $rs = executeArray($exec,$err);
                        $tmp=array();
                        $kode = array();
                        // $err=null;
                        if ($err == null)
                        {	
                            $tmp="sukses disimpan";
                            $sts=true;
                        }else{
                            $tmp=$err;
                            $sts=false;
                        }	
                        
                        $response["getnrc"] = $getnrc;
                        $response["exec"] = $exec;
    
                    }catch (exception $e) { 
                        error_log($e->getMessage());		
                        $error ="error " .  $e->getMessage();
                        $response["message"] = $error;
                    } 	
    
                }else{
                    $tmp=$err2;
                    $sts=false;
                }	

            }else{

                $del = "delete from neraca where modul = '$modul' and kode_fs='".$data['kode_fs']."' and kode_lokasi='$kode_lokasi' and kode_neraca='".$data['kode_neraca']."' ";
                array_push($exec,$del);

                $sql = "insert into neraca (kode_neraca,kode_fs,nama,level_spasi,level_lap,tipe,sum_header,jenis_akun,kode_induk,rowindex,modul,kode_lokasi) values ('".$data['kode_neraca']."','".$kode_fs."','".$data['nama']."','".$data['level_spasi']."','".$data['level_lap']."','".$data['tipe']."','".$data['sum_header']."','".$data['jenis_akun']."','".$data['kode_induk']."','".$data['rowindex']."','".$data['modul']."','$kode_lokasi')";

                array_push($exec,$sql);
                $rs = executeArray($exec,$err);
                $tmp=array();
                $kode = array();
                // $err=null;
                if ($err == null)
                {	
                    $tmp="sukses disimpan";
                    $sts=true;
                }else{
                    $tmp=$err;
                    $sts=false;
                }	

            }

            $response["message"] =$tmp;
            $response["status"] = $sts;
            
        }else{
                
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }

    function delLap(){
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $data = $_GET;
            $id=$data['kode_neraca'];
            $kode_lokasi=$data['kode_lokasi'];
            $nik_user=$data['nik_user'];
            $kode_fs=$data['kode_fs'];
            $modul=$data['modul'];

            $exec = array();
            if($data['kode_neraca'] != null){
                $cek = execute("select kode_neraca,rowindex from neraca where kode_neraca = '".$id."' and kode_fs='$kode_fs' and kode_lokasi='$kode_lokasi' and modul='$modul' ");

                if($cek->RecordCount() > 0){
                    
                    $del = "delete from neraca where kode_neraca = '".$id."' and kode_fs='$kode_fs' and kode_lokasi='$kode_lokasi' and modul='$modul' ";
                    array_push($exec,$del);
                    
                    $rs = executeArray($exec,$err);
                    if($err == NULL){
                        $response['status']= true;
                        $response['message']= 'Data berhasil dihapus';
                    }else{
                        $response['status'] = false;
                        $response['message']= 'Data gagal dihapus '.$err;

                    }
                }else{
                    $response['status'] = false;
                    $response['message']= 'Data tidak ada';
                }
            }
        }else{
                
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        echo json_encode($response);
    }

    function simpanMove(){
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $data = $_POST;
            $kode_lokasi=$data['kode_lokasi'];
            $nik_user=$data['nik_user'];
            $kode_fs=$data['kode_fs'];
            $modul=$data['modul'];
            $exec = array();
            if(count($data['kode_neraca']) > 0){
                $del = "delete from neraca where kode_fs='$kode_fs' and kode_lokasi='$kode_lokasi' and modul='$modul' ";
                array_push($exec,$del);

                $nu=1;
                for($i=0;$i<count($data['kode_neraca']);$i++){

                    $ins = "insert into neraca (kode_neraca,kode_fs,nama,level_spasi,level_lap,tipe,sum_header,jenis_akun,kode_induk,rowindex,modul,kode_lokasi) values ('".$data['kode_neraca'][$i]."','".$kode_fs."','".$data['nama'][$i]."','".$data['level_spasi'][$i]."','".$data['level_lap'][$i]."','".$data['tipe'][$i]."','".$data['sum_header'][$i]."','".$data['jenis_akun'][$i]."','".$data['kode_induk'][$i]."','".$nu."','".$data['modul']."','$kode_lokasi')";

                    array_push($exec,$ins);
                    $nu++;
                }
    
                $rs = executeArray($exec,$err);
                $tmp=array();
                $kode = array();
                // $err=null;
                if ($err == null)
                {	
                    $tmp="sukses disimpan";
                    $sts=true;
                }else{
                    $tmp=$err;
                    $sts=false;
                }	
            }else{
                $tmp= 'Error. Data kosong !';
                $sts=false;
            }
            
            $response["status"] = $sts;
            $response["message"] = $tmp;
            
            $response["exec"] = $exec;
        }else{
                
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        echo json_encode($response);
    }

    function getRelakun(){
        
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $kode_lokasi= $_GET['kode_lokasi'];
            $modul= $_GET['modul'];
            $kode_neraca= $_GET['kode_neraca'];

            $response["daftar"] = dbResultArray("select kode_akun,nama from masakun where kode_lokasi='$kode_lokasi' and kode_akun not in (select distinct kode_akun from relakun where kode_lokasi='$kode_lokasi') and modul='$modul'
            ") ;
            $sql= "select a.kode_akun,b.nama 
            from relakun a
            inner join masakun b on a.kode_akun=b.kode_akun and a.kode_lokasi=b.kode_lokasi 
            where a.kode_lokasi='$kode_lokasi' and a.kode_neraca='$kode_neraca' and b.modul='$modul'
            ";
            $response["daftar2"] = dbResultArray($sql);
            $response['status'] = TRUE;
            $response['sql']= $sql;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        
        // header('Content-Type: application/json');
        echo json_encode($response);
    }

    function simpanRelasi(){
        
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $data = $_POST;
            $kode_lokasi= $data['kode_lokasi'];
            $kode_neraca= $data['kode_neraca'];
            $kode_fs= $data['kode_fs'];
            $exec = array();
            if (count($data['kode_akun']) > 0){
                $del = "delete from relakun where kode_neraca ='$kode_neraca' and kode_fs='$kode_fs' and kode_lokasi='$kode_lokasi' ";
                array_push($exec,$del);
                for ($i=0;$i<count($data['kode_akun']);$i++){							
                    $ins3 = "insert into relakun (kode_neraca,kode_fs,kode_akun,kode_lokasi) values ('".$kode_neraca."','".$kode_fs."','".$data['kode_akun'][$i]."','".$kode_lokasi."')";
                    array_push($exec,$ins3); 
                }
            }
            $rs = executeArray($exec,$err);
            if($err == null){
                $sts = true;
                $msg = 'Relasi akun berhasil disimpan';
            }else{
                
                $sts = true;
                $msg = 'Relasi akun gagal disimpan. '.$err;
            }

            $response['status'] = $sts;
            $response['message'] = $msg;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }
