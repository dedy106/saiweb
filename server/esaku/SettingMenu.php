<?php

    // class CrudNw{
    if(function_exists($_GET['fx'])) {
        $_GET['fx']();
    }

    function reverseDate2($ymd_or_dmy_date, $org_sep='-', $new_sep='-'){
        $arr = explode($org_sep, $ymd_or_dmy_date);
        return $arr[2].$new_sep.$arr[1].$new_sep.$arr[0];
    }

    //FORM KONTEN
    function getMenu(){

        $kode_lokasi=$_POST['kode_lokasi'];

        $sql="select * from lab_konten_menu where kode_lokasi='".$kode_lokasi."' order by nu";
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
                <td class='set_kd_mn'>".$daftar[$i]['kode_menu']."</td>
                <td class='set_nama'>".$daftar[$i]['nama']."</td>
                <td class='set_link'>".$daftar[$i]['link']."</td>
                <td class='set_jenis'>".$daftar[$i]['jenis']."</td>
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

    function getLink(){
        $kode_lokasi=$_POST['kode_lokasi'];
        $result = array("message" => "", "rows" => 0, "status" => "" );

        $sql1="SELECT DISTINCT kode_form as id, nama_form as nama FROM m_form 
        UNION
        SELECT CONVERT(VARCHAR, id) as id, judul as nama FROM lab_konten where kode_lokasi='$kode_lokasi' and kode_klp = 'KLP02' ";

        $rs = execute($sql1);				
        
        $result['daftar'] = array();
        while ($row = $rs->FetchNextObject(false)){
            $result['daftar'][] = (array)$row;
        }
        $result['status']=TRUE;
        $result['sql']=$sql;
        echo json_encode($result);
    
    }

    function simpanMenu(){

        $data=$_POST;
        $kode_lokasi=$data['kode_lokasi'];
        $kode_menu=$data['kode_menu'];

        $cek = execute("SELECT kode_menu from lab_konten_menu where kode_lokasi = '$kode_lokasi' and kode_menu='".$kode_menu."'");
        $edit = ($cek->RecordCount() > 0 ? TRUE : FALSE);
     
        // nu + 1 where nu > posted
        $del = execute("DELETE FROM lab_konten_menu where kode_lokasi='$kode_lokasi' and kode_menu='".$kode_menu."'");

        $upd = execute("UPDATE lab_konten_menu set nu=(nu+1) where kode_lokasi='$kode_lokasi' and nu>".$data['nu']);

        $query = execute("insert into lab_konten_menu (kode_lokasi,kode_menu,nama,jenis,link,level_menu,nu,kode_induk,kode_klp) values ('".$kode_lokasi."','".$data['kode_menu']."','".$data['nama']."','".$data['jenis']."','".$data['link']."','".$data['level_menu']."','".$data['nu']."','".$data['kode_induk']."','".$data['kode_klp']."')");
                    
        if($del AND $upd AND $query){
            $result['status'] = 1;
            $result['edit'] = $edit;
            $result['alert'] = "Data Berhasil Disimpan";
        }else{
            $result['status'] = 2;
            $result['alert'] = "Data Gagal Disimpan";
        }
        echo json_encode($result);
    }

    function delMenu(){
        $lokasi=$_GET['lok'];
        $id=$_GET['param'];
        if($_GET['param'] != null){
            $cek = execute("select kode_menu,nu from lab_konten_menu where kode_lokasi = '".$lokasi."' and kode_menu = '".$id."'");
            $detail = $cek->FetchNextObject($toupper=false);

            $cek2 = execute("select kode_menu,nu from lab_konten_menu where kode_lokasi = '".$lokasi."' and nu > '".$detail->nu."'");

            if($cek->RecordCount() > 0){
                
                $del = execute("DELETE FROM lab_konten_menu where kode_lokasi = '".$lokasi."' and kode_menu = '".$id."'");
                
                if($cek2->RecordCount() > 0){
                    $upd = execute("UPDATE lab_konten_menu set nu=(nu-1) where kode_lokasi = '".$lokasi."' and nu > ".$detail->nu);
                }else{
                    $upd=true;
                }
                
                if($del AND $upd){
                    // $this->db->CommitTrans();
                    echo"<script>alert('Data berhasil dihapus'); window.location='fMain.php?hal=app/cms/fSettingMenu.php';</script>";
                }else{
                    // $this->db->RollbackTrans();
                    echo"<script>alert('Data gagal dihapus'); window.location='fMain.php?hal=app/cms/fSettingMenu.php';</script>";

                }
            }else{
                echo"<script>alert('Data tidak ada'); window.location='fMain.php?hal=app/cms/fSettingMenu.php';</script>";
            }
        }
    }

?>
