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
    }

    function cekAuth($user){
        getKoneksi();
        $user = qstr($user);

        $schema = db_Connect();
        $auth = $schema->SelectLimit("SELECT * FROM hakakses where nik=$user ", 1);
        if($auth->RecordCount() > 0){
            return true;
        }else{
            return false;
        }
    }

    function getPnj2(){
        session_start();
        getKoneksi();
        if($_COOKIE['isLogedIn'] AND cekAuth($_COOKIE['userLog'])){         
                $data = $_POST;
          
                $query = '';
                $output = array();
                
                $kode_lokasi = $_COOKIE['lokasi'];
                $query .= "select no_jual,tanggal,keterangan,nilai,periode from brg_jualpiu_dloc
                where kode_lokasi = '$kode_lokasi' and tanggal='".$data['tanggal']."' ";
                
                $column_array = array('no_jual','tanggal','keterangan','nilai','periode');
                $order_column = 'order by no_jual '.$data['order'][0]['dir'];
                $column_string = join(',', $column_array);
                
                $res = execute2($query);
                $jml_baris = $res->RecordCount();
                if(!empty($data['search']['value']))
                {
                    $search = $data['search']['value'];
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
                
                if(isset($data["order"]))
                {
                    $query .= ' order by '.$column_array[$data['order'][0]['column']].' '.$data['order'][0]['dir'];
                }
                else
                {
                    $query .= ' order by no_jual ';
                }
                if($data["length"] != -1)
                {
                    $query .= ' offset ' . $data['start'] . ' rows fetch next ' . $data['length'] . ' rows only ';
                }
                $statement = execute2($query);
                $data = array();
                $filtered_rows = $statement->RecordCount();
                while($row = $statement->FetchNextObject($toupper=false))
                {
                    $sub_array = array();
                    $sub_array[] = $row->no_jual;
                    $sub_array[] = $row->tanggal;
                    $sub_array[] = $row->keterangan;
                    $sub_array[] = $row->nilai;
                    $sub_array[] = $row->periode;
                    $data[] = $sub_array;
                }
                $response = array(
                    "draw"				=>	intval($data["draw"]),
                    "recordsTotal"		=> 	$filtered_rows,
                    "recordsFiltered"	=>	$jml_baris,
                    "data"				=>	$data,
                );
                $response['status']=true;
        }else{
            $response['status']=false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    function getPnj(){
        session_start();
        getKoneksi();
        if($_COOKIE['isLogedIn'] AND cekAuth($_COOKIE['userLog'])){
            
            $data = $_POST;
            
            $query = '';
            $output = array();
            
            $kode_lokasi = $_COOKIE['lokasi'];
            $query .= "select no_jual,tanggal,keterangan,nilai,periode from brg_jualpiu_dloc
            where kode_lokasi = '$kode_lokasi' and tanggal='".$data['tanggal']."' ";
            // $statement = execute2($query);
            // while($row = $statement->FetchNextObject($toupper=false))
            // {
            //     $sub_array = array();
            //     $sub_array[] = $row->no_jual;
            //     $sub_array[] = $row->tanggal;
            //     $sub_array[] = $row->keterangan;
            //     $sub_array[] = $row->nilai;
            //     $sub_array[] = $row->periode;
            //     $data2[] = $sub_array;
            // }
            $response['data'] = dbResultArray2($query);
            $response['status']=true;
           
        }else{
            $response['status']=false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }
   

?>