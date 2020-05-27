<?php
    session_start();
    
    $root=$_SERVER["DOCUMENT_ROOT"];
    $root_app="http://".$_SERVER['SERVER_NAME'];
    $root_ser="http://".$_SERVER['SERVER_NAME']."/server/crm";
	$folder_css=$root2."/assets/css";
	$folder_js=$root2."/assets/js";
    $folder_img=$root2."/assets/img";
    $root_img="http://".$_SERVER['SERVER_NAME'];
	
	
	
    if(!$_SESSION['isLogedIn']){
        echo "<script>alert('Harap login terlebih dahulu !'); window.location='$root_app/crm';</script>";
    }
    include_once($root.'/lib/helpers.php');
    include_once($root.'/lib/koneksi.php');
    include_once($root.'/setting.php');
	

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo "SAI WEB";//$app_nama?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="_token" content="<?=$_SESSION['token']?>" />
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="<?=$folder_css?>/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?=$folder_css?>/font-awesome.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="<?=$folder_css?>/ionicons.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?=$folder_css?>/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
        folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="<?=$folder_css?>/skin-red.min.css">
    <!-- Date Picker -->
    <link rel="stylesheet" href="<?=$folder_css?>/bootstrap-datepicker.min.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="<?=$folder_css?>/daterangepicker.css">
    

    <link rel="stylesheet" href="<?=$folder_css?>/dataTables.bootstrap.min.css">

    <link rel="stylesheet" href="<?=$folder_css?>/bootstrap-toggle.min.css">
    
  
    <!-- SELECTIZE -->
    <link href="<?=$folder_css?>/selectize.bootstrap3.css" rel="stylesheet">
    
     <!--Jquery Treegrid -->
    <link href="<?=$folder_css?>/jquery.treegrid.css" rel="stylesheet">
    
    <!--SAI GLOBAL ADMIN CSS-->
    <link href="<?=$folder_css?>/sai.css" rel="stylesheet">

    <!-- Text editor -->
    <link rel="stylesheet" href="<?=$folder_css?>/bootstrap3-wysihtml5.min.css">



    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

    <link rel="apple-touch-icon" sizes="76x76" href="<?=$root_img?>/web/img/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?=$root_img?>/web/img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?=$root_img?>/web/img/favicon/favicon-16x16.png">
    <link rel="manifest" href="<?=$root_img?>/web/img/favicon/manifest.json">
    <link rel="mask-icon" href="<?=$root_img?>/web/img/favicon/safari-pinned-tab.svg" color="#5bbad5">

     <!-- jQuery 3 -->
     <script src="<?=$folder_js?>/jquery.min.js"></script>
   <!-- Bootstrap 3.3.7 -->
    <script src="<?=$folder_js?>/bootstrap.min.js"></script>
    <!-- Highcharts -->
    <script src="<?=$folder_js?>/highcharts2.js"></script>
    <!-- <script src="js/highcharts-3d.js"></script> -->
    <!-- <script src="https://code.highcharts.com/modules/bullet.js"></script> -->
    <script src="<?=$folder_js?>/highcharts-more.js"></script>
    <!-- daterangepicker -->
    <script src="<?=$folder_js?>/moment.min.js"></script>
    <script src="<?=$folder_js?>/daterangepicker.js"></script>
    <!-- datepicker -->
    <script src="<?=$folder_js?>/bootstrap-datepicker.min.js"></script>
   <!-- AdminLTE App -->
    <script src="<?=$folder_js?>/adminlte.min.js"></script>
    <!-- DataTables -->
    <script src="<?=$folder_js?>/jquery.dataTables.min.js"></script>
    <script src="<?=$folder_js?>/dataTables.bootstrap.min.js"></script>
    <!-- Currency InputMask -->
    <script src="<?=$folder_js?>/inputmask.js"></script>
    <script src="<?=$folder_js?>/bootstrap-toggle.min.js"></script>
    <!-- Text editor -->
    <script src="<?=$folder_js?>/bootstrap3-wysihtml5.all.min.js"></script>
    <!-- Selectize -->
    <!-- <script src="js/selectize.min.js"></script> -->
    <script src="<?=$folder_js?>/standalone/selectize.min.js"></script>
    <!-- PULL REFRESH-->
    <script src="<?=$folder_js?>/index.umd.min.js"></script>

    <!-- JS Tree -->
    <script src="<?=$folder_js?>/jquery.treegrid.js"></script>
    <script type="text/javascript">
    $(document).ready(function() {
        $('.tree').treegrid({
                    expanderExpandedClass: 'glyphicon glyphicon-minus',
                    expanderCollapsedClass: 'glyphicon glyphicon-plus'
                });
        $('.selectize').selectize();
    });
    </script>
    
    <meta name="theme-color" content="#ffffff">
