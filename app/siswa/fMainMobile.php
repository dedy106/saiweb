<?php
//   $root=$_SERVER['REQUEST_SCHEME']."://"..$_SERVER['SERVER_NAME'];
    session_start();
    $root2=realpath($_SERVER["DOCUMENT_ROOT"])."/";
    $root=$_SERVER['REQUEST_SCHEME']."://".$_SERVER['SERVER_NAME'];
    $folder_css=$root."/vendor/asset_elite/dist/css";
    $folder_js=$root."/vendor/asset_elite/dist/js";
    $folder_assets=$root."/vendor/asset_elite";
    $folder_upload=$root."/vendor/asset_elite/upload";
    $folderroot_css=$root."/assets/css";
    $folderroot_js=$root."/assets/js";
    $folderroot_img=$root."/assets/img";
    $folderroot_img=$root."/assets/img";
    $root_upload=$root."/upload";
    $nik=$_SESSION['userLog'];
    $root_ser=$root."/server/siswa";
    $root_app=$root."/siswa_mainmobile";
    $root_log=$root."/siswa_mobile";
    $root_print=$root."/siswa_preview";

    include_once($root2.'lib/helpers.php');
    include_once($root2.'lib/koneksi.php');
    // include_once($root2.'/web/setting.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="<?=$folder_assets?>/images/sai_icon/saku.png">
    <title>SAKU | Sistem Akuntansi Keuangan Digital</title>
    <!-- This page CSS -->
    <!-- Font Awesome CSS -->
    <link href="<?=$folder_assets?>/icons/font-awesome/css/fa-brands.css" rel="stylesheet">
    <link href="<?=$folder_assets?>/icons/font-awesome/css/fa-regular.css" rel="stylesheet">
    <link href="<?=$folder_assets?>/icons/font-awesome/css/fa-solid.css" rel="stylesheet">
    <link href="<?=$folder_assets?>/icons/font-awesome/css/fontawesome.css" rel="stylesheet">
    <link href="<?=$folder_assets?>/icons/font-awesome/css/fontawesome-all.css" rel="stylesheet">
    <link href="<?=$folder_assets?>/icons/font-awesome/webfonts/fa-solid-900.woff2" rel="stylesheet">
    <link href="<?=$folder_assets?>/icons/font-awesome/webfonts/fa-solid-900.ttf" rel="stylesheet">
    <link href="<?=$folder_assets?>/icons/font-awesome/webfonts/fa-solid-900.woff" rel="stylesheet">
    
    <!-- chartist CSS -->
    <link href="<?=$folder_assets?>/node_modules/morrisjs/morris.css" rel="stylesheet">
     
    <!--Toaster Popup message CSS -->
    <link href="<?=$folder_assets?>/node_modules/toast-master/css/jquery.toast.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?=$folder_css?>/style.min.css" rel="stylesheet">
    <!-- Dashboard 1 Page CSS -->
    <link href="<?=$folder_css?>/pages/dashboard1.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?=$folder_assets?>/node_modules/datatables.net-bs4/css/dataTables.bootstrap4.css">
    <!-- Select 2 -->
    <link href="<?=$folder_assets?>/node_modules/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
    <!-- SAI CSS -->
    <link href="<?=$folder_css?>/sai.css" rel="stylesheet">
    
    <!-- <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.min.css'> -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@8.17.6/dist/sweetalert2.min.css">
    
    <!-- Selectize -->
    <link href="<?=$folderroot_css?>/selectize.bootstrap3.css" rel="stylesheet">
    <!-- Datepicker -->
    <link rel="stylesheet" href="<?=$folderroot_css?>/bootstrap-datepicker.min.css">
    <!-- Tagify -->
    <link rel="stylesheet" href="<?=$folder_assets?>/tagify/dist/tagify.css">
    <script src="<?=$folderroot_js?>/highcharts2.js"></script>
    <!-- <script src="js/highcharts-3d.js"></script> -->
    <!-- <script src="https://code.highcharts.com/modules/bullet.js"></script> -->
    <script src="<?=$folderroot_js?>/highcharts-more.js"></script>
        
    <!-- All Jquery -->
    <!-- <script src="<?=$folder_assets?>/node_modules/jquery/jquery-3.2.1.min.js"></script> -->
    <!-- ============================================================== -->
    <!-- <script src="<?=$folder_assets?>/node_modules/jquery/jquery-3.2.1.min.js"></script> -->
    <script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
    <!-- Bootstrap popper Core JavaScript -->
    <script src="<?=$folder_assets?>/node_modules/popper/popper.min.js"></script>
    <script src="<?=$folder_assets?>/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="<?=$folder_assets?>/dist/js/perfect-scrollbar.jquery.min.js"></script>
    <!--Wave Effects -->
    <script src="<?=$folder_assets?>/dist/js/waves.js"></script>
    <!--stickey kit -->
    <script src="<?=$folder_assets?>/node_modules/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <script src="<?=$folder_assets?>/node_modules/sparkline/jquery.sparkline.min.js"></script>
    <!-- Select 2 -->
    <script src="<?=$folder_assets?>/node_modules/select2/dist/js/select2.full.min.js" type="text/javascript"></script>
    <!--Menu sidebar -->
    <script src="<?=$folder_assets?>/dist/js/sidebarmenu.js"></script>
    <!--Custom JavaScript -->
    <script src="<?=$folder_assets?>/dist/js/custom.min.js"></script>
    <!-- <script src="js/selectize.min.js"></script> -->
    <script src="<?=$folderroot_js?>/standalone/selectize.min.js"></script>
    <!-- ============================================================== -->
    <!-- This page plugins -->
    <!-- ============================================================== -->
    <!--morris JavaScript -->
    <script src="<?=$folder_assets?>/node_modules/raphael/raphael-min.js"></script>
    <script src="<?=$folder_assets?>/node_modules/morrisjs/morris.min.js"></script>
    <script src="<?=$folder_assets?>/node_modules/jquery-sparkline/jquery.sparkline.min.js"></script>
   
    <!-- Popup message jquery -->
    <script src="<?=$folder_assets?>/node_modules/toast-master/js/jquery.toast.js"></script>
    <!-- Datepicker -->
    <script src="<?=$folderroot_js?>/bootstrap-datepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8.17.6/dist/sweetalert2.all.min.js"></script>
    
    <!-- This is data table -->
    <script src="<?=$folder_assets?>/node_modules/datatables.net/js/jquery.dataTables.min.js"></script>
    <!-- start - This is for export functionality only -->
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>
   
    
<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify@2.24.0/dist/tagify.min.js"></script>

        <script>
            $(document).ready(function() {
                $('.selectize').selectize();
            });
           
        </script>
        
