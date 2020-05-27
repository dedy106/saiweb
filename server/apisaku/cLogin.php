<?php
    if(function_exists($_GET['fx'])) {
        $_GET['fx']();
    }
    
    
	
    function login(){
        
        $root_lib=$_SERVER["DOCUMENT_ROOT"];
		if (substr($root_lib,-1)!="/") {
			$root_lib=$root_lib."/";
		}
        include_once($root_lib."lib/libcurl.php");

        $post=$_POST;
        $fields = array(
            "nik" => $post['nik'],
            "password" => $post['pass']
        );

        $root_ser="http://".$_SERVER['SERVER_NAME']."/server";
        $root="http://api.simkug.com/api/gl";
        $url_login = $root."/login";
        $url_profile = $root."/profile";

        $rs = curl($url_login,$fields);

        if($rs->message == "success"){
            $token = $rs->token;
            $rs2 = curl_view($url_profile,$token);
            if(count($rs2->user) > 0){
                $row = $rs2->user[0];
                $row1 = $rs2->periode[0];
                $row2 = $rs2->kode_fs;
                session_start();
                
                $_SESSION['isLogedIn'] = true;		
                $_SESSION['userLog'] = $row->nik;
                $_SESSION['lokasi'] = $row->kode_lokasi;
                $_SESSION['kodeMenu'] = $row->kode_klp_menu;
                $_SESSION['namalokasi'] = $row->nmlok;			
                $_SESSION['userStatus'] = $row->status_admin;	
                $_SESSION['namaUser'] = $row->nama;
                $_SESSION['kodePP'] = $row->kode_pp;
                $_SESSION['namaPP'] = $row->nama_pp;
                $_SESSION['kodeLokasiKonsol']=$row->kodelokkonsol;
                $_SESSION['kodeBidang'] = $row->kode_bidang;
                $_SESSION['foto'] = $row->foto;
                $_SESSION['dash'] = $row->path_view;
                $_SESSION['logo'] = $row->logo;	
                $_SESSION['loginTime'] = date('d-m-Y');
                $_SESSION['nikUser']= $row->nik."_".date('d-m-Y');
                $_SESSION['periode'] = $row1->periode;
                $_SESSION['exit_url']=$root_ser."/apisaku/cLogin.php?fx=logout";
                $_SESSION['form_login']="fLogin.php";
                $_SESSION['hakakses']="hakakses";
                $_SESSION['token']=$token;
                $_SESSION['kode_fs']=$row2[0]->kode_fs;

                if($row->path_view != null || $row->path_view != "-" ){
                    $dash=str_replace("_","/", $row->path_view);
                    $dash= explode("/",$dash);
                    $dash=$dash[2];
                }else{
                    $dash="";
                    
                }
                
                $_SESSION['form']=$row->form;
                $response['status'] = true;
                $response['message'] = "Login Sukses";
            }else{

                $response['status'] = false;
                $response['message'] = "Login Failed";
            }

        }else{
            $response['status'] = false;
            $response['message'] = $response["result"]->message.". Username/Password Salah ";
        }
        echo json_encode($response);
    }

    function logout(){

        $root_app="http://".$_SERVER['SERVER_NAME'];
        $header="Location: $root_app/apisaku";
                
        session_start();
        $_SESSION = [];
        unset($_SESSION);
        session_unset();
        session_destroy();
        
        header($header, true, 301);
        exit();   
    }

?>
