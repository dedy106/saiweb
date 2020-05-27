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

    function getLink(){
        
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $kode_lokasi = $_GET['kode_lokasi'];
            $kode_menu = $_GET['kode_menu'];

            $sql="select a.kode_form as id, a.nama_form as nama 
            from m_form a";

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

    function getMenu(){

        getKoneksi();
        $kode_lokasi=$_POST['kode_lokasi'];
        $kode_klp=$_POST['kode_klp'];

        $sql="select *,(nu*100)+nu as nu2 from lab_konten_menu where kode_klp='$kode_klp' and kode_lokasi='$kode_lokasi' order by nu";
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
                if(!ISSET($daftar[$i-1]['level_menu'])){
                    $prev_lv = 0;
                }else{
                    $prev_lv = $daftar[$i-1]['level_menu'];
                }
                
                if($daftar[$i]['level_menu'] == 0){
                    $parent_to_prt = "";
                    $prev_prt = $i;
                    $parent_array[$daftar[$i]['level_menu']] = $i;
                }else if($daftar[$i]['level_menu'] > $prev_lv){
                    $parent_to_prt = "treegrid-parent-".($i-1);
                    $prev_prt = $i-1;
                    $parent_array[$daftar[$i]['level_menu']] = $i - 1;
                }else if($daftar[$i]['level_menu'] == $prev_lv){
                    $parent_to_prt = "treegrid-parent-".($prev_prt);
                }else if($daftar[$i]['level_menu'] < $prev_lv){
                    $parent_to_prt = "treegrid-parent-".$parent_array[$daftar[$i]['level_menu']];
                }
    
                $html .= "
                <tr class='treegrid-$i $parent_to_prt'>
                <td class='set_kd_mn'>".$daftar[$i]['kode_menu']."<input type='hidden' name='kode_menu[]' value='".$daftar[$i]['kode_menu']."'><input type='hidden' class='set_lvl' name='level_menu[]' value='".$daftar[$i]['level_menu']."'></td>
                <td class='set_nama'>".$daftar[$i]['nama']."<input type='hidden' name='nama_menu[]' value='".$daftar[$i]['nama']."'></td>
                <td class='set_link'>".$daftar[$i]['link']."<input type='hidden' name='link[]' value='".$daftar[$i]['link']."'><input type='hidden' class='set_induk' name='kode_induk[]' value='".$daftar[$i]['kode_induk']."'></td>
                <td class='set_jenis' >".$daftar[$i]['jenis']."</td>
                <td class='set_nu' style='display:none'>".$daftar[$i]['nu2']."</td>
                <td class='set_index' style='display:none'>".$daftar[$i]['nu']."</td>
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

    function getKlp(){
        
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $kode_lokasi = $_GET['kode_lokasi'];
            $kode_menu = $_GET['kode_menu'];

            $sql="select kode_klp from menu_klp ";

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
    
    function simpanMenu(){
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            
            $exec = array();
            $exec_tmp = array();
            $data=$_POST;
            $kode_lokasi=$data['kode_lokasi'];
            $kode_klp=$data['kode_klp'];

            $cek = execute("select kode_menu from lab_konten_menu where kode_klp = '$kode_klp' and kode_menu='".$data['kode_menu']."'  and kode_lokasi='$kode_lokasi' ");
            $edit = ($cek->RecordCount() > 0 ? TRUE : FALSE);

            if(!$edit){

                //delete menu tmp
                $del_tmp = "delete from lab_konten_menu_tmp where kode_klp='$kode_klp'  and kode_lokasi='$kode_lokasi' ";
                array_push($exec_tmp,$del_tmp);
    
                //insert menu tmp
                $menu_tmp = "insert into lab_konten_menu_tmp (kode_menu,link,kode_klp,nama,level_menu,nu,jenis,kode_induk,kode_lokasi)
                select kode_menu, link, kode_klp, nama, level_menu, (nu*100)+nu  as nu, jenis, kode_induk,kode_lokasi
                from lab_konten_menu 
                where kode_klp='$kode_klp'  and kode_lokasi='$kode_lokasi'
                order by nu";
                array_push($exec_tmp,$menu_tmp);
    
                //insert 1 row to menu_tmp
                $sql = "insert into lab_konten_menu_tmp (kode_menu,link,kode_klp,nama,level_menu,nu,jenis,kode_induk,kode_lokasi) values ('".$data['kode_menu']."','".$data['link']."','".$kode_klp."','".$data['nama']."','".$data['level_menu']."','".$data['nu']."','".$data['jenis']."','".$data['kode_induk']."','$kode_lokasi')";
                array_push($exec_tmp,$sql);
    
                $rs = executeArray($exec_tmp,$err2);
                $sts=false;
                if ($err2 == null)
                {	
                    try{
                        
                        //del menu
                        $del = "delete from lab_konten_menu where kode_klp='".$kode_klp."' and kode_lokasi='$kode_lokasi' ";
                        array_push($exec,$del);
            
                        //get menu dari tmp
                        $getmenu = "select kode_menu, link, kode_klp, nama, level_menu, (nu*100)+nu  as nu, jenis, kode_induk,kode_lokasi
                        from lab_konten_menu_tmp 
                        where kode_klp='$kode_klp' and kode_lokasi='$kode_lokasi' 
                        order by nu";
                        $menu = execute($getmenu);
            
                        //insert menu
                        $i=1;
                        while($row = $menu->FetchNextObject($toupper=false)){
                            $ins = "insert into lab_konten_menu (kode_menu,link,kode_klp,nama,level_menu,nu,jenis,kode_induk,kode_lokasi) values ('".$row->kode_menu."','".$row->link."','".$row->kode_klp."','".$row->nama."','".$row->level_menu."','".$i."','".$row->jenis."','".$row->kode_induk."','".$row->kode_lokasi."')";
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
                        
                        $response["getmenu"] = $getmenu;
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

                $del = "delete from lab_konten_menu where kode_klp='$kode_klp' and kode_menu='".$data['kode_menu']."' and kode_lokasi='$kode_lokasi' ";
                array_push($exec,$del);

                $sql = "insert into lab_konten_menu (kode_menu,link,kode_klp,nama,level_menu,nu,jenis,kode_induk,kode_lokasi) values ('".$data['kode_menu']."','".$data['link']."','".$data['kode_klp']."','".$data['nama']."','".$data['level_menu']."','".$data['rowindex']."','".$data['jenis']."','".$data['kode_induk']."','".$data['kode_lokasi']."')";

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

    function delMenu(){
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){

            $id=$_GET['kode_menu'];
            $kode_klp=$_GET['kode_klp'];
            $kode_lokasi=$_GET['kode_lokasi'];
            $exec = array();
            if($_GET['kode_menu'] != null){
                $cek = execute("select kode_menu,nu from lab_konten_menu where kode_menu = '".$id."' and kode_klp='$kode_klp' and kode_lokasi='$kode_lokasi' ");

                if($cek->RecordCount() > 0){
                    
                    $del = "delete from lab_konten_menu where kode_menu = '".$id."' and kode_klp='$kode_klp'  and kode_lokasi='$kode_lokasi'  ";
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
            $kode_lokasi = $data['kode_lokasi'];
            $kode_klp = $data['kode_klp'];
            $exec = array();
            if(count($data['kode_menu']) > 0){
                $del = "delete from lab_konten_menu where kode_klp='$kode_klp' and kode_lokasi='$kode_lokasi'  ";
                array_push($exec,$del);

                $nu=1;
                for($i=0;$i<count($data['kode_menu']);$i++){

                    $ins = "insert into lab_konten_menu (kode_menu,link,kode_klp,nama,level_menu,nu,jenis,kode_induk,kode_lokasi) values ('".$data['kode_menu'][$i]."','".$data['link'][$i]."','".$kode_klp."','".$data['nama_menu'][$i]."','".$data['level_menu'][$i]."','".$nu."','".$data['jenis'][$i]."','".$data['kode_induk'][$i]."','$kode_lokasi')";
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
        }else{
                
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        echo json_encode($response);
    }
