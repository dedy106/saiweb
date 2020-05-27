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

    function getLib(){
		$root_lib=$_SERVER["DOCUMENT_ROOT"];
		if (substr($root_lib,-1)!="/") {
			$root_lib=$root_lib."/";
		}
        include_once($root_lib."lib/helpers.php");
        include_once($root_lib."lib/libcurl.php");
    }

    function joinNum2($num){
        // menggabungkan angka yang di-separate(10.000,75) menjadi 10000.00
        $num = str_replace(".", "", $num);
        $num = str_replace(",", ".", $num);
        return $num;
    }

    
    function loadMenu(){
        session_start();
        getLib();
        if($_SESSION['isLogedIn']){
            $post=$_POST;
            $kodeklp=$post['kodeklp'];
            $url = "http://api.simkug.com/api/gl/menu/$kodeklp";
            
            $token = $_SESSION['token'];
            $menu = curl_view($url,$token);
            $hasil='';
            $daftar_menu = $menu->success->data;
            $response['menu'] = $daftar_menu;
            $response['count'] = count($daftar_menu);
            if(count($daftar_menu) > 0){
                
            $root_app="http://".$_SERVER['SERVER_NAME']."/app/apisaku";

                $pre_prt = 0;
                $parent_array = array();
                for($i=0; $i<count($daftar_menu); $i++){
                    $forms = str_replace("_","/", $daftar_menu[$i]->form);
                    $this_lv = $daftar_menu[$i]->level_menu; 
                    $forms = explode("/",$forms);
                    $this_link = "$root_app/".$forms[2];
        
                    if(!ISSET($daftar_menu[$i-1]->level_menu)){
                        $prev_lv = 0; // t1 pv=0
                    }else{
                        $prev_lv = $daftar_menu[$i-1]->level_menu;
                    }
        
                    if(!ISSET($daftar_menu[$i+1]->level_menu)){
                        $next_lv = $daftar_menu[$i]->level_menu;
                    }else{
                        $next_lv = $daftar_menu[$i+1]->level_menu; //t1 nv=1
                    }
        
                    if($daftar_menu[$i]->level_menu=="0"){
                        if($daftar_menu[$i]->icon != "" && $daftar_menu[$i]->icon != null){
                            $icon="<i class='menu-icon ".$daftar_menu[$i]->icon."'></i>";
                        }else{
                            $icon="<i class='menu-icon fa fa-home'></i> ";
                            // $icon="<img src='$folder_assets/assets/icons/modul.svg' height='20px' width='20px'>";
                        }
                        
                    }else{
                        if($daftar_menu[$i]->icon != "" && $daftar_menu[$i]->icon != null){
                            $icon="<i class='menu-icon ".$daftar_menu[$i]->icon."'></i>";
                        }else{
                            $icon="";
                            // $icon="<img src='$folder_assets/assets/icons/modul.svg' height='20px' width='20px'>";
                        }
                    }
        
                    // Sintaks Menu Level 0 dan Tanpa Anak
                    if($this_lv == 0 AND $next_lv == 0){
                        $hasil.="
                        <li>
                            <a href='#' class='a_link' data-href='$this_link'>
                            $icon
                            <span class='hide-menu'>".$daftar_menu[$i]->nama."</span></a>
                        </li>
                        ";
                      
                    }
                    // Sintaks Menu Level 0 dan beranak
                    else if($this_lv == 0 AND $next_lv > 0){
                        $hasil.="
                        <li class='treeview'>
                        <a href='#' data-href='$this_link' class='has-arrow waves-effect waves-dark a_link' aria-expanded='false'>
                            <i class='icon-notebook'></i> <span class='hide-menu'>".$daftar_menu[$i]->nama."</span>
                        </a>
                        <ul class='collapse' id='sai_adminlte_menu_".$daftar_menu[$i]->kode_menu."' aria-expanded='false'>
                                
                        ";
        
                        // echo " 
                        // <li class='treeview'>
                        //     <a href='#' data-href='$this_link' class='has-arrow waves-effect waves-dark a_link' aria-expanded='false'>
                        //         <i class='icon-notebook'></i> <span class='hide-menu'>".$daftar_menu[$i]->nama."</span>
                        //     </a>
                        //     <ul class='collapse' id='sai_adminlte_menu_".$daftar_menu[$i]->kode_menu."' aria-expanded='false'>";
                    }else if(($this_lv > $prev_lv OR $this_lv == $prev_lv OR $this_lv < $prev_lv) AND $this_lv < $next_lv){
                        $hasil.= " 
                        <li class='treeview'>
                            <a href='#javascript:void($i)' data-href='$this_link' class='waves-effect waves-dark a_link'>
                            $icon
                             <span>".$daftar_menu[$i]->nama."a</span>
                                <span class='pull-right-container'>
                                    <i class='fa fa-angle-right pull-right'></i>
                                </span>
                            </a>
                            <ul class='collapse list-unstyled' id='javascript:void($i)'>";
                            
                            // <ul class='treeview-menu' id='sai_adminlte_menu_".$daftar_menu[$i]->kode_menu."'>";
                    }else if(($this_lv > $prev_lv OR $this_lv == $prev_lv OR $this_lv < $prev_lv) AND $this_lv == $next_lv){
                        $hasil.= " 
                        <li class=''>
                            <a href='#' data-href='$this_link' class='a_link'>
                            $icon
                             <span>".$daftar_menu[$i]->nama ."</span>
                            </a>
                        </li>";
                        
                    
                    }else if($this_lv > $prev_lv AND $this_lv > $next_lv){
                        $hasil.= " 
                        <li >
                            <a href='#' data-href='$this_link' class='a_link'>
                            $icon
                             <span>".$daftar_menu[$i]->nama."</span>
                            </a>
                        </li>";
                //         for($i=0; $i<($this_lv - $next_lv); $i++){
                //             echo "</ul></li>";
                //         }
                    }else if(($this_lv == $prev_lv OR $this_lv < $prev_lv) AND $this_lv > $next_lv){
                        $hasil.= " 
                        <li >
                            <a href='#' data-href='$this_link' class='a_link'>
                            $icon
                             <span>".$daftar_menu[$i]->nama."</span>
                            </a>
                        </li>
                        </ul>";
                    }
                }
            }

            $response['status'] = true;
            $response['hasil']=$hasil;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
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

        $sql="select *,(rowindex*100)+rowindex as nu from menu where kode_klp='$kode_klp' order by rowindex";
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
                <td class='set_nama'>".$daftar[$i]['nama']."<input type='hidden' name='nama_menu[]' value='".$daftar[$i]['nama']."'><input type='hidden' name='jenis_menu[]' value='".$daftar[$i]['jenis_menu']."'></td>
                <td class='set_link'>".$daftar[$i]['kode_form']."<input type='hidden' name='kode_form[]' value='".$daftar[$i]['kode_form']."'><input type='hidden' name='icon[]' value='".$daftar[$i]['icon']."'></td>
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

    function getKlp(){
        
        session_start();
        getKoneksi();
        if($_SESSION['isLogedIn'] AND cekAuth($_SESSION['userLog'])){
            $kode_lokasi = $_GET['kode_lokasi'];
            $kode_menu = $_GET['kode_menu'];

            $sql="select distinct kode_klp_menu as kode_klp from hakakses where kode_lokasi='$kode_lokasi' ";

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

            $cek = execute("select kode_menu from menu where kode_klp = '$kode_klp' and kode_menu='".$data['kode_menu']."'");
            $edit = ($cek->RecordCount() > 0 ? TRUE : FALSE);

            if(!$edit){

                //delete menu tmp
                $del_tmp = "delete from menu_tmp where kode_klp='$kode_klp' ";
                array_push($exec_tmp,$del_tmp);
    
                //insert menu tmp
                $menu_tmp = "insert into menu_tmp
                select kode_menu, kode_form, kode_klp, nama, level_menu, (rowindex*100)+rowindex  as rowindex, jenis_menu, icon
                from menu 
                where kode_klp='$kode_klp'
                order by rowindex";
                array_push($exec_tmp,$menu_tmp);
    
                //insert 1 row to menu_tmp
                $sql = "insert into menu_tmp (kode_menu,kode_form,kode_klp,nama,level_menu,rowindex,jenis_menu, icon) values ('".$data['kode_menu']."','".$data['link']."','".$data['kode_klp']."','".$data['nama']."','".$data['level_menu']."','".$data['nu']."','".$data['jenis_menu']."','".$data['icon']."')";
                array_push($exec_tmp,$sql);
    
                $rs = executeArray($exec_tmp,$err2);
                $sts=false;
                if ($err2 == null)
                {	
                    try{
                        
                        //del menu
                        $del = "delete from menu where kode_klp='".$kode_klp."' ";
                        array_push($exec,$del);
            
                        //get menu dari tmp
                        $getmenu = "select kode_menu, kode_form, kode_klp, nama, level_menu, (rowindex*100)+rowindex  as rowindex, jenis_menu, icon
                        from menu_tmp 
                        where kode_klp='$kode_klp'
                        order by rowindex";
                        $menu = execute($getmenu);
            
                        //insert menu
                        $i=1;
                        while($row = $menu->FetchNextObject($toupper=false)){
                            $ins = "insert into menu (kode_menu,kode_form,kode_klp,nama,level_menu,rowindex,jenis_menu, icon) values ('".$row->kode_menu."','".$row->kode_form."','".$row->kode_klp."','".$row->nama."','".$row->level_menu."','".$i."','".$row->jenis_menu."','".$row->icon."')";
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

                $del = "delete from menu where kode_klp='$kode_klp' and kode_menu='".$data['kode_menu']."' ";
                array_push($exec,$del);

                $sql = "insert into menu (kode_menu,kode_form,kode_klp,nama,level_menu,rowindex,jenis_menu, icon) values ('".$data['kode_menu']."','".$data['link']."','".$data['kode_klp']."','".$data['nama']."','".$data['level_menu']."','".$data['rowindex']."','".$data['jenis_menu']."','".$data['icon']."')";

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
            $exec = array();
            if($_GET['kode_menu'] != null){
                $cek = execute("select kode_menu,rowindex from menu where kode_menu = '".$id."' and kode_klp='$kode_klp' ");

                if($cek->RecordCount() > 0){
                    
                    $del = "delete from menu where kode_menu = '".$id."' and kode_klp='$kode_klp' ";
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
                $del = "delete from menu where kode_klp='$kode_klp' ";
                array_push($exec,$del);

                $nu=1;
                for($i=0;$i<count($data['kode_menu']);$i++){

                    $ins = "insert into menu (kode_menu,kode_form,kode_klp,nama,level_menu,rowindex,jenis_menu, icon) values ('".$data['kode_menu'][$i]."','".$data['kode_form'][$i]."','".$kode_klp."','".$data['nama_menu'][$i]."','".$data['level_menu'][$i]."','".$nu."','".$data['jenis_menu'][$i]."','".$data['icon'][$i]."')";
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
