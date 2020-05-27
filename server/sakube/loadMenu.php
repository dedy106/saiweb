<?php
    session_start();
    // $root_lib=$_SERVER["DOCUMENT_ROOT"];
    // if (substr($root_lib,-1)!="/") {
    //     $root_lib=$root_lib."/";
    // }
    // include_once($root_lib.'app/sakube/setting2.php');

    $root_lib=$_SERVER["DOCUMENT_ROOT"];
	if (substr($root_lib,-1)!="/") {
		$root_lib=$root_lib."/";
	}
	include_once($root_lib."lib/koneksi.php");
    include_once($root_lib."lib/helpers.php");
    
    
	
	$root=$_SERVER['REQUEST_SCHEME']."://".$_SERVER['SERVER_NAME'];
    $folder_bootstrap=$root."/vendor/sakube/bootstrap";
    $folder_css=$root."/vendor/sakube/assets/css";
    $folder_js=$root."/vendor/sakube/assets/js";
    
	$folder_assets=$root."/vendor/sakube";
	$folder_upload=$root."/vendor/sakube/upload";
	$folderoot_assets=$root."/assets";
	$folderroot_css=$root."/vendor/sakube/assets/css";
	$folderroot_js=$root."/assets/js";
	$folderroot_img=$root."/assets/img";
	$root_upload=$root."/upload";
	
	$root_ser=$root."/server/sakube";
    // $root_app=$root."/sakube_main";
    $root_app=$root."/sakube";
	$root_log=$root."/sakube";
	$root_print=$root."/sakube_preview";

    if(function_exists($_GET['fx'])) {
        $_GET['fx']();
    }
    
	
	function getKoneksi(){
		$root_lib=$_SERVER["DOCUMENT_ROOT"];
		if (substr($root_lib,-1)!="/") {
			$root_lib=$root_lib."/";
		}
		include_once($root_lib."lib/koneksi.php");
        include_once($root_lib."lib/helpers.php");
    }

    function loadMenu(){
        getKoneksi();
        // $root=$_SERVER['REQUEST_SCHEME']."://".$_SERVER['SERVER_NAME'];
        $folder_assets=$root."/vendor/sakube";
        $root_app=$root."/sakube";
        $post=$_POST;
        $jenis=$post['jenis'];
        $kodeklp=$post['kodeklp'];
        // $kode_menu = $_SESSION['kodeMenu'];
        $hasil='';
        
        // echo $kode_menu;
        // $kode_menu = "cms";
        $sql="select a.*,b.form from menu a left join m_form b on a.kode_form=b.kode_form where a.kode_klp = '$kodeklp' and a.jenis='$jenis' order by kode_klp, rowindex";
        // $sql="select a.*,b.form from menu a left join m_form b on a.kode_form=b.kode_form where a.kode_klp = '$kode_menu' and (isnull(a.jenis_menu,'-') = '-' OR a.jenis_menu = '') order by kode_klp, rowindex";
        $rs=execute($sql);
        // $daftar_menu = $rs->GetRowAssoc();
        
        while ($row = $rs->FetchNextObject($toupper=false))
        {
            $daftar_menu[] = (array)$row;
        }

        $pre_prt = 0;
        $parent_array = array();
        for($i=0; $i<count($daftar_menu); $i++){
            $forms = str_replace("_","/", $daftar_menu[$i]["form"]);
            $this_lv = $daftar_menu[$i]['level_menu']; // t1 lv=0
            // $this_link = "fMain.php?hal=".$forms.".php";
            $forms = explode("/",$forms);
            $this_link = "$root_app/".$forms[2];

            if(!ISSET($daftar_menu[$i-1]['level_menu'])){
                $prev_lv = 0; // t1 pv=0
            }else{
                $prev_lv = $daftar_menu[$i-1]['level_menu'];
            }

            if(!ISSET($daftar_menu[$i+1]['level_menu'])){
                $next_lv = $daftar_menu[$i]['level_menu'];
            }else{
                $next_lv = $daftar_menu[$i+1]['level_menu']; //t1 nv=1
            }

            if($daftar_menu[$i]['level_menu']=="0"){
                if($daftar_menu[$i]['icon'] != "" && $daftar_menu[$i]['icon'] != null){
                    $icon=$daftar_menu[$i]['icon'];
                }else{
                    // $icon="fa fa-home";
                    $icon="<img src='$folder_assets/assets/icons/modul.svg' height='20px' width='20px'>";
                }
                
            }else{
                if($daftar_menu[$i]['icon'] != "" && $daftar_menu[$i]['icon'] != null){
                    $icon=$daftar_menu[$i]['icon'];
                }else{
                    // $icon="fa fa-user";
                    $icon="<img src='$folder_assets/assets/icons/modul.svg' height='20px' width='20px'>";
                }
            }

            // Sintaks Menu Level 0 dan Tanpa Anak
            if($this_lv == 0 AND $next_lv == 0){
                $hasil.="
                <li>
                    <a href='#' class='a_link' data-breadcrumb='".$daftar_menu[$i]["nama"]."' data-href='$this_link'>
                    $icon
                    <span class='hide-menu'>".$daftar_menu[$i]["nama"]."</span></a>
                </li>
                ";
                // <li>
                //     <a href='#' class='a_link' data-href='$this_link'>
                //     <i class='menu-icon $icon'></i> 
                //     <span class='hide-menu'>".$daftar_menu[$i]["nama"]."</span></a>
                // </li>
            }
            // Sintaks Menu Level 0 dan beranak
            else if($this_lv == 0 AND $next_lv > 0){
                $hasil.="
                <li>
                    <a href='#javascript:void($i)' data-href='$this_link' data-breadcrumb='".$daftar_menu[$i]["nama"]."' data-toggle='collapse' aria-expanded='false' class='dropdown-toggle'>
                    $icon
                    <span class='hide-menu'>".$daftar_menu[$i]["nama"]."</span>
                    </a>
                    <ul class='collapse list-unstyled' id='javascript:void($i)'>
                        
                ";

        //         // echo " 
        //         // <li class='treeview'>
        //         //     <a href='#' data-href='$this_link' class='has-arrow waves-effect waves-dark a_link' aria-expanded='false'>
        //         //         <i class='icon-notebook'></i> <span class='hide-menu'>".$daftar_menu[$i]["nama"]."</span>
        //         //     </a>
        //         //     <ul class='collapse' id='sai_adminlte_menu_".$daftar_menu[$i]["kode_menu"]."' aria-expanded='false'>";
            }else if(($this_lv > $prev_lv OR $this_lv == $prev_lv OR $this_lv < $prev_lv) AND $this_lv < $next_lv){
                $hasil.= " 
                <li class='treeview'>
                    <a href='#javascript:void($i)' data-href='$this_link' class='waves-effect waves-dark a_link'>
                    $icon
                     <span>".$daftar_menu[$i]["nama"]."a</span>
                        <span class='pull-right-container'>
                            <i class='fa fa-angle-right pull-right'></i>
                        </span>
                    </a>
                    <ul class='collapse list-unstyled' id='javascript:void($i)'>";
                    
                    // <ul class='treeview-menu' id='sai_adminlte_menu_".$daftar_menu[$i]["kode_menu"]."'>";
            }else if(($this_lv > $prev_lv OR $this_lv == $prev_lv OR $this_lv < $prev_lv) AND $this_lv == $next_lv){
                $hasil.= " 
                <li class=''>
                    <a href='#' data-href='$this_link' class='a_link'>
                    $icon
                     <span>".$daftar_menu[$i]["nama"] ."</span>
                    </a>
                </li>";
                
            
            }else if($this_lv > $prev_lv AND $this_lv > $next_lv){
                $hasil.= " 
                <li >
                    <a href='#' data-href='$this_link' class='a_link'>
                    $icon
                     <span>".$daftar_menu[$i]["nama"]."</span>
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
                     <span>".$daftar_menu[$i]["nama"]."</span>
                    </a>
                </li>
                </ul>";
            }
        }
        // $hasil="what";
        $response['status'] = true;
        $response['hasil']=$hasil;
        echo json_encode($response);
        // echo "</ul>
        //     </li>";
                // for($i=0; $i<($this_lv - $next_lv); $i++){
                //     echo "</ul></li>";
                // }
            // }
        // }
    }
        
?>
