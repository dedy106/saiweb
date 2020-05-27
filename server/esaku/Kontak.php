<?php

    // class CrudNw{
    if(function_exists($_GET['fx'])) {
        $_GET['fx']();
    }

    function reverseDate2($ymd_or_dmy_date, $org_sep='-', $new_sep='-'){
        $arr = explode($org_sep, $ymd_or_dmy_date);
        return $arr[2].$new_sep.$arr[1].$new_sep.$arr[0];
    }

    
    // FORM KONTAK

    function getKontak(){

        $query = '';
        $output = array();
    
        $kode_lokasi = $_REQUEST['kode_lokasi'];
        $query .= "select id, judul, CASE WHEN flag_aktif='1' THEN 'Aktif' ELSE 'Tidak Aktif' end as status from lab_konten_kontak as status where kode_lokasi='".$kode_lokasi."' ";

        $column_array = array('id','judul','flag_aktif');
        $order_column = 'ORDER BY id '.$_POST['order'][0]['dir'];
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
            $query .= ' ORDER BY id ';
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
            $sub_array[] = $row->id;
            $sub_array[] = $row->judul;
            $sub_array[] = $row->status;
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
       
    function getEditKontak(){

        $id=$_POST['kode'];    
    
        $result = array("message" => "", "rows" => 0, "status" => "" );
    
        $sql="select id, judul, keterangan as isi, latitude,longitude from lab_konten_kontak where kode_lokasi='".$_POST['kode_lokasi']."' and id='$id' ";
        
        $rs = execute($sql);					
        
        while ($row = $rs->FetchNextObject(false)){
            $result['daftar'][] = (array)$row;
        }
        $result['status'] = TRUE;
        $result['sql'] = $sql;
        echo json_encode($result);
    
    }

    function simpanKontak(){

        $data=$_POST;

        // $sql2="select isnull(max(id),0)+1 as id from lab_konten_kontak where kode_lokasi='".$data['kode_lokasi']."' ";
        // $rs1=execute($sql2);
        // if($rs1->RecordCount() > 0){
        //     $id=$rs1->fields[0];
        // }else{
        //     $id=1;
        // }

        $sql1= "insert into lab_konten_kontak (kode_lokasi,nik_user,tgl_input,flag_aktif,judul,tanggal,keterangan,latitude,longitude) values ('".$data['kode_lokasi']."','".$data['nik_user']."',getdate(),'1','".$data['judul']."',getdate(),'".$data['keterangan']."','".$data['latitude']."','".$data['longitude']."') ";
        
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
        $result["sql2"] = $sql2;
        echo json_encode($result);
    }
    

    function ubahKontak(){
        
        $sql1="update lab_konten_kontak set judul='".$_POST['judul']."',keterangan='".$_POST['keterangan']."',latitude='".$_POST['latitude']."',longitude='".$_POST['longitude']."' where id = '".$_POST['id']."' and kode_lokasi='".$_POST['kode_lokasi']."' ";
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
    

    function hapusKontak(){
        
        $sql1="delete from lab_konten_kontak where id='".$_POST['id']."' and kode_lokasi='".$_POST['kode_lokasi']."' ";
        $sql=[$sql1];
        $rs=execute($sql);

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
        $result["sql"]=$sql;
        echo json_encode($result);
    }

?>
