<?php
    $kode_lokasi=$_SESSION['lokasi'];
    $periode=$_SESSION['periode'];
    $kode_pp=$_SESSION['kodePP'];
    $nik=$_SESSION['userLog'];
    $sts_user=$_SESSION['userStatus'];

    if(isset($_GET['tab'])){
        $tab=$_GET['tab'];
    } else {
        $tab="1";
    }

    $column = array('Biodata','Kepegawaian','Kedinasan','Pendidikan','Keluarga','Pelatihan','Penghargaan','Sanksi');
    $hal = array('fDataPribadi','fKepegawaian','fKedinasan','fPendidikan','fKeluarga','fPelatihan','fPenghargaan','fSanksi');
    $action = array('Update','Update','Add','Add','Add','Add','Add','Add');

    
    $path = "http://".$_SERVER["SERVER_NAME"]."/";

?>
    <div class="container-fluid mt-3">
        <div class="row" id="saku-data-barang">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <ul class='nav nav-tabs' role="tablist">
                        <?php for ($i=0;$i<count($column);$i++) { 
                                    $id = $i+1;
                                    if($tab == $id){
                                        $class="class='nav-item' ";
                                        $class2="active";
                                        $open=$hal[$i];
                                        if($action[$i] == "Update"){
                                            $link = "<li class='nav-item'><a href='#' class='nav-link' id='a".$column[$i]."'><i class='fa fa-pencil-alt'></i> Ubah Data</a></li>";
                                        }else{
                                            $link = "<li class='nav-item'><a href='#' class='nav-link' id='a".$column[$i]."'><i class='fa fa-plus'></i> Tambah</a></li>";
                                        }
                                    }else{
                                        $class="class='nav-item' ";
                                        $class2="";
                                    }
                                    echo"
                                    <li id='$id' $class >
                                    <a class='nav-link $class2' href='$root_app/fBiodata/?tab=$id' role='tab' aria-selected='false'><span class='hidden-sm-up'><i class='ti-".$column[$i]."'></i></span><span class='hidden-xs-down'>".$column[$i]."</span></a> </li>";
                                    
                            }
                            
                            echo $link;
                        ?>
                        </ul>
                        <div class='tab-content'>
                            <div class='tab-pane active'>
                                <?php include($open.".php"); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


