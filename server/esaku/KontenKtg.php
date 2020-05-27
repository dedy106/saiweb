<?php

    // class CrudNw{
    if(function_exists($_GET['fx'])) {
        $_GET['fx']();
    }

    function reverseDate2($ymd_or_dmy_date, $org_sep='-', $new_sep='-'){
        $arr = explode($org_sep, $ymd_or_dmy_date);
        return $arr[2].$new_sep.$arr[1].$new_sep.$arr[0];
    }

    function getKoneksi(){
        $root_lib=$_SERVER["DOCUMENT_ROOT"];
        include_once($root_lib."web/lib/koneksi.php");
        include_once($root_lib."web/lib/helpers.php");
    }

    function getKontenKtg(){
        session_start();
        getKoneksi();
        $query = '';
        $output = array();
    
        $kode_lokasi = $_REQUEST['kode_lokasi'];
        $query .= "SELECT kode_ktg, nama FROM sai_konten_ktg as status where kode_lokasi= '".$kode_lokasi."'  ";

        $column_array = array('kode_ktg','nama');
        $order_column = 'ORDER BY kode_ktg '.$_POST['order'][0]['dir'];
        $column_string = join(',', $column_array);

        $res = execute($query);
        $jml_baris = $res->RecordCount();
        if(!empty($_POST['search']['value']))
        {
            $search = $_POST['search']['value'];
            $filter_string = " and (";
    
            for($i=0; $i<count($column_array); $i++){
    
                if($i == (count($column_array) - 1)){
                    $filter_string .= $column_array[$i]." like '".$search."%' )";
                }else{
                    $filter_string .= $column_array[$i]." like '".$search."%' or ";
                }
            }
    
    
            $query.=" $filter_string ";
        }
    
        if(isset($_POST["order"]))
        {
            $query .= ' ORDER BY '.$column_array[$_POST['order'][0]['column']].' '.$_POST['order'][0]['dir'];
        }
        else
        {
            $query .= ' ORDER BY kode_ktg ';
        }
        if($_POST["length"] != -1)
        {
            $query .= ' OFFSET ' . $_POST['start'] . ' ROWS FETCH NEXT ' . $_POST['length'] . ' ROWS ONLY ';
        }
        $statement = execute($query);
        $data = array();
        $filtered_rows = $statement->RecordCount();
        while($row = $statement->FetchNextObject($toupper=false))
        {
            $sub_array = array();
            $sub_array[] = $row->kode_ktg;
            $sub_array[] = $row->nama;
            $data[] = $sub_array;
        }
        $output = array(
            "draw"				=>	intval($_POST["draw"]),
            "recordsTotal"		=> 	$filtered_rows,
            "recordsFiltered"	=>	$jml_baris,
            "data"				=>	$data,
        );
        echo json_encode($output);

        
    }
    


    function getEditKtg(){

        $id=$_POST['kode'];    
    
        $result = array("message" => "", "rows" => 0, "status" => "" );
    
        $sql="select kode_ktg, nama from sai_konten_ktg where kode_lokasi='".$_POST['kode_lokasi']."' and kode_ktg='$id' ";
        
        $rs = execute($sql);					
        
        while ($row = $rs->FetchNextObject(false)){
            $result['daftar'][] = (array)$row;
        }
        $result['status'] = TRUE;
        $result['sql'] = $sql;
        echo json_encode($result);
    
    }

    function simpanKtg(){
        session_start();
        getKoneksi();

        $data=$_POST;

        $str_format="000";
        $prefix="KTG";
        $sql2="select right(isnull(max(kode_ktg),'".$prefix."000'),".strlen($str_format).")+1 as id from sai_konten_ktg where kode_ktg like '$prefix%' and kode_lokasi='".$data['kode_lokasi']."'";
        $query = execute($sql2);

        $id = $prefix.str_pad($query->fields[0], strlen($str_format), $str_format, STR_PAD_LEFT);

        $sql1= "insert into sai_konten_ktg (kode_lokasi,kode_ktg,jenis,nama) values ('".$data['kode_lokasi']."','".$id."','Gambar','".$data['nama']."') ";

        $sql=[$sql1];
        
        $rs=executeArray($sql);

        $tmp=array();
        $kode = array();
        $sts=false;
        if ($rs)
        {	
            $tmp="sukses";
            $sts=true;
        }else{
            $tmp="gagal";
            $sts=false;
        }	

        $result["message"] =$tmp;
        $result["status"] = $sts;
        $result["sql"] = $sql;
        $result["id"] = $id;
        $result["query"] = $sql2;
        echo json_encode($result);
    }
    

    function ubahKtg(){
        session_start();
        getKoneksi();

        $sql1="update sai_konten_ktg set nama='".$_POST['nama']."' where kode_ktg = '".$_POST['kode_ktg']."' and kode_lokasi='".$_POST['kode_lokasi']."' ";
        
        $sql=[$sql1];
        $rs=executeArray($sql);

        $tmp=array();
        $kode = array();
        if ($rs)
        {	
            $tmp="sukses";
            $sts=true;
        }else{
            $tmp="gagal";
            $sts=false;
        }		
        $result["message"] =$tmp;
        $result["status"] = $sts;
        echo json_encode($result);
    }
    

    function hapusKtg(){
        session_start();
        getKoneksi();

        
        $sql1="delete from sai_konten_ktg where kode_ktg='".$_POST['kode_ktg']."' and kode_lokasi='".$_POST['kode_lokasi']."' ";
        
        $sql=[$sql1];
        $rs=executeArray($sql);

        $tmp=array();
        $kode = array();
        if ($rs)
        {	
            $tmp="sukses";
            $sts=true;
        }else{
            $tmp="gagal";
            $sts=false;
        }		
        $result["message"] =$tmp;
        $result["status"] = $sts;
        echo json_encode($result);
    }

    
?>
