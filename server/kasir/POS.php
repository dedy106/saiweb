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
		include_once($root_lib.'app/kasir/setting.php');
    }

    function cekAuth($user){
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

    function view(){
        // menggabungkan angka yang di-separate(10.000,75) menjadi 10000.00
        echo "test";
    }

    function joinNum2($num){
        // menggabungkan angka yang di-separate(10.000,75) menjadi 10000.00
        $num = str_replace(".", "", $num);
        $num = str_replace(",", ".", $num);
        return $num;
    }

    function getBarang(){
        
        session_start();
        getKoneksi();
        if($_COOKIE['isLogedIn'] AND cekAuth($_COOKIE['userLog'])){
            $kode_lokasi = $_GET['kode_lokasi'];
            
            $sql="select kode_barang,nama,hna as harga,barcode from brg_barang where flag_aktif='1' and kode_lokasi='$kode_lokasi'";
            $rs=execute($sql);
            $response["daftar"]=array();
            while ($row = $rs->FetchNextObject(false)){
                $response['daftar'][] = (array)$row;
            }
            $response['status'] = TRUE;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }

    function getNoOpen(){
        
        session_start();
        getKoneksi();
        if($_COOKIE['isLogedIn'] AND cekAuth($_COOKIE['userLog'])){
            $kode_lokasi = $_GET['kode_lokasi'];
            $nik=$_GET['nik'];
            
            $sql="select no_open from kasir_open where nik='$nik' and kode_lokasi='$kode_lokasi' and no_close='-' ";
            $rs=execute($sql);
            if($rs->RecordCount()>0){
                $no_open=$rs->fields[0];
            }else{
                $no_open="";
            }
            $response["no_open"]=$no_open;
            $response['status'] = TRUE;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        // header('Content-Type: application/json');
        echo json_encode($response);
    }

    function simpanPnj(){
        try{
            session_start();
            getKoneksi();
            if($_COOKIE['isLogedIn'] AND cekAuth($_COOKIE['userLog'])){
    
                $kode_lokasi=$_POST['kode_lokasi'];    
                $nik=$_POST['nik_user'];
                $kode_pp=$_POST['kode_pp'];

                $str_format="00000";
                $periode=date('Y').date('m');
                $per=date('y').date('m');
                $prefix=$kode_lokasi."-PNJ".$per.".";
                $sql2="select right(isnull(max(no_jual),'00000'),".strlen($str_format).")+1 as id from brg_jualpiu_dloc where no_jual like '$prefix%' and kode_lokasi='".$_POST['kode_lokasi']."' ";
                
                $query = execute($sql2);
                
                $id = $prefix.str_pad($query->fields[0], strlen($str_format), $str_format, STR_PAD_LEFT);

                $sql="select kode_spro,flag from spro where kode_spro in ('JUALDIS','HUTPPN','JUALKAS','CUSTINV') and kode_lokasi = '".$kode_lokasi."'";

                $rs=execute($sql);
                while ($row = $rs->FetchNextObject(false)){
                    if ($row->kode_spro == "HUTPPN") $akunPPN=$row->flag;
                    if ($row->kode_spro == "JUALDIS") $akunDiskon=$row->flag;
                    if ($row->kode_spro == "JUALKAS") $akunKas=$row->flag;
                    if ($row->kode_spro == "CUSTINV") $akunPiutang=$row->flag;
                }

                $sqlp="select distinct b.akun_pdpt as kode_akun from brg_barang a inner join brg_barangklp b on a.kode_klp=b.kode_klp and a.kode_lokasi=b.kode_lokasi where a.kode_lokasi='$kode_lokasi' ";

                $rsp=execute($sqlp);
                $rowp = $rsp->FetchNextObject(false);
                $akunPDPT=$rowp->kode_akun;

                $sqlg="select top 1 a.kode_gudang from brg_gudang a where a.kode_lokasi='$kode_lokasi' ";

                $rsg=execute($sqlg);
                if($rsg->RecordCount() > 0){
                    
                    $rowg = $rsg->FetchNextObject(false);
                    $kodeGudang=$rowg->kode_gudang;
                }else{
                    $kodeGudang="-";
                }
                $exec = array();

                $sql2="insert into brg_jualpiu_dloc(no_jual,kode_lokasi,tanggal,keterangan,kode_cust,kode_curr,kurs,kode_pp,nilai,periode,nik_user,tgl_input,akun_piutang,nilai_ppn,nilai_pph,no_fp,diskon,kode_gudang,no_ba,tobyr,no_open,no_close) values 
                                ('".$id."','".$kode_lokasi."',getdate(),'Penjualan No: ".$id."','CASH','IDR',1,'".$kode_pp."',".joinNum2($_POST['total_stlh']).",'".$periode."','".$nik."',getdate(),'".$akunPiutang."',0,0,'-',".joinNum2($_POST['total_disk']).",'$kodeGudang','-',".joinNum2($_POST['total_bayar']).",'".$_POST['no_open']."','-')";		
                array_push($exec,$sql2);
                
                for($a=0; $a<count($_POST['kode_barang']);$a++){
                    $sql4[$a]="insert into brg_trans_dloc (no_bukti,kode_lokasi,periode,modul,form,nu,kode_gudang,kode_barang,no_batch,tgl_ed,satuan,dc,stok,jumlah,bonus,harga,hpp,p_disk,diskon,tot_diskon,total) values 
                        ('".$id."','".$kode_lokasi."','".$periode."','BRGJUAL','BRGJUAL',".$a.",'".$kodeGudang."','".$_POST['kode_barang'][$a]."','-',getdate(),'-','C',0,".joinNum2($_POST['qty_barang'][$a]).",0,".joinNum2($_POST['harga_barang'][$a]).",0,0,".$_POST['disc_barang'][$a].",0,".joinNum2($_POST['sub_barang'][$a]).")";
                    array_push($exec,$sql4[$a]);
                }	

                $rs=executeArray($exec,$err);

                $tmp=array();
                $kode = array();
                $sts=false;
                if ($err == null)
                {	
                    $tmp="sukses disimpan";
                    $sts=true;
                }else{
                    $tmp=$err;
                    $sts=false;
                }		
                $response["message"] =$tmp;
                $response["status"] = $sts;
                $response["no_jual"]=$id;
            }else{
                
                $response["status"] = false;
                $response["message"] = "Unauthorized Access, Login Required";
            }
            // header('Content-Type: application/json');
            echo json_encode($response);
        }catch (exception $e) { 
            error_log($e->getMessage());		
            $error ="error " .  $e->getMessage();
            $response["status"] = false;
            $response["message"] = $error;
            echo json_encode($response);
        }
    }

    function getNota(){  
        session_start();
        getKoneksi();
        if($_COOKIE['isLogedIn'] AND cekAuth($_COOKIE['userLog'])){
            
            $kode_lokasi=$_GET['kode_lokasi'];    
            $response["nik"]=$_GET['nik_user'];
            $kode_pp=$_GET['kode_pp'];
            $no_bukti=$_GET['no_bukti'];
            $response["no_jual"] = $no_bukti;

            $sql="select * from brg_jualpiu_dloc where no_jual='$no_bukti' ";
            $rs=execute($sql);
            $row=$rs->FetchNextObject($toupper=false);
            $total_trans=$row->nilai+$row->diskon;
            $total_disk=$row->diskon;
            $total_stlh=$row->nilai;
            $total_byr=$row->tobyr;
            $kembalian=$row->tobyr-$row->nilai;

            $response["total_trans"]=$total_trans;
            $response["total_disk"]=$total_disk;
            $response["total_stlh"]=$total_stlh;
            $response["total_byr"]=$total_byr;
            $response["kembalian"]=$kembalian;
            $response["tgl"] = $row->tanggal;

            $sql="select a.kode_barang,a.harga,a.jumlah,a.diskon*-1 as diskon,b.nama,b.sat_kecil,a.total from brg_trans_dloc a inner join brg_barang b on a.kode_barang=b.kode_barang and a.kode_lokasi=b.kode_lokasi where a.no_bukti='$no_bukti' and a.kode_lokasi='$kode_lokasi' ";
            $response["daftar"] = dbResultArray($sql);       
            $response["status"] = true;
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        echo json_encode($response);
    }

    function cekBonus(){
        session_start();
        getKoneksi();
        if($_COOKIE['isLogedIn'] AND cekAuth($_COOKIE['userLog'])){
            try{

                $kode_brg=$_POST['kode_barang'];
                $jumlah_brg=$_POST['jumlah'];
                $harga=$_POST['harga'];
                $kode_lokasi=$_POST['kode_lokasi'];
                $tgl=$_POST['tanggal'];
                $sql=" select ref_qty as beli,bonus_qty as bonus from brg_bonus where kode_barang='$kode_brg' and kode_lokasi='$kode_lokasi' and '$tgl' between tgl_mulai and tgl_selesai ";
                $res=dbResultArray($sql);
                $bonus=0;
                $diskon=0;
                if(count($res)>0){
                    for($i=0;$i<count($res);$i++){
                        $bonus += (int) floor(abs($jumlah_brg/$res[$i]["beli"]));
                        $jumlah_brg+=($bonus*$res[$i]["bonus"]);
                        $diskon+= $bonus*$harga;
                    }
                }
        
                $response["bonus"] = $bonus;
                $response["jumlah"] = $jumlah_brg;
                $response["diskon"] = $diskon;
                $response["status"] = true; 
            }catch (exception $e) { 
                error_log($e->getMessage());		
                $error ="error " .  $e->getMessage();
                $response['error'] = $error;
                $response["status"] = false;
            } 	
        }else{
            
            $response["status"] = false;
            $response["message"] = "Unauthorized Access, Login Required";
        }
        echo json_encode($response);
    }
    
?>