</head>

<body class="skin-red fixed sidebar-mini sidebar-mini-expand-feature">
    <div class="wrapper">

        <header class="main-header" id='header'>
            <!-- Logo -->
            <a href="#" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b></b> SAI</span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><b></b> SAI</span>
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top" id='navbar_header'>
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>
            <?php
            
                $jumNot="select count(*) as jumlah from api_notif where kode_lokasi='99' and nik='dev' ";

                $rs2=execute($jumNot);
        
                $sqlNot="select top 5 * from api_notif where kode_lokasi='99' and nik='dev' order by tgl_notif desc ";
        
                $rs3=execute($sqlNot);
            ?>

            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                <!-- Messages: style can be found in dropdown.less-->
                <!-- Notifications: style can be found in dropdown.less -->
                <li class="dropdown messages-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-bell-o"></i>
                    <span class="label label-warning" id='ajax-notification-number'><?php echo $rs2->fields[0]; ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">You have <?php echo $rs2->fields[0]; ?> notifications</li>
                    <?php
                        while ($row = $rs3->FetchNextObject($toupper=false)) {
                    echo"
                        <li>
                            <ul class='menu' id='ajax-notification-section'>
                            <li>
                                <a href='#'>
                                <!-- <i class='fa fa-envelope-o text-aqua'></i> -->
                                <h4 style='margin-left:0px'> $row->title </h4>
                                <p style='margin-left:0px'>$row->pesan</p>
                                </a>
                            </li>
                            </ul>
                        </li>
                        ";
                    }
                    ?>
                        <li class="footer"><a href="fMain.php?hal=app/cms/vNotif.php">View all</a></li>
                    </ul>
                </li>
                <li><a href='#' id='btn-refresh' ><i class='fa fa-undo'></i></a>
			 	</li>
			 	<li>
			 		<a href='#' data-toggle='control-sidebar' id='open-sidebar' ><i class='fa fa-filter'> </i></a>
			 	</li>
                                    

                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">
                    <?php
                        $path_image = "http://".$_SERVER["SERVER_NAME"]."/server/media/";

                        if($_SESSION['foto'] != null AND $_SESSION['foto'] !="" AND $_SESSION['foto'] != "-"){
                            $img = $path_image.$_SESSION['foto'];
                        }else if($_SESSION['foto'] == ""){
                            $img = "$root_img/image/user2.png";
                        }else{
                            $img = "$root_img/image/user2.png";
                        }
                        
                    ?>
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <!-- <img src="<?php //echo $img; ?>" class="user-image foto-ui-ajax" alt="User Image"> -->
                    <i class='fa fa-user'></i>
                    <span class="hidden-xs"><?php echo $_SESSION['namaUser']; ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="<?php echo $img; ?>" class="img-circle foto-ui-ajax" alt="User Image">
                            <p>
                            <?php echo $_SESSION['namaUser']; ?>
                            <!--<small>Member since Nov. 2012</small>-->
                            </p>
                        </li>
                        <!-- Menu Body -->
                        <li class="user-body">
                            <!--<div class="row">
                                <div class="col-xs-4 text-center">
                                    <a href="#">Followers</a>
                                </div>
                                <div class="col-xs-4 text-center">
                                    <a href="#">Sales</a>
                                </div>
                                <div class="col-xs-4 text-center">
                                    <a href="#">Friends</a>
                                </div>
                            </div>-->
                            <!-- /.row -->
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                            <a href="#" class="btn btn-default btn-flat" id='ubpass'>Ubah Password</a>
                            </div>
                            <div class="pull-right">
                            <a href="<?php echo $_SESSION['exit_url']; ?>" class="btn btn-default btn-flat">Sign out</a>
                            </div>
                        </li>
                    </ul>
                </li>
                <!-- Control Sidebar Toggle Button -->
                <!-- <li>
                    <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                </li> -->
                
                </ul>
            </div>
            </nav>
        </header>
        <!-- Left side column. contains the logo and sidebar -->
        <aside class="main-sidebar">
            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">
                <ul class="sidebar-menu" data-widget="tree">
                    <?php
                    $kode_menu = $_SESSION['kodeMenu'];

                    $sql="select a.*,b.form  from menu a 
                    left join m_form b on a.kode_form=b.kode_form 
                    where a.kode_klp = '$kode_menu' and isnull(a.jenis_menu,'-') = '-' order by kode_klp, rowindex";
                    $rs=execute($sql);
                    //$daftar_menu = $rs->GetRowAssoc(); 

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
                        $this_link = "$root_app/crm_main/".$forms[2];

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
                                $icon="fa fa-caret-right";
                            }
                            
                        }else{
                            if($daftar_menu[$i]['icon'] != "" && $daftar_menu[$i]['icon'] != null){
                                $icon=$daftar_menu[$i]['icon'];
                            }else{
                                $icon="fa fa-edit";
                            }
                        }

                        if($this_lv == 0 AND $next_lv == 0){
                            echo " 
                            <li>
                                <a href='$this_link'>
                                    <i class='$icon'></i> <span>".$daftar_menu[$i]["nama"]."</span>
                                </a>
                            </li>";
                        }
                        else if($this_lv == 0 AND $next_lv > 0){
                            echo " 
                            <li class='treeview'>
                                <a href='$this_link'>
                                    <i class='$icon'></i> <span>".$daftar_menu[$i]["nama"]."</span>
                                    <span class='pull-right-container'>
                                        <i class='fa fa-angle-left pull-right'></i>
                                    </span>
                                </a>
                                <ul class='treeview-menu' id='sai_adminlte_menu_".$daftar_menu[$i]["kode_menu"]."'>";
                        }else if(($this_lv > $prev_lv OR $this_lv == $prev_lv OR $this_lv < $prev_lv) AND $this_lv < $next_lv){
                            echo " 
                            <li class='treeview'>
                                <a href='$this_link'>
                                    <i class='$icon'></i> <span>".$daftar_menu[$i]["nama"]."</span>
                                    <span class='pull-right-container'>
                                        <i class='fa fa-angle-left pull-right'></i>
                                    </span>
                                </a>
                                <ul class='treeview-menu' id='sai_adminlte_menu_".$daftar_menu[$i]["kode_menu"]."'>";
                        }else if(($this_lv > $prev_lv OR $this_lv == $prev_lv OR $this_lv < $prev_lv) AND $this_lv == $next_lv){
                            echo " 
                            <li>
                                <a href='$this_link'>
                                    <i class='$icon'></i> <span>".$daftar_menu[$i]["nama"]."</span>
                                </a>
                            </li>";
                           
                        }else if($this_lv > $prev_lv AND $this_lv > $next_lv){
                            echo " 
                            <li>
                                <a href='$this_link'>
                                    <i class='$icon'></i> <span>".$daftar_menu[$i]["nama"]."</span>
                                </a>
                            </li>";
                            for($i=0; $i<($this_lv - $next_lv); $i++){
                                echo "</ul></li>";
                            }
                        }else if(($this_lv == $prev_lv OR $this_lv < $prev_lv) AND $this_lv > $next_lv){
                            echo " 
                            <li>
                                <a href='$this_link'>
                                    <i class='$icon'></i> <span>".$daftar_menu[$i]["nama"]."</span>
                                </a>
                            </li>";
                    echo "</ul>
                        </li>";
                            // for($i=0; $i<($this_lv - $next_lv); $i++){
                            //     echo "</ul></li>";
                            // }
                        }
                    }
                ?>
                </ul>
            </section>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- <div class="row" style="padding-right:20px;">
                <section class="content-header">
                    <ol class="breadcrumb" style="float:left; position: relative; top: 0px; left: 10px; right:0px;">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                        <li class="active" id='content_sub_page'></li>
                    </ol>
                </section>
            </div> -->

            <!-- Main content -->
            

            <div id="loading-overlay" style="background: #e9e9e9; display: none; position: absolute; top: 0; right: 0; bottom: 0; left: 0; z-index:5;">
                <center>
                    <img src="" style='position:fixed; top: 50%; transform: translateY(-50%);'>
                </center>
            </div>
            <section class="content" id='ajax-content-section'>
                    <?php
                    
                    if(ISSET($_GET['hal'])){
                        
                        include_once($_GET['hal']); 

                    }
                    
                    ?>
            </section>
            <!-- /.content -->
        </div>
        <!-- FORM UBAH PASS -->
        <div class="modal fade" id="modalPass" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Ubah Password</h4>
                    </div>
                    
                    <form id="form-ubpass" method='POST'>
                        <div class="modal-body">
                            <div class='row'>
                                <div class='form-group'>
                                    <label class='control-label col-sm-3'>Password Lama</label>
                                    <div class='col-sm-9' style='margin-bottom:5px;'>
                                        <input type='password' name='password_lama' class='form-control' maxlength='10' placeholder='Masukkan Password Lama' required>
                                    </div>
                                </div>
                                <div class='form-group'>
                                    <label class='control-label col-sm-3'>Password Baru</label>
                                    <div class='col-sm-9' style='margin-bottom:5px;'>
                                        <input type='password' name='password_baru' class='form-control' maxlength='10' placeholder='Masukkan Password Lama' required>
                                    </div>
                                </div>
                                <div class='form-group'>
                                    <label class='control-label col-sm-3'>Ulangi Password</label>
                                    <div class='col-sm-9' style='margin-bottom:5px;'>
                                        <input type='password' name='password_repeat' class='form-control' maxlength='10' placeholder='Masukkan Password Lama' required>
                                    </div>
                                </div>
                                <div class='form-group'>
                                    <div class='col-sm-9' style='margin-bottom:5px;'>
                                        <input type='hidden' name='nik' class='form-control' value='<?php echo $nik; ?>'>
                                    </div>
                                </div>
                                <div class='form-group'>
                                    <div class='col-sm-9' style='margin-bottom:5px;'>
                                        <input type='hidden' name='kode_lokasi' class='form-control' value='<?php echo $kode_lokasi; ?>'>
                                    </div>
                                </div>
                                <div class='form-group'>
                                    <div class='col-sm-9' style='margin-bottom:5px;'>
                                        <input type='hidden' name='kode_pp' class='form-control' maxlength='10' value='<?php echo $kode_pp; ?>'>
                                    </div>
                                </div>
                                <div class='form-group'>
                                    <div class='col-sm-9' style='margin-bottom:5px;'>
                                        <input type='hidden' name='tbl' class='form-control' maxlength='10' value='<?php echo $_SESSION['hakakses']; ?>'>
                                    </div>
                                </div>
                            </div>
                            <div class='row'>
                                <div class='col-sm-12' style='margin-bottom:5px;'>
                                    <div id='validation-box2'></div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <a class="btn btn-default" data-dismiss="modal"> Tutup</a>
                            <button type="submit" class="btn btn-success">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /.content-wrapper -->
        <!--<footer class="main-footer">
            <strong>PT. Samudra Aplikasi Indonesia &copy; 2017 <a href="http://mysai.co.id/">MySAI</a>.</strong> <br>
            <div class="pull-right hidden-xs">
                <b>Version</b> 2.4.0
            </div>
            <strong>AdminLTE</strong> Template - Copyright &copy; 2014-2016 <a href="https://adminlte.io">Almsaeed Studio</a>. All rights
            reserved.
        </footer>-->

        <!-- Control Sidebar -->
        <!-- <aside class="control-sidebar control-sidebar-dark">
            <div class="tab-content">
                <div class="tab-pane active" id="control-sidebar-home-tab">
                    <select class='form-control input-sm' id='dash_dept' style="margin-bottom:5px;">
                        <option value=''>Pilih Lokasi</option>
                    </select>
                    <select class='form-control input-sm' id='dash_periode' style="margin-bottom:5px;">
                        <option value=''>Pilih Periode</option>
                    </select>
                    <a class="btn btn-sm btn-default pull-right" id='dash_refresh' style="cursor:pointer; max-height:30px;" data-toggle="control-sidebar"><i class="fa fa-refresh fa-1"></i> Refresh</a>
                </div>
            </div>
        </aside> -->
        <!-- /.control-sidebar -->
        <!-- Add the sidebar's background. This div must be placed
            immediately after the control sidebar -->
        <div class="control-sidebar-bg"></div>
    </div>
    <!-- ./wrapper -->

   

    