</head>

<body class="skin-default fixed-layout" style='background:white'>
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="loader">
            <div class="loader__figure"></div>
            <p class="loader__label">SAKU admin loading</p>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper" style='background:white'>

        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <header class="topbar">
        </header>
        <!-- ============================================================== -->
        <!-- End Topbar header -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
       
        <!-- ============================================================== -->
        <!-- End Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper" style="padding-top:0;background:white;margin-left: 0px;">
            
            <section class="content" id='ajax-content-section'>
                    <?php
                    
                    // echo $_GET['hal'];

                    if(ISSET($_GET['hal'])){
                        
                        include_once($_GET['hal']);

                    }
                    
                    ?>
            </section>
        </div>
        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- footer -->
        <!-- ============================================================== -->
       <?php
            include $folder_assets.'/dist/php/footer.php';
        ?>
        <!-- ============================================================== -->
        <!-- End footer -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    
</body>
<?php
	
    if(!$_COOKIE['LoggedIn']){
        if(ISSET($_COOKIE['user'])){
            $user=$_COOKIE['user'];
            echo"<script>alert('Session timeout !. Klik Ok to continue.'); 
            window.location='$root_ser/cLoginMobile.php?fx=autoLogin&nik=$user';
            </script>";
        }else{

            echo "<script>alert('Harap login terlebih dahulu !'); window.location='$root_log';</script>";
        }
    }
?>

</html>