</body>
</html>
<script>
    $('#btn-refresh').click(function(){
	    location.reload();
    });

    $('#ubpass').click(function(){
	    $('#modalPass').modal('show');
    });

    function clearInput(){
        $("input:not([type='radio'],[type='checkbox'],[type='submit'])").val('');
        $('textarea').val('');
        $("select:not('.selectize')").val('');
        $('#validation-box2').text('');
    }
    
    $('#form-ubpass').submit(function(event){
        event.preventDefault();
        var formData = new FormData(this);
        
        $.ajax({
            url: 'include_lib.php?hal=server/cms/CMS.php&fx=ubahPassword',
            data: formData,
            type: "post",
            dataType: "json",
            contentType: false, 
            cache: false, 
            processData:false, 
            success: function (data) {
                alert(data.alert);

                if(data.status == 1){
                    $('#modalPass').modal('hide');
                    $('#validation-box2').html("");
                    clearInput();
                    // location.reload();
                }else if (data.status == 3){
                    var error_list = "<div class='alert alert-danger' style='padding:0px; padding-top:5px; padding-bottom:5px; margin:0px; color: #a94442; background-color: #f2dede; border-color: #ebccd1;'><ul>";
                    for(i = 0; i<data.error_input.length; i++){
                        error_list += '<li>'+data.error_input[i]+'</li>';
                    }
                    error_list += "</ul></div>";
                    status = false;
                    $('#modalPass').find('#validation-box2').html(error_list);
                }
            }
        });
    });

    PullToRefresh.init({
        // mainElement: '#ajax-content-section',
        // distIgnore: 50,
        onRefresh: function() { 
            location.reload();
        }
      });
    
</script>

